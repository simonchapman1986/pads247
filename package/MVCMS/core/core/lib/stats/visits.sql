CREATE TABLE `visits` (
  `id` int(11) NOT NULL auto_increment,
  `ip_adr` varchar(15) NOT NULL default '',
  `referer` varchar(250) NOT NULL default '',
  `country` char(2) NOT NULL default '',
  `client` varchar(100) NOT NULL default '',
  `visit_date` date default NULL,
  `time` time NOT NULL default '00:00:00',
  `on_page` varchar(35) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;