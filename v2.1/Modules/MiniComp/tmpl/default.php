<?php
defined('_JEXEC') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 1.0 - 13/07/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: 
 */

?>
<div style="text-align:center;">
<?php
// do we need the user's image?
if ($params->get('avatar', 0) and $params->get('link', '')) {
	$img = modJTipsMiniCompHelper::getAvatar($who['who'], $params);
	if ($img) {
		echo JHTML::image($img, $who['who']->getUserField($params->get('display', 'username')));
	}
}
?>
<br />
<?php
if ($params->get('link', '') == 'JomSocial') {
	echo JHTML::link(getJomSocialProfileLink($who['who']->user_id), $who['who']->getUserField($params->get('display', 'username')));
} else if ($params->get('link', '') == 'CommunityBuilder') {
	echo JHTML::link(JRoute::_("index.php?option=com_comprofiler&task=userProfile&user=" . $who['who']->user_id), $who['who']->getUserField($params->get('display', 'username')));
} else {
	echo $who['who']->getUserField($params->get('display', 'username'));
}
?>
</div>
<div class="contentheading" style="text-align:center; padding:5px 0 5px 0;"><?php echo $who['points']; ?></div>
<?php if ($params->get('show_detail', 1)) : ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<thead>
	<tr class="sectiontableheader">
		<th class="sectiontableheader"><?php echo JText::_('Round'); ?></th>
		<th class="sectiontableheader"><?php echo JText::_('Round'); ?></th>
		<th class="sectiontableheader"><?php echo JText::_('Rank'); ?></th>
	</tr>
	</thead>
	<tbody>
<?php
for ($i=0; $i<count($who['results']); $i++) {
	$mod = ($i % 2) + 1;
	$class = "sectiontableentry" .$mod;
	$data = $who['results'][$i];
	?>
	<tr class="<?php echo $class; ?>">
		<td class="<?php echo $class; ?>" style='text-align:center;'><?php echo $data['round']; ?></td>
		<td class="<?php echo $class; ?>" style='text-align:center;'><?php echo $data['points']; ?></td>
		<td class="<?php echo $class; ?>" style='text-align:center;'><?php echo $data['rank']; ?></td>
	</tr>
	<?php
}
?>
	</tbody>
</table>
<?php endif; ?>
