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
-- 3. Las Delegaciones Oficiales (9 EPSAs Socias + 1 Delegación para Sorteo)
-- Fuente Oficial: Comité Organizador COSMOL (Montero)
-- -----------------------------------------------------
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `responsable`, `telefono`) VALUES
(1,  'COSMOL R.L.',                  'COSMOL',          '#0066B2', 'Johnny Gonzales',         '71012345'),
(2,  'SAGUAPAC R.L.',                'SAGUAPAC',        '#00A3E0', 'Mauricio Aguilera',       '72012345'),
(3,  'SEMAPA',                       'SEMAPA',          '#28A745', 'Erlan Arandia',           '73012345'),
(4,  'SELA',                         'SELA',            '#FD7E14', 'Erlan Vasquez',           '74012345'),
(5,  'EMAPYC',                       'EMAPYC',          '#E83E8C', 'Freddy Valdez',           '75012345'),
(6,  'EPSAS - LA PAZ',               'EPSAS',           '#6F42C1', 'Brogher Vargas',          '76012345'),
(7,  'ELAPAS',                       'ELAPAS',          '#DC3545', 'Marco Navas',             '77012345'),
(8,  'SAMAPA - LA PAZ',              'SAMAPA',          '#17A2B8', 'Walter Salazar',          '78012345'),
(9,  'COSAALT R.L.',                 'COSAALT',         '#20C997', 'Rodrigo Lopez',           '79012345'),
(10, 'Por Definir (Pendiente Sorteo)','SORTEO',         '#6C757D', 'Comité Técnico ANESAPA',  '70000000');

-- -----------------------------------------------------
-- 4. Catálogo de Disciplinas Deportivas y Recreativas
-- -----------------------------------------------------
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`) VALUES
(1, 'Fútbol',          'Fútbol sobre césped reglamentario',                'futbol',          'Activa'),
(2, 'Futsal',          'Fútbol de salón en coliseo techado',              'running',         'Activa'),
(3, 'Básquetbol',      'Básquetbol oficial',                              'basketball-ball', 'Activa'),
(4, 'Voleibol',        'Voleibol mixto en coliseo',                       'volleyball-ball', 'Activa'),
(5, 'Karaoke',         'Competencia de canto individual y parejas',       'microphone',      'Activa'),
(6, 'Billar',          'Torneo de billar en partidas directas',           'circle',          'Activa'),
(7, 'Cacho',           'Juego tradicional de dados por puntaje de planilla','dice',          'Activa'),
(8, 'Maratón ANESAPA', 'Prueba pedestre y circuito oficial de 5.0 km en Urb. Celina Muyurina', 'running', 'Activa'),
(9, 'Frontón',         'Torneo de pelota frontón / raqueta en frontón reglamentario', 'table-tennis', 'Activa');

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
(7, 7, 3, 1, 0, 0, 1, 0, 0), -- Cacho:       Vic=3, Emp=1, Der=0
(8, 8, 10, 0, 0, 0, 0, 0, 0), -- Maratón:     Vic=10 (Podio de llegada)
(9, 9, 2, 0, 1, 0, 0, 1, 2); -- Frontón:     Vic=2, Der=1 (Sets a 15 pts)

-- -----------------------------------------------------
-- 6. Sedes y Canchas Oficiales (Montero - Santa Cruz)
-- Coordenadas Oficiales GPS verificadas
-- -----------------------------------------------------
INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `latitud`, `longitud`, `estado`) VALUES
(1, 'Estadio Gilberto Parada',                     'Av. Circunvalación Este (Montero)',                      '39221144', -17.34308263, -63.26197784, 'Activa'),
(2, 'Coliseo Patujú',                             'Calle Litoral esq. Roberto Paz, Zona Central (Montero)', '39223300', -17.34033131, -63.25355583, 'Activa'),
(3, 'Coliseo "Plurinacional"',                     'Av. Betania (Montero)',                                  '39224400', -17.34959888, -63.23763889, 'Activa'),
(4, 'Bar Restaurante "La Perla"',                  'Av. Betania Nro. 218 (Montero)',                         '39225500', -17.34887214, -63.24223902, 'Activa'),
(5, 'Oficina y Salón Auditorio COSMOL',           'Calle Isaías Parada #219, Zona Central (Montero)',       '39221144', -17.33940000, -63.25580000, 'Activa'),
(6, 'Coliseo FINOR',                              'Campus Universitario FINOR, Av. Circunvalación Noroeste','39226600', -17.33120000, -63.26100000, 'Activa'),
(7, 'Coliseo Andrés Ibáñez',                      'Av. Circunvalación Noroeste (Montero)',                  '39227700', -17.34050945, -63.27135970, 'Activa'),
(8, 'Billar Springfield Suc. Montero',            'Calle Isaías Parada esq. Chuquisaca (Montero)',          '39228800', -17.33731540, -63.25739240, 'Activa'),
(9, 'Mutual de Ex Jugadores de Fútbol de Montero','Calle 16 de Julio #415 (Montero)',                       '39229900', -17.34757000, -63.25784800, 'Activa'),
(10,'Circuito Urb. Celina Muyurina',              'Urbanización Celina Muyurina (Montero)',                 '39220000', -17.35977000, -63.25867000, 'Activa'),
(11,'Frontón Municipal de Montero',               'Av. Circunvalación Noroeste (lado Coliseo Andrés Ibáñez)','39227700',-17.33999329, -63.27183894, 'Activa');

INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`) VALUES
(1,  1, 'Estadio Gilberto Parada - Campo Principal', 'Césped Natural',     'Disponible'),
(2,  1, 'Cancha Auxiliar',                           'Césped Natural',     'Disponible'),
(3,  2, 'Coliseo Patujú - Cancha Principal',         'Parquet Flotante',   'Disponible'),
(4,  3, 'Coliseo Plurinacional - Cancha Central',    'Tabloncillo / Parquet','Disponible'),
(5,  4, 'Escenario Principal La Perla',             'Salón de Eventos',   'Disponible'),
(6,  5, 'Salón Auditorio COSMOL',                   'Auditorio Climatizado','Disponible'),
(7,  6, 'Coliseo FINOR - Cancha Principal',          'Parquet',            'Disponible'),
(8,  7, 'Coliseo Andrés Ibáñez - Cancha Principal',  'Cemento Pulido',     'Disponible'),
(9,  11,'Frontón Municipal - Frontón Reglamentario', 'Frontón Oficial',    'Disponible'),
(10, 8, 'Mesas Oficiales Springfield',               'Paño Profesional',   'Disponible'),
(11, 9, 'Cancha Mutual de Ex Jugadores',            'Césped Natural',     'Disponible'),
(12, 10,'Circuito Oficial 5.0 km',                  'Asfalto Pavimentado','Disponible');

