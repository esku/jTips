<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mosConfig_absolute_path;

switch (jTipsGetParam($_REQUEST, 'task', 'list')) {
	case 'edit':
		jTipsMenuBar::startTable();
		jTipsMenuBar::spacer();
		jTipsMenuBar::save('save');
		jTipsMenuBar::spacer();
		jTipsMenuBar::apply();
		jTipsMenuBar::spacer();
		jTipsMenuBar::cancel('cancel');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
	default:
		jTipsMenuBar::startTable();
		jTipsMenuBar::spacer();
		jTipsMenuBar::editListX('edit');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
}
?>
