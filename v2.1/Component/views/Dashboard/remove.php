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

require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/juser.class.php');

$my =& $mainframe->getUser();

$season_id = getSeasonID();

if (!$season_id or empty($my->id)) {
	jTipsRedirect('index.php?option=com_jtips&Itemid=' .jTipsGetParam($_REQUEST, 'Itemid'));
}
$jTipsUser = new jTipsUser($database);
$parameters = array(
	'user_id' => $my->id,
	'season_id' => $season_id
);
$jTipsUser->loadByParams($parameters);
if ($jTipsUser->exists()) {
	jTipsLogger::_log('removing user ' .$jTipsUser->id. ' from competition ' .$jTipsUser->season_id);
	$jTipsUser->destroy();
}
$message = $jLang['_COM_UNSUBSCRIBE_SUCCESS'];
//do we need to stop any paypal subscriptions?
if ($jTips['Payments'] == 'paypal' and $jTips['PayPalIsSub'] and !empty($jTips['PayPalUnSub'])) {
	//Yes! redirect to paypal cancel page
	jTipsRedirect(stripslashes($jTips['PayPalUnSub']), $message);
} else {
	jTipsRedirect('index.php?option=com_jtips&Itemid=' .jTipsGetParam($_REQUEST, 'Itemid'). '&season=' .$season_id, $message);
}
?>