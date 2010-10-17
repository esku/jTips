<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1.10 - 20/05/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: Marks selected rounds as uncomplete
 * which then allows editing fully a round.
 * 
 * // BUG 351 - allow uncompleting a round
 */

jTipsSpoofCheck();

global $mosConfig_absolute_path, $database, $jLang;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');

$cid = jTipsGetParam($_REQUEST, 'cid', array());

if ($cid === false or (is_array($cid) and count($cid) == 0)) {
        //should never get here
        mosRedirect('index2.php?option=com_jtips&task=list&module=Rounds', $jLang['_ADMIN_ROUNDS_NONE_TO_PROCESS']);
}

jTipsLogger::_log('uncompleting rounds');

foreach ($cid as $id) {
        $jRound = new jRound($database);
        $jRound->load($id);
        $jRound->scored = 0;
        $jRound->save();
}
$msg = count($cid) . ' ' .$jLang['_ADMIN_ROUNDS_UNCOMPLETED'];
mosRedirect('index2.php?option=com_jtips&module=Rounds&task=list', $msg);