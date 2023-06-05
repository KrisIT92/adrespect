-- Adminer 4.8.1 MySQL 10.4.28-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `exchange_rates`;
CREATE TABLE `exchange_rates` (
  `mid` int(11) NOT NULL,
  `rate` decimal(10,0) NOT NULL,
  `date` date NOT NULL,
  `currency` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `exchange_rates` (`mid`, `rate`, `date`, `currency`) VALUES
(0,	4,	'2023-06-05',	1);

-- 2023-06-05 10:23:00
