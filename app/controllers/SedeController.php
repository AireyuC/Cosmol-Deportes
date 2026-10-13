<?php
/**
 * Controlador de Sedes y Canchas Deportivas
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Controller.php';
require_once APP_PATH . '/models/Sede.php';
require_once APP_PATH . '/models/Cancha.php';
require_once APP_PATH . '/helpers/AuthHelper.php';
require_once APP_PATH . '/helpers/CsrfHelper.php';
require_once APP_PATH . '/helpers/Validator.php';

class SedeController extends Controller {

    /** @var Sede */
    private $sedeModel;
    /** @var Cancha */
    private $canchaModel;

    public function __construct() {
        $this->sedeModel = new Sede();
        $this->canchaModel = new Cancha();
    }

    /**
     * Listado general de sedes
     */
    public function index() {
        AuthHelper::requireLogin();

        $sedes = $this->sedeModel->getAllWithCanchas();
        $this->view('admin/sedes/index', [
            'pageTitle' => 'Sedes y Canchas Deportivas',
            'sedes'     => $sedes
        ]);
    }

    /**
     * Formulario de creación de sede
     */
    public function create() {
        AuthHelper::requireAdmin();

        $this->view('admin/sedes/create', [
            'pageTitle' => 'Registrar Nueva Sede'
        ]);
    }

    /**
     * Guarda la sede y cancha inicial si se proporcionó
     */
    public function store() {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sedes');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre' => 'required|min:3|max:150'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->view('admin/sedes/create', [
                'pageTitle' => 'Registrar Nueva Sede',
                'old'       => $_POST
            ]);
            return;
        }

        $sedeId = $this->sedeModel->create($_POST);

        // Si se especificó una cancha inicial en el formulario
        if (!empty($_POST['nombre_cancha'])) {
            $this->canchaModel->create([
                'sede_id'         => $sedeId,
                'nombre_cancha'   => $_POST['nombre_cancha'],
                'tipo_superficie' => isset($_POST['tipo_superficie']) ? $_POST['tipo_superficie'] : null,
                'estado'          => isset($_POST['estado_cancha']) ? $_POST['estado_cancha'] : 'Disponible'
            ]);
        }

        Session::setFlash('success', 'Sede deportiva registrada correctamente.');
        $this->redirect('/sedes');
    }

    /**
     * Formulario de edición de sede
     */
    public function edit($id) {
        AuthHelper::requireAdmin();

        $sede = $this->sedeModel->getById($id);
        if (!$sede) {
            Session::setFlash('error', 'Sede no encontrada.');
            $this->redirect('/sedes');
        }

        $canchas = $this->canchaModel->getBySedeId($id);

        $this->view('admin/sedes/edit', [
            'pageTitle' => 'Editar Sede',
            'sede'      => $sede,
            'canchas'   => $canchas
        ]);
    }

    /**
     * Actualiza la sede
     */
    public function update($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sedes');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre' => 'required|min:3|max:150'
        ]);

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect('/sedes/' . $id . '/editar');
            return;
        }

        $this->sedeModel->updateSede($id, $_POST);
        Session::setFlash('success', 'Sede actualizada correctamente.');
        $this->redirect('/sedes');
    }

    /**
     * Elimina una sede
     */
    public function delete($id) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sedes');
        }

        try {
            $this->sedeModel->deleteSede($id);
            Session::setFlash('success', 'Sede y canchas eliminadas correctamente.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se puede eliminar la sede porque tiene partidos programados.');
        }

        $this->redirect('/sedes');
    }

    /**
     * Administración de canchas asociadas a una sede
     */
    public function canchas($sedeId) {
        AuthHelper::requireLogin();

        $sede = $this->sedeModel->getById($sedeId);
        if (!$sede) {
            Session::setFlash('error', 'Sede no encontrada.');
            $this->redirect('/sedes');
        }

        $canchas = $this->canchaModel->getBySedeId($sedeId);

        $this->view('admin/sedes/canchas', [
            'pageTitle' => 'Canchas de: ' . $sede['nombre'],
            'sede'      => $sede,
            'canchas'   => $canchas
        ]);
    }

    /**
     * Guarda una cancha en la sede
     */
    public function storeCancha($sedeId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sedes/' . $sedeId . '/canchas');
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre_cancha' => 'required|min:2|max:100'
        ]);

        $redirectTo = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : ('/sedes/' . $sedeId . '/canchas');

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect($redirectTo);
            return;
        }

        $_POST['sede_id'] = $sedeId;
        $this->canchaModel->create($_POST);
        Session::setFlash('success', 'Cancha agregada exitosamente a la sede.');
        $this->redirect($redirectTo);
    }

    /**
     * Actualiza una cancha existente
     */
    public function updateCancha($canchaId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sedes');
        }

        $cancha = $this->canchaModel->getById($canchaId);
        if (!$cancha) {
            Session::setFlash('error', 'Cancha no encontrada.');
            $this->redirect('/sedes');
            return;
        }

        $validator = new Validator();
        $isValid = $validator->validate($_POST, [
            'nombre_cancha' => 'required|min:2|max:100'
        ]);

        $redirectTo = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : ('/sedes/' . $cancha['sede_id'] . '/canchas');

        if (!$isValid) {
            Session::setFlash('error', $validator->getFirstError());
            $this->redirect($redirectTo);
            return;
        }

        $this->canchaModel->updateCancha($canchaId, $_POST);
        Session::setFlash('success', 'Cancha actualizada correctamente.');
        $this->redirect($redirectTo);
    }

    /**
     * Elimina una cancha
     */
    public function deleteCancha($canchaId) {
        AuthHelper::requireAdmin();

        if (!CsrfHelper::validate()) {
            Session::setFlash('error', 'Token de seguridad inválido.');
            $this->redirect('/sedes');
        }

        $cancha = $this->canchaModel->getById($canchaId);
        $sedeId = $cancha ? $cancha['sede_id'] : null;

        try {
            $this->canchaModel->deleteCancha($canchaId);
            Session::setFlash('success', 'Cancha eliminada.');
        } catch (Exception $e) {
            Session::setFlash('error', 'No se puede eliminar la cancha porque tiene partidos programados.');
        }

        $redirectTo = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : ($sedeId ? '/sedes/' . $sedeId . '/canchas' : '/sedes');
        $this->redirect($redirectTo);
    }
}
