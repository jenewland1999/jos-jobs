-- MySQL dump 10.16  Distrib 10.1.37-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database:
-- ------------------------------------------------------
-- Server version	10.1.37-MariaDB

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
-- Current Database: `job`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `job` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `job`;

--
-- Table structure for table `applicants`
--

DROP TABLE IF EXISTS `applicants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applicants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` longblob,
  `jobId` int(11) DEFAULT NULL,
  `cv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applicants`
--

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'IT'),(2,'Human Resources'),(4,'Sales');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job`
--

DROP TABLE IF EXISTS `job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longblob,
  `salary` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closingDate` date DEFAULT NULL,
  `categoryId` int(11) DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job`
--

LOCK TABLES `job` WRITE;
/*!40000 ALTER TABLE `job` DISABLE KEYS */;
INSERT INTO `job` VALUES (3,'First level tech support','Job overview:\r\n\r\nTo work alongside the IT field based team in one of our acute Hospital sites. This team provides high quality equipment installation, technical support and advisory services for EKHUFT staff. They proactively manage incidents and requests, accepting ownership, evaluating, resolving and enabling the rapid resolution of a broad range of issues. This will include the testing and implementing of new and replacement hardware and appropriate software and the resolving of malfunctions. They look to achieve high standards of customer service and delivery of maximum business benefits.\r\n\r\nMain responsibilities:\r\n\r\n\r\n    To analyse incidents and deliver technical resolutions as part of the IT support\r\n\r\n    Service, to contribute to an efficient and effective IT service desk.\r\n\r\n    Review tickets within Service Management systems using established priorities.\r\n\r\n    Man the helpdesk telephone on a daily basis to resolve issues for end users.\r\n\r\n    Learn what notes and updates should be done on tickets.\r\n\r\n    The initial investigation and resolution of incidents relating to business and\r\n\r\n    desktop applications, and subsequent referral to either senior support analysts, to\r\n\r\n    2nd/ 3rd line support, the application management team or a 3rd party.\r\n\r\n    Learn to deliver end user introductory training on IT systems.\r\n\r\n    Undertakes daily operational checks as defined and trained by the wider team.\r\n\r\n    Participate in projects as required.\r\n\r\n    Support tracking of IT assets (software and hardware) using software tools.\r\n\r\n    Setup workstations and laptops for new starters\r\n\r\n    Support with physical desk moves between locations\r\n\r\n    Deploy and install software to computers\r\n\r\n    Perform password resets and help end users with profile and connectivity issues.\r\n\r\n    Allow and remove access to folders and email distribution lists\r\n\r\n    Perform basic proactive tasks for backups and learn how to restore backups.\r\n\r\n    Carry out any other duties as required by the IT Management Team.','£15,000 - £18,000','2020-04-09',1,'Northampton'),(4,'IT Infrastructure Manager','About the role\r\n\r\nAs an experienced IT Infrastructure Manager, you will work closely with the Head of IT to design and deliver a robust, secure, and flexible IT solution.\r\n\r\nTaking day-to-day responsibility for the smooth running of the IT systems, you will ensure that full business continuity plans are in place for our IT systems and services.\r\n\r\nYou will work closely with the Park Services and Events teams to ensure that appropriate access to IT services, including CCTV, is available throughout the Parks.','£45,000 - £58,000','2020-05-15',1,'Northampton'),(5,'Sales Assistant','Our client is an award winning sales and marketing organisation; who are looking to enhance their sales team with independent individuals who are capable of seeking and developing new opportunities within the sales and marketing industry.\r\n\r\nWithin this opportunity you will be working alongside the best sales and marketing specialists, promoting an exciting client portfolio. You will represent iconic brands and play a very important role in ongoing business success while developing your skills in residential environments. This opportunity will provide high rewards both career wise, and financially.\r\n\r\nThe successful candidate will be a well-presented, self-starter capable of demonstrating a desire to succeed in a sales environment.\r\n\r\nSuccessful candidates will:\r\n\r\n    Have strong communication skills\r\n    Be self-motivated\r\n    Possess an impeccable work ethic\r\n    Have a tenacious approach to personal development\r\n    Possess a competitive sales mentality\r\n    Have an entrepreneurial mind-set\r\n    Team work\r\n\r\nIf you can demonstrate the qualities as set out above and believe that you have the ability to develop new business, they would like to hear from you!\r\n\r\nNo experience is necessary although our client welcomes candidates with any previous experience in the following areas: customer service, sales representative, marketing supervisor, sales executive, direct sales, field sales, marketing executive, retail, service supervisor, call centre, call centre inbound, marketing representative, manager, bar manager, hospitality, receptionist, warehouse, marketing assistant, front of house, direct marketing, sales assistant, and any other customer service or sales role. This is a self employed commission only opportunity with the ability to create your own future.','£12,000 - £15,000','2020-05-29',4,'Northampton'),(6,'HR Manager','HR Manager: An ambitious HR Manager is required to help deliver an effective and comprehensive Human Resource service to a growing organisation with fully-funded plans to double in size over the next 18 months.\r\n\r\nWorking in a consultative manner, the successful HR Manager will work on a standalone basis to ensure quality advice and support is provided as part of the journey to make the organisation an industry leading \"Employer of Choice\".\r\n\r\nThis exciting new role would ideally suit an ambitious generalist HR professional eager to take on a dynamic position offering genuine career progression opportunities.\r\n\r\nKey Responsibilities\r\n\r\n    Provide HR support and advice to management on company HR policies and procedures, including employment law advice e.g. disciplinary, grievance, performance.\r\n    Provide high-quality recruitment and selection service to all departments including the use of social media.\r\n    Develop and implement HR policy and practice, contract templates, HR documentation and HR database developments, ensuring that all are up to date with UK legislation.\r\n    Provide ongoing employee relations support and advice to whole firm relating to contractual and general HR matters.\r\n    Review compensation and benefit plans e.g. salary review, bonus plan and other non-specified benefit plans.\r\n    Propose and advise on internal and external training for employees.\r\n    Create career path models to include job descriptions, person specs and competency models for all roles to support individuals\' career progression.\r\n    Manage the HR calendar: performance reviews, salary reviews, development planning, ensuring these processes support the ongoing strategic growth plan.\r\n    Develop the organisation culture to ensure \"Employer of Choice\" status is attained through determining the current culture, proposing organisation initiatives and then implementing after approval to achieve \"EOC\" tag.','£35,000 - £40,000','2020-05-29',2,'Northampton');
/*!40000 ALTER TABLE `job` ENABLE KEYS */;
UNLOCK TABLES;
