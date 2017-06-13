/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table team_planner.person
CREATE TABLE IF NOT EXISTS `person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `education` varchar(128) DEFAULT NULL,
  `hired_year` year(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table team_planner.person: ~0 rows (approximately)
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
/*!40000 ALTER TABLE `person` ENABLE KEYS */;

-- Dumping structure for table team_planner.person_skill
CREATE TABLE IF NOT EXISTS `person_skill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `skill_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_id_skill_id` (`person_id`,`skill_id`),
  KEY `fk_person_skill_skill_skill_id` (`skill_id`),
  CONSTRAINT `fk_person_skill_person_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_person_skill_skill_skill_id` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table team_planner.person_skill: ~0 rows (approximately)
/*!40000 ALTER TABLE `person_skill` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_skill` ENABLE KEYS */;

-- Dumping structure for table team_planner.person_technology
CREATE TABLE IF NOT EXISTS `person_technology` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `technology_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_id_technology_id` (`person_id`,`technology_id`),
  KEY `fk_person_technology_technology_technology_id` (`technology_id`),
  CONSTRAINT `fk_person_technology_person_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_person_technology_technology_technology_id` FOREIGN KEY (`technology_id`) REFERENCES `technology` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table team_planner.person_technology: ~0 rows (approximately)
/*!40000 ALTER TABLE `person_technology` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_technology` ENABLE KEYS */;

-- Dumping structure for table team_planner.project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table team_planner.project: ~0 rows (approximately)
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;

-- Dumping structure for table team_planner.project_person
CREATE TABLE IF NOT EXISTS `project_person` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `person_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_person_id` (`project_id`,`person_id`),
  KEY `fk_project_person_person_person_id` (`person_id`),
  CONSTRAINT `fk_project_person_person_person_id` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_project_person_project_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table team_planner.project_person: ~0 rows (approximately)
/*!40000 ALTER TABLE `project_person` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_person` ENABLE KEYS */;

-- Dumping structure for table team_planner.project_skill
CREATE TABLE IF NOT EXISTS `project_skill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `skill_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_skill_id` (`project_id`,`skill_id`),
  KEY `fk_project_skill_skill_skill_id` (`skill_id`),
  CONSTRAINT `fk_project_skill_project_project_id` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_project_skill_skill_skill_id` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table team_planner.project_skill: ~0 rows (approximately)
/*!40000 ALTER TABLE `project_skill` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_skill` ENABLE KEYS */;

-- Dumping structure for table team_planner.skill
CREATE TABLE IF NOT EXISTS `skill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- Dumping data for table team_planner.skill: ~0 rows (approximately)
/*!40000 ALTER TABLE `skill` DISABLE KEYS */;
INSERT INTO `skill` (`id`, `name`) VALUES
	(4, 'Architectural design'),
	(1, 'Backend'),
	(12, 'Cryptography'),
	(7, 'Database design'),
	(6, 'Devops'),
	(14, 'Distrubuted system design'),
	(2, 'Frontend'),
	(3, 'Graphical design'),
	(13, 'High performance computing'),
	(9, 'Marketing'),
	(11, 'Network security'),
	(15, 'Payment gateways'),
	(10, 'Print design'),
	(5, 'Project management'),
	(8, 'Video production');
/*!40000 ALTER TABLE `skill` ENABLE KEYS */;

-- Dumping structure for table team_planner.technology
CREATE TABLE IF NOT EXISTS `technology` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table team_planner.technology: ~0 rows (approximately)
/*!40000 ALTER TABLE `technology` DISABLE KEYS */;
INSERT INTO `technology` (`id`, `name`) VALUES
	(13, 'BreezeJS'),
	(1, 'C#'),
	(8, 'CSS'),
	(11, 'd3.js'),
	(12, 'EntityFramework'),
	(7, 'HTML'),
	(4, 'Java'),
	(3, 'Javascript'),
	(10, 'Less'),
	(6, 'MSSQL'),
	(5, 'MySQL'),
	(2, 'PHP'),
	(15, 'RabbitMQ'),
	(14, 'Redis'),
	(9, 'Sass');
/*!40000 ALTER TABLE `technology` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
