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
			JToolBarHelper::title($this->formData['title'], 'user');
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
		<script type='text/javascript'>
		function submitbutton(pressbutton) {
			if (pressbutton != 'list') {
				//are any users selected?
				if(document.adminForm.boxchecked.value==0) {
					alert('<?php echo $jLang['_ADMIN_USER_NEW_NO_SELECTION']; ?>');
					return;
				}
				
				//is a season selected?
				if ($('season_id').value == '') {
					alert("<?php echo $jLang['_ADMIN_SEASON_SELECT_ALERT']; ?>");
					return;
				}
			}
			submitform(pressbutton);
		}
		</script>
		<form action='index2.php' name='adminForm' id='adminForm' method='post'>
			<input type="hidden" name="task" value="edit" />
			<input type="hidden" name="option" value="com_jtips" />
			<input type="hidden" name="module" value="Users" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order'); ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order_Dir'); ?>" />
			<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
			<table class="adminform">
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
					<th align='center' width='1'>#</th>
					<th align="center" width='20'><input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($this->users); ?>);" /></th>
					<th><a href="javascript:tableOrdering('name', '<?php echo $this->nextDir; ?>', 'edit');"><?php echo $jLang['_ADMIN_USERS_FULLNAME']; ?></a></th>
					<th><a href="javascript:tableOrdering('username', '<?php echo $this->nextDir; ?>', 'edit');"><?php echo $jLang['_ADMIN_USERS_USERNAME']; ?></a></th>
					<th><a href="javascript:tableOrdering('email', '<?php echo $this->nextDir; ?>', 'edit');"><?php echo $jLang['_ADMIN_USERS_EMAIL']; ?></a></th>
				</tr>
				</thead>
				<tbody>
				<?php
				$i = 0;
				foreach ($this->users as $user) {
					?>
					<tr class="row<?php echo ($i%2); ?>">
						<td align="center"><?php echo $i+1; ?></td>
						<td align="center"><?php echo jTipsHTML::idBox($i, $user->id); ?></td>
						<td><label for="cb<?php echo $i; ?>"><?php echo $user->name; ?></label></td>
						<td><?php echo $user->username; ?></td>
						<td><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></td>
					</tr>
					<?php
					$i++;
				}
				?>
				</tbody>
				<?php
				if (isJoomla15()) {
					?>
					<tfoot>
					<tr>
					<td colspan="50"><?php echo $this->pageNav->getListFooter(); ?></td>
					</tr>
					</tfoot>
					<?php
				}
				?>
				</table>
				<?php
				if (!isJoomla15()) {
					echo $this->pageNav->getListFooter();
					
				}
				?>
		</form>
		<?php
	}
}
?>