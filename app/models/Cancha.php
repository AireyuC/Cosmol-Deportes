<?php
/**
 * Modelo de Canchas por Sede
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Cancha extends Model {

    /**
     * Obtiene las canchas asociadas a una sede
     *
     * @param int $sedeId
     * @return array
     */
    public function getBySedeId($sedeId) {
        $sql = "SELECT * FROM canchas WHERE sede_id = :sede_id ORDER BY id ASC";
        return $this->fetchAll($sql, ['sede_id' => (int)$sedeId]);
    }

    /**
     * Obtiene una cancha por su ID junto al nombre de su sede
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT c.*, s.nombre AS sede_nombre 
                FROM canchas c 
                JOIN sedes s ON c.sede_id = s.id 
                WHERE c.id = :id 
                LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra una nueva cancha
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'sede_id'         => (int)$data['sede_id'],
            'nombre_cancha'   => trim($data['nombre_cancha']),
            'tipo_superficie' => isset($data['tipo_superficie']) ? trim($data['tipo_superficie']) : null,
            'estado'          => isset($data['estado']) ? $data['estado'] : 'Disponible'
        ];

        return $this->insert('canchas', $insertData);
    }

    /**
     * Actualiza los datos de una cancha
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateCancha($id, $data) {
        $updateData = [
            'nombre_cancha'   => trim($data['nombre_cancha']),
            'tipo_superficie' => isset($data['tipo_superficie']) ? trim($data['tipo_superficie']) : null,
            'estado'          => isset($data['estado']) ? $data['estado'] : 'Disponible'
        ];

        return $this->update('canchas', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina una cancha
     *
     * @param int $id
     * @return int
     */
    public function deleteCancha($id) {
        return $this->delete('canchas', 'id = :id', ['id' => (int)$id]);
    }
}
