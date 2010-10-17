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

jTipsSpoofCheck();

global $database;

require_once('components/com_jtips/classes/jbadword.class.php');
require_once('components/com_jtips/classes/jcomment.class.php');

$jBadWordFocus = new jBadWord($database);
$jCommentFocus = new jComment($database);

$badwords = forceArray($jBadWordFocus->loadByParams());
$comments = forceArray($jCommentFocus->loadByParams());
$count = $deleted = $replaced = 0;
foreach ($comments as $jComment) {
	foreach ($badwords as $jBadWord) {
		$search = '/' .$jBadWord->badword. '/' .($jBadWord->match_case == 1 ? 'i' : '');
		$found = preg_match_all($search, $jComment->comment, $matches);
		if ($found > 0) {
			$count++;
			$found = $found + $jBadWord->hits;
			$jBadWord->hits = $found;
			if ($jBadWord->action == 'delete') {
				$jComment->delete($jComment->id);
				$deleted++;
			} else {
				$new_comment = preg_replace($search, $jBadWord->replace, $jComment->comment);
				$jComment->comment = $new_comment;
				$jComment->save();
				$replaced++;
			}
			$jBadWord->save();
			continue;
		}
	}
}
$message = $count. " Matches Found. $deleted Deleted. $replaced Replaced";
mosRedirect('index2.php?option=' .jTipsGetParam($_REQUEST, 'option', 'com_jtips'). '&task=list&module=BadWords', $message);
?>