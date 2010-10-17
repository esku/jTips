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

if (!class_exists('jSeason')) {
	require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');
}

ob_clean();

$season_id = jTipsGetParam($_REQUEST, 'season_id', false);
if (!$season_id) {
	die(@json_encode(new stdClass));
}

$jSeason = new jSeason($database);
$jSeason->load($season_id);
//$season = json_encode($jSeason);
foreach ($jSeason as $key => $val) {
	if (!property_exists($jSeason, $key) or substr($key, 0, 1) == '_') {
		unset($jSeason->$key);
	}
}
//suppress errors for unsupported types
die(@json_encode($jSeason));
?>