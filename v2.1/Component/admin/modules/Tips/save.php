<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once('components/com_jtips/classes/jtip.class.php');

global $database, $jTips;

jTipsSpoofCheck();

//jTipsDebug($_REQUEST);

$game_ids = jTipsGetParam($_REQUEST, 'game_id', array());
//jTipsDebug($game_ids);
foreach ($game_ids as $index => $gid) {
	$jTip = new jTip($database);
	if (!empty($_REQUEST['id'][$index])) {
		$jTip->load($_REQUEST['id'][$index]);
	}
	$game_index = "g$gid";
	$jTip->tip_id = jTipsGetParam($_REQUEST[$game_index], 'tip_id');
	$jTip->home_score = jTipsGetParam($_REQUEST[$game_index], 'home_score');
	$jTip->away_score = jTipsGetParam($_REQUEST[$game_index], 'away_score');
	$jTip->margin = jTipsGetParam($_REQUEST[$game_index], 'margin');
	$jTip->bonus_id = jTipsGetParam($_REQUEST[$game_index], 'bonus_id');
	$jTip->game_id = $gid;
	$jTip->user_id = jTipsGetParam($_REQUEST, 'user_id');
	if (!empty($jTip->user_id)) {
		jTipsLogger::_log("ADMIN: saving tips. Tip_id = " .$jTip->tip_id);
		$jTip->save();
	}
}
mosRedirect('index2.php?option=com_jtips&task=list&module=Tips', 'Tips Saved');
?>