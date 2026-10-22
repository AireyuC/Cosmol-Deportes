-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: cosmol_deportes
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `canchas`
--

DROP TABLE IF EXISTS `canchas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `canchas`
--

LOCK TABLES `canchas` WRITE;
/*!40000 ALTER TABLE `canchas` DISABLE KEYS */;
INSERT INTO `canchas` VALUES (1,1,'Estadio Gilberto Parada - Campo Principal','Césped Natural','Disponible','2026-10-21 19:45:22'),(2,1,'Cancha Auxiliar (Azucareros / La Esperanza)','Césped Natural','Disponible','2026-10-21 19:45:22'),(3,2,'Coliseo Patujú - Cancha Central','Cancha polifuncional','Disponible','2026-10-21 19:45:22'),(4,3,'Coliseo Plurinacional - Cancha Central','Cemento pulido','Disponible','2026-10-21 19:45:22'),(5,4,'Escenario Principal La Perla','Bar Restaurant','Disponible','2026-10-21 19:45:22'),(6,5,'Salón Auditorio Central COSMOL','Auditorio','Disponible','2026-10-21 19:45:22'),(7,6,'Coliseo FINOR - Cancha Principal','Cemento Pulido','Disponible','2026-10-21 19:45:22'),(8,7,'Coliseo Andrés Ibáñez - Cancha Principal','Cemento Pulido','Disponible','2026-10-21 19:45:22'),(9,11,'Frontón Municipal - Frontón Reglamentario','Frontón Oficial','Disponible','2026-10-21 19:45:22'),(10,8,'Mesas Oficiales Springfield','Mesa de Billar','Disponible','2026-10-21 19:45:22'),(11,9,'Cancha Mutual de Ex Jugadores','Césped Natural','Disponible','2026-10-21 19:45:22'),(12,10,'Circuito Oficial 5.0 km','Asfalto Pavimentado','Disponible','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `canchas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delegaciones`
--

DROP TABLE IF EXISTS `delegaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `delegaciones` VALUES (1,'COSMOL (MO)','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(2,'ELAPAS (SU)','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(3,'SAGUAPAC (SC)','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(4,'SEMAPA (CB)','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(5,'SAMAPA (LP)','SAMAPA','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(6,'COSAALT (TR)','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(7,'EMAPYC (YC)','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(8,'SELA (OR)','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `delegaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(50) DEFAULT 'trophy',
  `estado` enum('Activa','Inactiva') NOT NULL DEFAULT 'Activa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciplinas`
--

LOCK TABLES `disciplinas` WRITE;
/*!40000 ALTER TABLE `disciplinas` DISABLE KEYS */;
INSERT INTO `disciplinas` VALUES (1,'Fútbol Once','2 tiempos de 45 min. Cancha de césped. Exigencia reglamentaria: 3 jugadores en cancha >= 45 años.','futbol','Activa','2026-10-21 19:45:22'),(2,'Fútbol de Salón','2 tiempos de 20 min. Exigencia reglamentaria: 1 jugador en cancha >= 45 años.','futbol','Activa','2026-10-21 19:45:22'),(3,'Voleibol Mixto','Partidos al mejor de 3 sets (25 pts). Exigencia reglamentaria: 2 damas y 1 veterano >= 50 años obligatorios en cancha.','volleyball-ball','Activa','2026-10-21 19:45:22'),(4,'Básquetbol Mixto','4 cuartos reglamentarios. Exigencia reglamentaria: 1 dama y 1 veterano >= 50 años obligatorios en cancha.','basketball-ball','Activa','2026-10-21 19:45:22'),(5,'Raqueta Frontón','Modalidad pareja (2 jugadores). Sets a 15 puntos (al mejor de 3 sets).','table-tennis','Activa','2026-10-21 19:45:22'),(6,'Media Maratón','Carrera pedestre urbana de 5 kilómetros. Hasta 2 representantes por delegación. Circuito Urb. Celina.','running','Activa','2026-10-21 19:45:22'),(7,'Cacho','Juego de dados por parejas (exigencia: al menos 1 directivo o ejecutivo).','dice','Activa','2026-10-21 19:45:22'),(8,'Karaoke','Noche de talentos. Hasta 2 representantes por delegación. Evaluación del jurado.','microphone-alt','Activa','2026-10-21 19:45:22'),(9,'Billar','Modalidad pareja. Exigencia: al menos 1 directivo o ejecutivo.','circle','Activa','2026-10-21 19:45:22'),(10,'Competencias Técnicas','Competencia tecnica con el balon.','futbol','Activa','2026-10-21 19:45:22'),(11,'Juegos Populares','Pruebas recreativas: Carrera de embolsao, Carrera de cuchara con huevo y Carrera de carretilla.','smile','Activa','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `disciplinas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
INSERT INTO `equipos` VALUES (1,1,1,1,'COSMOL - Fútbol Once','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(2,3,1,1,'SAGUAPAC - Fútbol Once','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(3,4,1,1,'SEMAPA - Fútbol Once','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(4,2,1,2,'ELAPAS - Fútbol Once','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(5,8,1,2,'SELA - Fútbol Once','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(6,7,1,2,'EMAPYC - Fútbol Once','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(7,6,1,2,'COSAALT - Fútbol Once','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(8,1,2,3,'COSMOL - Futsal','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(9,3,2,3,'SAGUAPAC - Futsal','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(10,4,2,3,'SEMAPA - Futsal','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(11,2,2,4,'ELAPAS - Futsal','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(12,8,2,4,'SELA - Futsal','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(13,6,2,4,'COSAALT - Futsal','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(14,1,3,5,'COSMOL - Voleibol Mixto','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(15,3,3,5,'SAGUAPAC - Voleibol Mixto','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(16,4,3,5,'SEMAPA - Voleibol Mixto','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(17,2,3,6,'ELAPAS - Voleibol Mixto','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(18,8,3,6,'SELA - Voleibol Mixto','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(19,7,3,6,'EMAPYC - Voleibol Mixto','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(20,6,3,6,'COSAALT - Voleibol Mixto','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(21,1,4,7,'COSMOL - Básquetbol Mixto','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(22,3,4,7,'SAGUAPAC - Básquetbol Mixto','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(23,4,4,7,'SEMAPA - Básquetbol Mixto','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(24,2,4,8,'ELAPAS - Básquetbol Mixto','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(25,8,4,8,'SELA - Básquetbol Mixto','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(26,7,4,8,'EMAPYC - Básquetbol Mixto','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(27,6,4,8,'COSAALT - Básquetbol Mixto','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(28,1,5,9,'COSMOL - Raqueta Frontón','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(29,4,5,9,'SEMAPA - Raqueta Frontón','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(30,8,5,9,'SELA - Raqueta Frontón','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(31,2,5,10,'ELAPAS - Raqueta Frontón','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(32,7,5,10,'EMAPYC - Raqueta Frontón','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(33,6,5,10,'COSAALT - Raqueta Frontón','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(34,1,6,NULL,'COSMOL - Media Maratón','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(35,2,6,NULL,'ELAPAS - Media Maratón','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(36,3,6,NULL,'SAGUAPAC - Media Maratón','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(37,4,6,NULL,'SEMAPA - Media Maratón','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(38,5,6,NULL,'SAMAPA - Media Maratón','SAMAPA','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(39,6,6,NULL,'COSAALT - Media Maratón','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(40,7,6,NULL,'EMAPYC - Media Maratón','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(41,8,6,NULL,'SELA - Media Maratón','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(42,1,7,NULL,'COSMOL - Cacho','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(43,8,7,NULL,'SELA - Cacho','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(44,3,7,NULL,'SAGUAPAC - Cacho','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(45,7,7,NULL,'EMAPYC - Cacho','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(46,2,7,NULL,'ELAPAS - Cacho','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(47,6,7,NULL,'COSAALT - Cacho','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(48,4,7,NULL,'SEMAPA - Cacho','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(49,5,7,NULL,'SAMAPA - Cacho','SAMAPA','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(50,1,8,NULL,'COSMOL - Karaoke','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(51,2,8,NULL,'ELAPAS - Karaoke','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(52,3,8,NULL,'SAGUAPAC - Karaoke','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(53,4,8,NULL,'SEMAPA - Karaoke','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(54,5,8,NULL,'SAMAPA - Karaoke','SAMAPA','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(55,6,8,NULL,'COSAALT - Karaoke','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(56,7,8,NULL,'EMAPYC - Karaoke','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(57,8,8,NULL,'SELA - Karaoke','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(58,1,9,NULL,'COSMOL A','COSMOL A','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(59,2,9,NULL,'ELAPAS A','ELAPAS A','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(60,3,9,NULL,'SAGUAPAC A','SAGUAPAC A','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(61,4,9,NULL,'SEMAPA A','SEMAPA A','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(62,5,9,NULL,'SAMAPA A','SAMAPA A','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(63,6,9,NULL,'COSAALT A','COSAALT A','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(64,8,9,NULL,'SELA A','SELA A','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(65,1,9,NULL,'COSMOL C (Llave B)','COSMOL C','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(66,1,10,NULL,'COSMOL - Juegos Técnicos','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(67,2,10,NULL,'ELAPAS - Juegos Técnicos','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(68,3,10,NULL,'SAGUAPAC - Juegos Técnicos','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(69,4,10,NULL,'SEMAPA - Juegos Técnicos','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(70,5,10,NULL,'SAMAPA - Juegos Técnicos','SAMAPA','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(71,6,10,NULL,'COSAALT - Juegos Técnicos','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(72,7,10,NULL,'EMAPYC - Juegos Técnicos','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(73,8,10,NULL,'SELA - Juegos Técnicos','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(74,1,11,NULL,'COSMOL - Juegos Populares','COSMOL','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(75,2,11,NULL,'ELAPAS - Juegos Populares','ELAPAS','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(76,3,11,NULL,'SAGUAPAC - Juegos Populares','SAGUAPAC','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(77,4,11,NULL,'SEMAPA - Juegos Populares','SEMAPA','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(78,5,11,NULL,'SAMAPA - Juegos Populares','SAMAPA','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(79,6,11,NULL,'COSAALT - Juegos Populares','COSAALT','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(80,7,11,NULL,'EMAPYC - Juegos Populares','EMAPYC','#20C997',NULL,'Freddy Valdez','75012345','2026-10-21 19:45:22'),(81,8,11,NULL,'SELA - Juegos Populares','SELA','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22'),(82,4,9,NULL,'SEMAPA B','SEMAPA B','#DC3545',NULL,'Erlan Arandia','73012345','2026-10-21 19:45:22'),(83,1,9,NULL,'COSMOL C (Llave H)','COSMOL C','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(84,6,9,NULL,'COSAALT B','COSAALT B','#FD7E14',NULL,'Rodrigo Lopez','79012345','2026-10-21 19:45:22'),(85,3,9,NULL,'SAGUAPAC B','SAGUAPAC B','#28A745',NULL,'Mauricio','72012345','2026-10-21 19:45:22'),(86,1,9,NULL,'COSMOL B','COSMOL B','#0066B2',NULL,'Johnny Gonzales','71012345','2026-10-21 19:45:22'),(87,5,9,NULL,'SAMAPA B','SAMAPA B','#00A3E0',NULL,'Walter Salazar','78012345','2026-10-21 19:45:22'),(88,2,9,NULL,'ELAPAS B','ELAPAS B','#6F42C1',NULL,'Marco Navas','77012345','2026-10-21 19:45:22'),(89,8,9,NULL,'SELA B','SELA B','#FFC107',NULL,'Erlan Vasquez','74012345','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jugadores`
--

DROP TABLE IF EXISTS `jugadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `olimpiadas` VALUES (1,'XXXIII Jornadas Deportivas Nacionales ANESAPA - COSMOL 2026',2026,'Gestión, Unidad y Confraternización Inter-Institucional','2026-10-22','2026-10-24','En Curso','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `olimpiadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partido_incidencias`
--

DROP TABLE IF EXISTS `partido_incidencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partidos`
--

LOCK TABLES `partidos` WRITE;
/*!40000 ALTER TABLE `partidos` DISABLE KEYS */;
INSERT INTO `partidos` VALUES (1,1,1,'Grupos','Jornada 1',3,2,1,'2026-10-22','07:30:00',NULL,NULL,'Programado','Fase de Grupos - Serie A: SEMAPA vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(2,1,2,'Grupos','Jornada 1',6,7,2,'2026-10-22','07:30:00',NULL,NULL,'Programado','Fase de Grupos - Serie B: EMAPYC vs COSAALT',NULL,'2026-10-21 19:45:22'),(3,1,1,'Grupos','Jornada 2',1,2,1,'2026-10-22','09:00:00',NULL,NULL,'Programado','Fase de Grupos - Serie A: COSMOL vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(4,1,2,'Grupos','Jornada 2',4,5,2,'2026-10-22','09:00:00',NULL,NULL,'Programado','Fase de Grupos - Serie B: ELAPAS vs SELA',NULL,'2026-10-21 19:45:22'),(5,1,1,'Grupos','Jornada 3',1,3,1,'2026-10-22','10:30:00',NULL,NULL,'Programado','Fase de Grupos - Serie A: COSMOL vs SEMAPA',NULL,'2026-10-21 19:45:22'),(6,1,2,'Grupos','Jornada 3',5,7,2,'2026-10-22','10:30:00',NULL,NULL,'Programado','Fase de Grupos - Serie B: SELA vs COSAALT',NULL,'2026-10-21 19:45:22'),(7,1,2,'Grupos','Jornada 4',4,6,1,'2026-10-22','12:00:00',NULL,NULL,'Programado','Fase de Grupos - Serie B: ELAPAS vs EMAPYC',NULL,'2026-10-21 19:45:22'),(8,1,2,'Grupos','Jornada 5',5,6,1,'2026-10-22','13:30:00',NULL,NULL,'Programado','Fase de Grupos - Serie B: SELA vs EMAPYC',NULL,'2026-10-21 19:45:22'),(9,1,2,'Grupos','Jornada 6',4,7,1,'2026-10-22','15:00:00',NULL,NULL,'Programado','Fase de Grupos - Serie B: ELAPAS vs COSAALT',NULL,'2026-10-21 19:45:22'),(10,3,5,'Grupos','Jornada 1',16,15,3,'2026-10-22','08:00:00',NULL,NULL,'Programado','Coliseo Patujú - Serie A: SEMAPA vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(11,3,6,'Grupos','Jornada 1',19,20,4,'2026-10-22','08:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: EMAPYC vs COSAALT',NULL,'2026-10-21 19:45:22'),(12,3,5,'Grupos','Jornada 2',14,15,3,'2026-10-22','09:00:00',NULL,NULL,'Programado','Coliseo Patujú - Serie A: COSMOL vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(13,3,6,'Grupos','Jornada 2',17,18,4,'2026-10-22','09:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: ELAPAS vs SELA',NULL,'2026-10-21 19:45:22'),(14,3,5,'Grupos','Jornada 3',14,16,3,'2026-10-22','10:00:00',NULL,NULL,'Programado','Coliseo Patujú - Serie A: COSMOL vs SEMAPA',NULL,'2026-10-21 19:45:22'),(15,3,6,'Grupos','Jornada 3',18,20,4,'2026-10-22','10:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: SELA vs COSAALT',NULL,'2026-10-21 19:45:22'),(16,3,6,'Grupos','Jornada 4',17,19,4,'2026-10-22','11:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: ELAPAS vs EMAPYC',NULL,'2026-10-21 19:45:22'),(17,3,6,'Grupos','Jornada 5',18,19,4,'2026-10-22','12:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: SELA vs EMAPYC',NULL,'2026-10-21 19:45:22'),(18,3,6,'Grupos','Jornada 6',17,20,4,'2026-10-22','13:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: ELAPAS vs COSAALT',NULL,'2026-10-21 19:45:22'),(19,8,NULL,'Final','Noche de Talentos',50,53,5,'2026-10-22','22:00:00',NULL,NULL,'Programado','Concurso Oficial de Karaoke ANESAPA 2026 (Bar Restaurante La Perla)',NULL,'2026-10-21 19:45:22'),(20,2,3,'Grupos','Jornada 1',8,9,3,'2026-10-23','08:00:00',NULL,NULL,'Programado','Coliseo Patujú - Serie A: COSMOL vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(21,2,4,'Grupos','Jornada 1',11,12,4,'2026-10-23','08:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: ELAPAS vs SELA',NULL,'2026-10-21 19:45:22'),(22,2,3,'Grupos','Jornada 2',10,9,3,'2026-10-23','09:00:00',NULL,NULL,'Programado','Coliseo Patujú - Serie A: SEMAPA vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(23,2,4,'Grupos','Jornada 2',13,12,4,'2026-10-23','09:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: COSAALT vs SELA',NULL,'2026-10-21 19:45:22'),(24,2,3,'Grupos','Jornada 3',8,10,3,'2026-10-23','10:00:00',NULL,NULL,'Programado','Coliseo Patujú - Serie A: COSMOL vs SEMAPA',NULL,'2026-10-21 19:45:22'),(25,2,4,'Grupos','Jornada 3',11,13,4,'2026-10-23','10:00:00',NULL,NULL,'Programado','Coliseo Plurinacional - Serie B: ELAPAS vs COSAALT',NULL,'2026-10-21 19:45:22'),(26,4,7,'Grupos','Jornada 1',23,22,7,'2026-10-23','08:00:00',NULL,NULL,'Programado','Coliseo FINOR - Serie A: SEMAPA vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(27,4,8,'Grupos','Jornada 1',26,27,8,'2026-10-23','08:00:00',NULL,NULL,'Programado','Coliseo Andrés Ibáñez - Serie B: EMAPYC vs COSAALT',NULL,'2026-10-21 19:45:22'),(28,4,7,'Grupos','Jornada 2',21,22,7,'2026-10-23','09:00:00',NULL,NULL,'Programado','Coliseo FINOR - Serie A: COSMOL vs SAGUAPAC',NULL,'2026-10-21 19:45:22'),(29,4,8,'Grupos','Jornada 2',24,25,8,'2026-10-23','09:00:00',NULL,NULL,'Programado','Coliseo Andrés Ibáñez - Serie B: ELAPAS vs SELA',NULL,'2026-10-21 19:45:22'),(30,4,7,'Grupos','Jornada 3',21,23,7,'2026-10-23','10:00:00',NULL,NULL,'Programado','Coliseo FINOR - Serie A: COSMOL vs SEMAPA',NULL,'2026-10-21 19:45:22'),(31,4,8,'Grupos','Jornada 3',25,27,8,'2026-10-23','10:00:00',NULL,NULL,'Programado','Coliseo Andrés Ibáñez - Serie B: SELA vs COSAALT',NULL,'2026-10-21 19:45:22'),(32,4,8,'Grupos','Jornada 4',24,26,8,'2026-10-23','11:00:00',NULL,NULL,'Programado','Coliseo Andrés Ibáñez - Serie B: ELAPAS vs EMAPYC',NULL,'2026-10-21 19:45:22'),(33,4,8,'Grupos','Jornada 5',25,26,8,'2026-10-23','12:00:00',NULL,NULL,'Programado','Coliseo Andrés Ibáñez - Serie B: SELA vs EMAPYC',NULL,'2026-10-21 19:45:22'),(34,4,8,'Grupos','Jornada 6',24,27,8,'2026-10-23','13:30:00',NULL,NULL,'Programado','Coliseo Andrés Ibáñez - Serie B: ELAPAS vs COSAALT',NULL,'2026-10-21 19:45:22'),(35,5,9,'Grupos','Jornada 1',28,29,9,'2026-10-23','08:00:00',NULL,NULL,'Programado','Frontón Municipal - Serie A: COSMOL vs SEMAPA',NULL,'2026-10-21 19:45:22'),(36,5,10,'Grupos','Jornada 1',31,32,9,'2026-10-23','08:00:00',NULL,NULL,'Programado','Frontón Municipal - Serie B: ELAPAS vs EMAPYC',NULL,'2026-10-21 19:45:22'),(37,5,9,'Grupos','Jornada 2',30,29,9,'2026-10-23','09:00:00',NULL,NULL,'Programado','Frontón Municipal - Serie A: SELA vs SEMAPA',NULL,'2026-10-21 19:45:22'),(38,5,10,'Grupos','Jornada 2',33,32,9,'2026-10-23','09:00:00',NULL,NULL,'Programado','Frontón Municipal - Serie B: COSAALT vs EMAPYC',NULL,'2026-10-21 19:45:22'),(39,5,9,'Grupos','Jornada 3',28,30,9,'2026-10-23','10:00:00',NULL,NULL,'Programado','Frontón Municipal - Serie A: COSMOL vs SELA',NULL,'2026-10-21 19:45:22'),(40,5,10,'Grupos','Jornada 3',31,33,9,'2026-10-23','10:00:00',NULL,NULL,'Programado','Frontón Municipal - Serie B: ELAPAS vs COSAALT',NULL,'2026-10-21 19:45:22'),(41,9,NULL,'Octavos','Llave A',58,82,10,'2026-10-23','20:00:00',NULL,NULL,'Programado','Clasificatorio Llave A: COSMOL A vs SEMAPA B',NULL,'2026-10-21 19:45:22'),(42,7,NULL,'Cuartos','Llave A',42,43,10,'2026-10-23','20:00:00',NULL,NULL,'Programado','Clasificatorio Llave A: COSMOL vs SELA',NULL,'2026-10-21 19:45:22'),(43,6,NULL,'Final','Final 5K',34,36,12,'2026-10-24','07:30:00',NULL,NULL,'Programado','Prueba Pedestre 5K Oficial ANESAPA (Circuito Urb. Celina Muyurina)',NULL,'2026-10-21 19:45:22'),(44,10,NULL,'Final','Prueba Técnica',66,68,12,'2026-10-24','09:30:00',NULL,NULL,'Programado','Competencias Técnicas: Dominadas con el Balón (Urb. Celina Muyurina)',NULL,'2026-10-21 19:45:22'),(45,9,NULL,'Octavos','Llave B',60,65,10,'2026-10-23','20:00:00',NULL,NULL,'Programado','Clasificatorio Llave B: SAGUAPAC A vs COSMOL C',NULL,'2026-10-21 19:45:22'),(46,9,NULL,'Octavos','Llave C',59,84,10,'2026-10-23','20:30:00',NULL,NULL,'Programado','Clasificatorio Llave C: ELAPAS A vs COSAALT B',NULL,'2026-10-21 19:45:22'),(47,9,NULL,'Octavos','Llave D',61,85,10,'2026-10-23','20:30:00',NULL,NULL,'Programado','Clasificatorio Llave D: SEMAPA A vs SAGUAPAC B',NULL,'2026-10-21 19:45:22'),(48,9,NULL,'Octavos','Llave E',62,86,10,'2026-10-23','21:00:00',NULL,NULL,'Programado','Clasificatorio Llave E: SAMAPA A vs COSMOL B',NULL,'2026-10-21 19:45:22'),(49,9,NULL,'Octavos','Llave F',63,87,10,'2026-10-23','21:00:00',NULL,NULL,'Programado','Clasificatorio Llave F: COSAALT A vs SAMAPA B',NULL,'2026-10-21 19:45:22'),(50,9,NULL,'Octavos','Llave G',64,88,10,'2026-10-23','21:30:00',NULL,NULL,'Programado','Clasificatorio Llave G: SELA A vs ELAPAS B',NULL,'2026-10-21 19:45:22'),(51,9,NULL,'Octavos','Llave H',83,89,10,'2026-10-23','21:30:00',NULL,NULL,'Programado','Clasificatorio Llave H: COSMOL C vs SELA B',NULL,'2026-10-21 19:45:22'),(52,7,NULL,'Cuartos','Llave B',44,45,10,'2026-10-23','20:30:00',NULL,NULL,'Programado','Clasificatorio Llave B: SAGUAPAC vs EMAPYC',NULL,'2026-10-21 19:45:22'),(53,7,NULL,'Cuartos','Llave C',46,47,10,'2026-10-23','21:00:00',NULL,NULL,'Programado','Clasificatorio Llave C: ELAPAS vs COSAALT',NULL,'2026-10-21 19:45:22'),(54,7,NULL,'Cuartos','Llave D',48,49,10,'2026-10-23','21:30:00',NULL,NULL,'Programado','Clasificatorio Llave D: SEMAPA vs SAMAPA',NULL,'2026-10-21 19:45:22');
/*!40000 ALTER TABLE `partidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reglas_puntuacion`
--

DROP TABLE IF EXISTS `reglas_puntuacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reglas_puntuacion`
--

LOCK TABLES `reglas_puntuacion` WRITE;
/*!40000 ALTER TABLE `reglas_puntuacion` DISABLE KEYS */;
INSERT INTO `reglas_puntuacion` VALUES (1,1,3,1,0,0,1,0,0,'2026-10-21 19:45:22'),(2,2,3,1,0,0,1,0,0,'2026-10-21 19:45:22'),(3,3,3,0,0,1,0,1,2,'2026-10-21 19:45:22'),(4,4,2,0,1,0,0,0,0,'2026-10-21 19:45:22'),(5,5,2,0,0,0,0,1,2,'2026-10-21 19:45:22'),(6,6,2,0,0,0,0,0,0,'2026-10-21 19:45:22'),(7,7,2,1,0,0,1,0,0,'2026-10-21 19:45:22'),(8,8,2,0,0,0,0,0,0,'2026-10-21 19:45:22'),(9,9,2,0,0,0,0,0,0,'2026-10-21 19:45:22'),(10,10,2,0,0,0,0,0,0,'2026-10-21 19:45:22'),(11,11,1,0,0,0,0,0,0,'2026-10-21 19:45:22');
/*!40000 ALTER TABLE `reglas_puntuacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanciones`
--

DROP TABLE IF EXISTS `sanciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sedes`
--

LOCK TABLES `sedes` WRITE;
/*!40000 ALTER TABLE `sedes` DISABLE KEYS */;
INSERT INTO `sedes` VALUES (1,'Estadio Gilberto Parada','Av. Circunvalación Este (Montero)','39221144',-17.34308263,-63.26197784,'Activa','2026-10-21 19:45:22'),(2,'Coliseo Patujú','Calle Litoral esq. Roberto Paz, Zona Central (Montero)','39223300',-17.34033131,-63.25355583,'Activa','2026-10-21 19:45:22'),(3,'Coliseo Plurinacional','Av. Betania (Montero)','39224400',-17.34959888,-63.23763889,'Activa','2026-10-21 19:45:22'),(4,'Bar Restaurante \"La Perla\"','Av. Betania Nro. 218 (Montero)','39225500',-17.34887214,-63.24223902,'Activa','2026-10-21 19:45:22'),(5,'Oficina y Salón Auditorio COSMOL','Calle Isaías Parada, Zona Central (Montero)','39221144',-17.33940000,-63.25580000,'Activa','2026-10-21 19:45:22'),(6,'Coliseo FINOR','Universitario FINOR, Av. Circunvalación Noroeste (Montero)','39226600',-17.33120000,-63.26100000,'Activa','2026-10-21 19:45:22'),(7,'Coliseo Andrés Ibáñez','Av. Circunvalación Noroeste (Montero)','39227700',-17.34050945,-63.27135970,'Activa','2026-10-21 19:45:22'),(8,'Billar Springfield Suc. Montero','Calle Isaías Parada esq. Chuquisaca (Montero)','39228800',-17.34149600,-63.25464100,'Activa','2026-10-21 19:45:22'),(9,'Mutual de Ex Jugadores de Fútbol de Montero','Calle 16 de Julio (Montero)','39229900',-17.33230800,-63.25752800,'Activa','2026-10-21 19:45:22'),(10,'Circuito Urb. Celina Muyurina','Urbanización Celina Muyurina (Montero)','39220000',-17.34850000,-63.24800000,'Activa','2026-10-21 19:45:22'),(11,'Frontón Municipal de Montero','Av. Circunvalación Noroeste (Al Lado del Coliseo Andrés Ibáñez)','39227700',-17.33999329,-63.27183894,'Activa','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `sedes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `torneo_grupos`
--

DROP TABLE IF EXISTS `torneo_grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
INSERT INTO `torneo_grupos` VALUES (1,1,'Serie A',1,'2026-10-21 19:45:22'),(2,1,'Serie B',2,'2026-10-21 19:45:22'),(3,2,'Serie A',1,'2026-10-21 19:45:22'),(4,2,'Serie B',2,'2026-10-21 19:45:22'),(5,3,'Serie A',1,'2026-10-21 19:45:22'),(6,3,'Serie B',2,'2026-10-21 19:45:22'),(7,4,'Serie A',1,'2026-10-21 19:45:22'),(8,4,'Serie B',2,'2026-10-21 19:45:22'),(9,5,'Serie A',1,'2026-10-21 19:45:22'),(10,5,'Serie B',2,'2026-10-21 19:45:22');
/*!40000 ALTER TABLE `torneo_grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `torneos`
--

DROP TABLE IF EXISTS `torneos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `torneos`
--

LOCK TABLES `torneos` WRITE;
/*!40000 ALTER TABLE `torneos` DISABLE KEYS */;
INSERT INTO `torneos` VALUES (1,1,1,1,'Fútbol Once Varones - ANESAPA 2026','Masculino','Grupos_Playoff','Solo_Ida','Fase_Grupos','2026-10-22','2026-10-24','2026-10-21 19:45:22'),(2,1,2,2,'Fútbol de Salón Varones - ANESAPA 2026','Masculino','Grupos_Playoff','Solo_Ida','Fase_Grupos','2026-10-23','2026-10-24','2026-10-21 19:45:22'),(3,1,3,3,'Voleibol Mixto - ANESAPA 2026','Mixto','Grupos_Playoff','Solo_Ida','Fase_Grupos','2026-10-22','2026-10-22','2026-10-21 19:45:22'),(4,1,4,4,'Básquetbol Mixto - ANESAPA 2026','Mixto','Grupos_Playoff','Solo_Ida','Fase_Grupos','2026-10-23','2026-10-24','2026-10-21 19:45:22'),(5,1,5,5,'Raqueta Frontón - ANESAPA 2026','Masculino','Grupos_Playoff','Solo_Ida','Fase_Grupos','2026-10-23','2026-10-23','2026-10-21 19:45:22'),(6,1,6,6,'Media Maratón 5K - ANESAPA 2026','Mixto','Liga','Solo_Ida','Fase_Grupos','2026-10-24','2026-10-24','2026-10-21 19:45:22'),(7,1,7,7,'Cacho (Juego de Salón) - ANESAPA 2026','Mixto','Eliminatoria','Solo_Ida','Playoffs','2026-10-23','2026-10-23','2026-10-21 19:45:22'),(8,1,8,8,'Karaoke (Noche de Talentos) - ANESAPA 2026','Mixto','Liga','Solo_Ida','Fase_Grupos','2026-10-22','2026-10-22','2026-10-21 19:45:22'),(9,1,9,9,'Billar (Juego de Salón) - ANESAPA 2026','Mixto','Eliminatoria','Solo_Ida','Playoffs','2026-10-23','2026-10-23','2026-10-21 19:45:22'),(10,1,10,10,'Competencias Técnicas - ANESAPA 2026','Mixto','Liga','Solo_Ida','Fase_Grupos','2026-10-24','2026-10-24','2026-10-21 19:45:22'),(11,1,11,11,'Juegos Populares Tradicionales - ANESAPA 2026','Mixto','Liga','Solo_Ida','Fase_Grupos','2026-10-24','2026-10-24','2026-10-21 19:45:22');
/*!40000 ALTER TABLE `torneos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador General COSMOL','admin','admin@cosmol.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ADMIN','Activo','2026-10-21 19:45:22','2026-10-21 19:45:22'),(2,'Comité ANESAPA','usuario','anesapa@cosmol.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','USUARIO','Activo','2026-10-21 19:45:22','2026-10-21 19:45:22');
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

-- Dump completed on 2026-10-21 15:47:04
