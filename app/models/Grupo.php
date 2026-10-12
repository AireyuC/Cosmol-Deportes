<?php
/**
 * Modelo de Grupos por Torneo (Fases de Grupos: Grupo A, Grupo B, etc.)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Grupo extends Model {

    /**
     * Obtiene los grupos de un torneo con el conteo de equipos asignados
     *
     * @param int $torneoId
     * @return array
     */
    public function getByTorneoId($torneoId) {
        $sql = "SELECT g.*, 
                       (SELECT COUNT(*) FROM equipos e WHERE e.grupo_id = g.id) AS total_equipos
                FROM torneo_grupos g
                WHERE g.torneo_id = :torneo_id
                ORDER BY g.orden ASC, g.id ASC";

        return $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);
    }

    /**
     * Obtiene un grupo por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT g.*, t.nombre AS torneo_nombre 
                FROM torneo_grupos g
                INNER JOIN torneos t ON g.torneo_id = t.id
                WHERE g.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Obtiene todos los grupos de un torneo incluyendo el listado de sus equipos
     *
     * @param int $torneoId
     * @return array
     */
    public function getGruposConEquipos($torneoId) {
        $grupos = $this->getByTorneoId($torneoId);

        foreach ($grupos as &$grupo) {
            $sqlEquipos = "SELECT e.*, d.nombre AS delegacion_nombre, d.color_representativo,
                                  (SELECT COUNT(*) FROM jugadores j WHERE j.equipo_id = e.id) AS total_jugadores
                           FROM equipos e
                           INNER JOIN delegaciones d ON e.delegacion_id = d.id
                           WHERE e.grupo_id = :grupo_id
                           ORDER BY e.nombre ASC";
            $grupo['equipos'] = $this->fetchAll($sqlEquipos, ['grupo_id' => (int)$grupo['id']]);
        }

        return $grupos;
    }

    /**
     * Obtiene los equipos inscritos en el torneo que aún no tienen grupo asignado
     *
     * @param int $torneoId
     * @return array
     */
    public function getEquiposSinGrupo($torneoId) {
        $sql = "SELECT e.*, d.nombre AS delegacion_nombre, d.color_representativo
                FROM equipos e
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                WHERE e.torneo_id = :torneo_id AND (e.grupo_id IS NULL OR e.grupo_id = 0)
                ORDER BY e.nombre ASC";

        return $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);
    }

    /**
     * Crea un nuevo grupo en el torneo
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        // Calcular el orden automáticamente si no viene dado
        $orden = isset($data['orden']) ? (int)$data['orden'] : 0;
        if ($orden <= 0) {
            $sqlMax = "SELECT MAX(orden) AS max_orden FROM torneo_grupos WHERE torneo_id = :torneo_id";
            $res = $this->fetch($sqlMax, ['torneo_id' => (int)$data['torneo_id']]);
            $orden = ($res && $res['max_orden']) ? ((int)$res['max_orden'] + 1) : 1;
        }

        $insertData = [
            'torneo_id'    => (int)$data['torneo_id'],
            'nombre_grupo' => trim($data['nombre_grupo']),
            'orden'        => $orden
        ];

        return $this->insert('torneo_grupos', $insertData);
    }

    /**
     * Actualiza el nombre u orden de un grupo
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateGrupo($id, $data) {
        $updateData = [
            'nombre_grupo' => trim($data['nombre_grupo']),
            'orden'        => isset($data['orden']) ? (int)$data['orden'] : 1
        ];

        return $this->update('torneo_grupos', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Asigna un equipo a un grupo específico
     *
     * @param int $equipoId
     * @param int $grupoId
     * @return int
     */
    public function asignarEquipo($equipoId, $grupoId) {
        return $this->update('equipos', ['grupo_id' => (int)$grupoId], 'id = :where_id', ['where_id' => (int)$equipoId]);
    }

    /**
     * Desasigna un equipo de su grupo actual (lo deja sin grupo)
     *
     * @param int $equipoId
     * @return int
     */
    public function desasignarEquipo($equipoId) {
        $sql = "UPDATE equipos SET grupo_id = NULL WHERE id = :id";
        $stmt = $this->query($sql, ['id' => (int)$equipoId]);
        return $stmt->rowCount();
    }

    /**
     * Elimina un grupo y desasigna sus equipos asociados
     *
     * @param int $id
     * @return int
     */
    public function deleteGrupo($id) {
        // Primero desasignar los equipos del grupo para evitar inconsistencias
        $this->update('equipos', ['grupo_id' => null], 'grupo_id = :where_id', ['where_id' => (int)$id]);
        return $this->delete('torneo_grupos', 'id = :id', ['id' => (int)$id]);
    }
}
