<?php
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class jPilot {
	var $version = '2.1';
	var $build = '15';
	var $updates = array();

	function jPilot() {

	}

	function getVersion() {
		return $this->version;
	}

	function getBuild() {
		return $this->build;
	}

	function getUpdates() {
		return $this->updates;
	}

	function dbUpdate() {
		global $database;
		$results = array();
		foreach ($this->db_updates as $query) {
			$database->setQuery($query);
			if (!$database->query()) {
				array_push($results, $query);
				jTipsLogger::_log($query, 'FAILED UPDATE QUERY:');
			}
		}
		return $results;
	}
}
?>