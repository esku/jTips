<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jteam.class.php');

class jTip extends jTipsDBTable {
	var $id			= null;
	var $user_id	= null;
	var $game_id	= null;
	var $tip_id		= null;
	var $home_score	= null;
	var $away_score	= null;
	var $margin		= null;
	var $bonus_id	= null;
	var $updated	= null;
	
	function jTip(&$db) {
		$this->mosDBTable( '#__jtips_tips', 'id', $db );
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
		$query = "SELECT " .$this->_tbl. ".id FROM " .$this->_tbl. " " .buildWhere($params). " $extra_clause;";
		jTipsLogger::_log("FULL QUERY: $query");
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array();
			$list = (array)$database->loadResultArray();
			foreach ($list as $id) {
				$jObj = new jTip($database);
				$jObj->load($id);
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
	
	function getCount($params=array(), $wrap=false) {
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
		if ($wrap == true) {
			$query = "SELECT " .$this->_tbl. ".id FROM " .$this->_tbl. " " .buildWhere($params);
			$query = "SELECT COUNT(*) FROM ($query) b";
		}
		jTipsLogger::_log("Tip COUNT Query: $query");
		$database->setQuery($query);
		return intval($database->loadResult());
	}
	
	function fillInAdditionalFields() {
		global $database;
		
		$jTipsUser = new jTipsUser($database);
		$jTipsUser->load($this->user_id);
		$this->user =& $jTipsUser;

		$jTeam = new jTeam($database);		
		if ($this->tip_id > 0) {
			$jTeam->load($this->tip_id);
		}
		$this->team =& $jTeam;
		
		$jGame = new jGame($database);
		$jGame->load($this->game_id);
		
		$jRound = new jRound($database);
		$jRound->load($jGame->round_id);
		$this->round =& $jRound;
		$this->round_num = $jRound->round;
		
		$jSeason = new jSeason($database);
		$jSeason->load($jRound->season_id);
		$this->season =& $jSeason;
	}
	
	//Alias function for delete
	function destroy($id=false) {
		if ($id == FALSE) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " .$this->_tbl. " record with id = '$id'");
		return $this->delete($id);
	}
	
	function export() {
		global $database;
		$query = "SELECT u.name AS 'Full Name', u.username AS 'User Name', " .
				"s.name AS 'Season Name', r.round AS 'Round', " .
				"CONCAT(ht.location, ' ', ht.name) AS 'Home Team', " .
				"CONCAT(at.location, ' ', at.name) AS 'Away Team', " .
				"CONCAT(t.location, ' ', t.name) AS 'Tip', " .
				"p.home_score AS 'Tipped Home Score', " .
				"p.away_score AS 'Tipped Away Score', " .
				"p.margin AS 'Tipped Margin', " .
				"CONCAT(wt.location, ' ', wt.name) AS 'Winning Team', " .
				"g.home_score AS 'Actual Home Score', " .
				"g.away_score AS 'Actual Away Score', " .
				"ABS(g.home_score - g.away_score) AS 'Actual Margin'" .
				"FROM " .$this->_tbl. " p JOIN jos_jtips_users ju ON p.user_id = ju.id " .
				"JOIN #__users u ON ju.user_id = u.id " .
				"JOIN #__jtips_games g ON p.game_id = g.id " .
				"JOIN #__jtips_teams t ON p.tip_id = t.id " .
				"JOIN #__jtips_teams ht ON g.home_id = ht.id " .
				"JOIN #__jtips_teams at ON g.away_id = at.id " .
				"JOIN #__jtips_teams wt ON g.winner_id = wt.id " .
				"JOIN #__jtips_rounds r ON g.round_id = r.id " .
				"JOIN #__jtips_seasons s ON r.season_id = s.id " .
				"ORDER BY r.round, u.username";
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
}
?>