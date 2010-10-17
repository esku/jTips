<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1 - 09/10/2008
 * @version 2.1
 * @package jTips
 *
 * Description:
 */
class ListMode {

	function display() {
		global $mainframe, $database, $jTips, $jLang, $jLicence, $mosConfig_absolute_path, $mosConfig_live_site, $database;
		jTipsLogger::_log('Loading dashboard', 'INFO');
		jTipsCommonHTML::loadOverlib();
		$option = jTipsGetParam($_REQUEST, 'option', 'com_jtips');
		if (isJoomla15()) {
			JToolbarHelper::title($jLang['_ADMIN_DASH_CPANEL'], 'frontpage');
		} else {
		?>
			<table class="adminheading" border="0" width="100%">
			<tr>
				<th class="cpanel" align="left"><?php echo $jLang['_ADMIN_DASH_CPANEL']; ?></th>
				<td align="right"><a href="http://www.jtips.com.au" target="_blank" title="jTips Home"><img src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/license_logo.png" alt="" border="0" /></a></td>
			</tr>
			</table>
		<?php
		}
		?>
		<table class="adminform">
		<tr>
		<td width="55%" valign="top">
		<?php
	jTipsLogger::_log('Building dashboard icons', 'INFO');
	foreach ($this->menu as $item) {
		if (isset($item['url']) and !empty($item['url'])) {
			$link = $item['url'];
			$extra = "target='_blank'";
		} else {
			$link = "index2.php?option=$option&amp;task=" .$item['task'];
			$extra = "";
		}
		?>
		<div id="cpanel" style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>" <?php echo $extra; ?>>
				<img src="components/com_jtips/images/<?php echo $item['image']; ?>"  alt="<?php echo $jLang[$item['alt']]; ?>" align="middle" border="0" /><span><?php echo $jLang[$item['link']]; ?></span></a>
			</div>
		</div>
		<?php
	}
	?>
</td>
<td width="45%" valign="top">
	<div style="width: 100%;">
	<?php
	$tabs = new jTipsTabs(1);
	$tabs->startPane('dashpanel');
	$tabs->startTab($jLang['_ADMIN_DASH_TAB_SUMMARY'], 'summary');
	$center = "style='text-align:center;'";
	?>
	&nbsp;
	<table class='adminlist'>
	<thead>
	<tr>
		<th><?php echo $jLang['_COM_DASH_SEASON']; ?></th>
		<th <?php echo $center; ?>><?php echo $jLang['_COM_DASH_CURR_ROUND']; ?></th>
		<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_TOTAL_USERS']; ?></th>
		<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_PENDING_TIPS']; ?></th>
		<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_PENDING_PAYMENT']; ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	jTipsLogger::_log('Loading season data', 'INFO');
	$total_user_count = 0;
	foreach ($this->jSeasons as $jSeason) {
		if (TimeDate::toDatabaseDate($jSeason->end_time) > gmdate('Y-m-d')) {
			$round_id = $jSeason->getCurrentRound();
			$jRound = new jRound($database);
			if ($round_id) {
				$jRound->load($round_id);
			}
			$jTipsUsers = $jSeason->getUsers();
			$total_users = 0;
			$no_tips = 0;
			$has_paid = 0;
			$no_tips_overlib = "";
			foreach ($jTipsUsers as $jTipsUser) {
				$total_users++;
				if (!$jTipsUser->hasTipped($jRound->id)) {
					$no_tips++;
					if ($no_tips < 10) {
						$no_tips_overlib .= "<br />" .htmlentities($jTipsUser->getName());
					} else if ($no_tips == 10) {
						$no_tips_overlib .= "<br />...more";
					}

				}
				if ($jTipsUser->paid == 1) {
					$has_paid++;
				}
			}
			if ($jTips['Payments'] != 0) {
				$pending_payment = $total_users - $has_paid;
			} else {
				$pending_payment = "N/A";
			}
			$no_tips_overlib = substr($no_tips_overlib, 6);
			if ($no_tips > 0) {
				$no_tips_overlib_call = jTipsToolTip($no_tips_overlib, "Users Missing Tips");
			} else {
				$no_tips_overlib_call = '';
			}
			?>
			<tr>
				<td><?php echo $jSeason->name; ?></td>
				<td <?php echo $center; ?>><?php echo $jRound->round; ?></td>
				<td <?php echo $center; ?>><?php echo $total_users; ?></td>
				<td <?php echo $center; ?>><?php echo $no_tips."&nbsp;".$no_tips_overlib_call; ?></td>
				<td <?php echo $center; ?>><?php echo $pending_payment; ?></td>
			</tr>
			<?php
			$total_user_count += $total_users;
		}
	}
	?>
	</tbody>
	</table>
	<?php
	$tabs->endTab();
	$tabs->startTab($jLang['_ADMIN_DASH_TAB_UPDATED'], 'update');
	$filesWritable = filesWritable();
	?>
	&nbsp;
	<table class="adminlist" width="100%">
		<thead>
		<tr>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_UPG_THISVERSION']; ?></th>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_UPG_LATESTVERSION']; ?></th>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_UPG_UPGRADE']; ?></th>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_UPG_FILE_CHECK']; ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td <?php echo $center; ?>><?php echo getFullVersion(); ?></td>
			<td <?php echo $center; ?> id="latest_version"><input type="button" onclick="liveCheckLatestVersion('latest_version');" class="button" value="Check Now" <?php if (!$filesWritable) echo "disabled"; ?> /></td>
			<td <?php echo $center; ?>><span id="liveupdatespan"><?php echo getUpdateButton('', ''); ?></span></td>
			<td <?php echo $center; ?>>
			<?php
			if ($filesWritable) {
				$tip = $jLang['_ADMIN_DASH_UPG_FILE_CHECK_OK'];
				$tipTitle = $jLang['_ADMIN_DASH_UPG_FILE_CHECK_OK_TITLE'];
				$img = 'checkin.png';
				echo jTipsToolTip($tip, $tipTitle, '', $img);
			} else {
				$tip = $jLang['_ADMIN_DASH_UPG_FILE_CHECK_FAIL'];
				$tipTitle = $jLang['_ADMIN_DASH_UPG_FILE_CHECK_FAIL_TITLE'];
				$img = 'warning.png';
				?>
				<a href=>
				<?php
				echo jTipsToolTip($tip, $tipTitle, '', $img, '', "index2.php?option=com_jtips&amp;module=Upgrade&amp;task=list");
				?>
				</a>
				<?php
			}
			?>
			</td>
		</tr>
		<?php
		if (needsUpgrade()) {
		?>
		<tr>
			<td colspan="4" id="upgrade_area" <?php echo $center; ?>>
				<input type="button" class="button" name="upgrade" value="<?php echo $jLang['_ADMIN_UPGRADE_BUTTON']; ?>" onclick="doUpgrade(this);" />
			</td>
		</tr>
		<?php
		}
		?>
		</tbody>
		<thead>
		<tr>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_LAST_VALIDATED']; ?></th>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_VALIDATION']; ?></th>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_DASH_EXPIRY_DATE']; ?></th>
			<th <?php echo $center; ?>><?php echo $jLang['_ADMIN_LICENSE_USER_COUNT']; ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td <?php echo $center; ?>><?php
			$validation_date = $jLicence->getValidationDate();
			if ($validation_date == -1) {
				echo $jLang['_ADMIN_DASH_LIC_EXPIRED'];
			} else {
				echo $validation_date;
			}
			?>
			</td>
			<td <?php echo $center; ?>><a href="index2.php?option=com_jtips&amp;task=Validate&amp;module=Dashboard"><?php echo $jLang['_ADMIN_DASH_REVALIDATE']; ?></a></td>
			<td <?php echo $center; ?>><?php echo TimeDate::toDisplayDate($jLicence->licence['license_expiry']); ?>&nbsp;</td>
			<td <?php echo $center; ?>><?php echo $jLicence->getActiveUserCount(); ?> / <?php echo $jLicence->getLicensedUsers(); ?></td>
		</tr>
		</tbody>
		<thead>
		<tr>
			<th <?php echo $center; ?> colspan="4"><?php echo $jLang['_ADMIN_DASH_LOGGING']; ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td nowrap><?php echo $jLang['_ADMIN_DASH_FILE_SIZE']; ?>: <?php echo size_readable(filesize($mosConfig_absolute_path. '/components/com_jtips/jtips.log')); ?></td>
			<td <?php echo $center; ?>><a href="index2.php?option=com_jtips&amp;task=DownloadLog&amp;module=Dashboard"><?php echo $jLang['_ADMIN_DASH_DOWNLOAD']; ?></a></td>
			<td <?php echo $center; ?>><a href="index2.php?option=com_jtips&amp;task=PurgeLog&amp;module=Dashboard"><?php echo $jLang['_ADMIN_DASH_PURGE']; ?></a></td>
			<td>&nbsp;</td>
		</tr>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="4"><small><em><?php echo $jLang['_ADMIN_DASH_LOG_ROTATED']; ?></em></small></td>
		</tr>
		</tfoot>
	</table>
	<?php
	$tabs->endTab();
	$tabs->startTab($jLang['_ADMIN_DASH_TAB_ABOUT'], 'about');
	?>
	<div style="text-align:center;"><img src="components/com_jtips/images/logo.png" border="0" />
	<h1><?php echo getFullVersion(); ?></h1></div>
	<p><?php echo $jLang['_ADMIN_DASH_ABOUT_UPDATES']; ?> <a href="http://www.jtips.com.au/" target="_blank">http://www.jtips.com.au</a></p>
	<p><?php echo $jLang['_ADMIN_DASH_ABOUT_SALES']; ?> <a href="mailto:sales@jtips.com.au?subject=jTips Enquiry">sales@jtips.com.au</a></p>
	<p><?php echo $jLang['_ADMIN_DASH_ABOUT_SUPPORT']; ?> <a href="http://www.jtips.com.au" target="_blank">jTips.com.au</a></p>
	<p><?php echo $jLang['_ADMIN_DASH_ABOUT_REBUILD']; ?> <a href="#" onclick="sendRebuildRequest('rebuildProgress');">&raquo; <?php echo $jLang['_COMMON_CLICK_HERE']; ?> &laquo;</a>&nbsp;&nbsp;&nbsp;<span id="rebuildProgress"></span></p>
	<?php
	$tabs->endTab();
	$tabs->startTab($jLang['_ADMIN_DASH_TAB_CREDITS'], 'credits');
	?>
	&nbsp;
	<p><strong><?php echo $jLang['_ADMIN_DASH_CREDITS']; ?>:</strong></p>
	<table class="adminlist" width="100%">
		<thead>
		<tr>
			<th><?php echo $jLang['_ADMIN_DASH_CREDITS_PACKAGE']; ?></th>
			<th><?php echo $jLang['_ADMIN_DASH_CREDITS_HOMEPAGE']; ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>mootools</td>
			<td><a href="http://mootools.net/" target="_blank">http://mootools.net</a></td>
		</tr>
		<tr>
			<td>tnimg</td>
			<td><a href="http://vagh.armdex.com/tnimg" target="_blank">http://vagh.armdex.com/tnimg</a></td>
		</tr>
		<tr>
			<td>cURL</td>
			<td><a href="http://www.phpclasses.org/browse/package/1988.html" target="_blank">http://www.phpclasses.org</a></td>
		</tr>
		<tr>
			<td>dUnzip2</td>
			<td><a href="http://www.phpclasses.org/browse/package/2495.html" target="_blank">http://www.phpclasses.org</a></td>
		</tr>
		<tr>
			<td>MOOdalBox</td>
			<td><a href="http://www.e-magine.ro/web-dev-and-design/36/moodalbox/" target="_blank">http://www.e-magine.ro</a></td>
		</tr>
		<tr>
			<td>Silk Icons</td>
			<td><a href="http://www.famfamfam.com/lab/icons/silk/" target="_blank">http://www.famfamfam.com</a></td>
		</tr>
		<tr>
			<td>datejs</td>
			<td><a href="http://www.datejs.com/" target="_blank">http://www.datejs.com</a></td>
		</tr>
		<tr>
			<td>strftime in javascript</td>
			<td><a href="http://tech.bluesmoon.info/2008/04/strftime-in-javascript.html" target="_blank">http://tech.bluesmoon.info/2008/04/strftime-in-javascript.html</a></td>
		</tr>
		<tr>
			<td>Editor Area</td>
			<td><a href="http://www.cdolivet.net/editarea/" target="_blank">http://www.cdolivet.net/editarea</a></td>
		</tr>
		<tr>
			<td>GNOME Icons</td>
			<td><a href="http://commons.wikimedia.org/wiki/GNOME_Desktop_icons" target="_blank">http://commons.wikimedia.org/wiki/GNOME_Desktop_icons</a></td>
		</tr>
		</tbody>
	</table>
	<?php
	$tabs->endTab();
	$tabs->startTab($jLang['_ADMIN_DASH_TAB_HELP'], 'help');
	?>
	&nbsp;
	<h2><?php echo $jLang['_ADMIN_DASH_HELP']; ?></h2>
	<p><?php echo $jLang['_ADMIN_DASH_HELP_INTRO']; ?> <a href="http://www.jtips.com.au" target="_blank">www.jtips.com.au</a></p>
	<ul>
		<li><?php echo $jLang['_ADMIN_DASH_HELP_GETTING_STARTED']; ?></li>
		<li><?php echo $jLang['_ADMIN_DASH_HELP_GUIDES']; ?></li>
		<li><?php echo $jLang['_ADMIN_DASH_HELP_TRICKS']; ?></li>
	</ul>
	<p>Do you have an invalid license key? Please contact jTips Support
		at <a href="mailto:support@jtips.com.au">support@jtips.com.au</a> and confirm your license key
		is valid for the domain <strong><?php echo $_SERVER['SERVER_NAME']; ?></strong>.</p>
	<?php
	$tabs->endTab();
	$tabs->endPane();
	?>
	</div>
	</td>
	</tr>
	</table>
	<?php
	}
}

?>
