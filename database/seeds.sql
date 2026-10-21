-- =====================================================================
-- Datos Semilla Consolidados (Seeds Oficiales): Cosmol-Deportes
-- Evento Oficial: XXXIII JORNADAS DEPORTIVAS NACIONALES DE ANESAPA - COSMOL 2026
-- Empresa Anfitriona: COSMOL R.L. (Montero - Santa Cruz)
-- Fechas Oficiales: 22 al 24 de Octubre de 2026
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
-- Hash bcrypt para 'admin123':  $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- -----------------------------------------------------
INSERT INTO `usuarios` (`id`, `nombre`, `username`, `email`, `password`, `rol`, `estado`) VALUES
(1, 'Administrador General COSMOL', 'admin', 'admin@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'Activo'),
(2, 'Comité ANESAPA', 'usuario', 'anesapa@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USUARIO', 'Activo');

-- -----------------------------------------------------
-- 2. Macro-Evento Oficial: XXXIII Jornadas ANESAPA - COSMOL 2026
-- -----------------------------------------------------
INSERT INTO `olimpiadas` (`id`, `nombre`, `anio`, `lema`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 'XXXIII Jornadas Deportivas Nacionales ANESAPA - COSMOL 2026', 2026, 'Gestión, Unidad y Confraternización Inter-Institucional', '2026-10-22', '2026-10-24', 'En Curso');

-- -----------------------------------------------------
-- 3. Las Delegaciones Oficiales (EPSAs Socias Participantes)
-- -----------------------------------------------------
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `responsable`, `telefono`) VALUES
(1,  'COSMOL (MO)',                   'COSMOL',          '#0066B2', 'Johnny Gonzales',         '71012345'),
(2,  'ELAPAS (SU)',                   'ELAPAS',          '#6F42C1', 'Marco Navas',             '77012345'),
(3,  'SAGUAPAC (SC)',                 'SAGUAPAC',        '#28A745', 'Mauricio Aguilera',       '72012345'),
(4,  'SEMAPA (CB)',                   'SEMAPA',          '#DC3545', 'Erlan Arandia',           '73012345'),
(5,  'SAMAPA (LP)',                   'SAMAPA',          '#00A3E0', 'Walter Salazar',          '78012345'),
(6,  'COSAALT (TR)',                  'COSAALT',         '#FD7E14', 'Rodrigo Lopez',           '79012345'),
(7,  'EMAPYC (YC)',                   'EMAPYC',          '#20C997', 'Freddy Valdez',           '75012345'),
(8,  'SELA (OR)',                     'SELA',            '#FFC107', 'Erlan Vasquez',           '74012345'),
(9,  'EPSAS (LP)',                    'EPSAS',           '#6F42C1', 'Brogher Vargas',          '76012345'),
(10, 'Por Definir (Pendiente Sorteo)','SORTEO',          '#6C757D', 'Comité Técnico ANESAPA',  '70000000');

-- -----------------------------------------------------
-- 4. Catálogo Oficial de las 11 Disciplinas
-- -----------------------------------------------------
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`) VALUES
(1,  'Fútbol Once',            '2 tiempos de 45 min. Cancha de césped. Exigencia reglamentaria: 3 jugadores en cancha >= 45 años.', 'futbol', 'Activa'),
(2,  'Fútbol de Salón',        '2 tiempos de 20 min. Cancha cubierta. Exigencia reglamentaria: 1 jugador en cancha >= 45 años.', 'futbol', 'Activa'),
(3,  'Voleibol Mixto',         'Partidos al mejor de 3 sets (25 pts). Exigencia reglamentaria: 2 damas y 1 veterano >= 50 años obligatorios en cancha.', 'volleyball-ball', 'Activa'),
(4,  'Básquetbol Mixto',       '4 cuartos reglamentarios. Exigencia reglamentaria: 1 dama y 1 veterano >= 50 años obligatorios en cancha.', 'basketball-ball', 'Activa'),
(5,  'Raqueta Frontón',        'Modalidad pareja (2 jugadores). Sets a 15 puntos (al mejor de 3 sets). Normas oficiales de frontón.', 'table-tennis', 'Activa'),
(6,  'Media Maratón',          'Carrera pedestre urbana de 5 kilómetros. Hasta 2 representantes por delegación. Circuito Urb. Celina.', 'running', 'Activa'),
(7,  'Cacho',                  'Juego de dados por parejas (exigencia: al menos 1 directivo o ejecutivo). Anotación en planilla Alalay.', 'dice', 'Activa'),
(8,  'Karaoke',                'Noche de talentos. Hasta 2 representantes por delegación. Calificación global de jurado.', 'microphone-alt', 'Activa'),
(9,  'Billar',                 'Modalidad pareja. Exigencia: al menos 1 directivo o ejecutivo. Partidas por chicos ganados.', 'circle', 'Activa'),
(10, 'Competencias Técnicas',  'Instalación de acometida de agua potable matriz a llave de paso. 3 operarios por EPSA.', 'tools', 'Activa'),
(11, 'Juegos Populares',       'Pruebas recreativas: Carrera de embolsao, Carrera de cuchara con huevo y Carrera de carretilla.', 'smile', 'Activa');

-- -----------------------------------------------------
-- 5. Reglas Matemáticas de Puntuación por Disciplina
-- -----------------------------------------------------
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`) VALUES
(1,  1,  3, 1, 0, 0, 1, 0, 0), -- Fútbol Once: Vic=3, Emp=1, Der=0
(2,  2,  3, 1, 0, 0, 1, 0, 0), -- Futsal: Vic=3, Emp=1, Der=0
(3,  3,  3, 0, 0, 1, 0, 1, 2), -- Vóley: Vic 2-0/2-1=3pts, Der 1-2=1pt
(4,  4,  2, 0, 1, 0, 0, 0, 0), -- Básquet: Vic=2, Der=1
(5,  5,  2, 0, 0, 0, 0, 1, 2), -- Frontón: Sets a 15 pts
(6,  6,  2, 0, 0, 0, 0, 0, 0), -- Maratón: Cronometraje y orden
(7,  7,  2, 1, 0, 0, 1, 0, 0), -- Cacho: Vic=2, Emp=1, Der=0
(8,  8,  2, 0, 0, 0, 0, 0, 0), -- Karaoke: Puntuación de jurado
(9,  9,  2, 0, 0, 0, 0, 0, 0), -- Billar: Vic=2, Der=0
(10, 10, 2, 0, 0, 0, 0, 0, 0), -- Competencias Técnicas: Tiempo y estanqueidad
(11, 11, 1, 0, 0, 0, 0, 0, 0); -- Juegos Populares: Puntos por prueba

