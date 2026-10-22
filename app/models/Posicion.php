<?php
/**
 * Motor de Cálculo de Tablas de Posiciones por Torneo y Grupo
 * Soporta múltiples disciplinas: Fútbol/Futsal/Recreativas (marcador directo)
 * y Voleibol (sistema de sets con puntuación diferenciada).
 * Compatible con PHP 7.3
 */

require_once __DIR__ . '/Model.php';

class Posicion extends Model {

    /**
     * Calcula y retorna la tabla de posiciones completa para un torneo.
     * Si se indica $grupoId, filtra solo los partidos de ese grupo.
     *
     * @param int $torneoId
     * @param int|null $grupoId
     * @return array  Array de filas con estadísticas ordenadas
     */
    public function calcularPorTorneo($torneoId, $grupoId = null) {
        $torneoId = (int)$torneoId;

        // 1. Obtener datos del torneo y su regla de puntuación
        $sqlTorneo = "SELECT t.id, t.nombre, t.tipo_torneo,
                             r.pts_victoria, r.pts_empate, r.pts_derrota,
                             r.pts_derrota_ajustada, r.permite_empate, r.formato_sets,
                             r.sets_para_ganar
                      FROM torneos t
                      LEFT JOIN reglas_puntuacion r ON t.regla_puntuacion_id = r.id
                      WHERE t.id = :torneo_id
                      LIMIT 1";
        $torneo = $this->fetch($sqlTorneo, ['torneo_id' => $torneoId]);

        if (!$torneo) {
            return [];
        }

        // 2. Obtener todos los equipos del torneo (o del grupo)
        $equipoParams = ['torneo_id' => $torneoId];
        $grupoWhere = '';
        if ($grupoId !== null && $grupoId > 0) {
            $grupoWhere = 'AND e.grupo_id = :grupo_id';
            $equipoParams['grupo_id'] = (int)$grupoId;
        }

        $sqlEquipos = "SELECT e.id, e.nombre, e.nombre_corto, e.logo_url, e.grupo_id,
                              d.nombre AS delegacion_nombre, d.color_representativo,
                              g.nombre_grupo
                       FROM equipos e
                       INNER JOIN delegaciones d ON e.delegacion_id = d.id
                       LEFT JOIN torneo_grupos g ON e.grupo_id = g.id
                       WHERE e.torneo_id = :torneo_id {$grupoWhere}
                       ORDER BY e.nombre ASC";
        $equipos = $this->fetchAll($sqlEquipos, $equipoParams);

        if (empty($equipos)) {
            return [];
        }

        // 3. Obtener todos los partidos FINALIZADOS o con W.O. del torneo (o del grupo)
        $partidoParams = ['torneo_id' => $torneoId];
        $grupoPartidoWhere = '';
        if ($grupoId !== null && $grupoId > 0) {
            $grupoPartidoWhere = 'AND p.grupo_id = :grupo_id';
            $partidoParams['grupo_id'] = (int)$grupoId;
        }

        $sqlPartidos = "SELECT p.id, p.equipo_local_id, p.equipo_visitante_id,
                               p.marcador_local, p.marcador_visitante, p.estado,
                               p.grupo_id
                        FROM partidos p
                        WHERE p.torneo_id = :torneo_id
                          AND p.estado IN ('Finalizado', 'WO')
                          {$grupoPartidoWhere}
                        ORDER BY p.id ASC";
        $partidos = $this->fetchAll($sqlPartidos, $partidoParams);

        // 4. Construir mapa de estadísticas iniciales por equipo
        $stats = [];
        foreach ($equipos as $eq) {
            $stats[$eq['id']] = [
                'equipo_id'          => (int)$eq['id'],
                'nombre'             => $eq['nombre'],
                'nombre_corto'       => $eq['nombre_corto'],
                'logo_url'           => $eq['logo_url'],
                'grupo_id'           => $eq['grupo_id'],
                'nombre_grupo'       => $eq['nombre_grupo'],
                'delegacion_nombre'  => $eq['delegacion_nombre'],
                'color_representativo' => $eq['color_representativo'],
                'pj'  => 0, // Partidos Jugados
                'pg'  => 0, // Partidos Ganados
                'pe'  => 0, // Partidos Empatados
                'pp'  => 0, // Partidos Perdidos
                'gf'  => 0, // Goles / Puntos A Favor
                'gc'  => 0, // Goles / Puntos En Contra
                'dg'  => 0, // Diferencia de Goles / Puntos
                'sf'  => 0, // Sets a Favor (solo Voleibol)
                'sc'  => 0, // Sets en Contra (solo Voleibol)
                'pts' => 0  // Puntos en la tabla
            ];
        }

        // 5. Procesar partidos según el tipo de disciplina
        $esVoleibol = (!empty($torneo['formato_sets']) && (int)$torneo['formato_sets'] === 1);

        if ($esVoleibol) {
            $this->procesarPartidosVoleibol($partidos, $stats, $torneo);
        } else {
            $this->procesarPartidosMarcador($partidos, $stats, $torneo);
        }

        // 6. Calcular diferencia final
        foreach ($stats as $id => $s) {
            $stats[$id]['dg'] = $s['gf'] - $s['gc'];
            if ($esVoleibol) {
                $stats[$id]['dg'] = $s['sf'] - $s['sc'];
            }
        }

        // 7. Ordenar la tabla
        $tabla = array_values($stats);
        usort($tabla, function ($a, $b) {
            // 1º Puntos DESC
            if ($a['pts'] !== $b['pts']) {
                return $b['pts'] - $a['pts'];
            }
            // 2º Diferencia de goles/sets DESC
            if ($a['dg'] !== $b['dg']) {
                return $b['dg'] - $a['dg'];
            }
            // 3º Goles/Puntos a Favor DESC
            if ($a['gf'] !== $b['gf']) {
                return $b['gf'] - $a['gf'];
            }
            // 4º Goles/Puntos en Contra ASC (menor es mejor)
            return $a['gc'] - $b['gc'];
        });

        // 8. Agregar posición ordinal
        foreach ($tabla as $pos => $row) {
            $tabla[$pos]['posicion'] = $pos + 1;
        }

        return $tabla;
    }

