<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/* Home 首页 */
$route['home'] = 'home/index'; // 首页

/* Account 账号 */
$route['mine'] = 'account/mine'; // 个人中心（仅限登录后）
$route['login'] = 'account/login'; // 登录
$route['register'] = 'account/register'; // 注册
$route['logout'] = 'account/logout'; // 退出当前账号
$route['email_reset'] = 'account/email_reset'; // 换绑Email（仅限登录后）
$route['mobile_reset'] = 'account/mobile_reset'; // 换绑手机号（仅限登录后）
$route['password_reset'] = 'account/password_reset'; // 重置密码（仅限登录前）
$route['password_change'] = 'account/password_change'; // 修改密码（仅限登录后）
$route['account/edit'] = 'account/edit'; // 编辑账户资料

/* 以下按控制器类名称字母降序排列 */

/* API 接口 */
$route['api/detail'] = 'api/detail'; // 详情
$route['api/edit'] = 'api/edit'; // 编辑
$route['api/create'] = 'api/create'; // 创建
$route['api/delete'] = 'api/delete'; // 删除
$route['api/restore'] = 'api/restore'; // 恢复
$route['api/trash'] = 'api/trash'; // 回收站
$route['api'] = 'api/index'; // 列表

/*TODO Category 类别 */
$route['category/detail'] = 'category/detail'; // 详情
$route['category/edit'] = 'category/edit'; // 编辑
$route['category/create'] = 'category/create'; // 创建
$route['category/delete'] = 'category/delete'; // 删除
$route['category/restore'] = 'category/restore'; // 恢复
$route['category/trash'] = 'category/trash'; // 回收站
$route['category'] = 'category/index'; // 列表

/* Page 页面 */
$route['page/detail'] = 'page/detail'; // 详情
$route['page/edit'] = 'page/edit'; // 编辑
$route['page/create'] = 'page/create'; // 创建
$route['page/delete'] = 'page/delete'; // 删除
$route['page/restore'] = 'page/restore'; // 恢复
$route['page/trash'] = 'page/trash'; // 回收站
$route['page'] = 'page/index'; // 列表

/* Project 项目 */
$route['project/detail'] = 'project/detail'; // 详情
$route['project/edit'] = 'project/edit'; // 编辑
$route['project/create'] = 'project/create'; // 创建
$route['project/delete'] = 'project/delete'; // 删除
$route['project/restore'] = 'project/restore'; // 恢复
$route['project/trash'] = 'project/trash'; // 回收站
$route['project'] = 'project/index'; // 列表

/* Param_public 公共参数 */
$route['param_public/detail'] = 'param_public/detail'; // 详情
$route['param_public/edit'] = 'param_public/edit'; // 编辑
$route['param_public/create'] = 'param_public/create'; // 创建
$route['param_public/delete'] = 'param_public/delete'; // 删除
$route['param_public/restore'] = 'param_public/restore'; // 恢复
$route['param_public/trash'] = 'param_public/trash'; // 回收站
$route['param_public'] = 'param_public/index'; // 列表

/* FAQ 常见问题 */
$route['faq/detail'] = 'faq/detail'; // 详情
$route['faq/edit'] = 'faq/edit'; // 编辑
$route['faq/create'] = 'faq/create'; // 创建
$route['faq/delete'] = 'faq/delete'; // 删除
$route['faq/restore'] = 'faq/restore'; // 恢复
$route['faq/trash'] = 'faq/trash'; // 回收站
$route['faq'] = 'faq/index'; // 列表

/* Order 订单 */
$route['order/mine'] = 'order/mine'; // 我的
$route['order/detail'] = 'order/detail'; // 详情
$route['order'] = 'order/index'; // 列表

/* User 用户（无社交功能的前台一般可删除该组） */
$route['user/detail'] = 'user/detail'; // 用户详情
$route['user'] = 'user/index'; // 用户列表

$route['default_controller'] = 'home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE; // 将路径中的“-”解析为“_”，兼顾SEO需要与类命名规范

/* End of file routes.php */
/* Location: ./application/config/routes.php */