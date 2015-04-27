-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2015 at 09:20 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `epta_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_type` enum('ebay','amazon') NOT NULL DEFAULT 'ebay',
  `site_cat_id` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `price_id` int(11) NOT NULL,
  `dbCreatedOn` datetime DEFAULT NULL,
  `dbUpdatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL,
  `name` text COMMENT 'Tag Tag Tag Tag Tag',
  `price_id` int(11) NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_has_tag`
--

CREATE TABLE IF NOT EXISTS `group_has_tag` (
  `group_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_upc` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT 'ENUM - won''t set\nAuction, StoreInventory, FixedPrice, AuctionWithBIN',
  `image` varchar(255) DEFAULT NULL,
  `bestOffer` tinyint(1) DEFAULT '0',
  `buyItNow` tinyint(1) DEFAULT '0',
  `buyItNowPrice` decimal(15,2) DEFAULT '0.00',
  `currentPrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `startTime` datetime DEFAULT NULL,
  `endTime` datetime DEFAULT NULL,
  `condition` varchar(45) DEFAULT NULL,
  `sold` tinyint(1) DEFAULT NULL,
  `sellingState` varchar(50) DEFAULT NULL,
  `shippingServiceCost` decimal(10,2) NOT NULL,
  `shippingType` varchar(50) DEFAULT NULL,
  `shipToLocations` varchar(50) DEFAULT NULL,
  `topRatedListing` tinyint(1) NOT NULL DEFAULT '0',
  `site_item_id` varchar(30) DEFAULT NULL,
  `site_product_id` varchar(30) DEFAULT NULL,
  `site_type` enum('ebay','amazon') DEFAULT NULL,
  `site_url` varchar(255) DEFAULT NULL,
  `raw` text,
  `dbCreatedOn` datetime DEFAULT NULL,
  `dbUpdatedOn` datetime DEFAULT NULL,
  `dbGenerated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_has_tag`
--

CREATE TABLE IF NOT EXISTS `item_has_tag` (
  `item_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `price`
--

CREATE TABLE IF NOT EXISTS `price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avg` decimal(15,2) NOT NULL DEFAULT '0.00',
  `max` decimal(15,2) NOT NULL DEFAULT '0.00',
  `min` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_upc`
--

CREATE TABLE IF NOT EXISTS `product_upc` (
  `upc` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `alias` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `verified` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`upc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recent_search`
--

CREATE TABLE IF NOT EXISTS `recent_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('keyword','advanced') NOT NULL,
  `value` text NOT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `dbCreatedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) DEFAULT NULL,
  `price_id` int(11) NOT NULL,
  `tag_type_id` int(11) NOT NULL,
  `numItems` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`price_id`),
  UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tag_has_category`
--

CREATE TABLE IF NOT EXISTS `tag_has_category` (
  `tag_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tag_type`
--

CREATE TABLE IF NOT EXISTS `tag_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `weight` varchar(45) DEFAULT NULL,
  `delimiter` varchar(10) NOT NULL DEFAULT ' ',
  `remove_chars` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tag_type`
--

INSERT INTO `tag_type` (`id`, `name`, `weight`, `delimiter`, `remove_chars`) VALUES
(1, 'title', '3', ' ', '''['','']'','','',''.'','':'',''('','')'','';'',''"'',''--'''),
(2, 'subtitle', '2', ' ', '''['','']'','','',''.''');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
