<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_lang, $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $jLicence, $jTips, $jLang, $task, $database, $Itemid;

if (defined('_JEXEC')) {
	$mosConfig_absolute_path = JPATH_SITE;
}
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/logger.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/compat.php');

/**
 * Determine debugging level by request parameter
 */
$debug = jTipsGetParam($_REQUEST, 'debug', false);
if ($debug == 1) {
	error_reporting(E_ALL);
}

/**
 * Load the current user
 */
$my = $mainframe->getUser();

/**
 * Load requried classes
 */
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/helper.php');
if (jTipsFileExists($mosConfig_absolute_path.'/administrator/components/com_jtips/config.jtips.php')) {
	include_once($mosConfig_absolute_path.'/administrator/components/com_jtips/config.jtips.php');
}
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/lib/class.curl.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/utils/functions.inc.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/utils/timedate.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/utils/version.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/utils/licence.php');
require_once($mosConfig_absolute_path . '/administrator/includes/pageNavigation.php');

/**
 * Setup html front end
 */
require_once($mainframe->getPath('front_html', 'com_jtips'));

/**
 * Setup default language (English) and
 * include selected language
 */
// BUG 335 - do not redeclare the mosConfig_lang var
//$mosConfig_lang = $mainframe->getCfg('lang');
include_once($mosConfig_absolute_path.'/administrator/components/com_jtips/i18n/master.php');
if (file_exists($mosConfig_absolute_path.'/administrator/components/com_jtips/i18n/'.$mosConfig_lang.'.php')) {
	$masterLanguage = $jLang;
	include_once($mosConfig_absolute_path.'/administrator/components/com_jtips/i18n/'.$mosConfig_lang.'.php');
	// BUG 333 - merge the master lanuage array with the edited one
	$jLang = array_merge($masterLanguage, $jLang);
}

$jLicence = new jTipsLicence();

/**
 * Make sure we are including javascript libraries
 * and the select template (stylesheet)
 */
$stylesheet = jTipsGetParam($jTips, 'template', 'default');

//set the language vars for use in the js scripts
//$jLangJSON = json_encode($jLang);
//$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsLang = " .$jLangJSON. ";</script>");

//BUG 265 - better javascript language definitions
writeJavascriptLanguage();
$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/language.js'></script>");

//This should speed things up a fraction
if ($jTips['LoadMooTools'] == 1 or !isset($jTips['LoadMooTools'])) {
	$mainframe->addCustomHeadTag("<script src='" .$mosConfig_live_site. "/components/com_jtips/js/mootools.js' type='text/javascript'></script>");
}

//so we can determine which version of joomla from javascript - for using built-in functions
//$mainframe->addCustomHeadTag("<script type='text/javascript'>var isJoomla15 = " .@json_encode(isJoomla15()). ";</script>");
$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsItemid = '" .$Itemid. "';</script>");

/**
 * @deprecated 2.1 - 25/11/2008
 * Sortables are no londer needed
 */
