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
        if (AuthHelper::isLogged()) {
            $this->redirect('/');
        }

        $this->view('auth/login', [
            'pageTitle' => 'Iniciar Sesión'
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
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('auth/login', [
                'pageTitle' => 'Iniciar Sesión',
                'oldEmail'  => isset($_POST['email']) ? $_POST['email'] : ''
            ], null);
            return;
        }

        $email = strtolower(trim($_POST['email']));
        $password = $_POST['password'];

        $user = $this->usuarioModel->findByEmail($email);

        if (!$user || !$this->usuarioModel->verifyPassword($password, $user['password'])) {
            Session::setFlash('error', 'Las credenciales proporcionadas no son válidas.');
            $this->view('auth/login', [
                'pageTitle' => 'Iniciar Sesión',
                'oldEmail'  => $email
            ], null);
            return;
        }

        if ($user['estado'] !== 'Activo') {
            Session::setFlash('error', 'Su cuenta se encuentra inactiva. Contacte al comité organizador.');
            $this->redirect('/login');
        }

        // Iniciar sesión
        AuthHelper::login($user);
        Session::setFlash('success', '¡Bienvenido(a), ' . $user['nombre'] . '!');

        $this->redirect('/');
    }

    /**
     * Muestra el formulario de registro de usuario
     */
    public function showRegister() {
        if (AuthHelper::isLogged()) {
            $this->redirect('/');
        }

        $this->view('auth/register', [
            'pageTitle' => 'Registro de Usuario'
        ], null);
    }

    /**
     * Procesa el registro de un nuevo usuario
     */
    public function processRegister() {
        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido. Intente nuevamente.');
            $this->redirect('/registro');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'                => 'required|min:3|max:100',
            'email'                 => 'required|email|max:150',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|matches:password'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('auth/register', [
                'pageTitle' => 'Registro de Usuario',
                'old'       => $_POST
            ], null);
            return;
        }

        $email = strtolower(trim($_POST['email']));

        // Verificar si ya existe el correo
        $existing = $this->usuarioModel->findByEmail($email);
        if ($existing) {
            Session::setFlash('error', 'El correo electrónico ya está registrado.');
            $this->view('auth/register', [
                'pageTitle' => 'Registro de Usuario',
                'old'       => $_POST
            ], null);
            return;
        }

        // Crear usuario con rol USUARIO
        $newUserId = $this->usuarioModel->create([
            'nombre'   => $_POST['nombre'],
            'email'    => $email,
            'password' => $_POST['password'],
            'rol'      => 'USUARIO',
            'estado'   => 'Activo'
        ]);

        if ($newUserId > 0) {
            $newUser = $this->usuarioModel->findById($newUserId);
            AuthHelper::login($newUser);

            Session::setFlash('success', '¡Cuenta creada con éxito! Bienvenido(a) a las Olimpiadas Cosmol.');
            $this->redirect('/');
        } else {
            Session::setFlash('error', 'No se pudo crear la cuenta. Intente nuevamente.');
            $this->redirect('/registro');
        }
    }

    /**
     * Cierra la sesión activa
     */
    public function logout() {
        AuthHelper::logout();
        Session::setFlash('info', 'Sesión cerrada correctamente.');
        $this->redirect('/login');
    }
}
