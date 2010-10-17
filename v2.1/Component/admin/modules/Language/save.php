<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 10/09/2008
 *
 * Description: Saves the edited language key definition
 * to the jLang array and writes the array to
 * the language file.
 *
 * Allows complete language customisation
 */

global $mainframe, $jLang, $mosConfig_lang, $mosConfig_absolute_path;

//Make sure this is not a hack job
jTipsSpoofCheck();

//set the edited field
$key = jTipsGetParam($_REQUEST, 'languageKey', '');
$val = jTipsHTML::cleanText(jTipsGetParam($_REQUEST, 'languageVar', ''));

$jLang[$key] = trim($val);

ksort($jLang);

if (writeArrayToFile('jLang', $jLang, 'components/com_jtips/i18n/'.$mosConfig_lang.'.php')) {
	//BUG 265 - delete the existing js language file if it exists
	// BUG 320 - Fixed typo in path to js language file
	$jsLangFile = $mosConfig_absolute_path. '/components/com_jtips/js/language.js';
	if (jTipsFileExists($jsLangFile)) {
		if (isJoomla15()) {
			jimport('joomla.filesystem.file');
			JFile::delete($jsLangFile);
		} else {
			unlink($jsLangFile);
		}
	}
	$message = $jLang['_ADMIN_LANGUAGE_UPDATED'];
} else {
	$message = $jLang['_ADMIN_LANGUAGE_UPDATE_FAILED'];
}

//redirect to list
mosRedirect('index2.php?option=com_jtips&module=Language&task=list', $message);
?>