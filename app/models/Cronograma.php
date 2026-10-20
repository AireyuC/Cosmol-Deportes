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
                'nombre'     => 'Coliseo "Plurinacional"',
                'direccion'  => 'Av. Betania',
                'actividades'=> 'Inauguración Oficial, Vóley (Grupo B) y Futsal (Grupo B)',
                'latitud'    => -17.342000,
                'longitud'   => -63.254000
            ],
            [
                'nombre'     => 'Coliseo Patuju',
                'direccion'  => 'Calle Litoral esq. Roberto Paz, Zona Central',
                'actividades'=> 'Vóley (Grupo A) y Futsal (Grupo A)',
                'latitud'    => -17.341100,
                'longitud'   => -63.256200
            ],
            [
                'nombre'     => 'Coliseo FINOR',
                'direccion'  => 'Campus Universitario, Av. Circunvalación Noroeste',
                'actividades'=> 'Básquet (Grupo A)',
                'latitud'    => -17.332500,
                'longitud'   => -63.262100
            ],
            [
                'nombre'     => 'Coliseo y Frontón "Andrés Ibáñez"',
                'direccion'  => 'Av. Circunvalación Noroeste',
                'actividades'=> 'Básquet (Grupo B) y Torneo de Frontón',
                'latitud'    => -17.334000,
                'longitud'   => -63.261000
            ],
            [
                'nombre'     => 'Cancha Azucareros',
                'direccion'  => 'Barrio La Esperanza',
                'actividades'=> 'Fútbol (Fase Clasificatoria)',
                'latitud'    => -17.348000,
                'longitud'   => -63.265000
            ],
            [
                'nombre'     => 'Mutual de Ex Jugadores de Montero',
                'direccion'  => 'Calle 16 de Julio #415',
                'actividades'=> 'Fútbol (3er / 4to Puesto y Gran Final)',
                'latitud'    => -17.345000,
                'longitud'   => -63.258000
            ],
            [
                'nombre'     => 'Urbanización Celina Muyurina',
                'direccion'  => 'Montero (Santa Cruz)',
                'actividades'=> 'Maratón (Circuito Oficial 5.0 km) y Prueba Técnica',
                'latitud'    => -17.359770,
                'longitud'   => -63.258670
            ],
            [
                'nombre'     => 'Bar Restaurante "La Perla"',
                'direccion'  => 'Av. Betania Nro. 218',
                'actividades'=> 'Concurso de Karaoke, Juegos Populares, Clausura y Premiación',
                'latitud'    => -17.343500,
                'longitud'   => -63.252500
            ],
            [
                'nombre'     => 'Billar Springfield',
                'direccion'  => 'Calle Isaías Parada esq. Chuquisaca',
                'actividades'=> 'Campeonato de Billar y Campeonato de Cacho',
                'latitud'    => -17.340000,
                'longitud'   => -63.257000
            ],
            [
                'nombre'     => 'Oficina y Auditorio COSMOL',
                'direccion'  => 'Calle Isaías Parada #219, Zona Central',
                'actividades'=> 'Capacitación Técnica y Reunión del Consejo Directivo ANESAPA',
                'latitud'    => -17.339400,
                'longitud'   => -63.255800
            ]
        ];
    }
}
