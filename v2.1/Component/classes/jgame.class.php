<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class jGame extends jTipsDBTable {
	var $id			= null;
	var $round_id	= null;
	var $home_id	= null;
	var $away_id	= null;
	var $position	= null;
	var $winner_id	= null;
	var $draw		= 0;
	var $home_score	= 0;
	var $away_score	= 0;
	var $bonus_id	= null;
	var $has_bonus	= 0;
	var $has_margin	= 0;
	var $has_score	= 0;
	var $updated	= 0;
    var $home_start = null;
    var $away_start = null;
    var $start_time	= null;
    var $tough_score= null;
    var $description = '';

	function jGame(&$db) {
		$this->mosDBTable( '#__jtips_games', 'id', $db );
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
		jTipsLogger::_log($query);
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array();
			$list = (array)$database->loadAssocList();
			foreach ($list as $row => $id) {
				$jObj = new jGame($database);
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
	
	/*function loadExtraDetails() {
		global $database;
		//Load the home team name
		$jTeam_home = new jTeam($database);
		$jTeam_home->load($this->get('home_id'));
		$this->set('hometeam', $jTeam_home->getName());
		
		//Load the away team name
		$jTeam_away = new jTeam($database);
		$jTeam_away->load($this->get('away_id'));
		$this->set('awayteam', $jTeam_away->getName());
	}*/
	
	//Deprecated - user loadByParams
	function getTips($user_id) {
		global $database;
		$query = "SELECT id FROM #__jtips_tips WHERE game_id = " .$this->id. " AND user_id = " .$user_id. ";";
		$database->setQuery($query);
		$jTip = new jTip($database);
		$jTip->load($database->loadResult());
		return $jTip;
	}
	
	function destroy($id=false) {
		if ($id == FALSE) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " .$this->_tbl. " record with id = '$id'");
		return $this->delete($id);
	}
	
	/*function getAllGames($round_id=false) {
		return $this->loadAll($round_id);
	}*/
	
	//returns an array of game objects. if $round_id is passed in
	//only games for that round will be returned
	function loadAll($round_id=FALSE) {
		global $database;
		$query = "SELECT id FROM #__jtips_games" .(is_numeric($round_id) ? " WHERE round_id = $round_id" : ""). ";";
		$database->setQuery($query);
		$list = (array)$database->loadAssocList();
		$gamesObjects = array();
		foreach ($list as $id) {
			$jGame = new jGame($database);
			$jGame->load($id['gameid']);
			$gamesObjects[] = $jGame;
		}
		return $gamesObjects;
	}
	
	function export() {
		global $database;
		$query = "SELECT s.name AS 'Season Name', r.round AS 'Round', " .
				"CONCAT(ht.location, ' ', ht.name) AS 'Home Team', " .
				"CONCAT(at.location, ' ', at.name) AS 'Away Team', " .
				"g.home_score AS 'Home Score', g.away_score AS 'Away Score', " .
				"CONCAT(wt.location, ' ', wt.name) AS 'Winning Team'" .
				"FROM " .$this->_tbl. " g JOIN #__jtips_rounds r ON g.round_id = r.id " .
				"JOIN #__jtips_seasons s ON r.season_id = s.id " .
				"JOIN #__jtips_teams ht ON g.home_id = ht.id " .
				"JOIN #__jtips_teams at ON g.away_id = at.id " .
				"LEFT JOIN #__jtips_teams wt ON g.winner_id = wt.id " .
				"LEFT JOIN #__jtips_teams bt ON g.bonus_id = bt.id " .
				"ORDER BY s.name, r.round";
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
	
	function getFieldMap() {
		require('components/com_jtips/fieldmap/jgame.fields.php');
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
}
?>
