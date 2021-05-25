<?php
use RKO\Router;
use App\Controller\{Home,NotFound};
$router = new Router;
/*
@ define your route here
*/
$router->get("/",[Home::class,'index'],['example']);

$router->fallback([NotFound::class,'notFound']);
$router->methodNotFound([NotFound::class,'methodNotFound']);
//ROUTER RESOLVE; PLEASE DON'T DELETE THIS LINE.OTHER WISE ROUTING WILL NOT WORK;
$router->resolve();

