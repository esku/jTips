<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
require_once('components/com_jtips/lib/dUnzip2.inc.php');

if(function_exists('jimport') and defined('_JEXEC')) {
	//We are in Joomla 1.5 so load the filesystem layer
	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.archive');
	jimport('joomla.filesystem.folder');
}

function updatejTipsFiles() {
	global $mosConfig_absolute_path;
	$release = str_replace('.', '', getFullVersion());
	$curl = new jTipsCurl("http://www.jtips.com.au/update.php?action=update&release=$release");
	$result = $curl->exec();
	if($curl->hasError()) {
		jTipsLogger::_log("Error downloading update");
		return 'DOWNLOAD ERROR';
	} else
		if(substr($result, 0, 3) == 'ERR') {
			jTipsLogger::_log('update response: ' . $result);
			return $result;
		}
	$curl->close();
	$update_dir = $mosConfig_absolute_path . '/administrator/components/com_jtips/updates';
	/*if (!file_exists($update_dir)) {
		if (!mkdir($update_dir)) {
			return "FAILED TO MAKE UPLOAD DIRECTORY";
		}
	}*/
	$update_file = $update_dir . '/' . date("Y-m-d") . '.zip';
	jTipsLogger::_log("writing update to file: " . $update_file);

	$decoded = unserialize(base64_decode(urldecode($result)));
	$buffer = base64_decode($decoded['update']);

	return jTipsWriteUpdateFile($update_file, $buffer);
}

/**
 * Version safe function to write a single file from buffer to a file
 *
 * @param string The target full file path
 * @param string The content to be written to the file
 *
 * @return [mixed]
 */
function jTipsWriteUpdateFile($file, $buffer) {
	if(function_exists('jimport') and defined('_JEXEC')) {
		JFile :: write($file, $buffer);
		return jTipsUpdate($file);
	} else {
		//Use traditional methods
		if(!file_exists(dirname($file))) {
			if(!mkdir(dirname($file))) {
				return "FAILED TO MAKE UPLOAD DIRECTORY";
			}
		}
		if(file_exists($file)) {
			unlink($file);
		}
		@ touch($file);
		if($handle = fopen($file, 'w+')) {
			if(fwrite($handle, $buffer)) {
				$updated = jTipsUpdate($file);
				fclose($handle);
				return $updated;
			} else {
				jTipsLogger::_log("FAILED TO WRITE UPDATE FILE'");
				return 'FAILED TO WRITE UPDATE FILE';
			}
		} else {
			jTipsLogger::_log("FAILED TO LOAD UPDATE FILE");
			return 'FAILED TO LOAD UPDATE FILE';
		}
	}
}

function jTipsUpdate($filepath = '') {
	global $jLicence;
	$path = dirname($filepath) . '/' . basename($filepath, '.zip');
	jTipsLogger::_log('about to extract update package from ' . $filepath . ' to ' . $path, 'INFO');
	//extract zip
	if(jTipsExtractZip($filepath, $path)) {
		jTipsLogger::_log('update extract successfully!', 'INFO');
		//run db queries if they exist
		if(file_exists($path . '/pilot.php')) {
			$db_updates = null;
			include($path . '/pilot.php');
			if(is_array($db_updates) and !empty($db_updates)) {
				jTipsLogger::_log('updating database from update', 'INFO');
				dbUpdate($db_updates);
			}
		}
		//delete the temp directory
		if (isJoomla15()) {
			JFolder::delete($path);
		} else {
			// BUG 318 - remove unnecessary call to unlink
			//unlink($path);
		}
		$jLicence->revalidate(true);
		jTipsLogger::_log('upgrade successful!', 'INFO');
		return true;
	} else {
		jTipsLogger::_log('error extracting update to ' . $path, 'ERROR');
		return false;
	}

}

