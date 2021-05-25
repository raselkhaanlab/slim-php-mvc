<?php
//== application constant variable set for access across the application ==
define("ROOT",__DIR__);
define("APP_PATH",__DIR__."/app");
define('CONFIG_PATH',__DIR__."/config");
define("VIEWS_PATH",__DIR__."/views");
define("PUBLIC_PATH",__DIR__."/public");
define('APP_CONFIG',require CONFIG_PATH."/application.php");
define('DATABASE_CONFIG',require CONFIG_PATH."/database.php");
define('KERNEL',require APP_PATH."/Middleware/kernel.php");
//=================
session_name(APP_CONFIG['session-name']);
session_start();
header("X-Powered-By: RKO");
require_once 'vendor/autoload.php';
require_once 'route.php';
exit;