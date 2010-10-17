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

global $database;

require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');

ob_clean();

$season_id = jTipsGetParam($_REQUEST, 'season_id', false);
$round_id = jTipsGetParam($_REQUEST, 'round_id', false);


if (empty($season_id)) {
	$js  = "\$('round_id').length = 0;\n";
	$js .= "\$('round_id').options[0] = new Option('--None--', '-1');\n";
	die($js."\$('round_id').disabled = true;");
}
$js = "\$('round_id').length = 0;\n";
$jRound = new jRound($database);
$params = array(
	'season_id' => $season_id
);
$jRounds = $jRound->loadByParams($params);
if (!is_array($jRounds)) {
	if ($jRound->exists()) {
		$jRounds = array($jRound);
	} else {
		$jRounds = array();
	}
}
$i = $selectedIndex = 0;
foreach ($jRounds as $jr) {
	$js .= "\$('round_id').options[$i] = new Option('" .$jr->round. "', '" .$jr->id. "');\n";
	if ($round_id == $jr->id) {
		$selectedIndex = $i;
	}
	$i++;
}
$js .= "\$('round_id').disabled = false;";
$js .= "\$('round_id').selectedIndex = $selectedIndex;";
die($js);
?>