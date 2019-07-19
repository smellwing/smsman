-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 192.168.1.200    Database: smsfront
-- ------------------------------------------------------
-- Server version	5.7.22-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `workloads`
--

DROP TABLE IF EXISTS `workloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dni` int(10) unsigned DEFAULT NULL,
  `dni_dv` char(1) DEFAULT NULL,
  `phone` int(9) unsigned DEFAULT NULL,
  `lista_id` int(10) unsigned NOT NULL,
  `file_id` int(10) unsigned NOT NULL,
  `link_id` int(10) unsigned DEFAULT NULL,
  `message_id` int(10) unsigned DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `deuda` int(10) unsigned DEFAULT NULL,
  `sucursal` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `cuenta` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`lista_id`,`file_id`),
  KEY `CARGAS_idx` (`file_id`,`dni`,`phone`),
  KEY `link_id` (`link_id`),
  KEY `dni` (`dni`,`phone`,`lista_id`),
  KEY `lista_id` (`lista_id`),
  KEY `lista_id_2` (`lista_id`,`created`)
) ENGINE=InnoDB AUTO_INCREMENT=9770213 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-07 12:00:47
