<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 16/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

global $database, $mosConfig_absolute_path;
jTipsLogger::_log("Rebuilding jTips tables...");

if (isJoomla15()) {
	jimport('joomla.filesystem.file');
	$install = JFile::read('components/com_jtips/sql/install.mysql.sql');
	$delete = JFile::read('components/com_jtips/sql/uninstall.mysql.sql');
} else {
	$install = file_get_contents('components/com_jtips/sql/install.mysql.sql');
	$delete = file_get_contents('components/com_jtips/sql/uninstall.mysql.sql');
}

$success = true;

jTipsLogger::_log('deleting existing tables');
//split on ;
$deleteQueries = explode(';', $delete);
foreach ($deleteQueries as $d) {
	$theQ = trim($d);
	if (empty($theQ)) continue;
	$database->setQuery($theQ);
$database->query();
if ($database->_errorMsg) {
	jTipsLogger::_log('Something went wrong deleting tables: ' .$database->getErrorMsg(), 'ERROR');
	$success = false;
}
}


jTipsLogger::_log('recreating tables');
$installQueries = explode(';', $install);
foreach ($installQueries as $i) {
	$theQ = trim($i);
	if (empty($theQ)) continue;
	$database->setQuery($theQ);
$database->query();
if ($database->_errorMsg) {
		jTipsLogger::_log('Something went wrong deleting tables: ' .$database->getErrorMsg(), 'ERROR');
	$success = false;
}
}
if ($success) {
	die('Done');
} else {
	die('Rebuild Failed: ' .$database->getErrorMsg());
}
?>