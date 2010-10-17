<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 02/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: build a paginated list of customisation files
 */

global $jLang, $database, $mosConfig_absolute_path, $mainframe;

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Customisations/List.js'></script>");

$formData = array(
	'title' => $jLang['_ADMIN_DASH_CUSTOMISATION_LIST'],
	'editTask' => 'edit',
	'module' => 'Customisations',
	'icon' => 'customisations'
);

$currentDir = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
if ($currentDir == 'asc') {
	$dir = 'desc';
} else {
	$dir = 'asc';
}
//The header row
$header = array(
	'',
	'File Path',
	'Permissions',
	'Owner'
);

$path = $mosConfig_absolute_path. '/components/com_jtips/custom/';
$data = $files = array();
if (file_exists($path)) {
	$fileList = findAllFiles($path, $files);
	$index = 0;
	foreach ($fileList as $file) {
		$owner = posix_getpwuid(fileowner($file));
		$data[] = array(
			makeListLink(str_replace($path, '', $file), $index++),
			getFilePermissions($file),
			$owner['name']
		);
	}
}

jTipsAdminDisplay::ListView($formData, $header, $data, null, '', null, $jLang['_ADMIN_CUSTOMISATIONS_INFO']);
?>