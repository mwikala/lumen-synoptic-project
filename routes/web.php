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

$router->group(['prefix' => 'card'], function () use ($router) {
    $router->post('scan', 'GeneralController@card_scanned');
    $router->post('verify', 'GeneralController@verify_login');
});



$router->group(['prefix' => 'user'], function () use ($router) {
    $router->post('register', 'GeneralController@register');
});
