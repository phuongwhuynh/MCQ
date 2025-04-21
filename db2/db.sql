-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: mcq_db
-- ------------------------------------------------------
-- Server version	8.0.36
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
-- Table structure for table `admin`
--
DROP TABLE IF EXISTS `admin`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `admin` (
    `user_id` int unsigned NOT NULL AUTO_INCREMENT,
    `status` enum ('active', 'deleted') NOT NULL,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--
LOCK TABLES `admin` WRITE;

/*!40000 ALTER TABLE `admin` DISABLE KEYS */;

INSERT INTO
  `admin`
VALUES
  (1, 'active'),
  (2, 'active');

/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `admin_cur_question`
--
DROP TABLE IF EXISTS `admin_cur_question`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `admin_cur_question` (
    `creator` int unsigned NOT NULL,
    `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `cate` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `ans1` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `ans2` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `ans3` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `ans4` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `correct_answer` enum ('1', '2', '3', '4') DEFAULT NULL,
      `image_path` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`creator`),
      KEY `cate` (`cate`),
      CONSTRAINT `admin_cur_question_ibfk_1` FOREIGN KEY (`cate`) REFERENCES `category` (`cate`),
      CONSTRAINT `admin_cur_question_ibfk_2` FOREIGN KEY (`creator`) REFERENCES `admin` (`user_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_cur_question`
--
LOCK TABLES `admin_cur_question` WRITE;

/*!40000 ALTER TABLE `admin_cur_question` DISABLE KEYS */;

/*!40000 ALTER TABLE `admin_cur_question` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `category`
--
DROP TABLE IF EXISTS `category`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `category` (
    `cate` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
      PRIMARY KEY (`cate`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--
LOCK TABLES `category` WRITE;

/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO
  `category`
VALUES
  ('Geography'),
  ('History'),
  ('Literature'),
  ('Math'),
  ('Science');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `chosen_answer`
--
DROP TABLE IF EXISTS `chosen_answer`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `chosen_answer` (
    `question_id` int unsigned NOT NULL,
    `attempt_id` int unsigned NOT NULL,
    `answer` enum ('1', '2', '3', '4') DEFAULT NULL,
    PRIMARY KEY (`question_id`, `attempt_id`),
    KEY `attempt_id` (`attempt_id`),
    CONSTRAINT `chosen_answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`),
    CONSTRAINT `chosen_answer_ibfk_2` FOREIGN KEY (`attempt_id`) REFERENCES `test_attempt` (`attempt_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chosen_answer`
--
LOCK TABLES `chosen_answer` WRITE;

/*!40000 ALTER TABLE `chosen_answer` DISABLE KEYS */;

INSERT INTO
  `chosen_answer`
VALUES
  (2, 1, '2'),
  (2, 4, '3'),
  (8, 1, '1'),
  (8, 4, '3'),
  (10, 3, '3'),
  (13, 1, '1'),
  (13, 4, '1'),
  (16, 3, '4'),
  (19, 2, '2'),
  (20, 2, '3'),
  (29, 1, '1'),
  (30, 1, '1'),
  (34, 1, '3');

/*!40000 ALTER TABLE `chosen_answer` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `question`
--
DROP TABLE IF EXISTS `question`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `question` (
    `question_id` int unsigned NOT NULL AUTO_INCREMENT,
    `description` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `cate` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `ans1` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `ans2` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `ans3` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `ans4` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `correct_answer` enum ('1', '2', '3', '4') NOT NULL,
      `image_path` varchar(255) DEFAULT NULL,
      `creator` int unsigned DEFAULT NULL,
      `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
      `status` enum ('active', 'deleted') DEFAULT 'active',
      PRIMARY KEY (`question_id`),
      KEY `creator` (`creator`),
      KEY `cate` (`cate`),
      CONSTRAINT `question_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `admin` (`user_id`),
      CONSTRAINT `question_ibfk_2` FOREIGN KEY (`cate`) REFERENCES `category` (`cate`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 41 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question`
--
LOCK TABLES `question` WRITE;

/*!40000 ALTER TABLE `question` DISABLE KEYS */;

INSERT INTO
  `question`
VALUES
  (
    1,
    'How do you write 100 + 60 + 8 in standard form?\\r\\n\\r\\n\\r\\n',
    'Math',
    '169',
    '1,068',
    '168',
    '170',
    '3',
    NULL,
    1,
    '2025-04-14 13:27:22',
    'active'
  ),
  (
    2,
    'Largest 3 digit number is?',
    'Math',
    '100',
    '900',
    '199',
    '999',
    '4',
    'images/questions/2.jpg',
    1,
    '2025-04-14 13:30:21',
    'active'
  ),
  (
    3,
    '40 + 2 is expanded form for which number?',
    'Math',
    '42',
    '72',
    '96',
    '402',
    '1',
    NULL,
    1,
    '2025-04-14 13:31:01',
    'active'
  ),
  (
    4,
    'Convert 200 + 70 + 4 to standard form.',
    'Math',
    '2074',
    '274',
    '247',
    '2047',
    '2',
    NULL,
    1,
    '2025-04-14 13:31:53',
    'active'
  ),
  (
    5,
    '100',
    'Math',
    'One',
    'One hundred',
    'Ten',
    'One hundred and one',
    '2',
    NULL,
    1,
    '2025-04-14 13:32:31',
    'active'
  ),
  (
    6,
    '120 + 20 + 1 =',
    'Math',
    '120,201',
    '141',
    '321',
    '100+201',
    '2',
    NULL,
    1,
    '2025-04-14 13:33:05',
    'active'
  ),
  (
    7,
    'What is 200 + 30 + 9?',
    'Math',
    '239',
    '2039',
    '200,309',
    '20,309',
    '1',
    NULL,
    1,
    '2025-04-14 13:33:36',
    'active'
  ),
  (
    8,
    'What is 917 written in expanded form?',
    'Math',
    '900 + 10 + 7',
    '90 + 10 + 7',
    '9 + 1 + 7',
    '9+17',
    '1',
    NULL,
    1,
    '2025-04-14 13:34:07',
    'active'
  ),
  (
    9,
    'What is 567 in expanded form?',
    'Math',
    '600 + 50 + 7',
    '500 + 60 + 7',
    '700 + 60 + 5',
    '600 + 70 + 5',
    '2',
    NULL,
    1,
    '2025-04-14 13:34:37',
    'active'
  ),
  (
    10,
    'Write this number in EXPANDED form',
    'Math',
    '900 + 37',
    '973',
    ' Nine hundred seventy three',
    '900 + 70 + 3',
    '4',
    'images/questions/10.png',
    1,
    '2025-04-14 13:35:25',
    'active'
  ),
  (
    11,
    'Which answer is in expanded form?',
    'Math',
    '300 + 20 + 5',
    '3 hundreds, 2 tens, 5 ones',
    '325',
    'Three hundred twenty-five',
    '1',
    NULL,
    1,
    '2025-04-14 13:36:06',
    'active'
  ),
  (
    12,
    'Write the number 549 in expanded form.',
    'Math',
    ' 500 + 50 - 1',
    '500 + 40 + 9',
    '400 + 140 + 9',
    ' 500 + 4 + 90',
    '2',
    NULL,
    1,
    '2025-04-14 13:36:38',
    'active'
  ),
  (
    13,
    'What is the expanded form of this number? 650',
    'Math',
    '600+50+0',
    '60+500+0',
    ' 6+5+0',
    '60+5',
    '1',
    NULL,
    1,
    '2025-04-14 13:37:16',
    'active'
  ),
  (
    14,
    'What is 254 in expanded form?',
    'Math',
    '1+2+5+4',
    '20+500+4',
    '2+5+4',
    '200+50+4',
    '4',
    NULL,
    1,
    '2025-04-14 13:37:47',
    'active'
  ),
  (
    15,
    'What is the expanded form for 897?',
    'Math',
    ' 800 + 70 + 9',
    ' 800 + 70 + 9',
    ' 80 + 7',
    '80 + 9',
    '2',
    NULL,
    1,
    '2025-04-14 13:38:21',
    'active'
  ),
  (
    16,
    'What is the expanded form of the number 456?',
    'Math',
    '400 + 500 + 6',
    '400 + 5 + 6',
    ' 400 + 50 + 6',
    ' 400 + 50 + 60',
    '3',
    NULL,
    1,
    '2025-04-14 13:38:51',
    'active'
  ),
  (
    17,
    'What is the expanded form for 418?',
    'Math',
    '8 + 400 + 18',
    '400 + 10 + 8',
    ' 100 + 40 + 8',
    '4+1+8',
    '2',
    NULL,
    1,
    '2025-04-14 13:39:15',
    'active'
  ),
  (
    18,
    'What is the expanded form of 369?',
    'Math',
    '300 + 60 +9',
    '300 + 6 +90',
    '3 + 600 + 90',
    '30 + 60 +9',
    '1',
    NULL,
    1,
    '2025-04-14 13:40:03',
    'active'
  ),
  (
    19,
    'Đảng ta xác định kế hoạch, mục tiêu và những giải pháp quan trọng để phát triển kinh tế - xã hội trong 5 năm, đó là năm nào ?',
    'History',
    '2011-2016',
    '2016-2020',
    '2012-2016',
    '2015-2020',
    '2',
    NULL,
    1,
    '2025-04-14 13:42:46',
    'active'
  ),
  (
    20,
    'Phương châm và định hướng lớn của hoạt động đối ngoại là gì?',
    'History',
    'Bảo đảm lợi ích quốc gia - dân tộc,dựa trên nguyên tắc cơ bản của luật pháp quốc tế, bình đẳng và cùng có lợi',
    'Tập trung phát huy vai trò chính trị, nâng cao hiệu quả các hoạt động đối ngoại',
    ' Đa dạng hóa, đa phương hóa trong quan hệ đối ngoại; chủ động và tích cực hội nhập quốc tế',
    ' Giữ vững môi trường hòa bình, ổn định khu vực vì mục tiêu “dân giàu, nước mạnh, dân chủ, công bằng, văn minh”',
    '3',
    NULL,
    1,
    '2025-04-14 13:43:11',
    'active'
  ),
  (
    21,
    'Đại hội nào của Đảng khẳng định: “Tư tưởng HCM không chỉ là kết quả của sự vận dụng sáng tạo mà còn phát triển sáng tạo chủ nghĩa Mác – Lênin vào điều kiện cụ thể của nước ta”?',
    'History',
    'Đại hội VI',
    ' Đại hội VIII',
    'Đại hội IX',
    ' Đại hội X',
    '3',
    'images/questions/21.jpg',
    1,
    '2025-04-14 13:43:52',
    'active'
  ),
  (
    22,
    'Đâu là nguyên tắc Đúng trong Đại hội Đảng Cộng sản Việt Nam XI?',
    'History',
    '“Bảo đảm lợi ích quốc gia, giữ vững độc lập, tự chủ, vì hòa bình, hữu nghị, hợp tác và phát triển”',
    '“Tôn trọng các nguyên tắc cơ bản của luật pháp quốc tế, Hiến chương Liên hợp quốc”.',
    '“Ứng xử của khu vực\\\"',
    ' Tất cả đều đúng',
    '4',
    NULL,
    1,
    '2025-04-14 13:44:21',
    'active'
  ),
  (
    23,
    'Mục tiêu đối ngoại của Đảng ta trong đại hội XI là gì?',
    'History',
    'Bảo đảm lợi ích quốc gia là cơ sở cơ bản để xây dựng một nước Việt Nam xã hội chủ nghĩa.',
    ' Bảo đảm lợi ích dân tộc là cơ sở cơ bản để xây dựng một nước Việt Nam xã hội chủ nghĩa.',
    'Cả hai câu a, b đều đúng.',
    ' Cả hai câu a, b đều sai.',
    '3',
    NULL,
    1,
    '2025-04-14 13:45:03',
    'active'
  ),
  (
    24,
    'Đại hội Đảng toàn quốc lần thứ V có những quyết định quan trọng. Điều nào sau đây chưa phải là quyết định của Đại hội này?',
    'History',
    'Cả nước tiến nhanh, tiến mạnh, tiến vững chắc lên CNXH',
    'Tiếp tục đường lối xây dựng CNXH trong phạm vi cả nước',
    'Thời kỳ quá độ lên CNXH ở nước ta phải trải qua nhiều chặng',
    ' Phương hướng, nhiệm vụ, mục tiêu của kế hoạch 5 năm (1981-1985)',
    '1',
    NULL,
    1,
    '2025-04-14 13:45:31',
    'active'
  ),
  (
    25,
    'Quyết định 25-CP trong chính sách về công nghiệp nói về quyền gì:',
    'History',
    'Chủ động sản xuất kinh doanh và tư chủ về tài chính',
    'Mở rộng hình thức trả lương khoán',
    ' Vận dụng hình thức tiền thưởng trong đơn vị sản xuất kinh doanh',
    'Tất cả đều đúng',
    '1',
    NULL,
    1,
    '2025-04-14 13:45:56',
    'active'
  ),
  (
    26,
    'Trong các nguồn lực để công nghiệp hóa, hiện đại hóa ở nước ta đến năm 2020, Đại hội VIII của Đảng đã xác định nguồn lực nào là yếu tố cơ bản cho sự phát triển nhanh và bền vững?',
    'History',
    ' Khoa học công nghệ',
    ' Tài nguyên đất đai',
    ' Con người',
    'Cả A,B,C',
    '3',
    NULL,
    1,
    '2025-04-14 13:46:23',
    'active'
  ),
  (
    27,
    'Đại hội lần thứ V của Đảng khẳng định điều gì?',
    'History',
    'Tiếp tục thực hiện đường lối cách mạng xã hội chủ nghĩa đã vạch ra từ Đại hội lần thứ IV',
    ' Thực hiện công cuộc đổi mới đất nước',
    'Thực hiện công cuộc công nghiệp hóa và hiện đại hóa đất nước',
    'Tất cả đều đúng',
    '1',
    NULL,
    1,
    '2025-04-14 13:46:52',
    'active'
  ),
  (
    28,
    '\\r\\n“Nước ta đã thoát khỏi khủng hoảng kinh tế - xã hội nhưng một số mặt còn chưa vững chắc” là đánh giá tổng quát của Đại hội nào?',
    'History',
    'Đại hội VI',
    ' Đại hội VII',
    'Đại hội VIII',
    ' Đại hội IX',
    '3',
    NULL,
    1,
    '2025-04-14 13:47:19',
    'active'
  ),
  (
    29,
    'What is the opening that extends from the magma chamber to the top of the volcano called?',
    'Science',
    ' magma',
    ' lava',
    'vent',
    ' crater',
    '3',
    'images/questions/29.jpg',
    1,
    '2025-04-14 13:50:15',
    'active'
  ),
  (
    30,
    'What is Magma when it reaches the surface of Earth?',
    'Science',
    'Lava',
    ' Mafic Magma',
    ' Felsic Magma',
    'None of these',
    '1',
    'images/questions/30.jpg',
    1,
    '2025-04-14 13:50:58',
    'active'
  ),
  (
    31,
    'This is the name for the chain of volcanos around the edge of the Pacific Plate.',
    'Science',
    'Circle of Fire',
    ' Ring of Lava',
    'Ring of Fire',
    'Circle of Magma',
    '3',
    'images/questions/31.jpg',
    1,
    '2025-04-14 13:51:38',
    'active'
  ),
  (
    32,
    'As magma rises towards the surface it collects in a \\\'room\\\' called a _____ _____ . ',
    'Science',
    'magma chamber',
    'side vent',
    'main vent',
    ' lava chamber',
    '1',
    'images/questions/32.jpg',
    1,
    '2025-04-14 13:52:25',
    'active'
  ),
  (
    33,
    'What place can you see?',
    'Geography',
    'A church.',
    ' A museum.',
    ' A park.',
    'A hospital',
    '4',
    'images/questions/33.png',
    1,
    '2025-04-14 13:55:11',
    'active'
  ),
  (
    34,
    'I can see the...',
    'Geography',
    ' café.',
    ' police station.',
    'supermarket.',
    ' post office.',
    '3',
    'images/questions/34.jpg',
    1,
    '2025-04-14 13:56:03',
    'active'
  ),
  (
    35,
    'What place can you see?',
    'Geography',
    'A church.',
    ' A museum.',
    ' A park.',
    'A hospital',
    '4',
    'images/questions/35.png',
    2,
    '2025-04-14 14:00:26',
    'active'
  ),
  (
    36,
    '_____ do you live?',
    'Literature',
    ' Here',
    'Where',
    'There',
    'Near',
    '2',
    NULL,
    2,
    '2025-04-14 14:01:02',
    'active'
  ),
  (
    37,
    'The bus stop is over ____.',
    'Literature',
    ' where',
    ' on',
    'there',
    'near',
    '3',
    NULL,
    2,
    '2025-04-14 14:01:34',
    'active'
  ),
  (
    38,
    'He lives ____ the school. It is next to his house.',
    'Literature',
    'near',
    'here',
    'between',
    'there',
    '1',
    NULL,
    2,
    '2025-04-14 14:02:02',
    'active'
  ),
  (
    39,
    'I want to eat sushi. Let\\\'s go to the ____ over there.',
    'Literature',
    'train station',
    ' library',
    'convenience store',
    ' restaurant',
    '4',
    NULL,
    2,
    '2025-04-14 14:02:33',
    'active'
  ),
  (
    40,
    'The supermarket is ____ the museum.',
    'Geography',
    ' between',
    ' in front of',
    'behin',
    'next to',
    '4',
    'images/questions/40.png',
    2,
    '2025-04-14 14:03:21',
    'active'
  );

/*!40000 ALTER TABLE `question` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `test`
--
DROP TABLE IF EXISTS `test`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `test` (
    `test_id` int unsigned NOT NULL AUTO_INCREMENT,
    `test_name` text CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci,
      `total_time` int unsigned NOT NULL,
      `cate` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `number_of_questions` smallint unsigned DEFAULT NULL,
      `creator` int unsigned DEFAULT NULL,
      `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
      `status` enum ('public', 'private', 'deleted') NOT NULL DEFAULT 'private',
      `image_path` varchar(255) DEFAULT NULL,
      `total_attempts` int unsigned DEFAULT '0',
      PRIMARY KEY (`test_id`),
      KEY `creator` (`creator`),
      CONSTRAINT `test_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `admin` (`user_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 15 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test`
--
LOCK TABLES `test` WRITE;

/*!40000 ALTER TABLE `test` DISABLE KEYS */;

INSERT INTO
  `test`
VALUES
  (
    1,
    'Test',
    10,
    'Math',
    4,
    1,
    '2025-04-14 13:41:21',
    'public',
    'images/tests/1.jpg',
    1
  ),
  (
    2,
    'LỊCH SỬ ĐẢNG CỘNG SẢN VIỆT NAM',
    10,
    'History',
    10,
    1,
    '2025-04-14 13:48:06',
    'public',
    'images/tests/2.jpg',
    2
  ),
  (
    3,
    'Volcanoes',
    5,
    'Science',
    4,
    1,
    '2025-04-14 13:53:36',
    'public',
    'images/tests/3.jpg',
    0
  ),
  (
    4,
    'Test 2',
    5,
    'Geography',
    6,
    1,
    '2025-04-14 13:56:50',
    'public',
    'images/tests/4.jpg',
    2
  ),
  (
    5,
    'Test 3',
    10,
    'Literature',
    10,
    1,
    '2025-04-14 13:57:15',
    'public',
    NULL,
    0
  ),
  (
    6,
    'Test 4',
    10,
    'Literature',
    11,
    1,
    '2025-04-14 13:57:35',
    'public',
    'images/tests/6.png',
    0
  ),
  (
    7,
    'Literature final test',
    10,
    'Literature',
    4,
    2,
    '2025-04-14 14:04:48',
    'public',
    'images/tests/7.jpg',
    0
  ),
  (
    8,
    'Literature	midterm test',
    50,
    'Literature',
    5,
    2,
    '2025-04-14 14:05:17',
    'public',
    'images/tests/8.jpg',
    0
  ),
  (
    9,
    'Geography test',
    15,
    'Geography',
    2,
    2,
    '2025-04-14 14:05:57',
    'public',
    'images/tests/9.jpg',
    0
  ),
  (
    10,
    'Final exam',
    60,
    'Literature',
    6,
    2,
    '2025-04-14 14:06:29',
    'public',
    NULL,
    0
  ),
  (
    11,
    'Random quiz',
    15,
    'Science',
    3,
    2,
    '2025-04-14 14:07:15',
    'private',
    NULL,
    0
  ),
  (
    12,
    'Random test',
    15,
    'Math',
    4,
    1,
    '2025-04-14 14:08:33',
    'public',
    NULL,
    0
  ),
  (
    13,
    'Kinh tế chính trị',
    50,
    'History',
    9,
    1,
    '2025-04-14 14:09:15',
    'public',
    'images/tests/13.jpg',
    0
  ),
  (
    14,
    'Triết học',
    30,
    'History',
    10,
    1,
    '2025-04-14 14:10:00',
    'public',
    'images/tests/14.jpg',
    0
  );

/*!40000 ALTER TABLE `test` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `test_attempt`
--
DROP TABLE IF EXISTS `test_attempt`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `test_attempt` (
    `attempt_id` int unsigned NOT NULL AUTO_INCREMENT,
    `status` enum ('IN_PROGRESS', 'FINISHED') NOT NULL,
    `start_time` datetime NOT NULL,
    `current_question` smallint unsigned DEFAULT NULL,
    `score` smallint unsigned DEFAULT NULL,
    `user_id` int unsigned DEFAULT NULL,
    `test_id` int unsigned DEFAULT NULL,
    PRIMARY KEY (`attempt_id`),
    KEY `user_id` (`user_id`),
    KEY `test_id` (`test_id`),
    CONSTRAINT `test_attempt_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
    CONSTRAINT `test_attempt_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_attempt`
--
LOCK TABLES `test_attempt` WRITE;

/*!40000 ALTER TABLE `test_attempt` DISABLE KEYS */;

INSERT INTO
  `test_attempt`
VALUES
  (1, 'FINISHED', '2025-04-14 14:13:25', 7, 4, 3, 4),
  (2, 'FINISHED', '2025-04-14 14:13:55', 3, 2, 3, 2),
  (3, 'FINISHED', '2025-04-14 14:31:51', 3, 0, 3, 1),
  (
    4,
    'IN_PROGRESS',
    '2025-04-14 14:42:17',
    4,
    0,
    3,
    4
  ),
  (
    5,
    'IN_PROGRESS',
    '2025-04-14 14:43:02',
    1,
    0,
    3,
    2
  );

/*!40000 ALTER TABLE `test_attempt` ENABLE KEYS */;

UNLOCK TABLES;

/*!50003 SET @saved_cs_client      = @@character_set_client */;

/*!50003 SET @saved_cs_results     = @@character_set_results */;

/*!50003 SET @saved_col_connection = @@collation_connection */;

/*!50003 SET character_set_client  = utf8mb4 */;

/*!50003 SET character_set_results = utf8mb4 */;

/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */;

/*!50003 SET @saved_sql_mode       = @@sql_mode */;

/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */;

DELIMITER;

;

/*!50003 CREATE*/
/*!50017 DEFINER=`root`@`localhost`*/
/*!50003 TRIGGER `trg_update_total_attempts` AFTER INSERT ON `test_attempt` FOR EACH ROW BEGIN
UPDATE test
SET total_attempts = total_attempts + 1
WHERE test_id = NEW.test_id;
END */;

;

DELIMITER;

/*!50003 SET sql_mode              = @saved_sql_mode */;

/*!50003 SET character_set_client  = @saved_cs_client */;

/*!50003 SET character_set_results = @saved_cs_results */;

/*!50003 SET collation_connection  = @saved_col_connection */;

--
-- Table structure for table `test_have_question`
--
DROP TABLE IF EXISTS `test_have_question`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `test_have_question` (
    `test_id` int unsigned NOT NULL,
    `question_id` int unsigned NOT NULL,
    `question_number` smallint unsigned DEFAULT NULL,
    PRIMARY KEY (`test_id`, `question_id`),
    KEY `question_id` (`question_id`),
    CONSTRAINT `test_have_question_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test` (`test_id`),
    CONSTRAINT `test_have_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_have_question`
--
LOCK TABLES `test_have_question` WRITE;

/*!40000 ALTER TABLE `test_have_question` DISABLE KEYS */;

INSERT INTO
  `test_have_question`
VALUES
  (1, 10, 1),
  (1, 16, 2),
  (1, 17, 3),
  (1, 18, 4),
  (2, 19, 1),
  (2, 20, 2),
  (2, 21, 3),
  (2, 22, 4),
  (2, 23, 5),
  (2, 24, 6),
  (2, 25, 7),
  (2, 26, 8),
  (2, 27, 9),
  (2, 28, 10),
  (3, 29, 1),
  (3, 30, 2),
  (3, 31, 3),
  (3, 32, 4),
  (4, 2, 1),
  (4, 8, 2),
  (4, 13, 3),
  (4, 29, 4),
  (4, 30, 5),
  (4, 34, 6),
  (5, 2, 1),
  (5, 4, 2),
  (5, 6, 3),
  (5, 8, 4),
  (5, 10, 5),
  (5, 11, 6),
  (5, 13, 7),
  (5, 29, 8),
  (5, 30, 9),
  (5, 34, 10),
  (6, 2, 1),
  (6, 4, 2),
  (6, 6, 3),
  (6, 8, 4),
  (6, 10, 5),
  (6, 11, 6),
  (6, 13, 7),
  (6, 19, 8),
  (6, 29, 9),
  (6, 30, 10),
  (6, 34, 11),
  (7, 36, 1),
  (7, 37, 2),
  (7, 38, 3),
  (7, 39, 4),
  (8, 35, 1),
  (8, 37, 2),
  (8, 38, 3),
  (8, 39, 4),
  (8, 40, 5),
  (9, 35, 1),
  (9, 40, 2),
  (10, 35, 1),
  (10, 36, 2),
  (10, 37, 3),
  (10, 38, 4),
  (10, 39, 5),
  (10, 40, 6),
  (11, 37, 1),
  (11, 39, 2),
  (11, 40, 3),
  (12, 21, 1),
  (12, 24, 2),
  (12, 28, 3),
  (12, 32, 4),
  (13, 8, 1),
  (13, 9, 2),
  (13, 14, 3),
  (13, 18, 4),
  (13, 21, 5),
  (13, 24, 6),
  (13, 28, 7),
  (13, 30, 8),
  (13, 32, 9),
  (14, 2, 1),
  (14, 20, 2),
  (14, 21, 3),
  (14, 23, 4),
  (14, 24, 5),
  (14, 25, 6),
  (14, 28, 7),
  (14, 31, 8),
  (14, 32, 9),
  (14, 34, 10);

/*!40000 ALTER TABLE `test_have_question` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `user`
--
DROP TABLE IF EXISTS `user`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `user` (
    `user_id` int unsigned NOT NULL AUTO_INCREMENT,
    `status` enum ('active', 'deleted') NOT NULL,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--
LOCK TABLES `user` WRITE;

/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO
  `user`
VALUES
  (3, 'active');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `users` (
    `user_id` int unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password_hash` char(255) NOT NULL,
    `email` varchar(255) DEFAULT NULL,
    `name` varchar(255) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `role` enum ('admin', 'user') DEFAULT NULL,
      PRIMARY KEY (`user_id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
LOCK TABLES `users` WRITE;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO
  `users`
VALUES
  (
    1,
    'admin',
    '$2y$10$EaTJ2hbHCaA5TXVW4quwXeMOC7GcB.Jazamd4ZNIdfY02z7ut0pNK',
    'abc@gmail.com',
    'phuong huynh',
    'admin'
  ),
  (
    2,
    'admin2',
    '$2y$10$7yVl/6P1RpN5CEf1mu9wWOd/5MfJSHMwHv24pD0y.93B56wJ5jZeG',
    'abc@gmail.com',
    'zzz zzz',
    'admin'
  ),
  (
    3,
    'user1',
    '$2y$10$JcMJLESTfz7YTNT7KuEQ.u4BoE5/ifFlrd7MUgrZx.e0lsR6m2RRy',
    'ABC@gmail.com',
    'a A',
    'user'
  );

/*!40000 ALTER TABLE `users` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Dumping events for database 'mcq_db'
--
--
-- Dumping routines for database 'mcq_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-14 14:57:53