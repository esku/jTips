<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class jComment extends jTipsDBTable {
	var $id			= null;
	var $user_id	= null;
	var $round_id	= null;
	var $comment	= null;
	var $updated	= null;
	
	function jComment(&$db) {
		$this->mosDBTable( '#__jtips_comments', 'id', $db );
	}
	
	function loadByParams($params=array(), $limit=FALSE, $offset=FALSE) {
		global $database;
		$extra_clause = "";
		if ($limit !== FALSE and !empty($limit)) {
			$extra_clause .= " LIMIT $limit";
		}
		if ($offset !== FALSE and !empty($offse)) {
			$extra_clause .= " OFFSET $offset";
		}
		$query = "SELECT " .$this->_tbl. ".id FROM " .$this->_tbl. " " .buildWhere($params). " $extra_clause;";
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array();
			$list = (array)$database->loadAssocList();
			foreach ($list as $row => $id) {
				$jObj = new jComment($database);
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
//		$where_clause = "";
//		foreach ($params as $key => $val) {
//			if (is_array($val)) {
//				$where_clause .= "$key ";
//				switch ($val['type']) {
//					case 'in':
//						$where_clause .= "IN (" .$val['query']. ") AND ";
//						break;
//					case 'join':
//						break;
//					case 'left_join':
//						break;
//				}
//			} else {
//				$where_clause .= "$key = " .(is_numeric($val) ? $val : "'$val'"). " AND ";
//			}
//		}
		$where_clause = buildWhere($params);//substr($where_clause, 0, -5);
		$query = "SELECT COUNT(*) FROM " .$this->_tbl. " " .(strlen($where_clause) > 0 ? $where_clause : "");
		jTipsLogger::_log('COMMENTS COUNT QUERY: ' .$query);
		$database->setQuery($query);
		return intval($database->loadResult());
	}
	
	function loadAll($season_id=false) {
		global $database;
		if ($season_id) {
			$in = "WHERE round_id IN (";
			$database->setQuery("SELECT roundid FROM #__jtips_rounds WHERE season_id = $season_id;");
			$rounds = $database->loadAssocList();
			$in2 = "";
			foreach ($rounds as $rid) {
				$in2 .= $rid['roundid']. ",";
			}
			$in .= substr($in2, 0, -1). ")";
		} else {
			$in = '';
		}
		$query = "SELECT id FROM #__jtips_comments $in ORDER BY updated DESC;";
		$database->setQuery($query);
		$res = $database->loadAssocList();
		$comments = array();
		foreach ($res as $row) {
			$jComment = new jComment($database);
			$jComment->load($row['id']);
			$comments[] = $jComment;
		}
		return $comments;
	}
	
	function save() {
		$bind = array();
		$this->updated = gmdate('Y-m-d H:i:s');
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
	
	function getSeasonName() {
		global $database;
		if (!isset($this->season_name)) {
			$jRound = new jRound($database);
			$jRound->load($this->round_id);
			$jSeason = new jSeason($database);
			$jSeason->load($jRound->season_id);
			$this->season_name = $jSeason->name;
		}
		return $this->season_name;
	}
	
	function export() {
		global $database;
		$query = "SELECT u.name AS 'Full Name', u.username AS 'User Name', " .
				"s.name AS 'Season Name', r.round AS 'Round', c.comment As 'Comment', " .
				"c.updated AS 'Last Modified' " .
				"FROM " .$this->_tbl. " c JOIN jos_jtips_rounds r ON c.round_id = r.id " .
				"JOIN #__jtips_seasons s ON r.season_id = s.id " .
				"JOIN #__jtips_users ju ON c.user_id = ju.id " .
				"JOIN #__users u ON ju.user_id = u.id";
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
				if ($heading == 'Last Modified') {
					//$val = getLocalDate($val);
					//BUG 277 - call to undefined function
					$val = TimeDate::toDisplayDate($val);
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
	
	function exists() {
		if (isset($this->id) && !empty($this->id)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
