<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * User 类
	 *
	 * 用户相关功能
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class User extends CI_Controller
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
            // $this->output->enable_profiler(TRUE);

			// 未登录用户转到登录页
			if ($this->session->logged_in !== TRUE) redirect(base_url('login'));

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '用户'; // 改这里……
			$this->table_name = 'user'; // 和这里……
			$this->id_name = 'user_id'; // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置需要自动在视图文件中生成显示的字段
			$this->data_to_display = array(
				'mobile' => '手机号',
				'lastname' => '姓',
				'firstname' => '名',
				'role' => '角色',
				'level' => '级别',
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
		 * 列表页
		 */
		public function index()
		{
			// 若角色为成员，则转到相应的详情页面
			if ($this->session->role === '成员')
				redirect( base_url('user/detail?id='.$this->session->user_id) );

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '列表',
				'class' => $this->class_name.' '. $this->class_name.'-index',
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 筛选条件
			$condition = NULL;
            // 非系统级管理员仅可看到自己企业相关的信息，否则可接收传入的参数
            if ( ! empty($this->session->biz_id) ):
                $condition['biz_id'] = $this->session->biz_id;
            elseif ($this->session->role === '管理员'):
                $condition['biz_id'] = $this->input->get_post('biz_id');
            endif;

			// 排序条件
			$order_by['biz_id'] = 'DESC';
            $order_by['role'] = 'DESC';
            $order_by['level'] = 'DESC';

			// Go Basic！
			$this->basic->index($data, array_filter($condition), $order_by);
		}

		/**
		 * 详情页
		 */
		public function detail()
		{
			// 检查是否已传入必要参数
			$id = $this->input->get_post('id');
			if ( empty($id) )
				redirect(base_url('error/code_404'));

			// 页面信息
			$data = array(
				'title' => NULL,
				'class' => $this->class_name.' '. $this->class_name.'-detail',
			);

			// 获取页面数据
			$data['item'] = $this->basic_model->select_by_id($id);

			// 若存在相关数据
			if ( !empty($data['item']) ):
                // 若存在所属企业，则获取企业信息
                if ( !empty($data['item']['biz_id']) ):
                    $data['biz'] = $this->basic->get_by_id($data['item']['biz_id'], 'biz', 'biz_id');
                endif;

                // 生成页面标题
                $data['title'] = $data['item']['lastname'].$data['item']['firstname'];
            endif;

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root.'/detail', $data);
			$this->load->view('templates/footer', $data);
		}

        /**
         * 个人主页（详情页）
         */
        public function mine()
        {
            // 检查是否已传入必要参数
            $id = $this->session->user_id;
            if ($id === NULL)
                redirect(base_url('error/code_404'));

            // 页面信息
            $data = array(
                'title' => '我的',
                'class' => $this->class_name.' '. $this->class_name.'-mine',
            );

            // 获取页面数据
            $data['item'] = $this->basic_model->select_by_id($id);

            // 若存在所属企业，则获取企业信息
            if ( !empty($data['item']) && !empty($data['item']['biz_id']) ):
                $data['biz'] = $this->basic->get_by_id($data['item']['biz_id'], 'biz', 'biz_id');
            endif;

            $this->load->view('templates/header', $data);
            $this->load->view($this->view_root.'/mine', $data);
            $this->load->view('templates/footer', $data);
        }

		/**
		 * 回收站
		 */
		public function trash()
		{
			// 操作可能需要检查操作权限
			$role_allowed = array('管理员', '经理'); // 角色要求
			$min_level = 30; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 页面信息
			$data = array(
				'title' => $this->class_name_cn. '回收站',
				'class' => $this->class_name.' '. $this->class_name.'-trash',
			);

			// 将需要显示的数据传到视图以备使用
			$data['data_to_display'] = $this->data_to_display;

			// 筛选条件
			$condition = NULL;
			// 非系统级管理员仅可看到自己企业相关的信息
			if ( ! empty($this->session->biz_id) )
				$condition['biz_id'] = $this->session->biz_id;

			// 排序条件
			$order_by['time_delete'] = 'DESC';

			// Go Basic！
			$this->basic->trash($data, $condition, $order_by);
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

			// 获取所属企业数据
			$biz_id = $this->input->get_post('biz_id')? $this->input->get_post('biz_id'): NULL;
			if ( ! empty($biz_id) )
				$data['biz'] = $this->basic->get_by_id($biz_id, 'biz', 'biz_id');

			// 待验证的表单项
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('biz_id', '所属企业', 'trim|is_natural_no_zero');
			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|is_natural|exact_length[11]');
			$this->form_validation->set_rules('lastname', '姓', 'trim|required');
			$this->form_validation->set_rules('firstname', '名', 'trim|required');
			$this->form_validation->set_rules('gender', '性别', 'trim');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');

			$this->form_validation->set_rules('role', '角色', 'trim');
			$this->form_validation->set_rules('level', '等级', 'trim|is_natural|max_length[2]|less_than_equal_to['.$this->session->level.']');

			// 需要存入数据库的信息
			$data_to_create = array(
				'biz_id' => $this->input->post('biz_id'),
				'mobile' => $this->input->post('mobile'),
				'password' => SHA1( substr($this->input->post('mobile'), -6) ),
				'lastname' => $this->input->post('lastname'),
				'firstname' => $this->input->post('firstname'),
				'gender' => $this->input->post('gender'),
				'email' => $this->input->post('email'),
				'role' => $this->input->post('role'),
				'level' => $this->input->post('level'),
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
			$role_allowed = array('管理员', '经理', '成员', '设计师', '工程师'); // 角色要求
			$min_level = 10; // 级别要求
			$this->basic->permission_check($role_allowed, $min_level);

			// 非管理员、经理角色的用户仅可修改自己的信息
			if ( !in_array($this->session->role, array('管理员', '经理')) && $this->session->user_id !== $this->input->get_post('id'))
				redirect(base_url('error/permission_role'));

			// 页面信息
			$data = array(
				'title' => '编辑'.$this->class_name_cn,
				'class' => $this->class_name.' '. $this->class_name.'-edit',
			);

			// 待验证的表单项
			// "成员"角色的用户仅可修改部分信息
			if ($this->session->role === '管理员' || $this->session->role === '经理'):
				$this->form_validation->set_rules('mobile', '手机号', 'trim|required|is_natural|exact_length[11]');
				$this->form_validation->set_rules('lastname', '姓', 'trim|required');
				$this->form_validation->set_rules('firstname', '名', 'trim|required');
				$this->form_validation->set_rules('role', '角色', 'trim');

				// 不可授予他人比自己高的等级
				if ($this->session->user_id !== $this->input->get_post('id')):
					$max_level = $this->session->level - 1;
				else:
					$max_level = $this->session->level;
				endif;

				$this->form_validation->set_rules('level', '等级', 'trim|is_natural|max_length[2]|less_than_equal_to['.$max_level.']');
			endif;
			$this->form_validation->set_rules('nickname', '昵称', 'trim');
			$this->form_validation->set_rules('gender', '性别', 'trim');
			$this->form_validation->set_rules('dob', '生日（公历）', 'trim');
			$this->form_validation->set_rules('avatar', '头像URL', 'trim|valid_url');
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');

			// 对生日做特别处理
			$dob = $this->input->post('dob');
			if ($dob === '0000-00-00' || empty($dob)) $dob = NULL;
			
			// 需要编辑的信息
			$data_to_edit = array(
				'nickname' => $this->input->post('nickname'),
				'gender' => $this->input->post('gender'),
				'dob' => $dob,
				'avatar' => $this->input->post('avatar'),
				'email' => $this->input->post('email'),
			);

			if ($this->session->role === '管理员' || $this->session->role === '经理'):
				$data_to_edit['mobile'] = $this->input->post('mobile');
				$data_to_edit['lastname'] = $this->input->post('lastname');
				$data_to_edit['firstname'] = $this->input->post('firstname');
				$data_to_edit['role'] = $this->input->post('role');
				$data_to_edit['level'] = $this->input->post('level');
			endif;

			// Go Basic!
			$this->basic->edit($data, $data_to_edit);
		}

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
		}

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
		}
	}

/* End of file User.php */
/* Location: ./application/controllers/User.php */
