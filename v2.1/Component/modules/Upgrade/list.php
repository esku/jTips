<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.0.14
 * @version 2.1
 * @package jTips
 * 
 * Description: list jTips files and directories that cannot be written to
 */

global $database, $jTips, $mosConfig_absolute_path;

$header = array(
	$jLang['_ADMIN_UPGRADER_TYPE'],	
	$jLang['_ADMIN_UPGRADER_LOCATION'],
	$jLang['_ADMIN_UPGRADER_PERMISSIONS'],
	$jLang['_ADMIN_UPGRADER_OCTAL'],
	$jLang['_ADMIN_UPGRADER_OWNER']
);

$formData = array(
	'title' => $jLang['_ADMIN_UPGRADER_TITLE'],
	'editTask' => '',
	'module' => 'Upgrade',
	'icon' => 'install'
);

$files = filesWritable(true);
$data = array();
$index = 0;
if (is_array($files) and !empty($files)) {
	foreach ($files as $f) {
		$file = $mosConfig_absolute_path. '/' .$f;
		$owner = posix_getpwuid(fileowner($file));
		if (is_dir($file)) {
			$img = 'sections.png';
			$title = $jLang['_ADMIN_UPGRADER_DIR_TITLE'];
			$tip = $jLang['_ADMIN_UPGRADER_DIR_INFO'];
		} else {
			$img = 'document.png';
			$title = $jLang['_ADMIN_UPGRADER_FILE_TITLE'];
			$tip = $jLang['_ADMIN_UPGRADER_FILE_INFO'];
		}
		$data[$index] = array(
			jTipsToolTip($tip, $title, '', $img),
			$f,
			getFilePermissions($file),
			substr(sprintf('%o', fileperms($file)), -3),
			$owner['name']
		);
		$index++;
	}
}

jTipsAdminDisplay::ListView($formData, $header, $data, null, '', null, $jLang['_ADMIN_UPGRADER_LIST_INFO']);
?>