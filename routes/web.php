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
    return "SmartClock API<br>" . $router->app->version();
});

$router->get('/api/weather', 'WeatherSource@getWeatherFiltered');
$router->get('/api/time', 'TimeController@getTime');
$router->get('/api/init', 'SmartClockController@getInitSettings');
$router->get('/api/get/{index}', 'SmartClockController@getDashboard');


