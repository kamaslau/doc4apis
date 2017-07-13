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
			$required_params = array('biz_id', 'project_id', 'class_name', 'class_name_cn', 'code', 'table_name', 'id_name', 'api_url');
			foreach ($required_params as $param):
				${$param} = $this->input->post($param);
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;
			
			// 从API服务器获取相应列表信息
			$params = array(
				'class_name' => $class_name,
				'class_name_cn' => $class_name_cn,
				'table_name' => $table_name,
				'id_name' => $id_name,
				'skip_sign' => 'please',
			);
			$url = api_url($api_url);
			$result = $this->curl->go($url, $params, 'array');
			if ($result['status'] === 200):
				$info_to_parse = array('form_data', 'names_list', 'rules', 'params_request', 'params_respond', 'elements', 'create', 'edit', 'detail',);
				foreach ($info_to_parse as $info_item):
					$$info_item = $result['content'][$info_item];
				endforeach;
			else:
				echo 'API失败';
				exit();
			endif;

			// 获取模板文件并生成待生成API文件内容
			$api_file_content = file_get_contents($_SERVER['DOCUMENT_ROOT']. '/file_templates/Template_api.php');
			$api_file_content = str_replace('[[class_name]]', ucfirst( strtolower($class_name) ), $api_file_content); // 类名需首字母大写
			$api_file_content = str_replace('[[class_name_cn]]', $class_name_cn, $api_file_content);
			$api_file_content = str_replace('[[table_name]]', $table_name, $api_file_content);
			$api_file_content = str_replace('[[id_name]]', $id_name, $api_file_content);
			$api_file_content = str_replace('[[rules]]', trim($rules), $api_file_content);
			$api_file_content = str_replace('[[names_list]]', trim($names_list), $api_file_content);
			$api_file_content = str_replace('[[params_request]]', trim($params_request), $api_file_content);
			$api_file_content = str_replace('[[params_respond]]', trim($params_respond), $api_file_content);
			
			// 获取模板文件并生成待生成控制器文件内容
			$controller_file_content = file_get_contents($_SERVER['DOCUMENT_ROOT']. '/file_templates/Template_app.php');
			$controller_file_content = str_replace('[[class_name]]', ucfirst( strtolower($class_name) ), $controller_file_content); // 类名需首字母大写
			$controller_file_content = str_replace('[[class_name_cn]]', $class_name_cn, $controller_file_content);
			$controller_file_content = str_replace('[[table_name]]', $table_name, $controller_file_content);
			$controller_file_content = str_replace('[[id_name]]', $id_name, $controller_file_content);
			$controller_file_content = str_replace('[[rules]]', trim($rules), $controller_file_content);
			$controller_file_content = str_replace('[[names_list]]', trim($names_list), $controller_file_content);
			$controller_file_content = str_replace('[[params_request]]', trim($params_request), $controller_file_content);
			$controller_file_content = str_replace('[[params_respond]]', trim($params_respond), $controller_file_content);

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

				$api_file_content = str_replace('[[extra_functions]]', $extra_functions_text, $api_file_content);
				$controller_file_content = str_replace('[[extra_functions]]', $extra_functions_text, $controller_file_content);

			else:
				// 若没有需要特别生成的类方法，清除模板文件中的模板标记
				$api_file_content = str_replace('[[extra_functions]]', NULL, $api_file_content);
				$controller_file_content = str_replace('[[extra_functions]]', NULL, $controller_file_content);

			endif;

			// 赋值类属性，为后续生成文档做准备
			$this->class_name = $class_name;
			$this->class_name_cn = $class_name_cn;
			$this->params_request = $params_request;
			$this->params_respond = $params_respond;
			$this->elements = $elements;

			// 生成API文档
			$apis = array('计数', '列表', '详情', '创建', '修改', '单项修改', '批量操作'); // 需要生成文档的常规功能API
			if ( !empty($extra_functions) ) $apis = array_merge($apis, $extra_functions);  // 其它附加功能
			for ($i=0; $i<count($apis); $i++):
				// 页面文档必要字段
				$doc_content_api = array(
					'biz_id' => $biz_id,
					'name' => ucwords( $class_name_cn ). $apis[$i],
					'code' => strtoupper( $code ). $i,
					'url' => $class_name.'/',
					'sample_request' => $form_data,
					'status' => '0', // 默认为草稿状态
				);

				// 生成API文档
				//$this->doc_api_generate($doc_content_api, $apis, $i);
			endfor;

			// 生成页面文档
			$pages = array(
				'result' => '操作结果',
				'index' => '列表',
				'detail' => '详情',
				'create' => '创建',
				'edit' => '修改',
				'edit_certain' => '单项修改',
				'delete' => '删除',
				'restore' => '找回',
			); // 需生成文档的常规功能页面
			if ( !empty($extra_functions) ) $pages = array_merge($pages, $extra_functions);  // 其它附加功能
			$i = 0; // 页面序号
			foreach ($pages as $name => $title):
				// 为特殊功能做特别处理
				if ( is_numeric($name) ) $name = $title;

				// 页面文档必要字段
				$doc_content_page = array(
					'biz_id' => $biz_id,
					'project_id' => $project_id,
					'name' => ucwords( $class_name_cn ). $title,
					'code' => strtoupper( $code ).'P'. $i,
					'code_class' => $class_name,
					'code_function' => $name,
					'status' => '0', // 默认为草稿状态
				);
				$i++; // 更新序号

				// 生成页面文档
				//$this->doc_page_generate($doc_content_page, $pages, $title);
				// 对部分页面，生成视图文件
				$pages_to_generate = array('create', 'edit', 'detail',);
				if ( in_array($name, $pages_to_generate) ):
					$target_directory = 'views/'.$class_name.'/';
					$file_name = $name. '.php';
					$this->view_file_generate($target_directory, $file_name, $$name);
				endif;
			endforeach;

			// 待生成的API及控制器文件名
			$file_name = ucfirst($class_name). '.php';
			
			// 生成API文件
			$target_directory = 'api/';
			$this->api_file_generate($target_directory, $file_name, $api_file_content);
			
			// 生成控制器文件
			$target_directory = 'controllers/';
			$this->controller_file_generate($target_directory, $file_name, $controller_file_content);
		} // end class_generate

		// 生成API文档，不含需特别生成的类方法相关页面
		private function doc_api_generate($data_to_create, $apis, $i)
		{
			$general_return_names =
				'<tr><td>time_create</td><td>string</td><td>否</td><td>'.date('Y-m-d H:i:s').'</td><td>创建时间</td></tr>'. "\n".
				'<tr><td>time_delete</td><td>string</td><td>否</td><td>'.date('Y-m-d H:i:s').'</td><td>删除时间</td></tr>'. "\n".
				'<tr><td>time_edit</td><td>string</td><td>否</td><td>'.date('Y-m-d H:i:s').'</td><td>最后操作时间</td></tr>'. "\n".
				'<tr><td>creator_id</td><td>string</td><td>否</td><td>20</td><td>创建者用户ID</td></tr>'. "\n".
				'<tr><td>operator_id</td><td>sring</td><td>否</td><td>17</td><td>最后操作者用户ID</td></tr>'. "\n";
			
			switch ($apis[$i]):
				case '计数':
					$data_to_create['url'] .= 'count';
					$data_to_create['params_request'] = $general_return_names. $this->params_request;
					$data_to_create['params_respond'] = '<tr><td>count</td><td>int</td><td>是</td><td>1</td><td>符合筛选条件（若有）的商家数量</td></tr>';

					$extra_request = array('time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id');
					foreach ($extra_request as $extra) $data_to_create['sample_request'] .= $extra.":\n"; // 请求参数，后同
					break;

				case '列表':
					$data_to_create['url'] .= 'index';
					$data_to_create['params_request'] =
						'<tr><td>limit</td><td>int</td><td>否</td><td>10</td><td>需要获取多少行数据<br>默认获取所有数据</td></tr>'. "\n".
						'<tr><td>offset</td><td>int</td><td>否</td><td>20</td><td>需要跳过多少行数据<br>默认“0”</td></tr>'. "\n".
						$general_return_names. $this->params_request;
					$data_to_create['params_respond'] = $this->params_respond;

					$extra_request = array('limit', 'offset');
					foreach ($extra_request as $extra) $data_to_create['sample_request'] .= $extra.":\n";
					break;

				case '详情':
					$data_to_create['url'] .= 'detail';
					$data_to_create['params_request'] = '<tr><td>id</td><td>string</td><td>是</td><td>1</td><td>'.$this->class_name_cn.'ID</td></tr>';
					$data_to_create['params_respond'] = $this->params_respond;

					$data_to_create['sample_request'] .= "id:\n";
					break;

				case '创建':
					$data_to_create['url'] .= 'create';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>创建者用户ID</td></tr>'. "\n".
						$this->params_request;
					$data_to_create['params_respond'] =
						'<tr><td>id</td><td>string</td><td>1</td><td>创建的'.$this->class_name_cn.'ID</td></tr>'. "\n".
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';
					
					$data_to_create['sample_request'] .= "user_id:\n";
					break;

				case '修改':
					$data_to_create['url'] .= 'edit';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>修改者用户ID</td></tr>'. "\n".
						'<tr><td>id</td><td>string</td><td>是</td><td>21</td><td>待修改项ID</td></tr>'. "\n".
						$this->params_request;
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';
					
					$extra_request = array('user_id', 'id');
					foreach ($extra_request as $extra) $data_to_create['sample_request'] .= $extra.":\n";
					break;

				case '单项修改':
					$data_to_create['url'] .= 'edit_certain';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>操作者用户ID</td></tr>'. "\n".
						'<tr><td>id</td><td>string</td><td>是</td><td>1</td><td>待修改项ID</td></tr>'. "\n".
						'<tr><td>name</td><td>string</td><td>是</td><td>详见“返回示例”，下同</td><td>字段名</td></tr>'. "\n".
						'<tr><td>value</td><td>string</td><td>是</td><td></td><td>字段值</td></tr>'. "\n".
						'<tr><td colspan=5>字段值需符合相应格式</td></tr>';
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';

					// 重置请求示例
					$data_to_create['sample_request'] = '';
					$extra_request = array('user_id', 'id', 'name', 'value');
					foreach ($extra_request as $extra) $data_to_create['sample_request'] .= $extra.":\n";
					break;

				case '批量操作':
					$data_to_create['url'] .= 'edit_bulk';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>操作者用户ID</td></tr>'. "\n".
						'<tr><td>ids</td><td>string</td><td>是</td><td>1,2,3</td><td>待操作项ID们，多个ID间以一个半角逗号分隔</td></tr>'. "\n".
						'<tr><td>operation</td><td>string</td><td>是</td><td>delete</td><td>待执行操作<br>删除delete,找回restore</td></tr>'. "\n".
						'<tr><td>password</td><td>string</td><td>是</td><td>略</td><td>操作者用户密码</td></tr>';
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';

					// 重置请求示例
					$data_to_create['sample_request'] = '';
					$extra_request = array('user_id', 'ids', 'operation', 'password');
					foreach ($extra_request as $extra) $data_to_create['sample_request'] .= $extra.":\n";
					break;

				default:
					$data_to_create['url'] .= $apis[$i];

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

		// 生成页面文档，不含需特别生成的类方法相关页面
		private function doc_page_generate($data_to_create, $pages, $title)
		{
			switch ($title):
				case '操作结果':
					$data_to_create['description'] = '显示'.$this->class_name_cn.'的操作结果';
					$data_to_create['return_allowed'] = 0;
					$data_to_create['elements'] =
						'<tr><td>text_title</td><td>1</td><td>文本</td><td>“操作结果”</td></tr>'. "\n".
						'<tr><td>button_home</td><td>1</td><td>按钮</td><td>“首页”</td></tr>'. "\n".
						'<tr><td>button_mine</td><td>1</td><td>按钮</td><td>“'.$this->class_name_cn.'列表”</td></tr>';
					$data_to_create['onloads'] = '<li>获取传入的title，赋值到text_title进行显示</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_'.$this->class_name.'.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>转到'.$this->class_name_cn.'列表页</li>'. "\n".
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
					$data_to_create['description'] = '显示符合给定条件（若有）的'.$this->class_name_cn.'摘要/详细信息';
					$data_to_create['elements'] =
						'<tr><td>list</td><td>1</td><td>列表</td><td>信息列表</td></tr>'. "\n".
						'<tr><td>┗item</td><td>1</td><td>块级区域</td><td>单项信息</td></tr>'. "\n".
						$this->elements."\n".
						'<tr><td>button_create</td><td>1</td><td>按钮</td><td>“创建'.$this->class_name_cn.'”</td></tr>';
					$data_to_create['onloads'] =
						'<li>调用'. substr($data_to_create['code'], 0, -2). '1，若为空或失败则结束并提示</li>'. "\n".
						'<li>将返回值各项循环赋值为list中的item视图元素</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_create.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>转到'.$this->class_name_cn.'创建页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>item.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>传'.$this->class_name.'_id键值对到'.$this->class_name_cn.'详情页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					break;

				case '详情':
					$data_to_create['description'] = '显示单个'.$this->class_name_cn.'详细信息';
					$data_to_create['elements'] =
						'<tr><td>item</td><td>1</td><td>块级区域</td><td>信息</td></tr>'. "\n".
						$this->elements."\n".
						'<tr><td>button_edit</td><td>1</td><td>按钮</td><td>“编辑”</td></tr>';
					$data_to_create['onloads'] =
						'<li>调用'. substr($data_to_create['code'], 0, -2). '2，若为空或失败则结束并提示</li>'. "\n".
						'<li>将返回值赋值到相应视图元素</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>item.*.click（除button_edit外）</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>传id=相应'.$this->class_name.'_id、name=相应字段名、value=相应字段值到'.$this->class_name_cn.'单项修改页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_edit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>传'.$this->class_name.'_id键值对到'.$this->class_name_cn.'修改页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					break;

				case '创建':
					$data_to_create['description'] = '创建单个'.$this->class_name_cn;
					$data_to_create['elements'] =
						'<tr><td>form_create</td><td>1</td><td>表单</td><td>创建表单</td></tr>'. "\n".
						$this->elements. "\n".
						'<tr><td>┗button_sumbit</td><td>1</td><td>按钮</td><td>“确定”，默认未激活</td></tr>';
					$data_to_create['onloads'] =
						'获取本地user.user_id、user.biz_id值并赋值到相应字段';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>form_create.*.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>若该元素之前有其它字段，则对相应字段进行格式验证，若失败则结束并进行提示</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_sumbit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>调用'. substr($data_to_create['code'], 0, -2). '3，若失败则结束并进行提示</li>'. "\n".
						'		<li>传title="成功创建'.$this->class_name_cn.'"到'.$this->class_name_cn.'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '修改':
					$data_to_create['description'] = '修改'.$this->class_name_cn.'完整信息';
					$data_to_create['elements'] =
						'<tr><td>form_edit</td><td>1</td><td>表单</td><td>编辑表单</td></tr>'. "\n".
						$this->elements. "\n".
						'<tr><td>┗button_sumbit</td><td>1</td><td>按钮</td><td>“确定”，默认未激活</td></tr>';
					$data_to_create['onloads'] =
						'<li>调用'. substr($data_to_create['code'], 0, -2). '2，若为空或失败则结束并提示</li>'. "\n".
						'<li>将返回值赋值到相应视图元素</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>form_edit.*.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>若该元素之前有其它字段，则对相应字段进行格式验证，若失败则结束并进行提示</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_sumbit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>'. substr($data_to_create['code'], 0, -2). '4，若失败则结束并进行提示</li>'. "\n".
						'		<li>传title="成功修改'.$this->class_name_cn.'"到'.$this->class_name_cn.'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '单项修改':
					$data_to_create['description'] = '修改'.$this->class_name_cn.'单项信息';
					$data_to_create['elements'] =
						'<tr><td>form_edit_certain</td><td>1</td><td>表单</td><td>单项编辑表单</td></tr>'. "\n".
						'<tr><td>┣value_to_update</td><td>1</td><td>字段</td><td>待修改字段值及相应控件，例如文本输入框、单选组、多选组、图片选择器、文件选择器等</td></tr>'. "\n".
						'<tr><td>┗button_sumbit</td><td>1</td><td>按钮</td><td>“确定”，默认未激活</td></tr>';
					$data_to_create['onloads'] =
						'<li>获取传入的id、name、value值</li>'. "\n".
						'<li>赋值name为页面标题</li>'. "\n".
						'<li>调用'. substr($data_to_create['code'], 0, -2). '2，若为空或失败则结束并提示</li>'. "\n".
						'<li>查看是否存在与name值匹配的返回值（可为空），若不存在则结束并提示</li>'. "\n".
						'<li>将与name值匹配的值赋值到value_to_update作为初始字段值</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>value_to_update.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>监控该控件，若被输入值，且被输入后的值与初始值不同，则设置button_sumbit为激活状态</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_sumbit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>对value_to_update字段值进行格式验证，若失败则结束并进行提示</li>'. "\n".
						'		<li>调用'. substr($data_to_create['code'], 0, -2). '5，若失败则结束并进行提示</li>'. "\n".
						'		<li>传'.$this->class_name.'_id=当前id”到'.$this->class_name_cn.'详情页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '删除':
					$data_to_create['description'] = '删除单个/多个'.$this->class_name_cn;
					$data_to_create['elements'] =
						'<tr><td>table_items</td><td>1</td><td>表格</td><td>待操作项主要信息表</td></tr>'. "\n".
						$this->elements. "\n".
						'<tr><td>form_delete</td><td>1</td><td>表单</td><td>删除表单</td></tr>'. "\n".
						'<tr><td>┣warning</td><td>1</td><td>文本</td><td>“确定要删除上述'.$this->class_name_cn.'？”</td></tr>'. "\n".
						'<tr><td>┣password</td><td>1</td><td>字段</td><td>密码</td></tr>'. "\n".
						'<tr><td>┗button_sumbit</td><td>1</td><td>按钮</td><td>“确定”，默认未激活</td></tr>';
					$data_to_create['onloads'] =
						'<li>获取传入的ids值，若为空或失败则返回上一页面并提示</li>'. "\n".
						'<li>依次调用'. substr($data_to_create['code'], 0, -2). '2，将返回值赋值到相应视图元素，若为空或失败则结束并提示</li>'. "\n".
						'<li>将返回值赋值到相应视图元素</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_sumbit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>对password字段值进行格式验证，若失败则结束并进行提示</li>'. "\n".
						'		<li>调用'. substr($data_to_create['code'], 0, -2). '6，若失败则结束并进行提示</li>'. "\n".
						'		<li>传title="成功删除'.$this->class_name_cn.'"到'.$this->class_name_cn.'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '找回':
					$data_to_create['description'] = '找回单个/多个已删除'.$this->class_name_cn;
					$data_to_create['elements'] =
						'<tr><td>table_items</td><td>1</td><td>表格</td><td>待操作项主要信息表</td></tr>'. "\n".
						$this->elements. "\n".
						'<tr><td>form_restore</td><td>1</td><td>表单</td><td>找回表单</td></tr>'. "\n".
						'<tr><td>┣warning</td><td>1</td><td>文本</td><td>“确定要找回上述'.$this->class_name_cn.'？”</td></tr>'. "\n".
						'<tr><td>┣password</td><td>1</td><td>字段</td><td>密码</td></tr>'. "\n".
						'<tr><td>┗button_sumbit</td><td>1</td><td>按钮</td><td>“确定”，默认未激活</td></tr>';
					$data_to_create['onloads'] =
						'<li>获取传入的ids值，若为空或失败则返回上一页面并提示</li>'. "\n".
						'<li>依次调用'. substr($data_to_create['code'], 0, -2). '2，将返回值赋值到相应视图元素，若为空或失败则结束并提示</li>'. "\n".
						'<li>将返回值赋值到相应视图元素</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_sumbit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>对password字段值进行格式验证，若失败则结束并进行提示</li>'. "\n".
						'		<li>调用'. substr($data_to_create['code'], 0, -2). '6，若失败则结束并进行提示</li>'. "\n".
						'		<li>传title="成功找回'.$this->class_name_cn.'"到'.$this->class_name_cn.'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				default:
					$data_to_create['elements'] = $this->elements;
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
		 * 生成API文件
		 */
		private function api_file_generate($target_directory, $file_name, $file_content)
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
				$this->result['content']['api']['file_name'] = $target_url;
				$this->result['content']['file_size'] = round($result / 1024, 2). ' kb';
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = ucfirst( strtolower($class_name) ). '类API文件创建失败';
			endif;
		} // end api_file_generate
		
		/**
		 * 生成控制器文件
		 */
		private function controller_file_generate($target_directory, $file_name, $file_content)
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
				$this->result['content']['controllers']['file_name'] = $target_url;
				$this->result['content']['file_size'] = round($result / 1024, 2). ' kb';
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = ucfirst( strtolower($class_name) ). '类控制器文件创建失败';
			endif;
		} // end controller_file_generate

		/**
		 * 生成视图文件
		 */
		private function view_file_generate($target_directory, $file_name, $content_to_insert)
		{
			// 获取模板文件并生成待生成API文件内容
			$file_content = file_get_contents($_SERVER['DOCUMENT_ROOT']. '/file_templates/template_view/'. $file_name);
			$file_content = str_replace('[[content]]', $content_to_insert, $file_content);

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
				$this->result['content']['views']['file_name'][] = $target_url;
				$this->result['content']['file_size'] = round($result / 1024, 2). ' kb';
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = '视图文件创建失败';
			endif;
		} // end view_file_generate

	}

/* End of file Tool.php */
/* Location: ./application/controllers/Tool.php */
