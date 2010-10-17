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
global $mosConfig_absolute_path, $mainframe, $database, $jTips;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jcomment.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jhistory.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/juser.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/Dashboard/tmpl/default.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/Dashboard/tmpl/default.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/Dashboard/tmpl/default.php');
}

$render = new jTipsRenderDashboard();

$season_id = getSeasonID();

//jTipsLogger::_log($_REQUEST, 'ERROR');
$my = $mainframe->getUser();

$jSeason = new jSeason($database);
if (is_numeric($season_id)) {
	$jSeason->load($season_id);
	$render->assign('jSeason', $jSeason);
	
	$jRound = new jRound($database);
	if (($round_id = $jSeason->getCurrentRound()) !== false) {
		$jRound->load($round_id);
	}
	$render->assign('jRound', $jRound);
	
	$render->display();
} else {
	echo "<span class='error'>Invalid Season/Competition Specified.</span>";
}
//jtips_HTML::jtips_home($jTipsUser, $jRound, $jSeason);
?>