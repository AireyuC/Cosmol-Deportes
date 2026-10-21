<?php
/**
 * Modelo de Delegaciones Institucionales
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Delegacion extends Model {

    /**
     * Obtiene todas las delegaciones con el conteo de equipos inscritos
     *
     * @return array
     */
    public function getAll() {
        $sql = "SELECT d.*, 
                       COUNT(DISTINCT e.id) AS total_equipos,
                       GROUP_CONCAT(DISTINCT disc.nombre ORDER BY disc.nombre SEPARATOR ', ') AS disciplinas_nombres
                FROM delegaciones d 
                LEFT JOIN equipos e ON d.id = e.delegacion_id 
                LEFT JOIN torneos t ON e.torneo_id = t.id
                LEFT JOIN disciplinas disc ON t.disciplina_id = disc.id
                WHERE d.nombre NOT LIKE '%definir%' AND d.alias NOT LIKE '%definir%'
                GROUP BY d.id 
                ORDER BY d.nombre ASC";
        return $this->fetchAll($sql);
    }

    /**
     * Obtiene una delegación por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM delegaciones WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra una nueva delegación
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'nombre'               => trim($data['nombre']),
            'alias'                => isset($data['alias']) ? trim($data['alias']) : null,
            'color_representativo' => !empty($data['color_representativo']) ? trim($data['color_representativo']) : '#007bff',
            'responsable'          => isset($data['responsable']) ? trim($data['responsable']) : null,
            'telefono'             => isset($data['telefono']) ? trim($data['telefono']) : null,
            'logo_url'             => isset($data['logo_url']) ? trim($data['logo_url']) : null
        ];

        return $this->insert('delegaciones', $insertData);
    }

    /**
     * Actualiza una delegación
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateDelegacion($id, $data) {
        $updateData = [
            'nombre'               => trim($data['nombre']),
            'alias'                => isset($data['alias']) ? trim($data['alias']) : null,
            'color_representativo' => !empty($data['color_representativo']) ? trim($data['color_representativo']) : '#007bff',
            'responsable'          => isset($data['responsable']) ? trim($data['responsable']) : null,
            'telefono'             => isset($data['telefono']) ? trim($data['telefono']) : null,
            'logo_url'             => isset($data['logo_url']) ? trim($data['logo_url']) : null
        ];

        return $this->update('delegaciones', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina una delegación
     *
     * @param int $id
     * @return int
     */
    public function deleteDelegacion($id) {
        return $this->delete('delegaciones', 'id = :id', ['id' => (int)$id]);
    }
}
