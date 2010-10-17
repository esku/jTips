<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 29/09/2008
 *
 * Description: Displays the full team ladder
 *
 *
 */
class jTipsRenderCompetitionLadder {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function display() {
		global $mainframe, $database, $jTips, $jLang, $Itemid, $jTipsCurrentUser;

		$sort_by = jTipsGetParam($_REQUEST, 'sort_by', 'rank');
		$sort_dir = jTipsGetParam($_REQUEST, 'dir', 'asc');
		if (empty($this->jRound->id)) {
			$this->jRound->id = -1;
		}
		//BUG 132 - form submits to homepage
		$postURL  = "index.php?option=com_jtips&Itemid=$Itemid";
		//$postURL .= http_build_query($_GET);
		?>
		<script type="text/javascript">
		window.addEvent('domready', function(){
			populateRounds('<?php echo $this->jSeason->id; ?>', 'round_id', <?php echo $this->jRound->id; ?>);
		})

		function _goToRound(obj) {
			var baseURL = "<?php echo html_entity_decode(jTipsRoute($postURL)); ?>";
			if (baseURL.match('round_id')) {
				baseURL = baseURL.replace(/round_id=[0-9]*/g, 'round_id=' + $(obj).value);
			} else {
				baseURL += '&round_id=' + $(obj).value;
			}
			window.location.href=baseURL;
		}
		</script>
		<form action="<?php echo jTipsRoute($postURL); ?>" method="POST" name="adminForm" id="compform">
		<input type="hidden" name="filter_order" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order', 'rank'); ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc'); ?>" />
		<input type="hidden" name="view" value="CompetitionLadder" />
		<input type="hidden" name="task" value="CompetitionLadder" />
		<input type="hidden" name="option" value="com_jtips" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
		<h3 align='center' class="jladderselects">
			<?php
			//if (jTipsGetParam($_REQUEST, 'menu', 1) and !$jTips['DisableSeasonSelect'] and !empty($this->jSeasons) and count($this->jSeasons) > 1) {
				//jtips_HTML::seasonsList($jTipsUser, $jSeasons, "populateRounds(this, 'round_id');", true);
				/*$select_options = array();
				foreach ($jSeasons as $jS) {
					$select_options[$jS->id] = $jS->name;
				}
				echo makeSelectList($select_options, 'season', "onChange=\"populateRounds(this, 'round_id');\"", $this->jSeason->id);
				*/
				//jtips_HTML::seasonsList($jTipsCurrentUser, $this->jSeasons, "id='season_id' onChange=\"populateRounds(this, 'round_id');\"", false, $this->jSeason->id);
			//} else {
				if (empty($this->jRound->id)) {
					$this->jRound->id = -1;
				}
				?>
				<input type="hidden" name="season" id="season" value="<?php echo $this->jSeason->id; ?>" />
				<?php
			//}
			?>
			&nbsp;<?php echo $jLang['_COM_DASH_ROUND']; ?>:&nbsp;
			<select name="round_id" id="round_id" disabled="disabled" class="inputbox" onChange="goToRound(this);">
				<option value=""><?php echo $jLang['_COM_LADDER_SELECT_SEASON']; ?></option>
			</select>
		</h3>
		<div class='contentheading jmain_heading'>
		<?php
		echo $this->jSeason->name;
		if ($this->jRound->exists()) {
			echo " " .$jLang['_COM_COMP_LADAT']. " " .$this->jRound->round;
		} else {
			echo " " .$jLang['_COM_LADDER_NO_ROUNDS'];
		}
		?>
		</div>
		<?php /*echo getPageNavigation($page);*/ ?>
		<table width='100%' cellspacing='0' border='0'>
		<thead>
		<tr class='sectiontableheader jtableheader'>
			<?php
			if (!empty($jTips['CompetitionLadderColumns'])) {
				foreach ($jTips['CompetitionLadderColumns'] as $column) {
					?>
					<th><?php echo getUserLadderHeader($column); ?></th>
					<?php
				}
			}
			?>
		</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		foreach ($this->jTipsUsers as $jTipsUser) {
			if (empty($jTipsUser->id)) {
				continue;
			}
			if ($i % 2 == 0) {
				$class = 'sectiontableentry1 jtablerow1';
			} else {
				$class = 'sectiontableentry2 jtablerow2';
			}
			if ($jTipsUser->id == $jTipsCurrentUser->id) {
				$font = "style='font-weight:bold;'";
			} else {
				$font = "";
			}
			?>
			<tr class='<?php echo $class; ?>' <?php echo $font;?>>
				<?php
				if (!empty($jTips['CompetitionLadderColumns'])) {
					foreach ($jTips['CompetitionLadderColumns'] as $column) {
						$field = getUserLadderField($jTipsUser, $this->jRound, $column);
						if (is_numeric($field)) {
							$align = 'center;';
						} else {
							$align = 'left;';
						}
						?>
						<td style='text-align:<?php echo $align; ?>;'><?php echo $field; ?></td>
						<?php
					}
				}
				?>
			</tr>
			<?php
			$i++;
		}
		?>
		</tbody>
		<tfoot>
		<tr>
		<?php
		if (!is_null($this->pageNav)) {
			echo "<td colspan='100' align='center' style='text-align:center;'>" .$this->pageNav->getListFooter(). "</td>";
		}
		?>
		</tr>
		</tfoot>
		</table>
		</form>
		<?php
	}
}
?>