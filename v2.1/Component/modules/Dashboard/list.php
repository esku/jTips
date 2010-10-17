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
 * Description: Build the Dashboard layout
 */

global $database, $jLicence, $mainframe;

require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/modules/Dashboard/list.tmpl.php');
require_once('components/com_jtips/utils/update.php');

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Dashboard/Dashboard.js'></script>");
$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsCurrentVersion = '" .getFullVersion(). "';</script>");

$tpl = new ListMode();

if (!jTipsGetParam($_REQUEST, 'nu', 0)) {
	$autoUpgradeResult = autoUpgrade();
	if ($autoUpgradeResult !== false) {
		$message = '';
		if ($autoUpgradeResult == 1) {
			//upgrade success!
			$jTipsVersion = getFullVersion();
			//BUG 262 - AutoUpgrade to version message corrected
			$newVersion = jTipsGetParam($_SESSION, 'jtips_upgraded_version', 'Latest Version');
			$message = 'System Upgraded!';// to ' .$jTipsVersion;
			unset($_SESSION['jtips_upgraded_version']);
		} else if ($autoUpgradeResult == 0) {
			//upgrade not required
		} else if (is_string($autoUpgradeResult)) {
			//display the error message
			//Auto Update Failed: [message]
			$message = 'System Upgrade Failed: ' .$autoUpgradeResult;
		}
		if ($message) {
			mosRedirect('index2.php?option=com_jtips&module=Dashboard&nu=1&task=Validate', $message);
		}
	}
}

$jSeason = new jSeason($database);
$parameters = array (
	'end_time' => array (
		'type' => 'query',
		'query' => "> '" . gmdate('Y-m-d') . "'"
	),
	'start_time' => array (
		'type' => 'query',
		'query' => "< '" . gmdate('Y-m-d') . "'"
	)
);
$jSeasons = forceArray($jSeason->loadByParams($parameters));
$tpl->jSeasons = $jSeasons;

$dashboard = array();
include('components/com_jtips/modules/Dashboard/dashboard.php');
$tpl->menu = $dashboard;
//jdash_HTML::dashboard($jSeasons, $dashboard);
$tpl->display();
?>