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
// 1. PORTAL PÚBLICO INFORMATIVO (Libre Acceso - Sin Login)
// ==========================================
Router::get('/', 'PortalController@index');
Router::get('/disciplina/{id}', 'PortalController@disciplina');
Router::get('/portal/disciplina/{id}', 'PortalController@disciplina');
Router::get('/portal/sedes', 'PortalController@sedes');
Router::get('/portal/posiciones', 'PortalController@posiciones');
Router::get('/portal/fixture', 'PortalController@fixture');
Router::get('/portal/partido/{id}', 'PortalController@partidoDetalle');
Router::get('/portal/medallero', 'PortalController@medallero');

// ==========================================
// 2. AUTENTICACIÓN (Exclusiva para Administradores)
// ==========================================
Router::get('/login', 'AuthController@showLogin');
Router::post('/login', 'AuthController@processLogin');
Router::get('/registro', 'AuthController@showRegister'); // Deshabilitado
Router::post('/registro', 'AuthController@processRegister'); // Deshabilitado
Router::get('/logout', 'AuthController@logout');
Router::get('/auth/logout', 'AuthController@logout');

// ==========================================
// 3. PANEL DE ADMINISTRACIÓN (Protegido para Rol ADMIN)
// ==========================================
Router::get('/dashboard', 'DashboardController@index');
Router::get('/admin', 'DashboardController@index');

// 1. Módulo de Olimpiadas
Router::get('/olimpiadas', 'OlimpiadaController@index');
Router::get('/olimpiadas/crear', 'OlimpiadaController@create');
Router::post('/olimpiadas/guardar', 'OlimpiadaController@store');
Router::get('/olimpiadas/{id}/editar', 'OlimpiadaController@edit');
Router::post('/olimpiadas/{id}/actualizar', 'OlimpiadaController@update');
Router::post('/olimpiadas/{id}/eliminar', 'OlimpiadaController@delete');

// 2. Módulo de Reglas de Puntuación (Deportes y Normas)
Router::get('/reglas-puntuacion', 'ReglaPuntuacionController@index');
Router::get('/reglas-puntuacion/crear', 'ReglaPuntuacionController@create');
Router::post('/reglas-puntuacion/guardar', 'ReglaPuntuacionController@store');
Router::get('/reglas-puntuacion/{id}/editar', 'ReglaPuntuacionController@edit');
Router::post('/reglas-puntuacion/{id}/actualizar', 'ReglaPuntuacionController@update');
Router::post('/reglas-puntuacion/{id}/eliminar', 'ReglaPuntuacionController@delete');

// 3. Módulo de Sedes y Canchas
Router::get('/sedes', 'SedeController@index');
Router::get('/sedes/crear', 'SedeController@create');
Router::post('/sedes/guardar', 'SedeController@store');
Router::get('/sedes/{id}/editar', 'SedeController@edit');
Router::post('/sedes/{id}/actualizar', 'SedeController@update');
Router::post('/sedes/{id}/eliminar', 'SedeController@delete');
Router::get('/sedes/{id}/canchas', 'SedeController@canchas');
Router::post('/sedes/{id}/canchas/guardar', 'SedeController@storeCancha');
Router::post('/canchas/{id}/actualizar', 'SedeController@updateCancha');
Router::post('/canchas/{id}/eliminar', 'SedeController@deleteCancha');

// 4. Módulo de Delegaciones (Institución)
Router::get('/delegaciones', 'DelegacionController@index');
Router::get('/delegaciones/crear', 'DelegacionController@create');
Router::post('/delegaciones/guardar', 'DelegacionController@store');
Router::get('/delegaciones/{id}', 'DelegacionController@show');
Router::get('/delegaciones/{id}/editar', 'DelegacionController@edit');
Router::post('/delegaciones/{id}/actualizar', 'DelegacionController@update');
Router::post('/delegaciones/{id}/eliminar', 'DelegacionController@delete');

// ==========================================
// Módulo: Delegación, Equipo y Disciplina
// ==========================================

// 1. Módulo de Disciplinas (donde compiten Delegaciones y sus Equipos)
Router::get('/disciplinas', 'DisciplinaController@index');
Router::get('/disciplinas/crear', 'DisciplinaController@create');
Router::post('/disciplinas/guardar', 'DisciplinaController@store');
Router::get('/disciplinas/{id}', 'DisciplinaController@show');
Router::get('/disciplinas/{id}/editar', 'DisciplinaController@edit');
Router::post('/disciplinas/{id}/actualizar', 'DisciplinaController@update');
Router::post('/disciplinas/{id}/eliminar', 'DisciplinaController@delete');

// 2. Módulo de Gestión de Grupos por Disciplina
Router::post('/disciplinas/{id}/grupos/guardar', 'GrupoController@store');
Router::get('/api/disciplinas/{id}/grupos', 'EquipoController@gruposJson');
Router::post('/grupos/asignar-equipo', 'GrupoController@asignarEquipo');
Router::post('/grupos/desasignar-equipo/{id}', 'GrupoController@desasignarEquipo');
Router::post('/grupos/{id}/eliminar', 'GrupoController@delete');

