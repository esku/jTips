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
 * Description: Updates a comment once edited
 */

//check for hack attempt
jTipsSpoofCheck();

global $jTips, $database;

require_once('components/com_jtips/classes/jcomment.class.php');

$jComment = new jComment($database);
if ($id = jTipsGetParam($_REQUEST, 'id', false)) {
	$jComment->load($id);
}

$jComment->bind($_POST);
if ($jComment->save()) {
	$message = $jlang['_ADMIN_COMMENT_SAVE_SUCCESS'];
} else {
	$message = $jlang['_ADMIN_COMMENT_SAVE_FAILURE']. " " .$jComment->_error;
}
mosRedirect('index2.php?option=com_jtips&task=list&module=Comments', $message);

?>