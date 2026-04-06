<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Route untuk fitur Books (Buku)
$routes->get('books', 'BookController::index');
$routes->get('books/(:num)', 'BookController::detail/$1');

// Route untuk fitur Members (Member)
$routes->get('members', 'MemberController::index');
$routes->get('members/(:num)', 'MemberController::detail/$1');
