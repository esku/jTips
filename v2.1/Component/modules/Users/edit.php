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

jTipsSpoofCheck();

global $jTips, $database;

require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/classes/juser.class.php');
require_once('components/com_jtips/modules/Users/edit.tmpl.php');

$tpl = new EditMode();

$tpl->formData = array(
	'title' => $jLang['_ADMIN_USERS_TITLE']. ": " .$jLang['_ADMIN_OTHER_NEW']
);

$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams());
$options = array(jTipsHTML::makeOption('', $jLang['_ADMIN_USERS_SELECT_SEASON']));
foreach ($jSeasons as $season) {
	$options[] = jTipsHTML::makeOption($season->id, $season->name);
}

$filters = array(
	$jLang['_ADMIN_SEASON_SELECT'] => jTipsHTML::selectList($options, 'season_id', "id='season_id' class='inputbox'", 'value', 'text', jTipsGetParam($_REQUEST, 'season_id', '')),
	$jLang['_ADMIN_SEARCH'] => "<input type='text' name='user_search' value='" .jTipsGetParam($_REQUEST, 'user_search', ''). "' class='inputbox' /> <button type='submit' class='button'>" .$jLang['_ADMIN_GO']. "</button> <button type='submit' class='button' onclick='this.form.user_search.value=\"\";'>" .$jLang['_ADMIN_RESET']. "</button>"
);
$tpl->selectLists = $filters;


$currentDir = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if ($currentDir == 'asc') {
	$dir = 'desc';
} else {
	$dir = 'asc';
}
$tpl->nextDir = $dir;

if ($user_search = jTipsGetParam($_REQUEST, 'user_search', false)) {
	$where = "AND (username LIKE '%" .$database->getEscaped($user_search). "%' OR name LIKE '%" .$database->getEscaped($user_search). "%')";
} else {
	$where = '';
}


$offset = jTipsGetParam($_REQUEST, 'limitstart', 0);
$query = "SELECT COUNT(*) FROM #__users WHERE block = '0' $where";
$database->setQuery($query);
$total = $database->loadResult();
$limit = jTipsGetParam($_REQUEST, 'limit', $jTips['NumMax']);
$pageNav = new mosPageNav($total, $offset, $limit);
$tpl->pageNav = $pageNav;

$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if (empty($direction)) $direction = 'asc';

$orderby = jTipsGetParam($_REQUEST, 'filter_order', 'name');
if (empty($orderby)) $orderby = 'name';

// BUG 319 - creating users that don't require activation, or creating a user by admin leaves an activation code
//$query = "SELECT id FROM #__users WHERE activation = '' ORDER BY $orderby $direction";
$query = "SELECT id FROM #__users WHERE block = '0' $where ORDER BY $orderby $direction";
$database->setQuery($query, $pageNav->limitstart, $pageNav->limit);
$list = (array)$database->loadResultArray();
$users = array();
foreach ($list as $id) {
	if (isJoomla15()) {
		$JoomlaUser = new JUser();
	} else {
		$JoomlaUser = new mosUser($database);
	}
	$JoomlaUser->load($id);
	$users[] = $JoomlaUser;
}
$tpl->users = $users;

$tpl->display();
?>