<?php
return array(
	//'配置项'=>'配置值'
	//'配置项'=>'配置值'
	//mysql数据库全局定义
	'DB_TYPE' => 'mysql',	//数据库类型
	'DB_HOST' => 'localhost',
	'DB_USER' => 'root',
	'DB_PWD' => '',
	'DB_NAME' => 'cm_app',
	'DB_PORT' => 3306,
	'DB_PREFIX' => 'cm_',     //表前缀
	
	// 'SHOW_PAGE_TRACE' => true,
	
	
	// 'DEFAULT_MODULE'=>'Home',  // 默认模块
	//'DEFAULT_CONTROLLER' => 'Login', // 默认控制器名称
	// 'DEFAULT_ACTION' => 'index', // 默认操作名称
	//关于视图
	'TMPL_TEMPLATE_SUFFIX'=>'.php', //设置默认后缀
	
	'TMPL_PARSE_STRING' =>array(				//配置公共路径
		'__PUBLIC__' => __ROOT__.'/Public',),
		
	'MODULE_ALLOW_LIST' => array ('Home'),
    'DEFAULT_MODULE' => 'Home'
	
);