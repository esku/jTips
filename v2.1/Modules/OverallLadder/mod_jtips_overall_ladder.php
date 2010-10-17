<?php
defined('_JEXEC') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 1.0 - 30/04/2009
 * @version 1.0.0
 * @package jTips
 *
 * Description: Build the layout for the ladder
 */

require_once (dirname(__FILE__).DS.'helper.php');

// set default options
$params->def('display', 'username');
$params->def('field', 'points');
$params->def('limit', 10);
$params->def('order', 'DESC');
$params->def('link', '');
$params->def('avatar', 0);

// get all the users for the current competitions
$users = modJTipsOverallLadderModule::getList($params);

// can be used later to allow different layout options in xml parameters - Not currently used
$layout = $params->get('layout', 'default');

$layout	= JFilterInput::clean($layout, 'word');
$path	= JModuleHelper::getLayoutPath('mod_jtips_overall_ladder', $layout);
if (file_exists($path)) {
	require($path);
}