<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
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

global $mosConfig_absolute_path, $database, $mainframe, $jTips, $jLang, $mosConfig_live_site, $jTipsCurentUser, $Itemid;

//Extend the render class if it exists
if (jTipsFileExists($mosConfig_absolute_path.'/components/com_jtips/custom/views/Menu/tmpl/default.ext.php')) {
	require_once($mosConfig_absolute_path.'/components/com_jtips/custom/views/Menu/tmpl/default.ext.php');
} else {
	require_once($mosConfig_absolute_path.'/components/com_jtips/views/Menu/tmpl/default.php');
}

$render = new jTipsRenderMenu();
jTipsLogger::_log('MENU TASK: ' .$task);
//$Itemid = jTipsGetParam($_REQUEST, 'Itemid', $Itemid); //Itemid is a global!

if (!isJoomla15()) {
	$dateFormat = '%Y-%m-%d';
} else {
	$dateFormat = $jTips['DateFormat'];
}
$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsLiveSite = '$mosConfig_live_site';var Offset = " .TimeDate::getOffset(false). ";var DateFormat = '" .$dateFormat. "';</script>");

$jSeason = new jSeason($database);
$season_id = getSeasonID();
$jSeason->load($season_id);
$season_link = '';//"&season=" .$jSeason->id;
$render->assign('jSeason', $jSeason);
$render->assign('season_link', $season_link);

$render->assign('adminUrl', "view=Administration&Itemid=$Itemid&season=" .getSeasonID());
/*
 * TODO: BUG: XXX - the admin scripts were not loaded if the scorer was not part of a competition
 */
$my =& $mainframe->getUser();
if ($jSeason->scorer_id == $my->id) {
	//$mainframe->addCustomHeadTag('<script type="text/javascript" src="' .$mosConfig_live_site. '/components/com_jtips/views/Administration/Administration.js"></script>');
	//$mainframe->addCustomHeadTag('<script type="text/javascript" src="' .$mosConfig_live_site. '/administrator/components/com_jtips/lib/date.js"></script>');
	jTipsCommonHTML::loadCalendar();
}

$render->displayPreferencesLink();

if (!isset($jTips['Menu']) or empty($jTips['Menu'])) {
	$jTips['Menu'] = array();
}

if (array_key_exists($view, $jTips['Menu'])) {
	$classes = array(
		'Dashboard' => array(
			'class' => "sectiontableentry2 jmain_menu",
			'cursor' => "cursor:pointer;cursor:hand;",
			'onevent' => "onClick='window.location.href=\"" .jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Dashboard$season_link"). "\"'; onmouseover='this.className=\"sectiontableentry1 jmain_menu_hover\"' onmouseout='this.className=\"sectiontableentry2 jmain_menu\"'"
		),
		'CompetitionLadder' => array(
			'class' => "sectiontableentry2 jmain_menu",
			'cursor' => "cursor:pointer;cursor:hand;",
			'onevent' => "onClick='window.location.href=\"" .jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=CompetitionLadder$season_link"). "\"' onmouseover='this.className=\"sectiontableentry1 jmain_menu_hover\"' onmouseout='this.className=\"sectiontableentry2 jmain_menu\"'"
		),
		'TeamLadder' => array(
			'class' => "sectiontableentry2 jmain_menu",
			'cursor' => "cursor:pointer;cursor:hand;",
			'onevent' => "onClick='window.location.href=\"" .jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=TeamLadder$season_link"). "\"' onmouseover='this.className=\"sectiontableentry1 jmain_menu_hover\"' onmouseout='this.className=\"sectiontableentry2 jmain_menu\"'"
		)
	);
	if ($jSeason->disable_tips or (is_a($jTipsCurrentUser, 'jTipsUser') and $jTipsCurrentUser->inSeason($jSeason) and $jTipsCurrentUser->status  == '1')) {
		$classes['Tips'] = array(
			'class' => "sectiontableentry2 jmain_menu",
			'cursor' => "cursor:pointer;cursor:hand;",
			'onevent' => "onClick='window.location.href=\"" .jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Tips$season_link"). "\"' onmouseover='this.className=\"sectiontableentry1 jmain_menu_hover\"' onmouseout='this.className=\"sectiontableentry2 jmain_menu\"'"
		);
	}
	$classes[$view]['class'] = 'sectiontableheader jmain_menu';
	$classes[$view]['onevent'] = substr($classes[$view]['onevent'], 0, strpos($classes[$view]['onevent'], ' '));

	$render->assign('menuitems', $classes);

	$size = round((100/count($classes)), 1);
	$render->assign('cellsize', $size);

	//Should the menu actually be shown? So far we have set the page title
	if (!$jTips['DisableMenu']) {
		$render->display();
	}
}
