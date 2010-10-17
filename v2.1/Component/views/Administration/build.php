<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1 - 13/10/2008
 * @version 2.1
 * @package jTips
 *
 * Description:
 */

global $mosConfig_absolute_path, $jTipsCurrentUser, $mainframe, $database, $Itemid;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/Administration/tmpl/default.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/Administration/tmpl/default.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/Administration/tmpl/default.php');
}

if (!jTipsGetParam($_REQUEST, 'menu', 1)) {
	if (isJoomla15()) {
		//Trash anything we have so far
		ob_clean();
		ob_start();
	}
	$customCalFunc = true;
} else {
	$customCalFunc = false;
}
$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsCustomCalFunc = " .@json_encode($customCalFunc). ";</script>");

//load the season
$season_id = getSeasonID();
$jSeason = new jSeason($database);
$jSeason->load($season_id);

//check if this user is really allowed to be here
$my =& $mainframe->getUser();
//jTipsDebug($jSeason);

$mainframe->addCustomHeadTag('<script type="text/javascript" src="' .$mosConfig_live_site. '/administrator/components/com_jtips/lib/date.js"></script>');

if ($jSeason->scorer_id != $my->id or empty($my->id) or empty($jSeason->scorer_id) ) {
	//die cleanly
	/*if (isJoomla15()) {
		jexit('You are not a scorer for this competition');
	} else {
		die('You are not a scorer for this competition');
	}*/
	jTipsRedirect('index.php?option=com_jtips&Itemid=' .$Itemid, $jLang['_COM_NOT_A_SCORER']);
	exit;
} else {
	if (isJoomla15()) {
		$mainframe->addCustomHeadTag('<script type="text/javascript" src="' .$mosConfig_live_site. '/components/com_jtips/views/Administration/Administration.js"></script>');
		$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsLiveSite = '$mosConfig_live_site';var Offset = " .TimeDate::getOffset(false). ";var DateFormat = '" .$jTips['DateFormat']. "';</script>");
		jTipsCommonHTML::loadCalendar();
	}
	echo '<style>
	@import url(' .$mosConfig_live_site. '/components/com_jtips/css/jtips-default.css);
	@import url(' .$mosConfig_live_site. '/components/com_jtips/css/jtips-popup.css);
	</style>';
}

//now we are ok... render the page
$render = new jTipsRenderAdministration();
$render->assign('jSeason', $jSeason);
global $Itemid;
$render->assign('Itemid', jTipsGetParam($_REQUEST, 'Itemid', $Itemid));

$jGame = new jGame($database);

if (!jTipsGetParam($_REQUEST, 'menu', 1)) {
	$width = $jTips['ShowTipsWidth'] - 40;
	echo '<div style="padding-top:10px;padding-left:10px;padding-right:10px;padding-bottom:10px;text-align:center;">';
}

