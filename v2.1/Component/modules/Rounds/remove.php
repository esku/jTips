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
 * Description: Delete the selected rounds and their related games
 */
jTipsSpoofCheck();

global $mainframe, $database;

require_once('components/com_jtips/classes/jgame.class.php');
require_once('components/com_jtips/classes/jround.class.php');

$cids = jTipsGetParam($_REQUEST, 'cid', array());

$jRound = new jRound($database);
$success = 0;
foreach ($cids as $rid) {
	if ($jRound->destroy($rid)) {
		$success++;
	} else {
		jTipsLogger::_log("Failed to delete round with id: $rid - " .$jRound->_error);
	}
}
$message = "$success / " .count($cids). " " .$jLang['_ADMIN_ROUND_DELETED'];
mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $message);
?>