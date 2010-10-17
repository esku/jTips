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
global $jTips, $jLang, $jTipsCurrentUser;
?>
<div class="contentheading"><?php echo $jLang['_COM_DASH_SUMMARY']; ?></div>
<table width='100%' class='jdatatable' cellspacing="0">
<thead>
<tr class='sectiontableheader jtableheader'>
<?php
//Get columns and ordering
foreach ($jTips['ScoreSummary'] as $item) {
	?>
	<th><?php echo getSummaryHeader($item); ?></th>
	<?php
}
?>
</tr>
</thead>
<tbody>
<tr class='sectiontableentry1 jtablerow1'>
<?php
foreach ($jTips['ScoreSummary'] as $col) {
	?>
	<td><?php echo getSummaryDetail($jTipsCurrentUser, $col); ?></td>
	<?php
}
?>
</tr>
</tbody>
</table>