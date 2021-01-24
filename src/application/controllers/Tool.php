<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Tool类
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@dingtalk.com>
	 * @copyright Kamas 'Iceberg' Lau <kamaslau@dingtalk.com>
	 */
	class Tool extends CI_Controller
	{
        /**
         * 模板文件根URL
         *
         * @var string
         */
	    protected $template_url;

        /**
         * 视图模板文件根URL
         *
         * @var string
         */
	    protected $view_template_root;

        /**
         * 视图文件模式
         *
         * @var string vue|php
         */
	    protected $view_mode = 'vue';

        /**
         * 数据表信息
         *
         * @var array
         */
	    protected $info = array(
            'names_csv' => '', // 所有表字段；CSV
            'names_list' => '', // 所有表字段；数组
            'form_data' => '', // 用于接口测试的key-value值，可用于Postman等工具
            'rules' => '', // 验证规则
            'params_request' => '', // 请求参数（生成文档用）
            'params_respond' => '', // 响应参数（生成文档用）
            'elements' => '', // 主要视图元素（生成文档用）

            // Vue.js视图文件
            'form' => '', // 创建/编辑（SPA）的表单组件内容
            'brief' => '', // 详情页组件内容
            // PHP视图文件
            'create' => '', // 创建页
            'edit' => '', // 编辑页
            'detail' => '', // 详情页
        );

		public function __construct()
		{
			parent::__construct();

			// 统计业务逻辑运行时间起点
			$this->benchmark->mark('start');

            // 检查是否已打开测试模式，
            if ($this->input->post('test_mode') === 'on'):
                $this->output->enable_profiler(TRUE); // 输出调试信息

                $this->result['user_agent'] = $_SERVER['HTTP_USER_AGENT']; // 获取当前设备信息
            endif;
		} // end __construct

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
		} // end __destruct

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
			$required_params = array('biz_id', 'project_id', 'code', 'class_name_cn', 'api_url');
			foreach ($required_params as $param):
				${$param} = trim($this->input->post($param));
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

            // 生成非必要参数值
            $this->info['class_name_cn'] = $class_name_cn; unset($class_name_cn); // 回收内存
            $this->info['code'] = strtoupper($code);
            $class_name = empty($this->input->post('class_name'))? $code: $this->input->post('class_name');  unset($code); // 回收内存
            $this->info['class_name'] = ucfirst(strtolower( $class_name ));
            $this->info['table_name'] = empty($this->input->post('table_name'))? $class_name: $this->input->post('table_name');
            $this->info['id_name'] = empty($this->input->post('id_name'))? $class_name.'_id': $this->input->post('id_name');

            /**
             * 获取表字段信息，并解析到类属性
             */
			$this->grab_table(
			    api_url($api_url),
                array(
                    // 'skip_sign' => 'please', // 默认添加用于测试环境的跳过签名检查的设置参数，若在环境变量中已设置，则可注释掉这行代码
                    'table_name' => $this->info['table_name'],
                    'id_name' => $this->info['id_name'],
                    'class_name' => $this->info['class_name'],
                    'class_name_cn' => $this->info['class_name_cn'],
                )
            );
			unset($api_url); // 回收内存

            /**
             * 获取待执行操作
             */
            $doc_api = $this->input->post('doc_api') === 'yes'; // 生成API文档？
            $doc_page = $this->input->post('doc_page') === 'yes'; // 生成页面文档？
            $file_api = $this->input->post('file_api') === 'yes'; // 生成API控制器文件？
            $file_app = $this->input->post('file_app') === 'yes'; // 生成应用控制器文件？
            $file_view = $this->input->post('file_view') === 'yes'; // 生成应用视图文件？
            $allow_edit_certain = $this->input->post('allow_edit_certain') === 'yes'; // 包含"单项修改"方法？
            $extra_functions = $this->input->post('extra_functions'); // 非标准功能
            // 若需要生成文件，配置相关路径
            if ($file_view || $file_app || $file_api):
                // 赋值模板文件路径
                $this->template_url = $_SERVER['DOCUMENT_ROOT']. '/file_templates/';

                // 根据视图文件模式，生成视图文件根路径
                if ($file_view)
                    $this->view_template_root = $this->template_url. 'view/'. $this->view_mode. '/';
            endif;

            /**
             * 生成API或页面文档
             */
			// 生成API文档
			$apis = array('计数', '列表', '详情', '创建', '修改', '单项修改', '批量操作'); // 需要生成文档的常规功能API
            if ( ! $allow_edit_certain) array_splice($apis,5,1); // 若不允许修改单项，则不生成相应文档
			if ( ! empty($extra_functions)) $apis = array_merge($apis, $extra_functions);  // 其它附加功能
			for ($i=0, $j=count($apis); $i<$j; $i++):
				// 页面文档必要字段
				$doc_content_api = array(
					'biz_id' => $biz_id,
                    'project_id' => $project_id,
					'name' => $this->info['class_name_cn']. $apis[$i],
					'code' => $this->info['code']. $i,
					'url' => strtolower( $this->info['class_name'] ).'/',
					'sample_request' => $this->info['form_data'],
					'status' => '0', // 默认为草稿状态
				);

				// 生成API文档
				if ($doc_api) $this->doc_api_generate($doc_content_api, $apis, $i);
				unset($doc_content_api);
			endfor;

			// 生成页面文档及视图文件
			$pages = array(
				'result' => '操作结果',
				'index' => '列表',
				'detail' => '详情',
				'create' => '创建',
				'edit' => '修改',
				'edit_certain' => '单项修改',
				'delete' => '删除',
				'restore' => '找回',
                'form' => '',
                'brief' => '',
			);
            // 需生成文档的页面
            $pages_to_doc = array(
                'result', 'index', 'detail', 'create', 'edit', 'edit_certain', 'delete', 'restore',
            );
            // 需生成视图文件的页面
            $pages_to_generate = array(
                'create', 'detail', 'edit', 'form', 'brief',
            );
            if ($this->view_mode === 'vue') $pages_to_generate[] = 'index';
            if ( ! $allow_edit_certain) unset($pages['edit_certain']); // 若不允许修改单项，则不生成相应文档
			if ( ! empty($extra_functions)) $pages = array_merge($pages, $extra_functions);  // 其它附加功能
			$i = 0; // 初始化页面序号
			foreach ($pages as $name => $title):
				// 为特殊功能做特别处理
				if ( is_numeric($name) ) $name = $title;

				// 生成页面文档
				if ($doc_page && in_array($name, $pages_to_doc)):
                    // 页面文档必要字段
                    $doc_content_page = array(
                        'biz_id' => $biz_id,
                        'project_id' => $project_id,
                        'name' => $this->info['class_name_cn']. $title,
                        'code' => $this->info['code'].'P'. $i,
                        'code_class' => strtolower( $this->info['class_name'] ),
                        'code_function' => $name,
                        'status' => '0', // 默认为草稿状态
                    );

					$this->doc_page_generate($doc_content_page, $pages, $title);
                    unset($doc_content_page);
                endif;
                $i++; // 更新序号

				// 生成视图文件；部分页面需插入具体生成的内容
				if ($file_view && in_array($name, $pages_to_generate)):
                    $target_directory = 'views/'.strtolower( $this->info['class_name'] ).'/';
                    $this->view_file_generate($target_directory, $name, $this->info[$name]);
				endif;
			endforeach;

            unset($biz_id, $project_id); // 回收内存

            // PHP 生成通用视图文件
            if ($file_view && $this->view_mode === 'php'):
                $common_pages = array('delete', 'index', 'restore', 'result', 'trash',);
                if ($allow_edit_certain) $common_pages[] = 'edit_certain';
                foreach ($common_pages as $name):
                    $target_directory = 'views/'.strtolower( $this->info['class_name'] ).'/';
                    $this->view_file_generate($target_directory, $name);
                endforeach;
            endif;

            /**
             * 生成视图或控制器文件
             */

            // 生成控制器文件
            if ($file_app || $file_api):
                // 获取模板文件，并开始生成待生成文件内容
                // 待替换的内容
                $real_contents = array(
                    'table_name', 'id_name', 'class_name', 'class_name_cn', 'code', 'rules', 'names_csv', 'names_list',
                );

                if ($file_api):
                    $api_file_content = file_get_contents($this->template_url.'Template_api.php');
                    foreach ($real_contents as $real_content):
                        $api_file_content = str_replace('[['.$real_content.']]', $this->info[$real_content], $api_file_content);
                    endforeach;
                endif;

                if ($file_app):
                    $app_file_content = file_get_contents($this->template_url.'Template_app.php');
                    foreach ($real_contents as $real_content):
                        $app_file_content = str_replace('[['.$real_content.']]', $this->info[$real_content], $app_file_content);
                    endforeach;
                endif;

                // 若有需要特别生成的类方法，进行生成
                if ( empty($extra_functions) ):
                    // 若没有需要特别生成的类方法，清除模板文件中的相应占位标记
                    if ($file_api):
                        $api_file_content = str_replace('[[extra_functions]]', NULL, $api_file_content);
                    endif;
                    if ($file_app):
                        $app_file_content = str_replace('[[extra_functions]]', NULL, $app_file_content);
                    endif;

                else:
                    $extra_functions = explode(',', $extra_functions);
                    $extra_functions_code = ''; // 待插入模板控制器文件的代码

                    foreach ($extra_functions as $function):
                        $extra_functions_code .=
                            "\n\n".
                            "\t\t". '// 类方法'. "\n".
                            "\t\t". 'public function '. $function. '()'. "\n".
                            "\t\t". '{'.
                            "\n\n".
                            "\t\t". '} // end '. $function;
                    endforeach;

                    if ($file_api) $api_file_content = str_replace('[[extra_functions]]', $extra_functions_code, $api_file_content);
                    if ($file_app) $app_file_content = str_replace('[[extra_functions]]', $extra_functions_code, $app_file_content);

                endif;

                // 待生成的文件名
                $file_name = ucfirst($this->info['class_name']). '.php';

                // 生成应用控制器文件
                if ($file_app):
                    $this->controller_file_generate('controllers/', $file_name, $app_file_content);
                    unset($app_file_content);
                endif;

                // 生成API控制器文件
                if ($file_api):
                    $this->api_file_generate('api/', $file_name, $api_file_content);
                    unset($api_file_content);
                endif;
            endif;
        } // end class_generate

        /**
         * 以下为工具方法
         */

        /**
         * 从API获取相应表字段信息
         *
         * @param $api_url 目标API路径
         * @param null $params 请求参数
         */
        private function grab_table($api_url, $params = NULL)
        {
            // $this->output->enable_profiler(TRUE); // 输出调试信息
            
            try {
                $result = $this->curl->go($api_url, $params, 'array');
            } catch(Exception $error) {
                // var_dump($error);
                $this->result['status'] = 500;
                $this->result['content']['error']['message'] = 'grab_table failed with code 500';
                exit();
            }

            if ($result['status'] === 200):
                try {
                    $this->parse_table($result['content']);
                } catch(Exception $error) {
                    // var_dump($error);
                    $this->result['status'] = 500;
                    $this->result['content']['error']['message'] = 'parse_table failed';
                    exit();
                }

            else:
                // var_dump($this->result);
                $this->result['status'] = 404;
                $this->result['content']['error']['message'] = 'grab_table failed with code 404';
                exit('API请求不成功');
            endif;
        } // end grab_table

        /**
         * 解析表字段
         *
         * @param $names_info 表字段信息
         * @return $result 解析后的内容
         */
        protected function parse_table($names_info)
        {
            foreach ($names_info as $column):
                // 预赋值部分待用数据
                $name = $column['name']; // 当前字段名
                $comment = $column['comment']; // 当前字段备注
                $type = $column['type']; // 当前字段数据类型
                $required = $column['allow_null'] === 'NO'? TRUE: FALSE; // 当前字段是否必要

                /**
                 * 输出表字段信息
                 */
                $this->info['form_data'] .= $name. ':'. "\n";
                $this->info['params_request'] .= '<tr><td>'. $name. '</td><td>'.$type.'</td><td>'.($required? '是': '否').'</td><td>示例</td><td>'.$comment.'</td></tr>'. "\n";

                // 对于其它用途，去除字段备注中首个分号（全角或半角）之后的部分
                $position_of_first_comma = strpos($comment, '；');
                $length_to_end = $position_of_first_comma? $position_of_first_comma: strpos($comment, ';');
                if ($length_to_end) $comment = substr($comment, 0, $length_to_end);

                $this->info['params_respond'] .= '<tr><td>'. $name. '</td><td>'.$type.'</td><td>详见返回示例</td><td>'.$comment.'</td></tr>'. "\n";
                $this->info['elements'] .= '<tr><td>┣'. $name. '</td><td>1</td><td>文本</td><td>'.$comment.'</td></tr>'. "\n";

                // 对于下列用途，跳过通用字段
                $meta_names = array($this->info['id_name'], 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id');
                if ( ! in_array($name, $meta_names)):
                    $this->info['names_csv'] .= "$name,";
                    $this->info['names_list'] .= "'$name', ";

                    $this->info['rules'] .= "\t\t\t". '$this->form_validation->set_rules('. "'$name', '$comment', 'trim". ($required? '|required': NULL). "');". "\n";

                    $this->info['form'] .=
                        "\t\t\t\t\t\t".
                        "<div class=\"form-group row\">
                            <label class=\"col-form-label col-sm-2\">$comment". ($required? ' *': NULL). "</label>
                            
                            <div class=\"input-group col-sm-10\">
                                <input v-model.trim.lazy=\"$name\" class=form-control type=text placeholder=\"$comment\"". ($required? ' required': NULL). ">
                            </div>
                        </div>". "\n\n";

                    $this->info['brief'] .=
                        "\t\t".'<dt>'.$comment.'</dt>'. "\n".
                        "\t\t".'<dd>{{ item.'. $name. ' }}</dd>'. "\n\n";

                    $this->info['create'] .=
                        "\t\t\t\t\t\t".
                        "<div class=form-group>
                            <label for=$name class=\"col-form-label col-sm-2\">$comment". ($required? ' *': NULL). "</label>
                            
                            <div class=\"input-group col-sm-10\">
                                <input class=form-control name=$name type=text value=\"<?php echo set_value('$name') ?>\" placeholder=\"$comment\"". ($required? ' required': NULL). ">
                            </div>
                        </div>". "\n\n";

                    $this->info['edit'] .=
                        "\t\t\t\t\t\t".
                        "<div class=form-group>
                            <label for=$name class=\"col-form-label col-sm-2\">$comment". ($required? ' *': NULL). "</label>
                            
                            <div class=\"input-group col-sm-10\">
                                <input class=form-control name=$name type=text value=\"<?php echo empty(set_value('$name'))? ".'$item'."['$name']: set_value('$name') ?>\" placeholder=\"$comment\"". ($required? ' required': NULL). ">
                            </div>
                        </div>". "\n\n";

                    $this->info['detail'] .=
                        "\t\t".'<dt>'.$comment.'</dt>'. "\n".
                        "\t\t".'<dd><?php echo empty($item'."['$name']".')? \'N/A\': $item'."['$name']".' ?></dd>'. "\n\n";
                endif;
            endforeach;
        } // end parse_table

        /**
         * 生成路径并确认权限
         *
         * @param $target_directory 文件目标路径
         */
        protected function generate_directory($target_directory)
        {
            // 检查目标路径是否存在
            if ( ! file_exists($target_directory) )
                mkdir($target_directory, 0777, TRUE); // 若不存在则新建，且允许新建多级子目录

            // 设置目标路径（含文件名）
            chmod($target_directory, 0777); // 设置权限为可写
        } // end generate_directory

        /**
         * 生成API文档
         *
         * 不含需特别生成的类方法相关页面
         * @param $data_to_create
         * @param $apis
         * @param $i
         */
		private function doc_api_generate($data_to_create, $apis, $i)
		{
			switch ($apis[$i]):
				case '计数':
					$data_to_create['url'] .= 'count';
					$data_to_create['params_request'] = $this->params_request;
					$data_to_create['params_respond'] = '<tr><td>count</td><td>int</td><td>1</td><td>符合筛选条件（若有）的数据项数量</td></tr>';
					break;

				case '列表':
					$data_to_create['url'] .= 'index';
					$data_to_create['params_request'] =
						'<tr><td>limit</td><td>int</td><td>否</td><td>10</td><td>获取多少行数据；默认获取所有数据</td></tr>'. "\n".
						'<tr><td>offset</td><td>int</td><td>否</td><td>20</td><td>跳过多少行数据；默认“0”</td></tr>'. "\n".
                        '<tr><td>ids</td><td>string</td><td>否</td><td>1,3,45,179,332</td><td>需获取的数据主键值清单；CSV，此值不为空时，limit、offset将被忽略</td></tr>'. "\n".
						$this->params_request;
					$data_to_create['params_respond'] = $this->params_respond;

					$extra_request = array('limit', 'offset');
					foreach ($extra_request as $extra)
					    $data_to_create['sample_request'] = $extra.":\n". $data_to_create['sample_request'];
					break;

				case '详情':
					$data_to_create['url'] .= 'detail';
					$data_to_create['params_request'] = '<tr><td>id</td><td>string</td><td>是</td><td>1</td><td>'.$this->class_name_cn.'ID</td></tr>';
					$data_to_create['params_respond'] = $this->params_respond;

					$data_to_create['sample_request'] = "id:\n";
					break;

				case '创建':
					$data_to_create['url'] .= 'create';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>创建者用户ID</td></tr>'. "\n".
						$this->params_request;
					$data_to_create['params_respond'] =
						'<tr><td>id</td><td>string</td><td>1</td><td>创建的'.$this->class_name_cn.'ID</td></tr>'. "\n".
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';
					
					$data_to_create['sample_request'] = "user_id:\n". $data_to_create['sample_request'];
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
					foreach ($extra_request as $extra)
					    $data_to_create['sample_request'] = $extra.":\n". $data_to_create['sample_request'];
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
					foreach ($extra_request as $extra)
					    $data_to_create['sample_request'] = $extra.":\n". $data_to_create['sample_request'];
					break;

				case '批量操作':
					$data_to_create['url'] .= 'edit_bulk';
					$data_to_create['params_request'] =
						'<tr><td>user_id</td><td>string</td><td>是</td><td>1</td><td>操作者用户ID</td></tr>'. "\n".
						'<tr><td>ids</td><td>string</td><td>是</td><td>1,2,3</td><td>待操作项ID们；CSV</td></tr>'. "\n".
						'<tr><td>operation</td><td>string</td><td>是</td><td>delete</td><td>待执行操作；删除delete,找回restore</td></tr>'. "\n".
						'<tr><td>password</td><td>string</td><td>是</td><td>略</td><td>操作者用户密码</td></tr>';
					$data_to_create['params_respond'] =
						'<tr><td>message</td><td>string</td><td>详见“返回示例”</td><td>需要显示的提示信息</td></tr>';

					// 重置请求示例
					$data_to_create['sample_request'] = '';
					$extra_request = array('user_id', 'ids', 'operation', 'password');
					foreach ($extra_request as $extra)
					    $data_to_create['sample_request'] = $extra.":\n". $data_to_create['sample_request'];
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

        /**
         * 生成页面文档
         *
         * 不含需特别生成的类方法相关页面
         * @param $data_to_create
         * @param $pages
         * @param $title
         */
		private function doc_page_generate($data_to_create, $pages, $title)
		{
			switch ($title):
				case '操作结果':
					$data_to_create['description'] = '显示'.$this->info['class_name_cn'].'的操作结果';
					$data_to_create['return_allowed'] = 0;
					$data_to_create['elements'] =
						'<tr><td>text_title</td><td>1</td><td>文本</td><td>“操作结果”</td></tr>'. "\n".
						'<tr><td>button_home</td><td>1</td><td>按钮</td><td>“首页”</td></tr>'. "\n".
						'<tr><td>button_mine</td><td>1</td><td>按钮</td><td>“'.$this->info['class_name_cn'].'列表”</td></tr>';
					$data_to_create['onloads'] = '<li>获取传入的title，赋值到text_title进行显示</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_'.$this->info['class_name'].'.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>转到'.$this->info['class_name_cn'].'列表页</li>'. "\n".
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
					$data_to_create['description'] = '显示符合给定条件（若有）的'.$this->info['class_name_cn'].'摘要/详细信息';
					$data_to_create['elements'] =
						'<tr><td>list</td><td>1</td><td>列表</td><td>信息列表</td></tr>'. "\n".
						'<tr><td>┗item</td><td>1</td><td>块级区域</td><td>单项信息</td></tr>'. "\n".
						$this->elements."\n".
						'<tr><td>button_create</td><td>1</td><td>按钮</td><td>“创建'.$this->info['class_name_cn'].'”</td></tr>';
					$data_to_create['onloads'] =
						'<li>调用'. substr($data_to_create['code'], 0, -2). '1，若为空或失败则结束并提示</li>'. "\n".
						'<li>将返回值各项循环赋值为list中的item视图元素</li>';
					$data_to_create['events'] =
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_create.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>转到'.$this->info['class_name_cn'].'创建页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>item.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>传'.$this->info['class_name'].'_id键值对到'.$this->info['class_name_cn'].'详情页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					break;

				case '详情':
					$data_to_create['description'] = '显示单个'.$this->info['class_name_cn'].'详细信息';
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
						'		<li>传id=相应'.$this->info['class_name'].'_id、name=相应字段名、value=相应字段值到'.$this->info['class_name_cn'].'单项修改页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>'. "\n".
						'<div class="panel panel-default">'. "\n".
						'	<h4 class=panel-heading>button_edit.click</h4>'. "\n".
						'	<ol class=panel-body>'. "\n".
						'		<li>传'.$this->info['class_name'].'_id键值对到'.$this->info['class_name_cn'].'修改页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					break;

				case '创建':
					$data_to_create['description'] = '创建单个'.$this->info['class_name_cn'];
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
						'		<li>传title="成功创建'.$this->info['class_name_cn'].'"到'.$this->info['class_name_cn'].'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '修改':
					$data_to_create['description'] = '修改'.$this->info['class_name_cn'].'完整信息';
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
						'		<li>传title="成功修改'.$this->info['class_name_cn'].'"到'.$this->info['class_name_cn'].'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '单项修改':
					$data_to_create['description'] = '修改'.$this->info['class_name_cn'].'单项信息';
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
						'		<li>传'.$this->info['class_name'].'_id=当前id”到'.$this->info['class_name_cn'].'详情页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '删除':
					$data_to_create['description'] = '删除单个/多个'.$this->info['class_name_cn'];
					$data_to_create['elements'] =
						'<tr><td>table_items</td><td>1</td><td>表格</td><td>待操作项主要信息表</td></tr>'. "\n".
						$this->elements. "\n".
						'<tr><td>form_delete</td><td>1</td><td>表单</td><td>删除表单</td></tr>'. "\n".
						'<tr><td>┣warning</td><td>1</td><td>文本</td><td>“确定要删除上述'.$this->info['class_name_cn'].'？”</td></tr>'. "\n".
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
						'		<li>传title="成功删除'.$this->info['class_name_cn'].'"到'.$this->info['class_name_cn'].'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				case '找回':
					$data_to_create['description'] = '找回单个/多个已删除'.$this->info['class_name_cn'];
					$data_to_create['elements'] =
						'<tr><td>table_items</td><td>1</td><td>表格</td><td>待操作项主要信息表</td></tr>'. "\n".
						$this->elements. "\n".
						'<tr><td>form_restore</td><td>1</td><td>表单</td><td>找回表单</td></tr>'. "\n".
						'<tr><td>┣warning</td><td>1</td><td>文本</td><td>“确定要找回上述'.$this->info['class_name_cn'].'？”</td></tr>'. "\n".
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
						'		<li>传title="成功找回'.$this->info['class_name_cn'].'"到'.$this->info['class_name_cn'].'操作结果页</li>'. "\n".
						'	</ol>'. "\n".
						'</div>';
					$data_to_create['note_developer'] = '各字段格式参考相应API文档；需为各输入型字段激活适当类型的键盘';
					break;

				default:
					$data_to_create['elements'] = $this->info['elements'];
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
         * 生成API控制器文件
         *
         * @param $target_directory
         * @param $file_name
         * @param $file_content
         */
		private function api_file_generate($target_directory, $file_name, $file_content)
		{
			// 生成完整的文件所在目录
			$target_directory = 'generated/'. $target_directory;
			$this->generate_directory($target_directory);

			// 创建新文件并写入内容
            $target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $target_directory. $file_name;
			$result = file_put_contents($target_url, $file_content);
			if ( $result !== FALSE ):
				$this->result['status'] = 200;
				$this->result['content']['api'] = array(
                    'file_name' => $target_url,
                    'file_size' => round($result / 1024, 2). ' kb'
                );
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = '类API文件创建失败';
			endif;
		} // end api_file_generate

        /**
         * 生成应用控制器文件
         *
         * @param $target_directory
         * @param $file_name
         * @param $file_content
         */
		private function controller_file_generate($target_directory, $file_name, $file_content)
		{
			// 生成完整的文件所在目录
			$target_directory = 'generated/'. $target_directory;
            $this->generate_directory($target_directory);

            // 创建新文件并写入内容
            $target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $target_directory. $file_name;
			$result = file_put_contents($target_url, $file_content);
			if ( $result !== FALSE ):
				$this->result['status'] = 200;
				$this->result['content']['controllers'] = array(
                    'file_name' => $target_url,
                    'file_size' => round($result / 1024, 2). ' kb'
                );
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = '类控制器文件创建失败';
			endif;
		} // end controller_file_generate

        /**
         * 生成视图文件
         *
         * 部分文件需插入相关字段相关内容
         * @param $target_directory
         * @param $file_name
         * @param null $content_to_insert
         */
		private function view_file_generate($target_directory, $file_name, $content_to_insert = NULL)
		{
		    // 为vue视图模式生成特定的视图路径
		    if ($this->view_mode === 'vue'):
                if ($file_name === 'form' || $file_name === 'brief'):
                    $file_name = '_'.$file_name;
                    $template_root = $this->view_template_root .'components/';
                else:
                    $template_root = $this->view_template_root .'page/';
                endif;
            else:
                $template_root = $this->view_template_root;
            endif;

			// 获取模板文件并生成待生成API文件内容
			$file_content = file_get_contents($template_root. $file_name. '.'.$this->view_mode);
			if ($content_to_insert != NULL)
			    $file_content = str_replace('[[content]]', $content_to_insert, $file_content);

			// 生成完整的文件写入目录
			$target_directory = 'generated/'. $target_directory;
            $this->generate_directory($target_directory);

            // 创建新文件并写入内容
            $target_url = $_SERVER['DOCUMENT_ROOT']. '/'. $target_directory. $file_name. '.'.$this->view_mode;
			$result = file_put_contents($target_url, $file_content);
			if ( $result !== FALSE ):
				$this->result['status'] = 200;
				$this->result['content']['views'][] = array(
                    'file_name' => $target_url,
                    'file_size' => round($result / 1024, 2). ' kb'
                );
			else:
				$this->result['status'] = 400;
				$this->result['error']['message'] = '视图文件创建失败';
			endif;
		} // end view_file_generate

	} // end class Tool

/* End of file Tool.php */
/* Location: ./application/controllers/Tool.php */
