<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Biz 商家类
	 *
	 * 以API服务形式返回数据列表、详情、创建、单行编辑、单/多行编辑（删除、恢复）等功能提供了常见功能的示例代码
	 * CodeIgniter官方网站 https://www.codeigniter.com/user_guide/
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Biz extends MY_Controller
	{
		/**
		 * 可作为列表筛选条件的字段名；可在具体方法中根据需要删除不需要的字段并转换为字符串进行应用，下同
		 */
		protected $names_to_sort = array(
			'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
			'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id',
		);

		/**
		 * 可作为查询结果返回的字段名
		 */
		protected $names_to_return = array(
			'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
			'creator_id', 'operator_id',
		);

		/**
		 * 创建时必要的字段名
		 */
		protected $names_create_required = array(
			'user_id',
			'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
		);

		/**
		 * 可被编辑的字段名
		 */
		protected $names_edit_allowed = array(
			'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
		);

		/**
		 * 完整编辑单行时必要的字段名
		 */
		protected $names_edit_required = array(
			'user_id', 'id',
			'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
		);

		/**
		 * 编辑单行特定字段时必要的字段名
		 */
		protected $names_edit_certain_required = array(
			'user_id', 'id',
			'name', 'value',
		);

		/**
		 * 编辑多行特定字段时必要的字段名
		 */
		protected $names_edit_bulk_required = array(
			'user_id', 'ids',
			'operation', 'password',
		);

		public function __construct()
		{
			parent::__construct();

			// 设置主要数据库信息
			$this->table_name = 'biz'; // 这里……
			$this->id_name = 'biz_id'; // 这里……
			$this->names_to_return[] = 'biz_id'; // 还有这里，OK，这就可以了

			// 主要数据库信息到基础模型类
			$this->basic_model->table_name = $this->table_name;
			$this->basic_model->id_name = $this->id_name;

			// （可选）某些用于此类的自定义函数
		    function function_name($parameter)
			{
				//...
		    }
		}

		/**
		 * 0 计数
		 */
		public function count()
		{
			// 筛选条件
			$condition = NULL;
			//$condition['name'] = 'value';

			// （可选）遍历筛选条件
			foreach ($this->sorter_names as $sorter):
				if ( !empty($this->input->post_get($sorter)) ):
					// 对时间范围做限制
					if ($sorter === 'start_time'):
						$condition['time_create >='] = $this->input->post_get($sorter);
					elseif ($sorter === 'end_time'):
						$condition['time_create <='] = $this->input->post_get($sorter);
					else:
						$condition[$sorter] = $this->input->post_get($sorter);
					endif;

				endif;
			endforeach;

			// 获取列表；默认不获取已删除项
			$count = $this->basic_model->count($condition);

			if ($count !== FALSE):
				$this->result['status'] = 200;
				$this->result['content']['count'] = $count;

			else:
				$this->result['status'] = 414;
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
				${$param} = $this->input->post($param);
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

			// 筛选条件
			$condition = NULL;
			//$condition['name'] = 'value';
			// （可选）遍历筛选条件
			foreach ($this->names_to_sort as $sorter):
				if ( !empty($this->input->post($sorter)) )
					$condition[$sorter] = $this->input->post($sorter);
			endforeach;
			
			// 排序条件
			$order_by = NULL;
			//$order_by['name'] = 'value';

			// 限制可返回的字段
			$this->db->select( implode(',', $this->names_to_return) );

			// 获取列表；默认不获取已删除项
			$items = $this->basic_model->select($condition, $order_by, FALSE, FALSE);
			if ( !empty($items) ):
				$this->result['status'] = 200;
				$this->result['content'] = $items;

			else:
				$this->result['status'] = 414;
				$this->result['content']['error']['message'] = '没有符合条件的数据';
			
			endif;
		} // end index

		/**
		 * 2 详情
		 */
		public function detail()
		{
			// 检查必要参数是否已传入
			$id = $this->input->post('id');
			if ( empty($id) ):
				$this->result['status'] = 400;
				$this->result['content']['error']['message'] = '必要的请求参数未传入';
				exit();
			endif;

			// 限制可返回的字段
			$this->db->select( implode(',', $this->names_to_return) );
			
			// 获取特定项；默认可获取已删除项
			$item = $this->basic_model->select_by_id($id);
			if ( !empty($item) ):
				$this->result['status'] = 200;
				$this->result['content'] = $item;

			else:
				$this->result['status'] = 414;
				$this->result['content']['error']['message'] = '没有符合条件的数据';

			endif;
		} // end detail

		/**
		 * 3 创建
		 */
		public function create()
		{
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
				${$param} = $this->input->post($param);
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

			// 初始化并配置表单验证库
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			// 验证规则 https://www.codeigniter.com/user_guide/libraries/form_validation.html#rule-reference
			$this->form_validation->set_rules('biz_id', '商家ID', 'trim|');
			$this->form_validation->set_rules('name', '全称', 'trim|');
			$this->form_validation->set_rules('brief_name', '简称', 'trim|');
			$this->form_validation->set_rules('url_name', '店铺域名', 'trim|required');
			$this->form_validation->set_rules('url_logo', 'LOGO', 'trim|required');
			$this->form_validation->set_rules('slogan', '宣传语', 'trim|required');
			$this->form_validation->set_rules('description', '简介', 'trim|required');
			$this->form_validation->set_rules('notification', '店铺公告', 'trim|required');
			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|');
			$this->form_validation->set_rules('tel_protected_biz', '商务联系手机号', 'trim|');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|required');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|required');
			$this->form_validation->set_rules('freight', '每笔订单运费（元）', 'trim|required');
			$this->form_validation->set_rules('freight_free_subtotal', '免邮费起始金额（元）', 'trim|required');
			$this->form_validation->set_rules('freight_free_count', '免邮费起始份数（份）', 'trim|required');
			$this->form_validation->set_rules('min_order_subtotal', '最低小计金额（元）', 'trim|required');
			$this->form_validation->set_rules('delivery_time_start', '配送起始时间', 'trim|required');
			$this->form_validation->set_rules('delivery_time_end', '配送结束时间', 'trim|required');
			$this->form_validation->set_rules('country', '国家', 'trim|');
			$this->form_validation->set_rules('province', '省', 'trim|required');
			$this->form_validation->set_rules('city', '市', 'trim|required');
			$this->form_validation->set_rules('county', '区', 'trim|required');
			$this->form_validation->set_rules('detail', '详细地址', 'trim|required');
			$this->form_validation->set_rules('longitude', '经度', 'trim|required');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|required');
			$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|required');
			$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|required');
			$this->form_validation->set_rules('code_license', '统一社会信用代码', 'trim|');
			$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|');
			$this->form_validation->set_rules('code_ssn_auth', '授权人身份证号', 'trim|required');
			$this->form_validation->set_rules('url_image_license', '营业执照', 'trim|');
			$this->form_validation->set_rules('url_image_owner_id', '法人身份证', 'trim|');
			$this->form_validation->set_rules('url_image_auth_id', '授权人身份证', 'trim|required');
			$this->form_validation->set_rules('url_image_auth_doc', '授权书', 'trim|required');
			$this->form_validation->set_rules('url_web', '官方网站', 'trim|required');
			$this->form_validation->set_rules('url_weibo', '官方微博', 'trim|required');
			$this->form_validation->set_rules('url_taobao', '淘宝/天猫店铺', 'trim|required');
			$this->form_validation->set_rules('url_wechat', '微信二维码', 'trim|required');
			$this->form_validation->set_rules('url_image_product', '产品', 'trim|required');
			$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|required');
			$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|required');
			$this->form_validation->set_rules('note_admin', '管理员备注', 'trim|required');
			$this->form_validation->set_rules('time_create', '创建时间', 'trim|');
			$this->form_validation->set_rules('time_delete', '删除时间', 'trim|required');
			$this->form_validation->set_rules('time_edit', '最后操作时间', 'trim|');
			$this->form_validation->set_rules('creator_id', '创建者ID', 'trim|required');
			$this->form_validation->set_rules('operator_id', '最后操作者ID', 'trim|required');
			$this->form_validation->set_rules('status', '状态', 'trim|');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$this->result['status'] = 401;
				$this->result['content']['error']['message'] = validation_errors();

			else:
				// 需要创建的数据；逐一赋值需特别处理的字段
				$data_to_create = array(
					'creator_id' => $user_id,
					//'name' => $this->input->post('name')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
				);
				foreach ($data_need_no_prepare as $name)
					$data_to_create[$name] = $this->input->post($name);

				$result = $this->basic_model->create($data_to_create, TRUE);
				if ($result !== FALSE):
					$this->result['status'] = 200;
					$this->result['content']['id'] = $result;
					$this->result['content']['message'] = '创建成功';

				else:
					$this->result['status'] = 424;
					$this->result['content']['error']['message'] = '创建失败';

				endif;
			endif;
		} // end create

		/**
		 * 4 编辑单行数据
		 */
		public function edit()
		{
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
				${$param} = $this->input->post($param);
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

			// 初始化并配置表单验证库
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('biz_id', '商家ID', 'trim|');
			$this->form_validation->set_rules('name', '全称', 'trim|');
			$this->form_validation->set_rules('brief_name', '简称', 'trim|');
			$this->form_validation->set_rules('url_name', '店铺域名', 'trim|required');
			$this->form_validation->set_rules('url_logo', 'LOGO', 'trim|required');
			$this->form_validation->set_rules('slogan', '宣传语', 'trim|required');
			$this->form_validation->set_rules('description', '简介', 'trim|required');
			$this->form_validation->set_rules('notification', '店铺公告', 'trim|required');
			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|');
			$this->form_validation->set_rules('tel_protected_biz', '商务联系手机号', 'trim|');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|required');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|required');
			$this->form_validation->set_rules('freight', '每笔订单运费（元）', 'trim|required');
			$this->form_validation->set_rules('freight_free_subtotal', '免邮费起始金额（元）', 'trim|required');
			$this->form_validation->set_rules('freight_free_count', '免邮费起始份数（份）', 'trim|required');
			$this->form_validation->set_rules('min_order_subtotal', '最低小计金额（元）', 'trim|required');
			$this->form_validation->set_rules('delivery_time_start', '配送起始时间', 'trim|required');
			$this->form_validation->set_rules('delivery_time_end', '配送结束时间', 'trim|required');
			$this->form_validation->set_rules('country', '国家', 'trim|');
			$this->form_validation->set_rules('province', '省', 'trim|required');
			$this->form_validation->set_rules('city', '市', 'trim|required');
			$this->form_validation->set_rules('county', '区', 'trim|required');
			$this->form_validation->set_rules('detail', '详细地址', 'trim|required');
			$this->form_validation->set_rules('longitude', '经度', 'trim|required');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|required');
			$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|required');
			$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|required');
			$this->form_validation->set_rules('code_license', '统一社会信用代码', 'trim|');
			$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|');
			$this->form_validation->set_rules('code_ssn_auth', '授权人身份证号', 'trim|required');
			$this->form_validation->set_rules('url_image_license', '营业执照', 'trim|');
			$this->form_validation->set_rules('url_image_owner_id', '法人身份证', 'trim|');
			$this->form_validation->set_rules('url_image_auth_id', '授权人身份证', 'trim|required');
			$this->form_validation->set_rules('url_image_auth_doc', '授权书', 'trim|required');
			$this->form_validation->set_rules('url_web', '官方网站', 'trim|required');
			$this->form_validation->set_rules('url_weibo', '官方微博', 'trim|required');
			$this->form_validation->set_rules('url_taobao', '淘宝/天猫店铺', 'trim|required');
			$this->form_validation->set_rules('url_wechat', '微信二维码', 'trim|required');
			$this->form_validation->set_rules('url_image_product', '产品', 'trim|required');
			$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|required');
			$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|required');
			$this->form_validation->set_rules('note_admin', '管理员备注', 'trim|required');
			$this->form_validation->set_rules('time_create', '创建时间', 'trim|');
			$this->form_validation->set_rules('time_delete', '删除时间', 'trim|required');
			$this->form_validation->set_rules('time_edit', '最后操作时间', 'trim|');
			$this->form_validation->set_rules('creator_id', '创建者ID', 'trim|required');
			$this->form_validation->set_rules('operator_id', '最后操作者ID', 'trim|required');
			$this->form_validation->set_rules('status', '状态', 'trim|');
			// 针对特定条件的验证规则
			if ($this->app_type === '管理员'):
				// ...
			endif;

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$this->result['status'] = 401;
				$this->result['content']['error']['message'] = validation_errors();

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit = array(
					'operator_id' => $user_id,
					//'name' => $this->input->post('name')),
				);
				// 自动生成无需特别处理的数据
				$data_need_no_prepare = array(
					'biz_id', 'name', 'brief_name', 'url_name', 'url_logo', 'slogan', 'description', 'notification', 'tel_public', 'tel_protected_biz', 'tel_protected_fiscal', 'tel_protected_order', 'freight', 'freight_free_subtotal', 'freight_free_count', 'min_order_subtotal', 'delivery_time_start', 'delivery_time_end', 'country', 'province', 'city', 'county', 'detail', 'longitude', 'latitude', 'bank_name', 'bank_account', 'code_license', 'code_ssn_owner', 'code_ssn_auth', 'url_image_license', 'url_image_owner_id', 'url_image_auth_id', 'url_image_auth_doc', 'url_web', 'url_weibo', 'url_taobao', 'url_wechat', 'url_image_product', 'url_image_produce', 'url_image_retail', 'note_admin', 'time_create', 'time_delete', 'time_edit', 'creator_id', 'operator_id', 'status',
				);
				foreach ($data_need_no_prepare as $name)
					$data_to_edit[$name] = $this->input->post($name);

				// 根据客户端类型等条件筛选可操作的字段名
				if ($this->app_type !== 'admin'):
					//unset($data_to_edit['name']);
				endif;

				// 进行修改
				$result = $this->basic_model->edit($id, $data_to_edit);
				if ($result !== FALSE):
					$this->result['status'] = 200;
					$this->result['content']['message'] = '编辑成功';

				else:
					$this->result['status'] = 434;
					$this->result['content']['error']['message'] = '编辑失败';

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
			$required_params = $this->names_edit_certain_required;
			foreach ($required_params as $param):
				${$param} = $this->input->post($param);
				if ( $param !== 'value' && empty( ${$param} ) ): // value 可以为空；必要字段会在字段验证中另行检查
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

			// 检查目标字段是否可编辑
			if ( ! in_array($name, $this->names_edit_allowed) ):
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
			$this->form_validation->set_error_delimiters('', '');
			// 动态设置待验证字段名及字段值
			$data_to_validate["{$name}"] = $value;
			$this->form_validation->set_data($data_to_validate);
			$this->form_validation->set_rules('biz_id', '商家ID', 'trim|');
			$this->form_validation->set_rules('name', '全称', 'trim|');
			$this->form_validation->set_rules('brief_name', '简称', 'trim|');
			$this->form_validation->set_rules('url_name', '店铺域名', 'trim|required');
			$this->form_validation->set_rules('url_logo', 'LOGO', 'trim|required');
			$this->form_validation->set_rules('slogan', '宣传语', 'trim|required');
			$this->form_validation->set_rules('description', '简介', 'trim|required');
			$this->form_validation->set_rules('notification', '店铺公告', 'trim|required');
			$this->form_validation->set_rules('tel_public', '消费者联系电话', 'trim|');
			$this->form_validation->set_rules('tel_protected_biz', '商务联系手机号', 'trim|');
			$this->form_validation->set_rules('tel_protected_fiscal', '财务联系手机号', 'trim|required');
			$this->form_validation->set_rules('tel_protected_order', '订单通知手机号', 'trim|required');
			$this->form_validation->set_rules('freight', '每笔订单运费（元）', 'trim|required');
			$this->form_validation->set_rules('freight_free_subtotal', '免邮费起始金额（元）', 'trim|required');
			$this->form_validation->set_rules('freight_free_count', '免邮费起始份数（份）', 'trim|required');
			$this->form_validation->set_rules('min_order_subtotal', '最低小计金额（元）', 'trim|required');
			$this->form_validation->set_rules('delivery_time_start', '配送起始时间', 'trim|required');
			$this->form_validation->set_rules('delivery_time_end', '配送结束时间', 'trim|required');
			$this->form_validation->set_rules('country', '国家', 'trim|');
			$this->form_validation->set_rules('province', '省', 'trim|required');
			$this->form_validation->set_rules('city', '市', 'trim|required');
			$this->form_validation->set_rules('county', '区', 'trim|required');
			$this->form_validation->set_rules('detail', '详细地址', 'trim|required');
			$this->form_validation->set_rules('longitude', '经度', 'trim|required');
			$this->form_validation->set_rules('latitude', '纬度', 'trim|required');
			$this->form_validation->set_rules('bank_name', '开户行名称', 'trim|required');
			$this->form_validation->set_rules('bank_account', '开户行账号', 'trim|required');
			$this->form_validation->set_rules('code_license', '统一社会信用代码', 'trim|');
			$this->form_validation->set_rules('code_ssn_owner', '法人身份证号', 'trim|');
			$this->form_validation->set_rules('code_ssn_auth', '授权人身份证号', 'trim|required');
			$this->form_validation->set_rules('url_image_license', '营业执照', 'trim|');
			$this->form_validation->set_rules('url_image_owner_id', '法人身份证', 'trim|');
			$this->form_validation->set_rules('url_image_auth_id', '授权人身份证', 'trim|required');
			$this->form_validation->set_rules('url_image_auth_doc', '授权书', 'trim|required');
			$this->form_validation->set_rules('url_web', '官方网站', 'trim|required');
			$this->form_validation->set_rules('url_weibo', '官方微博', 'trim|required');
			$this->form_validation->set_rules('url_taobao', '淘宝/天猫店铺', 'trim|required');
			$this->form_validation->set_rules('url_wechat', '微信二维码', 'trim|required');
			$this->form_validation->set_rules('url_image_product', '产品', 'trim|required');
			$this->form_validation->set_rules('url_image_produce', '工厂/产地', 'trim|required');
			$this->form_validation->set_rules('url_image_retail', '门店/柜台', 'trim|required');
			$this->form_validation->set_rules('note_admin', '管理员备注', 'trim|required');
			$this->form_validation->set_rules('time_create', '创建时间', 'trim|');
			$this->form_validation->set_rules('time_delete', '删除时间', 'trim|required');
			$this->form_validation->set_rules('time_edit', '最后操作时间', 'trim|');
			$this->form_validation->set_rules('creator_id', '创建者ID', 'trim|required');
			$this->form_validation->set_rules('operator_id', '最后操作者ID', 'trim|required');
			$this->form_validation->set_rules('status', '状态', 'trim|');

			// 若表单提交不成功
			if ($this->form_validation->run() === FALSE):
				$this->result['status'] = 401;
				$this->result['content']['error']['message'] = validation_errors();

			else:
				// 需要编辑的数据
				$data_to_edit['operator_id'] = $user_id;
				$data_to_edit[$name] = $value;

				// 获取ID
				$id = $this->input->post('id');
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
			$required_params = $this->names_edit_bulk_required;
			foreach ($required_params as $param):
				${$param} = $this->input->post($param);
				if ( empty( ${$param} ) ):
					$this->result['status'] = 400;
					$this->result['content']['error']['message'] = '必要的请求参数未全部传入';
					exit();
				endif;
			endforeach;

			// 初始化并配置表单验证库
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('ids', '待操作数据ID们', 'trim|required|regex_match[/^(\d|\d,?)+$/]'); // 仅允许非零整数和半角逗号
			$this->form_validation->set_rules('operation', '待执行操作', 'trim|required|in_list[delete,restore]');
			$this->form_validation->set_rules('operator_id', '操作者ID', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$this->result['status'] = 401;
				$this->result['content']['error']['message'] = validation_errors();
				exit();

			elseif ($this->operator_check() !== TRUE):
				$this->result['status'] = 453;
				$this->result['content']['error']['message'] = '与该ID及类型对应的操作者不存在，或操作密码错误';
				exit();

			else:
				// 需要编辑的数据；逐一赋值需特别处理的字段
				$data_to_edit['operator_id'] = $user_id;

				// 根据待执行的操作赋值待编辑数据
				switch ( $operation ):
					case 'delete':
						$data_to_edit['time_delete'] = date('Y-m-d H:i:s');
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
		

	}

/* End of file Biz.php */
/* Location: ./application/controllers/Biz.php */
