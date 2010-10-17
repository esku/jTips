<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 * 
 * Description: 
 * 
 * 
 */
global $database, $jTips, $jLang, $mosConfig_live_site, $jTipsCurrentUser,$mosConfig_absolute_path;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/utils/functions.inc.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jhistory.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jround.class.php');
require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');

if ($params->get('load_mootools')) {
	$mainframe->addCustomHeadTag("<script type='text/javascript' src='" .$mosConfig_live_site. "/components/com_jtips/js/mootools.js'></script>");
}

/*
 * quick check to make the xml file the right one so it appears
 * properly in the list of modules
 */
if (isJoomla15() and jTipsFileExists(dirname(__FILE__). '/installer.xml')) {
	jimport('joomla.filesystem.file');
	$data = JFile::read(dirname(__FILE__). '/installer.xml');
	JFile::write(dirname(__FILE__). '/mod_jtips_dash_comp_ladder.xml', $data);
	JFile::delete(dirname(__FILE__). '/installer.xml');
}

$jSeason = new jSeason($database);
$season_id = getSeasonID();
if (is_numeric($season_id)) {
	$jSeason->load($season_id);
} else {
	$date = gmdate('Y-m-d');
	//Bug 33.4 - Extended params array to default to season for current user
	$objParams = array(
		'end_time' => array(
			'type' => 'query',
			'query' => "> '$date'"
		),
		'left_join' => array(
			'type' => 'left_join',
			'join_table' => '#__jtips_users',
			'lhs_table' => '#__jtips_seasons',
			'lhs_key' => 'id',
			'rhs_table' => '#__jtips_users',
			'rhs_key' => 'season_id'
		),
		/*'order' => array(
			'order' => 'name',
		)*/
	);
	if (isset($jTipsUser->user_id) and !empty($jTipsUser->user_id)) {
		$objParams['#__jtips_users.user_id'] = $jTipsUser->user_id;
	}
	$jSeasons = forceArray($jSeason->loadByParams($objParams));
	if (count($jSeasons) > 0) {
		$jSeason =& array_shift($jSeasons);
	}
}

$jRound = new jRound($database);
$round_id = $jSeason->getCurrentRound();
$jRound->load($round_id);

if ($jRound->scored == 0) {
	$prev_round_id = $jSeason->getLastRound();
	$prev_round = new jRound($database);
	$prev_round->load($prev_round_id);
	$jRound =& $prev_round;
	$load_round = $prev_round_id;
} else {
	$load_round = $jRound->id;
}
$jHistory = new jHistory($database);
$jTipsUsers = $jHistory->getLadder($jTips['NumDefault'], $load_round);
?>
<table width='100%' cellspacing="0">
	<thead>
		<tr class='sectiontableheader jtableheader'>
		<th>#</th>
		<th><?php echo $jLang['_COM_TIP_LADDER_USER']; ?></th>
		<th><?php echo $jLang['_COM_TIP_LADDER_LAST']; ?></th>
		<th><?php echo $jLang['_COM_TIP_LADDER_SCORE']; ?></th>
		<?php
		if ($jTips['EnableComments'] == 1) {
			?>
			<th><img src='<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/comment.png' alt='<?php echo $jLang['_COM_DASH_COMMENT']; ?>' align='absmiddle' /></th>
			<?php
		}
	?>
	</tr>
	</thead>
	<tbody>
	<?php
$i = 0;

$userAdded = array();
$jHistory = new jHistory($database);
$rowIndex = 0;
foreach ($jTipsUsers as $ju) {
	if (!$ju->id) {
		continue;
	}
	$name = getUserLadderField($ju, $jRound, 'user');
	$score = $ju->getTotalScore('points');
	$last = $ju->getRoundScore('points', $jRound->id);
	$index = ++$i;
	$comment = getUserLadderField($ju, $jRound, 'comment');
	$userAdded[] = $ju->user_id;
	if ($ju->user_id == $jTipsCurrentUser->user_id) {
		$index = "<strong>$index</strong>";
		$name = "<strong>$name</strong>";
		$score = "<strong>$score</strong>";
		$last = "<strong>$last</strong>";
	}
	$thisRowIndex = $rowIndex++;
	?>
	<tr class="sectiontableentry<?php echo ($thisRowIndex%2)+1; ?> jtablerow<?php echo ($thisRowIndex%2)+1; ?>">
		<td><?php echo $index; ?>.</td>
		<td style='text-align:left'><?php echo $name; ?></td>
		<td style='text-align:center'><?php echo $last; ?></td>
		<td style='text-align:center'><?php echo $score; ?></td>
		<?php
		if ($jTips['EnableComments'] == 1) {
			?>
			<td style='text-align:center'>
            <?php
            if ($comment) {
                ?>
                <img src='<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/comment.png' alt='<?php echo $jLang['_COM_DASH_COMMENT']; ?>' align='absmiddle' style='cursor:pointer;' id="comToggler<?php echo $i; ?>" />
                <?php
            } else {
                echo '&nbsp;';
            }
            ?>
            </td>
			<?php
		}
		?>
	</tr>
	<?php
	if ($jTips['EnableComments'] == 1 and $comment) {
		?>
		<tr>
		<td colspan="5">
			<div id="comment<?php echo $i; ?>">
				<?php echo $comment; ?>
			</div>
			<script type="text/javascript">
			window.addEvent('domready', function() {
				var myComment<?php echo $i; ?> = new Fx.Slide('comment<?php echo $i; ?>', {
					duration:<?php echo $jTips['JsLadderDuration']/2 * 1000; ?>,
					wait: true,
					transition: Fx.Transitions.<?php echo $jTips['JsLadder'];
					if ($jTips['JsLadder'] != 'linear') {
						echo "." .$jTips['JsLadderStyle'];
					}
					?>
				});
				myComment<?php echo $i; ?>.hide();
				$('comToggler<?php echo $i; ?>').addEvent('click', function(e){
					e = new Event(e);
					myComment<?php echo $i; ?>.toggle();
					e.stop();
				});
			});
			</script>
		</td>
		</tr>
		<?php
	}
}
if (empty($jTipsUsers)) {
	?>
	<tr>
		<td colspan="5" style="text-align:center"><?php echo $jLang['_COM_ONE_ROUND_REQUIRED']; ?></td>
	</tr>
	<?php
}
?>
</tbody>
</table>