-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: yhaproject
-- ------------------------------------------------------
-- Server version	8.0.44

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
  `yphNo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `yEmail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_url` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,'အမှတ် (၂၉) ၊ လှည်းတန်း ၊ အင်းစိန်လမ်းမကြီး ၊ ခိုင်ရွှေဝါလမ်းထိပ် ၊ Ice Berry ဘေးတိုက်  အပေါ်ထပ် (၆) လွှာ ၊ ကမာရွတ်မြို့နယ် ၊ ရန်ကုန်မြို့','8 : 00 AM to 5 : 00 PM','09 882 328 992','yhacomputer@gmail.com','https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3818.940287874809!2d96.1274117107673!3d16.829318118575134!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c195e761f1bd03%3A0xe7089153abd5d8be!2sYHA%20Computer%20Training%20Center%20Hledan!5e0!3m2!1sen!2smm!4v1752131567925!5m2!1sen!2smm','2025-06-29 22:34:12','2026-10-06 09:24:15');
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
  `remark` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_student_course_subject_section_date_unique` (`student_id`,`course_id`,`subject_id`,`section_id`,`date`),
  KEY `attendances_course_id_foreign` (`course_id`),
  KEY `attendances_subject_id_foreign` (`subject_id`),
  KEY `attendances_section_id_foreign` (`section_id`),
  KEY `attendances_date_status_lookup` (`date`,`status`),
  KEY `attendances_student_date_lookup` (`student_id`,`date`),
  CONSTRAINT `attendances_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (2,16,3,4,1,'2026-10-04',4,NULL,'2026-10-04 01:15:41','2026-10-04 01:15:41'),(3,16,3,5,1,'2026-10-05',2,NULL,'2026-10-05 09:53:25','2026-10-05 09:53:25'),(4,34,3,5,1,'2026-10-05',1,NULL,'2026-10-05 09:53:25','2026-10-05 09:53:25'),(5,16,3,5,1,'2026-10-07',1,NULL,'2026-10-07 03:52:20','2026-10-07 03:52:20'),(6,34,3,5,1,'2026-10-07',1,NULL,'2026-10-07 03:52:20','2026-10-07 03:52:20');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificates`
--

DROP TABLE IF EXISTS `certificates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `complete_date` date DEFAULT NULL,
  `certificate_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not received',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `certificate_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `certificates_student_id_foreign` (`student_id`),
  KEY `certificates_complete_date_index` (`complete_date`),
  CONSTRAINT `certificates_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=475 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificates`
--

LOCK TABLES `certificates` WRITE;
/*!40000 ALTER TABLE `certificates` DISABLE KEYS */;
INSERT INTO `certificates` VALUES (442,34,'2026-09-29','certificate/6ac71dd86fd77_2026-02-08-114257-am.jpg','not received','2026-10-06 08:49:58','2026-10-08 04:36:40',NULL);
/*!40000 ALTER TABLE `certificates` ENABLE KEYS */;
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
  UNIQUE KEY `course_sections_course_id_section_id_unique` (`course_id`,`section_id`),
  KEY `course_sections_section_id_foreign` (`section_id`),
  CONSTRAINT `course_sections_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_sections_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_sections`
--

