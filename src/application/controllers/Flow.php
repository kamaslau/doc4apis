<?php
defined('BASEPATH') or exit('此文件不可被直接访问');

/**
 * Flow 类
 *
 * 流程相关功能
 *
 * @version 1.0.0
 * @author Kamas 'Iceberg' Lau <kamaslau@dingtalk.com>
 * @copyright ICBG <www.bingshankeji.com>
 */
class Flow extends CI_Controller
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
		$this->class_name_cn = '流程'; // 改这里……
		$this->table_name = 'flow'; // 和这里……
		$this->id_name = 'flow_id'; // 还有这里，OK，这就可以了
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
		$project_id = $this->input->get_post('project_id') ? $this->input->get_post('project_id') : NULL;
		if (empty($project_id)) redirect(base_url('project'));

		// 页面信息
		$data = array(
			'title' => $this->class_name_cn . '列表',
			'class' => $this->class_name . ' ' . $this->class_name . '-index',
		);

		// 将需要显示的数据传到视图以备使用
		$data['data_to_display'] = $this->data_to_display;

		// 获取项目数据
		$data['project'] = $this->basic->get_by_id($project_id, 'project', 'project_id');

		// 筛选条件
		$condition['project_id'] = $project_id;
		// 非系统级管理员尽可看到自己企业相关的用户
		if (!empty($this->session->biz_id))
			$condition['biz_id'] = $this->session->biz_id;

		// 排序条件
		$order_by[$this->id_name] = 'ASC';

		// Go Basic！
		$this->basic_model->table_name = 'flow';
		$this->basic_model->id_name = 'flow_id';
		$this->basic->index($data, $condition, $order_by);
	} // end index

	/**
	 * 详情页
	 */
	public function detail()
	{
		// 检查是否已传入必要参数
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : NULL;
		if (empty($id))
			redirect(base_url('error/code_404'));

		// 页面信息
		$data = array(
			'title' => NULL,
			'class' => $this->class_name . ' ' . $this->class_name . '-detail',
		);

		// 获取页面数据
		$data['item'] = $this->basic_model->select_by_id($id);

		// 若存在相关页面，则获取页面信息
		if (!empty($data['item']['page_ids'])) :
			$data['pages'] = $this->basic->get_by_ids($data['item']['page_ids'], 'page', 'page_id');
		endif;

		// 获取项目数据
		$this->basic_model->table_name = 'project';
		$this->basic_model->id_name = 'project_id';
		$data['project'] = $this->basic_model->select_by_id($data['item']['project_id']);

		$this->load->view('templates/header', $data);
		$this->load->view($this->view_root . '/detail', $data);
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

		// 对于非系统级管理员之外的角色，需检查是否已传入必要参数
		$project_id = $this->input->get_post('project_id') ? $this->input->get_post('project_id') : NULL;
		if (empty($project_id) && $this->session->role !== '管理员') redirect(base_url('project'));

		// 页面信息
		$data = array(
			'title' => $this->class_name_cn . '回收站',
			'class' => $this->class_name . ' ' . $this->class_name . '-trash',
		);

		// 将需要显示的数据传到视图以备使用
		$data['data_to_display'] = $this->data_to_display;

		// 获取项目数据
		$data['project'] = $this->basic->get_by_id($project_id, 'project', 'project_id');

		// 筛选条件
		$condition['project_id'] = $project_id;
		// 非系统级管理员尽可看到自己企业相关的用户
		if (!empty($this->session->biz_id))
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
		$id = $this->input->get_post('project_id') ? $this->input->get_post('project_id') : NULL;
		if (empty($id))
			redirect(base_url('error/code_404'));

		// 页面信息
		$data = array(
			'title' => '创建' . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-create',
		);

		// 获取项目数据
		$data['project'] = $this->basic->get_by_id($id, 'project', 'project_id');

		// 获取页面列表作为“相关页面”备选项
		$data['pages'] = $this->get_pages($data['project']['project_id']);

		// 待验证的表单项
		// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
		$this->form_validation->set_rules('project_id', '所属项目ID', 'trim|is_natural_no_zero|required');
		$this->form_validation->set_rules('name', '名称', 'trim|required');
		$this->form_validation->set_rules('description', '说明', 'trim|required');
		$this->form_validation->set_rules('page_ids', '相关页面', 'trim');
		$this->form_validation->set_rules('status', '状态', 'trim|required');

		// 需要存入数据库的信息
		$data_to_create = array(
			'project_id' => $this->input->post('project_id'),
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'page_ids' => $this->input->post('page_ids'),
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
		$role_allowed = array('管理员', '经理'); // 角色要求
		$min_level = 30; // 级别要求
		$this->basic->permission_check($role_allowed, $min_level);

		// 检查是否已传入必要参数
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : NULL;
		if (empty($id))
			redirect(base_url('error/code_404'));

		// 页面信息
		$data = array(
			'title' => '编辑' . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-edit',
		);

		// 获取待编辑信息
		$data['item'] = $this->basic_model->select_by_id($id);

		// 获取项目数据
		$data['project'] = $this->basic->get_by_id($data['item'][$this->id_name], 'project', 'project_id');

		// 获取页面列表作为“相关页面”备选项
		$data['pages'] = $this->get_pages($data['item']['project_id']);

		// 待验证的表单项
		$this->form_validation->set_rules('name', '名称', 'trim|required');
		$this->form_validation->set_rules('description', '说明', 'trim|required');
		$this->form_validation->set_rules('page_ids', '相关页面', 'trim');
		$this->form_validation->set_rules('status', '状态', 'trim|required');

		// 验证表单值格式
		if ($this->form_validation->run() === FALSE) :
			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root . '/edit', $data);
			$this->load->view('templates/footer', $data);

		else :
			// 需要编辑的信息
			$data_to_edit = array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'page_ids' => $this->input->post('page_ids'),
				'status' => $this->input->post('status'),
			);
			$result = $this->basic_model->edit($id, $data_to_edit);

			if ($result !== FALSE) :
				$data['content'] = '<p class="alert alert-success">保存成功。</p>';
			else :
				$data['content'] = '<p class="alert alert-warning">保存失败。</p>';
			endif;

			$this->load->view('templates/header', $data);
			$this->load->view($this->view_root . '/result', $data);
			$this->load->view('templates/footer', $data);

		endif;
	} // end edit

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
			'title' => $op_name . $this->class_name_cn,
			'class' => $this->class_name . ' ' . $this->class_name . '-' . $op_view,
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
	} // end restore
}

/* End of file Flow.php */
/* Location: ./application/controllers/Flow.php */
