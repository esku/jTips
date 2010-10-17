<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 * 
 * Description: 
 * 
 * 
 */

global $database, $mainframe, $mosConfig_absolute_path, $jTipsCurrentUser;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jhistory.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/CompetitionLadder/tmpl/default.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/CompetitionLadder/tmpl/default.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/CompetitionLadder/tmpl/default.php');
}

$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/views/CompetitionLadder/CompetitionLadder.js'></script>");

$render = new jTipsRenderCompetitionLadder();

global $mainframe, $database, $jTips;
$my = $mainframe->getUser();
//$jTipsUser = new jTipsUser($database);
$page = jTipsGetParam($_REQUEST, 'page', 0);
$round_id = jTipsGetParam($_REQUEST, 'round_id', false);
$jSeason = new jSeason($database);
$season_id = getSeasonID();
$jSeason->load($season_id);
$render->assign('jSeason', $jSeason);
//die(getSeasonID());
$jRound = new jRound($database);
if ($round_id !== false) {
	$jRound->load($round_id);
} else {
	//Bug 171 & 172 - getCurrentRound is problematic here - gets currently active round!  
	//$round_id = $jSeason->getCurrentRound();
	$round_id = $jSeason->getLastRound();
	$jRound->load($round_id);
	if ($jRound->scored != 1) {
		$prev_id = $jRound->getPrev();
		if ($prev_id !== false) {
			$jRound->load($prev_id);
		}
	}
}
$render->assign('jRound', $jRound);

$jTipsCurrentUserParams = array(
	'season_id' => $jSeason->id,
	'status' => 1
);
$total = $jTipsCurrentUser->getCount($jTipsCurrentUserParams);

//Better pagination
$start = jTipsGetParam($_REQUEST, 'start', jTipsGetParam($_REQUEST, 'limitstart', 0));
$limit = jTipsGetParam($_REQUEST, 'limit', $jTips['NumMax']);

//BUG 238 - Busted page calculations filter down to select queries
$page = $start > 0 ? $start/$limit : 0;
$pageNav = new mosPageNav($total, $start, $limit);
$render->assign('pageNav', $pageNav);

if ($limit == 0) {
	$limit = false;
}

$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
$jHistory = new jHistory($database);
$jTipsUsers = $jHistory->getLadder($limit, $jRound->id, $page, jTipsGetParam($_REQUEST, 'filter_order', 'rank'), $direction, $jRound->id);
$render->assign('jTipsUsers', $jTipsUsers);

//build season dropdown
$jSeasonParams = array(
	'end_time' => array(
		'type' => 'query',
		'query' => "> '" .gmdate('Y-m-d H:i:s'). "'"
	)
);
$jSeasons = forceArray($jSeason->loadByParams($jSeasonParams));
$render->assign('jSeasons', $jSeasons);

$render->display();
//return jtips_HTML::jtips_ladder($jTipsUsers, $jSeason, $jRound, $pageNav);
?>