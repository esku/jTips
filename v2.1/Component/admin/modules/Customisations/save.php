<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 23/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Write file
 */

jTipsSpoofCheck();

global $mosConfig_absolute_path;

$file = jTipsGetParam($_REQUEST, 'path', '');

if (!$file) {
	//create a new file
	$file = $mosConfig_absolute_path. '/components/com_jtips/custom/views/' .jTipsGetParam($_REQUEST, 'view', 'DEFAULT');
	if (jTipsGetParam($_REQUEST, 'tmpl', false)) {
		$file .= '/tmpl';
	}
	$filename = jTipsGetParam($_REQUEST, 'filename', 'default.ext.php');
	//clean the filename
	$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
	$file .= '/' .preg_replace($regex, '', $filename);
}


if (jTipsGetParam($_FILES['fileupload'], 'tmp_name', false)) {
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		$result = JFile::upload($_FILES['fileupload']['tmp_name'], $file);
	} else {
		$result = move_uploaded_file($_FILES['fileupload']['tmp_name'], $file);
	}
} else {
	$content = jTipsGetParam($_REQUEST, 'filecontent', '');
	$result = writeFile($file, jTipsStripslashes($content));
}


if ($result) {
	$message = $jLang['_ADMIN_CSTM_SAVE_SUCCESS'];
} else {
	$message = $jLang['_ADMIN_CSTM_SAVE_FAILURE'];
}
mosRedirect('index2.php?option=com_jtips&module=Customisations', $message);
?>