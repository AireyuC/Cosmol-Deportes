<?php
/**
 * Controlador de Tablas de Posiciones por Torneo y Grupo
 * Muestra el ranking dinámico calculado a partir de los partidos finalizados.
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Posicion.php';
require_once APP_PATH . '/models/Olimpiada.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';

class PosicionesController extends Controller {

    /** @var Posicion */
    private $posicionModel;

    /** @var Olimpiada */
    private $olimpiadaModel;

    /** @var Torneo */
    private $torneoModel;

    public function __construct() {
        $this->posicionModel  = new Posicion();
        $this->olimpiadaModel = new Olimpiada();
        $this->torneoModel    = new Torneo();
    }

    /**
     * Vista principal de tablas de posiciones.
     * Permite filtrar por torneo y grupo mediante GET.
     */
    public function index() {
        AuthHelper::requireLogin();

        // Olimpiada activa
        $olimpiada = null;
        try {
            $olimpiada = $this->olimpiadaModel->getActiva();
        } catch (Exception $e) {
            // continúa sin olimpiada activa
        }

        $olimpiadaId = $olimpiada ? (int)$olimpiada['id'] : null;

        // Obtener todos los torneos con grupos disponibles
        $torneos = [];
        try {
            $torneos = $this->posicionModel->getTorneosConGrupos($olimpiadaId);
        } catch (Exception $e) {
            $torneos = [];
        }

        // Filtros desde GET
        $torneoIdFiltro = isset($_GET['torneo_id']) ? (int)$_GET['torneo_id'] : 0;
        $grupoIdFiltro  = isset($_GET['grupo_id'])  ? (int)$_GET['grupo_id']  : 0;

        // Si no hay torneo seleccionado, usar el primero disponible
        if ($torneoIdFiltro === 0 && !empty($torneos)) {
            $torneoIdFiltro = (int)$torneos[0]['id'];
        }

        // Obtener grupos del torneo seleccionado
        $grupos = [];
        $torneoActual = null;
        $tablaPosiciones = [];

        if ($torneoIdFiltro > 0) {
            try {
                $grupos       = $this->posicionModel->getGruposPorTorneo($torneoIdFiltro);
                $torneoActual = $this->torneoModel->getById($torneoIdFiltro);
                $tablaPosiciones = $this->posicionModel->calcularPorTorneo(
                    $torneoIdFiltro,
                    $grupoIdFiltro > 0 ? $grupoIdFiltro : null
                );
            } catch (Exception $e) {
                $tablaPosiciones = [];
            }
        }

        // Si hay grupos y NO se seleccionó uno específico, mostrar por grupos separados
        $tablasPorGrupo = [];
        if (!empty($grupos) && $grupoIdFiltro === 0 && $torneoIdFiltro > 0) {
            foreach ($grupos as $grupo) {
                try {
                    $tablaGrupo = $this->posicionModel->calcularPorTorneo(
                        $torneoIdFiltro,
                        (int)$grupo['id']
                    );
                } catch (Exception $e) {
                    $tablaGrupo = [];
                }
                $tablasPorGrupo[] = [
                    'grupo'  => $grupo,
                    'tabla'  => $tablaGrupo
                ];
            }
        }

        $this->view('admin/posiciones/index', [
            'pageTitle'        => 'Tablas de Posiciones',
            'olimpiada'        => $olimpiada,
            'torneos'          => $torneos,
            'torneoActual'     => $torneoActual,
            'grupos'           => $grupos,
            'torneoIdFiltro'   => $torneoIdFiltro,
            'grupoIdFiltro'    => $grupoIdFiltro,
            'tablaPosiciones'  => $tablaPosiciones,
            'tablasPorGrupo'   => $tablasPorGrupo
        ]);
    }

    /**
     * Endpoint JSON: retorna la tabla de posiciones de un torneo (para AJAX).
     * GET /api/posiciones/{torneoId}?grupo_id=X
     *
     * @param int $torneoId
     */
    public function apiTabla($torneoId) {
        header('Content-Type: application/json');

        $torneoId = (int)$torneoId;
        $grupoId  = isset($_GET['grupo_id']) ? (int)$_GET['grupo_id'] : null;

        if ($torneoId <= 0) {
            echo json_encode(['error' => 'Torneo no válido']);
            return;
        }

        try {
            $tabla = $this->posicionModel->calcularPorTorneo($torneoId, $grupoId);
            echo json_encode(['success' => true, 'data' => $tabla]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al calcular posiciones', 'detalle' => $e->getMessage()]);
        }
    }
}
