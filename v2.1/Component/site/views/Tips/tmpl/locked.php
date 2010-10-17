<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 *
 * Description: Takes data from the build and displays it
 */

class jTipsRenderLockedTips {
	var $byes = array();

	function displayLocked() {
		global $jLang;
		?>
<p class="alert"><?php echo $jLang['_COM_TIPS_PANEL_LOCKED']; ?></p>
		<?php
	}

	/**
	 * Take data assigned in $data and display it
	 */
	function display() {
		global $jTipsCurrentUser, $mainframe, $database, $jLang, $jTips, $mosConfig_live_site;
		$mosConfig_offset = $mainframe->getCfg('offset');
		global $Itemid;
		$useJs = false;
		if ($jTips['JsLadder'] != 'none') {
			$useJs = true;
		}
		?>
<h2 class="contentheading jmain_heading"><?php echo $this->jSeason->name; ?></h2>
<h3 align="center"><?php
if ($this->jRound->getPrev()) {
	?> <a style="font-size: smaller;"
	href='<?php echo jTipsRoute("index.php?option=com_jtips&amp;Itemid=$Itemid&amp;view=Tips&amp;layout=locked&amp;rid=" .$this->jRound->getPrev()); ?>'>&laquo;
	<?php echo $jLang['_COM_PREV_ROUND']; ?></a> <?php
}
echo "&nbsp;" .$jLang['_COM_DASH_ROUND']. " " .$this->jRound->round. "&nbsp;";
if ($this->jRound->getNext()) {
	?> <a style="font-size: smaller;"
	href='<?php echo jTipsRoute("index.php?option=com_jtips&amp;Itemid=$Itemid&amp;view=Tips&amp;layout=locked&amp;rid=" .$this->jRound->getNext()); ?>'><?php echo $jLang['_COM_NEXT_ROUND']; ?>
&raquo;</a> <?php
}
?></h3>
<?php
		if ($this->jRound->exists()) {
			$jGameParams = array(
				'round_id' => $this->jRound->id,
				'order' => array( //BUG 278 - Ordering not handled in front-end display
					'type' => 'order',
					'by' => 'position',
					'direction' => 'ASC'
				)
			);
			$jGame = new jGame($database);
			$jGames = forceArray($jGame->loadByParams($jGameParams));
			$tags = "class='sectiontableheader jtableheader'";
			?>
		<table width="100%" cellspacing="0">
			<thead>
				<tr class="sectiontableheader">
					<th <?php echo $tags; ?> width="50%"><?php echo $jLang['_COM_ROUND_START_TIME']; ?></th>
					<?php
					if ($jTips['ShowJSCountdown'] == 1) {
						?>
					<th <?php echo $tags; ?> width="50%"><?php echo $jLang['_COM_ROUND_TIME_TO_START']; ?></th>
					<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<tr class="sectiontableentry1">
					<td style="text-align: center;"><?php
					//BUG 136 - show closed/closes depending on start time
					echo TimeDate::toDisplayDateTime($this->jRound->start_time, false);
					?></td>
					<?php
					if ($jTips['ShowJSCountdown'] == 1) {
						?>
					<td>
					<div id='countdown' style="text-align: center;" class="highlight"><?php echo $jLang['_COM_CLOSED']; ?></div>
					</td>
					<?php
					}
					?>
				</tr>
			</tbody>
		</table>
					<?php
					if (jTipsGetParam($jTips, 'TeamLadderPopup', 0)) {
						$url = "view=TeamLadder&Itemid=$Itemid&menu=0";
						?>
		<p style="text-align: center; font-weight: bold;">
						<?php
						if (isJoomla15()) {
							// better popup handling in J1.5
							JHTML::_('behavior.modal');
							$rel = json_encode(array('size' => array('x' => $jTips['ShowTipsWidth'], 'y' => $jTips['ShowTipsHeight'])));
							$url = jTipsRoute("index.php?option=com_jtips&tmpl=component&" .$url);
							$attribs = array(
						    	'class'	=> 'modal',
						    	'rel'	=> str_replace('"', "'", $rel),
						    	'title' => $jLang['_COM_TIPS_SHOWHIDE']
							);
							echo JHTML::link($url, $jLang['_COM_TIPS_SHOWHIDE'], $attribs);
						} else {
							?> <a href='javascript:void(0);'
							onClick="openPopup('<?php echo $url; ?>', 'Team Ladder');"><?php echo $jLang['_COM_TIPS_SHOWHIDE']; ?></a>
							<?php
						}
						?></p><?php
					}
					//BUG 189 - Which order should the tips panel be shown in
					if ($this->jSeason->tips_layout == 'away') {
						$left = 'away';
						$right = 'home';
					} else {
						$left = 'home';
						$right = 'away';
					}
					?>
		<table align='center' width='100%' class="jdatatable" cellspacing="0">
			<tr>
				<th <?php echo $tags; ?>><?php echo $jLang['_COM_GAME_' .strtoupper($left)]; ?></th>
				<?php
				if ($this->jSeason->team_starts) {
					?>
				<th <?php echo $tags; ?>><?php echo $jLang['_COM_TIPS_' .strtoupper($left). 'START']; ?></th>
				<?php
				}
				if ($this->jSeason->team_starts) {
					?>
				<th <?php echo $tags; ?>><?php echo $jLang['_COM_TIPS_' .strtoupper($right). 'START']; ?></th>
				<?php
				}
				?>
				<th <?php echo $tags; ?>>&nbsp;</th>
				<th <?php echo $tags; ?>><?php echo $jLang['_COM_GAME_' .strtoupper($right)]; ?></th>
				<?php
				if ($this->jSeason->game_times) {
					?>
				<th <?php echo $tags; ?>><?php echo $jLang['_COM_TIPS_TIME']; ?></th>
				<?php
				}
				if ($this->jRound->scored == 1) {
					?>
				<th <?php echo $tags; ?>><?php echo $jLang['_COM_TIPS_RESULT']; ?></th>
				<?php
				}
				?>
				<th <?php echo $tags; ?>>&nbsp;</th>
			</tr>
			<?php
			$rowIndex = 0;
			foreach ($jGames as $jGame) {
				$leftTeam = new jTeam($database);
				$rightTeam = new jTeam($database);
				$left_id_field = $left. '_id';
				$right_id_field = $right. '_id';
				$leftLoaded = $rightLoaded = false;
				if ($jGame->$left_id_field) {
					$leftTeam->load($jGame->$left_id_field);
					$leftLoaded = true;
				}
				if ($jGame->$right_id_field) {
					$rightTeam->load($jGame->$right_id_field);
					$rightLoaded = true;
				}
				if (!$leftLoaded and $rightLoaded) $this->byes[] = $rightTeam;
				if ($leftLoaded and !$rightLoaded) $this->byes[] = $leftTeam;
				if (!$leftLoaded or !$rightLoaded) continue;
				if ($jGame->winner_id == $leftTeam->id) {
					$left_style = "font-weight:bold;";
					$right_style = "";
				} else if ($jGame->winner_id == -1) {
					$left_style = "font-style:italics;";
					$right_style = "font-style:italics;";
				} else if ($jGame->winner_id == $rightTeam->id) {
					$left_style = "";
					$right_style = "font-weight:bold;";
				} else {
					$left_style = "";
					$right_style = "";
				}
				$rowClasses = "class='sectiontableentry" .(($rowIndex%2)+1). " jtablerow" .(($rowIndex%2)+1). "'";
				?>
			<tr <?php echo $rowClasses; ?>>
				<td style='text-align:right;<?php echo $left_style; ?>'><label
					for="<?php echo $left; ?>_game<?php echo $jGame->id; ?>"><?php echo $leftTeam->getDisplayLogoName('right'); ?></label></td>
					<?php
					if ($this->jSeason->team_starts) {
						$left_start_field = $left. '_start';
						$leftStart = ($jGame->$left_start_field + 0);
						if ($jGame->$left_start_field > 0) {
							$leftStart = "+$leftStart";
						} else if ($leftStart == 0) {
							$leftStart = "&nbsp;";
						}
						?>
				<td style='text-align: center;'><?php echo $leftStart; ?></td>
				<?php
					}
					?>
				<td style='text-align: center;'>&nbsp;<?php echo $jLang['_COM_TEAM_VS']; ?>&nbsp;</td>
				<?php
				$right_start_field = $right. '_start';
				if ($this->jSeason->team_starts) {
					$rightStart = ($jGame->$right_start_field + 0);
					if ($jGame->$right_start_field > 0) {
						$rightStart = "+$rightStart";
					} else if ($rightStart == 0) {
						$rightStart = "&nbsp;";
					}
					?>
				<td style='text-align: center;'><?php echo $rightStart; ?></td>
				<?php
				}
				?>
				<td style='text-align:left;<?php echo $right_style; ?>'><label
					for="<?php echo $right; ?>_game<?php echo $jGame->id; ?>"><?php echo $rightTeam->getDisplayLogoName('left'); ?></label>
				</td>
				<?php
				//show the game time
				if ($this->jSeason->game_times) {
					?>
					<td><?php
					if ($jGame->start_time) {
						echo TimeDate::toDisplayDateTime($jGame->start_time, false);
					} else {
						echo "&nbsp;";
					}
					?></td><?php
				}
				if ($this->jRound->scored == 1) {
					$left_score_field = $left.'_score';
					$right_score_field = $right.'_score';
					?>
					<th <?php echo $tags; ?>><?php echo $jGame->$left_score_field. " - " .$jGame->$right_score_field; ?>&nbsp;</th>
					<?php
				}
				?><td><?php
				if (!empty($jGame->description)) {
					$description = nl2br(stripslashes($jGame->description));
					echo jTipsToolTip($description, $jLang['_COM_GAME_ADDITIONAL_INFO']);
				} else {
					echo "&nbsp;";
				}
				?></td>
			</tr>
			<?php
			$rowIndex++;
			}
			?>
		</table>
		<div><?php
		$this->renderByes();
		?></div>
		<?php
		} else if ($this->jRound->exists() and TimeDate::toDatabaseDateTime($this->jRound->start_time) > gmdate('Y-m-d H:i:s')) {
			?>
		<h2 style='text-align: center'><?php echo $jLang['_COM_DASH_ROUND']. " " .$this->jRound->round; ?></h2>
		<h2 style='text-align: center'><?php echo $jLang['_COM_ROUND_CLOSED']; ?></h2>
			<?php
		} else {
			if ($this->jRound->exists()) {
				?>
		<h2 style='text-align: center'><?php echo $jLang['_COM_DASH_ROUND']. " " .$this->jRound->round; ?></h2>
				<?php
			} else {
				?>
		<h2 style='text-align: center'><?php echo $jLang['_COM_ROUND_NOGAMES']; ?></h2>
				<?php
			}
		}
		if ($this->jRound->getStatus() === false) {
			$userTime = strtotime($this->jRound->start_time)-TimeDate::getOffset(false);
			$targetTime = date('Y-m-d H:i:s', $userTime);
			if ($jTips['ShowJSCountdown'] == 1) {
				$field = "'countdown'";
			} else {
				$field = "false";
			}
			?>
		<script type='text/javascript'>
						window.addEvent('domready', function(){
							var year = <?php echo TimeDate::format($targetTime, '%Y', true, false); ?>;
							var month = <?php echo (TimeDate::format($targetTime, '%m', true, false)-1); ?>;
							var day = <?php echo TimeDate::format($targetTime, '%d', true, false); ?>;
							var hour = <?php echo TimeDate::format($targetTime, '%H', true, false); ?>;
							var min = <?php echo TimeDate::format($targetTime, '%M', true, false); ?>;
							var sec = 0;
							jTipsCountdown(year, month, day, hour, min, 1, <?php echo $field; ?>);
						});
					</script>
			<?php
		}
	}

	function renderByes() {
		global $jLang, $jTips;
		if (!empty($this->byes)) {
			// now display the byes bit
			?>
<div style='float: right;'>
<table cellspacing='0' align='right' border='0'>
	<thead id="teamByesHeader"
		title="<?php echo $jLang['_COM_CLICK_TO_EXPAND']; ?>">
		<tr class="sectiontableheader">
			<th class="sectiontableheader"><?php echo $jLang['_COM_BYES']; ?></th>
		</tr>
	</thead>
	<tbody id="byes_list">
	<?php
	$i = 0;
	foreach ($this->byes as $jTeam) {
		$i++;
		?>
		<tr class="sectiontableentry<?php echo $i%2; ?>">
			<td class="sectiontableentry<?php echo $i%2; ?>"><?php echo $jTeam->getDisplayLogoName(); ?></td>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>
</div>
	<?php
	if ($jTips['JsLadder'] != 'none') {
		?>
<script type='text/javascript'>
				window.addEvent('domready', function() {
					var teamByes = new Fx.Slide('byes_list', {
						duration:<?php echo $jTips['JsLadderDuration'] * 1000; ?>,
						wait: true,
						<?php
						if ($jTips['JsLadder'] == 'linear') {
							echo "transition: Fx.Transitions.linear";
						} else {
							?>
						transition: Fx.Transitions.<?php echo $jTips['JsLadder']; ?>.<?php echo $jTips['JsLadderStyle']; ?>
							<?php
						}
						?>
					});
					teamByes.hide();
					$('teamByesHeader').addEvent('click', function(e){
						e = new Event(e);
						teamByes.toggle();
						e.stop();
					});
					$('teamByesHeader').setStyle('cursor', 'pointer');
				});
				</script>
						<?php
	}
		}
	}

	function assign($var, $val) {
		$this->$var = $val;
	}
}

?>
