<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 16/10/2008
 * @version 2.1.1
 * @package jTips
 * 
 * Description: Performs the background upgrade.
 */


require_once('components/com_jtips/utils/update.php');

$result = updatejTipsFiles();

if (is_bool($result)) {
	//BUG 233 - have json encode the boolean/integer result
	die(json_encode(intval($result)));
} else {
	die($result);
}

?>