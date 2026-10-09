-- =====================================================================
-- Esquema de Base de Datos Relacional: Cosmol-Deportes (Edición Olimpiadas)
-- Motor: MySQL / MariaDB (InnoDB)
-- Codificación: utf8mb4 / utf8mb4_unicode_ci
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `cosmol_deportes` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cosmol_deportes`;

-- Desactivar temporalmente verificación de claves foráneas para recreación limpia si aplica
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `medallero`;
DROP TABLE IF EXISTS `sanciones`;
DROP TABLE IF EXISTS `partido_incidencias`;
DROP TABLE IF EXISTS `partido_sets`;
DROP TABLE IF EXISTS `partidos`;
DROP TABLE IF EXISTS `canchas`;
DROP TABLE IF EXISTS `sedes`;
DROP TABLE IF EXISTS `jugadores`;
DROP TABLE IF EXISTS `equipos`;
DROP TABLE IF EXISTS `torneo_grupos`;
DROP TABLE IF EXISTS `torneos`;
DROP TABLE IF EXISTS `reglas_puntuacion`;
DROP TABLE IF EXISTS `disciplinas`;
DROP TABLE IF EXISTS `delegaciones`;
DROP TABLE IF EXISTS `olimpiadas`;
DROP TABLE IF EXISTS `usuarios`;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- 1. Tabla: usuarios
-- -----------------------------------------------------
CREATE TABLE `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('ADMIN', 'USUARIO') NOT NULL DEFAULT 'USUARIO',
  `estado` ENUM('Activo', 'Inactivo') NOT NULL DEFAULT 'Activo',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 2. Tabla: olimpiadas (Macro-Evento Deportivo)
-- -----------------------------------------------------
CREATE TABLE `olimpiadas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `anio` INT NOT NULL,
  `lema` VARCHAR(255) NULL,
  `fecha_inicio` DATE NULL,
  `fecha_fin` DATE NULL,
  `estado` ENUM('Planificada', 'En Curso', 'Finalizada') NOT NULL DEFAULT 'Planificada',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 3. Tabla: delegaciones (Áreas o Filiales de la Institución)
-- -----------------------------------------------------
CREATE TABLE `delegaciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `alias` VARCHAR(50) NULL,
  `color_representativo` VARCHAR(20) DEFAULT '#007bff',
  `logo_url` VARCHAR(255) NULL,
  `responsable` VARCHAR(150) NULL,
  `telefono` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 4. Tabla: disciplinas (Deportes disponibles)
-- -----------------------------------------------------
CREATE TABLE `disciplinas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL,
  `icono` VARCHAR(50) DEFAULT 'trophy',
  `estado` ENUM('Activa', 'Inactiva') NOT NULL DEFAULT 'Activa',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 5. Tabla: reglas_puntuacion (Parámetros de puntos por disciplina)
-- -----------------------------------------------------
CREATE TABLE `reglas_puntuacion` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `disciplina_id` INT NOT NULL,
  `pts_victoria` INT NOT NULL DEFAULT 3,
  `pts_empate` INT NOT NULL DEFAULT 1,
  `pts_derrota` INT NOT NULL DEFAULT 0,
  `pts_derrota_ajustada` INT NOT NULL DEFAULT 0,
  `permite_empate` TINYINT(1) NOT NULL DEFAULT 1,
  `formato_sets` TINYINT(1) NOT NULL DEFAULT 0,
  `sets_para_ganar` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_reglas_disciplina` (`disciplina_id`),
  CONSTRAINT `fk_reglas_disciplina`
    FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 6. Tabla: torneos (Competencia por disciplina y rama)
