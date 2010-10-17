<?php
/**
* @version $Id: mod_jtips_upcoming_games.php,v 1.1 2009/02/12 10:51:05 jtipsco1 Exp $
* @package jTips 2.0
* @copyright (C) 2008 jTips
* @license Commercial - Do not modify, or redistribute
* jTips 2.0 - your ultimate tipping system... www.jtips.com.au
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
global $mosConfig_absolute_path, $mainframe, $database, $my, $jTips, $mosConfig_live_site, $jTipsCurrentUser;
$season_name		= $params->get('season', '');
$showlink			= $params->get('showlink', 0);
$load_mootools		= $params->get('load_mootools', 0);
$user_field			= $params->get('user_field', 'pointst');
$incimage			= $params->get('incimage', 0);
$numtoshow			= $params->get('numtoshow', 10);

require_once('administrator/components/com_jtips/utils/compat.php');

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';
$mosConfig_lang=$mainframe->getCfg( 'lang' );
include($include_path. 'i18n/english.php');
if (file_exists($include_path. 'i18n/'.$mosConfig_lang.'.php')) {
	include($include_path. 'i18n/'.$mosConfig_lang.'.php');
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

//Include required files
require_once($include_path. 'utils/timedate.php');
require_once($include_path. 'utils/functions.inc.php');
require_once($include_path. 'classes/jround.class.php');
require_once($include_path. 'classes/jseason.class.php');
require_once($include_path. 'classes/jgame.class.php');
require_once($include_path. 'classes/jteam.class.php');
require_once($include_path. 'classes/juser.class.php');
include($include_path. 'config.jtips.php');

//BUG
if (!$jTipsCurrentUser) {
	$jTipsCurrentUser	= new jTipsUser($database);
	$my =& $mainframe->getUser();
	$parameters = array(
		'user_id' => $my->id
	);
	$temp = forceArray($jTipsCurrentUser->loadByParams($parameters));
	$jTipsCurrentUser = array_shift($temp);
	unset($parameters);
}

//Get the season
$jtips_params = array(
	'name' => trim($season_name)
);
$jSeason = new jSeason($database);
$jSeason->loadByParams($jtips_params);
if (isset($jSeason->id) and !empty($jSeason->id)) {
	//parse the info here
	$hasData = true;
	$round_id = $jSeason->getCurrentRound();
	$jRound = new jRound($database);
	if (!$round_id) {
		$hasData = false;
	} else {
	    $jRound->load($round_id);
	}
	$data = array(
		'round_id' => $round_id
	);
	$game = new jGame($database);
	$jGames = forceArray($game->loadByParams($data));
	if (empty($jGames)) $hasData = false;

	if ($hasData) {
		if ($jSeason->tips_layout == 'away') {
		    $left = 'away';
		    $right = 'home';
		} else {
		    $left = 'home';
		    $right = 'away';
		}
		if ($params->get('showseason')) {
		    ?>
		    <h4 style="text-align:center;"><?php echo $jSeason->name; ?></h4>
		    <?php
		}
		if ($params->get('showround')) {
		    ?>
		    <h5 style="text-align:center;">Round <?php echo $jRound->round; ?></h5>
		    <?php
		}
		if ($params->get('showclose') != '0') {
		    if ($params->get('showclose') == 'static') {
		        ?>
		        <p align="center"><?php echo TimeDate::toDisplayDateTime($jRound->start_time, false); ?></p>
		        <?php
		    } else if ($params->get('showclose') == 'count') {
//		        $mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/countdown.js'></script>");
				echo "<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/language.js'></script>";
				echo "<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/mootools.js'></script>";
				echo "<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/countdown.js'></script>";
				//$userTime = strtotime($jRound->start_time);
				$userTime = strtotime($this->jRound->start_time)-TimeDate::getOffset(false); // let's make this consistent with the component

				$targetTime = date('Y-m-d H:i:s', $userTime);
				$randId = rand();
				?>
				<p align="center" id="counter<?php echo $randId; ?>"></p>
				<script type='text/javascript'>
					window.addEvent('domready', function(){
						var year = <?php echo TimeDate::format($targetTime, '%Y', true, false); ?>;
						var month = <?php echo (TimeDate::format($targetTime, '%m', true, false)-1); ?>;
						var day = <?php echo TimeDate::format($targetTime, '%d', true, false); ?>;
						var hour = <?php echo TimeDate::format($targetTime, '%H', true, false); ?>;
						var min = <?php echo TimeDate::format($targetTime, '%M', true, false); ?>;
						var sec = 0;
						jTipsCountdown(year, month, day, hour, min, 1, "counter<?php echo $randId; ?>", true);
					});
				</script>
				<?php
		    }
		}
		?>

		<table width="100%" callspcaing="0" width="100%">
			<thead>
			<tr class="sectiontableheader">
				<th><?php echo $jLang['_COM_GAME_' .strtoupper($left)]; ?></th>
				<th><?php echo $jLang['_COM_GAME_' .strtoupper($right)]; ?></th>
			</tr>
			</thead>
			<tbody>

		<?php
		$rowIndex = 0;
		$left_id_field = $left.'_id';
		$right_id_field = $right.'_id';
		foreach ($jGames as $jGame) {
			if (!$jGame->home_id or !$jGame->away_id) continue; // Allow for BYES
			$leftTeam = new jTeam($database);
			$rightTeam = new jTeam($database);
			$leftTeam->load($jGame->$left_id_field);
			$rightTeam->load($jGame->$right_id_field);
			?>
			<tr class="sectiontableentry<?php echo ($rowIndex++%2)+1; ?>">
			<td><?php echo $leftTeam->getDisplayLogoName(); ?></td>
			<td><?php echo $rightTeam->getDisplayLogoName(); ?></td>
			</tr>
			<?php
		}
		?>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<div class="message"><?php echo $jLang['_MOD_NO_DATA']; ?></div>
		<?php
	}
} else {
	//display error message
	?>
	<div class="message"><?php echo $jLang['_MOD_INVALID_SEASON_ERROR']; ?></div>
	<?php
}
?>