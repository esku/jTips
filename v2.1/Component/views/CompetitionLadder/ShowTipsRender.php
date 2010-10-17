<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 *
 * Description:
 *
 *
 */

class jTipsRenderShowTips {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function display($hideColumns=array(), $showThisRound=true) {
		global $database, $jTips, $jLang, $mosConfig_live_site, $mainframe, $jTipsCurrentUser, $Itemid;
		if (empty($this->jTipsUser->id)) {
			echo "";
			return;
		}
		?>
		<style type="text/css">
		@import url(<?php echo $mosConfig_live_site; ?>/templates/<?php echo jTipsGetTemplateName(); ?>/css/template.css);
		@import url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/jtips-popup.css);
		</style>
		<script type='text/javascript' src='<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/mootools.js'></script>
		<script type='text/javascript' src='<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/Popup.js'></script>
		<?php
		$width = $jTips['ShowTipsWidth'] - 40;
		if ($jTips['ShowTipsPadding']) {
			?>
			<div style="padding-top:10px;padding-left:10px;padding-right:10px;padding-bottom:10px;width:<?php echo $width; ?>px;text-align:center;">
			<?php
		}
		?>
		<h2 style='text-align:center;'><?php echo $this->jSeason->name; ?> <?php echo $jLang['_COM_SHOWTIPS_ROUND']; ?> <?php echo $this->jRound->round; ?></h2>
		<?php
		if ($jTips['SocialIntegration'] and $showThisRound) {
			if ($jTips['SocialIntegration'] == 'cb') {
				$imgSrc = getCommunityBuilderAvatar($this->jTipsUser->user_id);
				$link = jTipsRoute("index.php?option=com_comprofiler&amp;task=userProfile&amp;user={$this->jTipsUser->user_id}");
			} else {
				$imgSrc = getJomSocialAvatar($this->jTipsUser->user_id);
				$link = getJomSocialProfileLink($this->jTipsUser->user_id);
			}
			?>
			<div style="text-align:center;">
				<a href="javascript:void(0);" onClick="parent.location='<?php echo $link; ?>';" title='View Profile' id='userLadderLink_<?php echo $this->jTipsUser->id; ?>'>
					<img src="<?php echo $imgSrc; ?>" border="0" alt="" />
				</a>
			</div>
			<?php
		}

