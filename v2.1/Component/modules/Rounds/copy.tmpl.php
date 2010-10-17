<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1.9 - 06/04/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: 
 */
class CopyMode {
	var $formData = array();
	function display() {
		global $jLang;
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
		<table class="adminform" width="100%">
			<tbody>
			<tr>
				<td><?php echo $jLang['_ADMIN_ROUND_COPY_INFO']; ?></td>
			</tr>
		</table>
		<form action="index2.php" name="adminForm" id="adminForm" method="post">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="option" value="com_jtips" />
			<input type="hidden" name="module" value="Rounds" />
			<input type="hidden" name="id" value="<?php echo $this->round_id; ?>" />
			<fieldset>
				<legend><?php echo $jLang['_ADMIN_ROUND_COPY_FROM']; ?></legend>
				<table class="admintable" width="100%">
				<tr>
					<td class="key" width="25%"><?php echo $jLang['_ADMIN_ROUND_COPYING']; ?></td>
					<td><?php echo $jLang['_ADMIN_ROUND_ROUND']; ?> <?php echo $this->round; ?> - <?php echo $this->season_name; ?></td>
				</tr>
				<tr>
					<td class="key"><?php echo $jLang['_ADMIN_ROUND_COPY_TO_SEASON']; ?></td>
					<td><?php echo $this->season_name; ?></td>
				</tr>
				<tr>
					<td class="key"><label for="round"><?php echo $jLang['_ADMIN_ROUND_COPY_TO_ROUND']; ?></label></td>
					<td><input type="text" name="round" id="round" class="inputbox" size="5" maxlength="3" /></td>
				</tr>
				</table>
			</fieldset>
		</form>
		<?php
	}
}