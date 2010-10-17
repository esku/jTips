<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 *
 * Description:
 *
 *
 */

class jTipsRenderDashboard {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function display() {
		global $mainframe, $database, $jTips, $jLang, $jPilot, $Itemid, $jTipsCurrentUser, $mosConfig_absolute_path;
		$my = $mainframe->getUser();

		?>
		<script type='text/javascript'>
		//var mySavingBlock;
		function getDashSeason(obj) {
			var id = obj.options[obj.selectedIndex].value;
			window.location.href="<?php echo html_entity_decode(jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&season=")); ?>" + id;
		}
		</script>
		<?php

		include($mosConfig_absolute_path. '/components/com_jtips/views/Dashboard/Dashlets/SeasonSummary.php');
		if (!$this->jSeason->disable_tips and $jTips['LastWin'] and isset($jTips['LastRoundSummary']) and count($jTips['LastRoundSummary']) > 0 and !empty($this->jRound->id)) {
			include($mosConfig_absolute_path. '/components/com_jtips/views/Dashboard/Dashlets/LastRoundSummary.php');
		}

		if (!$this->jSeason->disable_tips and $jTipsCurrentUser->inSeason($this->jSeason) and $jTipsCurrentUser->status == 1 and isset($jTips['ScoreSummary']) and count($jTips['ScoreSummary']) > 0) {
			include($mosConfig_absolute_path. '/components/com_jtips/views/Dashboard/Dashlets/ScoreSummary.php');
		}

		// BUG 339 - better subscription system
		if ($this->jSeason->id) { // we have a competition to join
			include($mosConfig_absolute_path. '/components/com_jtips/views/Dashboard/Dashlets/SubscriptionStatus.php');
		}

		// BUG 404 - post dash text
		if ($jTips['PostDashboardText']) {
			?>
			<div class='jtips_post_dashboard'><?php echo jTipsStripslashes($jTips['PostDashboardText']); ?></div>
			<?php
		}

		$leftCount = jTipsGetModuleCount('jTipsDash');
		if ($leftCount > 0) {
			$leftSplit = floor(100/$leftCount);
			echo jTipsRenderModules('jTipsDash', $leftSplit);
		}
	}
}
