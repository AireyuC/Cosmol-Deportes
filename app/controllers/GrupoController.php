<?php
/**
 * Controlador de Grupos por Torneo (Creación, Asignación y Gestión de Grupos)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Grupo.php';
require_once APP_PATH . '/models/Equipo.php';
require_once APP_PATH . '/models/Torneo.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class GrupoController extends Controller {

    /** @var Grupo */
    private $grupoModel;

    /** @var Equipo */
    private $equipoModel;

    public function __construct() {
        $this->grupoModel = new Grupo();
        $this->equipoModel = new Equipo();
    }

    /**
     * Crea un nuevo grupo dentro de un torneo
     *
     * @param int $torneoId
     */
    public function store($torneoId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas/' . $torneoId);
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre_grupo' => 'required|min:2|max:50'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/disciplinas/' . $torneoId);
        }

        try {
            $data = [
                'torneo_id'    => (int)$torneoId,
                'nombre_grupo' => trim($_POST['nombre_grupo']),
                'orden'        => !empty($_POST['orden']) ? (int)$_POST['orden'] : 0
            ];

            $this->grupoModel->create($data);
            Session::setFlash('success', 'Grupo "' . e($_POST['nombre_grupo']) . '" creado exitosamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al crear el grupo: ' . $e->getMessage());
        }

        $this->redirect('/disciplinas/' . $torneoId);
    }

    /**
     * Asigna un equipo a un grupo específico
     */
    public function asignarEquipo() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        $equipoId = isset($_POST['equipo_id']) ? (int)$_POST['equipo_id'] : 0;
        $grupoId = isset($_POST['grupo_id']) ? (int)$_POST['grupo_id'] : 0;
        $torneoId = isset($_POST['torneo_id']) ? (int)$_POST['torneo_id'] : 0;

        if ($equipoId <= 0 || $grupoId <= 0) {
            Session::setFlash('error', 'Debe seleccionar un equipo y un grupo válidos.');
            $this->redirect($torneoId > 0 ? '/disciplinas/' . $torneoId : '/disciplinas');
        }

        try {
            $this->grupoModel->asignarEquipo($equipoId, $grupoId);
            Session::setFlash('success', 'Equipo asignado al grupo satisfactoriamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al asignar el equipo: ' . $e->getMessage());
        }

        $this->redirect($torneoId > 0 ? '/disciplinas/' . $torneoId : '/disciplinas');
    }

    /**
     * Desasigna un equipo de su grupo
     *
     * @param int $equipoId
     */
    public function desasignarEquipo($equipoId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        $torneoId = isset($_POST['torneo_id']) ? (int)$_POST['torneo_id'] : 0;

        try {
            $this->grupoModel->desasignarEquipo($equipoId);
            Session::setFlash('success', 'Equipo desasignado del grupo.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al desasignar equipo: ' . $e->getMessage());
        }

        $this->redirect($torneoId > 0 ? '/disciplinas/' . $torneoId : '/disciplinas');
    }

    /**
     * Elimina un grupo del torneo
     *
     * @param int $id
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/disciplinas');
        }

        $torneoId = isset($_POST['torneo_id']) ? (int)$_POST['torneo_id'] : 0;

        try {
            $this->grupoModel->deleteGrupo($id);
            Session::setFlash('success', 'Grupo eliminado. Los equipos que pertenecían al grupo quedaron sin grupo asignado.');
        } catch (Exception $e) {
            Session::setFlash('error', 'Error al eliminar el grupo: ' . $e->getMessage());
        }

        $this->redirect($torneoId > 0 ? '/disciplinas/' . $torneoId : '/disciplinas');
    }
}
