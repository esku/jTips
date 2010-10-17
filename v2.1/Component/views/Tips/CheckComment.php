<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 16/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

global $database, $mosConfig_absolute_path;

ob_clean();

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jbadword.class.php');

$comment = jTipsGetParam($_REQUEST, 'comment', '');

$jBad = new jBadWord($database);
$badwords = forceArray($jBad->loadByParams(array()));
$count = $deleted = $replaced = 0;
$results = array();
foreach ($badwords as $jBadWord) {
	$search = '/' .$jBadWord->badword. '/' .($jBadWord->match_case == 1 ? 'i' : '');
	$found = preg_match_all($search, $comment, $matches);
	if ($found > 0) {
		$count++;
		$found = $found + $jBadWord->hits;
		$jBadWord->hits = $found;
		if ($jBadWord->action == 'delete') {
			$deleted++;
			array_push($results, 0);
		} else {
			$new_comment = preg_replace($search, $jBadWord->replace, $comment);
			$replaced++;
			array_push($results, 1);
		}
		$jBadWord->save();
		continue;
	}
}
if (in_array(0, $results)) {
	echo @json_encode(0);
	exit();
}
if (in_array(1, $results)) {
	echo @json_encode(1);
	exit();
}
echo @json_encode(-1);
exit();
?>