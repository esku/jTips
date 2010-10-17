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
 * Description: Update the CSS file
 */
jTipsSpoofCheck();

global $mosConfig_absolute_path, $jLang;
if (isJoomla15()) {
	$the_file = $mosConfig_absolute_path.DS.'components'.DS.'com_jtips'.DS.'css'.DS.jTipsGetParam($_REQUEST, 'filename', 'stylesheet');
} else {
	$the_file = $mosConfig_absolute_path. '/components/com_jtips/css/'.jTipsGetParam($_REQUEST, 'filename', 'stylesheet');
}

if (substr($the_file, -4) != '.css') {
	$message = $jLang['_ADMIN_STYLE_BAD_FILENAME'];
	mosRedirect('index2.php?option=com_jtips&module=Styles', $message);
}

if (jTipsGetParam($_REQUEST, 'jstyle', false)) {
	$the_string = jTipsGetParam($_REQUEST, 'jstyle', '');
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		jimport('joomla.filter.filterinput');
		$the_string = stripslashes(JFilterInput::clean($the_string));
		if (JFile::write($the_file, $the_string)) {
			$message = $jLang['_ADMIN_STYLE_SAVED'];
		} else {
			$message = $jLang['_ADMIN_STYLE_ERROR_SAVING'];
		}
	} else {
		if($fh = @fopen($the_file, "w")) {
			$the_string = stripslashes($the_string);
			fputs($fh, $the_string, strlen($the_string));
			fclose($fh);
			$message = $jLang['_ADMIN_STYLE_SAVED'];
	    } else {
			$message = $jLang['_ADMIN_STYLE_ERROR_SAVING'];
	    }
	}
}
// BUG 398 - the zeroth element cases a false, check the type too
if ($task == 'apply' and jTipsGetParam($_REQUEST, 'cid', false) !== false) {
	mosRedirect('index2.php?option=com_jtips&module=Styles&task=edit&cid[]=' .jTipsGetParam($_REQUEST, 'cid', ''). '&hidemainmenu=1', $message);
} else {
	mosRedirect('index2.php?option=com_jtips&module=Styles', $message);
}
?>
