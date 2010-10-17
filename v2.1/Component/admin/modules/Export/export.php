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
 * Description: 
 */

global $database;

$class = jTipsGetParam($_REQUEST, 'table', 'jSeason');

//BUG 284 - class jtipsuser has no matching file
$file = $class;
if (strtolower($file) == 'jtipsuser') {
	$file = 'juser';
}

$classFile = 'components/com_jtips/classes/' .strtolower($file). '.class.php';
if (!file_exists($classFile)) {
	jTipsLogger::_log('Failed to export object of type ' .$file. ' - class file not found!', 'ERROR');
	mosRedirect('index2.php?option=com_jtips&task=list&module=Export', 'Export Failed');
} else {
	require_once($classFile);
}

$jObj = new $class($database);
$report = $jObj->export();
$filename = "components/com_jtips/exports/{$class}_" .TimeDate::getMySQLDate(). ".csv";
jTipsLogger::_log('exporting file for ' .$class. ' to target file: ' .$filename);
//jTipsDebug($report);
//die();
if (writeFile($filename, $report)) {
	ob_end_clean();
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header('Content-Description: File Transfer');
	header('Content-Type: application/vnd.ms-excel');
	header("Content-Disposition: inline; filename=\"" .basename($filename). "\";");
	header("Content-Transfer-Encoding: binary");
	header('Content-Length: ' . filesize($filename));
	@readfile($filename) or die("Cannot read file: $filename");
	ob_start();
	mosRedirect('index2.php?option=com_jtips&task=list&module=Export');
} else {
	mosRedirect('index2.php?option=com_jtips&task=list&module=Export', 'Failed to write to file');
}
?>