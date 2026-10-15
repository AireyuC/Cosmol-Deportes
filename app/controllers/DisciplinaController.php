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

    public function __construct() {
        $this->torneoModel = new Torneo();
        $this->olimpiadaModel = new Olimpiada();
        $this->disciplinaModel = new Disciplina();
        $this->grupoModel = new Grupo();
        $this->equipoModel = new Equipo();
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

        $this->view('admin/disciplinas/show', [
            'pageTitle'        => $torneo['nombre'] . ' | Grupos y Equipos',
            'torneo'           => $torneo,
            'grupos'           => $gruposConEquipos,
            'equiposSinGrupo'  => $equiposSinGrupo,
            'todosEquipos'     => $todosEquipos
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
            'nombre'        => 'required|min:3|max:150',
            'olimpiada_id'  => 'required|numeric',
            'disciplina_id' => 'required|numeric',
            'rama'          => 'required',
            'tipo_torneo'   => 'required',
            'modalidad'     => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/disciplinas/crear');
        }

        try {
            $nuevoId = $this->torneoModel->create($_POST);

            // Si es de tipo Grupos, crear Grupo A por defecto automáticamente
            if ($_POST['tipo_torneo'] === 'Grupos_Playoff') {
                $this->grupoModel->create([
                    'torneo_id'    => $nuevoId,
                    'nombre_grupo' => 'Grupo A',
                    'orden'        => 1
                ]);
            }

            Session::setFlash('success', 'Disciplina "' . e($_POST['nombre']) . '" registrada con éxito.');
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
            'nombre'        => 'required|min:3|max:150',
            'olimpiada_id'  => 'required|numeric',
            'disciplina_id' => 'required|numeric',
            'rama'          => 'required',
            'tipo_torneo'   => 'required',
            'modalidad'     => 'required',
            'estado'        => 'required'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/disciplinas/' . $id . '/editar');
        }

        try {
            $this->torneoModel->updateTorneo($id, $_POST);
            Session::setFlash('success', 'Disciplina actualizada correctamente.');
            $this->redirect('/disciplinas/' . $id);
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al actualizar la disciplina: ' . $e->getMessage());
            $this->redirect('/disciplinas/' . $id . '/editar');
        }
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
