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

global $database, $mosConfig_absolute_path;

ob_clean();

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');

$element_id		= jTipsGetParam($_REQUEST, 'element', '');
$season_id		= jTipsGetParam($_REQUEST, 'season_id', null);

$preselected	= jTipsGetParam($_REQUEST, 'round_id', false);
if (empty($season_id) or !isset($preselected)) {
	$js  = "document.getElementById('$element_id').length = 0;\n";
	$js .= "document.getElementById('$element_id').options[0] = new Option('--None--', '-1');\n";
	die($js."document.getElementById('$element_id').disabled = true;");
}
$jSeason = new jSeason($database);
$jSeason->load($season_id);
$curr = $jSeason->getLatestRound();
$jRound = new jRound($database);
$parameters = array(
	'season_id' => $season_id,
	'scored' => 1
);

$jRounds = forceArray($jRound->loadByParams($parameters));
$js = "document.getElementById('$element_id').length = 0;\n";
$selected = 0;
$roundsArr = array();
for ($i=0; $i<count($jRounds); $i++) {
	if ($jRounds[$i]->id == $curr and !$preselected) {
		$selected = $i;
	}
	$js .= "document.getElementById('$element_id').options[$i] = new Option('" .$jRounds[$i]->round. "', '" .$jRounds[$i]->id. "');\n";
	$roundsArr[$jRounds[$i]->id] = $i;
}
if ($preselected and isset($roundsArr[$preselected])) {
	$selected = $roundsArr[$preselected];
}
if (!empty($roundsArr)) {
	$js .= "document.getElementById('$element_id').disabled = false;\n";
} else {
	$js .= "document.getElementById('$element_id').options[0] = new Option('--None--', '-1');\n";
	$js .= "document.getElementById('$element_id').disabled = true;\n";
}
$js .= "document.getElementById('$element_id').selectedIndex = $selected;";
die($js);
?>