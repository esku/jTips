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
 * Description: Revalidate the license key
 */

global $jLicence, $jLang;

$data = $jLicence->revalidate(true);
if ($jLicence->writeLicenceFile()) {
	$message = $jLang['_ADMIN_LIC_VALIDATION_SUCCESS'];
} else {
	$message = $jLang['_ADMIN_LIC_WRITE_FAILED'];
}
jTipsLogger::_log('license response type is: ' .gettype($data));
if ($data === false) {
	$message = $jLang['_ADMIN_LIC_VALIDATION_FAILURE'];
} else if (is_string($data)) {
	$message = $data;
}
//die($message);
mosRedirect('index2.php?option=com_jtips', $message);
?>