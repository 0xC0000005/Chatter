CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `display_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('banned','member','admin','staff','superuser','') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'member',
  `title` varchar(48) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'newbie',
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_signature` text COLLATE utf8_unicode_ci,
  `state` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;


CREATE TABLE IF NOT EXISTS `forum` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `forum_category_id` int(8) DEFAULT NULL,
  `minimum_access` enum('member','admin','staff','banned') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forum_category_id` (`forum_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2613 ;


CREATE TABLE IF NOT EXISTS `forum_category` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `category` char(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



CREATE TABLE IF NOT EXISTS `thread` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(96) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL,
  `forum_id` int(8) NOT NULL,
  `view_count` int(11) NOT NULL DEFAULT '0',
  `date_updated` datetime NOT NULL,
  `sticky` tinyint(4) NOT NULL DEFAULT '0',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forum_id` (`forum_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `thread_id` int(8) NOT NULL,
  `content` text NOT NULL,
  `last_updated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `thread_id` (`thread_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;


CREATE TABLE IF NOT EXISTS `static_email_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('reset_password','food_order','','') COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `user_uuid` (
  `user_id` int(11) NOT NULL,
  `uuid` char(36) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('reset_password','','','') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'reset_password',
  PRIMARY KEY (`user_id`,`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`forum_category_id`) REFERENCES `forum_category` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;


ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_ibfk_4` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;


ALTER TABLE `thread`
  ADD CONSTRAINT `thread_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `thread_ibfk_5` FOREIGN KEY (`forum_id`) REFERENCES `forum` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;


ALTER TABLE `user_uuid`
  ADD CONSTRAINT `user_uuid_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);