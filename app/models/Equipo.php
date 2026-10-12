<?php
/**
 * Modelo de Equipos Deportivos
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Equipo extends Model {

    /**
     * Obtiene todos los equipos con filtros opcionales de torneo, delegación o grupo
     *
     * @param int|null $torneoId
     * @param int|null $delegacionId
     * @param int|null $grupoId
     * @return array
     */
    public function getAll($torneoId = null, $delegacionId = null, $grupoId = null) {
        $where = [];
        $params = [];

        if ($torneoId !== null && $torneoId > 0) {
            $where[] = "e.torneo_id = :torneo_id";
            $params['torneo_id'] = (int)$torneoId;
        }

        if ($delegacionId !== null && $delegacionId > 0) {
            $where[] = "e.delegacion_id = :delegacion_id";
            $params['delegacion_id'] = (int)$delegacionId;
        }

        if ($grupoId !== null && $grupoId > 0) {
            $where[] = "e.grupo_id = :grupo_id";
            $params['grupo_id'] = (int)$grupoId;
        }

        $whereSql = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

        $sql = "SELECT e.*, 
                       d.nombre AS delegacion_nombre, d.alias AS delegacion_alias, d.color_representativo,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                       disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono,
                       g.nombre_grupo,
                       (SELECT COUNT(*) FROM jugadores j WHERE j.equipo_id = e.id) AS total_jugadores
                FROM equipos e
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                INNER JOIN torneos t ON e.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                LEFT JOIN torneo_grupos g ON e.grupo_id = g.id
                {$whereSql}
                ORDER BY t.id DESC, e.nombre ASC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Obtiene un equipo por su ID con todos sus datos asociados
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT e.*, 
                       d.nombre AS delegacion_nombre, d.alias AS delegacion_alias, d.color_representativo,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama, t.olimpiada_id,
                       disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono,
                       g.nombre_grupo,
                       (SELECT COUNT(*) FROM jugadores j WHERE j.equipo_id = e.id) AS total_jugadores
                FROM equipos e
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                INNER JOIN torneos t ON e.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                LEFT JOIN torneo_grupos g ON e.grupo_id = g.id
                WHERE e.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Obtiene los equipos inscritos en un torneo
     *
     * @param int $torneoId
     * @return array
     */
    public function getByTorneoId($torneoId) {
        return $this->getAll($torneoId);
    }

    /**
     * Registra un nuevo equipo
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'delegacion_id'      => (int)$data['delegacion_id'],
            'torneo_id'          => (int)$data['torneo_id'],
            'grupo_id'           => !empty($data['grupo_id']) ? (int)$data['grupo_id'] : null,
            'nombre'             => trim($data['nombre']),
            'nombre_corto'       => isset($data['nombre_corto']) ? trim($data['nombre_corto']) : null,
            'color_indumentaria' => isset($data['color_indumentaria']) ? trim($data['color_indumentaria']) : null,
            'logo_url'           => isset($data['logo_url']) ? trim($data['logo_url']) : null,
            'delegado_contacto'  => isset($data['delegado_contacto']) ? trim($data['delegado_contacto']) : null,
            'telefono_contacto'  => isset($data['telefono_contacto']) ? trim($data['telefono_contacto']) : null
        ];

        return $this->insert('equipos', $insertData);
    }

    /**
     * Actualiza un equipo existente
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateEquipo($id, $data) {
        $updateData = [
            'delegacion_id'      => (int)$data['delegacion_id'],
            'torneo_id'          => (int)$data['torneo_id'],
            'grupo_id'           => !empty($data['grupo_id']) ? (int)$data['grupo_id'] : null,
            'nombre'             => trim($data['nombre']),
            'nombre_corto'       => isset($data['nombre_corto']) ? trim($data['nombre_corto']) : null,
            'color_indumentaria' => isset($data['color_indumentaria']) ? trim($data['color_indumentaria']) : null,
            'logo_url'           => isset($data['logo_url']) ? trim($data['logo_url']) : null,
            'delegado_contacto'  => isset($data['delegado_contacto']) ? trim($data['delegado_contacto']) : null,
            'telefono_contacto'  => isset($data['telefono_contacto']) ? trim($data['telefono_contacto']) : null
        ];

        return $this->update('equipos', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina un equipo por su ID
     *
     * @param int $id
     * @return int
     */
    public function deleteEquipo($id) {
        return $this->delete('equipos', 'id = :id', ['id' => (int)$id]);
    }
}
