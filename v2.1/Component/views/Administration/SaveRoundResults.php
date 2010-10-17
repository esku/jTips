<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 13/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

jTipsSpoofCheck();

global $mosConfig_absolute_path, $database;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');

//Trash anything we have so far
ob_clean();
ob_start();

$cid = jTipsGetParam($_REQUEST, 'cid', array());

if (empty($cid)) {
	//should never get here
	jTipsLogger::_log('Trying to process a round from site, but no round selected', 'ERROR');
	die('<p class="error">Nothing to process</p>');
}

$round_id = false;
foreach ($cid as $i => $gid) {
	$jGame = new jGame($database);
	$jGame->load($gid);
	$jGame->home_score = jTipsGetParam($_REQUEST['home_score'], $i, 0);
	$jGame->away_score = jTipsGetParam($_REQUEST['away_score'], $i, 0);
	$jGame->bonus_id = jTipsGetParam($_REQUEST['bonus_id'], $i, null);
	$jGame->draw = null;
	if ($jGame->home_score > $jGame->away_score) {
		$jGame->winner_id = $jGame->home_id;
	} else if ($jGame->away_score > $jGame->home_score) {
		$jGame->winner_id = $jGame->away_id;
	} else {
		$jGame->winner_id = -1;
		$jGame->draw = 1;
	}
	if (!$round_id) {
		$round_id = $jGame->round_id;
	}
	$jGame->save();
}

if ($round_id) {
	//now to process the round
	$jRound = new jRound($database);
	$jRound->load($round_id);
	$jRound->process();
	$message = '<p class="message">' .$jLang['_ADMIN_ROUND_STATUS_C']. '</p>';
} else {
	$message = '<p class="error">' .$jLang['_COM_FAILED']. ': ' .$jRound->getErrorMsg(). '</p>';
}
die($message);
?>