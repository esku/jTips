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

global $database, $jTips, $mainframe;

$season_id = getSeasonID();

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/juser.class.php');

$my =& $mainframe->getUser();

//make sure we have a logged in user
if (!$my->id or !isset($my->id) or empty($my->id)) {
	jTipsRedirect('index.php?option=com_jtips&Itemid=' .jTipsGetParam($_REQUEST, 'Itemid', ''). '&season=' .$season_id, 'Please register first');
}

$jTipsUser = new jTipsUser($database);
$jTipsUser->user_id = $my->id; //jTipsGetParam($_REQUEST, 'user_id', $my->id);
$jTipsUser->status = $jTips['AutoReg'];
$jTipsUser->season_id = $season_id;
jTipsLogger::_log('current token: ' .$_SESSION['paypalToken'], 'INFO');
jTipsLogger::_log('returned token: ' .jTipsGetParam($_REQUEST, 'token', ''), 'INFO');
if ($jTips['Payments'] == 'paypal' and jTipsGetParam($_REQUEST, 'token', '') == $_SESSION['paypalToken']) {
	$jTipsUser->paid = 1;
}
$jTipsUser->save();

if ($jTips['UserNotifyEnable']) {
	$jTipsUser->sendNotificationEmail('');
}
if ($jTips['RegisterAllCompetitions']) {
	$query = "SELECT id FROM #__jtips_seasons WHERE end_time > NOW() AND id <> " .$database->quote($season_id);
	$database->setQuery($query);
	$seasons = $database->loadResultArray();
	if (!empty($seasons)) {
		foreach ($seasons as $other_season_id) {
			$jTipsUser->season_id = $other_season_id;
			$jTipsUser->id = null;
			$jTipsUser->save();
			if ($jTips['UserNotifyEnable']) {
				$jTipsUser->sendNotificationEmail('');
			}
		}
	}
}
$jTipsUserParams = array('user_id' => $my->id, 'season_id' => $season_id);
$jTipsUser->loadByParams($jTipsUserParams);
if ($jTips['AdminNotifyEnable']) {
	jTipsLogger::_log('preparing to send email notification to administration', 'INFO');
	$variables = array();
	$values = array();
	foreach (get_object_vars($jTipsUser) as $key => $val) {
		if (is_string($key)) {
			array_push($variables, $key);
			$values[$key] = $val;
		}
	}
	foreach (get_object_vars($my) as $key => $val) {
		if (is_string($key)) {
			array_push($variables, $key);
			$values[$key] = $val;
		}
	}
	// add the season name to the email variables
	$query = "SELECT name FROM #__jtips_seasons WHERE id = '" .$this->season_id. "'";
	$database->setQuery($query);
	$season = $database->loadResult();
	$values['competition'] = $season;
	$values['season'] = $season;
	/**
	 * BUG 76: the parseTemplate function was taking
	 * the AdminNotifySubject as the first parameter.
	 * Changed to be the actual message body
	 */
	$body = parseTemplate($jTips['AdminNotifyMessage'], $variables, $values);
	if (jTipsMail($jTips['AdminNotifyFromEmail'], $jTips['AdminNotifyFromName'], $jTips['AdminNotifyToEmail'], $jTips['AdminNotifySubject'], $body)) {
		jTipsLogger::_log('email sent to administration', 'INFO');
	} else {
		jTipsLogger::_log('failed to send email to administration', 'ERROR');
	}
}
if ($jTips['AutoReg']) {
	$message = $jLang['_COM_JOIN_COMPLETE'];
} else {
	$message = $jLang['_COM_JOIN_PENDING'];
}
jTipsRedirect('index.php?option=com_jtips&Itemid=' .jTipsGetParam($_REQUEST, 'Itemid', ''). '&season=' .$season_id, $message);
?>