<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 01/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Builds the toolbar for the Users module
 */

global $jLang;

switch (jTipsGetParam($_REQUEST, 'task', 'list')) {
	case 'list':
		jTipsMenuBar::startTable();
		jTipsMenuBar::custom('process', 'process', '../components/com_jtips/images/PROCESS.png', 'Process', true);
		jTipsMenuBar::spacer();
		jTipsMenuBar::deleteListX($jLang['_ADMIN_CONFIRM_REMOVE']);
		jTipsMenuBar::spacer();
		jTipsMenuBar::editListX('edit');
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
		jTipsMenuBar::cancel('list');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
}
?>