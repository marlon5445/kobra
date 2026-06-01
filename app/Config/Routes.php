<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// =====================================================================
// RUTAS PÚBLICAS (Sin autenticación requerida)
// =====================================================================
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login/procesar', 'Auth::procesar');
$routes->get('logout', 'Auth::logout');

// =====================================================================
// RUTAS PRIVADAS (Requieren autenticación + verificación de permisos)
// =====================================================================
$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard
    $routes->get('dashboard', 'Home::index', ['filter' => 'permission']);

    // Grupo de Categorías
    $routes->group('categorias', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Categorias::index');
        $routes->get('crear', 'Categorias::crear');
        $routes->post('guardar', 'Categorias::guardar');
        $routes->get('editar/(:num)', 'Categorias::editar/$1');
        $routes->post('actualizar/(:num)', 'Categorias::actualizar/$1');
        $routes->get('eliminar/(:num)', 'Categorias::eliminar/$1');
    });

    // Grupo de Productos
    $routes->group('productos', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Productos::index');
        $routes->get('crear', 'Productos::crear');
        $routes->post('guardar', 'Productos::guardar');
        $routes->get('editar/(:num)', 'Productos::editar/$1');
        $routes->post('actualizar/(:num)', 'Productos::actualizar/$1');
        $routes->get('eliminar/(:num)', 'Productos::eliminar/$1');
    });

    // Grupo de Clientes
    $routes->group('clientes', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Clientes::index');
        $routes->get('crear', 'Clientes::crear');
        $routes->post('guardar', 'Clientes::guardar');
        $routes->get('editar/(:num)', 'Clientes::editar/$1');
        $routes->post('actualizar/(:num)', 'Clientes::actualizar/$1');
        $routes->get('eliminar/(:num)', 'Clientes::eliminar/$1');
    });

    // Grupo de Proveedores
    $routes->group('proveedores', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Proveedores::index');
        $routes->get('crear', 'Proveedores::crear');
        $routes->post('guardar', 'Proveedores::guardar');
        $routes->get('editar/(:num)', 'Proveedores::editar/$1');
        $routes->post('actualizar/(:num)', 'Proveedores::actualizar/$1');
        $routes->get('eliminar/(:num)', 'Proveedores::eliminar/$1');
    });

    // Grupo de Usuarios (solo Administrador)
    $routes->group('usuarios', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Usuarios::index');
        $routes->get('crear', 'Usuarios::crear');
        $routes->post('guardar', 'Usuarios::guardar');
        $routes->get('ver/(:num)', 'Usuarios::ver/$1');
        $routes->get('editar/(:num)', 'Usuarios::editar/$1');
        $routes->post('actualizar/(:num)', 'Usuarios::actualizar/$1');
        $routes->get('eliminar/(:num)', 'Usuarios::eliminar/$1');
        $routes->get('cambiarEstado/(:num)/(:num)', 'Usuarios::cambiarEstado/$1/$2');
        $routes->post('resetPassword/(:num)', 'Usuarios::resetPassword/$1');
    });

    // Grupo de Roles y Permisos (solo Administrador)
    $routes->group('roles', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Roles::index');
        $routes->get('crear', 'Roles::crear');
        $routes->post('guardar', 'Roles::guardar');
        $routes->get('editar/(:num)', 'Roles::editar/$1');
        $routes->post('actualizar/(:num)', 'Roles::actualizar/$1');
        $routes->get('eliminar/(:num)', 'Roles::eliminar/$1');
        $routes->get('permisos/(:num)', 'Roles::permisos/$1');
        $routes->post('guardarPermisos/(:num)', 'Roles::guardarPermisos/$1');
    });

    // Grupo de Configuración de Empresa (Fase 7)
    $routes->group('configuracion', ['filter' => 'permission'], function($routes) {
        $routes->get('/', 'Configuracion::index');
        $routes->post('actualizar', 'Configuracion::actualizar');
    });

    // ─────────────────────────────────────────────────────
    // Grupo de Compras (Fase 6)
    // ─────────────────────────────────────────────────────
    $routes->group('compras', ['filter' => 'permission'], function($routes) {
        $routes->get('/',                'Compras::index');
        $routes->get('nueva',            'Compras::nueva');
        $routes->post('registrar',       'Compras::registrar');
        $routes->get('ver/(:num)',       'Compras::ver/$1');
        $routes->get('anular/(:num)',    'Compras::anular/$1');
        $routes->get('buscarProducto',   'Compras::buscarProducto');
    });

    // ─────────────────────────────────────────────────────
    // Grupo de Ventas (Fase 6)
    // ─────────────────────────────────────────────────────
    $routes->group('ventas', ['filter' => 'permission'], function($routes) {
        $routes->get('/',                'Ventas::index');
        $routes->get('nueva',            'Ventas::nueva');
        $routes->post('registrar',       'Ventas::registrar');
        $routes->get('ver/(:num)',       'Ventas::ver/$1');
        $routes->get('anular/(:num)',    'Ventas::anular/$1');
        $routes->get('buscarProducto',   'Ventas::buscarProducto');
        $routes->get('buscarCliente',    'Ventas::buscarCliente');
        $routes->get('imprimir/(:num)',  'Ventas::imprimir/$1');
    });

    // Grupo de Reportes (Fase 8)
    $routes->group('reportes', function($routes) {
        $routes->get('ventas', 'Reportes::ventas', ['filter' => 'permission:reportes.ventas']);
        $routes->get('compras', 'Reportes::compras', ['filter' => 'permission:reportes.compras']);
    });
});

