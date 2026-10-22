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
     * Retorna la escala oficial de puntos olímpicos (Oro, Plata, Bronce)
     * correspondiente a una disciplina/torneo según el reglamento oficial de las Olimpiadas.
     *
     * 1. Fútbol once (11): 45 / 30 / 15
     * 2. Fútbol de salón Cinco (5): 40 / 30 / 15
     * 3. Voleibol mixto: 30 / 20 / 10
     * 4. Básquetbol mixto: 30 / 20 / 10
     * 5. Raqueta frontón: 30 / 20 / 10
     * 6. Media maratón (5 km Libre): 30 / 20 / 10
     * 7. Competencias técnicas deportivas: 20 / 15 / 10
     * 8. Cacho: 15 / 10 / 5
     * 10. Billar: 15 / 10 / 5
     * Recreativas / Juegos de salón (Karaoke, Juegos Populares): 15 / 10 / 5
     *
     * @param string $nombre Nombre de la disciplina o torneo
     * @return array Array asociativo con ['ORO' => int, 'PLATA' => int, 'BRONCE' => int]
     */
    public static function getPuntosOficialesPorDisciplina($nombre) {
        $nom = mb_strtolower(trim((string)$nombre), 'UTF-8');

        // 1. Cacho (15 / 10 / 5)
        if (strpos($nom, 'cacho') !== false) {
            return ['ORO' => 15, 'PLATA' => 10, 'BRONCE' => 5];
        }

        // 2. Billar (15 / 10 / 5)
        if (strpos($nom, 'billar') !== false) {
            return ['ORO' => 15, 'PLATA' => 10, 'BRONCE' => 5];
        }

        // 3. Fútbol de salón Cinco (5) (40 / 30 / 15)
        if (strpos($nom, 'futsal') !== false || 
            strpos($nom, 'futbol 5') !== false || 
            strpos($nom, 'fútbol 5') !== false || 
            (strpos($nom, 'futbol') !== false && (strpos($nom, 'salon') !== false || strpos($nom, 'salón') !== false)) ||
            (strpos($nom, 'fútbol') !== false && (strpos($nom, 'salon') !== false || strpos($nom, 'salón') !== false))) {
            return ['ORO' => 40, 'PLATA' => 30, 'BRONCE' => 15];
        }

        // 4. Fútbol once (11) (45 / 30 / 15)
        if (strpos($nom, 'once') !== false || 
            strpos($nom, 'futbol 11') !== false || 
            strpos($nom, 'fútbol 11') !== false || 
            strpos($nom, 'futbol') !== false || 
            strpos($nom, 'fútbol') !== false) {
            return ['ORO' => 45, 'PLATA' => 30, 'BRONCE' => 15];
        }

        // 5. Voleibol mixto (30 / 20 / 10)
        if (strpos($nom, 'volei') !== false || strpos($nom, 'voley') !== false || strpos($nom, 'vóley') !== false) {
            return ['ORO' => 30, 'PLATA' => 20, 'BRONCE' => 10];
        }

        // 6. Basquetbol mixto (30 / 20 / 10)
        if (strpos($nom, 'basquet') !== false || strpos($nom, 'básquet') !== false || strpos($nom, 'baloncesto') !== false) {
            return ['ORO' => 30, 'PLATA' => 20, 'BRONCE' => 10];
        }

        // 7. Raqueta frontón (30 / 20 / 10)
        if (strpos($nom, 'raqueta') !== false || strpos($nom, 'fronton') !== false || strpos($nom, 'frontón') !== false) {
            return ['ORO' => 30, 'PLATA' => 20, 'BRONCE' => 10];
        }

        // 8. Media maratón (5 km Libre) (30 / 20 / 10)
        if (strpos($nom, 'marat') !== false || strpos($nom, 'atletismo') !== false || strpos($nom, '5 km') !== false || strpos($nom, '5k') !== false) {
            return ['ORO' => 30, 'PLATA' => 20, 'BRONCE' => 10];
        }

        // 9. Competencias técnicas deportivas (20 / 15 / 10)
        if (strpos($nom, 'tecnica') !== false || strpos($nom, 'técnica') !== false) {
            return ['ORO' => 20, 'PLATA' => 15, 'BRONCE' => 10];
        }

        // 10. Recreativas y de salón por defecto (Karaoke, Juegos Populares) (15 / 10 / 5)
        return ['ORO' => 15, 'PLATA' => 10, 'BRONCE' => 5];
    }

    /**
     * Obtiene todos los registros del medallero de una olimpiada agrupados por torneo
     * para facilitar la visualización del podio (ORO / PLATA / BRONCE) por torneo,
     * incluyendo las disciplinas pendientes con su escala oficial de puntuación.
     *
     * @param int $olimpiadaId
     * @return array Array indexado por torneo_id, luego por tipo_medalla
     */
    public function getPodiosPorOlimpiada($olimpiadaId) {
        $sqlTorneos = "SELECT t.id AS torneo_id, t.nombre AS torneo_nombre, t.rama AS torneo_rama,
                              disc.nombre AS disciplina_nombre, disc.icono AS disciplina_icono
                       FROM torneos t
                       INNER JOIN disciplinas disc ON t.disciplina_id = disc.id
                       WHERE t.olimpiada_id = :olimpiada_id
                       ORDER BY disc.nombre ASC, t.rama ASC";
        $torneos = $this->fetchAll($sqlTorneos, ['olimpiada_id' => (int)$olimpiadaId]);

        $detalle = $this->getDetallePorTorneo($olimpiadaId);
        $medallasPorTorneo = [];
        foreach ($detalle as $row) {
            $tid = $row['torneo_id'];
            $medallasPorTorneo[$tid][$row['tipo_medalla']] = [
                'medalla_id'        => $row['id'],
                'delegacion_id'     => $row['delegacion_id'],
                'delegacion_nombre' => $row['delegacion_nombre'],
                'delegacion_alias'  => $row['delegacion_alias'],
                'color'             => $row['color_representativo'],
                'puntos_olimpicos'  => $row['puntos_olimpicos']
            ];
        }

        $podios = [];
        foreach ($torneos as $t) {
            $tid = $t['torneo_id'];
            $puntosDefecto = self::getPuntosOficialesPorDisciplina($t['disciplina_nombre'] . ' ' . $t['torneo_nombre']);
            $podios[] = [
                'torneo_id'          => $t['torneo_id'],
                'torneo_nombre'      => $t['torneo_nombre'],
                'torneo_rama'        => $t['torneo_rama'],
                'disciplina_nombre'  => $t['disciplina_nombre'],
                'disciplina_icono'   => $t['disciplina_icono'],
                'puntos_defecto'     => $puntosDefecto,
                'ORO'    => isset($medallasPorTorneo[$tid]['ORO']) ? $medallasPorTorneo[$tid]['ORO'] : null,
                'PLATA'  => isset($medallasPorTorneo[$tid]['PLATA']) ? $medallasPorTorneo[$tid]['PLATA'] : null,
                'BRONCE' => isset($medallasPorTorneo[$tid]['BRONCE']) ? $medallasPorTorneo[$tid]['BRONCE'] : null,
            ];
        }

        return $podios;
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
     * Retorna todos los torneos / disciplinas de una olimpiada con indicador de si ya tienen podio asignado.
     * Permite al administrador visualizar y editar el medallero de todas las disciplinas.
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
     * Si no hay equipos inscritos específicamente en el torneo, retorna todas las delegaciones de la olimpiada.
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
        $delegaciones = $this->fetchAll($sql, ['torneo_id' => (int)$torneoId]);

        if (empty($delegaciones)) {
            $sqlTodas = "SELECT id, nombre, alias, color_representativo FROM delegaciones ORDER BY nombre ASC";
            $delegaciones = $this->fetchAll($sqlTodas);
        }

        return $delegaciones;
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

    /**
     * Calcula y asigna automáticamente el podio oficial (ORO, PLATA, BRONCE)
     * para un torneo / disciplina en base a sus partidos finalizados y/o tabla de posiciones.
     *
     * @param int $torneoId
     * @return array Resumen con las delegaciones asignadas
     */
    public function actualizarMedalleroAutomaticoPorTorneo($torneoId) {
        $torneoId = (int)$torneoId;
        $sqlTorneo = "SELECT t.*, o.id AS olimp_id, d.nombre AS disciplina_nombre
                      FROM torneos t
                      INNER JOIN olimpiadas o ON t.olimpiada_id = o.id
                      INNER JOIN disciplinas d ON t.disciplina_id = d.id
                      WHERE t.id = :id LIMIT 1";
        $torneo = $this->fetch($sqlTorneo, ['id' => $torneoId]);
        if (!$torneo) {
            return ['success' => false, 'error' => 'Torneo no encontrado'];
        }

        $olimpiadaId = (int)$torneo['olimpiada_id'];

        // Obtener partidos del torneo
        $sqlPartidos = "SELECT p.*,
                               el.nombre AS local_nombre, el.delegacion_id AS local_delegacion_id,
                               ev.nombre AS visitante_nombre, ev.delegacion_id AS visitante_delegacion_id
                        FROM partidos p
                        INNER JOIN equipos el ON p.equipo_local_id = el.id
                        INNER JOIN equipos ev ON p.equipo_visitante_id = ev.id
                        WHERE p.torneo_id = :torneo_id
                        ORDER BY p.id ASC";
        $partidos = $this->fetchAll($sqlPartidos, ['torneo_id' => $torneoId]);

        $oroDelegacionId = null;
        $plataDelegacionId = null;
        $bronceDelegacionId = null;

        // 1. Buscar si existe partido de Gran Final y Tercer Puesto
        $partidoFinal = null;
        $partidoTercerPuesto = null;
        $partidosSemis = [];

        foreach ($partidos as $p) {
            $fase = trim($p['fase']);
            $jornada = mb_strtolower(trim($p['jornada']), 'UTF-8');
            $obs = mb_strtolower(trim(isset($p['observaciones']) ? $p['observaciones'] : ''), 'UTF-8');

            $esFinal = ($fase === 'Final') ||
                       (strpos($jornada, 'final') !== false && strpos($jornada, 'semi') === false && strpos($jornada, 'cuarto') === false && strpos($jornada, 'octavo') === false);

            $esTercerPuesto = ($fase === 'Tercer_Puesto') ||
                              strpos($jornada, 'tercer') !== false ||
                              strpos($jornada, '3er') !== false ||
                              strpos($jornada, '3°') !== false ||
                              strpos($obs, 'tercer') !== false ||
                              strpos($obs, 'bronce') !== false;

            $esSemi = ($fase === 'Semifinal') || (strpos($jornada, 'semi') !== false);

            if ($esFinal) {
                $partidoFinal = $p;
            } elseif ($esTercerPuesto) {
                $partidoTercerPuesto = $p;
            } elseif ($esSemi) {
                $partidosSemis[] = $p;
            }
        }

        // Gran Final
        if ($partidoFinal && $partidoFinal['marcador_local'] !== null && $partidoFinal['marcador_visitante'] !== null) {
            $mL = (int)$partidoFinal['marcador_local'];
            $mV = (int)$partidoFinal['marcador_visitante'];

            if ($mL > $mV) {
                $oroDelegacionId   = (int)$partidoFinal['local_delegacion_id'];
                $plataDelegacionId = (int)$partidoFinal['visitante_delegacion_id'];
            } elseif ($mV > $mL) {
                $oroDelegacionId   = (int)$partidoFinal['visitante_delegacion_id'];
                $plataDelegacionId = (int)$partidoFinal['local_delegacion_id'];
            } else {
                $oroDelegacionId   = (int)$partidoFinal['local_delegacion_id'];
                $plataDelegacionId = (int)$partidoFinal['visitante_delegacion_id'];
            }
        }

        // Tercer Puesto
        if ($partidoTercerPuesto && $partidoTercerPuesto['marcador_local'] !== null && $partidoTercerPuesto['marcador_visitante'] !== null) {
            $mL = (int)$partidoTercerPuesto['marcador_local'];
            $mV = (int)$partidoTercerPuesto['marcador_visitante'];

            if ($mL >= $mV) {
                $bronceDelegacionId = (int)$partidoTercerPuesto['local_delegacion_id'];
            } else {
                $bronceDelegacionId = (int)$partidoTercerPuesto['visitante_delegacion_id'];
            }
        }

        // Si no hay Tercer Puesto explícito pero sí semifinales:
        if (!$bronceDelegacionId && !empty($partidosSemis)) {
            $perdedoresSemis = [];
            foreach ($partidosSemis as $ps) {
                if ($ps['marcador_local'] !== null && $ps['marcador_visitante'] !== null) {
                    $smL = (int)$ps['marcador_local'];
                    $smV = (int)$ps['marcador_visitante'];
                    if ($smL > $smV) {
                        $perdedoresSemis[] = [
                            'delegacion_id' => (int)$ps['visitante_delegacion_id'],
                            'score'         => $smV,
                            'diff'          => $smV - $smL
                        ];
                    } else {
                        $perdedoresSemis[] = [
                            'delegacion_id' => (int)$ps['local_delegacion_id'],
                            'score'         => $smL,
                            'diff'          => $smL - $smV
                        ];
                    }
                }
            }
            if (!empty($perdedoresSemis)) {
                usort($perdedoresSemis, function($a, $b) {
                    if ($a['diff'] !== $b['diff']) {
                        return $b['diff'] - $a['diff'];
                    }
                    return $b['score'] - $a['score'];
                });
                $bronceDelegacionId = $perdedoresSemis[0]['delegacion_id'];
            }
        }

        // 2. Si no se resolvió por llaves eliminatorias o faltan medallas, recurrir a la Tabla de Posiciones
        if (!$oroDelegacionId || !$plataDelegacionId || !$bronceDelegacionId) {
            require_once APP_PATH . '/models/Posicion.php';
            $posicionModel = new Posicion();
            $tabla = $posicionModel->calcularPorTorneo($torneoId);

            if (!empty($tabla)) {
                if (!$oroDelegacionId && isset($tabla[0]['delegacion_id']) && (int)$tabla[0]['delegacion_id'] > 0) {
                    $oroDelegacionId = (int)$tabla[0]['delegacion_id'];
                }
                if (!$plataDelegacionId && isset($tabla[1]['delegacion_id']) && (int)$tabla[1]['delegacion_id'] > 0) {
                    $plataDelegacionId = (int)$tabla[1]['delegacion_id'];
                }
                if (!$bronceDelegacionId && isset($tabla[2]['delegacion_id']) && (int)$tabla[2]['delegacion_id'] > 0) {
                    $bronceDelegacionId = (int)$tabla[2]['delegacion_id'];
                }
            }
        }

        // 3. Fallback de contingencia con delegaciones participantes en el torneo
        if (!$oroDelegacionId || !$plataDelegacionId || !$bronceDelegacionId) {
            $delegacionesTorneo = $this->getDelegacionesPorTorneo($torneoId);
            $usadas = array_filter([$oroDelegacionId, $plataDelegacionId, $bronceDelegacionId]);
            foreach ($delegacionesTorneo as $dt) {
                $dId = (int)$dt['id'];
                if (in_array($dId, $usadas, true)) {
                    continue;
                }
                if (!$oroDelegacionId) {
                    $oroDelegacionId = $dId;
                    $usadas[] = $dId;
                } elseif (!$plataDelegacionId) {
                    $plataDelegacionId = $dId;
                    $usadas[] = $dId;
                } elseif (!$bronceDelegacionId) {
                    $bronceDelegacionId = $dId;
                    $usadas[] = $dId;
                }
            }
        }

        // 4. Asignar las medallas en la tabla medallero según la escala oficial de la disciplina
        $escalaPuntos = self::getPuntosOficialesPorDisciplina(
            (isset($torneo['disciplina_nombre']) ? $torneo['disciplina_nombre'] : '') . ' ' .
            (isset($torneo['nombre']) ? $torneo['nombre'] : '')
        );

        $asignadas = [];
        if ($oroDelegacionId && $oroDelegacionId > 0) {
            $this->asignarMedalla($olimpiadaId, $torneoId, $oroDelegacionId, 'ORO', $escalaPuntos['ORO']);
            $asignadas['ORO'] = $oroDelegacionId;
        }
        if ($plataDelegacionId && $plataDelegacionId > 0) {
            $this->asignarMedalla($olimpiadaId, $torneoId, $plataDelegacionId, 'PLATA', $escalaPuntos['PLATA']);
            $asignadas['PLATA'] = $plataDelegacionId;
        }
        if ($bronceDelegacionId && $bronceDelegacionId > 0) {
            $this->asignarMedalla($olimpiadaId, $torneoId, $bronceDelegacionId, 'BRONCE', $escalaPuntos['BRONCE']);
            $asignadas['BRONCE'] = $bronceDelegacionId;
        }

        // Cargar nombres para el reporte/alerta
        $nombres = [];
        if (!empty($asignadas)) {
            $ids = implode(',', array_map('intval', array_values($asignadas)));
            if (!empty($ids)) {
                $sqlNombres = "SELECT id, nombre, alias FROM delegaciones WHERE id IN ({$ids})";
                $delRows = $this->fetchAll($sqlNombres);
                foreach ($delRows as $dr) {
                    $nombres[(int)$dr['id']] = !empty($dr['alias']) ? $dr['alias'] : $dr['nombre'];
                }
            }
        }

        return [
            'success'   => true,
            'asignadas' => $asignadas,
            'nombres'   => $nombres,
            'oro'       => $oroDelegacionId,
            'plata'     => $plataDelegacionId,
            'bronce'    => $bronceDelegacionId
        ];
    }
}

