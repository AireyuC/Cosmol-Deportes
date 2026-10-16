-- =====================================================================
-- Datos Semilla (Seeds): Cosmol-Deportes
-- Evento Oficial: XXXIII JORNADAS DEPORTIVAS DE ANESAPA 2026
-- Empresa Anfitriona: COSMOL R.L. (Montero - Santa Cruz)
-- Fechas: 22 al 24 de Octubre de 2026
--
-- Credenciales por defecto:
--   Administrador: usuario 'admin'   / contraseña 'admin123'
--   Usuario:       usuario 'usuario' / contraseña 'usuario123'
-- =====================================================================

USE `cosmol_deportes`;

-- Forzar codificación utf8mb4 en la sesión de importación
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Limpieza previa en orden referencial
TRUNCATE TABLE `medallero`;
TRUNCATE TABLE `sanciones`;
TRUNCATE TABLE `partido_incidencias`;
TRUNCATE TABLE `partido_sets`;
TRUNCATE TABLE `partidos`;
TRUNCATE TABLE `jugadores`;
TRUNCATE TABLE `equipos`;
TRUNCATE TABLE `torneo_grupos`;
TRUNCATE TABLE `torneos`;
TRUNCATE TABLE `canchas`;
TRUNCATE TABLE `sedes`;
TRUNCATE TABLE `reglas_puntuacion`;
TRUNCATE TABLE `disciplinas`;
TRUNCATE TABLE `delegaciones`;
TRUNCATE TABLE `olimpiadas`;
TRUNCATE TABLE `usuarios`;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- 1. Usuarios Iniciales
-- Hash bcrypt para 'admin123':  $2y$10$wT8m9MvFh6LhLdZ7L8G8m.YqV9x6o1c8v3w8qM4u1w2e3r4t5y6u7
-- Hash bcrypt para 'password':  $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- -----------------------------------------------------
INSERT INTO `usuarios` (`id`, `nombre`, `username`, `email`, `password`, `rol`, `estado`) VALUES
(1, 'Administrador General COSMOL', 'admin', 'admin@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'Activo'),
(2, 'Comité ANESAPA', 'usuario', 'anesapa@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USUARIO', 'Activo');

-- -----------------------------------------------------
-- 2. Macro-Evento Oficial: XXXIII Jornadas ANESAPA 2026
-- -----------------------------------------------------
INSERT INTO `olimpiadas` (`id`, `nombre`, `anio`, `lema`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 'XXXIII Jornadas Deportivas de ANESAPA 2026', 2026, 'Unidad, Deporte e Integración de las Empresas de Agua y Saneamiento', '2026-10-22', '2026-10-24', 'En Curso');

-- -----------------------------------------------------
-- 3. Las 9 Delegaciones Oficiales Confirmadas (EPSAs Socias)
-- Fuente Oficial: Comité Organizador COSMOL (Montero)
-- -----------------------------------------------------
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `responsable`, `telefono`) VALUES
(1, 'COSMOL R.L.',          'COSMOL',          '#0066B2', 'Johnny Gonzales', '71012345'),
(2, 'SAGUAPAC R.L.',        'SAGUAPAC',        '#00A3E0', 'Mauricio',        '72012345'),
(3, 'SEMAPA',               'SEMAPA',          '#28A745', 'Erlan Arandia',   '73012345'),
(4, 'SELA',                 'SELA',            '#FD7E14', 'Erlan Vasquez',   '74012345'),
(5, 'EMAPYC',               'EMAPYC',          '#E83E8C', 'Freddy Valdez',   '75012345'),
(6, 'EPSAS - LA PAZ',       'EPSAS',           '#6F42C1', 'Brogher Vargas',  '76012345'),
(7, 'ELAPAS',               'ELAPAS',          '#DC3545', 'Marco Navas',     '77012345'),
(8, 'SAMAPA - LA PAZ',      'SAMAPA',          '#17A2B8', 'Walter Salazar',  '78012345'),
(9, 'COSAALT R.L.',         'COSAALT',         '#20C997', 'Rodrigo Lopez',   '79012345');

