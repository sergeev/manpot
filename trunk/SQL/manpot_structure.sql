SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `list`
-- ----------------------------
DROP TABLE IF EXISTS `list`;
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
  `character` int(11) NOT NULL,
  `attachment` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`,`title`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `projects`
-- ----------------------------

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `mini` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL,
  `client_exec` varchar(255) NOT NULL default '0',
  `github` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `subname` (`mini`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `todo_list`
-- ----------------------------

DROP TABLE IF EXISTS `todo_list`;
CREATE TABLE `todo_list` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `todo_main`
-- ----------------------------

DROP TABLE IF EXISTS `todo_main`;
CREATE TABLE `todo_main` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `project` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

