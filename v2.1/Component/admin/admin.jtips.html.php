<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

defined('_JEXEC') or defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

class jTipsAdminDisplay {
	function ListView($formData, $header, $data, $pageNav, $returnTask='', $filters=null, $info=null, $showProgressBar=false) {
		global $mainframe, $jTips, $jLang;
		if ($showProgressBar) {
			$slidercode = "<script type='text/javascript'>window.addEvent('domready', function() {slider = new Fx.Slide('progressbar', {duration: 500});slider.hide();});</script>";
			?>
			<div class="progress" id="progressbar">
				<span><img src="components/com_jtips/images/saving.gif" border="0" alt="<?php echo $jLang['_ADMIN_AJAX_PROCESSING']; ?>" /><br />
				<?php echo $jLang['_ADMIN_AJAX_PROCESSING']; ?></span>
			</div>
			<?php
			//BUG 258 - The javascript in J1.0 must be echoed after the DOM element
			if (isJoomla15()) {
				$mainframe->addCustomHeadTag($slidercode);
			} else {
				print($slidercode);
			}
		}
		?>
		<form action='index2.php' method='post' name='adminForm' id="adminForm" enctype="multipart/form-data">
		<input type="hidden" name="filter_order" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order'); ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order_Dir'); ?>" />
		<input type='hidden' name='option' value='<?php echo jTipsGetParam($_REQUEST, 'option', 'com_jtips'); ?>'>
		<input type='hidden' name='task' value='<?php echo jTipsGetParam($_REQUEST, 'task', $returnTask); ?>'>
		<input type='hidden' name='module' value='<?php echo jTipsGetParam($_REQUEST, 'module', ''); ?>'>
		<input type='hidden' name='hidemainmenu' id="hidemainmenu" value="">
		<input type='hidden' name='boxchecked' value='0'>
		<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
		<?php
		if (isJoomla15()) {
			if (!isset($formData['icon']) or empty($formData['icon'])) {
				$formData['icon'] = 'generic';
			}
			JToolBarHelper::title($formData['title'], $formData['icon']);
		} else {
			?>
			<table class='adminheading' width="100%">
			<tbody>
			<tr>
			<th valign="top" align="left" class="edit" nowrap><?php echo $formData['title']; ?></th>
			</tr>
			</tbody>
			</table>
			<?php
		}
		if (is_array($filters) or !is_null($info)) {
			?>
			<table width="100%" class="adminform">
			<tbody><tr><td width="100%">
			<?php
			echo $info;
			if (is_array($filters) and !empty($filters)) {
				?>
				</td><td nowrap>
				<?php
				foreach ($filters as $label => $formField) {
					echo $formField;
				}
			}
			?>
			</td></tr></tbody>
			<?php
		}
		if (!is_null($info) and false) {
			?>
			<tfoot>
			<tr>
			<td colspan="<?php echo count($filters)+1; ?>"><?php echo $info; ?></td>
			</tr>
			</tfoot>
			<?php
		}
		?>
		</table>
		<table class='adminlist'>
		<thead>
		<tr>
		<?php
		$hasIntCol = false;
		foreach ($header as $label) {
			if ($label == '' and !$hasIntCol) {
				?><th align='center' width='1'>#</th><?php
				$hasIntCol = true;
			}
			if ($label == '' and $hasIntCol) {
				?><th align='center' width='5'><input type="checkbox" name="toggle" onClick="checkAll(<?php echo count($data); ?>);" /></th><?php
			} else {
				?>
				<th><?php echo $label; ?>&nbsp;</th>
				<?php
			}
		}
		?>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($data as $id => $row) {
			?>
			<tr class="row<?php echo (($i) % 2); ?>">
			<?php
			if ($header[0] == '') {
				?>
				<td width='1'><?php echo $i+1; ?></td>
				<td width="5"><?php echo jTipsHTML::idBox($i, $id); ?></td>
				<?php
			}
			foreach ($row as $cell) {
				if (is_numeric($cell)) {
					$align = "center";
				} else {
					$align = "left";
				}
				?>
				<td align="<?php echo $align; ?>"><?php echo jTipsStripslashes($cell); ?></td>
				<?php
			}
			//BUG 264 - When no checkbox is shown, the row number is never increased, so the style never changes
			$i++;
			?>
			</tr>
			<?php
		}
		?>
		</tbody>
		<?php
		if (isJoomla15() and !is_null($pageNav)) {
			// BUG 400 - colspan changed to be calculated rather than fixed at 50, caused display errors in IE
			?>
			<tfoot>
			<tr>
			<td colspan="<?php echo count($header)+1; ?>"><?php echo $pageNav->getListFooter(); ?></td>
			</tr>
			</tfoot>
			<?php
		}
		?></table><?php
		if (!isJoomla15() and !is_null($pageNav)) {
			echo $pageNav->getListFooter();
		}
		?>
		</form>
		<?php
	}
	
	//function EditView($formData, &$seed) {
	function EditView($title, $formData, $icon='generic') {
		global $jTips, $jLang;
		jTipsCommonHTML::loadCalendar();
		?>
		<form action="index2.php" name="adminForm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="option" value='<?php echo jTipsGetParam($_REQUEST, 'option', 'com_jtips'); ?>'>
		<input type='hidden' name='task' value=''>
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type='hidden' name='module' value='<?php echo jTipsGetParam($_REQUEST, 'module', ''); ?>'>
		<input type="hidden" name="<?php echo jTipsSpoofValue(); ?>" value="1" />
		<?php
		if (isJoomla15()) {
			JToolBarHelper::title($title, $icon);
		} else {
			?>
			<table class="adminheading">
			<tr><th class="edit"><?php echo $title; ?></th></tr>
			</table>
			<?php
		}
		foreach ($formData as $fieldset) {
			?>
			<fieldset>
			<legend><?php echo $jLang[$fieldset['legend']]; ?></legend>
			<table class="admintable" width="100%">
			<tbody>
			<?php
			$hiddenFields = array();
			foreach ($fieldset['fields'] as $row) {
				if ($row['field']['type'] == 'hidden') {
					$hiddenFields[] = $row;
					continue;
				}
				if ($row['field']['type'] == 'editor') {
					$colspan = 2;
				} else {
					$colspan = 1;
				}
				?><tr>
				<td class="key" width="25%"><label for="<?php echo $row['field']['attributes']['id']; ?>"><?php echo $jLang[$row['label']]; ?></label></td>
				<td width="25%" colspan="<?php echo $colspan; ?>"><?php echo parseEditField($row['field']); ?></td>
				<?php
				if ($colspan == 1) {
					?>
				<td width="50%"><?php echo isset($row['description']) ? $jLang[$row['description']] : ""; ?>&nbsp;</td>
					<?php
				}
				?>
				</tr><?php
			}
			?>
			</tbody>
			<tfoot>
			</tfoot>
			</table>
			<?php
			//now loop on the hidden fields
			if (!empty($hiddenFields)) {
				foreach ($hiddenFields as $def) {
					echo parseEditField($def['field']);
				}
			}
			?>
			</fieldset>
			<?php
		}
		?>
		</form>
		<?php
	}
}
?>
