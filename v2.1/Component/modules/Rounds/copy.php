<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1.9 - 06/04/2009
 * @version 1.0.0
 * @package jTips
 * 
 * Description: 
 */

require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/modules/Rounds/copy.tmpl.php');

global $database, $jTips, $jLang;

$tpl = new CopyMode();

$tpl->formData['title'] = $jLang['_ADMIN_ROUND_COPY'];

// load the round id being copied
$focus = new jRound($database);

$ids = jTipsGetParam($_REQUEST, 'cid', array());
if (!empty($ids)) {
        $id = array_shift($ids);
        $focus->load($id);
} else {
        mosRedirect('index2.php?option=com_jtips&module=Rounds&task=list', $jLang['_ADMIN_ROUND_COPY_NOROUND']);
}
$tpl->round = $focus->round;
$tpl->round_id = $focus->id;

// load the season info
$jSeason = new jSeason($database);
$jSeason->load($focus->season_id);
$tpl->season_name = $jSeason->name;
$tpl->display();