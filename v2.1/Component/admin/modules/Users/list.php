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
 * Description: Prepares the data to render the users list. This is the list of
 * existing competition participants.
 */

global $database, $jTips, $mainframe;

require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jhistory.class.php');
require_once('components/com_jtips/classes/juser.class.php');

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Users/Users.js'></script>");

$formData = array(
	'title' => $jLang['_ADMIN_USERS_TITLE'],
	'editTask' => 'edit',
	'module' => 'Users',
	'icon' => 'user'
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
	"<a href='javascript:tableOrdering(\"name\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_USERS_NAME']. "</a>",
	"<a href='javascript:tableOrdering(\"username\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_USERS_USERNAME']. "</a>",
	$jLang['_ADMIN_ROUND_SEASON'],
	$jLang['_ADMIN_USERS_DOUBLE'],
	$jLang['_ADMIN_USERS_AVERAGE'],
	$jLang['_ADMIN_USERS_TOTAL'],
	$jLang['_ADMIN_USERS_PAID'],
	$jLang['_ADMIN_USERS_APPROVED']
);

$jTipsUser = new jTipsUser($database);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$limit = jTipsGetParam($_REQUEST, 'limit', 25);

$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if (empty($direction)) $direction = 'asc';

$orderby = jTipsGetParam($_REQUEST, 'filter_order', 'name');
if (empty($orderby)) $orderby = 'name';

$params = array(
	'order' => array(
		'type' => 'order',
		'direction' => $direction,
		'by' => $orderby
	),
	'join_users' => array(
		'type' => 'join',
		'join_table' => '#__users',
		'lhs_table' => '#__jtips_users',
		'lhs_key' => 'user_id',
		'rhs_table' => '#__users',
		'rhs_key' => 'id',
	)
);

if ($season_id = jTipsGetParam($_REQUEST, 'season_id', false)) {
	$params['season_id'] = $season_id;
}

$jTipsUsers = forceArray($jTipsUser->loadByParams($params, $limit, $limitstart));

$pageNav = new mosPageNav($jTipsUser->getCount(), $limitstart, $limit);
$data = array();
$i = 0;
foreach ($jTipsUsers as $jTipsUserItem) {
	$scores = $jTipsUserItem->getSummaryScores();
	$data[$jTipsUserItem->id] = array(
		"<label for='cb$i'>" .$jTipsUserItem->getUserField('name'). "</label>",
		$jTipsUserItem->getUserField('username'),
		$jTipsUserItem->getSeasonName(),
		$jTipsUserItem->doubleup,
		number_format($scores[$jTipsUserItem->season_id]['average'], 2),
		$scores[$jTipsUserItem->season_id]['total_points'],
		'<div style="text-align:center;"><a href="javascript:toggleBoolean(\'' .$jTipsUserItem->id. '\', \'paid\');"><img src="images/' .($jTipsUserItem->paid ? "tick" : "publish_x"). '.png" id="paid_' .$jTipsUserItem->id. '" border="0" /></div>',
		'<div style="text-align:center;"><a href="javascript:toggleBoolean(\'' .$jTipsUserItem->id. '\', \'status\');"><img src="images/' .($jTipsUserItem->status == 1 ? "tick" : "publish_x"). '.png" border="0" id="status_' .$jTipsUserItem->id. '" /></a></div>'
	);
	$i++;
}

$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams());
$options = array(jTipsHTML::makeOption('', $jLang['_ADMIN_USERS_SELECT_SEASON']));
foreach ($jSeasons as $season) {
	$options[] = jTipsHTML::makeOption($season->id, $season->name);
}

$filters = array(
	$jLang['_ADMIN_SEASON_SELECT'] => jTipsHTML::selectList($options, 'season_id', "id='season_id' class='inputbox' onChange='this.form.submit();'", 'value', 'text', jTipsGetParam($_REQUEST, 'season_id', ''))
);

//BUG 217 - 5th arg was teamEdit, changed to list - this is the TASK form field value
jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'list', $filters, $jLang['_ADMIN_USERS_INFO'], true);
?>