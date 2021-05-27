<?php
use RKO\Router as Route;
use App\Controller\{Home,NotFound};
/*
@ define your route here
*/
Route::get("/",[Home::class,'index'],['example']);

Route::fallback([NotFound::class,'notFound']);
Route::fallback([NotFound::class,'notFound']);
Route::methodNotFound([NotFound::class,'methodNotFound']);