-- -----------------------------------------------------
CREATE TABLE `torneos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `olimpiada_id` INT NOT NULL,
  `disciplina_id` INT NOT NULL,
  `regla_puntuacion_id` INT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `rama` ENUM('Masculino', 'Femenino', 'Mixto') NOT NULL DEFAULT 'Masculino',
  `tipo_torneo` ENUM('Grupos_Playoff', 'Liga', 'Eliminatoria') NOT NULL DEFAULT 'Grupos_Playoff',
  `modalidad` ENUM('Solo_Ida', 'Ida_Vuelta') NOT NULL DEFAULT 'Solo_Ida',
  `estado` ENUM('Borrador', 'Inscripciones', 'Fase_Grupos', 'Playoffs', 'Finalizado') NOT NULL DEFAULT 'Borrador',
  `fecha_inicio` DATE NULL,
  `fecha_fin` DATE NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_torneos_olimpiada` (`olimpiada_id`),
  INDEX `idx_torneos_disciplina` (`disciplina_id`),
  CONSTRAINT `fk_torneos_olimpiada`
    FOREIGN KEY (`olimpiada_id`) REFERENCES `olimpiadas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_torneos_disciplina`
    FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_torneos_regla`
    FOREIGN KEY (`regla_puntuacion_id`) REFERENCES `reglas_puntuacion` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 7. Tabla: torneo_grupos (Fases de Grupos: Grupo A, Grupo B, etc.)
-- -----------------------------------------------------
CREATE TABLE `torneo_grupos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `torneo_id` INT NOT NULL,
  `nombre_grupo` VARCHAR(50) NOT NULL,
  `orden` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_grupos_torneo` (`torneo_id`),
  CONSTRAINT `fk_grupos_torneo`
    FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 8. Tabla: equipos (Equipos representantes por torneo y grupo)
-- -----------------------------------------------------
CREATE TABLE `equipos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `delegacion_id` INT NOT NULL,
  `torneo_id` INT NOT NULL,
  `grupo_id` INT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `nombre_corto` VARCHAR(50) NULL,
  `color_indumentaria` VARCHAR(100) NULL,
  `logo_url` VARCHAR(255) NULL,
  `delegado_contacto` VARCHAR(150) NULL,
  `telefono_contacto` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_equipos_delegacion` (`delegacion_id`),
  INDEX `idx_equipos_torneo` (`torneo_id`),
  INDEX `idx_equipos_grupo` (`grupo_id`),
  CONSTRAINT `fk_equipos_delegacion`
    FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_equipos_torneo`
    FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_equipos_grupo`
    FOREIGN KEY (`grupo_id`) REFERENCES `torneo_grupos` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 9. Tabla: jugadores (Nómina de atletas por equipo)
-- -----------------------------------------------------
CREATE TABLE `jugadores` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `equipo_id` INT NOT NULL,
  `documento` VARCHAR(30) NOT NULL,
  `nombres` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  `fecha_nacimiento` DATE NULL,
  `dorsal` INT NOT NULL,
  `posicion` VARCHAR(50) NULL,
  `foto_url` VARCHAR(255) NULL,
  `estado` ENUM('Habilitado', 'Suspendido', 'Inactivo') NOT NULL DEFAULT 'Habilitado',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_jugadores_equipo` (`equipo_id`),
  UNIQUE KEY `uq_equipo_dorsal` (`equipo_id`, `dorsal`),
  CONSTRAINT `fk_jugadores_equipo`
    FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 10. Tabla: sedes (Recintos deportivos)
-- -----------------------------------------------------
CREATE TABLE `sedes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `direccion` VARCHAR(255) NULL,
  `telefono` VARCHAR(50) NULL,
  `estado` ENUM('Activa', 'Inactiva') NOT NULL DEFAULT 'Activa',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 11. Tabla: canchas (Campos de juego por sede)
-- -----------------------------------------------------
CREATE TABLE `canchas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sede_id` INT NOT NULL,
  `nombre_cancha` VARCHAR(100) NOT NULL,
  `tipo_superficie` VARCHAR(100) NULL,
  `estado` ENUM('Disponible', 'Mantenimiento', 'Inactiva') NOT NULL DEFAULT 'Disponible',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_canchas_sede` (`sede_id`),
  CONSTRAINT `fk_canchas_sede`
    FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 12. Tabla: partidos (Encuentros y cronograma)
