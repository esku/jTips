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
 * Description: Load a JSON encoded array of teams
 */

global $database, $mosConfig_absolute_path;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');

ob_clean();

if ($season_id = jTipsGetParam($_REQUEST, 'season_id', false)) {
	$params = array(
		'season_id' => $season_id,
		'order' => array(
			'type' => 'order',
			'by' => 'name',
			'direction' => 'ASC'
		)
	);
	$jTeam = new jTeam($database);
	$jTeams = forceArray($jTeam->loadByParams($params));
	die(@json_encode($jTeams));
} else {
	die(json_encode(false));
}
?>