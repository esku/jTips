<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTips
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 *
 * Description: Compatibility function declerations and global
 * variable definitions.
 */

/**
 * Checks which version of joomla is being used
 */
function isJoomla15() {
	return (function_exists('jimport') and defined('_JEXEC'));
}

/**
 * Initialize some globals
 */
if (isJoomla15()) {
	$lang =& JFactory::getLanguage();
	$GLOBALS['mosConfig_lang']          = $lang->getBackwardLang();

	$config = &JFactory::getConfig();
	$dbtype	= $config->getValue('config.dbtype', 'mysql');
	JLoader::register('database', JPATH_SITE.DS.'plugins'.DS.'system'.DS.'legacy'.DS.$dbtype.'.php');

	$conf =& JFactory::getConfig();
	$GLOBALS['database'] = new database($conf->getValue('config.host'), $conf->getValue('config.user'), $conf->getValue('config.password'), $conf->getValue('config.db'), $conf->getValue('config.dbprefix'));
	$GLOBALS['database']->debug($conf->getValue('config.debug'));

	$GLOBALS['mosConfig_live_site']		= substr_replace(JURI::root(), '', -1, 1);
	$GLOBALS['mosConfig_absolute_path']	= JPATH_SITE;
	$GLOBALS['mosConfig_offset'] = $conf->getValue('config.offset');

	class jTipsDBTable extends JTable {
		function mosDBTable($table, $key, &$db)	{
			parent::__construct( $table, $key, $db );
		}
	}
} else {
	global $mainframe, $mosConfig_live_site;
	$mainframe->addCustomHeadTag("<script type='text/javascript' src='$mosConfig_live_site/components/com_jtips/utils/helper.js'></script>");
	class jTipsDBTable extends mosDBTable {

	}
}

/**
 * Initiate the WYSIWYG editor
 */
function jTipsInitEditor() {
	if (isJoomla15()) {
		$editor =& JFactory::getEditor();
		echo $editor->initialise();
	} else {
		echo initEditor();
	}
}

/**
 * Display the WYSIWYG editor
 */
function jTipsEditorArea($name, $content, $hiddenField, $width, $height, $col, $row) {
	if (isJoomla15()) {
		jimport( 'joomla.html.editor' );
		$editor =& JFactory::getEditor();
		echo $editor->display($hiddenField, $content, $width, $height, $col, $row);
	} else {
		echo editorArea($name, $content, $hiddenField, $width, $height, $col, $row);
	}
}

/**
 * Clean text for display
 *
 * @param &string The string to clean
 *
 * @return string The clean string
 */
function jTipsStripslashes( &$value ){
	if (isJoomla15()) {
		$ret = '';
		if (is_string( $value )) {
			$ret = stripslashes( $value );
		} else {
			if (is_array( $value )) {
				$ret = array();
				foreach ($value as $key => $val) {
					$ret[$key] = jTipsStripslashes( $val );
				}
			} else {
				$ret = $value;
			}
		}
		return $ret;
	} else {
		return mosStripslashes($value);
	}
}

/**
 * Sort an array of objects based on a key
 *
 * @param &array The array
 * @param string The key to sort on
 * @param [int] Sort direction - 1 is ascending, 0 descending
 */
function jTipsSortArrayObjects( &$a, $k, $sort_direction=1 ) {
	if (isJoomla15()) {
		JArrayHelper::sortObjects($a, $k, $sort_direction);
	} else {
		sortArrayObjects($a, $k, $sort_direction);
	}
}

/**
 * send an email
 */
function jTipsMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null ) {
	if (isJoomla15()) {
		$result = JUTility::sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname );
		if (is_bool($result) and $result) {
			return true;
		} else {
			jimport('joomla.error.error');
			$error = JError::getError();
			jTipsLogger::_log('sending failed: ' .$error->code.': ' .$error->message, 'ERROR');
			return false;
		}
	} else {
		return mosMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
	}
}

/**
 * Loads an image with a onhover popup tool tip
 */
