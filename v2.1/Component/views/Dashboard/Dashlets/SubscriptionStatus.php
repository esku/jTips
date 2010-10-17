<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Not A Valid Entry Point');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1.9 - 17/04/2009
 * @version 1.0
 * @package jTips
 *
 * Description: Displays messsage area to allow subscribe
 * and unsubscribe to competitions.
 */
// BUG 339 - better subscription system
?>
<div class="highlight jtips_subscription_status" align="center"><?php
// allow competition subscription/unsubscription
if (!$my->id) { // user not logged in
	if (isJoomla15()) {
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		$allowRegistrations = $usersConfig->get( 'allowUserRegistration' );
	} else {
		$allowRegistrations = $mainframe->getCfg( 'allowUserRegistration' );
	}
	if ($allowRegistrations) { // user registrations enabled/allowed in joomla configuration
		?><a
		href="<?php echo jTipsRoute("index.php?option=com_user&view=register"); ?>"
		title="<?php echo $jLang['_COM_REGISTER_TITLE']; ?>"><?php echo $jLang['_COM_DASH_REG_REQ']; ?></a><?php
	} else {
		echo $jLang['_COM_DASH_REG_DENY'];
	}
} else if ($my->id and !$jTipsCurrentUser->id and !$jTips['AllowReg']) { // user logged in, but registrations disabled
	echo stripslashes($jTips['NoRegMessage']);
} else if ($my->id and !$jTipsCurrentUser->id and  $jTips['AllowReg']) { // user logged in, registrations enabled
	if ($jTips['Payments'] == 'paypal') {
		?>
		<h3 align="center"><?php echo $jLang['_COM_DASH_JOIN_COMP']; ?></h3>
		<?php
		echo parsePayPalCode($jTips['PayPal'], 'join', $this->jSeason->id);
	} else {
		?><a
		href="<?php echo jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Dashboard&action=add"); ?>"
		title="<?php echo $jLang['_COM_JOIN_NOW']; ?>"><?php echo $jLang['_COM_DASH_JOIN_COMP']; ?></a><?php
	}
} else if ($jTipsCurrentUser->id and !$jTipsCurrentUser->status) { // logged in, pending approval
	echo $jLang['_COM_DASH_JOIN_PEND'];
} else if ($jTipsCurrentUser->id and  $jTipsCurrentUser->status) { // logged in, approved! Allow unsubscribe
	// BUG 405 - link to tips page
	?>
	<a href="<?php echo jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Tips"); ?>"><?php echo $jLang['_COM_DASH_GOTO_TIPS']; ?></a><br />
	<?php
	$confirm_unsub = 'return confirm("' . $jLang['_COM_UNSUBLINK_PART1'] . ' ' . $this->jSeason->name . ' ' . $jLang['_COM_UNSUBLINK_PART2'] . '");';
	?><a
		href="<?php echo jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Dashboard&action=remove"); ?>"
		title="<?php echo $jLang['_COM_UNSUBSCRIBE']; ?>" onClick='<?php echo $confirm_unsub; ?>'><?php echo $jLang['_COM_UNSUBSCRIBE']; ?></a><?php
} else { // do nothing
}
?></div>
