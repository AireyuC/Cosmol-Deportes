<?php
/**
 * Controlador de Fixture, Programación y Actas de Partidos
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Partido.php';
require_once APP_PATH . '/models/PartidoSet.php';
require_once APP_PATH . '/models/Incidencia.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/models/Grupo.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Jugador.php';
require_once APP_PATH . '/models/Cancha.php';
require_once APP_PATH . '/models/Sede.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class PartidoController extends Controller {

    /** @var Partido */
    private $partidoModel;

    /** @var PartidoSet */
    private $partidoSetModel;

    /** @var Incidencia */
    private $incidenciaModel;

    /** @var Torneo */
    private $torneoModel;

    /** @var Grupo */
    private $grupoModel;

    /** @var Equipo */
    private $equipoModel;

    /** @var Jugador */
    private $jugadorModel;

    /** @var Cancha */
    private $canchaModel;

    public function __construct() {
        $this->partidoModel    = new Partido();
        $this->partidoSetModel = new PartidoSet();
        $this->incidenciaModel = new Incidencia();
        $this->torneoModel     = new Torneo();
        $this->grupoModel      = new Grupo();
        $this->equipoModel     = new Equipo();
        $this->jugadorModel    = new Jugador();
        $this->canchaModel     = new Cancha();
    }

    /**
     * Listado general de fixture y partidos con filtros
     */
    public function index() {
        AuthHelper::requireLogin();

        $filtroTorneo = isset($_GET['torneo_id']) && $_GET['torneo_id'] !== '' ? (int)$_GET['torneo_id'] : null;
        $filtroEstado = isset($_GET['estado']) && $_GET['estado'] !== '' ? trim($_GET['estado']) : null;
        $filtroFecha  = isset($_GET['fecha']) && $_GET['fecha'] !== '' ? trim($_GET['fecha']) : null;
        $filtroJornada = isset($_GET['jornada']) && $_GET['jornada'] !== '' ? trim($_GET['jornada']) : null;

        $partidos     = $this->partidoModel->getAll($filtroTorneo, $filtroEstado, $filtroFecha, $filtroJornada);
        $torneos      = $this->torneoModel->getAll();
        $estadisticas = $this->partidoModel->getEstadisticas($filtroTorneo);

        $this->view('admin/partidos/index', [
            'pageTitle'     => 'Fixture y Programación de Partidos',
            'partidos'      => $partidos,
            'torneos'       => $torneos,
            'estadisticas'  => $estadisticas,
            'filtroTorneo'  => $filtroTorneo,
            'filtroEstado'  => $filtroEstado,
            'filtroFecha'   => $filtroFecha,
            'filtroJornada' => $filtroJornada
        ]);
    }

    /**
     * Formulario para programar un nuevo partido
     */
    public function create() {
        AuthHelper::requireAdmin();

        $torneoId = isset($_GET['torneo_id']) ? (int)$_GET['torneo_id'] : null;

        $torneos = $this->torneoModel->getAll();
        $canchas = $this->canchaModel->getAllConSede();
        $grupos  = $torneoId ? $this->grupoModel->getByTorneoId($torneoId) : [];
        $equipos = $torneoId ? $this->equipoModel->getAll($torneoId) : [];

        $this->view('admin/partidos/create', [
            'pageTitle' => 'Programar Encuentro Deportivo',
            'torneos'   => $torneos,
            'canchas'   => $canchas,
            'grupos'    => $grupos,
            'equipos'   => $equipos,
            'torneoId'  => $torneoId
        ]);
    }

    /**
     * Registra un nuevo partido en el fixture
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'torneo_id'           => 'required|numeric',
            'equipo_local_id'     => 'required|numeric',
            'equipo_visitante_id' => 'required|numeric',
            'fase'                => 'required',
            'jornada'             => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/partidos/crear');
        }

        $localId     = (int)$_POST['equipo_local_id'];
        $visitanteId = (int)$_POST['equipo_visitante_id'];

        if ($localId === $visitanteId) {
            Session::setFlash('error', 'El equipo local y el equipo visitante no pueden ser el mismo.');
            $this->redirect('/partidos/crear?torneo_id=' . (int)$_POST['torneo_id']);
        }

        $canchaId = !empty($_POST['cancha_id']) ? (int)$_POST['cancha_id'] : null;
        $fecha    = !empty($_POST['fecha_partido']) ? trim($_POST['fecha_partido']) : null;
        $hora     = !empty($_POST['hora_partido']) ? trim($_POST['hora_partido']) : null;

        // Detección de colisión en cancha
        if ($canchaId && $fecha && $hora) {
            $colisionCancha = $this->partidoModel->verificarColisionCancha($canchaId, $fecha, $hora);
            if ($colisionCancha) {
                Session::setFlash('error', 'Conflicto de programación: La cancha seleccionada ya tiene programado el encuentro "' .
                    e($colisionCancha['local_nombre']) . ' vs ' . e($colisionCancha['visitante_nombre']) . '" a las ' .
                    substr($colisionCancha['hora_partido'], 0, 5) . '.');
                $this->redirect('/partidos/crear?torneo_id=' . (int)$_POST['torneo_id']);
            }

            // Detección de colisión de equipos
            $colisionLocal = $this->partidoModel->verificarColisionEquipo($localId, $fecha, $hora);
            if ($colisionLocal) {
                Session::setFlash('error', 'Conflicto de equipo: El equipo local ya tiene otro partido programado en esta fecha y horario.');
                $this->redirect('/partidos/crear?torneo_id=' . (int)$_POST['torneo_id']);
            }

            $colisionVisitante = $this->partidoModel->verificarColisionEquipo($visitanteId, $fecha, $hora);
            if ($colisionVisitante) {
                Session::setFlash('error', 'Conflicto de equipo: El equipo visitante ya tiene otro partido programado en esta fecha y horario.');
                $this->redirect('/partidos/crear?torneo_id=' . (int)$_POST['torneo_id']);
            }
        }

        try {
            $nuevoId = $this->partidoModel->create($_POST);
            Session::setFlash('success', 'Partido programado exitosamente en el fixture.');

            if (!empty($_POST['volver_a_torneo'])) {
                $this->redirect('/torneos/' . (int)$_POST['torneo_id']);
            } else {
                $this->redirect('/partidos/' . $nuevoId);
            }
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al programar el partido: ' . $e->getMessage());
            $this->redirect('/partidos/crear');
        }
    }

    /**
     * Muestra la ficha detallada de un partido y ruta GPS a la sede
     *
     * @param int $id
     */
    public function show($id) {
        AuthHelper::requireLogin();

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido solicitado no existe.');
            $this->redirect('/partidos');
        }

        $sets        = $this->partidoSetModel->getByPartidoId($id);
        $incidencias = $this->incidenciaModel->getByPartidoId($id);

        $this->view('admin/partidos/show', [
            'pageTitle'   => 'Ficha del Encuentro: ' . $partido['local_nombre'] . ' vs ' . $partido['visitante_nombre'],
            'partido'     => $partido,
            'sets'        => $sets,
            'incidencias' => $incidencias
        ]);
    }

    /**
     * Formulario de edición / reprogramación de un partido
     *
     * @param int $id
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect('/partidos');
        }

        $torneos = $this->torneoModel->getAll();
        $canchas = $this->canchaModel->getAllConSede();
        $grupos  = $this->grupoModel->getByTorneoId($partido['torneo_id']);
        $equipos = $this->equipoModel->getAll($partido['torneo_id']);

        $this->view('admin/partidos/edit', [
            'pageTitle' => 'Reprogramar / Editar Encuentro #' . $partido['id'],
            'partido'   => $partido,
            'torneos'   => $torneos,
            'canchas'   => $canchas,
            'grupos'    => $grupos,
            'equipos'   => $equipos
        ]);
    }

    /**
     * Actualiza la programación de un partido
     *
     * @param int $id
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos/' . (int)$id);
        }

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect('/partidos');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'torneo_id'           => 'required|numeric',
            'equipo_local_id'     => 'required|numeric',
            'equipo_visitante_id' => 'required|numeric',
            'fase'                => 'required',
            'jornada'             => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/partidos/' . (int)$id . '/editar');
        }

        $localId     = (int)$_POST['equipo_local_id'];
        $visitanteId = (int)$_POST['equipo_visitante_id'];

        if ($localId === $visitanteId) {
            Session::setFlash('error', 'El equipo local y el equipo visitante no pueden ser el mismo.');
            $this->redirect('/partidos/' . (int)$id . '/editar');
        }

        $canchaId = !empty($_POST['cancha_id']) ? (int)$_POST['cancha_id'] : null;
        $fecha    = !empty($_POST['fecha_partido']) ? trim($_POST['fecha_partido']) : null;
        $hora     = !empty($_POST['hora_partido']) ? trim($_POST['hora_partido']) : null;

        // Detección de colisión excluyendo el partido actual
        if ($canchaId && $fecha && $hora) {
            $colisionCancha = $this->partidoModel->verificarColisionCancha($canchaId, $fecha, $hora, $id);
            if ($colisionCancha) {
                Session::setFlash('error', 'Conflicto de cancha: Ya está ocupada para "' .
                    e($colisionCancha['local_nombre']) . ' vs ' . e($colisionCancha['visitante_nombre']) . '" a las ' .
                    substr($colisionCancha['hora_partido'], 0, 5) . '.');
                $this->redirect('/partidos/' . (int)$id . '/editar');
            }

            $colisionLocal = $this->partidoModel->verificarColisionEquipo($localId, $fecha, $hora, $id);
            if ($colisionLocal) {
                Session::setFlash('error', 'Conflicto de equipo: El equipo local ya tiene otro partido programado en esta fecha y horario.');
                $this->redirect('/partidos/' . (int)$id . '/editar');
            }

            $colisionVisitante = $this->partidoModel->verificarColisionEquipo($visitanteId, $fecha, $hora, $id);
            if ($colisionVisitante) {
                Session::setFlash('error', 'Conflicto de equipo: El equipo visitante ya tiene otro partido programado en esta fecha y horario.');
                $this->redirect('/partidos/' . (int)$id . '/editar');
            }
        }

        try {
            $this->partidoModel->updatePartido($id, $_POST);
            Session::setFlash('success', 'Encuentro actualizado correctamente.');
            $this->redirect('/partidos/' . (int)$id);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al actualizar el partido: ' . $e->getMessage());
            $this->redirect('/partidos/' . (int)$id . '/editar');
        }
    }

    /**
     * Elimina un partido del fixture
     *
     * @param int $id
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos');
        }

        try {
            $this->partidoModel->deletePartido($id);
            Session::setFlash('success', 'Partido eliminado del fixture correctamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al eliminar el partido: ' . $e->getMessage());
        }

        $this->redirect('/partidos');
    }

    /**
     * Acta Digital de Partido (Marcador en vivo, desglose de sets e incidencias)
     *
     * @param int $id
     */
    public function acta($id) {
        AuthHelper::requireLogin();

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido solicitado no existe.');
            $this->redirect('/partidos');
        }

        $sets        = $this->partidoSetModel->getByPartidoId($id);
        $incidencias = $this->incidenciaModel->getByPartidoId($id);

        $jugadoresLocal     = $this->jugadorModel->getByEquipoId($partido['equipo_local_id']);
        $jugadoresVisitante = $this->jugadorModel->getByEquipoId($partido['equipo_visitante_id']);

        $this->view('admin/partidos/acta', [
            'pageTitle'          => 'Acta Digital: ' . $partido['local_nombre'] . ' vs ' . $partido['visitante_nombre'],
            'partido'            => $partido,
            'sets'               => $sets,
            'incidencias'        => $incidencias,
            'jugadoresLocal'     => $jugadoresLocal,
            'jugadoresVisitante' => $jugadoresVisitante
        ]);
    }

    /**
     * Actualiza el resultado global, estado y observaciones desde el acta
     *
     * @param int $id
     */
    public function guardarResultado($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos/' . (int)$id . '/acta');
        }

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect('/partidos');
        }

        $marcadorLocal     = isset($_POST['marcador_local']) && $_POST['marcador_local'] !== '' ? (int)$_POST['marcador_local'] : null;
        $marcadorVisitante = isset($_POST['marcador_visitante']) && $_POST['marcador_visitante'] !== '' ? (int)$_POST['marcador_visitante'] : null;
        $estado            = !empty($_POST['estado']) ? trim($_POST['estado']) : $partido['estado'];
        $observaciones     = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;

        try {
            $this->partidoModel->actualizarResultado($id, $marcadorLocal, $marcadorVisitante, $estado, $observaciones);
            Session::setFlash('success', 'Resultado y estado del encuentro actualizados.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al guardar el resultado: ' . $e->getMessage());
        }

        $this->redirect('/partidos/' . (int)$id . '/acta');
    }

    /**
     * Registra o actualiza un set de juego (especial para Voleibol y deportes por sets)
     *
     * @param int $id
     */
    public function guardarSet($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos/' . (int)$id . '/acta');
        }

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect('/partidos');
        }

        $numeroSet       = isset($_POST['numero_set']) ? (int)$_POST['numero_set'] : 1;
        $puntosLocal     = isset($_POST['puntos_local']) ? (int)$_POST['puntos_local'] : 0;
        $puntosVisitante = isset($_POST['puntos_visitante']) ? (int)$_POST['puntos_visitante'] : 0;

        try {
            $this->partidoSetModel->guardarSet($id, $numeroSet, $puntosLocal, $puntosVisitante);

            // Si el deporte se juega por sets, sincronizar automáticamente el marcador global
            if (!empty($partido['formato_sets'])) {
                $setsGanados = $this->partidoSetModel->calcularSetsGanados($id);
                $this->partidoModel->actualizarResultado($id, $setsGanados['local'], $setsGanados['visitante'], $partido['estado']);
            }

            Session::setFlash('success', 'Set #' . $numeroSet . ' guardado correctamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al registrar el set: ' . $e->getMessage());
        }

        $this->redirect('/partidos/' . (int)$id . '/acta');
    }

    /**
     * Elimina un set de juego
     *
     * @param int $id
     */
    public function eliminarSet($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos/' . (int)$id . '/acta');
        }

        $partido = $this->partidoModel->getById($id);
        $setId   = isset($_POST['set_id']) ? (int)$_POST['set_id'] : 0;

        try {
            $this->partidoSetModel->deleteSet($setId);

            // Sincronizar marcador si es disciplina de sets
            if (!empty($partido['formato_sets'])) {
                $setsGanados = $this->partidoSetModel->calcularSetsGanados($id);
                $this->partidoModel->actualizarResultado($id, $setsGanados['local'], $setsGanados['visitante'], $partido['estado']);
            }

            Session::setFlash('success', 'Set eliminado correctamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al eliminar el set: ' . $e->getMessage());
        }

        $this->redirect('/partidos/' . (int)$id . '/acta');
    }

    /**
     * Registra una incidencia en el acta (Goles, Puntos, Tarjetas Amarillas/Rojas, Faltas)
     *
     * @param int $id
     */
    public function guardarIncidencia($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos/' . (int)$id . '/acta');
        }

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect('/partidos');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'equipo_id' => 'required|numeric',
            'tipo'      => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/partidos/' . (int)$id . '/acta');
        }

        try {
            $data = [
                'partido_id'    => (int)$id,
                'equipo_id'     => (int)$_POST['equipo_id'],
                'jugador_id'    => (!empty($_POST['jugador_id']) && (int)$_POST['jugador_id'] > 0) ? (int)$_POST['jugador_id'] : null,
                'tipo'          => trim($_POST['tipo']),
                'minuto_tiempo' => (!empty($_POST['minuto_tiempo']) || (isset($_POST['minuto_tiempo']) && $_POST['minuto_tiempo'] === '0')) ? (int)$_POST['minuto_tiempo'] : null,
                'observaciones' => !empty($_POST['observaciones']) ? trim($_POST['observaciones']) : null
            ];

            $this->incidenciaModel->create($data);

            // Si es un deporte sin sets (Fútbol/Futsal) y se marcó un GOL, actualizar marcador global si el usuario lo marcó
            if (empty($partido['formato_sets']) && $data['tipo'] === 'GOL' && !empty($_POST['auto_sumar_marcador'])) {
                $golesLocal = $this->incidenciaModel->contarAnotaciones($id, $partido['equipo_local_id'], 'GOL');
                $golesVis   = $this->incidenciaModel->contarAnotaciones($id, $partido['equipo_visitante_id'], 'GOL');
                $this->partidoModel->actualizarResultado($id, $golesLocal, $golesVis, $partido['estado']);
            }

            Session::setFlash('success', 'Incidencia registrada en el acta.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al registrar incidencia: ' . $e->getMessage());
        }

        $this->redirect('/partidos/' . (int)$id . '/acta');
    }

    /**
     * Elimina una incidencia del acta
     *
     * @param int $id
     */
    public function eliminarIncidencia($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/partidos/' . (int)$id . '/acta');
        }

        $incidenciaId = isset($_POST['incidencia_id']) ? (int)$_POST['incidencia_id'] : 0;

        try {
            $this->incidenciaModel->deleteIncidencia($incidenciaId);
            Session::setFlash('success', 'Incidencia eliminada del acta.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al eliminar incidencia: ' . $e->getMessage());
        }

        $this->redirect('/partidos/' . (int)$id . '/acta');
    }

    /**
     * Endpoint API JSON para cargar equipos y grupos de un torneo dinámicamente
     *
     * @param int $torneoId
     */
    public function apiEquiposPorTorneo($torneoId) {
        $equipos = $this->equipoModel->getAll((int)$torneoId);
        $grupos  = $this->grupoModel->getByTorneoId((int)$torneoId);

        $this->json([
            'success' => true,
            'equipos' => $equipos,
            'grupos'  => $grupos
        ]);
    }
}
