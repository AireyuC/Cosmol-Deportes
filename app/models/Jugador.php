<?php
/**
 * Modelo de Jugadores / Nómina de Atletas por Equipo
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Jugador extends Model {

    /**
     * Obtiene el padrón de jugadores con filtros opcionales
     *
     * @param int|null $equipoId
     * @param int|null $torneoId
     * @param string|null $estado
     * @return array
     */
    public function getAll($equipoId = null, $torneoId = null, $estado = null) {
        $where = [];
        $params = [];

        if ($equipoId !== null && $equipoId > 0) {
            $where[] = "j.equipo_id = :equipo_id";
            $params['equipo_id'] = (int)$equipoId;
        }

        if ($torneoId !== null && $torneoId > 0) {
            $where[] = "e.torneo_id = :torneo_id";
            $params['torneo_id'] = (int)$torneoId;
        }

        if (!empty($estado)) {
            $where[] = "j.estado = :estado";
            $params['estado'] = $estado;
        }

        $whereSql = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

        $sql = "SELECT j.*, 
                       e.nombre AS equipo_nombre, e.color_indumentaria,
                       d.nombre AS delegacion_nombre, d.color_representativo,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                       disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono
                FROM jugadores j
                INNER JOIN equipos e ON j.equipo_id = e.id
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                INNER JOIN torneos t ON e.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                {$whereSql}
                ORDER BY e.nombre ASC, j.dorsal ASC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Obtiene los jugadores de un equipo específico ordenados por dorsal
     *
     * @param int $equipoId
     * @return array
     */
    public function getByEquipoId($equipoId) {
        return $this->getAll($equipoId);
    }

    /**
     * Obtiene un jugador por su ID con datos del equipo y delegación
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT j.*, 
                       e.nombre AS equipo_nombre, e.color_indumentaria, e.torneo_id,
                       d.nombre AS delegacion_nombre, d.color_representativo,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                       disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono
                FROM jugadores j
                INNER JOIN equipos e ON j.equipo_id = e.id
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                INNER JOIN torneos t ON e.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                WHERE j.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Valida si un dorsal ya está registrado en el equipo (evita duplicados)
     *
     * @param int $equipoId
     * @param int $dorsal
     * @param int|null $excludeId
     * @return bool
     */
    public function dorsalExisteEnEquipo($equipoId, $dorsal, $excludeId = null) {
        $sql = "SELECT id FROM jugadores WHERE equipo_id = :equipo_id AND dorsal = :dorsal";
        $params = [
            'equipo_id' => (int)$equipoId,
            'dorsal'    => (int)$dorsal
        ];

        if ($excludeId !== null && $excludeId > 0) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = (int)$excludeId;
        }

        $sql .= " LIMIT 1";
        $res = $this->fetch($sql, $params);
        return $res !== null;
    }

    /**
     * Registra un nuevo jugador
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'equipo_id'        => (int)$data['equipo_id'],
            'documento'        => trim($data['documento']),
            'nombres'          => trim($data['nombres']),
            'apellidos'        => trim($data['apellidos']),
            'fecha_nacimiento' => !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null,
            'dorsal'           => (int)$data['dorsal'],
            'posicion'         => isset($data['posicion']) ? trim($data['posicion']) : null,
            'foto_url'         => isset($data['foto_url']) ? trim($data['foto_url']) : null,
            'estado'           => isset($data['estado']) ? $data['estado'] : 'Habilitado'
        ];

        return $this->insert('jugadores', $insertData);
    }

    /**
     * Actualiza un jugador
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateJugador($id, $data) {
        $updateData = [
            'equipo_id'        => (int)$data['equipo_id'],
            'documento'        => trim($data['documento']),
            'nombres'          => trim($data['nombres']),
            'apellidos'        => trim($data['apellidos']),
            'fecha_nacimiento' => !empty($data['fecha_nacimiento']) ? $data['fecha_nacimiento'] : null,
            'dorsal'           => (int)$data['dorsal'],
            'posicion'         => isset($data['posicion']) ? trim($data['posicion']) : null,
            'foto_url'         => isset($data['foto_url']) ? trim($data['foto_url']) : null,
            'estado'           => isset($data['estado']) ? $data['estado'] : 'Habilitado'
        ];

        return $this->update('jugadores', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Cambia el estado del jugador (Habilitado, Suspendido, Inactivo)
     *
     * @param int $id
     * @param string $estado
     * @return int
     */
    public function cambiarEstado($id, $estado) {
        return $this->update('jugadores', ['estado' => $estado], 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina un jugador
     *
     * @param int $id
     * @return int
     */
    public function deleteJugador($id) {
        return $this->delete('jugadores', 'id = :id', ['id' => (int)$id]);
    }
}
