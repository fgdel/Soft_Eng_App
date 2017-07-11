CREATE DATABASE  IF NOT EXISTS `itb_cdm` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `itb_cdm`;
-- MySQL dump 10.13  Distrib 5.7.9, for linux-glibc2.5 (x86_64)
--
-- Host: localhost    Database: itb_cdm
-- ------------------------------------------------------
-- Server version	5.7.12

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
-- Table structure for table `cv`
--

DROP TABLE IF EXISTS `cv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cv_id` varchar(24) DEFAULT NULL,
  `address_1` varchar(120) DEFAULT NULL,
  `address_2` varchar(120) DEFAULT NULL,
  `address_3` varchar(120) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `personal_statement` blob,
  `curr_ed` varchar(200) DEFAULT NULL,
  `curr_gpa` char(4) DEFAULT NULL,
  `former_ed_1` varchar(200) DEFAULT NULL,
  `former_ed_2` varchar(200) DEFAULT NULL,
  `former_ed_3` varchar(200) DEFAULT NULL,
  `interests` blob,
  `referee` varchar(60) DEFAULT NULL,
  `curr_emp` varchar(200) DEFAULT NULL,
  `former_emp_1` varchar(200) DEFAULT NULL,
  `former_emp_2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cv`
--

LOCK TABLES `cv` WRITE;
/*!40000 ALTER TABLE `cv` DISABLE KEYS */;
INSERT INTO `cv` VALUES (1,'EAGames','Somewhere','Somewhere','Somewhere','0185000000','I am a super-capable person who plays sports video games all day. Recently volunteered as tester for Google Olympics fruit games. Excellent digital skills. Awesome personality.','Level 8, HDip. Computer Science, at ITB','0.00','Level 8, BSc at DIT. 2013','','','Costume-maker for film and video. Member of Offbeat Ensemble, community orchestra. Attending sons football matches.','Gerome Donelly, lecturer at ITB.','none','Bar Staff at Croke Park, Drinks Concession','Sales Assist. SPAR, Phibsboro.'),(2,'FM104','Somewhere','Somewhere','Somewhere','08565656565','I am a social media specialist, with graphic design and video production skills. Keen to establish a career in Irish broadcast media.','Yr 2, Level 8, BSc Creative Digital Media at ITB','4.83','St Declans College, Cabra, D9','','','Member of a local rock and roll band. Perform gigs at birthdays, barmitzvahs etc..','Robert Smith, lecturer at ITB.','Mixed Martial Arts bootcamp trainer.','Nightclub Bouncer, The Right Place','None');
/*!40000 ALTER TABLE `cv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employer`
--

DROP TABLE IF EXISTS `employer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `address_1` varchar(100) DEFAULT NULL,
  `address_2` varchar(100) DEFAULT NULL,
  `address_3` varchar(100) DEFAULT NULL,
  `description` tinyblob,
  `contact_name` varchar(60) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employer`
--

LOCK TABLES `employer` WRITE;
/*!40000 ALTER TABLE `employer` DISABLE KEYS */;
INSERT INTO `employer` VALUES (1,'ITB','http://itb.ie','North Rd.','Blanchardstown','Dublin 15','The LINC is a tangible link between the Institute and the Business Community.','Matt Smith','9876543'),(2,'Electronic Arts','http://www.ea.com/','elsewhere','elsewhere','elsewhere','Founded in 1982, Electronic Arts Inc. is a leading global interactive entertainment software company.','Sharon Francis','009009009'),(3,'104FM','http://fm104.ie/Home','somewhere','somewhere','elsewhere','Dublins hippest radio station and a whole lot more.','Hipster Harry','444444'),(4,'Harvest Energy','http://harvestenergy.ie','somewhere','somewhere','elsewhere','Sustainable and renewable building and renovation work.','Colin Daly','123123'),(5,'Viral Viral Videos','http://viralviralvideos.com','somewhere','somewhere','elsewhere','Publisher, creator and licencer of viral webvideos.','Sith_Lord','666 666');
/*!40000 ALTER TABLE `employer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `placement`
--

DROP TABLE IF EXISTS `placement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `placement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(24) NOT NULL,
  `role` varchar(45) NOT NULL,
  `company` varchar(60) NOT NULL,
  `company_url` varchar(255) DEFAULT NULL,
  `deadline` date NOT NULL,
  `description` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `placement`
--

LOCK TABLES `placement` WRITE;
/*!40000 ALTER TABLE `placement` DISABLE KEYS */;
INSERT INTO `placement` VALUES (2,'Harvest_01','Web Developer','Harvest Energy',NULL,'2016-08-01','Web developer needed to work on our company website, social channels and other projects.The ideal candidate will have an eye for design, along with practical web development abilities.'),(3,'VVV_01','Kittens Viral Video Creator','Viral Viral Videos','http://viralviralvideos.com','2016-08-01','Trainee digital video creator to create and publish viral kitten videos to our website. You will bring fresh ideas and dynamic workflow to the cutthroat viral kitten video scene. Adept at using Final Cut, Adobe products and popular video social channels: YouTube, Vimeo, Reddit.'),(4,'Radio_FM104','Social Content Creater','FM104',NULL,'2016-08-17','Trainee digital media designer to support creation and maintenance of marketing assets and content.'),(5,'Cafe-2Boys','Social Content Manager','Two Boys Brew',NULL,'2016-06-30','Community manager and social content creator required for this up and coming cafe in D7. Facebook, Twitter, Instagram and assorted review sites are their core social presence.'),(6,'GraphicDesk','Trainee graphic designer','Graphix',NULL,'2016-08-17','Role for enthusiastic and imaginative jnr designer with busy but happy team.');
/*!40000 ALTER TABLE `placement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `description` tinyblob NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` varchar(4) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
INSERT INTO `profile` VALUES (1,'http://sewmuchtime.wordpress.com','Bespoke digital artifacts created for you.',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'https://behance.net/gallery/34694629/ART-DE-PAS/','Graphic artist and budding art director.',NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'fiona','delaney','fion@fiona.com','fiona','$2y$10$ixYkvo8bsCNcY052BzXDgOTE/U9qcTFcheMfuTGH/qZq7w05tHuuS','2'),(4,'Matt','Smith','matt@smith.com','matt','$2y$10$mfackrQqsgJwxOhNI.X3.uArL7ALBp43S8bsx0nKYjcdiiLYU1Ax.','1'),(5,'Paul','Rivers','paulrivers@shop.com','Paul_Rivers','$2y$10$X3NoiRf0xCQ/oa.sz/t4xucfBIFG39GfhPi.Y4EQyX0TLC9zF4wCq','3'),(7,'Ciaran','Alexander','Cici@alexander.com','Ciaran','$2y$10$JWC2Z362cpBE6kazzFuaf.vcOoaBU5WYwz1TARtt6KW96CgszbwXi','3'),(8,'Sharon','Plankston','sharon_p@FM104.com','FM104','$2y$10$sYlb9LeZZXwFY0Y8HR8CBOCtx/.7rZ/Wt6QTYky1AYOEnUt16WaXK','3'),(9,'Colin','Daly','colin@harvest.com','Colin_Harvest','$2y$10$3kxXMoNz6.8EPntpLFDLxu2IgRDYZr0x1Gt3IT0Utb5SOvLgWHEaK','3'),(10,'Brandon','Berkley','boy_1@2boysbrew.ie','boy_1','$2y$10$SGYPwuv31ig1x7ksnR4rgeW5o3N6ReArJWFoRZTVDzivEtwKN5.ce','3'),(11,'Xang','Wu','xang_wu@vvv.com','kitten','$2y$10$5ySUJ/QSb95iHprjaw4cn.YrzcyfK0u5fcm62nanivGarMDzklUdS','3');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-15 17:31:50