    /**
     * Procesa partidos con marcador directo (Fútbol, Básquetbol, Recreativas, etc.)
     *
     * @param array $partidos
     * @param array $stats (por referencia)
     * @param array $torneo
     */
    private function procesarPartidosMarcador($partidos, &$stats, $torneo) {
        $ptsVic    = isset($torneo['pts_victoria'])        ? (int)$torneo['pts_victoria']        : 3;
        $ptsEmp    = isset($torneo['pts_empate'])          ? (int)$torneo['pts_empate']          : 1;
        $ptsDer    = isset($torneo['pts_derrota'])         ? (int)$torneo['pts_derrota']         : 0;
        $ptsDerAdj = isset($torneo['pts_derrota_ajustada']) ? (int)$torneo['pts_derrota_ajustada'] : $ptsDer;
        $permiteEmp = isset($torneo['permite_empate'])     ? (int)$torneo['permite_empate']      : 1;

        foreach ($partidos as $p) {
            $local    = (int)$p['equipo_local_id'];
            $visitante = (int)$p['equipo_visitante_id'];
            $ml = (int)$p['marcador_local'];
            $mv = (int)$p['marcador_visitante'];

            // Solo procesar equipos que existan en la tabla del grupo
            if (!isset($stats[$local]) || !isset($stats[$visitante])) {
                continue;
            }

            // Actualizar PJ y GF/GC para ambos
            $stats[$local]['pj']++;
            $stats[$local]['gf'] += $ml;
            $stats[$local]['gc'] += $mv;

            $stats[$visitante]['pj']++;
            $stats[$visitante]['gf'] += $mv;
            $stats[$visitante]['gc'] += $ml;

            $esWO = ($p['estado'] === 'WO');

            if ($ml > $mv) {
                // Victoria Local (el que asistió gana los puntos de victoria)
                $stats[$local]['pg']++;
                $stats[$local]['pts'] += $ptsVic;
                $stats[$visitante]['pp']++;
                // En W.O., el que no se presentó recibe 0 pts (incluso en Básquet donde una derrota normal daría 1 pt)
                $stats[$visitante]['pts'] += ($esWO ? 0 : $ptsDer);
            } elseif ($mv > $ml) {
                // Victoria Visitante (el que asistió gana los puntos de victoria)
                $stats[$visitante]['pg']++;
                $stats[$visitante]['pts'] += $ptsVic;
                $stats[$local]['pp']++;
                // En W.O., el que no se presentó recibe 0 pts
                $stats[$local]['pts'] += ($esWO ? 0 : $ptsDer);
            } else {
                // Empate (si está permitido por la regla)
                if ($permiteEmp) {
                    $stats[$local]['pe']++;
                    $stats[$local]['pts'] += $ptsEmp;
                    $stats[$visitante]['pe']++;
                    $stats[$visitante]['pts'] += $ptsEmp;
                } else {
                    // Empate no permitido (ej. Básquetbol): tratamos como derrota para ambos
                    // o según la lógica que el administrador defina en la regla
                    $stats[$local]['pe']++;
                    $stats[$visitante]['pe']++;
                }
            }
        }
    }