LOCK TABLES `course_sections` WRITE;
/*!40000 ALTER TABLE `course_sections` DISABLE KEYS */;
INSERT INTO `course_sections` VALUES (1,2,1,'2026-10-04 01:07:37','2026-10-04 01:07:37'),(2,2,2,'2026-10-04 01:07:37','2026-10-04 01:07:37'),(3,3,1,'2026-10-04 01:11:40','2026-10-04 01:11:40'),(5,1,1,'2026-10-04 01:11:47','2026-10-04 01:11:47'),(6,1,2,'2026-10-04 01:11:47','2026-10-04 01:11:47'),(7,4,1,'2026-10-04 02:34:19','2026-10-04 02:34:19'),(8,4,2,'2026-10-04 02:34:19','2026-10-04 02:34:19');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_types`
--

LOCK TABLES `course_types` WRITE;
/*!40000 ALTER TABLE `course_types` DISABLE KEYS */;
INSERT INTO `course_types` VALUES (1,'Web Development',NULL,'2026-10-04 01:06:04'),(2,'Data Science and Ai',NULL,'2026-10-04 01:06:35'),(3,'Mobile Development',NULL,'2026-10-04 01:07:00'),(4,'ICT','2026-10-04 01:07:11','2026-10-04 01:07:11');
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
  `type` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'Python Development','6ac202f6444e7_photo_2026-09-29_13-02-30.jpg','WDD',400000,500000,120,'2025-06-30 01:21:18','2026-10-04 01:10:38','<p>WDD</p>','link',2),(2,'ICT Foundation','6ac20323948ac_photo_2026-09-29_13-02-33.jpg','ICT',400000,500000,90,'2025-06-30 01:29:02','2026-10-04 01:17:07','<p>kl;</p>','o',4),(3,'Flutter and Dart','6ac202df8e1a1_photo_2026-09-29_13-02-22.jpg','hjjkkl',200000,3000000,120,'2025-06-30 01:29:35','2026-10-04 01:10:15','<p>dfs</p>','iopk',3),(4,'Laravel Vue','6ac202c7c6757_photo_2026-09-29_13-02-27.jpg','တကယ်လို့ သင်က Web Design ‌and Development Class အတန်းပီးသွားပြီ ဆိုရင်တော့  Advance Level ကို ထပ်ပြီး တက်လှမ်းဖို့လိုလာပါမယ် ကျနော်တို့က Pure Language တင်ရပ်နေလို့မရပါဘူး။',400000,500000,120,'2025-07-06 19:33:33','2026-10-04 01:09:51','<p class=\"MsoNormal\" style=\"text-align:justify;text-indent:.5in\"><span style=\"font-size:11.0pt;line-height:115%;font-family:&quot;Myanmar Text&quot;,sans-serif\">တကယ်လို့\r\nသင်က Web</span><span style=\"font-size:11.0pt;line-height:115%\"> Design </span><span style=\"font-size:11.0pt;line-height:115%;font-family:&quot;Myanmar Text&quot;,sans-serif\">‌and\r\nDevelopment Class အတန်းပီးသွားပြီ ဆိုရင်တော့&nbsp;\r\nAdvance Level ကို ထပ်ပြီး တက်လှမ်းဖို့လိုလာပါမယ် ကျနော်တို့က Pure\r\nLanguage တင်ရပ်နေလို့မရပါဘူး။ တကယ်လုပ်ငန်းခွင်တွေမှာ သုံးနေတဲ့ Framework တွေကိုလဲ\r\nထပ်ပြီးလေ့လာသင့်ပါတယ်။ ဒါမှသာ ကျနော်တို့က လုပ်ငန်းခွင်မှာ ဝင်ရောက်နိုင်မှာပါ ဒီအတန်းမှာတော့\r\nFront End ပိုင်းဖြစ်တဲ့ Javascript ရဲ့ Framework နဲ့&nbsp; PHP ရဲ့ Framework ဖြစ်တဲ့ Larvael အကြောင်းတွေကို\r\nထပ်ပြီးလေ့လာရမှာ ဖြစ်ပါတယ်။ ဒီအတန်းမှာ ဆိုရင်လဲ Mini Project ပေါင်းများစွာနဲ့\r\nECO Shop Project တစ်ခုကိုပါ သင်ကြားရမှာပါ။ Module တစ်ခုချင်းစီတိုင်းမှာလဲ\r\nCoding Test တွေ ဖြေဆိုရမှာဖြစ်ပါတယ်။ ဒီမှာလဲ leaderboard တွေ အမှတ်တွေ နဲ့ အတူသွားတာဖြစ်လို့\r\nအားနည်းတဲ့သူတွေကိုလဲ ပြန်လည်သင်ကြားပေးမှာဖြစ်ပါတယ်။ ချွင်းချက်အနေနဲ့ကတော့ ဒီအတန်းလေးကို\r\nလေ့လာဖို့အတွက် Html , Css , JS အနည်းငယ်ကိုတော့ သိထားရမှာ ဖြစ်ပါတယ်။ တကယ်လို PHP\r\nမရသေးရင်တော့ OOP Concepts ကနေပြီးပြန်သင်ကြားပေးမှာဖြစ်ပါတယ်။ ဒီအတန်းလေးနဲ့ ပတ်သတ်ပြီးတော့\r\nအသေးစိတ်ကိုလဲ အောက်မှာ သွားပြီးလေ့လာနိုင်ပါတယ်။<o:p></o:p></span></p>','https://www.facebook.com/yhacomputerhledan/posts/pfbid0sABWomG33fXKyUDfoBMCTxgcrcUrUzb7LbrMKZWBQaQTqY1iESg7t5ACzjNJrmmgl',1),(5,'MERN Stack','6ac202a58b0d1_27.jpg','This is MERN Stack',300000,500000,130,'2025-07-10 03:00:11','2026-10-04 01:09:17','<p>This is MERN Stack About</p>','link',1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `drop_outs`
--

DROP TABLE IF EXISTS `drop_outs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `drop_outs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `drop_out_date` date NOT NULL,
  `remark` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drop_outs_student_id_foreign` (`student_id`),
  KEY `drop_outs_drop_out_date_index` (`drop_out_date`),
  KEY `drop_outs_course_id_foreign` (`course_id`),
  CONSTRAINT `drop_outs_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `drop_outs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=610 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drop_outs`
--

LOCK TABLES `drop_outs` WRITE;
/*!40000 ALTER TABLE `drop_outs` DISABLE KEYS */;
/*!40000 ALTER TABLE `drop_outs` ENABLE KEYS */;
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
INSERT INTO `events` VALUES (1,'WDD COding','2025-06-27','<p>Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo Helllo&nbsp;</p>','2025-06-30 03:23:07','2026-10-07 04:11:03','events/JKOBM15mJpDrH8FxagbTbPUebdPTqbM2YxJ4z9HV.jpg'),(2,'MERN Stack Coding','2025-06-29','<p>MERN</p>','2025-07-02 00:26:16','2026-10-07 04:11:28','events/edrGs94dQui3Jac0nlwW2k4caBNhMLx5qikNnfoY.jpg'),(5,'Hello MERN','2025-06-30','<p>Hello MERN Stack</p>','2025-07-02 00:27:40','2026-10-07 04:11:48','events/dsb5WOcdhF8eQFRuP6W21UVvtDXLD5odSDsT0oW3.webp'),(6,'ICT','2025-07-02','<p>ICT</p>','2025-07-02 00:28:38','2026-10-07 04:12:23','events/74qPYVgQ3i4zYMk9q8oISo4H11gayTnr2JeRuDdB.jpg');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam_answers`
--

