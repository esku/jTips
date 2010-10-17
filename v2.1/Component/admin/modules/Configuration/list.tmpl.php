<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 09/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: 
 */

class ListMode {
	
	function display() {
		global $jLang;
		
		?>
		<form action='index2.php' method='post' name='adminForm' enctype='multipart/form-data'>
		<input type='hidden' name='task' value='' />
		<input type='hidden' name='option' value='<?php echo jTipsGetParam($_REQUEST, 'options', 'com_jtips'); ?>' />
		<input type='hidden' name='hidemainmenu' value='0' />
		<input type='hidden' name='module' value='Configuration' />
		<input type='hidden' name='<?php echo jTipsSpoofValue(); ?>' value='1' />
		<?php
		
		if (isJoomla15()) {
			JToolbarHelper::title($jLang['_ADMIN_CONF_TITLE']. " - " .getVersionNum(), 'config');
		} else {
			?>
			<table class='adminheading'>
			<tr><th><?php echo $jLang['_ADMIN_CONF_TITLE']. " - " .getVersionNum(); ?></th></tr>
			</table>
			<?php
		}
		
		?>
		<div style='width: 100%;'>
		<?php
		$tabs = new jTipsTabs(1);
		$tabs->startPane('dashboard');
		foreach ($this->configuration as $tab => $items) {
			$tabs->startTab(ucwords($tab), $tab);
			?>
			&nbsp;
			<table class='adminlist'>
				<thead>
				<tr>
					<th align='left' width='20%'><?php echo $jLang['_ADMIN_CONF_SETTING']; ?></th>
					<th align='left' width='20%'><?php echo $jLang['_ADMIN_CONF_VARIABLE']; ?></th>
					<th align='left'><?php echo $jLang['_ADMIN_CONF_DESCRIPTION']; ?></th>
				</tr>
				</thead>
				<tbody>
			<?php
			$i=0;
			foreach ($items as $key => $val) {
				if (isset($val['versions']) and !empty($val['versions'])) {
					continue;
				} else {
					if (isset($val['type']) and !empty($val['type'])) {
						$label = (isset($val['label']) and !empty($val['label']) and isset($jLang[$val['label']]) and !empty($jLang[$val['label']])) ? $val['label'] : '';
						
						if (isset($val['definition']) and !empty($val['definition']) and isset($jLang[$val['definition']]) and !empty($jLang[$val['definition']])) {
							$definition = $jLang[$val['definition']];
						} else {
							$definition = '&nbsp;';
						}
						?>
					<tr class='row<?php echo $i % 2; ?>'>
						<td><?php
						if (isset($jLang[$label]) and !empty($jLang[$label])) {
							echo $jLang[$label];
						} else {
							echo "&nbsp;";
						}
						?></td>
						<td><?php echo makeConfigItem($key, $val); ?></td>
						<td><?php echo $definition; ?></td>
					</tr>
					<?php
					}
				}
				$i++;
			}
				?>
				</tbody>
				</table>
				<?php
				$tabs->endTab();
		}
		$tabs->endPane();
		?>
		</div>
		</form>
		<?php
	}
}
?>