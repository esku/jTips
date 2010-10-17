<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 09/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */
jTipsSpoofCheck();

global $jTips, $jLang, $task;

// include('components/com_jtips/configuration.jtips.php');
if (empty($_POST)) {
	mosRedirect('index2.php?option=com_jtips&module=Dashboard', 'Restricted Access');
}
ksort($_POST);
$new_config = array();
foreach ($_POST as $key => $val) {
	if (preg_match('/^[A-Z]/', $key)) {
		$new_config[$key] = $val;
	}
}
$filename = 'components/com_jtips/config.jtips.php';
if (writeArrayToFile('jTips', $new_config, $filename)) {
	$message = $jLang['_ADMIN_CONFIG_SAVE_SUCCESS'];
} else {
	$message = $jLang['_ADMIN_CONFIG_SAVE_FAILURE'];//"Error: The file $filename is not writable";
}
if ($task == 'apply') {
	mosRedirect('index2.php?option=com_jtips&hidemainmenu=1&module=Configuration', $message);
} else {
	mosRedirect('index2.php?option=com_jtips&module=Dashboard', $message);
}
?>
