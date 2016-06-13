-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 12 月 10 日 20:53
-- 服务器版本: 5.5.35
-- PHP 版本: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `vifnn`
--

-- --------------------------------------------------------

--
-- 表的结构 `vifnn_product_detail`
--

CREATE TABLE IF NOT EXISTS `vifnn_product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '??ID',
  `format` varchar(100) NOT NULL COMMENT '?????????',
  `color` varchar(100) NOT NULL COMMENT '??',
  `num` int(10) unsigned NOT NULL COMMENT '??',
  `price` float NOT NULL COMMENT '??',
  `vprice` float NOT NULL,
  `logo` varchar(200) NOT NULL COMMENT '??',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

