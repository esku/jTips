<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

class TimeDate {
	/**
	 * Takes a date or datetime string in DB format and converts
	 * it to the format defined in the config.
	 * 
	 * @param string The date or datetime
	 * @param [bool] True if the first parameter includes time, Defaults to false
	 * @param [bool] True when displaying dates in the admin area. When calling from frontend, pass in false
	 * 
	 * @return string The date in the configured format
	 */
	static function toDisplayDate($date, $hasTime=false, $isAdmin=true) {
		global $jTips, $mainframe, $jTipsCurrentUser;
		//if hasTime, then apply GMT offset
		if ($hasTime) {
			$datestamp = strtotime($date) + TimeDate::getOffset($isAdmin);
		} else {
			$datestamp = strtotime($date);
		}
		return strftime($jTips['DateFormat'], $datestamp);
	}
	
	/**
	 * Turns a database formatted datetime into a format defined in the config.
	 * 
	 * @param string The datetime string
	 * @param [bool] True is calling from the admin area, false when calling from the front-end
	 * 
	 * @return string The formatted date time string
	 */
	static function toDisplayDateTime($datetime, $isAdmin=true) {
		global $jTips, $mainframe;
		$time = strtotime($datetime); //This is safe since the expected format is Y-m-d H:i:s
		
		//apply the offset from the config
		$time += TimeDate::getOffset($isAdmin);
		
		//now format it
		return strftime($jTips['DateFormat']. " " .$jTips['TimeFormat'], $time);
		
	}
	
	/**
	 * Takes a date in the format defined in the config and 
	 * converts it back to DB format. If hasTime is true, then apply a negative
	 * GMT offset to get GMT0 time.
	 * 
	 * @param string The date or datetime
	 * @param [bool] True if the first parameter includes time, Defaults to false
	 * 
	 * @return string The date in database format
	 */
	static function toDatabaseDate($date, $hasTime=false) {
		global $jTips;
		if ($hasTime) {
			//TODO: fill in as required
		} else {
			// BUG 324 - handle cases when strptime is not defined
			if (function_exists('strptime')) {
				$datetime = strptime($date, $jTips['DateFormat']);
				$time = mktime($datetime['tm_hour'], $datetime['tm_min'], $datetime['tm_sec'], $datetime['tm_mon']+1, $datetime['tm_mday'], $datetime['tm_year']);
			} else {
				$time = strtotime($date);
			}
			//Bug 211 - strptime fails on some systems so use a fallback
			//BUG 250 - add check for negative value
			if (!$time or $time < 0 or !$datetime) {
				$time = strtotime($date);
			}
			return date('Y-m-d', $time);
		}
	}
	
	/**
	 * Converts a datetime string into MySQL format and deducts any offsets
	 * 
	 * @param string The datetime to be converted
	 * @param [bool] True if calling from the admin area, false when calling from the front-end
	 * 
	 * @return string The GMT datetime in MySQL format
	 */
	static function toDatabaseDateTime($datetime, $isAdmin=true) {
		global $jTips;
		$time  = strtotime($datetime);
		$time -= TimeDate::getOffset($isAdmin);
		return strftime('%Y-%m-%d %H:%M:%S', $time);
	}
	
	/**
	 * Converts a datetime string into MySQL format and deducts any offsets
	 * 
	 * @param string The datetime to be converted
	 * @param [bool] True if calling from the admin area, false when calling from the front-end
	 * 
	 * @return string The GMT time in MySQL format
	 */
	static function toDatabaseTime($datetime, $isAdmin=true) {
		global $jTips;
		$time  = strtotime($datetime);
		$time -= TimeDate::getOffset($isAdmin);
		return strftime('%H:%M:%S', $time);
	}
	
	/**
	 * Get the number of seconds to add to the formatted database time
	 * 
	 * @param [bool] True if calling from the admin area, false when calling from the front-end
	 * 
	 * @return int The number of seconds to add
	 */
	static function getOffset($isAdmin=true) {
		global $mainframe, $jTipsCurrentUser;
		$offset = 0;
		if ($isAdmin or !isset($jTipsCurrentUser->id) or empty($jTipsCurrentUser->id)) {
			if (isJoomla15()) {
				$offset = $mainframe->getCfg('offset') * 60 * 60;
			} else {
				$offset = $mainframe->getCfg('offset_user') * 60 * 60;
			}
			//TODO: try to add DST if applicable to this timezone
		} else {
			$tz = $jTipsCurrentUser->getPreference('timezone');
			if (is_numeric($tz)) {
				//$dateTimeZone = new DateTimeZone($tz);
				//$dateTime = new DateTime("now", $dateTimeZone);
				//$offset = $dateTimeZone->getOffset($dateTime);
				$offset = $tz * 60 * 60;
			} else {
				return TimeDate::getOffset();
			}
		}
		return $offset;
	}
	
	/**
	 * Changes the date(time) string into the format passed in.
	 * Argument should be used with strftime. Default is the config var
	 * 
	 * @param string The date(time) string
	 * @param [string] The target format - must be compatible with strftime()
	 * @param [bool] True if the first parameter includes time. Defaults to false
	 * @param [bool] True if calling from the admin area, false when calling from the front-end
	 * 
	 * @return string The date/time in the format passed in
	 */
	static function format($date, $format=null, $hasTime=false, $isAdmin=true) {
		global $jTips, $jTipsCurrentUser, $mainframe;
		if (is_null($format)) {
			if ($hasTime) {
				$format = $jTips['DateFormat']. ' ' .$jTips['TimeFormat'];
			} else {
				$format = $jTips['TimeFormat'];
			}
		}
		$time = strtotime($date);
		if ($hasTime) {
			$time += TimeDate::getOffset($isAdmin);
		}
		return strftime($format, $time);
	}
	
	/**
	 * Get the current GMT date in MySQL format
	 * 
	 * @return string The MySQL formatted date
	 */
	static function getMySQLDate() {
		return gmdate('Y-m-d');
	}
}
?>
