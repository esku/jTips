<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 * 
 * @since 2.1 - 30/09/2008
 * @copyright Copyright &copy; 2009, jTips
 * @author Jeremy Roberts
 * @package jTips
 * @version 2.1
 * 
 * Description: Save the season data from the posted form
 */

global $mainframe, $database, $mosConfig_absolute_path;

//Make sure this is not a hack job
jTipsSpoofCheck();

require_once('components/com_jtips/classes/jseason.class.php');

$jSeason = new jSeason($database);
if ($id = jTipsGetParam($_REQUEST, 'id', FALSE)) {
	$jSeason->load($id);
}

$jSeason->bind($_REQUEST);

//BUG 263 - set the date fields if we are in J1.0
if (!isJoomla15()) {
	$jSeason->start_time = TimeDate::toDisplayDate($jSeason->start_time);
	$jSeason->end_time = TimeDate::toDisplayDate($jSeason->end_time);
}

if ($_FILES['image']['name'] and imageDirCheck()) {
	$logofile = 'images/jtips/' .$_FILES['image']['name'];
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		jTipsLogger::_log('MOVING ' .$_FILES['image']['tmp_name']. ' TO ' .$logofile, 'ERROR');
		//JFile::move($_FILES['image']['tmp_name'], $mosConfig_absolute_path.'/'.$logofile);
		//BUG 270 - to complete upload, use the upload function, not move
		JFile::upload($_FILES['image']['tmp_name'], $mosConfig_absolute_path.'/'.$logofile);
	} else {
		if (!is_dir($mosConfig_absolute_path. '/images/jtips') or !file_exists($mosConfig_absolute_path. '/images/jtips')) {
			mkdir($mosConfig_absolute_path. '/images/jtips');
		}
		move_uploaded_file($_FILES['image']['tmp_name'], $mosConfig_absolute_path.'/'.$logofile);
	}
	$jSeason->image = $logofile;
} else if (jTipsGetParam($_REQUEST, 'remove_image', 0) == 1) {
	$jSeason->image = '';
}
$saved = $jSeason->save();
$message = 'Season ' .$jSeason->name.($saved != false ? ' Saved!' : ' failed to save. Error!');

if ($task == 'apply') {
	mosRedirect('index2.php?option=com_jtips&hidemainmenu=1&task=edit&module=Seasons&cid[]=' .$jSeason->id, $message);
} else {
	mosRedirect('index2.php?option=com_jtips&task=list&module=Seasons', $message);
}
?>
