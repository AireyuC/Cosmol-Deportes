<?php
/**
 * Controlador del Panel de Control Principal (Dashboard)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Olimpiada.php';
require_once APP_PATH . '/models/Disciplina.php';
require_once APP_PATH . '/models/Delegacion.php';
require_once APP_PATH . '/models/Medallero.php';
require_once APP_PATH . '/models/Partido.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Sancion.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';

class DashboardController extends Controller {

    /**
     * Muestra la vista principal del dashboard
     */
    public function index() {
        AuthHelper::requireAdmin();

        $olimpiadaModel = new Olimpiada();
        $disciplinaModel = new Disciplina();
        $delegacionModel = new Delegacion();
        $partidoModel = new Partido();
        $equipoModel = new Equipo();
        $sancionModel = new Sancion();
        $torneoModel = new Torneo();

        // Inicializar indicadores y variables en cero / vacío
        $olimpiadaActiva = null;
        $totalDisciplinas = 0;
        $totalDelegaciones = 0;
        $totalEquipos = 0;
        $totalSancionesActivas = 0;
        $disciplinasList = [];

        try {
            $olimpiadaActiva = $olimpiadaModel->getActiva();

            $dbDisciplinas = $disciplinaModel->getAll();
            if (is_array($dbDisciplinas)) {
                $totalDisciplinas = count($dbDisciplinas);
                $disciplinasList = $dbDisciplinas;
            }

            $dbDelegaciones = $delegacionModel->getAll();
            if (is_array($dbDelegaciones)) {
                $totalDelegaciones = count($dbDelegaciones);
            }

            $dbEquipos = $equipoModel->getAll();
            if (is_array($dbEquipos)) {
                $totalEquipos = count($dbEquipos);
            }

            $dbSanciones = $sancionModel->getAll(null, 'Activa');
            if (is_array($dbSanciones)) {
                $totalSancionesActivas = count($dbSanciones);
            }
        } catch (Exception $e) {
            // Silencioso
        }

        if (!$olimpiadaActiva) {
            $olimpiadaActiva = [
                'id'     => null,
                'nombre' => 'Sin Olimpiada Activa',
                'lema'   => 'No hay una olimpiada activa registrada en el sistema.',
                'estado' => 'Sin Iniciar',
                'anio'   => date('Y')
            ];
        }

        // Consultar partidos de hoy para la olimpiada activa
        $fechaHoy = date('Y-m-d');
        $olimpiadaId = !empty($olimpiadaActiva['id']) ? (int)$olimpiadaActiva['id'] : null;
        $partidosHoy = [];

        if ($olimpiadaId !== null) {
            try {
                $partidosHoy = $partidoModel->getPartidosHoy($fechaHoy, $olimpiadaId);
            } catch (Exception $e) {
                $partidosHoy = [];
            }
        }

        // Formatear disciplinas reales desde la BD con conteo de torneos
        $colorMap = ['success', 'info', 'warning', 'danger', 'primary', 'secondary'];
        $formattedDisciplinas = [];
        if (!empty($disciplinasList)) {
            $torneoCounts = [];
            if ($olimpiadaId !== null) {
                try {
                    $torneos = $torneoModel->getAll($olimpiadaId);
                    if (is_array($torneos)) {
                        foreach ($torneos as $t) {
                            $dId = $t['disciplina_id'];
                            $torneoCounts[$dId] = isset($torneoCounts[$dId]) ? $torneoCounts[$dId] + 1 : 1;
                        }
                    }
                } catch (Exception $e) {
                    $torneoCounts = [];
                }
            }

            $i = 0;
            foreach ($disciplinasList as $d) {
                $countTorneos = isset($torneoCounts[$d['id']]) ? (int)$torneoCounts[$d['id']] : 0;
                $formattedDisciplinas[] = [
                    'id'      => $d['id'],
                    'nombre'  => $d['nombre'],
                    'icono'   => !empty($d['icono']) ? $d['icono'] : 'trophy',
                    'color'   => $colorMap[$i % count($colorMap)],
                    'torneos' => $countTorneos
                ];
                $i++;
            }
        }

        // Obtener medallero top real solo si existen medallas en la BD
        $medalleroTop = [];
        if ($olimpiadaId !== null) {
            try {
                $medalleroModel = new Medallero();
                $dbMedallero = $medalleroModel->getResumenPorOlimpiada($olimpiadaId);
                if (!empty($dbMedallero) && is_array($dbMedallero)) {
                    $pos = 1;
                    foreach ($dbMedallero as $row) {
                        $medalleroTop[] = [
                            'pos'        => $pos++,
                            'delegacion' => $row['delegacion_nombre'],
                            'color'      => !empty($row['color_representativo']) ? $row['color_representativo'] : '#007bff',
                            'oro'        => (int)$row['oro'],
                            'plata'      => (int)$row['plata'],
                            'bronce'     => (int)$row['bronce'],
                            'total'      => (int)$row['total_medallas']
                        ];
                        if ($pos > 5) break;
                    }
                }
            } catch (Exception $e) {
                $medalleroTop = [];
            }
        }

        $data = [
            'pageTitle'    => 'Panel de Control de Olimpiadas',
            'olimpiada'    => $olimpiadaActiva,
            'kpis' => [
                'disciplinas'   => $totalDisciplinas,
                'delegaciones'  => $totalDelegaciones,
                'equipos'       => $totalEquipos,
                'partidos_hoy'  => count($partidosHoy),
                'sanciones_act' => $totalSancionesActivas
            ],
            'partidos_hoy' => $partidosHoy,
            'fecha_hoy'    => $fechaHoy,
            'disciplinas'  => $formattedDisciplinas,
            'medallero_top'=> $medalleroTop
        ];

        $this->view('admin/dashboard/index', $data);
    }
}
