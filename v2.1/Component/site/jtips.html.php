<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

class jtips_HTML {
	/**
	 * Display the page title and set the document title based on parameters
	 * // BUG 349 - support for Page Title menu item parameter option
	 *
	 * @since 2.1.10
	 */
	function title() {
		global $mainframe, $jTips;
		$view = jTipsGetParam($_REQUEST, 'view', 'Dashboard');
		$default_title = jTipsGetParam($jTips['Menu'], $view, 'jTips');
		if (isJoomla15()) {
			$document	=& JFactory::getDocument();
			$params		= &$mainframe->getParams();

			// Page Title
			$menus		= &JSite::getMenu();
			$menu		= $menus->getActive();

			if (is_object( $menu )) {
				$menu_params = new JParameter( $menu->params );
				if (!$menu_params->get( 'page_title')) {
					$params->set('page_title',	JText::_( $default_title ));
				}
			} else {
				$params->set('page_title',	JText::_( $default_title ));
			}
			$document->setTitle( $params->get( 'page_title' ) );


			if ( $params->def( 'show_page_title', 0 ) ) {
				?>
				<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<?php echo html_entity_decode(stripslashes($params->get('page_title'))); ?>
				</div>
				<?php
			}
		} else {
			$mainframe->setPageTitle($default_title);
			if (!empty($jTips['Title'])) {
				?>
<h1 class='componentheading jmain_heading'><?php echo $jTips['Title']; ?></h1>
				<?php
			}
		}
	}
	/**
	 * Shows the jTips logo, version and copyright information
	 */
	function licence() {
		global $jLicence, $mosConfig_live_site;
		$opacity = 50;
		?>
<div style='clear: both; padding-top: 10px;'>
<table border='0' align='right' cellspacing='3' id="jtips_copyright">
	<tr>
		<td style='text-align: right; font-size: xx-small;' valign='bottom'>v<?php echo getFullVersion(); ?>
		<br />
		<a href='http://www.jtips.com.au' style='float: right;' target="_blank" rel="nofollow">&copy; <?php echo date('Y'); ?>
		jTips</a></td>
		<td style='text-align: center' valign='bottom'><a
			href='http://www.jtips.com.au' target="_blank" rel="nofollow"> <img src='<?php echo $mosConfig_live_site; ?>/components/com_jtips/images/license_logo.png' alt='jTips <?php echo getVersionNum(); ?>' border='0' style='filter:alpha(opacity=<?php echo $opacity; ?>);-moz-opacity:.<?php echo $opacity; ?>;opacity:.<?php echo $opacity; ?>; float:right;' />
		</a></td>
	</tr>
	<!--tr>
					<td style='text-align:right'>Last Validated:</td>
					<td><?php echo $jLicence->getValidationDate(); ?></td>
				</tr-->
</table>
</div>
<div
	style="clear: both; display: none;" id="debug"></div>
<div style="clear: both;"></div>
		<?php
	}

	/**
	 *
	 */
	function expired() {
		global $jLicence, $mainframe;
		$my = $mainframe->getUser();
		if ($jLicence->getLicenceStatus() and $my->gid == '25') {
			?>
<p class="error"><?php echo $jLicence->getValidationError(); ?></p>
<p class="message">Last Validated: <?php echo $jLicence->getValidationDate(); ?></p>
			<?php
		}
	}
}
?>