function jTipsToolTip ( $tooltip, $title='', $width='', $image='tooltip.png', $text='', $href='', $link=1 ) {
	if (isJoomla15()) {
		static $init;
		if ( ! $init )
		{
			JHTML::_('behavior.tooltip');
			$init = true;
		}

		return JHTML::_('tooltip', $tooltip, $title, $image, $text, $href, $link);
	} else {
		jTipsCommonHTML::loadOverlib();
		return mosToolTip($tooltip, $title, $width, $image, $text, $href, $link);
	}
}

/**
 * Generate a random string
 *
 * @param mixed 1 to use date as salt, null for default or anything else to use as salt
 *
 * @return string The random hash string
 */
function jTipsSpoofValue($alt=null) {
	if (isJoomla15()) {
		global $mainframe;

		if ($alt) {
			if ( $alt == 1 ) {
				$random		= date( 'Ymd' );
			} else {
				$random		= $alt . date( 'Ymd' );
			}
		} else {
			$random		= date( 'dmY' );
		}
		// the prefix ensures that the hash is non-numeric
		// otherwise it will be intercepted by globals.php
		$validate 	= 'j' . JUtility::getHash( $mainframe->getCfg( 'db' ) . $random );

		return $validate;
	} else {
		return josSpoofValue();
	}
}

function jTipsSpoofCheck($header=false, $alternate=null) {
	if (isJoomla15()){
		// Lets make sure they saw the html form
		$check = true;
		$hash	= jTipsSpoofValue($alternate);
		$valid	= JRequest::getBool( $hash, 0, 'post' );
		if (!$valid) {
			$check = false;
		}

		// Make sure request came from a client with a user agent string.
		if (!isset( $_SERVER['HTTP_USER_AGENT'] )) {
			$check = false;
		}

		// Check to make sure that the request was posted as well.
		$requestMethod = JArrayHelper::getValue( $_SERVER, 'REQUEST_METHOD' );
		if ($requestMethod != 'POST') {
			$check = false;
		}

		if (!$check)
		{
			header( 'HTTP/1.0 403 Forbidden' );
			jexit( JText::_('E_SESSION_TIMEOUT') );
		}
	} else {
		josSpoofCheck();
	}
}

/**
 * Build a URL based on SEF if enabled
 *
 * @param string The normal url to redirect to
 * @param bool True to change & to &amp; False for internal redirects
 *
 * @return string The formatted url
 */
function jTipsRoute($url, $xhtml=true) {
	if (isJoomla15()) {
		return JRoute::_($url, $xhtml);
	} else {
		return sefRelToAbs($url);
	}
}

/**
 * Determines if the file (argument) exists on the filesystem
 *
 * @param string The path to the file
 *
 * @return bool True if exists, False otherwise
 */
function jTipsFileExists($path) {
	jTipsLogger::_log("looking for file at: " .$path, 'INFO');
	//BUG 294 - Check for directory navigation attack
	if (preg_match('/\.\./', $path)) {
		$error_msg = 'Hack attempt denied at location: ' .$path;
		jTipsLogger::_log($error_msg, 'ERROR');
		if (isJoomla15()) {
			jimport('joomla.error.error');
			JError::raiseError('294', 'Directory navigation attack denied', $error_msg);
		} else {
			die($error_msg);
		}
	} else {
		if (isJoomla15()) {
			// BUG 310 - check for existence for folder as well as file
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');
			return (JFile::exists($path) or JFolder::exists($path));
		} else {
			return file_exists($path);
		}
	}
}

/**
 * Use the correct getParam function if we are using J1.5 in non-legacy mode
 *
 * @param array The source of the data
 * @param string The key to look for in the array
 * @param [mixed] The default type to return
 *
 * @return mixed The value in the array at the index defined by the key
 */
