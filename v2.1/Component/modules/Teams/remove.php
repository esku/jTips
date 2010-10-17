<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 01/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Delete the selected teams from the list
 */

//check for hack attempt
jTipsSpoofCheck();

require_once('components/com_jtips/classes/jteam.class.php');

global $database;
$jTeam = new jTeam($database);
$results = array();
foreach (jTipsGetParam($_REQUEST, 'cid', array()) as $team_id) {
	if ($jTeam->destroy($team_id)) {
		array_push($results, 1);
	} else {
		array_push($results, 0);
	}
}
$message = 'Deleted ' .array_sum($results). ' out of a possible ' .count($results). ' teams';
mosRedirect('index2.php?option=com_jtips&task=list&module=Teams', $message);
?>