-- -----------------------------------------------------
-- 7. Competencias Activas por Disciplina
-- -----------------------------------------------------
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 1, 1, 1, 'Fútbol Varones - ANESAPA 2026',    'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-24'),
(2, 1, 2, 2, 'Futsal Varones - ANESAPA 2026',    'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-24'),
(3, 1, 4, 4, 'Voleibol Mixto - ANESAPA 2026',    'Mixto',     'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-22'),
(4, 1, 3, 3, 'Básquetbol Varones - ANESAPA 2026','Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-24'),
(5, 1, 5, 5, 'Concurso de Karaoke ANESAPA 2026', 'Mixto',     'Eliminatoria',   'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-22'),
(6, 1, 6, 6, 'Campeonato de Billar ANESAPA 2026','Masculino', 'Eliminatoria',   'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-23'),
(7, 1, 7, 7, 'Campeonato de Cacho ANESAPA 2026', 'Mixto',     'Liga',           'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-23'),
(8, 1, 8, 8, 'Maratón ANESAPA 2026',           'Mixto',     'Eliminatoria',   'Solo_Ida', 'Fase_Grupos', '2026-10-24', '2026-10-24'),
(9, 1, 9, 9, 'Campeonato de Frontón ANESAPA 2026','Masculino','Eliminatoria',   'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-23');

