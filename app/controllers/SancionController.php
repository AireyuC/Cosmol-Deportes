<?php
/**
 * Controlador del Régimen Disciplinario y Control de Sanciones
 * Administra el registro, consulta y levantamiento oficial de sanciones
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Sancion.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Jugador.php';
require_once APP_PATH . '/models/Partido.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class SancionController extends Controller {

    /** @var Sancion */
    private $sancionModel;

    /** @var Torneo */
    private $torneoModel;

    /** @var Equipo */
    private $equipoModel;

    /** @var Jugador */
    private $jugadorModel;

    /** @var Partido */
    private $partidoModel;

    public function __construct() {
        $this->sancionModel = new Sancion();
        $this->torneoModel  = new Torneo();
        $this->equipoModel  = new Equipo();
        $this->jugadorModel = new Jugador();
        $this->partidoModel = new Partido();
    }

    /**
     * Boletín Disciplinario General con filtros y KPIs
     */
    public function index() {
        AuthHelper::requireLogin();

        $filtroTorneo = isset($_GET['torneo_id']) && $_GET['torneo_id'] !== '' ? (int)$_GET['torneo_id'] : null;
        $filtroEstado = isset($_GET['estado']) && $_GET['estado'] !== '' ? trim($_GET['estado']) : null;
        $filtroTipo   = isset($_GET['tipo']) && $_GET['tipo'] !== '' ? trim($_GET['tipo']) : null;

        $sanciones    = $this->sancionModel->getAll($filtroTorneo, $filtroEstado, $filtroTipo);
        $torneos      = $this->torneoModel->getAll();
        $estadisticas = $this->sancionModel->getEstadisticas($filtroTorneo);

        $this->view('admin/sanciones/index', [
            'pageTitle'    => 'Boletín Disciplinario y Control de Sanciones',
            'sanciones'    => $sanciones,
            'torneos'      => $torneos,
            'estadisticas' => $estadisticas,
            'filtroTorneo' => $filtroTorneo,
            'filtroEstado' => $filtroEstado,
            'filtroTipo'   => $filtroTipo
        ]);
    }

    /**
     * Formulario para registrar una nueva sanción disciplinaria
     */
    public function create() {
        AuthHelper::requireAdmin();

        $torneoId  = isset($_GET['torneo_id']) ? (int)$_GET['torneo_id'] : null;
        $jugadorId = isset($_GET['jugador_id']) ? (int)$_GET['jugador_id'] : null;
        $partidoId = isset($_GET['partido_id']) ? (int)$_GET['partido_id'] : null;

        $torneos = $this->torneoModel->getAll();
        $equipos = $torneoId ? $this->equipoModel->getAll($torneoId) : [];
        $partidos = $torneoId ? $this->partidoModel->getAll($torneoId) : [];

        $jugadorPreseleccionado = null;
        if ($jugadorId) {
            $jugadorPreseleccionado = $this->jugadorModel->getById($jugadorId);
            if ($jugadorPreseleccionado && !$torneoId) {
                // Inferir torneo si el jugador vino seleccionado
                $equipo = $this->equipoModel->getById($jugadorPreseleccionado['equipo_id']);
                if ($equipo) {
                    $torneoId = (int)$equipo['torneo_id'];
                    $equipos  = $this->equipoModel->getAll($torneoId);
                    $partidos = $this->partidoModel->getAll($torneoId);
                }
            }
        }

        $this->view('admin/sanciones/create', [
            'pageTitle'              => 'Registrar Sanción Disciplinaria',
            'torneos'                => $torneos,
            'equipos'                => $equipos,
            'partidos'               => $partidos,
            'torneoId'               => $torneoId,
            'jugadorId'              => $jugadorId,
            'partidoId'              => $partidoId,
            'jugadorPreseleccionado' => $jugadorPreseleccionado
        ]);
    }

    /**
     * Procesa y guarda la nueva sanción
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sanciones');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'torneo_id'    => 'required|numeric',
            'jugador_id'   => 'required|numeric',
            'tipo_sancion' => 'required',
            'motivo'       => 'required|min:5'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/sanciones/crear' . (!empty($_POST['torneo_id']) ? '?torneo_id=' . (int)$_POST['torneo_id'] : ''));
        }

        try {
            $nuevoId = $this->sancionModel->create($_POST);
            Session::setFlash('success', 'Sanción disciplinaria registrada correctamente en el boletín.');
            $this->redirect('/sanciones/' . $nuevoId);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al registrar la sanción: ' . $e->getMessage());
            $this->redirect('/sanciones/crear');
        }
    }

    /**
     * Muestra la ficha detallada de una sanción y el historial del jugador
     *
     * @param int $id
     */
    public function show($id) {
        AuthHelper::requireLogin();

        $sancion = $this->sancionModel->getById($id);
        if (!$sancion) {
            Session::setFlash('error', 'La sanción solicitada no existe.');
            $this->redirect('/sanciones');
        }

        $historialJugador = $this->sancionModel->getByJugadorId($sancion['jugador_id']);

        $this->view('admin/sanciones/show', [
            'pageTitle'        => 'Expediente Disciplinario #' . $sancion['id'] . ' &mdash; ' . $sancion['jugador_nombres'] . ' ' . $sancion['jugador_apellidos'],
            'sancion'          => $sancion,
            'historialJugador' => $historialJugador
        ]);
    }

    /**
     * ACCIÓN CLAVE: Quitar o Levantar Sanción
     * Procesa la anulación/indulto desde el modal y restaura la habilitación del jugador
     *
     * @param int $id
     */
    public function levantar($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sanciones/' . (int)$id);
        }

        $sancion = $this->sancionModel->getById($id);
        if (!$sancion) {
            Session::setFlash('error', 'La sanción no existe.');
            $this->redirect('/sanciones');
        }

        $motivoTexto = isset($_POST['motivo_levantamiento']) ? trim($_POST['motivo_levantamiento']) : '';
        $tipoResolucion = !empty($_POST['tipo_resolucion']) ? trim($_POST['tipo_resolucion']) : 'Resolución Administrativa';

        if (empty($motivoTexto) || strlen($motivoTexto) < 5) {
            Session::setFlash('error', 'Debe especificar un motivo válido de al menos 5 caracteres para levantar la sanción.');
            $this->redirect('/sanciones/' . (int)$id);
        }

        $motivoFinal = "[{$tipoResolucion}] " . $motivoTexto;
        $usuarioId   = AuthHelper::id();

        try {
            $this->sancionModel->levantarSancion($id, $motivoFinal, $usuarioId);
            Session::setFlash('success', 'Sanción #' . $id . ' levantada con éxito. El estado del atleta "' .
                e($sancion['jugador_nombres']) . ' ' . e($sancion['jugador_apellidos']) .
                '" ha sido reevaluado en el padrón oficial.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al levantar la sanción: ' . $e->getMessage());
        }

        if (!empty($_POST['volver_a_listado'])) {
            $this->redirect('/sanciones');
        } else {
            $this->redirect('/sanciones/' . (int)$id);
        }
    }

    /**
     * Registra un partido cumplido de la suspensión
     *
     * @param int $id
     */
    public function cumplirFecha($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sanciones/' . (int)$id);
        }

        try {
            $this->sancionModel->cumplirPartido($id);
            Session::setFlash('success', 'Fecha cumplida registrada para la sanción #' . (int)$id . '.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al registrar cumplimiento: ' . $e->getMessage());
        }

        $this->redirect('/sanciones/' . (int)$id);
    }

    /**
     * Elimina una sanción del registro
     *
     * @param int $id
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sanciones');
        }

        try {
            $this->sancionModel->deleteSancion($id);
            Session::setFlash('success', 'Sanción eliminada del boletín disciplinario.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al eliminar la sanción: ' . $e->getMessage());
        }

        $this->redirect('/sanciones');
    }

    /**
     * Endpoint API JSON para cargar jugadores dinámicamente según el equipo
     *
     * @param int $equipoId
     */
    public function apiJugadoresPorEquipo($equipoId) {
        AuthHelper::requireLogin();
        $jugadores = $this->jugadorModel->getByEquipoId((int)$equipoId);
        $this->json([
            'success'   => true,
            'jugadores' => $jugadores
        ]);
    }
}
