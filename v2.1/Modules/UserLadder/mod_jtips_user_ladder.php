<?php
/**
* @version $Id: mod_jtips_user_ladder.php,v 1.5 2009/04/13 06:44:12 jtipsco1 Exp $
* @package jTips 2.1
* @copyright (C) 2008 jTips
* @license Commercial - Do not modify, or redistribute
* jTips - your ultimate tipping system... www.jtips.com.au
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $database, $my, $jTips, $jLang;
$season_name		= $params->get('season', '');
$showlink			= $params->get('showlink', 0);
$load_mootools		= $params->get('load_mootools', 0);
$user_field			= $params->get('user_field', 'pointst');
$incimage			= $params->get('incimage', 0);
$numtoshow			= $params->get('numtoshow', 10);

require_once('administrator/components/com_jtips/utils/compat.php');
require_once('administrator/components/com_jtips/utils/functions.inc.php');

loadLanguage();

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

//Include required files
require_once($include_path. 'utils/compat.php');
require_once($include_path. 'utils/timedate.php');
require_once($include_path. 'utils/functions.inc.php');
require_once($include_path. 'classes/jseason.class.php');
require_once($include_path. 'classes/juser.class.php');
require_once($include_path. 'classes/jteam.class.php');
require_once($include_path. 'classes/jround.class.php');
require_once($include_path. 'classes/jhistory.class.php');
require_once($include_path. 'classes/jcomment.class.php');
include($include_path. 'config.jtips.php');


//Get the season
$jtips_params = array(
	'name' => trim($season_name)
);
$jSeason = new jSeason($database);
$jSeason->loadByParams($jtips_params);
if (isset($jSeason->id) and !empty($jSeason->id)) {
	//parse the info here
	if (!isJoomla15()) {
		if ($load_mootools) {
			?>
			<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/mootools.js"></script>
			<?php
		}
		?>
		<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/moodalbox.js"></script>
		<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/Popup.js"></script>
		<style type="text/css">@import url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/moodalbox.css);</style>
		<?php
	} else {
		JHTML::_('behavior.modal');
	}
	?>
	<script type="text/javascript">
	var jTipsShowTipsWidth = <?php echo $jTips['ShowTipsWidth']; ?>;
	var jTipsShowTipsHeight = <?php echo $jTips['ShowTipsHeight']; ?>;
	var jTipsSitePath = "<?php echo $mosConfig_live_site; ?>/";
	</script>
	<style type="text/css">@import url(<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/jtips-default.css);</style>
	<?php
	$fieldOptions = array(
		'-1' => '--None--',
		'points' => $jLang['_MOD_LAST_ROUND_POINTS'],
		'pointst' => $jLang['_MOD_TOTAL_POINTS'],
		'prec' => $jLang['_MOD_LAST_ROUND_PRECISION'],
		'prect' => $jLang['_MOD_PRECISION_SCORE'],
		'comment' => $jLang['_MOD_LAST_ROUND_COMMENT'],
		'moved' => $jLang['_MOD_LADDER_MOVEMENT']
	);
	?>
	<table width="100%" border="0" id="mod_jtips_team_table" cellspacing="0">
		<thead>
		<tr class="sectiontableheader">
			<th class="sectiontableheader">#</th>
			<th class="sectiontableheader"><?php echo $jLang['_COM_TIP_LADDER_USER']; ?></th>
	<?php
	if ($user_field != '-1') {
		?>
		<th class="sectiontableheader" style="text-align:center"><?php echo $fieldOptions[$user_field]; ?></th>
		<?php
	}
	?>
		</tr>
		</thead>
		<tbody>
	<?php
	$round_id = $jSeason->getLastRound();
	$jRound = new jRound($database);
	$jRound->load($round_id);
	$jHistory = new jHistory($database);
	$orderField = ($user_field != 'moved' and $user_field != '-1') ? $user_field : 'pointst';
	if ($orderField == 'points' or $orderField == 'comment') {
	    $order = 'desc';
	} else if ($orderField == 'rank' or $orderField == 'pointst') {
	    $order = 'asc';
	} else {
	    $order = 'default';
	}
	$userLadder = $jHistory->getLadder($numtoshow, $round_id, 0, $orderField, $order);
	$i = 1;
	foreach ($userLadder as $jUser) {
		?>
		<tr class="sectiontableentry<?php echo ($i%2)+1; ?>">
			<td class="sectiontableentry<?php echo ($i%2)+1; ?>" align="right"><?php echo $i; ?>.</td>
			<td class="sectiontableentry<?php echo ($i%2)+1; ?>" >
			<?php echo getUserLadderField($jUser, $jRound, 'user');	?></td>
			<?php
			if ($user_field != '-1') {
				?>
				<td class="sectiontableentry<?php echo ($i%2)+1; ?>" style="text-align:center">
					<?php echo getUserLadderField($jUser, $jRound, $user_field); ?>
				</td>
				<?php
			}
			?>
		</tr>
		<?php
		$i++;
	}
	?>
	</tbody>
	</table>
	<?php
} else {
	//display error message
	?>
	<div class="message"><?php echo $jLang['_MOD_INVALID_SEASON_ERROR']; ?></div>
	<?php
}
?>