<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 09/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Get a list of available import fields for the selected table
 */

global $database, $jLang;

ob_clean();

$table = jTipsGetParam($_REQUEST, 'table', '');

if ($table == '-1' or $table == '') {
	die("opts = new Array();");
}
$jObj = new $table($database);
$defs =& $jObj->getFieldMap();
$i = 1;
ksort($defs);
$js = "
var opts = new Array();

";
foreach ($defs as $field => $def) {
	if (isset($def['import']) and $def['import'] == true) {
		$label = $jLang[$def['label']];
		$js .= "
		opts.push(new Option('$label', '$field'));
		";			
		$i++;
	}
}
die($js);
?>