<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/pilot.php');

function getVersionNum() {
	jTipsLogger::_log('checking current software version', 'INFO');
	$jPilot = new jPilot();
	return $jPilot->getVersion();
}

function getFullVersion() {
	jTipsLogger::_log('checking current software version, with build', 'INFO');
	$jPilot = new jPilot();
	$build = $jPilot->getBuild();
	return $jPilot->getVersion(). '.' .$build;
}

function getLatestVersion() {
	jTipsLogger::_log('Checking for latest version', 'INFO');
	if (!extension_loaded('curl')) {
		jTipsLogger::_log('curl not loaded in php', 'ERROR');
		return 'N/A';
	}
	$release = str_replace('.', '', getFullVersion());
	$curl = new jTipsCurl("http://www.jtips.com.au/update.php?version=10&release=$release");
	$curl->setopt(CURLOPT_CONNECTTIMEOUT, 10);
	$result = $curl->exec();
	if ($theError = $curl->hasError()) {
		return $theError;
		jTipsLogger::_log($result, 'ERROR');
	}
	$curl->close();
	$response = unserialize(base64_decode($result));
	return !empty($response) ? $response : "N/A";
}
?>
