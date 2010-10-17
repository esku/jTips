<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 *
 * Description:
 *
 *
 */

global $database, $jTips, $jLang, $mosConfig_live_site, $mainframe;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jhistory.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jtip.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/CompetitionLadder/ShowTipsRender.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/CompetitionLadder/ShowTipsRender.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/CompetitionLadder/ShowTipsRender.php');
}

if (!isJoomla15()) {
	//Trash anything we have so far
	ob_clean();
	ob_start();
}

$render = new jTipsRenderShowTips();

$user_id = jTipsGetParam($_REQUEST, 'uid', '');
$jTipsUser = new jTipsUser($database);
$jTipsUser->load($user_id);
$render->assign('jTipsUser', $jTipsUser);

$round_id = jTipsGetParam($_REQUEST, 'rid', '');
$jSeason = new jSeason($database);
$jSeason->load($jTipsUser->season_id);
$render->assign('jSeason', $jSeason);

$jRound = new jRound($database);
if ($round_id == false) {
	$complete = 0;
	while ($complete !== 1) {
		$tempRound = new jRound($database);
		if ($round_id == false) {
			$round_id = $jSeason->getCurrentRound();
		}
		$tempRound->load($round_id);
		$round_id = $tempRound->getPrev();
		if (!is_numeric($round_id)) {
			break;
		}
		$jRound = new jRound($database);
		$jRound->load($round_id);
		$complete = $jRound->getStatus();
	}
}
$jRound->load($round_id);
$render->assign('jRound', $jRound);

$jGameParams = array(
	'round_id' => $jRound->id
);
$jGame = new jGame($database);
$jGames = forceArray($jGame->loadByParams($jGameParams));
$render->assign('jGames', $jGames);

//should columns be hidden?
$hideRaw = jTipsGetParam($_REQUEST, 'hide');
if ($hideRaw) {
	//BUG 231 - Add a fallback in case we are using PHP < 5.2.0
	if (!function_exists('json_decode')) {
		$hideColumns = array('result', 'actual', 'awarded');
	} else {
		// TODO: need to ensure json_decode returns an array here
		$hideColumns = json_decode(jTipsStripslashes(rawurldecode($hideRaw)));
	}
	// BUG 371 - use a fallback if something went wrong
	if (!$hideColumns) {
		$hideColumns = array('result', 'actual', 'awarded');
	}
	$render->assign('stats', false);
} else {
	$hideColumns = array();
	//should the extra stats be calculated
	$jHistory = new jHistory($database);
	if ($jTips['ShowTipsStats']) {
		//ok, here we go!
		$stats = array(
			'tips' => array(
				'round' => $jTipsUser->getTipsAccuracy($jRound->id),
				'overall' => $jTipsUser->getTipsAccuracy()
			),
			'score' => array(
				'round' => $jTipsUser->getRoundScore('precision', $jRound->id),
				'overall' => $jHistory->getProgressScore($jTipsUser, $jRound->id, 'precision')
			)
		);
		$render->assign('stats', $stats);
	} else {
		$render->assign('stats', false);
	}
}


$render->display($hideColumns);
if (!isJoomla15()) {
	die();
}
?>
