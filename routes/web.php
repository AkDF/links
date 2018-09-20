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

$router->get('/', function () {
    abort(404);
});

$router->post('/login', 'UserController@login');
$router->post('/register', 'UserController@register');

$router->group(['middleware'=>'auth'],function ($router){

    $router->get('/logout','UserController@logout');




    $router->get('/users', 'UserController@users');

});







/*
 * $router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/', function ()    {
        // Uses Auth Middleware
    });

    $router->get('user/profile', function () {
        // Uses Auth Middleware
    });
});
 */

/*
 * $router->group(['prefix' => 'admin'], function () use ($router) {
    $router->get('users', function ()    {
        // Matches The "/admin/users" URL
    });
});
 */



/*
 * $app->get($uri, $callback);
$app->post($uri, $callback);
$app->put($uri, $callback);
$app->patch($uri, $callback);
$app->delete($uri, $callback);
$app->options($uri, $callback);
 */