		if ($this->stats) {
			$this->showStats();
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
		<table align='center' width='100%' border='0' cellspacing='0' style="padding-top:25px;">
			<thead>
			<tr class="sectiontableheader">
			<?php
			if (!in_array($left, $hideColumns)) {
				?><th class="sectiontableheader TB_th"><?php echo $jLang['_COM_GAME_' .strtoupper($left)]; ?></th><?php
			}
			if (!in_array($right, $hideColumns)) {
				?><th class="sectiontableheader TB_th"><?php echo $jLang['_COM_GAME_' .strtoupper($right)]; ?></th><?php
			}
			if (!in_array('tipped', $hideColumns)) {
				?><th class="sectiontableheader TB_th"><?php echo $jLang['_COM_DASH_TIPPED']; ?></th><?php
			}
			if (!in_array('result', $hideColumns)) {
				?><th class="sectiontableheader TB_th"><?php echo $jLang['_COM_TIPS_RESULT']; ?></th><?php
			}
			if (!in_array('more', $hideColumns)) {
				?><th class="sectiontableheader TB_th"><?php echo $jLang['_COM_TIPS_MORE']; ?></th><?php
			}
			?>
			</tr>
			</thead>
			<tbody>
		<?php
		$rowIndex = 0;
		$i = 1;
		$hasTipped = $this->jTipsUser->hasTipped($this->jRound->id);
		$byeTeams = array(); // Might want to list the byes somewhere later
		foreach ($this->jGames as $jGame) {
			$leftTeam = new jTeam($database);
			$left_id = $left.'_id';
			//$leftTeam->load($jGame->$left_id);
			$rightTeam = new jTeam($database);
			$right_id = $right.'_id';
			//$rightTeam->load($jGame->$right_id);

			// Skip games with byes
			$leftLoaded = $rightLoaded = false;
			if ($jGame->$left_id) {
				$leftTeam->load($jGame->$left_id);
				$leftLoaded = true;
			}
			if ($jGame->$right_id) {
				$rightTeam->load($jGame->$right_id);
				$rightLoaded = true;
			}
			if (!$leftLoaded and $rightLoaded) $byeTeams[] = $rightTeam;
			if ($leftLoaded and !$rightLoaded) $byeTeams[] = $leftTeam;
			if (!$leftLoaded or !$rightLoaded) continue;


			$jTip = new jTip($database);
			$jTipParams = array(
				'user_id' => $this->jTipsUser->id,
				'game_id' => $jGame->id
			);
			$jTip->loadByParams($jTipParams);
			$overlib_text = "";
			if ($jTip->tip_id == $leftTeam->id) {
				$tipName = $leftTeam->getName();
				$tip = $leftTeam->getDisplayLogoName();
				$nonName = $rightTeam->getName();
			} else if ($jTip->tip_id == $rightTeam->id) {
				$tipName = $rightTeam->getName();
				$tip = $rightTeam->getDisplayLogoName();
				$nonName = $leftTeam->getName();
			} else if ($jTip->tip_id == -1) {
				$tip = $jLang['_COM_GAME_DRAW'];
				$tipName = $leftTeam->getName();
				$nonName = $rightTeam->getName();
			} else {
				$tip = $jLang['_ADMIN_CONF_NONE'];
			}
			if ($jGame->winner_id == $jTip->tip_id) {
				$result = "<img src='" .$mosConfig_live_site. "/administrator/images/tick.png' alt='Y' border='0' align='middle' />";
			} else {
				$result = "<img src='" .$mosConfig_live_site. "/administrator/images/publish_x.png' alt='N' border='0' align='middle' />";
			}
			$onClick = "onClick='toggleMore(" .$jGame->id. ", this)'";
			$more = "<img src='{$mosConfig_live_site}/components/com_jtips/images/show.gif' alt='more' border='0' $onClick style='cursor:pointer;' />";
			$rowIndex++;
			?>
			<tr class="sectiontableentry<?php echo ($i%2)+1; ?>" valign='middle'>
			<?php
			if (!in_array($left, $hideColumns)) {
				if ($jGame->winner_id == $leftTeam->id) {
					$style = "style='font-weight:bold;'";
				} else if ($jGame->winner_id == -1) {
					$style = "style='font-style:italic;'";
				} else {
					$style = '';
				}
				?><td class="sectiontableentry<?php echo ($i%2)+1; ?>" align="left" <?php echo $style; ?>><?php echo $leftTeam->getDisplayLogoName(); ?></td><?php
			}
			if (!in_array($right, $hideColumns)) {
				if ($jGame->winner_id == $rightTeam->id) {
					$style = "style='font-weight:bold;'";
				} else if ($jGame->winner_id == -1) {
					$style = "style='font-style:italic;'";
				} else {
					$style = '';
				}
				?><td class="sectiontableentry<?php echo ($i%2)+1; ?>" align="left" <?php echo $style; ?>><?php echo $rightTeam->getDisplayLogoName(); ?></td><?php
			}
			if (!in_array('tipped', $hideColumns)) {
				?><td class="sectiontableentry<?php echo ($i%2)+1; ?>" align="left"><?php echo $tip; ?></td><?php
			}
			if (!in_array('result', $hideColumns)) {
				?><td class="sectiontableentry<?php echo ($i%2)+1; ?>" style='text-align:center;'><?php echo $result; ?></td><?php
			}
			if (!in_array('more', $hideColumns)) {
				?><td class="sectiontableentry<?php echo ($i%2)+1; ?>" style='text-align:center;'><?php echo $more; ?></td><?php
			}
			?>
			</tr>
			<?php
			/*
			 * Process the in-depth game analysis
			 */
			$left_score_field = $left.'_score';
			$right_score_field = $right.'_score';
			if ($jGame->winner_id == $leftTeam->id) {
				$winnerName = $leftTeam->getDisplayLogoName();
			} else if ($jGame->winner_id == $rightTeam->id) {
				$winnerName = $rightTeam->getDisplayLogoName();
			} else if ($jGame->winner_id == -1) {
				$winnerName = $jLang['_COM_GAME_DRAW'];
			} else {
				$winnerName = '';
			}
			$gameTotal = 0;
			$tipWidth = 30;
			if (in_array('actual', $hideColumns)) {
				$tipWidth += 30;
			}
			if (in_array('awarded', $hideColumns)) {
				$tipWidth += 10;
			}
			?>
			<tr>
			<td colspan="50" id="moreInfo<?php echo $jGame->id; ?>" style="display:none;">
				<table width="100%" cellspacing="0">
				<thead>
				<tr class="sectiontableheader">
				<th class="sectiontableheader" width="30%">&nbsp;</th>
				<th class="sectiontableheader" width="<?php echo $tipWidth; ?>%"><?php echo $jLang['_COM_SHOWTIPS_PREDICTED']; ?></th>
				<?php
				if (!in_array('actual', $hideColumns)) {
				?>
				<th class="sectiontableheader" width="30%"><?php echo $jLang['_COM_SHOWTIPS_ACTUAL']; ?></th>
				<?php
				}
				if (!in_array('awarded', $hideColumns)) {
				?>
				<th class="sectiontableheader" width="10%"><?php echo $jLang['_COM_SHOWTIPS_AWARDED']; ?></th>
				<?php
				}
				?>
				</tr>
				</thead>
				<tbody>
					<tr class="sectiontableentry1">
					<th class="sectiontableentry1"><?php echo $jLang['_COM_DASH_TIPPED']; ?></th>
					<td class="sectiontableentry1" align="left"><?php echo $tip; ?>&nbsp;</td>
					<?php
					if (!in_array('actual', $hideColumns)) {
						?>
						<td class="sectiontableentry1" align="left"><?php echo $winnerName; ?>&nbsp;</td>
						<?php
					}
					if (!in_array('awarded', $hideColumns)) {
						?>
						<td class="sectiontableentry1" style="text-align:right;">
						<?php
						if ($hasTipped) {
							if ($jGame->winner_id == $jTip->tip_id) {
								// BUG 379 - Incorrect value displayed when draw correctly picked
								if ($jGame->winner_id == -1) {
									echo $this->jSeason->user_draw;
									$gameTotal += $this->jSeason->user_draw;
								} else {
									echo $this->jSeason->user_correct;
									$gameTotal += $this->jSeason->user_correct;
								}
							} else {
								echo '0';
							}
						} else {
							//what were the default points here?
							if ($this->jSeason->user_none >= 0) {
								echo $this->jSeason->user_none;
								$gameTotal += $this->jSeason->user_none;
							} else if ($this->jSeason->user_none == -1) {
								//holy crap! you got the lowest score equivalence!
								echo $jLang['_ADMIN_CONF_ACT_NA'];
							} else if ($this->jSeason->user_none == -2) {
								//you got all the away teams
								if ($jGame->winner_id == $jGame->away_id) {
									echo $this->jSeason->user_correct;
									$gameTotal += $this->jSeason->user_correct;
								} else {
									echo '0';
								}
							}
						}
						?>
						&nbsp;</td>
						<?php
					}
					?>
					</tr>
				<?php
				$subIndex = 1;
				if ($jGame->has_score) {
					?>
					<tr class="sectiontableentry<?php echo $subIndex%2+1; ?>">
					<th class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $jLang['_COM_DASH_POINTS']; ?></th>
					<td class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $jTip->$left_score_field + 0; ?> - <?php echo $jTip->$right_score_field + 0; ?>&nbsp;</td>
					<?php
					if (!in_array('actual', $hideColumns)) {
						?>
						<td class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $jGame->$left_score_field; ?> - <?php echo $jGame->$right_score_field; ?>&nbsp;</td>
						<?php
					}
					if (!in_array('awarded', $hideColumns)) {
						?>
						<td class="sectiontableentry<?php echo $subIndex%2+1; ?>" style="text-align:right;">
						<?php
						if ($jTip->$left_score_field == $jGame->$left_score_field and $jTip->$right_score_field == $jGame->$right_score_field) {
							echo $this->jSeason->user_pick_score;
							$gameTotal += $this->jSeason->user_pick_score;
						} else {
							echo '0';
						}
						?>
						&nbsp;</td>
						<?php
					}
					?>
					</tr>
					<?php
					$subIndex++;
				}
				if ($jGame->has_margin) {
					?>
					<tr class="sectiontableentry<?php echo $subIndex%2+1; ?>">
					<th class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $jLang['_COM_TIPS_MARGIN']; ?></th>
					<td class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $jTip->margin + 0; ?>&nbsp;</td>
					<?php
					if (!in_array('actual', $hideColumns)) {
						?>
						<td class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo abs($jGame->$left_score_field - $jGame->$right_score_field); ?>&nbsp;</td>
						<?php
					}
					if (!in_array('awarded', $hideColumns)) {
						?>
						<td class="sectiontableentry<?php echo $subIndex%2+1; ?>" style="text-align:right;">
						<?php
						if ($jTip->margin == abs($jGame->$left_score_field - $jGame->$right_score_field)) {
							echo $this->jSeason->user_pick_margin;
							$gameTotal += $this->jSeason->user_pick_margin;
						} else {
							echo '0';
						}
						?>
						&nbsp;</td>
						<?php
					}
					?>
					</tr>
					<?php
					$subIndex++;
				}
				if ($jGame->has_bonus) {
					if ($jGame->bonus_id == $leftTeam->id) {
						$bonusTeam = $leftTeam->getDisplayLogoName();
					} else if ($jGame->bonus_id == $rightTeam->id) {
						$bonusTeam = $rightTeam->getDisplayLogoName();
					} else if ($jGame->bonus_id == -2) {
						$bonusTeam = 'Both Teams';
					} else {
						$bonusTeam = "";
					}
					if ($jTip->bonus_id == $leftTeam->id) {
						$bonusTip = $leftTeam->getDisplayLogoName();
					} else if ($jTip->bonus_id == $rightTeam->id) {
						$bonusTip = $rightTeam->getDisplayLogoName();
					} else if ($jTip->bonus_id == -2) {
						$bonusTip = 'Both Teams';
					} else {
						$bonusTip = $jLang['_ADMIN_CONF_NONE'];
					}
					?>
					<tr class="sectiontableentry<?php echo $subIndex%2+1; ?>">
					<th class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $jLang['_COM_SHOWTIPS_BONUS_TEAM']; ?></th>
					<td class="sectiontableentry<?php echo $subIndex%2+1; ?>" align="left"><?php echo $bonusTip; ?>&nbsp;</td>
					<?php
					if (!in_array('actual', $hideColumns)) {
						?>
						<td class="sectiontableentry<?php echo $subIndex%2+1; ?>"><?php echo $bonusTeam; ?>&nbsp;</td>
						<?php
					}
					if (!in_array('awarded', $hideColumns)) {
						?>
						<td class="sectiontableentry<?php echo $subIndex%2+1; ?>" style="text-align:right;">
						<?php
						if ($jGame->bonus_id == $jTip->bonus_id) {
							echo $this->jSeason->user_pick_bonus;
							$gameTotal += $this->jSeason->user_pick_bonus;
						} else {
							echo '0';
						}
						?>
						&nbsp;</td>
						<?php
					}
					?>
					</tr>
					<?php
				}
				?>
				</tbody>
				<?php
				if (!in_array('awarded', $hideColumns)) {
					?>
					<tfoot>
					<tr class="sectiontableheader">
					<th class="sectiontableheader" colspan="3"><?php echo $jLang['_COM_SHOWTIPS_TOTAL']; ?></th>
					<th class="sectiontableheader" style="text-align:right;"><?php echo $gameTotal; ?>&nbsp;</th>
					</tr>
					</tfoot>
					<?php
				}
				?>
				</table>
			</td>
			</tr>
			<?php
			$i++;
		}
		?>
		</tbody>
		</table>
		<?php
		/*
		 * Do we have a comment to show
		 */
		$jComment = new jComment($database);
		$jCommentParams = array(
			'user_id' => $this->jTipsUser->id,
			'round_id' => $this->jRound->id
		);
		$jComment->loadByParams($jCommentParams);
		if (isset($jComment->comment) && !empty($jComment->comment)) {
			?>
			<hr />
			<p align="center"><?php echo jTipsStripslashes($jComment->comment); ?></p>
			<?php
		}
		/*
		 * Show the Peek into Next Round link be shown and parsed
		 */
		if ($jTips['EnableShowTips'] == 1 and $showThisRound) {
			//getCurrentUser
			$current_round_id = $this->jSeason->getCurrentRound();
			$showFuture = false;
			if (isset($jTipsCurrentUser->id) and !empty($jTipsCurrentUser->id)) {
				if ($jTips['ShowTipsAccess'] == 'any' or ($jTips['ShowTipsAccess'] == 'processed' and $jTipsCurrentUser->hasTipped($current_round_id)) or ($jTips['ShowTipsAccess'] == 'inprogress' and $this->jRound->getStatus() !== false)) {
					$showFuture = true;
				}
			} else if ($jTips['ShowTipsAccess'] == 'any') {
				$showFuture = true;
			}
			if ($current_round_id != $this->jRound->id and $current_round_id and ($showFuture)) {
				$hide = array('result', 'actual', 'awarded');
				$colsToHide = json_encode($hide);
				$data = "&season={$this->jSeason->id}&hide=" .rawurlencode($colsToHide);
				if ($Itemid) $data .= "&Itemid=$Itemid";
				if (isJoomla15()) {
					?>
					<p align="center"><a href="<?php echo jTipsRoute("index2.php?option=com_jtips&view=CompetitionLadder&menu=0&action=ShowTips&uid=" .$this->jTipsUser->id. "&rid=" .$current_round_id.$data); ?>"><?php echo $jLang['_POPUP_TIPS_PEEK']; ?></a></p>
					<?php
				} else {
					?>
					<p align="center"><a href="javascript:loadTipsPopup(<?php echo $this->jTipsUser->id; ?>, <?php echo $current_round_id; ?>, $('mb_caption'), '<?php echo $data; ?>');"><?php echo $jLang['_POPUP_TIPS_PEEK']; ?></a></p>
					<?php
				}
			}
		}
		if ($jTips['ShowTipsPadding']) {
			echo "</div>";
		}
	}

