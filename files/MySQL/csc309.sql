-- phpMyAdmin SQL Dump
-- version 3.3.8
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 14, 2011 at 12:46 AM
-- Server version: 5.1.52
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `csc309`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--
-- Creation: Jun 12, 2011 at 08:23 PM
--

CREATE TABLE IF NOT EXISTS `cars` (
  `car_id` int(11) NOT NULL AUTO_INCREMENT,
  `car_class` varchar(16) NOT NULL,
  `emission` int(11) NOT NULL,
  `colour` varchar(16) NOT NULL,
  `seats` int(11) NOT NULL,
  PRIMARY KEY (`car_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--
-- Creation: Jun 19, 2011 at 12:53 AM
--

CREATE TABLE IF NOT EXISTS `destinations` (
  `trip_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `route_order` int(11) NOT NULL,
  PRIMARY KEY (`trip_id`,`name`,`route_order`),
  KEY `destination` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `destinations`:
--   `trip_id`
--       `trips` -> `trip_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--
-- Creation: Jun 19, 2011 at 12:53 AM
--

CREATE TABLE IF NOT EXISTS `friends` (
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`fid`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `friends`:
--   `uid`
--       `users` -> `uid`
--   `fid`
--       `users` -> `uid`
--

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--
-- Creation: Jul 07, 2011 at 08:04 PM
--

CREATE TABLE IF NOT EXISTS `rate` (
  `rater_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `score` double NOT NULL,
  `trip_id` int(11) NOT NULL,
  PRIMARY KEY (`rater_id`,`target_id`,`trip_id`),
  KEY `target_id` (`target_id`),
  KEY `trip_id` (`trip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- RELATIONS FOR TABLE `rate`:
--   `rater_id`
--       `trip_users` -> `uid`
--   `target_id`
--       `trip_users` -> `uid`
--   `trip_id`
--       `trip_users` -> `trip_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--
-- Creation: Jun 19, 2011 at 12:53 AM
--

CREATE TABLE IF NOT EXISTS `trips` (
  `trip_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `start_date` date NOT NULL,
  `depart_time` time NOT NULL,
  `price` int(11) DEFAULT NULL,
  `car_id` int(11) NOT NULL,
  `home` varchar(128) NOT NULL,
  `description` text,
  `return_time` time DEFAULT NULL,
  `seats` int(11) NOT NULL,
  `end_date` date DEFAULT NULL,
  `frequency` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`trip_id`),
  KEY `car_id` (`car_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- RELATIONS FOR TABLE `trips`:
--   `car_id`
--       `cars` -> `car_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `trip_users`
--
-- Creation: Jun 19, 2011 at 12:53 AM
--

CREATE TABLE IF NOT EXISTS `trip_users` (
  `trip_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `trip_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `user_role` varchar(16) NOT NULL,
  PRIMARY KEY (`trip_user_id`),
  KEY `uid` (`uid`),
  KEY `trip_id` (`trip_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- RELATIONS FOR TABLE `trip_users`:
--   `trip_id`
--       `trips` -> `trip_id`
--   `uid`
--       `users` -> `uid`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jun 19, 2011 at 12:53 AM
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `fname` varchar(16) NOT NULL DEFAULT '',
  `lname` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL,
  `description` text,
  `pwd` varchar(255) NOT NULL,
  `telephone` varchar(16) DEFAULT '',
  `age` smallint(6) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `driver_rate_score` float NOT NULL DEFAULT '0',
  `driver_rater_num` int(11) NOT NULL DEFAULT '0',
  `passenger_rate_score` float NOT NULL DEFAULT '0',
  `passenger_rater_num` int(11) NOT NULL DEFAULT '0',
  `pic` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`),
  KEY `car_id` (`car_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1380 ;

--
-- RELATIONS FOR TABLE `users`:
--   `car_id`
--       `cars` -> `car_id`
--

--
-- Constraints for dumped tables
--

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `destinations_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`fid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_ibfk_3` FOREIGN KEY (`rater_id`) REFERENCES `trip_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rate_ibfk_4` FOREIGN KEY (`target_id`) REFERENCES `trip_users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rate_ibfk_5` FOREIGN KEY (`trip_id`) REFERENCES `trip_users` (`trip_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trip_users`
--
ALTER TABLE `trip_users`
  ADD CONSTRAINT `trip_users_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trip_users_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;
