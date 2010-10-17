<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

global $mosConfig_absolute_path;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jcomment.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jhistory.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jteam.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jtip.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/juser.class.php');

class jRound extends jTipsDBTable {
	var $id = null;
	var $round = null;
	var $season_id = null;
	var $start_time = null;
	var $end_time = null;
	var $scored = null;
	var $updated = null;

	function jRound(& $db) {
		$this->mosDBTable('#__jtips_rounds', 'id', $db);
	}

	function save() {
		$bind = array ();
		$this->updated = gmdate('Y-m-d H:i:s');
		foreach ($this as $prop => $val) {
			$bind[$prop] = $val;
		}
		// BUG 317 - unset properties not in the rounds table
		unset($this->prev);
		unset($this->next);
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

	//Alias function for delete
	function destroy($id = false) {
		global $database;
		if ($id == false) {
			$id = $this->id;
		}
		//this will delete all history for this round as well as all games
		//destroy the games first
		$params = array(
			'round_id' => $id
		);
		$jGame = new jGame($database);
		$jGames = forceArray($jGame->loadByParams($params));
		if (!empty($jGames)) {
			foreach ($jGames as $game) {
				$game->destroy();
			}
		}

		//now destroy the history
		$jHistory = new jHistory($database);
		$jHistories = forceArray($jHistory->loadByParams($params));
		if (!empty($jHistories)) {
			foreach ($jHistories as $history) {
				$history->destroy();
			}
		}

		//delete any comments for this round
		$jComment = new jComment($database);
		$jComments = forceArray($jComment->loadByParams($params));
		if (!empty($jComments)) {
			jTipsLogger::_log('deleting comments');
			foreach ($jComments as $comment) {
				$comment->destroy();
			}
		}

		//finally, delete the round
		jTipsLogger::_log("Delete " . $this->_tbl . " record with id = '$id'");
		return $this->delete($id);
	}

	function loadByParams($params = array (), $limit = false, $offset = false) {
		global $database;
		$extra_clause = "";
		if ($limit !== false and !empty ($limit)) {
			$extra_clause .= " LIMIT $limit";
		}
		if ($offset !== false and !empty ($offset)) {
			$extra_clause .= " OFFSET $offset";
		}
		$query = "SELECT " . $this->_tbl . ".id FROM " . $this->_tbl . " " . buildWhere($params) . " $extra_clause;";
		$database->setQuery($query);
		$database->query();
		if ($database->getNumRows() > 1) {
			$jObjs = array ();
			$list = (array) $database->loadAssocList();
			foreach ($list as $row => $id) {
				$jObj = new jRound($database);
				$jObj->load($id['id']);
				array_push($jObjs, $jObj);
			}
			return $jObjs;
		} else
		if ($database->getNumRows() == 1) {
			$this->load($database->loadResult());
			return $this;
		} else {
			return false;
		}
	}

	function getCount($params = array ()) {
		global $database;
		//		$where_clause = "";
		//		foreach ($params as $key => $val) {
		//			if (is_array($val)) {
		//				$where_clause .= "$key ";
		//				switch ($val['type']) {
		//					case 'in' :
		//						$where_clause .= "IN (" . $val['query'] . ") AND ";
		//						break;
		//					case 'join' :
		//						break;
		//					case 'left_join' :
		//						break;
		//				}
		//			} else {
		//				$where_clause .= "$key = " . (is_numeric($val) ? $val : "'$val'") . " AND ";
		//			}
		//		}
		$where_clause = buildWhere($params);//substr($where_clause, 0, -5);
		$query = "SELECT COUNT(*) FROM " . $this->_tbl . " " .(strlen($where_clause) > 0 ? $where_clause : "");
		jTipsLogger::_log('ROUNDS COUNT QUERY: ' .$query);
		$database->setQuery($query);
		return intval($database->loadResult());
	}

	//CALL count($jGame->loadAll($round_id)) instead
	/*function getNumGames() {
	global $mainframe, $database;
	$db =& $database;
	$query = "SELECT COUNT(*) FROM #__jtips_games WHERE roundid = " .$this->roundid. ";";
	$db->setQuery($query);
	return $db->loadResult();
	}*/

	function getNextLogicalRound() {
		global $mainframe, $database;
		$season = "";
		$query = "SELECT MAX(g.round_id) FROM #__jtips_games g JOIN #__jtips_rounds r ON g.round_id = r.id WHERE r.season_id = " . $this->get('season_id') . ";";
		$database->setQuery($query);
		$round_id = $database->loadResult();
		$query = "SELECT round FROM #__jtips_rounds WHERE round_id = $round_id;";
		$database->setQuery($query);
		$round = $database->loadResult();
		$jSeason = new jSeason($database);
		$jSeason->load($this->get('season_id'));
		if ($jSeason->get('rounds') == $round) {
			return $round;
		} else {
			return $round +1;
		}
	}

	//Deprecated - Moved to the jSeason class
	/*function getCurrentRound($rid=false, $season_id=false) {
	global $mainframe, $database;
	$db = & $database;
	$mosConfig_offset = $mainframe->getCfg('offset');
	$season = "";
	if (is_numeric($season_id)) {
	$season = "AND season_id = $season_id";
	} else if ($this->get('season_id')) {
	$season = "AND season_id = " .$this->get('season_id');
	}
	if ($rid < 1) {
	$query = "SELECT roundid FROM #__jtips_rounds WHERE (scored = 0 OR scored IS NULL) AND end > " .(time()+($mosConfig_offset*60*60)). " $season ORDER BY round ASC LIMIT 1;";
	$db->setQuery($query);
	$rid = $db->loadResult();
	}
	$query = "SELECT roundid FROM #__jtips_rounds WHERE " .($rid ? "roundid = $rid" : "end > " .(time()+($mosConfig_offset*60*60))). " $season ORDER BY round ASC LIMIT 1;";
	$db->setQuery($query);
	$db->query();
	if ($db->getNumRows() == 0) {
	$query = "SELECT MAX(roundid) FROM #__jtips_rounds WHERE " .substr($season, 4). ";";
	$db->setQuery($query);
	$roundid = $db->loadResult();
	if ($roundid) {
	$this->getCurrentRound($roundid, $season_id);
	}
	}
	$this->load($db->loadResult());
	if ($this->exists()) {
	return $this;
	}
	return false;
	}*/

	//CALL jGame->loadAll($reason_id) INSTEAD
	function getGames() {
		//return jGame::loadAll($this->get('id'));
		/*global $database;
		$db =& $database;
		$query = "SELECT gameid FROM #__jtips_games WHERE roundid = " .$this->get('roundid'). " ORDER BY position ASC, gameid ASC;";
		$db->setQuery($query);
		$list = $db->loadAssocList();
		$games = array();
		if (!is_array($list)) {
		return $games;
		}
		foreach ($list as $id) {
		$jGame = new jGame($database);
		$jGame->load($id['gameid']);
		$games[] = $jGame;
		}
		return $games;*/
	}

	function roundOver() {
		if ($this->scored == 1) {
			return true;
		}
		return false;
	}

	function getStatus() {
		if ($this->roundOver()) {
			//Finished and has been scored
			return 1;
		} else
		if ($this->start_time < gmdate('Y-m-d H:i:s') && $this->end_time > gmdate('Y-m-d H:i:s')) {
			//in progress
			return 0;
		} else
		if ($this->end_time < gmdate('Y-m-d H:i:s') && !$this->roundOver()) {
			//pending results
			return -1;
		} else {
			//not started
			return false;
		}
	}

	//Deprecated
	//Use getNext instead
	/*function roundHasNext() {
	global $database;
	$query = "SELECT COUNT(*) FROM #__jtips_rounds WHERE `round` > " .$this->get('round'). " AND season_id = " .$this->get('season_id'). ";";
	$database->setQuery($query);
	if ($database->loadResult() > 0) {
	return true;
	}
	return false;
	}*/

	//Returns the id of the next round or false if there is none
	function getNext() {
		global $database;
		if (!isset ($this->next)) {
			$query = "SELECT id FROM #__jtips_rounds WHERE round > " . $this->round . " AND season_id = " . $this->season_id . " ORDER BY round ASC LIMIT 1;";
			$database->setQuery($query);
			$id = $database->loadResult();
			$this->next = is_numeric($id) ? $id : false;
		}
		return $this->next;
	}

	//Deprecated
	//Use getPrev instead
	/*function roundHasPrev($season_id=false) {
	global $database;
	$db =& $database;
	$season = "";
	$query = "SELECT COUNT(*) FROM #__jtips_rounds WHERE `round` < " .$this->get('round'). " AND season_id = " .$this->get('season_id'). ";";
	$db->setQuery($query);
	if ($db->loadResult() > 0) {
	return true;
	}
	return false;
	}*/

	//Returns the id of the previous round or false if there is none
	function getPrev($season_id = false, $force=false, $scored=false) {
		global $database;
		if (!isset ($this->prev)) {
			$query = "SELECT id FROM #__jtips_rounds WHERE round < " . $this->round . " AND season_id = " . $this->season_id . " " .($scored ? "AND scored = 1" : ""). " ORDER BY round DESC";
			$database->setQuery($query, 0, 1);
			$id = $database->loadResult();
			$this->prev = is_numeric($id) ? $id : false;

			if ($this->prev == false and $force) {
				//only one round exists and it has been completed
				//make sure there is only one round
				//and that this isn't the last round in the season
				$query = "SELECT COUNT(*) FROM #__jtips_rounds WHERE season_id = " .$database->quote($this->season_id);
				$database->setQuery($query);
				$count = $database->loadResult();
				if ($count == 1) {
					$this->prev = $this->id;
				}
			}
		}
		return $this->prev;
	}

	function exists() {
		if (isset ($this->id) && !empty ($this->id)) {
			return true;
		}
		return false;
	}

	//add check for enableprecision in config - if enabled then sort by precision desc
	//else sort by round score desc
	function getRoundWinners() {
		global $database, $jTips;
		//$jSeason = new jSeason($database);
		//$jSeason->load($this->season_id);
		//Bug 209 - late entrants appear as last round winners
		//get the users that have tipped for this round
		$query = "SELECT user_id FROM #__jtips_tips t JOIN #__jtips_games g ON t.game_id = g.id AND g.round_id = " .$database->Quote($this->getPrev(false, true, true)). " GROUP BY t.user_id";
		$database->setQuery($query);
		$user_ids = $database->loadResultArray();

		//Get the best score
		$query = "SELECT MAX(points) AS points FROM #__jtips_history WHERE round_id = " .$database->Quote($this->getPrev(false, true, true)). " AND user_id IN ('" .implode("', '", $user_ids). "')";
		//jTipsDebug($query);
		$database->setQuery($query);
		$max = $database->loadResult();
		if (is_null($max)) return array();
		$num_to_show = $jTips['NumDefault'];
		$query = "SELECT user_id FROM #__jtips_history WHERE points = $max AND round_id = " .$database->Quote($this->getPrev(false, true, true)) . " ORDER BY `precision` ASC" .($num_to_show ? " LIMIT $num_to_show" : "");
		$database->setQuery($query);
		$row = $database->loadResultArray();
		$users = array ();
		if (is_array($row) and !empty ($row)) {
			foreach ($row as $id) {
				$jTipsUser = new jTipsUser($database);
				$jTipsUser->load($id);
				array_push($users, $jTipsUser);
			}
		}
		return $users;
	}

	function getLastRoundWinners(& $jTipsUser, $season_id = false) {
		global $database;
		if ($season_id === false) {
			$season_ids = $jTipsUser->getSeasons();
		} else {
			$season_ids = array($season_id);
		}
		$winners = array ();
		foreach ($season_ids as $season_id) {
			$jSeason = new jSeason($database);
			$jSeason->load($season_id);
			//$round_id = $jSeason->getLastRound();
			if ($this->id) {
				$jRound = new jRound($database);
				$jRound->load($this->id);
				$winners[$season_id] = $jRound->getRoundWinners();
			} else {
				continue;
			}
		}
		//jTipsDebug(count($winners));
		return $winners;
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

	/**
	 * Delete all history related to this round
	 */
	function clearHistory() {
		global $database;
		$params = array(
			'round_id' => $this->id
		);
		$jHistory = new jHistory($database);
		$jHistories = forceArray($jHistory->loadByParams($params));
		foreach ($jHistories as $jHistory) {
			$jHistory->destroy();
		}
	}

	function export() {
		global $database;
		$query = "SELECT s.name AS 'Season Name', r.round AS 'Round', r.start_time AS 'Round Start', " .
		"r.end_time AS 'Round End', scored AS 'Processed' FROM " . $this->_tbl . " r " .
		"JOIN #__jtips_seasons s ON r.season_id = s.id ORDER BY s.name, r.start_time";
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
				if (preg_match('/start/i', $heading) or preg_match('/end/i', $heading)) {
					//$val = getLocalDate($val);
					//BUG 277 - call to undefined function
					$val = TimeDate::toDisplayDateTime($val);
				}
				array_push($line, str_replace('"', "'", addslashes($val)));
			}
			array_push($lines, '"' . implode('","', $line) . '"');
			$row = next($rows);
		}
		$body = implode("\r\n", $lines);
		$export = '"' . implode('","', $headers) . '"' . "\r\n" . $body;
		return $export;
	}

