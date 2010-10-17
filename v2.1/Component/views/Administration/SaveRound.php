<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1 - 14/10/2008
 * @version 2.1
 * @package jTips
 *
 * Description: Adds or Updates a round and game information
 */

jTipsSpoofCheck();

ob_clean();
ob_start();

global $database, $jTips, $mosConfig_absolute_path;

//jTipsDebug($_REQUEST);
//die();
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');

$jRound = new jRound($database);
$jSeason = new jSeason($database);
if ($round_id = jTipsGetParam($_REQUEST, 'id', false)) {
	$jRound->load($round_id);
}
$season_id = jTipsGetParam($_REQUEST, 'season_id', false);
$jSeason->load($season_id);
$results = $games_to_keep = array();
//jTipsLogger::_log($_REQUEST);
jTipsLogger::_log('about to save round and games');

//first, save the round info
$start_time_date  = jTipsGetParam($_REQUEST, 'date_start_date', strftime($jTips['DateFormat'])). " ";
$start_minute = jTipsGetParam($_REQUEST, 'date_start_time_minute', '');
$start_time_time = jTipsGetParam($_REQUEST, 'date_start_time_hour', ''). ":" .str_pad($start_minute, 2, '0', STR_PAD_LEFT).jTipsGetParam($_REQUEST, 'date_start_time_meridiem', '');
if (!preg_match('/\d/', $start_time_time)) {
	$start_time_time = strftime($jTips['TimeFormat']);
}
$start_time = $start_time_date. " " .$start_time_time;
//BUG 263 - set the date fields if we are in J1.0
if (!isJoomla15()) {
	$jRound->start_time = TimeDate::toDisplayDate($jRound->start_time);
}
$jRound->start_time = TimeDate::toDatabaseDateTime($start_time);
$end_time_date  = jTipsGetParam($_REQUEST, 'date_end_date', strftime($jTips['DateFormat'])). " ";
$end_minute = jTipsGetParam($_REQUEST, 'date_end_time_minute', '');
$end_time_time = jTipsGetParam($_REQUEST, 'date_end_time_hour', ''). ":" .str_pad($end_minute, 2, '0', STR_PAD_LEFT).jTipsGetParam($_REQUEST, 'date_end_time_meridiem', '');
if (!preg_match('/\d/', $end_time_time)) {
	$end_time_time = strftime($jTips['TimeFormat']);
}
$end_time = $end_time_date. " " .$end_time_time;
//BUG 263 - set the date fields if we are in J1.0
if (!isJoomla15()) {
	$jRound->end_time = TimeDate::toDisplayDate($jRound->end_time);
}
$jRound->end_time = TimeDate::toDatabaseDateTime($end_time);
$jRound->season_id = $season_id;
$jRound->round = jTipsGetParam($_REQUEST, 'roundnum', 0);
if (!is_numeric($jRound->scored)) {
	$jRound->scored = 0;
}
if ($jRound->save()) {
	$message = 'Round Saved!';
} else {
	$message = 'Save Failed: ' .$jRound->_error;
}


