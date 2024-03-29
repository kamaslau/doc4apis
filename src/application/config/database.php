<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_group = 'local';
$query_builder = TRUE;

/* 适用于生产环境的数据库参数 */
$db['aliyun'] = array(
	'dsn' => 'mysqli://liuyajie728:027889@sensestrong.mysql.rds.aliyuncs.com/doc4apis',
	'hostname' => 'sensestrong.mysql.rds.aliyuncs.com', // 数据库URL，以阿里云为例
	'username' => 'liuyajie728', // 数据库用户名
	'password' => '027889', // 数据库密码
	'database' => 'doc4apis', //数据库名
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE,
);

/* 适用于本地开发环境的数据库参数 */
$db['local'] = array(
	'dsn'	=> '',
	'hostname' => 'mysql',
	'username' => 'root',
	'password' => '123456',
	'database' => 'doc4apis',
	'dbdriver' => 'mysqli', // 根据本地环境的不同，可能需要修改为mysql
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8mb4',
	'dbcollat' => 'utf8mb4_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE,
);

/* End of file database.php */
/* Location: ./application/config/database.php */