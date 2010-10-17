<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1.8 - 15/03/2009
 * @version 1.0
 * @package jTips
 *
 * Description: Writes to the JomSocial activity stream.
 * Has to be done manually since the JomSocial libraries
 * can only be called form the site, not from the admin
 * area.
 */

class jTipsJomSocial {
	/**
	 * Write the activity to the database
	 *
	 * @since 2.1.8
	 * @param object The activity object
	 */
	function write($act) {
		$act->cid	= md5('jtips');
		jTipsLogger::_log('writing action to jomsocial');
//		CFactory::load('libraries', 'activities');
//		CActivityStream::add($act);

		jimport('joomla.utilities.date');

                $db      = &JFactory::getDBO();
                $today =& JFactory::getDate();
/*
                $obj = new StdClass();
                $obj->actor     = $actor;
                $obj->target    = $target;
                $obj->title             = $title;
                $obj->content   = $content;
                $obj->app               = $appname;
                $obj->cid               = $cid;
                $obj->params    = $params;
                $obj->created   = $today->toMySQL();
                $obj->points    = $points;
*/
		$act->created	= $today->toMySQL();
		// parse the variables in the content string

		$db->insertObject('#__community_activities', $act);
		//jTipsLogger::_log('social fail: ' .$db->getErrorMsg(), 'ERROR');
	}

	/**
	 * Write the results of each user for the selected round to the JomSocial
	 * activity stream
	 *
	 * @param object The season
	 * @param object The round
	 * @param int The Joomla user id for the user to write details for
	 * @param int The score the the user
	 */
	function writeRoundResult($jSeason, $jRound, $user_id, $score) {
		global $jTips;
		$points = "point";
		if ($score != 1) {
			$points .= "s";
		}
		$variables = array('season' => $jSeason->name, 'round' => $jRound->round, 'score' => $score);
		$string = jTipsJomSocial::parseString($jTips['JomSocialRoundResultMessage'], $variables, $jSeason);//{actor} scored ' .$score. ' ' .$points. ' in round ' .$jRound->round. ' of the ' .$jSeason->name.jTipsJomSocial::_getCompetitionSuffix($jSeason);
		jTipsLogger::_log('building action to send to jomsocial');
		$act = new stdClass();
		$act->actor		= $user_id;
		$act->target	= 0;
		$act->title		= $string;
		$act->content	= '';
		$act->app		= 'jtips_wrr';
		$act->points		= $score;
		jTipsJomSocial::write($act);
	}

	/**
	 * Add details on who won the round being processed
	 *
	 * @param array A list of jTipsUser objects
	 * @param object The round object
	 * @param object The season object
	 */
	function writeRoundWinners($winners, $jRound, $jSeason) {
		global $jTips;
		if (!empty($winners)) {
			$suffix = "";
			if (count($winners) > 1) {
				$suffix = " and " .count($winners). " other";
				if (count($winners) > 2) $suffix .= "s";
			}
			$variables = array('season' => $jSeason->name, 'round' => $jRound->round);
			foreach ($winners as $jTipsUser) {
				$act		= new stdClass();
				$act->actor	= $jTipsUser->user_id;
				$act->target	= 0;
				$act->title	= jTipsJomSocial::parseString($jTips['JomSocialRoundWinnersMessage'], $variables, $jSeason);//"{actor} won round {$jRound->round} of the {$jSeason->name}" .jTipsJomSocial::_getCompetitionSuffix($jSeason);
				$act->app	= "jtips_wrw";
				jTipsJomSocial::write($act);
			}
		}
	}

	/**
	 * When a user is added to the competition, send the message to JomSocial
	 *
	 * @since 2.1.8
	 * @param int The Joomla id for the user
	 * @param object The season object
	 */
	function writeJoinMessage($user_id, $jSeason) {
		global $jTips;
		jTipsLogger::_log('building join message to send to jomsocial');
		$variables = array('season' => $jSeason->name);
		$act = new stdClass();
		$act->actor		= $user_id;
		$act->target	= 0;
		$act->title		= jTipsJomSocial::parseString($jTips['JomSocialJoinMessage'], $variables, $jSeason);//"{actor} joined the {$jSeason->name}" .jTipsJomSocial::_getCompetitionSuffix($jSeason);
		$act->content	= '';
		$act->app		= 'jtips_wjm';
		jTipsJomSocial::write($act);
	}

