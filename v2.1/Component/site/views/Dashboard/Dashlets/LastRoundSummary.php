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

$round_winners = array();
if ($jTips['LastWinCurrSeason']) {
	$round_winners[$this->jSeason->id] = $this->jRound->getRoundWinners();
} else {
	$round_winners = $this->jRound->getLastRoundWinners($jTipsCurrentUser);
}
?>
<div class="contentheading"><?php echo $jLang['_COM_LAST_ROUND_SUMMARY']; ?></div>
<table width='100%' class='jdatatable' cellspacing="0">
<thead>
<tr class='sectiontableheader jtableheader'>
<?php
foreach ($jTips['LastRoundSummary'] as $item) {
	?>
	<th><?php echo getSummaryHeader($item); ?></th>
	<?php
}
?>
</tr>
</thead>
<tbody>
<?php
if (count($round_winners) > 0) {
	$rowIndex = 0;
	foreach ($round_winners as $season_id => $j_Users) {
		foreach ($j_Users as $ju) {
			$thisRowIndex = $rowIndex++;
			?>
			<tr class='sectiontableentry<?php echo ($thisRowIndex%2)+1; ?> jtablerow<?php echo ($thisRowIndex%2)+1; ?>'>
			<?php
			foreach ($jTips['LastRoundSummary'] as $col) {
				?>
				<td class='sectiontableentry<?php echo ($thisRowIndex%2)+1; ?>'><?php echo getLastRoundSummaryDetail($ju, $col); ?></td>
				<?php
			}
			?>
			</tr>
			<?php
		}
	}
} else {
	?>
	<tr>
		<td colspan='<?php echo count($jTips['LastRoundSummary']); ?>'><?php echo $jLang['_COM_DASH_NO_ROUNDS']; ?></td>
	</tr>
	<?php
}
?>
</tbody>
</table>