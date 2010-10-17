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
 * Description: Build the CSS file editor
 */
/*
global $mosConfig_absolute_path;

require_once('components/com_jtips/modules/Styles/list.tmpl.php');

$tpl = new ListMode();

$path = $mosConfig_absolute_path. '/components/com_jtips/css/jtips-default.css';

if (isJoomla15()) {
	jimport('joomla.filesystem.file');
	$css = JFile::read($path);
} else {
	$css = file_get_contents($path);
}
$tpl->css = jTipsStripslashes($css);

$tpl->display();
*/

global $jLang;

$formData = array(
	'title' => $jLang['_ADMIN_CSS_TITLE'],
	'editTask' => 'edit',
	'module' => 'Styles',
	'icon' => 'css'
);

$header = array(
	'',
	'Filename',
	'Date Modified',
	$jLang['_ADMIN_UPGRADER_PERMISSIONS']
);

global $mosConfig_absolute_path;

if (isJoomla15()) {
	$dir = $mosConfig_absolute_path.DS.'components'.DS.'com_jtips'.DS.'css'.DS;
	jimport('joomla.filesystem.folder');
	$files = JFolder::files($dir);
} else {

	$dir = $mosConfig_absolute_path.'/components/com_jtips/css/';
	$files = findAllFiles($dir);
}

// get the key of the index.html file
$flipped = array_flip($files);
unset($files[$flipped['index.html']]);
sort($files);

$data = array();
$index = 0;
if (is_array($files) and !empty($files)) {
	foreach ($files as $f) {
		$file = $mosConfig_absolute_path.$f;
		$data[$index] = array(
			makeListLink($f, $index),
			date('Y-m-d H:i:s', filemtime($dir.$f)),
			getFilePermissions($dir.$f)
		);
		$index++;
	}
}
jTipsAdminDisplay::ListView($formData, $header, $data, null);
?>
