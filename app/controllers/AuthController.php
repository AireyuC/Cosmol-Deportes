<?php
/**
 * Controlador de Autenticación de Usuarios
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Usuario.php';
require_once APP_PATH . '/helpers/Validator.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';

class AuthController extends Controller {

    /** @var Usuario Modelo de usuario */
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Muestra el formulario de inicio de sesión
     */
    public function showLogin() {
        if (AuthHelper::isLogged() && AuthHelper::isAdmin()) {
            $this->redirect('/dashboard');
        }

        // Cabeceras de protección de seguridad
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

        $this->view('auth/login', [
            'pageTitle' => 'Acceso Administrativo | Olimpiadas COSMOL'
        ], null); // Sin layout envolvente
    }

    /**
     * Procesa la solicitud de inicio de sesión
     */
    public function processLogin() {
        // Cabeceras de protección de seguridad
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');

        // Control de bloqueo por intentos fallidos reiterados (Rate Limiting)
        $maxAttempts = 5;
        $lockoutMinutes = 10;
        $lockoutUntil = (int)Session::get('login_lockout_until', 0);

        if ($lockoutUntil > time()) {
            $minutosRestantes = ceil(($lockoutUntil - time()) / 60);
            Session::setFlash('error', "Demasiados intentos fallidos. Acceso bloqueado temporalmente por seguridad. Intente de nuevo en {$minutosRestantes} minuto(s).");
            $this->redirect('/login');
            return;
        }

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado. Por favor, intente nuevamente.');
            $this->redirect('/login');
            return;
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'username' => 'required|min:3',
            'password' => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('auth/login', [
                'pageTitle'   => 'Acceso Administrativo | Olimpiadas COSMOL',
                'oldUsername' => isset($_POST['username']) ? $_POST['username'] : ''
            ], null);
            return;
        }

        $username = strtolower(trim($_POST['username']));
        $password = $_POST['password'];

        // Buscar por nombre de usuario (o correo como identificador alternativo)
        $user = $this->usuarioModel->findByUsernameOrEmail($username);

        if (!$user || !$this->usuarioModel->verifyPassword($password, $user['password'])) {
            $intentos = (int)Session::get('login_failed_attempts', 0) + 1;
            Session::set('login_failed_attempts', $intentos);

            if ($intentos >= $maxAttempts) {
                Session::set('login_lockout_until', time() + ($lockoutMinutes * 60));
                Session::set('login_failed_attempts', 0);
                Session::setFlash('error', "Ha superado el límite de intentos permitidos. Por seguridad, el acceso ha sido bloqueado temporalmente por {$lockoutMinutes} minutos.");
            } else {
                $restantes = $maxAttempts - $intentos;
                Session::setFlash('error', "Credenciales no válidas. Intentos restantes antes del bloqueo temporal: {$restantes}.");
            }

            $this->view('auth/login', [
                'pageTitle'   => 'Acceso Administrativo | Olimpiadas COSMOL',
                'oldUsername' => $username
            ], null);
            return;
        }

        if ($user['estado'] !== 'Activo') {
            Session::setFlash('error', 'Su cuenta de administrador se encuentra inactiva. Contacte al comité organizador.');
            $this->redirect('/login');
            return;
        }

        // REGLA ESTRICTA DE ROLES: Solo los administradores pueden hacer login
        if ($user['rol'] !== 'ADMIN') {
            Session::setFlash('error', 'Acceso denegado: Solo el personal administrativo autorizado puede iniciar sesión.');
            $this->view('auth/login', [
                'pageTitle'   => 'Acceso Administrativo | Olimpiadas COSMOL',
                'oldUsername' => $username
            ], null);
            return;
        }

        // Limpiar intentos fallidos al iniciar sesión con éxito
        Session::remove('login_failed_attempts');
        Session::remove('login_lockout_until');

        // Iniciar sesión administrativa con regeneración de ID de sesión
        AuthHelper::login($user);
        Session::setFlash('success', '¡Bienvenido(a), Administrador ' . $user['nombre'] . '!');

        $this->redirect('/dashboard');
    }

    /**
     * El registro de usuarios públicos está deshabilitado
     */
    public function showRegister() {
        Session::setFlash('info', 'El registro de usuarios está deshabilitado. El portal informativo de las Olimpiadas COSMOL es de libre acceso.');
        $this->redirect('/');
    }

    /**
     * Procesa intento de registro deshabilitado
     */
    public function processRegister() {
        Session::setFlash('info', 'El registro de usuarios está deshabilitado. El portal informativo de las Olimpiadas COSMOL es de libre acceso.');
        $this->redirect('/');
    }

    /**
     * Cierra la sesión activa y regresa al portal público
     */
    public function logout() {
        AuthHelper::logout();
        Session::setFlash('info', 'Sesión administrativa cerrada correctamente.');
        $this->redirect('/');
    }
}
