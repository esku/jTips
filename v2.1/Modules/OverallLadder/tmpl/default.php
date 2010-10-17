<?php
defined('_JEXEC') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 1.0 - 30/04/2009
 * @version 1.0.0
 * @package jTips
 *
 * Description: Display the table of users
 */


?>
<table width="100%" border="0" cellspacing="0">
	<thead>
		<tr class="sectiontableheader">
			<th class="sectiontableheader">#</th>
			<?php if ($params->get('avatar')) : ?>
			<th class="sectiontableheader">&nbsp;</th>
			<?php endif; ?>
			<th class="sectiontableheader"><?php echo JText::_('User'); ?></th>
			<th class="sectiontableheader" align="center"><?php echo JText::_(ucwords($params->get('field'))); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (!empty($users)) {
		$index = 0;
		foreach ($users as $user) {
			$class = "sectiontableentry".($index++%2+1);
			?>
		<tr class="<?php echo $class; ?>">
			<td class="<?php echo $class; ?>"><?php echo $index; ?>.</td>
			<?php if ($params->get('avatar')) : ?>
			<td class="<?php echo $class; ?>"><?php echo modJTipsOverallLadderModule::getProfileImage($user->user_id, $params->get('link')); ?></td>
			<?php endif; ?>
			<td class="<?php echo $class; ?>"><?php echo modJTipsOverallLadderModule::getName($user, $params); ?></td>
			<td class="<?php echo $class; ?>" align="center"><?php echo $user->score; ?></td>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>