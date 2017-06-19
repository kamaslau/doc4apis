<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Meta 类
	 *
	 * 技术参数相关功能
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Meta extends CI_Controller
	{
		/* 类名称小写，应用于多处动态生成内容 */
		public $class_name;

		/* 类名称中文，应用于多处动态生成内容 */
		public $class_name_cn;

		/* 主要相关表名 */
		public $table_name;

		/* 主要相关表的主键名*/
		public $id_name;

		/* 视图文件所在目录名 */
		public $view_root;
		
		/* 需要显示的字段 */
		public $data_to_display;

		public function __construct()
		{
			parent::__construct();

			// （可选）未登录用户转到登录页
			if ($this->session->logged_in !== TRUE) redirect(base_url('login'));

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '参数'; // 改这里……
			$this->table_name = 'meta'; // 和这里……
			$this->id_name = 'meta_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'project_id' => '项目ID',
				'sdk_ios' => 'iOS最低版本',
				'sdk_android' => 'Android最低版本',
			);

			// 设置并调用Basic核心库
			$basic_configs = array(
				'table_name' => $this->table_name,
				'id_name' => $this->id_name,
				'view_root' => $this->view_root,
			);
			$this->load->library('basic', $basic_configs);
		}
		
		/**
		 * 截止3.1.3为止，CI_Controller类无析构函数，所以无需继承相应方法
		 */
		public function __destruct()
		{
			// 调试信息输出开关
			// $this->output->enable_profiler(TRUE);
		}

		/**
		 * 列表页
		 */
		public function index()
		{
			// 检查是否已传入必要参数
			$project_id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;
			if ( empty($project_id) ) redirect(base_url('project'));

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '列表',
				'class' => $this->class_name.' '. $this->class_name.'-index',
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($project_id, 'project', 'project_id');

			// 筛选条件
			$condition['project_id'] = $project_id;

			// 排序条件
			$order_by = NULL;

			// Go Basic！
			$this->basic->index($data, $condition, $order_by);
		}

		/**
		 * 详情页
		 */
		public function detail()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id');
			$project_id = $this->input->get_post('project_id');
			if ( empty($id) && empty($project_id) )
				redirect(base_url('error/code_404'));

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '详情',
				'class' => $this->class_name.' '. $this->class_name.'-detail',
			);

			// 获取页面数据
			if ( !empty($id) ):
				$data['item'] = $this->basic_model->select_by_id($id);
			else:
				$data['item'] = $this->basic_model->find('project_id', $project_id);
			endif;

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($data['item']['project_id'], 'project', 'project_id');

			// 生成页面标题
			$data['title'] = $data['project']['name']. $this->class_name_cn;

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view('templates/footer', $data);
		}

		/**
		 * 创建
		 */
		public function create()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '创建'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-create',
			);

			// (可选) 检查是否已传入必要参数，例如创建某项目所属的页面
			$id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;
			if ( empty($id) )
				redirect(base_url('error/code_404'));
			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($id, 'project', 'project_id');

			// 待验证的表单项
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero|required');
			$this->form_validation->set_rules('url_web', 'WEB URL', 'trim|valid_url');
			$this->form_validation->set_rules('url_wechat', '微信公众号二维码', 'trim|valid_url');
			$this->form_validation->set_rules('url_api', 'API URL', 'trim|valid_url');
			$this->form_validation->set_rules('url_ios', 'iOS URL', 'trim|valid_url');
			$this->form_validation->set_rules('sdk_ios', 'iOS最低版本', 'trim');
			$this->form_validation->set_rules('sdk_android', 'Android最低版本', 'trim');
			$this->form_validation->set_rules('url_android', 'Android URL', 'trim|valid_url');
			$this->form_validation->set_rules('sandbox_url_web', '开发环境WEB URL', 'trim|valid_url');
			$this->form_validation->set_rules('sandbox_url_api', '开发环境API URL', 'trim|valid_url');
			$this->form_validation->set_rules('encode', '编码方式', 'trim');
			$this->form_validation->set_rules('protocol', '传输协议', 'trim');
			$this->form_validation->set_rules('request_method', '请求方式', 'trim');
			$this->form_validation->set_rules('request_format', '请求格式', 'trim');
			$this->form_validation->set_rules('respond_format', '响应返回格式', 'trim');
			$this->form_validation->set_rules('sign', '签名方式', 'trim');
			$this->form_validation->set_rules('params_request', '请求参数', 'trim');
			$this->form_validation->set_rules('params_respond', '返回参数', 'trim');

			// 需要存入数据库的信息
			$data_to_create = array(
				'project_id' => $this->input->post('project_id'),
				'url_web' => $this->input->post('url_web'),
				'url_wechat' => $this->input->post('url_wechat'),
				'url_api' => $this->input->post('url_api'),
				'sdk_ios' => $this->input->post('sdk_ios'),
				'sdk_android' => $this->input->post('sdk_android'),
				'url_ios' => $this->input->post('url_ios'),
				'url_android' => $this->input->post('url_android'),
				'sandbox_url_web' => $this->input->post('sandbox_url_web'),
				'sandbox_url_api' => $this->input->post('sandbox_url_api'),
				
				'encode' => $this->input->post('encode'),
				'protocol' => $this->input->post('protocol'),
				'request_method' => $this->input->post('request_method'),
				'request_format' => $this->input->post('request_format'),
				'respond_format' => $this->input->post('respond_format'),
				
				'sign' => $this->input->post('sign'),
				'params_request' => $this->input->post('params_request'),
				'params_respond' => $this->input->post('params_respond'),
			);

			// Go Basic!
			$this->basic->create($data, $data_to_create);
		}

		/**
		 * 编辑单行
		 */
		public function edit()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => '编辑'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-edit',
			);

			// 待验证的表单项
			$this->form_validation->set_rules('sdk_ios', 'iOS最低版本', 'trim');
			$this->form_validation->set_rules('sdk_android', 'Android最低版本', 'trim');
			$this->form_validation->set_rules('url_web', 'WEB URL', 'trim|valid_url');
			$this->form_validation->set_rules('url_wechat', '微信公众号二维码', 'trim|valid_url');
			$this->form_validation->set_rules('url_api', 'API URL', 'trim|valid_url');
			$this->form_validation->set_rules('url_ios', 'iOS URL', 'trim|valid_url');
			$this->form_validation->set_rules('url_android', 'Android URL', 'trim|valid_url');
			$this->form_validation->set_rules('sandbox_url_web', '开发环境WEB URL', 'trim|valid_url');
			$this->form_validation->set_rules('sandbox_url_api', '开发环境API URL', 'trim|valid_url');
			$this->form_validation->set_rules('encode', '编码方式', 'trim');
			$this->form_validation->set_rules('protocol', '传输协议', 'trim');
			$this->form_validation->set_rules('request_method', '请求方式', 'trim');
			$this->form_validation->set_rules('request_format', '请求格式', 'trim');
			$this->form_validation->set_rules('respond_format', '响应返回格式', 'trim');
			$this->form_validation->set_rules('sign', '签名方式', 'trim');
			$this->form_validation->set_rules('params_request', '公共请求参数', 'trim');
			$this->form_validation->set_rules('params_respond', '公共返回参数', 'trim');

			// 需要编辑的信息
			$data_to_edit = array(
				'sdk_ios' => $this->input->post('sdk_ios'),
				'sdk_android' => $this->input->post('sdk_android'),
				'url_web' => $this->input->post('url_web'),
				'url_wechat' => $this->input->post('url_wechat'),
				'url_api' => $this->input->post('url_api'),
				'url_ios' => $this->input->post('url_ios'),
				'url_android' => $this->input->post('url_android'),
				'sandbox_url_web' => $this->input->post('sandbox_url_web'),
				'sandbox_url_api' => $this->input->post('sandbox_url_api'),
				
				'encode' => $this->input->post('encode'),
				'protocol' => $this->input->post('protocol'),
				'request_method' => $this->input->post('request_method'),
				'request_format' => $this->input->post('request_format'),
				'respond_format' => $this->input->post('respond_format'),
				
				'sign' => $this->input->post('sign'),
				'params_request' => $this->input->post('params_request'),
				'params_respond' => $this->input->post('params_respond'),
			);

			// Go Basic!
			$this->basic->edit($data, $data_to_edit); // 可以自定义视图文件名
		}
	}

/* End of file Meta.php */
/* Location: ./application/controllers/Meta.php */