-- -----------------------------------------------------
-- 6. Sedes y Canchas Oficiales (Montero - Santa Cruz)
-- Coordenadas GPS Oficiales Verificadas
-- -----------------------------------------------------
INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `latitud`, `longitud`, `estado`) VALUES
(1,  'Estadio Gilberto Parada',                      'Av. Circunvalación Este (Montero)',                                        '39221144', -17.34308263, -63.26197784, 'Activa'),
(2,  'Coliseo Patujú',                              'Calle Litoral esq. Roberto Paz, Zona Central (Montero)',                   '39223300', -17.34033131, -63.25355583, 'Activa'),
(3,  'Coliseo Plurinacional',                       'Av. Betania (Montero)',                                                    '39224400', -17.34959888, -63.23763889, 'Activa'),
(4,  'Bar Restaurante "La Perla"',                   'Av. Betania Nro. 218 (Montero)',                                           '39225500', -17.34887214, -63.24223902, 'Activa'),
(5,  'Oficina y Salón Auditorio COSMOL',            'Calle Isaías Parada #219, Zona Central (Montero)',                         '39221144', -17.33940000, -63.25580000, 'Activa'),
(6,  'Coliseo FINOR',                               'Campus Universitario FINOR, Av. Circunvalación Noroeste (Montero)',        '39226600', -17.33120000, -63.26100000, 'Activa'),
(7,  'Coliseo Andrés Ibáñez',                       'Av. Circunvalación Noroeste (Montero)',                                    '39227700', -17.34050945, -63.27135970, 'Activa'),
(8,  'Billar Springfield Suc. Montero',             'Calle Isaías Parada esq. Chuquisaca (Montero)',                            '39228800', -17.34149600, -63.25464100, 'Activa'),
(9,  'Mutual de Ex Jugadores de Fútbol de Montero', 'Calle 16 de Julio #415 (Montero)',                                         '39229900', -17.33230800, -63.25752800, 'Activa'),
(10, 'Circuito Urb. Celina Muyurina',               'Urbanización Celina Muyurina (Montero)',                                   '39220000', -17.34850000, -63.24800000, 'Activa'),
(11, 'Frontón Municipal de Montero',                'Av. Circunvalación Noroeste (lado Coliseo Andrés Ibáñez)',                  '39227700', -17.33999329, -63.27183894, 'Activa');

INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`) VALUES
(1,  1,  'Estadio Gilberto Parada - Campo Principal',    'Césped Natural',     'Disponible'),
(2,  1,  'Cancha Auxiliar (Azucareros / La Esperanza)',  'Césped Natural',     'Disponible'),
(3,  2,  'Coliseo Patujú - Cancha Central',              'Parquet Flotante',   'Disponible'),
(4,  3,  'Coliseo Plurinacional - Cancha Central',       'Tabloncillo',        'Disponible'),
(5,  4,  'Escenario Principal La Perla',                'Salón de Eventos',   'Disponible'),
(6,  5,  'Salón Auditorio Central COSMOL',              'Auditorio',          'Disponible'),
(7,  6,  'Coliseo FINOR - Cancha Principal',             'Parquet',            'Disponible'),
(8,  7,  'Coliseo Andrés Ibáñez - Cancha Principal',     'Cemento Pulido',     'Disponible'),
(9,  11, 'Frontón Municipal - Frontón Reglamentario',    'Frontón Oficial',    'Disponible'),
(10, 8,  'Mesas Oficiales Springfield',                  'Paño Profesional',   'Disponible'),
(11, 9,  'Cancha Mutual de Ex Jugadores',               'Césped Natural',     'Disponible'),
(12, 10, 'Circuito Oficial 5.0 km',                     'Asfalto Pavimentado','Disponible');

-- -----------------------------------------------------
-- 7. Competencias Activas por Disciplina (Los 11 Torneos Oficiales)
-- -----------------------------------------------------
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1,  1, 1,  1,  'Fútbol Once Varones - ANESAPA 2026',                  'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-24'),
(2,  1, 2,  2,  'Fútbol de Salón Varones - ANESAPA 2026',              'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-24'),
(3,  1, 3,  3,  'Voleibol Mixto - ANESAPA 2026',                         'Mixto',     'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-22'),
(4,  1, 4,  4,  'Básquetbol Mixto - ANESAPA 2026',                       'Mixto',     'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-24'),
(5,  1, 5,  5,  'Raqueta Frontón - ANESAPA 2026',                      'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-23'),
(6,  1, 6,  6,  'Media Maratón 5K - ANESAPA 2026',                     'Mixto',     'Liga',           'Solo_Ida', 'Fase_Grupos', '2026-10-24', '2026-10-24'),
(7,  1, 7,  7,  'Cacho (Juego de Salón) - ANESAPA 2026',               'Mixto',     'Eliminatoria',   'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-23'),
(8,  1, 8,  8,  'Karaoke (Noche de Talentos) - ANESAPA 2026',            'Mixto',     'Liga',           'Solo_Ida', 'Fase_Grupos', '2026-10-22', '2026-10-22'),
(9,  1, 9,  9,  'Billar (Juego de Salón) - ANESAPA 2026',              'Mixto',     'Eliminatoria',   'Solo_Ida', 'Fase_Grupos', '2026-10-23', '2026-10-23'),
(10, 1, 10, 10, 'Competencias Técnicas (Fontanería) - ANESAPA 2026',   'Mixto',     'Liga',           'Solo_Ida', 'Fase_Grupos', '2026-10-24', '2026-10-24'),
(11, 1, 11, 11, 'Juegos Populares Tradicionales - ANESAPA 2026',       'Mixto',     'Liga',           'Solo_Ida', 'Fase_Grupos', '2026-10-24', '2026-10-24');

-- -----------------------------------------------------
-- 8. Grupos por Competencia (Series A y B)
-- -----------------------------------------------------
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`) VALUES
-- Fútbol Once (Torneo 1)
(1,  1, 'Serie A', 1),
(2,  1, 'Serie B', 2),
-- Futsal (Torneo 2)
(3,  2, 'Serie A', 1),
(4,  2, 'Serie B', 2),
-- Voleibol Mixto (Torneo 3)
(5,  3, 'Serie A', 1),
(6,  3, 'Serie B', 2),
-- Básquetbol Mixto (Torneo 4)
(7,  4, 'Serie A', 1),
(8,  4, 'Serie B', 2),
-- Raqueta Frontón (Torneo 5)
(9,  5, 'Serie A', 1),
(10, 5, 'Serie B', 2);

