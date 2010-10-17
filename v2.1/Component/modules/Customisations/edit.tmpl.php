<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 23/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

class EditMode {
	function assign($var, $val) {
		$this->$var = $val;
	}
	
	function displayFileSetup() {
		global $jLang;
		?>
		<fieldset>
		<legend><?php echo $jLang['_ADMIN_CSTM_FILE_SETUP']; ?></legend>
		
		<table class="admintable">
		<tbody>
		<tr>
			<td class="key" width="25%"><label for="view"><?php echo $jLang['_ADMIN_CSTM_VIEW']; ?></label></td>
			<td><?php echo $this->viewList; ?></td>
		</tr>
		<tr>
			<td class="key" width="25%"><label for="tmpl"><?php echo $jLang['_ADMIN_CSTM_IS_TMPL']; ?></label></td>
			<td><input type="checkbox" name="tmpl" id="tmpl" value="1" onClick="toggleFileName(this);"/>
		</tr>
		<tr>
			<td class="key" width="25%"><label for="filename"><?php echo $jLang['_ADMIN_CSTM_FILE_NAME']; ?></label></td>
			<td><input type="text" name="filename" id="filename" class="inputbox" size="40" /></td>
		</tr>
		<tr>
			<td class="key" width="25%"><label for="fileupload"><?php echo $jLang['_ADMIN_CSTM_FILE_UPLOAD']; ?></label></td>
			<td><input class="inputbox" id="fileupload" name="fileupload" type="file" /></td>
		</tr>
		</tbody>
		</table>
		
		</fieldset>
		<?php
	}
	
	function display() {
		global $jLang;
		if (isJoomla15()) {
			JToolbarHelper::title($jLang['_ADMIN_EDIT_CUSTOMISATIONS_TITLE'], 'customisations');
		} else {
			?>
			<table class='adminheading'>
				<tr><th><?php echo $jLang['_ADMIN_EDIT_CUSTOMISATIONS_TITLE']; ?></th></tr>
			</table>
			<?php
		}
		?>
		<form action="index2.php?option=com_jtips" method="POST" name="adminForm" enctype="multipart/form-data">
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="module" value="Customisations" />
			<input type="hidden" name="path" value="<?php echo $this->path; ?>" />
			<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
			<?php
			if (!$this->path) {
				$this->displayFileSetup();
			}
			?>
			<fieldset>
			<legend><?php echo $jLang['_ADMIN_CSTM_FILE_EDIT']; ?></legend>
			<table class='adminform'>
				<thead>
				<tr>
				<th><?php echo $this->path; ?></th>
				</tr>
				</thead>
				<tbody>
				<tr>
				<td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" id="filecontent" class="inputbox"><?php echo $this->content; ?></textarea></td>
				</tr>
				</tbody>
			</table>
			</fieldset>
		</form>
		<?php
		
	}
}

?>