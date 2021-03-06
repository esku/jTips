<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 08/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Download or delete a previously exported file
 */
jTipsSpoofCheck();

global $mosConfig_absolute_path;

$file = jTipsGetParam($_REQUEST, 'export_history', null);
$action = jTipsGetParam($_REQUEST, 'expAction', 'download');

$path = $mosConfig_absolute_path.'/administrator/components/com_jtips/exports/';
$filename = $path.$file;

if (!jTipsFileExists($filename)) {
	mosRedirect('index2.php?option=com_jtips&task=list&module=Export', 'File not found');
}
$message = '';
if ($action == 'download') {
	ob_end_clean();
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header('Content-Description: File Transfer');
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: inline; filename=\"".basename($filename)."\";");
	header("Content-Transfer-Encoding: binary");
	header('Content-Length: ' . filesize($filename));
	@readfile($filename) or die("Cannot read file: $filename");
	ob_start();
} else {
	if (isJoomla15()) {
		JFile::delete($filename);
	} else {
		unlink($filename);
	}
	$message = 'File deleted';
}

mosRedirect('index2.php?option=com_jtips&task=list&module=Export', $message);

?>