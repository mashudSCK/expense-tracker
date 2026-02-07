<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route redirects to login
$routes->get('/', 'AuthController::login');

// Authentication routes
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');
$routes->get('logout', 'AuthController::logout');

// Protected routes (require authentication)
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');
    
    // Expenses
    $routes->get('expenses', 'ExpenseController::index');
    $routes->get('expenses/create', 'ExpenseController::create');
    $routes->post('expenses/store', 'ExpenseController::store');
    $routes->get('expenses/edit/(:num)', 'ExpenseController::edit/$1');
    $routes->post('expenses/update/(:num)', 'ExpenseController::update/$1');
    $routes->get('expenses/delete/(:num)', 'ExpenseController::delete/$1');
    
    // Categories (Admin only)
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/create', 'CategoryController::create');
    $routes->post('categories/store', 'CategoryController::store');
    $routes->get('categories/edit/(:num)', 'CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');
    
});
