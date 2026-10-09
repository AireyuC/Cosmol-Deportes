<?php
/**
 * Configuración Global del Sistema Cosmol-Deportes
 * Compatible con PHP 7.3
 */

// Constantes de directorios
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . DS . 'app');
define('PUBLIC_PATH', ROOT_PATH . DS . 'public');

// Cargar variables de entorno desde .env si existe
require_once APP_PATH . '/helpers/Env.php';
Env::load(ROOT_PATH . DS . '.env');

// Zona horaria configurable
date_default_timezone_set(env('APP_TIMEZONE', 'America/La_Paz'));

// Detección automática o configuración de URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$scriptDir = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])) : '';
$baseUrl = rtrim($protocol . $host . $scriptDir, '/');

// Si el scriptDir termina en /public, la URL base puede usarse directamente
define('BASE_URL', $baseUrl);

// Configuración de la Aplicación
define('APP_NAME', env('APP_NAME', 'Cosmol-Deportes'));
define('APP_SLOGAN', 'Sistema de Gestión de Olimpiadas Deportivas');
define('APP_VERSION', '1.0.0');
define('APP_ENV', env('APP_ENV', 'local'));
define('APP_DEBUG', (bool)env('APP_DEBUG', true));
define('APP_KEY', env('APP_KEY', 'cosmol_deportes_secret_key_2026_xyz'));

// Parámetros de Base de Datos para XAMPP
define('DB_CONNECTION', env('DB_CONNECTION', 'mysql'));
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_NAME', env('DB_DATABASE', 'cosmol_deportes'));
define('DB_USER', env('DB_USERNAME', 'root'));
define('DB_PASS', env('DB_PASSWORD', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));
