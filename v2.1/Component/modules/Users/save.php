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
 * Description: 
 */

require_once('components/com_jtips/classes/juser.class.php');

global $database, $jLicence;

$season_id = jTipsGetParam($_REQUEST, 'season_id', false);
$result = array();
$success = $fail = 0;
if (is_numeric($season_id)) {
	foreach (jTipsGetParam($_REQUEST, 'cid', array()) as $uid) {
		$jTipsUser = new jTipsUser($database);
		$params = array(
			'user_id' => $uid,
			'season_id' => $season_id
		);
		$jTipsUser->loadByParams($params);
		if ($jTipsUser->exists() === false) {
			$jTipsUser->user_id = $uid;
			$jTipsUser->season_id = $season_id;
			$jTipsUser->status = 1;
			$saved = $jTipsUser->save();
			if ($saved !== false) {
				$success++;
				array_push($result, 1);
			} else {
				$fail++;
				array_push($result, 0);
			}
		} else {
			$fail++;
			array_push($result, 0);
		}
	}
	$message = "Added $success out of a possible " .($success + $fail). " users to season";
	// BUG 386 - validate with new number of users
	$jLicence->revalidate(true);
} else {
	$message = "No users added as no season was selected!";
}
mosRedirect('index2.php?option=com_jtips&task=list&module=Users', $message);
?>
