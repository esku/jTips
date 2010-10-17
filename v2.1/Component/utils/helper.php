<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 2.1 - 16/10/2008
 * @version 2.1
 * @package jTips
 *
 * Description: This adds support for wrapping Joomla functions so that
 * we can let jTips determine if we are in J!.0 or J1.5.
 */

class jTipsCommonHTML {
	function loadOverlib() {
		if (isJoomla15()) {
			JHTML::_('behavior.tooltip');
		} else {
			mosCommonHTML::loadOverlib();
		}
	}

	function loadCalendar()	{
		if (isJoomla15()) {
			JHTML::_('behavior.calendar');
		} else {
			mosCommonHTML::loadCalendar();
		}
	}
}

if (isJoomla15()) {
	JLoader::register('JPaneTabs',  JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'pane.php');
	class jTipsTabs extends jPaneTabs {
		var $useCookies = false;

		function __construct( $useCookies, $xhtml = null) {
			parent::__construct( array('useCookies' => $useCookies) );
		}

		function startTab( $tabText, $paneid ) {
			echo $this->startPanel( $tabText, $paneid);
		}

		function endTab() {
			echo $this->endPanel();
		}

		function startPane( $tabText ){
			echo parent::startPane( $tabText );
		}

		function endPane(){
			echo parent::endPane();
		}
	}
} else {
	class jTipsTabs extends mosTabs {

	}
}

class jTipsHTML {
	function makeOption( $value, $text='', $value_name='value', $text_name='text' )	{
		if (isJoomla15()) {
			return JHTML::_('select.option', $value, $text, $value_name, $text_name);
		} else {
			return mosHTML::makeOption($value, $text, $value_name, $text_name);
		}
	}

	function selectList( &$arr, $tag_name, $tag_attribs, $key, $text, $selected=NULL, $idtag=false, $flag=false ) {
		// BUG 393 - remove slashes from lists
		for ($x=0; $x<count($arr); $x++) {
                        if (is_object($arr[$x])) {
                                $arr[$x]->text = jTipsStripslashes($arr[$x]->text);
                        }
                }
		if (isJoomla15()) {
			return JHTML::_('select.genericlist', $arr, $tag_name, $tag_attribs, $key, $text, $selected, $idtag, $flag );
		} else {
			//BUG 216 - removed ampersand from $arr
			return mosHTML::selectList( $arr, $tag_name, $tag_attribs, $key, $text, $selected, $idtag, $flag);
		}
	}

	function yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes='Yes', $no='No', $id=false ) {
		if (isJoomla15()) {
			return JHTML::_('select.booleanlist',  $tag_name, $tag_attribs, $selected, $yes, $no, $id ) ;
		} else {
			return mosHTML::yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes, $no, $id);
		}
	}

	function idBox( $rowNum, $recId, $checkedOut=false, $name='cid' ) {
		if (isJoomla15()) {
			return JHTML::_('grid.id', $rowNum, $recId, $checkedOut, $name);
		} else {
			return mosHTML::idBox($rowNum, $recId, $checkedOut, $name);
		}
	}

	function integerSelectList( $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format="" ) {
		if (isJoomla15()) {
			return JHTML::_('select.integerlist', $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format) ;
		} else {
			return mosHTML::integerSelectList( $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format);
		}
	}

	function keepAlive() {
		if (isJoomla15()) {
			echo JHTML::_('behavior.keepalive');
		} else {
			//This function does not exist in J1.0
			//mosHTML::keepAlive();
		}
	}

	function cleanText ( &$text ) {
		if (isJoomla15()) {
			return JFilterOutput::cleanText($text);
		} else {
			return mosHTML::cleanText($text);
		}
	}
}

if (isJoomla15()) {
	JLoader::register('JToolbarHelper' , JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbar.php');
	class jTipsMenuBar extends JToolbarHelper	{
		function startTable() {
			return;
		}

		function endTable()	{
			return;
		}

		function addNew($task = 'new', $alt = 'New') {
			parent::addNew($task, $alt);
		}

		function addNewX($task = 'new', $alt = 'New') {
			parent::addNew($task, $alt);
		}

		function saveedit()	{
			parent::save('saveedit');
		}
	}
} else {
	$mosConfig_absolute_path;
	require_once($mosConfig_absolute_path.'/administrator/includes/menubar.html.php');
	class jTipsMenuBar extends mosMenuBar {

	}
}

/**
 * Function to get the SEF route.
 *
 * Thanks to masterchief for this function :)
 *
 * @since 2.1.10
 * @param   string   $url   The non-SEF route, starting with ?
 * @return   string   The SEF route
 */
function jTipsGetSiteRoute($url) {
	global $mainframe;

	if ($mainframe->isAdmin()) { // do the parsing for the router
		static $router;

		jimport('joomla.application.router');
		require_once(JPATH_SITE.DS.'includes'.DS.'application.php');

		// Only get the router once.
		if (!is_object($router)) {
			// Get and configure the site router.
			$config	= &JFactory::getConfig();
			$router	= &JRouter::getInstance('site');
			$router->setMode($config->getValue('sef', 1));
		}

		// Build the route.
		$uri	= &$router->build($url);
		$route	= $uri->toString(array('path', 'query', 'fragment'));

		// Strip out the base portion of the route.
		$route = str_replace(JURI::base(true).'/', '', $route);
	} else {
		$route = JRoute::_($url, false);
	}

	return $route;
}
?>
