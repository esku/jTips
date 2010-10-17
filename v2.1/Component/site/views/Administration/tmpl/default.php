<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 13/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */
class jTipsRenderAdministration {
	function assign($key, $val) {
		$this->$key = $val;
	}
	
	function displayRoundPicker() {
		global $mosConfig_live_site, $jLang, $Itemid;
		//this javascript needs to be here so it loads for the popup and for the full page
		?>
		<script type="text/javascript">
		window.addEvent('domready', function() {
			$('jTipsAdministrationForm').addEvent('submit', function(e) {
				new Event(e).stop();
				if ($('roundnum').value=='') return false;
				var log = $('loader1').empty().addClass('ajax-loading');
				this.send({
					update: log,
					onComplete: function() {
						log.removeClass('ajax-loading');
						if ($('season_id')) {
							initAdministration();
							addRoundEditFormSubmit();
						} else {
							//adding scores to a round
							addRoundScoreFormSubmit();
						}
					}
				});
			});
		});
		</script>
		<style type='text/css'>
		#loader1.ajax-loading {
			background:url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/loading.gif) no-repeat center;
			padding: 20px 0;
		}
		</style>
		<form action="<?php echo $mosConfig_live_site; ?>/index2.php?Itemid=<?php echo $this->Itemid; ?>" method="post" id="jTipsAdministrationForm">
		<input type="hidden" name="option" value="com_jtips" />
		<input type="hidden" name="view" value="Administration" />
		<input type="hidden" name="menu" value="0" />
		<div class="contentheading"><?php echo $this->jSeason->name; ?></div>
		<div class="contentheading" id="round_select" style="text-align:center;">
			<?php echo $jLang['_COM_ADMIN_RESULTS_SELECT_ROUND']; ?>: <?php echo $this->roundDropDown; ?>
			<input type="submit" value="Load Round" name="load_round" class="button" /></div>
		</form>
		<div id="loader1"></div>
		<?php
	}
	
	//display the results entry form
	function display() {
		global $jLang, $database, $mosConfig_live_site, $Itemid;
		$left_id_field = $this->left. '_id';
		$right_id_field = $this->right. '_id';
		$left_score = $this->left. '_score';
		$right_score = $this->right. '_score';
		?>
		<style type='text/css'>
		#loader2.ajax-loading {
			background:url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/loading.gif) no-repeat center;
			padding: 20px 0;
		}
		</style>
		<div id="loader2"></div>
		<div id="games_table">
		<form action="index2.php?Itemid=<?php echo $Itemid; ?>" method="post" name="adminForm" id="jTipsRoundProcessForm">
		<input type="hidden" name="option" value="com_jtips" />
		<input type="hidden" name="view" value="Administration" />
		<input type="hidden" name="action" value="SaveRoundResults" />
		<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
		<table class="admintable" cellspacing="0" width="100%">
		<thead>
		<tr class="sectiontableheader">
		<th><?php echo $jLang['_COM_GAME_' .strtoupper($this->left)]; ?></th>
		<th><?php echo $jLang['_COM_GAME_' .strtoupper($this->right)]; ?></th>
		<th><?php echo $jLang['_COM_TIPS_' .strtoupper($this->left). 'SCORE']; ?></th>
		<th><?php echo $jLang['_COM_TIPS_' .strtoupper($this->right). 'SCORE']; ?></th>
		<th><?php echo $jLang['_ADMIN_GAME_BONUS']; ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		$rowIndex = 0;
		foreach ($this->jGames as $jGame) {
			$leftTeam  = new jTeam($database);
			$rightTeam = new jTeam($database);
			$leftTeam->load($jGame->$left_id_field);
			$rightTeam->load($jGame->$right_id_field);
			?>
			<tr class="sectiontableentry<?php echo $rowIndex++%2+1; ?>">
			<td align="left">
				<input type="hidden" name="cid[]" value="<?php echo $jGame->id; ?>" />
				<?php echo $leftTeam->getDisplayLogoName(); ?>
			</td>
			<td align="left"><?php echo $rightTeam->getDisplayLogoName(); ?></td>
			<td><input type="text" size="5" class="inputbox" autocomplete="off" style="text-align:center;" name="<?php echo $left_score; ?>[]" value="<?php echo $jGame->$left_score; ?>" /></td>
			<td><input type="text" size="5" class="inputbox" autocomplete="off" style="text-align:center;" name="<?php echo $right_score; ?>[]" value="<?php echo $jGame->$right_score; ?>" /></td>
			<td align="left">
				<?php
				$bonusDD = array(
					jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']),
					jTipsHTML::makeOption($leftTeam->id, $leftTeam->getName()),
					jTipsHTML::makeOption($rightTeam->id, $rightTeam->getName())
				);
				if ($this->jSeason->pick_bonus == 2) {
					$bonusDD[] = jTipsHTML::makeOption(-2, $jLang['_ADMIN_SEASON_EPTB_BOT']);
				}
				echo jTipsHTML::selectList($bonusDD, 'bonus_id[]', "class='inputbox' style='width:95%;'", 'value', 'text', $jGame->bonus_id);
				?>
			</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		<div class="contentheading" style="text-align:center;">
		<?php
		if (isJoomla15()) {
			?>
			<input type="submit" value="<?php echo $jLang['_COM_ADMIN_RESULTS_SAVE_PROCESS']; ?>" name="submit_process" class="button" />
			<input type="button" value="<?php echo $jLang['_COM_CANCEL']; ?>" name="cancel" onClick="window.parent.document.getElementById('sbox-window').close();" class="button" />
			<?php
		} else {
			?>
			<input type="submit" value="<?php echo $jLang['_COM_ADMIN_RESULTS_SAVE_PROCESS']; ?>" name="submit_process" class="button" />
			<input type="button" value="<?php echo $jLang['_COM_CANCEL']; ?>" name="cancel" onClick="$('mb_close_link').onclick();" class="button" />
			<?php
		}
		?>
		</div>
		</form>
		</div>
		<?php
	}
	
	function displayEditRound() {
		global $jLang, $mosConfig_live_site, $jTips, $Itemid;
		?>
		<style type='text/css'>
		#loader3.ajax-loading {
			background:url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/loading.gif) no-repeat center;
			padding: 20px 0;
		}
		</style>
		<div id="loader3"></div>
		<form action="index2.php?Itemid=<?php echo $Itemid; ?>" method="post" id="jTipsRoundEditForm">
		<input type="hidden" name="option" value="com_jtips" />
		<input type="hidden" name="view" value="Administration" />
		<input type="hidden" name="action" value="SaveRound" />
		<input type="hidden" name="season_id" id="season_id" value="<?php echo $this->jSeason->id; ?>" />
		<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
		<input type="hidden" name="id" id="id" value="<?php echo $this->focus->id; ?>" />
		<input type="hidden" name="roundnum" id="roundnum" value="<?php echo $this->roundnum; ?>" />
			<h2><?php echo $jLang['_ADMIN_ROUND_LEGEND']; ?></h2>
			<table class='admintable' width="100%">
			<tr>
			<td class="key" width="25%"><?php echo $jLang['_ADMIN_ROUND_START']. " " .$jLang['_ADMIN_ROUND_DATE']. " &amp; " .$jLang['_ADMIN_ROUND_TIME']; ?></td>
			<td>
			<input type="text" class="inputbox" name="date_start_date" size="30" value="<?php echo $this->date_start_date; ?>" id="date_start_date" />&nbsp;<img src='<?php echo $mosConfig_live_site; ?>/administrator/components/com_jtips/images/calendar.png' onclick='return showCalendar("date_start_date", "<?php echo $jTips['DateFormat']; ?>");' border='0' alt='...' align='absmiddle' />
			&nbsp;<?php echo $this->date_start_time_hour; ?>
			:<?php echo $this->date_start_time_minute; ?>
			&nbsp;<?php echo $this->date_start_time_meridiem; ?>
			</td>
			</tr>
			<tr>
			<td class="key" width="25%"><?php echo $jLang['_ADMIN_ROUND_END']. " " .$jLang['_ADMIN_ROUND_DATE']. " &amp; " .$jLang['_ADMIN_ROUND_TIME']; ?></td>
			<td>
			<input type="text" class="inputbox"  name="date_end_date" size="30" value="<?php echo $this->date_end_date; ?>" id="date_end_date" />&nbsp;<img src='<?php echo $mosConfig_live_site; ?>/administrator/components/com_jtips/images/calendar.png' onclick='return showCalendar("date_end_date", "<?php echo $jTips['DateFormat']; ?>");' border='0' alt='...' align='absmiddle' />
			&nbsp;<?php echo $this->date_end_time_hour; ?>
			:<?php echo $this->date_end_time_minute; ?>
			&nbsp;<?php echo $this->date_end_time_meridiem; ?>
			</td>
			</tr>
			<tr>
			<td class="key" width="25%"><label for="show_jtips_info"><?php echo $jLang['_ADMIN_ROUND_SHOW_COMMENTS']; ?></label></td>
			<td><input type="checkbox" id="show_jtips_info" onClick="if(this.checked){$$('.jtipsinfo').setStyle('display', '');}else{$$('.jtipsinfo').setStyle('display', 'none');}" /></td>
			</tr>
			</table>
		<h2><?php echo $jLang['_ADMIN_ROUND_GAMES_LEGEND']; ?></h2>
		<?php
		if (!$this->focus->getStatus() or !$this->focus->exists()) {
			?>
			<p><input type="button" class="button" onClick="addGameRow();" id="addgame" value="Add Game" />
			&nbsp;<input type="button"  class="button" onClick="removeGameRow();" id="removegame" value="Remove Game(s)" /></p>
			<?php
		}
		?>
		<table class="admintable" cellspacing="0" width="100%">
		<thead>
		<tr class="sectiontableheader">
			<!--th align='center' width='1'>#</th-->
			<th align="center" width='20'>&nbsp;</th>
			<th id="left_team_th"><?php echo $jLang['_ADMIN_ROUND_' .strtoupper($this->left)]; ?></th>
			<th id="right_team_th"><?php echo $jLang['_ADMIN_ROUND_' .strtoupper($this->right)]; ?></th>
			<th class="pick">Enable Picks</th>
			<th id="left_start_th" class="team_starts"><?php echo $jLang['_ADMIN_GAME_' .strtoupper($this->left). '_START']; ?></th>
			<th id="right_start_th" class="team_starts"><?php echo $jLang['_ADMIN_GAME_' .strtoupper($this->right). '_START']; ?></th>
			<th class="date_time"><?php echo $jLang['_ADMIN_GAME_TIME']; ?></th>
			<th class="jtipsinfo" style="display:none;"><?php echo $jLang['_ADMIN_ROUNDS_INFO']; ?></th>
		</tr>
		</thead>
		<tbody id="table">
		</tbody>
		</table>
		<div class="contentheading" style="text-align:center;">
		<?php
		if (isJoomla15()) {
			?>
			<input type="submit" value="<?php echo $jLang['_COM_SAVE']; ?>" name="submit_round" class="button" />
			<input type="button" value="<?php echo $jLang['_COM_CANCEL']; ?>" name="cancel_round" onClick="window.parent.document.getElementById('sbox-window').close();" class="button" />
			<?php
		} else {
			?>
			<input type="submit" value="<?php echo $jLang['_COM_SAVE']; ?>" name="submit_round" class="button" />
			<input type="button" value="<?php echo $jLang['_COM_CANCEL']; ?>" name="cancel_round" onClick="$('mb_close_link').onclick();" class="button" />
			<?php
		}
		?>
		</div>
		</form>
		<?php
	}
	
	function displayNoRounds() {
		global $jLang;
		?>
		<p class="error">No rounds pending results</p>
		<?php
	}
}

?>
