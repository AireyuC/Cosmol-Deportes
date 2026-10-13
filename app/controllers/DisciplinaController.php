<?php
/**
 * Controlador de Disciplinas Deportivas y Reglas de Puntuación
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Disciplina.php';
require_once APP_PATH . '/models/ReglaPuntuacion.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class DisciplinaController extends Controller {

    /** @var Disciplina */
    private $disciplinaModel;

    public function __construct() {
        $this->disciplinaModel = new Disciplina();
    }

    /**
     * Listado de disciplinas y parámetros de puntuación
     */
    public function index() {
        AuthHelper::requireLogin();

        $disciplinas = $this->disciplinaModel->getAll();
        $this->view('admin/disciplinas/index', [
            'pageTitle'   => 'Disciplinas y Reglas de Puntuación',
            'disciplinas' => $disciplinas
        ]);
    }

    /**
     * Formulario de creación
     */
    public function create() {
        AuthHelper::requireAdmin();

        $this->view('admin/disciplinas/create', [
            'pageTitle' => 'Registrar Nueva Disciplina'
        ]);
    }

    /**
     * Guarda la nueva disciplina y su regla
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        // Normalización de datos de reglas
        if (isset($_POST['permite_empate']) && (string)$_POST['permite_empate'] === '0') {
            $_POST['pts_empate'] = 0;
        }
        if (isset($_POST['formato_sets']) && (string)$_POST['formato_sets'] === '0') {
            $_POST['sets_para_ganar'] = 0;
        }
        if (!isset($_POST['pts_derrota_ajustada']) || $_POST['pts_derrota_ajustada'] === '') {
            $_POST['pts_derrota_ajustada'] = 0;
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'       => 'required|min:3|max:100',
            'pts_victoria' => 'required|numeric',
            'pts_empate'   => 'required|numeric',
            'pts_derrota'  => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('admin/disciplinas/create', [
                'pageTitle' => 'Registrar Nueva Disciplina',
                'old'       => $_POST
            ]);
            return;
        }

        $this->disciplinaModel->create($_POST);
        Session::setFlash('success', 'Disciplina y reglas de puntuación registradas con éxito.');
        $this->redirect('/disciplinas');
    }

    /**
     * Formulario de edición
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $disciplina = $this->disciplinaModel->getById($id);
        if (!$disciplina) {
            Session::setFlash('error', 'Disciplina no encontrada.');
            $this->redirect('/disciplinas');
        }

        $this->view('admin/disciplinas/edit', [
            'pageTitle'  => 'Editar Disciplina y Reglas',
            'disciplina' => $disciplina
        ]);
    }

    /**
     * Actualiza la disciplina
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        // Normalización de datos de reglas
        if (isset($_POST['permite_empate']) && (string)$_POST['permite_empate'] === '0') {
            $_POST['pts_empate'] = 0;
        }
        if (isset($_POST['formato_sets']) && (string)$_POST['formato_sets'] === '0') {
            $_POST['sets_para_ganar'] = 0;
        }
        if (!isset($_POST['pts_derrota_ajustada']) || $_POST['pts_derrota_ajustada'] === '') {
            $_POST['pts_derrota_ajustada'] = 0;
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'       => 'required|min:3|max:100',
            'pts_victoria' => 'required|numeric',
            'pts_empate'   => 'required|numeric',
            'pts_derrota'  => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/disciplinas/' . $id . '/editar');
            return;
        }

        $this->disciplinaModel->updateDisciplina($id, $_POST);
        Session::setFlash('success', 'Disciplina y reglas de puntuación actualizadas.');
        $this->redirect('/disciplinas');
    }

    /**
     * Elimina una disciplina
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        try {
            $this->disciplinaModel->deleteDisciplina($id);
            Session::setFlash('success', 'Disciplina eliminada correctamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se puede eliminar la disciplina porque tiene torneos o partidos vinculados.');
        }

        $this->redirect('/disciplinas');
    }
}