-- -----------------------------------------------------
-- 9. Representativos por Competencia (Equipos Oficiales de devChichico + Cupos de Playoffs)
-- -----------------------------------------------------
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `delegado_contacto`, `telefono_contacto`) VALUES
-- Torneo 1: Fútbol Once
(1,  1, 1, 1, 'COSMOL - Fútbol Once',         'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(2,  3, 1, 1, 'SAGUAPAC - Fútbol Once',       'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(3,  4, 1, 1, 'SEMAPA - Fútbol Once',         'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(4,  2, 1, 2, 'ELAPAS - Fútbol Once',         'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(5,  8, 1, 2, 'SELA - Fútbol Once',           'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),
(6,  7, 1, 2, 'EMAPYC - Fútbol Once',         'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(7,  6, 1, 2, 'COSAALT - Fútbol Once',        'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),

-- Torneo 2: Fútbol de Salón (Futsal)
(8,  1, 2, 3, 'COSMOL - Futsal',              'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(9,  3, 2, 3, 'SAGUAPAC - Futsal',            'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(10, 4, 2, 3, 'SEMAPA - Futsal',              'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(11, 2, 2, 4, 'ELAPAS - Futsal',              'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(12, 8, 2, 4, 'SELA - Futsal',                'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),
(13, 6, 2, 4, 'COSAALT - Futsal',             'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),

-- Torneo 3: Voleibol Mixto
(14, 1, 3, 5, 'COSMOL - Voleibol Mixto',      'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(15, 3, 3, 5, 'SAGUAPAC - Voleibol Mixto',    'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(16, 4, 3, 5, 'SEMAPA - Voleibol Mixto',      'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(17, 2, 3, 6, 'ELAPAS - Voleibol Mixto',      'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(18, 8, 3, 6, 'SELA - Voleibol Mixto',        'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),
(19, 7, 3, 6, 'EMAPYC - Voleibol Mixto',      'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(20, 6, 3, 6, 'COSAALT - Voleibol Mixto',     'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),

-- Torneo 4: Básquetbol Mixto
(21, 1, 4, 7, 'COSMOL - Básquetbol Mixto',    'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(22, 3, 4, 7, 'SAGUAPAC - Básquetbol Mixto',  'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(23, 4, 4, 7, 'SEMAPA - Básquetbol Mixto',    'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(24, 2, 4, 8, 'ELAPAS - Básquetbol Mixto',    'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(25, 8, 4, 8, 'SELA - Básquetbol Mixto',      'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),
(26, 7, 4, 8, 'EMAPYC - Básquetbol Mixto',    'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(27, 6, 4, 8, 'COSAALT - Básquetbol Mixto',   'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),

-- Torneo 5: Raqueta Frontón
(28, 1, 5, 9,  'COSMOL - Raqueta Frontón',    'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(29, 4, 5, 9,  'SEMAPA - Raqueta Frontón',    'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(30, 8, 5, 9,  'SELA - Raqueta Frontón',      'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),
(31, 2, 5, 10, 'ELAPAS - Raqueta Frontón',    'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(32, 7, 5, 10, 'EMAPYC - Raqueta Frontón',    'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(33, 6, 5, 10, 'COSAALT - Raqueta Frontón',   'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),

-- Torneo 6: Media Maratón
(34, 1, 6, NULL, 'COSMOL - Media Maratón',    'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(35, 2, 6, NULL, 'ELAPAS - Media Maratón',    'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(36, 3, 6, NULL, 'SAGUAPAC - Media Maratón',  'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(37, 4, 6, NULL, 'SEMAPA - Media Maratón',    'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(38, 5, 6, NULL, 'SAMAPA - Media Maratón',    'SAMAPA',   '#00A3E0', 'Walter Salazar',  '78012345'),
(39, 6, 6, NULL, 'COSAALT - Media Maratón',   'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),
(40, 7, 6, NULL, 'EMAPYC - Media Maratón',    'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(41, 8, 6, NULL, 'SELA - Media Maratón',      'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),

-- Torneo 7: Cacho
(42, 1, 7, NULL, 'COSMOL - Cacho',            'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(43, 8, 7, NULL, 'SELA - Cacho',              'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),
(44, 3, 7, NULL, 'SAGUAPAC - Cacho',          'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(45, 7, 7, NULL, 'EMAPYC - Cacho',            'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(46, 2, 7, NULL, 'ELAPAS - Cacho',            'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(47, 6, 7, NULL, 'COSAALT - Cacho',           'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),
(48, 4, 7, NULL, 'SEMAPA - Cacho',            'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(49, 5, 7, NULL, 'SAMAPA - Cacho',            'SAMAPA',   '#00A3E0', 'Walter Salazar',  '78012345'),

-- Torneo 8: Karaoke
(50, 1, 8, NULL, 'COSMOL - Karaoke',          'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(51, 2, 8, NULL, 'ELAPAS - Karaoke',          'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(52, 3, 8, NULL, 'SAGUAPAC - Karaoke',        'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(53, 4, 8, NULL, 'SEMAPA - Karaoke',          'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(54, 5, 8, NULL, 'SAMAPA - Karaoke',          'SAMAPA',   '#00A3E0', 'Walter Salazar',  '78012345'),
(55, 6, 8, NULL, 'COSAALT - Karaoke',         'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),
(56, 7, 8, NULL, 'EMAPYC - Karaoke',          'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(57, 8, 8, NULL, 'SELA - Karaoke',            'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),

-- Torneo 9: Billar
(58, 1, 9, NULL, 'COSMOL - Billar',           'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(59, 2, 9, NULL, 'ELAPAS - Billar',           'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(60, 3, 9, NULL, 'SAGUAPAC - Billar',         'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(61, 4, 9, NULL, 'SEMAPA - Billar',           'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(62, 5, 9, NULL, 'SAMAPA - Billar',           'SAMAPA',   '#00A3E0', 'Walter Salazar',  '78012345'),
(63, 6, 9, NULL, 'COSAALT - Billar',          'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),
(64, 7, 9, NULL, 'EMAPYC - Billar',           'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(65, 8, 9, NULL, 'SELA - Billar',             'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),

-- Torneo 10: Competencias Técnicas
(66, 1, 10, NULL, 'COSMOL - Fontanería',      'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(67, 2, 10, NULL, 'ELAPAS - Fontanería',      'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(68, 3, 10, NULL, 'SAGUAPAC - Fontanería',    'SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(69, 4, 10, NULL, 'SEMAPA - Fontanería',      'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(70, 5, 10, NULL, 'SAMAPA - Fontanería',      'SAMAPA',   '#00A3E0', 'Walter Salazar',  '78012345'),
(71, 6, 10, NULL, 'COSAALT - Fontanería',     'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),
(72, 7, 10, NULL, 'EMAPYC - Fontanería',      'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(73, 8, 10, NULL, 'SELA - Fontanería',        'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),

-- Torneo 11: Juegos Populares
(74, 1, 11, NULL, 'COSMOL - Juegos Populares',  'COSMOL',   '#0066B2', 'Johnny Gonzales', '71012345'),
(75, 2, 11, NULL, 'ELAPAS - Juegos Populares',  'ELAPAS',   '#6F42C1', 'Marco Navas',     '77012345'),
(76, 3, 11, NULL, 'SAGUAPAC - Juegos Populares','SAGUAPAC', '#28A745', 'Mauricio',        '72012345'),
(77, 4, 11, NULL, 'SEMAPA - Juegos Populares',  'SEMAPA',   '#DC3545', 'Erlan Arandia',   '73012345'),
(78, 5, 11, NULL, 'SAMAPA - Juegos Populares',  'SAMAPA',   '#00A3E0', 'Walter Salazar',  '78012345'),
(79, 6, 11, NULL, 'COSAALT - Juegos Populares', 'COSAALT',  '#FD7E14', 'Rodrigo Lopez',   '79012345'),
(80, 7, 11, NULL, 'EMAPYC - Juegos Populares',  'EMAPYC',   '#20C997', 'Freddy Valdez',   '75012345'),
(81, 8, 11, NULL, 'SELA - Juegos Populares',    'SELA',     '#FFC107', 'Erlan Vasquez',   '74012345'),

-- Cupos Oficiales para Semifinales y Finales de Playoffs (Torneos 1, 2, 3, 4, 5)
-- Fútbol Once (Torneo 1)
(82, 10, 1, NULL, 'Ganador Serie B (Por Definir)',      'Ganador B',    '#28A745', 'Comité Técnico', '70000000'),
(83, 10, 1, NULL, 'Segundo Serie A (Por Definir)',      'Segundo A',    '#FFC107', 'Comité Técnico', '70000000'),
(84, 10, 1, NULL, 'Ganador Serie A (Por Definir)',      'Ganador A',    '#0066B2', 'Comité Técnico', '70000000'),
(85, 10, 1, NULL, 'Segundo Serie B (Por Definir)',      'Segundo B',    '#FD7E14', 'Comité Técnico', '70000000'),
(86, 10, 1, NULL, 'Perdedor Semifinal 1 (Por Definir)', 'Perd. Semi 1', '#6C757D', 'Comité Técnico', '70000000'),
(87, 10, 1, NULL, 'Perdedor Semifinal 2 (Por Definir)', 'Perd. Semi 2', '#6C757D', 'Comité Técnico', '70000000'),
(88, 10, 1, NULL, 'Ganador Semifinal 1 (Por Definir)',  'Gan. Semi 1',  '#0066B2', 'Comité Técnico', '70000000'),
(89, 10, 1, NULL, 'Ganador Semifinal 2 (Por Definir)',  'Gan. Semi 2',  '#28A745', 'Comité Técnico', '70000000'),

-- Futsal (Torneo 2)
(90, 10, 2, NULL, 'Primero Serie A (Por Definir)',      '1º Serie A',   '#0066B2', 'Comité Técnico', '70000000'),
(91, 10, 2, NULL, 'Segundo Serie B (Por Definir)',      '2º Serie B',   '#FD7E14', 'Comité Técnico', '70000000'),
(92, 10, 2, NULL, 'Primero Serie B (Por Definir)',      '1º Serie B',   '#28A745', 'Comité Técnico', '70000000'),
(93, 10, 2, NULL, 'Segundo Serie A (Por Definir)',      '2º Serie A',   '#FFC107', 'Comité Técnico', '70000000'),
(94, 10, 2, NULL, 'Perdedor Semi 1 Futsal',             'Perd. Semi 1', '#6C757D', 'Comité Técnico', '70000000'),
(95, 10, 2, NULL, 'Perdedor Semi 2 Futsal',             'Perd. Semi 2', '#6C757D', 'Comité Técnico', '70000000'),
(96, 10, 2, NULL, 'Ganador Semi 1 Futsal',              'Gan. Semi 1',  '#0066B2', 'Comité Técnico', '70000000'),
(97, 10, 2, NULL, 'Ganador Semi 2 Futsal',              'Gan. Semi 2',  '#28A745', 'Comité Técnico', '70000000'),

-- Voleibol Mixto (Torneo 3)
(98, 10, 3, NULL, 'Ganador Serie B Vóley (Por Definir)','Ganador B',    '#28A745', 'Comité Técnico', '70000000'),
(99, 10, 3, NULL, 'Segundo Serie A Vóley (Por Definir)','Segundo A',    '#FFC107', 'Comité Técnico', '70000000'),
(100,10, 3, NULL, 'Ganador Serie A Vóley (Por Definir)','Ganador A',    '#0066B2', 'Comité Técnico', '70000000'),
(101,10, 3, NULL, 'Segundo Serie B Vóley (Por Definir)','Segundo B',    '#FD7E14', 'Comité Técnico', '70000000'),
(102,10, 3, NULL, 'Ganador Semi 1 Vóley (Por Definir)', 'Gan. Semi 1',  '#0066B2', 'Comité Técnico', '70000000'),
(103,10, 3, NULL, 'Ganador Semi 2 Vóley (Por Definir)', 'Gan. Semi 2',  '#28A745', 'Comité Técnico', '70000000'),

-- Básquetbol Mixto (Torneo 4)
(104,10, 4, NULL, 'Primero Serie A Básquet',            '1º Serie A',   '#0066B2', 'Comité Técnico', '70000000'),
(105,10, 4, NULL, 'Segundo Serie B Básquet',            '2º Serie B',   '#FD7E14', 'Comité Técnico', '70000000'),
(106,10, 4, NULL, 'Primero Serie B Básquet',            '1º Serie B',   '#28A745', 'Comité Técnico', '70000000'),
(107,10, 4, NULL, 'Segundo Serie A Básquet',            '2º Serie A',   '#FFC107', 'Comité Técnico', '70000000'),
(108,10, 4, NULL, 'Perdedor Semi 1 Básquet',            'Perd. Semi 1', '#6C757D', 'Comité Técnico', '70000000'),
(109,10, 4, NULL, 'Perdedor Semi 2 Básquet',            'Perd. Semi 2', '#6C757D', 'Comité Técnico', '70000000'),
(110,10, 4, NULL, 'Ganador Semi 1 Básquet',             'Gan. Semi 1',  '#0066B2', 'Comité Técnico', '70000000'),
(111,10, 4, NULL, 'Ganador Semi 2 Básquet',             'Gan. Semi 2',  '#28A745', 'Comité Técnico', '70000000'),

-- Raqueta Frontón (Torneo 5)
(112,10, 5, NULL, 'Primero Serie A Frontón',            '1º Serie A',   '#0066B2', 'Comité Técnico', '70000000'),
(113,10, 5, NULL, 'Segundo Serie B Frontón',            '2º Serie B',   '#FD7E14', 'Comité Técnico', '70000000'),
(114,10, 5, NULL, 'Primero Serie B Frontón',            '1º Serie B',   '#28A745', 'Comité Técnico', '70000000'),
(115,10, 5, NULL, 'Segundo Serie A Frontón',            '2º Serie A',   '#FFC107', 'Comité Técnico', '70000000'),
(116,10, 5, NULL, 'Perdedor Semi 1 Frontón',            'Perd. Semi 1', '#6C757D', 'Comité Técnico', '70000000'),
(117,10, 5, NULL, 'Perdedor Semi 2 Frontón',            'Perd. Semi 2', '#6C757D', 'Comité Técnico', '70000000'),
(118,10, 5, NULL, 'Ganador Semi 1 Frontón',             'Gan. Semi 1',  '#0066B2', 'Comité Técnico', '70000000'),
(119,10, 5, NULL, 'Ganador Semi 2 Frontón',             'Gan. Semi 2',  '#28A745', 'Comité Técnico', '70000000');

-- -----------------------------------------------------
-- 10. Fixture Oficial de Partidos (Jueves 22, Viernes 23, Sábado 24)
-- Basado fielmente en Docs/fixture_jueves.md, Docs/fixture_viernes.md y Docs/fixture_sabado.md
-- Todos en estado 'Programado' listos para ser operados por la mesa de control
-- -----------------------------------------------------
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`) VALUES

-- ==================================================================================
-- 📅 JUEVES 22 DE OCTUBRE DE 2026
-- ==================================================================================

-- ⚽ FÚTBOL ONCE: Cancha 1 (Estadio Gilberto Parada) y Cancha 2 (Cancha Esperanza / Azucareros)
(1,  1, 1, 'Grupos',    'Jornada 1', 3,   2,   1, '2026-10-22', '07:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie A: SEMAPA vs SAGUAPAC'),
(2,  1, 2, 'Grupos',    'Jornada 1', 6,   7,   2, '2026-10-22', '07:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: EMAPYC vs COSAALT'),
(3,  1, 1, 'Grupos',    'Jornada 2', 1,   2,   1, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie A: COSMOL vs SAGUAPAC'),
(4,  1, 2, 'Grupos',    'Jornada 2', 4,   5,   2, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: ELAPAS vs SELA'),
(5,  1, 1, 'Grupos',    'Jornada 3', 1,   3,   1, '2026-10-22', '10:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie A: COSMOL vs SEMAPA'),
(6,  1, 2, 'Grupos',    'Jornada 3', 5,   7,   2, '2026-10-22', '10:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: SELA vs COSAALT'),
(7,  1, 2, 'Grupos',    'Jornada 4', 4,   6,   1, '2026-10-22', '12:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: ELAPAS vs EMAPYC'),
(8,  1, 2, 'Grupos',    'Jornada 5', 5,   6,   1, '2026-10-22', '13:30:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: SELA vs EMAPYC'),
(9,  1, 2, 'Grupos',    'Jornada 6', 4,   7,   1, '2026-10-22', '15:00:00', NULL, NULL, 'Programado', 'Fase de Grupos - Serie B: ELAPAS vs COSAALT'),
(10, 1, NULL, 'Semifinal', 'Semifinal 1', 82, 83, 1, '2026-10-22', '16:30:00', NULL, NULL, 'Programado', 'Semifinal 1: Ganador Serie B vs Segundo Serie A (Estadio Gilberto Parada)'),
(11, 1, NULL, 'Semifinal', 'Semifinal 2', 84, 85, 2, '2026-10-22', '16:30:00', NULL, NULL, 'Programado', 'Semifinal 2: Ganador Serie A vs Segundo Serie B (Cancha Esperanza)'),

-- 🏐 VOLEIBOL MIXTO: Cancha 3 (Coliseo Patujú) y Cancha 4 (Coliseo Plurinacional)
(12, 3, 5, 'Grupos',    'Jornada 1', 16,  15,  3, '2026-10-22', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: SEMAPA vs SAGUAPAC'),
(13, 3, 6, 'Grupos',    'Jornada 1', 19,  20,  4, '2026-10-22', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: EMAPYC vs COSAALT'),
(14, 3, 5, 'Grupos',    'Jornada 2', 14,  15,  3, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: COSMOL vs SAGUAPAC'),
(15, 3, 6, 'Grupos',    'Jornada 2', 17,  18,  4, '2026-10-22', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs SELA'),
(16, 3, 5, 'Grupos',    'Jornada 3', 14,  16,  3, '2026-10-22', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: COSMOL vs SEMAPA'),
(17, 3, 6, 'Grupos',    'Jornada 3', 18,  20,  4, '2026-10-22', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: SELA vs COSAALT'),
(18, 3, 6, 'Grupos',    'Jornada 4', 17,  19,  4, '2026-10-22', '11:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs EMAPYC'),
(19, 3, 6, 'Grupos',    'Jornada 5', 18,  19,  4, '2026-10-22', '12:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: SELA vs EMAPYC'),
(20, 3, 6, 'Grupos',    'Jornada 6', 17,  20,  4, '2026-10-22', '13:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs COSAALT'),
(21, 3, NULL, 'Semifinal', 'Semifinal 1', 98, 99,  3, '2026-10-22', '15:00:00', NULL, NULL, 'Programado', 'Semifinal 1: Ganador Serie B vs Segundo Serie A (Coliseo Patujú)'),
(22, 3, NULL, 'Semifinal', 'Semifinal 2', 100, 101, 3, '2026-10-22', '16:00:00', NULL, NULL, 'Programado', 'Segunda Semifinal: Ganador Serie A vs Segundo Serie B (Coliseo Patujú)'),
(23, 3, NULL, 'Final',     'Gran Final',  102, 103, 3, '2026-10-22', '17:30:00', NULL, NULL, 'Programado', 'Gran Final Vóley Mixto: Disputa Medalla de Oro y Plata (Coliseo Patujú)'),

-- 🎤 KARAOKE (Torneo 8): Cancha 5 (Bar Restaurante La Perla)
(24, 8, NULL, 'Final',     'Noche de Talentos', 50, 53, 5, '2026-10-22', '22:00:00', NULL, NULL, 'Programado', 'Concurso Oficial de Karaoke ANESAPA 2026 (Bar Restaurante La Perla)'),

-- ==================================================================================
-- 📅 VIERNES 23 DE OCTUBRE DE 2026
-- ==================================================================================

-- ⚽ FUTSAL (Torneo 2): Cancha 3 (Coliseo Patujú) y Cancha 4 (Coliseo Plurinacional)
(25, 2, 3, 'Grupos',    'Jornada 1', 8,   9,   3, '2026-10-23', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: COSMOL vs SAGUAPAC'),
(26, 2, 4, 'Grupos',    'Jornada 1', 11,  12,  4, '2026-10-23', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs SELA'),
(27, 2, 3, 'Grupos',    'Jornada 2', 10,  9,   3, '2026-10-23', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: SEMAPA vs SAGUAPAC'),
(28, 2, 4, 'Grupos',    'Jornada 2', 13,  12,  4, '2026-10-23', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: COSAALT vs SELA'),
(29, 2, 3, 'Grupos',    'Jornada 3', 8,   10,  3, '2026-10-23', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Patujú - Serie A: COSMOL vs SEMAPA'),
(30, 2, 4, 'Grupos',    'Jornada 3', 11,  13,  4, '2026-10-23', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Plurinacional - Serie B: ELAPAS vs COSAALT'),
(31, 2, NULL, 'Semifinal', 'Semifinal 1', 90, 91, 3, '2026-10-23', '12:00:00', NULL, NULL, 'Programado', 'Semifinal 1: Primero Serie A vs Segundo Serie B (Coliseo Patujú)'),
(32, 2, NULL, 'Semifinal', 'Semifinal 2', 92, 93, 4, '2026-10-23', '12:00:00', NULL, NULL, 'Programado', 'Semifinal 2: Primero Serie B vs Segundo Serie A (Coliseo Plurinacional)'),
(33, 2, NULL, 'Tercer_Puesto', '3er y 4to', 94, 95, 3, '2026-10-23', '15:00:00', NULL, NULL, 'Programado', 'Definición Tercer Puesto Futsal (Coliseo Patujú)'),
(34, 2, NULL, 'Final',     'Gran Final',  96, 97,  3, '2026-10-23', '17:00:00', NULL, NULL, 'Programado', 'Gran Final Futsal: Disputa Medalla de Oro y Plata (Coliseo Patujú)'),

-- 🏀 BÁSQUETBOL MIXTO (Torneo 4): Cancha 7 (Coliseo FINOR) y Cancha 8 (Coliseo Andrés Ibáñez)
(35, 4, 7, 'Grupos',    'Jornada 1', 23,  22,  7, '2026-10-23', '08:00:00', NULL, NULL, 'Programado', 'Coliseo FINOR - Serie A: SEMAPA vs SAGUAPAC'),
(36, 4, 8, 'Grupos',    'Jornada 1', 26,  27,  8, '2026-10-23', '08:00:00', NULL, NULL, 'Programado', 'Coliseo Andrés Ibáñez - Serie B: EMAPYC vs COSAALT'),
(37, 4, 7, 'Grupos',    'Jornada 2', 21,  22,  7, '2026-10-23', '09:00:00', NULL, NULL, 'Programado', 'Coliseo FINOR - Serie A: COSMOL vs SAGUAPAC'),
(38, 4, 8, 'Grupos',    'Jornada 2', 24,  25,  8, '2026-10-23', '09:00:00', NULL, NULL, 'Programado', 'Coliseo Andrés Ibáñez - Serie B: ELAPAS vs SELA'),
(39, 4, 7, 'Grupos',    'Jornada 3', 21,  23,  7, '2026-10-23', '10:00:00', NULL, NULL, 'Programado', 'Coliseo FINOR - Serie A: COSMOL vs SEMAPA'),
(40, 4, 8, 'Grupos',    'Jornada 3', 25,  27,  8, '2026-10-23', '10:00:00', NULL, NULL, 'Programado', 'Coliseo Andrés Ibáñez - Serie B: SELA vs COSAALT'),
(41, 4, 8, 'Grupos',    'Jornada 4', 24,  26,  8, '2026-10-23', '11:00:00', NULL, NULL, 'Programado', 'Coliseo Andrés Ibáñez - Serie B: ELAPAS vs EMAPYC'),
(42, 4, 8, 'Grupos',    'Jornada 5', 25,  26,  8, '2026-10-23', '12:00:00', NULL, NULL, 'Programado', 'Coliseo Andrés Ibáñez - Serie B: SELA vs EMAPYC'),
(43, 4, 8, 'Grupos',    'Jornada 6', 24,  27,  8, '2026-10-23', '13:30:00', NULL, NULL, 'Programado', 'Coliseo Andrés Ibáñez - Serie B: ELAPAS vs COSAALT'),
(44, 4, NULL, 'Semifinal', 'Semifinal 1', 104, 105, 7, '2026-10-23', '15:00:00', NULL, NULL, 'Programado', 'Semifinal 1: Primero Serie A vs Segundo Serie B (Coliseo FINOR)'),
(45, 4, NULL, 'Semifinal', 'Semifinal 2', 106, 107, 8, '2026-10-23', '15:00:00', NULL, NULL, 'Programado', 'Semifinal 2: Primero Serie B vs Segundo Serie A (Coliseo Andrés Ibáñez)'),
(46, 4, NULL, 'Tercer_Puesto', '3er y 4to', 108, 109, 7, '2026-10-23', '17:00:00', NULL, NULL, 'Programado', 'Definición 3er y 4to Puesto Básquetbol Mixto (Coliseo FINOR)'),
(47, 4, NULL, 'Final',     'Gran Final',  110, 111, 7, '2026-10-23', '18:30:00', NULL, NULL, 'Programado', 'Gran Final Básquetbol Mixto (Coliseo FINOR)'),

-- 🎾 RAQUETA FRONTÓN (Torneo 5): Cancha 9 (Frontón Municipal de Montero)
(48, 5, 9,  'Grupos',   'Jornada 1', 28,  29,  9, '2026-10-23', '08:00:00', NULL, NULL, 'Programado', 'Frontón Municipal - Serie A: COSMOL vs SEMAPA'),
(49, 5, 10, 'Grupos',   'Jornada 1', 31,  32,  9, '2026-10-23', '08:00:00', NULL, NULL, 'Programado', 'Frontón Municipal - Serie B: ELAPAS vs EMAPYC'),
(50, 5, 9,  'Grupos',   'Jornada 2', 30,  29,  9, '2026-10-23', '09:00:00', NULL, NULL, 'Programado', 'Frontón Municipal - Serie A: SELA vs SEMAPA'),
(51, 5, 10, 'Grupos',   'Jornada 2', 33,  32,  9, '2026-10-23', '09:00:00', NULL, NULL, 'Programado', 'Frontón Municipal - Serie B: COSAALT vs EMAPYC'),
(52, 5, 9,  'Grupos',   'Jornada 3', 28,  30,  9, '2026-10-23', '10:00:00', NULL, NULL, 'Programado', 'Frontón Municipal - Serie A: COSMOL vs SELA'),
(53, 5, 10, 'Grupos',   'Jornada 3', 31,  33,  9, '2026-10-23', '10:00:00', NULL, NULL, 'Programado', 'Frontón Municipal - Serie B: ELAPAS vs COSAALT'),
(54, 5, NULL, 'Semifinal', 'Semifinal 1', 112, 113, 9, '2026-10-23', '12:00:00', NULL, NULL, 'Programado', 'Semifinal 1: Primero Serie A vs Segundo Serie B (Frontón Municipal)'),
(55, 5, NULL, 'Semifinal', 'Semifinal 2', 114, 115, 9, '2026-10-23', '12:00:00', NULL, NULL, 'Programado', 'Semifinal 2: Primero Serie B vs Segundo Serie A (Frontón Municipal)'),
(56, 5, NULL, 'Tercer_Puesto', '3er y 4to', 116, 117, 9, '2026-10-23', '15:00:00', NULL, NULL, 'Programado', 'Definición 3er y 4to Puesto Frontón (Frontón Municipal)'),
(57, 5, NULL, 'Final',     'Gran Final',  118, 119, 9, '2026-10-23', '17:00:00', NULL, NULL, 'Programado', 'Gran Final Frontón: Medalla de Oro y Plata (Frontón Municipal)'),

-- 🎱 BILLAR Y CACHO (Viernes Noche): Cancha 10 (Billar Springfield Suc. Montero)
(58, 9, NULL, 'Final',     'Torneo Directivo', 58, 59, 10, '2026-10-23', '20:00:00', NULL, NULL, 'Programado', 'Torneo Oficial de Billar ANESAPA 2026 (Billar Springfield)'),
(59, 7, NULL, 'Final',     'Torneo Directivo', 42, 43, 10, '2026-10-23', '20:30:00', NULL, NULL, 'Programado', 'Campeonato Oficial de Cacho Alalay (Billar Springfield)'),

-- ==================================================================================
-- 📅 SÁBADO 24 DE OCTUBRE DE 2026
-- ==================================================================================

-- 🏃 MARATÓN ANESAPA 5K (Torneo 6): Cancha 12 (Circuito Urb. Celina Muyurina)
(60, 6, NULL, 'Final',     'Final 5K',    34,  36,  12, '2026-10-24', '07:30:00', NULL, NULL, 'Programado', 'Prueba Pedestre 5K Oficial ANESAPA (Circuito Urb. Celina Muyurina)'),

-- ⚽ FÚTBOL ONCE: FINALES (Torneo 1): Cancha 11 (Mutual de Ex Jugadores de Fútbol de Montero)
(61, 1, NULL, 'Tercer_Puesto', '3er y 4to Puesto', 86, 87, 11, '2026-10-24', '08:00:00', NULL, NULL, 'Programado', 'Definición 3er y 4to Puesto Fútbol Once (Mutual de Ex Jugadores)'),
(62, 1, NULL, 'Final',     'Gran Final',          88, 89, 11, '2026-10-24', '09:30:00', NULL, NULL, 'Programado', 'Gran Final de Fútbol Once ANESAPA 2026 (Mutual de Ex Jugadores)'),

-- 🎯 COMPETENCIAS TÉCNICAS (Torneo 10): Cancha 12 (Urb. Celina Muyurina)
(63, 10, NULL, 'Final',    'Prueba Técnica',      66, 68, 12, '2026-10-24', '09:30:00', NULL, NULL, 'Programado', 'Prueba Técnica de Acometidas de Agua Potable (Urb. Celina Muyurina)');

-- -----------------------------------------------------
-- 11. Nómina Oficial Inicial de Jugadores Habilitados
-- -----------------------------------------------------
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `estado`) VALUES
(1,  1,  '4321011', 'Juan Carlos',   'Vargas Pérez',    '1980-05-14', 10, 'Delantero',        'Habilitado'),
(2,  1,  '4321012', 'Marcos',        'Flores Roca',     '1979-08-22', 1,  'Arquero',          'Habilitado'),
(3,  1,  '4321013', 'Luis Alberto',  'Ríos Castro',     '1981-11-03', 4,  'Defensa Central',  'Habilitado'),
(4,  4,  '1234071', 'Jorge',         'Barrancos Soliz', '1980-10-15', 9,  'Centro Delantero', 'Habilitado'),
(5,  4,  '1234072', 'Carlos Alberto','Gutiérrez Paz',   '1978-07-20', 10, 'Mediocampista',    'Habilitado'),
(6,  14, '4321021', 'Johnny',       'Gonzales Barba',  '1975-03-12', 7,  'Armador',          'Habilitado'),
(7,  14, '4321022', 'Mariana',      'Suárez Roca',     '1985-09-18', 5,  'Rematadora',       'Habilitado'),
(8,  17, '1234081', 'Marco',        'Navas Rivera',    '1976-12-04', 8,  'Capitán',          'Habilitado');

-- Restaurar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
