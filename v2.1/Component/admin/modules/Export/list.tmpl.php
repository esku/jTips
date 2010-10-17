<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 08/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */
class ListMode {
	var $formData = array();
	var $selectLists = array();
	
	function display() {
		global $jLang;
		?>
		<form action='index2.php' method='post' name='adminForm'>
			<input type='hidden' name='task' value='list' />
			<input type='hidden' name='option' value='<?php echo jTipsGetParam($_REQUEST, 'option', 'com_jtips'); ?>' />
			<input type='hidden' name='module' value='Export' />
			<input type='hidden' name='hidemainmenu' value='0' />
			<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
			<?php
			if (isJoomla15()) {
				JToolBarHelper::title($jLang['_ADMIN_CONF_EXPBTN'], 'export');
			} else {
				?>
				<table class='adminheading'>
					<tr><th><?php echo $jLang['_ADMIN_CONF_EXPBTN']; ?></th></tr>
				</table>
				<?php
			}
			?>
			<fieldset>
			<legend><?php echo $jLang['_ADMIN_EXP_INFO']; ?></legend>
			<table class='admintable' width="100%">
				<tr>
					<td class="key" width="25%"><?php echo $jLang['_ADMIN_EXP_PREVIOUS']; ?></td>
					<td><?php echo $this->selectLists['history']; ?>&nbsp;<?php echo $this->selectLists['actions']; ?>&nbsp;<input type='submit' name='doAction' value='Go!' class='button' <?php echo $this->disableExportButton; ?> onclick="this.form.task.value='history'" /></td>
				</tr>
				<tr>
					<td class="key" width="25%"><?php echo $jLang['_ADMIN_EXP_SELECT_TYPE']; ?></td>
					<td><?php echo $this->selectLists['objects']; ?>&nbsp;<input type="submit" name="getExport" value="Export Data" class="button" onclick="this.form.task.value='export'" /></td>
				</tr>
			</table>
			</fieldset>
		</form>
		<?php
	}
}
?>