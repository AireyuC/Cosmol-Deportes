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
            'username' => 'required|min:3',
            'password' => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('auth/login', [
                'pageTitle'   => 'Iniciar Sesión',
                'oldUsername' => isset($_POST['username']) ? $_POST['username'] : ''
            ], null);
            return;
        }

        $username = strtolower(trim($_POST['username']));
        $password = $_POST['password'];

        // Buscar por nombre de usuario (o permitir correo como identificador alternativo)
        $user = $this->usuarioModel->findByUsernameOrEmail($username);

        if (!$user || !$this->usuarioModel->verifyPassword($password, $user['password'])) {
            Session::setFlash('error', 'Las credenciales proporcionadas no son válidas.');
            $this->view('auth/login', [
                'pageTitle'   => 'Iniciar Sesión',
                'oldUsername' => $username
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

        $rules = [
            'nombre'                => 'required|min:3|max:100',
            'username'              => 'required|alpha_dash|min:3|max:50',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|matches:password'
        ];

        // Validar email si fue proporcionado
        if (!empty($_POST['email'])) {
            $rules['email'] = 'email|max:150';
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, $rules);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('auth/register', [
                'pageTitle' => 'Registro de Usuario',
                'old'       => $_POST
            ], null);
            return;
        }

        $username = strtolower(trim($_POST['username']));
        $email = !empty($_POST['email']) ? strtolower(trim($_POST['email'])) : null;

        // Verificar si ya existe el nombre de usuario
        $existingUsername = $this->usuarioModel->findByUsername($username);
        if ($existingUsername) {
            Session::setFlash('error', 'El nombre de usuario "' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '" ya está en uso. Por favor, elija otro.');
            $this->view('auth/register', [
                'pageTitle' => 'Registro de Usuario',
                'old'       => $_POST
            ], null);
            return;
        }

        // Verificar si ya existe el correo (si fue ingresado)
        if ($email !== null) {
            $existingEmail = $this->usuarioModel->findByEmail($email);
            if ($existingEmail) {
                Session::setFlash('error', 'El correo electrónico ya está registrado.');
                $this->view('auth/register', [
                    'pageTitle' => 'Registro de Usuario',
                    'old'       => $_POST
                ], null);
                return;
            }
        }

        // Crear usuario con rol USUARIO
        $newUserId = $this->usuarioModel->create([
            'nombre'   => $_POST['nombre'],
            'username' => $username,
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
