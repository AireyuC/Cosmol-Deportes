<?php
/**
 * Modelo de Torneos Deportivos por Disciplina y Rama
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Torneo extends Model {

    /**
     * Obtiene todos los torneos con datos de olimpiada, disciplina y conteos
     *
     * @param int|null $olimpiadaId
     * @return array
     */
    public function getAll($olimpiadaId = null) {
        $params = [];
        $where = "";

        if ($olimpiadaId !== null) {
            $where = "WHERE t.olimpiada_id = :olimpiada_id";
            $params['olimpiada_id'] = (int)$olimpiadaId;
        }

        $sql = "SELECT t.*, 
                       o.nombre AS olimpiada_nombre, o.anio AS olimpiada_anio,
                       d.nombre AS disciplina_nombre, d.icono AS disciplina_icono,
                       r.pts_victoria, r.pts_empate, r.pts_derrota, r.formato_sets,
                       (SELECT COUNT(*) FROM equipos e WHERE e.torneo_id = t.id) AS total_equipos,
                       (SELECT COUNT(*) FROM torneo_grupos g WHERE g.torneo_id = t.id) AS total_grupos,
                       (SELECT COUNT(*) FROM partidos p WHERE p.torneo_id = t.id) AS total_partidos
                FROM torneos t
                INNER JOIN olimpiadas o ON t.olimpiada_id = o.id
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                LEFT JOIN reglas_puntuacion r ON t.regla_puntuacion_id = r.id
                {$where}
                ORDER BY t.id DESC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Obtiene un torneo detallado por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT t.*, 
                       o.nombre AS olimpiada_nombre, o.anio AS olimpiada_anio,
                       d.nombre AS disciplina_nombre, d.icono AS disciplina_icono,
                       r.pts_victoria, r.pts_empate, r.pts_derrota, r.pts_derrota_ajustada,
                       r.permite_empate, r.formato_sets, r.sets_para_ganar,
                       (SELECT COUNT(*) FROM equipos e WHERE e.torneo_id = t.id) AS total_equipos,
                       (SELECT COUNT(*) FROM torneo_grupos g WHERE g.torneo_id = t.id) AS total_grupos
                FROM torneos t
                INNER JOIN olimpiadas o ON t.olimpiada_id = o.id
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                LEFT JOIN reglas_puntuacion r ON t.regla_puntuacion_id = r.id
                WHERE t.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra un nuevo torneo
     *
     * @param array $data
     * @return int ID creado
     */
    public function create($data) {
        // Si no se proporcionó regla_puntuacion_id, buscar la asociada a la disciplina
        $reglaId = !empty($data['regla_puntuacion_id']) ? (int)$data['regla_puntuacion_id'] : null;
        if (!$reglaId && !empty($data['disciplina_id'])) {
            $sqlRegla = "SELECT id FROM reglas_puntuacion WHERE disciplina_id = :d_id LIMIT 1";
            $regla = $this->fetch($sqlRegla, ['d_id' => (int)$data['disciplina_id']]);
            if ($regla) {
                $reglaId = (int)$regla['id'];
            }
        }

        $insertData = [
            'olimpiada_id'        => (int)$data['olimpiada_id'],
            'disciplina_id'       => (int)$data['disciplina_id'],
            'regla_puntuacion_id' => $reglaId,
            'nombre'              => trim($data['nombre']),
            'rama'                => isset($data['rama']) ? $data['rama'] : 'Masculino',
            'tipo_torneo'         => isset($data['tipo_torneo']) ? $data['tipo_torneo'] : 'Grupos_Playoff',
            'modalidad'           => isset($data['modalidad']) ? $data['modalidad'] : 'Solo_Ida',
            'estado'              => isset($data['estado']) ? $data['estado'] : 'Borrador',
            'fecha_inicio'        => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            'fecha_fin'           => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null
        ];

        return $this->insert('torneos', $insertData);
    }

    /**
     * Actualiza un torneo existente
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateTorneo($id, $data) {
        // Si no se proporcionó regla_puntuacion_id, buscar la asociada a la disciplina
        $reglaId = !empty($data['regla_puntuacion_id']) ? (int)$data['regla_puntuacion_id'] : null;
        if (!$reglaId && !empty($data['disciplina_id'])) {
            $sqlRegla = "SELECT id FROM reglas_puntuacion WHERE disciplina_id = :d_id LIMIT 1";
            $regla = $this->fetch($sqlRegla, ['d_id' => (int)$data['disciplina_id']]);
            if ($regla) {
                $reglaId = (int)$regla['id'];
            }
        }

        $updateData = [
            'olimpiada_id'        => (int)$data['olimpiada_id'],
            'disciplina_id'       => (int)$data['disciplina_id'],
            'regla_puntuacion_id' => $reglaId,
            'nombre'              => trim($data['nombre']),
            'rama'                => isset($data['rama']) ? $data['rama'] : 'Masculino',
            'tipo_torneo'         => isset($data['tipo_torneo']) ? $data['tipo_torneo'] : 'Grupos_Playoff',
            'modalidad'           => isset($data['modalidad']) ? $data['modalidad'] : 'Solo_Ida',
            'estado'              => isset($data['estado']) ? $data['estado'] : 'Borrador',
            'fecha_inicio'        => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            'fecha_fin'           => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null
        ];

        return $this->update('torneos', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Cambia el estado de un torneo (Borrador, Inscripciones, Fase_Grupos, Playoffs, Finalizado)
     *
     * @param int $id
     * @param string $estado
     * @return int
     */
    public function cambiarEstado($id, $estado) {
        return $this->update('torneos', ['estado' => $estado], 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina un torneo
     *
     * @param int $id
     * @return int
     */
    public function deleteTorneo($id) {
        return $this->delete('torneos', 'id = :id', ['id' => (int)$id]);
    }
}
