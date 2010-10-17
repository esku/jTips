<?php
defined('_JEXEC') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 1.0 - 13/07/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: Displays the winning user from a set number of rounds 
 */

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jtips'.DS.'utils'.DS.'functions.inc.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jtips'.DS.'classes'.DS.'jseason.class.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_jtips'.DS.'classes'.DS.'juser.class.php');

class modJTipsMiniCompHelper {
	function &getUser($season_id, $rounds, $show_detail) {
		// load the season
		$db =& JFactory::getDBO();

		$season = new jSeason($db);
		$season->load($season_id);

		$current_round_id = $season->getCurrentRound();
//		echo "<br>CURRENT ROUND ID: " .$current_round_id;
		// get the round number for the current round
		$query = "SELECT round FROM #__jtips_rounds WHERE id = " .$db->Quote($current_round_id);
		$db->setQuery($query);
		$current_round_number = $db->loadResult();
//		echo "<br>CURRENT ROUND NUM: " .$current_round_number;

		// is the current round a multiple of $rounds?
		$mod = $current_round_number % $rounds;
//		echo "<br>MOD = " .$mod;

		// upper limit 
		$upper = $current_round_number - $mod;
		if ($upper < 0) $upper = 0;
//		echo "<br>UPPER = " .$upper;

		// lower limit
		$lower = $upper - $rounds + 1;
		if ($lower < 0) $lower = 0;
//		echo "<br>LOWER = " .$lower;

		// get the round numbers for all the rounds between
		$round_numbers = array();
		for ($i=$lower; $i<=$upper; $i++) {
			$round_numbers[] = $i;
		}

		// get the ids for these rounds
		$query = "SELECT id FROM #__jtips_rounds WHERE round IN (" .implode(", ", $round_numbers). ") AND season_id = " .$db->Quote($season_id);
		$db->setQuery($query);
		$ids = $db->loadResultArray();

		// get the scores for the rounds between the lower and upper limits
		$query = " SELECT user_id, SUM(points) AS total FROM #__jtips_history " .
			 " WHERE round_id IN (" .implode(", ", $ids). ") " .
			 " GROUP BY user_id ORDER BY total DESC";
//		echo $query;
		$db->setQuery($query, 0, 1);
		$win = $db->loadObject();

		$jTipsUser = new jTipsUser($db);
		$jTipsUser->load($win->user_id);

		$data = array(
			'who' => $jTipsUser,
			'points' => $win->total
		);
		
		if ($show_detail) {
			$query = "SELECT #__jtips_rounds.round, #__jtips_history.points, #__jtips_history.rank FROM #__jtips_history " .
				" JOIN #__jtips_rounds ON #__jtips_history.round_id = #__jtips_rounds.id " .
				" WHERE user_id = {$win->user_id} AND #__jtips_history.round_id IN (" .implode(", ", $ids). ") " .
				" ORDER BY #__jtips_rounds.round ASC";
			$db->setQuery($query);
			$data['results'] = $db->loadAssocList();
		} else {
			$data['results'] = array();
		}

		return $data;
	}

	function getAvatar($user, $params) {
		if ($params->get('link', '') and $params->get('avatar', 0)) {
			if ($params->get('link', '') == 'JomSocial') {
				return getJomSocialAvatar($user->user_id);
			} else if ($params->get('link', '') == 'CommunityBuilder') {
				return getCommunityBuilderAvatar($user->user_id);
			}
		}
		return false; // fallback
	}
}
