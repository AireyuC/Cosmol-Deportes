-- Backup automatico generado antes de cargar ANESAPA 2026
-- Fecha: 2026-10-15 15:26:41

SET FOREIGN_KEY_CHECKS = 0;

-- Tabla: `canchas`
INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`, `created_at`) VALUES ('1', '1', 'Cancha Principal de Césped Natural', 'Césped Natural', 'Disponible', '2026-10-12 10:47:01');
INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`, `created_at`) VALUES ('2', '1', 'Cancha Sintética de Futsal', 'Césped Sintético', 'Disponible', '2026-10-12 10:47:01');
INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`, `created_at`) VALUES ('3', '2', 'Coliseo Polideportivo Principal', 'Parquet Flotante', 'Disponible', '2026-10-12 10:47:01');
INSERT INTO `canchas` (`id`, `sede_id`, `nombre_cancha`, `tipo_superficie`, `estado`, `created_at`) VALUES ('4', '2', 'Cancha Auxiliar de Voleibol', 'Cemento Pulido', 'Disponible', '2026-10-12 10:47:01');

-- Tabla: `delegaciones`
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `logo_url`, `responsable`, `telefono`, `created_at`) VALUES ('1', 'Gerencia Comercial', 'Comercial', '#007bff', NULL, 'Carlos Mendoza', '70011223', '2026-10-12 10:47:01');
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `logo_url`, `responsable`, `telefono`, `created_at`) VALUES ('2', 'Planta Operaciones', 'Operaciones', '#28a745', NULL, 'Roberto Silva', '70044556', '2026-10-12 10:47:01');
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `logo_url`, `responsable`, `telefono`, `created_at`) VALUES ('3', 'Tecnología & Redes', 'Tecnología', '#6f42c1', NULL, 'Daniel Ramos', '70077889', '2026-10-12 10:47:01');
INSERT INTO `delegaciones` (`id`, `nombre`, `alias`, `color_representativo`, `logo_url`, `responsable`, `telefono`, `created_at`) VALUES ('4', 'Rubro', '', '#007bff', NULL, '', '', '2026-10-12 10:47:01');

-- Tabla: `disciplinas`
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`, `created_at`) VALUES ('1', 'Fútbol', 'Fútbol 11 reglamentario sobre césped', 'futbol', 'Activa', '2026-10-12 10:47:01');
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`, `created_at`) VALUES ('2', 'Futsal', 'Fútbol de salón en coliseo techado', 'running', 'Activa', '2026-10-12 10:47:01');
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`, `created_at`) VALUES ('3', 'Básquetbol', 'Básquetbol 5x5 oficial', 'basketball-ball', 'Activa', '2026-10-12 10:47:01');
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`, `created_at`) VALUES ('4', 'Voleibol', 'Voleibol 6x6 en coliseo', 'volleyball-ball', 'Activa', '2026-10-12 10:47:01');
INSERT INTO `disciplinas` (`id`, `nombre`, `descripcion`, `icono`, `estado`, `created_at`) VALUES ('5', 'Karaoke', 'cantar', 'trophy', 'Activa', '2026-10-13 10:06:47');

-- Tabla: `equipos`
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('1', '1', '1', '1', 'Comercial FC', 'Comercial', 'Azul y Blanco', NULL, 'Carlos Mendoza', '70011223', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('2', '2', '1', '1', 'Operaciones United', 'Operaciones', 'Verde y Negro', NULL, 'Roberto Silva', '70044556', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('3', '3', '1', '2', 'Tecnología Sporting', 'Tecnología', 'Morado y Blanco', NULL, 'Daniel Ramos', '70077889', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('4', '4', '1', '2', 'Administración Real', 'Administración', 'Naranja y Negro', NULL, 'Lucia Fernández', '70099001', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('5', '1', '2', '3', 'Comercial Voley', 'Comercial Voley', 'Azul', NULL, 'Karen Ortiz', '70011444', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('6', '2', '2', '3', 'Operaciones Voley', 'Operaciones Voley', 'Verde', NULL, 'Mario Justiniano', '70022555', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('7', '3', '2', '4', 'Tecno Spikes', 'Tecno Spikes', 'Morado', NULL, 'Paola Méndez', '70033666', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('8', '4', '2', '4', 'Admin Voley', 'Admin Voley', 'Naranja', NULL, 'Jorge Suárez', '70044777', '2026-10-12 10:47:01');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('9', '3', '1', NULL, 'Pasantes FC', 'PAS', 'Rojo y Azul', NULL, 'Chichico', '77651849', '2026-10-12 16:52:57');
INSERT INTO `equipos` (`id`, `delegacion_id`, `torneo_id`, `grupo_id`, `nombre`, `nombre_corto`, `color_indumentaria`, `logo_url`, `delegado_contacto`, `telefono_contacto`, `created_at`) VALUES ('10', '4', '3', '5', 'Cantantes Ofi', '', '', NULL, '', '', '2026-10-13 10:31:00');

-- Tabla: `jugadores`
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('1', '1', '5432101', 'Juan Carlos', 'Vargas Pérez', '1992-05-14', '10', 'Delantero', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('2', '1', '5432102', 'Marcos', 'Flores Roca', '1994-08-22', '1', 'Arquero', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('3', '1', '5432103', 'Luis Alberto', 'Rios Castro', '1990-11-03', '4', 'Defensa Central', NULL, 'Suspendido', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('4', '2', '6543201', 'Gustavo', 'Medina Chávez', '1993-02-18', '9', 'Delantero', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('5', '2', '6543202', 'Andrés', 'Gutiérrez Paz', '1995-07-09', '5', 'Mediocampista', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('6', '3', '7654301', 'Mauricio', 'Aguilera Rojas', '1996-03-30', '7', 'Extremo', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('7', '3', '7654302', 'David', 'Mamani Soliz', '1991-09-12', '10', 'Enganche', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('8', '4', '8765401', 'Felipe', 'Salazar Pinto', '1994-12-05', '8', 'Volante', NULL, 'Habilitado', '2026-10-12 10:47:01');
INSERT INTO `jugadores` (`id`, `equipo_id`, `documento`, `nombres`, `apellidos`, `fecha_nacimiento`, `dorsal`, `posicion`, `foto_url`, `estado`, `created_at`) VALUES ('9', '4', '8765402', 'Ignacio', 'Céspedes Barba', '1993-04-17', '2', 'Lateral', NULL, 'Habilitado', '2026-10-12 10:47:01');

-- Tabla: `medallero`
INSERT INTO `medallero` (`id`, `olimpiada_id`, `delegacion_id`, `torneo_id`, `tipo_medalla`, `puntos_olimpicos`, `created_at`) VALUES ('1', '1', '1', '1', 'ORO', '10', '2026-10-12 10:47:01');
INSERT INTO `medallero` (`id`, `olimpiada_id`, `delegacion_id`, `torneo_id`, `tipo_medalla`, `puntos_olimpicos`, `created_at`) VALUES ('2', '1', '2', '1', 'PLATA', '7', '2026-10-12 10:47:01');

-- Tabla: `olimpiadas`
INSERT INTO `olimpiadas` (`id`, `nombre`, `anio`, `lema`, `fecha_inicio`, `fecha_fin`, `estado`, `created_at`) VALUES ('1', 'Olimpiadas Cosmol 2026', '2026', 'Unidad, Deporte e Integración Institucional', '2026-10-01', '2026-11-15', 'En Curso', '2026-10-12 10:47:01');

-- Tabla: `partido_incidencias`
INSERT INTO `partido_incidencias` (`id`, `partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`, `created_at`) VALUES ('1', '1', '1', '1', 'GOL', '15', 'Gol de jugada colectiva', '2026-10-12 10:47:01');
INSERT INTO `partido_incidencias` (`id`, `partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`, `created_at`) VALUES ('2', '1', '1', '1', 'GOL', '42', 'Remate de fuera del área', '2026-10-12 10:47:01');
INSERT INTO `partido_incidencias` (`id`, `partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`, `created_at`) VALUES ('3', '1', '2', '4', 'GOL', '65', 'Descuento de cabeza', '2026-10-12 10:47:01');
INSERT INTO `partido_incidencias` (`id`, `partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`, `created_at`) VALUES ('4', '1', '1', '2', 'GOL', '88', 'Gol de contragolpe', '2026-10-12 10:47:01');
INSERT INTO `partido_incidencias` (`id`, `partido_id`, `equipo_id`, `jugador_id`, `tipo`, `minuto_tiempo`, `observaciones`, `created_at`) VALUES ('5', '1', '1', '3', 'ROJA', '70', 'Falta de último recurso', '2026-10-12 10:47:01');

-- Tabla: `partidos`
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`, `created_at`) VALUES ('1', '1', '1', 'Grupos', 'Jornada 1', '1', '2', '1', '2026-10-08', '19:00:00', '3', '1', 'Finalizado', NULL, '2026-10-12 10:47:01');
INSERT INTO `partidos` (`id`, `torneo_id`, `grupo_id`, `fase`, `jornada`, `equipo_local_id`, `equipo_visitante_id`, `cancha_id`, `fecha_partido`, `hora_partido`, `marcador_local`, `marcador_visitante`, `estado`, `observaciones`, `created_at`) VALUES ('2', '1', '2', 'Grupos', 'Jornada 1', '3', '4', '1', '2026-10-14', '20:30:00', NULL, NULL, 'Programado', '', '2026-10-12 10:47:01');

