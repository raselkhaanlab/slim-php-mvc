<?php
define("ROOT",__DIR__);
require_once ROOT."/framework/EnvManager.php";
require_once 'vendor/autoload.php';
use RKO\Router;
//== application constant variable set for access across the application ==
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
header("X-Powered-By:".APP_CONFIG['app_title']);
Router::initialize();
require_once 'route.php';
Router::resolve();
exit;