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
 * Description: Prepares a paginated list of badwords
 */

global $database, $jTips;

require_once('components/com_jtips/classes/jbadword.class.php');

$formData = array(
	'title' => $jLang['_ADMIN_BW_HEADER'],
	'editTask' => 'edit',
	'module' => 'BadWords',
	'icon' => 'badwords'
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
	"<a href='javascript:tableOrdering(\"badword\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_BW_BAD_WORD']. "</a>",
	"<a href='javascript:tableOrdering(\"match_case\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_BW_CASE_SENSITIVE']. "</a>",
	"<a href='javascript:tableOrdering(\"action\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_BW_ACTION']. "</a>",
	"<a href='javascript:tableOrdering(\"replace\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_BW_REPLACEMENT']. "</a>",
	"<a href='javascript:tableOrdering(\"hits\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_BW_HITS']. "</a>",
	"<a href='javascript:tableOrdering(\"updated\", \"$dir\", \"list\");'>" .$jLang['_ADMIN_BW_UPDATED']. "</a>"
);

$jBadWord = new jBadWord($database);
$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$limit = jTipsGetParam($_REQUEST, 'limit', 25);

$direction = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if (empty($direction)) $direction = 'asc';

$orderby = jTipsGetParam($_REQUEST, 'filter_order', 'badword');
if (empty($orderby)) $orderby = 'badword';

$params = array(
	'order' => array(
		'type' => 'order',
		'direction' => $direction,
		'by' => $orderby
	)
);

$jBadWords = forceArray($jBadWord->loadByParams($params, $limit, $limitstart));

$pageNav = new mosPageNav($jBadWord->getCount(), $limitstart, $limit);

$data = array();
$i = 0;
foreach ($jBadWords as $badword) {
	$data[$badword->id] = array(
		makeListLink($badword->badword, $i++),
		"<div style='text-align:center;'><img src='images/" .($badword->match_case ? 'tick' : 'publish_x'). ".png' border='0' alt='{$badword->match_case}' /></div>",
		ucwords($badword->action),
		$badword->replace,
		$badword->hits,
		TimeDate::toDisplayDateTime($badword->updated)
	);
}

jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, 'edit');
?>