<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $jLang;

switch (jTipsGetParam($_REQUEST, 'task', 'list')) {
	case 'list':
		jTipsMenuBar::startTable();
		jTipsMenuBar::custom('process', 'process', '../components/com_jtips/images/PROCESS.png', 'Process', true);
		jTipsMenuBar::spacer();
		jTipsMenuBar::custom('uncomplete', 'restore', 'restore', 'UnComplete', true);
		jTipsMenuBar::spacer();
		jTipsMenuBar::customX('copy', 'copy.png', 'copy_f2.png', 'Copy');
		jTipsMenuBar::spacer();
		jTipsMenuBar::deleteListX($jLang['_ADMIN_CONFIRM_REMOVE']);
		jTipsMenuBar::spacer();
		jTipsMenuBar::editListX();
		jTipsMenuBar::spacer();
		if (isJoomla15()) {
			JToolbarHelper::addNewX('edit');
		} else {
			jTipsMenuBar::addNewX('edit');
		}
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
	case 'edit':
		jTipsMenuBar::startTable();
		jTipsMenuBar::spacer();
		jTipsMenuBar::save('save');
		jTipsMenuBar::spacer();
		jTipsMenuBar::apply();
		jTipsMenuBar::spacer();
		jTipsMenuBar::cancel('list');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
	case 'copy':
		jTipsMenuBar::startTable();
		jTipsMenuBar::spacer();
		jTipsMenuBar::save('docopy');
		jTipsMenuBar::spacer();
		jTipsMenuBar::cancel('list');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
}
?>