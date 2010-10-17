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
		<form action='index2.php' method='post' name='adminForm' enctype='multipart/form-data'>
		<input type='hidden' name='task' value='' />
		<input type='hidden' name='option' value='<?php echo jTipsGetParam($_REQUEST, 'option', 'com_jtips'); ?>' />
		<input type='hidden' name='hidemainmenu' value='0' />
		<input type="hidden" name="module" value="Import" />
		<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
		<?php
		if (isJoomla15()) {
			JToolBarHelper::title($jLang['_ADMIN_IMP_HEADER'], 'import');
		} else {
			?>
			<table class='adminheading'>
				<tr>
					<th><?php echo $jLang['_ADMIN_IMP_HEADER']; ?></th>
				</tr>
			</table>
			<?php
		}
		?>
		<?php
		if ($this->importExists) {
			?>
			<fieldset>
			<legend><?php echo $jLang['_ADMIN_IMPORT_SETUP_LEGEND']; ?></legend>
			<input type="hidden" name="hasFile" id="hasFile" value="1" />
			<input type="hidden" name="importFile" value="1" id="importFile" />
			<table class='admintable' width="100%">
			<tr>
				<td class="key"><label for="importObject"><?php echo $jLang['_ADMIN_IMP_SELECT_TYPE']; ?></label></td>
				<td><?php echo $this->selectLists['objects']; ?><span id='loading' style='display:none;'><?php echo getAjaxLoading(); ?></span></td>
			</tr>
			<tr id="match_row" style="display:none;">
				<td class="key" valign="top"><?php echo $jLang['_ADMIN_IMP_MATCH_SELECT']; ?>:</td>
				<td><select name="match_on[]" id="match_on" multiple="multiple" class="inputbox"></select></td>
			</tr>
			</table>
			</fieldset>
			<fieldset>
			<legend><?php echo $jLang['_ADMIN_IMP_FIELD_MAPPING_LEGEND']; ?></legend>
			<table class='adminlist' width="100%">
			<thead>
			<tr>
				<th width='25%'><?php echo $jLang['_ADMIN_IMP_COL_HEADER']; ?></th>
				<th width='25%'><?php echo $jLang['_ADMIN_IMP_FIELD_MAP']; ?></th>
				<th width='25%'>Row 1 Data</th>
				<th width='25%'>Row 2 Data</th>
			</tr>
			</thead>
			<tbody>
			<?php
			for ($i=0; $i<count($this->headers); $i++) {
				$col = cleanString($this->headers[$i]);
				$row1 = isset($this->row1Data[$i]) ? $this->row1Data[$i] : '';
				$row2 = isset($this->row2Data[$i]) ? $this->row2Data[$i] : '';
				?>
				<tr class="row<?php echo $i%2; ?>">
					<td class="key" width='25%'><?php echo $col; ?></td>
					<td><?php echo jTipsHTML::selectList($this->noneOptions, "importFields[$col]", 'disabled id="' .$col. '" class="importFields"', 'value', 'text'); ?></td>
					<td style='text-align:left;'><?php echo jTipsHTML::cleanText($row1); ?></td>
					<td style='text-align:left;'><?php echo jTipsHTML::cleanText($row2); ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
			</table>
			</fieldset>
			<?php
		} else {
			?>
			<fieldset>
			<legend><?php echo $jLang['_ADMIN_IMPORT_UPLOAD_LEGEND']; ?></legend>
			<input type="hidden" name="hasFile" id="hasFile" value="0" />
			<table class='admintable' width="100%">
			<tr>
				<td class="key" width='25%'><label for="importFile"><?php echo $jLang['_ADMIN_IMP_UPLOAD']; ?></label></td>
				<td><input type='file' name='importFile' id="importFile" size='40' /></td>
			</tr>
			</table>
			</fieldset>
			<?php
		}
		?>
		</form>
		<?php
	}
}
?>