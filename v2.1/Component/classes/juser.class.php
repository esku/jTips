<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jcomment.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jhistory.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jtip.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/jTipsJomSocial.php');

class jTipsUser extends jTipsDBTable {
	var $id = null;
	var $user_id = null;
	var $season_id = null;
	var $status = 0;
	var $doubleup = 0;
	var $paid = 0;
	var $comment = null;
	var $preferences = null;
	var $updated = null;

	function jTipsUser(& $db) {
		$this->mosDBTable('#__jtips_users', 'id', $db);
	}

	function loadAll($season_id = FALSE) {
		global $database;
		$where_clause = "";
		if (is_numeric($season_id)) {
			$where_clause = " season_id = $season_id";
		}
		$query = "SELECT id FROM #__jtips_users" . (strlen($where_clause) ? $where_clause : "") . ";";
		$database->setQuery($query);
		$res = (array) $database->loadAssocList();
		$jTipsUsers = array ();
		foreach ($res as $key => $id) {
			$jTipsUser = new jTipsUser($database);
			$jTipsUser->load($id['id']);
			array_push($jTipsUsers, $jTipsUser);
		}
		return $jTipsUsers;
	}

	function loadByParams($params = array (), $limit = FALSE, $offset = FALSE) {
		global $database;
		$extra_clause = "";
		if ($limit !== FALSE and !empty($limit)) {
			$extra_clause .= " LIMIT $limit";
		}
		if ($offset !== FALSE and !empty($offset)) {
			$extra_clause .= " OFFSET $offset";
		}
		$query = "SELECT " . $this->_tbl . ".id FROM " . $this->_tbl . " " . buildWhere($params) . " $extra_clause";
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array ();
			$list = (array) $database->loadResultArray();
			foreach ($list as $id) {
				$jObj = new jTipsUser($database);
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

	function save() {
		global $database, $jTips;
		$bind = array ();
		$this->set('updated', gmdate('Y-m-d H:i:s'));
		foreach ($this as $prop => $val) {
			$bind[$prop] = $val;
		}
		if (!$this->bind($bind)) {
			return false;
		}
		//jTipsDebug("IDA: " .$this->id);
		if (!$this->check()) {
			return false;
		}
		if (!$this->user_id) {
		    jTipsLogger::_log('attempt to save jtipsuser with no related josuser. aborted', 'ERROR');
		    return false;
		}
		//jTipsDebug("IDB: " .$this->id);
		if (empty($this->id) and !empty($this->season_id)) {
			$newUser = true;
		} else {
			$newUser = false;
		}
		//Bug 36: Set the image property to empty string instead of null
		if (!$this->store(true)) {
			jTipsLogger::_log($this->_db->getErrorMsg(), 'ERROR');
			return false;
		}
		//jTipsDebug("IDC " .$this->id);
		//late entry points here
		jTipsLogger::_log('saving user with id ' .$this->id. ' and season ' .$this->season_id);
		if ($newUser) {
			// BUG 406 - optionally set reminders as on by default
			if ($jTips['DefaultReminders']) {
				jTipsLogger::_log('setting email reminder option as on by default');
				$this->setPreference('email_reminder', 1);
			}
			jTipsLogger::_log('setting default points for new user for season ' .$this->season_id);
			$jSeason = new jSeason($database);
			$jSeason->load($this->season_id);
			jTipsLogger::_log('comparing dates ' .date('Y-m-d'). ' >? ' .TimeDate::toDatabaseDate($jSeason->start_time));
			if (date('Y-m-d') > TimeDate::toDatabaseDate($jSeason->start_time)) {
				$jSeason->getDefaultPoints($this->id);
			}

			if ($jTips['JomSocialActivities'] and $jTips['JomSocialOnUserJoin']) {
				// BUG 334 - typo in user_id variable.
				jTipsJomSocial::writeJoinMessage($this->user_id, $jSeason);
			}
		}
		return true;
	}

	//Alias function for delete
	function destroy($id = false) {
		if ($id == FALSE) {
			$id = $this->id;
		}
		jTipsLogger::_log("Delete " . $this->_tbl . " record with id = '$id'");
		//delete all history records for this user and season
		if (!$this->id) {
			$this->load($id);
		}
		// BUG 129 - Unsubscribing from season leaves history
		$params = array(
			'user_id' => $id/*,
			'round_id' => array(
				'type' => 'IN',
				'query' => "SELECT id FROM #__jtips_rounds WHERE season_id = " .$this->_db->Quote($this->season_id)
			)*/
		);
		//deleting history etc can take a while, so dont time out
		set_time_limit(0);
		$jHistory = new jHistory($this->_db);
		$jHistories = forceArray($jHistory->loadByParams($params));
		jTipsLogger::_log('Found ' .count($jHistories). ' history records for user ' .$id, 'INFO');
		if (is_array($jHistories) and !empty($jHistories)) {
			foreach ($jHistories as $jHist) {
				jTipsLogger::_log('deleting history record', 'INFO');
				$jHist->destroy();
			}
		}
		//reset outof and ranks for remaining users
		//get the rounds to be updated firs
		$query = "SELECT id FROM #__jtips_rounds WHERE season_id = '" .$this->season_id. "' AND scored = 1";
		$this->_db->setQuery($query);
		$rids = $this->_db->loadResultArray();
		//die(implode(", ", $rids));
		if (!empty($rids)) {
			foreach ($rids as $r) {
				$jHistory->setRanks($r);
			}
		}

		//Now delete the tips
		$tip = new jTip($this->_db);
		$tips = forceArray($tip->loadByParams($params));
		if (!empty($tips)) {
		    foreach ($tips as $t) {
		        $t->destroy();
		    }
		}


		// BUG 402 - need to also delete any comments made
		$comment = new jComment($this->_db);
		$comments = $comment->loadByParams($params);
		if (!empty($comments)) {
			foreach ($comments as $c) {
				$c->destroy();
			}
		}

		return $this->delete($id);
	}

	function getPreference($key) {
		$prefs = unserialize(base64_decode($this->preferences));
		if (isset ($prefs[$key])) {
			return $prefs[$key];
		} else {
			return null;
		}
	}

	/**
	 * DEPRECATED
	 */
	function _getPreference($key) {
		$data = array ();
		if (isset ($this->preferences) && !empty ($this->preferences)) {
			@ eval ('$data = ' . base64_decode($this->preferences)); //returned variable is $data
		} else {
			$data = null;
		}
		if (is_array($data)) {
			if (array_key_exists($key, $data)) {
				return ($data[$key]);
			} else {
				return null;
			}
		} else {
			return null;
		}
	}

	function setPreference($key, $value) {
		$prefs = unserialize(base64_decode($this->preferences));
		$prefs[$key] = $value;
		$this->preferences = base64_encode(serialize($prefs));
		$this->save();
	}

	/**
	 * DEPRECATED
	 */
	function _setPreference($key, $value) {
		if (isset ($this->preferences) && !empty ($this->preferences)) {
			eval ('$data = ' . base64_decode($this->preferences)); //returned variable is $data
		} else {
			$data = null;
		}
		if (is_array($data)) {
			if (!empty ($value)) {
				$data[$key] = $value;
			} else {
				unset ($data[$key]);
			}
		} else {
			if (!is_array($value) || (is_array($value) && count($value) > 0)) {
				$data = array (
					$key => $value
				);
			}
		}
		if (is_array($data)) {
			ksort($data);
		} else {
			$data = array ();
		}
		$prefs = substr($this->buildPreferences($data), 0, -1);
		if (!empty ($prefs)) {
			$prefs .= ";";
		}
		if ($prefs != 'array);') {
			$data = base64_encode($prefs);
		} else {
			$data = "";
		}
		$this->preferences = $data;
		return $this->save();
	}

	/**
	 * DEPRECATED
	 */
	function buildPreferences($preferences, $string = "") {
		$string = "array(";
		foreach ($preferences as $key => $val) {
			$string .= !is_numeric($key) ? "'$key' => " : "";
			if (is_array($val) && !empty ($val)) {
				$string .= $this->buildPreferences($val, $string);
			} else {
				if (!empty ($val)) {
					$string .= is_numeric($val) ? $val . "," : "'$val',";
				}
			}
		}
		return substr($string, 0, -1) . "),";
	}

	function getSummaryScores() {
		global $database;
		$season_ids = $this->getSeasons();
		$scores = array ();
		foreach ($season_ids as $season_id) {
			$jSeason = new jSeason($database);
			$jSeason->load($season_id);
			$round_id = $jSeason->getCurrentRound();
			$jRound = new jRound($database);
			$jRound->load($round_id);
			$last_round_id = $jRound->getPrev();
			$jHistory = new jHistory($database);
			if (is_integer($last_round_id)) {
				$params = array (
					'user_id' => $this->user_id,
					'round_id' => $round_id
				);
				$jHistory->loadByParams($params);
			}
			$jTipsUser_season = new jTipsUser($database);
			$params = array (
				'user_id' => $this->user_id,
				'season_id' => $season_id
			);
			$jTipsUser_season->loadByParams($params);
			$jRound->load($last_round_id);
			$total = $jHistory->getTotal($this, 'points');
			if (!isset ($total)) {
				$total = 0;
			}
			$average = $total / (is_numeric($jRound->round) && $jRound->round > 0 ? $jRound->round : 1);
			if (!isset ($average)) {
				$average = 0;
			}
			$scores[$season_id] = array (
				'total_points' => $total,
				'total_precision' => $jHistory->getTotal($this,
				'precision'
			), 'average' => $average, 'doubleup' => $jTipsUser_season->doubleup, 'paid' => $jTipsUser_season->paid);
		}
		return $scores;
	}

	//Deprecated - use count(loadAll($season_id))
	function getTotalUsers() {
		$params = array (
			'season_id' => $this->season_id,
			'status' => 1
		);
		/*$total = $this->loadByParams($params);
		if (!is_array($total)) {
			$total = array($this);
		}
		return count($total);*/
		return $this->getCount($params);
	}

	function hasTipped($round_id) {
		global $database;
		$query = "SELECT COUNT(*) FROM #__jtips_tips t JOIN #__jtips_games g ON t.game_id = g.id WHERE t.user_id = " . $this->id . " AND g.round_id = $round_id;";
		jTipsLogger::_log("Checking if user has tipped for round_id = $round_id - QUERY: $query");
		$database->setQuery($query);
		$res = $database->loadResult();
		if (is_numeric($res) and $res > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function exists() {
		if (isset ($this->season_id) && !empty ($this->season_id)) {
			return TRUE;
		}
		return FALSE;
	}

	function sort(& $jTipsUsers, $sort_on = 'points', $sort_dir = 'asc') {
		global $database;
		$sorter = array ();
		$jHistory = new jHistory($database);
		$users = array ();
		foreach ($jTipsUsers as $jTipsUser) {
			$jTipsUser->getTotalScore($sort_on); //make sure the field is set
			array_push($users, $jTipsUser);
		}
		if ($sort_dir == 'asc') {
			$direction = 1;
		} else {
			$direction = -1;
		}
		jTipsSortArrayObjects($users, $sort_on, $direction);
		return $users;
	}

	//Simply retrieve the rank field for a given round
	//from the history table
	function getRank($round_id = FALSE) {
		global $database;
		$jHistory = new jHistory($database);
		if ($round_id === FALSE) {
			$jSeason = new jSeason($database);
			$jSeason->load($this->season_id);
			$round_id = $jSeason->getLastRound();
		}
		$params = array (
			'user_id' => $this->id,
			'round_id' => $round_id
		);
		$jHistory->loadByParams($params);
		return $jHistory->rank;
	}

	function getRoundScore($field, $round_id) {
		global $database;
		if (!isset ($this-> $field)) {
			$jHistory = new jHistory($database);
			$params = array (
				'round_id' => $round_id,
				'user_id' => $this->id
			);
			$jHistory->loadByParams($params);
			if ($jHistory->exists()) {
				$this-> $field = $jHistory-> $field;
			} else {
				$this-> $field = 0;
			}
		}
		return $this-> $field;
	}

	function getTotalScore($field) {
		global $database;
		/*
		if (!isset($this->$field)) {
			$jHistory = new jHistory($database);
			$this->$field = $jHistory->getTotal($this, $field);
		}
		return $this->$field;
		*/
		$jHistory = new jHistory($database);
		return $jHistory->getTotal($this, $field);
	}

	//Rewrite - done
	function getSeasons() {
		global $database;
		$query = "SELECT season_id FROM #__jtips_users WHERE user_id = " . $this->user_id . ";";
		$database->setQuery($query);
		$list = (array) $database->loadAssocList();
		$season_ids = array ();
		foreach ($list as $row => $id) {
			array_push($season_ids, $id['season_id']);
		}
		return $season_ids;
	}

	//Rewrite - Done
	function inSeason(& $jSeason) {
		global $database;
		$params = array (
			'user_id' => $this->user_id,
			'season_id' => $jSeason->id
		);
		$jTipsUser = new jTipsUser($database);
		$jTipsUser->loadByParams($params);
		if (isset ($jTipsUser->season_id) && !empty ($jTipsUser->season_id) && $jTipsUser->season_id == $jSeason->id) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function getSeasonName() {
		global $database;
		if (!isset ($this->season_name)) {
			$jSeason = new jSeason($database);
			$jSeason->load($this->season_id);
			$this->season_name = $jSeason->name;
		}
		return $this->season_name;
	}

	function getUserField($field) {
		global $database;
		if (!isset ($this-> $field)) {
			if (isJoomla15()) {
				$mosUser = new JUser();
			} else {
				$mosUser = new mosUser($database);
			}
			$mosUser->load($this->user_id);
			$this->$field = $mosUser-> $field;
		}
		return $this-> $field;
	}

	/**
	 * Get the name of the user based on config settings
	 *
	 * @return string The name of the current user
	 */
	function getName() {
		global $jTips;
		if (!isset ($this->name)) {
			$this->name = $jTips['DisplayName'] == 'user' ? $this->getUserField('username') : $this->getUserField('name');
		}
		return $this->name;
	}

	function export() {
		global $database;
		$query = "SELECT u.name AS 'Full Name', u.username AS 'User Name', u.email AS 'Email Address', " .
		"s.name AS 'Season Name', (CASE WHEN ju.status = 1 THEN 'Yes' ELSE 'No' END) AS 'Active', " .
		"(CASE WHEN ju.paid = 1 THEN 'Yes' ELSE 'No' END) AS 'Paid', " .
		"r.round AS 'DoubleUP Round' " .
		"FROM " . $this->_tbl . " ju LEFT JOIN #__jtips_rounds r ON ju.doubleup = r.id " .
		"JOIN #__users u ON ju.user_id = u.id JOIN #__jtips_seasons s ON ju.season_id = s.id " .
		"ORDER BY s.name, u.username";
		//jTipsDebug($query);
		//die();
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

	function sendNotificationEmail($type) {
		jTipsLogger::_log('preparing to send ' . $type . ' notification email', 'INFO');
		global $jTips, $database;
		$subject = stripslashes($jTips["UserNotify" . $type . "Subject"]);
		$message = stripslashes($jTips["UserNotify" . $type . "Message"]);
		$from_name = $jTips['UserNotifyFromName'];
		$from_email = $jTips['UserNotifyFromEmail'];
		$variables = array ();
		$values = array ();
		foreach (get_object_vars($this) as $key => $val) {
			if (is_string($key)) {
				array_push($variables, $key);
				$values[$key] = $val;
			}
		}
		if (isJoomla15()) {
			$user = new JUser();
		} else {
			$user = new mosUser($database);
		}
		$user->load($this->user_id);
		foreach (get_object_vars($user) as $key => $val) {
			if (is_string($key)) {
				array_push($variables, $key);
				$values[$key] = $val;
			}
		}
		// find out which season this is for an add it to the avaialble variables
		$query = "SELECT name FROM #__jtips_seasons WHERE id = '" .$this->season_id. "'";
		$database->setQuery($query);
		$season = $database->loadResult();
		$values['competition'] = $season;
		$values['season'] = $season;
		$body = parseTemplate($message, $variables, $values);
		jTipsLogger::_log('sending email: ' . $body, 'INFO');
		if (jTipsMail($from_email, $from_name, $this->getUserField('email'), $subject, $body)) {
			jTipsLogger::_log('notification email sent successfully', 'INFO');
			return TRUE;
		} else {
			jTipsLogger::_log('sending notification email failed', 'ERROR');
			return FALSE;
		}
	}

	/**
	 * Determines the accuracy of this user's tipping
	 *
	 * @param [int] The round id. If non supplied, returns overall accuracy
	 */
	function getTipsAccuracy($round_id=false) {
		$query = "SELECT COUNT(*) AS accuracy, (CASE WHEN t.tip_id = g.winner_id THEN 1 ELSE 0 END) AS result " .
				"FROM #__jtips_tips t JOIN #__jtips_games g ON t.game_id = g.id AND t.user_id = " .$this->id;
				//"GROUP BY result"
		if ($round_id) {
			$query .= " AND g.round_id = " .$this->_db->Quote($round_id);
		}
		$query .= " GROUP BY result";
		//jTipsDebug($query);
		$this->_db->setQuery($query);
		$rows = $this->_db->loadAssocList();
		$top = 0;
		$bottom = 1;
		$correct = $incorrect = 0;
		foreach ($rows as $row) {
			if ($row['result'] == 1) {
				$top = $row['accuracy'];
				$correct = $row['accuracy'];
			} else {
				$incorrect = ($row['accuracy'] == 0 ? 1 : $row['accuracy']);
			}
		}
		if (($correct + $incorrect) > 0) {
			$bottom = $correct + $incorrect;
		}
		return round((($top/$bottom) * 100), 2);
	}
}
?>
