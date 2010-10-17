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
 * Description: Build the Configuration form
 */

global $mainframe;

require_once('components/com_jtips/modules/Configuration/list.tmpl.php');

$tpl = new ListMode();

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Configuration/Configuration.js'></script>");

$configpath = 'components/com_jtips/config.jtips.php';
$configuration = array();
include('components/com_jtips/modules/Configuration/configuration.jtips.php');
$tpl->configuration = $configuration;

$tpl->display();
?>