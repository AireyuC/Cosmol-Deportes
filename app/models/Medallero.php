<?php
/**
 * Modelo de Medallero Olímpico General
 * Gestiona la asignación, consulta y eliminación de medallas por delegación y torneo.
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Medallero extends Model {

    /**
     * Obtiene el resumen general del medallero por delegación de una olimpiada.
     * Ordenamiento olímpico oficial: ORO DESC, PLATA DESC, BRONCE DESC, PUNTOS DESC
     *
     * @param int $olimpiadaId
     * @return array
     */
    public function getResumenPorOlimpiada($olimpiadaId) {
        $sql = "SELECT d.id AS delegacion_id,
                       d.nombre AS delegacion_nombre,
                       d.alias AS delegacion_alias,
                       d.color_representativo,
                       d.logo_url,
                       COALESCE(SUM(CASE WHEN m.tipo_medalla = 'ORO'    THEN 1 ELSE 0 END), 0) AS oro,
                       COALESCE(SUM(CASE WHEN m.tipo_medalla = 'PLATA'  THEN 1 ELSE 0 END), 0) AS plata,
                       COALESCE(SUM(CASE WHEN m.tipo_medalla = 'BRONCE' THEN 1 ELSE 0 END), 0) AS bronce,
                       COUNT(m.id)                                                             AS total_medallas,
                       COALESCE(SUM(m.puntos_olimpicos), 0)                                    AS puntos_olimpicos
                FROM delegaciones d
                LEFT JOIN medallero m ON m.delegacion_id = d.id AND m.olimpiada_id = :olimpiada_id
                GROUP BY d.id, d.nombre, d.alias, d.color_representativo, d.logo_url
                ORDER BY puntos_olimpicos DESC, oro DESC, plata DESC, bronce DESC, d.nombre ASC";

        return $this->fetchAll($sql, ['olimpiada_id' => (int)$olimpiadaId]);
    }

    /**
     * Obtiene el detalle de medallas por torneo dentro de una olimpiada.
     * Muestra qué delegación obtuvo cada medalla en cada disciplina/torneo.
     *
     * @param int $olimpiadaId
     * @return array
     */
    public function getDetallePorTorneo($olimpiadaId) {
        $sql = "SELECT m.id, m.tipo_medalla, m.puntos_olimpicos,
                       m.torneo_id, m.delegacion_id, m.olimpiada_id,
                       t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                       disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono,
                       d.nombre AS delegacion_nombre, d.alias AS delegacion_alias,
                       d.color_representativo
                FROM medallero m
                INNER JOIN torneos t      ON m.torneo_id     = t.id
                INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                INNER JOIN delegaciones d   ON m.delegacion_id = d.id
                WHERE m.olimpiada_id = :olimpiada_id
                ORDER BY disc.nombre ASC, t.rama ASC, FIELD(m.tipo_medalla, 'ORO', 'PLATA', 'BRONCE')";

        return $this->fetchAll($sql, ['olimpiada_id' => (int)$olimpiadaId]);
    }

    /**
     * Obtiene todos los registros del medallero de una olimpiada agrupados por torneo
     * para facilitar la visualización del podio (ORO / PLATA / BRONCE) por torneo.
     *
     * @param int $olimpiadaId
     * @return array Array indexado por torneo_id, luego por tipo_medalla
     */
    public function getPodiosPorOlimpiada($olimpiadaId) {
        $detalle = $this->getDetallePorTorneo($olimpiadaId);

        $podios = [];
        foreach ($detalle as $row) {
            $tid = $row['torneo_id'];
            if (!isset($podios[$tid])) {
                $podios[$tid] = [
                    'torneo_id'          => $row['torneo_id'],
                    'torneo_nombre'      => $row['torneo_nombre'],
                    'torneo_rama'        => $row['torneo_rama'],
                    'disciplina_nombre'  => $row['disciplina_nombre'],
                    'disciplina_icono'   => $row['disciplina_icono'],
                    'ORO'    => null,
                    'PLATA'  => null,
                    'BRONCE' => null
                ];
            }
            $podios[$tid][$row['tipo_medalla']] = [
                'medalla_id'        => $row['id'],
                'delegacion_id'     => $row['delegacion_id'],
                'delegacion_nombre' => $row['delegacion_nombre'],
                'delegacion_alias'  => $row['delegacion_alias'],
                'color'             => $row['color_representativo'],
                'puntos_olimpicos'  => $row['puntos_olimpicos']
            ];
        }

        return array_values($podios);
    }

    /**
     * Asigna o actualiza una medalla para un torneo.
     * Usa INSERT ... ON DUPLICATE KEY UPDATE para respetar la restricción UNIQUE(torneo_id, tipo_medalla).
     *
     * @param int $olimpiadaId
     * @param int $torneoId
     * @param int $delegacionId
     * @param string $tipoMedalla  'ORO' | 'PLATA' | 'BRONCE'
     * @param int $puntosOlimpicos
     * @return bool
     */
    public function asignarMedalla($olimpiadaId, $torneoId, $delegacionId, $tipoMedalla, $puntosOlimpicos) {
        $sql = "INSERT INTO medallero
                    (olimpiada_id, torneo_id, delegacion_id, tipo_medalla, puntos_olimpicos)
                VALUES
                    (:olimpiada_id, :torneo_id, :delegacion_id, :tipo_medalla, :puntos_olimpicos)
                ON DUPLICATE KEY UPDATE
                    delegacion_id      = VALUES(delegacion_id),
                    puntos_olimpicos   = VALUES(puntos_olimpicos)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'olimpiada_id'    => (int)$olimpiadaId,
            'torneo_id'       => (int)$torneoId,
            'delegacion_id'   => (int)$delegacionId,
            'tipo_medalla'    => $tipoMedalla,
            'puntos_olimpicos' => (int)$puntosOlimpicos
        ]);
    }

    /**
     * Elimina una medalla específica del medallero.
     *
     * @param int $medallaId
     * @return int Filas afectadas
     */
    public function eliminarMedalla($medallaId) {
        return $this->delete('medallero', 'id = :id', ['id' => (int)$medallaId]);
    }

    /**
     * Verifica si un torneo ya tiene medallas asignadas.
     *
     * @param int $torneoId
     * @return array Array con las medallas asignadas
     */
    public function getMedallasPorTorneo($torneoId) {
        $sql = "SELECT m.*, d.nombre AS delegacion_nombre, d.color_representativo
                FROM medallero m
                INNER JOIN delegaciones d ON m.delegacion_id = d.id
                WHERE m.torneo_id = :torneo_id
                ORDER BY FIELD(m.tipo_medalla, 'ORO', 'PLATA', 'BRONCE')";
        return $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);
    }

    /**
     * Retorna los torneos finalizados de una olimpiada con indicador de si ya tienen podio asignado.
     *
     * @param int $olimpiadaId
     * @return array
     */
    public function getTorneosFinalizadosPendientes($olimpiadaId) {
        $sql = "SELECT t.id, t.nombre, t.rama, t.estado,
                       d.nombre AS disciplina_nombre, d.icono AS disciplina_icono,
                       (SELECT COUNT(*) FROM medallero m WHERE m.torneo_id = t.id) AS medallas_asignadas
                FROM torneos t
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                WHERE t.olimpiada_id = :olimpiada_id
                ORDER BY d.nombre ASC, t.rama ASC";

        return $this->fetchAll($sql, ['olimpiada_id' => (int)$olimpiadaId]);
    }

    /**
     * Obtiene las delegaciones disponibles para asignar como ganadoras.
     *
     * @param int $torneoId
     * @return array
     */
    public function getDelegacionesPorTorneo($torneoId) {
        $sql = "SELECT DISTINCT d.id, d.nombre, d.alias, d.color_representativo
                FROM equipos e
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                WHERE e.torneo_id = :torneo_id
                ORDER BY d.nombre ASC";
        return $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);
    }

    /**
     * Obtiene un resumen rápido de los 3 primeros del medallero para el Dashboard.
     *
     * @param int $olimpiadaId
     * @return array Máximo 3 delegaciones
     */
    public function getTop3Dashboard($olimpiadaId) {
        $resumen = $this->getResumenPorOlimpiada($olimpiadaId);
        return array_slice($resumen, 0, 3);
    }
}
