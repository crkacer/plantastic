-- MySQL dump 10.13  Distrib 5.5.57, for debian-linux-gnu (x86_64)
--
-- Host: 0.0.0.0    Database: c9
-- ------------------------------------------------------
-- Server version	5.5.57-0ubuntu0.14.04.1

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
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_id_unique` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (9,'715','15',NULL,'2017-11-10 19:09:04','2017-11-10 19:09:04'),(10,'715','3',NULL,'2017-11-10 19:09:05','2017-11-10 19:09:05'),(11,'716','1',NULL,'2017-11-10 19:23:08','2017-11-10 19:23:08'),(12,'715','1',NULL,'2017-11-10 19:38:19','2017-11-10 19:38:19'),(13,'717','1',NULL,'2017-11-10 19:39:00','2017-11-10 19:39:00'),(14,'718','14',NULL,'2017-11-10 20:20:22','2017-11-10 20:20:22'),(15,'715','14',NULL,'2017-11-10 20:32:10','2017-11-10 20:32:10'),(16,'722','14',NULL,'2017-11-10 20:35:59','2017-11-10 20:35:59'),(17,'725','3',NULL,'2017-11-10 20:39:43','2017-11-10 20:39:43'),(18,'725','14',NULL,'2017-11-10 20:41:59','2017-11-10 20:41:59'),(19,'730','1',NULL,'2017-11-11 02:52:27','2017-11-11 02:52:27'),(20,'729','1',NULL,'2017-11-11 02:52:35','2017-11-11 02:52:35'),(21,'718','1',NULL,'2017-11-11 02:52:51','2017-11-11 02:52:51'),(22,'724','1',NULL,'2017-11-12 02:19:04','2017-11-12 02:19:04'),(23,'731','15',NULL,'2017-11-12 21:20:08','2017-11-12 21:20:08'),(24,'732','3',NULL,'2017-11-16 02:37:17','2017-11-16 02:37:17');
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_id_unique` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Conference','conference','2017-10-12 07:56:34','2017-10-12 07:56:34'),(2,'Meeting','meeting','2017-10-12 07:56:34','2017-10-12 07:56:34'),(3,'Seminar','seminar','2017-10-12 07:56:34','2017-10-12 07:56:34'),(4,'Career fair','career fair','2017-10-12 07:56:34','2017-10-12 07:56:34'),(5,'Symposium','symposium','2017-10-12 07:56:34','2017-10-12 07:56:34'),(6,'Training','training','2017-10-12 07:56:34','2017-10-12 07:56:34'),(7,'Community/social','community/social','2017-10-12 07:56:34','2017-10-12 07:56:34'),(8,'Performance','performance','2017-10-12 07:56:34','2017-10-12 07:56:34'),(9,'Athelitcs','athelitcs','2017-10-12 07:56:34','2017-10-12 07:56:34');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_types`
--

