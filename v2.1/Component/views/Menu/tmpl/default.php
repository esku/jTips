<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
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

class jTipsRenderMenu {
	function assign($var, $val) {
		$this->$var = $val;
	}

	function displayPreferencesLink() {
		global $jTipsCurrentUser, $jTips, $jLang, $Itemid, $mosConfig_live_site, $mainframe;
		?>
		<div class="jpreferences">
		<?php
		if (!empty($jTipsCurrentUser->id)) {
			if (isJoomla15()) {
				/*?>
				<a class="modal" rel="{handler:'iframe', size: {x: <?php echo $jTips['ShowTipsWidth']; ?>, y: <?php echo $jTips['ShowTipsHeight']; ?>}}" href="<?php echo jTipsRoute("index2.php?option=com_jtips&view=UserPreferences&Itemid=$Itemid&return=" .jTipsGetParam($_REQUEST, 'view', ''). "&menu=0"); ?>" title="<?php echo $jLang['_COM_EDIT_PREFERENCES']; ?>"><?php echo $jLang['_COM_EDIT_PREFERENCES']; ?></a>
				<?php*/
				JHTML::_('behavior.modal');
				$rel = json_encode(array('size' => array('x' => $jTips['ShowTipsWidth'], 'y' => $jTips['ShowTipsHeight'])));
				$url = jTipsRoute("index.php?option=com_jtips&view=UserPreferences&tmpl=component&Itemid=$Itemid&return=" .jTipsGetParam($_REQUEST, 'view', ''). "&menu=0");
				$attribs = array(
			    	'class'	=> 'modal',
			    	'rel'	=> str_replace('"', "'", $rel),
			    	'title' => $jLang['_COM_EDIT_PREFERENCES']
			    );
			    echo JHTML::link($url, $jLang['_COM_EDIT_PREFERENCES'], $attribs);
			} else {
				?>
				<a href="javascript:void(0);" onClick="loadPreferencesPopup('<?php echo jTipsGetParam($_REQUEST, 'view', ''); ?>', '<?php echo $Itemid; ?>', '<?php echo $this->season_link; ?>', this);" title="<?php echo $jLang['_COM_EDIT_PREFERENCES']; ?>"><?php echo $jLang['_COM_EDIT_PREFERENCES']; ?></a>
				<?php
			}

		}
		//can this user administer the results for this competition
		// BUG 309 - remove the popup admin pages - accessible as normal link
		$my =& $mainframe->getUser();
		if ($this->jSeason->scorer_id == $my->id and !empty($my->id) and !empty($this->jSeason->scorer_id) and jTipsGetParam($_REQUEST, 'view', 'Dashboard') != 'Administration') {
			/*if (isJoomla15()) {
				?>
				<a href="<?php echo jTipsRoute("index2.php?option=com_jtips&amp;" .$this->adminUrl); ?>" class="modal" rel="{handler: 'iframe', size: {x: 800, y: 450}}" title="<?php echo $jLang['_COM_ADMIN_RESULTS']; ?>"><?php echo $jLang['_COM_ADMIN_RESULTS']; ?></a>
				<?php
			} else {
			    ?>
				&nbsp;<a href="javascript:void(0);" onClick="openPopup('<?php echo $this->adminUrl; ?>', '<?php echo $jLang['_COM_ADMIN_RESULTS']; ?>', 800, 450);" title="<?php echo $jLang['_COM_ADMIN_RESULTS']; ?>"><?php echo $jLang['_COM_ADMIN_RESULTS']; ?></a>
				<?php
			}*/
			?>
			<a href="<?php echo jTipsRoute("index.php?option=com_jtips&amp;" .$this->adminUrl); ?>" title="<?php echo $jLang['_COM_ADMIN_RESULTS']; ?>"><?php echo $jLang['_COM_ADMIN_RESULTS']; ?></a>
			<?php
		}
		?>
		</div>
		<?php
	}

	function display() {
		global $mosConfig_live_site, $jTips, $Itemid, $jLang, $jTipsCurrentUser;
		if (is_object($this->jSeason) and $this->jSeason->exists() and isset($this->jSeason->image) and !empty($this->jSeason->image)) {
			?>
			<div class="jseason_image">
			<?php
			$close_a = "";
			if (isset($this->jSeason->url) && !empty($this->jSeason->url)) {
				//BUG 273 - Invalid reference for jSeason->url, should be this->jSeason->url
				?>
				<a href='<?php echo $this->jSeason->url; ?>' target='_blank'>
				<?php
				$close_a = "</a>";
			}
			?>
			<img src='<?php echo $mosConfig_live_site.'/'.$this->jSeason->image; ?>' border='0' /><?php echo $close_a; ?>
			</div>
			<?php
		} else if (!empty($jTips['Title'])) { // BUG 340 - moved to jtips.php to be included in all pages, regardless of displaying the menu
			/*?>
			<h1 class='componentheading jmain_heading'><?php echo $jTips['Title']; ?></h1>
			<?php*/
		}
		if (!$jTips['DisableMenu']) {
			?>
			<div style="clear:both;"></div>
			<table align='center' width='100%'>
				<tr valign='middle'>
			<?php
			foreach ($this->menuitems as $key => $item) {
				?>
					<td class='<?php echo $item['class']; ?>' style='<?php echo $item['cursor']; ?> width:<?php echo $this->cellsize; ?>%;' <?php echo $item['onevent']; ?>><?php echo $jTips['Menu'][$key]; ?></td>
				<?php
			}
			?>
				</tr>
			</table>
			<?php
		}
		?>
		<div style="clear:both;"></div>
		<div id="blackout" style="position:absolute; background-color:#444B4F; color:white; opacity:0; z-index:-1000; text-align:center; visibility:hidden;">
			<div id="viewer"></div>
		</div>
		<?php
	}
}
?>
