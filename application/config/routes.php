<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Home 首页 */
$route['home'] = 'home/index'; // 首页

/* Account 账号 */
$route['mine'] = 'account/mine'; // 个人中心（登录后）
$route['login_sms'] = 'account/login_sms'; // 短信登录
$route['login'] = 'account/login'; // 密码登录
$route['register_sms'] = 'account/register_sms'; // 短信注册
$route['register'] = 'account/register'; // 密码注册
$route['logout'] = 'account/logout'; // 退出当前账号
//$route['mobile_reset'] = 'account/mobile_reset'; // 换绑手机号（登录后）
$route['password_set'] = 'account/password_set'; // 设置密码（登录后）
$route['password_change'] = 'account/password_change'; // 修改密码（登录后）
$route['password_reset'] = 'account/login_sms'; // 重置密码（登录前），即转到短信登录

/* Project 项目 */
$route['project/detail'] = 'project/detail'; // 详情
$route['project/edit'] = 'project/edit'; // 编辑
$route['project/create'] = 'project/create'; // 创建
$route['project/delete'] = 'project/delete'; // 删除
$route['project/restore'] = 'project/restore'; // 恢复
$route['project/trash'] = 'project/trash'; // 回收站
$route['project'] = 'project/index'; // 列表

/* Flow 流程 */
$route['flow/detail'] = 'flow/detail'; // 详情
$route['flow/edit'] = 'flow/edit'; // 编辑
$route['flow/create'] = 'flow/create'; // 创建
$route['flow/delete'] = 'flow/delete'; // 删除
$route['flow/restore'] = 'flow/restore'; // 恢复
$route['flow/trash'] = 'flow/trash'; // 回收站
$route['flow'] = 'flow/index'; // 列表

/* Page 页面 */
$route['page/detail'] = 'page/detail'; // 详情
$route['page/edit'] = 'page/edit'; // 编辑
$route['page/create'] = 'page/create'; // 创建
$route['page/delete'] = 'page/delete'; // 删除
$route['page/restore'] = 'page/restore'; // 恢复
$route['page/trash'] = 'page/trash'; // 回收站
$route['page'] = 'page/index'; // 列表

/* API 接口 */
$route['api/detail'] = 'api/detail'; // 详情
$route['api/edit'] = 'api/edit'; // 编辑
$route['api/create'] = 'api/create'; // 创建
$route['api/delete'] = 'api/delete'; // 删除
$route['api/restore'] = 'api/restore'; // 恢复
$route['api/trash'] = 'api/trash'; // 回收站
$route['api'] = 'api/index'; // 列表

/* Biz 企业 */
$route['biz/detail'] = 'biz/detail'; // 详情
$route['biz/edit'] = 'biz/edit'; // 编辑
$route['biz/create'] = 'biz/create'; // 创建
$route['biz/delete'] = 'biz/delete'; // 删除
$route['biz/restore'] = 'biz/restore'; // 恢复
$route['biz/trash'] = 'biz/trash'; // 回收站
$route['biz'] = 'biz/index'; // 列表

/* Team 团队 */
$route['team/detail'] = 'team/detail'; // 详情
$route['team/edit'] = 'team/edit'; // 编辑
$route['team/create'] = 'team/create'; // 创建
$route['team/delete'] = 'team/delete'; // 删除
$route['team/restore'] = 'team/restore'; // 恢复
$route['team/trash'] = 'team/trash'; // 回收站
$route['team'] = 'team/index'; // 列表

/* User 用户 */
$route['user/detail'] = 'user/detail'; // 详情
$route['user/edit'] = 'user/edit'; // 编辑
$route['user/create'] = 'user/create'; // 创建
$route['user/delete'] = 'user/delete'; // 删除
$route['user/restore'] = 'user/restore'; // 恢复
$route['user/trash'] = 'user/trash'; // 回收站
$route['user'] = 'user/index'; // 列表

/* Task 任务 */
$route['task/detail'] = 'task/detail'; // 详情
$route['task/edit'] = 'task/edit'; // 编辑
$route['task/create'] = 'task/create'; // 创建
$route['task/delete'] = 'task/delete'; // 删除
$route['task/restore'] = 'task/restore'; // 恢复
$route['task/trash'] = 'task/trash'; // 回收站
$route['task'] = 'task/index'; // 列表

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

/* User 用户（无社交功能的前台一般可删除该组） */
$route['user/detail'] = 'user/detail'; // 用户详情
$route['user'] = 'user/index'; // 用户列表

$route['default_controller'] = 'home/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE; // 将路径中的“-”解析为“_”，兼顾SEO需要与类命名规范

/* End of file routes.php */
/* Location: ./application/config/routes.php */