DROP TABLE IF EXISTS `event_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_types_id_unique` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_types`
--

LOCK TABLES `event_types` WRITE;
/*!40000 ALTER TABLE `event_types` DISABLE KEYS */;
INSERT INTO `event_types` VALUES (1,'Private','Event related to a specific party, pub gathering, bar gathering, night club gathering...','2017-10-12 07:52:12','2017-10-12 07:52:12'),(2,'Public','Event opened to all members: faculty and students','2017-10-12 07:53:19','2017-10-12 07:53:19');
/*!40000 ALTER TABLE `event_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location` varchar(700) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(700) COLLATE utf8_unicode_ci NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `user_id` int(10) unsigned DEFAULT NULL,
  `organizer_description` text COLLATE utf8_unicode_ci,
  `event_type_id` int(10) unsigned DEFAULT NULL,
  `background_photo` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `template` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `category_id` int(10) unsigned DEFAULT NULL,
  `url` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `registered_amount` bigint(20) unsigned NOT NULL,
  `capacity` bigint(20) unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000000',
  `price` bigint(20) unsigned NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lng` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_id_unique` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=733 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (715,'180 Yonge Ave','GBC Basketball State Finals','2018-01-17 00:00:00','2018-01-17 00:00:00','0000-00-00 16:00:00','0000-00-00 20:00:00','This event is tailored for all GBC students, come and support the Huskies as they fight for their 9th championship in the state finals',1,'GBC Athletics Faculty',1,'/assets/img/blur.jpg','A',8,'',4,2000,'0000000',10,NULL,'2017-11-10 19:08:52','2017-11-15 22:13:53',43.6524,-79.3792),(716,'450 King St','GBC Hotel Management Conference','2018-01-22 00:00:00','2018-01-22 00:00:00','0000-00-00 18:00:00','0000-00-00 20:00:00','This event is tailored for all GBC Hotel Management students, come and learn new management techniques implemented in 5 star hotels around the world',1,'GBC Hotel Management Faculty',1,'/assets/img/Hotel.jpg','B',1,'',1,500,'0000000',12,NULL,'2017-11-10 19:20:05','2017-11-10 19:23:08',43.6509,-79.3703),(717,'22 Bloor St','Game Programming Club Meeting','2016-02-22 00:00:00','2016-02-22 00:00:00','0000-00-00 14:00:00','0000-00-00 15:00:00','This event is tailored for all GBC students, if you are interested in learning new game programming languages please come and join this club meeting',1,'GBC Student Association',1,'/assets/img/Game.jpg','A',2,'',1,100,'0000000',0,NULL,'2017-11-10 19:35:21','2017-11-10 19:39:00',43.6431,-79.4245),(718,'214 King West St','Fashion Design Seminar','2017-12-22 00:00:00','2017-12-22 00:00:00','0000-00-00 18:00:00','0000-00-00 20:00:00','This event is tailored for GBC Fashion Design students, come and joing us in a seminar where 5 different clothing design styles will be exposed by the famous designers from NYC',1,'GBC Fashion Design Faculty',2,'/assets/img/Fashion.jpg','A',3,'',2,500,'0000000',10,NULL,'2017-11-10 19:41:33','2017-11-11 02:52:51',43.6454,-79.4134),(721,'311 Queen West St','Computer Technology Career Fair','2017-11-22 00:00:00','2017-11-22 00:00:00','0000-00-00 15:00:00','0000-00-00 20:00:00','This event is tailored for all GBC Computer Technology students, come and introduce yourself to the largest companies in computer technology in the GTA',1,'GBC Computer Technology Faculty',2,'/assets/img/career.png','A',4,'',0,1000,'0000000',0,NULL,'2017-11-10 20:12:14','2017-11-10 20:12:14',43.7082,-79.3984),(722,'31 Church West St','GBC Technology Symposium','2015-11-22 00:00:00','2015-11-22 00:00:00','0000-00-00 15:00:00','0000-00-00 20:00:00','This event is tailored for all GBC Computer Technology students, come and enjoy a symposium night where 3 of the largest technology pioneers in Toronto will provide their points of view about what the future awaits in technology',1,'GBC Computer Technology Faculty',2,'/assets/img/Symposium.jpg','A',5,'',1,1000,'0000000',20,NULL,'2017-11-10 20:16:21','2017-11-10 20:35:59',43.6684,-79.386),(723,'31 Church West St','GBC Fashion Design Symposium','2019-11-22 00:00:00','2019-11-22 00:00:00','0000-00-00 13:00:00','0000-00-00 20:00:00','This event is tailored for all GBC Fashion design students, come and enjoy a symposium night where 3 of the largest fashion pioneers in Toronto will provide their points of view about what the future awaits in fashion design',1,'GBC Fashion Design Faculty',2,'/assets/img/Fashion2.jpg','A',5,'',0,1000,'0000000',10,NULL,'2017-11-10 20:21:39','2017-11-10 20:21:39',43.6655,-79.4092),(724,'122 Jarvis Ave','Unity Training for Game Programmers','2020-02-21 00:00:00','2020-02-21 00:00:00','0000-00-00 14:00:00','0000-00-00 20:00:00','This event is tailored for all GBC Computer Technology students, come and learn working with game engines such as Unity in order to master programming skills when developing new game applications',1,'GBC Computer Technology Faculty',2,'/assets/img/Unity.jpg','B',6,'',1,300,'0000000',60,NULL,'2017-11-10 20:26:47','2017-11-12 02:19:04',43.6656,-79.4073),(725,'12 Jane St','Soccer Training','2018-02-21 00:00:00','2018-02-21 00:00:00','0000-00-00 14:00:00','0000-00-00 20:00:00','This event is tailored only for members of the GBC Soccer Team, come and learn new soccer techniques in order to improve your game for the upcoming season.',1,'GBC Athletics Faculty',1,'/assets/img/blur.jpg','B',6,'',2,30,'0000000',0,NULL,'2017-11-10 20:35:18','2017-11-15 21:42:54',43.6624,-79.3955),(726,'12 Duplex Ave','Community Fundraising','2017-03-21 00:00:00','2017-03-23 00:00:00','0000-00-00 14:00:00','0000-00-00 15:00:00','This event is tailored for all GBC students and faculty, come and join us while we gather money and items for the most needed in Toronto. We will be collecting all different items which will be donated to charity.',1,'GBC Athletics Faculty',2,'/assets/img/Fundraising.jpg','B',7,'',0,1000,'0000000',0,NULL,'2017-11-10 20:39:51','2017-11-10 20:39:51',43.6575,-79.3811),(727,'15 Rex Ave','Community Fundraising v 2.0','2017-04-21 00:00:00','2017-04-23 00:00:00','0000-00-00 14:00:00','0000-00-00 15:00:00','This event is tailored for all GBC students and faculty, come and join us while we gather money and items  for the second time for the most needed in Toronto. We will be collecting all different items which will be donated to charity.',1,'GBC Faculty',2,'/assets/img/fundraising2.jpg','B',7,'',0,1000,'0000000',0,NULL,'2017-11-10 20:44:18','2017-11-10 20:44:18',43.6583,-79.3819),(728,'15 Rex Ave','GBC Hockey State Finals','2017-05-02 00:00:00','2017-05-02 00:00:00','0000-00-00 16:00:00','0000-00-00 19:00:00','This event is tailored for all GBC students and faculty, come and join us supporting your hockey team as they fight for their 1st state championship.',1,'GBC Athletics Faculty',2,'/assets/img/Hockey.jpg','A',9,'',0,2000,'0000000',50,NULL,'2017-11-10 20:49:42','2017-11-10 20:49:42',43.6639,-79.3078),(729,'15 Kendal Ave','GBC Piano Night','2017-06-04 00:00:00','2017-06-04 00:00:00','0000-00-00 20:00:00','0000-00-00 22:00:00','This event is tailored for all GBC students and faculty, come and join us to enjoy a magical Piano night where 2 of the best Montreal Piano players will delight us with their presence.',1,'GBC Art Faculty',2,'/assets/img/piano.jpg','A',8,'',1,800,'0000000',20,NULL,'2017-11-10 20:53:18','2017-11-11 02:52:35',43.6639,-79.3078),(730,'15 Kendal Ave','Opera Night','2014-03-04 00:00:00','2014-03-04 00:00:00','0000-00-00 20:00:00','0000-00-00 22:00:00','This event is tailored for all GBC students and faculty, come and join us to enjoy a magical Opera night with amazing performers from Quebec.',1,'GBC Art Faculty',2,'/assets/img/opera.jpg','A',8,'',1,500,'A8C75730',30,NULL,'2017-11-10 20:57:32','2017-11-11 02:52:27',43.6549,-79.3655),(731,'123 Kendal Ave','GBC Volleyball State Final','2017-11-13 00:00:00','2017-11-13 00:00:00','0000-00-00 03:20:00','0000-00-00 04:55:00','Come and support the GBC Volleyball team',15,'GBC Athletics Faculty',2,'/assets/img/blur.jpg','A',9,'',1,1000,'0000000',10,NULL,'2017-11-12 21:18:58','2017-11-12 21:20:08',43.6547,-79.3686),(732,'248 Pape Ave, Toronto, ON M4M 2W5, Canada','Indie Game ','2017-11-14 00:00:00','2017-11-14 00:00:00','0000-00-00 01:13:00','0000-00-00 22:13:00','test',1,'test',1,'/assets/img/blur.jpg','A',1,'',1,8,'9CII732',100,NULL,'2017-11-15 19:14:22','2017-11-16 02:37:17',43.6655,-79.3394);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table',1),('2017_10_11_015402_create_events_table',1),('2017_10_11_022248_create_event_type_table',1),('2017_10_11_022418_create_category_table',1),('2017_10_27_204050_create_table_attendance',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `DOB` datetime NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profile_picture` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `social_network` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jehn Kennedy','john.k@gmail.com','$2y$10$viI/nUBK0Oh7fGZRrug0Tuz/dFqmfYRkiRsJpxT2Te94zhYw.K1dG','female','1993-02-16 00:00:00','Jehn','Kennedy','/assets/img/myAvatar.png','https://www.facebook.com/groups/kingdomheartsUX/?multi_permalinks=1922627784723047&notif_id=1510689421230572&notif_t=group_highlights','hvJShGTSoe100hprLktn3jyZ1hIEphWoLEKWfWRSAdxcrCGWdEqxSlfvt6Si','2017-10-12 07:58:15','2017-11-15 22:50:12'),(2,'Peter Jackson','peter.j@gmail.com','$2y$10$cARt6i4uR2auZks7ox1qDeghfJ7a7F7P1F/qHPwT/N41NNT06mG5W','female','1995-03-04 00:00:00','Peter','Jackson','/assets/img/myAvatar.png',NULL,NULL,'2017-10-12 07:59:35','2017-10-12 07:59:35'),(3,'Huy ','huyst1234567890@gmail.com','$2y$10$RxpZH2dumjTZ8s92cw/dBeEvohgWzPPfpr8egjV3.kiJF7l3KMwEa','male','1997-03-06 00:00:00','Huy ','Dam','/assets/img/myAvatar.png',' ','0uV2vywJVmKx9n2ANQqQBvOLUkIsXWkMEZMQ2jqTl8bYgwRGkvEpby2jSU2N','2017-11-10 00:48:30','2017-11-13 04:28:21'),(4,'Daddy','mydaddy@gmail.com','$2y$10$KTm9nPYNOScHRd.9U7qQKOus/pcMXVvX5lIw2BLg5MM8GDidjawBi','male','1995-05-09 00:00:00','Daddy','Boss','/assets/img/myAvatar.png',' ','9bo6Ww2fSmdlp6mOvtpfvLAtx0SUshAsAepCEX5LnyZ5asnisJJHvZqDa6ha','2017-11-10 00:49:01','2017-11-10 00:50:13'),(13,'D','huydam@gmail.com','$2y$10$wcea4e0v4m4KPHbcjEINA.uk/eGxkibTyU3g6iY5Bp/aogI39jJI2','male','2000-11-09 00:00:00','D','H','/assets/img/myAvatar.png',' ',NULL,'2017-11-10 00:50:26','2017-11-10 00:50:26'),(14,'Ken','kkk22@gmail.com','$2y$10$gKbbJCt92SszRYFHFnS2te022X7uH0HCr/ctXozODT8A0GLWdMoAG','male','2000-11-09 00:00:00','Ken','Todd','/assets/img/myAvatar.png',' ','Zb6Vz2g7eYfkPpGgQ9uSXr5S8NIZ3HxJPpeghpmLTSGGwAHT6IF0p2xNcue0','2017-11-10 00:50:45','2017-11-10 20:52:04'),(15,'Allan Malagonn','user1@gmail.com','$2y$10$oHZYQYjk27QAYgRE47jvWucpmIs2cjLlMD.Nt7TkzrP9kwRaGYsUa','male','1985-07-25 00:00:00','Allan','Malagonn','/assets/img/myAvatar.png',' ','M86gexYWz8ukWoyq33anTbss2I60KAsclExdUCEKrc248QCEvVcpi2B15PBD','2017-11-10 17:17:34','2017-11-15 22:40:50'),(18,'Duc','minhducng93@gmail.com','$2y$10$Ye1Iea5PvJV0d4rSqj4Z7uajbpo9DAlBnioGy9IMYdClev6p.Np6i','male','1993-07-10 00:00:00','Duc','Nguyen','/assets/img/myAvatar.png',' ','k35ua7UXu4dyQFSJKFBHrdz63mDGOD8hlFTwWCuWePpnd2FrZoFUvrwmZI9f','2017-11-13 04:25:53','2017-11-13 04:26:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-16  3:04:25
