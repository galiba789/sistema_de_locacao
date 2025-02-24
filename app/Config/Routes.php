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
$routes->post('/clientes/salvar', 'Clientes::salvar');
$routes->post('/clientes/consulta', 'Clientes::consulta');
$routes->get('/clientes/excluir/(:num)', 'Clientes::excluir/$1');
$routes->get('/clientes/editar/(:num)', 'Clientes::editar/$1');
$routes->post('/clientes/update/(:num)', 'Clientes::update/$1');

$routes->get('/categorias', 'Categorias::index');
$routes->get('/categorias/cadastrar', 'Categorias::cadastrar');
$routes->post('/categorias/salvar', 'Categorias::salvar');
$routes->get('/categorias/edita/(:num)', 'Categorias::edita/$1');
$routes->post('/categorias/editar/(:num)', 'Categorias::editar/$1');
$routes->get('/categorias/excluir/(:num)', 'Categorias::excluir/$1');

$routes->get('/produtos', 'Produtos::index');
$routes->get('/produtos/cadastrar', 'Produtos::cadastrar');
$routes->post('/produtos/salvar', 'Produtos::salvar');
$routes->get('/produtos/edita/(:num)', 'Produtos::edita/$1');
$routes->post('/produtos/editar/(:num)', 'Produtos::editar/$1');
$routes->get('/produtos/excluir/(:num)', 'Produtos::excluir/$1');

$routes->get('/locacoes', 'Locacoes::index');
$routes->get('/locacoes/cadastrar', 'Locacoes::cadastrar');

$routes->get('/home', 'Home::index');