	/**
	 * Displays additional statistics for this user
	 */
	function showStats() {
		global $jLang;
		?>
		<div style="text-align:center;">
		<table align="center" cellspacing="0" cellpadding="2" width="50%">
			<thead>
			<tr class="sectiontableheader">
			<th>&nbsp;</th>
			<th><?php echo $jLang['_COM_SHOWTIPS_THIS_ROUND']; ?></th>
			<th><?php echo $jLang['_COM_SHOWTIPS_OVERALL']; ?></th>
			</tr>
			</thead>
			<tbody>
			<tr class="sectiontableentry1">
			<td class="sectiontableentry1" style="font-weight:bold;"><?php echo $jLang['_COM_TIP_ACCURACY']; ?></td>
			<td class="sectiontableentry1" align="center"><?php echo $this->stats['tips']['round']; ?>%&nbsp;</td>
			<td class="sectiontableentry1" align="center"><?php echo $this->stats['tips']['overall']; ?>%&nbsp;</td>
			</tr>
			<?php
			$next = 2;
			if ($this->jSeason->precision_score) {
			?>
			<tr class="sectiontableentry<?php echo $next; ?>">
			<td class="sectiontableentry<?php echo $next; ?>" style="font-weight:bold;"><?php echo $jLang['_COM_PRECISION_SCORE']; ?></td>
			<td class="sectiontableentry<?php echo $next; ?>" align="center"><?php echo $this->stats['score']['round']; ?>&nbsp;</td>
			<td class="sectiontableentry<?php echo $next; ?>" align="center"><?php echo $this->stats['score']['overall']; ?>&nbsp;</td>
			</tr>
			<?php
			$next = ($next == 2 ? 1 : 2);
			}
			/*if ($this->jSeason->pick_margin) {
			?>
			<tr class="sectiontableentry<?php echo $next; ?>">
			<td style="font-weight:bold;">Pick The Margin Accuracy</td>
			<td><?php echo $this->stats['margin']['round']; ?>&nbsp;</td>
			<td><?php echo $this->stats['margin']['overall']; ?>&nbsp;</td>
			</tr>
			<?php
			$next = ($next == 2 ? 1 : 2);
			}
			if ($this->jSeason->pick_bonus) {
			?>
			<tr class="sectiontableentry<?php echo $next; ?>">
			<td style="font-weight:bold;">Pick The Bonus Accuracy</td>
			<td><?php echo $this->stats['bonus']['round']; ?>&nbsp;</td>
			<td><?php echo $this->stats['bonus']['overall']; ?>&nbsp;</td>
			</tr>
			<?php
			}*/
			?>
			</tbody>
		</table>
		</div>
		<?php
	}
}
?>
