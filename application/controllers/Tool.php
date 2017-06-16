<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Tool 类
	 *
	 * 以API服务形式返回数据列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的示例代码
	 * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Tool extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();

			// 统计业务逻辑运行时间起点
			$this->benchmark->mark('start');
		}

		/**
		 * 析构时将待输出的内容以json格式返回
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			// 将请求参数一并返回以便调试
			$this->result['param']['get'] = $this->input->get();
			$this->result['param']['post'] = $this->input->post();

			// 返回服务器端时间信息
			$this->result['timestamp'] = time();
			$this->result['datetime'] = date('Y-m-d H:i:s');
			$this->result['timezone'] = date_default_timezone_get();

			// 统计业务逻辑运行时间终点
			$this->benchmark->mark('end');
			// 计算并输出业务逻辑运行时间（秒）
			$this->result['elapsed_time'] = $this->benchmark->elapsed_time('start', 'end');

			header("Content-type:application/json;charset=utf-8");
			$output_json = json_encode($this->result);
			echo $output_json;
		}

		// 签名生成工具
		public function sign_generate()
		{
			// 设置需要参与签名的必要参数；由于本方法为测试用途，故timestamp字段可以不传入，由服务器端生成
			$params_required = array(
				'app_type',
				'app_version',
				'device_platform',
				'device_number',
				'timestamp',
				'random',
			);

			// 获取传入的参数们
			$params = $_POST;
			// 为便于测试，时间戳可由服务器生成
			if ( empty($params['timestamp']) ):
				$timestamp = time();
				$params['timestamp'] = &$timestamp;
			endif;

			// 检查必要参数是否已传入
			if ( array_intersect_key($params_required, array_keys($params)) !== $params_required ):
				$this->result['status'] = 400;
				$this->result['content']['error_code'] = '必要参数未全部传入';

			// 检查来自移动客户端的请求中，必要参数是否存在空值
			//elseif:

			else:
				// 对参与签名的参数进行排序
				ksort($params);

				// 对随机字符串进行SHA1计算
				$params['random'] = SHA1( $params['random'] );

				// 拼接字符串
				$param_string = '';
				foreach ($params as $key => $value)
					$param_string .= '&'. $key.'='.$value;
				$param_string .= '&key='. API_TOKEN;

				// 计算字符串SHA1值并转为大写
				$sign = strtoupper( SHA1($param_string) );

				// 输出生成的签名及相关测试内容
				$this->result['status'] = 200;
				$this->result['content']['params_input'] = &$_POST;
				if ( empty($_POST['timestamp']) ):
					$this->result['content']['params_added']['timestamp'] = &$timestamp;
				endif;
				$this->result['content']['params_added']['key'] = '安全起见不显示key值';
				$this->result['content']['sign_string'] = substr($param_string,0,-25).'安全起见不显示key值';
				$this->result['content']['sign'] = $sign;

			endif; //end 检查必要参数是否已传入
		} // end sign_generate

		/**
		 * 类文件生成
		 *
		 * @params $class_name 类名，用于新类文件名及类名
		 * @params $class_name_cn 类名中文名称
		 * @params $table_name 主要相关表名
		 * @params $id_name 主要相关表的主键名
		 * @params $names_csv 可公开显示的字段CSV格式，将以之生成的各字段名类类属性，除$names_to_return类属性外需自行删减字段
		 * @params $extra_functions 需要特别生成的类方法，及模板类中未预设的方法
		 */
		public function class_generate()
		{
			// 检查必要参数是否已传入
			$required_params = array('class_name', 'class_name_cn', 'table_name', 'id_name', 'names_csv');
			foreach ($required_params as $param):
				${$param} = $this->input->post($param);
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

			// 根据字段名生成相关类属性及验证规则
			$names = explode(',', $names_csv);
			$names_list = '';
			$rules = "\n";
			foreach ($names as $name):
				$names_list .= "'$name', ";
				$rules .= "\t\t\t". '$this->form_validation->set_rules('. "'$name', '名称', 'trim|required');". "\n";
			endforeach;

			// 获取模板文件
			$file_content = file_get_contents($_SERVER['DOCUMENT_ROOT']. '/file_templates/Template_api.php');
			$file_content = str_replace('[[class_name]]', ucfirst( strtolower($class_name) ), $file_content); // 类名需首字母大写
			$file_content = str_replace('[[class_name_cn]]', $class_name_cn, $file_content);
			$file_content = str_replace('[[table_name]]', $table_name, $file_content);
			$file_content = str_replace('[[id_name]]', $id_name, $file_content);
			$file_content = str_replace('[[rules]]', $rules, $file_content);
			$file_content = str_replace('[[names_list]]', $names_list, $file_content);

			// 若有需要特别生成的类方法，进行生成
			$extra_functions = $this->input->post('extra_functions');
			if ( !empty($extra_functions) ):
				$extra_functions = explode(',', $extra_functions);
				$extra_functions_text = '';
				foreach ($extra_functions as $function_name):
					$extra_functions_text .=
					"\n\n".
					"\t\t". '// 类方法'. "\n".
					"\t\t". 'public function '. $function_name. '()'. "\n".
					"\t\t". '{'.
					"\n\n".
					"\t\t". '} // end '. $function_name;
				endforeach;

				$file_content = str_replace('[[extra_functions]]', $extra_functions_text, $file_content);
				
			else:
				$file_content = str_replace('[[extra_functions]]', NULL, $file_content);
				
			endif;

			// 生成文件
			$target_directory = 'generated/';
			$file_name = ucfirst($class_name). '.php';
			$this->file_generate($target_directory, $file_name, $file_content);
		} // end class_generate
		
		/**
		 * 生成文件
		 *
		 * @params 
		 */
		private function file_generate($target_directory, $file_name, $file_content)
		{
			// 检查目标路径是否存在
			if ( ! file_exists($target_directory) )
				mkdir($target_directory, 0777, TRUE); // 若不存在则新建，且允许新建多级子目录

			// 设置目标路径
			chmod($target_directory, 0777); // 设置权限为可写
			$target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $target_directory. $file_name;
			
			// 创建新文件并写入内容
			$result = file_put_contents($target_url, $file_content);
			if ( $result !== FALSE )
			{
				$this->result['status'] = 200;
				$this->result['content']['file_name'] = $target_url;
				$this->result['content']['file_size'] = $result;
			}
			else
			{
				$this->result['status'] = 400;
				$this->result['error']['message'] = '文件创建失败';
			}
		}

	}

/* End of file Tool.php */
/* Location: ./application/controllers/Tool.php */
