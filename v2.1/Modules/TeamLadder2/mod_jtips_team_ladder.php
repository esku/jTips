<?php
/**
* @version $Id: mod_jtips_team_ladder.php,v 1.3 2009/02/16 11:21:22 jtipsco1 Exp $
* @package jTips 2.1
* @copyright (C) 2009 jTips
* @license Commercial - Do not modify, or redistribute
* jTips - your ultimate tipping system... www.jtips.com.au
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $database, $my, $jLang, $jTips;

$season_name		= $params->get('season', '');
$showlink			= $params->get('showlink', 0);
$load_mootools		= $params->get('load_mootools', 0);
$team_field			= $params->get('team_field', 'points');
$incimage			= $params->get('incimage', 0);


if (defined('_JEXEC')) {
	$mosConfig_absolute_path = JPATH_SITE;
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

require_once($include_path. 'utils/compat.php');

//$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

//Include required files
require_once($include_path. 'utils/timedate.php');
require_once($include_path. 'utils/functions.inc.php');
require_once($include_path. 'classes/jseason.class.php');
require_once($include_path. 'classes/juser.class.php');
require_once($include_path. 'classes/jteam.class.php');
require_once($include_path. 'classes/jgame.class.php');
loadLanguage();
include($include_path. 'config.jtips.php');

//Get the season
$jtips_params = array(
	'name' => trim($season_name)
);
$jSeason = new jSeason($database);
$jSeason->loadByParams($jtips_params);
if (isset($jSeason->id) and !empty($jSeason->id)) {
	//parse info here
	if (!isJoomla15()) {
		if ($load_mootools) {
			?>
			<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/mootools.js"></script>
			<?php
		}
		?>
		<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/moodalbox.js"></script>
		<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_jtips/js/Popup.js"></script>
		<link rel='stylesheet' href='<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/moodalbox.css' type='text/css' media='screen' />
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
	<link rel='stylesheet' href='<?php echo $mosConfig_live_site; ?>/components/com_jtips/css/jtips-default.css' type='text/css' media='screen' />
	<?php
	$fieldOptions = array(
		'-1' => '--None--',
		'points' => 'Points',
		'wins' => 'Wins',
		'played' => 'Played',
		'points_for' => 'GF',
		'points_against' => 'GA'
	);
	?>
	<table width="100%" border="0" id="mod_jtips_team_table" cellspacing="0">
		<thead>
		<tr class="sectiontableheader">
			<th class="sectiontableheader">#</th>
			<th class="sectiontableheader"><?php echo $jLang['_COM_TLD_TEAM']; ?></th>
	<?php
	if ($team_field != '-1') {
		?>
		<th class="sectiontableheader" style="text-align:center;"><?php echo $fieldOptions[$team_field]; ?></th>
		<?php
	}
	?>
		</tr>
		</thead>
		<tbody>
	<?php
	$teamLadder = $jSeason->getTeamLadder($team_field);
	$i = 1;
	$rowIndex = 0;
	foreach ($teamLadder as $jTeam) {
		?>
		<tr class="sectiontableentry<?php echo ($i%2)+1; ?>">
			<td class="sectiontableentry<?php echo ($i%2)+1; ?>" align="right"><?php echo $i; ?>.</td>
			<td class="sectiontableentry<?php echo ($i%2)+1; ?>" align="left">
			<?php
			if ($incimage) {
				$img = $jTeam->getLogo();
				if (!empty($img)) {
					echo $img. "&nbsp;";
				}
			}
			$popupUrl = "view=TeamLadder&action=ShowTeam&menu=0&id=" .$jTeam->id. "&season_id=" .$jTeam->season_id;
			if (isJoomla15()) {
				$x = $jTips['ShowTipsWidth'];
				$y = $jTips['ShowTipsHeight'];
				//BUG 274 - team ladder popup results in broken scroll bar
				if (basename($_SERVER['SCRIPT_FILENAME']) != 'index2.php') {
					$class = "class='modal'";
				} else {
					$class = '';
				}
				$teamname = "<a $class rel=\"{handler: 'iframe', size: {x: $x, y: $y}}\" href='" .jTipsRoute($mosConfig_live_site. "/index2.php?option=com_jtips&$popupUrl"). "'>" .$jTeam->getName(). "</a>";
			} else {
				$teamname = "<a href='javascript:void(0);' onClick='openPopup(\"$popupUrl\", \"" .$jTeam->getName(). "\");'>" .$jTeam->getName(). "</a>";
			}
			echo $teamname;
			?></td>
			<?php
			if ($team_field != '-1') {
				?>
				<td class="sectiontableentry<?php echo ($i%2)+1; ?>" style="align:center;"><?php echo $jTeam->getTeamField($team_field); ?></td>
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
	//error message
	?>
	<div class="message"><?php echo $jLang['_MOD_INVALID_SEASON_ERROR']; ?></div>
	<?php
}
?>