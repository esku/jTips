<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 01/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

global $database, $jTips;

require_once('components/com_jtips/classes/jteam.class.php');

$formData = array(
	'title' => $jLang['_ADMIN_TEAM_TITLE'],
	'editTask' => 'edit',
	'module' => 'Teams',
	'icon' => 'teams'
);

$currentDir = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if ($currentDir == 'asc') {
	$dir = 'desc';
} else {
	$dir = 'asc';
}
//The header row
$header = array(
	'',
	"<a href='javascript:tableOrdering(\"name\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_TEAM_TEAM']. "</a>",
	"<a href='javascript:tableOrdering(\"location\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_TEAM_LOCATION']. "</a>",
	$jLang['_ADMIN_TEAM_INSEASON'],
	"<a href='javascript:tableOrdering(\"points\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_TEAM_POINTS']. "</a>",
);

$jTeam = new jTeam($database);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$limit = jTipsGetParam($_REQUEST, 'limit', 25);

$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if (empty($direction)) $direction = 'asc';

$orderby = jTipsGetParam($_REQUEST, 'filter_order', 'name');
if (empty($orderby)) $orderby = 'name';

$parameters = array(
	'order' => array(
		'type' => 'order',
		'direction' => $direction,
		'by' => $orderby
	)
);
//has the season select been used?
if ($season_id = jTipsGetParam($_REQUEST, 'season_id', false)) {
	$parameters['season_id'] = $season_id;
}
//jTipsDebug($params);
//die();
$jTeams = forceArray($jTeam->loadByParams($parameters, $limit, $limitstart));

$pageNav = new mosPageNav($jTeam->getCount(), $limitstart, $limit);
$data = array();
$i = 0;
foreach ($jTeams as $team) {
	$data[$team->id] = array(
		makeListLink($team->name, $i++),
		$team->location,
		$team->getSeasonName(),
		$team->points
	);
}

//Build the Season filter dropdown
$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams());
$options = array(jTipsHTML::makeOption('', $jLang['_ADMIN_USERS_SELECT_SEASON']));
foreach ($jSeasons as $season) {
	$options[] = jTipsHTML::makeOption($season->id, $season->name);
}

$filters = array(
	$jLang['_ADMIN_SEASON_SELECT'] => jTipsHTML::selectList($options, 'season_id', "id='season_id' class='inputbox' onChange='this.form.submit();'", 'value', 'text', jTipsGetParam($_REQUEST, 'season_id', ''))
);

jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'list', $filters);
?>