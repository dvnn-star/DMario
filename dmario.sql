-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: absensi
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Starter',NULL,NULL,NULL,NULL),(2,'Soup',NULL,NULL,NULL,NULL),(3,'Noodle Soup',NULL,NULL,NULL,NULL),(4,'Snack',NULL,NULL,NULL,NULL),(5,'Burger',NULL,NULL,NULL,NULL),(6,'Share Menu',NULL,NULL,NULL,NULL),(7,'Pizzette',NULL,NULL,NULL,NULL),(8,'Pasta',NULL,NULL,NULL,NULL),(9,'Fried Rice and noodler',NULL,NULL,NULL,NULL),(10,'Asian Delight',NULL,NULL,NULL,NULL),(11,'Skewers',NULL,NULL,NULL,NULL),(12,'Authentic Sate Selectioan',NULL,NULL,NULL,NULL),(13,'Vegetables',NULL,NULL,NULL,NULL),(14,'Dessert',NULL,NULL,NULL,NULL),(15,'Classic Coffee',NULL,NULL,NULL,NULL),(16,'Manual Brew',NULL,NULL,NULL,NULL),(17,'Signature Coffee',NULL,NULL,NULL,NULL),(18,'Signature Non Coffee',NULL,NULL,NULL,NULL),(19,'Tea Series',NULL,NULL,NULL,NULL),(20,'Non Coffee',NULL,NULL,NULL,NULL),(21,'Mocktail',NULL,NULL,NULL,NULL),(22,'Fresh Juice',NULL,NULL,NULL,NULL),(23,'Smothies',NULL,NULL,NULL,NULL),(24,'Soft Drink',NULL,NULL,NULL,NULL),(25,'Starter',NULL,NULL,NULL,NULL),(26,'Soup',NULL,NULL,NULL,NULL),(27,'Noodle Soup',NULL,NULL,NULL,NULL),(28,'Snack',NULL,NULL,NULL,NULL),(29,'Burger',NULL,NULL,NULL,NULL),(30,'Share Menu',NULL,NULL,NULL,NULL),(31,'Pizzette',NULL,NULL,NULL,NULL),(32,'Pasta',NULL,NULL,NULL,NULL),(33,'Fried Rice and noodler',NULL,NULL,NULL,NULL),(34,'Asian Delight',NULL,NULL,NULL,NULL),(35,'Skewers',NULL,NULL,NULL,NULL),(36,'Authentic Sate Selectioan',NULL,NULL,NULL,NULL),(37,'Vegetables',NULL,NULL,NULL,NULL),(38,'Dessert',NULL,NULL,NULL,NULL),(39,'Classic Coffee',NULL,NULL,NULL,NULL),(40,'Manual Brew',NULL,NULL,NULL,NULL),(41,'Signature Coffee',NULL,NULL,NULL,NULL),(42,'Signature Non Coffee',NULL,NULL,NULL,NULL),(43,'Tea Series',NULL,NULL,NULL,NULL),(44,'Non Coffee',NULL,NULL,NULL,NULL),(45,'Mocktail',NULL,NULL,NULL,NULL),(46,'Fresh Juice',NULL,NULL,NULL,NULL),(47,'Smothies',NULL,NULL,NULL,NULL),(48,'Soft Drink',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
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
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` int NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_recommended` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_category_id_foreign` (`category_id`),
  CONSTRAINT `menu_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,NULL,'INSALATA DI CAESAR','Selada romain segar yang dicampur dengan bawang putih & teri, diberi telur rebus, potongan bacon, dada ayam panggang, crouton panggang, dan taburan keju parmesan parut.',55000,1,1,1,'2026-07-22 07:26:48','2026-07-22 09:21:55'),(2,NULL,'GADO-GADO','Campuran sayuran Indonesia harian (wortel, kol, tauge, kacang panjang, bayam) disajikan dengan lontong, telur rebus, kentang rebus, kerupuk emping, dan saus kacang.',35000,1,1,1,'2026-07-22 07:26:48','2026-07-22 09:22:02'),(3,NULL,'RUJAK','Salad buah campur Indonesia dengan asam jawa, gula aren, dan saus kacang pedas.',28000,1,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(4,NULL,'THAI CHICKEN SALAD','Ayam panggang dengan selada campur disajikan dengan Dressing rempah Thailand.',25000,1,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(5,NULL,'CREMA DI FUNGI','Sup krim jamur hutan dengan aroma tomat kering dan bawang, disajikan dengan stik puff pastry.',32000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(6,NULL,'CREAMY PUMKIN','Sup krim labu panggang dengan aroma tomat kering, disajikan dengan crouton roti.',32000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(7,NULL,'ZUPPA DI PATATE E PORRI','Sup klasik kentang dan daun bawang disajikan dengan potongan bacon dan crouton roti.',32000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(8,NULL,'TOM YAM TALAY','Sup Thailand dengan udang, ikan, dan cumi, jamur, tomat, dan pasta tom yum disajikan dengan bawang merah goreng dan daun ketumbar.',48000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(9,NULL,'TOM YAM GAI','Sup Thailand dengan ayam, wortel, jagung muda, jamur, tomat, dan pasta tom yum disajikan dengan bawang merah goreng dan daun ketumbar.',35000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(10,NULL,'ANDALAS/ SUP KAMBING','Bahu domba yang dimasak lambat dalam krim kuning kelapa dengan kentang, wortel, irisan kol putih, tomat, bawang merah goreng, seledri, dan daun bawang.',65000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(11,NULL,'CHICKEN CLEAR SOUP','Sup bening dengan ayam, kentang, wortel, jamur, tomat, disajikan dengan nasi kukus dan sambal cabai hijau di samping.',36000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(12,NULL,'OXTAIL SOUP','Sup buntut otentik Indonesia disajikan dengan kentang, wortel, tomat, bawang merah goreng, seledri, daun bawang, disajikan dengan nasi kukus, sambal, dan jeruk nipis.',122000,2,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(13,NULL,'SOTO AYAM','Sup tradisional Indonesia dengan mi nasi, ayam, tomat, daun bawang di atasnya, disajikan dengan sambal dan jeruk nipis.',27000,3,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(14,NULL,'SINGAPORE LAKSA','Mi laksa, udang, fishcake, tahu, telur rebus dalam kuah laksa.',38000,3,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(15,NULL,'Beef Spring Roll','Daging sapi cincang yang digoreng dalam kulit lumpia bertepung, disajikan dengan saus Keju dan saus BBQ.',56000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(16,NULL,'CHICKEN CHEESSE BALL','Bola keju goreng dalam ayam renyah disajikan dengan saus pepper relish dan keju leleh di atasnya.',55000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(17,NULL,'CHICKEN FIRE WING','Sayap ayam renyah goreng dicampur dengan saus bbq, biji wijen di atasnya, dan saus keju oranye di samping.',43000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(18,NULL,'FISH AND CHIP','Ikan goreng dengan adonan bir, French fries disajikan dengan saus tomat dan saus tartar.',42000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(19,NULL,'FISH FINGER','Ikan bertepung goreng dengan salad campur disajikan dengan saus tartar dan mayo madu pedas.',42000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(20,NULL,'VEGETABLE SPRING ROLL','Sayuran goreng dalam kulit lumpia disajikan dengan saus cabai manis.',28000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(21,NULL,'GARLIC FRIES','Kentang goreng lurus yang dicampur dengan mentega bawang putih dan keju parmesan parut disajikan dengan saus keju Cheddar.',35000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(22,NULL,'POTATO WEDGES','Kentang wedges goreng disajikan dengan mayones, cabai Thailand, dan saus keju oranye.',37000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(23,NULL,'HASHBRON','Hashbrown goreng disajikan dengan salad campur, saus keju oranye, dan saus chili mayo.',55000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(24,NULL,'CASSAVA','Singkong goreng disajikan dengan chilli con carne dan saus keju oranye.',32000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(25,NULL,'CHICKEN BURITOS','Ayam juicy dengan paprika campur, keju leleh, saus krim dibungkus dalam kulit tortilla disajikan dengan saus pepper relish.',58000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(26,NULL,'CHICKEN NUGGET','Ayam renyah goreng disajikan dengan selada campur dan saus keju.',42000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(27,NULL,'GOHYONG','Ayam cincang yang dimarinasi dengan udang dan digulung dengan kulit tahu dan disajikan dengan saus cabai manis.',52000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(28,NULL,'CIRENG','Makanan ringan tradisional Indonesia dengan lapisan tepung terigu dan tepung beras disajikan dengan saus manis pedas.',35000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(29,NULL,'OTAK OTAK CRISPY','Makanan ringan tradisional Indonesia dengan ikan cincang, tepung terigu, dan digulung dengan rice pepper disajikan dengan saus manis pedas.',38000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(30,NULL,'EMPEK - EMPEK','Makanan ringan tradisional Indonesia dengan lapisan tepung terigu dan ikan cincang segar disajikan dengan saus manis pedas.',65000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(31,NULL,'EMPEK - EMPEK D\'MARIO','Varian empek-empek khas D\'Mario.',65000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(32,NULL,'EMPEK - EMPEK KULIT','Empek-empek yang dibuat dari kulit ikan pilihan.',65000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(33,NULL,'EMPEK - EMPEK LENJER','Empek-empek berbentuk lonjong (lenjer).',65000,4,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(34,NULL,'BEEF BURGER','Patty daging sapi panggang dengan keju cheddar leleh, beef bacon, telur goreng, selada, irisan tomat dalam bun wijen panggang disajikan dengan garlic parmesan fries dan gravy.',58000,5,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(35,NULL,'CHICKEN BURGER','Patty ayam renyah goreng dengan keju cheddar leleh, selada, irisan tomat dalam bun wijen panggang disajikan dengan garlic parmesan fries.',52000,5,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(36,NULL,'GRILL PLATTER','Platter campur dengan kaki ayam panggang, sosis Bruwtash panggang, lamb chop shoulder panggang disajikan dengan saus BBQ, saus lada hitam, atau saus jamur.',185000,6,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(37,NULL,'CHICKEN LOLLIPOPS','Paha ayam empuk, dibungkus dalam bacon asap dengan glasir madu bbq, disajikan dengan hashbrown dan potato wedges.',235000,6,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(38,NULL,'MIX PLATER','Platter campur dengan fish finger, cumi calamari, sayap ayam, chicken nugget, hashbrown, potato wedges disajikan dengan salad campur, saus tartar, saus keju, dan saus BBQ.',135000,6,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(39,NULL,'D\'MARIO','Saus tomat, keju mozzarella, keju cheddar, tomat segar, bawang bombay, paprika campur panggang, dan udang cabai merah beraroma dengan percikan minyak zaitun.',125000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(40,NULL,'PEPERONI ARROSITITI','Saus tomat, keju mozzarella, keju cheddar, beef peperoni, bawang bombay, dan paprika campur panggang.',115000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(41,NULL,'THREEMUSKETER','Beef pepperoni, chicken ham, paprika, berbasis tomat, dan keju mozzarella.',125000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(42,NULL,'BIANCA','Ayam panggang dalam cabai manis, bawang bombay, paprika, berbasis tomat, dan keju mozzarella.',115000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(43,NULL,'AL FUNGI','Berbasis tomat, keju mozzarella, jamur, dan bawang bombay.',110000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(44,NULL,'HAWAIIAN','Ayam panggang, chicken ham, paprika, nanas, berbasis tomat, dan keju mozzarella.',120000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(45,NULL,'CHICKEN FLORENTINE','Berbasis tomat, keju mozarela dengan ayam krim, paprika campur, bawang bombay, dan jagung.',125000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(46,NULL,'MARGARITA','Berbasis tomat, keju mozarela, tomat segar, disajikan dengan oregano.',85000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(47,NULL,'BOLOGNAISE','Saus tomat, keju mozzarella, keju cheddar, bawang bombay, daging sapi cincang yang dimarinasi, dan paprika campur panggang.',125000,7,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(48,NULL,'AGLIO OLIO DE PEPPERONCINO','Pilihan pasta: SPAGHETTI, PENNE, DAN LINGUINE. Ditumis dengan minyak zaitun, bawang putih, cabai kering, peterseli, dan udang panggang di atasnya.',45000,8,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(49,NULL,'BEEF LASAGNA','Ragout daging sapi cincang Italia dengan lapisan pasta yang dimasak dalam saus tomat, keju, dan saus bechamel.',75000,8,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(50,NULL,'ALFREDO','Saus krim kontemporer yang diresapi keju dengan chicken ham dan kacang polong, diberi irisan dada ayam panggang.',60000,8,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(51,NULL,'BOLOGNESE','Ragout daging sapi cincang Italia dengan anggur merah dan saus tomat, bawang bombay karamel, dan oregano.',55000,8,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(52,NULL,'LAMB FRIED RICE','Nasi goreng Indonesia dengan domba, pasta cabai, telur goreng, lamb chop shoulder panggang, kerupuk, dan acar.',85000,9,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(53,NULL,'SEAFOOD FRIED RICE','Nasi goreng dengan makanan laut, sayuran, dicampur dengan pasta XO disajikan dengan telur goreng, udang, dan kerupuk.',47000,9,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(54,NULL,'KAMPONG BUGIS FRIED RICE','Nasi goreng gaya kampung dengan pasta cabai, ikan teri disajikan dengan ayam goreng, telur goreng, kerupuk udang, dan acar.',45000,9,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(55,NULL,'CHICKEN FRIED RICE','Nasi goreng Indonesia dengan ayam, pasta cabai, sate ayam, telur goreng, ayam goreng, kerupuk, dan acar.',38000,9,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(56,NULL,'JAVA FRIED NOODLE','Mi goreng Indonesia dengan ayam, sayuran campur, dan pasta cabai disajikan dengan telur goreng, kerupuk, dan acar.',38000,9,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(57,NULL,'SEAFOOD FRIED NOODLE','Mi goreng dengan udang, cumi, bakso ikan, sayuran campur, dan pasta XO disajikan dengan telur goreng, prawn beer butter, dan kerupuk.',48000,9,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(58,NULL,'AYAM PANGGANG TALIWANG','Setengah ayam panggang dengan marinasi khas Bali, disajikan dengan sayuran, nasi kukus, acar, dan kerupuk.',65000,10,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(59,NULL,'BUNTUT BAKAR','Buntut sapi panggang yang dimarinasi pedas dan manis dengan bumbu dan rempeyek krekers disajikan dengan nasi kukus.',128000,10,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(60,NULL,'AYAM BAKAR KECAP','Paha ayam panggang dengan marinasi pasta kuning Indonesia disajikan dengan tahu.',55000,10,1,0,'2026-07-22 07:26:48','2026-07-22 07:26:48'),(61,NULL,'INSALATA DI CAESAR','Selada romain segar yang dicampur dengan bawang putih & teri, diberi telur rebus, potongan bacon, dada ayam panggang, crouton panggang, dan taburan keju parmesan parut.',55000,1,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(62,NULL,'GADO-GADO','Campuran sayuran Indonesia harian (wortel, kol, tauge, kacang panjang, bayam) disajikan dengan lontong, telur rebus, kentang rebus, kerupuk emping, dan saus kacang.',35000,1,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(63,NULL,'RUJAK','Salad buah campur Indonesia dengan asam jawa, gula aren, dan saus kacang pedas.',28000,1,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(64,NULL,'THAI CHICKEN SALAD','Ayam panggang dengan selada campur disajikan dengan Dressing rempah Thailand.',25000,1,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(65,NULL,'CREMA DI FUNGI','Sup krim jamur hutan dengan aroma tomat kering dan bawang, disajikan dengan stik puff pastry.',32000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(66,NULL,'CREAMY PUMKIN','Sup krim labu panggang dengan aroma tomat kering, disajikan dengan crouton roti.',32000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(67,NULL,'ZUPPA DI PATATE E PORRI','Sup klasik kentang dan daun bawang disajikan dengan potongan bacon dan crouton roti.',32000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(68,NULL,'TOM YAM TALAY','Sup Thailand dengan udang, ikan, dan cumi, jamur, tomat, dan pasta tom yum disajikan dengan bawang merah goreng dan daun ketumbar.',48000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(69,NULL,'TOM YAM GAI','Sup Thailand dengan ayam, wortel, jagung muda, jamur, tomat, dan pasta tom yum disajikan dengan bawang merah goreng dan daun ketumbar.',35000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(70,NULL,'ANDALAS/ SUP KAMBING','Bahu domba yang dimasak lambat dalam krim kuning kelapa dengan kentang, wortel, irisan kol putih, tomat, bawang merah goreng, seledri, dan daun bawang.',65000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(71,NULL,'CHICKEN CLEAR SOUP','Sup bening dengan ayam, kentang, wortel, jamur, tomat, disajikan dengan nasi kukus dan sambal cabai hijau di samping.',36000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(72,NULL,'OXTAIL SOUP','Sup buntut otentik Indonesia disajikan dengan kentang, wortel, tomat, bawang merah goreng, seledri, daun bawang, disajikan dengan nasi kukus, sambal, dan jeruk nipis.',122000,2,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(73,NULL,'SOTO AYAM','Sup tradisional Indonesia dengan mi nasi, ayam, tomat, daun bawang di atasnya, disajikan dengan sambal dan jeruk nipis.',27000,3,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(74,NULL,'SINGAPORE LAKSA','Mi laksa, udang, fishcake, tahu, telur rebus dalam kuah laksa.',38000,3,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(75,NULL,'Beef Spring Roll','Daging sapi cincang yang digoreng dalam kulit lumpia bertepung, disajikan dengan saus Keju dan saus BBQ.',56000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(76,NULL,'CHICKEN CHEESSE BALL','Bola keju goreng dalam ayam renyah disajikan dengan saus pepper relish dan keju leleh di atasnya.',55000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(77,NULL,'CHICKEN FIRE WING','Sayap ayam renyah goreng dicampur dengan saus bbq, biji wijen di atasnya, dan saus keju oranye di samping.',43000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(78,NULL,'FISH AND CHIP','Ikan goreng dengan adonan bir, French fries disajikan dengan saus tomat dan saus tartar.',42000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(79,NULL,'FISH FINGER','Ikan bertepung goreng dengan salad campur disajikan dengan saus tartar dan mayo madu pedas.',42000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(80,NULL,'VEGETABLE SPRING ROLL','Sayuran goreng dalam kulit lumpia disajikan dengan saus cabai manis.',28000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(81,NULL,'GARLIC FRIES','Kentang goreng lurus yang dicampur dengan mentega bawang putih dan keju parmesan parut disajikan dengan saus keju Cheddar.',35000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(82,NULL,'POTATO WEDGES','Kentang wedges goreng disajikan dengan mayones, cabai Thailand, dan saus keju oranye.',37000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(83,NULL,'HASHBRON','Hashbrown goreng disajikan dengan salad campur, saus keju oranye, dan saus chili mayo.',55000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(84,NULL,'CASSAVA','Singkong goreng disajikan dengan chilli con carne dan saus keju oranye.',32000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(85,NULL,'CHICKEN BURITOS','Ayam juicy dengan paprika campur, keju leleh, saus krim dibungkus dalam kulit tortilla disajikan dengan saus pepper relish.',58000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(86,NULL,'CHICKEN NUGGET','Ayam renyah goreng disajikan dengan selada campur dan saus keju.',42000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(87,NULL,'GOHYONG','Ayam cincang yang dimarinasi dengan udang dan digulung dengan kulit tahu dan disajikan dengan saus cabai manis.',52000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(88,NULL,'CIRENG','Makanan ringan tradisional Indonesia dengan lapisan tepung terigu dan tepung beras disajikan dengan saus manis pedas.',35000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(89,NULL,'OTAK OTAK CRISPY','Makanan ringan tradisional Indonesia dengan ikan cincang, tepung terigu, dan digulung dengan rice pepper disajikan dengan saus manis pedas.',38000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(90,NULL,'EMPEK - EMPEK','Makanan ringan tradisional Indonesia dengan lapisan tepung terigu dan ikan cincang segar disajikan dengan saus manis pedas.',65000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(91,NULL,'EMPEK - EMPEK D\'MARIO','Varian empek-empek khas D\'Mario.',65000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(92,NULL,'EMPEK - EMPEK KULIT','Empek-empek yang dibuat dari kulit ikan pilihan.',65000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(93,NULL,'EMPEK - EMPEK LENJER','Empek-empek berbentuk lonjong (lenjer).',65000,4,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(94,NULL,'BEEF BURGER','Patty daging sapi panggang dengan keju cheddar leleh, beef bacon, telur goreng, selada, irisan tomat dalam bun wijen panggang disajikan dengan garlic parmesan fries dan gravy.',58000,5,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(95,NULL,'CHICKEN BURGER','Patty ayam renyah goreng dengan keju cheddar leleh, selada, irisan tomat dalam bun wijen panggang disajikan dengan garlic parmesan fries.',52000,5,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(96,NULL,'GRILL PLATTER','Platter campur dengan kaki ayam panggang, sosis Bruwtash panggang, lamb chop shoulder panggang disajikan dengan saus BBQ, saus lada hitam, atau saus jamur.',185000,6,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(97,NULL,'CHICKEN LOLLIPOPS','Paha ayam empuk, dibungkus dalam bacon asap dengan glasir madu bbq, disajikan dengan hashbrown dan potato wedges.',235000,6,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(98,NULL,'MIX PLATER','Platter campur dengan fish finger, cumi calamari, sayap ayam, chicken nugget, hashbrown, potato wedges disajikan dengan salad campur, saus tartar, saus keju, dan saus BBQ.',135000,6,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(99,NULL,'D\'MARIO','Saus tomat, keju mozzarella, keju cheddar, tomat segar, bawang bombay, paprika campur panggang, dan udang cabai merah beraroma dengan percikan minyak zaitun.',125000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(100,NULL,'PEPERONI ARROSITITI','Saus tomat, keju mozzarella, keju cheddar, beef peperoni, bawang bombay, dan paprika campur panggang.',115000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(101,NULL,'THREEMUSKETER','Beef pepperoni, chicken ham, paprika, berbasis tomat, dan keju mozzarella.',125000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(102,NULL,'BIANCA','Ayam panggang dalam cabai manis, bawang bombay, paprika, berbasis tomat, dan keju mozzarella.',115000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(103,NULL,'AL FUNGI','Berbasis tomat, keju mozzarella, jamur, dan bawang bombay.',110000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(104,NULL,'HAWAIIAN','Ayam panggang, chicken ham, paprika, nanas, berbasis tomat, dan keju mozzarella.',120000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(105,NULL,'CHICKEN FLORENTINE','Berbasis tomat, keju mozarela dengan ayam krim, paprika campur, bawang bombay, dan jagung.',125000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(106,NULL,'MARGARITA','Berbasis tomat, keju mozarela, tomat segar, disajikan dengan oregano.',85000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(107,NULL,'BOLOGNAISE','Saus tomat, keju mozzarella, keju cheddar, bawang bombay, daging sapi cincang yang dimarinasi, dan paprika campur panggang.',125000,7,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(108,NULL,'AGLIO OLIO DE PEPPERONCINO','Pilihan pasta: SPAGHETTI, PENNE, DAN LINGUINE. Ditumis dengan minyak zaitun, bawang putih, cabai kering, peterseli, dan udang panggang di atasnya.',45000,8,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(109,NULL,'BEEF LASAGNA','Ragout daging sapi cincang Italia dengan lapisan pasta yang dimasak dalam saus tomat, keju, dan saus bechamel.',75000,8,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(110,NULL,'ALFREDO','Saus krim kontemporer yang diresapi keju dengan chicken ham dan kacang polong, diberi irisan dada ayam panggang.',60000,8,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(111,NULL,'BOLOGNESE','Ragout daging sapi cincang Italia dengan anggur merah dan saus tomat, bawang bombay karamel, dan oregano.',55000,8,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(112,NULL,'LAMB FRIED RICE','Nasi goreng Indonesia dengan domba, pasta cabai, telur goreng, lamb chop shoulder panggang, kerupuk, dan acar.',85000,9,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(113,NULL,'SEAFOOD FRIED RICE','Nasi goreng dengan makanan laut, sayuran, dicampur dengan pasta XO disajikan dengan telur goreng, udang, dan kerupuk.',47000,9,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(114,NULL,'KAMPONG BUGIS FRIED RICE','Nasi goreng gaya kampung dengan pasta cabai, ikan teri disajikan dengan ayam goreng, telur goreng, kerupuk udang, dan acar.',45000,9,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(115,NULL,'CHICKEN FRIED RICE','Nasi goreng Indonesia dengan ayam, pasta cabai, sate ayam, telur goreng, ayam goreng, kerupuk, dan acar.',38000,9,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(116,NULL,'JAVA FRIED NOODLE','Mi goreng Indonesia dengan ayam, sayuran campur, dan pasta cabai disajikan dengan telur goreng, kerupuk, dan acar.',38000,9,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(117,NULL,'SEAFOOD FRIED NOODLE','Mi goreng dengan udang, cumi, bakso ikan, sayuran campur, dan pasta XO disajikan dengan telur goreng, prawn beer butter, dan kerupuk.',48000,9,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(118,NULL,'AYAM PANGGANG TALIWANG','Setengah ayam panggang dengan marinasi khas Bali, disajikan dengan sayuran, nasi kukus, acar, dan kerupuk.',65000,10,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(119,NULL,'BUNTUT BAKAR','Buntut sapi panggang yang dimarinasi pedas dan manis dengan bumbu dan rempeyek krekers disajikan dengan nasi kukus.',128000,10,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45'),(120,NULL,'AYAM BAKAR KECAP','Paha ayam panggang dengan marinasi pasta kuning Indonesia disajikan dengan tahu.',55000,10,1,0,'2026-07-22 07:27:45','2026-07-22 07:27:45');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_08_14_170933_add_two_factor_columns_to_users_table',1),(5,'2026_02_01_103113_create_categories_table',1),(6,'2026_02_01_103147_create_menu_items_table',1),(7,'2026_02_03_093943_create_tables_table',1),(8,'2026_02_03_094339_create_orders_table',1),(9,'2026_02_03_094346_create_order_details_table',1),(10,'2026_02_03_103253_create_reservations_table',1),(11,'2026_07_20_153340_add_soft_deletes_to_orders_table',1),(12,'2026_07_20_160331_add_price_to_order_details_table',1),(13,'2026_07_21_151433_add_soft_deletes_to_categories_table',1),(14,'2026_07_21_152456_add_slug_to_categories_table',1),(15,'2026_07_22_043147_add_deleted_at_to_reservations_table',1),(16,'2026_07_22_120339_modify_is_recommended_column_in_menu_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `menu_item_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,3) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_menu_item_id_foreign` (`menu_item_id`),
  CONSTRAINT `order_details_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_id` bigint unsigned NOT NULL,
  `total_price` decimal(12,3) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_table_id_foreign` (`table_id`),
  CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,1212121.000,'pending','cash','2026-07-23 22:56:56','2026-07-23 22:57:56',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_id` bigint unsigned NOT NULL,
  `reservation_time` datetime NOT NULL,
  `number_of_guests` int NOT NULL,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_table_id_foreign` (`table_id`),
  CONSTRAINT `reservations_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservations`
--

LOCK TABLES `reservations` WRITE;
/*!40000 ALTER TABLE `reservations` DISABLE KEYS */;
INSERT INTO `reservations` VALUES (1,'de',2,'2026-07-22 10:00:00',1,'confirmed','2026-07-22 09:08:23','2026-07-22 09:21:18',NULL),(2,'de',4,'2026-07-22 10:00:00',1,'confirmed','2026-07-22 09:15:23','2026-07-23 22:56:33',NULL),(3,'dede',3,'2026-07-23 20:00:00',1,'pending','2026-07-23 08:48:19','2026-07-23 08:48:19',NULL);
/*!40000 ALTER TABLE `reservations` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('Ce79DxFCIZGBbsF4Bq7RnzWEaCzOEs0n5ol34qwM',3,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YToxMDp7czo2OiJfdG9rZW4iO3M6NDA6ImJUQk9kbERxd2lndWRTSzdONTluVmhjSDBSZVNYYmdVYXNKWFBLRGMiO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL21lbnUtaXRlbXMiO3M6NToicm91dGUiO3M6NDE6ImZpbGFtZW50LmFkbWluLnJlc291cmNlcy5tZW51LWl0ZW1zLmluZGV4Ijt9czoyMDoiYWlfYWN0aXZlX3Nlc3Npb25faWQiO3M6NDI6Imd1ZXN0XzAyYzIxYWFlLWJlNzgtNDNjNi1hOGI4LTA1Y2NlMjEwMWRiNiI7czoxODoiYWlfc2Vzc2lvbl9oaXN0b3J5IjthOjE6e2k6MDtzOjQyOiJndWVzdF8wMmMyMWFhZS1iZTc4LTQzYzYtYThiOC0wNWNjZTIxMDFkYjYiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiIzN2YxOWQ1ODYwZDZlZWU4N2Y1MWE3ZWJkYzY3NGMwMDkyNDJlN2YxMzg2M2VlYTVjM2M4NjM5YzExYzI3NjhiIjtzOjY6InRhYmxlcyI7YTozOntzOjQwOiJhZjhkZmE0NDYyMjllYzViYzQyMWQxMTFiOGY1NTZmYl9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJ0YWJsZS5pZCI7czo1OiJsYWJlbCI7czo1OiJUYWJsZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InRvdGFsX3ByaWNlIjtzOjU6ImxhYmVsIjtzOjExOiJUb3RhbCBwcmljZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6InBheW1lbnRfbWV0aG9kIjtzOjU6ImxhYmVsIjtzOjE0OiJQYXltZW50IG1ldGhvZCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImRlbGV0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkRlbGV0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiZGRjMWQwOGViZWZhNjUyMjkwM2FiMWYzN2MzY2I4YWNfY29sdW1ucyI7YTozOntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJzbHVnIjtzOjU6ImxhYmVsIjtzOjQ6IlNsdWciO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6Ijc2ZTA1NTY1YjY4MzcwMzc4MjdiZDhhYTBkNDY5MWI0X2NvbHVtbnMiO2E6Nzp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJpbWFnZV9wYXRoIjtzOjU6ImxhYmVsIjtzOjY6IkdhbWJhciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo5OiJOYW1hIE1lbnUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEzOiJjYXRlZ29yeS5uYW1lIjtzOjU6ImxhYmVsIjtzOjg6IkthdGVnb3JpIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo1OiJwcmljZSI7czo1OiJsYWJlbCI7czo1OiJIYXJnYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6ImlzX2F2YWlsYWJsZSI7czo1OiJsYWJlbCI7czo4OiJUZXJzZWRpYSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTQ6ImlzX3JlY29tbWVuZGVkIjtzOjU6ImxhYmVsIjtzOjExOiJSZWtvbWVuZGFzaSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319fXM6OToiYWlfbWVtb3J5IjthOjE6e3M6NDI6Imd1ZXN0XzAyYzIxYWFlLWJlNzgtNDNjNi1hOGI4LTA1Y2NlMjEwMWRiNiI7YTo0OntzOjEwOiJzZXNzaW9uX2lkIjtzOjQyOiJndWVzdF8wMmMyMWFhZS1iZTc4LTQzYzYtYThiOC0wNWNjZTIxMDFkYjYiO3M6ODoibWVzc2FnZXMiO2E6NDp7aTowO2E6NDp7czo0OiJyb2xlIjtzOjQ6InVzZXIiO3M6NzoiY29udGVudCI7czoxNDoidGVzIGR1bHUgYmFuZwoiO3M6OToidGltZXN0YW1wIjtzOjI1OiIyMDI2LTA3LTI0VDEzOjI3OjUzKzAwOjAwIjtzOjg6Im1ldGFkYXRhIjthOjA6e319aToxO2E6NDp7czo0OiJyb2xlIjtzOjk6ImFzc2lzdGFudCI7czo3OiJjb250ZW50IjtzOjMwOiJIYWksIGFkYSB5YW5nIGJpc2Egc2F5YSBiYW50dT8iO3M6OToidGltZXN0YW1wIjtzOjI1OiIyMDI2LTA3LTI0VDEzOjI3OjU0KzAwOjAwIjtzOjg6Im1ldGFkYXRhIjthOjA6e319aToyO2E6NDp7czo0OiJyb2xlIjtzOjQ6InVzZXIiO3M6NzoiY29udGVudCI7czoyNDoiamVsYXNrYW4gdHJlbmQgc2FhdCBpbmkKIjtzOjk6InRpbWVzdGFtcCI7czoyNToiMjAyNi0wNy0yNFQxMzozMDo1MSswMDowMCI7czo4OiJtZXRhZGF0YSI7YTowOnt9fWk6MzthOjQ6e3M6NDoicm9sZSI7czo5OiJhc3Npc3RhbnQiO3M6NzoiY29udGVudCI7czo0NzQ6Ii0gKipUcmVuZCBwZW5kYXBhdGFuKio6IHRpZGFrIGFkYSBwZXJ1YmFoYW4gKDDigK8lKTsgcGVuZGFwYXRhbiBidWxhbiBpbmkgZGFuIGJ1bGFuIGxhbHUgc2FtYeKAkW55YSAoUnDigK8wKS4gIAotICoqQ2F0YXRhbioqOiBkYXRhIHBlbmRhcGF0YW4gYmVsdW0gdGVyc2VkaWEvdGVyY2F0YXQgdW50dWsgcGVyaW9kZSBpbmkuICAKCioqU2FyYW4gc2luZ2thdCoqICAKLSBQZXJpa3NhIGFwYWthaCBhZGEgcGVuanVhbGFuIHlhbmcgYmVsdW0gdGVyaW5wdXQgYXRhdSBtYXNhbGFoIHBhZGEgcGVuY2F0YXRhbiBQT1MuICAKLSBDZWsgcGVyZm9ybWEgbWVudSAodG9w4oCvc2VsbGluZyB2cyBkZWFk4oCRc3RvY2spIHVudHVrIG1lbmVtdWthbiBwb3RlbnNpIHBlbmluZ2thdGFuIHBlbmp1YWxhbi4gIAoKSmlrYSBpbmdpbiBhbmFsaXNpcyBsYWluIChtaXMuIG1lbnUsIHJlc2VydmFzaSwgcGVtYmF5YXJhbiksIGJlcmkgdGFodSBzYXlhLiI7czo5OiJ0aW1lc3RhbXAiO3M6MjU6IjIwMjYtMDctMjRUMTM6MzA6NTMrMDA6MDAiO3M6ODoibWV0YWRhdGEiO2E6MDp7fX19czo3OiJzdW1tYXJ5IjtOO3M6NzoiY29udGV4dCI7Tjt9fX0=',1784907702),('X878jhPwXDToxc3gSJ7h4vuCqSV5Pnbu7bYndm2O',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTFOZTM4NElQaDJVRGRhUkI2eEY3cGFPZFhvd1J1TmhITWlOVkpLbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly90dW5pbmctcmF5LXN0b3BwaW5nLWVuY29kaW5nLnRyeWNsb3VkZmxhcmUuY29tIjtzOjU6InJvdXRlIjtzOjExOiJsYW5kaW5ncGFnZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1784970685);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_number` int NOT NULL,
  `identifier` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_code_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('available','occupied','reserved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tables_identifier_unique` (`identifier`),
  UNIQUE KEY `tables_qr_code_path_unique` (`qr_code_path`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,1,'62ea2d5c-f118-4b69-8cd0-2afe8fcbfb2e','qrcodes/table-1.png','available','2026-07-22 07:27:46','2026-07-22 07:27:46'),(2,2,'2ce401d3-3079-4957-b181-ace9e042fd1c','qrcodes/table-2.png','available','2026-07-22 07:27:47','2026-07-22 07:27:47'),(3,3,'cb2a226e-3430-4eba-8bc6-a3f7a160f81d','qrcodes/table-3.png','available','2026-07-22 07:27:48','2026-07-22 07:27:48'),(4,4,'68890910-dd0f-4a66-a10f-25d712dfd2e0','qrcodes/table-4.png','available','2026-07-22 07:27:49','2026-07-22 07:27:49'),(5,5,'45c253c0-007e-465f-91e0-10ff9d8834e6','qrcodes/table-5.png','available','2026-07-22 07:27:49','2026-07-22 07:27:49'),(6,6,'f966439b-bce5-40ab-92af-af9b76becddc','qrcodes/table-6.png','available','2026-07-22 07:27:50','2026-07-22 07:27:50'),(7,7,'4e471ad4-50dc-4e9b-81af-0518ffc20706','qrcodes/table-7.png','available','2026-07-22 07:27:51','2026-07-22 07:27:51'),(8,8,'45f0045c-856f-4964-a8c4-949c5b7863c0','qrcodes/table-8.png','available','2026-07-22 07:27:52','2026-07-22 07:27:52'),(9,9,'6d08f443-9b4a-4708-8231-e0ebbcd0b13c','qrcodes/table-9.png','available','2026-07-22 07:27:53','2026-07-22 07:27:53'),(10,10,'d31d036f-eb3b-4793-ba33-23aecfe5c8f3','qrcodes/table-10.png','available','2026-07-22 07:27:54','2026-07-22 07:27:54'),(11,11,'7bff3c58-e57d-468f-846d-f805a0ada15a','qrcodes/table-11.png','available','2026-07-22 07:27:55','2026-07-22 07:27:55'),(12,12,'092ddeca-0cf5-430b-a0a2-4a0ed3f7af84','qrcodes/table-12.png','available','2026-07-22 07:27:56','2026-07-22 07:27:56'),(13,13,'7e6039f0-9077-4ec4-864d-6de9d0759faf','qrcodes/table-13.png','available','2026-07-22 07:27:57','2026-07-22 07:27:57'),(14,14,'24bc83f7-9de8-42e6-b636-7717693775a3','qrcodes/table-14.png','available','2026-07-22 07:27:58','2026-07-22 07:27:58'),(15,15,'f842f17e-0435-411e-9e88-6952adece48f','qrcodes/table-15.png','available','2026-07-22 07:27:59','2026-07-22 07:27:59'),(16,16,'31b9af98-2838-4b39-ab75-4fa9b0f3b127','qrcodes/table-16.png','available','2026-07-22 07:27:59','2026-07-22 07:27:59'),(17,17,'c5fc2354-b75f-4074-b538-03fda6cf4272','qrcodes/table-17.png','available','2026-07-22 07:28:00','2026-07-22 07:28:00'),(18,18,'b0adfb48-37cd-4c28-9c88-0122b7ebfd9a','qrcodes/table-18.png','available','2026-07-22 07:28:01','2026-07-22 07:28:01'),(19,19,'24fb99e5-809b-4983-b10a-d0a00b5172c6','qrcodes/table-19.png','available','2026-07-22 07:28:02','2026-07-22 07:28:02'),(20,20,'0451c471-ba29-42cf-bcaa-9f0e12a8b6b3','qrcodes/table-20.png','available','2026-07-22 07:28:03','2026-07-22 07:28:03');
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-07-22 07:26:47','$2y$12$EqlHCo9DNSLz4umYXHP/1Oie8WNnhqiYPazeHyQKrNjxoLlzMO1uS',NULL,NULL,NULL,NULL,'WQUTreu8zN','2026-07-22 07:26:48','2026-07-22 07:26:48'),(3,'Delvin','delvinn12.7@gmail.com',NULL,'$2y$12$eGeLkPwsRIAySrWMvWIZwOToX9fzeg1DNDfEvT1lgGxPzRzEwhdUa',NULL,NULL,NULL,'admin',NULL,'2026-07-22 09:14:41','2026-07-22 09:14:41');
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

-- Dump completed on 2026-07-25 21:31:17
