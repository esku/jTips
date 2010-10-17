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
global $database, $mainframe, $mosConfig_absolute_path, $jTipsCurrentUser;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/TeamLadder/ShowTeamRender.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/TeamLadder/ShowTeamRender.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/TeamLadder/ShowTeamRender.php');
}

if (!isJoomla15()) {
	//Trash anything we have so far
	ob_clean();
	ob_start();
}

$render = new jTipsRenderShowTeam();

$id = jTipsGetParam($_REQUEST, 'id', '');
$jTeam = new jTeam($database);
$jTeam->load($id);
$render->assign('jTeam', $jTeam);

$jSeason = new jSeason($database);
$jSeason->load($jTeam->season_id);
$render->assign('jSeason', $jSeason);

$render->display();

if (!isJoomla15()) {
	die();
}
/*
$my = $mainframe->getUser();
$jTipsUser = new jTipsUser($database);
$jTipsUserParams = array(
	'user_id' => $my->id,
	'season_id' => $jTeam->season_id
);
$jTipsUser = $jTipsUser->loadByParams($jTipsUserParams);
*/
//sjtips_HTML::show_team($jTeam, $jSeason, $jTipsUser);
?>