//which round should be displayed?
if (!jTipsGetParam($_REQUEST, 'roundnum', false)) {
	//Need to get list of available rounds from season
	$render->assign('roundDropDown', jTipsHTML::integerSelectList(1, $jSeason->rounds, 1, 'roundnum', "id='roundnum' class='inputbox'", jTipsGetParam($_REQUEST, 'round')));

	$render->displayRoundPicker();
} else {
	$round = jTipsGetParam($_REQUEST, 'roundnum', '');
	$render->assign('roundnum', $round);
//	$mainframe->addCustomHeadTag('<script type="text/javascript" src="' .$mosConfig_live_site. '/administrator/components/com_jtips/lib/date.js"></script>');
	//determine round id from season and round
	$parameters = array(
		'season_id' => $jSeason->id,
		'round' => $round
	);

	$jRound = new jRound($database);
	$jRound->loadByParams($parameters);

	//jTipsDebug($jRound);

	//which order to show home/away
	if ($jSeason->tips_layout == 'away') { //away on left
		$left = 'away';
		$right = 'home';
	} else {
		$left = 'home';
		$right = 'away';
	}

	$render->assign('left', $left);
	$render->assign('right', $right);

	if (jTipsGetParam($_REQUEST, 'roundnum', false)) {
		//get the games that are pending results
		$gameParameters = array(
			'round_id' => $jRound->id
		);
		$jGames = forceArray($jGame->loadByParams($gameParameters));
		$render->assign('jGames', $jGames);

		//show the page
		if (!$jRound->exists() or $jRound->end_time > gmdate('Y-m-d H:i:s')) {
			//show the create round and games form
			$render->assign('focus', $jRound);
			$render->assign('status',  $jRound->getStatus());
			if ($jRound->getStatus() !== false) {
				$render->assign('massToggles',  "disabled");
			} else {
				$render->assign('massToggles',  "");
			}

			$meridiemOptions = array(
				jTipsHTML::makeOption('am', 'am'),
				jTipsHTML::makeOption('pm', 'pm')
			);

			//get the time from the start_time
			if (!$jRound->start_time) {
				$jRound->start_time = gmdate('Y-m-d H:i:s');
			}
			//$render->date_start_date			= TimeDate::toDisplayDate($jRound->start_time, true);
			//BUG 263
			if (!isJoomla15()) {
				$render->date_start_date		= TimeDate::toDisplayDate($jRound->start_time, true);
				$render->date_start_date		= TimeDate::toDatabaseDate($render->date_start_date);
			} else {
				$render->date_start_date		= TimeDate::toDisplayDate($jRound->start_time, true);
			}
			$date_start_time_hour				= TimeDate::format($jRound->start_time, '%I', true);
			$date_start_time_minute				= TimeDate::format($jRound->start_time, '%M', true);
			$date_start_time_meridiem			= strtolower(TimeDate::format($jRound->start_time, '%p', true));

			$render->date_start_time_hour		= jTipsHTML::integerSelectList(1, 12, 1, 'date_start_time_hour', "class='inputbox'", $date_start_time_hour);
			$render->date_start_time_minute		= jTipsHTML::integerSelectList('00', 55, 5, 'date_start_time_minute', "class='inputbox'", $date_start_time_minute);
			$render->date_start_time_meridiem	= jTipsHTML::selectList($meridiemOptions, 'date_start_time_meridiem', "class='inputbox'", 'value', 'text', $date_start_time_meridiem);

			//now prepare the end_time
			if (!$jRound->end_time) {
				$jRound->end_time = gmdate('Y-m-d H:i:s');
			}
			//$render->date_end_date			= TimeDate::toDisplayDate($jRound->end_time, true);
			//BUG 263
			if (!isJoomla15()) {
				$render->date_end_date		= TimeDate::toDisplayDate($jRound->end_time, true);
				$render->date_end_date		= TimeDate::toDatabaseDate($render->date_end_date);
			} else {
				$render->date_end_date		= TimeDate::toDisplayDate($jRound->end_time, true);
			}
			$date_end_time_hour				= TimeDate::format($jRound->end_time, '%I', true);
			$date_end_time_minute			= TimeDate::format($jRound->end_time, '%M', true);
			$date_end_time_meridiem			= strtolower(TimeDate::format($jRound->end_time, '%p', true));

			$render->date_end_time_hour		= jTipsHTML::integerSelectList(1, 12, 1, 'date_end_time_hour', "class='inputbox'", $date_end_time_hour);
			$render->date_end_time_minute	= jTipsHTML::integerSelectList('00', 55, 5, 'date_end_time_minute', "class='inputbox'", $date_end_time_minute);
			$render->date_end_time_meridiem	= jTipsHTML::selectList($meridiemOptions, 'date_end_time_meridiem', "class='inputbox'", 'value', 'text', $date_end_time_meridiem);


			$render->displayEditRound();
		} else {
			//show the add results page
			$render->display();
		}
	} else {
		$render->displayNoRounds();
	}
}
if (!jTipsGetParam($_REQUEST, 'menu', 1)) {
	echo '</div>';
	if (!isJoomla15()) {
		die();
	} else {
		// only exit if we are using a menu item to get to the admin area
		if (jTipsGetParam($_REQUEST, 'roundnum', false)) { // quick hack!
			jexit();
		}
	}
}
?>
