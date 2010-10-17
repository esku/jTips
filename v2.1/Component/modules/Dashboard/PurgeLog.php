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
 * Description: 
 */

global $mosConfig_absolute_path;
$filename = $mosConfig_absolute_path. '/components/com_jtips/jtips.log';
if (isJoomla15()) {
	jimport('joomla.filesystem.file');
	JFile::delete($filename);
	JFile::write($filename, '');
} else {
	unlink($filename);
	touch($filename);
}
mosRedirect('index2.php?option=com_jtips', 'Log Purged');
?>