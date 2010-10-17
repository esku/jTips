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

require_once('components/com_jtips/classes/juser.class.php');

global $database, $jLicence;

if(!is_array(jTipsGetParam($_REQUEST, 'cid', array()))) {
	//Should never get here
	mosRedirect('index2.php?option=com_jtips&task=list&module=Users', 'Invalid Selection!');
}
$results = array();
foreach(jTipsGetParam($_REQUEST, 'cid', array()) as $id) {
	$jTipsUser = new jTipsUser($database);
	$jTipsUser->load($id);
	$res = $jTipsUser->destroy();
	$results[] = $res;
}
// BUG 382 - recount the number of users and update licence
if (!empty($results)) {
	$jLicence->revalidate(true);
}
$message = 'Deleted ' . array_sum($results) . ' users out of ' . count($results) . ' selected';
mosRedirect('index2.php?option=com_jtips&task=list&module=Users', $message);
?>
