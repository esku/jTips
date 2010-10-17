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
 * Description: Creates a new BadWord or updates an existing one.
 */

jTipsSpoofCheck();

global $database;

require_once('components/com_jtips/classes/jbadword.class.php');

$focus = new jBadWord($database);

$id = jTipsGetParam($_REQUEST, 'id', false);
if ($id) {
	$focus->load($id);
}

$focus->bind($_POST);

if (jTipsGetParam($_REQUEST, 'reset_hits', 0) == 1) {
	$focus->hits = 0;
}
if ($focus->save()) {
	$message = $jlang['_ADMIN_BADWORD_SAVE_SUCCESS'];
} else {
	$message = $jlang['_ADMIN_BADWORD_SAVE_FAILURE'];
}
mosRedirect('index2.php?option=com_jtips&task=list&module=BadWords', $message);
?>