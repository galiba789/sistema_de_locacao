<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/login/logout', 'Auth::logout');

$routes->get('/home', 'Home::index');

$routes->get('/clientes', 'Clientes::index');
$routes->get('/clientes/cadastrar', 'Clientes::cadastrar');
$routes->post('/clientes/salvar', 'Clientes::salvar');
$routes->post('/clientes/consulta', 'Clientes::consulta');
$routes->post('/clientes/editar/consulta', 'Clientes::consulta');
$routes->get('/clientes/excluir/(:num)', 'Clientes::excluir/$1');
$routes->get('/clientes/editar/(:num)', 'Clientes::editar/$1');
$routes->post('/clientes/update/(:num)', 'Clientes::update/$1');
$routes->get('/clientes/buscar', 'Clientes::buscar');
$routes->get('/clientes/historico/(:num)', 'Clientes::historico/$1');



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
$routes->get('produtos/buscar', 'Produtos::buscar');


$routes->get('/locacoes', 'Locacoes::index');
$routes->get('/locacoes/cadastrar', 'Locacoes::cadastrar');
$routes->post('/locacoes/salvar', 'Locacoes::salvar');
$routes->get('/locacoes/contrato/(:num)', 'Locacoes::gerarContrato/$1');
$routes->get('/locacoes/edita/(:num)', 'Locacoes::edita/$1');
$routes->post('/locacoes/editar/(:num)', 'Locacoes::editar/$1');
$routes->get('/locacoes/cancelar/(:num)', 'Locacoes::cancelarContrato/$1');
$routes->get('/locacoes/confirmar/(:num)', 'Locacoes::confirmarlocacao/$1');
$routes->get('locacoes/buscar', 'Locacoes::buscar');
$routes->post('/locacoes/consulta', 'Locacoes::consulta');
$routes->post('/locacoes/edita/consulta', 'Locacoes::consulta');
$routes->post('/locacoes/salvarClientes', 'Locacoes::salvarClientes');
$routes->post('/locacoes/verificarDisponibilidadeAjax', 'Locacoes::verificarDisponibilidadeAjax');
$routes->get('/locacoes/pagamento/(:num)', 'Locacoes::pagamento/$1');

$routes->get('/calendario', 'Calendario::index');
$routes->get('/calendario/index/(:num)/(:num)', 'Calendario::index/$1/$2');

$routes->get('/orcamento', 'Orcamento::index');
$routes->get('/orcamento/cadastrar', 'Orcamento::cadastrar');
$routes->post('/orcamento/salvar', 'Orcamento::salvar');
$routes->get('/orcamento/contrato/(:num)', 'Orcamento::gerarContrato/$1');
$routes->get('/orcamento/edita/(:num)', 'Orcamento::edita/$1');
$routes->post('/orcamento/editar/(:num)', 'Orcamento::editar/$1');
$routes->get('/orcamento/cancelar/(:num)', 'Orcamento::cancelarOrcamento/$1');
$routes->get('orcamento/buscar', 'Orcamento::buscar');
$routes->post('/orcamento/consulta', 'Orcamento::consulta');
$routes->post('/orcamento/edita/consulta', 'Orcamento::consulta');
$routes->post('/orcamento/salvarClientes', 'Orcamento::salvarClientes');
$routes->post('/orcamento/verificarDisponibilidadeAjax', 'Orcamento::verificarDisponibilidadeAjax');
$routes->get('/orcamento/fazerContrato/(:num)', 'Orcamento::fazerContrato/$1');



$routes->get('/usuarios', 'Users::index');
$routes->get('/usuarios/cadastrar', 'Users::cadastrar');
$routes->post('/usuarios/salvar', 'Users::salvar');
$routes->get('/usuarios/edita/(:num)', 'Users::edita/$1');
$routes->post('/usuarios/editar/(:num)', 'Users::editar/$1');
$routes->get('/usuarios/excluir/(:num)', 'Users::excluir/$1');

$routes->get('/home', 'Home::index');

$routes->get('/relatorios', 'Relaorios::index');
