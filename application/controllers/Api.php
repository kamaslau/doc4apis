<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Api 类
	 *
	 * API相关功能
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Api extends CI_Controller
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
			$this->class_name_cn = 'API'; // 改这里……
			$this->table_name = 'api'; // 和这里……
			$this->id_name = 'api_id'; // 还有这里，OK，这就可以了
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
			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '列表',
				'class' => $this->class_name.' '. $this->class_name.'-index',
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 获取项目数据
			$project_id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;
			if ( !empty($project_id) )
				$data['project'] = $this->basic->get_by_id($project_id, 'project', 'project_id');

			// 筛选条件
			$condition = NULL;

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
			$order_by['code'] = 'ASC'; // 按API序号字母顺序进行排序

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

			// 获取企业信息
			if ( !empty($data['item']['biz_id']) ):
				$data['biz'] = $this->basic->get_by_id($data['item']['biz_id'], 'biz', 'biz_id');
			endif;

			// 获取项目数据
			if ( !empty($data['item']['project_id']) ):
				$data['project'] = $this->basic->get_by_id($data['item']['project_id'], 'project', 'project_id');
			endif;

			// 生成最终页面标题
			$data['title'] = '['.$data['item']['code'].']'. $data['item']['name'];

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
			if ( empty($project_id) ) redirect(base_url('project'));

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
			// 非系统级管理员仅可看到自己企业相关的信息
			if ( ! empty($this->session->biz_id) )
				$condition['biz_id'] = $this->session->biz_id;
			
			// 排序条件
			$order_by['time_delete'] = 'DESC';
			
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

			// 页面信息
			$data = array(
				'title' => '创建'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-create',
			);
			
			// 获取项目数据
			$project_id = $this->input->get_post('project_id')? $this->input->get_post('project_id'): NULL;
			if ( !empty($project_id) ):
				$data['project'] = $this->basic->get_by_id($project_id, 'project', 'project_id');
			endif;

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
			$this->form_validation->set_rules('biz_id', '所属项目ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('code', '序号', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('url', 'URL', 'trim');
			$this->form_validation->set_rules('url_full', '第三方URL', 'trim|valid_url');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('params_request', '请求参数', 'trim');
			$this->form_validation->set_rules('params_respond', '响应参数', 'trim');
			$this->form_validation->set_rules('sample_request', '请求示例', 'trim');
			$this->form_validation->set_rules('sample_respond', '响应示例', 'trim');
			$this->form_validation->set_rules('status', '状态', 'trim|required');

			// 需要存入数据库的信息
			$data_to_create = array(
				'project_id' => $this->input->post('project_id'),
				'category_id' => $this->input->post('category_id'),
				'name' => ucwords( $this->input->post('name') ),
				'code' => strtoupper( $this->input->post('code') ),
				'url' => $this->input->post('url'),
				'url_full' => $this->input->post('url_full'),
				'description' => $this->input->post('description'),
				'params_request' => $this->input->post('params_request'),
				'params_respond' => $this->input->post('params_respond'),
				'sample_request' => $this->input->post('sample_request'),
				'sample_respond' => $this->input->post('sample_respond'),
				'status' => $this->input->post('status'),
			);
			// 非系统管理员的用户，企业ID默认为当前用户所属企业ID
			if ($this->session->role !== '管理员'):
				$data_to_create['biz_id'] = $this->session->biz_id;
			else:
				$data_to_create['biz_id'] = $this->input->post('biz_id');
			endif;

			// Go Basic!
			$this->basic->create($data, $data_to_create);
		} // end create

		/**
		 * 编辑单行
		 */
		public function edit()
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

			// 待验证的表单项
			if ($this->session->role === '管理员')
				$this->form_validation->set_rules('biz_id', '所属企业', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('code', '序号', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('url', 'URL', 'trim');
			$this->form_validation->set_rules('url_full', '第三方URL', 'trim|valid_url');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('params_request', '请求参数', 'trim');
			$this->form_validation->set_rules('params_respond', '相应参数', 'trim');
			$this->form_validation->set_rules('sample_request', '请求示例', 'trim');
			$this->form_validation->set_rules('sample_respond', '响应示例', 'trim');
			$this->form_validation->set_rules('status', '状态', 'trim|required');

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/edit', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的信息
				$data_to_edit = array(
					'project_id' => $this->input->post('project_id'),
					'category_id' => $this->input->post('category_id'),
					'name' => ucwords( $this->input->post('name') ),
					'code' => strtoupper( $this->input->post('code') ),
					'url' => $this->input->post('url'),
					'url_full' => $this->input->post('url_full'),
					'description' => $this->input->post('description'),
					'params_request' => $this->input->post('params_request'),
					'params_respond' => $this->input->post('params_respond'),
					'sample_request' => $this->input->post('sample_request'),
					'sample_respond' => $this->input->post('sample_respond'),
					'status' => $this->input->post('status'),
				);
				if ($this->session->role === '管理员'):
					$data_to_edit['biz_id'] = $this->input->post('biz_id');
				else:
					$data_to_edit['biz_id'] = $this->session->biz_id;
				endif;

				$result = $this->basic_model->edit($id, $data_to_edit);

				if ($result !== FALSE):
					$data['content'] = '<p class="alert alert-success">保存成功。</p>';
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

			// 待验证的表单项
			if ($this->session->role === '管理员')
				$this->form_validation->set_rules('biz_id', '所属企业', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('category_id', '所属分类ID', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('name', '名称', 'trim|required');
			$this->form_validation->set_rules('code', '序号', 'trim|alpha_numeric|required');
			$this->form_validation->set_rules('url', 'URL', 'trim');
			$this->form_validation->set_rules('url_full', '第三方URL', 'trim|valid_url');
			$this->form_validation->set_rules('description', '说明', 'trim');
			$this->form_validation->set_rules('params_request', '请求参数', 'trim');
			$this->form_validation->set_rules('params_respond', '相应参数', 'trim');
			$this->form_validation->set_rules('sample_request', '请求示例', 'trim');
			$this->form_validation->set_rules('sample_respond', '响应示例', 'trim');
			$this->form_validation->set_rules('status', '状态', 'trim|required');

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/duplicate', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的信息
				$data_to_create = array(
					'project_id' => $this->input->post('project_id'),
					'category_id' => $this->input->post('category_id'),
					'name' => $this->input->post('name'),
					'code' => strtoupper($this->input->post('code')),
					'url' => $this->input->post('url'),
					'url_full' => $this->input->post('url_full'),
					'description' => $this->input->post('description'),
					'params_request' => $this->input->post('params_request'),
					'params_respond' => $this->input->post('params_respond'),
					'sample_request' => $this->input->post('sample_request'),
					'sample_respond' => $this->input->post('sample_respond'),
					'status' => $this->input->post('status'),
				);
				if ($this->session->role === '管理员'):
					$data_to_create['biz_id'] = $this->input->post('biz_id');
				else:
					$data_to_create['biz_id'] = $this->session->biz_id;
				endif;

				// 向数据库中写入记录
				$result = $this->basic_model->create($data_to_create);
				if ($result !== FALSE):
					$data['content'] = '<p class="alert alert-success">克隆成功。</p>';
				else:
					$data['content'] = '<p class="alert alert-warning">克隆失败。</p>';
				endif;

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/result', $data);
				$this->load->view('templates/footer', $data);
			endif;
		} // end duplicate

		/**
		 * 删除单行或多行项目
		 *
		 * 一般用于存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如delete、restore、draft等
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
		 *
		 * 一般用于存为草稿、上架、下架、删除、恢复等状态变化，请根据需要修改方法名，例如delete、restore、draft等
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

/* End of file Api.php */
/* Location: ./application/controllers/Api.php */
