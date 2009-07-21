/*
SQLyog Community Edition- MySQL GUI v5.29
Host - 5.0.45-community-nt : Database - test
*********************************************************************
Server version : 5.0.45-community-nt
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `list` */

CREATE TABLE `list` (
  `id` int(11) NOT NULL auto_increment,
  `project` int(11) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `report` longtext NOT NULL,
  `status` tinyint(1) NOT NULL default '0',
  `by` int(11) NOT NULL default '0',
  `priority` tinyint(1) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `started` int(11) NOT NULL default '0',
  `finished` int(11) NOT NULL default '0',
  `due` int(11) NOT NULL default '0',
  `assigned` int(11) NOT NULL default '0',
  `attachment` varchar(100) NOT NULL,
  KEY `id` (`id`,`title`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `projects` */

CREATE TABLE `projects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `mini` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL,
  `client_exec` varchar(255) NOT NULL,
  `github` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `subname` (`mini`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `todo_list` */

CREATE TABLE `todo_list` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` int(2) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `todo_main` */

CREATE TABLE `todo_main` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `project` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