-- Tabla: `reglas_puntuacion`
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`, `created_at`) VALUES ('1', '1', '3', '1', '0', '0', '1', '0', '0', '2026-10-12 10:47:01');
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`, `created_at`) VALUES ('2', '2', '3', '1', '0', '0', '1', '0', '0', '2026-10-12 10:47:01');
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`, `created_at`) VALUES ('3', '3', '2', '0', '1', '0', '0', '0', '0', '2026-10-12 10:47:01');
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`, `created_at`) VALUES ('4', '4', '3', '0', '0', '1', '0', '1', '3', '2026-10-12 10:47:01');
INSERT INTO `reglas_puntuacion` (`id`, `disciplina_id`, `pts_victoria`, `pts_empate`, `pts_derrota`, `pts_derrota_ajustada`, `permite_empate`, `formato_sets`, `sets_para_ganar`, `created_at`) VALUES ('5', '5', '3', '1', '0', '0', '0', '0', '1', '2026-10-13 10:06:48');

-- Tabla: `sanciones`
INSERT INTO `sanciones` (`id`, `jugador_id`, `partido_id`, `torneo_id`, `tipo_sancion`, `partidos_suspension`, `partidos_cumplidos`, `motivo`, `estado`, `motivo_levantamiento`, `levantado_por_usuario_id`, `fecha_levantamiento`, `created_at`) VALUES ('1', '3', '1', '1', 'ROJA', '1', '0', 'Expulsión con roja directa por cortar opción manifiesta de gol en el min 70', 'Activa', NULL, NULL, NULL, '2026-10-12 10:47:01');
INSERT INTO `sanciones` (`id`, `jugador_id`, `partido_id`, `torneo_id`, `tipo_sancion`, `partidos_suspension`, `partidos_cumplidos`, `motivo`, `estado`, `motivo_levantamiento`, `levantado_por_usuario_id`, `fecha_levantamiento`, `created_at`) VALUES ('2', '1', '1', '1', 'AMARILLA', '0', '0', 'Amonestación por reclamo', 'Levantada', 'Error arbitral corregido: la falta fue cometida por otro jugador (apelación aprobada)', '1', '2026-10-09 10:30:00', '2026-10-12 10:47:01');