function jTipsGetParam(&$array, $key, $default=null) {
	if (isJoomla15()) {
		$val = JArrayHelper::getValue( $array, $key, $default, '' );
		if (is_numeric($val)) {
			settype($val, 'double');
			return $val;
		} else if (is_string($val)) {
			return trim($val);
		} else {
			return $val;
		}
	} else {
		return mosGetParam($array, $key, $default);
	}
}

/**
 * Use the correct redirect if using J1.5
 * Used for the jTips admin area
 *
 * @param string the URL to redirect to
 * @param [string] A message to display after redirect
 */
if (!function_exists('mosRedirect')) {
	function mosRedirect($url, $msg='') {
		//jTipsRedirect($url, $msg);
		global $mainframe;
		$mainframe->redirect($url, $msg);
	}
}

/**
 * Redirect function for front-end redirects
 * Makes sure the redirect URL is absolute to avoid any
 * possible conflicts with SEF - See BUG 300
 *
 * @since 2.1.7 - 10/02/2009
 * @param string the URL to redirect to
 * @param [string] A message to display after redirect
 */
function jTipsRedirect($url, $msg='') {
    global $mainframe, $mosConfig_live_site;
	// BUG 300 - Without a full URL, some SEF configurations cause infinite redirect
	if (!isJoomla15() and !stristr($url, $mosConfig_live_site)) {
	    $url = $mosConfig_live_site.'/'.$url;
	    //$url = preg_replace('/([^:])\/\//', '\1/'.$url); // not sure what this does anymore
	}
	$url = jTipsRoute($url, false);
	if (isJoomla15()) {
	    $mainframe->redirect($url, $msg);
	} else {
	    mosRedirect($url, $msg);
	}
}

function jTipsGetModuleCount($position) {
	if (isJoomla15()) {
		return count(JModuleHelper::getModules($position));
	} else {
		return mosCountModules($position);
	}
}

function jTipsRenderModules($position) {
	if (isJoomla15()) {
		$modules = JModuleHelper::getModules($position);
		//jTipsDebug("COUNT: " .(count($modules)));
		$width = floor(100/count($modules));
		$html = array();
		foreach ($modules as $module) {
			$html[] = "<div style='width:$width%;float:left;'><div style='margin:1px;'>" .JModuleHelper::renderModule($module). "</div></div>";
			//echo "<div class='moduletable' style='float:left;width:$width%;'>" .$mod. "</div>";
		}
		//$width -= count($modules)/2;
		return implode('', $html);
		//return "<div style='float:left;width:$width%;margin-left:1%;'>" .implode("</div><div style='float:left;width:$width%;margin-right:1%;'>", $html). "</div>";
	} else {
		return mosLoadModules($position, 1);
	}
}

/**
 * Adds PHP5.2 json functionality
 *
 * @param [mixed] The data to encode
 *
 * @return string The json encoded data
 */
if(!function_exists('json_encode')) {
	function json_encode($a = false) {
		if(is_null($a))
			return 'null';
		if($a === false)
			return 'false';
		if($a === true)
			return 'true';
		if(is_scalar($a)) {
			if(is_float($a)) {
				// Always use "." for floats.
				return floatval(str_replace(",", ".", strval($a)));
			}

			if(is_string($a)) {
				static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
				return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
			} else
				return $a;
		}
		$isList = true;
		for($i = 0, reset($a); $i < count($a); $i++, next($a)) {
			if(key($a) !== $i) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if($isList) {
			foreach($a as $v)
				$result[] = json_encode($v);
			return '[' . join(',', $result) . ']';
		} else {
			foreach($a as $k => $v)
				$result[] = json_encode($k) . ':' . json_encode($v);
			return '{' . join(',', $result) . '}';
		}
	}
}

/**
 * Find out the name of the current template being used
 *
 * @since 2.1.7 - 12/02/2009
 */
function jTipsGetTemplateName() {
	global $mainframe;
	// Commented since in J1.5 always returns 'system'
	//if (isJoomla15()) {
	//	return JApplication::getTemplate();
	//} else {
		return $mainframe->getTemplate();
	//}
}
?>