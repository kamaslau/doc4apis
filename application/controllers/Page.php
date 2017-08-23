<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Page 类
	 *
	 * 页面相关功能
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
				'code' => '序号',
				'name' => '名称',
				'biz_id' => '所属企业ID',
				'project_id' => '所属项目ID',
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
			if ( !empty($project_id) )
				$condition['project_id'] = $project_id;

			// 非系统级管理员仅可看到自己企业相关的信息
			if ( ! empty($this->session->biz_id) ):
				$condition['biz_id'] = $this->session->biz_id;

			// 系统级管理员可查看任意企业的相关信息
			elseif ($this->session->role === '管理员'):
				$biz_id = $this->input->get_post('biz_id')? $this->input->get_post('biz_id'): NULL;
				if ( !empty($biz_id) )
					$condition['biz_id'] = $biz_id;
			endif;

			// 排序条件
			$order_by['biz_id'] = 'ASC';
			$order_by['project_id'] = 'ASC';
			$order_by['code'] = 'ASC'; // 按序号字母顺序进行排序

			// Go Basic！
			$this->basic->index($data, $condition, $order_by);
		} // end index

		/**
		 * 详情页
		 */
		public function detail()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( empty($id) )
				redirect(base_url('error/code_404'));

			// 页面信息
			$data = array(
				'title' => NULL,
				'class' => $this->class_name.' '. $this->class_name.'-detail',
			);

			// 获取页面数据
			$data['item'] = $this->basic_model->select_by_id($id);

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($data['item']['project_id'], 'project', 'project_id');

			// 若存在相关页面，则获取页面信息
			if ( !empty($data['item']['page_ids']) ):
				$data['pages'] = $this->basic->get_by_ids($data['item']['page_ids'], 'page', 'page_id');
			endif;

			// 若存在相关API，则获取API信息
			if ( !empty($data['item']['api_ids']) ):
				$data['apis'] = $this->basic->get_by_ids($data['item']['api_ids'], 'api', 'api_id');
			endif;

			// 生成最终页面标题
			$data['title'] = $data['project']['name']. $data['item']['name']. '页 ';

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view('templates/footer', $data);
		} // end detail

		/**
		 * 回收站
		 */
		public function trash()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 检查是否已传入必要参数
			$project_id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;
			if ( empty($project_id) )
				redirect(base_url('project'));

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '回收站',
				'class' => $this->class_name.' '. $this->class_name.'-trash',
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($project_id, 'project', 'project_id');

			// 筛选条件
			$condition['project_id'] = $project_id;
			// 非系统级管理员尽可看到自己企业相关的用户
			if ( ! empty($this->session->biz_id) )
				$condition['biz_id'] = $this->session->biz_id;

			// 排序条件
			$order_by = NULL;

			// Go Basic！
			$this->basic->trash($data, $condition, $order_by);
		} // end trash

		/**
		 * 创建
		 */
		public function create()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 检查是否已传入必要参数
			$id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;
			if ( empty($id) )
				redirect(base_url('error/code_404'));

			// 页面信息
			$data = array(
				'title' => '创建'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-create',
			);

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($id, 'project', 'project_id');

			// 获取页面列表作为“相关页面”备选项
			$data['pages'] = $this->get_pages($data['project']['project_id']);

			// 管理员可获取所有企业、项目信息待选
			if ($this->session->role === '管理员'):
				$this->basic_model->table_name = 'biz';
				$this->basic_model->id_name = 'biz_id';
				$data['bizs'] = $this->basic_model->select(NULL, NULL);

				$this->basic_model->table_name = 'project';
				$this->basic_model->id_name = 'project_id';
				$data['projects'] = $this->basic_model->select(NULL, NULL);

				// 还原数据库相关类属性
				$this->basic_model->table_name = $this->table_name;
				$this->basic_model->id_name = $this->id_name;
			endif;

			// 待验证的表单项
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('biz_id', '所属企业ID', 'trim|is_natural_no_zero|required');
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero|required');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('code', '序号', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('code_class', '类名', 'trim|alpha_dash');
			$this->form_validation->set_rules('code_function', '方法名', 'trim|alpha_dash');
			$this->form_validation->set_rules('private', '是否需登录', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('return_allowed', '是否可返回', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('nav_top', '显示标题栏', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('nav_bottom', '显示导航栏', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('elements', '主要视图元素', 'trim');
			$this->form_validation->set_rules('url_design', '设计图URL', 'trim');
			$this->form_validation->set_rules('url_assets', '美术素材URL', 'trim|valid_url');
			$this->form_validation->set_rules('note_designer', '设计师备注', 'trim');
			$this->form_validation->set_rules('onloads', '载入事件', 'trim');
			$this->form_validation->set_rules('returns', '返回事件', 'trim');
			$this->form_validation->set_rules('events', '业务流程', 'trim');
			$this->form_validation->set_rules('api_ids', '相关API', 'trim');
			$this->form_validation->set_rules('page_ids', '相关页面', 'trim');
			$this->form_validation->set_rules('note_developer', '开发者备注', 'trim');
			$this->form_validation->set_rules('status', '状态', 'trim|required');

			// 需要存入数据库的信息
			$data_to_create = array(
				'biz_id' => $this->input->post('biz_id'),
				'project_id' => $this->input->post('project_id'),
				'category_id' => $this->input->post('category_id'),
				'code' => strtoupper( $this->input->post('code') ),
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'code_class' => $this->input->post('code_class'),
				'code_function' => $this->input->post('code_function'),
				'private' => $this->input->post('private'),
				'return_allowed' => $this->input->post('return_allowed'),
				'nav_top' => $this->input->post('nav_top'),
				'nav_bottom' => $this->input->post('nav_bottom'),
				'elements' => $this->input->post('elements'),
				'url_design' => $this->input->post('url_design'),
				'url_assets' => $this->input->post('url_assets'),
				'note_designer' => $this->input->post('note_designer'),
				'onloads' => $this->input->post('onloads'),
				'returns' => $this->input->post('returns'),
				'events' => $this->input->post('events'),
				'api_ids' => $this->input->post('api_ids'),
				'page_ids' => $this->input->post('page_ids'),
				'note_developer' => $this->input->post('note_developer'),
				'status' => $this->input->post('status'),
			);

			// Go Basic!
			$this->basic->create($data, $data_to_create);
		} // end create

		/**
		 * 编辑单行
		 */
		public function edit()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理', '设计师'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( empty($id) )
				redirect(base_url('error/code_404'));

			// 页面信息
			$data = array(
				'title' => '编辑'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-edit',
			);

			// 获取待编辑信息
			$data['item'] = $this->basic_model->select_by_id($id);

			// 管理员可获取所有企业、项目信息待选
			if ($this->session->role === '管理员'):
				$this->basic_model->table_name = 'biz';
				$this->basic_model->id_name = 'biz_id';
				$data['bizs'] = $this->basic_model->select(NULL, NULL);

				$this->basic_model->table_name = 'project';
				$this->basic_model->id_name = 'project_id';
				$data['projects'] = $this->basic_model->select(NULL, NULL);

				// 还原数据库相关类属性
				$this->basic_model->table_name = $this->table_name;
				$this->basic_model->id_name = $this->id_name;
			endif;

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($data['item'][$this->id_name], 'project', 'project_id');

			// 获取API列表作为“相关API”备选项
			$data['apis'] = $this->get_apis($data['item']['biz_id']);

			// 获取页面列表作为“相关页面”备选项
			$data['pages'] = $this->get_pages($data['item']['project_id']);

			// 待验证的表单项
			if ($this->session->role === '管理员')
				$this->form_validation->set_rules('biz_id', '所属企业', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero|required');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('code', '序号', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('private', '是否需登录', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('return_allowed', '是否可返回', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('nav_top', '显示标题栏', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('nav_bottom', '显示导航栏', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('elements', '视图元素', 'trim');
			$this->form_validation->set_rules('url_design', '设计图URL', 'trim');
			$this->form_validation->set_rules('url_assets', '美术素材URL', 'trim|valid_url');
			$this->form_validation->set_rules('note_designer', '设计师备注', 'trim');
			if ($this->session->role !== '设计师'):
				$this->form_validation->set_rules('code_class', '类名', 'trim|alpha_dash');
				$this->form_validation->set_rules('code_function', '方法名', 'trim|alpha_dash');
				$this->form_validation->set_rules('onloads', '载入事件', 'trim');
				$this->form_validation->set_rules('returns', '返回事件', 'trim');
				$this->form_validation->set_rules('events', '业务流程', 'trim');
				$this->form_validation->set_rules('api_ids', '相关API', 'trim');
				$this->form_validation->set_rules('page_ids', '相关页面', 'trim');
				$this->form_validation->set_rules('note_developer', '开发者备注', 'trim');
				$this->form_validation->set_rules('status', '状态', 'trim|required');
			endif;

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的信息
				$data_to_edit = array(
					'project_id' => $this->input->post('project_id'),
					'category_id' => $this->input->post('category_id'),
					'code' => strtoupper( $this->input->post('code') ),
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description'),
					'private' => $this->input->post('private'),
					'return_allowed' => $this->input->post('return_allowed'),
					'nav_top' => $this->input->post('nav_top'),
					'nav_bottom' => $this->input->post('nav_bottom'),
					'elements' => $this->input->post('elements'),
					'url_design' => $this->input->post('url_design'),
					'url_assets' => $this->input->post('url_assets'),
					'note_designer' => $this->input->post('note_designer'),
				);
				if ($this->session->role !== '设计师'):
					$data_not_for_designer = array(
						'code_class' => $this->input->post('code_class'),
						'code_function' => $this->input->post('code_function'),
						'onloads' => $this->input->post('onloads'),
						'returns' => $this->input->post('returns'),
						'events' => $this->input->post('events'),
						'api_ids' => $this->input->post('api_ids'),
						'page_ids' => $this->input->post('page_ids'),
						'note_developer' => $this->input->post('note_developer'),
						'status' => $this->input->post('status'),
					);
					$data_to_edit = array_merge($data_to_edit, $data_not_for_designer);
				endif;
				
				if ($this->session->role === '管理员'):
					$data_to_edit['biz_id'] = $this->input->post('biz_id');
				else:
					$data_to_edit['biz_id'] = $this->session->biz_id;
				endif;

				$result = $this->basic_model->edit($id, $data_to_edit);
				if ($result !== FALSE):
					$data['content'] = '<p class="alert alert-success">保存成功。</p>';
					$data['operation'] = 'edit';
					$data['id'] = $id;
				else:
					$data['content'] = '<p class="alert alert-warning">保存失败。</p>';
				endif;

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/result', $data);
				$this->load->view('templates/footer', $data);

			endif;
		} // end edit
		
		/**
		 * 克隆单行
		 */
		public function duplicate()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);
			
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id')? $this->input->get_post('id'): NULL;
			if ( empty($id) )
				redirect(base_url('error/code_404'));

			// 页面信息
			$data = array(
				'title' => '克隆'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-edit',
			);

			// 获取待克隆信息
			$data['item'] = $this->basic_model->select_by_id($id);

			// 管理员可获取所有企业、项目信息待选
			if ($this->session->role === '管理员'):
				$this->basic_model->table_name = 'biz';
				$this->basic_model->id_name = 'biz_id';
				$data['bizs'] = $this->basic_model->select(NULL, NULL);

				$this->basic_model->table_name = 'project';
				$this->basic_model->id_name = 'project_id';
				$data['projects'] = $this->basic_model->select(NULL, NULL);

				// 还原数据库相关类属性
				$this->basic_model->table_name = $this->table_name;
				$this->basic_model->id_name = $this->id_name;
			endif;

			// 获取项目数据
			$data['project'] = $this->basic->get_by_id($data['item'][$this->id_name], 'project', 'project_id');

			// 获取API列表作为“相关API”备选项
			$data['apis'] = $this->get_apis($data['item']['biz_id']);

			// 获取页面列表作为“相关页面”备选项
			$data['pages'] = $this->get_pages($data['item']['project_id']);

			// 待验证的表单项
			if ($this->session->role === '管理员')
				$this->form_validation->set_rules('biz_id', '所属企业', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero|required');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('code', '序号', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('private', '是否需登录', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('return_allowed', '是否可返回', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('nav_top', '显示标题栏', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('nav_bottom', '显示导航栏', 'trim|in_list[0,1]');
			$this->form_validation->set_rules('elements', '视图元素', 'trim');
			$this->form_validation->set_rules('url_design', '设计图URL', 'trim');
			$this->form_validation->set_rules('url_assets', '美术素材URL', 'trim|valid_url');
			$this->form_validation->set_rules('note_designer', '设计师备注', 'trim');
			if ($this->session->role !== '设计师'):
				$this->form_validation->set_rules('code_class', '类名', 'trim|alpha_dash');
				$this->form_validation->set_rules('code_function', '方法名', 'trim|alpha_dash');
				$this->form_validation->set_rules('onloads', '载入事件', 'trim');
				$this->form_validation->set_rules('returns', '返回事件', 'trim');
				$this->form_validation->set_rules('events', '业务流程', 'trim');
				$this->form_validation->set_rules('api_ids', '相关API', 'trim');
				$this->form_validation->set_rules('page_ids', '相关页面', 'trim');
				$this->form_validation->set_rules('note_developer', '开发者备注', 'trim');
				$this->form_validation->set_rules('status', '状态', 'trim|required');
			endif;

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$data['error'] = validation_errors();

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/duplicate', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的信息
				$data_to_create = array(
					'project_id' => $this->input->post('project_id'),
					'category_id' => $this->input->post('category_id'),
					'code' => strtoupper( $this->input->post('code') ),
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description'),
					'private' => $this->input->post('private'),
					'return_allowed' => $this->input->post('return_allowed'),
					'nav_top' => $this->input->post('nav_top'),
					'nav_bottom' => $this->input->post('nav_bottom'),
					'elements' => $this->input->post('elements'),
					'url_design' => $this->input->post('url_design'),
					'url_assets' => $this->input->post('url_assets'),
					'note_designer' => $this->input->post('note_designer'),
				);
				if ($this->session->role !== '设计师'):
					$data_not_for_designer = array(
						'code_class' => $this->input->post('code_class'),
						'code_function' => $this->input->post('code_function'),
						'onloads' => $this->input->post('onloads'),
						'returns' => $this->input->post('returns'),
						'events' => $this->input->post('events'),
						'api_ids' => $this->input->post('api_ids'),
						'page_ids' => $this->input->post('page_ids'),
						'note_developer' => $this->input->post('note_developer'),
						'status' => $this->input->post('status'),
					);
					$data_to_create = array_merge($data_to_create, $data_not_for_designer);
				endif;
				
				if ($this->session->role === '管理员'):
					$data_to_create['biz_id'] = $this->input->post('biz_id');
				else:
					$data_to_create['biz_id'] = $this->session->biz_id;
				endif;

				// 向数据库中写入记录
				$result = $this->basic_model->create($data_to_create, TRUE);
				if ($result !== FALSE):
					$data['content'] = '<p class="alert alert-success">克隆成功。</p>';
					$data['operation'] = 'duplicate';
					$data['id'] = $result;
				else:
					$data['content'] = '<p class="alert alert-warning">克隆失败。</p>';
				endif;

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/result', $data);
				$this->load->view('templates/footer', $data);
			endif;
		} // end duplicate

		// 根据企业ID获取API列表
		private function get_apis($biz_id)
		{
			$this->basic_model->table_name = 'api';
			$this->basic_model->id_name = 'api_id';
			
			$condition['biz_id'] = $biz_id;
			$this->db->select('api_id, code, name');
			$this->db->order_by('code', 'ASC');
			$result = $this->basic_model->select($condition);

			// 还原数据库相关类属性
			$this->basic_model->table_name = $this->table_name;
			$this->basic_model->id_name = $this->id_name;
			
			return $result;
		} // end get_apis
		
		// 根据项目ID获取页面列表
		private function get_pages($project_id)
		{
			$this->basic_model->table_name = 'page';
			$this->basic_model->id_name = 'page_id';

			$condition['project_id'] = $project_id;
			$this->db->select('page_id, code, name');
			$this->db->order_by('code', 'ASC');
			$result = $this->basic_model->select($condition);

			// 还原数据库相关类属性
			$this->basic_model->table_name = $this->table_name;
			$this->basic_model->id_name = $this->id_name;
			
			return $result;
		} // end get_pages

		/**
		 * 删除单行或多行项目
		 */
		public function delete()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);
			
			$op_name = '删除'; // 操作的名称
			$op_view = 'delete'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-'. $op_view,
			);
			
			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 待验证的表单项
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			// 需要存入数据库的信息
			$data_to_edit = array(
				'time_delete' => date('Y-m-d H:i:s'), // 批量删除
			);

			// Go Basic!
			$this->basic->bulk($data, $data_to_edit, $op_name, $op_view);
		} // end delete

		/**
		 * 恢复单行或多行项目
		 */
		public function restore()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			$op_name = '恢复'; // 操作的名称
			$op_view = 'restore'; // 视图文件名

			// 页面信息
			$data = array(
				'title' => $op_name. $this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-'. $op_view,
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 待验证的表单项
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			// 需要存入数据库的信息
			$data_to_edit = array(
				'time_delete' => NULL, // 批量恢复
			);

			// Go Basic!
			$this->basic->bulk($data, $data_to_edit, $op_name, $op_view);
		} // end restore
	}

/* End of file Page.php */
/* Location: ./application/controllers/Page.php */
