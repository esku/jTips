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


?>
<div class="contentheading"><?php echo ($this->jSeason->exists() ? $this->jSeason->name : $jLang['_COM_DASH_NOSEASONS']); ?></div>
<?php
if (!empty($this->jSeason->description)) {
	echo nl2br(jTipsStripslashes($this->jSeason->description));
}
if ($this->jSeason->exists()) {
	?>
	<table border='0' align='center' width="100%" class='jdatatable' cellspacing="0">
	<thead>
	<tr class='sectiontableheader jtableheader'>
		<th><?php echo $jLang['_COM_DASH_CURR_ROUND']; ?></th>
		<th><?php echo $jLang['_COM_DASH_TOT_ROUND']; ?></th>
		<!-- th><?php echo $jLang['_COM_DASH_GPER_ROUND']; ?></th -->
		<th><?php echo $jLang['_COM_DASH_START']; ?></th>
		<th><?php echo $jLang['_COM_DASH_END']; ?></th>
	</tr>
	</thead>
	<tbody>
	<tr class='sectiontableentry1 jtablerow1'>
		<td><?php echo $this->jRound->round; ?></td>
		<td><?php echo $this->jSeason->rounds; ?></td>
		<!-- td><?php echo $this->jSeason->games_per_round; ?></td -->
		<td><?php echo TimeDate::toDisplayDate($this->jSeason->start_time, true, false); ?></td>
		<td><?php echo TimeDate::toDisplayDate($this->jSeason->end_time, true, false); ?></td>
	</tr>
	</tbody>
	</table>
	<?php
}
?>