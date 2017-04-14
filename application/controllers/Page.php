<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Page 类
	 *
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Page extends CI_Controller
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
			$this->class_name_cn = '页面'; // 改这里……
			$this->table_name = 'page'; // 和这里……
			$this->id_name = 'page_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'name' => '名称',
				'description' => '描述',
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
			
			// 筛选条件
			$condition['project_id'] = $project_id;
			
			// 排序条件
			$order_by[$this->id_name] = 'ASC';
			
			// Go Basic！
			$this->basic->index($data, $condition, $order_by);
		}

		/**
		 * 详情页
		 */
		public function detail()
		{
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;

			// 检查是否已传入必要参数
			if ( empty($id) ):
				$this->error(404, '网址不完整');
				exit;
			endif;
			
			// 页面信息
			$data = array(
				'title' => NULL,
				'class' => $this->class_name.' '. $this->class_name.'-detail',
			);

			// 获取页面数据
			$data['item'] = $this->basic_model->select_by_id($id);
			
			// 若存在相关页面，则获取页面信息
			if ( !empty($data['item']['page_ids']) ):
				$page_ids = explode(' ', $data['item']['page_ids']);
				$data['pages'] = array();
				foreach ($page_ids as $page_id):
					$data['pages'][] = $this->basic_model->select_by_id($page_id);
				endforeach;
			endif;

			// 若存在相关API，则获取API信息
			if ( !empty($data['item']['api_ids']) ):
				$api_ids = explode(' ', $data['item']['api_ids']);
				$data['apis'] = array();
				$this->basic_model->table_name = 'project';
				$this->basic_model->id_name = 'project_id';
				foreach ($api_ids as $api_id):
					$data['apis'][] = $this->basic_model->select_by_id($api_id);
				endforeach;
			endif;

			// 获取项目数据
			$this->basic_model->table_name = 'project';
			$this->basic_model->id_name = 'project_id';
			$data['project'] = $this->basic_model->select_by_id($data['item']['project_id']);

			// 生成最终页面标题
			$data['title'] = $data['project']['name']. $data['item']['name']. '页 ';

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view('templates/footer', $data);
		}

		/**
		 * 回收站
		 *
		 * 一般为后台功能
		 */
		public function trash()
		{
			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '回收站',
				'class' => $this->class_name.' '. $this->class_name.'-trash',
			);
			
			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;
			
			// 筛选条件
			$condition = NULL;
			//$condition['name'] = 'value';
			
			// 排序条件
			$order_by = NULL;
			//$order_by['name'] = 'value';
			
			// Go Basic！
			$this->basic->trash($data, $condition, $order_by);
		}

		/**
		 * 创建
		 *
		 * 一般为后台功能
		 */
		public function create()
		{
			$id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;

			// 检查是否已传入必要参数
			if (empty($id)):
				$this->error(404, '网址不完整');
				exit;
			endif;

			// 页面信息
			$data = array(
				'title' => '创建'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-create',
			);

			// 获取项目数据
			$this->basic_model->table_name = 'project';
			$this->basic_model->id_name = 'project_id';
			$data['project'] = $this->basic_model->select_by_id($id);

			// 后台操作可能需要检查操作权限
			/*
			$role_allowed = array('editor', 'manager'); // 员工角色要求
			$min_level = 0; // 员工最低权限
			$this->basic->permission_check($role_allowed, $min_level);
			*/

			// 待验证的表单项
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero|required');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('description', '说明', 'trim|required');
			$this->form_validation->set_rules('private', '是否需登录', 'trim');
			$this->form_validation->set_rules('elements', '视图元素', 'trim');
			$this->form_validation->set_rules('url_design', '设计图URL', 'trim');
			$this->form_validation->set_rules('url_assets', '美术素材URL', 'trim');
			$this->form_validation->set_rules('onloads', '载入事件', 'trim');
			$this->form_validation->set_rules('events', '业务流程', 'trim');
			$this->form_validation->set_rules('api_ids', '相关API', 'trim');
			$this->form_validation->set_rules('page_ids', '相关页面', 'trim');

			// 需要存入数据库的信息
			// 不建议直接用$this->input->post/get/post_get等方法直接在此处赋值，向数组赋值前处理会保持最大的灵活性以应对图片上传等场景
			$data_to_create = array(
				'project_id' => $this->input->post('project_id'),
				'category_id' => $this->input->post('category_id'),
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'private' => $this->input->post('private'),
				'elements' => $this->input->post('elements'),
				'url_design' => $this->input->post('url_design'),
				'url_assets' => $this->input->post('url_assets'),
				'onloads' => $this->input->post('onloads'),
				'events' => $this->input->post('events'),
				'api_ids' => $this->input->post('api_ids'),
				'page_ids' => $this->input->post('page_ids'),
			);

			// Go Basic!
			$this->basic_model->table_name = 'page';
			$this->basic_model->id_name = 'page_id';
			$this->basic->create($data, $data_to_create);
		}

		/**
		 * 编辑单行
		 *
		 * 一般为后台功能
		 */
		public function edit()
		{
			// 页面信息
			$data = array(
				'title' => '编辑'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-edit',
			);

			// 后台操作可能需要检查操作权限
			/*
			$role_allowed = array('editor', 'manager'); // 员工角色要求
			$min_level = 0; // 员工最低权限
			$this->basic->permission_check($role_allowed, $min_level);
			*/

			// 待验证的表单项
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('description', '说明', 'trim|required');
			$this->form_validation->set_rules('private', '是否需登录', 'trim');
			$this->form_validation->set_rules('elements', '视图元素', 'trim');
			$this->form_validation->set_rules('url_design', '设计图URL', 'trim');
			$this->form_validation->set_rules('url_assets', '美术素材URL', 'trim');
			$this->form_validation->set_rules('onloads', '载入事件', 'trim');
			$this->form_validation->set_rules('events', '业务流程', 'trim');
			$this->form_validation->set_rules('api_ids', '相关API', 'trim');
			$this->form_validation->set_rules('page_ids', '相关页面', 'trim');

			// 需要编辑的信息
			// 不建议直接用$this->input->post、$this->input->get等方法直接在此处赋值，向数组赋值前处理会保持最大的灵活性以应对图片上传等场景
			$data_to_edit = array(
				'category_id' => $this->input->post('category_id'),
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'private' => $this->input->post('private'),
				'elements' => $this->input->post('elements'),
				'url_design' => $this->input->post('url_design'),
				'url_assets' => $this->input->post('url_assets'),
				'onloads' => $this->input->post('onloads'),
				'events' => $this->input->post('events'),
				'api_ids' => $this->input->post('api_ids'),
				'page_ids' => $this->input->post('page_ids'),
			);

			// Go Basic!
			$this->basic->edit($data, $data_to_edit);
		}

		/**
		 * 删除单行或多行项目
		 *
		 * 一般用于存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如delete、restore、draft等
		 */
		public function delete()
		{
			$op_name = '删除'; // 操作的名称
			$op_view = 'delete'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-'. $op_view,
			);
			
			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 后台操作可能需要检查操作权限
			/*
			$role_allowed = array('editor', 'manager'); // 员工角色要求
			$min_level = 0; // 员工最低权限
			$this->basic->permission_check($role_allowed, $min_level);
			*/

			// 待验证的表单项
			$this->form_validation->set_rules('password', '密码', 'trim|required|is_natural|exact_length[6]');

			// 需要存入数据库的信息
			$data_to_edit = array(
				'time_delete' => date('y-m-d H:i:s'), // 批量删除
			);

			// Go Basic!
			$this->basic->bulk($data, $data_to_edit, $op_name, $op_view);
		}
		
		/**
		 * 恢复单行或多行项目
		 *
		 * 一般用于存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如delete、restore、draft等
		 */
		public function restore()
		{
			$op_name = '恢复'; // 操作的名称
			$op_view = 'restore'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-'. $op_view,
			);
			
			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 后台操作可能需要检查操作权限
			/*
			$role_allowed = array('editor', 'manager'); // 员工角色要求
			$min_level = 0; // 员工最低权限
			$this->basic->permission_check($role_allowed, $min_level);
			*/

			// 待验证的表单项
			$this->form_validation->set_rules('password', '密码', 'trim|required|is_natural|exact_length[6]');

			// 需要存入数据库的信息
			$data_to_edit = array(
				'time_delete' => NULL, // 批量恢复
			);

			// Go Basic!
			$this->basic->bulk($data, $data_to_edit, $op_name, $op_view);
		}
	}

/* End of file Page.php */
/* Location: ./application/controllers/Page.php */
