<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 30/09/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

global $database;

jTipsSpoofCheck();

require_once('components/com_jtips/classes/jcomment.class.php');
require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/classes/jtip.class.php');
require_once('components/com_jtips/classes/juser.class.php');

$ids = jTipsGetParam($_REQUEST, 'cid', array());
$destroyed = array();
if (!empty($ids)) {
	foreach ($ids as $id) {
		if (is_numeric($id)) {
			$jSeason = new jSeason($database);
			$jSeason->load($id);
			jTipsLogger::_log('Deleting season ' .$jSeason->name);
			$params = array(
				'season_id' => $id
			);
			$jRound = new jRound($database);
			$jRounds = $jSeason->loadByParams($params);
			if (!is_array($jRounds)) {
				if ($jRound->exists()) {
					$jRounds = array($jRound);
				} else {
					$jRounds = array();
				}
			}
			jTipsLogger::_log('deleting rounds from season');
			foreach ($jRounds as $jRound) {
				array_push($destroyed, $jRound->destroy());
			}
			$jTipsUser = new jTipsUser($database);
			$jTipsUsers = $jTipsUser->loadByParams($params);
			if (!is_array($jTipsUsers)) {
				if ($jTipsUser->exists()) {
					$jTipsUsers = array($jTipsUser);
				} else {
					$jTipsUsers = array();
				}
			}
			jTipsLogger::_log('deleting users from season');
			foreach ($jTipsUsers as $jTipsUser) {
				array_push($destroyed, $jTipsUser->destroy());
			}
			
			array_push($destroyed, $jSeason->destroy());
			if (in_array(FALSE, $destroyed)) {
				$message = 'Season(s) not cleanly removed!';
			} else {
				$message = 'Season(s) deleted!';
			}
		}
	}
} else {
	$message = 'Failed to delete season';
}
mosRedirect('index2.php?option=com_jtips&task=list&module=Seasons', $message);
?>