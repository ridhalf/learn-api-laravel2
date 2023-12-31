<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
$router->get('auth', 'AuthController@login');

$router->group(['prefix' => '', 'middleware' => 'checktoken'], function () use ($router) {
    $router->get('products', 'ProductController@index');
    $router->post('products', 'ProductController@store');
    $router->put('products/{id}', 'ProductController@update');
    $router->get('products/{id}', 'ProductController@detail');
    $router->delete('products/{id}', 'ProductController@destroy');
});
