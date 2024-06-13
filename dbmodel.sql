-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- undergrove implementation : © <Mathieu Chatrain> <mathieu.chatrain@gmail.com>
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.

CREATE TABLE IF NOT EXISTS `pending` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `player_id` int(10) NULL,  
  `function` varchar(50) NULL,
  `target` varchar(50) NULL,
  `arg` varchar(50) NULL,  
  `arg2` varchar(50) NULL,
  `arg3` varchar(50) NULL,
  `arg4` varchar(50) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;
CREATE TABLE IF NOT EXISTS `champignon` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(50) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(50) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  `carbone` int(2) unsigned DEFAULT 0,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `tiles` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(50) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(50) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `goal` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(50) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(50) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  `p1` int(2) unsigned DEFAULT 0,
  `p2` int(2) unsigned DEFAULT 0,
  `p3` int(2) unsigned DEFAULT 0,
  `p4` int(2) unsigned DEFAULT 0,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `foret` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NULL,  
  `location` varchar(50) NULL,
  `player_id` int(10) NULL,    
  `carbone` int(2) unsigned DEFAULT 0,
  `sens_racine` int(10) NULL,
  `vp_racine1` int(2) unsigned DEFAULT 0,
  `vp_racine2` int(2) unsigned DEFAULT 0,
  `vp_racine3` int(2) unsigned DEFAULT 0,
  `vp_racine4` int(2) unsigned DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `champispecial` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(2) NULL,  
  `nbre` int(2) unsigned DEFAULT 0,
  `score` int(2) unsigned DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `player` ADD `activation_b` int(2) unsigned DEFAULT 1;
ALTER TABLE `player` ADD `activation_p` int(2) unsigned DEFAULT 1;
ALTER TABLE `player` ADD `activation_g` int(2) unsigned DEFAULT 1;
ALTER TABLE `player` ADD `activation_y` int(2) unsigned DEFAULT 1;
ALTER TABLE `player` ADD `bonus_racine_reproduce` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonus_racine_partner` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonus_champi_reproduce` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonus_champi_partner` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonus_carbone` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `bonus_score` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `carbone` int(2) unsigned DEFAULT 4;
ALTER TABLE `player` ADD `azote` int(2) unsigned DEFAULT 2;
ALTER TABLE `player` ADD `phosphore` int(2) unsigned DEFAULT 2;
ALTER TABLE `player` ADD `potassium` int(2) unsigned DEFAULT 2;
ALTER TABLE `player` ADD `semi` int(2) unsigned DEFAULT 6;
ALTER TABLE `player` ADD `arbre` int(2) unsigned DEFAULT 4;
ALTER TABLE `player` ADD `racine` int(2) unsigned DEFAULT 18;
ALTER TABLE `player` ADD `track` int(2) unsigned DEFAULT 0;
ALTER TABLE `player` ADD `final` int(2) unsigned DEFAULT 0;
