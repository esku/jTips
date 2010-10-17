<?php
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $jLicence, $jTips, $jLang, $mosConfig_lang, $task;

require_once('components/com_jtips/utils/logger.php');
require_once('components/com_jtips/utils/compat.php');

$debug = jTipsGetParam($_REQUEST, 'debug', false);
if ($debug == 1) {
	error_reporting(E_ALL);
}

require_once('components/com_jtips/utils/helper.php');
require_once('components/com_jtips/utils/functions.inc.php');
require_once('components/com_jtips/lib/class.curl.php');
require_once('components/com_jtips/utils/timedate.php');
require_once('components/com_jtips/utils/version.php');
require_once('components/com_jtips/utils/licence.php');
require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );

$timedate = new TimeDate();

$jLicence = new jTipsLicence();
if (!isJoomla15()) {
	//inclide the sef so that urls in the config can be parsed correctly
	include_once($mosConfig_absolute_path. '/includes/sef.php');
}
//include ($mosConfig_absolute_path . '/administrator/components/com_jtips/i18n/master.php');
//if (jTipsFileExists($mosConfig_absolute_path . '/administrator/components/com_jtips/i18n/' . $mosConfig_lang . '.php')) {
//	$masterLanguage = $jLang;
//	include ($mosConfig_absolute_path . '/administrator/components/com_jtips/i18n/' . $mosConfig_lang . '.php');
//	// BUG 333 - merge the master lanuage array with the edited one
//	$jLang = array_merge($masterLanguage, $jLang);
//}
// load the default language
loadLanguage();
if (jTipsFileExists($mosConfig_absolute_path. '/administrator/components/com_jtips/config.jtips.php')) {
	include ($mosConfig_absolute_path. '/administrator/components/com_jtips/config.jtips.php');
}

//remove excess slashes from language vars
foreach ($jLang as $key => $val) {
	$jLang[$key] = stripslashes($val);
}


//set the language vars for use in the js scripts
//$jLangJSON = @json_encode($jLang);
//$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsLang = " .$jLangJSON. ";</script>");

//BUG 265 - better javascript language definitions
writeJavascriptLanguage();
$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/language.js'></script>");

$mainframe->addCustomHeadTag("<script type='text/javascript'>var isJoomla15 = " .@json_encode(isJoomla15()). ";</script>");

//This should speed things up a fraction
if ($jTips['LoadMooTools'] == 1 or !isset($jTips['LoadMooTools']) or (defined('_VALID_MOS') and !defined('_JEXEC'))) {
	$mainframe->addCustomHeadTag('<script type="text/javascript" src="' .$mosConfig_live_site. '/components/com_jtips/js/mootools.js"></script>');
}
// BUG 378 - try to assign the task variable if not already defined
if (!$task) $task = jTipsGetParam($_REQUEST, 'task', 'list');
if (isJoomla15()) {
	// Make sure the user is authorized to view this page
	$user = & JFactory::getUser();
	$acl    = & JFactory::getACL();
	$aclAction = (!empty($task) ? $task : 'list');
	//die($aclAction);
	// Fudge ACL for Administrators
	$acl->addACL( 'com_jtips', $aclAction, 'users', 'super administrator' );
	$acl->addACL( 'com_jtips', $aclAction, 'users', 'administrator' );
	// Uncomment to allow Manager access
	//$acl->addACL( 'com_jce', $task, 'users', 'manager' );
	if (!$user->authorize( 'com_jtips', $aclAction )) {
		$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
	}

	//tidy up from upgrade and make compatible
	if (jTipsFileExists($mosConfig_absolute_path. '/administrator/components/com_jtips/jtips.xml') and jTipsFileExists($mosConfig_absolute_path. '/administrator/components/com_jtips/installer.xml')) {
		jimport('joomla.filesystem.file');
		JFile::delete($mosConfig_absolute_path. '/administrator/components/com_jtips/jtips.xml');
	}
} else {
	// ensure user has access to this function
	if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' )| $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_jtips' ))) {
		mosRedirect( 'index2.php', _NOT_AUTH );
	}

	//add the additional class to anything that is admintable
	$script = <<<EOF
	<script type="text/javascript">
		window.addEvent('domready', function() {
			$$('.admintable').addClass('adminform');
		});
	</script>
EOF;
	$mainframe->addCustomHeadTag($script);
}

require_once( $mainframe->getPath( 'admin_html', 'com_jtips' ) );

$mainframe->addCustomHeadTag('<link rel="stylesheet" type="text/css" href="components/com_jtips/css/default.css" />');

//BUG 255 - Determine if the buffer should be force-cleared
if (basename($_SERVER['SCRIPT_NAME']) == 'index3.php') {
	ob_clean();
} else {
	// only do this if we are not in an AJAX request
	/*
	 * Check the validity of the Licence data
	 */
	if (!$jLicence->checkValidation()) {
		$jLicence->revalidate();
	}
	if (!$jLicence->checkValidation()) {
		$error = $jLicence->getValidationError();
		if ($error !== false) {
			if (isJoomla15()) {
				$mainframe->enqueueMessage($error, 'error');
			} else {
				echo "<div class='message'>$error</div>";
			}
		}
	}
	/*
	 * End Licence check
	 */
}

$include_file = "components/com_jtips/modules/" .jTipsGetParam($_REQUEST, 'module', 'Dashboard'). "/" .jTipsGetParam($_REQUEST, 'task', 'list'). ".php";
jTipsLogger::_log("looking for include file: $include_file");
if (jTipsFileExists($include_file)) {
	jTipsLogger::_log("found $include_file... running script");
	include($include_file);
} else {
	jTipsLogger::_log('failed to find include file! returning to dashboard', 'ERROR');
	mosRedirect('index2.php?option=com_jtips');
}
?>
