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
 * Description: 
 */

global $jTips, $database, $jLang, $mainframe;

require_once('components/com_jtips/classes/jgame.class.php');
require_once('components/com_jtips/classes/jround.class.php');

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Rounds/List.js'></script>");

$focus = new jRound($database);

//can add in an ordering field here in the params
$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'desc');
if (empty($direction)) $direction = 'desc';

$orderby = jTipsGetParam($_REQUEST, 'filter_order', 'round');
if (empty($orderby)) $orderby = 'round';

$params = array(
	'order' => array(
		'type' => 'order',
		'direction' => "$direction,",
		'by' => $orderby
	),
	'order1' => array(
		'type' => 'order',
		'direction' => 'ASC',
		'by' => 'name'
	),
	'join' => array(
		'type' => 'join',
		'join_table' => '#__jtips_seasons',
		'lhs_table' => '#__jtips_rounds',
		'lhs_key' => 'season_id',
		'rhs_table' => '#__jtips_seasons',
		'rhs_key' => 'id',
	)
);

//has the season select been used?
if ($season_id = jTipsGetParam($_REQUEST, 'season_id', false)) {
	$params['season_id'] = $season_id;
}
//if ($status = jTipsGetParam($_REQUEST, 'status', '')) {
//	$params['status'] = $status;
//}

$limit = jTipsGetParam($_REQUEST, 'limit', 25);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$pageNav = new mosPageNav($focus->getCount($params), $limitstart, $limit);
$jRounds = forceArray($focus->loadByParams($params, $limit, $limitstart));

$formData = array(
	'title' => $jLang['_ADMIN_ROUND_TITLE'],
	'editTask' => 'edit',
	'module' => 'Rounds',
	'icon' => 'rounds'
);

$currentDir = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'desc');
if ($currentDir == 'desc') {
	$dir = 'asc';
} else {
	$dir = 'desc';
}
//The header row
$header = array(
	'',
	"<a href='javascript:tableOrdering(\"round\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_ROUND_ROUND']. "</a>",
	"<a href='javascript:tableOrdering(\"name\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_ROUND_SEASON']. "</a>",
	"<a href='javascript:tableOrdering(\"#__jtips_rounds.start_time\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_ROUND_START']. "</a>",
	"<a href='javascript:tableOrdering(\"#__jtips_rounds.end_time\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_ROUND_END']. "</a>",
	$jLang['_ADMIN_ROUND_STATUS']
);

//The row data for each for
$data = array();
$i = 0;
foreach ($jRounds as $round) {
	$status = $round->getStatus();
	if ($status === FALSE) {
			$status = $jLang['_ADMIN_ROUND_STATUS_NS']; //orange  - Not Started
			$colour = "#FF7200";
			$disabled = "";
	} else if ($status === 0) {
			$status = $jLang['_ADMIN_ROUND_STATUS_IP'];//green  - In Progress
			$colour = "#106F00";
	} else if ($status === 1) {
			$status = $jLang['_ADMIN_ROUND_STATUS_C']; //black - Complete
			$colour = "#000000";
	} else if ($status === -1) {
			$status = $jLang['_ADMIN_ROUND_STATUS_P']; //red  - Pending Results
			$colour = "#DF0009";
	}
	$data[$round->id] = array(
		makeListLink($jLang['_ADMIN_ROUND_ROUND']. " " .$round->round, $i++),
		$round->getSeasonName(),
		TimeDate::toDisplayDateTime($round->start_time), //Need to correctly parse this datetime
		TimeDate::toDisplayDateTime($round->end_time), //Need to correctly parse this datetime
		"<div style='text-align:center;color:$colour;font-weight:bold;'>" .$status. "</div>"
	);
}

$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams());
$options = array(jTipsHTML::makeOption('', $jLang['_ADMIN_USERS_SELECT_SEASON']));
foreach ($jSeasons as $season) {
	$options[] = jTipsHTML::makeOption($season->id, $season->name);
}

//$statusOptions = array(
//	jTipsHTML::makeOption('', $jLang['_ADMIN_ROUND_STATUS_SELECT']),
//	jTipsHTML::makeOption('false', $jLang['_ADMIN_ROUND_STATUS_NS']),
//	jTipsHTML::makeOption('0', $jLang['_ADMIN_ROUND_STATUS_IP']),
//	jTipsHTML::makeOption('1', $jLang['_ADMIN_ROUND_STATUS_C']),
//	jTipsHTML::makeOption('-1', $jLang['_ADMIN_ROUND_STATUS_P'])
//);

$filters = array(
	$jLang['_ADMIN_SEASON_SELECT'] => jTipsHTML::selectList($options, 'season_id', "id='season_id' class='inputbox' onChange='this.form.submit();'", 'value', 'text', jTipsGetParam($_REQUEST, 'season_id', ''))
//	$jLang['_ADMIN_ROUND_STATUS_SELECT'] = jTipsHTML::selectList($statusOptions, 'status', "id='status' class='inputbox' onChange='this.form.submit();'", 'value', 'text', jTipsGetParam($_REQUEST, 'status', ''))
);

jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'list', $filters, null, true);
?>