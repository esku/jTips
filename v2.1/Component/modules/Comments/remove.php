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
 * Description: Delete the selected comments from the database
 */

//check for hack attempt
jTipsSpoofCheck();

global $database;

require_once('components/com_jtips/classes/jcomment.class.php');

$ids = jTipsGetParam($_REQUEST, 'cid', array());

if (!is_array($ids) or empty($ids)) {
	//Should never get here
	mosRedirect('index2.php?option=com_jtips&task=list&module=Comments', 'Invalid Selection');
}
$jComment = new jComment($database);
$results = array();
foreach ($ids as $id) {
	$results[] = $jComment->destroy($id);
}
$message = 'Deleted ' .array_sum($results). ' out of ' .count($results). ' selected';
mosRedirect('index2.php?option=com_jtips&task=list&module=Comments', $message);
?>