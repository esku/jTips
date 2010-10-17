<?php
/**
* @version $Id: mod_jtips_best_tipper.php,v 1.2 2009/02/12 08:50:20 jtipsco1 Exp $
* @package jTips 2.1
* @copyright (C) 2008 jTips
* @license Commercial - Do not modify, or redistribute
* jTips 2.1 - your ultimate tipping system... www.jtips.com.au
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path, $mainframe, $database, $my, $jTips;
$season_name		= $params->get('season', '');


if (defined('_JEXEC')) {
	$mosConfig_absolute_path = JPATH_SITE;
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';
require_once($include_path. 'utils/compat.php');

$mosConfig_lang=$mainframe->getCfg( 'lang' );
include($include_path. 'i18n/english.php');
if (file_exists($include_path. 'i18n/'.$mosConfig_lang.'.php')) {
	include($include_path. 'i18n/'.$mosConfig_lang.'.php');
}

$include_path = $mosConfig_absolute_path. '/administrator/components/com_jtips/';

//Include required files
require_once($include_path. 'utils/timedate.php');
require_once($include_path. 'utils/functions.inc.php');
require_once($include_path. 'classes/jcomment.class.php');
require_once($include_path. 'classes/jhistory.class.php');
require_once($include_path. 'classes/jround.class.php');
require_once($include_path. 'classes/jseason.class.php');
require_once($include_path. 'classes/juser.class.php');
include($include_path. 'config.jtips.php');

//Get the season
$jtips_params = array(
	'name' => trim($season_name)
);
$jSeason = new jSeason($database);
$jSeason->loadByParams($jtips_params);
if (isset($jSeason->id) and !empty($jSeason->id)) {
	//parse the info here
	//get the last round
	$jRound = new jRound($database);
	$round_id = $jSeason->getLastRound();
	$jRound->load($round_id);
	//Get the list of rounds
	$query = "SELECT id FROM #__jtips_rounds WHERE season_id = " .$database->Quote($jSeason->id). " AND scored = 1";
	$database->setQuery($query);
	$rids = $database->loadResultArray();
	//find the user
	$query = "SELECT user_id, AVG(points) pointsa, AVG(`precision`) preca FROM #__jtips_history " .
			"WHERE round_id IN ('" .implode("', '", $rids). "') GROUP BY user_id ";
	if ($params->get('type') == 'worst') {
		$query .= " ORDER BY pointsa ASC, preca DESC, RAND()";
	} else {
		$query .= " ORDER BY pointsa DESC, preca ASC, RAND()";
	}
	//jTipsDebug($query);
	$database->setQuery($query, 0, 1);
	$result = $database->loadAssocList();
	if (!empty($result) and count($result)) {
		$row = array_shift($result);
		$jTipsUser = new jTipsUser($database);
		//jTipsDebug($row);
		$jTipsUser->load($row['user_id']);
		//get the user avator if enabled
		$name = $jTipsUser->getName();
		if($params->get('socimage')) {
			if ($params->get('socimage') == 'cb') {
				// Link is to CB Pofile
				$img = getCommunityBuilderAvatar($jTipsUser->user_id);
				$link = "index.php?option=com_comprofiler&task=userProfile&user=" .$jTipsUser->user_id;
			} else if ($params->get('socimage') == 'js') {
				$img = getJomSocialAvatar($jTipsUser->user_id);
				$link = getJomSocialProfileLink($jTipsUser->user_id);
			}
			$alt = $name;
			if(!empty($img)) {
				
				?>
				<div style='text-align:center;'>
				<?php
				$imgHTML = "<img src='$img' border='0' alt='Profile Avatar for $alt' />";
				echo "<a href='" . jTipsRoute($link) . "' title='View Profile' id='userModLadderLink_" . $jTipsUser->id . "'>" . $imgHTML . "</a>";
				?>
				</div>
				<?php
			}
		}
		
		//build the array for the rows
		$tableData = array(
			'rank' => $params->get('rank'),
			'pointst' => $params->get('pointst'),
			'points' => $params->get('points'),
			'pointsa' => $params->get('pointsa'),
			'prect' => $params->get('prect'),
			'prec' => $params->get('prec'),
			'preca' => $params->get('preca'),
			'comment' => $params->get('comment')
		);
		?>
		<table width="100%" cellspacing="0">
			<thead>
			<tr class="sectiontableheader">
				<th class="sectiontableheader" colspan="2">
					<h4><?php echo $jTipsUser->getName(); ?></h4>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$rowIndex = 0;
			$i = 1;
			foreach ($tableData as $stat => $enabled) {
				if (!$enabled) continue;
				$rowIndex++;
				//Do we have some data to show
				if (array_key_exists($stat, $row)) {
					$data = round($row[$stat], 1);
				} else {
					$data = getUserLadderField($jTipsUser, $jRound, $stat);
				}
				if ((empty($data) and $data != '0') or $data == "&nbsp;") continue;
				?>
				<tr class="sectiontableentry<?php echo ($i%2)+1; ?>">
				<?php
				if ($stat != 'comment') {
					?>
					<td class="sectiontableentry<?php echo ($i%2)+1; ?>" style='font-weight:bold;'><?php echo $jLang['_COM_DASH_' .str_replace(' ', '_', strtoupper($stat))]; ?></td>
					<?php
					$colspan = 1;
				} else {
					$colspan = 2;
				}
				?>
				<td class="sectiontableentry<?php echo ($i%2)+1; ?>" colspan="<?php echo $colspan; ?>" style="text-align:center;"><?php echo $data; ?></td>
				</tr>
				<?php
				$i++;
			}
			?>
			</tbody>
		</table>
		<?php
	} else {
		?>
		<div class="message"><?php echo $jLang['_MOD_NO_DATA']; ?></div>
		<?php
	}
} else {
	//display error message
	?>
	<div class="message"><?php echo $jLang['_MOD_INVALID_SEASON_ERROR']; ?></div>
	<?php
}
?>