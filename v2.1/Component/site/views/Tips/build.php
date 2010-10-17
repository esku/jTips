<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 * 
 * Description: Gets the necessary data for the Tips Panel and
 * passes it to the render
 */

global $mosConfig_absolute_path, $mainframe, $database, $jTips, $jLang, $jTipsCurrentUser, $Itemid;

require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jcomment.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jseason.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jtip.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/juser.class.php');

//Extend the render class if it exists
if (jTipsFileExists($mosConfig_absolute_path.'/components/com_jtips/custom/views/Tips/tmpl/default.php')) {
	require_once($mosConfig_absolute_path.'/components/com_jtips/custom/views/Tips/tmpl/default.php');
} else {
	require_once($mosConfig_absolute_path.'/components/com_jtips/views/Tips/tmpl/default.php');
}

$mainframe->addCustomHeadTag("<script src='" .$mosConfig_live_site. "/components/com_jtips/js/countdown.js' type='text/javascript'></script>");
$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/views/Tips/Tips.js'></script>");

//Instantiate the render object
$render = new jTipsRenderTips();

$jSeason	= new jSeason($database);
$season_id	= getSeasonID();
$jSeason->load($season_id);
	

if ((!isset($jTipsCurrentUser->id) or empty($jTipsCurrentUser->id)) and !$jSeason->disable_tips) {
	$render->displayLocked();
} else {
	if ($jSeason->disable_tips or jTipsGetParam($_REQUEST, 'layout', false)) {
		//jTipsRedirect('index.php?option=com_jtips&Itemid=' .$Itemid. '&view=Dashboard&season=' .getSeasonID());
		// rebuild the render
		require_once(dirname(__FILE__).'/tmpl/locked.php');
		$render = new jTipsRenderLockedTips();
	}
	
	$render->assign('jSeason', $jSeason);
	
	//Load the current user.
	//This should be done in the calling script and the jTipsUser object made a global
	/*$jTipsUser	= new jTipsUser($database);
	$parameters = array(
		'season_id' => $jSeason->id,
		'user_id' => $id
	);
	$jTipsUser->loadByParams($parameters);*/
	$parameters = array();
	
	$jSeasons = forceArray($jSeason->loadByParams($parameters));
	for ($i=0; $i<count($jSeasons); $i++) {
		if ($jSeasons[$i]->end_time < gmdate('Y-m-d')) {
			unset($jSeasons[$i]);
		}
	}
	
	//Load the current round info
	$jRound		= new jRound($database);
	$rid = jTipsGetParam($_REQUEST, 'rid', false);
	if (!$rid) {
		$rid = $jSeason->getCurrentRound();
	}
	$jRound->load($rid);
	$render->assign('jRound', $jRound);
	
	//Load the comment for this round
	$jComment	= new jComment($database);
	$parameters = array(
		'user_id' => $jTipsCurrentUser->id,
		'round_id' => $jRound->id
	);
	$jComment->loadByParams($parameters);
	$render->assign('jComment', $jComment);
	
	$render->display();
}
//jtips_HTML::jtips_addtips($jTipsUser, $jRound, $jSeason, $jSeasons, $jComment);
?>
