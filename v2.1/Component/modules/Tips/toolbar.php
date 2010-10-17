<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );

switch (jTipsGetParam($_REQUEST, 'task', 'list')) {
	case 'list':
		jTipsMenuBar::startTable();
		jTipsMenuBar::editListX('edit');
		jTipsMenuBar::spacer();
		jTipsMenuBar::addNewX('edit');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
	case 'edit':
		jTipsMenuBar::startTable();
		jTipsMenuBar::spacer();
		jTipsMenuBar::save('save');
		jTipsMenuBar::spacer();
		jTipsMenuBar::cancel('list');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
}
?>