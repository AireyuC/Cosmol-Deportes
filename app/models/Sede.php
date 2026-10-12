<?php
/**
 * Modelo de Sedes y Complejos Deportivos
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Cancha.php';

class Sede extends Model {

    /**
     * Obtiene todas las sedes con el conteo de canchas asociadas
     *
     * @return array
     */
    public function getAll() {
        $sql = "SELECT s.*, COUNT(c.id) AS total_canchas 
                FROM sedes s 
                LEFT JOIN canchas c ON s.id = c.sede_id 
                GROUP BY s.id 
                ORDER BY s.nombre ASC";
        return $this->fetchAll($sql);
    }

    /**
     * Obtiene una sede por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM sedes WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra una nueva sede
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'nombre'    => trim($data['nombre']),
            'direccion' => isset($data['direccion']) ? trim($data['direccion']) : null,
            'telefono'  => isset($data['telefono']) ? trim($data['telefono']) : null,
            'latitud'   => (!empty($data['latitud']) || (isset($data['latitud']) && is_numeric($data['latitud']))) ? (float)$data['latitud'] : null,
            'longitud'  => (!empty($data['longitud']) || (isset($data['longitud']) && is_numeric($data['longitud']))) ? (float)$data['longitud'] : null,
            'estado'    => isset($data['estado']) ? $data['estado'] : 'Activa'
        ];

        return $this->insert('sedes', $insertData);
    }

    /**
     * Actualiza una sede
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateSede($id, $data) {
        $updateData = [
            'nombre'    => trim($data['nombre']),
            'direccion' => isset($data['direccion']) ? trim($data['direccion']) : null,
            'telefono'  => isset($data['telefono']) ? trim($data['telefono']) : null,
            'latitud'   => (!empty($data['latitud']) || (isset($data['latitud']) && is_numeric($data['latitud']))) ? (float)$data['latitud'] : null,
            'longitud'  => (!empty($data['longitud']) || (isset($data['longitud']) && is_numeric($data['longitud']))) ? (float)$data['longitud'] : null,
            'estado'    => isset($data['estado']) ? $data['estado'] : 'Activa'
        ];

        return $this->update('sedes', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina una sede
     *
     * @param int $id
     * @return int
     */
    public function deleteSede($id) {
        return $this->delete('sedes', 'id = :id', ['id' => (int)$id]);
    }
}
