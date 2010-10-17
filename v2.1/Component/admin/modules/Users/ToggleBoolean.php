<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 16/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

global $database;

ob_clean();

require_once('components/com_jtips/classes/juser.class.php');

$user_id = jTipsGetParam($_REQUEST, 'user_id', '');
$field = jTipsGetParam($_REQUEST, 'field', '');

$jTipsUser = new jTipsUser($database);
$jTipsUser->load($user_id);
$jTipsUser->$field = intval(!$jTipsUser->$field);
$jTipsUser->save();
if ($jTipsUser->$field) {
	if ($field == 'status') {
		//send notification email
		$jTipsUser->sendNotificationEmail('Approval');
	}
	die('tick');
} else {
	if ($field == 'status') {
		//send notification email
		$jTipsUser->sendNotificationEmail('Reject');
	}
	die('publish_x');
}
?>