--
-- Table structure for table `#__jtips_badwords`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_badwords` (
  `id` int(11) NOT NULL auto_increment,
  `badword` varchar(25) NOT NULL default '',
  `match_case` tinyint(1) default '0',
  `action` varchar(10) NOT NULL default 'delete',
  `replace` varchar(25) default NULL,
  `hits` int(11) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_comments`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_comments` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0' COMMENT 'This is the PK from the jtips_users table - NOT the actual user id',
  `round_id` int(11) default NULL,
  `comment` text,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_games`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_games` (
  `id` int(11) NOT NULL auto_increment,
  `round_id` int(11) default NULL,
  `home_id` int(11) default NULL,
  `away_id` int(11) default NULL,
  `position` int(11) default NULL,
  `winner_id` int(11) default NULL,
  `draw` int(11) default NULL,
  `home_score` int(11) default NULL,
  `away_score` int(11) default NULL,
  `bonus_id` int(11) default NULL,
  `has_bonus` tinyint(1) default '0',
  `has_margin` tinyint(1) default '0',
  `has_score` tinyint(1) default '0',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `home_start` float(5,2) default NULL,
  `away_start` float(5,2) default NULL,
  `start_time` datetime default NULL,
  `tough_score` int(11) default NULL,
  `description` text default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_history`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_history` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL COMMENT 'This is the PK from the jtips_users table - NOT the actual user id',
  `round_id` int(11) default NULL,
  `points` int(11) default '0',
  `precision` int(11) NOT NULL default '0',
  `rank` int(11) default NULL,
  `outof` int(11) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_remind`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_remind` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `round_id` int(11) default NULL,
  `notified` tinyint(1) default '0',
  `attempts` int(3) default '0',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_rounds`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_rounds` (
  `id` int(11) NOT NULL auto_increment,
  `round` int(11) default NULL,
  `season_id` int(11) default NULL,
  `start_time` datetime default NULL,
  `end_time` datetime default NULL,
  `scored` tinyint(1) default '0',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_seasons`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_seasons` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL,
  `description` text,
  `start_time` date default NULL,
  `end_time` date default NULL,
  `rounds` int(3) default NULL,
  `games_per_round` int(3) default NULL,
  `pick_score` tinyint(1) default '0',
  `pick_margin` tinyint(1) default '0',
  `pick_bonus` tinyint(1) default '0',
  `pick_draw` tinyint(1) default '0',
  `team_bonus` tinyint(4) default '0',
  `team_win` tinyint(4) default '0',
  `team_draw` tinyint(4) default '0',
  `team_lose` tinyint(4) default '0',
  `team_bye` tinyint(4) default '0',
  `user_correct` tinyint(4) default '0',
  `user_draw` tinyint(4) default '0',
  `user_none` tinyint(4) default '0',
  `user_bonus` tinyint(4) default '0',
  `user_pick_score` tinyint(4) default '0',
  `user_pick_margin` tinyint(4) default '0',
  `user_pick_bonus` tinyint(4) default '0',
  `url` varchar(200) default NULL,
  `image` varchar(200) default NULL,
  `precision_score` tinyint(1) default '0',
  `tip_display` tinyint(1) NOT NULL default '0',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `team_starts` tinyint(1) default '0',
  `default_points` varchar(20) default NULL,
  `tips_layout` varchar(10) default NULL,
  `game_times` tinyint(1) NOT NULL default '0',
  `disable_tips` tinyint(1) default '0',
  `scorer_id` int(11) default NULL,
  `tough_score` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_teams`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_teams` (
  `id` int(11) NOT NULL auto_increment,
  `season_id` int(11) default NULL,
  `name` varchar(255) default NULL,
  `location` varchar(255) default NULL,
  `about` text,
  `logo` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `wins` int(11) default NULL,
  `draws` int(11) default NULL,
  `losses` int(11) default NULL,
  `points_for` int(11) default NULL,
  `points_against` int(11) default NULL,
  `points` int(11) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_tips`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_tips` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL COMMENT 'Maps to the PK of the jtips_users table',
  `game_id` int(11) default NULL,
  `tip_id` int(10) default NULL,
  `home_score` int(11) default NULL,
  `away_score` int(11) default NULL,
  `margin` int(11) NOT NULL default '0',
  `bonus_id` int(11) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `#__jtips_users`
--

SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE IF NOT EXISTS `#__jtips_users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `season_id` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `doubleup` int(11) NOT NULL default '0',
  `paid` tinyint(1) NOT NULL default '0',
  `comment` text,
  `preferences` text,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;
