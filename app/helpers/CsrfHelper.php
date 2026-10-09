<?php
/**
 * Helper de Protección contra CSRF (Cross-Site Request Forgery)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Session.php';

class CsrfHelper {

    /**
     * Genera o devuelve el token CSRF activo
     *
     * @return string
     */
    public static function getToken() {
        Session::init();
        $token = Session::get('csrf_token');
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return $token;
    }

    /**
     * Devuelve un campo HTML oculto con el token CSRF
     *
     * @return string
     */
    public static function field() {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Valida el token enviado por POST contra el guardado en sesión
     *
     * @param string|null $token
     * @return bool
     */
    public static function validate($token = null) {
        Session::init();
        if ($token === null) {
            $token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
        }
        $sessionToken = Session::get('csrf_token');
        if (empty($token) || empty($sessionToken)) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }
}

/**
 * Función helper global para escapar cadenas HTML (previene XSS)
 *
 * @param mixed $value
 * @return string
 */
function e($value) {
    if ($value === null) {
        return '';
    }
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
