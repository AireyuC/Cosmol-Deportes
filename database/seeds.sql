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

-- Desactivar temporalmente verificación de claves foráneas para importación limpia y segura
SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Limpieza segura con DELETE (compatible con MySQL 5.7, 8.0, 8.4 y MariaDB sin fallar por TRUNCATE en FKs)
-- -----------------------------------------------------
DELETE FROM `medallero`;
DELETE FROM `sanciones`;
DELETE FROM `partido_incidencias`;
DELETE FROM `partido_sets`;
DELETE FROM `partidos`;
DELETE FROM `jugadores`;
DELETE FROM `equipos`;
DELETE FROM `torneo_grupos`;
DELETE FROM `torneos`;
DELETE FROM `canchas`;
DELETE FROM `sedes`;
DELETE FROM `reglas_puntuacion`;
DELETE FROM `disciplinas`;
DELETE FROM `delegaciones`;
DELETE FROM `olimpiadas`;
DELETE FROM `usuarios`;

-- Reinicio seguro de contadores autoincrementables
ALTER TABLE `medallero` AUTO_INCREMENT = 1;
ALTER TABLE `sanciones` AUTO_INCREMENT = 1;
ALTER TABLE `partido_incidencias` AUTO_INCREMENT = 1;
ALTER TABLE `partido_sets` AUTO_INCREMENT = 1;
ALTER TABLE `partidos` AUTO_INCREMENT = 1;
ALTER TABLE `jugadores` AUTO_INCREMENT = 1;
ALTER TABLE `equipos` AUTO_INCREMENT = 1;
ALTER TABLE `torneo_grupos` AUTO_INCREMENT = 1;
ALTER TABLE `torneos` AUTO_INCREMENT = 1;
ALTER TABLE `canchas` AUTO_INCREMENT = 1;
ALTER TABLE `sedes` AUTO_INCREMENT = 1;
ALTER TABLE `reglas_puntuacion` AUTO_INCREMENT = 1;
ALTER TABLE `disciplinas` AUTO_INCREMENT = 1;
ALTER TABLE `delegaciones` AUTO_INCREMENT = 1;
ALTER TABLE `olimpiadas` AUTO_INCREMENT = 1;
ALTER TABLE `usuarios` AUTO_INCREMENT = 1;

