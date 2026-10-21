<?php
/**
 * Modelo de Datos: Cronograma General de Actividades
 * Basado en la programación oficial de las Jornadas Deportivas ANESAPA - COSMOL
 * Compatible con PHP 7.3
 */

class Cronograma {

    /**
     * Retorna el título oficial y subtítulo del macro-evento
     *
     * @return array
     */
    public static function getInfoEvento() {
        return [
            'titulo'    => 'Cronograma General de Actividades',
            'evento'    => 'Jornadas Deportivas ANESAPA - COSMOL',
            'dias_rango'=> 'Jueves 22 · Viernes 23 · Sábado 24',
            'descripcion'=> 'Programa oficial de competencias deportivas, juegos de confraternización, capacitaciones institucionales y actos protocolares.'
        ];
    }

    /**
     * Retorna el listado completo estructurado por días
     *
     * @return array
     */
    public static function getDias() {
        return [
            [
                'dia_id'      => 'jueves-22',
                'dia_nombre'  => 'Jueves 22',
                'fecha_corta' => '22 Oct',
                'subtitulo'   => 'Apertura de Competencias, Capacitación e Inauguración',
                'total_actividades' => 6,
                'actividades' => [
                    [
                        'horario'   => '07:30 – 18:00',
                        'actividad' => 'Fútbol',
                        'detalle'   => 'Fase clasificatoria de fútbol en césped',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Cancha Azucareros, Barrio La Esperanza'
                    ],
                    [
                        'horario'   => '08:00 – 18:00',
                        'actividad' => 'Vóley – Grupo "A"',
                        'detalle'   => 'Fase de grupos disciplina voleibol',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Coliseo Patuju, Calle Litoral esquina Roberto Paz, Zona Central'
                    ],
                    [
                        'horario'   => '08:00 – 18:00',
                        'actividad' => 'Vóley – Grupo "B"',
                        'detalle'   => 'Fase de grupos disciplina voleibol',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Coliseo "Plurinacional", Av. Betania'
                    ],
                    [
                        'horario'   => '09:00 – 13:00',
                        'actividad' => 'Capacitación Técnica',
                        'detalle'   => 'Jornada formativa y de actualización técnica para delegaciones',
                        'categoria' => 'Institucional',
                        'lugar'     => 'Salón Auditorio COSMOL, Calle Isaías Parada'
                    ],
                    [
                        'horario'   => '19:00 – 21:00',
                        'actividad' => 'Inauguración Oficial',
                        'detalle'   => 'Ceremonia de apertura y desfile de las delegaciones participantes',
                        'categoria' => 'Protocolar',
                        'lugar'     => 'Coliseo "Plurinacional", Av. Betania'
                    ],
                    [
                        'horario'   => '21:00 en adelante',
                        'actividad' => 'Concurso de Karaoke',
                        'detalle'   => 'Noche de confraternización inter-cooperativa de canto y talento',
                        'categoria' => 'Recreativa',
                        'lugar'     => 'Bar Restaurante "La Perla", Av. Betania Nro. 218'
                    ]
                ]
            ],
            [
                'dia_id'      => 'viernes-23',
                'dia_nombre'  => 'Viernes 23',
                'fecha_corta' => '23 Oct',
                'subtitulo'   => 'Futsal, Básquet, Frontón, Consejo Directivo y Juegos de Salón',
                'total_actividades' => 9,
                'actividades' => [
                    [
                        'horario'   => '08:00 – 19:00',
                        'actividad' => 'Futsal – Grupo "A"',
                        'detalle'   => 'Fase de grupos de fútbol de salón',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Coliseo Patuju'
                    ],
                    [
                        'horario'   => '08:00 – 19:00',
                        'actividad' => 'Futsal – Grupo "B"',
                        'detalle'   => 'Fase de grupos de fútbol de salón',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Coliseo "Plurinacional", Av. Betania'
                    ],
                    [
                        'horario'   => '08:00 – 17:00',
                        'actividad' => 'Básquet – Grupo "A"',
                        'detalle'   => 'Partidos clasificatorios de básquetbol',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Coliseo FINOR, Campus Universitario, Av. Circunvalación Noroeste'
                    ],
                    [
                        'horario'   => '08:00 – 17:00',
                        'actividad' => 'Básquet – Grupo "B"',
                        'detalle'   => 'Partidos clasificatorios de básquetbol',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Coliseo "Andrés Ibáñez", Av. Circunvalación Noroeste'
                    ],
                    [
                        'horario'   => '08:00 – 17:00',
                        'actividad' => 'Frontón',
                        'detalle'   => 'Torneo de frontón pelota de mano / raqueta',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Frontón Andrés Ibáñez, lado Coliseo "Andrés Ibáñez", Av. Circunvalación Noroeste'
                    ],
                    [
                        'horario'   => '08:00 – 13:00',
                        'actividad' => 'Reunión Consejo Directivo ANESAPA',
                        'detalle'   => 'Sesión directiva de autoridades institucionales',
                        'categoria' => 'Institucional',
                        'lugar'     => 'Oficina de COSMOL, Calle Isaías Parada #219, Zona Central'
                    ],
                    [
                        'horario'   => '13:00 – 14:00',
                        'actividad' => 'Visita a PTAR',
                        'detalle'   => 'Recorrido técnico a la Planta de Tratamiento de Aguas Residuales',
                        'categoria' => 'Institucional',
                        'lugar'     => 'Planta de Tratamiento PTAR Montero'
                    ],
                    [
                        'horario'   => '14:00 – 16:00',
                        'actividad' => 'Almuerzo Consejo Directivo',
                        'detalle'   => 'Confraternización de directores y autoridades de ANESAPA',
                        'categoria' => 'Protocolar',
                        'lugar'     => 'Instalaciones Oficiales COSMOL'
                    ],
                    [
                        'horario'   => '19:00 en adelante',
                        'actividad' => 'Campeonato de Billar y Campeonato de Cacho',
                        'detalle'   => 'Competencias de juegos de salón por equipos e individual',
                        'categoria' => 'Recreativa',
                        'lugar'     => 'Billar Springfield, Calle Isaías Parada esq. Chuquisaca'
                    ]
                ]
            ],
            [
                'dia_id'      => 'sabado-24',
                'dia_nombre'  => 'Sábado 24',
                'fecha_corta' => '24 Oct',
                'subtitulo'   => 'Maratón, Finales de Fútbol, Prueba Técnica, Clausura y Premiación',
                'total_actividades' => 4,
                'actividades' => [
                    [
                        'horario'   => '07:30 – 09:00',
                        'actividad' => 'Maratón',
                        'detalle'   => 'Prueba pedestre circuito perimetral de 5.0 km',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Urbanización Celina Muyurina'
                    ],
                    [
                        'horario'   => '08:00 – 12:00',
                        'actividad' => 'Fútbol – Tercer y Cuarto Lugar y Finalísima',
                        'detalle'   => 'Definición de podio olímpico por medallas de Bronce, Plata y Oro',
                        'categoria' => 'Deportiva',
                        'lugar'     => 'Mutual de Ex Jugadores de Montero, Calle 16 de Julio #415'
                    ],
                    [
                        'horario'   => '09:00 – 12:00',
                        'actividad' => 'Prueba Técnica',
                        'detalle'   => 'Demostración de destrezas y pruebas de campo técnico de agua y saneamiento',
                        'categoria' => 'Institucional',
                        'lugar'     => 'Urbanización Celina Muyurina'
                    ],
                    [
                        'horario'   => '12:00 en adelante',
                        'actividad' => 'Juegos Populares, Clausura y Premiación',
                        'detalle'   => 'Entrega oficial de trofeos, medallas y proclamación de la institución campeona',
                        'categoria' => 'Protocolar',
                        'lugar'     => 'Bar Restaurante "La Perla", Av. Betania Nro. 218'
                    ]
                ]
            ]
        ];
    }

