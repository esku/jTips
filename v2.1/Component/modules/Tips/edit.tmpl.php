<?php
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
	var $games = array();
	
	function display() {
		global $jTips, $jLang;
		if (isJoomla15()) {
			JToolbarHelper::title($this->formData['title'], 'tips');
		} else {
			?>
			<table class='adminheading'>
				<tr><th><?php echo $this->formData['title']; ?></th></tr>
			</table>
			<?php
		}
		?>
		<form action='index2.php' name='adminForm' id='adminForm' method='post'>
			<input type="hidden" name="task" value="edit" />
			<input type="hidden" name="option" value="com_jtips" />
			<input type="hidden" name="module" value="Tips" />
			<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
			<table class='adminform'>
				<?php
				foreach ($this->selectLists as $label => $list) {
					?>
					<tr>
						<td width="20%"><?php echo $label; ?>:&nbsp;</td>
						<td><?php echo $list; ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php
				}
				?>
			</table>
			<table class="adminlist">
				<thead>
				<tr>
					<th colspan="2"><?php echo $jLang['_ADMIN_ROUND_HOME']; ?></th>
					<th><?php echo $jLang['_COM_TIPS_HOMESCORE']; ?></th>
					<th colspan="2"><?php echo $jLang['_ADMIN_ROUND_AWAY']; ?></th>
					<th><?php echo $jLang['_COM_TIPS_AWAYSCORE']; ?></th>
					<th width="5" align="center"><?php echo $jLang['_ADMIN_ROUND_DRAW']; ?></th>
					<th><?php echo $jLang['_ADMIN_TIPSMAN_SCORE_MARGIN']; ?></th>
					<th><?php echo $jLang['_ADMIN_CONF_BP']; ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($this->games as $game_id => $data) {
					?>
					<tr class="row<?php echo ($i++) % 2; ?>">
						<td width="5" align="center">
							<input type="hidden" name="game_id[]" value="<?php echo $game_id; ?>" />
							<input type="hidden" name="id[]" value="<?php echo $data['id']; ?>" />
							<input type="radio" name="g<?php echo $game_id; ?>[tip_id]" value="<?php echo $data['home']->id; ?>" <?php echo $data['home_tipped']; ?> />
						</td>
						<td><?php echo $data['home']->getName(); ?></td>
						<td><input type="text" name="g<?php echo $game_id; ?>[home_score]" size="5" value="<?php echo $data['home_score']; ?>" /></td>
						<td align="center"><input type="radio" name="g<?php echo $game_id; ?>[tip_id]" value="<?php echo $data['away']->id; ?>" <?php echo $data['away_tipped']; ?> /></td>
						<td><?php echo $data['away']->getName(); ?></td>
						<td><input type="text" name="g<?php echo $game_id; ?>[away_score]" size="5" value="<?php echo $data['away_score']; ?>" /></td>
						<td align="center"><input type="radio" name="g<?php echo $game_id; ?>[tip_id]" value="-1" <?php echo $data['draw_tipped']; ?> /></td>
						<td><input type="text" name="g<?php echo $game_id; ?>[margin]" size="5" value="<?php echo $data['margin']; ?>" /></td>
						<td><?php echo $data['bonus_id']; ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</form>
		<?php
	}
}
?>