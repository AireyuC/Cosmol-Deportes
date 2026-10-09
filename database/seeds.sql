-- =====================================================================
-- Datos Semilla (Seeds): Cosmol-Deportes (Edición Olimpiadas)
-- Credenciales por defecto:
--   Administrador: admin@cosmol.com / admin123
--   Usuario:       usuario@cosmol.com / usuario123
-- =====================================================================

USE `cosmol_deportes`;

-- -----------------------------------------------------
-- 1. Usuarios Iniciales
-- Hash bcrypt para 'admin123': $2y$10$wT8m9MvFh6LhLdZ7L8G8m.YqV9x6o1c8v3w8qM4u1w2e3r4t5y6u7
-- Hash bcrypt para 'password': $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- -----------------------------------------------------
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `estado`) VALUES
(1, 'Administrador General', 'admin@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'Activo'),
(2, 'Delegado Comercial', 'usuario@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USUARIO', 'Activo');

-- -----------------------------------------------------
-- 2. Macro-Evento: Olimpiada
-- -----------------------------------------------------
INSERT INTO `olimpiadas` (`id`, `nombre`, `anio`, `lema`, `fecha_inicio`, `fecha_fin`, `estado`) VALUES
(1, 'Olimpiadas Cosmol 2026', 2026, 'Unidad, Deporte e Integración Institucional', '2026-10-01', '2026-11-15', 'En Curso');

-- -----------------------------------------------------
-- 3. Delegaciones Institucionales
-- -----------------------------------------------------
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `responsable`, `telefono`) VALUES
(1, 'Gerencia Comercial', 'Comercial', '#007bff', 'Carlos Mendoza', '70011223'),
(2, 'Planta Operaciones', 'Operaciones', '#28a745', 'Roberto Silva', '70044556'),
(3, 'Tecnología & Redes', 'Tecnología', '#6f42c1', 'Daniel Ramos', '70077889'),
(4, 'Administración & Finanzas', 'Administración', '#fd7e14', 'Lucia Fernández', '70099001');

-- -----------------------------------------------------
-- 4. Disciplinas Deportivas
-- -----------------------------------------------------
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`) VALUES
(1, 'Fútbol', 'Fútbol 11 reglamentario sobre césped', 'futbol', 'Activa'),
(2, 'Futsal', 'Fútbol de salón en coliseo techado', 'running', 'Activa'),
(3, 'Básquetbol', 'Básquetbol 5x5 oficial', 'basketball-ball', 'Activa'),
(4, 'Voleibol', 'Voleibol 6x6 en coliseo', 'volleyball-ball', 'Activa');

-- -----------------------------------------------------
-- 5. Reglas de Puntuación por Disciplina
-- -----------------------------------------------------
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`) VALUES
(1, 1, 3, 1, 0, 0, 1, 0, 0), -- Fútbol: Vic=3, Emp=1, Der=0
(2, 2, 3, 1, 0, 0, 1, 0, 0), -- Futsal: Vic=3, Emp=1, Der=0
(3, 3, 2, 0, 1, 0, 0, 0, 0), -- Básquet: Vic=2, Der=1 (por presentación), WO=0
(4, 4, 3, 0, 0, 1, 0, 1, 3); -- Vóley: Vic 3-0/3-1 = 3pts, Vic 3-2 = 2pts, Der 2-3 = 1pt

-- -----------------------------------------------------
-- 6. Sedes y Canchas
-- -----------------------------------------------------
INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `estado`) VALUES
(1, 'Complejo Deportivo Central Cosmol', 'Av. Principal #450', '33445566', 'Activa'),
(2, 'Coliseo Polideportivo Municipal', 'Calle Bolívar esq. Sucre', '33221100', 'Activa');

INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`) VALUES
(1, 1, 'Cancha Principal de Césped Natural', 'Césped Natural', 'Disponible'),
(2, 1, 'Cancha Sintética de Futsal', 'Césped Sintético', 'Disponible'),
(3, 2, 'Coliseo Polideportivo Principal', 'Parquet Flotante', 'Disponible'),
(4, 2, 'Cancha Auxiliar de Voleibol', 'Cemento Pulido', 'Disponible');

-- -----------------------------------------------------
-- 7. Torneos Activos en la Olimpiada
-- -----------------------------------------------------
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 1, 1, 1, 'Fútbol Varones - Olimpiadas 2026', 'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-05', '2026-11-10'),
(2, 1, 4, 4, 'Voleibol Mixto - Olimpiadas 2026', 'Mixto', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-06', '2026-11-12');

-- -----------------------------------------------------
-- 8. Grupos por Torneo
-- -----------------------------------------------------
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`) VALUES
(1, 1, 'Grupo A', 1),
(2, 1, 'Grupo B', 2),
(3, 2, 'Grupo A', 1),
(4, 2, 'Grupo B', 2);

