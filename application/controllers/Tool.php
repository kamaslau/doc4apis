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
		 * @params $code 序号
		 * @params $table_name 主要相关表名
		 * @params $id_name 主要相关表的主键名
		 * @params $names_csv 可公开显示的字段CSV格式，将以之生成的各字段名类类属性，除$names_to_return类属性外需自行删减字段
		 * @params $extra_functions 需要特别生成的类方法，及模板类中未预设的方法
		 */
		public function class_generate()
		{
			// 检查必要参数是否已传入
			$required_params = array('biz_id', 'project_id', 'class_name', 'class_name_cn', 'code', 'table_name', 'id_name', 'names_csv');
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
			$form_data = ''; // 用于接口测试的key-value值，可用于Postman等工具
			$names_list = ''; // 字段列表
			$rules = "\n"; // 验证规则
			$params_request = ''; // 请求参数（生成文档用）
			$params_respond = '<tr><td>'.$id_name.'</td><td>string</td><td>详见“返回示例”，下同</td><td>'.$class_name_cn.'ID</td></tr>'. "\n"; // 响应参数（生成文档用）
			$elements = ''; // 主要视图元素（生成文档用）
			foreach ($names as $name):
				$form_data .= $name. ':'. "\n";
				$names_list .= "'$name', ";
				$rules .= "\t\t\t". '$this->form_validation->set_rules('. "'$name', '名称', 'trim|required');". "\n";
				$params_request .= '<tr><td>'. $name. '</td><td>string</td><td>否</td><td>示例</td><td>说明</td></tr>'. "\n";
				$params_respond .= '<tr><td>'. $name. '</td><td>string</td><td>详见返回示例</td><td>说明</td></tr>'. "\n";
				$elements .= '<tr><td>┣'. $name. '</td><td>1</td><td>文本</td><td>说明</td></tr>'. "\n";
			endforeach;

			// 获取模板文件
			$file_content = file_get_contents($_SERVER['DOCUMENT_ROOT']. '/file_templates/Template_api.php');
			$file_content = str_replace('[[class_name]]', ucfirst( strtolower($class_name) ), $file_content); // 类名需首字母大写
			$file_content = str_replace('[[class_name_cn]]', $class_name_cn, $file_content);
			$file_content = str_replace('[[table_name]]', $table_name, $file_content);
			$file_content = str_replace('[[id_name]]', $id_name, $file_content);
			$file_content = str_replace('[[rules]]', trim($rules), $file_content);
			$file_content = str_replace('[[names_list]]', trim($names_list), $file_content);
			$file_content = str_replace('[[params_request]]', trim($params_request), $file_content);
			$file_content = str_replace('[[params_respond]]', trim($params_respond), $file_content);

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
				// 若没有需要特别生成的类方法，清除模板文件中的模板标记
				$file_content = str_replace('[[extra_functions]]', NULL, $file_content);

			endif;

			// 赋值类属性，为后续生成文档做准备
			$this->class_name_cn = $class_name_cn;
			$this->names = $names;
			$this->params_request = $params_request;
			$this->params_respond = $params_respond;

			// // 生成API文档
			// $functions = array('计数', '列表', '详情', '创建', '修改', '单项修改', '批量操作'); // 需要生成文档的功能
			// if ( !empty($extra_functions) ) $functions = array_merge($functions, $extra_functions);  // 其它附加功能
			// for ($i=0; $i<count($functions); $i++):
			// 	// 页面文档必要字段
			// 	$doc_content_api = array(
			// 		'biz_id' => $biz_id,
			// 		'name' => ucwords( $class_name_cn ). $functions[$i],
			// 		'code' => strtoupper( $code ). $i,
			// 		'url' => $class_name.'/',
			// 		'sample_respond' => $form_data,
			// 		'status' => '0', // 默认为草稿状态
			// 	);
			//
			// 	// 生成API文档
			// 	$this->doc_api_generate($doc_content_api, $functions, $i);
			// endfor;

			// 生成页面文档
			$functions = array('操作结果', '列表', '详情', '创建', '修改', '删除', '找回'); // 需生成文档的常规功能
			if ( !empty($extra_functions) ) $functions = array_merge($functions, $extra_functions); // 其它附加功能
			for ($i=0; $i<count($functions); $i++):
				// 页面文档必要字段
				$doc_content_page = array(
					'biz_id' => $biz_id,
					'project_id' => $project_id,
					'name' => ucwords( $class_name_cn ). $functions[$i],
					'code' => strtoupper( $code ).'P'. $i,
					'code_class' => $class_name,
					'code_function' => NULL,
					'status' => '0', // 默认为草稿状态
				);

				// 生成页面文档
				$this->doc_page_generate($doc_content_page, $functions, $i);
			endfor;

			// 生成文件
			$target_directory = 'api/';
			$file_name = ucfirst($class_name). '.php';
			$this->file_generate($target_directory, $file_name, $file_content);
		} // end class_generate

		// 生成API文档，不含需特别生成的类方法相关页面
		private function doc_api_generate($data_to_create, $functions, $i)
		{
			$general_return_names =
				'<tr><td>time_create</td><td>string</td><td>否</td><td>'.date('Y-m-d H:i:s').'</td><td>创建时间</td></tr>'. "\n".
				'<tr><td>time_delete</td><td>string</td><td>否</td><td>'.date('Y-m-d H:i:s').'</td><td>删除时间</td></tr>'. "\n".
				'<tr><td>time_edit</td><td>string</td><td>否</td><td>'.date('Y-m-d H:i:s').'</td><td>最后操作时间</td></tr>'. "\n".
				'<tr><td>creator_id</td><td>string</td><td>否</td><td>20</td><td>创建者用户ID</td></tr>'. "\n".
				'<tr><td>operator_id</td><td>sring</td><td>否</td><td>17</td><td>最后操作者用户ID</td></tr>'. "\n";
			
			switch ($functions[$i]):
				case '计数':
					$data_to_create['url'] .= 'count';
					$data_to_create['params_request'] = $general_return_names. $this->params_request;
					$data_to_create['params_respond'] = '<tr><td>count</td><td>int</td><td>是</td><td>1</td><td>符合筛选条件（若有）的商家数量</td></tr>';

					$extra_request = array('time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id');
					foreach ($extra_request as $extra) $data_to_create['sample_respond'] .= $extra.":\n";
					break;

				case '列表':
					$data_to_create['url'] .= 'index';
					$data_to_create['params_request'] =
						'<tr><td>limit</td><td>int</td><td>否</td><td>10</td><td>需要获取多少行数据<br>默认获取所有数据</td></tr>'. "\n".
						'<tr><td>offset</td><td>int</td><td>否</td><td>20</td><td>需要跳过多少行数据<br>默认“0”</td></tr>'. "\n".
						$general_return_names. $this->params_request;
					$data_to_create['params_respond'] = $this->params_respond;

					$extra_request = array('limit', 'offset');
					foreach ($extra_request as $extra) $data_to_create['sample_respond'] .= $extra.":\n";
					break;

				case '详情':
					$data_to_create['url'] .= 'detail';
					$data_to_create['params_request'] = '<tr><td>id</td><td>string</td><td>是</td><td>1</td><td>'.$this->class_name_cn.'ID</td></tr>';
					$data_to_create['params_respond'] = $this->params_respond;

					$data_to_create['sample_respond'] .= "id:\n";
					break;

				case '创建':
					$data_to_create['url'] .= 'create';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>修改者用户ID</td></tr>'. "\n".
						$this->params_request;
					$data_to_create['params_respond'] =
						'<tr><td>id</td><td>string</td><td>1</td><td>创建的'.$this->class_name_cn.'ID</td></tr>'. "\n".
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';
					
					$data_to_create['sample_respond'] .= "user_id:\n";
					break;

				case '修改':
					$data_to_create['url'] .= 'edit';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>修改者用户ID</td></tr>'. "\n".
						'<tr><td>id</td><td>string</td><td>是</td><td>21</td><td>待修改'.$this->class_name_cn.'ID</td></tr>'. "\n".
						$this->params_request;
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';
					
					$extra_request = array('user_id', 'id');
					foreach ($extra_request as $extra) $data_to_create['sample_respond'] .= $extra.":\n";
					break;

				case '单项修改':
					$data_to_create['url'] .= 'edit_certain';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>修改者用户ID</td></tr>'. "\n".
						'<tr><td>id</td><td>string</td><td>是</td><td>1</td><td>待修改'.$this->class_name_cn.'ID</td></tr>'. "\n".
						'<tr><td>name</td><td>string</td><td>是</td><td>'.$this->names[0].'</td><td>字段名</td></tr>'. "\n".
						'<tr><td>value</td><td>string</td><td>是</td><td>详见“返回示例”</td><td>字段值</td></tr>'. "\n".
						'<tr><td colspan=5>字段值需符合相应格式</td></tr>';
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';

					$extra_request = array('user_id', 'id', 'name', 'value');
					foreach ($extra_request as $extra) $data_to_create['sample_respond'] .= $extra.":\n";
					break;

				case '批量操作':
					$data_to_create['url'] .= 'edit_bulk';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>修改者用户ID</td></tr>'. "\n".
						'<tr><td>ids</td><td>string</td><td>是</td><td>1,2,3</td><td>待修改'.$this->class_name_cn.'ID们，多个ID间以一个半角逗号分隔</td></tr>'. "\n".
						'<tr><td>operation</td><td>string</td><td>是</td><td>delete</td><td>待执行操作<br>删除delete,找回restore</td></tr>'. "\n".
						'<tr><td>password</td><td>string</td><td>是</td><td>略</td><td>修改者用户密码</td></tr>';
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';

					$extra_request = array('user_id', 'ids', 'operation', 'password');
					foreach ($extra_request as $extra) $data_to_create['sample_respond'] .= $extra.":\n";
					break;

				default:
					$data_to_create['url'] .= $functions[$i];

			endswitch;
			
			// 向数据库添加数据
			$this->basic_model->table_name = 'api';
			$this->basic_model->id_name = 'api_id';

			$result = $this->basic_model->create($data_to_create);
			if ($result !== FALSE):
				$data['content']['doc'] = 'API文档创建成功';
			else:
				$data['content']['error']['message'] = 'API文档创建失败';
			endif;
		} // end doc_api_generate

		// TODO 生成页面文档，不含需特别生成的类方法相关页面
		private function doc_page_generate($data_to_create, $functions, $i)
		{
			switch ($functions[$i]):
				case '操作结果':
					$data_to_create['return_allowed'] = 0;
					$data_to_create['code_function'] = 'result';
					$data_to_create['elements'] =
						'<tr><td>text_title</td><td>1</td><td>文本</td><td>“操作结果”</td></tr>'. "\n".
						'<tr><td>button_home</td><td>1</td><td>按钮</td><td>“首页”</td></tr>'. "\n".
						'<tr><td>button_mine</td><td>1</td><td>按钮</td><td>“个人中心”</td></tr>';
					$data_to_create['onloads'] = '<li>获取传入的title，赋值到text_title进行显示</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_mine.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>转到我的页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_home.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>转到首页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					break;

				case '列表':
					$data_to_create['code_function'] = 'index';
					$data_to_create['elements'] = $this->params_respond;
					break;

				case '详情':
					$data_to_create['code_function'] = 'detail';
					$data_to_create['elements'] = $this->params_respond;
					break;

				case '创建':
					$data_to_create['code_function'] = 'create';
					$data_to_create['elements'] = $this->params_respond;
					break;

				case '修改':
					$data_to_create['code_function'] = 'edit';
					$data_to_create['elements'] = $this->params_respond;
					break;

				case '删除':
					$data_to_create['code_function'] = 'delete';
					$data_to_create['elements'] = $this->params_respond;
					break;

				case '找回':
					$data_to_create['code_function'] = 'restore';
					$data_to_create['elements'] = $this->params_respond;
					break;

			endswitch;

			// 向数据库添加数据
			$this->basic_model->table_name = 'page';
			$this->basic_model->id_name = 'page_id';

			$result = $this->basic_model->create($data_to_create);
			if ($result !== FALSE):
				$data['content']['doc'] = '页面文档创建成功';
			else:
				$data['content']['error']['message'] = '页面文档创建失败';
			endif;
		} // end doc_page_generate
		
		/**
		 * 生成文件
		 */
		private function file_generate($target_directory, $file_name, $file_content)
		{
			// 生成完整的文件所在目录
			$target_directory = 'generated/'. $target_directory;

			// 检查目标路径是否存在
			if ( ! file_exists($target_directory) )
				mkdir($target_directory, 0777, TRUE); // 若不存在则新建，且允许新建多级子目录

			// 设置目标路径（含文件名）
			chmod($target_directory, 0777); // 设置权限为可写
			$target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $target_directory. $file_name;

			// 创建新文件并写入内容
			$result = file_put_contents($target_url, $file_content);
			if ( $result !== FALSE ):
				$this->result['status'] = 200;
				$this->result['content']['file_name'] = $target_url;
				$this->result['content']['file_size'] = round($result / 1024, 2). ' kb';
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = ucfirst( strtolower($class_name) ). '类文件创建失败';
			endif;
		} // file_generate

	}

/* End of file Tool.php */
/* Location: ./application/controllers/Tool.php */