-- -----------------------------------------------------
CREATE TABLE `partidos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `torneo_id` INT NOT NULL,
  `grupo_id` INT NULL,
  `fase` ENUM('Grupos', 'Octavos', 'Cuartos', 'Semifinal', 'Tercer_Puesto', 'Final') NOT NULL DEFAULT 'Grupos',
  `jornada` VARCHAR(50) NOT NULL DEFAULT 'Jornada 1',
  `equipo_local_id` INT NOT NULL,
  `equipo_visitante_id` INT NOT NULL,
  `cancha_id` INT NULL,
  `fecha_partido` DATE NULL,
  `hora_partido` TIME NULL,
  `marcador_local` INT NULL,
  `marcador_visitante` INT NULL,
  `estado` ENUM('Programado', 'En_Juego', 'Finalizado', 'Suspendido', 'WO') NOT NULL DEFAULT 'Programado',
  `observaciones` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_partidos_torneo` (`torneo_id`),
  INDEX `idx_partidos_grupo` (`grupo_id`),
  INDEX `idx_partidos_local` (`equipo_local_id`),
  INDEX `idx_partidos_visitante` (`equipo_visitante_id`),
  INDEX `idx_partidos_cancha` (`cancha_id`),
  INDEX `idx_partidos_fecha_hora` (`fecha_partido`, `hora_partido`),
  CONSTRAINT `fk_partidos_torneo`
    FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_grupo`
    FOREIGN KEY (`grupo_id`) REFERENCES `torneo_grupos` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_local`
    FOREIGN KEY (`equipo_local_id`) REFERENCES `equipos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_visitante`
    FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_cancha`
    FOREIGN KEY (`cancha_id`) REFERENCES `canchas` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 13. Tabla: partido_sets (Desglose de sets/tiempos)
-- -----------------------------------------------------
CREATE TABLE `partido_sets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `partido_id` INT NOT NULL,
  `numero_set` INT NOT NULL,
  `puntos_local` INT NOT NULL DEFAULT 0,
  `puntos_visitante` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_sets_partido` (`partido_id`),
  CONSTRAINT `fk_sets_partido`
    FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 14. Tabla: partido_incidencias (Goles, puntos, tarjetas)
-- -----------------------------------------------------
CREATE TABLE `partido_incidencias` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `partido_id` INT NOT NULL,
  `equipo_id` INT NOT NULL,
  `jugador_id` INT NULL,
  `tipo` ENUM('GOL', 'PUNTO', 'AMARILLA', 'ROJA', 'FALTA') NOT NULL,
  `minuto_tiempo` INT NULL,
  `observaciones` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_incidencias_partido` (`partido_id`),
  INDEX `idx_incidencias_equipo` (`equipo_id`),
  INDEX `idx_incidencias_jugador` (`jugador_id`),
  CONSTRAINT `fk_incidencias_partido`
    FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_incidencias_equipo`
    FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_incidencias_jugador`
    FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 15. Tabla: sanciones (Expediente disciplinario y levantamiento)
-- -----------------------------------------------------
CREATE TABLE `sanciones` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `jugador_id` INT NOT NULL,
  `partido_id` INT NULL,
  `torneo_id` INT NOT NULL,
  `tipo_sancion` ENUM('AMARILLA', 'ROJA', 'PARTIDOS_SUSPENSION', 'EXPULSION') NOT NULL,
  `partidos_suspension` INT NOT NULL DEFAULT 1,
  `partidos_cumplidos` INT NOT NULL DEFAULT 0,
  `motivo` TEXT NOT NULL,
  `estado` ENUM('Activa', 'Cumplida', 'Levantada') NOT NULL DEFAULT 'Activa',
  `motivo_levantamiento` TEXT NULL,
  `levantado_por_usuario_id` INT NULL,
  `fecha_levantamiento` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_sanciones_jugador` (`jugador_id`),
  INDEX `idx_sanciones_torneo` (`torneo_id`),
  INDEX `idx_sanciones_estado` (`estado`),
  CONSTRAINT `fk_sanciones_jugador`
    FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sanciones_partido`
    FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_sanciones_torneo`
    FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sanciones_usuario`
    FOREIGN KEY (`levantado_por_usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- 16. Tabla: medallero (Medallero Olímpico Oficial)
-- -----------------------------------------------------
CREATE TABLE `medallero` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `olimpiada_id` INT NOT NULL,
  `delegacion_id` INT NOT NULL,
  `torneo_id` INT NOT NULL,
  `tipo_medalla` ENUM('ORO', 'PLATA', 'BRONCE') NOT NULL,
  `puntos_olimpicos` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_medallero_olimpiada` (`olimpiada_id`),
  INDEX `idx_medallero_delegacion` (`delegacion_id`),
  INDEX `idx_medallero_torneo` (`torneo_id`),
  UNIQUE KEY `uq_torneo_medalla` (`torneo_id`, `tipo_medalla`),
  CONSTRAINT `fk_medallero_olimpiada`
    FOREIGN KEY (`olimpiada_id`) REFERENCES `olimpiadas` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_medallero_delegacion`
    FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_medallero_torneo`
    FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
