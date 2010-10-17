<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
function com_install() {
	global $database, $mosConfig_absolute_path;
	# Set up new icons for admin menu
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_jtips/images/MINILOGO.png' WHERE admin_menu_link='option=com_jtips'");
	$iconresult[0] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_jtips/images/MENUSEASON.png' WHERE admin_menu_link='option=com_jtips&task=seasons'");
	$iconresult[1] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_jtips/images/MENUROUND.png' WHERE admin_menu_link='option=com_jtips&task=rounds'");
	$iconresult[2] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_jtips/images/MENUUSER.png' WHERE admin_menu_link='option=com_jtips&task=users'");
	$iconresult[3] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_jtips/images/MENUTEAM.png' WHERE admin_menu_link='option=com_jtips&task=teams'");
	$iconresult[4] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_jtips/images/MENUSETTINGS.png' WHERE admin_menu_link='option=com_jtips&task=config'");
	$iconresult[5] = $database->query();
}
?>