// Alias de compatibilidad hacia DisciplinaController
Router::get('/torneos', 'DisciplinaController@index');
Router::get('/torneos/crear', 'DisciplinaController@create');
Router::post('/torneos/guardar', 'DisciplinaController@store');
Router::get('/torneos/{id}', 'DisciplinaController@show');
Router::get('/torneos/{id}/editar', 'DisciplinaController@edit');
Router::post('/torneos/{id}/actualizar', 'DisciplinaController@update');
Router::post('/torneos/{id}/eliminar', 'DisciplinaController@delete');
Router::post('/torneos/{id}/grupos/guardar', 'GrupoController@store');
Router::get('/api/torneos/{id}/grupos', 'EquipoController@gruposJson');

// 3. Módulo de Equipos
Router::get('/equipos', 'EquipoController@index');
Router::get('/equipos/crear', 'EquipoController@create');
Router::post('/equipos/guardar', 'EquipoController@store');
Router::get('/equipos/{id}/editar', 'EquipoController@edit');
Router::post('/equipos/{id}/actualizar', 'EquipoController@update');
Router::post('/equipos/{id}/eliminar', 'EquipoController@delete');

// 4. Módulo de Nómina de Jugadores
Router::get('/jugadores', 'JugadorController@index');
Router::get('/equipos/{id}/jugadores', 'JugadorController@porEquipo');
Router::get('/jugadores/crear', 'JugadorController@create');
Router::post('/jugadores/guardar', 'JugadorController@store');
Router::get('/jugadores/{id}', 'JugadorController@show');
Router::get('/jugadores/{id}/editar', 'JugadorController@edit');
Router::post('/jugadores/{id}/actualizar', 'JugadorController@update');
Router::post('/jugadores/{id}/cambiar-estado', 'JugadorController@cambiarEstado');
Router::post('/jugadores/{id}/eliminar', 'JugadorController@delete');

// 5. Módulo de Fixture y Programación de Partidos (Fase 6)
Router::get('/partidos', 'PartidoController@index');
Router::get('/partidos/crear', 'PartidoController@create');
Router::post('/partidos/guardar', 'PartidoController@store');
Router::get('/partidos/{id}', 'PartidoController@show');
Router::get('/partidos/{id}/editar', 'PartidoController@edit');
Router::post('/partidos/{id}/actualizar', 'PartidoController@update');
Router::post('/partidos/{id}/eliminar', 'PartidoController@delete');
Router::get('/partidos/{id}/acta', 'PartidoController@acta');
Router::post('/partidos/{id}/resultado', 'PartidoController@guardarResultado');
Router::post('/partidos/{id}/sets/guardar', 'PartidoController@guardarSet');
Router::post('/partidos/{id}/sets/eliminar', 'PartidoController@eliminarSet');
Router::post('/partidos/{id}/incidencias/guardar', 'PartidoController@guardarIncidencia');
Router::post('/partidos/{id}/incidencias/eliminar', 'PartidoController@eliminarIncidencia');
Router::get('/api/disciplinas/{id}/equipos', 'PartidoController@apiEquiposPorTorneo');
Router::get('/api/torneos/{id}/equipos', 'PartidoController@apiEquiposPorTorneo');

// ==========================================
// FASE 7: Régimen Disciplinario y Sanciones
// ==========================================
Router::get('/sanciones', 'SancionController@index');
Router::get('/sanciones/crear', 'SancionController@create');
Router::post('/sanciones/guardar', 'SancionController@store');
Router::get('/sanciones/{id}', 'SancionController@show');
Router::post('/sanciones/{id}/levantar', 'SancionController@levantar');
Router::post('/sanciones/{id}/cumplir-fecha', 'SancionController@cumplirFecha');
Router::post('/sanciones/{id}/eliminar', 'SancionController@delete');
Router::get('/api/equipos/{id}/jugadores-json', 'SancionController@apiJugadoresPorEquipo');

// ==========================================
// FASE 8: Tablas de Posiciones y Medallero Olímpico
// ==========================================
// Tablas de Posiciones
Router::get('/posiciones', 'PosicionesController@index');
Router::get('/api/posiciones/{id}', 'PosicionesController@apiTabla');

// Medallero Olímpico Oficial
Router::get('/medallero', 'MedalleroController@index');
Router::get('/medallero/gestion', 'MedalleroController@gestion');
Router::get('/medallero/disciplinas/{id}/asignar', 'MedalleroController@asignar');
Router::post('/medallero/disciplinas/{id}/guardar', 'MedalleroController@guardarPodio');
Router::get('/medallero/torneos/{id}/asignar', 'MedalleroController@asignar');
Router::post('/medallero/torneos/{id}/guardar', 'MedalleroController@guardarPodio');
Router::post('/medallero/{id}/eliminar', 'MedalleroController@eliminarMedalla');

// Procesar y despachar la solicitud HTTP
Router::dispatch();
