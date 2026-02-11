-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: localhost    Database: yha01
-- ------------------------------------------------------
-- Server version	8.4.5

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `about_descs`
--

DROP TABLE IF EXISTS `about_descs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `about_descs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `desc` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_descs`
--

LOCK TABLES `about_descs` WRITE;
/*!40000 ALTER TABLE `about_descs` DISABLE KEYS */;
INSERT INTO `about_descs` VALUES (1,'\n            Lorem ipsum, dolor sit amet consectetur adipisicing elit. At deserunt cupiditate minima totam! Necessitatibus aliquid quisquam consequuntur sunt excepturi praesentium ipsam, exercitationem earum rerum distinctio laborum eos ipsum aliquam voluptates.\n            ','2025-06-10 04:00:22','2025-06-10 04:00:22');
/*!40000 ALTER TABLE `about_descs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `abouts`
--

DROP TABLE IF EXISTS `abouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abouts`
--

LOCK TABLES `abouts` WRITE;
/*!40000 ALTER TABLE `abouts` DISABLE KEYS */;
INSERT INTO `abouts` VALUES (1,'68621a580033e_20.jpg',NULL,'2025-06-29 22:32:16','2025-06-29 22:32:16'),(2,'68621a5f2af4d_photo4.jpg',NULL,'2025-06-29 22:32:23','2025-06-29 22:32:23'),(3,'68621a673eba7_photo12.jpg',NULL,'2025-06-29 22:32:31','2025-06-29 22:32:31');
/*!40000 ALTER TABLE `abouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `OpenClose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yphNo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yEmail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `map_url` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,'အမှတ် (၂၉) ၊ လှည်းတန်း ၊ အင်းစိန်လမ်းမကြီး ၊ ခိုင်ရွှေဝါလမ်းထိပ် ၊ Beauty Room (Cosmetic)  အပေါ်ထပ် (၆) လွှာ ၊ ကမာရွတ်မြို့နယ် ၊ ရန်ကုန်မြို့','8 : 00 AM to 5 : 00 PM','09 882 328 992','yhacomputer@gmail.com','2025-06-29 22:34:12','2025-07-10 01:26:47','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3818.940287874809!2d96.1274117107673!3d16.829318118575134!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c195e761f1bd03%3A0xe7089153abd5d8be!2sYHA%20Computer%20Training%20Center%20Hledan!5e0!3m2!1sen!2smm!4v1752131567925!5m2!1sen!2smm');
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_student_id_foreign` (`student_id`),
  KEY `attendances_course_id_foreign` (`course_id`),
  KEY `attendances_subject_id_foreign` (`subject_id`),
  KEY `attendances_section_id_foreign` (`section_id`),
  CONSTRAINT `attendances_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `registers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_models`
--

DROP TABLE IF EXISTS `class_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_models` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_models_course_id_foreign` (`course_id`),
  KEY `class_models_subject_id_foreign` (`subject_id`),
  CONSTRAINT `class_models_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_models_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_models`
--

LOCK TABLES `class_models` WRITE;
/*!40000 ALTER TABLE `class_models` DISABLE KEYS */;
INSERT INTO `class_models` VALUES (1,1,1,'2025-06-30 01:29:46','2025-06-30 01:29:46'),(2,2,1,'2025-06-30 01:29:56','2025-06-30 01:29:56'),(3,3,4,'2025-06-30 01:30:09','2025-06-30 01:30:09'),(4,4,9,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(5,4,10,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(6,4,6,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(7,4,8,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(8,5,11,'2025-07-10 03:01:46','2025-07-10 03:01:46'),(9,5,12,'2025-07-10 03:01:46','2025-07-10 03:01:46');
/*!40000 ALTER TABLE `class_models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_sections`
--

DROP TABLE IF EXISTS `course_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_sections_course_id_foreign` (`course_id`),
  KEY `course_sections_section_id_foreign` (`section_id`),
  CONSTRAINT `course_sections_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_sections_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_sections`
--

LOCK TABLES `course_sections` WRITE;
/*!40000 ALTER TABLE `course_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_types`
--

DROP TABLE IF EXISTS `course_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_types`
--

LOCK TABLES `course_types` WRITE;
/*!40000 ALTER TABLE `course_types` DISABLE KEYS */;
INSERT INTO `course_types` VALUES (1,'Programming',NULL,NULL),(2,'Graphic Design',NULL,NULL),(3,'ICT',NULL,NULL);
/*!40000 ALTER TABLE `course_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `normal_price` int NOT NULL,
  `special_price` int NOT NULL,
  `duration` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci,
  `links` longtext COLLATE utf8mb4_unicode_ci,
  `type` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'Web Design and  Development','686241f688205_student-1.jpg','WDD',400000,500000,120,'2025-06-30 01:21:18','2025-06-30 01:21:18','<p>WDD</p>','link',1),(2,'ICT Foundation','686243c64bc87_18.jpg','ICT',400000,500000,90,'2025-06-30 01:29:02','2025-06-30 01:29:02','<p>kl;</p>','o',3),(3,'PS Beginner Sunday Only','686243e763e79_19.jpg','hjjkkl',200000,3000000,120,'2025-06-30 01:29:35','2025-06-30 01:29:35','<p>dfs</p>','iopk',2),(4,'Laravel Vue','686b2af4afa9a_student-1.jpg','တကယ်လို့ သင်က Web Design ‌and Development Class အတန်းပီးသွားပြီ ဆိုရင်တော့  Advance Level ကို ထပ်ပြီး တက်လှမ်းဖို့လိုလာပါမယ် ကျနော်တို့က Pure Language တင်ရပ်နေလို့မရပါဘူး။',400000,500000,120,'2025-07-06 19:33:33','2025-07-06 19:33:33','<p class=\"MsoNormal\" style=\"text-align:justify;text-indent:.5in\"><span style=\"font-size:11.0pt;line-height:115%;font-family:&quot;Myanmar Text&quot;,sans-serif\">တကယ်လို့\r\nသင်က Web</span><span style=\"font-size:11.0pt;line-height:115%\"> Design </span><span style=\"font-size:11.0pt;line-height:115%;font-family:&quot;Myanmar Text&quot;,sans-serif\">‌and\r\nDevelopment Class အတန်းပီးသွားပြီ ဆိုရင်တော့&nbsp;\r\nAdvance Level ကို ထပ်ပြီး တက်လှမ်းဖို့လိုလာပါမယ် ကျနော်တို့က Pure\r\nLanguage တင်ရပ်နေလို့မရပါဘူး။ တကယ်လုပ်ငန်းခွင်တွေမှာ သုံးနေတဲ့ Framework တွေကိုလဲ\r\nထပ်ပြီးလေ့လာသင့်ပါတယ်။ ဒါမှသာ ကျနော်တို့က လုပ်ငန်းခွင်မှာ ဝင်ရောက်နိုင်မှာပါ ဒီအတန်းမှာတော့\r\nFront End ပိုင်းဖြစ်တဲ့ Javascript ရဲ့ Framework နဲ့&nbsp; PHP ရဲ့ Framework ဖြစ်တဲ့ Larvael အကြောင်းတွေကို\r\nထပ်ပြီးလေ့လာရမှာ ဖြစ်ပါတယ်။ ဒီအတန်းမှာ ဆိုရင်လဲ Mini Project ပေါင်းများစွာနဲ့\r\nECO Shop Project တစ်ခုကိုပါ သင်ကြားရမှာပါ။ Module တစ်ခုချင်းစီတိုင်းမှာလဲ\r\nCoding Test တွေ ဖြေဆိုရမှာဖြစ်ပါတယ်။ ဒီမှာလဲ leaderboard တွေ အမှတ်တွေ နဲ့ အတူသွားတာဖြစ်လို့\r\nအားနည်းတဲ့သူတွေကိုလဲ ပြန်လည်သင်ကြားပေးမှာဖြစ်ပါတယ်။ ချွင်းချက်အနေနဲ့ကတော့ ဒီအတန်းလေးကို\r\nလေ့လာဖို့အတွက် Html , Css , JS အနည်းငယ်ကိုတော့ သိထားရမှာ ဖြစ်ပါတယ်။ တကယ်လို PHP\r\nမရသေးရင်တော့ OOP Concepts ကနေပြီးပြန်သင်ကြားပေးမှာဖြစ်ပါတယ်။ ဒီအတန်းလေးနဲ့ ပတ်သတ်ပြီးတော့\r\nအသေးစိတ်ကိုလဲ အောက်မှာ သွားပြီးလေ့လာနိုင်ပါတယ်။<o:p></o:p></span></p>','https://www.facebook.com/yhacomputerhledan/posts/pfbid0sABWomG33fXKyUDfoBMCTxgcrcUrUzb7LbrMKZWBQaQTqY1iESg7t5ACzjNJrmmgl',1),(5,'MERN Stack','686f882243b1e_Cover-Design.png','This is MERN Stack',300000,500000,130,'2025-07-10 03:00:11','2025-07-10 03:00:11','<p>This is MERN Stack About</p>','link',1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_details`
--

DROP TABLE IF EXISTS `event_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `images` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_details_event_id_foreign` (`event_id`),
  CONSTRAINT `event_details_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_details`
--

LOCK TABLES `event_details` WRITE;
/*!40000 ALTER TABLE `event_details` DISABLE KEYS */;
INSERT INTO `event_details` VALUES (1,'68625e83207d9_19.jpg',1,'2025-06-30 03:23:07','2025-06-30 03:23:07'),(2,'68625e832242b_18.jpg',1,'2025-06-30 03:23:07','2025-06-30 03:23:07'),(3,'6864d81078439_photo6.jpg',2,'2025-07-02 00:26:16','2025-07-02 00:26:16');
/*!40000 ALTER TABLE `event_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edate` date NOT NULL,
  `aboute` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'WDD COding','2025-06-27','<p>Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo&nbsp;</p>','2025-06-30 03:23:07','2025-07-10 01:38:24','68625e831b195_21.jpg'),(2,'MERN Stack Coding','2025-06-29','<p>MERN</p>','2025-07-02 00:26:16','2025-07-02 00:26:16','6864d810745ad_photo5.jpg'),(5,'Hello MERN','2025-06-30','<p>Hello MERN Stack</p>','2025-07-02 00:27:40','2025-07-02 00:27:40','6864d86490d63_photo6.jpg'),(6,'ICT','2025-07-02','<p>ICT</p>','2025-07-02 00:28:38','2025-07-02 00:28:38','6864d89e73abd_photo5.jpg');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `final_pay`
--

DROP TABLE IF EXISTS `final_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `final_pay` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vou_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `f_paid` decimal(9,2) NOT NULL,
  `vou_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `final_pay`
--

LOCK TABLES `final_pay` WRITE;
/*!40000 ALTER TABLE `final_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `final_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2023_12_30_045704_create_sessions_table',1),(7,'2023_12_30_061140_create_welcomes_table',1),(8,'2023_12_30_062615_create_abouts_table',1),(9,'2023_12_30_062627_create_about_descs_table',1),(10,'2024_01_06_070144_create_teachers_table',1),(11,'2024_01_06_081501_create_courses_table',1),(12,'2024_01_06_085705_create_subjects_table',1),(13,'2024_01_07_131325_create_positions_table',1),(14,'2024_01_07_154105_create_teaches_table',1),(15,'2024_01_09_123124_create_class_models_table',1),(16,'2024_01_09_152058_create_sections_table',1),(17,'2024_01_10_143209_create_course_sections_table',1),(18,'2024_01_11_044438_create_registers_table',1),(19,'2024_01_11_044537_create_attendances_table',1),(20,'2024_01_11_044549_create_time_tables_table',1),(21,'2024_01_15_175756_create_projects_table',1),(22,'2024_01_16_090407_create_galleries_table',1),(23,'2024_01_16_182846_changing_useragain_table',1),(24,'2024_01_22_064305_voucher_table',1),(25,'2024_01_22_185701_payment_table',1),(26,'2024_01_25_030616_final_pay.table',1),(27,'2024_01_25_072224_add_column_to_final_pay_table',1),(28,'2024_01_30_014615_create_addresses_table',1),(29,'2024_01_30_041316_create_events_table',1),(30,'2024_02_05_180346_create_course_types_table',1),(31,'2024_02_07_180254_add_courses_table',1),(32,'2024_07_28_053626_adding_coursetype_col_in_courses',1),(33,'2024_08_03_094351_add_section_to_voucher',1),(34,'2024_08_17_055453_changing_col_datatype_of_course',1),(35,'2024_08_18_050344_chang_event_about_data_type',1),(36,'2024_09_07_074542_create_monthlies_table',1),(37,'2024_09_07_094925_adding_address',1),(38,'2024_09_21_053746_adding_col__monthly',1),(39,'2024_09_28_083218_add_user_phone_and_role',1),(40,'2024_11_06_065215_event_detail',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monthlies`
--

DROP TABLE IF EXISTS `monthlies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `monthlies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `limited_seat` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `m_img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `m_desc` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monthlies`
--

LOCK TABLES `monthlies` WRITE;
/*!40000 ALTER TABLE `monthlies` DISABLE KEYS */;
INSERT INTO `monthlies` VALUES (4,3,1,'2025-07-02','2025-07-31',20,'2025-07-02 00:09:31','2025-07-02 00:09:31','6864d42398f09_21.jpg','<p>rxtcyvgubhjnmk</p>'),(5,1,1,'2025-07-02','2025-07-25',10,'2025-07-02 00:24:44','2025-07-02 00:24:44','6864d7b4f04da_19.jpg','<p>tyuhiueojkpe</p>'),(6,2,1,'2025-07-02','2025-07-30',20,'2025-07-02 00:25:13','2025-07-02 00:25:13','6864d7d116e16_18.jpg','<p>ICT Fo</p>');
/*!40000 ALTER TABLE `monthlies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amu` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `paid` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vou_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment`
--

LOCK TABLES `payment` WRITE;
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'Founder & CEO','2025-06-29 22:42:05','2025-06-29 22:42:37'),(2,'Manager','2025-06-29 22:42:16','2025-06-29 22:42:16'),(3,'Junior Programmming Teacher','2025-06-29 22:42:59','2025-06-29 22:42:59'),(4,'Office Staff','2025-06-29 22:43:18','2025-06-29 22:43:18'),(5,'Program Manager','2025-06-29 22:43:34','2025-06-29 22:43:34');
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `github` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_course_id_foreign` (`course_id`),
  CONSTRAINT `projects_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,1,'WDD Test 1','Hello','6864d96bc3c7d_19.jpg','https://github.com/YHA-Center/age_calculator','https://yha-center.github.io/age_calculator/','2025-07-02 00:32:03','2025-07-10 03:04:51'),(2,1,'YHA','Hello','6864dcc0119d8_21.jpg','https://github.com/hidecard','https://github.com/hidecard','2025-07-02 00:46:16','2025-07-02 00:46:16'),(3,3,'MERN','PS LEssons','6864dcdcee504_photo5.jpg','https://github.com/hidecard','https://github.com/hidecard','2025-07-02 00:46:44','2025-07-02 00:46:44'),(4,2,'ICT','Hello','6864ddf28e79e_18.jpg','https://github.com/dashboard','https://github.com/hidecard','2025-07-02 00:51:22','2025-07-02 00:51:22');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registers`
--

DROP TABLE IF EXISTS `registers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `register_date` date NOT NULL,
  `enroll_date` date NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `viber_phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `township` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `nrc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `education` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registers_course_id_foreign` (`course_id`),
  KEY `registers_section_id_foreign` (`section_id`),
  CONSTRAINT `registers_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registers_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registers`
--

LOCK TABLES `registers` WRITE;
/*!40000 ALTER TABLE `registers` DISABLE KEYS */;
/*!40000 ALTER TABLE `registers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'8:00 - 10:00','08:00:00','10:00:00','2025-06-29 22:39:37','2025-06-29 22:39:37'),(2,'10:00 - 12:00','09:00:00','12:00:00','2025-06-29 22:41:17','2025-06-29 22:41:17');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('07RYJqyxf6uqHHhrXegLWENjyhxRbbw9MfVWtklS',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoieWNDdm1tQzlwcjQyR3F6V1B6TjhYZWR3eDIwbUdRT2R0MGdORUxaNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136278),('0JxmpDWqv8X9gfOlQJ8N2uXNpyUZQkwcrfUaah3G',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMjl0cnQxbWxESHVKQW1BVlZMYkIxSnlNcU5IMXE1U0ZSTkJFNHg5ZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752141438),('0NejtbYmFd1hyDo0Q6PLesZia05aQd0fMIhXbPFM',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjBIVVJjTU1kSkhtSXFCZ0tycHFoWktQQkJPdXhCV2xMcUoyblRHeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136522),('0Y6UcZ2SJjPEAxEE2ZnK2P1x9wG70m8cGLdmHuE9',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFVJeDhOZ216b1dJS3lHZlNodkpCNEg2Qm55Y0lVRENVTkhjem1sUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136101),('0ZYIaTnzNl4PL3b0GWKPWnHT8VWpO4NXn5KdCIP7',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDhMNTd1WVR0cGR3Q3ZaVkNBOFgzRFlDZk9EQWtBTExsWDhXRk1RZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752140541),('1aIAVGjWeO8IKwS7KnheFDHu5piV6i1cqCLdwOOm',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXNzdVBtZUZuQXY3T3RhOWU4T1h4emNPVUpFRVVqYTFlVzFDeE5hTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136643),('1uMXz4ii8EKzuCKTxSwz0wQyq2KfeiJak5Bf2w11',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1Nzb2ViVWkzMm0xT3ExYWtIYnB3eEtiOVZsalFBOXNMZVBURjZubCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137061),('2UKab2IKDYtbqT4vsQwLZhJgUW767oqZ9f7wepxz',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3c1V2xiWXA2cE9vejFvQ1J4c2xKaTNmcU1vNURZb3Z2V3FBbWhUQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752134673),('33SirFDoVXS8fe7od4QHYfXISSkI6LIqIboveD2e',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVF2WnhibUhpTTB4bThWZGtFc2FoTUdFR3BrVmlYZlVrNkxvSTU3VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135428),('44nZMIfCQyFTJo9pt66iS2GBmh88dPdrL2kXpLzY',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUpYeGpycUVtYnlpSTVQaFV0WXc1Sm9ueTJEU09qRTdrVnVQYkxYViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137327),('473r8ExQfYovdI6lH7tAKC4Cy1syDCVzO54ruTvd',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjJTRDZCMXJXUThWdVZEYXM5ZVh1NHBFV0tHaHhzcjdYYUdhU3RsTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138518),('4ZEXtZp66Wt96SSFzaekjMNtusvx9pqT2WWk0Mut',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzNsakhqOXE0dzhTblRXVDNCcklVazdOeW10WTRjd0hPeWliMVh2OCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136208),('5TmVuErkumyYkeCuDjiv5UeikzfENWszZrjbdr5Q',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoidVE3cThPTGJHbUtpZnZaMWVFSDFhc2xxMUQzUE9LeDZqZ3hZcDdaMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137225),('6zIyfXBsmOcXBdmWjAN6EImrlWIg1rQwCjOPWTBK',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWNnNnNSZEJqQVFDTjRjZlRYYXhrWVFzZmJCT0xYanRTOTJtd3U3OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752141640),('6zYAvwVEjN13aSfynyPx8NiHGkZwZbrcVYgIeBbF',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUl2S21LaEFLd2xTM29FbGl0ZnhUaVBrdlJiTk5meHJGM0JYOVFoMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137049),('71KZY1QFbgJGH5xkkxYKjuADQK5HxnDkinJnIjvM',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYk5ZVGlJVjNJUGpJSDNMRHhtdXExamNvTFczRThIekdzMzJuVTVLYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752134546),('75hexuCbmlR6g0yP3ByzyU3jZpDGpNkbkVYgB2pl',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoieEVPanpVRGc5SXppRDlzRHZxS1o2bUhqMHF5Ync4dHp6NDlNdFI0VCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137784),('76NjtGXd8f3xU9jeThyUShFfKo8xCdEsNkK9DIwt',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnBWS3BZNE1IZmF6Z25FdUJNdGV2V0dGUGgySjBWeHAwdDdyNVNWSiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752144163),('7PIdhvJU2dIb666pKCBYUllz4Y6TCM4YGddGWab3',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0plbTFOcjhVNXJsNjNtZjFpQjZIQWZHNmozS2ljbXpNQ2FsM29YVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137058),('7s3RXoWCmld8iGXFaea948IVbQHTzTmtMXZHnXDc',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVm5sNG5kOGg0MUJBUnVDRUNpWjRuUEhQeGpWOVUxS2o0bE10cHlWRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136116),('7uLdp77oGk11hLccIMeyr478Rbl4ZjSMGpLONOc2',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWJ5M0tpS29BcDU4UXZvdHVObmdDR2tTSFlDNGNpVHVEOW5iTnUyYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136216),('9C6CYctKX9dWsFCkXxNSBR1acMlxrHji2djVeXkx',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkRrQ2pGSUNXM2habXBmQ3ZYb25neFdRR2NmbFVoNXRQbkk5VXVvdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752134574),('aapk0lyM9Abu4SUANg8ZL2toP1dW4lvoP3sME4pD',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEhkb1BzRkZUY1VsWFpKU3A0eUFiamNQcGUyQmdnNm1ZeUk2dkNGOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137654),('AgnAyvOVgHEXIuwSQZZkpsRWEtzNvFLsEQbWYzgB',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3hDTG9wVWlLRFgxZldQWVNJbm41eFU2NjVPaVVGd1JMeE1UaW1PaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138002),('b5Fk3q92rGQPRGNx9zb03btFeJKEyhud7nXMHoZv',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnFMQ3B6ZVh1RnNRbFpiUWRIa0hicmRScHZlTUtGU3lIZURsMzlzUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752140848),('b9By9Nzc0JRLMFQAePuORgjkYPRWWaaHA1JzAo1z',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNW1pakVEYkRSMGF3QXhyUGFnbmZtTWVIUWM0N0NOS1FhUTZnYmhGWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138032),('bNpyNTFx2DBanvmdnquyMHALngZtPYgCrSw9h4y9',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVdCZER2RFhMbTdNT3hLZmdMc3R6VU1kZXdXWmloTFdsbXByNFh2RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138011),('br91OpPx87VJnzUc5etyASihgMygMObT0SiDFMGo',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoia29iY09scHlmemE5ZjlyblZSMTlRUXZRSUpSdUtyV2RXUGxpSEV4RiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752134677),('bT4kPafSGIDr3QExyvVGgLPefoBClEIRVUIVtZt8',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoielRzbFplVFRxTFdGUDZVQkU5bkN0SDlhQllTdTB5dlR0YmJlSEl6WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137999),('bUXAs9QOEZKcdxIYorykeXzaWp76TzYQbaDD9Cpx',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoidDJTY3cyUlR6bHlrdzdmejBxblU0UVFzZEVldjhWb09GZnc2b1licCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135441),('BXIJRihuyyDe0hQ9ceox3giscynr3La5wrVV0WWj',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTNXQTRBdWxDUlQ2UU9UUkRZVG1FZFlrVnpYeWtDSEFYc2JNWnZaMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137054),('CbcBrguO19zp3pa7oJf8OtY8yxAg1Vb4EdFF8658',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDBidGtmN0F6Q0tCWE9CY093RVdUMjh4aUo3MklyWDhnbW9pMFZiTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752132288),('cHn1R4XNKD6XbsaXbfnvtTgKoo5vE2XgAZQabX7y',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMGV0MWFyZzJTc0NLUUQwMlkzZkFPOW1zdHU3RDkyZkVhb2xtZmpMMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752132527),('Djpws4EglErEgCQ1RIbMkrcjWsPXsEkdZhQ34p4Q',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVW04cXdqWVJWMDM4b2hTaWRQR3hhcHBtYTlEcVJFamlaYUo3WEVxaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135892),('DvK5dyxbRkVg2FGrkkc3zVwgf5Ze1AfItKt9xUQU',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3ZNV2k3UTdxc0N1c2tlTkNkeHl6dHBJVnE1bFp3eUlCOUVMWHZqVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137996),('Dy35FKvPkXKIFECfV9DcMdlPd9oiOg6yX5vNz4ee',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzM4TEtkS2JmY3d6Zzl6REV1UjNyZFN3OGR4T2tHT2RRM0RFcHJLbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137082),('EFgzRYdwCsDDuIMeTmHAyvRnwkzsSjOe1jPR06h8',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoid1hzbmtYQXIzS0Q5ZUx0ckh3QUZLdjhpb2hyOFoxMnFUdUJqS3UzeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC95aGEvZXZlbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752293054),('eFjXLfg1UiOHb3HDnDG5i6AFz83bP0ooxKPwb8Tz',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkpHMTVhZ2FUV1psT1hvUkdNWnZvZWowZWxuZzJ5Y2pJbjFXQWZvViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138507),('EX8buupHt1GgxlxH4zLK7tD9jAtVxCn4IJPkv4dC',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOHBINHdBenhJbFB6VmtNaVRYMnFJRGttR2g1SDJhemJ3d21CUEdmdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752143177),('FgCcTHEesMPj8fIIpLNe6Iv2LBCMMKuKKYll6Vf4',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiakI1YTVQdTVCWFA2YXlIZldSdEttMGV5UjlYRzFRM0p2THBaYmlBSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138648),('GkhI4BSk2UN8KPF2ikdgXCiy3feNFyuaq40vcZyE',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHJDTmNBUEo0UVZLQllrUWFTUVhCMmxPYzlwWlIxZVRFVnlsSlZGNCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137085),('HaATlXVsujxZ4MGgRPClHRIFnP4FTeKPD9y3RmTZ',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTk9hQjhzckVsa1FwMWdJVTJvMWFZalJOZmVMS2JPVkV0T3o5U082aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136107),('Hha7QKRN8asS2z2ktha72ZmjWkv3un2aVWv14cox',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0g3TFlwUGMybGNER1BLeFpnbFM4M0E2RVRCWmxzVm1JS29jOXFzMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752133151),('hjERupZosqPPM3yAzlsvCkG3jhWMRAdFB8DkaIWZ',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNkkxQ0FGNUFrQjgxMzhkOU5LWjB0U2F5S2dEM1IwOVdYa3NBcVJqWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752146160),('hsLAOi1b6GQkWGorUcoOXNHgP0pLgmsxTaMkN2AD',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3M1NVplWHVhbDNLMDdndVlCcUszbmlBenZuN053bEJIU0trcUdOQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752132832),('hTbTp2dup5i7kclgKjGMm2Eq2PIvtSE6ki6l9Ntv',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoibGJaZmNXTDExd0JRanR0TnVtR2dEaGxwOHY2akpLU3JRcWxUaGZDZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135392),('I5Bn90prOy6YsMX5Yw59qr8rmSWcRkK4XA1uFG10',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS21DeFpZMFVsangwQmlHYmJiRWtrWWhBN1lESUtyVm1sd0dBbmRhTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136202),('IYiY3MeJBDgUxf2fAtKTjyoKsednwqDiL0YecWiT',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMktCelRwakxPRzNYd3g5dVpHazlWTEd2cDdlYnRadDJWdkFLaENDNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138016),('IZ4JgAO9ToF6Vhq13TcAxEiCkKPhLvqGBL2h1iEY',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoieUU0TndiR1FndzlJcVRWcGJyWjY1VGNhT1hFU3FhaU1tcW44TDVWVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135380),('j1hTljs2UFRTYVMTVxFjSAvqOyQdPXm2H0vSRPou',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoidW1YN1RyMXI4dG1xS0pxU29FZzNhMktxaGhVd21vMUdXN3JRMU1RTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135885),('jS7F3w0hRab2xD0n2NVGITgL8I9ArYbxzJdoCwc6',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmI2UnVGSFZoQVpWWnhKWUpJZGh1QzZ5enFDaU5taExkUkhVOFhmdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752146126),('jsEVK69jdZKqbZXCDtZGimaHYlqOduysoca2ecVQ',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3pqOVMwTElZTFpqQzdtMjFqNTJPRFR6U2tmMk5IU0hpZ2VENE1HUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138160),('Jt8IGjmd7KSQiztE116Vct0Ajg1dUq27GUmRbs0S',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjRPZVFJcFk1OVVaWERlWkpYeUlJZXl6blRScDlkenM1NlZ3S2huaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752141153),('k8MqUCpZtAaZwOHXFDmn4DrQdGCdoLhL4a14KHQp',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOVh3bG1Rc1dqWW1NNlBxMmFoNFYzSTl2RlVRZGdtaWZwTVdheHpheCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137095),('kSZWcFdeLyojoNSd8XtJCuoDujeipHeL74qoqGNk',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGtZT0hzVGU0b3pGYTlEZzU1bDNmRDR1TkpiV3pJM0lTMmpCTXdHNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138560),('LkdQWK1q1PKzA98hAf06ezQBak7CdkEtE2NcXPgf',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0phSm1oa1JGWFN5T1BPUTF0OXdoeXRWOEZkdTY4Z1I5ellydVJhTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752143267),('lnSZXdZhOMtZ2j8zMGYCsgoF6As61VTljhG5T1zM',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1J4b2JBS3BvbGY2UXFNbDUxelFRWlNjUmJPSVp1emdxWW9CTDBxMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752145859),('M1Qgg3P37kHYIf9nHuWbUidztsiZekKriYATD2Lq',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFdzempqeGNyR1g4SlFOTHpxeDhTVDExMXZ5OFRIV2FVRHZvSXY4WiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137761),('MoiqjePRbjkPrAGnTsSWZehCo8Iqp6H2CSt8s4rE',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXBuTGZSVTBJdmZYclBMU1BpdXloMWxvNjR3bW93WmJKTGo3VXBRNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136988),('mzpF9OAb6Ppzh4iSQyHAZ1yIuv3k6y6W8GMfkmrf',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoidTNldklFdVBQTkk4cVpmRTdKREU1YmdxYkx6THRIRENPUExsZ0x5USI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752144176),('ndvJglBcYQIXMEn7l7bebMoCA7YcFOPAvb7KcdRc',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXVZdURIRUN4M0ZFeWJDZkhoeU9wY3BXV015ajkyZHR1MEtKVWkxViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752144754),('njprUKo1zmDHkVQZptWoFGYLIvTzEa3o7Ofx4XIc',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWFPbTlTOWZ4OEp0SGhtSjZnTjNHYm5rRHFKaXNwc043ZVA2TVhFUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136260),('NJZsccM8HFTK3GsZeCSxo0uyMCwwvOs3jP7cmpfk',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNldPUEU4OUNXbnJPbEFmTDFuamRxWXluUkowaURTajVRY1hLcVdKTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137116),('nVG5nJuPDvZV4BCj3dnMHF3O1OlDu6FkqSKj3a9U',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0txRnIyQmJRVURCMHRjUzhBVjkwZFlHUkZCcXlBRHdvWmtWNWYwYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752141504),('o4vttsgepA4kX7JUlu3K9dbZouMXNBI4mL6WyIqe',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMTRna2FsMW95N09Sd2VUOUwzamZ3NGtBZlZxMU9OR1FNZ2xaTU83dSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138046),('Oh5F4YU4qV3KeZDv8RLSMk2q8Zxu6T6gEwPeN4eY',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaVhJZmVVaTVTUlExZ2dnWW5qOHB3QXkzaWhQTHFCeUViZ3M4ekJZYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137642),('OjN1zY47wB5LBazbLBR6dDojBu1nuFeCFu8udx9J',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSHdLN3JtNnBnRHE1YWZ6RFJZTEVydWo1SldsdlYwWVN2dEc0VUJGRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137769),('oOZoGvjwPYY7OEegUKP1PfPkuWFc3Udg5ht2vuug',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYnhYTzcxQmFtSUFvRlIzbVMweVR5Wm9EdGoyRnRpVjFvOXlFMVdpRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137076),('opyME4YDlJqihAvnAqaWi48EBHngksl4UHcLrEYP',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2N6RVRDMXNjb3J6YjRMSFl0WEZxTkR0ZmxYS0NyWURWUkc0dldKUCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752145868),('oyanFJvOtRKJJXNJC1IuuUdvh4vA3cVhrCVKr3je',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU05JZDA4b3NVWEJzVXZsQlFmYk1ZNXkzemRJZnZNeTlrN0Qxck9KSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138008),('p4GqrcTmeAuJPSJVFeTzFMMJ16dWeSZMk9fz4dPh',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjN1Z3Z0YnhMWkFxVVFHaWF2MUFSdFJNZ0FtZlEwQnV5bWp0bVA5QSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752143098),('ps8RAbgQcs8ptlFIYEobkRfnIcvLPBu8H33m95UF',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoibjJBVmtUZEJUblhsWVhNdzk3ZGRERmF4YXZrYzZsR0RWb0NGb3YyNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138665),('puv40hnu0QLqHICum4d4zgnGELGFSMmEIWthonP8',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkRWVU9sa1dxakdycEt2ODJ0c3ZQWjJqblBlWWN6WjY0RHdRNUIzMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137759),('Q24xaiCS9T6ZVOCdiILON1DPiVA7DFarQMm0ygy9',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT093UjNTWkRXZkZ4MWN6ZXFobVlFN1hpbVpqdjUyQVVPOEJuZ3BaeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137211),('q3rkis5oMh3c2rr7Kb7fzsBnnk8DvJdgCLmx7X1V',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTJtNnVkVjlsTlViU1lNbE9sbUhwUkdIeHdhaGRuV0ZRUDFDUEllZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136120),('q60IBfD9nVA8ZonBEnRkS9DsMdPh4NN0rVZYRj20',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVTJ3eVFTaFBZdUsybzd0MjdjR0t6bEZ6d25iTmJuWWtTV2RQNFZLNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135261),('qCR8NIN8yrRri2EFGgFCUu9SONZxlshqqiTMqYlu',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoidXVWdFV6U20xcFFSOWNvTFFIVmNnZDJsRWd2VTJ4MTZ6bmtLa2hOdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138036),('qenkOrfEv8pYIZQgAb92QWmR7uXSTcUsYDcbQZtg',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzJQdGI4ejhkOThza1cyRXFqQ1JrNU5TV1FTRkVzblFUcHNvVnBtZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752135888),('qfHHWGpLTyM649SLCPswg9ub2hshSsNKgIA3daOa',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoieGxQYmRkMExBcEx5TkxvNnB0cWE0eEhYZGg5R0hWQmYzcUVQcVVUeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136045),('Qi0jmaTe5QupbL7bdV1hQK8dnbS6SFLmYZejLifq',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjU1SW0xaTQ0Q3FjbWJqS3dMYkVjUmZoakNVTzhrQUJPSVZyMnhUeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136113),('QuvDjCP9VZdLJ5u97If17yO9A0Qbk5LG8aCrESZ4',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSEdwdEhkZ3NTQTJMWFQzc0VsUUdCZGlTc2xiQTNidHUzczdIQXo0NiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138590),('QZWXV18sq76lfDv6hYECceSGcF1H6sMOwBV2sGiM',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTlMTEtBTVFNWWROQU1EOVlHVFkyTWU4OXJBRFliaDVnQk9EekZwQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138512),('RcLrukeCsuvT514ohBuVDyiS8QLzkrabP7f9Dorj',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGE1bXBaN2pGTDVGU0ljY21kYkQzODliYVVGUEJxcEs1dVQ1SjE3WCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752145913),('ReBWFen6kwYPp9ZTWN55QKW00ZW0Pt1zpNGrxen6',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUtPb1k2ODhPd2F1S1RXUnZXNXoxNU1FclNNSEdoSW5UZGtKWnpIQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752370475),('rOD3FdKSXgMutu4m9ic3KXSo3g8WrznSw7feItT9',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkF4eDRpSXROdXVoRWU1SXJMVjFhb3dGSjhLbmdBYXY1NnAxeXhmQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137064),('rxeHuO9hs0MI094JafpjF7AIKoj0BmgiUMr1A0AG',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjU5MnM3Rmc2ZUloT3hqWWtZNEtvQkxNSFJERzlibDY5N3VGc2tlbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752140883),('Ss6zjTh4YN3n4GE4rUlhVqNzEkoqNrFrb3vGHI7P',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUFvQUtBMmRTUGREVExTaUM5UE9hUDhDazBkWktsdUd0T1NlRkNxbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752139187),('syGdLYf1sOifxIjBbkMgqqknlp135hwJ8Ce6tIOx',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiODQzZGdHcnRuT2VqY2xGNmRPaTlqMmE5UUN5ZnJ0U2xGdTBkMXBCciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137899),('T3HusuifEpKxIqV1SPCskpMn8Yn3ZROytECsubQw',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoieVJ4ZTB5cXBTNmF5NXF0OElreUpScjBBckw0YkF2OEMwVEZPYm5NUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138739),('UBgD0PZgKZXRkA6at6xJihhQWDGoG7bjVQoDcQHx',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMU44Wk1LVzFSYUV1MjY0NURqTXV0Z3E3bWpFSHpScUY5Mm5OczdFYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137099),('uK5yPZl6gpkBmwFZjajyk4ZYjGI55zBfqxGmNRYK',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiekVGZDJhQjZFcDZYTk1RR2xCQ3EySmNYMjBQamN1VjRXTWRBTDJlQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752144814),('uwrhqaYDx2Ohfq2nlHOJ45Y0V34zypycL8uq6k13',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczJFQ1d2V1BNeXlyYVJhS2hDaEM0RHE1djBCMWFNS1owM1l4SkJ6ZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136271),('VRoFdxMxYdCNPO8LEd7l9OWAFniflb1dmNTdMM48',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoieVV6NXI0SWFyeTRvSjc4S3FOelFpcERsUVpPTVlVY3M1T1JzcUM0OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752141285),('vx9C6o0G44QmCXbMQzWpQVvNw2S33l8xRXBL2Jqt',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0FzVVJpaXdIVzFKM2lEV01UVzNKOExhM0xCUjNhS0pXT2trZWFVaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752292975),('vZCrpuAH8IA7nCisCr9XoN2gPMvhh3pmFI3zepYf',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR09ZVzlXMkFQaE5MYWQ4Y21DRlh2Y3ptejhLWk92TWpycE96TGFsWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752139110),('w9IqBEklcJaaCU1iKNQrKQpQFgCYnOP4fIS4t82K',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFR0bndJNzM4eGlTQk00NGNQYm9KcTJ2M2tqc1lJUVdhYWdlTTNvcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137073),('WCuP5IjJV0NEagz8RSdXCshxxpSbqXNWoVnEA25W',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUW43bWRJVExMa0JKdEV3NXJhSUo2SUJHN1k1VEFaNHF5R3dBY2VWSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137805),('wJN7BtKGi8kK85t7njYbtAowirYRM4h1Se3aRTXh',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0RGbGJoc3VDYWU3NzVWYWJzVTlIa2xMMVJRcjNYWTR6MDhCNHplZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137206),('wRLX4xHQD51TXUZ4oOfkaNI522a2pAY6G6kBJa9K',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnFsQ2dmSUpOQTQ0WFJIb3l3WnJkY1diTDU0SUU4RkFaVkhrR2lvYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752140878),('wRtdC72lNtQycEYCHEV60ZUBNScubL4XGsWc0v9I',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZDVWSW8ycDQ2V05CSUVxdWNqQ29uYTVaeWRtcnFYR21Rd05SNW55QiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137613),('xAbpYnDkIe3aPRXPn2LuT2sNyXPyFbhA95LmxIIc',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTEJGRFk3R2t1dXBrUU9wMUxUV1hkdHFUYTMxdlV6MUtWYU5HeG5mRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752139176),('XLacsCcA4hG5WCwLS1jgvtcorZihKrFE6OR6Otx1',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEcyMldPOW4zUnRTNDlFbHJ6VTcxZTlIOTJKVGxVUFNiMEdUS2wzSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752137108),('Xu95Xmxbr3eVkMAUZrEw4FcBVeI65f9ZwqvcP2Zh',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOFdWd2xGakpSanFmMUxaVVRhUWZ5N2RnMjdBdDg5WWdWdXB1cjMxNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138829),('Y6pEzun0f8CUkVUNoFOI99rW6ZsM5je6UU1eiA2u',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmVISzZYYk1saXhXZUFVeWtKS25zSVY4d1NNRlI4Z3k5V2NhOVNKbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752136958),('yLawatJCVmob7SXLt5jwYhhJy4NQmqAqlLQASlcZ',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoidE5rQXNLQ3R4N0JHSElyMTRVMkhKbzNFSUFQQWxRMkdHcUhUd2tqZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138779),('ynSD5hVFzerUnGWdQfZVqzouRvy28mt02nuc6ZNc',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUU1WMHFtZXNmazFZcjFtd25OZjhHYndyNnZweU12dkYwMk1TZWkyQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138536),('z4uuWH7tZglNP99LfmJnGA4mQtT8gMRjk448sW0r',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVdnOXl5YTNVMVcxUmlVQ0ZIY0JGNnF2T25WUml3T2xPcXl4RXJTaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752138409),('ZcCEbalhuMohEYsOR8bSTGDvSO0nmrDRRiUJYRZr',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkxPazZNQ3ZWZ2xZN3ZLSzNPajZaVUxmMVdMSEZIVERFTUltOXFWdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1752146173),('zcV0ZSGXIXfW6FEKMzBITXUnOR4IUEHKjMCsL3fP',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTNITjJkVW1DTVV3czZuWEdSWmszaG9FeE9WRmdIUHFZQ1pLWkNoeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752144784),('zLrDyNvkwagSGB3DerysWiDvAdU1Qf9q57lIGfe1',NULL,NULL,'','YTozOntzOjY6Il90b2tlbiI7czo0MDoibEp3NmNBa0xvWlBNVHdZc2VSOVdhVnRpdzVDVVZTYmhWUk1VN3o0byI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODoiaHR0cDovLzoiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1752134693);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Html','2025-06-29 22:35:02','2025-06-29 22:35:02'),(2,'Css','2025-06-29 22:35:07','2025-06-29 22:35:07'),(3,'Bootstrap','2025-06-29 22:35:17','2025-06-29 22:35:17'),(4,'JavaScript','2025-06-29 22:35:25','2025-06-29 22:35:25'),(5,'Figma','2025-06-29 22:35:37','2025-06-29 22:35:37'),(6,'Php','2025-06-29 22:35:48','2025-06-29 22:35:48'),(7,'MySql','2025-06-29 22:35:55','2025-06-29 22:35:55'),(8,'Git and Git Hub','2025-06-29 22:36:11','2025-06-29 22:36:11'),(9,'Vue Js','2025-06-29 22:36:25','2025-06-29 22:37:05'),(10,'Laravel','2025-06-29 22:36:34','2025-06-29 22:36:34'),(11,'React Js','2025-06-29 22:36:42','2025-06-29 22:36:42'),(12,'MongoDB','2025-07-10 03:01:21','2025-07-10 03:01:21');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `position_id` bigint unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,'Sayar Ye Htun Aung',12,1,'68621d2eb644f_photo14.png','2025-06-29 22:44:22','2025-06-29 22:44:22'),(2,'Sayar Arkar Yan',3,5,'68621d563c64d_photo14.png','2025-06-29 22:45:02','2025-06-29 22:45:02'),(3,'Tr Kay Thi',12,2,'68621d88e4979_photo14.png','2025-06-29 22:45:52','2025-06-29 22:45:52');
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teaches`
--

DROP TABLE IF EXISTS `teaches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teaches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teaches_teacher_id_foreign` (`teacher_id`),
  KEY `teaches_subject_id_foreign` (`subject_id`),
  CONSTRAINT `teaches_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `teaches_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teaches`
--

LOCK TABLES `teaches` WRITE;
/*!40000 ALTER TABLE `teaches` DISABLE KEYS */;
/*!40000 ALTER TABLE `teaches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_tables`
--

DROP TABLE IF EXISTS `time_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `time_tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `section_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `assistant_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `time_tables_section_id_foreign` (`section_id`),
  KEY `time_tables_course_id_foreign` (`course_id`),
  KEY `time_tables_subject_id_foreign` (`subject_id`),
  KEY `time_tables_student_id_foreign` (`student_id`),
  KEY `time_tables_teacher_id_foreign` (`teacher_id`),
  KEY `time_tables_assistant_id_foreign` (`assistant_id`),
  CONSTRAINT `time_tables_assistant_id_foreign` FOREIGN KEY (`assistant_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_tables_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_tables_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_tables_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `registers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_tables_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `time_tables_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_tables`
--

LOCK TABLES `time_tables` WRITE;
/*!40000 ALTER TABLE `time_tables` DISABLE KEYS */;
/*!40000 ALTER TABLE `time_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `confirm_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','yhacomputer@gmail.com',NULL,'$2y$12$JHLjxR9DasxbA.STrvg0q.DRxi2gxz8HwfqajE1UxaAFRP2qM3w76',NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-10 04:00:22','2025-06-10 04:00:22',NULL,'234232443','admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voucher`
--

DROP TABLE IF EXISTS `voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voucher` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stu_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `enroll_date` date NOT NULL,
  `fees` decimal(10,2) NOT NULL,
  `vou_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `section_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voucher_course_id_index` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voucher`
--

LOCK TABLES `voucher` WRITE;
/*!40000 ALTER TABLE `voucher` DISABLE KEYS */;
/*!40000 ALTER TABLE `voucher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `welcomes`
--

DROP TABLE IF EXISTS `welcomes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `welcomes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `welcomes`
--

LOCK TABLES `welcomes` WRITE;
/*!40000 ALTER TABLE `welcomes` DISABLE KEYS */;
INSERT INTO `welcomes` VALUES (1,'68621a3c2956e_slider1.JPG','2025-06-29 22:31:48','2025-06-29 22:31:48'),(2,'68621a44514b6_slider2.JPG','2025-06-29 22:31:56','2025-06-29 22:31:56'),(3,'68621a4bce23c_slider3.JPG','2025-06-29 22:32:03','2025-06-29 22:32:03');
/*!40000 ALTER TABLE `welcomes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-13  8:05:06