	//THIS FUNCTION SHOULDN'T BE HERE!
	//MOVE TO jTip AND CLEAN UP
	function getUpdateTime($userid) {
		global $database, $mainframe;
		$db = & $database;
		$mosConfig_offset = $mainframe->getCfg('offset');
		$query = "SELECT MAX(time) FROM #__jtips_tips t JOIN #__jtips_games g ON t.gameid = g.gameid AND g.roundid = " . $this->get('roundid') . " AND t.userid = $userid;";
		$db->setQuery($query);
		$res = $db->loadResult();
		if (is_numeric($res)) {
			return $res + ($mosConfig_offset * 60 * 60);
		} else {
			return false;
		}
	}

	function process() {
		global $database, $jTips, $mosConfig_absolute_path;
		$this->clearHistory();
		$params = array(
			'round_id' => $this->id
		);
		$jSeason = new jSeason($database);
		$jSeason->load($this->season_id);
		$jGame = new jGame($database);
		$jGames = forceArray($jGame->loadByParams($params));
		$params = array(
			'season_id' => $this->season_id
		);
		$jTipsUser = new jTipsUser($database);
		$jTipsUsers = forceArray($jTipsUser->loadByParams($params));
		$noTips = $scores = $worst_precision = array();
		$played = count($jGames);
		foreach ($jTipsUsers as $jTipsUser) {
			jTipsLogger::_log("Processing scores for user " .$jTipsUser->id);
			$score = $matching = $precision = $allAwayScore = 0;
			if ($jTipsUser->hasTipped($this->id)) {
				jTipsLogger::_log($jTipsUser->id. " has tipped in round " .$this->id);
				foreach ($jGames as $jGame) {
					jTipsLogger::_log("Processing game " .$jGame->id);
					$params = array(
						'user_id' => $jTipsUser->id,
						'game_id' => $jGame->id
					);
					$jTip = new jTip($database);
					$jTip->loadByParams($params);

					// make sure this is not a bye game
					if (!$jGame->home_id or !$jGame->away_id or !$jGame->winner_id) {
						jTipsLogger::_log('attempting to process tips on a bye game, skipping', 'INFO');
						continue;
					}

					/*
					 * Feature Request 101 - Team Starts
					 * Determine the winner when we take the starts into account
					 * We only care about the starts for picking the winner/draw
					 * For picking the margins and scores, use the actual winner
					 */
					if ($jSeason->team_starts) {
						jTipsLogger::_log('processing team starts');
						$homeScore = $awayScore = 0;
						$homeScore = $jGame->home_score + ($jGame->home_start + 0);
						$awayScore = $jGame->away_score + ($jGame->away_start + 0);
						if ($homeScore > $awayScore) {
							$winnerID = $jGame->home_id;
						} else if ($homeScore < $awayScore){
							$winnerID = $jGame->away_id;
						} else if ($homeScore == $awayScore){
							$winnerID = -1;
						}
						jTipsLogger::_log('feature 101: With starts, the winner is ' .$winnerID. ', otherwise the winner is ' .$jGame->winner_id. " HOME $homeScore v AWAY $awayScore");
					} else {
						$winnerID = $jGame->winner_id;
					}

					if ($jTip->tip_id == $winnerID) {
						//User tipped right!
						jTipsLogger::_log("CORRECT TIP by " .$jTipsUser->id. " in round_id " .$this->id. " in game_id " .$jGame->id);
						//BUG 248 - Add ToughScore if enabled
						if ($jSeason->tough_score and $jGame->tough_score) {
							$score += $jGame->tough_score;
						}
						if ($winnerID == -1) {
							$score += isset($jSeason->user_draw) ? $jSeason->user_draw : 0;
							jTipsLogger::_log("Draw correctly picked!");
						} else {
							$score += isset($jSeason->user_correct) ? $jSeason->user_correct : 0;
						}
						$matching++;
					}

					if ($winnerID == $jGame->away_id) {
						$allAwayScore += $jSeason->user_correct;
					}

					//Check for correct margins and handle precision score gathering
					if ($jSeason->pick_margin == 1 and $jGame->has_margin == 1) {
						$margin = abs($jGame->home_score - $jGame->away_score);
						if ($jTip->margin == $margin) {
							$score += isset($jSeason->user_pick_margin) ? $jSeason->user_pick_margin : 0;
							jTipsLogger::_log("correct margin picked!");
						}
						if ($jSeason->precision_score == 1) {
							if ($jGame->winner_id == $jTip->tip_id) {
								$margin_offset = abs(($margin - $jTip->margin));
							} else {
								$margin_offset = abs(($margin + $jTip->margin));
							}
							if ((isset($worst_precision[$jGame->id]) && $margin_offset > $worst_precision[$jGame->id]) || empty($worst_precision[$jGame->id])) {
								$worst_precision[$jGame->id] = $margin_offset;
							}
							$precision += $margin_offset;
							jTipsLogger::_log("PICK_MARGIN: Adding $margin_offset to precision of $precision");
						}
					}
					//Check for correct scores and handle precision score gathering
					if ($jSeason->pick_score == 1 and $jGame->has_score == 1) {
						$margin = abs($jGame->home_score - $jGame->away_score);
						if ($jTip->home_score == $jGame->home_score and $jTip->away_score == $jGame->away_score) {
							$score += isset($jSeason->user_pick_score) ? $jSeason->user_pick_score : 0;
							jTipsLogger::_log("Correct scores picked!");
						}
						if ($jSeason->precision_score == 1) {
							$pickedScoreMargin = abs(($jTip->home_score - $jTips->away_score));
							if ($jGame->winner_id == $jTip->tip_id) {
								$score_offset = abs(($margin - $pickedScoreMargin));
							} else {
								$score_offset = abs(($margin + $pickedScoreMargin));
							}
							if ((isset($worst_precision[$jGame->id]) and $score_offset > $worst_precision[$jGame->id]) or empty($worst_precision[$jGame->id])) {
								$worst_precision[$jGame->id] = $score_offset;
							}
							$precision += $score_offset;
							jTipsLogger::_log("PICK_SCORE: Adding $score_offset to precision of $precision");
							jTipsLogger::_log("PREC DEBUG: {$jTipsUser->id}-{$jTipsUser->user_id} Picked Margin: $pickedScoreMargin. Actual Margin: $margin. Applied Precision: $score_offset. Running Precision: $precision", 'INFO');
						}
					}
					//Check for a bonus team selection
					if ($jSeason->pick_bonus >= 1 and $jGame->has_bonus == 1) {
						if ($jTip->bonus_id == $jGame->bonus_id && $jGame->bonus_id != -1) {
							$score += isset($jSeason->user_pick_bonus) ? $jSeason->user_pick_bonus : 0;
						}
					}
				}
				//was a perfect round picked?
				if ($matching == $played) {
					$score += isset($jSeason->user_bonus) ? $jSeason->user_bonus : 0;
				}
				//did the user use their 'doubleup'
				if ($jTipsUser->doubleup == $this->id and $jTips['DoubleUp'] == 1) {
					$score = $score * 2;
				}
				$scores[] = $score;
				//Save the data to the history object
				$jHistory			= new jHistory($database);
				$jHistory->user_id	= $jTipsUser->id;
				$jHistory->round_id	= $this->id;
				jTipsLogger::_log("Score for user_id " .$jTipsUser->id. " in round_id " .$this->id. " is $score");
				$jHistory->points	= $score;
				//Update rank after all users have been saved
				$jHistory->outof	= count($jTipsUsers);
				//$jHistory->comment	= $jTipsUser->comment;
				if ($jSeason->precision_score == 1) {
					jTipsLogger::_log("setting precision to $precision for user_id " .$jTipsUser->id. " in round_id " .$this->id);
					$jHistory->precision = $precision;
				} else {
					$jHistory->precision = 0;
				}
				if ($jHistory->save() !== false) {
					$results[] = 1;
				} else {
					jTipsLogger::_log("Error saving history: " .$jHistory->_error);
					$results[] = 0;
				}
				//remove the current comment
				$jTipsUser->comment = null;
				$jTipsUser->save();

				// Check if the AlphaUserPoints config option is set
				if (isJoomla15()) {
					$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
				} else {
					$api_AUP = $mosConfig_absolute_path. 'components/com_alphauserpoints/helper.php';
				}
				if (!$this->scored and $jTips['AlphaUserPoints'] and jTipsFileExists($api_AUP)) {
					require_once($api_AUP);
					jTipsLogger::_log('sending ' .$score. ' points for user ' .$jTipsUser->user_id, 'INFO');
					$refID = AlphaUserPointsHelper::getAnyUserReferreID($jTipsUser->user_id);
					AlphaUserPointsHelper::newpoints('plgaup_jtips_total_points', $refID, '', '', $score);
				}
				if (!$this->scored and $jTips['JomSocialActivities'] and $jTips['JomSocialUserResults']) {
					global $mosConfig_absolute_path;
					require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/jTipsJomSocial.php');
					jTipsJomSocial::writeRoundResult($jSeason, $this, $jTipsUser->user_id, $score);
				}
			} else {
				$noTips[] = $jTipsUser;
			}
		}
		if (count($noTips) > 0) {
			/////////////////////////////////////////////////
			// Feature Request 71
			// Allow users that did not to be assigned
			// all the away teams
			//
			/*if ($jSeason->user_none != -1) {
			$thisRound = $jSeason->user_none;
			} else if (is_array($scores) && count($scores) > 0) {
			$thisRound = min($scores);
			} else {
			$thisRound = 0;
			}*/
			if ($jSeason->user_none == -2) {
				//handle all away teams
				$thisRound = $allAwayScore;
				jTipsLogger::_log("didn't tip? You'll be stuck with the away teams. You got $thisRound");
			} else if ($jSeason->user_none == -1) {
				//handle lowest possible score
				if (is_array($scores) and count($scores) > 0) {
					$thisRound = min($scores);
					jTipsLogger::_log("didn't tip? You'll be stuck with the lowest score this round, $thisRound");
				} else {
					$thisRound = 0;
					jTipsLogger::_log("didn't tip? You'll be stuck $thisRound");
				}
			} else {
				//handle allocated score
				$thisRound = $jSeason->user_none;
				jTipsLogger::_log("didn't tip? You're getting $thisRound");
			}
			//
			// END Feature Request 71
			////////////////////////////////////////////////////

			foreach ($noTips as $jTipsUser) {
				$jHistory				= new jHistory($database);
				$jHistory->user_id		= $jTipsUser->id;
				$jHistory->round_id		= $this->id;
				$jHistory->points		= $thisRound;
				$jHistory->precision	= array_sum($worst_precision);
				//$jHistory->outof		= count($jTipsUsers);
				//$jHistory->comment	= $jTipsUser->comment;
				if ($jHistory->save() !== false) {
					$results[] = 1;
				} else {
					$results[] = 0;
				}
				$jTipsUser->save();
				// Check if the AlphaUserPoints config option is set
				if (isJoomla15()) {
					$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
				} else {
					$api_AUP = $mosConfig_absolute_path. 'components/com_alphauserpoints/helper.php';
				}
				if (!$this->scored and $jTips['AlphaUserPoints'] and jTipsFileExists($api_AUP)) {
					require_once($api_AUP);
					jTipsLogger::_log('sending ' .$score. ' points for user ' .$jTipsUser->user_id, 'INFO');
					$refID = AlphaUserPointsHelper::getAnyUserReferreID($jTipsUser->user_id);
					AlphaUserPointsHelper::newpoints('plgaup_jtips_total_points', $refID, '', '', $thisRound);
				}
				if (!$this->scored and $jTips['JomSocialActivities']) {
					global $mosConfig_absolute_path;
					require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/jTipsJomSocial.php');
					if ($jTips['JomSocialUserResults']) {
						jTipsJomSocial::writeRoundResult($jSeason, $this, $jTipsUser->user_id, $score);
					}
					if ($jTips['JomSocialOnNoTips']) {
						jTipsJomSocial::writeOnNoTips($jTipsUser->user_id, $jSeason, $this);
					}
				}
			}
		}
		$jHistory = new jHistory($database);
		$jHistory->setRanks($this->id, true);
		if (!$this->scored and $jTips['JomSocialActivities']) {
			// find out who won the round and write it to the JomSocial stream
			$winners = $this->getRoundWinners();
			jTipsJomSocial::writeRoundWinners($winners, $this, $jSeason);
		}
		$this->scored = 1;
		$result = $this->save();
		//if ($this->scored != 1) {
		jTeam::updateLadder($this, $jSeason);
		//}
		//$this->scored = 1;
		//return $this->save();
		return $result;
	}

	function getFieldMap() {
		require ('components/com_jtips/fieldmap/jround.fields.php');
		return $fields;
	}

	/**
	 * Take an array of data values,
	 * Format them according to the field map
	 * and save in a new object
	 */
	function import($data, $mapping, $match_on) {
		global $database;
		if (!is_array($data) or empty ($data) or empty ($mapping)) {
			return false;
		}
		$defs = & $this->getFieldMap();
		$class = get_class($this);
		$jObject = new $class ($database);
		$mapped_fields = $params = array ();
		foreach ($mapping as $column => $fieldmap) {
			if (array_key_exists($fieldmap, $defs)) {
				$field = $defs[$fieldmap];
				if (!isset ($field['import']) or $field['import'] == false) {
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
		if (!empty ($params)) {
			$jObject = new $class ($database);
			$jObject->loadByParams($params);
		}
		foreach ($mapped_fields as $key => $val) {
			$jObject-> $key = $val;
		}
		foreach ($defs as $key => $def) {
			if (!array_key_exists($key, $mapped_fields)) {
				if (isset ($def['default'])) {
					$jObject-> $key = $def['default'];
				}
			}
		}
		return $jObject->save();
	}
}
?>
