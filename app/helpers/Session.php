<?php
/**
 * Helper de Gestión de Sesiones y Mensajes Flash
 * Compatible con PHP 7.3
 */

class Session {

    /**
     * Inicia la sesión si no está iniciada
     */
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Parámetros de seguridad de sesión
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            session_start();
        }
    }

    /**
     * Asigna un valor en la sesión
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    /**
     * Obtiene un valor de la sesión
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null) {
        self::init();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Verifica si existe una clave en sesión
     *
     * @param string $key
     * @return bool
     */
    public static function has($key) {
        self::init();
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina una clave de la sesión
     *
     * @param string $key
     */
    public static function remove($key) {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destruye la sesión completa
     */
    public static function destroy() {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Establece un mensaje flash (desaparece tras leerlo)
     *
     * @param string $type ('success', 'error', 'warning', 'info')
     * @param string $message
     */
    public static function setFlash($type, $message) {
        self::init();
        $_SESSION['flash_messages'][$type] = $message;
    }

    /**
     * Obtiene y consume un mensaje flash
     *
     * @param string $type
     * @return string|null
     */
    public static function getFlash($type) {
        self::init();
        if (isset($_SESSION['flash_messages'][$type])) {
            $msg = $_SESSION['flash_messages'][$type];
            unset($_SESSION['flash_messages'][$type]);
            return $msg;
        }
        return null;
    }

    /**
     * Verifica si existe un mensaje flash del tipo indicado
     *
     * @param string $type
     * @return bool
     */
    public static function hasFlash($type) {
        self::init();
        return isset($_SESSION['flash_messages'][$type]);
    }
}
