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
 * Description: 
 */

global $mainframe;

require_once('components/com_jtips/modules/Customisations/edit.tmpl.php');

$ids = jTipsGetParam($_REQUEST, 'cid', array());
if (empty($ids)) {
	//mosRedirect('index2.php?option=com_jtips&module=Customisations', $jLang['_ADMIN_CSTM_NO_FILE_SPECIFIED']);
}
$id = array_shift($ids);


$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/lib/edit_area/edit_area_full.js'></script>");
$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Customisations/Edit.js'></script>");

$tpl = new EditMode();

if ($id or $id === '0') {
	$path = $mosConfig_absolute_path. '/components/com_jtips/custom/';
	$files = array();
	$fileList = findAllFiles($path, $files);
	
	$file = $fileList[$id];
	$tpl->path = $file;
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		$tpl->content = JFile::read($file);
	} else {
		$tpl->content = file_get_contents($file);
	}
} else {
	$tpl->path = $tpl->content = '';
	
	$viewOptions = array(
		jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']),
		jTipsHTML::makeOption('Administration', 'Administration'),
		jTipsHTML::makeOption('CompetitionLadder', 'Competition Ladder'),
		jTipsHTML::makeOption('Dashboard', 'Dashboard'),
		jTipsHTML::makeOption('Menu', 'Menu'),
		jTipsHTML::makeOption('TeamLadder', 'Team Ladder'),
		jTipsHTML::makeOption('Tips', 'Tips Panel'),
		jTipsHTML::makeOption('UserPreferences', 'User Preferences')
	);
	$tpl->viewList = jTipsHTML::selectList($viewOptions, 'view', "class='inputbox'", 'value', 'text');
	
	//$tpl->displayFileSetup();
}


$tpl->display();
?>