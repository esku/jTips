<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1.9 - 06/04/2009
 * @version 1.0.0
 * @package jTips
 *
 * Description:
 */

require_once('components/com_jtips/classes/jgame.class.php');
require_once('components/com_jtips/classes/jround.class.php');

global $database, $jLang;

$id = jTipsGetParam($_REQUEST, 'id', false);

if (!$id or !is_numeric($id)) { // BUG 373 - Round fails to copy
	jTipsLogger::_log('No round id specified to copy!', 'ERROR');
	mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $jLang['_ADMIN_ROUND_COPY_FAIL']);
}

$focus = new jRound($database);
$focus->load($id);
$focus->id = null;
$focus->round = jTipsGetParam($_REQUEST, 'round', '');
$focus->scored = 0;

if (!$focus->round) {
	jTipsLogger::_log('No Round number entered when copying round, determine next one.', 'ERROR');
	$query = "SELECT MAX(round)+1 AS nextround FROM #__jtips_rounds WHERE season_id = '" .$focus->season_id. "'";
	$database->setQuery($query);
	$focus->round = $database->loadResult();
}

$focus->save();

$query = "SELECT id FROM #__jtips_games WHERE round_id = '" .$database->getEscaped($id). "'";

$database->setQuery($query);
$game_ids = $database->loadResultArray();

if (!empty($game_ids)) {
	foreach ($game_ids as $gid) {
		$jGame = new jGame($database);
		$jGame->load($gid);
		$jGame->round_id = $focus->id;
		$jGame->id = null;
		$jGame->save();
	}
}

mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $jLang['_ADMIN_ROUND_COPY_SUCCESS']);
