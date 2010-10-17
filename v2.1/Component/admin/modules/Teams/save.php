<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1 - 01/10/2008
 * @version 2.1
 * @package jTips
 *
 * Description: Save the posted information to the team record
 */

global $mainframe, $database, $mosConfig_absolute_path;

//Make sure this is not a hack job
jTipsSpoofCheck();

require_once('components/com_jtips/classes/jteam.class.php');
require_once('components/com_jtips/lib/tnimg.lib.php');

$jTeam = new jTeam($database);
if ($id = jTipsGetParam($_REQUEST, 'id', false)) {
	$jTeam->load($id);
}
$jTeam->bind($_POST);
if (jTipsGetParam($_REQUEST, 'removeImage', false)) {
	$jTeam->logo = null;
} else {
	unset($jTeam->logo); //so we dont overwrite the current value
}
if (isset($_FILES['logo']['name']) and !empty($_FILES['logo']['name']) and imageDirCheck()) { //Upload image
	$filename	= str_replace(' ', '_', $_FILES['logo']['name']);
	$filename_100	= getJtipsImage($filename, 100);
	$filename_25	= getJtipsImage($filename);
	if (!isJoomla15()) {
		if (!is_dir($mosConfig_absolute_path. '/images/jtips') or !file_exists($mosConfig_absolute_path. '/images/jtips')) {
			mkdir($mosConfig_absolute_path. '/images/jtips');
		}
	} else {
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.path');
		if (!JFolder::exists($mosConfig_absolute_path. '/images/jtips')) {
			JFolder::create($mosConfig_absolute_path. '/images/jtips');
		}
		//create empty img files and make writeable
		JFile::write($mosConfig_absolute_path. '/images/jtips/'.$filename_100, '');
		if (JPath::canChmod($mosConfig_absolute_path. '/images/jtips/'.$filename_100)) {
			JPath::setPermissions($mosConfig_absolute_path. '/images/jtips/'.$filename_100, 666);
		}

		JFile::write($mosConfig_absolute_path. '/images/jtips/'.$filename_25, '');
		if (JPath::canChmod($mosConfig_absolute_path. '/images/jtips/'.$filename_25)) {
			JPath::setPermissions($mosConfig_absolute_path. '/images/jtips/'.$filename_25, 666);
		}
	}

	/**
	 * This is the only part that requires actual apache access to the images directory
	 * Difficult to resize an image if apache cannot write the image anywhere
	 */
	$ti = new ThumbnailImage();
	$ti->src_file = $_FILES['logo']['tmp_name'];
	$ti->dest_type = THUMB_JPEG;
	$ti->dest_file = $mosConfig_absolute_path. '/images/jtips/'.$filename_100;
	$ti->max_width = 100;
	$ti->max_height = 100;
	$ti->Output();
	$ti = null;
	$ti = new ThumbnailImage();
	$ti->src_file = $_FILES['logo']['tmp_name'];
	$ti->dest_type = THUMB_JPEG;
	$ti->dest_file = $mosConfig_absolute_path. '/images/jtips/'.$filename_25;
	$ti->max_width = 25;
	$ti->max_height = 25;
	$ti->Output();

	$jTeam->logo = 'images/jtips/'.$filename;
} else if (jTipsGetParam($_REQUEST, 'remove_logo', 0) == 1) {
	$jTeam->logo = '';
}
if (!$jTeam->save()) {
	$message = 'Save Failed!';
} else {
	$message = 'Team Saved!';
}
//show_teams($option);
mosRedirect('index2.php?option=com_jtips&task=list&module=Teams', $message);
?>
