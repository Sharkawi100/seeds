-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: juzoor_quiz_local
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
-- Table structure for table `ai_usage_logs`
--

DROP TABLE IF EXISTS `ai_usage_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_usage_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `count` int(11) DEFAULT 1,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ai_usage_logs_type_index` (`type`),
  KEY `ai_usage_logs_created_at_index` (`created_at`),
  KEY `ai_usage_logs_type_created_at_index` (`type`,`created_at`),
  KEY `ai_usage_logs_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_usage_logs`
--

LOCK TABLES `ai_usage_logs` WRITE;
/*!40000 ALTER TABLE `ai_usage_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_usage_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `answers`
--

DROP TABLE IF EXISTS `answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `answers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) unsigned NOT NULL,
  `result_id` bigint(20) unsigned NOT NULL,
  `selected_answer` varchar(191) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `answers_question_id_foreign` (`question_id`),
  KEY `answers_result_id_foreign` (`result_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `answers`
--

LOCK TABLES `answers` WRITE;
/*!40000 ALTER TABLE `answers` DISABLE KEYS */;
INSERT INTO `answers` VALUES (1,1,1,'الحمامة الصغيرة',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(2,2,1,'اللعب مع باقي الحمام',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(3,3,1,'لأنه كان مريضًا',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(4,4,1,'بقيت بجواره ولم تفعل شيئًا',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(5,5,1,'أصبح الطفل خائفًا منها',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(6,6,1,'كانت الحمامة غريبة عن الطفل',1,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(7,7,1,'علمتنا أن نحافظ على الطبيعة والبيئة',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(8,8,1,'لا أعرف إذا كانت تتوقع ذلك أم لا',0,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(9,1,2,'الحمامة البيضاء',0,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(10,2,2,'الرقاد والنوم فقط',0,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(11,3,2,'لأنه كان ضائعًا في البستان',1,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(12,4,2,'بقيت بجواره ولم تفعل شيئًا',0,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(13,5,2,'أصبح الطفل خائفًا منها',0,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(14,6,2,'كانت الحمامة غريبة عن الطفل',1,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(15,7,2,'علمتنا أن نكون أقوياء ومتحدين',0,'2025-05-30 08:55:58','2025-05-30 08:55:58'),(16,8,2,'لا يوجد ما يشير إلى أنها توقعت ذلك',0,'2025-05-30 08:55:58','2025-05-30 08:55:58');
/*!40000 ALTER TABLE `answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('juzoor_welcome_page_data','a:3:{s:14:\"activeSubjects\";a:2:{i:0;a:2:{s:4:\"name\";s:25:\"اللغة العربية\";s:5:\"count\";i:1;}i:1;a:2:{s:4:\"name\";s:12:\"العلوم\";s:5:\"count\";i:1;}}s:5:\"stats\";a:4:{s:13:\"total_quizzes\";i:2;s:14:\"total_attempts\";i:2;s:14:\"active_schools\";i:0;s:15:\"total_questions\";i:18;}s:11:\"growthStats\";a:4:{s:6:\"jawhar\";a:2:{s:10:\"percentage\";d:0;s:6:\"growth\";i:15;}s:4:\"zihn\";a:2:{s:10:\"percentage\";d:17;s:6:\"growth\";i:22;}s:6:\"waslat\";a:2:{s:10:\"percentage\";d:50;s:6:\"growth\";i:18;}s:5:\"roaya\";a:2:{s:10:\"percentage\";d:0;s:6:\"growth\";i:12;}}}',1748624154);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_27_163233_create_personal_access_tokens_table',1),(5,'2025_05_27_165155_create_quizzes_table',1),(6,'2025_05_27_165156_create_questions_table',1),(7,'2025_05_27_165158_create_results_table',1),(8,'2025_05_27_165159_create_answers_table',1),(9,'2025_05_27_192054_add_passage_to_questions_table',1),(10,'2025_05_27_195034_add_is_admin_to_users_table',1),(11,'2025_05_27_200501_add_soft_deletes_to_users_table',1),(12,'2025_05_28_031143_add_description_to_quizzes_table',1),(13,'2025_05_28_031516_add_description_to_quizzes_table',1),(15,'2025_05_29_050329_create_ai_usage_logs_table',1),(16,'2025_05_29_051139_create_ai_usage_logs_table',2),(17,'2025_05_30_000000_add_pin_and_demo_to_quizzes_table',2),(18,'2025_05_30_000001_add_school_fields_to_users_table',2),(19,'2025_05_30_120000_add_missing_columns_to_users_table',2),(20,'2025_05_30_120001_add_quiz_pin_columns',2),(21,'2025_05_30_120002_fix_ai_usage_logs_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) unsigned NOT NULL,
  `question` text NOT NULL,
  `root_type` varchar(191) NOT NULL,
  `depth_level` int(11) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(191) NOT NULL,
  `passage` text DEFAULT NULL,
  `passage_title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `questions_quiz_id_foreign` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,3,'ما هو اسم الحمامة المذكورة في النص؟','jawhar',1,'[\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u0628\\u064a\\u0636\\u0627\\u0621\",\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u0645\\u0637\\u0648\\u0642\\u0629\",\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u0635\\u063a\\u064a\\u0631\\u0629\",\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u062c\\u0645\\u064a\\u0644\\u0629\"]','الحمامة المطوقة','الحمامة المطوقة\r\n\r\nفي إحدى البساتين الخضراء، كانت حمامة بيضاء جميلة تعيش هناك مع قطيع من الحمام الآخرين. كان لها طوق أسود أنيق حول رقبتها، وهذا هو ما أعطاها اسمها: الحمامة المطوقة.\r\n\r\nكانت الحمامة المطوقة تحب الطيران بين الأشجار والنباتات الجميلة. كما أنها كانت تحب البحث عن الحبوب والبذور الطازجة لتطعم نفسها وأفراخها الصغيرة في عشها. وفي المساء، كانت ترقد بهدوء مع باقي القطيع تحت ظلال الأشجار.\r\n\r\nذات يوم، وجدت الحمامة المطوقة طفلاً صغيراً ضائعاً في البستان. كان الطفل يبكي ويبدو خائفاً. فقررت الحمامة المطوقة مساعدته. بدأت تطير حوله وتُصدر أصواتاً مريحة لتهدئته. ثم قادته برفق إلى خارج البستان حيث كان والداه ينتظرانه بقلق.\r\n\r\nفرح الوالدان كثيراً برؤية ابنهما بأمان. وشكروا الحمامة المطوقة على إيجاده وإعادته إليهم. منذ ذلك اليوم، أصبحت الحمامة المطوقة محبوبة من قبل جميع سكان البستان لفعلتها الطيبة.\r\n\r\nهكذا تعلمنا من الحمامة المطوقة أن نساعد الآخرين ونكون رحماء وكرماء، فذلك يجعلنا محبوبين ومقدَّرين من قبل الجميع.','الحمامة المطوقة','2025-05-30 00:54:38','2025-05-30 00:54:38'),(2,3,'ما هي الأشياء التي كانت الحمامة المطوقة تحب القيام بها؟','jawhar',2,'[\"\\u0627\\u0644\\u0637\\u064a\\u0631\\u0627\\u0646 \\u0648\\u0627\\u0644\\u0628\\u062d\\u062b \\u0639\\u0646 \\u0627\\u0644\\u062d\\u0628\\u0648\\u0628 \\u0648\\u0627\\u0644\\u0628\\u0630\\u0648\\u0631\",\"\\u0627\\u0644\\u0631\\u0642\\u0627\\u062f \\u0648\\u0627\\u0644\\u0646\\u0648\\u0645 \\u0641\\u0642\\u0637\",\"\\u0627\\u0644\\u0644\\u0639\\u0628 \\u0645\\u0639 \\u0628\\u0627\\u0642\\u064a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\",\"\\u0627\\u0644\\u063a\\u0646\\u0627\\u0621 \\u0648\\u0627\\u0644\\u062a\\u063a\\u0631\\u064a\\u062f\"]','الطيران والبحث عن الحبوب والبذور',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(3,3,'لماذا كان الطفل الصغير يبكي ويبدو خائفًا؟','zihn',1,'[\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u062c\\u0627\\u0626\\u0639\\u064b\\u0627\",\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u0636\\u0627\\u0626\\u0639\\u064b\\u0627 \\u0641\\u064a \\u0627\\u0644\\u0628\\u0633\\u062a\\u0627\\u0646\",\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u0648\\u062d\\u064a\\u062f\\u064b\\u0627\",\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u0645\\u0631\\u064a\\u0636\\u064b\\u0627\"]','لأنه كان ضائعًا في البستان',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(4,3,'كيف تصرفت الحمامة المطوقة لمساعدة الطفل الصغير؟','zihn',2,'[\"\\u0637\\u0627\\u0631\\u062a \\u062d\\u0648\\u0644\\u0647 \\u0648\\u0623\\u0635\\u062f\\u0631\\u062a \\u0623\\u0635\\u0648\\u0627\\u062a\\u064b\\u0627 \\u0645\\u0631\\u064a\\u062d\\u0629 \\u0644\\u062a\\u0647\\u062f\\u0626\\u062a\\u0647\",\"\\u0642\\u0627\\u062f\\u062a\\u0647 \\u0625\\u0644\\u0649 \\u0648\\u0627\\u0644\\u062f\\u064a\\u0647 \\u0628\\u0639\\u0646\\u0641\",\"\\u0628\\u0642\\u064a\\u062a \\u0628\\u062c\\u0648\\u0627\\u0631\\u0647 \\u0648\\u0644\\u0645 \\u062a\\u0641\\u0639\\u0644 \\u0634\\u064a\\u0626\\u064b\\u0627\",\"\\u0635\\u0631\\u062e\\u062a \\u0644\\u062c\\u0630\\u0628 \\u0627\\u0646\\u062a\\u0628\\u0627\\u0647 \\u0627\\u0644\\u0646\\u0627\\u0633\"]','طارت حوله وأصدرت أصواتًا مريحة لتهدئته',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(5,3,'ما هي النتيجة التي ترتبت على فعل الحمامة المطوقة؟','zihn',3,'[\"\\u0623\\u0635\\u0628\\u062d\\u062a \\u0645\\u062d\\u0628\\u0648\\u0628\\u0629 \\u0645\\u0646 \\u0642\\u0628\\u0644 \\u0633\\u0643\\u0627\\u0646 \\u0627\\u0644\\u0628\\u0633\\u062a\\u0627\\u0646\",\"\\u0644\\u0645 \\u064a\\u0644\\u0627\\u062d\\u0638 \\u0623\\u062d\\u062f \\u0645\\u0627 \\u0641\\u0639\\u0644\\u062a\\u0647\",\"\\u0623\\u0635\\u0628\\u062d \\u0627\\u0644\\u0637\\u0641\\u0644 \\u062e\\u0627\\u0626\\u0641\\u064b\\u0627 \\u0645\\u0646\\u0647\\u0627\",\"\\u0644\\u0645 \\u064a\\u0634\\u0643\\u0631\\u0647\\u0627 \\u0623\\u062d\\u062f \\u0639\\u0644\\u0649 \\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0647\\u0627\"]','أصبحت محبوبة من قبل سكان البستان',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(6,3,'ما هي العلاقة بين الحمامة المطوقة والطفل الصغير؟','waslat',1,'[\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0623\\u0645 \\u0627\\u0644\\u0637\\u0641\\u0644\",\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0635\\u062f\\u064a\\u0642\\u0629 \\u0627\\u0644\\u0637\\u0641\\u0644\",\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u063a\\u0631\\u064a\\u0628\\u0629 \\u0639\\u0646 \\u0627\\u0644\\u0637\\u0641\\u0644\",\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u062d\\u0627\\u0631\\u0633\\u0629 \\u0627\\u0644\\u0637\\u0641\\u0644\"]','كانت الحمامة غريبة عن الطفل',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(7,3,'كيف ارتبطت فعلة الحمامة المطوقة بالقيم التي يجب أن نتعلمها؟','waslat',2,'[\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u0633\\u0627\\u0639\\u062f \\u0627\\u0644\\u0622\\u062e\\u0631\\u064a\\u0646 \\u0648\\u0646\\u0643\\u0648\\u0646 \\u0631\\u062d\\u0645\\u0627\\u0621 \\u0648\\u0643\\u0631\\u0645\\u0627\\u0621\",\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u062d\\u0627\\u0641\\u0638 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0628\\u064a\\u0639\\u0629 \\u0648\\u0627\\u0644\\u0628\\u064a\\u0626\\u0629\",\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u0643\\u0648\\u0646 \\u0623\\u0642\\u0648\\u064a\\u0627\\u0621 \\u0648\\u0645\\u062a\\u062d\\u062f\\u064a\\u0646\",\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u0643\\u0648\\u0646 \\u0645\\u0633\\u0624\\u0648\\u0644\\u064a\\u0646 \\u0648\\u062d\\u0630\\u0631\\u064a\\u0646\"]','علمتنا أن نساعد الآخرين ونكون رحماء وكرماء',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(8,3,'هل تعتقد أن الحمامة المطوقة كانت تتوقع أن تصبح محبوبة بعد مساعدتها للطفل؟','roaya',2,'[\"\\u0646\\u0639\\u0645\\u060c \\u0643\\u0627\\u0646\\u062a \\u062a\\u062a\\u0648\\u0642\\u0639 \\u0630\\u0644\\u0643\",\"\\u0644\\u0627\\u060c \\u0644\\u0645 \\u062a\\u0643\\u0646 \\u062a\\u062a\\u0648\\u0642\\u0639 \\u0630\\u0644\\u0643\",\"\\u0644\\u0627 \\u0623\\u0639\\u0631\\u0641 \\u0625\\u0630\\u0627 \\u0643\\u0627\\u0646\\u062a \\u062a\\u062a\\u0648\\u0642\\u0639 \\u0630\\u0644\\u0643 \\u0623\\u0645 \\u0644\\u0627\",\"\\u0644\\u0627 \\u064a\\u0648\\u062c\\u062f \\u0645\\u0627 \\u064a\\u0634\\u064a\\u0631 \\u0625\\u0644\\u0649 \\u0623\\u0646\\u0647\\u0627 \\u062a\\u0648\\u0642\\u0639\\u062a \\u0630\\u0644\\u0643\"]','لا، لم تكن تتوقع ذلك',NULL,NULL,'2025-05-30 00:54:38','2025-05-30 00:54:38'),(9,4,'ما هي الأشياء الثلاثة التي تحتاجها النباتات لتنمو؟','jawhar',1,'[\"\\u0627\\u0644\\u0645\\u0627\\u0621 \\u0648\\u0627\\u0644\\u062a\\u0631\\u0627\\u0628 \\u0648\\u0627\\u0644\\u062d\\u062c\\u0627\\u0631\\u0629\",\"\\u0627\\u0644\\u0645\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0647\\u0648\\u0627\\u0621 \\u0648\\u0636\\u0648\\u0621 \\u0627\\u0644\\u0634\\u0645\\u0633\",\"\\u0627\\u0644\\u0645\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0633\\u0643\\u0631 \\u0648\\u0627\\u0644\\u0645\\u0644\\u062d\",\"\\u0627\\u0644\\u0647\\u0648\\u0627\\u0621 \\u0648\\u0627\\u0644\\u0646\\u0627\\u0631 \\u0648\\u0627\\u0644\\u062a\\u0631\\u0627\\u0628\"]','الماء والهواء وضوء الشمس','النباتات كائنات حية مدهشة تعيش في كل مكان حولنا. تحتاج النباتات إلى الماء والهواء وضوء الشمس لتنمو وتزدهر. تمتص النباتات الماء من التربة عبر جذورها، وتستخدم أوراقها الخضراء لصنع غذائها من ضوء الشمس في عملية تسمى البناء الضوئي. خلال هذه العملية، تأخذ النباتات ثاني أكسيد الكربون من الهواء وتطلق الأكسجين الذي نتنفسه. النباتات مهمة جداً لحياتنا، فهي تعطينا الغذاء والأكسجين وتجعل عالمنا جميلاً بألوانها المختلفة.','النباتات من حولنا','2025-05-30 13:24:21','2025-05-30 13:24:21'),(10,4,'من أين تمتص النباتات الماء؟','jawhar',1,'[\"\\u0645\\u0646 \\u0627\\u0644\\u0623\\u0648\\u0631\\u0627\\u0642\",\"\\u0645\\u0646 \\u0627\\u0644\\u0623\\u0632\\u0647\\u0627\\u0631\",\"\\u0645\\u0646 \\u0627\\u0644\\u062c\\u0630\\u0648\\u0631\",\"\\u0645\\u0646 \\u0627\\u0644\\u0633\\u0627\\u0642\"]','من الجذور',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(11,4,'ما اسم العملية التي تصنع فيها النباتات غذاءها؟','jawhar',2,'[\"\\u0627\\u0644\\u062a\\u0646\\u0641\\u0633\",\"\\u0627\\u0644\\u0628\\u0646\\u0627\\u0621 \\u0627\\u0644\\u0636\\u0648\\u0626\\u064a\",\"\\u0627\\u0644\\u0647\\u0636\\u0645\",\"\\u0627\\u0644\\u0646\\u0645\\u0648\"]','البناء الضوئي',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(12,4,'لماذا أوراق النباتات خضراء اللون؟','zihn',1,'[\"\\u0644\\u0623\\u0646\\u0647\\u0627 \\u062a\\u062d\\u0628 \\u0627\\u0644\\u0644\\u0648\\u0646 \\u0627\\u0644\\u0623\\u062e\\u0636\\u0631\",\"\\u0644\\u062a\\u0635\\u0646\\u0639 \\u063a\\u0630\\u0627\\u0621\\u0647\\u0627 \\u0645\\u0646 \\u0636\\u0648\\u0621 \\u0627\\u0644\\u0634\\u0645\\u0633\",\"\\u0644\\u0623\\u0646\\u0647\\u0627 \\u0645\\u0635\\u0628\\u0648\\u063a\\u0629\",\"\\u0644\\u062a\\u062e\\u064a\\u0641 \\u0627\\u0644\\u062d\\u0634\\u0631\\u0627\\u062a\"]','لتصنع غذاءها من ضوء الشمس',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(13,4,'كيف تساعد النباتات في تنظيف الهواء؟','zihn',2,'[\"\\u062a\\u0645\\u062a\\u0635 \\u0627\\u0644\\u063a\\u0628\\u0627\\u0631 \\u0641\\u0642\\u0637\",\"\\u062a\\u0623\\u062e\\u0630 \\u062b\\u0627\\u0646\\u064a \\u0623\\u0643\\u0633\\u064a\\u062f \\u0627\\u0644\\u0643\\u0631\\u0628\\u0648\\u0646 \\u0648\\u062a\\u0637\\u0644\\u0642 \\u0627\\u0644\\u0623\\u0643\\u0633\\u062c\\u064a\\u0646\",\"\\u062a\\u0637\\u0644\\u0642 \\u062b\\u0627\\u0646\\u064a \\u0623\\u0643\\u0633\\u064a\\u062f \\u0627\\u0644\\u0643\\u0631\\u0628\\u0648\\u0646\",\"\\u062a\\u0645\\u062a\\u0635 \\u0627\\u0644\\u0623\\u0643\\u0633\\u062c\\u064a\\u0646 \\u0641\\u0642\\u0637\"]','تأخذ ثاني أكسيد الكربون وتطلق الأكسجين',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(14,4,'ماذا يحدث للنبات إذا لم يحصل على ضوء الشمس؟','zihn',2,'[\"\\u064a\\u0646\\u0645\\u0648 \\u0628\\u0634\\u0643\\u0644 \\u0623\\u0633\\u0631\\u0639\",\"\\u064a\\u0635\\u0628\\u062d \\u0644\\u0648\\u0646\\u0647 \\u0623\\u0635\\u0641\\u0631 \\u0648\\u064a\\u0645\\u0648\\u062a\",\"\\u064a\\u062a\\u062d\\u0648\\u0644 \\u0625\\u0644\\u0649 \\u0634\\u062c\\u0631\\u0629\",\"\\u0644\\u0627 \\u064a\\u062d\\u062f\\u062b \\u0634\\u064a\\u0621\"]','يصبح لونه أصفر ويموت',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(15,4,'ما العلاقة بين النباتات والحيوانات؟','waslat',2,'[\"\\u0644\\u0627 \\u062a\\u0648\\u062c\\u062f \\u0639\\u0644\\u0627\\u0642\\u0629\",\"\\u0627\\u0644\\u0646\\u0628\\u0627\\u062a\\u0627\\u062a \\u062a\\u0623\\u0643\\u0644 \\u0627\\u0644\\u062d\\u064a\\u0648\\u0627\\u0646\\u0627\\u062a\",\"\\u0627\\u0644\\u062d\\u064a\\u0648\\u0627\\u0646\\u0627\\u062a \\u062a\\u0623\\u0643\\u0644 \\u0627\\u0644\\u0646\\u0628\\u0627\\u062a\\u0627\\u062a \\u0648\\u0627\\u0644\\u0646\\u0628\\u0627\\u062a\\u0627\\u062a \\u062a\\u0639\\u0637\\u064a \\u0627\\u0644\\u0623\\u0643\\u0633\\u062c\\u064a\\u0646\",\"\\u0627\\u0644\\u062d\\u064a\\u0648\\u0627\\u0646\\u0627\\u062a \\u062a\\u0639\\u0637\\u064a \\u0627\\u0644\\u0645\\u0627\\u0621 \\u0644\\u0644\\u0646\\u0628\\u0627\\u062a\\u0627\\u062a\"]','الحيوانات تأكل النباتات والنباتات تعطي الأكسجين',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(16,4,'كيف ترتبط النباتات بحياة الإنسان اليومية؟','waslat',2,'[\"\\u0644\\u0627 \\u062a\\u0631\\u062a\\u0628\\u0637\",\"\\u0646\\u0623\\u0643\\u0644\\u0647\\u0627 \\u0648\\u0646\\u062a\\u0646\\u0641\\u0633 \\u0627\\u0644\\u0623\\u0643\\u0633\\u062c\\u064a\\u0646 \\u0645\\u0646\\u0647\\u0627\",\"\\u0646\\u0633\\u062a\\u062e\\u062f\\u0645\\u0647\\u0627 \\u0644\\u0644\\u0639\\u0628 \\u0641\\u0642\\u0637\",\"\\u0646\\u0633\\u062a\\u062e\\u062f\\u0645\\u0647\\u0627 \\u0644\\u0644\\u0632\\u064a\\u0646\\u0629 \\u0641\\u0642\\u0637\"]','نأكلها ونتنفس الأكسجين منها',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(17,4,'كيف يمكنك المساعدة في حماية النباتات في مدرستك؟','roaya',2,'[\"\\u0642\\u0637\\u0639 \\u0627\\u0644\\u0623\\u0648\\u0631\\u0627\\u0642\",\"\\u0633\\u0642\\u064a\\u0647\\u0627 \\u0628\\u0627\\u0646\\u062a\\u0638\\u0627\\u0645 \\u0648\\u0639\\u062f\\u0645 \\u062f\\u0648\\u0633\\u0647\\u0627\",\"\\u062a\\u0631\\u0643\\u0647\\u0627 \\u0628\\u062f\\u0648\\u0646 \\u0645\\u0627\\u0621\",\"\\u0627\\u0644\\u0644\\u0639\\u0628 \\u0628\\u0627\\u0644\\u062a\\u0631\\u0627\\u0628 \\u062d\\u0648\\u0644\\u0647\\u0627\"]','سقيها بانتظام وعدم دوسها',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21'),(18,4,'إذا أردت إنشاء حديقة صغيرة في المنزل، ما أول شيء يجب أن تفكر فيه؟','roaya',3,'[\"\\u0634\\u0631\\u0627\\u0621 \\u0623\\u063a\\u0644\\u0649 \\u0627\\u0644\\u0646\\u0628\\u0627\\u062a\\u0627\\u062a\",\"\\u0627\\u062e\\u062a\\u064a\\u0627\\u0631 \\u0645\\u0643\\u0627\\u0646 \\u0628\\u0647 \\u0636\\u0648\\u0621 \\u0634\\u0645\\u0633 \\u0643\\u0627\\u0641\\u064d\",\"\\u0648\\u0636\\u0639 \\u0627\\u0644\\u0646\\u0628\\u0627\\u062a\\u0627\\u062a \\u0641\\u064a \\u0627\\u0644\\u0638\\u0644\\u0627\\u0645\",\"\\u0627\\u0633\\u062a\\u062e\\u062f\\u0627\\u0645 \\u0627\\u0644\\u0645\\u0627\\u0621 \\u0627\\u0644\\u0645\\u0627\\u0644\\u062d\"]','اختيار مكان به ضوء شمس كافٍ',NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21');
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quizzes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `grade_level` int(11) NOT NULL,
  `pin` varchar(6) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_demo` tinyint(1) DEFAULT 0,
  `is_public` tinyint(1) DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`settings`)),
  `passage_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`passage_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pin` (`pin`),
  KEY `quizzes_user_id_foreign` (`user_id`),
  KEY `quizzes_pin_index` (`pin`),
  KEY `quizzes_is_active_index` (`is_active`),
  KEY `quizzes_is_demo_index` (`is_demo`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quizzes`
--

LOCK TABLES `quizzes` WRITE;
/*!40000 ALTER TABLE `quizzes` DISABLE KEYS */;
INSERT INTO `quizzes` VALUES (3,1,'فهم المقروء','arabic',5,NULL,1,0,0,NULL,'{\"jawhar\":[{\"depth\":\"1\",\"count\":1},{\"depth\":\"2\",\"count\":1}],\"zihn\":[{\"depth\":\"1\",\"count\":2},{\"depth\":\"2\",\"count\":3},{\"depth\":\"3\",\"count\":2}],\"waslat\":[{\"depth\":\"1\",\"count\":2},{\"depth\":\"2\",\"count\":2},{\"depth\":\"3\",\"count\":1}],\"roaya\":[{\"depth\":\"2\",\"count\":1}]}',NULL,'2025-05-30 00:54:28','2025-05-30 00:54:28'),(4,2,'اختبار تجريبي: النباتات','science',4,'DEMO01',1,1,1,NULL,'{\"jawhar\":[{\"depth\":1,\"count\":2},{\"depth\":2,\"count\":1}],\"zihn\":[{\"depth\":1,\"count\":1},{\"depth\":2,\"count\":2}],\"waslat\":[{\"depth\":2,\"count\":2}],\"roaya\":[{\"depth\":2,\"count\":1},{\"depth\":3,\"count\":1}]}',NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21');
/*!40000 ALTER TABLE `quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quiz_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `guest_token` varchar(191) DEFAULT NULL,
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`scores`)),
  `total_score` int(11) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `results_quiz_id_foreign` (`quiz_id`),
  KEY `results_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (1,3,1,NULL,'{\"jawhar\":0,\"zihn\":0,\"waslat\":50,\"roaya\":0}',13,NULL,'2025-05-30 01:11:35','2025-05-30 01:11:35'),(2,3,1,NULL,'{\"jawhar\":0,\"zihn\":33,\"waslat\":50,\"roaya\":0}',25,NULL,'2025-05-30 08:55:58','2025-05-30 08:55:58');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_school` tinyint(1) DEFAULT 0,
  `school_name` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'sharkawi','sharkawi@iseraj.com',NULL,'$2y$12$Wzu.V10fk5vAgtboOKO4F.xLX3p6.NJS15RYSC0Anv6XZNiVXGVrq',0,0,NULL,NULL,NULL,'2025-05-30 00:38:04','2025-05-30 00:38:04',NULL),(2,'معلم تجريبي','demo@juzoor.test','2025-05-30 13:24:21','$2y$12$OIboqifoHVhIzuPs0JUENeA3JPbozdrecO3/fRWo7A6ff7WM.LrlC',0,0,NULL,NULL,NULL,'2025-05-30 13:24:21','2025-05-30 13:24:21',NULL);
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

-- Dump completed on 2025-05-30 19:53:05
