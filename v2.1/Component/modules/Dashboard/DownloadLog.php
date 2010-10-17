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

global $mosConfig_absolute_path;
$filename = $mosConfig_absolute_path. '/components/com_jtips/jtips.log';
ob_end_clean();
header("Pragma: public"); // required
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false); // required for certain browsers 
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Content-Disposition: inline; filename=\"" .basename($filename). "\";");
header("Content-Transfer-Encoding: binary");
header('Content-Length: ' . filesize($filename));
@readfile($filename) or die("Cannot read file: $filename");
ob_start();
?>