-- Tabla: `sedes`
INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `latitud`, `longitud`, `estado`, `created_at`) VALUES ('1', 'Complejo Deportivo Central Cosmol', 'Av. Principal #450', '33445566', '-17.78330000', '-63.18210000', 'Activa', '2026-10-12 10:47:01');
INSERT INTO `sedes` (`id`, `nombre`, `direccion`, `telefono`, `latitud`, `longitud`, `estado`, `created_at`) VALUES ('2', 'Coliseo Polideportivo Municipal', 'Calle Bolívar esq. Sucre', '33221100', '-17.78620000', '-63.18120000', 'Activa', '2026-10-12 10:47:01');

-- Tabla: `torneo_grupos`
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`, `created_at`) VALUES ('1', '1', 'Grupo A', '1', '2026-10-12 10:47:01');
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`, `created_at`) VALUES ('2', '1', 'Grupo B', '2', '2026-10-12 10:47:01');
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`, `created_at`) VALUES ('3', '2', 'Grupo A', '1', '2026-10-12 10:47:01');
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`, `created_at`) VALUES ('4', '2', 'Grupo B', '2', '2026-10-12 10:47:01');
INSERT INTO `torneo_grupos` (`id`, `torneo_id`, `nombre_grupo`, `orden`, `created_at`) VALUES ('5', '3', 'Grupo A', '1', '2026-10-13 10:22:37');

-- Tabla: `torneos`
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`, `created_at`) VALUES ('1', '1', '1', '1', 'Fútbol Varones - Olimpiadas 2026', 'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-05', '2026-11-10', '2026-10-12 10:47:01');
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`, `created_at`) VALUES ('2', '1', '4', '4', 'Voleibol Mixto - Olimpiadas 2026', 'Mixto', 'Grupos_Playoff', 'Solo_Ida', 'Fase_Grupos', '2026-10-06', '2026-11-12', '2026-10-12 10:47:01');
INSERT INTO `torneos` (`id`, `olimpiada_id`, `disciplina_id`, `regla_puntuacion_id`, `nombre`, `rama`, `tipo_torneo`, `modalidad`, `estado`, `fecha_inicio`, `fecha_fin`, `created_at`) VALUES ('3', '1', '5', '5', 'Karaoke', 'Masculino', 'Grupos_Playoff', 'Solo_Ida', 'Borrador', NULL, NULL, '2026-10-13 10:22:37');

-- Tabla: `usuarios`
INSERT INTO `usuarios` (`id`, `nombre`, `username`, `email`, `password`, `rol`, `estado`, `created_at`, `updated_at`) VALUES ('1', 'Administrador General', 'admin', 'admin@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 'Activo', '2026-10-12 10:47:01', '2026-10-12 10:47:01');
INSERT INTO `usuarios` (`id`, `nombre`, `username`, `email`, `password`, `rol`, `estado`, `created_at`, `updated_at`) VALUES ('2', 'Delegado Comercial', 'usuario', 'usuario@cosmol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'USUARIO', 'Activo', '2026-10-12 10:47:01', '2026-10-12 10:47:01');

SET FOREIGN_KEY_CHECKS = 1;
