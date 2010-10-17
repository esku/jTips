<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 * 
 * Description: List the available seasons
 */

global $jTips, $database, $jLang, $mosConfig_absolute_path;

require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jseason.class.php');

$jSeason = new jSeason($database);

//can add in an ordering field here in the params
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

$limit = jTipsGetParam($_REQUEST, 'limit', 25);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$pageNav = new mosPageNav($jSeason->getCount(), $limitstart, $limit);
$jSeasons = forceArray($jSeason->loadByParams($parameters, $limit, $limitstart));

$formData = array(
	'title' => $jLang['_ADMIN_SEASON_TITLE'],
	'editTask' => 'edit',
	'module' => 'Seasons',
	'icon' => 'seasons'
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
	"<a href='javascript:tableOrdering(\"name\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_SEASON_NAME']. "</a>",
	"<a href='javascript:tableOrdering(\"start_time\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_SEASON_START']. "</a>",
	"<a href='javascript:tableOrdering(\"end_time\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_SEASON_END']. "</a>"
);

//The row data for each for
$data = array();
$i = 0;
foreach ($jSeasons as $jSeason) {
	$data[$jSeason->id] = array(
		makeListLink($jSeason->name, $i++),
		$jSeason->start_time, //Need to correctly parse this datetime
		$jSeason->end_time //Need to correctly parse this datetime
	);
}

jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'list');
?>