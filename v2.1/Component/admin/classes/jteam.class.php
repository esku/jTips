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

class jTeam extends jTipsDBTable {
	var $id				= null;
	var $season_id		= null;
	var $name			= null;
	var $location		= null;
	var $about			= null;
	var $logo			= null;
	var $url			= null;
	var $wins			= 0;
	var $losses			= 0;
	var $draws			= 0;
	var $points_for		= 0;
	var $points_against	= 0;
	var $points			= 0;
	var $updated		= null;
	function jTeam(&$db) {
		$this->mosDBTable( '#__jtips_teams', 'id', $db );
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
	
	function getName() {
		return trim($this->location. " " .$this->name);
	}
	
	/**
	 * Get HTML to display the current team logo
	 * 
	 * @param [int] The size of the logo to display. Defaults to 25px
	 * 
	 * @return string The HTML img code
	 */
	function getLogo($size=25) {
		global $mainframe, $mosConfig_live_site;
		$logo_text = '';
		if (!empty($this->logo) and jTipsFileExists(getJtipsImage($this->logo, $size))) {
			$logo_text = "<img src='" .$mosConfig_live_site;
			$logo_text .=  "/" .getJtipsImage($this->logo, $size). "' alt='" .$this->getName(). "' border='0' align='absmiddle' />";
		}
		return $logo_text;
	}
	
	function getDisplayLogoName($side='left') {
		global $database, $mainframe, $mosConfig_absolute_path, $jTips;
		$jSeason = new jSeason($database);
		$jSeason->load($this->season_id);
		if ($jSeason->tip_display > 0) {
			$hasLogo = false;
			$size = $jSeason->tip_display == 1 ? 100 : 25;
			$logo_text = $this->getLogo($size);
			if (empty($logo_text)) {
				$logo_text = $this->getName();
			} else {
				$hasLogo = true;
			}
			if ($jSeason->tip_display == 2 and $hasLogo) {
				if ($side != 'left') {
					$logo_text = $this->getName(). "&nbsp;" .$logo_text;
				} else {
					$logo_text .= "&nbsp;" .$this->getName();
				}
			}
		} else {
			$logo_text = $this->getName();
		}
		return $logo_text;
	}
	
	//Alias function for delete
	function destroy($id=false) {
		global $database;
		if ($id == false) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " .$this->_tbl. " record with id = '$id'");
		
		//delete all games and tips where this team was involved
		//first, the games
		$query = "SELECT id FROM #__jtips_games WHERE home_id = " .$database->Quote($id). " OR away_id = " .$database->Quote($id);
		jTipsLogger::_log('loading games to delete');
		$database->setQuery($query);
		$ids = $database->loadResultArray();
		if (!empty($ids)) {
			$jGame = new jGame($database);
			foreach ($ids as $id) {
				$jGame->destroy($id);
			}
		}
		
		//now the tips
		$query = "SELECT id FROM #__jtips_tips WHERE game_id IN ('" .implode("', '", $ids). "')";
		jTipsLogger::_log('loading tips to delete');
		$database->setQuery($query);
		$ids = $database->loadResultArray();
		if (!empty($ids)) {
			$jTip = new jTip($database);
			foreach ($ids as $id) {
				$jTip->destroy($id);
			}
		}
		
		return $this->delete($id);
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
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array();
			$list = (array)$database->loadAssocList();
			foreach ($list as $row => $id) {
				$jObj = new jTeam($database);
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
	
	function setGoalDifference() {
		$this->for_against = $this->points_for - $this->points_against;
	}
	
	function getHistory() {
		$query = "SELECT g.*, r.round, r.start_time FROM #__jtips_rounds r JOIN #__jtips_games g ON r.id = g.round_id " .
				"AND r.scored = 1 AND r.season_id = {$this->season_id} " .
				"WHERE g.home_id = {$this->id} OR g.away_id = {$this->id} ORDER BY r.round DESC";
				
		jTipsLogger::_log($query);
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();
		//jTipsLogger::_log($result, 'RESULT');
		return $result;
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
					case 'reference':
						$where_clause .= "= (" .$val['query']. ") AND ";
						break;
				}
			} else {
				$where_clause .= "$key = " .(is_numeric($val) ? $val : "'$val'"). " AND ";
			}
		}
		$where_clause = substr($where_clause, 0, -5);
		$query = "SELECT COUNT(*) FROM " .$this->_tbl.(strlen($where_clause) > 0 ? " WHERE $where_clause" : ""). ";";
		jTipsLogger::_log($query, "jTeam getCount");
		$database->setQuery($query);
		return intval($database->loadResult());
	}
	
