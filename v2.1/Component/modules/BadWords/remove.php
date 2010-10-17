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

$ids = jTipsGetParam($_REQUEST, 'cid', array());

if (!is_array($ids)) {
	mosRedirect('index2.php?option=com_jtips&task=list&module=BadWords', 'Invalid Selection');
}
$focus = new jBadWord($database);
$deleted = 0;
foreach ($ids as $id) {
	if ($focus->destroy($id)) {
		$deleted++;
	}
}
$message = "Deleted $deleted/" .count($id). " BadWords";
mosRedirect('index2.php?option=com_jtips&task=list&module=BadWords', $message);
?>