-- -----------------------------------------------------
-- 8. Grupos por Competencia (Series A y B)
-- -----------------------------------------------------
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`) VALUES
-- Fútbol Varones (Torneo 1)
(1, 1, 'Serie A', 1),
(2, 1, 'Serie B', 2),
-- Futsal Varones (Torneo 2)
(3, 2, 'Serie A', 1),
(4, 2, 'Serie B', 2),
-- Voleibol Mixto (Torneo 3)
(5, 3, 'Serie A', 1),
(6, 3, 'Serie B', 2),
-- Básquetbol Varones (Torneo 4)
(7, 4, 'Serie A', 1),
(8, 4, 'Serie B', 2),
-- Frontón (Torneo 9)
(9, 9, 'Serie Única', 1);

-- -----------------------------------------------------
-- 9. Representativos por Competencia y Cupos Oficiales
-- -----------------------------------------------------
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `delegado_contacto`, `telefono_contacto`) VALUES
-- Fútbol Varones: Serie A
(1, 1,  1, 1, 'COSMOL R.L.',                            'COSMOL',      '#0066B2', 'Johnny Gonzales',         '71012345'),
(2, 10, 1, 1, 'Equipo A2 (Por Definir)',                'Equipo A2',   '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),
(3, 10, 1, 1, 'Equipo A3 (Por Definir)',                'Equipo A3',   '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),

-- Fútbol Varones: Serie B
(4, 7,  1, 2, 'ELAPAS',                                 'ELAPAS',      '#DC3545', 'Marco Navas',             '77012345'),
(5, 10, 1, 2, 'Equipo B2 (Por Definir)',                'Equipo B2',   '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),
(6, 10, 1, 2, 'Equipo B3 (Por Definir)',                'Equipo B3',   '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),
(7, 10, 1, 2, 'Equipo B4 (Por Definir)',                'Equipo B4',   '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),

-- Cupos de Semifinales de Fútbol (Jueves 22)
(8,  10, 1, NULL, 'Ganador Serie B (Por Definir)',      'Ganador B',   '#28A745', 'Comité Técnico ANESAPA',  '70000000'),
(9,  10, 1, NULL, 'Segundo Serie A (Por Definir)',      'Segundo A',   '#FFC107', 'Comité Técnico ANESAPA',  '70000000'),
(10, 10, 1, NULL, 'Ganador Serie A (Por Definir)',      'Ganador A',   '#0066B2', 'Comité Técnico ANESAPA',  '70000000'),
(11, 10, 1, NULL, 'Segundo Serie B (Por Definir)',      'Segundo B',   '#FD7E14', 'Comité Técnico ANESAPA',  '70000000'),

-- Voleibol Mixto: Serie A (Coliseo Patujú)
(12, 1,  3, 5, 'COSMOL R.L.',                           'COSMOL',      '#0066B2', 'Johnny Gonzales',         '71012345'),
(13, 10, 3, 5, 'Equipo A2 Vóley (Por Definir)',         'Vóley A2',    '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),
(14, 10, 3, 5, 'Equipo A3 Vóley (Por Definir)',         'Vóley A3',    '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),

-- Voleibol Mixto: Serie B (Coliseo Plurinacional)
(15, 7,  3, 6, 'ELAPAS',                                'ELAPAS',      '#DC3545', 'Marco Navas',             '77012345'),
(16, 10, 3, 6, 'Equipo B2 Vóley (Por Definir)',         'Vóley B2',    '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),
(17, 10, 3, 6, 'Equipo B3 Vóley (Por Definir)',         'Vóley B3',    '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),
(18, 10, 3, 6, 'Equipo B4 Vóley (Por Definir)',         'Vóley B4',    '#6C757D', 'Comité Técnico ANESAPA',  '70000000'),

-- Cupos de Semifinales y Final de Vóley (Jueves 22)
(19, 10, 3, NULL, 'Ganador Serie B Vóley (Por Definir)','Ganador B',   '#28A745', 'Comité Técnico ANESAPA',  '70000000'),
(20, 10, 3, NULL, 'Segundo Serie A Vóley (Por Definir)','Segundo A',   '#FFC107', 'Comité Técnico ANESAPA',  '70000000'),
(21, 10, 3, NULL, 'Ganador Serie A Vóley (Por Definir)','Ganador A',   '#0066B2', 'Comité Técnico ANESAPA',  '70000000'),
(22, 10, 3, NULL, 'Segundo Serie B Vóley (Por Definir)','Segundo B',   '#FD7E14', 'Comité Técnico ANESAPA',  '70000000'),
(23, 10, 3, NULL, 'Ganador Semifinal 1 Vóley (Por Definir)','Ganador Semi 1','Por Definir', 'Comité Técnico ANESAPA', '70000000'),
(24, 10, 3, NULL, 'Ganador Semifinal 2 Vóley (Por Definir)','Ganador Semi 2','Por Definir', 'Comité Técnico ANESAPA', '70000000'),

-- Cupos de Tercer Puesto y Gran Final de Fútbol (Sábado 24)
(25, 10, 1, NULL, 'Perdedor Semifinal 1 Fútbol (Por Definir)', 'Perd. Semi 1',   'Por Definir',     'Comité Técnico ANESAPA', '70000000'),
(26, 10, 1, NULL, 'Perdedor Semifinal 2 Fútbol (Por Definir)', 'Perd. Semi 2',   'Por Definir',     'Comité Técnico ANESAPA', '70000000'),
(27, 10, 1, NULL, 'Ganador Semifinal 1 Fútbol (Por Definir)',  'Gan. Semi 1',    'Por Definir',     'Comité Técnico ANESAPA', '70000000'),
(28, 10, 1, NULL, 'Ganador Semifinal 2 Fútbol (Por Definir)',  'Gan. Semi 2',    'Por Definir',     'Comité Técnico ANESAPA', '70000000');

-- -----------------------------------------------------
-- 10. Nómina Inicial de Atletas Representativos
-- -----------------------------------------------------
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `estado`) VALUES
-- COSMOL Fútbol Club
(1, 1, '4321011', 'Juan Carlos',   'Vargas Pérez',    '1992-05-14', 10, 'Delantero',        'Habilitado'),
(2, 1, '4321012', 'Marcos',        'Flores Roca',     '1994-08-22', 1,  'Arquero',          'Habilitado'),
(3, 1, '4321013', 'Luis Alberto',  'Ríos Castro',     '1990-11-03', 4,  'Defensa Central',  'Habilitado'),
-- ELAPAS Fútbol
(4, 4, '1234071', 'Jorge',         'Barrancos Soliz', '1995-10-15', 9,  'Centro Delantero', 'Habilitado'),
(5, 4, '1234072', 'Carlos Alberto','Gutiérrez Paz',   '1993-07-20', 10, 'Mediocampista',    'Habilitado'),
-- COSMOL Vóley Mixto
(6, 12, '4321021', 'Johnny',       'Gonzales Barba',  '1991-03-12', 7,  'Armador',          'Habilitado'),
(7, 12, '4321022', 'Mariana',      'Suárez Roca',     '1996-09-18', 5,  'Rematadora',       'Habilitado'),
-- ELAPAS Vóley Mixto
(8, 15, '1234081', 'Marco',        'Navas Rivera',    '1992-12-04', 8,  'Capitán',          'Habilitado');

-- -----------------------------------------------------
-- 11. Fixture Oficial para el Día Jueves 22 de Octubre
-- Fuente Oficial: Docs/fixture_jueves.md
-- -----------------------------------------------------
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
-- =====================================================
-- ⚽ FÚTBOL: Estadio Gilberto Parada
-- =====================================================
-- 07:30 | Serie A: A3 vs A2 (Cancha 1: Estadio Gilberto Parada - Campo Principal)
(1,  1, 1, 'Grupos',    'Jornada 1', 3,  2,  1, '2026-10-22', '07:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie A: A3 vs A2'),
-- 07:30 | Serie B: B3 vs B4 (Cancha 2: Cancha Auxiliar)
(2,  1, 2, 'Grupos',    'Jornada 1', 6,  7,  2, '2026-10-22', '07:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: B3 vs B4'),
-- 09:00 | Serie A: COSMOL vs A2 (Cancha 1)
(3,  1, 1, 'Grupos',    'Jornada 2', 1,  2,  1, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie A: COSMOL vs A2'),
-- 09:00 | Serie B: ELAPAS vs B2 (Cancha 2)
(4,  1, 2, 'Grupos',    'Jornada 2', 4,  5,  2, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: ELAPAS vs B2'),
-- 10:30 | Serie A: COSMOL vs A3 (Cancha 1)
(5,  1, 1, 'Grupos',    'Jornada 3', 1,  3,  1, '2026-10-22', '10:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie A: COSMOL vs A3'),
-- 10:30 | Serie B: B2 vs B4 (Cancha 2)
(6,  1, 2, 'Grupos',    'Jornada 3', 5,  7,  2, '2026-10-22', '10:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: B2 vs B4'),
-- 12:00 | Serie B: ELAPAS vs B3 (Cancha 1)
(7,  1, 2, 'Grupos',    'Jornada 4', 4,  6,  1, '2026-10-22', '12:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: ELAPAS vs B3'),
-- 13:30 | Serie B: B2 vs B3 (Cancha 1)
(8,  1, 2, 'Grupos',    'Jornada 5', 5,  6,  1, '2026-10-22', '13:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: B2 vs B3'),
-- 15:00 | Serie B: ELAPAS vs B4 (Cancha 1)
(9,  1, 2, 'Grupos',    'Jornada 6', 4,  7,  1, '2026-10-22', '15:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: ELAPAS vs B4'),
-- 16:30 | Semifinal 1: Ganador Serie B vs Segundo Serie A (Estadio Gilberto Parada - Campo Principal)
(10, 1, NULL, 'Semifinal', 'Semifinal 1', 8,  9,  1, '2026-10-22', '16:30:00', NULL, NULL, 'Programado', 'Semifinal 1: Ganador Serie B vs Segundo Serie A (Estadio Gilberto Parada)'),
-- 16:30 | Semifinal 2: Ganador Serie A vs Segundo Serie B (Cancha Auxiliar)
(11, 1, NULL, 'Semifinal', 'Semifinal 2', 10, 11, 2, '2026-10-22', '16:30:00', NULL, NULL, 'Programado', 'Semifinal 2: Ganador Serie A vs Segundo Serie B (Cancha Auxiliar)'),

-- =====================================================
-- 🏐 VÓLEY: Coliseo Patujú y Coliseo Plurinacional
-- =====================================================
-- Serie A en Coliseo Patujú (Cancha 3)
(12, 3, 5, 'Grupos',    'Jornada 1', 14, 13, 3, '2026-10-22', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: A3 vs A2'),
(13, 3, 5, 'Grupos',    'Jornada 2', 12, 13, 3, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: COSMOL vs A2'),
(14, 3, 5, 'Grupos',    'Jornada 3', 12, 14, 3, '2026-10-22', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: COSMOL vs A3'),

-- Serie B en Coliseo Plurinacional (Cancha 4)
(15, 3, 6, 'Grupos',    'Jornada 1', 17, 18, 4, '2026-10-22', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: B3 vs B4'),
(16, 3, 6, 'Grupos',    'Jornada 2', 15, 16, 4, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs B2'),
(17, 3, 6, 'Grupos',    'Jornada 3', 16, 18, 4, '2026-10-22', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: B2 vs B4'),
(18, 3, 6, 'Grupos',    'Jornada 4', 15, 17, 4, '2026-10-22', '11:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs B3'),
(19, 3, 6, 'Grupos',    'Jornada 5', 16, 17, 4, '2026-10-22', '12:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: B2 vs B3'),
(20, 3, 6, 'Grupos',    'Jornada 6', 15, 18, 4, '2026-10-22', '13:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs B4'),

-- Semifinales y Gran Final de Vóley en Coliseo Patujú (Cancha 3)
(21, 3, NULL, 'Semifinal', 'Semifinal 1', 19, 20, 3, '2026-10-22', '15:00:00', NULL, NULL, 'Programado', 'Semifinal 1: Ganador Serie B vs Segundo Serie A (Coliseo Patujú)'),
(22, 3, NULL, 'Semifinal', 'Semifinal 2', 21, 22, 3, '2026-10-22', '16:00:00', NULL, NULL, 'Programado', 'Segunda Semifinal: Ganador Serie A vs Segundo Serie B (Coliseo Patujú)'),
(23, 3, NULL, 'Final',     'Gran Final',  23, 24, 3, '2026-10-22', '17:30:00', NULL, NULL, 'Programado', 'Gran Final Vóley Mixto: Ganadores de Semifinales (Coliseo Patujú)'),

-- =====================================================
-- 📅 SÁBADO 24 DE OCTUBRE: FINALES DE FÚTBOL
-- Sede: Mutual de Ex Jugadores de Fútbol de Montero, Calle 16 de Julio #415 (Cancha 11)
-- Fuente Oficial: Docs/fixture_sabado.md
-- =====================================================
-- 08:00 | Fútbol - 3er y 4to Puesto: Perdedores Semifinales del Jueves
(24, 1, NULL, 'Tercer_Puesto', '3er y 4to Puesto', 25, 26, 11, '2026-10-24', '08:00:00', NULL, NULL, 'Programado', 'Definición de 3er y 4to puesto (Medalla de Bronce). Sede: Mutual de Ex Jugadores de Fútbol de Montero, Calle 16 de Julio #415'),
-- 09:30 | Fútbol - Gran Final: Ganadores Semifinales del Jueves
(25, 1, NULL, 'Final',         'Gran Final',       27, 28, 11, '2026-10-24', '09:30:00', NULL, NULL, 'Programado', 'Gran Final de Fútbol - Disputa de Medalla de Oro y Plata. Sede: Mutual de Ex Jugadores de Fútbol de Montero, Calle 16 de Julio #415');

-- -----------------------------------------------------
-- 12. Medallero Olímpico Oficial Inicial
-- -----------------------------------------------------
INSERT INTO `medallero` (`olimpiada_id`, `delegacion_id`, `torneo_id`, `tipo_medalla`, `puntos_olimpicos`) VALUES
(1, 1, 1, 'ORO',    10),
(1, 7, 1, 'PLATA',  7),
(1, 2, 1, 'BRONCE', 5);