	function getGamesPlayed() {
		global $database;
		if (!isset($this->played)) {
			$jGame = new jGame($database);
			$params = array(
				'home_id' => $this->id,
				'round_id' => array(
					'type' => 'in',
					'query' => "SELECT id FROM #__jtips_rounds WHERE scored = 1 AND season_id = " .$this->season_id
				)
			);
			$homeGames = $jGame->getCount($params);
			unset($params['home_id']);
			$params['away_id'] = $this->id;
			$awayGames = $jGame->getCount($params);
			$this->played = $homeGames + $awayGames;
		}
		return $this->played;
	}
	
	function getTeamField($field) {
		switch ($field) {
			case 'played':
				return $this->getGamesPlayed();
				break;
			case 'for_against':
				return $this->points_for - $this->points_against;
				break;
			case 'logo':
				return $this->logo;
				break;
			case 'percentage': // BUG 322 - add % to team columns
				$denominator = $this->points_against > 0 ? $this->points_against : 1;
				return round(($this->points_for/$denominator)*100, 2);
				break;
			default:
				if (is_property($this, $field)) {
					return $this->$field;
				} else {
					return "-";
				}
				break;
		}
	}
	
	function getSeasonName() {
		global $database;
		if (!isset($this->season_name)) {
			$jSeason = new jSeason($database);
			$jSeason->load($this->season_id);
			$this->season_name = $jSeason->name;
		}
		return $this->season_name;
	}
	

	/**
	 * Get the total number of wins for a team.
	 * Should only be used when tallying results
	 */
	function _getNumberOfWins() {
		$query = "SELECT COUNT(*) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.winner_id = " .$this->_db->Quote($this->id). " AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if (!$result) $result = 0;
		return $result;
	}

	/**
	 * Get the number of drawn games this team has played in this season
	 * Should only be used when tallying results
	 */
	function _getNumberOfDraws() {
		$query = "SELECT COUNT(*) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.winner_id = '-1' AND (g.home_id = " .$this->_db->Quote($this->id). " OR g.away_id = " .$this->_db->Quote($this->id). ") AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if (!$result) $result = 0;
		return $result;
	}
	
	/**
	 * Get the number ofi losses games this team has played in this season
	 * Should only be used when tallying results
	 */
	function _getNumberOfLosses() {
		$query = "SELECT COUNT(*) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.winner_id != '-1' AND g.winner_id != " .$this->_db->Quote($this->id). " AND g.winner_id IS NOT NULL AND g.winner_id <> '' AND (g.home_id = " .$this->_db->Quote($this->id). " OR g.away_id = " .$this->_db->Quote($this->id). ") AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		if (!$result) $result = 0;
		return $result;
	}

	function _getPointsFor() {
		$points = 0;
		//the case when this team is the home team
		$query = "SELECT SUM(home_score) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.home_id = " .$this->_db->Quote($this->id). " AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
                if (!$result) $result = 0;
		$points += $result;
		//now when the team is the away team
		$query = "SELECT SUM(away_score) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.away_id = " .$this->_db->Quote($this->id). " AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
                if (!$result) $result = 0;
		$points += $result;
                return $points;
	}

	function _getPointsAgainst() {
		$points = 0;
		//the case when this team is the home team
		$query = "SELECT SUM(away_score) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.home_id = " .$this->_db->Quote($this->id). " AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
                if (!$result) $result = 0;
		$points += $result;
		//now when the team is the away team
		$query = "SELECT SUM(home_score) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE g.away_id = " .$this->_db->Quote($this->id). " AND r.season_id = " .$this->_db->Quote($this->season_id). " AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
                if (!$result) $result = 0;
		$points += $result;
                return $points;
	}

	function _getBonusPoints($jSeason=false) {
		if (!$jSeason) {
			$jSeason = new jSeason();
			$jSeason->load($this->season_id);
		}
		$query = "SELECT COUNT(bonus_id) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id AND (g.home_id = " .$this->_db->Quote($this->id). " OR g.away_id = " .$this->_db->Quote($this->id). ") AND r.season_id = " .$this->_db->Quote($this->season_id). " AND (bonus_id = " .$this->_db->Quote($this->id). " OR bonus_id = '-1') AND r.scored = '1'";
		$this->_db->setQuery($query);
		$result = $this->_db->loadResult();
		return ($result * ((isset($jSeason->team_bonus) ? $jSeason->team_bonus : 1)));
	}

