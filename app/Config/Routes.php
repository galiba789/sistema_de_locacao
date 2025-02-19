<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('login/logout', 'Auth::logout');



$routes->get('/home', 'Home::index');

$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/cadastrar', 'Clientes::cadastrar');
$routes->post('login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');



$routes->get('/home', 'Home::index');