    /**
     * Procesa partidos de Voleibol con puntuación por sets.
     * Lee la tabla partido_sets para determinar sets ganados.
     *
     * @param array $partidos
     * @param array $stats (por referencia)
     * @param array $torneo
     */
    private function procesarPartidosVoleibol($partidos, &$stats, $torneo) {
        $setsParaGanar = isset($torneo['sets_para_ganar']) ? (int)$torneo['sets_para_ganar'] : 3;

        foreach ($partidos as $p) {
            $local     = (int)$p['equipo_local_id'];
            $visitante = (int)$p['equipo_visitante_id'];
            $partidoId = (int)$p['id'];

            if (!isset($stats[$local]) || !isset($stats[$visitante])) {
                continue;
            }

            // Obtener los sets del partido
            $sqlSets = "SELECT puntos_local, puntos_visitante
                        FROM partido_sets
                        WHERE partido_id = :partido_id
                        ORDER BY numero_set ASC";
            $sets = $this->fetchAll($sqlSets, ['partido_id' => $partidoId]);

            if (empty($sets)) {
                // Si no hay sets registrados, usar marcador global como fallback
                $ml = (int)$p['marcador_local'];
                $mv = (int)$p['marcador_visitante'];
                $setsLocal = ($ml > $mv) ? $setsParaGanar : 0;
                $setsVisit = ($mv > $ml) ? $setsParaGanar : 0;
            } else {
                $setsLocal = 0;
                $setsVisit = 0;
                $totalPuntosLocal  = 0;
                $totalPuntosVisit  = 0;

                foreach ($sets as $set) {
                    $pl = (int)$set['puntos_local'];
                    $pv = (int)$set['puntos_visitante'];
                    $totalPuntosLocal  += $pl;
                    $totalPuntosVisit  += $pv;
                    if ($pl > $pv) {
                        $setsLocal++;
                    } else {
                        $setsVisit++;
                    }
                }

                // Acumular puntos de sets a favor/en contra (para ratio de puntos)
                $stats[$local]['gf']     += $totalPuntosLocal;
                $stats[$local]['gc']     += $totalPuntosVisit;
                $stats[$visitante]['gf'] += $totalPuntosVisit;
                $stats[$visitante]['gc'] += $totalPuntosLocal;
            }

            $stats[$local]['pj']++;
            $stats[$local]['sf']     += $setsLocal;
            $stats[$local]['sc']     += $setsVisit;
            $stats[$visitante]['pj']++;
            $stats[$visitante]['sf'] += $setsVisit;
            $stats[$visitante]['sc'] += $setsLocal;

            // Asignar puntos por resultado de sets
            if ($setsLocal > $setsVisit) {
                // Victoria Local
                $stats[$local]['pg']++;
                $stats[$visitante]['pp']++;
                // 3-0 o 3-1 => 3-0 / 3-2 => 2-1
                if ($setsVisit <= ($setsParaGanar - 2)) {
                    // Victoria sin set cedido (3-0) o con 1 set cedido (3-1)
                    $stats[$local]['pts']     += 3;
                    $stats[$visitante]['pts'] += 0;
                } else {
                    // Victoria ajustada (3-2)
                    $stats[$local]['pts']     += 2;
                    $stats[$visitante]['pts'] += 1;
                }
            } else {
                // Victoria Visitante
                $stats[$visitante]['pg']++;
                $stats[$local]['pp']++;
                if ($setsLocal <= ($setsParaGanar - 2)) {
                    $stats[$visitante]['pts'] += 3;
                    $stats[$local]['pts']     += 0;
                } else {
                    $stats[$visitante]['pts'] += 2;
                    $stats[$local]['pts']     += 1;
                }
            }
        }
    }

    /**
     * Retorna los grupos disponibles de un torneo con su conteo de equipos.
     *
     * @param int $torneoId
     * @return array
     */
    public function getGruposPorTorneo($torneoId) {
        $sql = "SELECT g.id, g.nombre_grupo,
                       (SELECT COUNT(*) FROM equipos e WHERE e.grupo_id = g.id) AS total_equipos
                FROM torneo_grupos g
                WHERE g.torneo_id = :torneo_id
                ORDER BY g.orden ASC, g.nombre_grupo ASC";
        return $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);
    }

    /**
     * Retorna los torneos disponibles de una olimpiada con su disciplina.
     *
     * @param int|null $olimpiadaId
     * @return array
     */
    public function getTorneosConGrupos($olimpiadaId = null) {
        $params = [];
        $where = '';
        if ($olimpiadaId !== null) {
            $where = 'WHERE t.olimpiada_id = :olimpiada_id';
            $params['olimpiada_id'] = (int)$olimpiadaId;
        }

        $sql = "SELECT t.id, t.nombre, t.rama, t.estado, t.olimpiada_id,
                       d.nombre AS disciplina_nombre, d.icono AS disciplina_icono,
                       (SELECT COUNT(*) FROM torneo_grupos g WHERE g.torneo_id = t.id) AS total_grupos,
                       (SELECT COUNT(*) FROM equipos e WHERE e.torneo_id = t.id) AS total_equipos
                FROM torneos t
                INNER JOIN disciplinas d ON t.disciplina_id = d.id
                {$where}
                ORDER BY d.nombre ASC, t.rama ASC, t.nombre ASC";

        return $this->fetchAll($sql, $params);
    }
}