$games = jTipsGetParam($_REQUEST, 'game', array());
for ($i=0; $i<count($games); $i++) {
	$jGame = new jGame($database);
	if (jTipsGetParam($_REQUEST, 'id', false)) {
		$game_id = jTipsGetParam($games, $i, null);
		if (is_numeric($game_id)) { // BUG 369 - IE passes in string 'null'!
			$jGame->load($game_id);
		}
	}
	$jGame->round_id = $jRound->id;
	if ($jRound->getStatus() === false) {
		$jGame->home_id = jTipsGetParam($_REQUEST['home_id'], $i, -1);
		$jGame->away_id = jTipsGetParam($_REQUEST['away_id'], $i, -1);
		$jGame->position = jTipsGetParam($_REQUEST['position'], $i, null);
	}

	//see the additional options for this game
	//first unset the all
	$jGame->has_margin = $jGame->has_score = $jGame->has_bonus = 0;
	//now set them again if selected
	$pick_types = jTipsGetParam($_REQUEST['pick_types'], $i, array());
	// BUG 366 - pick options not saved from front-end round manager
	if (is_array($pick_types) and !empty($pick_types)) {
		foreach ($pick_types as $type) {
			$property = 'has_'.$type;
			$jGame->$property = 1;
		}
	} else if (is_string($pick_types) and !empty($pick_types)) {
		$property = 'has_' .$pick_types;
		$jGame->$property = 1;
	}


    if ($jSeason->team_starts > 0 and jTipsGetParam($_REQUEST['home_start'], $i, false)) {
        $jGame->home_start = jTipsGetParam($_REQUEST['home_start'], $i, null);;
    } else {
        if ($jRound->getStatus() === false) {
            $jGame->home_start = null;
        }
    }
    if ($jSeason->team_starts > 0 and jTipsGetParam($_REQUEST['away_start'], $i, false)) {
        $jGame->away_start = jTipsGetParam($_REQUEST['away_start'], $i, null);
    } else {
        if ($jRound->getStatus() === false) {
            $jGame->away_start = null;
        }
    }
    //parse the game time if necessary
    if ($jSeason->game_times and jTipsGetParam($_REQUEST['start_time_date'], $i, false)) {
    	$start_time_date  = jTipsGetParam($_REQUEST['start_time_date'], $i, strftime($jTips['DateFormat'])). " ";
		$start_minute = jTipsGetParam($_REQUEST['start_time_minute'], $i, '');
		$start_time_time = jTipsGetParam($_REQUEST['start_time_hour'], $i, ''). ":" .str_pad($start_minute, 2, '0', STR_PAD_LEFT).jTipsGetParam($_REQUEST['start_time_meridiem'], $i, '');
		if (!preg_match('/\d/', $start_time_time)) {
			$start_time_time = strftime($jTips['TimeFormat']);
		}
		$start_time = $start_time_date. " " .$start_time_time;
		//jTipsDebug($start_time);
		//BUG 263 - set the date fields if we are in J1.0
		if (!isJoomla15()) {
			$start_time = TimeDate::toDisplayDate($start_time_date). " " .$start_time_time;
		}
		$jGame->start_time = TimeDate::toDatabaseDateTime($start_time, false);
    } else {
    	if ($jRound->getStatus() === false) {
    		$jGame->start_time = null;
    	}
    }

    //set the game scores
	if ($jRound->getStatus() !== FALSE) {
		$jGame->home_score = jTipsGetParam($_REQUEST['home_score'], $i, null);
		$jGame->away_score = jTipsGetParam($_REQUEST['away_score'], $i, null);
        /*
         * Can't put this code here since it will destroy the
         * team ladder and standings
         *
         * if ($jSeason->team_starts > 0) {
            if (is_numeric($jGame->home_start)) {
                $jGame->home_score += $jGame->home_start;
            }
            if (is_numeric($jGame->away_score)) {
                $jGame->away_score += $jGame->away_start;
            }
        }*/
		$jGame->draw = null;
		if ($jGame->home_score > $jGame->away_score) {
			$jGame->winner_id = $jGame->home_id;
		} else if ($jGame->away_score > $jGame->home_score) {
			$jGame->winner_id = $jGame->away_id;
		} else if (!$jGame->home_id or !$jGame->away_id) { // handle byes
			$jGame->winner_id = null;
		} else {
			$jGame->winner_id = -1;
			$jGame->draw = 1;
		}
		if ($jSeason->pick_bonus > 0 and jTipsGetParam($_REQUEST['bonus_id'], $i, false)) {
			$jGame->bonus_id = jTipsGetParam($_REQUEST['bonus_id'], $i, null);
		}
	}
	// set the description if we have one
        $jGame->description = strip_tags(jTipsGetParam($_REQUEST['description'], $i, ''));
	if ($jGame->save()) {
		$games_to_keep[] = $jGame->id;
		$results[] = 1;
	} else {
		$results[] = 0;
	}
}
$message = "Saved " .array_sum($results). "/" .count($results). " Games";

//Delete games that have been removed by unchecking them
$jGame = new jGame($database);
$jGameParams = array(
	'round_id' => $jRound->id,
	'id' => array(
		'type' => 'query',
		'query' => " NOT IN ('" .implode("', '", $games_to_keep). "')"
	)
);
$jGames = forceArray($jGame->loadByParams($jGameParams));
$deleted = 0;
foreach ($jGames as $oldGame) {
	$oldGame->destroy();
	$deleted++;
}
if ($deleted > 0) {
	$message .= " ($deleted Deleted)";
}
//die();
//mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $message);

die("<p class='message'>$message</p>");
?>
