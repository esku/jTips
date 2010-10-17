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
 * Description: Upload the CSV after validation
 */
jTipsSpoofCheck();

set_time_limit(0);
global $mosConfig_absolute_path;
$target = $mosConfig_absolute_path.'/administrator/components/com_jtips/import.csv';

if (strtolower(strrchr($_FILES['importFile']['name'], '.')) != '.csv') {
	$message = $jLang['_ADMIN_IMPORT_UPLOAD_TYPE_FAILURE'];
} else {
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		$result = JFile::upload($_FILES['importFile']['tmp_name'], $target);
	} else {
		$result = move_uploaded_file($_FILES['importFile']['tmp_name'], $target);
	}
	if ($result) {
		$message = $jLang['_ADMIN_IMPORT_UPLOAD_SUCCESS'];
	} else {
		$message = $jLang['_ADMIN_IMPORT_UPLOAD_FAILURE'];
	}
}
mosRedirect('index2.php?option=com_jtips&task=list&module=Import', $message);
?>