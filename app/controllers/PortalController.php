<?php
/**
 * Controlador del Portal Informativo Público de las Olimpiadas COSMOL
 * Diseñado para delegaciones de otros departamentos, deportistas y público general.
 * Acceso 100% público sin necesidad de iniciar sesión.
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Olimpiada.php';
require_once APP_PATH . '/models/Disciplina.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/models/Delegacion.php';
require_once APP_PATH . '/models/Sede.php';
require_once APP_PATH . '/models/Partido.php';
require_once APP_PATH . '/models/Medallero.php';
require_once APP_PATH . '/models/Posicion.php';
require_once APP_PATH . '/helpers/AuthHelper.php';

class PortalController extends Controller {

    /** @var Olimpiada */
    private $olimpiadaModel;
    /** @var Disciplina */
    private $disciplinaModel;
    /** @var Torneo */
    private $torneoModel;
    /** @var Delegacion */
    private $delegacionModel;
    /** @var Sede */
    private $sedeModel;
    /** @var Partido */
    private $partidoModel;
    /** @var Medallero */
    private $medalleroModel;
    /** @var Posicion */
    private $posicionModel;

    public function __construct() {
        $this->olimpiadaModel  = new Olimpiada();
        $this->disciplinaModel = new Disciplina();
        $this->torneoModel     = new Torneo();
        $this->delegacionModel = new Delegacion();
        $this->sedeModel       = new Sede();
        $this->partidoModel    = new Partido();
        $this->medalleroModel  = new Medallero();
        $this->posicionModel   = new Posicion();
    }

    /**
     * Página principal del Portal Informativo COSMOL
     * Muestra resumen de la olimpiada, disciplinas, medallero, sedes y botón de acceso admin.
     */
    public function index() {
        // 1. Olimpiada Activa
        $olimpiada = null;
        try {
            $olimpiada = $this->olimpiadaModel->getActiva();
        } catch (Exception $e) {
            $olimpiada = null;
        }

        if (!$olimpiada) {
            $olimpiada = [
                'id'           => 1,
                'nombre'       => 'Olimpiadas Cosmol 2026',
                'anio'         => 2026,
                'lema'         => 'Unidad, Deporte e Integración Departamental',
                'estado'       => 'En Curso',
                'fecha_inicio' => date('Y-m-01'),
                'fecha_fin'    => date('Y-m-28')
            ];
        }

        $olimpiadaId = isset($olimpiada['id']) ? (int)$olimpiada['id'] : null;

        // 2. Disciplinas deportivas activas
        $disciplinas = [];
        try {
            $disciplinas = $this->disciplinaModel->getAll();

            // Orden prioritario oficial para las disciplinas confirmadas
            $ordenPrioridad = [
                'futbol'     => 1,
                'fútbol'     => 1,
                'futsal'     => 2,
                'básquetbol' => 3,
                'basquetbol' => 3,
                'básquet'    => 3,
                'basquet'    => 3,
                'voleibol'   => 4,
                'voley'      => 4,
                'cacho'      => 5,
                'billar'     => 6,
                'karaoke'    => 7
            ];

            usort($disciplinas, function($a, $b) use ($ordenPrioridad) {
                $nA = mb_strtolower(trim($a['nombre']), 'UTF-8');
                $nB = mb_strtolower(trim($b['nombre']), 'UTF-8');
                $pA = isset($ordenPrioridad[$nA]) ? $ordenPrioridad[$nA] : 999;
                $pB = isset($ordenPrioridad[$nB]) ? $ordenPrioridad[$nB] : 999;
                if ($pA === $pB) {
                    return strcmp($nA, $nB);
                }
                return ($pA < $pB) ? -1 : 1;
            });
        } catch (Exception $e) {
            $disciplinas = [];
        }

        // Asociar a cada disciplina su conteo de torneos y equipos
        $torneosPorDisciplina = [];
        try {
            $todosTorneos = $this->torneoModel->getAll($olimpiadaId);
            foreach ($todosTorneos as $t) {
                $torneosPorDisciplina[$t['disciplina_id']][] = $t;
            }
        } catch (Exception $e) {
            $todosTorneos = [];
        }

        foreach ($disciplinas as &$d) {
            $d['torneos_count'] = isset($torneosPorDisciplina[$d['id']]) ? count($torneosPorDisciplina[$d['id']]) : 0;
            $d['torneos'] = isset($torneosPorDisciplina[$d['id']]) ? $torneosPorDisciplina[$d['id']] : [];
        }
        unset($d);

        // 3. Delegaciones participantes (representando departamentos/áreas)
        $delegaciones = [];
        try {
            $delegaciones = $this->delegacionModel->getAll();
        } catch (Exception $e) {
            $delegaciones = [];
        }

        // 4. Sedes deportivas con canchas y coordenadas GPS
        $sedes = [];
        try {
            $sedes = $this->sedeModel->getAllWithCanchas();
        } catch (Exception $e) {
            $sedes = [];
        }

        // 5. Medallero Olímpico General
        $medallero = [];
        if ($olimpiadaId) {
            try {
                $medallero = $this->medalleroModel->getResumenPorOlimpiada($olimpiadaId);
            } catch (Exception $e) {
                $medallero = [];
            }
        }

        // 6. Clasificación de Partidos: Hoy, Ayer, Mañana y General
        $fechaHoy    = date('Y-m-d');
        $fechaAyer   = date('Y-m-d', strtotime('-1 day'));
        $fechaManana = date('Y-m-d', strtotime('+1 day'));

        $partidosHoy       = [];
        $partidosAyer      = [];
        $partidosManana    = [];
        $proximosPartidos  = [];
        $ultimosResultados = [];
        $todosPartidos     = [];

        try {
            $todosPartidos = $this->partidoModel->getAll();
            foreach ($todosPartidos as $p) {
                // Clasificación estricta por fecha
                if ($p['fecha_partido'] === $fechaHoy) {
                    $partidosHoy[] = $p;
                } elseif ($p['fecha_partido'] === $fechaAyer) {
                    $partidosAyer[] = $p;
                } elseif ($p['fecha_partido'] === $fechaManana) {
                    $partidosManana[] = $p;
                }

                // Listas para compatibilidad general
                if ($p['estado'] === 'Finalizado') {
                    if (count($ultimosResultados) < 6) {
                        $ultimosResultados[] = $p;
                    }
                } else if ($p['estado'] === 'Programado' || $p['estado'] === 'En_Juego') {
                    if (count($proximosPartidos) < 6) {
                        $proximosPartidos[] = $p;
                    }
                }
            }
        } catch (Exception $e) {
            $todosPartidos     = [];
            $partidosHoy       = [];
            $partidosAyer      = [];
            $partidosManana    = [];
            $proximosPartidos  = [];
            $ultimosResultados = [];
        }

        $this->view('public/index', [
            'pageTitle'         => 'Olimpiadas COSMOL 2026 | Portal Oficial',
            'olimpiada'         => $olimpiada,
            'disciplinas'       => $disciplinas,
            'delegaciones'      => $delegaciones,
            'sedes'             => $sedes,
            'medallero'         => $medallero,
            'proximosPartidos'  => $proximosPartidos,
            'ultimosResultados' => $ultimosResultados,
            'partidosHoy'       => $partidosHoy,
            'partidosAyer'      => $partidosAyer,
            'partidosManana'    => $partidosManana,
            'todosPartidos'     => $todosPartidos,
            'fechaHoy'          => $fechaHoy,
            'fechaAyer'         => $fechaAyer,
            'fechaManana'       => $fechaManana,
            'isAdminLogged'     => AuthHelper::isAdmin()
        ], null); // Renderizado autónomo de alto impacto visual
    }

    /**
     * Vista de detalle de una Disciplina Deportiva específica
     * Muestra torneos (ramas), sedes donde se juega, "cómo llegar", tabla de posiciones y fixture.
     *
     * @param int $id ID de la disciplina
     */
    public function disciplina($id) {
        $id = (int)$id;
        $disciplina = $this->disciplinaModel->getById($id);

        if (!$disciplina) {
            Session::setFlash('error', 'La disciplina deportiva solicitada no existe.');
            $this->redirect('/');
        }

        // Torneos de esta disciplina
        $olimpiada = $this->olimpiadaModel->getActiva();
        $olimpiadaId = $olimpiada ? (int)$olimpiada['id'] : null;

        $torneos = [];
        try {
            $sql = "SELECT t.*, r.pts_victoria, r.pts_empate, r.pts_derrota, r.formato_sets, r.sets_para_ganar,
                           (SELECT COUNT(*) FROM equipos e WHERE e.torneo_id = t.id) AS total_equipos
                    FROM torneos t
                    LEFT JOIN reglas_puntuacion r ON t.regla_puntuacion_id = r.id
                    WHERE t.disciplina_id = :disciplina_id " . ($olimpiadaId ? "AND t.olimpiada_id = :olimpiada_id" : "") . "
                    ORDER BY t.rama ASC, t.nombre ASC";
            $params = ['disciplina_id' => $id];
            if ($olimpiadaId) {
                $params['olimpiada_id'] = $olimpiadaId;
            }
            $torneos = $this->torneoModel->fetchAll($sql, $params);
        } catch (Exception $e) {
            $torneos = [];
        }

        // Calcular tablas de posiciones y partidos para cada torneo de la disciplina
        $datosTorneos = [];
        $sedesUsadas = [];

        foreach ($torneos as $torneo) {
            $torneoId = (int)$torneo['id'];
            
            // Posiciones
            $posiciones = [];
            try {
                $posiciones = $this->posicionModel->calcularPorTorneo($torneoId);
            } catch (Exception $e) {
                $posiciones = [];
            }

            // Partidos
            $partidos = [];
            try {
                $partidos = $this->partidoModel->getAll($torneoId);
            } catch (Exception $e) {
                $partidos = [];
            }

            // Recolectar sedes de los partidos
            foreach ($partidos as $p) {
                if (!empty($p['sede_id']) && !isset($sedesUsadas[$p['sede_id']])) {
                    $sedesUsadas[$p['sede_id']] = [
                        'id'        => $p['sede_id'],
                        'nombre'    => $p['sede_nombre'],
                        'direccion' => $p['sede_direccion'],
                        'latitud'   => $p['sede_latitud'],
                        'longitud'  => $p['sede_longitud']
                    ];
                }
            }

            $datosTorneos[] = [
                'torneo'     => $torneo,
                'posiciones' => $posiciones,
                'partidos'   => $partidos
            ];
        }

        // Si no hay sedes de partidos, traer sedes generales
        if (empty($sedesUsadas)) {
            $todasSedes = $this->sedeModel->getAll();
            foreach ($todasSedes as $s) {
                $sedesUsadas[$s['id']] = $s;
            }
        }

        $this->view('public/disciplina', [
            'pageTitle'    => $disciplina['nombre'] . ' | Olimpiadas COSMOL',
            'disciplina'   => $disciplina,
            'olimpiada'    => $olimpiada,
            'datosTorneos' => $datosTorneos,
            'sedes'        => array_values($sedesUsadas),
            'isAdminLogged'=> AuthHelper::isAdmin()
        ], null);
    }

    /**
     * Vista pública de Sedes Deportivas y Geolocalización "Cómo Llegar"
     */
    public function sedes() {
        $olimpiada = $this->olimpiadaModel->getActiva();
        $sedes = $this->sedeModel->getAllWithCanchas();

        $this->view('public/sedes', [
            'pageTitle'     => 'Sedes Deportivas y Cómo Llegar | Olimpiadas COSMOL',
            'olimpiada'     => $olimpiada,
            'sedes'         => $sedes,
            'isAdminLogged' => AuthHelper::isAdmin()
        ], null);
    }

    /**
     * Vista pública de Tablas de Posiciones
     */
    public function posiciones() {
        $olimpiada = $this->olimpiadaModel->getActiva();
        $olimpiadaId = $olimpiada ? (int)$olimpiada['id'] : null;

        $disciplinas = $this->disciplinaModel->getAll();
        $torneoId = isset($_GET['torneo_id']) ? (int)$_GET['torneo_id'] : 0;

        $torneos = $this->torneoModel->getAll($olimpiadaId);
        if ($torneoId === 0 && !empty($torneos)) {
            $torneoId = (int)$torneos[0]['id'];
        }

        $torneoSeleccionado = null;
        $tablaPosiciones = [];
        if ($torneoId > 0) {
            $torneoSeleccionado = $this->torneoModel->getById($torneoId);
            try {
                $tablaPosiciones = $this->posicionModel->calcularPorTorneo($torneoId);
            } catch (Exception $e) {
                $tablaPosiciones = [];
            }
        }

        $this->view('public/posiciones', [
            'pageTitle'          => 'Tablas de Posiciones | Olimpiadas COSMOL',
            'olimpiada'          => $olimpiada,
            'torneos'            => $torneos,
            'torneoSeleccionado' => $torneoSeleccionado,
            'tablaPosiciones'    => $tablaPosiciones,
            'isAdminLogged'      => AuthHelper::isAdmin()
        ], null);
    }

    /**
     * Vista pública del Fixture y Calendario de Encuentros
     */
    public function fixture() {
        $olimpiada = $this->olimpiadaModel->getActiva();
        $disciplinas = $this->disciplinaModel->getAll();

        $disciplinaFiltro = isset($_GET['disciplina_id']) ? (int)$_GET['disciplina_id'] : null;
        $fechaFiltro      = isset($_GET['fecha']) ? trim($_GET['fecha']) : null;
        $estadoFiltro     = isset($_GET['estado']) ? trim($_GET['estado']) : null;

        $todosPartidos = $this->partidoModel->getAll(null, $estadoFiltro, $fechaFiltro);

        if ($disciplinaFiltro && $disciplinaFiltro > 0) {
            $partidosFiltrados = [];
            foreach ($todosPartidos as $p) {
                if ((int)$p['disciplina_id'] === $disciplinaFiltro) {
                    $partidosFiltrados[] = $p;
                }
            }
            $todosPartidos = $partidosFiltrados;
        }

        $this->view('public/fixture', [
            'pageTitle'        => 'Fixture y Calendario Deportivo | Olimpiadas COSMOL',
            'olimpiada'        => $olimpiada,
            'disciplinas'      => $disciplinas,
            'partidos'         => $todosPartidos,
            'disciplinaFiltro' => $disciplinaFiltro,
            'fechaFiltro'      => $fechaFiltro,
            'estadoFiltro'     => $estadoFiltro,
            'isAdminLogged'    => AuthHelper::isAdmin()
        ], null);
    }

    /**
     * Vista pública del Medallero Olímpico Oficial
     */
    public function medallero() {
        $olimpiada = $this->olimpiadaModel->getActiva();
        $olimpiadaId = $olimpiada ? (int)$olimpiada['id'] : null;

        $resumen = [];
        $podios = [];
        if ($olimpiadaId) {
            try {
                $resumen = $this->medalleroModel->getResumenPorOlimpiada($olimpiadaId);
                $podios  = $this->medalleroModel->getPodiosPorOlimpiada($olimpiadaId);
            } catch (Exception $e) {
                $resumen = [];
                $podios  = [];
            }
        }

        $this->view('public/medallero', [
            'pageTitle'     => 'Medallero Olímpico General | Olimpiadas COSMOL',
            'olimpiada'     => $olimpiada,
            'resumen'       => $resumen,
            'podios'        => $podios,
            'isAdminLogged' => AuthHelper::isAdmin()
        ], null);
    }
}