-- -----------------------------------------------------
-- 4. Catálogo de Disciplinas Deportivas y Recreativas
-- -----------------------------------------------------
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`) VALUES
(1, 'Fútbol',      'Fútbol sobre césped',                                 'futbol',          'Activa'),
(2, 'Futsal',      'Fútbol de salón en coliseo',                                          'running',         'Activa'),
(3, 'Básquetbol',  'Básquetbol oficial',                                                      'basketball-ball', 'Activa'),
(4, 'Voleibol',    'Voleibol en coliseo',                                                     'volleyball-ball', 'Activa'),
(5, 'Karaoke',     'Competencia de canto individual y parejas',                                    'microphone',      'Activa'),
(6, 'Billar',      'Torneo de billar en partidas directas',                                        'circle',          'Activa'),
(7, 'Cacho',       'Juego tradicional de dados por puntaje de planilla',                             'dice',            'Activa');

-- -----------------------------------------------------
-- 5. Reglas Matemáticas de Puntuación por Disciplina
-- -----------------------------------------------------
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`) VALUES
(1, 1, 3, 1, 0, 0, 1, 0, 0), -- Fútbol:      Vic=3, Emp=1, Der=0
(2, 2, 3, 1, 0, 0, 1, 0, 0), -- Futsal:      Vic=3, Emp=1, Der=0
(3, 3, 2, 0, 1, 0, 0, 0, 0), -- Básquet:     Vic=2, Der=1 (presentación), WO=0
(4, 4, 3, 0, 0, 1, 0, 1, 3), -- Vóley:       Vic 3-0/3-1=3pts, Vic 3-2=2pts, Der 2-3=1pt
(5, 5, 3, 0, 0, 0, 0, 0, 0), -- Karaoke:     Vic=3, Calificación jurado
(6, 6, 2, 0, 1, 0, 0, 0, 0), -- Billar:      Vic=2, Der=1
(7, 7, 3, 1, 0, 0, 1, 0, 0); -- Cacho:       Vic=3, Emp=1, Der=0

-- -----------------------------------------------------
-- 6. Sedes y Canchas (Montero - Santa Cruz)
-- -----------------------------------------------------
INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `latitud`, `longitud`, `estado`) VALUES
(1, 'Complejo Deportivo Central COSMOL', 'Av. Montero esq. Circunvalación (Montero)', '39221144', -17.33940000, -63.25580000, 'Activa'),
(2, 'Coliseo Polideportivo Municipal Montero', 'Calle Sucre esq. Independencia (Montero)', '39223300', -17.34200000, -63.25400000, 'Activa');

INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`) VALUES
(1, 1, 'Cancha Principal de Césped Natural', 'Césped Natural', 'Disponible'),
(2, 1, 'Cancha Sintética de Futsal',         'Césped Sintético', 'Disponible'),
(3, 2, 'Coliseo Polideportivo Principal',    'Parquet Flotante', 'Disponible'),
(4, 2, 'Cancha Auxiliar de Voleibol',         'Cemento Pulido',   'Disponible');

-- -----------------------------------------------------
-- 7. Competencias Activas por Disciplina
-- -----------------------------------------------------
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 1, 1, 1, 'Fútbol Varones - ANESAPA 2026',  'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-24'),
(2, 1, 2, 2, 'Futsal Varones - ANESAPA 2026',  'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-24'),
(3, 1, 4, 4, 'Voleibol Mixto - ANESAPA 2026',  'Mixto',     'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-24'),
(4, 1, 3, 3, 'Básquetbol Varones - ANESAPA 2026','Masculino','Liga',          'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-24');

-- -----------------------------------------------------
-- 8. Grupos por Competencia
-- -----------------------------------------------------
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`) VALUES
-- Fútbol Varones
(1, 1, 'Grupo A', 1),
(2, 1, 'Grupo B', 2),
-- Futsal Varones
(3, 2, 'Grupo A', 1),
(4, 2, 'Grupo B', 2),
-- Voleibol Mixto
(5, 3, 'Grupo A', 1),
(6, 3, 'Grupo B', 2);

-- -----------------------------------------------------
-- 9. Equipos Representativos de las 9 Delegaciones
-- -----------------------------------------------------
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `delegado_contacto`, `telefono_contacto`) VALUES
-- Fútbol Varones: Grupo A
(1, 1, 1, 1, 'COSMOL Fútbol Club',        'COSMOL',          'Azul y Blanco',   'Johnny Gonzales', '71012345'),
(2, 2, 1, 1, 'SAGUAPAC Selección',        'SAGUAPAC',        'Celeste y Blanco','Mauricio',        '72012345'),
(3, 3, 1, 1, 'SEMAPA Representativo',     'SEMAPA',          'Verde y Blanco',  'Erlan Arandia',   '73012345'),
(4, 4, 1, 1, 'SELA Oruro',                'SELA',            'Naranja y Azul',  'Erlan Vasquez',   '74012345'),

