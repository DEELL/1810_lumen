<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
//注册
$router->post('/user/reg','User\UserController@reg');
$router->post('/user/login','User\UserController@login');
$router->post('/user/amend','User\UserController@amend');
$router->post('/user/weather','User\UserController@weather');
$router->post('/text/curl','Text\TextController@curl');
//http加密
$router->post('/text/curl2','Text\TextController@curl2');
$router->post('/text/curl3','Text\TextController@curl3');
$router->post('/asymm','Text\TextController@asymm');
$router->post('/personal','Text\TextController@personal');//    私钥加密  公钥解密
$router->post('/exerci','Text\TextController@exerci');// 练习


$router->post('/gateway','Text\TextController@gateway');// 支付宝
