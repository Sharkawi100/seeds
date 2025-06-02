-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2025 at 03:08 AM
-- Server version: 10.6.21-MariaDB-cll-lve-log
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jqfujdmy_iseraj_roots`
--

-- --------------------------------------------------------

--
-- Table structure for table `ai_usage_logs`
--

CREATE TABLE `ai_usage_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `model` varchar(100) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT 1,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ai_usage_logs`
--

INSERT INTO `ai_usage_logs` (`id`, `type`, `model`, `count`, `user_id`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 'text_generation', 'claude-3-haiku-20240307', 1, NULL, NULL, '2025-05-30 00:50:53', NULL),
(2, 'questions_from_text', 'claude-3-haiku-20240307', 11, NULL, NULL, '2025-05-30 00:51:15', NULL),
(3, 'questions_from_text', 'claude-3-haiku-20240307', 9, NULL, NULL, '2025-05-30 00:52:14', NULL),
(4, 'questions_from_text', 'claude-3-haiku-20240307', 8, NULL, NULL, '2025-05-30 00:54:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `result_id` bigint(20) UNSIGNED NOT NULL,
  `selected_answer` varchar(191) NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `result_id`, `selected_answer`, `is_correct`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'الحمامة الصغيرة', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(2, 2, 1, 'اللعب مع باقي الحمام', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(3, 3, 1, 'لأنه كان مريضًا', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(4, 4, 1, 'بقيت بجواره ولم تفعل شيئًا', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(5, 5, 1, 'أصبح الطفل خائفًا منها', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(6, 6, 1, 'كانت الحمامة غريبة عن الطفل', 1, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(7, 7, 1, 'علمتنا أن نحافظ على الطبيعة والبيئة', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(8, 8, 1, 'لا أعرف إذا كانت تتوقع ذلك أم لا', 0, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(9, 1, 2, 'الحمامة البيضاء', 0, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(10, 2, 2, 'الرقاد والنوم فقط', 0, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(11, 3, 2, 'لأنه كان ضائعًا في البستان', 1, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(12, 4, 2, 'بقيت بجواره ولم تفعل شيئًا', 0, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(13, 5, 2, 'أصبح الطفل خائفًا منها', 0, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(14, 6, 2, 'كانت الحمامة غريبة عن الطفل', 1, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(15, 7, 2, 'علمتنا أن نكون أقوياء ومتحدين', 0, '2025-05-30 08:55:58', '2025-05-30 08:55:58'),
(16, 8, 2, 'لا يوجد ما يشير إلى أنها توقعت ذلك', 0, '2025-05-30 08:55:58', '2025-05-30 08:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_27_163233_create_personal_access_tokens_table', 1),
(5, '2025_05_27_165155_create_quizzes_table', 1),
(6, '2025_05_27_165156_create_questions_table', 1),
(7, '2025_05_27_165158_create_results_table', 1),
(8, '2025_05_27_165159_create_answers_table', 1),
(9, '2025_05_27_192054_add_passage_to_questions_table', 1),
(10, '2025_05_27_195034_add_is_admin_to_users_table', 1),
(11, '2025_05_27_200501_add_soft_deletes_to_users_table', 1),
(12, '2025_05_28_031143_add_description_to_quizzes_table', 1),
(13, '2025_05_28_031516_add_description_to_quizzes_table', 1),
(14, '2025_05_29_050329_create_ai_usage_logs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `root_type` varchar(191) NOT NULL,
  `depth_level` int(11) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `correct_answer` varchar(191) NOT NULL,
  `passage` text DEFAULT NULL,
  `passage_title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question`, `root_type`, `depth_level`, `options`, `correct_answer`, `passage`, `passage_title`, `created_at`, `updated_at`) VALUES
(1, 3, 'ما هو اسم الحمامة المذكورة في النص؟', 'jawhar', 1, '[\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u0628\\u064a\\u0636\\u0627\\u0621\",\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u0645\\u0637\\u0648\\u0642\\u0629\",\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u0635\\u063a\\u064a\\u0631\\u0629\",\"\\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0627\\u0644\\u062c\\u0645\\u064a\\u0644\\u0629\"]', 'الحمامة المطوقة', 'الحمامة المطوقة\r\n\r\nفي إحدى البساتين الخضراء، كانت حمامة بيضاء جميلة تعيش هناك مع قطيع من الحمام الآخرين. كان لها طوق أسود أنيق حول رقبتها، وهذا هو ما أعطاها اسمها: الحمامة المطوقة.\r\n\r\nكانت الحمامة المطوقة تحب الطيران بين الأشجار والنباتات الجميلة. كما أنها كانت تحب البحث عن الحبوب والبذور الطازجة لتطعم نفسها وأفراخها الصغيرة في عشها. وفي المساء، كانت ترقد بهدوء مع باقي القطيع تحت ظلال الأشجار.\r\n\r\nذات يوم، وجدت الحمامة المطوقة طفلاً صغيراً ضائعاً في البستان. كان الطفل يبكي ويبدو خائفاً. فقررت الحمامة المطوقة مساعدته. بدأت تطير حوله وتُصدر أصواتاً مريحة لتهدئته. ثم قادته برفق إلى خارج البستان حيث كان والداه ينتظرانه بقلق.\r\n\r\nفرح الوالدان كثيراً برؤية ابنهما بأمان. وشكروا الحمامة المطوقة على إيجاده وإعادته إليهم. منذ ذلك اليوم، أصبحت الحمامة المطوقة محبوبة من قبل جميع سكان البستان لفعلتها الطيبة.\r\n\r\nهكذا تعلمنا من الحمامة المطوقة أن نساعد الآخرين ونكون رحماء وكرماء، فذلك يجعلنا محبوبين ومقدَّرين من قبل الجميع.', 'الحمامة المطوقة', '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(2, 3, 'ما هي الأشياء التي كانت الحمامة المطوقة تحب القيام بها؟', 'jawhar', 2, '[\"\\u0627\\u0644\\u0637\\u064a\\u0631\\u0627\\u0646 \\u0648\\u0627\\u0644\\u0628\\u062d\\u062b \\u0639\\u0646 \\u0627\\u0644\\u062d\\u0628\\u0648\\u0628 \\u0648\\u0627\\u0644\\u0628\\u0630\\u0648\\u0631\",\"\\u0627\\u0644\\u0631\\u0642\\u0627\\u062f \\u0648\\u0627\\u0644\\u0646\\u0648\\u0645 \\u0641\\u0642\\u0637\",\"\\u0627\\u0644\\u0644\\u0639\\u0628 \\u0645\\u0639 \\u0628\\u0627\\u0642\\u064a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\",\"\\u0627\\u0644\\u063a\\u0646\\u0627\\u0621 \\u0648\\u0627\\u0644\\u062a\\u063a\\u0631\\u064a\\u062f\"]', 'الطيران والبحث عن الحبوب والبذور', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(3, 3, 'لماذا كان الطفل الصغير يبكي ويبدو خائفًا؟', 'zihn', 1, '[\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u062c\\u0627\\u0626\\u0639\\u064b\\u0627\",\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u0636\\u0627\\u0626\\u0639\\u064b\\u0627 \\u0641\\u064a \\u0627\\u0644\\u0628\\u0633\\u062a\\u0627\\u0646\",\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u0648\\u062d\\u064a\\u062f\\u064b\\u0627\",\"\\u0644\\u0623\\u0646\\u0647 \\u0643\\u0627\\u0646 \\u0645\\u0631\\u064a\\u0636\\u064b\\u0627\"]', 'لأنه كان ضائعًا في البستان', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(4, 3, 'كيف تصرفت الحمامة المطوقة لمساعدة الطفل الصغير؟', 'zihn', 2, '[\"\\u0637\\u0627\\u0631\\u062a \\u062d\\u0648\\u0644\\u0647 \\u0648\\u0623\\u0635\\u062f\\u0631\\u062a \\u0623\\u0635\\u0648\\u0627\\u062a\\u064b\\u0627 \\u0645\\u0631\\u064a\\u062d\\u0629 \\u0644\\u062a\\u0647\\u062f\\u0626\\u062a\\u0647\",\"\\u0642\\u0627\\u062f\\u062a\\u0647 \\u0625\\u0644\\u0649 \\u0648\\u0627\\u0644\\u062f\\u064a\\u0647 \\u0628\\u0639\\u0646\\u0641\",\"\\u0628\\u0642\\u064a\\u062a \\u0628\\u062c\\u0648\\u0627\\u0631\\u0647 \\u0648\\u0644\\u0645 \\u062a\\u0641\\u0639\\u0644 \\u0634\\u064a\\u0626\\u064b\\u0627\",\"\\u0635\\u0631\\u062e\\u062a \\u0644\\u062c\\u0630\\u0628 \\u0627\\u0646\\u062a\\u0628\\u0627\\u0647 \\u0627\\u0644\\u0646\\u0627\\u0633\"]', 'طارت حوله وأصدرت أصواتًا مريحة لتهدئته', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(5, 3, 'ما هي النتيجة التي ترتبت على فعل الحمامة المطوقة؟', 'zihn', 3, '[\"\\u0623\\u0635\\u0628\\u062d\\u062a \\u0645\\u062d\\u0628\\u0648\\u0628\\u0629 \\u0645\\u0646 \\u0642\\u0628\\u0644 \\u0633\\u0643\\u0627\\u0646 \\u0627\\u0644\\u0628\\u0633\\u062a\\u0627\\u0646\",\"\\u0644\\u0645 \\u064a\\u0644\\u0627\\u062d\\u0638 \\u0623\\u062d\\u062f \\u0645\\u0627 \\u0641\\u0639\\u0644\\u062a\\u0647\",\"\\u0623\\u0635\\u0628\\u062d \\u0627\\u0644\\u0637\\u0641\\u0644 \\u062e\\u0627\\u0626\\u0641\\u064b\\u0627 \\u0645\\u0646\\u0647\\u0627\",\"\\u0644\\u0645 \\u064a\\u0634\\u0643\\u0631\\u0647\\u0627 \\u0623\\u062d\\u062f \\u0639\\u0644\\u0649 \\u0645\\u0633\\u0627\\u0639\\u062f\\u062a\\u0647\\u0627\"]', 'أصبحت محبوبة من قبل سكان البستان', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(6, 3, 'ما هي العلاقة بين الحمامة المطوقة والطفل الصغير؟', 'waslat', 1, '[\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0623\\u0645 \\u0627\\u0644\\u0637\\u0641\\u0644\",\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u0635\\u062f\\u064a\\u0642\\u0629 \\u0627\\u0644\\u0637\\u0641\\u0644\",\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u063a\\u0631\\u064a\\u0628\\u0629 \\u0639\\u0646 \\u0627\\u0644\\u0637\\u0641\\u0644\",\"\\u0643\\u0627\\u0646\\u062a \\u0627\\u0644\\u062d\\u0645\\u0627\\u0645\\u0629 \\u062d\\u0627\\u0631\\u0633\\u0629 \\u0627\\u0644\\u0637\\u0641\\u0644\"]', 'كانت الحمامة غريبة عن الطفل', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(7, 3, 'كيف ارتبطت فعلة الحمامة المطوقة بالقيم التي يجب أن نتعلمها؟', 'waslat', 2, '[\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u0633\\u0627\\u0639\\u062f \\u0627\\u0644\\u0622\\u062e\\u0631\\u064a\\u0646 \\u0648\\u0646\\u0643\\u0648\\u0646 \\u0631\\u062d\\u0645\\u0627\\u0621 \\u0648\\u0643\\u0631\\u0645\\u0627\\u0621\",\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u062d\\u0627\\u0641\\u0638 \\u0639\\u0644\\u0649 \\u0627\\u0644\\u0637\\u0628\\u064a\\u0639\\u0629 \\u0648\\u0627\\u0644\\u0628\\u064a\\u0626\\u0629\",\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u0643\\u0648\\u0646 \\u0623\\u0642\\u0648\\u064a\\u0627\\u0621 \\u0648\\u0645\\u062a\\u062d\\u062f\\u064a\\u0646\",\"\\u0639\\u0644\\u0645\\u062a\\u0646\\u0627 \\u0623\\u0646 \\u0646\\u0643\\u0648\\u0646 \\u0645\\u0633\\u0624\\u0648\\u0644\\u064a\\u0646 \\u0648\\u062d\\u0630\\u0631\\u064a\\u0646\"]', 'علمتنا أن نساعد الآخرين ونكون رحماء وكرماء', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38'),
(8, 3, 'هل تعتقد أن الحمامة المطوقة كانت تتوقع أن تصبح محبوبة بعد مساعدتها للطفل؟', 'roaya', 2, '[\"\\u0646\\u0639\\u0645\\u060c \\u0643\\u0627\\u0646\\u062a \\u062a\\u062a\\u0648\\u0642\\u0639 \\u0630\\u0644\\u0643\",\"\\u0644\\u0627\\u060c \\u0644\\u0645 \\u062a\\u0643\\u0646 \\u062a\\u062a\\u0648\\u0642\\u0639 \\u0630\\u0644\\u0643\",\"\\u0644\\u0627 \\u0623\\u0639\\u0631\\u0641 \\u0625\\u0630\\u0627 \\u0643\\u0627\\u0646\\u062a \\u062a\\u062a\\u0648\\u0642\\u0639 \\u0630\\u0644\\u0643 \\u0623\\u0645 \\u0644\\u0627\",\"\\u0644\\u0627 \\u064a\\u0648\\u062c\\u062f \\u0645\\u0627 \\u064a\\u0634\\u064a\\u0631 \\u0625\\u0644\\u0649 \\u0623\\u0646\\u0647\\u0627 \\u062a\\u0648\\u0642\\u0639\\u062a \\u0630\\u0644\\u0643\"]', 'لا، لم تكن تتوقع ذلك', NULL, NULL, '2025-05-30 00:54:38', '2025-05-30 00:54:38');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `grade_level` int(11) NOT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`settings`)),
  `passage_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`passage_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `user_id`, `title`, `subject`, `grade_level`, `settings`, `passage_data`, `created_at`, `updated_at`) VALUES
(3, 1, 'فهم المقروء', 'arabic', 5, '{\"jawhar\":[{\"depth\":\"1\",\"count\":1},{\"depth\":\"2\",\"count\":1}],\"zihn\":[{\"depth\":\"1\",\"count\":2},{\"depth\":\"2\",\"count\":3},{\"depth\":\"3\",\"count\":2}],\"waslat\":[{\"depth\":\"1\",\"count\":2},{\"depth\":\"2\",\"count\":2},{\"depth\":\"3\",\"count\":1}],\"roaya\":[{\"depth\":\"2\",\"count\":1}]}', NULL, '2025-05-30 00:54:28', '2025-05-30 00:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_token` varchar(191) DEFAULT NULL,
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`scores`)),
  `total_score` int(11) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `quiz_id`, `user_id`, `guest_token`, `scores`, `total_score`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, NULL, '{\"jawhar\":0,\"zihn\":0,\"waslat\":50,\"roaya\":0}', 13, NULL, '2025-05-30 01:11:35', '2025-05-30 01:11:35'),
(2, 3, 1, NULL, '{\"jawhar\":0,\"zihn\":33,\"waslat\":50,\"roaya\":0}', 25, NULL, '2025-05-30 08:55:58', '2025-05-30 08:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'sharkawi', 'sharkawi@iseraj.com', NULL, '$2y$12$Wzu.V10fk5vAgtboOKO4F.xLX3p6.NJS15RYSC0Anv6XZNiVXGVrq', NULL, '2025-05-30 00:38:04', '2025-05-30 00:38:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ai_usage_logs`
--
ALTER TABLE `ai_usage_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ai_usage_logs_type_index` (`type`),
  ADD KEY `ai_usage_logs_created_at_index` (`created_at`),
  ADD KEY `ai_usage_logs_type_created_at_index` (`type`,`created_at`),
  ADD KEY `ai_usage_logs_user_id_index` (`user_id`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_question_id_foreign` (`question_id`),
  ADD KEY `answers_result_id_foreign` (`result_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_user_id_foreign` (`user_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `results_quiz_id_foreign` (`quiz_id`),
  ADD KEY `results_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ai_usage_logs`
--
ALTER TABLE `ai_usage_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
