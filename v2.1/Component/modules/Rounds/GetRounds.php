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
 * Description: Load javascript to generate a list of rounds as options in a dropdown
 */

global $database;

require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');

ob_clean();

$season_id = jTipsGetParam($_REQUEST, 'season_id', '');
$round_id = jTipsGetParam($_REQUEST, 'round_id', '');

if (empty($season_id)) {
	$js  = "\$('roundnum').length = 0;\n";
	$js .= "\$('roundnum').options[0] = new Option('--None--', '-1');\n";
	die($js."\$('roundnum').disabled = true;");
}
$jSeason = new jSeason($database);
$jSeason->load($season_id);
$js = "\$('roundnum').length = 0;\n";
$current = false;
for ($i=1; $i<=$jSeason->rounds; $i++) {
	$js .= "\$('roundnum').options[" .($i-1). "] = new Option('$i', '$i');\n";
}
$js .= "\$('roundnum').disabled = false;";
$jRound = new jRound($database);
if (is_numeric($round_id)) {
	$jRound->load($round_id);
	$roundid = $jRound->round - 1;
} else {
	//$roundid = $jSeason->getCurrentRound();
	//$jRound->load($roundid);
	$roundid = $jSeason->getNextLogicalRound()-1;
}
if (!$roundid || $roundid < 0) {
	$roundid = 0;
}
if (empty($roundid)) {
	$roundid = '0';
}
$js .= "\n\$('roundnum').selectedIndex = '$roundid';";
die($js);
?>