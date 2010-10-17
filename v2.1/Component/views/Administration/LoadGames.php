<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 07/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Load a JSON encoded string of game objects for the passed in round
 */

//Can't do this here since this script is loaded with ajax
//jTipsSpoofCheck();

global $database, $mosConfig_absolute_path;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jgame.class.php');

ob_clean();
//ob_start();

if ($round_id = jTipsGetParam($_REQUEST, 'round_id', false)) {
	$jGame = new jGame($database);
	$jGameParams = array(
		'round_id' => $round_id,
		'order' => array(
			'type' => 'order',
			'by' => 'position',
			'direction' => 'asc'
		)
	);
	$jGames = forceArray($jGame->loadByParams($jGameParams));
	die(@json_encode($jGames));
} else {
	die(json_encode(false));
}
?>