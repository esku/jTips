<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 02/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: builds the edit fields for the round.
 * New in 2.1 - edit the games and round info on the one page!
 */

//This breaks the shortcuts on the dashboard
//jTipsSpoofCheck();

global $jTips, $database, $jLang, $mainframe;

require_once('components/com_jtips/classes/jgame.class.php');
require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/modules/Rounds/edit.tmpl.php');

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/lib/date.js'></script>");
$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Rounds/Edit.js'></script>");
if (!isJoomla15()) {
	$dateFormat = '%Y-%m-%d';
} else {
	$dateFormat = $jTips['DateFormat'];
}
// Set default values for newer config variables
if (!isset($jTips['Is24Hour'])) $jTips['Is24Hour'] = '0';
if (!isset($jTips['MinuteValues'])) $jTips['MinuteValues'] = '0';
$mainframe->addCustomHeadTag("<script type='text/javascript'>var DateFormat = '" . ($dateFormat). "'; var Offset = " .TimeDate::getOffset(). "; var MinuteValues = {$jTips['MinuteValues']}; var Is24Hour = {$jTips['Is24Hour']};</script>");
//BUG 259 - No need to define style here - causes problems in J1.0 with IE
//$mainframe->addCustomHeadTag("<style type='text/css'>.pick_score {display:none;} .pick_margin {display:none} .pick_bonus {display:none;} .team_starts {display:none;}</style>");

$tpl = new EditMode();

$focus = new jRound($database);

$ids = jTipsGetParam($_REQUEST, 'cid', array());
if (!empty($ids)) {
	$id = array_shift($ids);
	$focus->load($id);
}
//load the jRound object into the script
$mainframe->addCustomHeadTag("<script type='text/javascript'>var jRound = " .@json_encode($focus). ";</script>");

$tpl->formData = array(
	'title' => $jLang['_ADMIN_ROUND_TITLE']. ": " .($focus->exists() ? $jLang['_ADMIN_OTHER_EDIT'] : $jLang['_ADMIN_OTHER_NEW'])
);

$tpl->focus =& $focus;
$tpl->status = $focus->getStatus();
if ($focus->getStatus() !== false) {
	$tpl->massToggles = "disabled";
} else {
	$tpl->massToggles = "";
}

$meridiemOptions = array(
	jTipsHTML::makeOption('am', 'am'),
	jTipsHTML::makeOption('pm', 'pm')
);

//get the time from the start_time
if (!$focus->start_time) {
	$focus->start_time = gmdate('Y-m-d H:i:s');
}
//BUG 263
if (!isJoomla15()) {
	$tpl->date_start_date             = TimeDate::toDisplayDate($focus->start_time, true);
	$tpl->date_start_date             = TimeDate::toDatabaseDate($tpl->date_start_date); 
} else {
	$tpl->date_start_date             = TimeDate::toDisplayDate($focus->start_time, true);
}
$date_start_time_hour			= TimeDate::format($focus->start_time, '%I', true);
$date_start_time_minute			= TimeDate::format($focus->start_time, '%M', true);
$date_start_time_meridiem		= strtolower(TimeDate::format($focus->start_time, '%p', true));

// BUG 326 - Optionally allo 24-hour format
if (intval($jTips['Is24Hour']) == 1) {
	$hour_end = 23;
	if ($date_start_time_meridiem == 'pm') {
		$date_start_time_hour += 12;
	}
} else {
	$hour_end = 12;
}
$tpl->date_start_time_hour		= jTipsHTML::integerSelectList(1, $hour_end, 1, 'date_start_time_hour', "class='inputbox'", $date_start_time_hour);
// BUG 326 - Optionally allow all minute values
if (intval($jTips['MinuteValues']) == 1) {
	$end = 59;
	$step = 1;
} else {
	$end = 55;
	$step = 5;
}
$tpl->date_start_time_minute	= jTipsHTML::integerSelectList('00', $end, $step, 'date_start_time_minute', "class='inputbox'", $date_start_time_minute);
if (intval($jTips['Is24Hour']) == 1) {
	$tpl->date_start_time_meridiem = '';
} else {
	$tpl->date_start_time_meridiem	= jTipsHTML::selectList($meridiemOptions, 'date_start_time_meridiem', "class='inputbox'", 'value', 'text', $date_start_time_meridiem);

}

//now prepare the end_time
if (!$focus->end_time) {
	$focus->end_time = gmdate('Y-m-d H:i:s');
}
//BUG 263
if (!isJoomla15()) {
	$tpl->date_end_date             = TimeDate::toDisplayDate($focus->end_time, true);
	$tpl->date_end_date             = TimeDate::toDatabaseDate($tpl->date_end_date); 
} else {
	$tpl->date_end_date             = TimeDate::toDisplayDate($focus->end_time, true);
}
$date_end_time_hour				= TimeDate::format($focus->end_time, '%I', true);
$date_end_time_minute			= TimeDate::format($focus->end_time, '%M', true);
$date_end_time_meridiem			= strtolower(TimeDate::format($focus->end_time, '%p', true));

// BUG 326 - Optionally allo 24-hour format
if (intval($jTips['Is24Hour']) == 1) {
	$hour_end = 23;
	if ($date_end_time_meridiem == 'pm') {
		$date_end_time_hour += 12;
	}
} else {
	$hour_end = 12;
}
$tpl->date_end_time_hour		= jTipsHTML::integerSelectList(1, $hour_end, 1, 'date_end_time_hour', "class='inputbox'", $date_end_time_hour);
$tpl->date_end_time_minute		= jTipsHTML::integerSelectList('00', $end, $step, 'date_end_time_minute', "class='inputbox'", $date_end_time_minute);
if (intval($jTips['Is24Hour']) == 1) {
	$tpl->date_end_time_meridiem = '';
} else {
	$tpl->date_end_time_meridiem	= jTipsHTML::selectList($meridiemOptions, 'date_end_time_meridiem', "class='inputbox'", 'value', 'text', $date_end_time_meridiem);
}


$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams());
$options = array(jTipsHTML::makeOption('', $jLang['_ADMIN_USERS_SELECT_SEASON']));
foreach ($jSeasons as $season) {
	$options[] = jTipsHTML::makeOption($season->id, $season->name);
}
$tpl->selectLists['season_id'] = jTipsHTML::selectList($options, 'season_id', "id='season_id' class='inputbox' onChange='getTheRounds(this);'", 'value', 'text', $focus->season_id);

$roundOptions = array(jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']));
$tpl->selectLists['roundnum'] = jTipsHTML::selectList($roundOptions, 'roundnum', "disabled id='roundnum', class='inputbox'", 'value', 'text', $focus->round);

$tpl->display();
?>
