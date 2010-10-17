<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class jBadWord extends jTipsDBTable {
	var $id			= null;
	var $badword	= null;
	var $match_case	= 0;
	var $action		= 'delete';
	var $replace	= null;
	var $hits		= 0;
	var $updated	= null;
	
	/**
	 * Constructor - instantiate the object
	 * @param $db The database connection resource
	 */
	function jBadWord(&$db) {
		$this->mosDBTable( '#__jtips_badwords', 'id', $db );
	}
	
	/**
	 * Return an array of all BadWords as objects
	 * 
	 * @return array
	 */
	function loadAll() {
		global $database;
		$query = "SELECT id FROM #__jtips_badwords";
		$database->setQuery($query);
		$res = $database->loadAssocList();
		$jBadWords = $sort = array();
		foreach ($res as $row) {
			$jBadWord = new jBadWord($database);
			$jBadWord->load($row['id']);
			$jBadWords[] = $jBadWord;
			$sort[$row['id']] = $jBadWord->get('badword');
		}
		array_multisort($sort, SORT_ASC, $jBadWords);
		return $jBadWords;
	}
	
	/**
	 * Load one or more badword objects by passing an array of key value pairs to lookup
	 * the record on non-key fields and to perform joins
	 *
	 * @return mixed An array if more than one, otherwise the object
	 */
	function loadByParams($params=array(), $limit=FALSE, $offset=FALSE) {
		global $database;
		$extra_clause = "";
		if ($limit !== FALSE and !empty($limit)) {
			$extra_clause .= " LIMIT $limit";
		}
		if ($offset !== FALSE and !empty($offset)) {
			$extra_clause .= " OFFSET $offset";
		}
		$query = "SELECT id FROM " .$this->_tbl. " " .buildWhere($params). " $extra_clause;";
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array();
			$list = (array)$database->loadAssocList();
			foreach ($list as $row => $id) {
				$jObj = new jBadWord($database);
				$jObj->load($id['id']);
				array_push($jObjs, $jObj);
			}
			return $jObjs;
		} else if ($database->getNumRows() == 1) {
			$this->load($database->loadResult());
			return $this;
		} else {
			return FALSE;
		}
	}
	
	function getCount($params=array()) {
		global $database;
		$where_clause = "";
		foreach ($params as $key => $val) {
			if (is_array($val)) {
				$where_clause .= "$key ";
				switch ($val['type']) {
					case 'in':
						$where_clause .= "IN (" .$val['query']. ") AND ";
						break;
					case 'join':
						break;
					case 'left_join':
						break;
				}
			} else {
				$where_clause .= "$key = " .(is_numeric($val) ? $val : "'$val'"). " AND ";
			}
		}
		$where_clause = substr($where_clause, 0, -5);
		$query = "SELECT COUNT(*) FROM " .$this->_tbl.(strlen($where_clause) > 0 ? " WHERE $where_clause" : ""). ";";
		$database->setQuery($query);
		return intval($database->loadResult());
	}
	
	function save() {
		$bind = array();
		$this->set('updated', gmdate('Y-m-d H:i:s'));
		foreach ($this as $prop => $val) {
			$bind[$prop] = $val;
		}
		if (!$this->bind($bind)) {
			return false;
		}
		if (!$this->check()) {
			return false;
		}
		//Bug 36: Set the image property to empty string instead of null
		if (!$this->store(true)) {
			jTipsLogger::_log($this->_db->getErrorMsg(), 'ERROR');
			return false;
		}
		return true;
	}
	
	function destroy($id=false) {
		if ($id == FALSE) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " .$this->_tbl. " record with id = '$id'");
		return $this->delete($id);
	}
	
	function export() {
		global $database;
		$query = "SELECT badword as 'BadWord', match_case AS 'Case-Sensitive', " .
				"action AS 'Action', `replace` AS 'Replace With', hits AS 'Matches' " .
				"FROM " .$this->_tbl. " ORDER BY badword ASC";
		$database->setQuery($query);
		$rows = $database->loadAssocList();
		$lines = $headers = array();
		while ($row = current($rows)) {
			$line = array();
			if (empty($headers)) {
				foreach ($row as $heading => $val) {
					array_push($headers, $heading);
				}
			}
			foreach ($row as $heading => $val) {
				if ($heading == 'Case-Sensitive') {
					if ($val == 1) {
						$val = 'Yes';
					} else {
						$val = 'No';
					}
				}
				array_push($line, str_replace('"', "'", addslashes($val)));
			}
			array_push($lines, '"' .implode('","', $line). '"');
			$row = next($rows);
		}
		$body = implode("\r\n", $lines);
		$export = '"' .implode('","', $headers). '"'. "\r\n". $body;
		return $export;
	}
	
	function getFieldMap() {
		require('components/com_jtips/fieldmap/jbadword.fields.php');
		return $fields;
	}
	
	/**
	 * Take an array of data values,
	 * Format them according to the field map
	 * and save in a new object
	 */
	function import($data, $mapping, $match_on) {
		global $database;
		if (!is_array($data) or empty($data) or empty($mapping)) {
			return FALSE;
		}
		$defs =& $this->getFieldMap();
		$class = get_class($this);
		$jObject = new $class($database);
		$mapped_fields = $params = array();
		foreach ($mapping as $column => $fieldmap) {
			if (array_key_exists($fieldmap, $defs)) {
				$field = $defs[$fieldmap];
				if (!isset($field['import']) or $field['import'] == FALSE) {
					continue;
				} else {
					$mapped_fields[$fieldmap] = parseDataType($data, $column, $field);
					//array_push($mapped_fields, $fieldmap);
					if (in_array($fieldmap, $match_on)) {
						$params[$fieldmap] = $mapped_fields[$fieldmap];
					}
				}
			} else {
				continue;
			}
		}
		if (!empty($params)) {
			$jObject = new $class($database);
			$jObject->loadByParams($params);
		}
		foreach ($mapped_fields as $key => $val) {
			$jObject->$key = $val;
		}
		foreach ($defs as $key => $def) {
			if (!array_key_exists($key, $mapped_fields)) {
				if (isset($def['default'])) {
					$jObject->$key = $def['default'];
				}
			}
		}
		return $jObject->save();
	}
	
	function exists() {
		if (isset($this->id) && !empty($this->id)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>