-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: cosmol_deportes
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `cosmol_deportes`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `cosmol_deportes` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `cosmol_deportes`;

--
-- Table structure for table `canchas`
--

DROP TABLE IF EXISTS `canchas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `canchas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sede_id` int(11) NOT NULL,
  `nombre_cancha` varchar(100) NOT NULL,
  `tipo_superficie` varchar(100) DEFAULT NULL,
  `estado` enum('Disponible','Mantenimiento','Inactiva') NOT NULL DEFAULT 'Disponible',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_canchas_sede` (`sede_id`),
  CONSTRAINT `fk_canchas_sede` FOREIGN KEY (`sede_id`) REFERENCES `sedes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canchas`
--

LOCK TABLES `canchas` WRITE;
/*!40000 ALTER TABLE `canchas` DISABLE KEYS */;
INSERT INTO `canchas` VALUES (1,1,'Cancha Principal Fútbol 11 (Césped Natural','Cesped Natural','Disponible','2026-10-20 13:33:52'),(2,1,'Coliseo Cerrado COSMOL','Parquet / Tabloncillo','Disponible','2026-10-20 13:38:48'),(3,1,'Frontón Olímpico COSMOL','Cemento','Disponible','2026-10-20 13:39:32'),(4,2,'Salón de Honor (Billar, Cacho y Karaoke)','Salón Cubierto','Disponible','2026-10-20 13:41:46');
/*!40000 ALTER TABLE `canchas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delegaciones`
--

DROP TABLE IF EXISTS `delegaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delegaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `alias` varchar(50) DEFAULT NULL,
  `color_representativo` varchar(20) DEFAULT '#007bff',
  `logo_url` varchar(255) DEFAULT NULL,
  `responsable` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delegaciones`
--

LOCK TABLES `delegaciones` WRITE;
/*!40000 ALTER TABLE `delegaciones` DISABLE KEYS */;
INSERT INTO `delegaciones` VALUES (1,'COSMOL (MO)','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-20 22:20:12'),(2,'ELAPAS (SU)','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-20 22:33:31'),(3,'SAGUAPAC (SC)','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-20 22:33:31'),(4,'SEMAPA (CB)','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-20 22:33:31'),(5,'SAMAPA (LP)','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-20 22:33:31'),(6,'COSSALT (TR)','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-20 22:33:31'),(7,'EMAPYC (YC)','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-20 22:33:31'),(8,'SELA (OR)','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-20 22:33:31');
/*!40000 ALTER TABLE `delegaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(50) DEFAULT 'trophy',
  `estado` enum('Activa','Inactiva') NOT NULL DEFAULT 'Activa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinas`
--

LOCK TABLES `disciplinas` WRITE;
/*!40000 ALTER TABLE `disciplinas` DISABLE KEYS */;
INSERT INTO `disciplinas` VALUES (1,'Fútbol Once','2 tiempos de 45 min. Cancha de césped. Exigencia reglamentaria: 3 jugadores en cancha >= 45 años.','futbol','Activa','2026-10-21 02:06:56'),(2,'Fútbol de Salón','2 tiempos de 20 min. Cancha cubierta. Exigencia reglamentaria: 1 jugador en cancha >= 45 años.','futbol','Activa','2026-10-21 02:06:56'),(3,'Voleibol Mixto','Partidos al mejor de 3 sets (25 pts). Exigencia reglamentaria: 2 damas y 1 veterano >= 50 años obligatorios en cancha.','volleyball-ball','Activa','2026-10-21 02:06:56'),(4,'Básquetbol Mixto','4 cuartos reglamentarios. Exigencia reglamentaria: 1 dama y 1 veterano >= 50 años obligatorios en cancha.','basketball-ball','Activa','2026-10-21 02:06:56'),(5,'Raqueta Frontón','Modalidad pareja (2 jugadores). Sets a 15 puntos (al mejor de 3 sets). Normas oficiales de frontón.','table-tennis','Activa','2026-10-21 02:06:56'),(6,'Media Maratón','Carrera pedestre urbana de 5 kilómetros. Hasta 2 representantes por delegación. Orden de llegada a meta.','running','Activa','2026-10-21 02:06:56'),(7,'Cacho','Juego de dados por parejas (exigencia: al menos 1 directivo o ejecutivo). Anotación en planilla Alalay.','dice','Activa','2026-10-21 02:06:56'),(8,'Karaoke','Noche de talentos. Hasta 2 representantes por delegación. Calificación global de jurado (escala 1 a 100).','microphone-alt','Activa','2026-10-21 02:06:56'),(9,'Billar','Modalidad pareja. Exigencia: al menos 1 directivo o ejecutivo. Partidas por chicos ganados.','circle','Activa','2026-10-21 02:06:56'),(10,'Competencias Técnicas','Instalación de acometida de agua potable matriz a llave de paso. 3 operarios por EPSA. Evaluación por tiempo y estanqueidad.','tools','Activa','2026-10-21 02:06:56'),(11,'Juegos Populares','3 pruebas recreativas independientes: Carrera de embolsao, Carrera de cuchara con huevo y Carrera de carretilla.','smile','Activa','2026-10-21 02:06:56');
/*!40000 ALTER TABLE `disciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `delegacion_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `nombre_corto` varchar(50) DEFAULT NULL,
  `color_indumentaria` varchar(100) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `delegado_contacto` varchar(150) DEFAULT NULL,
  `telefono_contacto` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_equipos_delegacion` (`delegacion_id`),
  KEY `idx_equipos_torneo` (`torneo_id`),
  KEY `idx_equipos_grupo` (`grupo_id`),
  CONSTRAINT `fk_equipos_delegacion` FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_equipos_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `torneo_grupos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_equipos_torneo` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
INSERT INTO `equipos` VALUES (1,1,1,1,'COSMOL - Fútbol Once','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(2,3,1,1,'SAGUAPAC - Fútbol Once','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(3,4,1,1,'SEMAPA - Fútbol Once','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(4,2,1,2,'ELAPAS - Fútbol Once','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(5,8,1,2,'SELA - Fútbol Once','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(6,7,1,2,'EMAPYC - Fútbol Once','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(7,6,1,2,'COSSALT - Fútbol Once','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(8,1,2,3,'COSMOL - Futsal','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(9,3,2,3,'SAGUAPAC - Futsal','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(10,4,2,3,'SEMAPA - Futsal','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(11,2,2,4,'ELAPAS - Futsal','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(12,8,2,4,'SELA - Futsal','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(13,6,2,4,'COSSALT - Futsal','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(14,1,3,5,'COSMOL - Voleibol Mixto','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(15,3,3,5,'SAGUAPAC - Voleibol Mixto','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(16,4,3,5,'SEMAPA - Voleibol Mixto','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(17,2,3,6,'ELAPAS - Voleibol Mixto','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(18,8,3,6,'SELA - Voleibol Mixto','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(19,7,3,6,'EMAPYC - Voleibol Mixto','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(20,6,3,6,'COSSALT - Voleibol Mixto','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(21,1,4,7,'COSMOL - Básquetbol Mixto','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(22,3,4,7,'SAGUAPAC - Básquetbol Mixto','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(23,4,4,7,'SEMAPA - Básquetbol Mixto','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(24,2,4,8,'ELAPAS - Básquetbol Mixto','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(25,8,4,8,'SELA - Básquetbol Mixto','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(26,7,4,8,'EMAPYC - Básquetbol Mixto','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(27,6,4,8,'COSSALT - Básquetbol Mixto','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(28,1,5,9,'COSMOL - Raqueta Frontón','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(29,4,5,9,'SEMAPA - Raqueta Frontón','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(30,8,5,9,'SELA - Raqueta Frontón','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(31,2,5,10,'ELAPAS - Raqueta Frontón','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(32,7,5,10,'EMAPYC - Raqueta Frontón','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(33,6,5,10,'COSSALT - Raqueta Frontón','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(34,1,6,NULL,'COSMOL - Media Maratón','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(35,2,6,NULL,'ELAPAS - Media Maratón','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(36,3,6,NULL,'SAGUAPAC - Media Maratón','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(37,4,6,NULL,'SEMAPA - Media Maratón','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(38,5,6,NULL,'SAMAPA - Media Maratón','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-21 04:30:10'),(39,6,6,NULL,'COSSALT - Media Maratón','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(40,7,6,NULL,'EMAPYC - Media Maratón','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(41,8,6,NULL,'SELA - Media Maratón','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(42,1,7,NULL,'COSMOL - Cacho','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(43,8,7,NULL,'SELA - Cacho','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(44,3,7,NULL,'SAGUAPAC - Cacho','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(45,7,7,NULL,'EMAPYC - Cacho','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(46,2,7,NULL,'ELAPAS - Cacho','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(47,6,7,NULL,'COSSALT - Cacho','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(48,4,7,NULL,'SEMAPA - Cacho','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(49,5,7,NULL,'SAMAPA - Cacho','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-21 04:30:10'),(50,1,8,NULL,'COSMOL - Karaoke','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(51,2,8,NULL,'ELAPAS - Karaoke','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(52,3,8,NULL,'SAGUAPAC - Karaoke','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(53,4,8,NULL,'SEMAPA - Karaoke','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(54,5,8,NULL,'SAMAPA - Karaoke','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-21 04:30:10'),(55,6,8,NULL,'COSSALT - Karaoke','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(56,7,8,NULL,'EMAPYC - Karaoke','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(57,8,8,NULL,'SELA - Karaoke','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(58,1,9,NULL,'COSMOL - Billar','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(59,2,9,NULL,'ELAPAS - Billar','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(60,3,9,NULL,'SAGUAPAC - Billar','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(61,4,9,NULL,'SEMAPA - Billar','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(62,5,9,NULL,'SAMAPA - Billar','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-21 04:30:10'),(63,6,9,NULL,'COSSALT - Billar','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(64,7,9,NULL,'EMAPYC - Billar','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(65,8,9,NULL,'SELA - Billar','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(66,1,10,NULL,'COSMOL - Fontanería','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(67,2,10,NULL,'ELAPAS - Fontanería','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(68,3,10,NULL,'SAGUAPAC - Fontanería','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(69,4,10,NULL,'SEMAPA - Fontanería','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(70,5,10,NULL,'SAMAPA - Fontanería','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-21 04:30:10'),(71,6,10,NULL,'COSSALT - Fontanería','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(72,7,10,NULL,'EMAPYC - Fontanería','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(73,8,10,NULL,'SELA - Fontanería','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10'),(74,1,11,NULL,'COSMOL - Juegos Populares','COSMOL','#0066B2',NULL,'Johnny Gonzales','','2026-10-21 04:30:10'),(75,2,11,NULL,'ELAPAS - Juegos Populares','ELAPAS','#6F42C1',NULL,'Marco Navas','','2026-10-21 04:30:10'),(76,3,11,NULL,'SAGUAPAC - Juegos Populares','SAGUAPAC','#28A745',NULL,'Mauricio','','2026-10-21 04:30:10'),(77,4,11,NULL,'SEMAPA - Juegos Populares','SEMAPA','#DC3545',NULL,'Erlan Arandia','','2026-10-21 04:30:10'),(78,5,11,NULL,'SAMAPA - Juegos Populares','SAMAPA','#00A3E0',NULL,'Walter Salazar','','2026-10-21 04:30:10'),(79,6,11,NULL,'COSSALT - Juegos Populares','COSSALT','#FD7E14',NULL,'Rodrigo Lopez','','2026-10-21 04:30:10'),(80,7,11,NULL,'EMAPYC - Juegos Populares','EMAPYC','#20C997',NULL,'Freddy Valdez','','2026-10-21 04:30:10'),(81,8,11,NULL,'SELA - Juegos Populares','SELA','#FFC107',NULL,'Erlan Vasquez','','2026-10-21 04:30:10');
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jugadores`
--

DROP TABLE IF EXISTS `jugadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jugadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipo_id` int(11) NOT NULL,
  `documento` varchar(30) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `dorsal` int(11) NOT NULL,
  `posicion` varchar(50) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `estado` enum('Habilitado','Suspendido','Inactivo') NOT NULL DEFAULT 'Habilitado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_equipo_dorsal` (`equipo_id`,`dorsal`),
  KEY `idx_jugadores_equipo` (`equipo_id`),
  CONSTRAINT `fk_jugadores_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jugadores`
--

LOCK TABLES `jugadores` WRITE;
/*!40000 ALTER TABLE `jugadores` DISABLE KEYS */;
/*!40000 ALTER TABLE `jugadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medallero`
--

DROP TABLE IF EXISTS `medallero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medallero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `olimpiada_id` int(11) NOT NULL,
  `delegacion_id` int(11) NOT NULL,
  `torneo_id` int(11) NOT NULL,
  `tipo_medalla` enum('ORO','PLATA','BRONCE') NOT NULL,
  `puntos_olimpicos` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_torneo_medalla` (`torneo_id`,`tipo_medalla`),
  KEY `idx_medallero_olimpiada` (`olimpiada_id`),
  KEY `idx_medallero_delegacion` (`delegacion_id`),
  KEY `idx_medallero_torneo` (`torneo_id`),
  CONSTRAINT `fk_medallero_delegacion` FOREIGN KEY (`delegacion_id`) REFERENCES `delegaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_medallero_olimpiada` FOREIGN KEY (`olimpiada_id`) REFERENCES `olimpiadas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_medallero_torneo` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medallero`
--

LOCK TABLES `medallero` WRITE;
/*!40000 ALTER TABLE `medallero` DISABLE KEYS */;
/*!40000 ALTER TABLE `medallero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `olimpiadas`
--

DROP TABLE IF EXISTS `olimpiadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `olimpiadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `anio` int(11) NOT NULL,
  `lema` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('Planificada','En Curso','Finalizada') NOT NULL DEFAULT 'Planificada',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `olimpiadas`
--

LOCK TABLES `olimpiadas` WRITE;
/*!40000 ALTER TABLE `olimpiadas` DISABLE KEYS */;
INSERT INTO `olimpiadas` VALUES (1,'XXXIII Jornadas Deportivas Nacionales ANESAPA - COSMOL 2026',2026,'Gestión, Unidad y Confraternización Inter-Institucional','2026-10-22','2026-10-24','Planificada','2026-10-20 13:22:32');
/*!40000 ALTER TABLE `olimpiadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partido_incidencias`
--

DROP TABLE IF EXISTS `partido_incidencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partido_incidencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `jugador_id` int(11) DEFAULT NULL,
  `tipo` enum('GOL','PUNTO','AMARILLA','ROJA','FALTA') NOT NULL,
  `minuto_tiempo` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_incidencias_partido` (`partido_id`),
  KEY `idx_incidencias_equipo` (`equipo_id`),
  KEY `idx_incidencias_jugador` (`jugador_id`),
  CONSTRAINT `fk_incidencias_equipo` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_incidencias_jugador` FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_incidencias_partido` FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partido_incidencias`
--

LOCK TABLES `partido_incidencias` WRITE;
/*!40000 ALTER TABLE `partido_incidencias` DISABLE KEYS */;
/*!40000 ALTER TABLE `partido_incidencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partido_sets`
--

DROP TABLE IF EXISTS `partido_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partido_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partido_id` int(11) NOT NULL,
  `numero_set` int(11) NOT NULL,
  `puntos_local` int(11) NOT NULL DEFAULT 0,
  `puntos_visitante` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sets_partido` (`partido_id`),
  CONSTRAINT `fk_sets_partido` FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partido_sets`
--

LOCK TABLES `partido_sets` WRITE;
/*!40000 ALTER TABLE `partido_sets` DISABLE KEYS */;
/*!40000 ALTER TABLE `partido_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partidos`
--

DROP TABLE IF EXISTS `partidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `torneo_id` int(11) NOT NULL,
  `grupo_id` int(11) DEFAULT NULL,
  `fase` enum('Grupos','Octavos','Cuartos','Semifinal','Tercer_Puesto','Final') NOT NULL DEFAULT 'Grupos',
  `jornada` varchar(50) NOT NULL DEFAULT 'Jornada 1',
  `equipo_local_id` int(11) NOT NULL,
  `equipo_visitante_id` int(11) NOT NULL,
  `cancha_id` int(11) DEFAULT NULL,
  `fecha_partido` date DEFAULT NULL,
  `hora_partido` time DEFAULT NULL,
  `marcador_local` int(11) DEFAULT NULL,
  `marcador_visitante` int(11) DEFAULT NULL,
  `estado` enum('Programado','En_Juego','Finalizado','Suspendido','WO') NOT NULL DEFAULT 'Programado',
  `observaciones` text DEFAULT NULL,
  `foto_planilla` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_partidos_torneo` (`torneo_id`),
  KEY `idx_partidos_grupo` (`grupo_id`),
  KEY `idx_partidos_local` (`equipo_local_id`),
  KEY `idx_partidos_visitante` (`equipo_visitante_id`),
  KEY `idx_partidos_cancha` (`cancha_id`),
  KEY `idx_partidos_fecha_hora` (`fecha_partido`,`hora_partido`),
  CONSTRAINT `fk_partidos_cancha` FOREIGN KEY (`cancha_id`) REFERENCES `canchas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `torneo_grupos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_local` FOREIGN KEY (`equipo_local_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_torneo` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_partidos_visitante` FOREIGN KEY (`equipo_visitante_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partidos`
--

LOCK TABLES `partidos` WRITE;
/*!40000 ALTER TABLE `partidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `partidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reglas_puntuacion`
--

DROP TABLE IF EXISTS `reglas_puntuacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reglas_puntuacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `disciplina_id` int(11) NOT NULL,
  `pts_victoria` int(11) NOT NULL DEFAULT 3,
  `pts_empate` int(11) NOT NULL DEFAULT 1,
  `pts_derrota` int(11) NOT NULL DEFAULT 0,
  `pts_derrota_ajustada` int(11) NOT NULL DEFAULT 0,
  `permite_empate` tinyint(1) NOT NULL DEFAULT 1,
  `formato_sets` tinyint(1) NOT NULL DEFAULT 0,
  `sets_para_ganar` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_reglas_disciplina` (`disciplina_id`),
  CONSTRAINT `fk_reglas_disciplina` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reglas_puntuacion`
--

LOCK TABLES `reglas_puntuacion` WRITE;
/*!40000 ALTER TABLE `reglas_puntuacion` DISABLE KEYS */;
INSERT INTO `reglas_puntuacion` VALUES (1,1,3,1,0,0,1,0,0,'2026-10-21 02:06:56'),(2,2,3,1,0,0,1,0,0,'2026-10-21 02:06:56'),(3,3,3,0,0,1,0,1,2,'2026-10-21 02:06:56'),(4,4,2,0,1,0,0,0,0,'2026-10-21 02:06:56'),(5,5,2,0,0,0,0,1,2,'2026-10-21 02:06:56'),(6,6,2,0,0,0,0,0,0,'2026-10-21 02:06:56'),(7,7,2,1,0,0,1,0,0,'2026-10-21 02:06:56'),(8,8,2,0,0,0,0,0,0,'2026-10-21 02:06:56'),(9,9,2,0,0,0,0,0,0,'2026-10-21 02:06:56'),(10,10,2,0,0,0,0,0,0,'2026-10-21 02:06:56'),(11,11,1,0,0,0,0,0,0,'2026-10-21 02:06:56');
/*!40000 ALTER TABLE `reglas_puntuacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanciones`
--

DROP TABLE IF EXISTS `sanciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sanciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jugador_id` int(11) NOT NULL,
  `partido_id` int(11) DEFAULT NULL,
  `torneo_id` int(11) NOT NULL,
  `tipo_sancion` enum('AMARILLA','ROJA','PARTIDOS_SUSPENSION','EXPULSION') NOT NULL,
  `partidos_suspension` int(11) NOT NULL DEFAULT 1,
  `partidos_cumplidos` int(11) NOT NULL DEFAULT 0,
  `motivo` text NOT NULL,
  `estado` enum('Activa','Cumplida','Levantada') NOT NULL DEFAULT 'Activa',
  `motivo_levantamiento` text DEFAULT NULL,
  `levantado_por_usuario_id` int(11) DEFAULT NULL,
  `fecha_levantamiento` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_sanciones_jugador` (`jugador_id`),
  KEY `idx_sanciones_torneo` (`torneo_id`),
  KEY `idx_sanciones_estado` (`estado`),
  KEY `fk_sanciones_partido` (`partido_id`),
  KEY `fk_sanciones_usuario` (`levantado_por_usuario_id`),
  CONSTRAINT `fk_sanciones_jugador` FOREIGN KEY (`jugador_id`) REFERENCES `jugadores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sanciones_partido` FOREIGN KEY (`partido_id`) REFERENCES `partidos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_sanciones_torneo` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sanciones_usuario` FOREIGN KEY (`levantado_por_usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanciones`
--

LOCK TABLES `sanciones` WRITE;
/*!40000 ALTER TABLE `sanciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `sanciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sedes`
--

DROP TABLE IF EXISTS `sedes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sedes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `estado` enum('Activa','Inactiva') NOT NULL DEFAULT 'Activa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sedes`
--

LOCK TABLES `sedes` WRITE;
/*!40000 ALTER TABLE `sedes` DISABLE KEYS */;
INSERT INTO `sedes` VALUES (1,'Complejo Deportivo COSMOL Montero','Calle Isaías Parada No 219, Montero, Santa Cruz','60765065',-17.34150000,-63.25580000,'Activa','2026-10-20 13:33:52'),(2,'Club Social COSMOL (Salón de Eventos)','Av. Barrientos s/n, Montero','60765065',-17.34300000,-63.25800000,'Activa','2026-10-20 13:41:46');
/*!40000 ALTER TABLE `sedes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `torneo_grupos`
--

DROP TABLE IF EXISTS `torneo_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torneo_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `torneo_id` int(11) NOT NULL,
  `nombre_grupo` varchar(50) NOT NULL,
  `orden` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_grupos_torneo` (`torneo_id`),
  CONSTRAINT `fk_grupos_torneo` FOREIGN KEY (`torneo_id`) REFERENCES `torneos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `torneo_grupos`
--

LOCK TABLES `torneo_grupos` WRITE;
/*!40000 ALTER TABLE `torneo_grupos` DISABLE KEYS */;
INSERT INTO `torneo_grupos` VALUES (1,1,'Grupo A',1,'2026-10-21 03:59:56'),(2,1,'Grupo B',2,'2026-10-21 03:59:56'),(3,2,'Grupo A',1,'2026-10-21 03:59:56'),(4,2,'Grupo B',2,'2026-10-21 03:59:56'),(5,3,'Grupo A',1,'2026-10-21 03:59:56'),(6,3,'Grupo B',2,'2026-10-21 03:59:56'),(7,4,'Grupo A',1,'2026-10-21 03:59:56'),(8,4,'Grupo B',2,'2026-10-21 03:59:56'),(9,5,'Grupo A',1,'2026-10-21 03:59:56'),(10,5,'Grupo B',2,'2026-10-21 03:59:56');
/*!40000 ALTER TABLE `torneo_grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `torneos`
--

DROP TABLE IF EXISTS `torneos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `torneos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `olimpiada_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `regla_puntuacion_id` int(11) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `rama` enum('Masculino','Femenino','Mixto') NOT NULL DEFAULT 'Masculino',
  `tipo_torneo` enum('Grupos_Playoff','Liga','Eliminatoria') NOT NULL DEFAULT 'Grupos_Playoff',
  `modalidad` enum('Solo_Ida','Ida_Vuelta') NOT NULL DEFAULT 'Solo_Ida',
  `estado` enum('Borrador','Inscripciones','Fase_Grupos','Playoffs','Finalizado') NOT NULL DEFAULT 'Borrador',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_torneos_olimpiada` (`olimpiada_id`),
  KEY `idx_torneos_disciplina` (`disciplina_id`),
  KEY `fk_torneos_regla` (`regla_puntuacion_id`),
  CONSTRAINT `fk_torneos_disciplina` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_torneos_olimpiada` FOREIGN KEY (`olimpiada_id`) REFERENCES `olimpiadas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_torneos_regla` FOREIGN KEY (`regla_puntuacion_id`) REFERENCES `reglas_puntuacion` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `torneos`
--

LOCK TABLES `torneos` WRITE;
/*!40000 ALTER TABLE `torneos` DISABLE KEYS */;
INSERT INTO `torneos` VALUES (1,1,1,1,'Fútbol Once Varones - ANESAPA 2026','Masculino','Grupos_Playoff','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(2,1,2,2,'Fútbol de Salón Varones - ANESAPA 2026','Masculino','Grupos_Playoff','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(3,1,3,3,'Voleibol Mixto - ANESAPA 2026','Mixto','Grupos_Playoff','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(4,1,4,4,'Básquetbol Mixto - ANESAPA 2026','Mixto','Grupos_Playoff','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(5,1,5,5,'Raqueta Frontón - ANESAPA 2026','Masculino','Grupos_Playoff','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(6,1,6,6,'Media Maratón 5K - ANESAPA 2026','Mixto','Liga','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(7,1,7,7,'Cacho (Juego de Salón) - ANESAPA 2026','Mixto','Eliminatoria','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(8,1,8,8,'Karaoke (Noche de Talentos) - ANESAPA 2026','Mixto','Liga','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(9,1,9,9,'Billar (Juego de Salón) - ANESAPA 2026','Mixto','Eliminatoria','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(10,1,10,10,'Competencias Técnicas (Fontanería) - ANESAPA 2026','Mixto','Liga','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56'),(11,1,11,11,'Juegos Populares Tradicionales - ANESAPA 2026','Mixto','Liga','Solo_Ida','Borrador',NULL,NULL,'2026-10-21 02:06:56');
/*!40000 ALTER TABLE `torneos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('ADMIN','USUARIO') NOT NULL DEFAULT 'USUARIO',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador General COSMOL','admin','admin@cosmol.com','$2y$10$wXQZyGsstvEV1zS2T7y0d.JvWp3hX6AFFbxkZ9k9r1rR5siE05P/m','ADMIN','Activo','2026-10-19 21:03:45','2026-10-19 21:03:45');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-21  0:53:43
