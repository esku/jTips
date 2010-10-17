<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 * 
 * Description: Saves the User Preferences
 */

global $database, $Itemid;
$jTipsUsers = new jTipsUser($database);
$jTipsUserParams = array(
	'user_id' => jTipsGetParam($_REQUEST, 'id', 0)
);
$jTipsUserArr = forceArray($jTipsUsers->loadByParams($jTipsUserParams));
foreach ($jTipsUserArr as $jTipsUser) {
	$jTipsUser->setPreference('default_season', jTipsGetParam($_REQUEST, 'default_season', -1));
	$jTipsUser->setPreference('timezone', jTipsGetParam($_REQUEST, 'timezone', null));
	$jTipsUser->setPreference('email_reminder', jTipsGetParam($_REQUEST, 'email_reminder', '0'));
	$jTipsUser->setPreference('tips_notifications', jTipsGetParam($_REQUEST, 'tips_notifications', '0'));
}
$return_view = jTipsGetParam($_REQUEST, 'return', 'Dashboard');
if (!$return_view) $return_view = 'Dashboard';
jTipsRedirect('index.php?option=com_jtips&view=' .jTipsGetParam($_REQUEST, 'return', 'Dashboard'). '&Itemid=' .$Itemid. '&season=' .getSeasonID());
?>