<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class jSeason extends jTipsDBTable {
	var $id					= null;
	var $name				= null;
	var $description		= null;
	var $start_time			= null;
	var $end_time			= null;
	var $rounds				= null;
	var $games_per_round	= null;
	var $pick_score			= 0;
	var $pick_margin		= 0;
	var $pick_bonus			= 0;
	var $pick_draw			= 0;
	var $team_bonus			= 0;
	var $team_win			= 0;
	var $team_lose			= 0;
	var $team_draw			= 0;
	var $team_bye			= 0;
	var $user_correct		= 0;
	var $user_draw			= 0;
	var $user_bonus			= 0;
	var $user_none			= -1;
	var $user_pick_score	= 0;
	var $user_pick_margin	= 0;
	var $user_pick_bonus	= 0;
	var $url				= null;
	var $image				= null;
	var $precision_score	= 0;
	var $tip_display		= 2;
	var $updated			= null;
    var $team_starts        = 0;
    var $default_points		= null;
    var $tips_layout		= null;
    var $game_times			= 0;
    var $disable_tips		= 0;
    var $scorer_id			= null;
    var $tough_score		= 0;
	
	function jSeason(&$db) {
		$this->mosDBTable( '#__jtips_seasons', 'id', $db );
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
		
		//set the dates back to db format
		if (!empty($this->start_time)) {
			$this->start_time = TimeDate::toDatabaseDate($this->start_time);
		}
		if (!empty($this->end_time)) {
			$this->end_time = TimeDate::toDatabaseDate($this->end_time);
		}
		
		//Bug 36: pass in true to allow nulls to be updated
		if (!$this->store(true)) {
			jTipsLogger::_log($this->_db->getErrorMsg(), 'ERROR');
			return false;
		}
		//reformat the dates
		//set the dates back to display format
		if (!empty($this->start_time)) {
			$this->start_time = TimeDate::toDisplayDate($this->start_time);
		}
		if (!empty($this->end_time)) {
			$this->end_time = TimeDate::toDisplayDate($this->end_time);
		}
		return true;
	}
	
	function loadAll($keyed=FALSE) {
		global $database;
		$query = "SELECT " .$this->_tbl. ".id FROM " .$this->_tbl. " WHERE start_time < '" .gmdate('Y-m-d'). "' AND end_time > '" .gmdate('Y-m-d'). "';";
		$database->setQuery($query);
		$rows = $database->loadResultArray();
		$seasons = array();
		foreach ($rows as $id) {
			$jSeason = new jSeason($database);
			$jSeason->load($id);
			if ($keyed !== FALSE) {
				$seasons[$id] = $jSeason;
			} else {
				array_push($seasons, $jSeason);
			}
		}
		return $seasons;
	}
	
	function load($id) {
		parent::load($id);
		if (!empty($this->start_time)) {
			$this->start_time = TimeDate::toDisplayDate($this->start_time);
		}
		if (!empty($this->end_time)) {
			$this->end_time = TimeDate::toDisplayDate($this->end_time);
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
		jTipsLogger::_log($params, 'jSeason->loadByParams:');
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array();
			$list = (array)$database->loadResultArray();
			foreach ($list as $id) {
				$jObj = new jSeason($database);
				$jObj->load($id);
				array_push($jObjs, $jObj);
			}
			return $jObjs;
		} else if ($database->getNumRows() == 1) {
			$load_id = $database->loadResult();
			$this->load($load_id);
			return $this;
		} else {
			return FALSE;
		}
	}
	
	function getCount($params=array()) {
		global $database;
		$query = "SELECT COUNT(*) FROM " .$this->_tbl. " " .buildWhere($params);
		$database->setQuery($query);
		return intval($database->loadResult());
	}
	
	//Alias function for delete
	function destroy($id=false) {
		if ($id == FALSE) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " .$this->_tbl. " record with id = '$id'");
		return $this->delete($id);
	}
	
	function getDefaultPoints($user_id) {
		global $database, $jTips;
		
		if (!$this->default_points) {
			jTipsLogger::_log('no default points should be allocated. aborting');
			return;
		} else {
			if ($this->default_points == 'low') {
				$limit = "LIMIT 1";
			} else {
				$limit = "";
			}
			jTipsLogger::_log('setting default points for user ' .$user_id. ' using method -' .$this->default_points. '-');
			$round_id = $this->getLastRound();
			$query = "SELECT id FROM #__jtips_rounds WHERE season_id = '" .$this->id. "'";
			$database->setQuery($query);
			$rids = $database->loadResultArray();
			$query = "SELECT SUM(points) lpt, SUM(`precision`) lpr FROM #__jtips_history WHERE round_id IN ('" .implode("', '", $rids). "') GROUP BY user_id ORDER BY lpt ASC $limit";
			jTipsLogger::_log("getDefaultPoints: ($user_id) ".$query);
			$database->setQuery($query);
			$database->query();
			//only create a jhistory record if at least 1 round is complete
			if ($database->getNumRows() > 0) {
				$jHistory = new jHistory($database);
				$jHistory->user_id = $user_id;
				$jHistory->round_id = $round_id;
				if ($limit) {
					//BUG 279 - no such function as loadAssoc
					$res = $database->loadAssocList();
					$jHistory->points = $res[0]['lpt'];
					$jHistory->precision = $res[0]['lpr'];
				} else {
					$res = $database->loadAssocList();
					//loop and get averages for each
					$points = 0;
					$precision = 0;
					foreach ($res as $row) {
						$points += $row['lpt'];
						$precision += $row['lpr'];
					}
					$jHistory->points = $points/count($res);
					$jHistory->precision = $precision/count($res);
					//rank will be different for averages!
				}
				//rank query
				$query = "SELECT outof FROM #__jtips_history WHERE round_id = '$round_id'";
				$database->setQuery($query);
				$max = $database->loadResult();
				$jHistory->outof = $max+1;
				$jHistory->rank = $max+1;
				$jHistory->save();
				$jHistory->setRanks($round_id, true);
				
				//update outof
				/*$query = "UPDATE #__jtips_history SET outof = '" .($max+1). "', updated = CURRENT_TIMESTAMP WHERE round_id = '$round_id'";
				jTipsLogger::_log('update ranks after adding user');
				$database->setQuery($query);
				$database->query();*/
			}
		}
		return 0;
	}
	
	function getCurrentRound() {
		global $database;
		//This require is needed here for ajax calls
		//require_once($GLOBALS['mosConfig_absolute_path']. '/administrator/components/com_jtips/timedate.php');
		//$query = "SELECT id FROM #__jtips_rounds WHERE start_time > '" .gmdate('Y-m-d H:i:s'). "' AND season_id = " .$this->id. " ORDER BY start_time ASC LIMIT 1;";
		$query = "SELECT id FROM #__jtips_rounds WHERE end_time >= '" .gmdate('Y-m-d H:i:s'). "' AND season_id = " .$this->id. " ORDER BY end_time ASC LIMIT 1;";
		$database->setQuery($query);
		$id = $database->loadResult();
		if (is_numeric($id)) {
			return $id;
		} else {
			$query = "SELECT MAX(id) FROM #__jtips_rounds WHERE season_id = " .$this->id. " AND end_time < '" .gmdate('Y-m-d H:i:s'). "';";
			$database->setQuery($query);
			$id = $database->loadResult();
			if (is_numeric($id)) {
				return $id;
			} else {
				return FALSE;
			}
		}
	}
	
	function getLatestRound() {
		global $database;
		$id = $this->getCurrentRound();
		if ($id === FALSE) {
			$query = "SELECT id FROM #__jtips_rounds WHERE season_id = " .$this->id. " ORDER BY round DESC LIMIT 1;";
			$database->setQuery($query);
			$id = $database->loadResult();
		}
		//$jRound = new jRound($database);
		if (is_numeric($id)) {
			return $id;
		}
		return FALSE;
	}
	
	function getLastRound() {
		global $database;
		$query = "SELECT id FROM #__jtips_rounds WHERE scored = 1 AND season_id = " .$this->id. " ORDER BY start_time DESC LIMIT 1";
		$database->setQuery($query);
		$id = $database->loadResult();
		if (!is_numeric($id)) {
			$id = $this->getLatestRound();
		}
		return $id;
	}
	
	function getNextLogicalRound() {
		global $database;
		$round_id = $this->getCurrentRound();
		$jRound = new jRound($database);
		$jRound->load($round_id);
		if ($jRound->round + 1 >= $this->rounds) {
			return $this->rounds;
		} else {
			return $jRound->round + 1;
		}
	}
	
	function exists() {
		if (isset($this->id) && !empty($this->id)) {
			return TRUE;
		}
		return FALSE;
	}
	
	function expired() {
		if ($this->get('end_time') < gmdate('Y-m-d H:i:s')) {
			return TRUE;
		}
		return FALSE;
	}
	
	function getUsers($round_id=FALSE) {
		global $database;
		if ($round_id === FALSE) {
			// BUG 304 - join with users table to avoid trying to load ids with no matching user
			$params = array (
				'#__jtips_users.season_id' => $this->id,
				'#__jtips_users.status' => 1,
				'join' => array(
					'type' => 'join',
					'join_table' => '#__users',
					'lhs_table' => '#__jtips_users',
					'lhs_key' => 'user_id',
					'rhs_table' => '#__users',
					'rhs_key' => 'id'
				)
			);
		} else {
			$params = array(
				'#__jtips_users.id' => array(
					'type' => 'in',
					'query' => 'SELECT #__jtips_history.user_id FROM #__jtips_history WHERE #__jtips_history.round_id = ' .$round_id
				),
				'#__jtips_users.season_id' => $this->id,
				'#__jtips_users.status' => 1
			);
		}
		$jTipsUser = new jTipsUser($database);
		$jTipsUsers = forceArray($jTipsUser->loadByParams($params));
		$this->users = $jTipsUsers;
		return $jTipsUsers;
	}
	
	function getUserLadder($num_to_show, $page=0, $field='points', $dir='desc', $round_id=FALSE) {
		global $database, $jTips;
		$org_round_id = $round_id;
		if ($round_id === FALSE) {
			$round_id = $this->getLatestRound();
		}
		$jHistory = new jHistory($database);
		$this->getUsers($round_id);
		$jTipsUsers = array();
		$start_from = $num_to_show * $page;
		$go_to = min(($start_from + $num_to_show), count($this->users));
		for ($i=$start_from; $i<$go_to; $i++) {
			$jTipsUser =& $this->users[$i];
			$params = array(
				'user_id' => $this->users[$i]->id,
				'round_id' => $round_id,
				'left_join' => array(
					'type' => 'left_join',
					'join_table' => '#__jtips_rounds',
					'lhs_table' => '#__jtips_history',
					'rhs_table' => '#__jtips_rounds',
					'lhs_key' => 'round_id',
					'rhs_key' => 'id',
					'supplement' => 'AND #__jtips_rounds.scored = 1'
				)
			);
			$jHistory->loadByParams($params);
			if (is_property($jHistory, $field)) {
				$jTipsUser->$field = $jHistory->$field;
			} else {
				if ($field == 'pointst') {
					$jTipsUser->$field = $jTipsUser->getTotalScore('points');
				} else if ($field == 'prect') {
					$jTipsUser->$field = $jTipsUser->getTotalScore('precision');
				} else if ($field == 'rank') {
					$jTipsUser->$field = $jTipsUser->getRank($org_round_id);
				} else if ($field == 'comment') {
					$jComment = new jComment($database);
					$params = array(
						'user_id' => $jTipsUser->id,
						'round_id' => $round_id
					);
					$jComment->loadByParams($params);
					$jTipsUser->$field = !empty($jComment->comment) ? $jComment->comment : "";
				} else {
					$jTipsUser->$field = $jTipsUser->getName();
				}
			}
			array_push($jTipsUsers, $jTipsUser);
		}
		//jTipsSortArrayObjects($jTipsUsers, $field, $dir);
		//return $jTipsUsers;
		return jTipsUser::sort($jTipsUsers, $field, $dir);
	}
	
	function getTeams() {
		global $database;
		if (!isset($this->teams)) {
			$jTeam = new jTeam($database);
			$params = array(
				'season_id' => $this->id
			);
			$jTeams = $jTeam->loadByParams($params);
			if (!is_array($jTeams)) {
				if ($jTeam->exists()) {
					$jTeams = array($jTeam);
				} else {
					$jTeams = array();
				}
			}
			$this->teams = $jTeams;
		}
		return $this->teams;
	}
	
	function getTeamLadder($field='wins', $dir=-1) {
		$jTeams = $this->getTeams();
		//BUG 86 - Team Ladder Sorting/Ordering
		$fieldSort = $goalSort = $nameSort = array();
		//multisort on $field, then for/against (desc) then name (asc)
		foreach ($jTeams as $jTeam) {
			$jTeam->setGoalDifference();
			//the selected column
			$fieldSort[$jTeam->id] = $jTeam->$field;
			$goalSort[$jTeam->id] = $jTeam->for_against;
			$scoredSort[$jTeam->id] = $jTeam->points_for;
			$nameSort[$jTeam->id] = $jTeam->getName();
		}
		if ($dir < 0 or strtolower($dir) == 'desc') {
			$fieldDir = SORT_DESC;
		} else {
			$fieldDir = SORT_ASC;
		}
		//make sure all the arrays are the same langth to avoid errors
		/*
		 * TODO: BUG: XXX - min was checking the minimum value in each array, needed to check the length instead
		 */
		if (min(count($jTeams), count($fieldSort), count($goalSort), count($nameSort), count($scoredSort)) == count($jTeams) and count($jTeams) > 0) {
			array_multisort($fieldSort, $fieldDir, SORT_NUMERIC, $goalSort, SORT_DESC, SORT_NUMERIC, $scoredSort, SORT_DESC, SORT_NUMERIC, $nameSort, SORT_ASC, SORT_NUMERIC, $jTeams);
		} else {
			//as a fallback, use the original sort
			jTipsSortArrayObjects($jTeams, $field, $dir);
		}
		return $jTeams;
	}
	
	function export() {
		global $database;
		$query = "SELECT name AS 'Season Name', description AS 'Description', " .
				"start_time AS 'Season Start', end_time AS 'Season End', " .
				"rounds AS 'Total Rounds', games_per_round AS 'Games Per Round' " .
				"FROM " .$this->_tbl;
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
				if ($heading == 'Season Start' or $heading == 'Season End') {
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
	
	function getFieldMap() {
		require('components/com_jtips/fieldmap/jseason.fields.php');
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