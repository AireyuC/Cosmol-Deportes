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

        $this->view('auth/login', [
            'pageTitle' => 'Acceso Administrativo | Olimpiadas COSMOL'
        ], null); // Sin layout envolvente
    }

    /**
     * Procesa la solicitud de inicio de sesión
     */
    public function processLogin() {
        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado. Por favor, intente nuevamente.');
            $this->redirect('/login');
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
            Session::setFlash('error', 'Las credenciales proporcionadas no son válidas.');
            $this->view('auth/login', [
                'pageTitle'   => 'Acceso Administrativo | Olimpiadas COSMOL',
                'oldUsername' => $username
            ], null);
            return;
        }

        if ($user['estado'] !== 'Activo') {
            Session::setFlash('error', 'Su cuenta de administrador se encuentra inactiva. Contacte al comité organizador.');
            $this->redirect('/login');
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

        // Iniciar sesión administrativa
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
