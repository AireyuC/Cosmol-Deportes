<?php
/**
 * Controlador de Gestión de Olimpiadas (Macro-Evento)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Olimpiada.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class OlimpiadaController extends Controller {

    /** @var Olimpiada */
    private $olimpiadaModel;

    public function __construct() {
        $this->olimpiadaModel = new Olimpiada();
    }

    /**
     * Listado general de Olimpiadas
     */
    public function index() {
        AuthHelper::requireLogin();

        $olimpiadas = $this->olimpiadaModel->getAll();
        $this->view('admin/olimpiadas/index', [
            'pageTitle'  => 'Gestión de Olimpiadas',
            'olimpiadas' => $olimpiadas
        ]);
    }

    /**
     * Formulario para crear una nueva Olimpiada
     */
    public function create() {
        AuthHelper::requireAdmin();

        $this->view('admin/olimpiadas/create', [
            'pageTitle' => 'Registrar Nueva Olimpiada'
        ]);
    }

    /**
     * Guarda la nueva Olimpiada
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/olimpiadas');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre' => 'required|min:4|max:150',
            'anio'   => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('admin/olimpiadas/create', [
                'pageTitle' => 'Registrar Nueva Olimpiada',
                'old'       => $_POST
            ]);
            return;
        }

        $id = $this->olimpiadaModel->create($_POST);
        if ($id) {
            Session::setFlash('success', 'Olimpiada registrada exitosamente.');
            $this->redirect('/olimpiadas');
        } else {
            Session::setFlash('error', 'No se pudo registrar la olimpiada.');
            $this->redirect('/olimpiadas/crear');
        }
    }

    /**
     * Formulario de edición
     *
     * @param int $id ID de la Olimpiada a editar
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $olimpiada = $this->olimpiadaModel->getById($id);
        if (!$olimpiada) {
            Session::setFlash('error', 'Olimpiada no encontrada.');
            $this->redirect('/olimpiadas');
            return;
        }

        $this->view('admin/olimpiadas/edit', [
            'pageTitle' => 'Editar Olimpiada',
            'olimpiada' => $olimpiada
        ]);
    }

    /**
     * Actualiza la Olimpiada
     *
     * @param int $id ID de la Olimpiada a actualizar
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/olimpiadas');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre' => 'required|min:4|max:150',
            'anio'   => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/olimpiadas/' . $id . '/editar');
            return;
        }

        $this->olimpiadaModel->updateOlimpiada($id, $_POST);
        Session::setFlash('success', 'Olimpiada actualizada correctamente.');
        $this->redirect('/olimpiadas');
    }

    /**
     * Elimina una Olimpiada
     *
     * @param int $id ID de la Olimpiada a eliminar
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/olimpiadas');
        }

        $this->olimpiadaModel->deleteOlimpiada($id);
        Session::setFlash('success', 'Olimpiada eliminada.');
        $this->redirect('/olimpiadas');
    }
}
