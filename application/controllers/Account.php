<?php
	defined('BASEPATH') OR exit('此文件不可被直接访问');

	/**
	 * Account Class
	 *
	 * @version 1.0.0
	 * @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	 * @copyright ICBG <www.bingshankeji.com>
	 */
	class Account extends CI_Controller
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
		
		/* 来路页面URL */
		public $from_url = BASE_URL;

		public function __construct()
		{
			parent::__construct();

			// 实际来路URL
			$from_url = isset($_SERVER["HTTP_REFERER"])? $_SERVER["HTTP_REFERER"]: NULL;

			// 如果来路URL是站内页面，则当前操作成功后跳转到该页面
			if ( strpos($from_url, BASE_URL) !== FALSE )
				$this->from_url = $from_url;

			// 向类属性赋值
			$this->class_name = strtolower(__CLASS__);
			$this->class_name_cn = '账户'; // 改这里……
			$this->table_name = 'user'; // 和这里……
			$this->id_name = 'user_id';  // 还有这里，OK，这就可以了
			$this->view_root = $this->class_name;

			// 设置并调用Basic核心库
			$basic_configs = array(
				'table_name' => $this->table_name,
				'id_name' => $this->id_name,
				'view_root' => $this->view_root,
			);
			$this->load->library('basic', $basic_configs);
		}

		public function __destruct()
		{
			//$this->output->enable_profiler(TRUE);
		}

		// 获取来源网址
		public function from_url()
		{
			$from_url = '';
			$system_from_url = $this->input->server('HTTP_REFERER');

			if (stripos($system_from_url, base_url()) === FALSE): // 若来源不是站内，则转到首页
				$from_url = base_url();
			elseif ($system_from_url == base_url('login') || $system_from_url == base_url('register')): // 若来源是登录或注册页，则转到session中已经存储的来源页面
				$from_url = $this->session->from_url;
			else: // 若来源是站内，且不是登录页，则转到当前页的来源网址
				$from_url = $system_from_url;
			endif;

			$this->session->set_userdata('from_url', $from_url);
		}

		/**
		 * 个人中心
		 */
		public function mine()
		{
			// 若未登录，转到登录页
			if ($this->session->logged_in !== TRUE) redirect( base_url('login') );
			
			// 转到相应的用户详情页
			redirect( base_url('user/detail?id='.$this->session->user_id) );
		}

		/**
		 * 密码登录
		 *
		 * @param string $_POST['mobile']
		 * @param string $_POST['password']
		 * @return void
		 */
		public function login()
		{
			// 若已登录，转到首页
			if ($this->session->logged_in === TRUE) redirect( base_url() );

			// 页面信息
			$data = array(
				'title' => '密码登录',
				'class' => $this->class_name.' '. $this->class_name.'-login',
			);

			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|is_natural|exact_length[11]');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');

			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/login', $data);
				$this->load->view('templates/footer', $data);

			else:
				$data_to_search = array(
					'mobile' => $this->input->post('mobile'),
					'password' => sha1( $this->input->post('password') ),
				);
				$result = $this->basic_model->match($data_to_search); // 使用密码登录

				// 成功登录
				if ( ! empty($result)):
					// 获取用户信息
					$data['user'] = $result;

					// 将信息键值对写入session
					foreach ($data['user'] as $key => $value):
						$user_data[$key] = $value;
					endforeach;
					$user_data['logged_in'] = TRUE; // 标记登录状态，便于快速判断是否已登录
					$this->session->set_userdata($user_data);

					// 将管理员手机号写入cookie并保存1个月
					$this->input->set_cookie('mobile', $data['user']['mobile'], 60*60*24*30, COOKIE_DOMAIN);
					// 转到来路页面
					redirect( $this->from_url );

				// 若用户不存在
				else:
					$if_exsit = $this->basic_model->find('mobile', $this->input->post('mobile')); // 检查用户是否存在
					if ( empty($if_exsit) ):
						$data['error'] = '此手机号未注册过，请先<a title="注册" href="'. base_url('register') .'">注册</a>';
						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/login', $data);
						$this->load->view('templates/footer', $data);

					// 若密码错误
					else:
						$data['error'] = '<p>密码不正确，请确认后重试。</p>';
						$this->load->view('templates/header', $data);
						$this->load->view($this->view_root.'/login', $data);
						$this->load->view('templates/footer', $data);
					endif;

				endif;
			endif;
		}

		/**
		 * 短信注册&登录
		 *
		 * 通过短信验证码登录后，若未设置密码则转到密码设置页；若已设置密码则转到来路页面
		 *
		 * @param string $_POST['mobile']
		 * @param string $_POST['captcha']
		 * @param string $_POST['sms']
		 * @return void
		 */
		public function login_sms()
		{
			// 若已登录，转到首页
			if ($this->session->logged_in === TRUE) redirect( base_url() );

			// 页面信息
			$data = array(
				'title' => '短信登录',
				'class' => $this->class_name.' '. $this->class_name.'-login',
			);

			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|is_natural|exact_length[11]');
			$this->form_validation->set_rules('captcha', '短信验证码', 'trim|required|is_natural|exact_length[6]');

			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/login_sms', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 通过服务器验证图片验证码
				$url = api_url('captcha/verify');
				$params = array(
					'word' => $this->input->post('captcha_verify'), // 图片验证码
					'ip_address' => $this->input->ip_address(), // 客户端IP
				);
				$captcha = $this->curl->go($url, $params, 'array');
				if ($captcha['status'] !== 200):
					$data['error'] = '图片验证码错误';
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/login_sms', $data);
					$this->load->view('templates/footer', $data);
				endif;

				// 通过服务器验证短信验证码
				$url = api_url('sms/verify');
				$params = array(
					'mobile' => $this->input->post('mobile'), // 手机号
					'captcha' => $this->input->post('captcha'), // 短信验证码
					'sms_id' => $this->input->cookie('sms_id'), // 获取已发送的短信ID
				);
				$sms = $this->curl->go($url, $params, 'array');
				if ($sms['status'] !== 200):
					$data['error'] = '短信验证码错误';
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/login_sms', $data);
					$this->load->view('templates/footer', $data);
				endif;

				// 若短信验证成功，获取用户数据并进行本地存储
				if ($sms['status'] === 200):
					// 根据手机号获取用户数据
					$url = api_url('account/login');
					$params = array(
						'name' => 'mobile',
						'value' => $this->input->post('mobile'),
						'user_ip' => $this->input->ip_address(),
					);
					$result = $this->curl->go($url, $params, 'array');

					// 若成功获取用户数据，则进一步处理
					if ($result['status'] === 200):
						$user = $result['content'];

						// 将用户数据对写入session
						foreach ($user as $key => $value):
							$user_data[$key] = $value;
						endforeach;
						$user_data['logged_in'] = TRUE; // 标记登录状态，便于快速判断是否已登录
						$this->session->set_userdata($user_data);

						// 将手机号写入cookie并保存1个月
						$this->input->set_cookie('mobile', $data['user']['mobile'], 60*60*24*30, COOKIE_DOMAIN);
						
						// 若未设置密码则转到密码设置页；若已设置密码则转到来路页面
						if ( empty($user['password']) ):
							redirect( 'password_set' );

						else:
							redirect( $this->from_url );

						endif;

					endif;
					
				endif;

			endif;
		}

		/**
		 * 密码注册
		 *
		 * 使用手机号和密码进行账户注册
		 *
		 * @param string $_POST['mobile']
		 * @param string $_POST['password']
		 * @param string $_POST['password2']
		 * @return void
		 */
		public function register()
		{
			// 暂不允许通过密码注册
			redirect( base_url() );

			/*
			// 若已登录，转到首页
			if ($this->session->logged_in === TRUE) redirect( base_url() );

			// 页面信息
			$data = array(
				'title' => '注册',
				'class' => $this->class_name.' '. $this->class_name.'-register',
			);

			$this->form_validation->set_rules('mobile', '手机号', 'trim|required|is_natural|exact_length[11]');
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password2', '确认密码', 'trim|required|matches[password]');

			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/register', $data);
				$this->load->view('templates/footer', $data);

			elseif ( ! empty($this->basic_model->find('mobile', $this->input->post('mobile')))):
				// 若用户已存在
				$data['error'] = '该手机号已注册过账户，您可<a title="登录" href="'. base_url('login') .'">直接登录</a>';
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/register', $data);
				$this->load->view('templates/footer', $data);

			else:
				$data_to_create = array(
					'mobile' => $this->input->post('mobile'),
					'password' => sha1($this->input->post('password')),
				);
				$result = $this->basic_model->create($data_to_create, TRUE); // 尝试创建用户

				// 成功创建
				if ( ! empty($result)):
					// 获取用户信息
					$data['user'] = $this->basic_model->select_by_id($result);

					// 将信息键值对写入session
					foreach ($data['user'] as $key => $value):
						$user_data[$key] = $value;
					endforeach;
					$user_data['logged_in'] = TRUE; // 标记登录状态，便于快速判断是否已登录
					$this->session->set_userdata($user_data);

					// 将管理员手机号写入cookie并保存1个月
					$this->input->set_cookie('mobile', $data['user']['mobile'], 60*60*24*30, COOKIE_DOMAIN);

					// 若未设置密码则转到密码设置页；若已设置密码则转到来路页面
					if ( empty($user['password']) ):
						redirect( 'password_set' );

					else:
						redirect( $this->from_url );

					endif;

				// 若密码错误
				else:
					$data['error'] = '注册失败，请重试。';
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/register', $data);
					$this->load->view('templates/footer', $data);

				endif;
			endif;
			*/
		}

		/**
		 * 密码设置
		 *
		 * 未设置过密码的已登录用户可修改密码
		 *
		 * @param string $_POST['password']
		 * @param string $_POST['password2']
		 * @return void
		 */
		public function password_set()
		{
			// 若用户未登录，转到密码重置页
			if ($this->session->logged_in !== TRUE) redirect( base_url('password_reset') );

			// 页面信息
			$data = array(
				'title' => '设置密码',
				'class' => $this->class_name.' '. $this->class_name.'-password-set',
			);

			// 待验证的表单项
			$this->form_validation->set_rules('password', '密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password2', '确认密码', 'trim|required|matches[password]');

			// 获取当前用户ID
			$id = $this->session->user_id;

			// 获取待编辑信息
			$data['item'] = $this->basic_model->select_by_id($id);

			// 若已设置密码，则转到个人中心
			if ( !empty($data['item']['password']) )
				redirect( base_url('mine') );

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_set', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 需要编辑的信息
				$data_to_edit = array(
					'password' => sha1($this->input->post('password')),
				);
				
				$result = $this->basic_model->edit($id, $data_to_edit);
				if ($result !== FALSE):
					$data['content'] = '<p class="alert alert-success">设置成功。</p>';
				else:
					$data['content'] = '<p class="alert alert-warning">设置失败，请重试。</p>';
				endif;

				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/result', $data);
				$this->load->view('templates/footer', $data);

			endif;
		}
		
		/**
		 * 密码修改
		 *
		 * 已登录用户可修改密码
		 *
		 * @param string $_POST['password']
		 * @param string $_POST['password_new']
		 * @param string $_POST['password2']
		 * @return void
		 */
		public function password_change()
		{
			// 若用户未登录，转到密码重置页
			if ($this->session->logged_in !== TRUE) redirect( base_url('password_reset') );

			// 页面信息
			$data = array(
				'title' => '修改密码',
				'class' => $this->class_name.' '. $this->class_name.'-password-change',
			);

			// 待验证的表单项
			$this->form_validation->set_rules('password', '原密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password_new', '新密码', 'trim|required|min_length[6]|max_length[20]');
			$this->form_validation->set_rules('password2', '确认密码', 'trim|required|matches[password_new]');

			// 验证表单值格式
			if ($this->form_validation->run() === FALSE):
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_change', $data);
				$this->load->view('templates/footer', $data);
			
			// 新密码需要不同于原密码
			elseif ($this->input->post('password') === $this->input->post('password_new')):
				$data['error'] = '新密码需要不同于原密码！';
				$this->load->view('templates/header', $data);
				$this->load->view($this->view_root.'/password_change', $data);
				$this->load->view('templates/footer', $data);

			else:
				// 验证原密码是否正确
				$data_to_search = array(
					'user_id' => $this->session->user_id,
					'password' => sha1($this->input->post('password')),
				);
				$item = $this->basic_model->match($data_to_search);
				if ( !empty($item) ):
					// 需要存入数据库的信息
					$data_to_edit['password'] = sha1( $this->input->post('password_new') );
					$result = $this->basic_model->edit($this->session->user_id, $data_to_edit);

					if ($result !== FALSE):
						$data['content'] = '<p class="alert alert-success">保存成功。</p>';
					else:
						$data['content'] = '<p class="alert alert-warning">保存失败。</p>';
					endif;

					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/result', $data);
					$this->load->view('templates/footer', $data);

				else:
					$data['error'] = '原密码错误，请重试！';
					$this->load->view('templates/header', $data);
					$this->load->view($this->view_root.'/password_change', $data);
					$this->load->view('templates/footer', $data);
				endif;

			endif;
		}

		/**
		 * 密码重置
		 *
		 * 未登录用户转到短信登录页
		 */
		public function password_reset()
		{
			// 若用户已登录，转到密码修改页
			if ($this->session->logged_in === TRUE) redirect( base_url('password_change') );

			// 若用户未登录，转到短信登录页
			if ($this->session->logged_in === TRUE) redirect( base_url('login_sms') );
		}

		/**
		 * 退出账户
		 *
		 * @param void
		 * @return void
		 */
		public function logout()
		{
			// 清除当前SESSION
			$this->session->sess_destroy();

			// 转到登录页
			redirect( base_url('login') );
		}
	}

/* End of file Account.php */
/* Location: ./application/controllers/Account.php */
