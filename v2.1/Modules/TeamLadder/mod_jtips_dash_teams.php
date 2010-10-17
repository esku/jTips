<?php
/**
* @version $Id: mod_jtips_dash_teams.php,v 1.2 2008/11/27 12:36:10 jtipsco1 Exp $
* @package jTips 2.0
* @copyright (C) 2008 jTips
* @license Commercial - Do not modify, or redistribute
* jTips 2.0 - your ultimate tipping system... www.jtips.com.au
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $database, $my;

$season_name		= $params->get('season', '');
$showlink			= $params->get('showlink', 0);
$load_mootools		= $params->get('load_mootools', 0);
$team_field			= $params->get('team_field', 'points');
$incimage			= $params->get('incimage', 0);
$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';
$mosConfig_lang=$mainframe->getCfg( 'lang' );
include($include_path. 'i18n/english.php');
if (file_exists($include_path. 'i18n/'.$mosConfig_lang.'.php')) {
	include($include_path. 'i18n/'.$mosConfig_lang.'.php');
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

//Include required files
require_once($include_path. 'utils/functions.inc.php');
require_once($include_path. 'classes/jseason.class.php');
require_once($include_path. 'classes/juser.class.php');
require_once($include_path. 'classes/jteam.class.php');
require_once($include_path. 'classes/jgame.class.php');
include($include_path. 'config.jtips.php');

/*
 * quick check to make the xml file the right one so it appears
 * properly in the list of modules
 */
if (isJoomla15() and jTipsFileExists(dirname(__FILE__). '/installer.xml')) {
	jimport('joomla.filesystem.file');
	$data = JFile::read(dirname(__FILE__). '/installer.xml');
	JFile::write(dirname(__FILE__). '/mod_jtips_dash_teams.xml', $data);
	JFile::delete(dirname(__FILE__). '/installer.xml');
}

//Get the season
$jSeason = new jSeason($database);
$jSeason->load(getSeasonID());
if (isset($jSeason->id) and !empty($jSeason->id)) {
	//parse info here
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
		<tr class="sectiontableheader jtableheader">
			<th>#</th>
			<th><?php echo $jLang['_COM_TLD_TEAM']; ?></th>
	<?php
	if ($team_field != '-1') {
		?>
		<th><?php echo $fieldOptions[$team_field]; ?></th>
		<?php
	}
	$teamLadder = $jSeason->getTeamLadder($team_field);
	$i = 1;
	$rowIndex = 0;
	?>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($teamLadder as $jTeam) {
		?>
		<tr class="sectiontableentry<?php echo ($rowIndex++%2)+1; ?>">
			<td align="right"><?php echo $i; ?>.</td>
			<td align="left">
			<?php
//			if ($incimage and isset($jTeam->logo) and !empty($jTeam->logo)) {
//				if (jTipsFileExists(getJtipsImage($jTeam->logo))) {
//					$img = "<img src='" .$mainframe->getCfg('live_site');
//					$img .= "/" .getJtipsImage($jTeam->logo). "' alt=' ' border='0' align='absmiddle' />&nbsp;";
//					echo $img;
//				}
//			}
			if ($params->get('incimage') and !empty($jTeam->logo) and jTipsFileExists(getJtipsImage($jTeam->logo))) {
				echo $jTeam->getLogo(). "&nbsp;";
			}
			global $Itemid;
			$popupUrl = "view=TeamLadder&action=ShowTeam&id=" .$jTeam->id. "&Itemid=" .$Itemid;
			if (isJoomla15()) {
				$x = $jTips['ShowTipsWidth'];
				$y = $jTips['ShowTipsHeight'];
				echo "<a class='modal' rel=\"{handler: 'iframe', size: {x: $x, y: $y}}\" href='" .jTipsRoute($mosConfig_live_site. "/index2.php?option=com_jtips&$popupUrl&menu=0"). "'>" .$jTeam->getName(). "</a>";
			} else {
				echo "<a href='javascript:void(0);' onClick='openPopup(\"$popupUrl\", \"" .$jTeam->getName(). "\");'>" .$jTeam->getName(). "</a>";
			}
			?></td>
			<?php
			if ($team_field != '-1') {
				?>
				<td style="text-align:center;"><?php echo $jTeam->getTeamField($team_field); ?></td>
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