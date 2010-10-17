<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1 - 08/10/2008
 * @version 2.1
 * @package jTips
 *
 * Description: Process the selected rounds tallying points for users and teams
 */

jTipsSpoofCheck();

global $database, $jTips, $jLang;

require_once('components/com_jtips/classes/jgame.class.php');
require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/classes/jtip.class.php');
require_once('components/com_jtips/classes/juser.class.php');

$cid = jTipsGetParam($_REQUEST, 'cid', array());

if ($cid === false or (is_array($cid) and count($cid) == 0)) {
	//should never get here
	mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $jLang['_ADMIN_ROUNDS_NONE_TO_PROCESS']);
}
$results = array();

//make sure we allow plenty of time for this...
set_time_limit(0);

///////////////////////////////////////////////////
// BUG 56 - Cannot process multiple rounds
// Resolution: Load the rounds first then order
// them by season id then by round (not round id)
//
jTipsLogger::_log("fixing round order before processing (bug 58)");
$rounds = $seasons = array();
foreach ($cid as $rid) {
	$jRound = new jRound($database);
	$jRound->load($rid);
	if (!isset($rounds[$jRound->season_id])) {
		$rounds[$jRound->season_id] = array();
		$seasons[] = $jRound->season_id;
	}
	$rounds[$jRound->season_id][$jRound->round] = $rid;
}
sort($seasons);
$rounds_to_process = array();
foreach ($seasons as $sid) {
	ksort($rounds[$sid]);
	$rounds_to_process = array_merge($rounds_to_process, $rounds[$sid]);
}
jTipsLogger::_log("round order now fixed (bug 58)");
//
//	END BUG 58 Fix
////////////////////////////////////////////////////

$skippedRounds = $processedRounds = 0;

foreach ($rounds_to_process as $id) {
	//clearHistory($id);
	$jRound = new jRound($database);
	$jRound->load($id);

	//make sure we only process rounds that are pending results
	if (!$jRound->getStatus()) {
		$skippedRounds++;
		jTipsLogger::_log('failed to process this round (' .$jRound->id. ')... it hasnt finished yet! STATUS: ' .$jRound->getStatus());
		continue;
	}

	if ($jRound->process()) {
		$processedRounds++;
	} else {
		$skippedRounds++;
	}
}

$message = $processedRounds. " / " .count($cid). " " .$jLang['_ADMIN_ROUND_PROCESSED'];
//jTipsDebug($message);
jTipsLogger::_log($message);
mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $message);
?>