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
 * Description: delete selected custom files
 */

//check for hack attempt
jTipsSpoofCheck();

global $mosConfig_absolute_path;

$path = $mosConfig_absolute_path. '/components/com_jtips/custom/';
$files = $results = array();
$fileList = findAllFiles($path, $files);

//$file = $fileList[$id];

foreach (jTipsGetParam($_REQUEST, 'cid', array()) as $fileid) {
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		$results[] = JFile::delete($fileList[$fileid]);
	} else {
		$results[] = unlink($fileList[$fileid]);
	}
}

$message = array_sum($results). ' ' .$jLang['_OUT_OF']. ' ' .count($results). ' ' .$jLang['_ADMIN_CSTM_FILES_DELETED'];
mosRedirect('index2.php?option=com_jtips&module=Customisations', $message);
?>