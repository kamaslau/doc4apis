<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	// 检查当前设备信息
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$is_wechat = strpos($user_agent, 'MicroMessenger')? TRUE: FALSE;
	$is_ios = strpos($user_agent, 'iPhone')? TRUE: FALSE;
	$is_android = strpos($user_agent, 'Android')? TRUE: FALSE;

	// 生成SEO相关变量，一般为页面特定信息与在config/config.php中设置的站点通用信息拼接
	$title = isset($title)? $title.' - '.SITE_NAME: SITE_NAME.' - '.SITE_SLOGAN;
	$keywords = isset($keywords)? $keywords.',': NULL;
	$keywords .= SITE_KEYWORDS;
	$description = isset($description)? $description: NULL;
	$description .= SITE_DESCRIPTION;
?>
<!doctype html>
<html lang=zh-cn>
	<head>
		<meta charset=utf-8>
		<meta http-equiv=x-dns-prefetch-control content=on>
		<link rel=dns-prefetch href="http://cdn.key2all.com">
		<title><?php echo $title ?></title>
		<meta name=description content="<?php echo $description ?>">
		<meta name=keywords content="<?php echo $keywords ?>">
		<meta name=version content="revision20170609">
		<meta name=author content="刘亚杰">
		<meta name=copyright content="刘亚杰">
		<meta name=contact content="kamaslau@outlook.com">
		<meta name=robots content="nofollow">

		<meta name=viewport content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<script src="https://cdn.key2all.com/js/jquery/new.js"></script>
		<script defer src="<?php echo base_url('js/jquery-qrcode.js') ?>"></script>
		<script defer src="https://cdn.key2all.com/bootstrap/js/bootstrap-3_3_7.min.js"></script>

		<link rel=stylesheet media=all href="https://cdn.key2all.com/css/reset.css">
		<link rel=stylesheet media=all href="https://cdn.key2all.com/bootstrap/css/bootstrap-3_3_7.min.css">
		<link rel=stylesheet media=all href="https://cdn.key2all.com/flat-ui/css/flat-ui.min.css">
		<link rel=stylesheet media=all href="https://cdn.key2all.com/font-awesome/css/font-awesome.min.css">
		<link rel=stylesheet media=all href="/css/style.css">

		<link rel="shortcut icon" href="//www.bandaodian.com/logos/logo_32x32.png">
		<link rel=apple-touch-icon href="//www.bandaodian.com/logos/logo_120x120.png">

		<link rel=canonical href="<?php echo current_url() ?>">

		<meta name=format-detection content="telephone=yes, address=no, email=no">
	</head>
<?php
	// 将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
	ob_flush();flush();
?>
<!-- 内容开始 -->
<?php
	/**
	 * APP中调用webview时配合URL按需显示相应部分
	 * 此处以在APP中以WebView打开页面时不显示页面header部分为例
	 */
	if ($this->input->get('from') != 'app'):
?>
	<body<?php echo (isset($class))? ' class="'.$class.'"': NULL; ?>>
		<header id=header role=banner>
			<div class=container>
				<h1>
					<a id=logo title="<?php echo SITE_NAME ?>" href="<?php echo base_url() ?>"><?php echo SITE_NAME ?></a>
				</h1>

				<nav id=nav-header role=navigation>
					<ul id=main-nav class=horizontal>
						<li<?php if (strpos($class, 'home') !== FALSE) echo ' class=active' ?>><a title="首页" href="<?php echo base_url() ?>">首页</a></li>
						<!--<li<?php if (strpos($class, 'task') !== FALSE) echo ' class=active' ?>><a title="任务" href="<?php echo base_url('task') ?>">任务</a></li>-->
						<li<?php if (strpos($class, 'task') !== FALSE) echo ' class=active' ?>><a title="任务" href="https://www.teambition.com/project/59093c6f8752371d796bda6f" target=_blank>任务</a></li>
						<li<?php if (strpos($class, 'project') !== FALSE) echo ' class=active' ?>><a title="项目" href="<?php echo base_url('project') ?>">项目</a></li>
						<!--<li<?php if (strpos($class, 'flow') !== FALSE) echo ' class=active' ?>><a title="流程" href="<?php echo base_url('flow') ?>">流程</a></li>-->
						<li<?php if (strpos($class, 'page') !== FALSE) echo ' class=active' ?>><a title="页面" href="<?php echo base_url('page') ?>">页面</a></li>
						<li<?php if (strpos($class, 'api') !== FALSE) echo ' class=active' ?>><a title="API" href="<?php echo base_url('api') ?>">API</a></li>

						<?php if ($this->session->role === '管理员'): ?>
						<li<?php if (strpos($class, 'biz') !== FALSE) echo ' class=active' ?>><a title="企业" href="<?php echo base_url('biz') ?>">企业</a></li>
						<li<?php if (strpos($class, 'team') !== FALSE) echo ' class=active' ?>><a title="团队" href="<?php echo base_url('team') ?>">团队</a></li>
						<?php endif ?>

						<li<?php if (strpos($class, 'user') !== FALSE) echo ' class=active' ?>><a title="成员" href="<?php echo base_url('user') ?>">成员</a></li>

						<li<?php if (strpos($class, 'faq') !== FALSE) echo ' class=active' ?>><a title="FAQ" href="<?php echo base_url('faq') ?>">FAQ</a></li>
					</ul>
				</nav>

				<div id=account-panel>
					<ul id=user-actions class=horizontal>
						<?php if ($this->session->logged_in !== TRUE): ?>
						<li><a title="登录" href="<?php echo base_url('login') ?>">登录</a></li>
						<!--<li><a title="注册" href="<?php echo base_url('register') ?>">注册</a></li>-->

						<?php
							else:
							$display_name = !empty($this->session->nickname)? $this->session->nickname: $this->session->lastname.$this->session->firstname;
						?>
						<li><a title="<?php echo $display_name ?>" href="<?php echo base_url('user/detail?id='.$this->session->user_id) ?>"><?php echo $display_name ?> [<?php echo $this->session->role ?>]</a></li>
						<li><a title="退出" href="<?php echo base_url('logout') ?>">退出</a></li>
						<?php endif ?>
					</ul>
				</div>
			</div>
		</header>
<?php endif ?>

		<main id=maincontainer role=main>