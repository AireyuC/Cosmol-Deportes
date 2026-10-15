<?php
/**
 * Controlador de Delegaciones Institucionales
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Delegacion.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Partido.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Session.php';
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

    /**
     * Muestra la ficha detallada y el historial de partidos de la delegación
     *
     * @param int $id
     */
    public function show($id) {
        AuthHelper::requireLogin();

        $id = (int)$id;
        $delegacion = $this->delegacionModel->getById($id);

        if (!$delegacion) {
            Session::setFlash('error', 'Delegación no encontrada.');
            $this->redirect('/delegaciones');
            return;
        }

        $equipoModel  = new Equipo();
        $partidoModel = new Partido();

        $filtroTorneo = isset($_GET['torneo_id']) && $_GET['torneo_id'] !== '' ? (int)$_GET['torneo_id'] : null;
        $filtroEstado = isset($_GET['estado']) && $_GET['estado'] !== '' ? $_GET['estado'] : null;

        // Equipos pertenecientes a esta delegación
        $equipos = $equipoModel->getAll(null, $id);

        // Historial completo de partidos
        $partidos = $partidoModel->getHistorialPorDelegacion($id, $filtroTorneo, $filtroEstado);

        // Extraer torneos participantes únicos para el filtro
        $torneosParticipantes = [];
        $torneoIdsVistos = [];
        foreach ($equipos as $eq) {
            $tid = (int)$eq['torneo_id'];
            if (!in_array($tid, $torneoIdsVistos, true)) {
                $torneoIdsVistos[] = $tid;
                $torneosParticipantes[] = [
                    'id'     => $tid,
                    'nombre' => isset($eq['torneo_nombre']) ? $eq['torneo_nombre'] : ('Torneo #' . $tid)
                ];
            }
        }

        // Cómputo de estadísticas
        $totalPartidos = count($partidos);
        $ganados     = 0;
        $empatados   = 0;
        $perdidos    = 0;
        $programados = 0;
        $golesFavor  = 0;
        $golesContra = 0;

        foreach ($partidos as $p) {
            if ($p['estado'] === 'Finalizado' || $p['estado'] === 'WO') {
                if ($p['resultado_tipo'] === 'VICTORIA') {
                    $ganados++;
                } elseif ($p['resultado_tipo'] === 'EMPATE') {
                    $empatados++;
                } elseif ($p['resultado_tipo'] === 'DERROTA') {
                    $perdidos++;
                }
                $golesFavor  += (int)$p['mis_goles'];
                $golesContra += (int)$p['rival_goles'];
            } elseif ($p['estado'] === 'Programado') {
                $programados++;
            }
        }

        $jugados = $ganados + $empatados + $perdidos;
        $puntosObtenidos = ($ganados * 3) + ($empatados * 1);
        $puntosPosibles  = $jugados * 3;
        $porcentajeRendimiento = ($puntosPosibles > 0) ? round(($puntosObtenidos / $puntosPosibles) * 100) : 0;

        $estadisticas = [
            'total'       => $totalPartidos,
            'jugados'     => $jugados,
            'ganados'     => $ganados,
            'empatados'   => $empatados,
            'perdidos'    => $perdidos,
            'programados' => $programados,
            'goles_favor' => $golesFavor,
            'goles_contra'=> $golesContra,
            'diferencia'  => $golesFavor - $golesContra,
            'puntos'      => $puntosObtenidos,
            'rendimiento' => $porcentajeRendimiento
        ];

        $this->view('admin/delegaciones/show', [
            'pageTitle'            => 'Historial de Partidos — ' . $delegacion['nombre'],
            'delegacion'           => $delegacion,
            'equipos'              => $equipos,
            'partidos'             => $partidos,
            'estadisticas'         => $estadisticas,
            'torneosParticipantes' => $torneosParticipantes,
            'filtroTorneo'         => $filtroTorneo,
            'filtroEstado'         => $filtroEstado
        ]);
    }
}