-- Fútbol Varones: Grupo B
(5, 5, 1, 2, 'EMAPYC Yacuiba',            'EMAPYC',          'Blanco y Rojo',   'Freddy Valdez',   '75012345'),
(6, 6, 1, 2, 'EPSAS La Paz FC',           'EPSAS',           'Morado y Blanco', 'Brogher Vargas',  '76012345'),
(7, 7, 1, 2, 'ELAPAS Sucre',              'ELAPAS',          'Rojo y Blanco',   'Marco Navas',     '77012345'),
(8, 8, 1, 2, 'SAMAPA La Paz',             'SAMAPA',          'Azul Turquesa',   'Walter Salazar',  '78012345'),
(9, 9, 1, 2, 'COSAALT Tarija',            'COSAALT',         'Verde y Negro',   'Rodrigo Lopez',   '79012345'),

-- Voleibol Mixto: Grupo A
(10, 1, 3, 5, 'COSMOL Vóley Mixto',       'COSMOL Vóley',    'Azul',            'Johnny Gonzales', '71012345'),
(11, 2, 3, 5, 'SAGUAPAC Vóley Mixto',     'SAGUAPAC Vóley',  'Celeste',         'Mauricio',        '72012345'),
(12, 3, 3, 5, 'SEMAPA Vóley',             'SEMAPA Vóley',    'Verde',           'Erlan Arandia',   '73012345'),
(13, 4, 3, 5, 'SELA Vóley',               'SELA Vóley',      'Naranja',         'Erlan Vasquez',   '74012345'),

-- Voleibol Mixto: Grupo B
(14, 5, 3, 6, 'EMAPYC Vóley',             'EMAPYC Vóley',    'Rosado',          'Freddy Valdez',   '75012345'),
(15, 6, 3, 6, 'EPSAS Vóley Mixto',        'EPSAS Vóley',     'Morado',          'Brogher Vargas',  '76012345'),
(16, 7, 3, 6, 'ELAPAS Vóley',             'ELAPAS Vóley',    'Rojo',            'Marco Navas',     '77012345'),
(17, 8, 3, 6, 'SAMAPA Vóley',             'SAMAPA Vóley',    'Turquesa',        'Walter Salazar',  '78012345'),
(18, 9, 3, 6, 'COSAALT Vóley',            'COSAALT Vóley',   'Verde Oscuro',    'Rodrigo Lopez',   '79012345');

-- -----------------------------------------------------
-- 10. Nómina de Jugadores Representativos (Muestra Ficticia)
-- -----------------------------------------------------
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `estado`) VALUES
-- COSMOL Fútbol Club
(1, 1, '4321011', 'Juan Carlos',   'Vargas Pérez',    '1992-05-14', 10, 'Delantero',        'Habilitado'),
(2, 1, '4321012', 'Marcos',        'Flores Roca',     '1994-08-22', 1,  'Arquero',          'Habilitado'),
(3, 1, '4321013', 'Luis Alberto',  'Ríos Castro',     '1990-11-03', 4,  'Defensa Central',  'Suspendido'),
-- SAGUAPAC Selección
(4, 2, '5432021', 'Gustavo',       'Medina Chávez',   '1993-02-18', 9,  'Delantero',        'Habilitado'),
(5, 2, '5432022', 'Andrés',        'Gutiérrez Paz',   '1995-07-09', 5,  'Mediocampista',    'Habilitado'),
-- SEMAPA Representativo
(6, 3, '6543031', 'Mauricio',      'Aguilera Rojas',  '1996-03-30', 7,  'Extremo Izquierdo','Habilitado'),
(7, 3, '6543032', 'David',         'Mamani Soliz',    '1991-09-12', 10, 'Enganche',         'Habilitado'),
-- SELA Oruro
(8, 4, '7654041', 'Felipe',        'Salazar Pinto',   '1994-12-05', 8,  'Volante',          'Habilitado'),
(9, 4, '7654042', 'Ignacio',       'Céspedes Barba',  '1993-04-17', 2,  'Lateral Derecho',  'Habilitado'),
-- EMAPYC Yacuiba
(10, 5, '8765051', 'Carlos',       'Torrez Romero',   '1992-06-11', 11, 'Delantero',        'Habilitado'),
-- EPSAS La Paz FC
(11, 6, '9876061', 'Raúl',         'Condori Quispe',  '1990-01-20', 10, 'Capitán / Volante','Habilitado'),
-- ELAPAS Sucre
(12, 7, '1234071', 'Jorge',        'Barrancos Soliz', '1995-10-15', 9,  'Centro Delantero', 'Habilitado'),
-- SAMAPA La Paz
(13, 8, '2345081', 'Javier',       'Mendoza Peña',    '1994-03-25', 6,  'Defensa',          'Habilitado'),
-- COSAALT Tarija
(14, 9, '3456091', 'Mateo',        'Llanos Farfán',   '1993-07-19', 7,  'Extremo',          'Habilitado');

