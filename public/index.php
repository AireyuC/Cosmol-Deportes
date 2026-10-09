<?php
/**
 * Front Controller Principal: Cosmol-Deportes
 * Punto de entrada único para todas las solicitudes web
 * Compatible con PHP 7.3
 */

// Cargar configuración global
require_once dirname(__DIR__) . '/app/config/config.php';

// Cargar helpers fundamentales
require_once APP_PATH . '/helpers/Session.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/Router.php';

// Iniciar sesión segura
Session::init();

// Autocargador simple compatible con PHP 7.3 para controladores y modelos
spl_autoload_register(function ($className) {
    $paths = [
        APP_PATH . '/controllers/' . $className . '.php',
        APP_PATH . '/models/' . $className . '.php',
        APP_PATH . '/helpers/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ==========================================
// REGISTRO DE RUTAS PRINCIPALES
// ==========================================

// Autenticación de Usuarios
Router::get('/login', 'AuthController@showLogin');
Router::post('/login', 'AuthController@processLogin');
Router::get('/registro', 'AuthController@showRegister');
Router::post('/registro', 'AuthController@processRegister');
Router::get('/logout', 'AuthController@logout');
Router::get('/auth/logout', 'AuthController@logout');

// Dashboard Principal
Router::get('/', 'DashboardController@index');
Router::get('/dashboard', 'DashboardController@index');

// 1. Módulo de Olimpiadas
Router::get('/olimpiadas', 'OlimpiadaController@index');
Router::get('/olimpiadas/crear', 'OlimpiadaController@create');
Router::post('/olimpiadas/guardar', 'OlimpiadaController@store');
Router::get('/olimpiadas/{id}/editar', 'OlimpiadaController@edit');
Router::post('/olimpiadas/{id}/actualizar', 'OlimpiadaController@update');
Router::post('/olimpiadas/{id}/eliminar', 'OlimpiadaController@delete');

// 2. Módulo de Disciplinas y Reglas de Puntuación
Router::get('/disciplinas', 'DisciplinaController@index');
Router::get('/disciplinas/crear', 'DisciplinaController@create');
Router::post('/disciplinas/guardar', 'DisciplinaController@store');
Router::get('/disciplinas/{id}/editar', 'DisciplinaController@edit');
Router::post('/disciplinas/{id}/actualizar', 'DisciplinaController@update');
Router::post('/disciplinas/{id}/eliminar', 'DisciplinaController@delete');

// 3. Módulo de Sedes y Canchas
Router::get('/sedes', 'SedeController@index');
Router::get('/sedes/crear', 'SedeController@create');
Router::post('/sedes/guardar', 'SedeController@store');
Router::get('/sedes/{id}/editar', 'SedeController@edit');
Router::post('/sedes/{id}/actualizar', 'SedeController@update');
Router::post('/sedes/{id}/eliminar', 'SedeController@delete');
Router::get('/sedes/{id}/canchas', 'SedeController@canchas');
Router::post('/sedes/{id}/canchas/guardar', 'SedeController@storeCancha');
Router::post('/canchas/{id}/eliminar', 'SedeController@deleteCancha');

// 4. Módulo de Delegaciones
Router::get('/delegaciones', 'DelegacionController@index');
Router::get('/delegaciones/crear', 'DelegacionController@create');
Router::post('/delegaciones/guardar', 'DelegacionController@store');
Router::get('/delegaciones/{id}/editar', 'DelegacionController@edit');
Router::post('/delegaciones/{id}/actualizar', 'DelegacionController@update');
Router::post('/delegaciones/{id}/eliminar', 'DelegacionController@delete');

// Rutas de siguientes fases (preparadas para Fase 5, 6, 7 y 8)
Router::get('/torneos', 'DashboardController@index');
Router::get('/equipos', 'DelegacionController@index');
Router::get('/jugadores', 'DashboardController@index');
Router::get('/partidos', 'DashboardController@index');
Router::get('/sanciones', 'DashboardController@index');
Router::get('/medallero', 'DashboardController@index');
Router::get('/posiciones', 'DashboardController@index');

// Procesar y despachar la solicitud HTTP
Router::dispatch();
