<?php
use RKO\Router;
use App\Controller\{Home,NotFound};
/*
@ define your route here
*/
Router::get("/",[Home::class,'index'],['example']);

Router::fallback([NotFound::class,'notFound']);
Router::methodNotFound([NotFound::class,'methodNotFound']);
