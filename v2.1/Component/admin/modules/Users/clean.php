<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1.14 - 31/08/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: apply the changes and reload the configuration page 
 */

global $database;

jTipsSpoofCheck();

require_once('components/com_jtips/classes/juser.class.php');

jTipsLogger::_log('Cleaning users', 'info');

// find out which users are not in the #__users table
$query = "SELECT j.id FROM #__jtips_users j LEFT JOIN #__users u ON j.user_id = u.id WHERE u.id IS NULL";
$database->setQuery($query);
$database->query();
$total = $database->getNumRows();
jTipsLogger::_log('Found ' .$total. ' users to be removed');
if ($total > 1) {
	$ids = (array) $database->loadResultArray();
	foreach ($ids as $id) {
		$jTipsUser = new jTipsUser($database);
		$jTipsUser->destroy($id);
	}
}

$message = sprintf($jLang['_COM_ADMIN_USERS_CLEANED_MESSAGE'], $total);

mosRedirect('index2.php?option=com_jtips&module=Users&task=list', $message);