//$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/SortableOrder.js'></script>");
$mainframe->addCustomHeadTag("<script type='text/javascript'>
		var jTipsShowTipsHeight = " .$jTips['ShowTipsHeight']. ";
		var jTipsShowTipsWidth = " .$jTips['ShowTipsWidth']. ";
		var isJoomla15 = " .@json_encode(isJoomla15()). ";
		</script>");
$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/Popup.js'></script>");
if (!isJoomla15()) {
	$mainframe->addCustomHeadTag("<link rel='stylesheet' href='" .$mosConfig_live_site. "/components/com_jtips/css/moodalbox.css' type='text/css' media='screen' />");
}

if (jTipsGetParam($_REQUEST, 'menu', 1)) {
	//force echo here so we load after the template, to avoid being overridden
	echo "<LINK REL='styleSheet' HREF='" .$mosConfig_live_site. "/components/com_jtips/css/jtips-{$stylesheet}.css' TYPE='text/css' MEDIA='screen'>";
}

//declare the path root for javascript popups
$mainframe->addCustomHeadTag("<script type='text/javascript'>var jTipsSitePath = '" .$mosConfig_live_site. "/';</script>");

//handle the built-in popups
if (isJoomla15()) {
	JHTML::_('behavior.mootools');
	JHTML::_('behavior.modal');
}

$Itemid = jTipsGetParam( $_REQUEST, 'Itemid', null );
$task = jTipsGetParam( $_REQUEST, 'view', '' );
$view = jTipsGetParam( $_REQUEST, 'view', '' );
if (!$view) $view = 'Dashboard'; //set a default, just in case

// BUG 340 - No Component Title
/**
 * @deprecated 2.1.10
 * replaced with jtips_HTML::title();
 */
if (!empty($jTips['Title']) and false) {
	?>
	<h1 class='componentheading jmain_heading'><?php echo $jTips['Title']; ?></h1>
	<?php
}

if (jTipsGetParam($_REQUEST, 'tmpl') != 'component') {
	// show the page title
	jtips_HTML::title();
}

jTips_HTML::expired();

/*
 * Load the current user object
 */
global $jTipsCurrentUser, $mainframe;
$jTipsCurrentUser	= new jTipsUser($database);
if (jTipsGetParam($_REQUEST, 'user_id', false)) {
	$jTipsCurrentUser->load(jTipsGetParam($_REQUEST, 'user_id', false));
} else {
	$my =& $mainframe->getUser();
	$parameters = array(
		'season_id' => getSeasonID(),
		'user_id' => $my->id
	);
	//BUG 219 - simplify user loading
	// BUG 348 - when somehow multiple users exist for the same competition, load only one.
	$usersArray = forceArray($jTipsCurrentUser->loadByParams($parameters));
	if (!count($usersArray)) {
		$jTipsCurrentUser->loadByParams($parameters);
	} else {
	$jTipsCurrentUser = array_shift($usersArray);
	}

	unset($parameters);
}
if (jTipsGetParam($_REQUEST, 'menu', 1)) {
	$_REQUEST['season'] = $jTipsCurrentUser->season_id;
	$_GET['season'] = $jTipsCurrentUser->season_id;
}

$task = $task ? $task : 'Dashboard';
$view = jTipsGetParam($_REQUEST, 'view', 'Dashboard');
if (!$view) $view = 'Dashboard'; //set a default, just in case

//Show the menu!
if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/views/Menu/build.php') and jTipsGetParam($_REQUEST, 'menu', 1) and getSeasonID()) {
	include($mosConfig_absolute_path. '/components/com_jtips/views/Menu/build.php');
}
$include_file = $mosConfig_absolute_path. "/components/com_jtips/views/" .$view. "/" .jTipsGetParam($_REQUEST, 'action', 'build'). ".php";
jTipsLogger::_log("looking for module file at: " .$include_file, 'INFO');

//BUG 289 - check if we are running the MailMan
if (basename($_SERVER['SCRIPT_FILENAME']) == 'index2.php' and (jTipsGetParam($_REQUEST, 'action', '') == 'MailMan' or jTipsGetParam($_REQUEST, 'rid', false))) {
	$bypassSeasonCheck = true;
} else {
	$bypassSeasonCheck = false;
}

if (jTipsFileExists($include_file) and (getSeasonID() or $bypassSeasonCheck)) {
	jTipsLogger::_log('Executing script ' .jTipsGetParam($_REQUEST, 'action', 'build'), 'INFO');
	include($include_file);
} else {
	switch ($task) {
		default:
			jTipsLogger::_log('fell through all possible cases in jtips.php for view = ' .$view. '; action=' .jTipsGetParam($_REQUEST, 'action'). '; task=' .$task. ' and season_id=' .getSeasonID(), 'ERROR');
			echo "<span class='error'>View or Competition not found! Aborting routine.</span>";
			break;
	}
}

////////////////////////////////
///	BUG 77 - IE Incompatibility
///	Moved the moodalbox script after DOM has loaded
if (!isJoomla15()) {
	echo "<script src='" .$mosConfig_live_site. "/components/com_jtips/js/moodalbox.js' type='text/javascript'></script>";
}
///	END BUG 77
///////////////////////////////

//lets show the logo at all times!
if (jTipsGetParam($_REQUEST, 'menu', 1)) {
	jtips_HTML::licence();
}

?>