<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	// 检查当前设备信息
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$is_wechat = strpos($user_agent, 'MicroMessenger')? TRUE: FALSE;
	$is_ios = strpos($user_agent, 'iPhone')? TRUE: FALSE;
	$is_android = strpos($user_agent, 'Android')? TRUE: FALSE;

    // 生成body的class
    $body_class = ( isset($class) )? $class: NULL;
    $body_class .= ($is_wechat)? ' is_wechat': NULL;
    $body_class .= ($is_ios)? ' is_ios': NULL;
    $body_class .= ($is_android)? ' is_android': NULL;

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
		<meta name=version content="revision20190831">
		<meta name=author content="刘亚杰">
		<meta name=copyright content="刘亚杰">
		<meta name=contact content="kamaslau@dingtalk.com">
		<meta name=robots content="index,nofollow">

		<meta name=viewport content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>
    <script defer src="<?php echo CDN_URL. 'jquery/jquery-qrcode.js' ?>"></script>
    <script defer src="https://pro.fontawesome.com/releases/v5.10.2/js/all.js" integrity="sha384-lowSFbzpSYKDOsvnpi2JVneSnkrbVjOTwcHOWpC+tj/YT1mxTDIB3ZqbtllmfUSC" crossorigin="anonymous"></script>
    <script>
      const user_agent = {
        is_wechat: <?php echo ($is_wechat === TRUE)? 'true': 'false' ?>,
        is_ios: <?php echo ($is_ios === TRUE)? 'true': 'false' ?>,
        is_android: <?php echo ($is_android === TRUE)? 'true': 'false' ?>
      }

      // 全局参数
      // const api_url = '<?php //echo API_URL ?>' // API根URL
      const base_url = '<?php echo BASE_URL ?>' // 页面根URL
      const current_url = '<?php echo current_url() ?>'
      // const cdn_url = '<?php echo CDN_URL ?>' // CDN根URL
      // const media_url = '<?php //echo MEDIA_URL ?>' // 媒体文件根URL
      const class_name = '<?php echo $this->class_name ?>'
      const class_name_cn = '<?php echo $this->class_name_cn ?>'

      // 当前用户信息
      const user_id = '<?php echo $this->session->user_id ?>'

      let common_params = {
        app_type: 'client', // 默认为客户端请求
        user_id
      }
    </script>

    <!--
    <link rel=stylesheet href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

		<link rel=stylesheet media=all href="<?php echo CDN_URL ?>Flat-UI/css/flat-ui.min.css">
		<link rel=stylesheet media=all href="<?php echo VIEWS_PATH ?>css/style.css">

<!--		<link rel="shortcut icon" href="/media/logos/logo_32x32.png">-->
<!--		<link rel=apple-touch-icon href="/media/logos/logo_120x120.png">-->

		<link rel=canonical href="<?php echo current_url() ?>">

		<meta name=format-detection content="telephone=yes, address=no, email=no">
	</head>
<?php
	// 将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
	ob_flush();flush();
?>

<!-- 内容开始 -->
	<body<?php echo ( !empty($body_class) )? ' class="'.$body_class.'"': NULL ?>>
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
						<li<?php if (strpos($class, 'project') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('project') ?>">项目</a></li>

						<?php if ($this->session->role === '管理员'): ?>
						<li<?php if (strpos($class, 'biz') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('biz') ?>">企业</a></li>
						<li<?php if (strpos($class, 'team') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('team') ?>">团队</a></li>
            <?php endif ?>

            <li<?php if (strpos($class, 'user') !== FALSE) echo ' class=active' ?>><a href="<?php echo base_url('user') ?>">成员</a></li>
					</ul>
				</nav>

				<div id=account-panel>
					<ul id=user-actions class=horizontal>
						<?php if ($this->session->logged_in !== TRUE): ?>
						<li><a href="<?php echo base_url('login') ?>"><i class="fal fa-sign-in"></i> 登录</a></li>

						<?php
							else:
							$display_name = empty($this->session->nickname)? $this->session->firstname.', '.$this->session->lastname: $this->session->nickname;
						?>
						<li>
                <a href="<?php echo base_url('user/mine') ?>">
                    <i class="fal fa-user-circle"></i> <?php echo $display_name ?>
                </a>
            </li>

						<li><a href="<?php echo base_url('logout') ?>"><i class="fal fa-sign-out"></i> 退出</a></li>
						<?php endif ?>
					</ul>
				</div>
			</div>
		</header>

		<main id=maincontainer role=main>