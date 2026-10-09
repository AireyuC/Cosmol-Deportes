<?php
/**
 * Modelo de Olimpiadas (Macro-Evento)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Olimpiada extends Model {

    /**
     * Obtiene todas las olimpiadas ordenadas por año descendente
     *
     * @return array
     */
    public function getAll() {
        $sql = "SELECT * FROM olimpiadas ORDER BY anio DESC, id DESC";
        return $this->fetchAll($sql);
    }

    /**
     * Obtiene la olimpiada actualmente activa (En Curso) o la más reciente
     *
     * @return array|null
     */
    public function getActiva() {
        $sql = "SELECT * FROM olimpiadas WHERE estado = 'En Curso' ORDER BY anio DESC, id DESC LIMIT 1";
        $activa = $this->fetch($sql);
        if ($activa) {
            return $activa;
        }

        // Si no hay ninguna 'En Curso', retornar la última registrada
        $sqlRecent = "SELECT * FROM olimpiadas ORDER BY id DESC LIMIT 1";
        return $this->fetch($sqlRecent);
    }

    /**
     * Obtiene una olimpiada por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM olimpiadas WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra una nueva olimpiada
     *
     * @param array $data
     * @return int ID creado
     */
    public function create($data) {
        $insertData = [
            'nombre'       => trim($data['nombre']),
            'anio'         => (int)$data['anio'],
            'lema'         => isset($data['lema']) ? trim($data['lema']) : null,
            'fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            'fecha_fin'    => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
            'estado'       => isset($data['estado']) ? $data['estado'] : 'Planificada'
        ];

        return $this->insert('olimpiadas', $insertData);
    }

    /**
     * Actualiza una olimpiada existente
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateOlimpiada($id, $data) {
        $updateData = [
            'nombre'       => trim($data['nombre']),
            'anio'         => (int)$data['anio'],
            'lema'         => isset($data['lema']) ? trim($data['lema']) : null,
            'fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            'fecha_fin'    => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
            'estado'       => isset($data['estado']) ? $data['estado'] : 'Planificada'
        ];

        return $this->update('olimpiadas', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina una olimpiada por su ID
     *
     * @param int $id
     * @return int
     */
    public function deleteOlimpiada($id) {
        return $this->delete('olimpiadas', 'id = :id', ['id' => (int)$id]);
    }
}
