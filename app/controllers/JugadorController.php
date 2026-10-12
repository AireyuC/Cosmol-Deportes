<?php
/**
 * Controlador de Jugadores / Nómina Oficial de Atletas
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Jugador.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class JugadorController extends Controller {

    /** @var Jugador */
    private $jugadorModel;

    /** @var Equipo */
    private $equipoModel;

    /** @var Torneo */
    private $torneoModel;

    public function __construct() {
        $this->jugadorModel = new Jugador();
        $this->equipoModel = new Equipo();
        $this->torneoModel = new Torneo();
    }

    /**
     * Listado general del padrón de atletas con filtros
     */
    public function index() {
        AuthHelper::requireLogin();

        $filtroEquipo = isset($_GET['equipo_id']) && $_GET['equipo_id'] !== '' ? (int)$_GET['equipo_id'] : null;
        $filtroTorneo = isset($_GET['torneo_id']) && $_GET['torneo_id'] !== '' ? (int)$_GET['torneo_id'] : null;
        $filtroEstado = isset($_GET['estado']) && $_GET['estado'] !== '' ? $_GET['estado'] : null;

        $jugadores = $this->jugadorModel->getAll($filtroEquipo, $filtroTorneo, $filtroEstado);
        $equipos = $this->equipoModel->getAll();
        $torneos = $this->torneoModel->getAll();

        $this->view('admin/jugadores/index', [
            'pageTitle'     => 'Nómina Oficial de Atletas',
            'jugadores'     => $jugadores,
            'equipos'       => $equipos,
            'torneos'       => $torneos,
            'filtroEquipo'  => $filtroEquipo,
            'filtroTorneo'  => $filtroTorneo,
            'filtroEstado'  => $filtroEstado
        ]);
    }

    /**
     * Visualización de la plantilla de jugadores por equipo
     *
     * @param int $equipoId
     */
    public function porEquipo($equipoId) {
        AuthHelper::requireLogin();

        $equipo = $this->equipoModel->getById($equipoId);
        if (!$equipo) {
            Session::setFlash('error', 'El equipo solicitado no existe.');
            $this->redirect('/equipos');
        }

        $jugadores = $this->jugadorModel->getByEquipoId($equipoId);

        $this->view('admin/jugadores/por_equipo', [
            'pageTitle' => 'Plantilla: ' . $equipo['nombre'],
            'equipo'    => $equipo,
            'jugadores' => $jugadores
        ]);
    }

    /**
     * Ficha de atleta / Credencial
     *
     * @param int $id
     */
    public function show($id) {
        AuthHelper::requireLogin();

        $jugador = $this->jugadorModel->getById($id);
        if (!$jugador) {
            Session::setFlash('error', 'El atleta solicitado no existe.');
            $this->redirect('/jugadores');
        }

        $this->view('admin/jugadores/show', [
            'pageTitle' => 'Ficha de Atleta: ' . $jugador['nombres'] . ' ' . $jugador['apellidos'],
            'jugador'   => $jugador
        ]);
    }

    /**
     * Formulario de inscripción de jugador
     */
    public function create() {
        AuthHelper::requireAdmin();

        $equipoId = isset($_GET['equipo_id']) ? (int)$_GET['equipo_id'] : null;
        $equipos = $this->equipoModel->getAll();

        $this->view('admin/jugadores/create', [
            'pageTitle' => 'Registrar Atleta en Nómina',
            'equipos'   => $equipos,
            'equipoId'  => $equipoId
        ]);
    }

    /**
     * Guarda el nuevo jugador con validación de dorsal único por equipo
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/jugadores');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'equipo_id' => 'required|numeric',
            'documento' => 'required|min:3|max:30',
            'nombres'   => 'required|min:2|max:100',
            'apellidos' => 'required|min:2|max:100',
            'dorsal'    => 'required|numeric'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/jugadores/crear' . (!empty($_POST['equipo_id']) ? '?equipo_id=' . (int)$_POST['equipo_id'] : ''));
        }

        $equipoId = (int)$_POST['equipo_id'];
        $dorsal = (int)$_POST['dorsal'];

        // REGLA CRÍTICA: Validar dorsal único por equipo
        if ($this->jugadorModel->dorsalExisteEnEquipo($equipoId, $dorsal)) {
            Session::setFlash('error', 'El dorsal #' . $dorsal . ' ya está ocupado por otro jugador en este equipo.');
            $this->redirect('/jugadores/crear?equipo_id=' . $equipoId);
        }

        try {
            $nuevoId = $this->jugadorModel->create($_POST);
            Session::setFlash('success', 'Atleta ' . e($_POST['nombres']) . ' ' . e($_POST['apellidos']) . ' (Dorsal #' . $dorsal . ') registrado con éxito.');

            if (!empty($_POST['volver_a_equipo'])) {
                $this->redirect('/equipos/' . $equipoId . '/jugadores');
            } else {
                $this->redirect('/jugadores');
            }
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al guardar el atleta: ' . $e->getMessage());
            $this->redirect('/jugadores/crear?equipo_id=' . $equipoId);
        }
    }

    /**
     * Formulario de edición de jugador
     *
     * @param int $id
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $jugador = $this->jugadorModel->getById($id);
        if (!$jugador) {
            Session::setFlash('error', 'El atleta solicitado no existe.');
            $this->redirect('/jugadores');
        }

        $equipos = $this->equipoModel->getAll();

        $this->view('admin/jugadores/edit', [
            'pageTitle' => 'Editar Atleta: ' . $jugador['nombres'] . ' ' . $jugador['apellidos'],
            'jugador'   => $jugador,
            'equipos'   => $equipos
        ]);
    }

    /**
     * Actualiza los datos del jugador validando dorsal único
     *
     * @param int $id
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/jugadores');
        }

        $jugador = $this->jugadorModel->getById($id);
        if (!$jugador) {
            Session::setFlash('error', 'El atleta solicitado no existe.');
            $this->redirect('/jugadores');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'equipo_id' => 'required|numeric',
            'documento' => 'required|min:3|max:30',
            'nombres'   => 'required|min:2|max:100',
            'apellidos' => 'required|min:2|max:100',
            'dorsal'    => 'required|numeric',
            'estado'    => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/jugadores/' . $id . '/editar');
        }

        $equipoId = (int)$_POST['equipo_id'];
        $dorsal = (int)$_POST['dorsal'];

        // Validar dorsal único excluyendo el ID actual
        if ($this->jugadorModel->dorsalExisteEnEquipo($equipoId, $dorsal, $id)) {
            Session::setFlash('error', 'El dorsal #' . $dorsal . ' ya está asignado a otro jugador de este equipo.');
            $this->redirect('/jugadores/' . $id . '/editar');
        }

        try {
            $this->jugadorModel->updateJugador($id, $_POST);
            Session::setFlash('success', 'Datos del atleta actualizados correctamente.');
            $this->redirect('/equipos/' . $equipoId . '/jugadores');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al actualizar el atleta: ' . $e->getMessage());
            $this->redirect('/jugadores/' . $id . '/editar');
        }
    }

    /**
     * Cambio rápido de estado del jugador (Habilitado, Suspendido, Inactivo)
     *
     * @param int $id
     */
    public function cambiarEstado($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/jugadores');
        }

        $nuevoEstado = isset($_POST['estado']) ? $_POST['estado'] : 'Habilitado';
        $equipoId = isset($_POST['equipo_id']) ? (int)$_POST['equipo_id'] : 0;

        try {
            $this->jugadorModel->cambiarEstado($id, $nuevoEstado);
            Session::setFlash('success', 'Estado del jugador actualizado a "' . e($nuevoEstado) . '".');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }

        if ($equipoId > 0) {
            $this->redirect('/equipos/' . $equipoId . '/jugadores');
        } else {
            $this->redirect('/jugadores');
        }
    }

    /**
     * Elimina un jugador
     *
     * @param int $id
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/jugadores');
        }

        $equipoId = isset($_POST['equipo_id']) ? (int)$_POST['equipo_id'] : 0;

        try {
            $this->jugadorModel->deleteJugador($id);
            Session::setFlash('success', 'Atleta retirado de la nómina.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se pudo eliminar el jugador: ' . $e->getMessage());
        }

        if ($equipoId > 0) {
            $this->redirect('/equipos/' . $equipoId . '/jugadores');
        } else {
            $this->redirect('/jugadores');
        }
    }
}
