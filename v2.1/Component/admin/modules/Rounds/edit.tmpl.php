<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Created: 27/03/2008
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
class EditMode {
	var $formData = array();
	var $selectLists = array();
	
	function display() {
		global $jTips, $jLang, $mainframe;
		jTipsCommonHTML::loadCalendar();
		if (isJoomla15()) {
			JToolBarHelper::title($this->formData['title'], 'round');
		} else {
			?>
			<table class='adminheading'>
			<tr>
				<th><?php echo $this->formData['title']; ?></th>
			</tr>
			</table>
			<?php
		}
		?>
		<style type="text/css">
		.hide {
			display:none;
		}
		</style>
		<form action='index2.php' name='adminForm' id='adminForm' method='post'>
			<input type="hidden" name="task" value="edit" />
			<input type="hidden" name="option" value="com_jtips" />
			<input type="hidden" name="module" value="Rounds" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="id" id="id" value="<?php echo $this->focus->id; ?>" />
			<input type="hidden" name="filter_order" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order'); ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order_Dir'); ?>" />
			<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
			<fieldset>
				<legend><?php echo $jLang['_ADMIN_ROUND_LEGEND']; ?></legend>
				<table class='admintable' width="100%">
				<tr>
				<td class="key" width="25%"><?php echo $jLang['_ADMIN_SEASON_SELECT']; ?></td>
				<td><?php echo $this->selectLists['season_id']; ?></td>
				</tr>
				<tr>
				<td class="key" width="25%"><?php echo $jLang['_ADMIN_ROUND_ROUND']; ?></td>
				<td><?php echo $this->selectLists['roundnum']; ?></td>
				</tr>
				<tr>
				<td class="key" width="25%"><?php echo $jLang['_ADMIN_ROUND_START']. " " .$jLang['_ADMIN_ROUND_DATE']. " &amp; " .$jLang['_ADMIN_ROUND_TIME']; ?></td>
				<td>
				<input type="text" name="date_start_date" size="30" value="<?php echo $this->date_start_date; ?>" id="date_start_date" />&nbsp;<img src='components/com_jtips/images/calendar.png' onclick='return showCalendar("date_start_date", "<?php echo $jTips['DateFormat']; ?>");' border='0' alt='...' align='absmiddle' />
				&nbsp;<?php echo $this->date_start_time_hour; ?>
				:<?php echo $this->date_start_time_minute; ?>
				&nbsp;<?php echo $this->date_start_time_meridiem; ?>
				</td>
				</tr>
				<tr>
				<td class="key" width="25%"><?php echo $jLang['_ADMIN_ROUND_END']. " " .$jLang['_ADMIN_ROUND_DATE']. " &amp; " .$jLang['_ADMIN_ROUND_TIME']; ?></td>
				<td>
				<input type="text" name="date_end_date" size="30" value="<?php echo $this->date_end_date; ?>" id="date_end_date" />&nbsp;<img src='components/com_jtips/images/calendar.png' onclick='return showCalendar("date_end_date", "<?php echo $jTips['DateFormat']; ?>");' border='0' alt='...' align='absmiddle' />
				&nbsp;<?php echo $this->date_end_time_hour; ?>
				:<?php echo $this->date_end_time_minute; ?>
				&nbsp;<?php echo $this->date_end_time_meridiem; ?>
				</td>
				</tr>
				<tr>
				<td class="key" width="25%"><label for="show_comments"><?php echo $jLang['_ADMIN_ROUND_SHOW_COMMENTS']; ?></label></td>
				<td><input type="checkbox" id="show_comments" onClick="$$('.info').toggleClass('hide');" /></td>
				</tr>
				</table>
			</fieldset>
			<fieldset>
			<legend><?php echo $jLang['_ADMIN_ROUND_GAMES_LEGEND']; ?></legend>
			<?php
			if (!$this->focus->getStatus() or !$this->focus->exists()) {
				?>
			<p><input type="button" disabled class="button" onClick="addGameRow();toggleColumns();" id="addgame" value="Add Game" />
			&nbsp;<input type="button" disabled  class="button" onClick="removeGameRow();toggleColumns();" id="removegame" value="Remove Game(s)" /></p>
				<?php
			}
			?>
			<div id="round_loading" style="text-align:center;"><img src="components/com_jtips/images/loading.gif" alt="Loading..." border="0" /></div>
			<table class="adminlist" id="games_list" style="display:none;opacity:0;">
				<thead>
				<tr>
					<!--th align='center' width='1'>#</th-->
					<th align="center" width='20'>&nbsp;</th>
					<th id="left_team_th">&nbsp;</th>
					<th id="right_team_th">&nbsp;</th>
					<th><?php echo $jLang['_ADMIN_ROUND_ORDER']; ?></th>
					<th class="pick_score"><input type='checkbox' onclick='toggleYesNo(this, "score");' <?php echo ((is_numeric($this->status) and $this->focus->exists()) ? "style='display:none;'" : ""); ?> id="pick_score_toggle" /> <label for="pick_score_toggle"><?php echo $jLang['_ADMIN_GAME_HAS_SCORE']; ?></label></th>
					<th class="pick_margin"><input type='checkbox' onclick='toggleYesNo(this, "margin");' <?php echo ((is_numeric($this->status) and $this->focus->exists()) ? "style='display:none;'" : ""); ?> id="pick_margin_toggle" /> <label for="pick_margin_toggle"><?php echo $jLang['_ADMIN_GAME_HAS_MARGIN']; ?></label></th>
					<th class="pick_bonus"><input type='checkbox' onclick='toggleYesNo(this, "bonus");' <?php echo ((is_numeric($this->status) and $this->focus->exists()) ? "style='display:none;'" : ""); ?> id="pick_bonus_toggle" /> <label for="pick_bonus_toggle"><?php echo $jLang['_ADMIN_GAME_HAS_BONUS']; ?></label></th>
		            <th id="left_start_th" class="team_starts">&nbsp;</th>
		            <th id="right_start_th" class="team_starts">&nbsp;</th>
		            <th id="tough_score_th" class="tough_score">&nbsp;</th>
		            <th class="date_time"><?php echo $jLang['_ADMIN_GAME_TIME']; ?></th>
		            <th id="left_score_th" class="results">&nbsp;</th>
		            <th id="right_score_th" class="results">&nbsp;</th>
		            <th class="bonus_results"><?php echo $jLang['_ADMIN_GAME_BONUS']; ?></th>
			    <th id="info_th" class="info hide"><?php echo $jLang['_ADMIN_ROUNDS_INFO']; ?></th>
				</tr>
				</thead>
				<tbody id="table">
				</tbody>
			</table>
			</fieldset>
		</form>
		<?php
		$init = "
		<script type='text/javascript'>
		window.addEvent('domready', function() {
			getTheRounds($('season_id'))
		});
		</script>";
		if (isJoomla15()) {
			$mainframe->addCustomHeadTag($init);
		} else {
			echo $init;
		}
	}
}
?>
