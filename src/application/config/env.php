<?php
// 生产环境配置文件
defined('BASEPATH') or exit('No direct script access allowed');

// 需要自定义的常量
define('SITE_NAME', 'doc4APIs'); // 站点名称
define('SITE_SLOGAN', '创造本该流畅'); // 站点广告语
define('SITE_KEYWORDS', 'API,RESTful,开发文档,项目管理,开发管理,敏捷开发'); // 站点关键词
define('SITE_DESCRIPTION', 'doc4apis是一个基于API的WEB项目协作平台，基于BasicCodeIgniter框架'); // 站点描述
define('ICP_NUMBER', NULL); // （可选）ICP备案号码

define('ROOT_DOMAIN', 'localhost');
// define('ROOT_DOMAIN', '.doc4apis.liuyajie.com');

define('BASE_URL', 'http://localhost/');
// define('BASE_URL', 'https://'. $_SERVER['SERVER_NAME'].'/'); // 可对外使用的站点URL

define('API_TOKEN', '7C4l7JLaM3Fq5biQurtmk9nFS');

// （可选）JS、CSS、样式图片等非当前站点特有资源所在URL，可用于配合又拍云等第三方存储
define('CDN_URL', 'https://cdn.liuyajie.com/');
// （可选）非样式图片存储的根目录所在URL，可用于配合又拍云等第三方存储
define('IMAGES_URL', BASE_URL . 'uploads/');

/* End of file env.php */
/* Location: ./application/config/env.php */
