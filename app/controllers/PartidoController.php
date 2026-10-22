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
require_once APP_PATH . '/models/Medallero.php';
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

    /** @var Medallero */
    private $medalleroModel;

    public function __construct() {
        $this->partidoModel    = new Partido();
        $this->partidoSetModel = new PartidoSet();
        $this->incidenciaModel = new Incidencia();
        $this->torneoModel     = new Torneo();
        $this->grupoModel      = new Grupo();
        $this->equipoModel     = new Equipo();
        $this->jugadorModel    = new Jugador();
        $this->canchaModel     = new Cancha();
        $this->medalleroModel  = new Medallero();
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

        $torneoId = isset($_POST['torneo_id']) ? (int)$_POST['torneo_id'] : 0;
        $esPopulares = $this->esDisciplinaJuegosPopulares($torneoId);

        if ($esPopulares) {
            // En Juegos Populares compiten todas las delegaciones simultáneamente en prueba única.
            // Para satisfacer la integridad referencial NOT NULL de la base de datos sin alterarla,
            // asignamos de forma transparente los primeros equipos habilitados de la competencia si no vienen fijados.
            $equiposTorneo = $this->equipoModel->getAll($torneoId);
            if (empty($_POST['equipo_local_id']) && !empty($equiposTorneo)) {
                $_POST['equipo_local_id'] = $equiposTorneo[0]['id'];
            }
            if (empty($_POST['equipo_visitante_id']) && !empty($equiposTorneo)) {
                $_POST['equipo_visitante_id'] = isset($equiposTorneo[1]) ? $equiposTorneo[1]['id'] : $equiposTorneo[0]['id'];
            }
            if (empty($_POST['fase'])) {
                $_POST['fase'] = 'Final';
            }
            if (empty($_POST['jornada'])) {
                $torneoInfo = $this->torneoModel->getById($torneoId);
                $nomT = mb_strtolower(($torneoInfo['nombre'] ?? '') . ' ' . ($torneoInfo['disciplina_nombre'] ?? ''), 'UTF-8');
                if (strpos($nomT, 'karaoke') !== false) {
                    $_POST['jornada'] = 'Noche de Talentos';
                } elseif (strpos($nomT, 'maratón') !== false || strpos($nomT, 'maraton') !== false) {
                    $_POST['jornada'] = 'Carrera Pedestre 5K';
                } else {
                    $_POST['jornada'] = 'Prueba Única';
                }
            }
        }

        $rules = [
            'torneo_id' => 'required|numeric',
            'fase'      => 'required',
            'jornada'   => 'required'
        ];
        if (!$esPopulares) {
            $rules['equipo_local_id']     = 'required|numeric';
            $rules['equipo_visitante_id'] = 'required|numeric';
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, $rules);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/partidos/crear?torneo_id=' . $torneoId);
        }

        $localId     = (int)($_POST['equipo_local_id'] ?? 0);
        $visitanteId = (int)($_POST['equipo_visitante_id'] ?? 0);

        if (!$esPopulares && $localId === $visitanteId) {
            Session::setFlash('error', 'El equipo local y el equipo visitante no pueden ser el mismo.');
            $this->redirect('/partidos/crear?torneo_id=' . (int)$_POST['torneo_id']);
        }

        $canchaId = !empty($_POST['cancha_id']) ? (int)$_POST['cancha_id'] : null;
        $fecha    = !empty($_POST['fecha_partido']) ? trim($_POST['fecha_partido']) : null;
        $hora     = !empty($_POST['hora_partido']) ? trim($_POST['hora_partido']) : null;

        // Validación de colisión de equipos (se omite para juegos populares donde compiten todos a la vez)
        if (!$esPopulares && $fecha && $hora) {
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
                $this->redirect('/disciplinas/' . (int)$_POST['torneo_id']);
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

        $torneoId = isset($_POST['torneo_id']) ? (int)$_POST['torneo_id'] : (int)$partido['torneo_id'];
        $esPopulares = $this->esDisciplinaJuegosPopulares($torneoId);

        if ($esPopulares) {
            $equiposTorneo = $this->equipoModel->getAll($torneoId);
            if (empty($_POST['equipo_local_id']) && !empty($equiposTorneo)) {
                $_POST['equipo_local_id'] = $equiposTorneo[0]['id'];
            } elseif (empty($_POST['equipo_local_id'])) {
                $_POST['equipo_local_id'] = $partido['equipo_local_id'];
            }
            if (empty($_POST['equipo_visitante_id']) && !empty($equiposTorneo)) {
                $_POST['equipo_visitante_id'] = isset($equiposTorneo[1]) ? $equiposTorneo[1]['id'] : $equiposTorneo[0]['id'];
            } elseif (empty($_POST['equipo_visitante_id'])) {
                $_POST['equipo_visitante_id'] = $partido['equipo_visitante_id'];
            }
            if (empty($_POST['fase'])) {
                $_POST['fase'] = 'Final';
            }
            if (empty($_POST['jornada'])) {
                $torneoInfo = $this->torneoModel->getById($torneoId);
                $nomT = mb_strtolower(($torneoInfo['nombre'] ?? '') . ' ' . ($torneoInfo['disciplina_nombre'] ?? ''), 'UTF-8');
                if (strpos($nomT, 'karaoke') !== false) {
                    $_POST['jornada'] = 'Noche de Talentos';
                } elseif (strpos($nomT, 'maratón') !== false || strpos($nomT, 'maraton') !== false) {
                    $_POST['jornada'] = 'Carrera Pedestre 5K';
                } else {
                    $_POST['jornada'] = 'Prueba Única';
                }
            }
        }

        $rules = [
            'torneo_id' => 'required|numeric',
            'fase'      => 'required',
            'jornada'   => 'required'
        ];
        if (!$esPopulares) {
            $rules['equipo_local_id']     = 'required|numeric';
            $rules['equipo_visitante_id'] = 'required|numeric';
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, $rules);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/partidos/' . (int)$id . '/editar');
        }

        $localId     = (int)($_POST['equipo_local_id'] ?? 0);
        $visitanteId = (int)($_POST['equipo_visitante_id'] ?? 0);

        if (!$esPopulares && $localId === $visitanteId) {
            Session::setFlash('error', 'El equipo local y el equipo visitante no pueden ser el mismo.');
            $this->redirect('/partidos/' . (int)$id . '/editar');
        }

        $canchaId = !empty($_POST['cancha_id']) ? (int)$_POST['cancha_id'] : null;
        $fecha    = !empty($_POST['fecha_partido']) ? trim($_POST['fecha_partido']) : null;
        $hora     = !empty($_POST['hora_partido']) ? trim($_POST['hora_partido']) : null;

        // Validación de horarios (se omite colisión de equipos en juegos populares)
        if (!$esPopulares && $fecha && $hora) {
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

            $partidoActualizado = $this->partidoModel->getById($id);
            $estadoP = !empty($_POST['estado']) ? trim($_POST['estado']) : ($partidoActualizado['estado'] ?? '');
            if ($partidoActualizado) {
                $this->sincronizarFinalYMedallero($partidoActualizado['torneo_id'], $partidoActualizado, $estadoP);
            }

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
            return;
        }

        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect('/partidos');
            return;
        }

        $marcadorLocal     = isset($_POST['marcador_local']) && $_POST['marcador_local'] !== '' ? (int)$_POST['marcador_local'] : null;
        $marcadorVisitante = isset($_POST['marcador_visitante']) && $_POST['marcador_visitante'] !== '' ? (int)$_POST['marcador_visitante'] : null;
        $estado            = !empty($_POST['estado']) ? trim($_POST['estado']) : $partido['estado'];

        if ($marcadorLocal !== null && $marcadorVisitante === null) {
            $marcadorVisitante = 0;
        } elseif ($marcadorVisitante !== null && $marcadorLocal === null) {
            $marcadorLocal = 0;
        } elseif (in_array($estado, ['Finalizado', 'WO']) && $marcadorLocal === null && $marcadorVisitante === null) {
            $marcadorLocal = 0;
            $marcadorVisitante = 0;
        }
        $observaciones     = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;
        $fotoPlanilla      = null;

        // Procesar carga de fotografía de planilla oficial si se ha enviado un archivo
        if (isset($_FILES['foto_planilla']) && $_FILES['foto_planilla']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errCode = $_FILES['foto_planilla']['error'];
            if ($errCode !== UPLOAD_ERR_OK) {
                $uploadErrors = [
                    UPLOAD_ERR_INI_SIZE   => 'El archivo excede el tamaño máximo permitido por el servidor (upload_max_filesize). Comprima la foto o aumente el límite.',
                    UPLOAD_ERR_FORM_SIZE  => 'El archivo excede el tamaño máximo configurado en el formulario.',
                    UPLOAD_ERR_PARTIAL    => 'El archivo se subió de forma incompleta. Intente de nuevo.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal en el servidor para almacenar subidas.',
                    UPLOAD_ERR_CANT_WRITE => 'Error al escribir el archivo en el disco del servidor. Verifique permisos.',
                    UPLOAD_ERR_EXTENSION  => 'Una extensión del servidor detuvo la subida del archivo.'
                ];
                $msg = isset($uploadErrors[$errCode]) ? $uploadErrors[$errCode] : 'Error al subir el archivo (Código ' . $errCode . ').';
                Session::setFlash('error', $msg);
                $this->redirect('/partidos/' . (int)$id . '/acta');
                return;
            }

            $file = $_FILES['foto_planilla'];
            $allowedMimes = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/webp', 'application/pdf', 'application/x-pdf'];
            $allowedExts  = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $mime = '';
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
            } elseif (function_exists('mime_content_type')) {
                $mime = mime_content_type($file['tmp_name']);
            }

            if (!in_array($ext, $allowedExts, true) || (!empty($mime) && !in_array($mime, $allowedMimes, true))) {
                Session::setFlash('error', 'Formato no permitido. Solo se aceptan imágenes (JPG, PNG, WEBP) o documentos PDF.');
                $this->redirect('/partidos/' . (int)$id . '/acta');
                return;
            }

            if ($file['size'] > 12 * 1024 * 1024) { // 12 MB
                Session::setFlash('error', 'El archivo de la planilla es demasiado pesado (máximo 12 MB).');
                $this->redirect('/partidos/' . (int)$id . '/acta');
                return;
            }

            $uploadDir = PUBLIC_PATH . DS . 'uploads' . DS . 'planillas';
            if (!is_dir($uploadDir)) {
                if (!@mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
                    Session::setFlash('error', 'No se pudo crear la carpeta "public/uploads/planillas" en el servidor. Asigne permisos de escritura (chmod 775 o 777).');
                    $this->redirect('/partidos/' . (int)$id . '/acta');
                    return;
                }
            }
            @chmod($uploadDir, 0777);

            // Eliminar foto anterior si existe en disco
            $fotoAnterior = isset($partido['foto_planilla']) ? $partido['foto_planilla'] : null;
            if (!empty($fotoAnterior) && file_exists(PUBLIC_PATH . DS . str_replace('/', DS, $fotoAnterior))) {
                @unlink(PUBLIC_PATH . DS . str_replace('/', DS, $fotoAnterior));
            }

            $fileName = 'planilla_p' . (int)$id . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destPath = $uploadDir . DS . $fileName;

            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                $fotoPlanilla = 'uploads/planillas/' . $fileName;
                @chmod($destPath, 0664);
            } else {
                Session::setFlash('error', 'Error del servidor: no se pudo mover el archivo subido a "public/uploads/planillas/". Verifique los permisos de escritura del servidor web.');
                $this->redirect('/partidos/' . (int)$id . '/acta');
                return;
            }
        } elseif (isset($_POST['eliminar_foto_planilla']) && $_POST['eliminar_foto_planilla'] === '1') {
            // Eliminar foto actual si se solicitó explícitamente
            $fotoAnterior = isset($partido['foto_planilla']) ? $partido['foto_planilla'] : null;
            if (!empty($fotoAnterior) && file_exists(PUBLIC_PATH . DS . str_replace('/', DS, $fotoAnterior))) {
                @unlink(PUBLIC_PATH . DS . str_replace('/', DS, $fotoAnterior));
            }
            $this->partidoModel->actualizarFotoPlanilla($id, null);
        }

        try {
            $this->partidoModel->actualizarResultado($id, $marcadorLocal, $marcadorVisitante, $estado, $observaciones, $fotoPlanilla);

            // Sincronizar automáticamente cierre de disciplina y medallero olímpico si aplica
            $partidoActualizado = $this->partidoModel->getById($id);
            $this->sincronizarFinalYMedallero($partido['torneo_id'], $partidoActualizado ? $partidoActualizado : $partido, $estado);

            Session::setFlash('success', 'Resultado, estado y acta del encuentro actualizados.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al guardar el resultado: ' . $e->getMessage());
        }

        $this->redirect('/partidos/' . (int)$id . '/acta');
    }

    /**
     * Carga rápida de resultado para mesa de control desde listados o modales
     * POST /partidos/{id}/resultado-rapido
     *
     * @param int $id
     */
    public function guardarResultadoRapido($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado.');
            $this->redirect($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/partidos');
            return;
        }

        $id = (int)$id;
        $partido = $this->partidoModel->getById($id);
        if (!$partido) {
            Session::setFlash('error', 'El partido no existe.');
            $this->redirect($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/partidos');
            return;
        }

        $marcadorLocal     = isset($_POST['marcador_local']) && $_POST['marcador_local'] !== '' ? (int)$_POST['marcador_local'] : null;
        $marcadorVisitante = isset($_POST['marcador_visitante']) && $_POST['marcador_visitante'] !== '' ? (int)$_POST['marcador_visitante'] : null;
        $estado            = !empty($_POST['estado']) ? trim($_POST['estado']) : 'Finalizado';

        try {
            $this->partidoModel->actualizarResultado($id, $marcadorLocal, $marcadorVisitante, $estado);

            // Sincronizar automáticamente cierre de disciplina y medallero olímpico si aplica
            $partidoActualizado = $this->partidoModel->getById($id);
            $this->sincronizarFinalYMedallero($partido['torneo_id'], $partidoActualizado ? $partidoActualizado : $partido, $estado);

            Session::setFlash('success', 'Resultado guardado exitosamente para el encuentro #' . $id . '.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al registrar resultado: ' . $e->getMessage());
        }

        $redirectUrl = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/disciplinas/' . (int)$partido['torneo_id']);
        $this->redirect($redirectUrl);
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

    /**
     * Si el encuentro finalizado corresponde a la Gran Final, Tercer Puesto o prueba única,
     * o si concluyeron todos los partidos programados, cierra automáticamente el torneo y
     * computa/actualiza las medallas oficiales (Oro, Plata, Bronce) en el Medallero Olímpico.
     *
     * @param int|string $torneoId
     * @param array $partido
     * @param string $estadoPartido
     */
    private function sincronizarFinalYMedallero($torneoId, $partido, $estadoPartido) {
        if ($estadoPartido !== 'Finalizado' || empty($torneoId)) {
            return;
        }

        $torneoId = (int)$torneoId;
        $torneo = $this->torneoModel->getById($torneoId);
        if (!$torneo) {
            return;
        }

        $fase = isset($partido['fase']) ? trim($partido['fase']) : '';
        $jornada = isset($partido['jornada']) ? mb_strtolower(trim($partido['jornada']), 'UTF-8') : '';
        $obs = isset($partido['observaciones']) ? mb_strtolower(trim($partido['observaciones']), 'UTF-8') : '';

        $esFinal = ($fase === 'Final') ||
                   (strpos($jornada, 'final') !== false && strpos($jornada, 'semi') === false && strpos($jornada, 'cuarto') === false && strpos($jornada, 'octavo') === false) ||
                   $this->esDisciplinaJuegosPopulares($torneoId);

        $esTercerPuesto = ($fase === 'Tercer_Puesto') ||
                          strpos($jornada, 'tercer') !== false ||
                          strpos($jornada, '3er') !== false ||
                          strpos($jornada, '3°') !== false ||
                          strpos($obs, 'tercer') !== false ||
                          strpos($obs, 'bronce') !== false;

        // Si no es explícitamente final, verificar si todos los partidos del torneo ya finalizaron (para torneos tipo Liga / Todos contra todos)
        $todosFinalizados = false;
        if (!$esFinal) {
            $partidosTorneo = $this->partidoModel->getAll($torneoId);
            if (!empty($partidosTorneo)) {
                $pendientes = 0;
                foreach ($partidosTorneo as $pt) {
                    if ($pt['estado'] !== 'Finalizado') {
                        $pendientes++;
                    }
                }
                if ($pendientes === 0) {
                    $todosFinalizados = true;
                }
            }
        }

        // Si se jugó la Final o concluyeron todos los partidos del torneo, cerrar automáticamente la disciplina
        if (($esFinal || $todosFinalizados) && $torneo['estado'] !== 'Finalizado') {
            $this->torneoModel->cambiarEstado($torneoId, 'Finalizado');
            $torneo['estado'] = 'Finalizado';
        }

        // Si el torneo quedó Finalizado o se finalizó Final / Tercer Puesto, recalcular y asignar medallas automáticamente
        if ($torneo['estado'] === 'Finalizado' || $esFinal || $esTercerPuesto || $todosFinalizados) {
            $this->medalleroModel->actualizarMedalleroAutomaticoPorTorneo($torneoId);
        }
    }

    /**
     * Determina si un torneo pertenece a una disciplina de competencia colectiva simultánea
     * (Juegos Populares, Karaoke, Media Maratón) donde compiten todas las delegaciones a la vez en prueba única
     *
     * @param int $torneoId
     * @return bool
     */
    private function esDisciplinaJuegosPopulares($torneoId) {
        if (!$torneoId) {
            return false;
        }
        $torneo = $this->torneoModel->getById((int)$torneoId);
        if (!$torneo) {
            return false;
        }
        $nom = mb_strtolower(($torneo['nombre'] ?? '') . ' ' . ($torneo['disciplina_nombre'] ?? ''), 'UTF-8');
        return (
            strpos($nom, 'populares') !== false ||
            strpos($nom, 'tradicionales') !== false ||
            strpos($nom, 'karaoke') !== false ||
            strpos($nom, 'maratón') !== false ||
            strpos($nom, 'maraton') !== false
        );
    }
}

