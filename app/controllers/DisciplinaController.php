<?php
/**
 * Controlador de Disciplinas Olímpicas y Competencias de Delegaciones y Equipos
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/models/Olimpiada.php';
require_once APP_PATH . '/models/Disciplina.php';
require_once APP_PATH . '/models/ReglaPuntuacion.php';
require_once APP_PATH . '/models/Grupo.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Partido.php';
require_once APP_PATH . '/models/Medallero.php';
require_once APP_PATH . '/models/Posicion.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class DisciplinaController extends Controller {

    /** @var Torneo */
    private $torneoModel;

    /** @var Olimpiada */
    private $olimpiadaModel;

    /** @var Disciplina */
    private $disciplinaModel;

    /** @var Grupo */
    private $grupoModel;

    /** @var Equipo */
    private $equipoModel;

    /** @var Partido */
    private $partidoModel;

    /** @var Medallero */
    private $medalleroModel;

    /** @var Posicion */
    private $posicionModel;

    public function __construct() {
        $this->torneoModel     = new Torneo();
        $this->olimpiadaModel  = new Olimpiada();
        $this->disciplinaModel = new Disciplina();
        $this->grupoModel      = new Grupo();
        $this->equipoModel     = new Equipo();
        $this->partidoModel    = new Partido();
        $this->medalleroModel  = new Medallero();
        $this->posicionModel   = new Posicion();
    }

    /**
     * Listado principal de disciplinas olímpicas
     */
    public function index() {
        AuthHelper::requireLogin();

        $olimpiadas = $this->olimpiadaModel->getAll();
        $olimpiadaActiva = $this->olimpiadaModel->getActiva();

        $filtroOlimpiada = isset($_GET['olimpiada_id']) && $_GET['olimpiada_id'] !== ''
            ? (int)$_GET['olimpiada_id']
            : ($olimpiadaActiva ? (int)$olimpiadaActiva['id'] : null);

        $torneos = $this->torneoModel->getAll($filtroOlimpiada);

        $this->view('admin/disciplinas/index', [
            'pageTitle'        => 'Disciplinas Olímpicas',
            'torneos'          => $torneos,
            'olimpiadas'       => $olimpiadas,
            'filtroOlimpiada'  => $filtroOlimpiada,
            'olimpiadaActiva'  => $olimpiadaActiva
        ]);
    }

    /**
     * Ficha interactiva de la disciplina con sus grupos y equipos
     *
     * @param int $id
     */
    public function show($id) {
        AuthHelper::requireLogin();

        $torneo = $this->torneoModel->getById($id);
        if (!$torneo) {
            Session::setFlash('error', 'La disciplina solicitada no existe.');
            $this->redirect('/disciplinas');
        }

        $gruposConEquipos = $this->grupoModel->getGruposConEquipos($id);
        $equiposSinGrupo = $this->grupoModel->getEquiposSinGrupo($id);
        $todosEquipos = $this->equipoModel->getByTorneoId($id);

        $partidos   = $this->partidoModel->getAll($id);
        $medallas   = $this->medalleroModel->getMedallasPorTorneo($id);
        $posiciones = [];
        try {
            $posiciones = $this->posicionModel->calcularPorTorneo($id);
        } catch (Exception $e) {
            $posiciones = [];
        }

        $this->view('admin/disciplinas/show', [
            'pageTitle'        => $torneo['nombre'] . ' | Control de Disciplina',
            'torneo'           => $torneo,
            'grupos'           => $gruposConEquipos,
            'equiposSinGrupo'  => $equiposSinGrupo,
            'todosEquipos'     => $todosEquipos,
            'partidos'         => $partidos,
            'medallas'         => $medallas,
            'posiciones'       => $posiciones
        ]);
    }

    /**
     * Formulario de creación de disciplina
     */
    public function create() {
        AuthHelper::requireAdmin();

        $olimpiadas = $this->olimpiadaModel->getAll();
        $disciplinas = $this->disciplinaModel->getAll();
        $olimpiadaActiva = $this->olimpiadaModel->getActiva();

        $this->view('admin/disciplinas/create', [
            'pageTitle'       => 'Registrar Nueva Disciplina',
            'olimpiadas'      => $olimpiadas,
            'disciplinas'     => $disciplinas,
            'olimpiadaActiva' => $olimpiadaActiva
        ]);
    }

    /**
     * Guarda una nueva disciplina en la base de datos
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'        => 'required|min:2|max:150',
            'olimpiada_id'  => 'required|numeric',
            'rama'          => 'required',
            'tipo_torneo'   => 'required',
            'modalidad'     => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/disciplinas/crear');
        }

        // Resolver o registrar automáticamente la disciplina base y sus reglas de puntuación
        $disciplinaId = !empty($_POST['disciplina_id']) ? (int)$_POST['disciplina_id'] : null;

        if (!$disciplinaId) {
            $nombreLimpio = trim($_POST['nombre']);
            $todasDisciplinas = $this->disciplinaModel->getAll();

            // 1. Coincidencia exacta (case-insensitive)
            foreach ($todasDisciplinas as $d) {
                if (mb_strtolower(trim($d['nombre'])) === mb_strtolower($nombreLimpio)) {
                    $disciplinaId = (int)$d['id'];
                    break;
                }
            }

            // 2. Coincidencia por contención (si el nombre incluye el deporte base o viceversa)
            if (!$disciplinaId) {
                usort($todasDisciplinas, function($a, $b) {
                    return mb_strlen($b['nombre']) - mb_strlen($a['nombre']);
                });
                $nomInput = mb_strtolower($nombreLimpio);
                foreach ($todasDisciplinas as $d) {
                    $nomD = mb_strtolower(trim($d['nombre']));
                    if (mb_strpos($nomInput, $nomD) !== false || mb_strpos($nomD, $nomInput) !== false) {
                        $disciplinaId = (int)$d['id'];
                        break;
                    }
                }
            }

            // 3. Si no existe ninguna coincidencia, crear automáticamente en 'disciplinas' y 'reglas_puntuacion'
            if (!$disciplinaId) {
                $disciplinaId = $this->disciplinaModel->create([
                    'nombre'               => $nombreLimpio,
                    'descripcion'          => 'Disciplina registrada desde Olimpiadas',
                    'icono'                => 'trophy',
                    'estado'               => 'Activa',
                    'pts_victoria'         => 3,
                    'pts_empate'           => 1,
                    'pts_derrota'          => 0,
                    'pts_derrota_ajustada' => 0,
                    'permite_empate'       => 1,
                    'formato_sets'         => 0,
                    'sets_para_ganar'      => 0
                ]);
            }
        }

        $_POST['disciplina_id'] = $disciplinaId;

        try {
            $nuevoId = $this->torneoModel->create($_POST);


            Session::setFlash('success', 'Disciplina "' . e($_POST['nombre']) . '" registrada con éxito. Puedes modificar sus reglas de puntuación en cualquier momento.');
            $this->redirect('/disciplinas/' . $nuevoId);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al guardar la disciplina: ' . $e->getMessage());
            $this->redirect('/disciplinas/crear');
        }
    }

    /**
     * Formulario de edición de disciplina
     *
     * @param int $id
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $torneo = $this->torneoModel->getById($id);
        if (!$torneo) {
            Session::setFlash('error', 'La disciplina a editar no existe.');
            $this->redirect('/disciplinas');
        }

        $olimpiadas = $this->olimpiadaModel->getAll();
        $disciplinas = $this->disciplinaModel->getAll();

        $this->view('admin/disciplinas/edit', [
            'pageTitle'   => 'Editar Disciplina: ' . $torneo['nombre'],
            'torneo'      => $torneo,
            'olimpiadas'  => $olimpiadas,
            'disciplinas' => $disciplinas
        ]);
    }

    /**
     * Actualiza la disciplina
     *
     * @param int $id
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        $torneo = $this->torneoModel->getById($id);
        if (!$torneo) {
            Session::setFlash('error', 'La disciplina solicitada no existe.');
            $this->redirect('/disciplinas');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre'        => 'required|min:2|max:150',
            'olimpiada_id'  => 'required|numeric',
            'rama'          => 'required',
            'tipo_torneo'   => 'required',
            'modalidad'     => 'required',
            'estado'        => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/disciplinas/' . $id . '/editar');
        }

        if (empty($_POST['disciplina_id'])) {
            $_POST['disciplina_id'] = $torneo['disciplina_id'];
        }

        try {
            $this->torneoModel->updateTorneo($id, $_POST);

            // Si se pasa a estado Finalizado, actualizar el medallero automáticamente
            if (isset($_POST['estado']) && $_POST['estado'] === 'Finalizado') {
                $this->medalleroModel->actualizarMedalleroAutomaticoPorTorneo($id);
            }

            Session::setFlash('success', 'Disciplina actualizada correctamente.');
            $this->redirect('/disciplinas/' . $id);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al actualizar la disciplina: ' . $e->getMessage());
            $this->redirect('/disciplinas/' . $id . '/editar');
        }
    }

    /**
     * Da por concluida una disciplina / torneo, finaliza sus encuentros pendientes
     * y actualiza automáticamente el Medallero Olímpico Oficial.
     * POST /disciplinas/{id}/concluir
     *
     * @param int $id
     */
    public function concluir($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado.');
            $this->redirect('/disciplinas/' . (int)$id);
            return;
        }

        $id = (int)$id;
        $torneo = $this->torneoModel->getById($id);
        if (!$torneo) {
            Session::setFlash('error', 'La disciplina solicitada no existe.');
            $this->redirect('/disciplinas');
            return;
        }

        try {
            // 1. Cambiar estado del torneo a Finalizado
            $this->torneoModel->cambiarEstado($id, 'Finalizado');

            // 2. Si hay partidos con marcador pero no finalizados, darlos por finalizados
            $partidos = $this->partidoModel->getAll($id);
            foreach ($partidos as $p) {
                if ($p['estado'] !== 'Finalizado' && $p['marcador_local'] !== null && $p['marcador_visitante'] !== null) {
                    $this->partidoModel->actualizarResultado($p['id'], $p['marcador_local'], $p['marcador_visitante'], 'Finalizado');
                }
            }

            // 3. Actualizar automáticamente el Medallero Olímpico
            $resPodio = $this->medalleroModel->actualizarMedalleroAutomaticoPorTorneo($id);

            $msgPodio = '';
            if (!empty($resPodio['nombres'])) {
                $parts = [];
                if (!empty($resPodio['asignadas']['ORO']) && isset($resPodio['nombres'][$resPodio['asignadas']['ORO']])) {
                    $parts[] = '🥇 Oro: ' . $resPodio['nombres'][$resPodio['asignadas']['ORO']];
                }
                if (!empty($resPodio['asignadas']['PLATA']) && isset($resPodio['nombres'][$resPodio['asignadas']['PLATA']])) {
                    $parts[] = '🥈 Plata: ' . $resPodio['nombres'][$resPodio['asignadas']['PLATA']];
                }
                if (!empty($resPodio['asignadas']['BRONCE']) && isset($resPodio['nombres'][$resPodio['asignadas']['BRONCE']])) {
                    $parts[] = '🥉 Bronce: ' . $resPodio['nombres'][$resPodio['asignadas']['BRONCE']];
                }
                if (!empty($parts)) {
                    $msgPodio = ' Podio proclamado: ' . implode(' | ', $parts) . '.';
                }
            }

            Session::setFlash('success', '¡Disciplina "' . e($torneo['nombre']) . '" concluida exitosamente! El Medallero Olímpico se ha actualizado automáticamente.' . $msgPodio);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al dar por concluida la disciplina: ' . $e->getMessage());
        }

        $this->redirect('/disciplinas/' . $id);
    }

    /**
     * Reabre una disciplina para continuar la programación o corrección de actas.
     * POST /disciplinas/{id}/reabrir
     *
     * @param int $id
     */
    public function reabrir($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado.');
            $this->redirect('/disciplinas/' . (int)$id);
            return;
        }

        $id = (int)$id;
        $torneo = $this->torneoModel->getById($id);
        if (!$torneo) {
            Session::setFlash('error', 'La disciplina no existe.');
            $this->redirect('/disciplinas');
            return;
        }

        try {
            $nuevoEstado = ($torneo['tipo_torneo'] === 'Grupos_Playoff') ? 'Playoffs' : 'Fase_Grupos';
            $this->torneoModel->cambiarEstado($id, $nuevoEstado);
            Session::setFlash('info', 'La disciplina "' . e($torneo['nombre']) . '" ha sido reabierta en estado ' . $nuevoEstado . '.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al reabrir la disciplina: ' . $e->getMessage());
        }

        $this->redirect('/disciplinas/' . $id);
    }

    /**
     * Guarda resultados por lote de los partidos de una disciplina
     * y opcionalmente la da por concluida en un solo paso.
     * POST /disciplinas/{id}/resultados-rapidos
     *
     * @param int $id
     */
    public function guardarResultadosRapidos($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado.');
            $this->redirect('/disciplinas/' . (int)$id);
            return;
        }

        $id = (int)$id;
        $torneo = $this->torneoModel->getById($id);
        if (!$torneo) {
            Session::setFlash('error', 'La disciplina no existe.');
            $this->redirect('/disciplinas');
            return;
        }

        $marcadoresLocal     = isset($_POST['marcador_local']) && is_array($_POST['marcador_local']) ? $_POST['marcador_local'] : [];
        $marcadoresVisitante = isset($_POST['marcador_visitante']) && is_array($_POST['marcador_visitante']) ? $_POST['marcador_visitante'] : [];
        $estados             = isset($_POST['estado_partido']) && is_array($_POST['estado_partido']) ? $_POST['estado_partido'] : [];

        $actualizados = 0;
        foreach ($marcadoresLocal as $partidoId => $scoreLocal) {
            $partidoId = (int)$partidoId;
            $scoreVisitante = isset($marcadoresVisitante[$partidoId]) ? $marcadoresVisitante[$partidoId] : null;

            if (($scoreLocal !== '' && $scoreLocal !== null) || ($scoreVisitante !== '' && $scoreVisitante !== null)) {
                $mL = ($scoreLocal !== '' && $scoreLocal !== null) ? (int)$scoreLocal : null;
                $mV = ($scoreVisitante !== '' && $scoreVisitante !== null) ? (int)$scoreVisitante : null;
                $est = !empty($estados[$partidoId]) ? trim($estados[$partidoId]) : 'Finalizado';

                try {
                    $this->partidoModel->actualizarResultado($partidoId, $mL, $mV, $est);
                    $actualizados++;
                } catch (Exception $e) {
                    // continuar con los demás
                }
            }
        }

        // Verificar si la Gran Final quedó concluida o si todos los encuentros ya están finalizados
        $concluir = !empty($_POST['concluir_disciplina']);
        $finalFinalizada = false;
        try {
            $partidosTorneo = $this->partidoModel->getAll($id);
            if (!empty($partidosTorneo)) {
                $pendientes = 0;
                foreach ($partidosTorneo as $pt) {
                    $pFase = isset($pt['fase']) ? trim($pt['fase']) : '';
                    $pJornada = isset($pt['jornada']) ? mb_strtolower(trim($pt['jornada']), 'UTF-8') : '';
                    $esFinalMatch = ($pFase === 'Final') ||
                                    (strpos($pJornada, 'final') !== false && strpos($pJornada, 'semi') === false && strpos($pJornada, 'cuarto') === false && strpos($pJornada, 'octavo') === false);
                    if ($esFinalMatch && $pt['estado'] === 'Finalizado') {
                        $finalFinalizada = true;
                        break;
                    }
                    if ($pt['estado'] !== 'Finalizado') {
                        $pendientes++;
                    }
                }
                if ($pendientes === 0) {
                    $finalFinalizada = true;
                }
            }
        } catch (Exception $e) {
            // silencioso
        }

        $msgExtra = '';

        if ($concluir || $finalFinalizada) {
            try {
                $this->torneoModel->cambiarEstado($id, 'Finalizado');
                $resPodio = $this->medalleroModel->actualizarMedalleroAutomaticoPorTorneo($id);

                if (!empty($resPodio['nombres'])) {
                    $parts = [];
                    if (!empty($resPodio['asignadas']['ORO']) && isset($resPodio['nombres'][$resPodio['asignadas']['ORO']])) {
                        $parts[] = '🥇 Oro: ' . $resPodio['nombres'][$resPodio['asignadas']['ORO']];
                    }
                    if (!empty($resPodio['asignadas']['PLATA']) && isset($resPodio['nombres'][$resPodio['asignadas']['PLATA']])) {
                        $parts[] = '🥈 Plata: ' . $resPodio['nombres'][$resPodio['asignadas']['PLATA']];
                    }
                    if (!empty($resPodio['asignadas']['BRONCE']) && isset($resPodio['nombres'][$resPodio['asignadas']['BRONCE']])) {
                        $parts[] = '🥉 Bronce: ' . $resPodio['nombres'][$resPodio['asignadas']['BRONCE']];
                    }
                    if (!empty($parts)) {
                        $msgExtra = ' ¡Disciplina concluida y Medallero Olímpico actualizado! (' . implode(', ', $parts) . ').';
                    }
                }
            } catch (Exception $e) {
                $msgExtra = ' (Error al concluir: ' . $e->getMessage() . ')';
            }
        } elseif ($torneo['estado'] === 'Finalizado') {
            // Si ya estaba finalizado, auto-sincronizar medallero
            try {
                $this->medalleroModel->actualizarMedalleroAutomaticoPorTorneo($id);
            } catch (Exception $e) {
                // silencioso
            }
        }

        Session::setFlash('success', "Se actualizaron {$actualizados} encuentros de la disciplina." . $msgExtra);
        $this->redirect('/disciplinas/' . $id . '#resultados');
    }

    /**
     * Elimina una disciplina
     *
     * @param int $id
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        try {
            $this->torneoModel->deleteTorneo($id);
            Session::setFlash('success', 'Disciplina eliminada satisfactoriamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se pudo eliminar la disciplina: ' . $e->getMessage());
        }

        $this->redirect('/disciplinas');
    }
}
