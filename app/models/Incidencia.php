<?php
/**
 * Modelo de Incidencias de Partido (Goles, Puntos, Tarjetas y Faltas)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Incidencia extends Model {

    /**
     * Obtiene todas las incidencias registradas de un partido con datos del equipo y jugador
     *
     * @param int $partidoId
     * @return array
     */
    public function getByPartidoId($partidoId) {
        $sql = "SELECT pi.*,
                       e.nombre AS equipo_nombre, e.nombre_corto AS equipo_corto,
                       j.nombres AS jugador_nombres, j.apellidos AS jugador_apellidos,
                       j.dorsal AS jugador_dorsal, j.posicion AS jugador_posicion
                FROM partido_incidencias pi
                INNER JOIN equipos e ON pi.equipo_id = e.id
                LEFT JOIN jugadores j ON pi.jugador_id = j.id
                WHERE pi.partido_id = :partido_id
                ORDER BY pi.minuto_tiempo ASC, pi.id ASC";

        return $this->fetchAll($sql, ['partido_id' => (int)$partidoId]);
    }

    /**
     * Obtiene una incidencia por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM partido_incidencias WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Registra una nueva incidencia en el partido
     *
     * @param array $data
     * @return int
     */
    public function create($data) {
        $insertData = [
            'partido_id'    => (int)$data['partido_id'],
            'equipo_id'     => (int)$data['equipo_id'],
            'jugador_id'    => (!empty($data['jugador_id']) && (int)$data['jugador_id'] > 0) ? (int)$data['jugador_id'] : null,
            'tipo'          => $data['tipo'],
            'minuto_tiempo' => (!empty($data['minuto_tiempo']) || (isset($data['minuto_tiempo']) && $data['minuto_tiempo'] === '0')) ? (int)$data['minuto_tiempo'] : null,
            'observaciones' => !empty($data['observaciones']) ? trim($data['observaciones']) : null
        ];

        return $this->insert('partido_incidencias', $insertData);
    }

    /**
     * Elimina una incidencia
     *
     * @param int $id
     * @return int
     */
    public function deleteIncidencia($id) {
        return $this->delete('partido_incidencias', 'id = :id', ['id' => (int)$id]);
    }

    /**
     * Cuenta la cantidad de goles o puntos anotados por un equipo en un partido
     *
     * @param int $partidoId
     * @param int $equipoId
     * @param string $tipo 'GOL' o 'PUNTO'
     * @return int
     */
    public function contarAnotaciones($partidoId, $equipoId, $tipo = 'GOL') {
        $sql = "SELECT COUNT(*) AS total 
                FROM partido_incidencias 
                WHERE partido_id = :partido_id 
                  AND equipo_id = :equipo_id 
                  AND tipo = :tipo";

        $res = $this->fetch($sql, [
            'partido_id' => (int)$partidoId,
            'equipo_id'  => (int)$equipoId,
            'tipo'       => $tipo
        ]);

        return (int)($res['total'] ?? 0);
    }

    /**
     * Obtiene la tabla de goleadores de un torneo
     *
     * @param int $torneoId
     * @param int $limite
     * @return array
     */
    public function getGoleadoresPorTorneo($torneoId, $limite = 10) {
        $sql = "SELECT j.id AS jugador_id, j.nombres, j.apellidos, j.dorsal,
                       e.id AS equipo_id, e.nombre AS equipo_nombre,
                       d.nombre AS delegacion_nombre, d.color_representativo,
                       COUNT(pi.id) AS total_goles
                FROM partido_incidencias pi
                INNER JOIN partidos p ON pi.partido_id = p.id
                INNER JOIN jugadores j ON pi.jugador_id = j.id
                INNER JOIN equipos e ON pi.equipo_id = e.id
                INNER JOIN delegaciones d ON e.delegacion_id = d.id
                WHERE p.torneo_id = :torneo_id 
                  AND pi.tipo = 'GOL'
                GROUP BY j.id, e.id
                ORDER BY total_goles DESC, j.apellidos ASC
                LIMIT " . (int)$limite;

        return $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);
    }
}
