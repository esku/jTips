<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );

//require_once( $mainframe->getPath( 'toolbar_html' ));

$include_file = "components/com_jtips/modules/" .jTipsGetParam($_REQUEST, 'module', ''). "/toolbar.php";
jTipsLogger::_log("looking for toolbar file: $include_file", 'INFO');
if (jTipsFileExists($include_file)) {
	jTipsLogger::_log("found $include_file... running script");
	include($include_file);
} else {
	jTipsLogger::_log('failed to find toolbar file!', 'ERROR');
}
?>
