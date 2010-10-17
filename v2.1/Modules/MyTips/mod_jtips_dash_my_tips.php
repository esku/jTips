<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

global $mosConfig_absolute_path, $database, $my, $jTips, $Itemid, $mosConfig_live_site;
if (is_null($mosConfig_absolute_path)) {
	$mosConfig_absolute_path = JPATH_SITE;
}

require_once('administrator/components/com_jtips/utils/logger.php');
require_once('administrator/components/com_jtips/utils/compat.php');
require_once('administrator/components/com_jtips/utils/functions.inc.php');
require_once('administrator/components/com_jtips/utils/timedate.php');
require_once('administrator/components/com_jtips/classes/juser.class.php');
require_once('administrator/components/com_jtips/classes/jseason.class.php');
require_once('administrator/components/com_jtips/classes/jgame.class.php');
require_once('administrator/components/com_jtips/classes/jteam.class.php');
if (jTipsFileExists('administrator/components/com_jtips/config.jtips.php')) {
	include('administrator/components/com_jtips/config.jtips.php');
}

if (!isJoomla15()) {
	if ($params->get('load_mootools')) {
		?>
		<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/mootools.js"></script>
		<?php
	}
	?>
	<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/moodalbox.js"></script>
	<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/Popup.js"></script>
	<link rel='stylesheet' href='<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/moodalbox.css' type='text/css' media='screen' />
	<?php
} else {
	JHTML::_('behavior.modal');
}

// load the selected season
$jSeason = new jSeason($database);
$jSeason->loadByParams(array('name' => $params->get('season')));

if (!$jSeason->id) {
	// show an error message
	?><p class="error">No competition selected. Please update module configuration.</p><?php
} else {
	$my =& $mainframe->getUser();
	//do we have a valid user?
	$jTipsMyTipsUser = new jTipsUser($database);
	$jTipsMyTipsUser->loadByParams(array('user_id' => $my->id, 'season_id' => $jSeason->id));
	if (!$jTipsMyTipsUser->id) {
		?><p class="message">Join the <?php echo $jSeason->name; ?> competition!</p><?php
	} else {
		// valid user and season, get their tips
		$round_id = $jSeason->getCurrentRound();
		if (!$round_id) {
			?><p class="message">Competition has ended.</p><?php
		} else {
			// valid everything, get the tips
			$jTipParams = array(
				'user_id' => $jTipsMyTipsUser->id,
				'game_id' => array(
					'type' => 'join',
					'join_table' => '#__jtips_games',
					'lhs_table' => '#__jtips_tips',
					'lhs_key' => 'game_id',
					'rhs_table' => '#__jtips_games',
					'rhs_key' => 'id',
					'supplement' => "AND #__jtips_games.round_id = '" .$round_id. "'"
				)
			);
			$jTip = new jTip($database);
			$tips = $jTip->loadByParams($jTipParams);

			if (empty($tips)) {
				?><p class="message">Tips not yet submitted.</p><?php
			} else {
				?>
				<table width="100%" border="0" cellspacing="0">
					<thead>
					<tr class="sectiontableheader">
					<th class="sectiontableheader">Team</th>
				<?php
				if ($jSeason->pick_score) {
					?><th class="sectiontableheader" align="center">Score</th><?php
				}
				if ($jSeason->pick_margin) {
					?><th class="sectiontableheader" align="center">Margin</th><?php
				}
				?>
					<th class="sectiontableheader">&nbsp;</th>
					</tr>
					</thead>
					<tbody>
				<?php
				$k = 0;

				foreach ($tips as $tip) {
					$tipped = new jTeam($database);
					$tipped->load($tip->tip_id);
					$popupUrl = "view=TeamLadder&Itemid=$Itemid&action=ShowTeam&id=" .$tipped->id. "&season_id=" .$jSeason->id;
					$game = new jGame($database);
					$game->load($tip->game_id);
					$otherTeam = new jTeam($database);
					if ($tip->tip_id == $game->home_id) {
						$otherTeam->load($game->away_id);
					} else if ($tip->tip_id == $game->away_id) {
						$otherTeam->load($game->home_id);
					}
					if ($params->get('show_logo')) {
						$teamname = $tipped->getLogo();
					} else {
						$teamname = '';
					}
					$callback = $params->get('display', 'getDisplayLogoName');
					if ($callback == 'getLogo') {
						$arg = '100';
						$display = $tipped->$callback($arg);
					} else {
						$display = $tipped->$callback();
					}
					if ($tipped->id != -1) {
						if (isJoomla15()) {
							$x = $jTips['ShowTipsWidth'];
							$y = $jTips['ShowTipsHeight'];
							if (basename($_SERVER['SCRIPT_FILENAME']) != 'index2.php') {
								$class = "class='modal'";
							} else {
								$class = '';
							}
							$teamname .= "<a $class rel=\"{handler: 'iframe', size: {x: $x, y: $y}}\" href='" .jTipsRoute($mosConfig_live_site. "/index2.php?option=com_jtips&$popupUrl&menu=0"). "'>" .$display. "</a>";
						} else {
							$teamname .= "<a href='javascript:void(0);' onClick='openPopup(\"$popupUrl\", \"" .$tipped->getName(). "\");'>" .$tipped->getName(). "</a>";
						}
					} else if ($tip->tip_id == -1) {
						$teamname = "<em>Draw</em>";
					} else {
						$teamname = "&nbsp;";
					}
					?>
					<tr class="sectiontableentry<?php echo $k%2+1; ?>">
						<td class="sectiontableentry<?php echo $k%2+1; ?>"><?php echo $teamname; ?></td>
					<?php
					if ($jSeason->pick_score) {
						$left_field = 'home_score';
						$right_field = 'away_score';
						if ($jSeason->tips_layout == 'away') {
							$left_field = 'away_score';
							$right_field = 'home_score';
						}
						?>
						<td class="sectiontableentry<?php echo $k%2+1; ?>" align="center" nowrap><?php echo $tip->$left_field; ?> - <?php echo $tip->$right_field; ?></td>
						<?php
					}
					if ($jSeason->pick_margin) {
						?>
						<td class="sectiontableentry<?php echo $k%2+1; ?>" align="center"><?php echo ($tip->margin ? $tip->margin : "&nbsp;"); ?></td>
						<?php
					}
					?>
						<td>
					<?php
					if ($otherTeam->id) {
						echo jTipsToolTip($otherTeam->getName(), 'Versus');
					} else if ($tip->tip_id == -1) {
						$homeTeam = new jTeam($database);
						$awayTeam = new jTeam($database);
						$homeTeam->load($game->home_id);
						$awayTeam->load($game->away_id);
						echo jTipsToolTip($homeTeam->getName(). " v " .$awayTeam->getName(), 'Draw Selected');
					} else {
						echo "&nbsp;";
					}
					?>
						</td>
					</tr>
					<?php
					$k++;
				}
				?>
					</tbody>
				</table>
				<?php
			}
		}
	}
}
