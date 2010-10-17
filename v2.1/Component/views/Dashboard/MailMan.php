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
 * Description: Sends email reminders to users that have opted to receive them
 */

set_time_limit(0);

ob_clean();

global $mosConfig_absolute_path, $database, $jTips;

require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jremind.class.php');

$jTips['EnableEmailReminders'] == '1' or die ('Reminders disabled');

//do we have the right key to avoid DOS
if (!jTipsGetParam($_GET, 'key', false) or md5(jTipsGetParam($_GET, 'key', '')) != md5($jTips['EmailManKey'])) {
	jTipsLogger::_log('invalid passkey. attempted to force email reminders!', 'ERROR');
	die('Invalid access key');
}

//Get All Current Seasons
$query = "SELECT id FROM #__jtips_seasons WHERE start_time <= '" .gmdate('Y-m-d'). "' AND end_time >= '" .gmdate('Y-m-d'). "';";
$database->setQuery($query);
$season_ids = $database->loadResultArray();
$from = $jTips['EmailRemindFromAddress'];
$fromname = $jTips['EmailRemindFromName'];
$subject = $jTips['EmailRemindSubject'];
$body = $jTips['EmailRemindBody'];
$tables = array(
	'#__jtips_users',
	'#__users'
);
sort($tables);
$write = array();
$tableArr = $database->getTableFields($tables);
$variables = array();
foreach ($tableArr as $table) {
	foreach ($table as $field => $type) {
		array_push($variables, $field);
	}
}
$sent = $attempted = 0;
foreach ($season_ids as $season_id) {
	//Get the current round
	// BUG 396 - query error. Fix supplied by James Butler
	$query = "SELECT #__jtips_rounds.*, #__jtips_seasons.name AS season FROM #__jtips_rounds 
				JOIN #__jtips_seasons ON #__jtips_rounds.season_id = #__jtips_seasons.id 
				WHERE season_id = $season_id AND #__jtips_rounds.start_time > '" .gmdate('Y-m-d H:i:s'). "' 
				AND scored = 0 ORDER BY start_time ASC LIMIT 1";
	$database->setQuery($query);
	$round = $database->loadAssocList();
	if (!$round) {
		jTipsLogger::_log('no current round found to send reminders for for season with id = ' .$season_id, 'info');
		continue;
	}
	//MySQL 4 fix (no sub selects)
	$query = "SELECT user_id FROM #__jtips_remind WHERE round_id = " .$round[0]['id']. " AND notified = 1";
	//echo $query;
	$database->setQuery($query);
	$user_ids = (array)$database->loadResultArray();

	//Get all active users in this season
	// BUG 397 - placeholders unavailable for email template. Fix supplied by James Butler
	$query = "SELECT ju.*, u.email, u.name, u.username FROM #__jtips_users ju JOIN #__users u ON ju.user_id = u.id WHERE ju.status = 1 AND season_id = $season_id AND ju.id NOT IN ('" .implode("', '", $user_ids). "') AND u.block = 0";
	//echo $query;
	$database->setQuery($query);
	$rows = (array)$database->loadAssocList();
	jTipsLogger::_log('found ' .count($rows). ' users to try to send to ', 'info');
	foreach ($rows as $user) {
		ksort($user);
		$jTipsUser = new jTipsUser($database);
		$jTipsUser->load($user['id']);
		//jTipsLogger::_log($jTipsUser);
		if ($jTipsUser->getPreference('email_reminder')) {
			$recipient = $user['email'];
			$user['round'] = $round[0]['round'];
			$user['competition'] = $round[0]['season'];
			$user['season'] = $round[0]['season'];
			$body = parseTemplate($body, $variables, $user);
			$record = array(
				'round_id' => $round[0]['id'],
				'user_id' => $user['id'],
				'notified' => 0
			);
			$attempted++;
			if (jTipsMail($from, $fromname, $recipient, $subject, $body)) {
				$record['notified'] = 1;
				jTipsLogger::_log('sent reminder email to ' .$recipient. ' subject: ' .$subject. ' from: ' .$fromname. ' <' .$from. '>', 'info');
				$sent++;
			} else {
				jTipsLogger::_log('failed to send reminder email to ' .$recipient, 'error');
			}
			$jRemind = new jRemind($database);
			$jRemindParams = array(
				'round_id' => $record['round_id'],
				'user_id' => $record['user_id']
			);
			$jRemind->loadByParams($jRemindParams);
			$jRemind->attempts++;
			$jRemind->bind($record);
			$jRemind->save();
		}
	}
}

$result = 'Sent ' .$sent. ' out of ' .$attempted. ' jTips reminder emails for ' .count($season_ids). ' seasons';
jTipsLogger::_log($result, 'INFO');
echo $result;
exit();
?>
