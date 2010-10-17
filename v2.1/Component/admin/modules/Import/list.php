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
 * Description: Allow file upload and import of data
 */
global $mosConfig_absolute_path, $mainframe;

require_once('components/com_jtips/modules/Import/list.tmpl.php');

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Import/Import.js'></script>");

$tpl = new ListMode();

$file = $mosConfig_absolute_path . '/administrator/components/com_jtips/import.csv';
$tpl->importExists = jTipsFileExists($file);

$objects = array(
	'' => $jLang['_ADMIN_CONF_NONE'],
	'jBadWord' => $jLang['_ADMIN_EXP_BADWORDS'],
	'jGame' => $jLang['_ADMIN_EXP_GAMES'],
	'jRound' => $jLang['_ADMIN_EXP_ROUNDS'],
	'jTeam' => $jLang['_ADMIN_EXP_TEAMS'],
	'jSeason' => $jLang['_ADMIN_EXP_SEASONS']
);
$objectsOptions = array();
foreach ($objects as $key => $val) {
	$objectsOptions[] = jTipsHTML::makeOption($key, $val);
}
$tpl->selectLists['objects'] = jTipsHTML::selectList($objectsOptions, 'importObject', "id='importObject' class='inputbox' onchange='getOptions(this);'", 'value', 'text');

$tpl->noneOptions = array(
	jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE'])
);

$tpl->headers = fetchImportRow();
$tpl->row1Data = fetchImportRow(1);
$tpl->row2Data = fetchImportRow(2);
$tpl->opts = '';

$tpl->display();
?>