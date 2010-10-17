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
	case 'list':
		jTipsMenuBar::startTable();
		jTipsMenuBar::custom('upload', 'upload', '', 'Upload', false);
		jTipsMenuBar::spacer();
		jTipsMenuBar::custom('remove', 'delete', '', 'Delete File', false);
		jTipsMenuBar::spacer();
		jTipsMenuBar::custom('process', 'process', '../components/com_jtips/images/PROCESS.png', 'Process', false);
		jTipsMenuBar::spacer();
		jTipsMenuBar::cancel('dashboard');
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