-- -----------------------------------------------------
-- 9. Equipos Registrados por Torneo y Grupo
-- -----------------------------------------------------
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `delegado_contacto`, `telefono_contacto`) VALUES
-- Fútbol Varones: Grupo A
(1, 1, 1, 1, 'Comercial FC', 'Comercial', 'Azul y Blanco', 'Carlos Mendoza', '70011223'),
(2, 2, 1, 1, 'Operaciones United', 'Operaciones', 'Verde y Negro', 'Roberto Silva', '70044556'),
-- Fútbol Varones: Grupo B
(3, 3, 1, 2, 'Tecnología Sporting', 'Tecnología', 'Morado y Blanco', 'Daniel Ramos', '70077889'),
(4, 4, 1, 2, 'Administración Real', 'Administración', 'Naranja y Negro', 'Lucia Fernández', '70099001'),
-- Voleibol Mixto: Grupo A
(5, 1, 2, 3, 'Comercial Voley', 'Comercial Voley', 'Azul', 'Karen Ortiz', '70011444'),
(6, 2, 2, 3, 'Operaciones Voley', 'Operaciones Voley', 'Verde', 'Mario Justiniano', '70022555'),
-- Voleibol Mixto: Grupo B
(7, 3, 2, 4, 'Tecno Spikes', 'Tecno Spikes', 'Morado', 'Paola Méndez', '70033666'),
(8, 4, 2, 4, 'Admin Voley', 'Admin Voley', 'Naranja', 'Jorge Suárez', '70044777');

-- -----------------------------------------------------
-- 10. Nómina de Jugadores de Ejemplo
-- -----------------------------------------------------
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `estado`) VALUES
-- Comercial FC
(1, 1, '5432101', 'Juan Carlos', 'Vargas Pérez', '1992-05-14', 10, 'Delantero', 'Habilitado'),
(2, 1, '5432102', 'Marcos', 'Flores Roca', '1994-08-22', 1, 'Arquero', 'Habilitado'),
(3, 1, '5432103', 'Luis Alberto', 'Rios Castro', '1990-11-03', 4, 'Defensa Central', 'Suspendido'),
-- Operaciones United
(4, 2, '6543201', 'Gustavo', 'Medina Chávez', '1993-02-18', 9, 'Delantero', 'Habilitado'),
(5, 2, '6543202', 'Andrés', 'Gutiérrez Paz', '1995-07-09', 5, 'Mediocampista', 'Habilitado'),
-- Tecnología Sporting
(6, 3, '7654301', 'Mauricio', 'Aguilera Rojas', '1996-03-30', 7, 'Extremo', 'Habilitado'),
(7, 3, '7654302', 'David', 'Mamani Soliz', '1991-09-12', 10, 'Enganche', 'Habilitado'),
-- Administración Real
(8, 4, '8765401', 'Felipe', 'Salazar Pinto', '1994-12-05', 8, 'Volante', 'Habilitado'),
(9, 4, '8765402', 'Ignacio', 'Céspedes Barba', '1993-04-17', 2, 'Lateral', 'Habilitado');

-- -----------------------------------------------------
-- 11. Partidos Iniciales
-- -----------------------------------------------------
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`) VALUES
-- Partido finalizado
(1, 1, 1, 'Grupos', 'Jornada 1', 1, 2, 1, '2026-10-08', '19:00:00', 3, 1, 'Finalizado'),
-- Partido programado para hoy/próximo
(2, 1, 2, 'Grupos', 'Jornada 1', 3, 4, 1, '2026-10-12', '20:30:00', NULL, NULL, 'Programado');

-- Incidencias del partido finalizado (Goles y tarjetas)
INSERT INTO `partido_incidencias` (`partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`) VALUES
(1, 1, 1, 'GOL', 15, 'Gol de jugada colectiva'),
(1, 1, 1, 'GOL', 42, 'Remate de fuera del área'),
(1, 2, 4, 'GOL', 65, 'Descuento de cabeza'),
(1, 1, 2, 'GOL', 88, 'Gol de contragolpe'),
(1, 1, 3, 'ROJA', 70, 'Falta de último recurso');

-- -----------------------------------------------------
-- 12. Sanciones Disciplinarias (Demostración de Activa y Levantada)
-- -----------------------------------------------------
INSERT INTO `sanciones` (`id`, `jugador_id`, `partido_id`, `torneo_id`, `tipo_sancion`, `partidos_suspension`, `partidos_cumplidos`, `motivo`, `estado`, `motivo_levantamiento`, `levantado_por_usuario_id`, `fecha_levantamiento`) VALUES
-- Sanción 1: Activa (Expulsión de Luis Alberto Rios)
(1, 3, 1, 1, 'ROJA', 1, 0, 'Expulsión con roja directa por cortar opción manifiesta de gol en el min 70', 'Activa', NULL, NULL, NULL),
-- Sanción 2: Levantada por error arbitral (Demostración de función Quitar Sanción)
(2, 1, 1, 1, 'AMARILLA', 0, 0, 'Amonestación por reclamo', 'Levantada', 'Error arbitral corregido: la falta fue cometida por otro jugador (apelación aprobada)', 1, '2026-10-09 10:30:00');

-- -----------------------------------------------------
-- 13. Medallero Olímpico Inicial
-- -----------------------------------------------------
INSERT INTO `medallero` (`olimpiada_id`, `delegacion_id`, `torneo_id`, `tipo_medalla`, `puntos_olimpicos`) VALUES
(1, 1, 1, 'ORO', 10),
(1, 2, 1, 'PLATA', 7);
