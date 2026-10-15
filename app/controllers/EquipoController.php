<?php
/**
 * Controlador de Equipos Deportivos
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/models/Delegacion.php';
require_once APP_PATH . '/models/Grupo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class EquipoController extends Controller {

    /** @var Equipo */
    private $equipoModel;

    /** @var Torneo */
    private $torneoModel;

    /** @var Delegacion */
    private $delegacionModel;

    /** @var Grupo */
    private $grupoModel;

    public function __construct() {
        $this->equipoModel = new Equipo();
        $this->torneoModel = new Torneo();
        $this->delegacionModel = new Delegacion();
        $this->grupoModel = new Grupo();
    }

    /**
     * Listado general de equipos con filtros
     */
    public function index() {
        AuthHelper::requireLogin();

        $filtroTorneo = isset($_GET['torneo_id']) && $_GET['torneo_id'] !== '' ? (int)$_GET['torneo_id'] : null;
        $filtroDelegacion = isset($_GET['delegacion_id']) && $_GET['delegacion_id'] !== '' ? (int)$_GET['delegacion_id'] : null;

        $equipos = $this->equipoModel->getAll($filtroTorneo, $filtroDelegacion);
        $torneos = $this->torneoModel->getAll();
        $delegaciones = $this->delegacionModel->getAll();

        $this->view('admin/equipos/index', [
            'pageTitle'        => 'Equipos Deportivos Registrados',
            'equipos'          => $equipos,
            'torneos'          => $torneos,
            'delegaciones'     => $delegaciones,
            'filtroTorneo'     => $filtroTorneo,
            'filtroDelegacion' => $filtroDelegacion
        ]);
    }

    /**
     * Formulario de registro de equipo
     */
    public function create() {
        AuthHelper::requireAdmin();

        $torneoId = isset($_GET['torneo_id']) ? (int)$_GET['torneo_id'] : null;

        $torneos = $this->torneoModel->getAll();
        $delegaciones = $this->delegacionModel->getAll();
        $grupos = $torneoId ? $this->grupoModel->getByTorneoId($torneoId) : [];

        $this->view('admin/equipos/create', [
            'pageTitle'    => 'Registrar Nuevo Equipo',
            'torneos'      => $torneos,
            'delegaciones' => $delegaciones,
            'grupos'       => $grupos,
            'torneoId'     => $torneoId
        ]);
    }

    /**
     * Guarda el nuevo equipo
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/equipos');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'        => 'required|min:3|max:150',
            'torneo_id'     => 'required|numeric',
            'delegacion_id' => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/equipos/crear');
        }

        try {
            $nuevoId = $this->equipoModel->create($_POST);
            Session::setFlash('success', 'Equipo "' . e($_POST['nombre']) . '" registrado correctamente.');

            if (!empty($_POST['volver_a_torneo'])) {
                $this->redirect('/disciplinas/' . (int)$_POST['torneo_id']);
            } else {
                $this->redirect('/equipos');
            }
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al guardar el equipo: ' . $e->getMessage());
            $this->redirect('/equipos/crear');
        }
    }

    /**
     * Formulario de edición de equipo
     *
     * @param int $id
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $equipo = $this->equipoModel->getById($id);
        if (!$equipo) {
            Session::setFlash('error', 'El equipo solicitado no existe.');
            $this->redirect('/equipos');
        }

        $torneos = $this->torneoModel->getAll();
        $delegaciones = $this->delegacionModel->getAll();
        $grupos = $this->grupoModel->getByTorneoId($equipo['torneo_id']);

        $this->view('admin/equipos/edit', [
            'pageTitle'    => 'Editar Equipo: ' . $equipo['nombre'],
            'equipo'       => $equipo,
            'torneos'      => $torneos,
            'delegaciones' => $delegaciones,
            'grupos'       => $grupos
        ]);
    }

    /**
     * Actualiza el equipo
     *
     * @param int $id
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/equipos');
        }

        $equipo = $this->equipoModel->getById($id);
        if (!$equipo) {
            Session::setFlash('error', 'El equipo solicitado no existe.');
            $this->redirect('/equipos');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'        => 'required|min:3|max:150',
            'torneo_id'     => 'required|numeric',
            'delegacion_id' => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/equipos/' . $id . '/editar');
        }

        try {
            $this->equipoModel->updateEquipo($id, $_POST);
            Session::setFlash('success', 'Equipo actualizado correctamente.');
            $this->redirect('/equipos');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al actualizar el equipo: ' . $e->getMessage());
            $this->redirect('/equipos/' . $id . '/editar');
        }
    }

    /**
     * Elimina un equipo
     *
     * @param int $id
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/equipos');
        }

        try {
            $this->equipoModel->deleteEquipo($id);
            Session::setFlash('success', 'Equipo eliminado satisfactoriamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se pudo eliminar el equipo: ' . $e->getMessage());
        }

        $this->redirect('/equipos');
    }

    /**
     * Endpoint JSON para obtener grupos dinámicamente según el torneo seleccionado
     *
     * @param int $torneoId
     */
    public function gruposJson($torneoId) {
        AuthHelper::requireLogin();
        $grupos = $this->grupoModel->getByTorneoId((int)$torneoId);
        $this->json($grupos);
    }
}
