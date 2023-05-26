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

// Router group for APIs
$router->group(['prefix' => 'api'], function () use ($router) {
    // Customers API
    $router->get('/customers', 'CustomerController@findAllCustomers');
    $router->get('/customers/{customerId}', 'CustomerController@findCustomer');
});