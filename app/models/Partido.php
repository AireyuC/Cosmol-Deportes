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
     * Obtiene los partidos programados o disputados en una fecha específica (por defecto la fecha actual de hoy)
     *
     * @param string|null $fecha Formato Y-m-d (si es null se usa la fecha actual)
     * @param int|null $olimpiadaId
     * @return array
     */
    public function getPartidosHoy($fecha = null, $olimpiadaId = null) {
        if ($fecha === null || $fecha === '') {
            $fecha = date('Y-m-d');
        }

        $params = ['fecha' => $fecha];
        $whereOlimpiada = "";

        if ($olimpiadaId !== null && (int)$olimpiadaId > 0) {
            $whereOlimpiada = "AND t.olimpiada_id = :olimpiada_id";
            $params['olimpiada_id'] = (int)$olimpiadaId;
        }

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
                WHERE p.fecha_partido = :fecha
                  {$whereOlimpiada}
                ORDER BY p.hora_partido ASC, p.id ASC";

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
     * @param string|null $fotoPlanilla
     * @return int
     */
    public function actualizarResultado($id, $marcadorLocal, $marcadorVisitante, $estado, $observaciones = null, $fotoPlanilla = null) {
        $updateData = [
            'marcador_local'     => ($marcadorLocal !== null && $marcadorLocal !== '') ? (int)$marcadorLocal : null,
            'marcador_visitante' => ($marcadorVisitante !== null && $marcadorVisitante !== '') ? (int)$marcadorVisitante : null,
            'estado'             => $estado
        ];

        if ($observaciones !== null) {
            $updateData['observaciones'] = trim($observaciones);
        }

        if ($fotoPlanilla !== null) {
            $updateData['foto_planilla'] = trim($fotoPlanilla);
        }

        return $this->update('partidos', $updateData, 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Guarda o actualiza la ruta de la fotografía de la planilla oficial
     *
     * @param int $id
     * @param string|null $rutaFoto
     * @return int
     */
    public function actualizarFotoPlanilla($id, $rutaFoto) {
        return $this->update('partidos', [
            'foto_planilla' => $rutaFoto
        ], 'id = :where_id', ['where_id' => (int)$id]);
    }

    /**
     * Obtiene la ruta de la foto de planilla actual de un partido
     *
     * @param int $id
     * @return string|null
     */
    public function getFotoPlanilla($id) {
        $row = $this->fetch("SELECT foto_planilla FROM partidos WHERE id = :id LIMIT 1", ['id' => (int)$id]);
        return $row ? $row['foto_planilla'] : null;
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
            'eq_local'     => (int)$equipoId,
            'eq_visitante' => (int)$equipoId,
            'fecha'        => $fecha,
            'hora'         => $hora
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
                WHERE (p.equipo_local_id = :eq_local OR p.equipo_visitante_id = :eq_visitante)
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
            'total'       => isset($res['total']) ? (int)$res['total'] : 0,
            'programados' => isset($res['programados']) ? (int)$res['programados'] : 0,
            'en_juego'    => isset($res['en_juego']) ? (int)$res['en_juego'] : 0,
            'finalizados' => isset($res['finalizados']) ? (int)$res['finalizados'] : 0,
            'suspendidos' => isset($res['suspendidos']) ? (int)$res['suspendidos'] : 0
        ];
    }

    /**
     * Obtiene el historial completo de partidos de todos los equipos de una delegación
     *
     * @param int $delegacionId
     * @param int|null $torneoId
     * @param string|null $estado
     * @return array
     */
    public function getHistorialPorDelegacion($delegacionId, $torneoId = null, $estado = null) {
        $delegacionId = (int)$delegacionId;
        $params = [
            'del_local'     => $delegacionId,
            'del_visitante' => $delegacionId
        ];

        $where = ["(el.delegacion_id = :del_local OR ev.delegacion_id = :del_visitante)"];

        if ($torneoId !== null && (int)$torneoId > 0) {
            $where[] = "p.torneo_id = :torneo_id";
            $params['torneo_id'] = (int)$torneoId;
        }

        if (!empty($estado)) {
            $where[] = "p.estado = :estado";
            $params['estado'] = $estado;
        }

        $whereSql = "WHERE " . implode(' AND ', $where);

        $sql = "SELECT p.*,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama, t.tipo_torneo,
                       disc.id AS disciplina_id, disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono,
                       el.nombre AS local_nombre, el.nombre_corto AS local_corto, el.logo_url AS local_logo, el.delegacion_id AS local_delegacion_id,
                       dl.nombre AS delegacion_local_nombre, dl.alias AS delegacion_local_alias, dl.color_representativo AS delegacion_local_color,
                       ev.nombre AS visitante_nombre, ev.nombre_corto AS visitante_corto, ev.logo_url AS visitante_logo, ev.delegacion_id AS visitante_delegacion_id,
                       dv.nombre AS delegacion_visitante_nombre, dv.alias AS delegacion_visitante_alias, dv.color_representativo AS delegacion_visitante_color,
                       c.nombre_cancha,
                       s.nombre AS sede_nombre, s.direccion AS sede_direccion
                FROM partidos p
                INNER JOIN torneos t ON p.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                INNER JOIN equipos el ON p.equipo_local_id = el.id
                INNER JOIN delegaciones dl ON el.delegacion_id = dl.id
                INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
                INNER JOIN delegaciones dv ON ev.delegacion_id = dv.id
                LEFT JOIN canchas c ON p.cancha_id = c.id
                LEFT JOIN sedes s ON c.sede_id = s.id
                {$whereSql}
                ORDER BY p.fecha_partido DESC, p.hora_partido DESC, p.id DESC";

        $partidos = $this->fetchAll($sql, $params);

        // Procesar resultado para cada partido en relación con la delegación consultada
        foreach ($partidos as &$p) {
            $esLocal = ((int)$p['local_delegacion_id'] === $delegacionId);
            $p['es_local'] = $esLocal;

            if ($esLocal) {
                $p['mi_equipo_nombre']    = $p['local_nombre'];
                $p['rival_equipo_nombre'] = $p['visitante_nombre'];
                $p['rival_delegacion']    = $p['delegacion_visitante_nombre'];
                $p['rival_alias']         = $p['delegacion_visitante_alias'];
                $p['rival_color']         = $p['delegacion_visitante_color'];
                $p['mis_goles']           = $p['marcador_local'];
                $p['rival_goles']         = $p['marcador_visitante'];
            } else {
                $p['mi_equipo_nombre']    = $p['visitante_nombre'];
                $p['rival_equipo_nombre'] = $p['local_nombre'];
                $p['rival_delegacion']    = $p['delegacion_local_nombre'];
                $p['rival_alias']         = $p['delegacion_local_alias'];
                $p['rival_color']         = $p['delegacion_local_color'];
                $p['mis_goles']           = $p['marcador_visitante'];
                $p['rival_goles']         = $p['marcador_local'];
            }

            if ($p['estado'] === 'Finalizado' || $p['estado'] === 'WO') {
                $mg = (int)$p['mis_goles'];
                $rg = (int)$p['rival_goles'];
                if ($mg > $rg) {
                    $p['resultado_tipo']  = 'VICTORIA';
                    $p['resultado_badge'] = 'badge-success';
                    $p['resultado_texto'] = 'Victoria';
                } elseif ($mg < $rg) {
                    $p['resultado_tipo']  = 'DERROTA';
                    $p['resultado_badge'] = 'badge-danger';
                    $p['resultado_texto'] = 'Derrota';
                } else {
                    $p['resultado_tipo']  = 'EMPATE';
                    $p['resultado_badge'] = 'badge-warning';
                    $p['resultado_texto'] = 'Empate';
                }
            } elseif ($p['estado'] === 'En_Juego') {
                $p['resultado_tipo']  = 'EN_JUEGO';
                $p['resultado_badge'] = 'badge-primary';
                $p['resultado_texto'] = 'En Juego';
            } elseif ($p['estado'] === 'Suspendido') {
                $p['resultado_tipo']  = 'SUSPENDIDO';
                $p['resultado_badge'] = 'badge-secondary';
                $p['resultado_texto'] = 'Suspendido';
            } else {
                $p['resultado_tipo']  = 'PROGRAMADO';
                $p['resultado_badge'] = 'badge-info';
                $p['resultado_texto'] = 'Programado';
            }
        }
        unset($p);

        return $partidos;
    }
}