function jTipsExtractZip($archive, $path) {
	global $mosConfig_absolute_path;
	$admin = $mosConfig_absolute_path . '/administrator/components/com_jtips/';
	$site = $mosConfig_absolute_path . '/components/com_jtips/';
	if(function_exists('jimport') and defined('_JEXEC')) {
		$zip = new JArchive();
		if($zip->extract($archive, $path)) {
			if(JFolder :: copy($path . '/admin/', $admin, '', true) and JFolder :: copy($path . '/front/', $site, '', true)) {
				$result = true;
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}

	} else {
		/*$zip = new dUnzip2($archive);
		$zip->unzipAll($path);
		if(full_copy($path . '/admin/', $admin) and full_copy($path . '/front/', $site)) {
			$result = true;
		} else {
			$result = false;
		}*/
		$result = jTipsExtractLegacy($archive);
	}
	return $result;
}

function jTipsExtractLegacy($filepath="") {
	global $mosConfig_absolute_path, $jLicence;
	//$filepath = $mosConfig_absolute_path. '/administrator/components/com_jtips/updates/test.zip';
	$writefile = $mosConfig_absolute_path. '/administrator/components/com_jtips/updates/pilot.php';

	$zip = new dUnzip2($filepath);
	$zip->getList();
	$zip->unzip('pilot.php', $writefile);

	$updates = null;
	include($writefile);
	$failed = false;
	if (is_array($updates)) {
		foreach ($updates as $key => $files) {
				$path = $mosConfig_absolute_path.($key == 'admin' ? '/administrator' : ''). '/components/com_jtips/';
				foreach ($files as $file) {
//					if (makeDirectories($path, $file)) {
//						jTipsLogger::_log("unzipping file $key/" .$file. " => " .$path.$file);
//						$res = $zip->unzip("$key/$file", $path.$file);
//						if ($res === false) {
//							$failed = true;
//						}
//					}
					//BUG 255 - Legacy create directories
					if (mosMakePath($path, dirname($file))) {
						jTipsLogger::_log("unzipping file $key/" .$file. " => " .$path.$file);
						$res = $zip->unzip("$key/$file", $path.$file);
						if ($res === false) {
							$failed = true;
						}
					}
				}
		}
	} else {
		jTipsLogger::_log("No files to update");
	}
	jTipsLogger::_log("Update result (failed = 1): " .intval($failed));
	$jLicence->revalidate(true);
	return intval(!$failed);
}


function dbUpdate($db_updates) {
	global $database;
	$results = array();
	foreach($db_updates as $query) {
		$database->setQuery($query);
		jTipsLogger::_log("Executing update query: " . $query);
		if(!$database->query()) {
			jTipsLogger::_log($database->_errorMsg, 'FAILED UPDATE QUERY:');
		} else {
			array_push($results, $query);
		}
	}
	return $results;
}

/**
 * Automatically check for the latest version and if it is newer, upgrade
 *
 * @return bool True on success, false otherwise
 */
function autoUpgrade() {
	global $jTips;
	$returnVal = false;
	if ($jTips['AutoUpgrade'] == 1) {
		if (getLastUpdateCheckDate() < gmdate('Y-m-d H:i:s', strtotime("-2 weeks"))) {
			$latestVersion = getLatestVersion();
			$latestVersionArray = explode('.', $latestVersion);
			$thisVersion = getFullVersion();
			$thisVersionArray = explode('.', $thisVersion);
			if (count($thisVersionArray) != count($latestVersionArray)) {
				jTipsLogger::_log('incompatible versions!', 'ERROR');
				$returnVal = false;
			} else {
				$doUpgrade = false;
				jTipsLogger::_log('comparing this version against latest version');
				for ($i=0; $i<count($thisVersionArray); $i++) {
					if ($latestVersionArray[$i] > $thisVersionArray[$i]) {
						$doUpgrade = true;
					}
				}
				if ($doUpgrade) {
					jTipsLogger::_log('about to do auto upgrade');
					$result = updatejTipsFiles();
					if (is_bool($result)) {
						//BUG 262 - AutoUpgrade to version message corrected
						$_SESSION['jtips_upgraded_version'] = $latestVersion;
						$returnVal = intval($result);
					} else {
						$returnVal = $result;
					}
				}
			}
		}
		setLastUpdateCheckDate();
		return $returnVal;
	}
	return false;
}

function getLastUpdateCheckDate() {
	global $mosConfig_absolute_path;
	$auto = $mosConfig_absolute_path. '/administrator/components/com_jtips/auto';
	if (!jTipsFileExists($auto)) {
		return gmdate('1970-01-01 00:00:00');
	}
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		return JFile::read($auto);
	} else {
		return file_get_contents($auto);
	}
}

function setLastUpdateCheckDate() {
	global $mosConfig_absolute_path;
	jTipsLogger::_log('updating update check date');
	$auto = $mosConfig_absolute_path. '/administrator/components/com_jtips/auto';
	$buffer = gmdate('Y-m-d H:i:s');
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		JFile::write($auto, $buffer);
	} else {
		file_put_contents($auto, $buffer);
	}
}
?>