-- -----------------------------------------------------
-- 1. Usuarios Iniciales
-- Hash bcrypt para 'admin123':  $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi (password)
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
-- 4. Catálogo Oficial de las 11 Disciplinas
-- -----------------------------------------------------
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`) VALUES
(1, 'Fútbol',                           'Fútbol once (11) reglamentario. Mínimo 3 jugadores de 45 años o más en cancha.', 'futbol', 'Activa'),
(2, 'Futsal',                           'Fútbol de Salón cinco (5) en coliseo. Mínimo 1 jugador de 45 años o más en cancha.', 'futbol', 'Activa'),
(3, 'Básquetbol',                       'Básquetbol mixto. Mínimo 1 jugador(a) de 50 años o más y 1 dama obligatoria en cancha.', 'basketball-ball', 'Activa'),
(4, 'Voleibol',                         'Voleibol mixto 6x6. Mínimo 1 jugador(a) de 50 años o más y 2 damas obligatorias en cancha.', 'volleyball-ball', 'Activa'),
(5, 'Karaoke',                          'Noche de talentos (hasta 2 representantes). Para motivación y confraternización.', 'microphone-alt', 'Activa'),
(6, 'Billar',                           'Modalidad pareja (hasta 2 parejas). Partidas directas de confraternización para directivos.', 'dot-circle', 'Activa'),
(7, 'Cacho',                            'Modalidad pareja (dos jugadores). Juego tradicional de dados en modalidad Alalay.', 'dice', 'Activa'),
(8, 'Raqueta Frontón',                  'Modalidad pareja (dos jugadores). Torneo por sets a 15 puntos en frontón.', 'table-tennis', 'Activa'),
(9, 'Media Maratón',                    'Prueba pedestre de 5 km Libre por circuito urbano en Montero.', 'running', 'Activa'),
(10, 'Competencias Técnicas Deportivas', 'Prueba técnica operativa: conexión nueva de agua desde matriz a llave de paso (3 operarios).', 'tools', 'Activa'),
(11, 'Juegos Tradicionales',             'Juegos populares: Carrera de embolsao, cuchara con huevo y carretilla (5 pts cada juego).', 'smile', 'Activa');

-- -----------------------------------------------------
-- 5. Reglas Matemáticas de Puntuación por Disciplina
-- -----------------------------------------------------
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`) VALUES
(1, 1, 3, 1, 0, 0, 1, 0, 0), -- Fútbol:      Vic=3, Emp=1, Der=0
(2, 2, 3, 1, 0, 0, 1, 0, 0), -- Futsal:      Vic=3, Emp=1, Der=0
(3, 3, 2, 0, 1, 0, 0, 0, 0), -- Básquet:     Vic=2, Der=1 (presentación), WO=0
(4, 4, 3, 0, 0, 1, 0, 1, 2), -- Vóley:       Vic 2-0/2-1=3pts, Der 1-2=1pt
(5, 5, 3, 0, 0, 0, 0, 0, 0), -- Karaoke:     Calificación directa de jurado
(6, 6, 2, 0, 1, 0, 0, 0, 0), -- Billar:      Vic=2, Der=1
(7, 7, 2, 1, 0, 0, 1, 0, 0), -- Cacho:       Vic=2, Emp=1, Der=0
(8, 8, 2, 0, 0, 0, 0, 1, 2), -- Frontón:     Sets a 15 pts, gana el primero a 2 sets
(9, 9, 3, 0, 0, 0, 0, 0, 0), -- Maratón:     Cronometraje y puesto
(10, 10, 3, 0, 0, 0, 0, 0, 0), -- Técnica:   Tiempo y prueba hidrostática
(11, 11, 1, 0, 0, 0, 0, 0, 0); -- Tradicionales: 5 pts olímpicos por juego ganado

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
-- 7. Competencias Activas por Disciplina (Los 11 Torneos Oficiales)
-- -----------------------------------------------------
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1,  1, 1,  1,  'Fútbol once (11) - ANESAPA 2026',                  'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(2,  1, 2,  2,  'Fútbol de Salón cinco (5) - ANESAPA 2026',          'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(3,  1, 4,  4,  'Voleibol Mixto - ANESAPA 2026',                     'Mixto',     'Grupos_Playoff', 'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(4,  1, 3,  3,  'Básquetbol Mixto - ANESAPA 2026',                    'Mixto',     'Liga',          'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(5,  1, 8,  8,  'Raqueta Frontón (Parejas) - ANESAPA 2026',           'Mixto',     'Grupos_Playoff', 'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(6,  1, 9,  9,  'Media Maratón (5 km Libre) - ANESAPA 2026',          'Mixto',     'Eliminatoria',   'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(7,  1, 10, 10, 'Competencias Técnicas Deportivas - ANESAPA 2026',    'Mixto',     'Eliminatoria',   'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(8,  1, 7,  7,  'Cacho (Directivos) - ANESAPA 2026',                  'Mixto',     'Liga',          'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(9,  1, 6,  6,  'Billar (Directivos) - ANESAPA 2026',                 'Mixto',     'Liga',          'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(10, 1, 5,  5,  'Karaoke (Noche de Talentos) - ANESAPA 2026',         'Mixto',     'Eliminatoria',   'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24'),
(11, 1, 11, 11, 'Juegos Tradicionales - ANESAPA 2026',                'Mixto',     'Eliminatoria',   'Solo_Ida', 'Finalizado', '2026-10-22', '2026-10-24');

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
-- Torneo 1: Fútbol once (11)
(1, 1, 1, 1, 'COSMOL R.L. - Fútbol once (11)',        'COSMOL',          'Azul y Blanco',   'Johnny Gonzales', '71012345'),
(2, 2, 1, 1, 'SAGUAPAC R.L. - Fútbol once (11)',      'SAGUAPAC',        'Celeste y Blanco','Mauricio',        '72012345'),
(3, 3, 1, 1, 'SEMAPA - Fútbol once (11)',             'SEMAPA',          'Verde y Blanco',  'Erlan Arandia',   '73012345'),
(4, 4, 1, 1, 'SELA - Fútbol once (11)',               'SELA',            'Naranja y Azul',  'Erlan Vasquez',   '74012345'),
(5, 5, 1, 2, 'EMAPYC - Fútbol once (11)',             'EMAPYC',          'Blanco y Rojo',   'Freddy Valdez',   '75012345'),
(6, 6, 1, 2, 'EPSAS - LA PAZ - Fútbol once (11)',     'EPSAS',           'Morado y Blanco', 'Brogher Vargas',  '76012345'),
(7, 7, 1, 2, 'ELAPAS - Fútbol once (11)',             'ELAPAS',          'Rojo y Blanco',   'Marco Navas',     '77012345'),
(8, 8, 1, 2, 'SAMAPA - LA PAZ - Fútbol once (11)',    'SAMAPA',          'Azul Turquesa',   'Walter Salazar',  '78012345'),
(9, 9, 1, 2, 'COSAALT R.L. - Fútbol once (11)',       'COSAALT',         'Verde y Negro',   'Rodrigo Lopez',   '79012345'),

-- Torneo 3: Voleibol Mixto
(10, 1, 3, 5, 'COSMOL R.L. - Voleibol Mixto',         'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(11, 2, 3, 5, 'SAGUAPAC R.L. - Voleibol Mixto',       'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(12, 3, 3, 5, 'SEMAPA - Voleibol Mixto',              'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(13, 4, 3, 5, 'SELA - Voleibol Mixto',                'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(14, 5, 3, 6, 'EMAPYC - Voleibol Mixto',              'EMAPYC',          'Rosado',          'Freddy Valdez',   '75012345'),
(15, 6, 3, 6, 'EPSAS - LA PAZ - Voleibol Mixto',      'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(16, 7, 3, 6, 'ELAPAS - Voleibol Mixto',              'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(17, 8, 3, 6, 'SAMAPA - LA PAZ - Voleibol Mixto',     'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(18, 9, 3, 6, 'COSAALT R.L. - Voleibol Mixto',        'COSAALT',         'Verde Oscuro',    'Rodrigo Lopez',   '79012345'),

-- Torneo 2: Futsal Varones
(28, 1, 2, 3, 'COSMOL R.L. - Fútbol de Salón',        'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(29, 2, 2, 3, 'SAGUAPAC R.L. - Fútbol de Salón',      'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(30, 3, 2, 3, 'SEMAPA - Fútbol de Salón',             'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(31, 4, 2, 3, 'SELA - Fútbol de Salón',               'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(32, 5, 2, 4, 'EMAPYC - Fútbol de Salón',             'EMAPYC',          'Blanco y Rojo',   'Freddy Valdez',   '75012345'),
(33, 6, 2, 4, 'EPSAS - LA PAZ - Fútbol de Salón',     'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(34, 7, 2, 4, 'ELAPAS - Fútbol de Salón',             'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(35, 8, 2, 4, 'SAMAPA - LA PAZ - Fútbol de Salón',    'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(36, 9, 2, 4, 'COSAALT R.L. - Fútbol de Salón',       'COSAALT',         'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 4: Básquetbol Mixto
(46, 1, 4, NULL, 'COSMOL R.L. - Básquetbol Mixto',     'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(47, 2, 4, NULL, 'SAGUAPAC R.L. - Básquetbol Mixto',   'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(48, 3, 4, NULL, 'SEMAPA - Básquetbol Mixto',          'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(49, 4, 4, NULL, 'SELA - Básquetbol Mixto',            'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(50, 5, 4, NULL, 'EMAPYC - Básquetbol Mixto',          'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(51, 6, 4, NULL, 'EPSAS - LA PAZ - Básquetbol Mixto',  'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(52, 7, 4, NULL, 'ELAPAS - Básquetbol Mixto',          'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(53, 8, 4, NULL, 'SAMAPA - LA PAZ - Básquetbol Mixto', 'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(54, 9, 4, NULL, 'COSAALT R.L. - Básquetbol Mixto',    'COSAALT',         'Verde Oscuro',    'Rodrigo Lopez',   '79012345'),

-- Torneo 5: Raqueta Frontón (Parejas)
(55, 1, 5, NULL, 'COSMOL R.L. - Raqueta Frontón',      'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(56, 2, 5, NULL, 'SAGUAPAC R.L. - Raqueta Frontón',    'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(57, 3, 5, NULL, 'SEMAPA - Raqueta Frontón',           'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(58, 4, 5, NULL, 'SELA - Raqueta Frontón',             'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(59, 5, 5, NULL, 'EMAPYC - Raqueta Frontón',           'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(60, 6, 5, NULL, 'EPSAS - LA PAZ - Raqueta Frontón',   'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(61, 7, 5, NULL, 'ELAPAS - Raqueta Frontón',           'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(62, 8, 5, NULL, 'SAMAPA - LA PAZ - Raqueta Frontón',  'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(63, 9, 5, NULL, 'COSAALT R.L. - Raqueta Frontón',     'COSAALT',         'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 6: Media Maratón (5 km Libre)
(64, 1, 6, NULL, 'COSMOL R.L. - Media Maratón',        'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(65, 2, 6, NULL, 'SAGUAPAC R.L. - Media Maratón',      'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(66, 3, 6, NULL, 'SEMAPA - Media Maratón',             'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(67, 4, 6, NULL, 'SELA - Media Maratón',               'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(68, 5, 6, NULL, 'EMAPYC - Media Maratón',             'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(69, 6, 6, NULL, 'EPSAS - LA PAZ - Media Maratón',     'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(70, 7, 6, NULL, 'ELAPAS - Media Maratón',             'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(71, 8, 6, NULL, 'SAMAPA - LA PAZ - Media Maratón',    'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(72, 9, 6, NULL, 'COSAALT R.L. - Media Maratón',       'COSAALT',         'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 7: Competencias Técnicas Deportivas
(73, 1, 7, NULL, 'COSMOL R.L. - Competencias Técnicas', 'COSMOL',         'Azul',            'Johnny Gonzales', '71012345'),
(74, 2, 7, NULL, 'SAGUAPAC R.L. - Competencias Técnicas','SAGUAPAC',       'Celeste',         'Mauricio',        '72012345'),
(75, 3, 7, NULL, 'SEMAPA - Competencias Técnicas',      'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(76, 4, 7, NULL, 'SELA - Competencias Técnicas',        'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(77, 5, 7, NULL, 'EMAPYC - Competencias Técnicas',      'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(78, 6, 7, NULL, 'EPSAS - LA PAZ - Competencias Técnicas','EPSAS',         'Morado',          'Brogher Vargas',  '76012345'),
(79, 7, 7, NULL, 'ELAPAS - Competencias Técnicas',      'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(80, 8, 7, NULL, 'SAMAPA - LA PAZ - Competencias Técnicas','SAMAPA',       'Turquesa',        'Walter Salazar',  '78012345'),
(81, 9, 7, NULL, 'COSAALT R.L. - Competencias Técnicas', 'COSAALT',        'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 8: Cacho (Directivos)
(82, 1, 8, NULL, 'COSMOL R.L. - Cacho',                'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(83, 2, 8, NULL, 'SAGUAPAC R.L. - Cacho',              'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(84, 3, 8, NULL, 'SEMAPA - Cacho',                     'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(85, 4, 8, NULL, 'SELA - Cacho',                       'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(86, 5, 8, NULL, 'EMAPYC - Cacho',                     'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(87, 6, 8, NULL, 'EPSAS - LA PAZ - Cacho',             'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(88, 7, 8, NULL, 'ELAPAS - Cacho',                     'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(89, 8, 8, NULL, 'SAMAPA - LA PAZ - Cacho',            'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(90, 9, 8, NULL, 'COSAALT R.L. - Cacho',               'COSAALT',         'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 9: Billar (Directivos)
(91, 1, 9, NULL, 'COSMOL R.L. - Billar',               'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(92, 2, 9, NULL, 'SAGUAPAC R.L. - Billar',             'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(93, 3, 9, NULL, 'SEMAPA - Billar',                    'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(94, 4, 9, NULL, 'SELA - Billar',                      'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(95, 5, 9, NULL, 'EMAPYC - Billar',                    'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(96, 6, 9, NULL, 'EPSAS - LA PAZ - Billar',            'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(97, 7, 9, NULL, 'ELAPAS - Billar',                    'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(98, 8, 9, NULL, 'SAMAPA - LA PAZ - Billar',           'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(99, 9, 9, NULL, 'COSAALT R.L. - Billar',              'COSAALT',         'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 10: Karaoke (Noche de Talentos)
(100, 1, 10, NULL, 'COSMOL R.L. - Karaoke',            'COSMOL',          'Azul',            'Johnny Gonzales', '71012345'),
(101, 2, 10, NULL, 'SAGUAPAC R.L. - Karaoke',          'SAGUAPAC',        'Celeste',         'Mauricio',        '72012345'),
(102, 3, 10, NULL, 'SEMAPA - Karaoke',                 'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(103, 4, 10, NULL, 'SELA - Karaoke',                   'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(104, 5, 10, NULL, 'EMAPYC - Karaoke',                 'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(105, 6, 10, NULL, 'EPSAS - LA PAZ - Karaoke',         'EPSAS',           'Morado',          'Brogher Vargas',  '76012345'),
(106, 7, 10, NULL, 'ELAPAS - Karaoke',                 'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(107, 8, 10, NULL, 'SAMAPA - LA PAZ - Karaoke',        'SAMAPA',          'Turquesa',        'Walter Salazar',  '78012345'),
(108, 9, 10, NULL, 'COSAALT R.L. - Karaoke',           'COSAALT',         'Verde',           'Rodrigo Lopez',   '79012345'),

-- Torneo 11: Juegos Tradicionales
(109, 1, 11, NULL, 'COSMOL R.L. - Juegos Tradicionales', 'COSMOL',        'Azul',            'Johnny Gonzales', '71012345'),
(110, 2, 11, NULL, 'SAGUAPAC R.L. - Juegos Tradicionales','SAGUAPAC',      'Celeste',         'Mauricio',        '72012345'),
(111, 3, 11, NULL, 'SEMAPA - Juegos Tradicionales',    'SEMAPA',          'Verde',           'Erlan Arandia',   '73012345'),
(112, 4, 11, NULL, 'SELA - Juegos Tradicionales',      'SELA',            'Naranja',         'Erlan Vasquez',   '74012345'),
(113, 5, 11, NULL, 'EMAPYC - Juegos Tradicionales',    'EMAPYC',          'Blanco',          'Freddy Valdez',   '75012345'),
(114, 6, 11, NULL, 'EPSAS - LA PAZ - Juegos Tradicionales','EPSAS',       'Morado',          'Brogher Vargas',  '76012345'),
(115, 7, 11, NULL, 'ELAPAS - Juegos Tradicionales',    'ELAPAS',          'Rojo',            'Marco Navas',     '77012345'),
(116, 8, 11, NULL, 'SAMAPA - LA PAZ - Juegos Tradicionales','SAMAPA',     'Turquesa',        'Walter Salazar',  '78012345'),
(117, 9, 11, NULL, 'COSAALT R.L. - Juegos Tradicionales','COSAALT',       'Verde',           'Rodrigo Lopez',   '79012345');

-- -----------------------------------------------------
-- 10. Nómina de Jugadores Representativos
-- -----------------------------------------------------
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `estado`) VALUES
(1,  1, '4321011', 'Juan Carlos',   'Vargas Pérez',    '1980-05-14', 10, 'Delantero',        'Habilitado'),
(2,  1, '4321012', 'Marcos',        'Flores Roca',     '1979-08-22', 1,  'Arquero',          'Habilitado'),
(3,  1, '4321013', 'Luis Alberto',  'Ríos Castro',     '1981-11-03', 4,  'Defensa Central',  'Suspendido'),
(4,  2, '5432021', 'Gustavo',       'Medina Chávez',   '1978-02-18', 9,  'Delantero',        'Habilitado'),
(5,  2, '5432022', 'Andrés',        'Gutiérrez Paz',   '1985-07-09', 5,  'Mediocampista',    'Habilitado'),
(6,  3, '6543031', 'Mauricio',      'Aguilera Rojas',  '1980-03-30', 7,  'Extremo Izquierdo','Habilitado'),
(7,  3, '6543032', 'David',         'Mamani Soliz',    '1979-09-12', 10, 'Enganche',         'Habilitado'),
(8,  4, '7654041', 'Felipe',        'Salazar Pinto',   '1981-12-05', 8,  'Volante',          'Habilitado'),
(9,  4, '7654042', 'Ignacio',       'Céspedes Barba',  '1978-04-17', 2,  'Lateral Derecho',  'Habilitado'),
(10, 5, '8765051', 'Carlos',       'Torrez Romero',   '1980-06-11', 11, 'Delantero',        'Habilitado'),
(11, 6, '9876061', 'Raúl',         'Condori Quispe',  '1979-01-20', 10, 'Capitán / Volante','Habilitado'),
(12, 7, '1234071', 'Jorge',        'Barrancos Soliz', '1985-10-15', 9,  'Centro Delantero', 'Habilitado'),
(13, 8, '2345081', 'Javier',       'Mendoza Peña',    '1982-03-25', 6,  'Defensa',          'Habilitado'),
(14, 9, '3456091', 'Mateo',        'Llanos Farfán',   '1980-07-19', 7,  'Extremo',          'Habilitado');

-- -----------------------------------------------------
-- 11. Partidos Finalizados con Marcadores y Resultados Reales
-- -----------------------------------------------------

-- 11.1 Partidos de Fútbol Once (Torneo 1)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(1, 1, 1, 'Grupos', 'Jornada 1', 1, 2, 1, '2026-10-22', '09:00:00', 3, 1, 'Finalizado', 'Partido inaugural de las XXXIII Jornadas ANESAPA'),
(2, 1, 1, 'Grupos', 'Jornada 1', 3, 4, 1, '2026-10-22', '10:30:00', 2, 2, 'Finalizado', 'Encuentro parejo entre SEMAPA y SELA'),
(3, 1, 2, 'Grupos', 'Jornada 1', 6, 7, 1, '2026-10-22', '14:00:00', 5, 3, 'Finalizado', 'Clásico interdepartamental EPSAS vs ELAPAS'),
(4, 1, 2, 'Grupos', 'Jornada 1', 8, 9, 1, '2026-10-22', '15:30:00', 4, 2, 'Finalizado', 'SAMAPA vs COSAALT');

-- 11.2 Partidos de Futsal (Torneo 2)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(5,  2, 3, 'Grupos', 'Jornada 1', 28, 29, 2, '2026-10-22', '09:00:00', 4, 2, 'Finalizado', 'Partido oficial de Futsal'),
(6,  2, 3, 'Grupos', 'Jornada 1', 30, 31, 2, '2026-10-22', '10:00:00', 5, 1, 'Finalizado', 'Partido oficial de Futsal'),
(7,  2, 4, 'Grupos', 'Jornada 1', 32, 33, 2, '2026-10-22', '11:00:00', 3, 2, 'Finalizado', 'Partido oficial de Futsal'),
(8,  2, 4, 'Grupos', 'Jornada 1', 34, 35, 2, '2026-10-22', '14:00:00', 2, 2, 'Finalizado', 'Partido oficial de Futsal'),
(9,  2, 3, 'Grupos', 'Jornada 2', 28, 30, 2, '2026-10-23', '09:00:00', 3, 2, 'Finalizado', 'Partido oficial de Futsal'),
(10, 2, 3, 'Grupos', 'Jornada 2', 29, 32, 2, '2026-10-23', '10:00:00', 4, 1, 'Finalizado', 'Partido oficial de Futsal'),
(11, 2, 4, 'Grupos', 'Jornada 2', 31, 36, 2, '2026-10-23', '11:00:00', 3, 3, 'Finalizado', 'Partido oficial de Futsal'),
(12, 2, 4, 'Grupos', 'Jornada 2', 33, 35, 2, '2026-10-23', '14:00:00', 4, 2, 'Finalizado', 'Partido oficial de Futsal'),
(13, 2, 3, 'Grupos', 'Jornada 3', 28, 36, 2, '2026-10-24', '09:00:00', 6, 2, 'Finalizado', 'Partido oficial de Futsal'),
(14, 2, 3, 'Grupos', 'Jornada 3', 30, 29, 2, '2026-10-24', '10:00:00', 3, 1, 'Finalizado', 'Partido oficial de Futsal'),
(15, 2, 4, 'Grupos', 'Jornada 3', 29, 36, 2, '2026-10-24', '11:00:00', 5, 2, 'Finalizado', 'Partido oficial de Futsal'),
(16, 2, 4, 'Grupos', 'Jornada 3', 28, 34, 2, '2026-10-24', '14:00:00', 5, 1, 'Finalizado', 'Partido oficial de Futsal');

-- 11.3 Partidos de Voleibol Mixto (Torneo 3)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(17, 3, 5, 'Grupos', 'Jornada 1', 10, 11, 4, '2026-10-23', '09:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(18, 3, 5, 'Grupos', 'Jornada 1', 14, 12, 4, '2026-10-23', '10:00:00', 2, 1, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(19, 3, 6, 'Grupos', 'Jornada 1', 15, 16, 4, '2026-10-23', '11:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(20, 3, 6, 'Grupos', 'Jornada 1', 17, 18, 4, '2026-10-23', '12:00:00', 2, 1, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(21, 3, 5, 'Grupos', 'Jornada 2', 10, 14, 4, '2026-10-23', '14:00:00', 2, 1, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(22, 3, 5, 'Grupos', 'Jornada 2', 11, 13, 4, '2026-10-23', '15:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(23, 3, 6, 'Grupos', 'Jornada 2', 12, 15, 4, '2026-10-23', '16:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(24, 3, 6, 'Grupos', 'Jornada 2', 16, 18, 4, '2026-10-23', '17:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(25, 3, 5, 'Grupos', 'Jornada 3', 14, 11, 4, '2026-10-24', '09:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(26, 3, 5, 'Grupos', 'Jornada 3', 10, 15, 4, '2026-10-24', '10:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(27, 3, 6, 'Grupos', 'Jornada 3', 11, 12, 4, '2026-10-24', '11:00:00', 2, 1, 'Finalizado', 'Encuentro oficial de Voleibol Mixto'),
(28, 3, 6, 'Grupos', 'Jornada 3', 13, 17, 4, '2026-10-24', '12:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Voleibol Mixto');

-- 11.4 Partidos de Básquetbol Mixto (Torneo 4)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(29, 4, NULL, 'Grupos', 'Jornada 1', 48, 46, 3, '2026-10-22', '09:00:00', 78, 74, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(30, 4, NULL, 'Grupos', 'Jornada 1', 54, 47, 3, '2026-10-22', '10:30:00', 76, 64, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(31, 4, NULL, 'Grupos', 'Jornada 1', 49, 50, 3, '2026-10-22', '14:00:00', 62, 58, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(32, 4, NULL, 'Grupos', 'Jornada 1', 51, 52, 3, '2026-10-22', '15:30:00', 55, 51, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(33, 4, NULL, 'Grupos', 'Jornada 2', 48, 54, 3, '2026-10-23', '09:00:00', 84, 75, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(34, 4, NULL, 'Grupos', 'Jornada 2', 46, 49, 3, '2026-10-23', '10:30:00', 81, 65, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(35, 4, NULL, 'Grupos', 'Jornada 2', 47, 53, 3, '2026-10-23', '14:00:00', 72, 60, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(36, 4, NULL, 'Grupos', 'Jornada 2', 50, 51, 3, '2026-10-23', '15:30:00', 64, 59, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(37, 4, NULL, 'Grupos', 'Jornada 3', 48, 47, 3, '2026-10-23', '17:00:00', 79, 71, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(38, 4, NULL, 'Grupos', 'Jornada 3', 46, 53, 3, '2026-10-23', '18:30:00', 85, 62, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(39, 4, NULL, 'Grupos', 'Jornada 3', 54, 52, 3, '2026-10-24', '09:00:00', 73, 54, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(40, 4, NULL, 'Grupos', 'Jornada 3', 49, 51, 3, '2026-10-24', '10:30:00', 67, 63, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(41, 4, NULL, 'Grupos', 'Jornada 4', 48, 49, 3, '2026-10-24', '14:00:00', 88, 72, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(42, 4, NULL, 'Grupos', 'Jornada 4', 46, 50, 3, '2026-10-24', '15:30:00', 83, 66, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(43, 4, NULL, 'Grupos', 'Jornada 4', 54, 49, 3, '2026-10-24', '17:00:00', 77, 68, 'Finalizado', 'Partido oficial de Básquetbol Mixto'),
(44, 4, NULL, 'Grupos', 'Jornada 4', 47, 52, 3, '2026-10-24', '18:30:00', 70, 55, 'Finalizado', 'Partido oficial de Básquetbol Mixto');

-- 11.5 Partidos de Raqueta Frontón (Torneo 5)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(45, 5, NULL, 'Grupos', 'Jornada 1', 56, 55, 1, '2026-10-23', '11:00:00', 2, 1, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(46, 5, NULL, 'Grupos', 'Jornada 1', 62, 57, 1, '2026-10-23', '11:45:00', 2, 0, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(47, 5, NULL, 'Grupos', 'Jornada 1', 58, 59, 1, '2026-10-23', '12:30:00', 2, 1, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(48, 5, NULL, 'Grupos', 'Jornada 1', 60, 61, 1, '2026-10-23', '13:15:00', 2, 0, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(49, 5, NULL, 'Grupos', 'Jornada 2', 56, 62, 1, '2026-10-24', '09:00:00', 2, 0, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(50, 5, NULL, 'Grupos', 'Jornada 2', 55, 58, 1, '2026-10-24', '09:45:00', 2, 0, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(51, 5, NULL, 'Grupos', 'Jornada 2', 62, 60, 1, '2026-10-24', '10:30:00', 2, 0, 'Finalizado', 'Encuentro oficial de Raqueta Frontón'),
(52, 5, NULL, 'Grupos', 'Jornada 2', 56, 63, 1, '2026-10-24', '11:15:00', 2, 0, 'Finalizado', 'Encuentro oficial de Raqueta Frontón');

-- 11.6 Partidos de Cacho (Torneo 8) y Billar (Torneo 9)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(53, 8, NULL, 'Grupos', 'Mesa 1', 89, 82, 1, '2026-10-23', '20:00:00', 145, 130, 'Finalizado', 'Partida oficial de Cacho Alalay'),
(54, 8, NULL, 'Grupos', 'Mesa 2', 89, 83, 1, '2026-10-23', '20:30:00', 150, 125, 'Finalizado', 'Partida oficial de Cacho Alalay'),
(55, 8, NULL, 'Grupos', 'Mesa 3', 82, 84, 1, '2026-10-23', '21:00:00', 140, 110, 'Finalizado', 'Partida oficial de Cacho Alalay'),
(56, 8, NULL, 'Grupos', 'Mesa 4', 83, 85, 1, '2026-10-23', '21:30:00', 135, 120, 'Finalizado', 'Partida oficial de Cacho Alalay'),
(57, 8, NULL, 'Grupos', 'Mesa 5', 86, 87, 1, '2026-10-24', '10:00:00', 128, 115, 'Finalizado', 'Partida oficial de Cacho Alalay'),
(58, 8, NULL, 'Grupos', 'Mesa 6', 88, 90, 1, '2026-10-24', '10:30:00', 132, 126, 'Finalizado', 'Partida oficial de Cacho Alalay'),
-- Billar
(59, 9, NULL, 'Grupos', 'Mesa 1', 92, 99, 1, '2026-10-23', '21:00:00', 3, 1, 'Finalizado', 'Partida oficial de Billar'),
(60, 9, NULL, 'Grupos', 'Mesa 2', 92, 93, 1, '2026-10-23', '21:30:00', 3, 0, 'Finalizado', 'Partida oficial de Billar'),
(61, 9, NULL, 'Grupos', 'Mesa 3', 99, 91, 1, '2026-10-23', '22:00:00', 3, 2, 'Finalizado', 'Partida oficial de Billar'),
(62, 9, NULL, 'Grupos', 'Mesa 4', 93, 94, 1, '2026-10-24', '11:00:00', 3, 1, 'Finalizado', 'Partida oficial de Billar'),
(63, 9, NULL, 'Grupos', 'Mesa 5', 95, 96, 1, '2026-10-24', '11:30:00', 2, 1, 'Finalizado', 'Partida oficial de Billar'),
(64, 9, NULL, 'Grupos', 'Mesa 6', 97, 98, 1, '2026-10-24', '12:00:00', 2, 0, 'Finalizado', 'Partida oficial de Billar');

-- 11.7 Pruebas de Media Maratón (6), Técnica (7), Karaoke (10) y Tradicionales (11)
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES
(65, 6, NULL, 'Final', 'Final 5K', 67, 66, 1, '2026-10-24', '07:30:00', 1, 2, 'Finalizado', 'Prueba de Media Maratón 5K'),
(66, 6, NULL, 'Final', 'Final 5K', 64, 65, 1, '2026-10-24', '07:30:00', 3, 4, 'Finalizado', 'Prueba de Media Maratón 5K'),
(67, 7, NULL, 'Final', 'Prueba Acometida', 73, 74, 1, '2026-10-24', '15:00:00', 1, 2, 'Finalizado', 'Prueba Técnica Acometida de Agua'),
(68, 7, NULL, 'Final', 'Prueba Acometida', 76, 75, 1, '2026-10-24', '15:00:00', 3, 4, 'Finalizado', 'Prueba Técnica Acometida de Agua'),
(69, 10, NULL, 'Final', 'Gala Final', 100, 102, 1, '2026-10-23', '22:00:00', 98, 95, 'Finalizado', 'Noche de Talentos Karaoke'),
(70, 10, NULL, 'Final', 'Gala Final', 103, 101, 1, '2026-10-23', '22:00:00', 92, 88, 'Finalizado', 'Noche de Talentos Karaoke'),
(71, 11, NULL, 'Final', 'Carrera Embolsao', 109, 110, 1, '2026-10-24', '16:00:00', 5, 0, 'Finalizado', 'Carrera de Embolsao'),
(72, 11, NULL, 'Final', 'Cuchara con Huevo', 111, 112, 1, '2026-10-24', '16:30:00', 5, 0, 'Finalizado', 'Carrera Cuchara con Huevo'),
(73, 11, NULL, 'Final', 'Carrera Carretilla', 110, 113, 1, '2026-10-24', '17:00:00', 5, 0, 'Finalizado', 'Carrera de Carretilla');

-- -----------------------------------------------------
-- 12. Desglose de Sets para Voleibol y Raqueta Frontón
-- -----------------------------------------------------
INSERT INTO `partido_sets` (`partido_id`, `numero_set`, `puntos_local`, `puntos_visitante`) VALUES
-- Voleibol (Partidos 17 al 28)
(17, 1, 25, 19), (17, 2, 25, 21),
(18, 1, 25, 22), (18, 2, 20, 25), (18, 3, 15, 12),
(19, 1, 25, 18), (19, 2, 25, 17),
(20, 1, 25, 23), (20, 2, 19, 25), (20, 3, 15, 11),
(21, 1, 25, 20), (21, 2, 21, 25), (21, 3, 15, 13),
(22, 1, 25, 18), (22, 2, 25, 16),
(23, 1, 25, 21), (23, 2, 25, 19),
(24, 1, 25, 20), (24, 2, 25, 22),
(25, 1, 25, 23), (25, 2, 25, 21),
(26, 1, 25, 15), (26, 2, 25, 17),
(27, 1, 25, 20), (27, 2, 23, 25), (27, 3, 15, 10),
(28, 1, 25, 22), (28, 2, 25, 18),
-- Raqueta Frontón (Partidos 45 al 52)
(45, 1, 15, 11), (45, 2, 12, 15), (45, 3, 15, 10),
(46, 1, 15, 9),  (46, 2, 15, 8),
(47, 1, 15, 12), (47, 2, 13, 15), (47, 3, 15, 11),
(48, 1, 15, 7),  (48, 2, 15, 10),
(49, 1, 15, 13), (49, 2, 15, 12),
(50, 1, 15, 7),  (50, 2, 15, 9),
(51, 1, 15, 10), (51, 2, 15, 8),
(52, 1, 15, 6),  (52, 2, 15, 8);

-- -----------------------------------------------------
-- 13. Incidencias del Partido Inaugural de Fútbol
-- -----------------------------------------------------
INSERT INTO `partido_incidencias` (`partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`) VALUES
(1, 1, 1, 'GOL', 14, 'Gol de apertura de COSMOL'),
(1, 1, 1, 'GOL', 38, 'Remate potente al ángulo'),
(1, 2, 4, 'GOL', 52, 'Descuento para SAGUAPAC de cabeza'),
(1, 1, 2, 'GOL', 80, 'Gol de contragolpe definitivo'),
(1, 1, 3, 'ROJA', 75, 'Falta táctica de último recurso');

-- -----------------------------------------------------
-- 14. Régimen Disciplinario Oficial
-- -----------------------------------------------------
INSERT INTO `sanciones` (`id`, `jugador_id`, `partido_id`, `torneo_id`, `tipo_sancion`, `partidos_suspension`, `partidos_cumplidos`, `motivo`, `estado`, `motivo_levantamiento`, `levantado_por_usuario_id`, `fecha_levantamiento`) VALUES
(1, 3, 1, 1, 'ROJA', 1, 0, 'Expulsión con tarjeta roja por cortar jugada manifiesta de gol en el min 75', 'Activa', NULL, NULL, NULL),
(2, 1, 1, 1, 'AMARILLA', 0, 0, 'Amonestación verbal', 'Levantada', 'Error arbitral corregido: falta cometida por otro dorsal (indulto por confraternización)', 1, '2026-10-22 12:00:00');

-- -----------------------------------------------------
-- 15. Medallero Olímpico Oficial Ponderado (Art. 15 Convocatoria ANESAPA)
-- -----------------------------------------------------
INSERT INTO `medallero` (`olimpiada_id`, `delegacion_id`, `torneo_id`, `tipo_medalla`, `puntos_olimpicos`) VALUES
-- 1. Fútbol once (11): Oro 45, Plata 30, Bronce 15
(1, 2, 1, 'ORO',    45),
(1, 1, 1, 'PLATA',  30),
(1, 4, 1, 'BRONCE', 15),

-- 2. Fútbol de Salón (Futsal): Oro 40, Plata 30, Bronce 15
(1, 1, 2, 'ORO',    40),
(1, 3, 2, 'PLATA',  30),
(1, 2, 2, 'BRONCE', 15),

-- 3. Voleibol Mixto: Oro 30, Plata 20, Bronce 10
(1, 1, 3, 'ORO',    30),
(1, 5, 3, 'PLATA',  20),
(1, 2, 3, 'BRONCE', 10),

-- 4. Básquetbol Mixto: Oro 30, Plata 20, Bronce 10
(1, 3, 4, 'ORO',    30),
(1, 1, 4, 'PLATA',  20),
(1, 9, 4, 'BRONCE', 10),

-- 5. Raqueta Frontón: Oro 30, Plata 20, Bronce 10
(1, 2, 5, 'ORO',    30),
(1, 5, 5, 'PLATA',  20),
(1, 1, 5, 'BRONCE', 10),

-- 6. Media Maratón (5 km Libre): Oro 30, Plata 20, Bronce 10
(1, 6, 6, 'ORO',    30),
(1, 3, 6, 'PLATA',  20),
(1, 1, 6, 'BRONCE', 10),

-- 7. Competencias Técnicas Deportivas: Oro 20, Plata 15, Bronce 10
(1, 1, 7, 'ORO',    20),
(1, 2, 7, 'PLATA',  15),
(1, 4, 7, 'BRONCE', 10),

-- 8. Cacho (Directivos): Oro 15, Plata 10, Bronce 5
(1, 5, 8, 'ORO',    15),
(1, 1, 8, 'PLATA',  10),
(1, 2, 8, 'BRONCE',  5),

-- 9. Billar (Directivos): Oro 15, Plata 10, Bronce 5
(1, 2, 9, 'ORO',    15),
(1, 9, 9, 'PLATA',  10),
(1, 3, 9, 'BRONCE',  5),

-- 10. Karaoke (Noche de Talentos): Oro 15, Plata 10, Bronce 5
(1, 1, 10, 'ORO',   15),
(1, 3, 10, 'PLATA', 10),
(1, 6, 10, 'BRONCE', 5),

-- 11. Juegos Tradicionales (5 pts fijos por prueba ganada)
(1, 1, 11, 'ORO',    5),
(1, 3, 11, 'PLATA',  5),
(1, 2, 11, 'BRONCE', 5);

-- Restaurar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;