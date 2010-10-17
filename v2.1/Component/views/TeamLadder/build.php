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

global $database, $mainframe, $mosConfig_absolute_path, $jTipsCurrentUser, $mosConfig_live_site;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/TeamLadder/tmpl/default.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/TeamLadder/tmpl/default.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/TeamLadder/tmpl/default.php');
}

if (isJoomla15()) {
	JHTML::_('behavior.modal');
}

$render = new jTipsRenderTeamLadder();

$season_id = getSeasonID();

$jSeason = new jSeason($database);
$jSeason->load($season_id);
$render->assign('jSeason', $jSeason);

$jTeams = $jSeason->getTeamLadder('points');
$render->assign('jTeams', $jTeams);

$jSeasonParams = array(
	'end_time' => array(
		'type' => 'query',
		'query' => "> '" .gmdate('Y-m-d'). "'"
	),
	'start_time' => array(
		'type' => 'query',
		'query' => "< '" .gmdate('Y-m-d'). "'"
	)
);
$jSeasons = $jSeason->loadByParams($jSeasonParams);
$render->assign('jSeasons', $jSeasons);

if (!jTipsGetParam($_REQUEST, 'menu', 1)) {
	echo "
	<style type='text/css'>
	@import url($mosConfig_live_site/templates/" .jTipsGetTemplateName(). "/css/template.css);
	@import url($mosConfig_live_site/components/com_jtips/css/jtips-popup.css);
	</style>";
	if ($jTips['ShowTipsPadding']) {
		$width = $jTips['ShowTipsWidth'] - 40;
		echo '<div style="padding-top:10px;padding-left:10px;padding-right:10px;padding-bottom:10px;width:' .$width. 'px;text-align:center;">';
	}
}

$render->display();

if (!jTipsGetParam($_REQUEST, 'menu', 1)) {
	if ($jTips['ShowTipsPadding']) {
		echo '</div>';
	}
	if (!isJoomla15()) {
		die();
	}
}

//jtips_HTML::show_teamLadder($jTipsUser, $jSeason);
?>