<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
global $mosConfig_absolute_path;
require_once ($mosConfig_absolute_path . '/administrator/components/com_jtips/classes/juser.class.php');
require_once ($mosConfig_absolute_path . '/administrator/components/com_jtips/classes/jseason.class.php');
require_once ($mosConfig_absolute_path . '/administrator/components/com_jtips/lib/class.curl.php');
require_once ($mosConfig_absolute_path . '/administrator/components/com_jtips/utils/timedate.php');
require_once ($mosConfig_absolute_path . '/administrator/components/com_jtips/utils/version.php');

class jTipsLicence {
	var $licence_file;
	var $licence_data = array();
	var $licence;
	// BUG 124 - Changed to https to use no-caching SSL
	var $host = 'https://www.jtips.com.au/validate.php';

	function jTipsLicence($file=FALSE) {
		global $mosConfig_absolute_path;
		if ($file and file_exists($file)) {
			$this->licence_file = $file;
		} else {
			$this->licence_file = $mosConfig_absolute_path . '/administrator/components/com_jtips/licence.lic';
		}
		if (isJoomla15()) {
			jimport('joomla.filesystem.file');
		}
		if (!file_exists($this->licence_file)) {
			if (isJoomla15()) {
				jimport('joomla.filesystem.file');
				JFile::write($this->licence_file, '');
			} else {
				@touch($this->licence_file);
			}
		}
		$this->loadLicenseFile();
	}

