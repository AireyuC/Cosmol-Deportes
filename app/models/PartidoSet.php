<?php
/**
 * Modelo de Sets por Partido (Voleibol y disciplinas por sets)
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class PartidoSet extends Model {

    /**
     * Obtiene los sets registrados para un partido en orden cronológico
     *
     * @param int $partidoId
     * @return array
     */
    public function getByPartidoId($partidoId) {
        $sql = "SELECT * FROM partido_sets 
                WHERE partido_id = :partido_id 
                ORDER BY numero_set ASC";
        return $this->fetchAll($sql, ['partido_id' => (int)$partidoId]);
    }

    /**
     * Obtiene un set por su ID
     *
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $sql = "SELECT * FROM partido_sets WHERE id = :id LIMIT 1";
        return $this->fetch($sql, ['id' => (int)$id]);
    }

    /**
     * Guarda o actualiza un set de juego
     *
     * @param int $partidoId
     * @param int $numeroSet
     * @param int $puntosLocal
     * @param int $puntosVisitante
     * @return int
     */
    public function guardarSet($partidoId, $numeroSet, $puntosLocal, $puntosVisitante) {
        $sql = "SELECT id FROM partido_sets 
                WHERE partido_id = :partido_id AND numero_set = :numero_set 
                LIMIT 1";
        $existente = $this->fetch($sql, [
            'partido_id' => (int)$partidoId,
            'numero_set' => (int)$numeroSet
        ]);

        if ($existente) {
            $this->update('partido_sets', [
                'puntos_local'     => (int)$puntosLocal,
                'puntos_visitante' => (int)$puntosVisitante
            ], 'id = :where_id', ['where_id' => (int)$existente['id']]);

            return (int)$existente['id'];
        }

        return $this->insert('partido_sets', [
            'partido_id'       => (int)$partidoId,
            'numero_set'       => (int)$numeroSet,
            'puntos_local'     => (int)$puntosLocal,
            'puntos_visitante' => (int)$puntosVisitante
        ]);
    }

    /**
     * Elimina un set por su ID
     *
     * @param int $id
     * @return int
     */
    public function deleteSet($id) {
        return $this->delete('partido_sets', 'id = :id', ['id' => (int)$id]);
    }

    /**
     * Calcula los sets ganados por cada equipo para determinar el marcador global
     *
     * @param int $partidoId
     * @return array ['local' => int, 'visitante' => int]
     */
    public function calcularSetsGanados($partidoId) {
        $sets = $this->getByPartidoId($partidoId);
        $local = 0;
        $visitante = 0;

        foreach ($sets as $s) {
            $pLocal = (int)$s['puntos_local'];
            $pVis   = (int)$s['puntos_visitante'];

            if ($pLocal > $pVis) {
                $local++;
            } elseif ($pVis > $pLocal) {
                $visitante++;
            }
        }

        return [
            'local'     => $local,
            'visitante' => $visitante
        ];
    }
}
