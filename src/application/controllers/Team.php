<?php
defined('BASEPATH') or exit('此文件不可被直接访问');

/**
 * Team 类
 *
 * 团队相关功能
 *
 * @version 1.0.0
 * @author Kamas 'Iceberg' Lau <kamaslau@dingtalk.com>
 * @copyright ICBG <www.bingshankeji.com>
 */
class Team extends CI_Controller
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

		// （可选）未登录用户转到登录页
		if ($this->session->logged_in !== TRUE) redirect(base_url('login'));

		// 向类属性赋值
		$this->class_name = strtolower(__CLASS__);
		$this->class_name_cn = '团队'; // 改这里……
		$this->table_name = 'team'; // 和这里……
		$this->id_name = 'team_id'; // 还有这里，OK，这就可以了
		$this->view_root = $this->class_name;

		// 设置需要自动在视图文件中生成显示的字段
		$this->data_to_display = array(
			'name' => '名称',
			'description' => '说明',
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
		// 页面信息
		$data = array(
			'title' => $this->class_name_cn . '列表',
			'class' => $this->class_name . ' ' . $this->class_name . '-index',
		);

		// 将需要显示的数据传到视图以备使用
		$data['data_to_display'] = $this->data_to_display;

		// 筛选条件
		$condition = NULL;

		// 非系统级管理员仅可看到自己企业相关的信息，否则可接收传入的参数
		if (!empty($this->session->biz_id)) :
			$condition['biz_id'] = $this->session->biz_id;
		elseif ($this->session->role === '管理员') :
			$condition['biz_id'] = $this->input->get_post('biz_id');
		endif;

		// 排序条件
		$order_by[$this->id_name] = 'ASC';

		// Go Basic！
		$this->basic->index($data, array_filter($condition), $order_by);
	}

	/**
	 * 详情页
	 */
	public function detail()
	{
		// 检查是否已传入必要参数
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : NULL;
		if (empty($id)) :
			$this->basic->error(404, '网址不完整');
			exit;
		endif;

		// 页面信息
		$data = array(
			'title' => NULL,
			'class' => $this->class_name . ' ' . $this->class_name . '-detail',
		);

		// 获取页面数据
		$data['item'] = $this->basic_model->select_by_id($id);

		// 若已指定成员，则获取成员信息
		if (!empty($data['item']['leader_id'])) :
			$data['user'] = $this->basic->get_by_id($data['item']['leader_id'], 'user', 'user_id');
		endif;

		// 若存在相关用户，则获取用户信息
		if (!empty($data['item']['user_ids'])) :
			$data['user'] = $this->basic->get_by_ids($data['item']['user_ids'], 'user', 'user_id');
		endif;

		// 若存在相关项目，则获取项目信息
		if (!empty($data['item']['project_ids'])) :
			$data['projects'] = $this->basic->get_by_ids($data['item']['project_ids'], 'project', 'project_id');
		endif;

		$this->load->view('templates/header', $data);
		$this->load->view($this->view_root . '/detail', $data);
		$this->load->view('templates/footer', $data);
	}

	/**
	 * 回收站
	 *
	 * 一般为后台功能
	 */
	public function trash()
	{
		// 操作可能需要检查操作权限
		$role_allowed = array('管理员', '经理'); // 角色要求
		$min_level = 10; // 级别要求
		$this->basic->permission_check($role_allowed, $min_level);

		// 页面信息
		$data = array(
			'title' => $this->class_name_cn . '回收站',
			'class' => $this->class_name . ' ' . $this->class_name . '-trash',
		);

		// 将需要显示的数据传到视图以备使用
		$data['data_to_display'] = $this->data_to_display;

		// 筛选条件
		$condition = NULL;
		// 非系统级管理员仅可看到自己企业相关的信息
		if (!empty($this->session->biz_id))
			$condition['biz_id'] = $this->session->biz_id;

		// 排序条件
		$order_by = NULL;

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
		// 操作可能需要检查操作权限
		$role_allowed = array('管理员', '经理'); // 角色要求
		$min_level = 10; // 级别要求
		$this->basic->permission_check($role_allowed, $min_level);

		// 页面信息
		$data = array(
			'title' => '创建' . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-create',
		);

		// 待验证的表单项
		// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
		$this->form_validation->set_rules('name', '名称', 'trim|required');
		$this->form_validation->set_rules('description', '说明', 'trim');
		$this->form_validation->set_rules('leader_id', '队长用户ID', 'trim|is_natural_no_zero|required');
		$this->form_validation->set_rules('project_ids', '相关项目ID们', 'trim');
		$this->form_validation->set_rules('user_ids', '相关用户ID们', 'trim');

		// 需要存入数据库的信息
		$data_to_create = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'leader_id' => $this->input->post('leader_id'),
			'project_ids' => $this->input->post('project_ids'),
			'user_ids' => $this->input->post('user_ids'),
		);

		// Go Basic!
		$this->basic->create($data, $data_to_create);
	}

	/**
	 * 编辑单行
	 *
	 * 一般为后台功能
	 */
	public function edit()
	{
		// 操作可能需要检查操作权限
		$role_allowed = array('管理员', '经理'); // 角色要求
		$min_level = 10; // 级别要求
		$this->basic->permission_check($role_allowed, $min_level);

		// 页面信息
		$data = array(
			'title' => '编辑' . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-edit',
		);

		// 待验证的表单项
		$this->form_validation->set_rules('name', '名称', 'trim|required');
		$this->form_validation->set_rules('description', '说明', 'trim');
		$this->form_validation->set_rules('leader_id', '队长用户ID', 'trim|is_natural_no_zero|required');
		$this->form_validation->set_rules('project_ids', '相关项目ID们', 'trim');
		$this->form_validation->set_rules('user_ids', '相关用户ID们', 'trim');

		// 需要编辑的信息
		$data_to_edit = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'leader_id' => $this->input->post('leader_id'),
			'project_ids' => $this->input->post('project_ids'),
			'user_ids' => $this->input->post('user_ids'),
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
		// 操作可能需要检查操作权限
		$role_allowed = array('管理员', '经理'); // 角色要求
		$min_level = 10; // 级别要求
		$this->basic->permission_check($role_allowed, $min_level);

		$op_name = '删除'; // 操作的名称
		$op_view = 'delete'; // 视图文件名

		// 页面信息
		$data = array(
			'title' => $op_name . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-' . $op_view,
		);

		// 将需要显示的数据传到视图以备使用
		$data['data_to_display'] = $this->data_to_display;

		// 待验证的表单项
		$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

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
		// 操作可能需要检查操作权限
		$role_allowed = array('管理员', '经理'); // 角色要求
		$min_level = 10; // 级别要求
		$this->basic->permission_check($role_allowed, $min_level);

		$op_name = '恢复'; // 操作的名称
		$op_view = 'restore'; // 视图文件名

		// 页面信息
		$data = array(
			'title' => $op_name . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-' . $op_view,
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

/* End of file Team.php */
/* Location: ./application/controllers/Team.php */