DROP TABLE IF EXISTS `exam_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exam_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `answer_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submitted_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `exam_answers_course_subject_student_unique` (`course_id`,`subject_id`,`student_id`),
  KEY `exam_answers_subject_id_foreign` (`subject_id`),
  KEY `exam_answers_student_id_foreign` (`student_id`),
  CONSTRAINT `exam_answers_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_answers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_answers_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=272 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_answers`
--

LOCK TABLES `exam_answers` WRITE;
/*!40000 ALTER TABLE `exam_answers` DISABLE KEYS */;
INSERT INTO `exam_answers` VALUES (2,2,20,16,'exam/answers/demo-microsoft-word-answer.pdf','2026-09-27 10:15:00','2026-10-04 01:02:55','2026-10-04 01:02:55'),(4,4,8,16,'exam/answers/demo-git-and-git-hub-answer.pdf','2026-09-27 10:15:00','2026-10-04 01:02:55','2026-10-04 01:02:55'),(229,3,8,34,'exam/answers/6ac46b50b6e7e_untitled-3.pdf','2026-10-06 10:00:24','2026-10-06 03:30:24','2026-10-06 03:30:24');
/*!40000 ALTER TABLE `exam_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exam_questions`
--

DROP TABLE IF EXISTS `exam_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exam_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `exam_date` date NOT NULL,
  `is_published` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `question_file` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_questions_subject_id_foreign` (`subject_id`),
  KEY `exam_questions_course_subject_date_index` (`course_id`,`subject_id`,`exam_date`),
  CONSTRAINT `exam_questions_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_questions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=944 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exam_questions`
--

LOCK TABLES `exam_questions` WRITE;
/*!40000 ALTER TABLE `exam_questions` DISABLE KEYS */;
INSERT INTO `exam_questions` VALUES (1,2,1,'06:47:55','10:32:55','2026-10-04','published','exam/questions/demo-html-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(4,2,22,'13:00:00','15:00:00','2026-10-01','published','exam/questions/demo-microsoft-power-point-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(5,2,23,'13:00:00','15:00:00','2026-10-01','published','exam/questions/demo-internet-and-email-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(6,2,24,'13:00:00','15:00:00','2026-10-01','published','exam/questions/demo-computer-system-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(7,2,25,'13:00:00','15:00:00','2026-10-01','published','exam/questions/demo-basic-graphic-design-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(9,4,6,'06:47:55','10:32:55','2026-10-04','published','exam/questions/demo-php-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(12,4,10,'13:00:00','15:00:00','2026-10-01','published','exam/questions/demo-laravel-paper.pdf','2026-10-04 01:02:55','2026-10-04 01:02:55'),(176,3,8,'09:58:00','10:10:00','2026-10-06','published','exam/questions/6ac216646a330_python-django.pdf','2026-10-04 02:33:32','2026-10-06 03:28:06');
/*!40000 ALTER TABLE `exam_questions` ENABLE KEYS */;
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
-- Table structure for table `grading_results`
--

DROP TABLE IF EXISTS `grading_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grading_results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grade_id` bigint unsigned DEFAULT NULL,
  `student_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `score` decimal(6,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grading_results_grade_id_foreign` (`grade_id`),
  KEY `grading_results_student_id_foreign` (`student_id`),
  KEY `grading_results_subject_id_foreign` (`subject_id`),
  KEY `grading_results_course_subject_date_index` (`course_id`,`subject_id`,`date`),
  CONSTRAINT `grading_results_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grading_results_grade_id_foreign` FOREIGN KEY (`grade_id`) REFERENCES `gradings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grading_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grading_results_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grading_results`
--

LOCK TABLES `grading_results` WRITE;
/*!40000 ALTER TABLE `grading_results` DISABLE KEYS */;
INSERT INTO `grading_results` VALUES (451,509,34,3,8,80.00,'2026-10-06','2026-10-06 08:48:31','2026-10-06 08:48:31'),(484,546,16,3,8,72.00,'2026-10-07','2026-10-07 03:25:23','2026-10-07 03:25:23');
/*!40000 ALTER TABLE `grading_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gradings`
--

DROP TABLE IF EXISTS `gradings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gradings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` decimal(6,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gradings_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=547 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gradings`
--

LOCK TABLES `gradings` WRITE;
/*!40000 ALTER TABLE `gradings` DISABLE KEYS */;
INSERT INTO `gradings` VALUES (509,'A',90.00,'2026-10-06 08:46:38','2026-10-06 08:46:38'),(546,'B',70.00,'2026-10-07 03:24:48','2026-10-07 03:24:48');
/*!40000 ALTER TABLE `gradings` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2023_12_30_045704_create_sessions_table',1),(7,'2023_12_30_061140_create_welcomes_table',1),(8,'2023_12_30_062615_create_abouts_table',1),(9,'2023_12_30_062627_create_about_descs_table',1),(10,'2024_01_06_070144_create_teachers_table',1),(11,'2024_01_06_081501_create_courses_table',1),(12,'2024_01_06_085705_create_subjects_table',1),(13,'2024_01_07_131325_create_positions_table',1),(14,'2024_01_07_154105_create_teaches_table',1),(15,'2024_01_09_123124_create_class_models_table',1),(16,'2024_01_09_152058_create_sections_table',1),(17,'2024_01_10_143209_create_course_sections_table',1),(18,'2024_01_11_044438_create_registers_table',1),(19,'2024_01_11_044537_create_attendances_table',1),(20,'2024_01_11_044549_create_time_tables_table',1),(21,'2024_01_15_175756_create_projects_table',1),(22,'2024_01_16_090407_create_galleries_table',1),(23,'2024_01_16_182846_changing_useragain_table',1),(24,'2024_01_22_064305_voucher_table',1),(25,'2024_01_22_185701_payment_table',1),(26,'2024_01_25_030616_final_pay.table',1),(27,'2024_01_25_072224_add_column_to_final_pay_table',1),(28,'2024_01_30_014615_create_addresses_table',1),(29,'2024_01_30_041316_create_events_table',1),(30,'2024_02_05_180346_create_course_types_table',1),(31,'2024_02_07_180254_add_courses_table',1),(32,'2024_07_28_053626_adding_coursetype_col_in_courses',1),(33,'2024_08_03_094351_add_section_to_voucher',1),(34,'2024_08_17_055453_changing_col_datatype_of_course',1),(35,'2024_08_18_050344_chang_event_about_data_type',1),(36,'2024_09_07_074542_create_monthlies_table',1),(37,'2024_09_07_094925_adding_address',1),(38,'2024_09_21_053746_adding_col__monthly',1),(39,'2024_09_28_083218_add_user_phone_and_role',1),(40,'2024_11_06_065215_event_detail',1),(41,'2025_07_14_152515_create_reviews_table',1),(42,'2026_02_10_051831_make_type_nullable_in_courses_table',1),(43,'2026_09_29_000000_rename_class_models_to_subject_detail_table',1),(44,'2026_09_29_010000_rename_registers_to_students_table',1),(45,'2026_09_29_020000_create_student_enrollments_table',1),(46,'2026_09_29_030000_make_students_city_nullable',1),(47,'2026_09_30_010000_harden_enrollment_course_section_attendance',2),(48,'2026_09_30_020000_unique_subject_detail_pairs',2),(49,'2026_09_30_030000_add_remark_to_attendances',2),(50,'2026_09_30_040000_create_materials_table',2),(51,'2026_09_30_050000_create_exam_questions_table',2),(52,'2026_09_30_060000_create_exam_answers_table',2),(53,'2026_10_03_000000_add_remark_to_reference_table',2),(54,'2026_10_03_010000_consolidate_materials_into_reference',2),(55,'2026_10_03_020000_allow_multiple_files_per_subject',2),(56,'2026_10_04_000000_add_question_file_to_exam_questions_table',2),(57,'2026_10_04_010000_create_drop_outs_table',3),(58,'2026_10_04_020000_create_certificates_table',3),(59,'2026_10_04_030000_create_gradings_table',3),(60,'2026_10_04_040000_create_grading_results_table',4),(61,'2026_10_05_000000_add_course_id_to_drop_outs_table',5),(62,'2026_10_08_000000_add_certificate_file_to_certificates_table',6);
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
  `student_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_course_id_foreign` (`course_id`),
  KEY `fk_projects_student_id` (`student_id`),
  CONSTRAINT `fk_projects_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,1,34,'WDD Test 1','Hello','6ac5c5c2a4d6c_711532208_991513263638754_9118989842733427847_n.jpg','2025-07-02 00:32:03','2026-10-07 05:23:03'),(2,1,16,'YHA','Hello','6ac5c620be941_707809455_987167510739996_8026825719239876301_n.jpg','2025-07-02 00:46:16','2026-10-07 05:23:24'),(3,3,34,'MERN','PS LEssons','6ac5c63218807_651035790_927066936750054_1955781679198796888_n.jpg','2025-07-02 00:46:44','2026-10-07 05:24:25'),(4,2,16,'ICT','Hello','6ac5c644859bf_703587651_980667121390035_792404673991599985_n.jpg','2025-07-02 00:51:22','2026-10-07 05:24:36');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reference`
--

DROP TABLE IF EXISTS `reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reference` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reference_subject_id_foreign` (`subject_id`),
  KEY `reference_course_id_subject_id_index` (`course_id`,`subject_id`),
  KEY `reference_type_index` (`type`),
  CONSTRAINT `reference_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reference_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reference`
--

LOCK TABLES `reference` WRITE;
/*!40000 ALTER TABLE `reference` DISABLE KEYS */;
INSERT INTO `reference` VALUES (1,3,4,'book','Hello Book','reference/6ac204598ea4d_dsa-101-data-structures-algorithms.pdf','Hello','2026-10-04 01:16:33','2026-10-04 01:16:33'),(2,3,8,'video','Video record Day 1','reference/6ac2097e16d4d_ict-notepadplus-install-video.mp4','Git Git Hub Guide','2026-10-04 01:38:30','2026-10-04 01:38:30'),(3,4,10,'zip','Laravel lesson Day 1','reference/6ac20a6f922a6_javascript.zip','Laravel lesson','2026-10-04 01:42:31','2026-10-04 01:42:31'),(4,2,1,'book','hello day 1','reference/6ac4bae18bc90_figma-basics.pdf','Html Css','2026-10-06 09:09:53','2026-10-06 09:09:53'),(21,2,23,'book',NULL,'reference/6ac5cb7fa933a_the-css-display-property-a-bootcamp-teaching-deck-1.pdf',NULL,'2026-10-07 04:33:03','2026-10-07 04:33:03');
/*!40000 ALTER TABLE `reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
INSERT INTO `sessions` VALUES ('fcxDso8Od4GUMY0wtCTcbwoHjNETLDyioLD046oF',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic3VaenlqaWRxS0paOWxsRjI0a0lUbGhaSjAycldmaTE4c0V2M0lKSCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=',1791433248),('QxioCt2RxQV2Nu1C8eefbk7zhY2kMfnbwOihTr5i',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRkk5bkNlZFdrOVhoZ25HdGRobFJsU1JCdndhY3l6OW5vU1QxM3YxNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9qZWN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1791433245),('tvtaaWOY2ACnUyP3k5C5YM1VqdL5SpadZYxOkdDE',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUW9GUGhGMWhrUWdIaDV1NnNLODI5ckpTS29TR0FNVDRBaWs3UGxBYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU0OiJsb2dpbl9zdHVkZW50XzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzQ7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1791440774);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_enrollments`
--

DROP TABLE IF EXISTS `student_enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_enrollments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned NOT NULL,
  `section_id` bigint unsigned DEFAULT NULL,
  `enroll_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `complete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_enrollments_section_id_foreign` (`section_id`),
  KEY `student_enrollments_course_id_enroll_date_index` (`course_id`,`enroll_date`),
  KEY `student_enrollments_active_lookup` (`student_id`,`course_id`,`section_id`,`status`),
  CONSTRAINT `student_enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_enrollments_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL,
  CONSTRAINT `student_enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=617 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_enrollments`
--

LOCK TABLES `student_enrollments` WRITE;
/*!40000 ALTER TABLE `student_enrollments` DISABLE KEYS */;
INSERT INTO `student_enrollments` VALUES (3,16,2,1,'2026-09-04','2026-10-04 01:02:45','2026-10-05 08:29:39','active',NULL),(4,16,3,1,'2026-09-04','2026-10-04 01:02:45','2026-10-07 03:51:58','active',NULL),(142,34,3,1,'2026-10-05','2026-10-05 09:20:09','2026-10-05 09:20:09','active',NULL),(616,34,2,1,'2026-10-07','2026-10-07 04:33:36','2026-10-07 04:33:36','active',NULL);
/*!40000 ALTER TABLE `student_enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned DEFAULT NULL,
  `section_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `viber_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telegram_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_acc_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `native_town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `religious_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `township` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nrc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `education` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_username_unique` (`username`),
  KEY `registers_course_id_foreign` (`course_id`),
  KEY `registers_section_id_foreign` (`section_id`),
  CONSTRAINT `registers_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registers_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (16,NULL,NULL,'Shoon','Shoon','2026-10-04 00:00:00',NULL,NULL,NULL,NULL,NULL,'shoon@yha.test',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'shoyha39','yha90135',NULL,'2026-10-04 01:02:45','2026-10-04 01:02:45','male','active'),(34,NULL,NULL,'Arkar Yan','hidecard',NULL,'U Mab','Daw Mi','09882328992','09882328992','yhacomputer','yhacomputer@gmail.com','No.29, 6th Floor','Arkar Yan',NULL,'Yangon','Buddhist','Bu',NULL,'2026-09-29 00:00:00','5/SaKaNa(N)280923','6ac36b8908a87_open.png','arkyha50','$2y$12$XUPq/rO229CKCtAjdct3b.nK2k/aj3mtRjhSRDpU/gH8z0DkINdbK','CU First year','2026-10-05 09:19:05','2026-10-05 09:19:05','male','active');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_detail`
--

DROP TABLE IF EXISTS `subject_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subject_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_detail_course_id_subject_id_unique` (`course_id`,`subject_id`),
  KEY `class_models_subject_id_foreign` (`subject_id`),
  CONSTRAINT `class_models_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_models_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_detail`
--

LOCK TABLES `subject_detail` WRITE;
/*!40000 ALTER TABLE `subject_detail` DISABLE KEYS */;
INSERT INTO `subject_detail` VALUES (1,1,1,'2025-06-30 01:29:46','2025-06-30 01:29:46'),(2,2,1,'2025-06-30 01:29:56','2025-06-30 01:29:56'),(4,4,9,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(5,4,10,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(6,4,6,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(7,4,8,'2025-07-06 19:36:42','2025-07-06 19:36:42'),(8,5,11,'2025-07-10 03:01:46','2025-07-10 03:01:46'),(9,5,12,'2025-07-10 03:01:46','2025-07-10 03:01:46'),(10,2,20,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(11,2,21,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(12,2,22,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(13,2,23,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(14,2,24,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(15,2,25,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(16,2,26,'2026-10-04 01:02:45','2026-10-04 01:02:45'),(17,3,4,'2026-10-04 01:25:42','2026-10-04 01:25:42'),(18,3,8,'2026-10-04 01:25:42','2026-10-04 01:25:42'),(19,3,5,'2026-10-04 01:25:42','2026-10-04 01:25:42');
/*!40000 ALTER TABLE `subject_detail` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Html','2025-06-29 22:35:02','2025-06-29 22:35:02'),(2,'Css','2025-06-29 22:35:07','2025-06-29 22:35:07'),(3,'Bootstrap','2025-06-29 22:35:17','2025-06-29 22:35:17'),(4,'JavaScript','2025-06-29 22:35:25','2025-06-29 22:35:25'),(5,'Figma','2025-06-29 22:35:37','2025-06-29 22:35:37'),(6,'Php','2025-06-29 22:35:48','2025-06-29 22:35:48'),(7,'MySql','2025-06-29 22:35:55','2025-06-29 22:35:55'),(8,'Git and Git Hub','2025-06-29 22:36:11','2025-06-29 22:36:11'),(9,'Vue Js','2025-06-29 22:36:25','2025-06-29 22:37:05'),(10,'Laravel','2025-06-29 22:36:34','2025-06-29 22:36:34'),(11,'React Js','2025-06-29 22:36:42','2025-06-29 22:36:42'),(12,'MongoDB','2025-07-10 03:01:21','2025-07-10 03:01:21'),(20,'Microsoft Word','2026-10-04 01:02:45','2026-10-04 01:02:45'),(21,'Microsoft Excel','2026-10-04 01:02:45','2026-10-04 01:02:45'),(22,'Microsoft Power Point','2026-10-04 01:02:45','2026-10-04 01:02:45'),(23,'Internet and Email','2026-10-04 01:02:45','2026-10-04 01:02:45'),(24,'Computer System','2026-10-04 01:02:45','2026-10-04 01:02:45'),(25,'Basic Graphic Design','2026-10-04 01:02:45','2026-10-04 01:02:45'),(26,'Basic Programming','2026-10-04 01:02:45','2026-10-04 01:02:45');
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
  CONSTRAINT `time_tables_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
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
INSERT INTO `welcomes` VALUES (1,'6ac203c853b60_photo_2026-09-28_11-22-01.jpg','2025-06-29 22:31:48','2026-10-04 01:14:08'),(2,'6ac203e3ef40d_photo_2026-09-28_11-22-01.jpg','2025-06-29 22:31:56','2026-10-04 01:14:35'),(3,'6ac203d3b1b7c_photo_2026-09-28_11-21-54.jpg','2025-06-29 22:32:03','2026-10-04 01:14:19');
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

-- Dump completed on 2026-10-08 13:02:03
