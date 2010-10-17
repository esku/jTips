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
 * Description: build a paginated list of comments
 */

global $database, $jTips;

require_once('components/com_jtips/classes/jcomment.class.php');
require_once('components/com_jtips/classes/jround.class.php');

$formData = array(
	'title' => $jLang['_ADMIN_DASH_COMMENT_MANAGER'],
	'editTask' => 'edit',
	'module' => 'Comments',
	'icon' => 'comments'
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
	"<a href='javascript:tableOrdering(\"comment\", \"$dir\", \"list\");'>" .$jLang['_COM_DASH_COMMENT']. "</a>",
	$jLang['_COM_DASH_USER'],
	$jLang['_ADMIN_USERS_USERNAME'],
	$jLang['_ADMIN_ROUND_SEASON'],
	$jLang['_ADMIN_ROUND_ROUND'],
	"<a href='javascript:tableOrdering(\"updated\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_COMM_POSTED']. "</a>"
);

$jComment = new jComment($database);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$limit = jTipsGetParam($_REQUEST, 'limit', 25);

$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if (empty($direction)) $direction = 'asc';

$orderby = jTipsGetParam($_REQUEST, 'filter_order', 'comment');
if (empty($orderby)) $orderby = 'comment';

$params = array(
	'order' => array(
		'type' => 'order',
		'direction' => $direction,
		'by' => $orderby
	)
);

//has the season select been used?
if ($season_id = jTipsGetParam($_REQUEST, 'season_id', false)) {
	$params['season_id'] = array(
		'type' => 'join',
		'join_table' => '#__jtips_rounds',
		'lhs_table' => '#__jtips_comments',
		'lhs_key' => 'round_id',
		'rhs_table' => '#__jtips_rounds',
		'rhs_key' => 'id',
		'supplement' => 'AND #__jtips_rounds.season_id = ' .$jComment->_db->Quote($season_id)
	);
}

$jComments = forceArray($jComment->loadByParams($params, $limit, $limitstart));

$pageNav = new mosPageNav($jComment->getCount($params), $limitstart, $limit);
$data = array();
$i = 0;
foreach ($jComments as $comment) {
	//get the comment poster details
	$jTipsUser = new jTipsUser($database);
	$jTipsUser->load($comment->user_id);
	
	//Which round is it for
	$jRound = new jRound($database);
	$jRound->load($comment->round_id);
	
	if (strlen($comment->comment) > 100) {
		$suffix = '...';
	} else {
		$suffix = '';
	}
	
	$data[$comment->id] = array(
		makeListLink(substr(jTipsStripslashes($comment->comment), 0, 100).$suffix, $i++),
		$jTipsUser->getUserField('name'),
		$jTipsUser->getUserField('username'),
		$comment->getSeasonName(),
		$jRound->round,
		TimeDate::toDisplayDateTime($comment->updated)
	);
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

jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'list', $filters, $jLang['_ADMIN_COMMENTS_INFO']);
?>