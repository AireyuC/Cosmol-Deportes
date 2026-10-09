<?php
/**
 * Controlador de Delegaciones Institucionales
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Delegacion.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class DelegacionController extends Controller {

    /** @var Delegacion */
    private $delegacionModel;

    public function __construct() {
        $this->delegacionModel = new Delegacion();
    }

    /**
     * Listado general de delegaciones
     */
    public function index() {
        AuthHelper::requireLogin();

        $delegaciones = $this->delegacionModel->getAll();
        $this->view('admin/delegaciones/index', [
            'pageTitle'    => 'Delegaciones Institucionales',
            'delegaciones' => $delegaciones
        ]);
    }

    /**
     * Formulario de creación
     */
    public function create() {
        AuthHelper::requireAdmin();

        $this->view('admin/delegaciones/create', [
            'pageTitle' => 'Registrar Nueva Delegación'
        ]);
    }

    /**
     * Guarda la delegación
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/delegaciones');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre' => 'required|min:3|max:150'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('admin/delegaciones/create', [
                'pageTitle' => 'Registrar Nueva Delegación',
                'old'       => $_POST
            ]);
            return;
        }

        $this->delegacionModel->create($_POST);
        Session::setFlash('success', 'Delegación registrada correctamente.');
        $this->redirect('/delegaciones');
    }

    /**
     * Formulario de edición
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $delegacion = $this->delegacionModel->getById($id);
        if (!$delegacion) {
            Session::setFlash('error', 'Delegación no encontrada.');
            $this->redirect('/delegaciones');
        }

        $this->view('admin/delegaciones/edit', [
            'pageTitle'  => 'Editar Delegación',
            'delegacion' => $delegacion
        ]);
    }

    /**
     * Actualiza la delegación
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/delegaciones');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre' => 'required|min:3|max:150'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/delegaciones/' . $id . '/editar');
            return;
        }

        $this->delegacionModel->updateDelegacion($id, $_POST);
        Session::setFlash('success', 'Delegación actualizada correctamente.');
        $this->redirect('/delegaciones');
    }

    /**
     * Elimina una delegación
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/delegaciones');
        }

        try {
            $this->delegacionModel->deleteDelegacion($id);
            Session::setFlash('success', 'Delegación eliminada.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se puede eliminar la delegación porque tiene equipos o atletas vinculados.');
        }

        $this->redirect('/delegaciones');
    }
}