    /**
     * Retorna las sedes oficiales y recintos con su ubicación
     *
     * @return array
     */
    public static function getSedes() {
        return [
            [
                'nombre'     => 'Estadio Gilberto Parada',
                'direccion'  => 'Av. Circunvalación Este, Montero',
                'actividades'=> 'Fútbol (Fase Clasificatoria y Semifinales)',
                'latitud'    => -17.34308263,
                'longitud'   => -63.26197784
            ],
            [
                'nombre'     => 'Coliseo Patujú',
                'direccion'  => 'Calle Litoral esq. Roberto Paz, Zona Central',
                'actividades'=> 'Vóley (Grupo A), Futsal (Grupo A) y Fase Final de Vóley',
                'latitud'    => -17.34033131,
                'longitud'   => -63.25355583
            ],
            [
                'nombre'     => 'Coliseo "Plurinacional"',
                'direccion'  => 'Av. Betania, Montero',
                'actividades'=> 'Inauguración Oficial, Vóley (Grupo B) y Futsal (Grupo B)',
                'latitud'    => -17.34959888,
                'longitud'   => -63.23763889
            ],
            [
                'nombre'     => 'Coliseo Andrés Ibáñez',
                'direccion'  => 'Av. Circunvalación Noroeste, Montero',
                'actividades'=> 'Básquetbol (Grupo B y Playoffs)',
                'latitud'    => -17.34050945,
                'longitud'   => -63.27135970
            ],
            [
                'nombre'     => 'Frontón Municipal de Montero',
                'direccion'  => 'Av. Circunvalación Noroeste (al lado del Coliseo Andrés Ibáñez)',
                'actividades'=> 'Campeonato Oficial de Frontón ANESAPA 2026',
                'latitud'    => -17.33999329,
                'longitud'   => -63.27183894
            ],
            [
                'nombre'     => 'Mutual de Ex Jugadores de Fútbol de Montero',
                'direccion'  => 'Calle 16 de Julio #415, Montero',
                'actividades'=> 'Fútbol (3er / 4to Puesto y Gran Final de Fútbol)',
                'latitud'    => -17.34757000,
                'longitud'   => -63.25784800
            ],
            [
                'nombre'     => 'Bar Restaurante "La Perla"',
                'direccion'  => 'Av. Betania Nro. 218, Montero',
                'actividades'=> 'Concurso de Karaoke, Juegos Populares, Clausura y Premiación',
                'latitud'    => -17.34887214,
                'longitud'   => -63.24223902
            ],
            [
                'nombre'     => 'Billar Springfield Suc. Montero',
                'direccion'  => 'Calle Isaías Parada esq. Chuquisaca, Montero',
                'actividades'=> 'Campeonato de Billar y Campeonato de Cacho',
                'latitud'    => -17.33731540,
                'longitud'   => -63.25739240
            ],
            [
                'nombre'     => 'Urbanización Celina Muyurina',
                'direccion'  => 'Urbanización Celina Muyurina, Montero',
                'actividades'=> 'Maratón ANESAPA (Circuito Oficial 5.0 km) y Prueba Técnica',
                'latitud'    => -17.35977000,
                'longitud'   => -63.25867000
            ],
            [
                'nombre'     => 'Oficina y Salón Auditorio COSMOL',
                'direccion'  => 'Calle Isaías Parada #219, Zona Central, Montero',
                'actividades'=> 'Capacitación Técnica y Reunión del Consejo Directivo ANESAPA',
                'latitud'    => -17.33940000,
                'longitud'   => -63.25580000
            ],
            [
                'nombre'     => 'Coliseo FINOR',
                'direccion'  => 'Campus Universitario FINOR, Av. Circunvalación Noroeste',
                'actividades'=> 'Básquetbol (Grupo A)',
                'latitud'    => -17.33120000,
                'longitud'   => -63.26100000
            ]
        ];
    }
}