	/**
	 * When a user submits their tips, send it to the JomSocial activity stream
	 *
	 * @since 2.1.9
	 * @param int The Joomla id for the user
	 * @param bool True if updating tips
	 * @param object The season object
	 * @param object The round object
	 */
	function writeOnSaveTips($user_id, $isUpdate, $jSeason, $jRound) {
		global $jTips;
		jTipsLogger::_log('building onSaveTips message to send to JomSocial');
		if ($isUpdate) {
			$action = 'updated';
		} else {
			$action = 'submitted';
		}
		$variables = array('season' => $jSeason->name, 'round' => $jRound->round);
		$act = new stdClass();
		$act->actor		= $user_id;
		$act->target	= 0;
		$act->title		= jTipsJomSocial::parseString($jTips['JomSocialOnSaveTipsMessage'], $variables, $jSeason);//"{actor} $action their tips for {$jRound->round} of the {$jSeason->name}" .jTipsJomSocial::_getCompetitionSuffix($jSeason);
		$act->content	= '';
		$act->app		= 'jtips_wost';
		jTipsJomSocial::write($act);
	}

	/**
	 * Write a message when a user forgets to submit their tips
	 *
	 * @since 2.1.9
	 * @param int The Joomla id for the user
	 * @param object The season object
	 * @param object The round object
	 */
	function writeOnNoTips($user_id, $jSeason, $jRound) {
		global $jTips;
		jTipsLogger::_log('building onNoTips message to send to JomSocial');
		$variables = array('season' => $jSeason->name, 'round' => $jRound->round);
		$act = new stdClass();
		$act->actor		= $user_id;
		$act->target	= 0;
		// BUG 344 - typo in call to parseString function
		$act->title		= jTipsJomSocial::parseString($jTips['JomSocialOnNoTipsMessage'], $variables, $jSeason);//"{actor} forgot to submit their tips for {$jRound->round} of the {$jSeason->name}" .jTipsJomSocial::_getCompetitionSuffix($jSeason);
		$act->content	= '';
		$act->app		= 'jtips_wont';
		jTipsJomSocial::write($act);
	}

	/**
	 * Determine the gramatically correct suffix for messages based on the name
	 * of the competition.
	 *
	 * @since 2.1.9
	 * @deprecated 2.1.9 Users can customise the activity stream strings
	 * @param object The season object
	 */
	function _getCompetitionSuffix($jSeason) {
		if (preg_match('/\b(competition)|(comp)\b/i', $jSeason->name)) {
			return '';
		} else {
			return ' competition';
		}
	}

	/**
	 * Build a proper string to write to the Activity Stream
	 *
	 * @since 2.1.9
	 * @param string The string to find substitutes in
	 * @param array An associative array of keys and values to match and replace
	 * @return string The parse string
	 */
	function parseString($string, $variables, $jSeason=false) {
		if (!empty($variables)) {
			foreach ($variables as $search => $replace) {
				// TODO: if $string = season, link to competition dashboard
				if ($search == 'season' and $jSeason) {
					$replace = jTipsJomSocial::getDashboardLink($jSeason);
				}
				$string = str_replace('{' .$search. '}', $replace, $string);
			}
		}
		return $string;
	}

	/**
	 * Build a link to jTips using the selected area
	 * Only compatible with Joomla! 1.5
	 * This can stay here since JomSocial is only available for J1.5
	 *
	 * @since 2.1.10
	 * @param object The season object
	 * @return string
	 */
	function getDashboardLink($jSeason) {
		global $database, $jTips;

		static $menu_ids;

		// initialise the menu_ids variable
		if (!$menu_ids) {
			$menu_ids = array();
		}

		if (!isset($jTips['JomSocialCompetitionLinkTarget']) or empty($jTips['JomSocialCompetitionLinkTarget'])) {
			return $jSeason->name;
		}

		if (isset($menu_ids[$jSeason->id]) and !empty($menu_ids[$jSeason->id])) {
			$id = $menu_ids[$jSeason->id];
		} else {
			// find menu item for this competition... somehow
			$query = "SELECT id FROM #__menu WHERE link LIKE" .
					" '%com_jtips%" .$database->getEscaped($jTips['JomSocialCompetitionLinkTarget']). "%'" .
					" AND params LIKE '%season_id=" .$jSeason->id. "\n%' AND published = 1";
			$database->setQuery($query);
			$id = $database->loadResult();
			$menu_ids[$jSeason->id] = $id;
		}
		jTipsLogger::_log('jomsocial jTips menu item id: ' .$id, 'INFO');
		if ($id) {
			// build a link
			jTipsLogger::_log("JOMSOCIAL: found menu id = $id");
			// Bug 361 - index.php missing from URL string
			$url = "index.php?option=com_jtips&view=" .$jTips['JomSocialCompetitionLinkTarget']. "&Itemid=" .$id;
			$uri = jTipsGetSiteRoute($url);
			$link = "<a href='$uri' title='{$jTips['JomSocialCompetitionLinkTarget']}'>" .$jSeason->name. "</a>";
			return $link;
		} else {
			// something is wrong, default to season name
			jTipsLogger::_log('loading menu itemid: ' .$database->getErrorMsg(), 'ERROR');
			return $jSeason->name;
		}
	}
}
