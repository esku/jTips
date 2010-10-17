<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 *
 * Description:
 *
 *
 */

jTipsSpoofCheck();

global $mosConfig_absolute_path, $database, $jTips, $jTipsCurrentUser, $jLang, $Itemid;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jcomment.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jgame.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jtip.class.php');

jTipsLogger::_log('about to start saving tips');

//TODO: remove this
if (empty($jTipsCurrentUser->id)) die('No jTips User!');

$jRound = new jRound($database);
$jRound->load(jTipsGetParam($_REQUEST, 'round_id', 0));

// BUG 389 - Can save tips by hacking HTML with Firebug
if (!$jRound->id or $jRound->getStatus() !== false) { // trying to post to an invalid round or round in progress
	jTipsRedirect('index.php?option=com_jtips&Itemid=' .$Itemid, $jLang['_COM_CANNOT_POST_CLOSED']);
	exit;
}

$jSeason = new jSeason($database);
$jSeason->load($jRound->season_id);

$jGame = new jGame($database);
$jGameParams = array('round_id' => $jRound->id);
$jGames = forceArray($jGame->loadByParams($jGameParams));
$myTips = array();
$isUpdate = null;

foreach($jGames as $jGame) {
	$game_id = 'game' . $jGame->id;
	jTipsLogger::_log('saving tips for game ' .$jGame->id);
	$jTip = new jTip($database);
	$jTipParams = array('user_id' => $jTipsCurrentUser->id, 'game_id' => $jGame->id);
	$jTip->loadByParams($jTipParams);
	if($jTip->exists() === false || empty($jTip->user_id) || empty($jTip->game_id)) {
		$jTip->user_id = $jTipsCurrentUser->id;
		$jTip->game_id = $jGame->id;
		if (is_null($isUpdate)) {
			$isUpdate = false;
		}
	} else {
		// we are updating or adding to existing tips
		if (is_null($isUpdate)) {
			$isUpdate = true;
		}
	}
	//Set the team tipped
	$jTip->tip_id = jTipsGetParam($_REQUEST, $game_id, null);
	//Set pick the score values if enabled
	if($jSeason->pick_score == 1 and $jGame->has_score == 1) {
		$home_id = 'home' . $jGame->id;
		$away_id = 'away' . $jGame->id;
		$jTip->home_score = jTipsGetParam($_REQUEST, $home_id, 0);
		$jTip->away_score = jTipsGetParam($_REQUEST, $away_id, 0);
		if($jTips['HideTeamSelect']) {
			if($jTip->home_score > $jTip->away_score) {
				$jTip->tip_id = $jGame->home_id;
			} else if($jTip->home_score < $jTip->away_score) {
				$jTip->tip_id = $jGame->away_id;
			} else if($jTip->home_score == $jTip->away_score) {
				$jTip->tip_id = -1;
			} else {
				$jTip->tip_id = null;
			}
		}
	} else {
		$jTip->home_score = 0;
		$jTip->away_score = 0;
	}
	//if nothing has been selected by now, delete the tip record if it exists
	if(!$jTip->tip_id) {
		if($jTip->exists()) {
			$jTip->destroy();
		}
		continue;
	}
	//Set the margin value is enabled
	if($jSeason->pick_margin == 1 and $jGame->has_margin == 1) {
		$margin = 'margin' . $jGame->id;
		$jTip->margin = jTipsGetParam($_REQUEST, $margin, null);
	} else {
		$jTip->margin = 0;
	}
	//Set the bonus team value if enabled
	if($jSeason->pick_bonus == 1 and $jGame->has_bonus == 1) {
		$bonus = 'bonus' . $jGame->id;
		$jTip->bonus_id = jTipsGetParam($_REQUEST, $bonus, null);
	} else {
		$jTip->bonus_id = 0;
	}
	jTipsLogger::_log('saving jTip record with id ' .$jTip->id);
	$jTip->save();
	$myTips[] = $jTip;
	jTipsLogger::_log('done saving jTip with id ' .$jTip->id. ' for game ' .$jGame->id);
}
if(jTipsGetParam($_REQUEST, 'doubleup', false) and empty($jTipsCurrentUser->doubleup)) {
	$jTipsCurrentUser->doubleup = jTipsGetParam($_REQUEST, 'doubleup', false);
	$jTipsCurrentUser->save();
} else
	if(!empty($jTipsCurrentUser->doubleup) and $jTipsCurrentUser->doubleup == $jRound->id and !jTipsGetParam($_REQUEST, 'doubleup', false)) {
		$jTipsCurrentUser->doubleup = null;
		$jTipsCurrentUser->save();
	}
$comment = trim(strip_tags(stripslashes(jTipsGetParam($_REQUEST, 'comment', ''))));
$comment = cleanComment(str_replace('\\', '', $comment));
if($jTips['EnableComments'] == 1 && !empty($comment)) {
	jTipsLogger::_log('saving comment');
	$jComment = new jComment($database);
	$jCommentParams = array('user_id' => $jTipsCurrentUser->id, 'round_id' => $jRound->id);
	$jComment->loadByParams($jCommentParams);
	$jComment->user_id = $jTipsCurrentUser->id;
	$jComment->round_id = $jRound->id;
	$jComment->comment = $comment;
	$jComment->save();
	jTipsLogger::_log('comment saved');
}
$emailResult = '';
if($jTips['TipsNotifyEnable'] and $jTipsCurrentUser->getPreference('tips_notifications')) {
	if (sendTipsConfirmation($jTipsCurrentUser, $myTips)) {
		$emailResult = '. ' .$jLang['_COM_TIPS_EMAIL_SUCCESS'];
	} else {
		$emailResult = '. ' .$jLang['_COM_TIPS_EMAIL_FAILURE'];
	}
}

// BUG 312 - more JomSocial Integration
if ($jTips['JomSocialActivities'] and $jTips['JomSocialOnSaveTips']) {
	require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/jTipsJomSocial.php');
	jTipsJomSocial::writeOnSaveTips($jTipsCurrentUser->user_id, $isUpdate, $jSeason, $jRound);
}
jTipsLogger::_log('done saving tips, redirecting...');
//die('save done');
$message = $jLang['_COM_TIPS_SAVED_MESSAGE'].$emailResult;
jTipsRedirect('index.php?option=com_jtips&view=Tips&Itemid=' . jTipsGetParam($_REQUEST, 'Itemid', '') . '&season=' . $jSeason->id, $message);
?>
