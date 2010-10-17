<?php
/**
* @version $Id: mod_jtips_favourite_team.php,v 1.2 2009/02/19 10:15:55 jtipsco1 Exp $
* @package jTips 2.1
* @copyright (C) 2008 jTips
* @license Commercial - Do not modify, or redistribute
* jTips 2.1 - your ultimate tipping system... www.jtips.com.au
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path, $mainframe, $database, $my, $jTips;
$season_name		= $params->get('season', '');


if (defined('_JEXEC')) {
	$mosConfig_absolute_path = JPATH_SITE;
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';
require_once($include_path. 'utils/compat.php');

$mosConfig_lang=$mainframe->getCfg( 'lang' );
include($include_path. 'i18n/english.php');
if (file_exists($include_path. 'i18n/'.$mosConfig_lang.'.php')) {
	include($include_path. 'i18n/'.$mosConfig_lang.'.php');
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

//Include required files
require_once($include_path. 'utils/timedate.php');
require_once($include_path. 'utils/functions.inc.php');
require_once($include_path. 'classes/jseason.class.php');
require_once($include_path. 'classes/jteam.class.php');
require_once($include_path. 'classes/juser.class.php');
include($include_path. 'config.jtips.php');

//Get the season
$jtips_params = array(
	'name' => trim($season_name)
);
$jSeason = new jSeason($database);
$jSeason->loadByParams($jtips_params);
if (isset($jSeason->id) and !empty($jSeason->id)) {
	//parse the info here
	if (!$my) $my =& $mainframe->getUser();
	$query = "SELECT tip_id AS team_id, COUNT(*) times_picked " .
			"FROM #__jtips_tips JOIN #__jtips_users ON " .
			"#__jtips_tips.user_id = #__jtips_users.id ";
	if ($params->get('user') and $my->id) {
		$jTipsUserParams = array(
			'season_id' => $jSeason->id,
			'user_id' => $my->id,
			'status' => 1
		);
		$jTipsFTModuleUser = new jTipsUser($database);
		$jTipsFTModuleUser->loadByParams($jTipsUserParams);
		if ($jTipsFTModuleUser->id) {
			$query .= "WHERE #__jtips_users.user_id = " .$database->Quote($my->id). " ";
		}
	}
	$query .= "GROUP BY #__jtips_tips.tip_id ORDER BY times_picked DESC";
	$database->setQuery($query, 0, 1);
	$team = $database->loadRow();
	if ($team) {
		$jTeam = new jTeam($database);
		$jTeam->load($team[0]);
		?><div style="text-align:center;"><?php
		$popupUrl = "view=TeamLadder&action=ShowTeam&menu=0&id=" .$jTeam->id. "&season_id=" .$jTeam->season_id;
		if (isJoomla15()) {
			$x = $jTips['ShowTipsWidth'];
			$y = $jTips['ShowTipsHeight'];
			//BUG 274 - team ladder popup results in broken scroll bar
			if (basename($_SERVER['SCRIPT_FILENAME']) != 'index2.php') {
				$class = "class='modal'";
			} else {
				$class = '';
			}
			$teamname = "<a $class rel=\"{handler: 'iframe', size: {x: $x, y: $y}}\" href='" .jTipsRoute($mosConfig_live_site. "/index2.php?option=com_jtips&$popupUrl"). "'>";
		} else {
			$teamname = "<a href='javascript:void(0);' onClick='openPopup(\"$popupUrl\", \"" .$jTeam->getName(). "\");'>";
		}
		echo $teamname;
		// Team logo
		if ($params->get('logo')) {
			echo $jTeam->getLogo(100);
			echo "<br />";
		}
		// Team name
		echo $jTeam->getName(); ?></a></div><?php
		
		// times picked
		?>
		<table width="100%" cellspacing="0">
			<tbody>
			<tr class="sectiontableentry1">
			<td class="sectiontableentry1"><strong>Times Picked</strong></td>
			<td class="sectiontableentry1"><?php echo $team[1]; ?></td>
			</tr>
		<?php
		// display times correctly picked
		$query = "SELECT count(*) FROM #__jtips_tips " .
				"JOIN #__jtips_users ON #__jtips_tips.user_id = #__jtips_users.id " .
				"JOIN #__jtips_games ON #__jtips_tips.game_id = #__jtips_games.id " .
				"WHERE jos_jtips_games.winner_id = jos_jtips_tips.tip_id " .
				"AND winner_id = " .$jTeam->id;
		if ($params->get('user') and $my->id) {
			$query .= " AND #__jtips_users.user_id = " .$database->Quote($my->id);
		}
		$database->setQuery($query);
		$correct = $database->loadResult();
		?>
			<tr class="sectiontableentry2">
			<td class="sectiontableentry2"><strong>Times Correctly Picked</strong></td>
			<td class="sectiontableentry2"><?php echo $correct; ?></td>
			</tr>
		<?php
		$query = "SELECT COUNT(*) FROM #__jtips_tips " .
				"JOIN #__jtips_games ON #__jtips_tips.game_id = #__jtips_games.id " .
				"JOIN #__jtips_users ON #__jtips_tips.user_id = #__jtips_users.id " .
				"WHERE (#__jtips_games.home_id = " .$database->Quote($jTeam->id). " " .
				"OR #__jtips_games.away_id = " .$database->Quote($jTeam->id). ")";
		if ($params->get('user') and $my->id) {
			$query .= " AND #__jtips_users.user_id = " .$database->Quote($my->id);
		}
		$database->setQuery($query);
		$totalGames = $database->loadResult();
		$average = ($team[1]/$totalGames)*100;
		$pickPercentage = round($average, 1);

		// overall accuracy
		$overall = round(($correct/$team[1])*100);
		?>
			<tr class="sectiontableentry1">
			<td class="sectiontableentry1"><strong>Time Incorrectly Picked</strong></td>
			<td class="sectiontableentry1"><?php echo ($totalGames - $team[1]); ?></td>
			</tr>
			<tr class="sectiontableentry2">
			<td class="sectiontableentry2"><strong>Pick Percentage</strong></td>
			<td class="sectiontableentry2"><?php echo $pickPercentage; ?>%</td>
			</tr>
			<tr class="sectiontableentry1">
			<td class="sectiontableentry1"><strong>Total Accuracy</strong></td>
			<td class="sectiontableentry1"><?php echo $overall; ?>%</td>
			</tr>
			</tbody>
		</table>
		<?php
	}
} else {
	//display error message
	?>
	<div class="message"><?php echo $jLang['_MOD_INVALID_SEASON_ERROR']; ?></div>
	<?php
}
?>