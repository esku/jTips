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
 * Description: Delete the current import file
 */
jTipsSpoofCheck();

global $jLang, $mosConfig_absolute_path;
$file = $mosConfig_absolute_path. '/administrator/components/com_jtips/import.csv';
if (isJoomla15()) {
	jimport('joomla.filesystem.file');
	if (JFile::exists($file)) {
		if (JFile::delete($file)) {
			$message = $jLang['_ADMIN_FILE_DELETED'];
		} else {
			$message = $jLang['_ADMIN_FILE_DELETE_FAILED'];
		}
	} else {
		$message = $jLang['_ADMIN_FILE_DELETE_NOFILE'];
	}
} else {
	if (file_exists($file)) {
		if (unlink($file)) {
			$message = $jLang['_ADMIN_FILE_DELETED'];
		} else {
			$message = $jLang['_ADMIN_FILE_DELETE_FAILED'];
		}
	} else {
		$message = $jLang['_ADMIN_FILE_DELETE_NOFILE'];
	}
}
mosRedirect('index2.php?option=com_jtips&task=list&module=Import', $message);
?>