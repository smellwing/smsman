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
-- Table structure for table `sentlogs`
--

DROP TABLE IF EXISTS `sentlogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sentlogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workload_id` int(11) NOT NULL,
  `task_id` int(10) unsigned DEFAULT NULL,
  `lyric_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lyric_message_id` int(10) unsigned DEFAULT NULL,
  `success` tinyint(1) DEFAULT NULL COMMENT '1: Message status was retrieved succesfully 0: An error ocurred',
  `message_status` tinyint(2) unsigned DEFAULT NULL COMMENT 'If an error ocurred, this field won''t be present on the response.     0: New     1: Processing     2: Sent     3: Failure ',
  `last_error` tinyint(2) unsigned DEFAULT NULL COMMENT 'If an error ocurred, this field won''t be present on the response.     0: None     1: Unknown     2: Destination number     3: Content     4: Network, temporal     5: Simcard',
  `n_tries` int(10) unsigned DEFAULT NULL COMMENT 'Number of failed attempts to send the message ',
  `num` bigint(20) unsigned DEFAULT NULL COMMENT 'Destination number of the message',
  `channel` tinyint(3) DEFAULT NULL COMMENT 'Last channel that attempted to send the message, successfully or not ',
  `send_date` timestamp NULL DEFAULT NULL COMMENT 'Date in seconds from epoch when the message was sent ',
  `recv_date` timestamp NULL DEFAULT NULL COMMENT 'Date in seconds from epoch when the message was queued ',
  `report_stage` tinyint(2) unsigned DEFAULT NULL COMMENT 'Possible values are: 0: No status report has been received yet 1: Temporary status report. More reports should be expected to arrive. 2: Final status report. Final report received for this message. ',
  `delivery_status` smallint(4) unsigned DEFAULT NULL COMMENT 'Status code contained in the report. Possible values are: 0: Message delivered 1: Message forwarded by the SC but unable to confirm delivery 2: Message replaced by the SC 32: Congestion 35: Service rejected 48: Specific to each SC 65: Incompatible destination',
  `delivery_date` timestamp NULL DEFAULT NULL COMMENT 'Date of the last status report received for the message ',
  `error_code` varchar(255) DEFAULT NULL COMMENT 'See error codes section',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `workload_id` (`workload_id`),
  KEY `workload_id_2` (`workload_id`),
  KEY `task_id_2` (`task_id`,`lyric_id`,`success`),
  KEY `id` (`task_id`,`lyric_id`,`success`,`id`) USING BTREE,
  KEY `lyric_recicla` (`task_id`,`lyric_id`,`message_status`,`last_error`,`send_date`,`error_code`,`created`) USING BTREE,
  KEY `task_id` (`task_id`,`delivery_date`,`error_code`)
) ENGINE=InnoDB AUTO_INCREMENT=10536517 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-07 12:00:46
