<?php
  defined('BASEPATH') OR exit('此文件不可被直接访问');

  /**
   * [[class_name]]/[[code]] [[class_name_cn]]类
   *
   * 以API服务形式返回数据列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的示例代码
   * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
   *
   * @version 1.0.0
   * @author Kamas 'Iceberg' Lau <kamaslau@dingtalk.com>
   * @copyright Kamas 'Iceberg' Lau <kamaslau@dingtalk.com>
   */
  class [[class_name]] extends MY_Controller
	{
    protected $names_csv = '[[names_csv]]'; // 类相关表所有字段

    // 排序字段；详见父类同名属性
    protected $sorter = array(
      '[[id_name]]',
      [[names_list]] 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
    );

    // 筛选字段；详见父类同名属性
    protected $filter = array(
      // 精准筛选
      'exact' => array(
        '[[id_name]]',
        [[names_list]] 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
      ),

      // 范围筛选
      'range' => array(
        '[[id_name]]',
        [[names_list]] 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
      ),
    );

    /**
     * 可作为查询结果返回的字段名
     *
     * 应删除time_create等需在MY_Controller通过names_return_for_admin等类属性声明的字段名
     */
    protected $names_to_return = array(
      '[[id_name]]',
      [[names_list]] 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
     * 创建时必要的字段名
     */
		protected $names_create_required = array(
      'operator_id',
      [[names_list]]
    );

		/**
     * 可被编辑的字段名
     */
		protected $names_edit_allowed = array(
      [[names_list]]
    );

		/**
     * 完整编辑单行时必要的字段名
     */
		protected $names_edit_required = array(
      'operator_id', 'id',
      [[names_list]]
    );

		/**
     * 编辑单行特定字段时必要的字段名；若与MY_Controller声明的同名类属性相同，可删除此处
     */
//		protected $names_edit_certain_required = array(
//			'operator_id', 'id',
//          'name', 'value',
//		);

		/**
     * 编辑多行特定字段时必要的字段名；若与MY_Controller声明的同名类属性相同，可删除此处
     */
		protected $names_edit_bulk_required = array(
      'operator_id', 'ids',
      'operation', 'password',
    );

		public function __construct()
    {
      parent::__construct();

      // $this->token_check(); // （可选）全局验证JWT

      // 设置主要数据库信息
      $this->table_name = '[[table_name]]'; // 这里……
      $this->id_name = '[[id_name]]'; // 这里……

      // 主要数据库信息到基础模型类
      $this->basic_model->table_name = $this->table_name;
      $this->basic_model->id_name = $this->id_name;

      // $this->output->enable_profiler(TRUE); // 输出调试信息
    } // end __construct

		/**
     * 0 计数
     */
    public function count()
    {
      // 生成筛选条件
      $filter = $this->filter_basic();
      $filter = $this->filter_advanced($filter); // 类特有筛选项
  
      // 商家仅可操作自己的数据
      // if ($this->app_type === 'biz') $filter['biz_id'] = $this->post_input('biz_id');
  
      // 获取列表；默认可获取已删除项
      $count = $this->basic_model->count($filter);
  
      if ($count !== FALSE) :
        $this->result['status'] = 200;
        $this->result['content']['count'] = $count;
  
      else :
        $this->result['status'] = 404;
        $this->result['content']['error']['message'] = '没有符合条件的数据';
  
      endif;
    } // end count

		/**
     * 1 列表/基本搜索
     */
		public function index()
    {
      // 检查必要参数是否已传入
      $required_params = array();
      foreach ($required_params as $param):
        ${$param} = $this->post_input($param);
        if (!isset(${$param})):
          $this->result['status'] = 400;
          $this->result['content']['error']['message'] = '必要的请求参数未全部传入';
          exit();
        endif;
      endforeach;

      // 生成筛选条件
      $filter = $this->filter_basic();
      $filter = $this->filter_advanced($filter); // 类特有筛选项

      // 生成翻页参数
      $this->basic_model->limit = $this->post_input('limit');
      $this->basic_model->offset = $this->post_input('offset');
      $this->basic_model->since_id = $this->post_input('since_id'); // 起始主键值

      // 生成排序条件
      $sorter = array();
      $sorter = $this->sorter_basic($sorter);

      // 限制可返回的字段
      if ($this->app_type === 'client') :
        $filter['time_delete'] = 'NULL'; // 客户端仅可查看未删除项
      elseif ($this->app_type === 'biz') :
        // 商家仅可操作自己的数据
        $filter['biz_id'] = $this->post_input('biz_id');  
      else :
        if (!isset($filter['time_delete'])) $filter['time_delete'] = 'NULL'; // 默认仅获取未删除项
        $this->names_to_return = array_merge($this->names_to_return, $this->names_return_for_admin);
      endif;
      $this->db->select(implode(',', $this->names_to_return));

      // 获取列表
      $ids = $this->post_input('ids'); // 可以CSV格式指定需要获取的信息ID们
      if (empty($ids)) :
        $items = $this->basic_model->select($filter, $sorter);
      else :
        // 限制可返回的字段
        $this->db->select(implode(',', $this->names_to_return));
        $items = $this->basic_model->select_by_ids($ids);
      endif;

      if (!empty($items)) :
        $this->result['status'] = 200;
        $this->result['content'] = $items['data'];
        $this->result['total_count'] = $items['count'];

      else :
        $this->result['status'] = 404;
        $this->result['content']['error']['message'] = '没有符合条件的数据';
        
      endif;
    } // end index

		/**
     * 2 详情
     */
		public function detail()
    {
      // 检查必要参数是否已传入
      $id = $this->post_input('id');
      if (!isset($id)):
        $this->result['status'] = 400;
        $this->result['content']['error']['message'] = '必要的请求参数未传入';
        exit();
      endif;

      if ($this->app_type === 'client') $filter['time_delete'] = 'NULL';

      // 限制可返回的字段
      $this->db->select(implode(',', $this->names_to_return));

      // 获取特定项；默认可获取已删除项
      $item = $this->basic_model->select_by_id($id);
      if (!empty($item)):
        $this->result['status'] = 200;
        $this->result['content'] = $item;

      else:
        $this->result['status'] = 404;
        $this->result['content']['error']['message'] = '没有符合条件的数据';

      endif;
    } // end detail

		/**
     * 3 创建
     */
		public function create()
    {
      // $this->token_check(); // （可选）验证JWT

      // 操作可能需要检查客户端及设备信息
      $type_allowed = array('admin', 'biz', 'client'); // 客户端类型
      $platform_allowed = array('ios', 'android', 'weapp', 'web'); // 客户端平台
      $min_version = '0.0.1'; // 最低版本要求
      $this->client_check($type_allowed, $platform_allowed, $min_version);

      // 管理类客户端操作可能需要检查操作权限
      //$role_allowed = array('管理员', '经理'); // 角色要求
      //$min_level = 10; // 级别要求
      //$this->permission_check($role_allowed, $min_level);

      // 检查必要参数是否已传入
      $required_params = $this->names_create_required;
      foreach ($required_params as $param):
        ${$param} = $this->post_input($param);
        if (empty(${$param})):
          $this->result['status'] = 400;
          $this->result['content']['error']['message'] = '必要的请求参数未全部传入';
          exit();
        endif;
      endforeach;

      // 初始化并配置表单验证库
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('', '')->set_data($this->post_input); // 待验证数据
      // 验证规则 https://www.codeigniter.com/userguide3/libraries/form_validation.html#rule-reference
      [[rules]]
      // 若表单提交不成功
      if ($this->form_validation->run() === FALSE):
        $this->result['status'] = 401;
        $this->result['content']['error']['message'] = validation_errors();

      else:
        // 需要创建的数据；逐一赋值需特别处理的字段
        $data_to_create = array(
          'creator_id' => $operator_id,

          //'name' => empty($this->post_input('name'))? NULL: $this->post_input('name'),
        );
        // 自动生成无需特别处理的数据
        $data_need_no_prepare = array(
          [[names_list]]
        );
        foreach ($data_need_no_prepare as $name)
          $data_to_create[$name] = $this->post_input($name);

        // 尝试创建
        try {
          $result = $this->basic_model->create(array_filter($data_to_create), TRUE);
        } catch (Exception $error) {
          $result = FALSE;
        }
        if ($result === FALSE):
          $this->result['status'] = 424;
          $this->result['content']['error']['message'] = '创建失败';

        else:
          $this->result['status'] = 200;
          $this->result['content']['id'] = $result;
          $this->result['content']['message'] = '创建成功';

        endif;
      endif;
    } // end create

		/**
     * 4 编辑单行数据
     */
		public function edit()
    {
      // $this->token_check(); // （可选）验证JWT

      // 操作可能需要检查客户端及设备信息
      $type_allowed = array('admin', 'biz', 'client'); // 客户端类型
      $platform_allowed = array('ios', 'android', 'weapp', 'web'); // 客户端平台
      $min_version = '0.0.1'; // 最低版本要求
      $this->client_check($type_allowed, $platform_allowed, $min_version);

      // 管理类客户端操作可能需要检查操作权限
      //$role_allowed = array('管理员', '经理'); // 角色要求
      //$min_level = 10; // 级别要求
      //$this->permission_check($role_allowed, $min_level);

      // 检查必要参数是否已传入
      $required_params = $this->names_edit_required;
      foreach ($required_params as $param):
        ${$param} = $this->post_input($param);
        if (empty(${$param})):
          $this->result['status'] = 400;
          $this->result['content']['error']['message'] = '必要的请求参数未全部传入';
          exit();
        endif;
      endforeach;

      // 初始化并配置表单验证库
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('', '')->set_data($this->post_input); // 待验证数据
      [[rules]]
      // 针对特定条件的验证规则
      if ($this->app_type === 'admin'):
        // ...
      endif;

      // 若表单提交不成功
      if ($this->form_validation->run() === FALSE):
        $this->result['status'] = 401;
        $this->result['content']['error']['message'] = validation_errors();

      else:
        // 需要编辑的数据；逐一赋值需特别处理的字段
        $data_to_edit = array(
          'operator_id' => $operator_id,

          //'name' => empty($this->post_input('name'))? NULL: $this->post_input('name'),
        );
        // 自动生成无需特别处理的数据
        $data_need_no_prepare = array(
          [[names_list]]
        );
        foreach ($data_need_no_prepare as $name)
          $data_to_edit[$name] = $this->post_input($name);

        // 根据客户端类型等条件筛选可操作的字段名
        if ($this->app_type !== 'admin'):
          //unset($data_to_edit['name']);
        endif;

        // 尝试修改
        try {
          $result = $this->basic_model->edit($id, $data_to_edit);
        } catch (Exception $error) {
          $result = FALSE;
        }
        if ($result === FALSE):
          $this->result['status'] = 434;
          $this->result['content']['error']['message'] = '修改失败';
          exit();

        else:
          $this->result['status'] = 200;
          $this->result['content']['message'] = '修改成功';

        endif;

      endif;
    } // end edit
		
		/**
     * 5 编辑单行数据特定字段
     *
     * 修改单行数据的单一字段值
     */
		public function edit_certain()
    {
      // （可选）验证JWT
      // $this->token_check();

      // 操作可能需要检查客户端及设备信息
      $type_allowed = array('admin', 'biz', 'client'); // 客户端类型
      $platform_allowed = array('ios', 'android', 'weapp', 'web'); // 客户端平台
      $min_version = '0.0.1'; // 最低版本要求
      $this->client_check($type_allowed, $platform_allowed, $min_version);

      // 管理类客户端操作可能需要检查操作权限
      $role_allowed = array('管理员', '经理'); // 角色要求
      $min_level = 10; // 级别要求
      $this->permission_check($role_allowed, $min_level);

      // 检查必要参数是否已传入
      $required_params = $this->names_edit_certain_required;
      foreach ($required_params as $param):
        ${$param} = $this->post_input($param);

        // value 可以为空；必要字段会在字段验证中另行检查
        if ($param !== 'value' && !isset(${$param})):
          $this->result['status'] = 400;
          $this->result['content']['error']['message'] = '必要的请求参数未全部传入';
          exit();
        endif;
      endforeach;

      // 检查目标字段是否可编辑
      if (!in_array($name, $this->names_edit_allowed)):
        $this->result['status'] = 431;
        $this->result['content']['error']['message'] = '该字段不可被修改';
        exit();
      endif;

      // 根据客户端类型检查是否可编辑
      /*
      $names_limited = array(
        'biz' => array('name1', 'name2', 'name3', 'name4'),
        'admin' => array('name1', 'name2', 'name3', 'name4'),
      );
      if ( in_array($name, $names_limited[$this->app_type]) ):
        $this->result['status'] = 432;
        $this->result['content']['error']['message'] = '该字段不可被当前类型的客户端修改';
        exit();
      endif;
      */

      // 初始化并配置表单验证库
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('', '')->set_data($this->post_input); // 待验证数据
      // 动态设置待验证字段名及字段值
      $data_to_validate["{$name}"] = $value;
      $this->form_validation->set_data($data_to_validate);
      [[rules]]
      // 若表单提交不成功
      if ($this->form_validation->run() === FALSE):
        $this->result['status'] = 401;
        $this->result['content']['error']['message'] = validation_errors();

      else:
        // 需要编辑的数据
        $data_to_edit['operator_id'] = $operator_id;
        $data_to_edit[$name] = $value;

        // 获取ID
        $result = $this->basic_model->edit($id, $data_to_edit);

        if ($result !== FALSE):
          $this->result['status'] = 200;
          $this->result['content']['message'] = '编辑成功';

        else:
          $this->result['status'] = 434;
          $this->result['content']['error']['message'] = '编辑失败';

        endif;
      endif;
    } // end edit_certain

    /**
     * 6 编辑多行数据特定字段
     *
     * 修改多行数据的单一字段值
     */
    public function edit_bulk()
    {
      // $this->token_check(); // （可选）验证JWT

      // 操作可能需要检查客户端及设备信息
      $type_allowed = array('admin', 'biz', 'client'); // 客户端类型
      $platform_allowed = array('ios', 'android', 'weapp', 'web'); // 客户端平台
      $min_version = '0.0.1'; // 最低版本要求
      $this->client_check($type_allowed, $platform_allowed, $min_version);

      // 管理类客户端操作可能需要检查操作权限
      $role_allowed = array('管理员', '经理'); // 角色要求
      $min_level = 10; // 级别要求
      $this->permission_check($role_allowed, $min_level);

      //$this->password_check(); // （可选）检查密码正确性

      // 检查必要参数是否已传入
      $required_params = $this->names_edit_bulk_required;
      foreach ($required_params as $param):
        ${$param} = $this->post_input($param);
        if (empty(${$param})):
          $this->result['status'] = 400;
          $this->result['content']['error']['message'] = '必要的请求参数未全部传入';
          exit();
        endif;
      endforeach;

      // 此类型方法通用代码块
      $this->common_edit_bulk(FALSE); // 默认不核对密码

      // 验证表单值格式
      if ($this->form_validation->run() === FALSE):
        $this->result['status'] = 401;
        $this->result['content']['error']['message'] = validation_errors();

      else:
        // 需要编辑的数据；逐一赋值需特别处理的字段
        $data_to_edit['operator_id'] = $operator_id;

        // 根据待执行的操作赋值待编辑数据
        switch ($operation):
          case 'delete':
            $data_to_edit['time_delete'] = time();
            break;
          case 'restore':
            $data_to_edit['time_delete'] = NULL;
            break;
        endswitch;

        // 依次操作数据并输出操作结果
        // 将待操作行ID们的CSV格式字符串，转换为待操作行的ID数组
        $ids = explode(',', $ids);

        // 默认批量处理全部成功，若有任一处理失败则将处理失败行进行记录
        $this->result['status'] = 200;
        foreach ($ids as $id):
          $result = $this->basic_model->edit($id, $data_to_edit);
          if ($result === FALSE):
            $this->result['status'] = 434;
            $this->result['content']['row_failed'][] = $id;
          endif;
        endforeach;

        // 添加全部操作成功后的提示
        if ($this->result['status'] = 200)
          $this->result['content']['message'] = '全部操作成功';

      endif;
    } // end edit_bulk
		[[extra_functions]]

		/**
     * 以下为工具类方法
     */

    /**
     * 类特有筛选器
     *
     * @param array $filter 当前筛选条件数组
     * @return array 生成的筛选条件数组
     */
    protected function filter_advanced(array $filter = array()): array
    {
      // TODO 类特有筛选器（若有）逻辑
      return $filter;
    } // end filter_advanced

	} // end class [[class_name]]

/* End of file [[class_name]].php */
/* Location: ./application/controllers/[[class_name]].php */
