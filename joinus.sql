-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 16, 2021 at 11:29 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joinus`
--

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
CREATE TABLE IF NOT EXISTS `food` (
  `foodid` int(11) NOT NULL AUTO_INCREMENT,
  `dishname` varchar(100) NOT NULL,
  `image` text NOT NULL,
  `price` double NOT NULL,
  `description` text NOT NULL,
  `chefid` int(11) NOT NULL,
  PRIMARY KEY (`foodid`),
  KEY `chef_food` (`chefid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joinu`
--

DROP TABLE IF EXISTS `joinu`;
CREATE TABLE IF NOT EXISTS `joinu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(90) NOT NULL,
  `RestName` varchar(30) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `UserName` varchar(40) NOT NULL,
  `Pass` varchar(45) NOT NULL,
  `Conpass` varchar(45) NOT NULL,
  `NatId` varchar(50) NOT NULL,
  `NatImage` blob,
  `Address` varchar(100) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `Category` varchar(20) NOT NULL,
  `Photo` text NOT NULL,
  `About` text NOT NULL,
  `Rating` double DEFAULT '0',
  `Reviews` int(11) DEFAULT '0',
  `Facebook` text NOT NULL,
  `Instagram` text NOT NULL,
  `Twitter` text NOT NULL,
  `Offer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `open_chats`
--

DROP TABLE IF EXISTS `open_chats`;
CREATE TABLE IF NOT EXISTS `open_chats` (
  `First_User_id` int(11) NOT NULL,
  `Second_User_id` int(11) NOT NULL,
  `FileName` text,
  KEY `user_chat` (`First_User_id`),
  KEY `chef_chat` (`Second_User_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `food_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `quantitiy` int(11) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`,`food_id`) USING BTREE,
  KEY `food_order` (`food_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `chef_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `user_orders` (`user_id`),
  KEY `chef_orders` (`chef_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

DROP TABLE IF EXISTS `register`;
CREATE TABLE IF NOT EXISTS `register` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `pass` varchar(10) NOT NULL,
  `compass` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `chef_food` FOREIGN KEY (`chefid`) REFERENCES `joinu` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `open_chats`
--
ALTER TABLE `open_chats`
  ADD CONSTRAINT `chef_chat` FOREIGN KEY (`Second_User_id`) REFERENCES `joinu` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `user_chat` FOREIGN KEY (`First_User_id`) REFERENCES `register` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `food_order` FOREIGN KEY (`food_id`) REFERENCES `food` (`foodid`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `chef_orders` FOREIGN KEY (`chef_id`) REFERENCES `joinu` (`ID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `order_orders` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `user_orders` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
