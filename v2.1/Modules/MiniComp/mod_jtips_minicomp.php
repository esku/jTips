<?php
defined('_JEXEC') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 1.0 - 13/07/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: 
 */

require_once(dirname(__FILE__).DS.'helper.php');

$season = $params->get('season', 0);
$rounds = $params->get('rounds', 0);
$show_detail = $params->get('show_detail', 1);

$user['name'] = $params->get('name', 'username');
$user['social'] = $params->get('link', '');
$user['avatar'] = $params->get('avatar', 0);

$who =& modJTipsMiniCompHelper::getUser($season, $rounds, $show_detail);
require(JModuleHelper::getLayoutPath('mod_jtips_minicomp'));
