<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );
global $database, $jTips;

require_once('components/com_jtips/classes/jtip.class.php');

$jTip = new jTip($database);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$limit = jTipsGetParam($_REQUEST, 'limit', $jTips['NumMax']);
$params = array(
	'join' => array(
		'type' => 'join',
		'join_table' => '#__jtips_games',
		'lhs_table' => '#__jtips_tips',
		'lhs_key' => 'game_id',
		'rhs_table' => '#__jtips_games',
		'rhs_key' => 'id'
	),
	'group' => array(
		'type' => 'group',
		'by' => 'round_id`, `user_id'
	),
	'order' => array(
		'type' => 'order',
		'by' => 'round_id',
		'direction' => 'DESC'
	)
);
if ($user_id = jTipsGetParam($_REQUEST, 'user_id', false)) {
	$params['user_id'] = $user_id;
}
$jTipArray = forceArray($jTip->loadByParams($params, $limit, $limitstart));
unset($params['order']);
$pageNav = new mosPageNav($jTip->getCount($params, true), $limitstart, $limit);

$header = array(
	'',
	'User',
	'Round',
	'Season'
);

$data = array();
$i = 0;
foreach ($jTipArray as $tip) {
	$tip->fillInAdditionalFields();
	$data[$tip->id] = array(
		makeListLink($tip->user->getName(), $i++),
		$tip->round_num,
		$tip->season->name
	);
}

$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams(array()));
$seasons = objectsToSelectList($jSeasons, 'name');

$jTipsUser = new jTipsUser($database);
$jTipsUsers = forceArray($jTipsUser->loadByParams(array()));
foreach ($jTipsUsers as $u) {
	$u->name = $u->getName() . " (" .$seasons[$u->season_id]. ")";
	$jTipsUsersArray[] = $u;
}
$users = objectsToSelectList($jTipsUsersArray, 'name');
asort($users);
$formData = array(
	'title' => $jLang['_ADMIN_DASH_TIPS_MANAGER'],
	'editTask' => 'edit',
	'module' => 'Tips',
	'icon' => 'tips'
);

$filters = array(
	$jLang['_ADMIN_USERS_SELECT'] => makeSelectList($users, 'user_id', "id='user_id' onChange='this.form.submit();'", jTipsGetParam($_REQUEST, 'user_id', '')),
	$jLang['_ADMIN_SEASON_SELECT'] => makeSelectList($seasons, 'season_id', "id='season_id' onChange='this.form.submit();'", jTipsGetParam($_REQUEST, 'season_id', ''))
);
jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'list', $filters);
?>