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
	default:
		jTipsMenuBar::startTable();
		jTipsMenuBar::save('save');
		jTipsMenuBar::spacer();
		jTipsMenuBar::apply();
		jTipsMenuBar::spacer();
		jTipsMenuBar::cancel('cancel');
		jTipsMenuBar::spacer();
		jTipsMenuBar::endTable();
		break;
}
?>