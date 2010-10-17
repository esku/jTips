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
 * Description: Perform an import
 */
jTipsSpoofCheck();

set_time_limit(0);
global $database;
if (!importExists() or !jTipsGetParam($_REQUEST, 'importObject', false)) {
	//should never get here
	mosRedirect('index2.php?option=com_jtips&task=list&module=Import', 'Nothing to Import');;
}

$filename = $mosConfig_absolute_path.'/administrator/components/com_jtips/import.csv';
$handle = fopen($filename, 'r');
$index = 0;
$class = jTipsGetParam($_REQUEST, 'importObject', 'jSeason');

require_once('components/com_jtips/classes/' .strtolower($class). '.class.php');

$jObj = new $class($database);
$header_row = true;
$mapping = array();
$importFields = jTipsGetParam($_REQUEST, 'importFields', array());
foreach ($importFields as $header => $field) {
	if ($field == -1) {
		continue;
	} else {
		$mapping[getIndexOf($header)] = $field;
	}
}
$success = $attempts = 0;

$match_on = jTipsGetParam($_REQUEST, 'match_on', array());
if (isset($match_on['']) and !empty($match_on[''])) {
	unset($match_on['']);
}
while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
	if ($header_row === TRUE) {
		$header_row = FALSE;
		continue;
	}
	$attempts++;
	if ($jObj->import($data, $mapping, $match_on)) {
		$success++;
	}
}
fclose($handle);
//if ($success == $attempts and false) {
//	unlink($filename);
//}
mosRedirect('index2.php?option=com_jtips&task=list&module=Import', "$success / $attempts " .$jLang['_ADMIN_IMPORT_RESULT']);
?>