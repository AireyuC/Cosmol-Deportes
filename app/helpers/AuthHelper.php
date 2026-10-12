<?php
/**
 * Helper de Autenticación y Control de Acceso por Roles
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Session.php';

class AuthHelper {

    /**
     * Inicia sesión para un usuario dado
     *
     * @param array $user
     */
    public static function login($user) {
        Session::init();
        Session::set('user_id', (int)$user['id']);
        Session::set('user_nombre', $user['nombre']);
        Session::set('user_username', isset($user['username']) ? $user['username'] : '');
        Session::set('user_email', isset($user['email']) ? $user['email'] : '');
        Session::set('user_rol', $user['rol']);
    }

    /**
     * Cierra la sesión activa
     */
    public static function logout() {
        Session::init();
        Session::remove('user_id');
        Session::remove('user_nombre');
        Session::remove('user_username');
        Session::remove('user_email');
        Session::remove('user_rol');
    }

    /**
     * Verifica si hay un usuario autenticado
     *
     * @return bool
     */
    public static function isLogged() {
        Session::init();
        return Session::has('user_id') && Session::get('user_id') > 0;
    }

    /**
     * Verifica si el usuario autenticado tiene rol de Administrador
     *
     * @return bool
     */
    public static function isAdmin() {
        Session::init();
        return self::isLogged() && Session::get('user_rol') === 'ADMIN';
    }

    /**
     * Retorna los datos del usuario autenticado
     *
     * @return array|null
     */
    public static function user() {
        if (!self::isLogged()) {
            return null;
        }

        return [
            'id'       => Session::get('user_id'),
            'nombre'   => Session::get('user_nombre'),
            'username' => Session::get('user_username'),
            'email'    => Session::get('user_email'),
            'rol'      => Session::get('user_rol')
        ];
    }

    /**
     * Retorna el ID del usuario activo
     *
     * @return int|null
     */
    public static function id() {
        return Session::get('user_id');
    }

    /**
     * Retorna el nombre del usuario activo
     *
     * @return string
     */
    public static function nombre() {
        return Session::get('user_nombre', 'Invitado');
    }

    /**
     * Retorna el nombre de usuario (username) del usuario activo
     *
     * @return string
     */
    public static function username() {
        return Session::get('user_username', 'invitado');
    }

    /**
     * Retorna el rol del usuario activo
     *
     * @return string
     */
    public static function rol() {
        return Session::get('user_rol', 'INVITADO');
    }

    /**
     * Middleware: Exige inicio de sesión
     */
    public static function requireLogin() {
        if (!self::isLogged()) {
            Session::setFlash('warning', 'Debe iniciar sesión para acceder a esta sección.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Middleware: Exige rol de Administrador
     */
    public static function requireAdmin() {
        self::requireLogin();

        if (!self::isAdmin()) {
            Session::setFlash('error', 'Acceso denegado: Se requieren permisos de Administrador.');
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }
}
