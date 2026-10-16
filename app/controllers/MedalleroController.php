<?php
/**
 * Controlador del Medallero Olímpico Oficial
 * Vista pública del medallero y gestión administrativa de medallas por torneo.
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Medallero.php';
require_once APP_PATH . '/models/Olimpiada.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Session.php';

class MedalleroController extends Controller {

    /** @var Medallero */
    private $medalleroModel;

    /** @var Olimpiada */
    private $olimpiadaModel;

    /** @var Torneo */
    private $torneoModel;

    public function __construct() {
        $this->medalleroModel = new Medallero();
        $this->olimpiadaModel = new Olimpiada();
        $this->torneoModel    = new Torneo();
    }

    /**
     * Vista principal del Medallero Olímpico.
     * Accesible para todos los usuarios autenticados.
     */
    public function index() {
        AuthHelper::requireLogin();

        $olimpiada = null;
        $resumen   = [];
        $podios    = [];
        $mensajeInfo = '';

        try {
            $olimpiada = $this->olimpiadaModel->getActiva();
        } catch (Exception $e) {
            // continúa sin olimpiada
        }

        if ($olimpiada) {
            try {
                $resumen = $this->medalleroModel->getResumenPorOlimpiada((int)$olimpiada['id']);
                $podios  = $this->medalleroModel->getPodiosPorOlimpiada((int)$olimpiada['id']);
            } catch (Exception $e) {
                $resumen = [];
                $podios  = [];
            }

            if (empty($resumen)) {
                $mensajeInfo = 'Aún no se han asignado medallas en esta olimpiada. '
                    . 'Las medallas se asignan al concluir cada torneo por parte del administrador.';
            }
        } else {
            $mensajeInfo = 'No hay ninguna olimpiada activa en este momento.';
        }

        $this->view('admin/medallero/index', [
            'pageTitle'   => 'Medallero Olímpico Oficial',
            'olimpiada'   => $olimpiada,
            'resumen'     => $resumen,
            'podios'      => $podios,
            'mensajeInfo' => $mensajeInfo
        ]);
    }

    /**
     * Vista de gestión administrativa de medallas.
     * Solo ADMIN. Muestra torneos finalizados con opción de asignar el podio.
     */
    public function gestion() {
        AuthHelper::requireAdmin();

        $olimpiada = null;
        $torneos   = [];

        try {
            $olimpiada = $this->olimpiadaModel->getActiva();
        } catch (Exception $e) {
            // continúa
        }

        if ($olimpiada) {
            try {
                $torneos = $this->medalleroModel->getTorneosFinalizadosPendientes((int)$olimpiada['id']);
            } catch (Exception $e) {
                $torneos = [];
            }
        }

        $this->view('admin/medallero/gestion', [
            'pageTitle' => 'Gestión de Medallas',
            'olimpiada' => $olimpiada,
            'torneos'   => $torneos,
            'csrfToken' => CsrfHelper::getToken()
        ]);
    }

    /**
     * Muestra el formulario para asignar el podio de un torneo específico.
     * GET /medallero/torneos/{torneoId}/asignar
     *
     * @param int $torneoId
     */
    public function asignar($torneoId) {
        AuthHelper::requireAdmin();

        $torneoId = (int)$torneoId;
        $torneo   = null;
        $medallasPrevias = [];
        $delegaciones    = [];

        try {
            $torneo = $this->torneoModel->getById($torneoId);
        } catch (Exception $e) {
            // no encontrado
        }

        if (!$torneo) {
            Session::setFlash('error', 'Torneo no encontrado.');
            $this->redirect('/medallero/gestion');
            return;
        }

        try {
            $medallasPrevias = $this->medalleroModel->getMedallasPorTorneo($torneoId);
            $delegaciones    = $this->medalleroModel->getDelegacionesPorTorneo($torneoId);
        } catch (Exception $e) {
            $medallasPrevias = [];
            $delegaciones    = [];
        }

        // Indexar medallas previas por tipo
        $asignadas = [];
        foreach ($medallasPrevias as $m) {
            $asignadas[$m['tipo_medalla']] = $m;
        }

        $this->view('admin/medallero/asignar', [
            'pageTitle'   => 'Asignar Podio — ' . $torneo['nombre'],
            'torneo'      => $torneo,
            'delegaciones' => $delegaciones,
            'asignadas'   => $asignadas,
            'csrfToken'   => CsrfHelper::getToken()
        ]);
    }

    /**
     * Procesa la asignación del podio (POST).
     * POST /medallero/torneos/{torneoId}/guardar
     *
     * @param int $torneoId
     */
    public function guardarPodio($torneoId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado. Intente nuevamente.');
            $this->redirect('/medallero/gestion');
            return;
        }

        $torneoId = (int)$torneoId;
        $torneo   = null;

        try {
            $torneo = $this->torneoModel->getById($torneoId);
        } catch (Exception $e) {
            // no encontrado
        }

        if (!$torneo) {
            Session::setFlash('error', 'Torneo no encontrado.');
            $this->redirect('/medallero/gestion');
            return;
        }

        $olimpiadaId = (int)$torneo['olimpiada_id'];

        // Puntajes olímpicos por posición según estándar (configurable)
        $puntajesPorMedalla = [
            'ORO'    => 5,
            'PLATA'  => 3,
            'BRONCE' => 1
        ];

        $errores = [];
        $tiposMedallas = ['ORO', 'PLATA', 'BRONCE'];

        foreach ($tiposMedallas as $tipo) {
            $campo = 'delegacion_' . strtolower($tipo);
            $delegacionId = isset($_POST[$campo]) ? (int)$_POST[$campo] : 0;

            if ($delegacionId <= 0) {
                continue; // Medalla opcional si no se selecciona delegación
            }

            $puntaje = isset($_POST['puntos_' . strtolower($tipo)])
                ? (int)$_POST['puntos_' . strtolower($tipo)]
                : $puntajesPorMedalla[$tipo];

            try {
                $this->medalleroModel->asignarMedalla(
                    $olimpiadaId,
                    $torneoId,
                    $delegacionId,
                    $tipo,
                    $puntaje
                );
            } catch (Exception $e) {
                $errores[] = 'Error al guardar medalla ' . $tipo . ': ' . $e->getMessage();
            }
        }

        if (!empty($errores)) {
            Session::setFlash('error', implode(' / ', $errores));
        } else {
            Session::setFlash('success', 'Podio del torneo "' . $torneo['nombre'] . '" asignado correctamente.');
        }

        $this->redirect('/medallero/gestion');
    }

    /**
     * Elimina una medalla específica del medallero.
     * POST /medallero/{medallaId}/eliminar
     *
     * @param int $medallaId
     */
    public function eliminarMedalla($medallaId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido o expirado. Intente nuevamente.');
            $this->redirect('/medallero/gestion');
            return;
        }

        $medallaId = (int)$medallaId;
        $eliminadas = 0;

        try {
            $eliminadas = $this->medalleroModel->eliminarMedalla($medallaId);
        } catch (Exception $e) {
            $eliminadas = 0;
        }

        if ($eliminadas > 0) {
            Session::setFlash('success', 'Medalla eliminada del medallero.');
        } else {
            Session::setFlash('warning', 'No se encontró la medalla o ya había sido eliminada.');
        }

        $this->redirect('/medallero/gestion');
    }
}