-- -----------------------------------------------------
-- 11. Partidos Iniciales y Demostración en Portal
-- -----------------------------------------------------
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
-- Partido 1 (Finalizado con marcador)
(1, 1, 1, 'Grupos', 'Jornada 1', 1, 2, 1, '2026-10-22', '09:00:00', 3, 1, 'Finalizado', 'Partido inaugural de las XXXIII Jornadas ANESAPA'),
-- Partido 2 (Finalizado con empate)
(2, 1, 1, 'Grupos', 'Jornada 1', 3, 4, 1, '2026-10-22', '10:30:00', 2, 2, 'Finalizado', 'Encuentro parejo entre SEMAPA y SELA'),
-- Partido 3 (Programado para hoy)
(3, 1, 2, 'Grupos', 'Jornada 1', 6, 7, 1, CURDATE(), '15:30:00', NULL, NULL, 'Programado', 'Clásico interdepartamental EPSAS vs ELAPAS'),
-- Partido 4 (Programado para hoy)
(4, 1, 2, 'Grupos', 'Jornada 1', 8, 9, 1, CURDATE(), '17:00:00', NULL, NULL, 'Programado', 'SAMAPA vs COSAALT');

-- Incidencias del partido inaugural (COSMOL 3 - 1 SAGUAPAC)
INSERT INTO `partido_incidencias` (`partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`) VALUES
(1, 1, 1, 'GOL', 14, 'Gol de apertura de COSMOL'),
(1, 1, 1, 'GOL', 38, 'Remate potente al ángulo'),
(1, 2, 4, 'GOL', 52, 'Descuento para SAGUAPAC de cabeza'),
(1, 1, 2, 'GOL', 80, 'Gol de contragolpe definitivo'),
(1, 1, 3, 'ROJA', 75, 'Falta táctica de último recurso');

-- -----------------------------------------------------
-- 12. Régimen Disciplinario (Demostración de Sanción Activa y Levantada)
-- -----------------------------------------------------
INSERT INTO `sanciones` (`id`, `jugador_id`, `partido_id`, `torneo_id`, `tipo_sancion`, `partidos_suspension`, `partidos_cumplidos`, `motivo`, `estado`, `motivo_levantamiento`, `levantado_por_usuario_id`, `fecha_levantamiento`) VALUES
-- Sanción Activa (Expulsión de Luis Alberto Ríos)
(1, 3, 1, 1, 'ROJA', 1, 0, 'Expulsión con tarjeta roja por cortar jugada manifiesta de gol en el min 75', 'Activa', NULL, NULL, NULL),
-- Sanción Levantada / Indulto por apelación
(2, 1, 1, 1, 'AMARILLA', 0, 0, 'Amonestación verbal', 'Levantada', 'Error arbitral corregido: falta cometida por otro dorsal (indulto por confraternización)', 1, '2026-10-22 12:00:00');

-- -----------------------------------------------------
-- 13. Medallero Olímpico Oficial Inicial
-- -----------------------------------------------------
INSERT INTO `medallero` (`olimpiada_id`, `delegacion_id`, `torneo_id`, `tipo_medalla`, `puntos_olimpicos`) VALUES
(1, 1, 1, 'ORO',    10),
(1, 2, 1, 'PLATA',  7),
(1, 3, 1, 'BRONCE', 5);
