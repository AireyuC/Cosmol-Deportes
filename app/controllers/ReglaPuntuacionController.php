<?php
/**
 * Controlador de Reglas de Puntuación y Deportes Base
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Disciplina.php';
require_once APP_PATH . '/models/ReglaPuntuacion.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class ReglaPuntuacionController extends Controller {

    /** @var Disciplina */
    private $disciplinaModel;

    public function __construct() {
        $this->disciplinaModel = new Disciplina();
    }

    /**
     * Listado de reglas de puntuación por deporte
     */
    public function index() {
        AuthHelper::requireLogin();

        $disciplinas = $this->disciplinaModel->getAll();
        $this->view('admin/reglas_puntuacion/index', [
            'pageTitle'   => 'Reglas de Puntuación por Deporte',
            'disciplinas' => $disciplinas
        ]);
    }

    /**
     * Formulario de creación
     */
    public function create() {
        AuthHelper::requireAdmin();

        $this->view('admin/reglas_puntuacion/create', [
            'pageTitle' => 'Registrar Nueva Regla de Puntuación'
        ]);
    }

    /**
     * Guarda la nueva regla de deporte
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/reglas-puntuacion');
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
            $this->view('admin/reglas_puntuacion/create', [
                'pageTitle' => 'Registrar Nueva Regla de Puntuación',
                'old'       => $_POST
            ]);
            return;
        }

        $this->disciplinaModel->create($_POST);
        Session::setFlash('success', 'Regla de puntuación registrada con éxito.');
        $this->redirect('/reglas-puntuacion');
    }

    /**
     * Formulario de edición
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $disciplina = $this->disciplinaModel->getById($id);
        if (!$disciplina) {
            Session::setFlash('error', 'Regla no encontrada.');
            $this->redirect('/reglas-puntuacion');
        }

        $this->view('admin/reglas_puntuacion/edit', [
            'pageTitle'  => 'Editar Regla de Puntuación',
            'disciplina' => $disciplina
        ]);
    }

    /**
     * Actualiza la regla
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/reglas-puntuacion');
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
            $this->redirect('/reglas-puntuacion/' . $id . '/editar');
            return;
        }

        $this->disciplinaModel->updateDisciplina($id, $_POST);
        Session::setFlash('success', 'Regla de puntuación actualizada.');
        $this->redirect('/reglas-puntuacion');
    }

    /**
     * Elimina una regla
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/reglas-puntuacion');
        }

        try {
            $this->disciplinaModel->deleteDisciplina($id);
            Session::setFlash('success', 'Regla de puntuación eliminada correctamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se puede eliminar la regla porque tiene competencias o partidos vinculados.');
        }

        $this->redirect('/reglas-puntuacion');
    }
}
