-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2017 at 05:37 PM
-- Server version: 5.5.38
-- PHP Version: 5.3.10-1ubuntu3.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ourblog`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idx_column_id` tinyint(3) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text,
  `user_id` tinyint(3) NOT NULL,
  `post_time` datetime DEFAULT NULL,
  `blog_url` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `idx_column_id`, `title`, `content`, `user_id`, `post_time`, `blog_url`) VALUES
(5, 9, '2', '2222222', 13, '2017-04-13 04:27:22', NULL),
(6, 10, '3', NULL, 13, '2017-04-13 04:30:31', 'http://www.baidu.com'),
(7, 9, 'ASdf', 'asdf', 13, '2017-04-13 04:42:34', NULL),
(8, 8, '4', '4444444', 13, '2017-04-13 04:48:12', NULL),
(11, 9, 'asdsdf', 'sdfsdfsdf', 13, '2017-04-14 10:19:09', NULL),
(12, 9, 'zxvxvzx', NULL, 13, '2017-04-14 12:24:33', 'http://www.baidu.com'),
(13, 8, 'Aaa', 'aaaaaaa', 13, '2017-04-14 02:07:01', NULL),
(14, 9, 'a', 'addddddd', 13, '2017-04-14 04:35:25', NULL),
(15, 10, 'Sdf', 'sdfdsf', 13, '2017-04-14 04:53:49', NULL),
(16, 10, 'Sdf', 'sdfdsf', 13, '2017-04-14 04:54:52', NULL),
(17, 9, 's', 'ssssss', 13, '2017-04-14 05:31:37', NULL),
(18, 10, 'sdfsdf', 'asdfasdf', 13, '2017-04-14 05:23:56', NULL),
(19, 9, '222222222', '222222222222', 13, '2017-04-14 05:32:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_tag`
--

CREATE TABLE IF NOT EXISTS `blog_tag` (
  `blog_id` int(10) NOT NULL,
  `tag_id` int(10) NOT NULL,
  `time` datetime DEFAULT NULL,
  KEY `blog_id` (`blog_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `index_column`
--

CREATE TABLE IF NOT EXISTS `index_column` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `index_column`
--

INSERT INTO `index_column` (`id`, `name`) VALUES
(8, 'Linux'),
(9, 'Apache'),
(10, 'PHP');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(21) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(40) NOT NULL,
  `reg_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;