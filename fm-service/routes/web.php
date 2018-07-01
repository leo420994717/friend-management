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

$router->post('/users', 'UserController@create');

$router->post('/friends', 'FriendController@create');

$router->post('/friends/get-list', 'FriendController@getList');

$router->post('/friends/get-common-list', 'FriendController@getCommonList');

$router->post('/subscribes', 'SubscribeController@create');

$router->post('/blocks', 'BlockListController@create');

$router->post('/updates', 'UpdatesController@create');