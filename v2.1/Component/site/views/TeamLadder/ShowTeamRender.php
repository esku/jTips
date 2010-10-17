<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
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

class jTipsRenderShowTeam {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function display() {
		global $jTipsCurrentUser, $database, $mainframe, $mosConfig_absolute_path, $jLang, $jTips, $mosConfig_live_site;
		$width = $jTips['ShowTipsWidth'] - 40;
		?>
		<style type="text/css">
		@import url(<?php echo $mosConfig_live_site; ?>/templates/<?php echo jTipsGetTemplateName(); ?>/css/template.css);
		@import url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/jtips-popup.css);
		</style>
		<?php
		if ($jTips['ShowTipsPadding']) {
			?>
			<div style="float:left;padding-top:10px;padding-left:10px;padding-right:10px;padding-bottom:10px;width:<?php echo $width; ?>px;text-align:center;">
			<?php
		}
		if (!empty($this->jTeam->url)) {
			?>
			<div style="float:right;">
				<a href="<?php echo $this->jTeam->url; ?>" target="_blank"><img src="images/M_images/weblink.png" align="absmiddle" border="0" alt="<?php echo $jLang['_COM_TEAM_HOME_PAGE']; ?>" title="<?php echo $this->jTeam->url; ?>" /></a>
				&nbsp;<a href="<?php echo $this->jTeam->url; ?>" target="_blank"><?php echo preg_replace('/.*[^\/]:\/+/i', '', $this->jTeam->url); ?></a>
			</div>
			<?php
		}
		?>
		<h1 align="left"><?php echo $this->jTeam->getName(); ?></h1>
		<?php
		$img = '';
		if (!empty($this->jTeam->logo) and jTipsFileExists($mosConfig_absolute_path. '/' .getJtipsImage($this->jTeam->logo, 100))) {
		        $path = getJtipsImage($this->jTeam->logo, 100);
		        $name = $this->jTeam->getName();
		        $img = "<img src='{$mosConfig_live_site}/{$path}' align='left' border='1' style='margin-right:10px;margin-bottom:10px;' alt='{$name}' />";
		}
		$about = $img.jTipsStripslashes($this->jTeam->about);
		if (!empty($about)) {
		        ?>
		        <p style="padding-top:5px;" align="left">
		        <?php echo $about; ?>
		        </p>
		        <?php
		}
		?>
		<div style="clear:both;">&nbsp;</div>
		<h2 align="center" class="contentheading"><?php echo $jLang['_COM_TEAM_HISTORY']; ?></h2>
		<table width="100%" border="0" cellspacing="0">
		<thead>
		<tr class="sectiontableheader">
		<th><?php echo $jLang['_COM_DASH_ROUND']; ?></th>
		<th><?php echo $jLang['_ADMIN_ROUND_DATE']; ?></th>
		<th><?php echo $jLang['_COM_TEAM_VS']; ?></th>
		<th><?php echo $jLang['_ADMIN_TEAM_LOCATION']; ?></th>
		<th><?php echo $jLang['_COM_SCORE']; ?></th>
		<th><?php echo $jLang['_COM_TIPS_RESULT']; ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$rowIndex = 0;
		$history = $this->jTeam->getHistory();
		foreach ($history as $game) {
			$other = new jTeam($database);
			if ($this->jTeam->id == $game->home_id) {
				$other->load($game->away_id);
				$location = $jLang['_COM_TEAM_HOME'];
			} else {
				$other->load($game->home_id);
				$location = $jLang['_COM_TEAM_AWAY'];
			}
			$isBye = false;
			if (!$other->id) {
				$isBye = true;
			}
			if ($game->winner_id == $this->jTeam->id) {
				$result = 'tick';
				$alt = $jLang['_COM_TEAM_WIN'];
			} else if ($game->winner_id == '-1') {
				$result = 'draw';
				$alt = $jLang['_COM_TEAM_DRAW'];
			} else {
				$result = 'cross';
				$alt = $jLang['_COM_TEAM_LOSS'];
			}
			$date = TimeDate::toDisplayDate($game->start_time, true, false);
			$live_site = $mainframe->getCfg('live_site');
			if (strrpos($live_site, '/') == (strlen($live_site)-1)) {
				$live_site = substr($live_site, 0, -1);
			}
			$thisRowIndex = $rowIndex++;
			?>
			<tr class="sectiontableentry<?php echo ($thisRowIndex%2)+1; ?> jtablerow<?php echo ($thisRowIndex%2)+1; ?>">
			<td align="center"><?php echo $game->round; ?>.</td>
			<td align="left"><?php echo $date; ?></td>
			<td align="left"><?php echo  ($isBye ? '<em>'.$jLang['_COM_BYE'].'</em>' : $other->getDisplayLogoName()); ?></td>
			<td align="center"><?php echo ($isBye ? '&nbsp;' : $location); ?></td>
			<td align="center"><?php echo ($isBye ? '&nbsp;' : $game->home_score. ' - ' .$game->away_score); ?></td>
			<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/<?php echo $result; ?>.png" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>" border="0" /></td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		<?php
		if ($jTips['ShowTipsPadding']) {
			echo "</div>";
		}
	}
}
?>
