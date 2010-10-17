<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

require_once('components/com_jtips/classes/jtip.class.php');
require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/modules/Tips/edit.tmpl.php');

global $database;

jTipsSpoofCheck();

$tpl = new EditMode();

$tpl->formData = array(
	'title' => 'Tips Editor'
);

$ids = jTipsGetParam($_REQUEST, 'cid', array());
//Do we have an existing set of tips?
$id = array_shift($ids);

if ($id) {
	$jTip = new jTip($database);
	$jTip->load($id);
	$jTip->fillInAdditionalFields();
	$season_id = $jTip->season->id;
	$round_id = $jTip->round->id;
	$user_id = $jTip->user->id;
} else {
	$season_id = jTipsGetParam($_REQUEST, 'season_id', false);
	$round_id = jTipsGetParam($_REQUEST, 'round_id', false);
	$user_id = jTipsGetParam($_REQUEST, 'user_id', false);
}

//need to select the season, then the round, then list the games

//get the seasons
$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams(array()));
//make a set of options for a select list
$season_options = objectsToSelectList($jSeasons, 'name');

$filters = array(
	'Select Season' => makeSelectList($season_options, 'season_id', "id='season_id' onChange='this.form.submit();'", $season_id)
);

//Load the Rounds
if ($season_id) {
	$params = array(
		'season_id' => $season_id
	);
	$jRound = new jRound($database);
	$jRounds = forceArray($jRound->loadByParams($params));
	$round_options = objectsToSelectList($jRounds, 'round');
	$filters['Select Round'] = makeSelectList($round_options, 'round_id', "id='round_id' onChange='this.form.submit();'", $round_id);
}

//Load the users
if ($round_id and $season_id) {
	$params = array(
		'season_id' => $season_id
	);
	$jTipsUser = new jTipsUser($database);
	$jTipsUsers = forceArray($jTipsUser->loadByParams($params));
	$user_options = objectsToSelectList($jTipsUsers, 'getName', true);
	asort($user_options);
	$filters['Select User'] = makeSelectList($user_options, 'user_id', "id='user_id' onChange='this.form.submit();'", $user_id);
}

$tpl->selectLists = $filters;
$data = array();

//Now we have the preliminary options, get the set of games
if ($season_id and $round_id and $user_id) {
	$params = array(
		'round_id' => $round_id
	);
	$jGame = new jGame($database);
	$jGames = forceArray($jGame->loadByParams($params));
	foreach ($jGames as $game) {
		$params = array(
			'game_id' => $game->id,
			'user_id' => $user_id
		);
		$jTip = new jTip($database);
		$jTip->loadByParams($params);
		//jTipsDebug($jTip);
		$jHome = new jTeam($database);
		$jAway = new jTeam($database);
		$jHome->load($game->home_id);
		$jAway->load($game->away_id);
		$team_options = array(
			'' => '--None--',
			$jHome->id => $jHome->getName(),
			$jAway->id => $jAway->getName(),
		);
		$bonus_options = $team_options;
		$bonus_options['-2'] = '--Both Teams--';
		
		$data[$game->id] = array(
			'home' => $jHome,
			'away' => $jAway,
			'home_score' => $jTip->home_score,
			'away_score' => $jTip->away_score,
			'margin' => $jTip->margin,
			'bonus_id' => makeSelectList($team_options, 'g'.$game->id. '[bonus_id]', "", ''),
			'home_tipped' => '',
			'away_tipped' => '',
			'draw_tipped' => '',
			'id' => $jTip->id
		);
		
		if ($jTip->tip_id == $jHome->id) {
			$data[$game->id]['home_tipped'] = "checked";
		} else if ($jTip->tip_id == $jAway->id) {
			$data[$game->id]['away_tipped'] = "checked";
		} else if ($jTip->tip_id == -1) {
			$data[$game->id]['draw_tipped'] = "checked";
		}
	}
	$tpl->games = $data;
}


$tpl->display();

?>