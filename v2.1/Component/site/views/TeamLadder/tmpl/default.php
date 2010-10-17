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
class jTipsRenderTeamLadder {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function display() {
		global $Itemid, $database, $mainframe, $jTips, $jLang, $mosConfig_live_site, $jTipsCurrentUser;
		$mosConfig_offset = $mainframe->getCfg('offset');
		$postURL = jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid");
		?>
		<script type='text/javascript'>
		function getTeamLadder(obj) {
			document.getElementById('season_id').value = obj.options[obj.selectedIndex].value;
			document.ladderForm.submit();
			//window.location.href='index.php?option=com_jtips&Itemid=<?php echo $Itemid; ?>&task=teams&season=' + id;
		}
		</script>
		<form action="<?php echo $postURL; ?>" method="post" name="ladderForm">
		<input type="hidden" name="option" value="com_jtips" />
		<input type="hidden" name="task" value="TeamLadder" />
		<input type="hidden" name="season" id="season_id" />
		<?php
		if (jTipsGetParam($_REQUEST, 'menu', 1)) {
			//jtips_HTML::seasonsList($jTipsCurrentUser, $this->jSeasons, "onchange='getTeamLadder(this);'", false, $this->jSeason->id);
		}
		?>
		<table width='100%' cellspacing="0">
		<thead>
		<?php
		?>
		<tr class='sectiontableheader jtableheader'>
			<th class="jtips_team_ladder_header">#</th>
			<th class="jtips_team_ladder_header">&nbsp;</th>
			<th class="jtips_team_ladder_header"><?php echo $jLang['_COM_TLD_TEAM']; ?></th>
			<?php
		if (!empty($jTips['TeamLadderColumns'])) {
			foreach ($jTips['TeamLadderColumns'] as $field) {
				$lang_key = "_COM_TLD_ABR_" .strtoupper($field);
				?>
				<th style="text-align:center" width="5" class="jtips_team_ladder_header"><?php echo $jLang[$lang_key]; ?></th>
				<?php
			}
		}
		?>
		</tr>
		<?php
		?>
		</thead>
		<tbody>
		<?php
		$i = 1;
		$rowIndex = 0;
		$noteams = true;
		if (count($this->jTeams) > 0) {
			foreach ($this->jTeams as $jTeam) {
				$extra = "";
				$thisRowIndex = $rowIndex++;
				?>
				<tr class='sectiontableentry<?php echo ($thisRowIndex%2)+1; ?> jtablerow<?php echo ($thisRowIndex%2)+1; ?>'>
					<td style='text-align:center'><?php echo $i; ?></td>
					<td style='text-align:center'>
					<?php
					$img = $jTeam->getLogo();
//					if (jTipsFileExists(getJtipsImage($jTeam->logo))) {
//						$img .= "<img src='" .$mainframe->getCfg('live_site');
//						if (!empty($jTips['SubDirectory'])) {
//							$img .= $jTips['SubDirectory'];
//						}
//						$img .= "/" .getJtipsImage($jTeam->logo). "' alt=' ' border='0' />";
//					}
					if (empty($img)) {
						$img .= "&nbsp;";
					}
					echo $img;
					?>
					</td>
					<?php
				$popupUrl = "view=TeamLadder&Itemid=$Itemid&action=ShowTeam&id=" .$jTeam->id;
				if (jTipsGetParam($_REQUEST, 'menu', 0) or !jTipsGetParam($_REQUEST, 'tmpl', false)) {
					if (isJoomla15()) {
						/*$x = $jTips['ShowTipsWidth'];
						$y = $jTips['ShowTipsHeight'];
						//BUG 274 - team ladder popup results in broken scroll bar
						if (basename($_SERVER['SCRIPT_FILENAME']) != 'index2.php') {
							$class = "class='modal'";
						} else {
							$class = '';
						}
						$teamname = "<a $class rel=\"{handler: 'iframe', size: {x: $x, y: $y}}\" href='" .jTipsRoute($mosConfig_live_site. "/index2.php?option=com_jtips&$popupUrl&menu=0"). "'>" .$jTeam->getName(). "</a>";
						*/
						// better popup handling in J1.5
						JHTML::_('behavior.modal');
						$rel = json_encode(array('handler' => 'iframe', 'size' => array('x' => $jTips['ShowTipsWidth'], 'y' => $jTips['ShowTipsHeight'])));
						$url = jTipsRoute("index.php?option=com_jtips&tmpl=component&$popupUrl&menu=0");
						$attribs = array(
						'class'	=> 'modal',
						'rel'	=> str_replace('"', "'", $rel),
						'title' => $jTeam->getName()
					    );
					    $teamname = JHTML::link($url, $jTeam->getName(), $attribs);
					} else {
						$teamname = "<a href='javascript:void(0);' onClick='openPopup(\"$popupUrl\", \"" .$jTeam->getName(). "\");'>" .$jTeam->getName(). "</a>";
					}
				} else {
					$teamname = $jTeam->getName();
				}
				?>
				<td align="left"><?php echo $teamname; ?></td>
				<?php
				if (!empty($jTips['TeamLadderColumns'])) {
					foreach ($jTips['TeamLadderColumns'] as $field) {
						?>
						<td style='text-align:center'><?php echo $jTeam->getTeamField($field); ?></td>
						<?php
					}
				}
				?>
			</tr>
			<?php
				$i++;
			}
		} else {
			?>
			<tr>
				<td style='text-align:center' colspan='20'><?php echo $jLang['_COM_TEAMS_UNAVAILABLE']; ?></td>
			</tr>
			<?php
		}
		$rowIndex = 0;
		?>
		</tbody>
		</table>
		<table align='right' cellspacing="0">
		<thead id="teamLegendHeader">
		<tr>
			<th colspan='2' class='sectiontableheader'><?php echo $jLang['_COM_TLD_LEGEND']; ?></th>
		</tr>
		</thead>
		<tbody id='teamLegend'>
		<?php
		if (!empty($jTips['TeamLadderColumns'])) {
			foreach ($jTips['TeamLadderColumns'] as $field) {
				$lang_key = "_COM_TLD_" .strtoupper($field);
				$abr_key = "_COM_TLD_ABR_" .strtoupper($field);
				$thisRowIndex = $rowIndex++;
				?>
				<tr class='sectiontableentry<?php echo ($thisRowIndex%2)+1; ?> jtablerow<?php echo ($thisRowIndex%2)+1; ?>'>
					<td style='text-align:center;font-weight:bold;'><?php echo $jLang[$abr_key]; ?></td>
					<td style='text-align:left'><?php echo $jLang[$lang_key]; ?></td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
		</table>
		<?php
		if ($jTips['JsLadder'] != 'none') {
			?>
			<script type='text/javascript'>
			window.addEvent('domready', function() {
				var teamLegend = new Fx.Slide('teamLegend', {
					duration:<?php echo $jTips['JsLadderDuration'] * 1000; ?>,
					wait: true,
					<?php
					if ($jTips['JsLadder'] == 'linear') {
						echo "transition: Fx.Transitions.linear";
					} else {
						?>
					transition: Fx.Transitions.<?php echo $jTips['JsLadder']; ?>.<?php echo $jTips['JsLadderStyle']; ?>
						<?php
					}
					?>
				});
				teamLegend.hide();
				$('teamLegendHeader').addEvent('click', function(e){
					e = new Event(e);
					teamLegend.toggle();
					e.stop();
				});
				$('teamLegendHeader').setStyle('cursor', 'pointer');
			});

			</script>
			<?php
		}
		?>
		</form>
		<?php
	}
}

?>
