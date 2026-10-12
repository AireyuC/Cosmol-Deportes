<?php
/**
 * Modelo de Sanciones Disciplinarias y Régimen Sancionatorio
 * Control de amonestaciones, suspensiones por partidos y levantamiento de sanciones
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Jugador.php';

class Sancion extends Model {

    /**
     * Obtiene el listado de sanciones con información completa de jugador, equipo y torneo
     *
     * @param int|null $torneoId
     * @param string|null $estado ('Activa', 'Cumplida', 'Levantada')
     * @param string|null $tipo ('AMARILLA', 'ROJA', 'PARTIDOS_SUSPENSION', 'EXPULSION')
     * @return array
     */
    public function getAll($torneoId = null, $estado = null, $tipo = null) {
        $whereParts = [];
        $params = [];

        if ($torneoId !== null && (int)$torneoId > 0) {
            $whereParts[] = "s.torneo_id = :torneo_id";
            $params['torneo_id'] = (int)$torneoId;
        }

        if (!empty($estado)) {
            $whereParts[] = "s.estado = :estado";
            $params['estado'] = trim($estado);
        }

        if (!empty($tipo)) {
            $whereParts[] = "s.tipo_sancion = :tipo";
            $params['tipo'] = trim($tipo);
        }

        $where = !empty($whereParts) ? "WHERE " . implode(" AND ", $whereParts) : "";

        $sql = "SELECT s.*,
                       j.documento AS jugador_documento, j.nombres AS jugador_nombres,
                       j.apellidos AS jugador_apellidos, j.dorsal AS jugador_dorsal,
                       j.posicion AS jugador_posicion, j.estado AS jugador_estado,
                       eq.id AS equipo_id, eq.nombre AS equipo_nombre, eq.nombre_corto AS equipo_corto,
                       dl.id AS delegacion_id, dl.nombre AS delegacion_nombre, dl.color_representativo,
                       t.id AS torneo_id, t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                       disc.id AS disciplina_id, disc.nombre AS disciplina_nombre,
                       p.jornada AS partido_jornada, p.fecha_partido,
                       u.nombre AS levantado_por_nombre, u.username AS levantado_por_username
                FROM sanciones s
                INNER JOIN jugadores j ON s.jugador_id = j.id
                INNER JOIN equipos eq ON j.equipo_id = eq.id
                INNER JOIN delegaciones dl ON eq.delegacion_id = dl.id
                INNER JOIN torneos t ON s.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                LEFT JOIN partidos p ON s.partido_id = p.id
                LEFT JOIN usuarios u ON s.levantado_por_usuario_id = u.id
                {$where}
                ORDER BY s.id DESC";

        return $this->fetchAll($sql, $params);
    }

    /**
     * Obtiene una sanción por su ID con todo el detalle de auditoría
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT s.*,
                       j.id AS jugador_id, j.documento AS jugador_documento, j.nombres AS jugador_nombres,
                       j.apellidos AS jugador_apellidos, j.dorsal AS jugador_dorsal,
                       j.posicion AS jugador_posicion, j.foto_url AS jugador_foto, j.estado AS jugador_estado,
                       eq.id AS equipo_id, eq.nombre AS equipo_nombre, eq.nombre_corto AS equipo_corto,
                       dl.id AS delegacion_id, dl.nombre AS delegacion_nombre, dl.color_representativo,
                       t.id AS torneo_id, t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                       disc.id AS disciplina_id, disc.nombre AS disciplina_nombre,
                       p.id AS partido_id, p.jornada AS partido_jornada, p.fecha_partido, p.hora_partido,
                       u.id AS usuario_id, u.nombre AS levantado_por_nombre, u.username AS levantado_por_username
                FROM sanciones s
                INNER JOIN jugadores j ON s.jugador_id = j.id
                INNER JOIN equipos eq ON j.equipo_id = eq.id
                INNER JOIN delegaciones dl ON eq.delegacion_id = dl.id
                INNER JOIN torneos t ON s.torneo_id = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                LEFT JOIN partidos p ON s.partido_id = p.id
                LEFT JOIN usuarios u ON s.levantado_por_usuario_id = u.id
                WHERE s.id = :id
                LIMIT 1";

        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Obtiene el historial disciplinario de un jugador
     *
     * @param int $jugadorId
     * @return array
     */
    public function getByJugadorId($jugadorId) {
        $sql = "SELECT s.*,
                       t.nombre AS torneo_nombre,
                       p.jornada AS partido_jornada, p.fecha_partido,
                       u.nombre AS levantado_por_nombre
                FROM sanciones s
                INNER JOIN torneos t ON s.torneo_id = t.id
                LEFT JOIN partidos p ON s.partido_id = p.id
                LEFT JOIN usuarios u ON s.levantado_por_usuario_id = u.id
                WHERE s.jugador_id = :jugador_id
                ORDER BY s.id DESC";

        return $this->fetchAll($sql, ['jugador_id' => (int)$jugadorId]);
    }

    /**
     * Cuenta cuántas sanciones activas tiene un jugador
     *
     * @param int $jugadorId
     * @param int|null $excludeSancionId
     * @return int
     */
    public function countActivasByJugador($jugadorId, $excludeSancionId = null) {
        $excludeSql = "";
        $params = [
            'jugador_id' => (int)$jugadorId
        ];

        if ($excludeSancionId !== null && (int)$excludeSancionId > 0) {
            $excludeSql = "AND id != :exclude_id";
            $params['exclude_id'] = (int)$excludeSancionId;
        }

        $sql = "SELECT COUNT(*) AS total 
                FROM sanciones 
                WHERE jugador_id = :jugador_id 
                  AND estado = 'Activa' 
                  {$excludeSql}";

        $res = $this->fetch($sql, $params);
        return (int)($res['total'] ?? 0);
    }

    /**
     * Registra una nueva sanción disciplinaria y actualiza la inhabilitación del jugador si aplica
     *
     * @param array $data
     * @return int
     * @throws Exception
     */
    public function create($data) {
        $this->beginTransaction();

        try {
            $jugadorId = (int)$data['jugador_id'];
            $tipoSancion = trim($data['tipo_sancion']);
            $partidosSuspension = isset($data['partidos_suspension']) ? max(0, (int)$data['partidos_suspension']) : 1;

            // Si es tarjeta amarilla, no genera fechas de suspensión obligatorias por defecto
            if ($tipoSancion === 'AMARILLA' && empty($data['partidos_suspension'])) {
                $partidosSuspension = 0;
            }

            $insertData = [
                'jugador_id'               => $jugadorId,
                'partido_id'               => (!empty($data['partido_id']) && (int)$data['partido_id'] > 0) ? (int)$data['partido_id'] : null,
                'torneo_id'                => (int)$data['torneo_id'],
                'tipo_sancion'             => $tipoSancion,
                'partidos_suspension'      => $partidosSuspension,
                'partidos_cumplidos'       => 0,
                'motivo'                   => trim($data['motivo']),
                'estado'                   => !empty($data['estado']) ? $data['estado'] : 'Activa',
                'motivo_levantamiento'     => null,
                'levantado_por_usuario_id' => null,
                'fecha_levantamiento'      => null
            ];

            $sancionId = $this->insert('sanciones', $insertData);

            // REGLA DISCIPLINARIA: Si es Roja, Suspensión por Fechas o Expulsión, inhabilitar de inmediato
            if (in_array($tipoSancion, ['ROJA', 'PARTIDOS_SUSPENSION', 'EXPULSION']) && $insertData['estado'] === 'Activa') {
                $jugadorModel = new Jugador();
                $jugadorModel->cambiarEstado($jugadorId, 'Suspendido');
            }

            $this->commit();
            return $sancionId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ACCIÓN CRUCIAL: Levantar o anular una sanción disciplinaria
     * Restaura la habilitación del atleta con registro estricto de auditoría
     *
     * @param int $id
     * @param string $motivoLevantamiento
     * @param int $usuarioId
     * @return bool
     * @throws Exception
     */
    public function levantarSancion($id, $motivoLevantamiento, $usuarioId) {
        $sancion = $this->getById($id);
        if (!$sancion) {
            throw new Exception("La sanción no existe.");
        }

        $this->beginTransaction();

        try {
            $now = date('Y-m-d H:i:s');
            $updateData = [
                'estado'                   => 'Levantada',
                'motivo_levantamiento'     => trim($motivoLevantamiento),
                'levantado_por_usuario_id' => (int)$usuarioId,
                'fecha_levantamiento'      => $now
            ];

            $this->update('sanciones', $updateData, 'id = :where_id', ['where_id' => (int)$id]);

            // Revalidar si el jugador tiene otras sanciones activas pendientes
            $otrasActivas = $this->countActivasByJugador($sancion['jugador_id'], $id);
            if ($otrasActivas === 0) {
                // Si ya no le quedan sanciones activas, restaurar de inmediato a Habilitado
                $jugadorModel = new Jugador();
                $jugadorModel->cambiarEstado($sancion['jugador_id'], 'Habilitado');
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * Registra un partido cumplido de la suspensión y revalida la habilitación
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function cumplirPartido($id) {
        $sancion = $this->getById($id);
        if (!$sancion) {
            throw new Exception("Sanción no encontrada.");
        }

        $this->beginTransaction();

        try {
            $cumplidos = (int)$sancion['partidos_cumplidos'] + 1;
            $suspension = (int)$sancion['partidos_suspension'];

            $updateData = [
                'partidos_cumplidos' => $cumplidos
            ];

            if ($cumplidos >= $suspension && $sancion['tipo_sancion'] !== 'EXPULSION') {
                $updateData['estado'] = 'Cumplida';
            }

            $this->update('sanciones', $updateData, 'id = :where_id', ['where_id' => (int)$id]);

            // Si se cumplió, verificar si el jugador ya puede quedar Habilitado
            if (isset($updateData['estado']) && $updateData['estado'] === 'Cumplida') {
                $otrasActivas = $this->countActivasByJugador($sancion['jugador_id'], $id);
                if ($otrasActivas === 0) {
                    $jugadorModel = new Jugador();
                    $jugadorModel->cambiarEstado($sancion['jugador_id'], 'Habilitado');
                }
            }

            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * Elimina una sanción del registro
     *
     * @param int $id
     * @return int
     * @throws Exception
     */
    public function deleteSancion($id) {
        $sancion = $this->getById($id);
        if (!$sancion) {
            return 0;
        }

        $this->beginTransaction();

        try {
            $res = $this->delete('sanciones', 'id = :id', ['id' => (int)$id]);

            // Revalidar el estado del jugador tras eliminar la sanción
            $otrasActivas = $this->countActivasByJugador($sancion['jugador_id']);
            if ($otrasActivas === 0) {
                $jugadorModel = new Jugador();
                $jugadorModel->cambiarEstado($sancion['jugador_id'], 'Habilitado');
            }

            $this->commit();
            return $res;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * Retorna contadores generales para métricas del boletín disciplinario
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
                    SUM(CASE WHEN estado = 'Activa' THEN 1 ELSE 0 END) AS activas,
                    SUM(CASE WHEN estado = 'Levantada' THEN 1 ELSE 0 END) AS levantadas,
                    SUM(CASE WHEN estado = 'Cumplida' THEN 1 ELSE 0 END) AS cumplidas,
                    SUM(CASE WHEN tipo_sancion = 'ROJA' THEN 1 ELSE 0 END) AS rojas,
                    SUM(CASE WHEN tipo_sancion = 'AMARILLA' THEN 1 ELSE 0 END) AS amarillas,
                    SUM(CASE WHEN tipo_sancion = 'EXPULSION' THEN 1 ELSE 0 END) AS expulsiones
                FROM sanciones
                {$where}";

        $res = $this->fetch($sql, $params);

        return [
            'total'       => (int)($res['total'] ?? 0),
            'activas'     => (int)($res['activas'] ?? 0),
            'levantadas'  => (int)($res['levantadas'] ?? 0),
            'cumplidas'   => (int)($res['cumplidas'] ?? 0),
            'rojas'       => (int)($res['rojas'] ?? 0),
            'amarillas'   => (int)($res['amarillas'] ?? 0),
            'expulsiones' => (int)($res['expulsiones'] ?? 0)
        ];
    }
}
