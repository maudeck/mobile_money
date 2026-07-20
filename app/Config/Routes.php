<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');

$routes->get('/login', 'AuthController::index');
$routes->post('/login/auth', 'AuthController::auth');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/admin/dashboard', 'AuthController::adminDashboard');
$routes->get('/client/dashboard', 'ClientController::dashboard');
$routes->get('/client/solde', 'ClientController::solde');
$routes->get('/client/historique', 'ClientController::historique');
$routes->post('/client/frais/(:segment)', 'ClientController::calculFrais/$1');
$routes->post('/client/depot', 'ClientController::depot');
$routes->post('/client/retrait', 'ClientController::retrait');
$routes->post('/client/transfert', 'ClientController::transfert');
$routes->post('/client/transfert-multiple', 'ClientController::transfertMultiple');
$routes->get('/client/(:segment)', 'ClientController::operation/$1');

$routes->get('/operateur', 'OperateurController::index');
$routes->get('/operateur/create', 'OperateurController::create');
$routes->post('/operateur/store', 'OperateurController::create');
$routes->get('/operateur/edit/(:num)', 'OperateurController::edit/$1');
$routes->post('/operateur/update/(:num)', 'OperateurController::edit/$1');
$routes->get('/operateur/delete/(:num)', 'OperateurController::delete/$1');
$routes->post('/operateur/delete/(:num)', 'OperateurController::delete/$1');

$routes->get('/type-operation', 'TypeOperationController::index');
$routes->get('/type-operation/create', 'TypeOperationController::create');
$routes->post('/type-operation/store', 'TypeOperationController::create');
$routes->get('/type-operation/edit/(:num)', 'TypeOperationController::edit/$1');
$routes->post('/type-operation/update/(:num)', 'TypeOperationController::edit/$1');
$routes->get('/type-operation/delete/(:num)', 'TypeOperationController::delete/$1');
$routes->post('/type-operation/delete/(:num)', 'TypeOperationController::delete/$1');
$routes->get('/type-operation/tranches/(:num)', 'TypeOperationController::tranches/$1');
$routes->get('/type-operation/add-tranche/(:num)', 'TypeOperationController::addTranche/$1');
$routes->post('/type-operation/add-tranche/(:num)', 'TypeOperationController::addTranche/$1');
$routes->get('/type-operation/delete-tranche/(:num)/(:num)', 'TypeOperationController::deleteTranche/$1/$2');
$routes->post('/type-operation/delete-tranche/(:num)/(:num)', 'TypeOperationController::deleteTranche/$1/$2');

$routes->get('/admin/gains', 'StatistiqueController::gains');
$routes->get('/admin/clients', 'StatistiqueController::clients');

$routes->get('/test-db', 'TestController::index');
