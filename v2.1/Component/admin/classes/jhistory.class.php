<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class jHistory extends jTipsDBTable {
	var $id = null;
	var $user_id = null;
	var $round_id = null;
	var $points = 0;
	var $precision = 0;
	var $rank = null;
	var $outof = null;
	var $updated = null;

	function jHistory(& $db) {
		$this->mosDBTable('#__jtips_history', 'id', $db);
	}

	function save() {
		$bind = array ();
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

	function loadByParams($params = array (), $limit = FALSE, $offset = FALSE, $debug = FALSE) {
		global $database;
		$extra_clause = "";
		if ($limit !== FALSE and !empty($limit)) {
			$extra_clause .= " LIMIT $limit";
		}
		if ($offset !== FALSE and !empty($offset)) {
			$extra_clause .= " OFFSET $offset";
		}
		$query = "SELECT " . $this->_tbl . ".id FROM " . $this->_tbl . " " . buildWhere($params) . " $extra_clause";
		jTipsLogger::_log($query, "DEBUG");
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array ();
			$list = (array) $database->loadResultArray();
			foreach ($list as $id) {
				$jObj = new jHistory($database);
				$jObj->load($id);
				array_push($jObjs, $jObj);
			}
			return $jObjs;
		} else
			if ($database->getNumRows() == 1) {
				$this->load($database->loadResult());
				return $this;
			} else {
				return FALSE;
			}
	}

	function getCount($params = array ()) {
		global $database;
		$query = "SELECT COUNT(*) FROM " . $this->_tbl . " " . buildWhere($params);
		$database->setQuery($query);
		return intval($database->loadResult());
	}

	//Alias function for delete
	function destroy($id = false) {
		if ($id == FALSE) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " . $this->_tbl . " record with id = '$id'");
		return $this->delete($id);
	}

	function getTotal(& $jTipsUser, $field = 'points') {
		global $database;
		$query = "SELECT id FROM #__jtips_rounds WHERE season_id = " . $jTipsUser->season_id;
		$database->setQuery($query);
		$ids = $database->loadResultArray();
		$in = "";
		foreach ($ids as $id) {
			$in .= "$id,";
		}
		$in = substr($in, 0, -1);
		$query = "SELECT SUM(`$field`) FROM #__jtips_history WHERE user_id = " . $jTipsUser->id . " AND round_id IN ($in)";
		jTipsLogger::_log($query, "jHistory::getTotal");
		$database->setQuery($query);
		$res = $database->loadResult();
		return is_numeric($res) ? intval($res) : 0;
	}

	function getProgressScore(& $jTipsUser, $round_id, $field = 'points') {
		global $database;
		$query = "SELECT start_time FROM #__jtips_rounds WHERE id = $round_id";
		$database->setQuery($query);
		$last_date = $database->loadResult();
		if (empty ($last_date)) {
			return 0;
		} else {
			$query = "SELECT id FROM #__jtips_rounds WHERE start_time <= '$last_date' AND scored = 1 AND season_id = " .$jTipsUser->season_id;
			$database->setQuery($query);
			$ids = $database->loadResultArray();
			$in = "";
			foreach ($ids as $id) {
				$in .= "$id,";
			}
			$in = substr($in, 0, -1);
			$query = "SELECT SUM(`$field`) FROM #__jtips_history WHERE user_id = " . $jTipsUser->id . " AND round_id IN ($in)";
			$database->setQuery($query);
			$res = $database->loadResult();
			return is_numeric($res) ? intval($res) : 0;
		}
	}

	function setRanks($round_id, $force=false) {
		global $database;
		if (!$round_id) {
			jTipsLogger::_log('setRanks - no round_id passed in!', 'ERROR');
			return;
		}
		//optimizing
		//only process this round if the number of users in the round (in history) does not equal the outof
		$query = "SELECT COUNT(*) total, outof from #__jtips_history WHERE round_id = " .$database->Quote($round_id). " GROUP BY round_id";
		//jTipsDebug($query);
		$database->setQuery($query);
		$rows = $database->loadAssocList();
		$row = array_shift($rows);
		//jTipsDebug($row);
		//die();
		if (!$force and $row['total'] == $row['outof']) {
			//no need to reprocess this round!
			jTipsLogger::_log('setRanks: round does not need to be processed');
			return;
		}

		//////////////////////////////
		// BUG 87 - Incorrect User Ranks and Scores
		//
		//$query = "SELECT user_id, SUM(`points`) AS total_points, SUM(`precision`) AS total_precision " .
		//"FROM #__jtips_history GROUP BY user_id ORDER BY total_points DESC, total_precision ASC";

		$getSeasonQuery = "SELECT season_id, `round` FROM #__jtips_rounds WHERE id = $round_id";
		$database->setQuery($getSeasonQuery);
		$rounds = $database->loadAssocList();
		$round = array_shift($rounds);
		$roundIDQuery = "SELECT id FROM #__jtips_rounds WHERE season_id = '" .$round['season_id']. "' AND `round` <= '" .$round['round']. "'";
		//$roundIDQuery = "SELECT id FROM #__jtips_rounds WHERE season_id = '" .$round['season_id']. "' AND start_time <= '" .$round['start_time']. "'";
		$database->setQuery($roundIDQuery);
		$round_ids_array = $database->loadResultArray();
		if (empty($round_ids_array)) {
			jTipsLogger::_log('setRanks - no rounds found! Something went wrong!', 'ERROR');
			return;
		}
		$round_ids = implode(', ', $round_ids_array);

		$query = "SELECT user_id, SUM(points) AS total_points, SUM(`precision`) AS total_precision " .
		"FROM #__jtips_history WHERE round_id IN ($round_ids) GROUP BY user_id " .
		"ORDER BY total_points DESC, total_precision ASC";
		// END BUG 87
		//////////////////////////////////
		jTipsLogger::_log("SETRANKS QUERY: ". $query);
		$database->setQuery($query);
		$rows = $database->loadResultArray();
		$outof = count($rows);
		$rank = 1;
		foreach ($rows as $id) {
			$params = array (
				'user_id' => $id,
				'round_id' => $round_id
			);
			$jHistory = new jHistory($database);
			$jHistory->loadByParams($params);
			$jHistory->rank = $rank;
			$jHistory->outof = $outof;
			if (!$id) {
				jTipsLogger::_log("NO USER ID!: U:$id R:$rank O:$outof Q:$query");
			}
			jTipsLogger::_log("Setting rank for user $id to $rank out of $outof");
			//jTipsLogger::_log($jHistory);
			$jHistory->save();
			if (!empty($jHistory->_error)) {
				jTipsLogger::_log($jHistory->_error, 'ERROR');
			}
			$rank++;
		}
	}

	function getLadder($num_to_show, $round_id, $page = 0, $field = 'rank', $dir = 'asc') {
		global $database;
		$debug_args = func_get_args();
		jTipsLogger::_log('jHistory: getLadder: ' .implode(', ', $debug_args));
		if ($round_id === FALSE) {
			$jSeason = new jSeason($database);
			$round_id = $jSeason->getLastRound();
		}
		if ($dir != 'asc' and $dir != 'desc') {
			$dir = 'desc';
		}
		if ($field == 'pointst') {
			$field = 'rank';
		}
		if ($field == 'prect') {
			return $this->getOverallLadder($num_to_show, $round_id, $page, $field, $dir);
		} else
			if ($field == 'user') {
				return $this->getNameLadder($num_to_show, $round_id, $page, $dir);
			} else
				if ($field == 'comment') {
					return $this->getCommentLadder($num_to_show, $round_id, $page, $dir);
				}
		if ($field == 'prec') {
			$field = 'precision';
		}

		$params = array (
			'round_id' => $round_id,
			'join_jtips_users' => array(
				'type' => 'join',
				'join_table' => '#__jtips_users',
				'lhs_table' => '#__jtips_history',
				'lhs_key' => 'user_id',
				'rhs_table' => '#__jtips_users',
				'rhs_key' => 'id'
			),
			'join_users' => array(
				'type' => 'join',
				'join_table' => '#__users',
				'lhs_table' => '#__jtips_users',
				'lhs_key' => 'user_id',
				'rhs_table' => '#__users',
				'rhs_key' => 'id'
			),
			'o2' => array (
				'type' => 'order',
				'by' => $field,
				'direction' => "$dir"
			)
		);

		$offset = $page * $num_to_show;
		jTipsLogger::_log("USER LADDER PAGINATION - OFFSET: $offset. PAGE: $page. NUMTOSHOW: $num_to_show", 'DEBUG');
		$jHistories = forceArray($this->loadByParams($params, $num_to_show, $offset, true));
		$users = array ();
		foreach ($jHistories as $history) {
			$jTipsUser = new jTipsUser($database);
			$jTipsUser->load($history->user_id);
			array_push($users, $jTipsUser);
		}
		return $users;

	}

	function getCommentLadder($num_to_show, $round_id, $page, $dir) {
		global $database;
		$jComment = new jComment($database);
		$params = array (
			'#__jtips_history.round_id' => $round_id,
			'join_jtips_users' => array(
				'type' => 'join',
				'join_table' => '#__jtips_users',
				'lhs_table' => '#__jtips_history',
				'lhs_key' => 'user_id',
				'rhs_table' => '#__jtips_users',
				'rhs_key' => 'id'
			),
			'join_users' => array(
				'type' => 'join',
				'join_table' => '#__users',
				'lhs_table' => '#__jtips_users',
				'lhs_key' => 'user_id',
				'rhs_table' => '#__users',
				'rhs_key' => 'id'
			),
			'left_join_comments' => array (
				'type' => 'left_join',
				'join_table' => '#__jtips_comments',
				'lhs_table' => '#__jtips_history',
				'rhs_table' => '#__jtips_comments',
				'lhs_key' => 'round_id',
				'rhs_key' => 'round_id',
				'supplement' => 'AND #__jtips_history.user_id = #__jtips_comments.user_id'
			),
			'o1' => array (
				'type' => 'order',
				'by' => 'comment',
				'direction' => $dir
			)
		);
		$offset = $page * $num_to_show;
		$jHistories = forceArray($this->loadByParams($params, $num_to_show, $offset));
		$users = array ();
		foreach ($jHistories as $jc) {
			$jTipsUser = new jTipsUser($database);
			$jTipsUser->load($jc->user_id);
			array_push($users, $jTipsUser);
		}
		return $users;
	}

	function getNameLadder($num_to_show, $round_id, $page = 0, $dir = 'asc') {
		global $database, $jTips;
		if ($jTips['DisplayName'] == 'real') {
			$field = 'name';
		} else {
			$field = 'username';
		}
		$offset = $page * $num_to_show;
		$query = "SELECT #__jtips_history.user_id FROM #__jtips_history JOIN #__jtips_users ON " .
		"#__jtips_history.user_id = #__jtips_users.id JOIN #__users ON #__jtips_users.user_id = #__users.id " .
		"WHERE #__jtips_history.round_id = $round_id ORDER BY #__users.$field $dir, rank ASC ";
		if ($num_to_show) {
			$query .= "LIMIT $num_to_show OFFSET $offset";
		}
		//jTipsDebug($query);
		$database->setQuery($query);
		$row = $database->loadResultArray();
		$users = array ();
		foreach ($row as $user_id) {
			$jTipsUser = new jTipsUser($database);
			$jTipsUser->load($user_id);
			array_push($users, $jTipsUser);
		}
		return $users;
	}

	/**
	 * 31 July 2007
	 * This function should now be deprecated
	 */
	function getOverallLadder($num_to_show, $round_id, $page = 0, $field = 'points', $dir = 'desc') {
		global $database;
		if ($field == 'pointst') {
			$field = 'points';
		} else
			if ($field == 'prect') {
				$field = 'precision';
			}
		$offset = $page * $num_to_show;
		//$query = "SELECT user_id, SUM(`$field`) AS total FROM #__jtips_history GROUP BY user_id ORDER BY total $dir";
		$query = "SELECT #__jtips_history.user_id, SUM(`$field`) AS total".
					" FROM #__jtips_history JOIN #__jtips_users ON #__jtips_history.user_id = #__jtips_users.id" .
					" JOIN #__users ON #__jtips_users.user_id = #__users.id" .
					" GROUP BY #__jtips_history.user_id ORDER BY total $dir";
		if ($num_to_show) {
			$query .= " LIMIT $num_to_show OFFSET $offset";
		}
		// BUG 368 - Call to undefined function jTipsDebug - not called as part of static class
		jTipsLogger::jTipsDebug($query, "jHistory::getOverallLadder");
		$database->setQuery($query);
		$row = $database->loadResultArray();
		$users = array ();
		foreach ($row as $user_id) {
			$jTipsUser = new jTipsUser($database);
			$jTipsUser->load($user_id);
			array_push($users, $jTipsUser);
		}
		return $users;
	}

	function getLast(& $jTipsUser, $field = 'points') {
		global $database;
		$jSeason = new jSeason($database);
		$jSeason->load($jTipsUser->season_id);
		$jRound = new jRound($database);
		$jRound->load($jSeason->getLatestRound());
		if ($jRound->scored != 1) {
			$jRound->load($jRound->getPrev());
		}
		//$prev_id = $jRound->getPrev();
		//$jRound->load($prev_id);
		$query = "SELECT `$field` FROM #__jtips_history WHERE user_id = " . $jTipsUser->id . " AND round_id = " . $jRound->id . ";";
		//jTipsDebug($query);
		$database->setQuery($query);
		$res = $database->loadResult();
		return is_numeric($res) ? floatval($res) : 0;
	}

	function export() {
		global $database;
		$query = "SELECT s.name AS 'Season Name', r.round AS 'Round', u.name AS 'Full Name', " .
		"u.username AS 'User Name', h.points AS 'Round Points', h.precision AS 'Round Precision', " .
		"h.rank AS 'Rank', h.outof 'Total Users' " .
		"FROM " . $this->_tbl . " h JOIN #__jtips_users ju ON h.user_id = ju.id " .
		"JOIN #__users u ON ju.user_id = u.id JOIN #__jtips_rounds r ON h.round_id = r.id " .
		"JOIN #__jtips_seasons s ON r.season_id = s.id ORDER BY s.start_time, r.round, h.rank";
		$database->setQuery($query);
		$rows = $database->loadAssocList();
		$lines = $headers = array ();
		while ($row = current($rows)) {
			$line = array ();
			if (empty ($headers)) {
				foreach ($row as $heading => $val) {
					array_push($headers, $heading);
				}
			}
			foreach ($row as $heading => $val) {
				array_push($line, str_replace('"', "'", addslashes($val)));
			}
			array_push($lines, '"' . implode('","', $line) . '"');
			$row = next($rows);
		}
		$body = implode("\r\n", $lines);
		$export = '"' . implode('","', $headers) . '"' . "\r\n" . $body;
		return $export;
	}

	function exists() {
		if (isset ($this->id) && !empty ($this->id)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
