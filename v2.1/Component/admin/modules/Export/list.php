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
 * Description: Build the form to download an existing export file or
 * generate a new one
 */

global $mosConfig_absolute_path, $jLang;

require_once('components/com_jtips/modules/Export/list.tmpl.php');

$tpl = new ListMode();

//Make available objects options
$objects = array(
	'' => $jLang['_ADMIN_CONF_NONE'],
	'jBadWord' => $jLang['_ADMIN_EXP_BADWORDS'],
	'jComment' => $jLang['_ADMIN_EXP_COMMENTS'],
	'jGame' => $jLang['_ADMIN_EXP_GAMES'],
	'jHistory' => $jLang['_ADMIN_EXP_HISTORY'],
	'jRound' => $jLang['_ADMIN_EXP_ROUNDS'],
	'jTeam' => $jLang['_ADMIN_EXP_TEAMS'],
	'jSeason' => $jLang['_ADMIN_EXP_SEASONS'],
	'jTipsUser' => $jLang['_ADMIN_EXP_USERS'],
	'jTip' => $jLang['_ADMIN_EXP_TIPS']
);
$objectsOptions = array();
foreach ($objects as $key => $val) {
	$objectsOptions[] = jTipsHTML::makeOption($key, $val);
}
$tpl->selectLists['objects'] = jTipsHTML::selectList($objectsOptions, 'table', "id='table' class='inputbox'", 'value', 'text');

$actionOptions = array(
	jTipsHTML::makeOption('download', $jLang['_ADMIN_DASH_DOWNLOAD']),
	jTipsHTML::makeOption('delete', $jLang['_ADMIN_CONF_COMMENTSACTION_DELETE'])
);
$disableExportButton = 'disabled';

$history = getExportHistory();
$historyOptions = array();
foreach ($history as $item) {
	if (is_int(strpos($item, '.csv'))) {
		$disableExportButton = '';
		break;
	}
}
foreach ($history as $key => $item) {
	$historyOptions[] = jTipsHTML::makeOption($key, $item);
}
$tpl->disableExportButton = $disableExportButton;
$tpl->selectLists['history'] = jTipsHTML::selectList($historyOptions, 'export_history', "$disableExportButton id='export_history' class='inputbox'", 'value', 'text');
$tpl->selectLists['actions'] = jTipsHTML::selectList($actionOptions, 'expAction', "$disableExportButton id='expAction', class='inputbox'", 'value', 'text');


$tpl->display();

?>