	function checkValidation() {
		if (!$this->getValidationError()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function revalidate($force=false) {
		global $database, $jTips, $mosConfig_absolute_path;
		$this_file = $mosConfig_absolute_path. '/administrator/components/com_jtips/licence.php';
		$jTipsUser = new jTipsUser($database);
		$jSeason = new jSeason($database);
		$seasons = array (
			'end_time' => array (
				'type' => 'query',
				'query' => ">= '" . gmdate('Y-m-d') . "'"
			)
		);
		//BUG 127 - Optionally use an unsecure connection
		if ($jTips['SSLValidation'] == 0 or $jTips['SSLValidation'] == '0') {
			$this->host = preg_replace('/https/', 'http', $this->host);
			jTipsLogger::_log('validating license through unsecure connection');
		}
		$params = array (
			'total_users' => $this->getActiveUserCount(),
			'total_seasons' => $jSeason->getCount($seasons),
			'license_key' => $jTips['ActivationKey'],
			'activation_email' => $jTips['ActivationEmail'],
			'domain_name' => preg_replace('/(www\.)|(www)/i', '', $_SERVER['SERVER_NAME']),
			'current_version' => getFullVersion()
		);
		$encoded = serialize($params);
		jTipsLogger::_log('Preparing to revalidate license', 'INFO');
		if ($this->hasError() or $this->licence['license_expiry'] <= gmdate('Y-m-d H:i:s', time()-(3600*24)) or $force) {
			jTipsLogger::_log('connecting to ' .$this->host);
			$curl = new jTipsCurl($this->host);
			$licence_params = array (
				'data' => $params,
				'license' => base64_encode(serialize($this->licence))
			);
			$data['key'] = base64_encode(serialize($licence_params));
			//$fields = $curl->asPostString($licence_params);
			$fields = $curl->asPostString($data);
			//jTipsDebug($fields);
			$curl->setopt(CURLOPT_POST, TRUE);
			$curl->setopt(CURLOPT_POSTFIELDS, $fields);
			$curl->setopt(CURLOPT_CONNECTTIMEOUT, 60);
			jTipsLogger::_log('Sending validation request', 'INFO');
			$result = $curl->exec();
			if ($curl->hasError()) {
				jTipsLogger::_log('curl error validation license: ' .$curl->hasError(), 'ERROR');
				//Return the current license data if there was an error in the connection
				return $curl->hasError();
			}
			jTipsLogger::_log('License validation request result:');
			jTipsLogger::_log($result);
			//jTipsDebug($result);
			//die();
			jTipsLogger::_log('Decoding license response', 'INFO');
			$decoded = ($result == '-1' ? -1 : @unserialize(base64_decode($result)));
			//jTipsDebug($result);
			//die();
			if ($decoded == -1) {
				jTipsLogger::_log('error in response', 'ERROR');
				return false;
			} else {
				jTipsLogger::_log('all is well with license ', 'INFO');
				$this->licence = $decoded;
				$this->writeLicenceFile();
				//jTipsDebug($this->licence);
				return $this->licence;
			}
		} else {
			jTipsLogger::_log('license still current', 'INFO');
			return $this->licence;
		}
	}

	function writeLicenceFile() {
		$encrypted_licence = base64_encode(serialize($this->licence));
		$wrapped = wordwrap($encrypted_licence, 75, "\n", true);
		if (isJoomla15()) {
			return JFile::write($this->licence_file, $wrapped);
		} else if ($fh = @ fopen($this->licence_file, "w")) {
			//$encrypted_licence = base64_encode(serialize($this->licence));
			//$wrapped = wordwrap($encrypted_licence, 75, "\n", true);
			fwrite($fh, $wrapped, strlen($wrapped));
			fclose($fh);
			return true;
		} else {
			return false;
		}
	}

	function getValidationDate() {
		jTipsLogger::_log('Checking date license was last validated', 'INFO');
		if (!is_array($this->licence)) {
			jTipsLogger::_log('Invalid license data!', 'ERROR');
			return -1;
		}
		/*if (strtotime($this->licence['last_validation']) > (time() - (3600 * 24 * 14))) {
			return TimeDate::toDisplayDateTime($this->licence['last_validation']);
		}*/
		return TimeDate::toDisplayDateTime($this->licence['last_validation']);
	}

	/**
	 * Determine the appropriate error message to display
	 *
	 * @return string The error message
	 */
	function getValidationError() {
		//jTipsLogger::dbg($this->licence);
		if (strtotime($this->licence['last_validation']) >= strtotime($this->licence['next_validation']) or strtotime($this->licence['next_validation']) <= time()) {
			return 'WARN: LICENCE REQURIES REVALIDATION';
		} else if ($this->licence['total_users'] > $this->licence['licensed_users']) {
			return 'ERR: TOTAL USERS EXCEEDS LICENCED LIMIT OF ' .$this->licence['licensed_users']. ' BY ' .($this->licence['total_users']-$this->licence['licensed_users']).'.';
//		} else if (strtotime($this->licence['license_expiry']) < time()) {
//			return 'ERR: LICENCE HAS EXPIRED';
		} else if (!is_array($this->licence) or !$this->licence['is_valid']) {
			return 'ERR: INVALID LICENCE DATA - REVALIDATE';
		} else if ($this->licence['license_expiry'] < date('Y-m-d', strtotime("+30 days"))) {
			$expires	= strtotime($this->licence['license_expiry']) / (60*60*24);
			$now		= time() / (60*60*24);
			$days		= floor($expires - $now);
			if ($days < 0) {
				$msg = 'expired ' .abs($days). ' days ago';
			} else {
				$msg = 'expires in ' .$days. ' days';
			}
			return 'WARN: License ' .$msg. '. Please contact <a href="mailto:sales@jtips.com.au">sales@jtips.com.au</a> to renew.';
		} else if (isset($this->licence['licence_expiry']) and $this->licence['licence_expiry'] > date('Y-m-d')) {
			return 'ERR: License has expired. Please contact <a href="mailto:sales@jtips.com.au">sales@jtips.com.au</a>';
		} else {
			return false;
		}
	}

	function getLicenceStatus($revalidate = true) {
		$error = $this->getValidationError();
		jTipsLogger::_log('license status: ' .$error);
		if (substr($error, 0, 3) == 'ERR') {
			$return = 'ERR';
		} else
			if (substr($error, 0, 3) == 'WAR') {
				$return = 'WARN';
			} else {
				$return = '';
			}
		if ($return and $revalidate) {
			$data = $this->revalidate();
			$this->writeLicenceFile();
			return $this->getLicenceStatus(false);
		} else {
			return $return;
		}
	}

	function hasError() {
		$result = $this->getLicenceStatus(FALSE);
		return strlen($result) > 0;
	}

	function loadLicenseFile() {
		if (isJoomla15()) {
			$licence = JFile::read($this->licence_file);
		} else {
			$licence = file_get_contents($this->licence_file);
		}
		$licence = preg_replace('/[\r\n]+/', '', $licence);
		$licence = unserialize(base64_decode($licence));
		$this->licence = $licence;
		return $licence;
	}

	/**
	 * Get the number of users allowed by the current licence
	 *
	 * @return int The number of users
	 */
	function getLicensedUsers() {
		if (!is_array($this->licence)) {
			jTipsLogger::_log('Invalid license data!', 'ERROR');
			return 0;
		}
		return $this->licence['licensed_users'];
	}

	/**
	 * Get the number of active users participating in current competitions
	 *
	 * @since 2.1.10
	 * @return int The number of active users
	 */
	function getActiveUserCount() {
		global $database;
		// BUG 342 - Get the total number of active users
		$query = "SELECT COUNT( DISTINCT user_id ) FROM #__jtips_users" .
				" JOIN #__jtips_seasons ON #__jtips_users.season_id = #__jtips_seasons.id" .
				" AND #__jtips_seasons.end_time > NOW() AND #__jtips_users.status = '1'";
		$database->setQuery($query);
		return $database->loadResult();
	}
}
?>