	function _getTotalPoints() {
		$jSeason = new jSeason($this->_db);
		$jSeason->load($this->season_id);
		$winPoints	= $this->wins * ((isset($jSeason->team_win) ? $jSeason->team_win : 1));
		$losePoints	= $this->losses * ((isset($jSeason->team_lose) ? $jSeason->team_lose : 0));
		$drawPoints	= $this->draws * ((isset($jSeason->team_draw) ? $jSeason->team_draw : 0));
		//handle the bonus points
		if ($jSeason->team_bonus) {
			$bonusPoints	= $this->_getBonusPoints($jSeason);
		} else {
			$bonusPoints	= 0;
		}

		return $winPoints + $losePoints + $drawPoints + $bonusPoints;
	}
	
	/**
	 * Updates the team points based on the round passed in
	 */
	static function updateLadder($jRound, $jSeason) {
		jTipsLogger::_log('updating team ladder for round ' .$jRound->id);
		global $database;
		$params = array(
			'round_id' => $jRound->id
		);
		$jGame = new jGame($database);
		$jGames = forceArray($jGame->loadByParams($params));
		$byeTeams = array();
		foreach ($jGames as $jGame) {
			$home = new jTeam($database);
			$away = new jTeam($database);
			$homeLoaded = $awayLoaded = false;
			if ($jGame->home_id) {
				$home->load($jGame->home_id);
				$homeLoaded = true;
			}
			if ($jGame->away_id) {
				$away->load($jGame->away_id);
				$awayLoaded = true;
			}
			if (!$homeLoaded and $awayLoaded) $byeTeams[] = $away;
			if ($homeLoaded and !$awayLoaded) $byeTeams[] = $home;
			if (!$homeLoaded or !$awayLoaded) continue; // nothing to process here
			$home->wins				= $home->_getNumberOfWins();
			$home->losses			= $home->_getNumberOfLosses();
			$home->draws			= $home->_getNumberOfDraws();
			$home->points_for		= $home->_getPointsFor();
			$home->points_against	= $home->_getPointsAgainst();
			$home->points			= $home->_getTotalPoints();

			$away->wins				= $away->_getNumberOfWins();
			$away->losses			= $away->_getNumberOfLosses();
			$away->draws			= $away->_getNumberOfDraws();
			$away->points_for		= $away->_getPointsFor();
			$away->points_against	= $away->_getPointsAgainst();
			$away->points			= $away->_getTotalPoints();

			$home->save();
			$away->save();
		}
		if (!empty($byeTeams)) {
			foreach ($byeTeams as $team) {
				$team->wins				= $team->_getNumberOfWins();
				$team->losses			= $team->_getNumberOfLosses();
				$team->draws			= $team->_getNumberOfDraws();
				$team->points_for		= $team->_getPointsFor();
				$team->points_against	= $team->_getPointsAgainst();
				$team->points			= $team->_getTotalPoints();
				$team->points += $jSeason->team_bye;
				$team->save();
			}
		}
	}
	
	function export() {
		global $database;
		$query = "SELECT s.name AS 'Season Name', t.location AS 'Location', " .
				"t.name AS 'Team Name', t.url AS 'Website', t.wins AS 'Wins', t.draws AS 'Draws', " .
				"t.losses AS 'Losses', t.points_for AS 'Points Scored', t.points_against AS 'Points Conceeded', " .
				"t.points 'Season Points' FROM " .$this->_tbl. " t " .
				"JOIN #__jtips_seasons s ON t.season_id = s.id ORDER BY s.name, t.location";
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
				if ($heading == 'Round Start' or $heading == 'Round End') {
					//$val = getLocalDateTime($val);
					//BUG 277 - call to undefined function
					$val = TimeDate::toDisplayDateTime($val);
				}
				array_push($line, str_replace('"', "'", addslashes(trim($val))));
			}
			array_push($lines, '"' .implode('","', $line). '"');
			$row = next($rows);
		}
		$body = implode("\r\n", $lines);
		$export = '"' .implode('","', $headers). '"'. "\r\n". $body;
		return $export;
	}
	
	function getFieldMap() {
		require('components/com_jtips/fieldmap/jteam.fields.php');
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