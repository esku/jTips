<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 23/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Display debug messages and write to the log file
 */

class jTipsLogger {
	static function jTipsDebug($item, $log = false) {
		if($log === false) {
			if(is_array($item) or is_object($item) or is_resource($item)) {
				echo "<pre>" . print_r($item, true) . "</pre>";
			} else {
				echo "<pre>" . var_dump($item) . "</pre>";
			}
		} else {
			jTipsLogger::_log($item, $log);
		}
	}
	
	/**
	 * Alias of jTipsDebug. Created for faster debugging
	 *
	 * @since 2.1.9
	 */
	static function dbg($item, $log=false) {
		jTipsLogger::jTipsDebug($item, $log);
	}
	
	static function _log($item, $prepend = "") {
		global $jTips, $mosConfig_absolute_path;
		$file_path = $mosConfig_absolute_path . "/components/com_jtips/jtips.log";
		$level = empty($prepend) ? 'INFO' : strtoupper($prepend);
		if (isset($jTips['DebugLevel']) and !in_array($level, $jTips['DebugLevel'])) {
			return true;
		}
		if (isJoomla15()) {
			jimport('joomla.filesystem.file');
			if (JFile::exists($file_path)) {
				$size = filesize($file_path);
				$human = round(($size /(1024 * 1024)), 4);
				if($human >= 10) {
					if(JFile::exists($file_path . ".0")) {
						JFile::delete($file_path . ".0");
					}
					JFile::move($file_path, $file_path . ".0");
				}
			}
		} else {
			if(file_exists($file_path)) {
				$size = filesize($file_path);
				$human = round(($size /(1024 * 1024)), 4);
				if($human >= 10) {
					if(file_exists($file_path . ".0")) {
						@unlink($file_path . ".0");
					}
					@rename($file_path, $file_path . ".0");
				}
			}
		}
		
		
		if(!empty($prepend) and is_string($prepend)) {
			$prepend .= " ";
		}
		$log = "jLOG " . gmdate('Y-m-d H:i:s') . ": " . $prepend . stripslashes(print_r($item, true)) . "\n";
		
		if (isJoomla15()) {
			$data = '';
			if (JFile::exists($file_path)) {
				$data = JFile::read($file_path);
				$data .= $log;
			}
			return JFile::write($file_path, $data);
		} else {
			if(!$handle = fopen($file_path, 'a')) {
				jTipsLogger::jTipsDebug("FAILED TO OPEN LOG FILE!", false);
			} else {
				if(fwrite($handle, $log) === false) {
					jTipsLogger::jTipsDebug("FAILED TO WRITE TO LOG FILE!", false);
					return false;
				}
				return fclose($handle);
			}
		}
	}
}
?>
