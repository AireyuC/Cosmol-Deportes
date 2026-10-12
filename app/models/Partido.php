<?php
/**
 * Modelo de Partidos / Encuentros Deportivos y Fixture
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Partido extends Model {

    /**
     * Obtiene el listado de partidos con filtros opcionales
     *
     * @param int|null $torneoId
     * @param string|null $estado
     * @param string|null $fecha
     * @param string|null $jornada
     * @param int|null $grupoId
     * @return array
     */
    public function getAll($torneoId = null, $estado = null, $fecha = null, $jornada = null, $grupoId = null) {
        $where = [];
        $params = [];

        if ($torneoId !== null && $torneoId > 0) {
            $where[] = "p.torneo_id = :torneo_id";
            $params['torneo_id'] = (int)$torneoId;
        }

        if (!empty($estado)) {
            $where[] = "p.estado = :estado";
            $params['estado'] = $estado;
        }

        if (!empty($fecha)) {
            $where[] = "p.fecha_partido = :fecha";
            $params['fecha'] = $fecha;
        }

        if (!empty($jornada)) {
            $where[] = "p.jornada = :jornada";
            $params['jornada'] = $jornada;
        }

        if ($grupoId !== null && $grupoId > 0) {
            $where[] = "p.grupo_id = :grupo_id";
            $params['grupo_id'] = (int)$grupoId;
        }

        $whereSql = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

        $sql = "SELECT p.*,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama, t.tipo_torneo, t.olimpiada_id,
                       disc.id AS disciplina_id, disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono,
                       r.formato_sets, r.sets_para_ganar, r.permite_empate,
                       g.nombre_grupo,
                       el.nombre AS local_nombre, el.nombre_corto AS local_corto, el.logo_url AS local_logo,
                       dl.nombre AS delegacion_local, dl.color_representativo AS local_color,
                       ev.nombre AS visitante_nombre, ev.nombre_corto AS visitante_corto, ev.logo_url AS visitante_logo,
                       dv.nombre AS delegacion_visitante, dv.color_representativo AS visitante_color,
                       c.nombre_cancha, c.tipo_superficie,
                       s.id AS sede_id, s.nombre AS sede_nombre, s.direccion AS sede_direccion,
                       s.latitud AS sede_latitud, s.longitud AS sede_longitud
                FROM partidos p
                INNER JOIN torneos t ON p.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                LEFT JOIN reglas_puntuacion r ON t.regla_puntuacion_id = r.id
                LEFT JOIN torneo_grupos g ON p.grupo_id = g.id
                INNER JOIN equipos el ON p.equipo_local_id = el.id
                INNER JOIN delegaciones dl ON el.delegacion_id = dl.id
                INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
                INNER JOIN delegaciones dv ON ev.delegacion_id = dv.id
                LEFT JOIN canchas c ON p.cancha_id = c.id
                LEFT JOIN sedes s ON c.sede_id = s.id
                {$whereSql}
                ORDER BY p.fecha_partido ASC, p.hora_partido ASC, p.id ASC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Obtiene el detalle completo de un partido por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT p.*,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama, t.tipo_torneo, t.olimpiada_id,
                       disc.id AS disciplina_id, disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono,
                       r.formato_sets, r.sets_para_ganar, r.permite_empate,
                       g.nombre_grupo,
                       el.nombre AS local_nombre, el.nombre_corto AS local_corto, el.logo_url AS local_logo,
                       el.color_indumentaria AS local_indumentaria,
                       dl.nombre AS delegacion_local, dl.color_representativo AS local_color,
                       ev.nombre AS visitante_nombre, ev.nombre_corto AS visitante_corto, ev.logo_url AS visitante_logo,
                       ev.color_indumentaria AS visitante_indumentaria,
                       dv.nombre AS delegacion_visitante, dv.color_representativo AS visitante_color,
                       c.nombre_cancha, c.tipo_superficie,
                       s.id AS sede_id, s.nombre AS sede_nombre, s.direccion AS sede_direccion,
                       s.latitud AS sede_latitud, s.longitud AS sede_longitud
                FROM partidos p
                INNER JOIN torneos t ON p.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                LEFT JOIN reglas_puntuacion r ON t.regla_puntuacion_id = r.id
                LEFT JOIN torneo_grupos g ON p.grupo_id = g.id
                INNER JOIN equipos el ON p.equipo_local_id = el.id
                INNER JOIN delegaciones dl ON el.delegacion_id = dl.id
                INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
                INNER JOIN delegaciones dv ON ev.delegacion_id = dv.id
                LEFT JOIN canchas c ON p.cancha_id = c.id
                LEFT JOIN sedes s ON c.sede_id = s.id
                WHERE p.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra un nuevo partido en el fixture
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'torneo_id'           => (int)$data['torneo_id'],
            'grupo_id'            => (!empty($data['grupo_id']) && (int)$data['grupo_id'] > 0) ? (int)$data['grupo_id'] : null,
            'fase'                => !empty($data['fase']) ? $data['fase'] : 'Grupos',
            'jornada'             => !empty($data['jornada']) ? trim($data['jornada']) : 'Jornada 1',
            'equipo_local_id'     => (int)$data['equipo_local_id'],
            'equipo_visitante_id' => (int)$data['equipo_visitante_id'],
            'cancha_id'           => (!empty($data['cancha_id']) && (int)$data['cancha_id'] > 0) ? (int)$data['cancha_id'] : null,
            'fecha_partido'       => !empty($data['fecha_partido']) ? $data['fecha_partido'] : null,
            'hora_partido'        => !empty($data['hora_partido']) ? $data['hora_partido'] : null,
            'estado'              => !empty($data['estado']) ? $data['estado'] : 'Programado',
            'observaciones'       => !empty($data['observaciones']) ? trim($data['observaciones']) : null
        ];

        return $this->insert('partidos', $insertData);
    }

    /**
     * Actualiza la programación de un partido
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updatePartido($id, $data) {
        $updateData = [
            'torneo_id'           => (int)$data['torneo_id'],
            'grupo_id'            => (!empty($data['grupo_id']) && (int)$data['grupo_id'] > 0) ? (int)$data['grupo_id'] : null,
            'fase'                => !empty($data['fase']) ? $data['fase'] : 'Grupos',
            'jornada'             => !empty($data['jornada']) ? trim($data['jornada']) : 'Jornada 1',
            'equipo_local_id'     => (int)$data['equipo_local_id'],
            'equipo_visitante_id' => (int)$data['equipo_visitante_id'],
            'cancha_id'           => (!empty($data['cancha_id']) && (int)$data['cancha_id'] > 0) ? (int)$data['cancha_id'] : null,
            'fecha_partido'       => !empty($data['fecha_partido']) ? $data['fecha_partido'] : null,
            'hora_partido'        => !empty($data['hora_partido']) ? $data['hora_partido'] : null,
            'estado'              => !empty($data['estado']) ? $data['estado'] : 'Programado',
            'observaciones'       => isset($data['observaciones']) ? trim($data['observaciones']) : null
        ];

        return $this->update('partidos', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Actualiza el resultado oficial y estado de un partido
     *
     * @param int $id
     * @param int|null $marcadorLocal
     * @param int|null $marcadorVisitante
     * @param string $estado
     * @param string|null $observaciones
     * @return int
     */
    public function actualizarResultado($id, $marcadorLocal, $marcadorVisitante, $estado, $observaciones = null) {
        $updateData = [
            'marcador_local'     => ($marcadorLocal !== null && $marcadorLocal !== '') ? (int)$marcadorLocal : null,
            'marcador_visitante' => ($marcadorVisitante !== null && $marcadorVisitante !== '') ? (int)$marcadorVisitante : null,
            'estado'             => $estado
        ];

        if ($observaciones !== null) {
            $updateData['observaciones'] = trim($observaciones);
        }

        return $this->update('partidos', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Elimina un partido del fixture
     *
     * @param int $id
     * @return int
     */
    public function deletePartido($id) {
        return $this->delete('partidos', 'id = :id', ['id' => (int)$id]);
    }

    /**
     * Detección de colisión de cancha: verifica si la cancha ya está asignada en el mismo rango de tiempo (90 min)
     *
     * @param int $canchaId
     * @param string $fecha
     * @param string $hora
     * @param int|null $excludePartidoId
     * @return array|null
     */
    public function verificarColisionCancha($canchaId, $fecha, $hora, $excludePartidoId = null) {
        if (empty($canchaId) || empty($fecha) || empty($hora)) {
            return null;
        }

        $params = [
            'cancha_id' => (int)$canchaId,
            'fecha'     => $fecha,
            'hora'      => $hora
        ];

        $excludeSql = "";
        if ($excludePartidoId !== null && (int)$excludePartidoId > 0) {
            $excludeSql = "AND p.id != :exclude_id";
            $params['exclude_id'] = (int)$excludePartidoId;
        }

        $sql = "SELECT p.*, el.nombre AS local_nombre, ev.nombre AS visitante_nombre
                FROM partidos p
                INNER JOIN equipos el ON p.equipo_local_id = el.id
                INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
                WHERE p.cancha_id = :cancha_id
                  AND p.fecha_partido = :fecha
                  AND p.estado NOT IN ('Finalizado', 'Suspendido', 'WO')
                  AND ABS(TIME_TO_SEC(TIMEDIFF(p.hora_partido, :hora))) < 5400
                  {$excludeSql}
                LIMIT 1";

        return $this->fetch($sql, $params);
    }

    /**
     * Detección de colisión de equipo: verifica si un equipo tiene otro partido programado en el mismo rango de tiempo
     *
     * @param int $equipoId
     * @param string $fecha
     * @param string $hora
     * @param int|null $excludePartidoId
     * @return array|null
     */
    public function verificarColisionEquipo($equipoId, $fecha, $hora, $excludePartidoId = null) {
        if (empty($equipoId) || empty($fecha) || empty($hora)) {
            return null;
        }

        $params = [
            'equipo_id' => (int)$equipoId,
            'fecha'     => $fecha,
            'hora'      => $hora
        ];

        $excludeSql = "";
        if ($excludePartidoId !== null && (int)$excludePartidoId > 0) {
            $excludeSql = "AND p.id != :exclude_id";
            $params['exclude_id'] = (int)$excludePartidoId;
        }

        $sql = "SELECT p.*, el.nombre AS local_nombre, ev.nombre AS visitante_nombre
                FROM partidos p
                INNER JOIN equipos el ON p.equipo_local_id = el.id
                INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
                WHERE (p.equipo_local_id = :equipo_id OR p.equipo_visitante_id = :equipo_id)
                  AND p.fecha_partido = :fecha
                  AND p.estado NOT IN ('Finalizado', 'Suspendido', 'WO')
                  AND ABS(TIME_TO_SEC(TIMEDIFF(p.hora_partido, :hora))) < 5400
                  {$excludeSql}
                LIMIT 1";

        return $this->fetch($sql, $params);
    }

    /**
     * Retorna contadores generales para métricas de fixture
     *
     * @param int|null $torneoId
     * @return array
     */
    public function getEstadisticas($torneoId = null) {
        $params = [];
        $where = "";

        if ($torneoId !== null && (int)$torneoId > 0) {
            $where = "WHERE torneo_id = :torneo_id";
            $params['torneo_id'] = (int)$torneoId;
        }

        $sql = "SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado = 'Programado' THEN 1 ELSE 0 END) AS programados,
                    SUM(CASE WHEN estado = 'En_Juego' THEN 1 ELSE 0 END) AS en_juego,
                    SUM(CASE WHEN estado = 'Finalizado' THEN 1 ELSE 0 END) AS finalizados,
                    SUM(CASE WHEN estado = 'Suspendido' THEN 1 ELSE 0 END) AS suspendidos
                FROM partidos
                {$where}";

        $res = $this->fetch($sql, $params);

        return [
            'total'       => (int)($res['total'] ?? 0),
            'programados' => (int)($res['programados'] ?? 0),
            'en_juego'    => (int)($res['en_juego'] ?? 0),
            'finalizados' => (int)($res['finalizados'] ?? 0),
            'suspendidos' => (int)($res['suspendidos'] ?? 0)
        ];
    }
}
