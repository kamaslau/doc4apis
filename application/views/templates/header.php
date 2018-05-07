<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	// 检查当前设备信息
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$is_wechat = strpos($user_agent, 'MicroMessenger')? TRUE: FALSE;
	$is_ios = strpos($user_agent, 'iPhone')? TRUE: FALSE;
	$is_android = strpos($user_agent, 'Android')? TRUE: FALSE;

    // 生成body的class
    $body_class = ( isset($class) )? $class: NULL;
    $body_class .= ($is_wechat === TRUE)? ' is_wechat': NULL;
    $body_class .= ($is_ios === TRUE)? ' is_ios': NULL;
    $body_class .= ($is_android === TRUE)? ' is_android': NULL;

	// 生成SEO相关变量，一般为页面特定信息与在config/config.php中设置的站点通用信息拼接
	$title = isset($title)? $title.' - '.SITE_NAME: SITE_NAME.' - '.SITE_SLOGAN;
    $keywords = (isset($keywords)? $keywords.',': NULL). SITE_KEYWORDS;
    $description = (isset($description)? $description: NULL). SITE_DESCRIPTION;
?>
<!doctype html>
<html lang=zh-cn>
	<head>
		<meta charset=utf-8>
		<meta http-equiv=x-dns-prefetch-control content=on>
		<link rel=dns-prefetch href="<?php echo CDN_URL ?>">
		<title><?php echo $title ?></title>
		<meta name=description content="<?php echo $description ?>">
		<meta name=keywords content="<?php echo $keywords ?>">
		<meta name=version content="revision20180507">
		<meta name=author content="刘亚杰">
		<meta name=copyright content="刘亚杰">
		<meta name=contact content="kamaslau@outlook.com">
		<meta name=robots content="nofollow">

		<meta name=viewport content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<script src="<?php echo CDN_URL ?>js/jquery-3.3.1.min.js"></script>
		<script defer src="<?php echo base_url('js/jquery-qrcode.js') ?>"></script>
        <script defer src="<?php echo CDN_URL ?>bootstrap/v3.3.7/bootstrap.min.js"></script>
        <script>
            var user_agent = new Object();
            user_agent.is_wechat = <?php echo ($is_wechat === TRUE)? 'true': 'false' ?>;
            user_agent.is_ios = <?php echo ($is_ios === TRUE)? 'true': 'false' ?>;
            user_agent.is_android = <?php echo ($is_android === TRUE)? 'true': 'false' ?>;
        </script>

		<link rel=stylesheet media=all href="<?php echo CDN_URL ?>css/reset.css">
        <link rel=stylesheet media=all href="<?php echo CDN_URL ?>bootstrap/v3.3.7/bootstrap.min.css">
		<link rel=stylesheet media=all href="<?php echo CDN_URL ?>css/flat-ui.min.css">
        <script defer src="<?php echo CDN_URL ?>font-awesome/v5.0.12/fontawesome-all.min.js"></script>
        <script defer src="<?php echo CDN_URL ?>font-awesome/v5.0.12/fa-v4-shims.min.js"></script>
		<link rel=stylesheet media=all href="/css/style.css">

		<link rel="shortcut icon" href="/media/logos/logo_32x32.png">
		<link rel=apple-touch-icon href="/media/logos/logo_120x120.png">

		<link rel=canonical href="<?php echo current_url() ?>">

		<meta name=format-detection content="telephone=yes, address=no, email=no">
	</head>
<?php
	// 将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
	ob_flush();flush();
?>

<!-- 内容开始 -->
	<body<?php echo ( !empty($body_class) )? ' class="'.$body_class.'"': NULL ?>>
	<?php
	/**
		 * APP中调用webview时配合URL按需显示相应部分
		 * 此处以在APP中以WebView打开页面时不显示页面header部分为例
		 */
		if ($this->input->get('from') != 'app'):
	?>
		<noscript>
			<p>您的浏览器功能加载出现问题，请刷新浏览器重试；如果仍然出现此提示，请考虑更换浏览器。</p>
		</noscript>
		
		<header id=header role=banner>
			<div class=container>
				<h1>
					<a id=logo title="<?php echo SITE_NAME ?>" href="<?php echo base_url() ?>"><?php echo SITE_NAME ?></a>
				</h1>

				<nav id=nav-header role=navigation>
					<ul id=main-nav class=horizontal>
						<li<?php if (strpos($class, 'home') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url() ?>">首页</a></li>
						<!--<li<?php if (strpos($class, 'task') !== FALSE) echo ' class=active' ?>><a title="任务" href="<?php echo base_url('task') ?>">任务</a></li>-->
						<li<?php if (strpos($class, 'task') !== FALSE) echo ' class=active' ?>><a href="https://www.teambition.com/project/59093c6f8752371d796bda6f" target=_blank>任务</a></li>
						<li<?php if (strpos($class, 'project') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('project') ?>">项目</a></li>
						<li<?php if (strpos($class, 'flow') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('flow') ?>">流程</a></li>
						<li<?php if (strpos($class, 'page') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('page') ?>">页面</a></li>
						<li<?php if (strpos($class, 'api') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('api') ?>">API</a></li>

						<?php if ($this->session->role === '管理员'): ?>
						<li<?php if (strpos($class, 'biz') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('biz') ?>">企业</a></li>
						<li<?php if (strpos($class, 'team') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('team') ?>">团队</a></li>
						<?php endif ?>

						<li<?php if (strpos($class, 'user') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('user') ?>">成员</a></li>

						<li<?php if (strpos($class, 'faq') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('faq') ?>">FAQ</a></li>
					</ul>
				</nav>

				<div id=account-panel>
					<ul id=user-actions class=horizontal>
						<?php if ($this->session->logged_in !== TRUE): ?>
						<li><a href="<?php echo base_url('login') ?>">登录</a></li>
						<!--<li><a href="<?php echo base_url('register') ?>">注册</a></li>-->
						<?php
							else:
							$display_name = !empty($this->session->nickname)? $this->session->nickname: $this->session->lastname.$this->session->firstname;
						?>
						<li><a href="<?php echo base_url('user/detail?id='.$this->session->user_id) ?>"><?php echo $display_name ?> [<?php echo $this->session->role ?>]</a></li>
						<li><a href="<?php echo base_url('logout') ?>">退出</a></li>
						<?php endif ?>
					</ul>
				</div>
			</div>
		</header>
<?php endif ?>

		<main id=maincontainer role=main>
			<script>
				document.ready = function(){
					// 每隔五分钟刷新页面
					setTimeout(function(){location.reload();}, 1000*60*5);
				}
			</script>
			<div class=bg-info style="overflow:hidden;">
				<p class="text-info text-center">页面最后刷新于 <em><?php echo date('H:i:s') ?></em></p>
			</div>