<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 *
 * Description:
 *
 *
 */

class jTipsRenderUserPreferences {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function display() {
		ob_get_contents();
		global $database, $mainframe, $jTips, $jLang, $jTipsCurrentUser, $Itemid, $mosConfig_live_site;
		$send_email = $jTipsCurrentUser->getPreference('email_reminder');
		if (empty($send_email)) {
			$send_email = '0';
		}
		$tips_notifications = $jTipsCurrentUser->getPreference('tips_notifications');
		if (empty($tips_notifications)) {
			$tips_notifications = '0';
		}
		$postURL = jTipsRoute("index.php?option=com_jtips&Itemid=" .$Itemid);
		$width = $jTips['ShowTipsWidth'] - 40;
		?>
		<style type="text/css">
		@import url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/jtips-default.css);
		@import url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/jtips-popup.css);
		</style>
		<div style="padding-top:10px;padding-left:10px;padding-right:10px;padding-bottom:10px;width:<?php echo $width; ?>px;text-align:center;">
		<form action="<?php echo $postURL; ?>" name="adminForm" method="post" id="adminForm">
		<input type="hidden" name="option" value="<?php echo jTipsGetParam($_REQUEST, 'option', 'com_jtips'); ?>" />
		<input type="hidden" name="view" value="UserPreferences" />
		<input type="hidden" name="action" value="save" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="return" value="<?php echo jTipsGetParam($_REQUEST, 'return', ''); ?>" />
		<input type="hidden" name="season" value="<?php echo getSeasonID(); ?>" />
		<input type="hidden" name="user" value="<?php echo $jTipsCurrentUser->id; ?>" />
		<input type="hidden" name="id" value="<?php echo $jTipsCurrentUser->user_id; ?>" />
		<h1 class="contentheading"><?php echo $jLang['_COM_USER_PREFERENCES']; ?></h1>
		<table width="100%" border="0" cellspacing="5" cellpadding="0" style="margin-top:25px;">
			<tbody>
			<tr>
				<th align="right"><?php echo $jLang['_COM_TIME_ZONE']; ?></th>
				<td><?php echo makeSelectList($this->timezones, 'timezone', "class='inputbox'", $this->timezone); ?></td>
			</tr>
			<?php
			if (isset($jTips['EnableEmailReminders']) and $jTips['EnableEmailReminders'] == 1) {
			?>
			<tr>
				<th align="right"><?php echo $jLang['_COM_SEND_REMINDER_EMAIL']; ?></th>
				<td><?php echo jTipsHTML::yesnoRadioList('email_reminder', '', $send_email); ?></td>
			</tr>
			<?php
			}
			?>
			<?php
			if (isset($jTips['TipsNotifyEnable']) and $jTips['TipsNotifyEnable'] == 1) {
			?>
			<tr>
				<th align="right"><?php echo $jLang['_ADMIN_CONF_NOTIFY_TIPS']; ?></th>
				<td><?php echo jTipsHTML::yesnoRadioList('tips_notifications', '', $tips_notifications); ?></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			<tfoot>
			<tr>
				<td style="text-align:center;" colspan="2"><input type="submit" name="submit_preferences" value="  <?php echo $jLang['_COM_SAVE']; ?>  " class="button" onClick="window.top.setTimeout('window.parent.document.getElementById(\'sbox-window\').close()', 700);" /></td>
			</tr>
			</tfoot>
		</table>
		</form>
		</div>
		<?php
	}
}
?>