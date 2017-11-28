<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
<<<<<<< HEAD
define('APP_DEBUG',True);
=======
//define('APP_DEBUG',True);
>>>>>>> 00974f969f7976db8c93b7277134a64cfcbc3b0c

// 定义应用目录
define('APP_PATH','./cmApp/');
define('BIND_MODULE','Admin');

define('BASE_PATH',str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");

set_time_limit(0);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单