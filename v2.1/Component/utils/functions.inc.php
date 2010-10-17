<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
global $mosConfig_absolute_path, $jTips;

require_once(dirname(__FILE__).'/logger.php');
require_once(dirname(__FILE__).'/compat.php');
require_once(dirname(__FILE__).'/timedate.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jbadword.class.php');
require_once($mosConfig_absolute_path.'/administrator/components/com_jtips/classes/jcomment.class.php');

/**
 * Build HTML used as the main record edit link in list views in the admin area
 *
 * @param $text The link text
 * @param $id The id of the record to link to
 * @return string The HTML for the anchor tag
 */
function makeListLink($text, $id) {
	return "<a href='javascript:hideMainMenu();listItemTask(\"cb$id\",\"edit\");'>$text</a>";
}

/**
 * Write the Javascript language file.
 * This allows the jTips language variables (jLang) to be accessed
 * from javascript, by accessing the array properties as jTipsLang.LANGUAGEKEY
 *
 * @return the number of bytes that were written to the file, or FALSE on failure
 */
function writeJavascriptLanguage() {
	global $mosConfig_absolute_path, $jLang;
	$file = $mosConfig_absolute_path. '/components/com_jtips/js/language.js';
	if (jTipsFileExists($file)) {
		return true;
	} else {
		$jLangJSON = "var jTipsLang = ";
		$jLangJSON .= @json_encode($jLang);
		if (isJoomla15()) {
			jimport('joomla.filesystem.file');
			return JFile::write($file, $jLangJSON);
		} else {
			return file_put_contents($file, $jLangJSON);
		}
	}
}

/**
 * Determine the current season from the request or from the params of the
 * menu item being used to access the current page
 *
 * @param [bool] Force retrieval from the request
 * @return int The season id
 */
function getSeasonID($force=false) {
	global $database, $mainframe, $Itemid;
	if ($force) {
		return jTipsGetParam($_REQUEST, 'season', jTipsGetParam($_REQUEST, 'season_id', null));
	} else {
		if (isJoomla15()) {
			//get the season_id
			$params = $mainframe->getParams('com_jtips');
			$season_id = $params->get('season_id');
		} else {
			$menu =& new mosMenu( $database );
			$menu->load( $Itemid );
			$params =& new mosParameters( $menu->params );
			$query = "SELECT id FROM #__jtips_seasons WHERE name = " .$database->quote($params->get('season'));
			$database->setQuery($query, 0, 1);
			//return $database->loadResult();
			$season_id = $database->loadResult();
		}
		if (!$season_id) {
			return getSeasonID(true);
		} else {
			return $season_id;
		}
	}
}

/**
 * Returns an array of Option objects of all active Joomla users
 *
 * @return array A set of HTML option objects
 */
function jTipsGetAvailableScorers() {
	global $jTips, $database, $jLang;
	$name = ($jTips['DisplayName'] == 'user' ? 'name' : 'username');
	$query = "SELECT id, $name AS name FROM #__users WHERE activation IS NULL or activation = '' ORDER BY $name ASC";
	$database->setQuery($query);
	$rows = $database->loadAssocList();
	$options = array(jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']));
	foreach ($rows as $row) {
		$options[] = jTipsHTML::makeOption($row['id'], $row['name']);
	}
	return $options;
}

/**
 * Used by the old My Tips (Slider) Dashlet to display details about
 * each game
 *
 * This should probably be moved to the jTip class if still being used.
 *
 * @deprecated ? (May still be in use)
 * @param $tipped The team id for the team that was selected
 * @param $not_tipped The team id for the team that was not selected
 * @param $jGame The jGame object the selection is for
 * @param $jTip The jTip object that contains the tip details
 * @return string The additional game information for a single tip
 */
function getTipOverlib($tipped, $not_tipped, $jGame, $jTip) {
	global $database, $jLang;
	$overlib = "";
	$break = false;
	if ($jGame->has_score == 1) {
		$overlib .= ($break ? "<br />" : "").$jLang['_COM_SCORE']. ": " .$jTip->home_score. " - " .$jTip->away_score;
		$break = true;
	}
	if ($jGame->has_margin == 1) {
		$overlib .= ($break ? "<br />" : "").$jLang['_COM_MARGIN']. ": " .$jTip->margin;
		$break = true;
	}
	if ($jGame->has_bonus == 1) {
		if ($jTip->bonus_id > 0) {
			$bTeam = new jTeam($database);
			$bTeam->load($jTip->bonus_id);
			$bmargin = $bTeam->getName();
		} else if ($jTip->bonus_id == -2) {
			$bmargin = $jLang['_COM_BONUS_CHOICE_BOTH'];
		} else {
			$bmargin = "-";
		}
		$overlib .= ($break ? "<br />" : "").$jLang['_COM_BONUS']. ": " .$bmargin;
		$break = true;
	}
	return $overlib;
}

/**
 * Determing the relative path to a selected image given the required size
 *
 * @param $location
 * @param $size
 * @return string The path to the image
 */
function getJtipsImage($location, $size=25) {
	$dot = strrpos($location, '.');
	$type = substr($location, $dot);
	$suffix = '_'.$size.$type;
	return substr_replace($location, $suffix, $dot);
}

/**
 * Set the correct extension for the image
 * Not used by core component - can this be deprecated?
 *
 * @param $filename The name fo the image file
 * @param $ext The file extension to use/convert to
 */
function setjTipsImageExt($filename, $ext) {
	$dot = strrpos($filename, '.');
	$suffix = preg_replace('/image\//i', '', $ext);
	if ($suffix == 'jpeg') $suffix = 'jpg';
	$negativeStart = 0-strlen($filename)-$dot;
	return substr($filename, 0, $negativeStart).$suffix;
}

/**
 * Turns an array of objects into an associative array for use with makeSelectList
 *
 * @param array The array of objects
 * @param string Can be either a property of the object, or a member function name
 * @param [bool] True if the display parameter is a member function. Default is true
 * @return array The associative array
 */
function objectsToSelectList($objects, $display, $isFunction = false) {
	$options = array('' => '--None--');
	// BUG 327 - empty array causes error at foreach.
	if (empty($objects)) {
		return $options;
	}
	//jTipsDebug(debug_backtrace());
	foreach($objects as $object) {
		if($isFunction) {
			$show = $object-> $display();
		} else {
			$show = $object-> $display;
		}
		$options[$object->id] = $show;
	}
	return $options;
}

/**
 * Determines is the selected property has been defined on the give class
 *
 * @param $class The object to look in
 * @param $property The property to look for
 * @return bool True if exists
 */
function is_property($class, $property) {
	if(is_object($class)) {
		$class = get_class($class);
	}
	return array_key_exists($property, get_class_vars($class));
}

/**
 * Based on the CB version, call the correct function and return the path
 * to the avatar image
 *
 * @param int The user id
 * @return string Path to avatar image
 */
function getCommunityBuilderAvatar($uid) {
	global $ueConfig;
	//BUG 260 - mainframe not declared as global
	global $mainframe;
	if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
		$_CB_joomla_adminpath = JPATH_ADMINISTRATOR;
	} else {
		$_CB_joomla_adminpath = $mainframe->getCfg( 'absolute_path' ). '/administrator';
	}
	$_CB_adminpath		=	$_CB_joomla_adminpath. '/components/com_comprofiler';
	//BUG 283 - Better fallback when CB enabled but not installed
	if (!jTipsFileExists($_CB_adminpath)) {
		jTipsLogger::_log('Community Builder is not installed! Aborting CB avatar', 'ERROR');
		return '';
	}
	include_once( $_CB_adminpath . '/ue_config.php' );
	//jTipsDebug($ueConfig);
	//BUG 128 - Changed version detection to regular expressions
	if (preg_match('/\b1.1/', $ueConfig['version'])) {
		return _getCB11Avatar($uid);
	} else if (preg_match('/\b1.2/', $ueConfig['version'])) {
		return _getCB12Avatar($uid);
	} else {
		return '';
	}
}

/**
 * Get the image source for the users JomSocial avatar
 *
 * @param int The id of the user
 * @return string The URI of the avatar image
 */
function getJomSocialAvatar($user_id) {
	global $mosConfig_absolute_path;
	// make sure the component is installed
	jTipsLogger::_log('Fetch JomSocial user avatar image path');
	$jspath = $mosConfig_absolute_path. '/components/com_community';
	if (jTipsFileExists($jspath)) {
		include_once($jspath.'/libraries/core.php');

		// Get CUser object
		$user =& CFactory::getUser($user_id);
		$avatarUrl = $user->getThumbAvatar();

		return $avatarUrl;
	} else {
		jTipsLogger::_log('JomSocial is not installed!', 'ERROR');
		return '';
	}
}

/**
 * Get the URL to the users JomSocial profile page
 *
 * @param int The id of the user
 * @return string The URL link
 */
function getJomSocialProfileLink($user_id) {
	global $mosConfig_absolute_path;
	$jspath = $mosConfig_absolute_path. '/components/com_community';
	jTipsLogger::_log('Fetch JomSocial user profile page link');
	if (jTipsFileExists($jspath)) {
		include_once($jspath.'/libraries/core.php');

		// Get CUser object
		$link = CRoute::_('index.php?option=com_community&view=profile&userid='.$user_id);

		return $link;
	} else {
		jTipsLogger::_log('JomSocial is not installed!', 'ERROR');
		return '';
	}
}

/**
 * Function to get the path of a users profile avatar from CB.
 * This is for Community Builder 1.2 only.
 * This function should not be called directly. Use getCommunityBuilderAvatar() instead
 *
 * @param int The user ID
 * @return string the path to the avatar image
 */
function _getCB12Avatar($uid) {
	global $ueConfig, $mainframe;
	jTipsLogger::_log('get community builder avatar from version 1.2', 'INFO');
	if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
		$_CB_joomla_adminpath = JPATH_ADMINISTRATOR;
	} else {
		$_CB_joomla_adminpath = $mainframe->getCfg( 'absolute_path' ). '/administrator';
	}
	$_CB_adminpath		=	$_CB_joomla_adminpath. '/components/com_comprofiler';
	include_once( $_CB_adminpath . '/ue_config.php' );
	include_once( $_CB_adminpath . '/plugin.foundation.php' );
	cbimport( 'cb.database' );
	cbimport( 'language.front' );
	$cbUser		=&	CBuser::getInstance($uid);
	$path = $cbUser->avatarFilePath( 2 );
	return $path;
}

/**
 * Function to get the path of a users profile avatar from CB.
 * This is for Community Builder 1.1 only.
 * This function should not be called directly. Use getCommunityBuilderAvatar() instead
 *
 * @param int The user ID
 * @return string The path to the image
 */
function _getCB11Avatar($uid) {
	global $mosConfig_absolute_path, $mosConfig_live_site, $database;
	jTipsLogger::_log('get community builder avatar from version 1.1', 'INFO');
	//global $_CB_database
	if(file_exists($mosConfig_absolute_path . '/administrator/components/com_comprofiler/plugin.class.php')) {
		require_once($mosConfig_absolute_path . '/administrator/components/com_comprofiler/plugin.class.php');
	}
	require_once($mosConfig_absolute_path . '/administrator/components/com_comprofiler/comprofiler.class.php');
	$users = array();
	$query = "SELECT * FROM #__comprofiler WHERE id = $uid";
	$database->setQuery($query);
	$users = $database->loadObjectList();
	if(count($users) == 0) {
		//echo _UE_NOSUCHPROFILE;
		jTipsLogger::_log('current user does not exist in community builder 1.1', 'ERROR');
		return "";
	}

	$user = $users[0];
	$path = getFieldValue('image', $user->avatar, $user);
	//jTipsDebug($path);
	//need to strip the img tag and get the src
	$path=preg_replace ('/.*<img src="([^"]+)".*/is','\1',$path);
	return $mosConfig_live_site. '/' .$path;
}

if (!function_exists('http_build_query')) {
	jTipsLogger::_log('http_build_query does not exists in this version - ' .php_version(), 'WARN');
	/**
	 * This should be moved to compat.php
	 * Contruct a http query string from an associative array
	 * LEGACY FUNCTION - see php.net for structure.
	 *
	 * @param $a
	 * @param $b
	 * @param $c
	 * @return string The query
	 */
	function http_build_query($a,$b='',$c=0){
		if (!is_array($a)) return false;
		foreach ((array)$a as $k=>$v){
			if ($c) $k=$b."[".$k."]"; elseif (is_int($k)) $k=$b.$k;
			if (is_array($v)||is_object($v)) {$r[]=http_build_query($v,$k,1);continue;}
			$r[]=$k."=".urlencode($v);
		}
		return implode("&",$r);
	}
}

/**
 * For the selected column, return the HTML for the header, including sortable link
 *
 * @param $column The name of the column
 * @return string The HTML for the column
 */
function getUserLadderHeader($column) {
	global $task;
	//$dir = jTipsGetParam($_REQUEST, 'dir', 'asc');
	$dir = jTipsGetParam($_REQUEST, 'filter_order_Dir', 'asc');
	if($dir == 'desc') {
		$dir = 'asc';
	} else {
		$dir = 'desc';
	}
	//return "<a href='index.php?option=com_jtips&amp;Itemid=" .jTipsGetParam($_REQUEST, 'Itemid', 1). "&amp;task=ladder&amp;season=" .jTipsGetParam($_REQUEST, 'season', ''). "&amp;sort_by=$column&amp;dir=$dir&amp;page=" .jTipsGetParam($_REQUEST, 'page', 0). "'>" .getSummaryHeader($column). "</a>";
	//return "<a href='javascript:void(1);' onclick='setLadderSort(\"$column\", \"$dir\");'>" . getSummaryHeader($column) . "</a>";
	if ($column == 'doubleup') {
		return getSummaryHeader($column);
	} else {
		//better column sorting
		return "<a href='javascript:tableOrdering(\"$column\", \"$dir\", \"$task\");'>" . getSummaryHeader($column) . "</a>";
	}
}

/**
 * For the given user and column, fetch the correct data to be displayed.
 * Returns the details from the users history for the selected column, eg, total score
 *
 * @param $jTipsUser The jTipsUser object to access data from/for
 * @param $jRound The round to be used as a reference for locating scores
 * @param $column The name of the column to fetch data for
 * @param $isModule True is being called from a module, false otherwise. Default is false
 * @return string The data for the user and field
 */
function getUserLadderField(& $jTipsUser, & $jRound, $column, $isModule = false) {
	global $mainframe, $database, $jTips, $jLang, $mosConfig_live_site, $Itemid, $mosConfig_absolute_path;
	jTipsLogger::_log('fetching user ladder data for user ' .$jTipsUser->id. ' and column ' .$column, 'INFO');
	switch($column) {
		case 'rank' :
			//return $jTipsUser->getRank($jRound->id);
			$params = array('round_id' => $jRound->id, 'user_id' => $jTipsUser->id);
			$jHistory = new jHistory($database);
			$jHistory->loadByParams($params);
			return $jHistory->rank;
			break;
		case 'user' :
			global $option;
			global $Itemid;
			if ($option == 'com_jtips') {
				$suffix = '&Itemid=' .$Itemid;
			} else {
				$suffix = '&season=' .$jTipsUser->season_id;
			}

			$name = $jTipsUser->getName();
			if($jTips['EnableShowTips'] == 1 and $jRound->roundOver() and (!isset($jTips['SocialIntegration']) or empty($jTips['SocialIntegration']))) {
				//Link is to popup
				if (isJoomla15()) {
				    $width	= $jTips['ShowTipsWidth'];
				    $height	= $jTips['ShowTipsHeight'];
				    $title	= $jLang['_LADDER_VIEW_TIPS_FOR']. " " . $name;
				    //$showTipsUrl = jTipsRoute("index2.php?option=com_jtips&view=CompetitionLadder&menu=0&action=ShowTips&uid={$jTipsUser->id}&rid={$jRound->id}".$suffix);
				    /*$link = <<<EOF
				    <a class="modal" rel="{handler: 'iframe', size: {x: {$width}, y: {$height}}}" title="$title" href="$showTipsUrl">{$name}</a>
EOF;*/
				    // load the modal popul helper
				    JHTML::_('behavior.modal');
				    $showTipsUrl = jTipsRoute("index.php?option=com_jtips&view=CompetitionLadder&Itemid={$Itemid}&tmpl=component&menu=0&action=ShowTips&uid={$jTipsUser->id}&rid={$jRound->id}".$suffix);
				    $rel = json_encode(array('size' => array('x' => $width, 'y' => $height)));
				    $attribs = array(
				    	'class'	=> 'modal',
				    	'rel'	=> str_replace('"', "'", $rel),
				    	'title' => $title
				    );
				    $link = JHTML::link($showTipsUrl, $name, $attribs);
				} else {
					global $Itemid;
					$data = "&Itemid=$Itemid";
					$link = "<a href='javascript:void(0);' onclick='loadTipsPopup(" . $jTipsUser->id . ", " . $jRound->id . ", this, \"$data\");' title='" .$jLang['_LADDER_VIEW_TIPS_FOR']. " " . $name . "' id='userLadderLink_" . $jTipsUser->id . "'>" . $name . "</a>";
				}
			} else
				//BUG 283 - Better fallback when CB enabled but not installed
				if(isset($jTips['SocialIntegration']) and !empty($jTips['SocialIntegration'])) {
					if ($jTips['SocialIntegration'] == 'cb') {
						// Link is to CB Pofile
						$img = getCommunityBuilderAvatar($jTipsUser->user_id);
					} else {
						// Link to JomSocial Profile and get avatar
						$img = getJomSocialAvatar($jTipsUser->user_id);
					}
					$alt = $name;
					if(!empty($img)) {
						//$img = preg_replace('/>|\/>/', "align='absmiddle' />", $img);
						$name = "<img src='$img' border='0' align='absmiddle' />&nbsp;$name";
					}
					if($jTips['EnableShowTips'] == 1) {
						if (isJoomla15()) {
							$width	= $jTips['ShowTipsWidth'];
				    		$height	= $jTips['ShowTipsHeight'];
				    		// BUG 312 - only display the user's name in the title of the link
				    		$title	= $jLang['_LADDER_VIEW_TIPS_FOR']. " " . $jTipsUser->getName();
							$showTipsUrl = jTipsRoute("index.php?option=com_jtips&view=CompetitionLadder&Itemid={$Itemid}&menu=0&action=ShowTips&uid={$jTipsUser->id}&rid={$jRound->id}" .$suffix);
							$link = <<<EOF
							    <a class="modal" rel="{handler: 'iframe', size: {x: {$width}, y: {$height}}}" title="$title" href="$showTipsUrl">{$name}</a>
EOF;
						} else {

							$data = "&Itemid=$Itemid";
							$link = "<a href='javascript:void(0);' onclick='loadTipsPopup(" . $jTipsUser->id . ", " . $jRound->id . ", this, \"$data\");' title='" .$jLang['_LADDER_VIEW_TIPS_FOR']. " " . $alt . "' id='userLadderLink_" . $jTipsUser->id . "'>" . $name . "</a>";
						}
					} else {
						// Get Community Builder Link
						if ($jTips['SocialIntegration'] == 'cb') {
							$link = "<a href='" . jTipsRoute("index.php?option=com_comprofiler&amp;task=userProfile&amp;user=" . $jTipsUser->user_id) . "'";
						} else {
							$ccLink = getJomSocialProfileLink($jTipsUser->user_id);
							$link = "<a href='" .jTipsRoute($ccLink). "'";
						}
						$link .=  " title='View Profile' id='userLadderLink_" . $jTipsUser->id . "'>" . $name . "</a>";
					}
				} else {
					$link = $name;
				}
			return $link;
			break;
		case 'points' :
			return $jTipsUser->getRoundScore('points', $jRound->id);
			break;
		case 'pointst' :
			//return $jTipsUser->getTotalScore('points');
			$jHistory = new jHistory($database);
			return $jHistory->getProgressScore($jTipsUser, $jRound->id);
			break;
		case 'prec' :
			return $jTipsUser->getRoundScore('precision', $jRound->id);
			break;
		case 'prect' :
			//return $jTipsUser->getTotalScore('precision');
			$jHistory = new jHistory($database);
			return $jHistory->getProgressScore($jTipsUser, $jRound->id, 'precision');
			break;
		case 'comment' :
			$params = array('user_id' => $jTipsUser->id, 'round_id' => $jRound->id);
			$jComment = new jComment($database);
			$jComment->loadByParams($params);
			/*if (strlen($jComment->comment) > 40) {
				$name = $jTipsUser->getName(). "'s Comment";
				$comment = "<span style='cursor:pointer; cursor:hand;' " .AddOverlibCall($jComment->comment, $name). ">" .substr($jComment->comment, 0, 40). "...</span>";
			} else */
			if(!empty($jComment->comment)) {
				$comment = "<div style='font-weight:normal;text-align:left;'>" . jTipsStripslashes($jComment->comment) . "</div>";
			} else {
				return "";
			}
			return $comment;
			break;
		case 'moved' :
			$prev_id = $jRound->getPrev();
			//BUG 171 & 172 Don't know why this is here... was causing problems
			/*if (!jTipsGetParam($_REQUEST, 'round_id', false)) {
				$prevRound = new jRound($database);
				$prevRound->load($prev_id);
				$prev_id = $prevRound->getPrev();
			}*/
			if ($prev_id < 0) {
				$prev_id = 0;
			}
			$params = array('round_id' => $jRound->id, 'user_id' => $jTipsUser->id);
			$jHistory = new jHistory($database);
			$jHistory->loadByParams($params);
			$curr_rank = $jHistory->rank;
			jTipsLogger::_log('CURRENT: ' .$curr_rank. ' HID: ' .$jHistory->id, 'ERROR');
			$params['round_id'] = $prev_id;
			$jHistory->loadByParams($params);
			$prev_rank = $jHistory->rank;
			jTipsLogger::_log('PREV: ' .$prev_rank. ' HID: ' .$jHistory->id, 'ERROR');
			if($curr_rank > $prev_rank) {
				$arrow = 'down';
			} else if($curr_rank < $prev_rank) {
				$arrow = 'up';
			} else {
				$arrow = 'none';
				return '&nbsp;';
			}
			//Bug 125 - Added mosConfig_live_site to path
			return "<img src='" .$mosConfig_live_site. "/components/com_jtips/images/" . $arrow . ".png' alt='" . $arrow . "' border='0' title='" . ucfirst($arrow) . " " . abs($curr_rank - $prev_rank) . "' />";
			break;
		case 'doubleup':
			if ($jTipsUser->doubleup == $jRound->id) {
				return "<img src='$mosConfig_live_site/administrator/images/tick.png' border='0' alt='Yes' align='absmiddle' />";
			} else {
				return "&nbsp;";
			}
			break;
		default :
			return '-';
			break;
	}
}

/**
 * For ladder tables, build the language key and return the value of the language key
 * The $col must match a corresponding key in $jLang in the form $jLang[_COM_DASH_$col]
 *
 * @param $col
 * @return string The header name
 */
function getSummaryHeader($col) {
	global $jTips, $jLang;
	$key = '_COM_DASH_' .str_replace(' ', '_', strtoupper($col));
	if (!jTipsGetParam($jLang, $key, false)) {
		jTipsLogger::_log('No summary header variable found for column ' .$col, 'ERROR');
		return "&nbsp;";
	} else {
		return $jLang[$key];
	}
}

function getLastRoundSummaryDetail(&$jTipsUser, $col) {
	global $jTips, $jLang, $database;
	$jSeason = new jSeason($database);
	$jSeason->load($jTipsUser->season_id);
	switch ($col) {
		case 'season':
			return $jSeason->name;
			break;
		case 'last_won':
			//return $jTipsUser->getName();
			// BUG 313 - Social Integration on Last Round Summary dashlet
			if (isset($jTips['SocialIntegration']) and !empty($jTips['SocialIntegration'])) {
				// which type of integration
				if ($jTips['SocialIntegration'] == 'cb') {
					// Community Builder
					$link	= jTipsRoute("index.php?option=com_comprofiler&task=userProfile&user=" . $jTipsUser->user_id);
					$img	= getCommunityBuilderAvatar($jTipsUser->user_id);
				} else {
					// JomSocial
					$link	= getJomSocialProfileLink($jTipsUser->user_id);
					$img	= getJomSocialAvatar($jTipsUser->user_id);
				}
				$html = "<img src='$img' alt='Avatar' title='" .$jTipsUser->getName(). "' align='absmiddle' />&nbsp;<a href='$link'>" .$jTipsUser->getName(). "</a>";
				return "<div style='text-align:left;'>$html</div>";
			} else {
				return $jTipsUser->getName();
			}
			break;
		case 'last_prec':
			$jHistory = new jHistory($database);
			return $jHistory->getLast($jTipsUser, 'precision');
			break;
		case 'last_round':
			$jHistory = new jHistory($database);
			return $jHistory->getLast($jTipsUser);
			break;
		case 'last_prect':
			return $jTipsUser->getTotalScore('precision');
			break;
		case 'last_tot':
			return $jTipsUser->getTotalScore('points');
			break;
		case 'last_comm':
			$jComment = new jComment($database);
			$params = array(
				'user_id' => $jTipsUser->id,
				'round_id' => $jSeason->getLastRound()
			);
			$jComment->loadByParams($params);
			return jTipsStripslashes($jComment->comment);
			break;
		default:
			return '-';
	}
}

function getSummaryDetail(&$jTipsUser, $col) {
	global $database, $jTips, $jLang, $mosConfig_live_site;
	$Itemid = jTipsGetParam($_REQUEST, 'Itemid', '');
	$jSeason = new jSeason($database);
	$jSeason->load($jTipsUser->season_id);
	switch($col) {
		case 'season':
			return $jSeason->name;
			break;
		case 'rank':
			$rank = $jTipsUser->getRank();
			if (empty($rank)) {
				return "N/A";
			} else {
				return $rank. " / " .$jTipsUser->getTotalUsers();
			}
			break;
		case 'score':
			return $jTipsUser->getTotalScore('points');
			break;
		case 'average':
			$jRound = new jRound($database);
			$jRound->load($jSeason->getLastRound());
			$round = $jRound->round ? $jRound->round : 1;
			return round(($jTipsUser->getTotalScore('points')/$round), 1);
			break;
		case 'precision':
			return $jTipsUser->getTotalScore('precision');
			break;
		case 'projected':
			$jRound = new jRound($database);
			$jRound->load($jSeason->getLatestRound());
			$round = $jRound->round ? $jRound->round : 1;
			$average = $jTipsUser->getTotalScore('points')/$round;
			return round(($average * $jSeason->rounds), 1);
			break;
		case 'doubleup':
			return "<img src='$mosConfig_live_site/administrator/images/" .($jTipsUser->doubleup  > 0 ? "publish_x.png' title='" .$jLang['_COM_DASH_ROUND']. " " .$jTipsUser->doubleup. "'" : "tick.png' />");
			break;
		case 'paid':
			global $Itemid;
			if ($jTips['Payments'] == 'paypal' and !$jTipsUser->paid) {
				$sid = $jSeason->id;
				if (!$sid) $sid = getSeasonID();
				return parsePayPalCode($jTips['PayPal'], 'join', $sid);
			} else if ($jTips['Payments'] == 'manual' and !$jTipsUser->paid) {
				return "<img src='$mosConfig_live_site/administrator/images/publish_x.png' />";
			} else if ($jTips['Payments'] and $jTipsUser->paid) {
				$confirm_unsub = 'return confirm("' . $jLang['_COM_UNSUBLINK_PART1'] . ' ' . $jSeason->name . ' ' . $jLang['_COM_UNSUBLINK_PART2'] . '");';
				return "<div style='text-align:center;'>
							<img src='$mosConfig_live_site/administrator/images/tick.png' alt='Paid' border='0' />
							<!-- br />
							<a href='" .jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Dashboard&action=remove&season={$jSeason->id}"). "' onclick='" .$confirm_unsub ."'>" .$jLang['_COM_UNSUBSCRIBE']. "</a -->
						</div>";
			} else {
				return "-";
			}



			/*if ($jTips['Payments'] == 'paypal') {
				if (!$jTipsUser->paid) {
					return parsePayPalCode($jTips['PayPal'], 'join', $jSeason->id);
				} else {
					$confirm_unsub = 'return confirm("' . $jLang['_COM_UNSUBLINK_PART1'] . ' ' . $jSeason->name . ' ' . $jLang['_COM_UNSUBLINK_PART2'] . '");';
					return "<div style='text-align:center;'>
								<img src='$mosConfig_live_site/administrator/images/tick.png' alt='Paid' border='0' />
								<br />
								<a href='" .jTipsRoute("index.php?option=com_jtips&Itemid=$Itemid&view=Dashboard&action=remove&season={$jSeason->id}"). "' onclick='" .$confirm_unsub ."'>" .$jLang['_COM_UNSUBSCRIBE']. "</a>
							</div>";
				}
			} else if ($jTips['Payments'] == 'manual') {
				return "<img src='$mosConfig_live_site/administrator/images/" .($jTipsUser->paid == 1 ? "tick.png" : "publish_x.png"). "' />";
			} else {
				return "-";
			}*/
			break;
		default:
			return "-";
			break;
	}
}

/**
 * Sends an email to the user confirming their selections
 * TODO: Make the email customisable
 *
 * @param object The jTipsUser to send to
 * @param array The set of tips for the user
 */
function sendTipsConfirmation($jTipsUser, $tips) {
	global $database, $jTips;
	if (!$jTips['TipsNotifyEnable']) {
		jTipsLogger::_log('tips confirmation email disabled', 'INFO');
		return true;
	}
	jTipsLogger::_log('building tips confirmation email');
	$jSeason = new jSeason($database);
	$jSeason->load($jTipsUser->season_id);
	$from_name = $jTips['UserNotifyFromName'];
	$from_email = $jTips['UserNotifyFromEmail'];
	if ($jTips['DisplayName'] == 'user') {
		$name = 'username';
	} else {
		$name = 'name';
	}
	$subject = "Selection Confirmation";
	$content = "Hi " .$jTipsUser->getUserField($name). ",<br /><br />" .
			"Thanks for submitting your tips!<br /><br />" .
			"You picked:<br /><br />";
	foreach ($tips as $jTip) {
		$jGame = new jGame($database);
		$jGame->load($jTip->game_id);
		$home = new jTeam($database);
		$away = new jTeam($database);
		$home->load($jGame->home_id);
		$away->load($jGame->away_id);
		$phrase = 'to defeat';
		if ($jTip->tip_id == $home->id or $jTip->tip_id == '-1') {
			$tipped =& $home;
			$notTipped =& $away;
		} else {
			$tipped =& $away;
			$notTipped =& $home;
		}
		if ($jTip->tip_id == '-1') {
			$phrase = 'to draw with';
		}
		$content .= $tipped->getName(). " $phrase " .$notTipped->getName();
		if ($jSeason->pick_margin == 1 and $jGame->has_margin == 1) {
			$content .= " by " .($jTip->margin ? $jTip->margin : '0');
		}
		if ($jSeason->pick_score == 1 and $jGame->has_score == 1) {
			$content .= " with a final score of ";
			if ($jTip->tip_id == $home->id or $jTip->tip_id == '-1') {
				$content .= $jTip->home_score. " to " .$jTip->away_score;
			} else {
				$content .= $jTip->away_score. " to " .$jTip->home_score;
			}
		}
		$content .= "<br />";
	}
	$content .= "<br />";
	$content .= "Good Luck!<br />";
	$content .= $from_name;

	//BUG 223 - Send the email in plaintext for best compatibility, so convert the line breaks
	$content = str_replace('<br />', "\n", $content);
	jTipsLogger::_log($content, 'ERROR');
	jTipsLogger::_log('sending tips confirmation email to ' .$jTipsUser->getUserField('email'). ' from <' .$from_email. '>');
	if (jTipsMail($from_email, $from_name, $jTipsUser->getUserField('email'), $subject, $content)) {
		jTipsLogger::_log('tips email confirmation sent', 'INFO');
		return true;
	} else {
		jTipsLogger::_log('failed to sent tips confirmation email', 'ERROR');
		return false;
	}
}

function size_readable($size, $retstring = null) {
	// adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
	$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	if($retstring === null) {
		$retstring = '%01.2f %s';
	}
	$lastsizestring = end($sizes);
	foreach($sizes as $sizestring) {
		if($size < 1024) {
			break;
		}
		if($sizestring != $lastsizestring) {
			$size /= 1024;
		}
	}
	if($sizestring == $sizes[0]) {
		$retstring = '%01d %s';
	} // Bytes aren't normally fractional
	return sprintf($retstring, $size, $sizestring);
}

function writeArrayToFile($the_name, $the_array, $the_file) {
	global $mosConfig_absolute_path;
	$the_string = "<?php\n" .
	'// created: ' . date('Y-m-d H:i:s') . "\n" .
	"\$$the_name = " .
	var_export_helper($the_array) .
	";\n?>";
	$the_file = $mosConfig_absolute_path. '/administrator/' .$the_file;
	jTipsLogger::_log('writing array to file at ' .$the_file);
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		if (JFile::write($the_file, $the_string)) {
			return true;
		} else {
			mosRedirect('index.php?option=com_jtips', 'Unable to write to config file - Check file permissions on administrator/components/com_jtips/config.jtips.php');
			return false;
		}
	} else {
		if($fh = @ fopen($the_file, "w")) {
			fputs($fh, $the_string, strlen($the_string));
			fclose($fh);
			return true;
		} else {
			mosRedirect('index2.php?option=com_jtips', 'Unable to write to config file - Check file permissions on administrator/components/com_jtips/config.jtips.php');
			return false;
		}
	}
}

function writeFile($filename, $content) {
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		return JFile::write($filename, $content);
	} else {
		if(is_writable($filename)) {
			if(!$handle = fopen($filename, 'w')) {
				return false;
			}
			if(fwrite($handle, $content) === false) {
				return false;
			}
			fclose($handle);
			return true;
		} else {
			jTipsLogger::_log("failed to write export to file $filename");
			if(!is_dir(dirname($filename))) {
				jTipsLogger::_log('creating export directory');
				mkdir(dirname($filename), 0777, true);
			} else {
				jTipsLogger::_log('touching export file ' . $filename);
				touch($filename);
			}
			return writeFile($filename, $content);
		}
	}
}

function var_export_helper($tempArray) {
	if(!is_array($tempArray)) {
		return var_export($tempArray, true);
	}
	$addNone = 0;
	foreach($tempArray as $key => $val) {
		if($key == '' && $val == '') {
			$addNone = 1;
		}
	}
	$newArray = var_export($tempArray, true);
	if($addNone) {
		$newArray = str_replace("array (", "array ( '' => '',", $newArray);
	}
	return $newArray;
}

function parsePayPalCode($encoded_data, $task, $season) {
	global $mainframe, $Itemid, $mosConfig_live_site;
	$my =& $mainframe->getUser();
	if (!$Itemid) $Itemid = jTipsGetParam($_REQUEST, 'Itemid', '');
	if ($task == 'join') {
		$path = "action=add";
	} else if ($task == 'removeme') {
		$path = "action=remove";
	}
	$path .= "&view=Dashboard&season_id=" .$season. "&user_id=" .$my->id;
	$_SESSION['paypalToken'] = md5(rand());
	$path .= "&token=" .$_SESSION['paypalToken'];
	//$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	//$path .= "&return=" .urlencode(base64_encode($url));
	// BUG 246 - not returning correctly from paypal
	// BUG 303 - pass false to not necode & as &amp;
	// BUG
	if (isJoomla15()) {
		$base = JURI::base();
	} else {
		$base = "http://" .$_SERVER['SERVER_NAME']. "/";
	}
	$url = jTipsRoute($base."index.php?option=com_jtips&Itemid=$Itemid&$path", false);
	jTipsLogger::_log("building paypal form with return url '$url'", 'ERROR');
	return str_replace('{RETURN_URL}', $url, stripslashes($encoded_data));
}

function cleanComment($comment) {
	global $jTips;
	if ($jTips['EnableCommentFilter'] != '1') {
		return $comment;
	}
	$badwords = jBadWord::loadAll();
	$count = $deleted = $replaced = 0;
	$results = array();
	foreach ($badwords as $jBadWord) {
		$search = '/' .$jBadWord->get('badword'). '/' .($jBadWord->get('match_case') == 1 ? 'i' : '');
		$found = preg_match_all($search, $comment, $matches);
		if ($found > 0) {
			$count++;
			$found = $found + $jBadWord->get('hits');
			$jBadWord->set('hits', $found);
			if ($jBadWord->get('action') == 'delete') {
				$deleted++;
				array_push($results, 0);
			} else {
				$comment = preg_replace($search, $jBadWord->get('replace'), $comment);
				$replaced++;
				array_push($results, 1);
			}
			$jBadWord->save();
			continue;
		}
	}
	if (in_array(0, $results)) {
		return false;
	}
	return $comment;
}

function getExportHistory() {
	global $mosConfig_absolute_path, $jLang;
	$path = $mosConfig_absolute_path . '/administrator/components/com_jtips/exports/';
	$files = php4_scandir($path);
	$options = array();
	foreach($files as $filename) {
		$file = $path . $filename;
		if(is_file($file) &&(substr(basename($file),(strlen(basename($file)) - 3))) == 'csv') {
			//$options .= "<option value='" .basename($file). "'>" .basename($file). "</option>";
			$options[basename($file)] = basename($file);
		}

	}
	if(count($options) == 0) {
		$options['none'] = $jLang['_ADMIN_CONF_NONE'];
	}
	//return strlen($options) ? $options : "<option value=''>--None--</option>";
	return $options;
}

function getDayOfWeek($time) {
	return strftime('%u', $time);
}

function php4_scandir($dir, $listDirectories = false, $skipDots = true) {
	$dirArray = array();
	if(is_dir($dir) and $handle = opendir($dir)) {
		while(false !==($file = readdir($handle))) {
			if(($file != "." && $file != "..") || $skipDots == true) {
				if($listDirectories == false) {
					if(is_dir($file)) {
						continue;
					}
				}
				array_push($dirArray, basename($file));
			}
		}
		closedir($handle);
	}
	return $dirArray;
}

function dirlist($dir) {
	foreach(php4_scandir($dir) as $entry)
		if($entry != '.' && $entry != '..') {
			$entry = $dir . '/' . $entry;
			if(is_dir($entry)) {
				$path = pathinfo($entry);
				$listarray[$path['basename']] = dirlist($entry);
			} else {
				$path = pathinfo($entry);
				$listarray[] = $path['basename'];
			}
		}
	return($listarray);
}

function getOffset() {
	global $mainframe;
	$mosConfig_offset = $mainframe->getCfg('offset_user');
	return($mosConfig_offset * 60 * 60);
}

function getAjaxLoading($path = "", $text = "Loading...") {
	return "<img src='" . $path . "components/com_jtips/loading.gif' alt='loading' align='absmiddle' /> <strong>$text</strong>";
}

/**
 * @deprecated 2.1 - 01/10/2008
 */
function AddOverlibCall($pcontent, $title = FALSE) {
	return "";
	$content = str_replace("'", "\\'", $pcontent);
	$content = str_replace("\\n", "<br />", $content);
	//$content = str_replace("<", "\\u003C",$content);
	//$content = str_replace(">", "\\u003E",$content);
	if($title !== FALSE) {
		$title = str_replace("'", "\\'", $title);
		$title = str_replace("\\n", "<br />", $title);
		$caption = ", CAPTION, '$title'";
	} else {
		$caption = "";
	}
	return $content;
	return(empty($content) ? "" : " onmouseover=\"return overlib('" . $content . "'" . $caption . ");\" onmouseout=\"return nd();\"");
}

function makeConfigItem($name, & $params) {
	global $jTips, $jLicence;
	$version = 'ult';
	if(strstr($name, '[')) {
		$first = strpos($name, '[');
		$last = strrpos($name, ']');
		$key1 = substr($name, 0, $first);
		$key2 = substr($name,($first +1), -1);
		$value = isset($jTips[$key1][$key2]) ? $jTips[$key1][$key2] :(isset($params['default']) ? $params['default'] : "");
	} else {
		$value = isset($jTips[$name]) ? $jTips[$name] :(isset($params['default']) ? $params['default'] : "");
	}
	if(isset($params['encode']) and $params['encode'] === true) {
		$value =($value);
	}
	if(is_string($value)) {
		$value = stripslashes($value);
	}
	$extra = isset($params['attributes']) ? $params['attributes'] : "";
	if(isset($params['type']) &&($params['type'] == 'text' || $params['type'] == 'file')) {
		$type = 'standard';
	} else {
		$type = isset($params['type']) ? $params['type'] : "";
	}
	$tags = "id='$name' $extra";
	if(isset($params['dependency']) && !empty($params['dependency'])) {
		if(call_user_func_array($params['dependency']) != $params['matches']) {
			return "";
		}
	}
	if(isset($params['is_array']) && !empty($params['is_array'])) {
		$name .= '[]';
	}
	jTipsLogger::_log("build form field of type '$type' with name '$name'", 'INFO');
	switch($type) {
		case 'standard' :
			$value_tag = "";
			if(isset($params['type']) && $params['type'] == 'text') {
				$value_tag = "value='$value'";
			}
			//if (isset($params['versions']) && is_array($params['versions'])) {
			//	if (in_array($version, $params['versions'])) {
			//		$item = "<input type='" .$params['type']. "' name='$name' $value_tag $tags />";
			//	}
			//} else {
			$item = "<input type='" . $params['type'] . "' name='$name' $value_tag $tags />";
			//}
			break;
		case 'textarea' :
			$item = "<textarea name='$name' $tags>$value</textarea>";
			break;
		case 'yesno' :
			$item = jTipsHTML :: yesnoRadioList($name, $tags, $value);
			break;
		case 'select' :
			$options = array();
			if(isset($params['options']) && is_array($params['options'])) {
				$set = $params['options']; //[$version];
				foreach($set as $option => $label) {
					$options[] = jTipsHTML :: makeOption($option, $label);
				}
			} else
				if(isset($params['options']) && isset($params['arguments']) && $params['options'] == '__function__') {
					$function_options = call_user_func_array($params['function'], $params['arguments']);
					foreach($function_options as $key => $val) {
						$options[] = jTipsHTML :: makeOption($key, $val);
					}
				} else {
					if(isset($params['options']) && !empty($params['options'])) {
						foreach($params['options'] as $key => $val) {
							$options[] = jTipsHTML :: makeOption($key, $val);
						}
					}
				}
			$selected = array();
			if(is_array($value)) {
				foreach($value as $item) {
					$selected[] = jTipsHTML :: makeOption($item, $item);
				}
			} else {
				$selected[] = jTipsHTML :: makeOption($value, $value);
			}
			$item = jTipsHTML :: selectList($options, $name, $tags, 'value', 'text', $selected);
			break;
		case 'div' :
			if(isset($params['default']) && isset($params['arguments']) && $params['default'] == '__function__') {
				$content = call_user_func_array($params['function'], $params['arguments']);
			} else {
				$content = isset($params['default']) ? $params['default'] : "";
			}
			$item = "<div $tags>$content</div>";
			break;
		case 'submit' :
			$item = "<input type='submit' name='$name' value='" .(isset($params['default']) ? $params['default'] : "") . "' $tags />";
			break;
		case 'button' :
			$item = "<input type='button' name='$name' value='" .(isset($params['default']) ? $params['default'] : "") . "' $tags />";
			break;
		case 'buttons' :
			$buttons = $params['buttons'];
			$item = "";
			foreach($buttons as $button) {
				$item .= "<input type='button' name='" . $button['name'] . "' value='" . $button['default'] . "' " . $button['attributes'] . " />&nbsp;";
			}
			break;
	}
	return $item;
}

/**
 * DEPRECATED
 * - visual licence management moved to dashboard
 *
function getActivationStatusText(&$text, $default="") {
	$return = "<strong><font color='";
	if (validate()) {
		$return .= "green";
		$display = $text;
	} else {
		$return .= "red";
		$display = $default;
	}
	return $return."'>$display</font></strong>";

}


/**
 * DEPRECATED
 *
function getThisVersion() {
	$version = validate();
	if ($version == 'ult') {
		return 'jTips Ultimate';
	} else if ($version == 'pro') {
		return 'jTips Pro';
	}
	return 'jTips Basic (Free)';
}
*/

function getUpdateButton($thisVersion, $latestVersion) {
	global $jLang;
	$disabled = 'disabled';
	$label = $jLang['_ADMIN_CONF_UPDATE_NA'];
	if($thisVersion < $latestVersion and $latestVersion != "N/A") {
		$disabled = '';
		$label = $jLang['_ADMIN_CONF_UPDATE_NOW'];
	}
	return "<input type='button' name='liveupdate' id='liveupdate' value='$label' class='button' $disabled onclick='updatePackage();' />";
}

function makeSelectList($options, $name, $attributes = "", $selected = false) {
	$theOptions = $selectedOption = array();
	foreach($options as $key => $val) {
		$thisOption = jTipsHTML :: makeOption($key, $val);
		if($key == $selected) {
			$selectedOption[] = $thisOption;
		}
		$thisOptions[] = $thisOption;

	}
	if(empty($thisOptions)) {
		$thisOptions[] = jTipsHTML :: makeOption('', '--None--');
		$attributes .= " disabled='disabled'";
	}
	return jTipsHTML :: selectList($thisOptions, $name, $attributes, 'value', 'text', $selectedOption);
}

/**
 * DEPRECATED as of v2.0.14
 */
function getPageNavigation($params) {
	$next = '<a href="#" onclick="goToPage(' . $params['next_page'] . ');">NEXT &raquo;</a>';
	$last = '<a href="#" onclick="goToPage(' .($params['total_pages'] - 1) . ');">END &raquo;|</a>';
	$prev = '<a href="#" onclick="goToPage(' . $params['prev_page'] . ');">&laquo; PREV</a>';
	$first = '<a href="#" onclick="goToPage(0);">|&laquo; START</a>';
	if($params['next_page'] === FALSE) {
		$next = "<span class='disabled_link'>" . strip_tags($next) . "</span>";
		$last = "<span class='disabled_link'>" . strip_tags($last) . "</span>";
	}
	if($params['prev_page'] === FALSE) {
		$prev = "<span class='disabled_link'>" . strip_tags($prev) . "</span>";
		$first = "<span class='disabled_link'>" . strip_tags($first) . "</span>";
	}
	$from =($params['curr_page'] * $params['per_page']) + 1;
	$to = min((($params['curr_page'] + 1) * $params['per_page']), $params['total']);
	$nav = '
		<div class="sectiontableheader pagenavigation">
		' . $first . ' ' . $prev . ' (' . $from . ' - ' . $to . ' of ' . $params['total'] . ') ' . $next . ' ' . $last . '
		</div>
		';
	return $nav;
}

function forceArray($data) {
	if(!is_array($data)) {
		if(is_object($data) && $data->exists()) {
			return array($data);
		} else {
			return array();
		}
	} else {
		return $data;
	}
}

function buildWhere($params = array()) {
	global $database;
	$where_clause = $join_clause = $group_clause = "";
	$order_clause = " ORDER BY ";
	$include_order = $include_group = FALSE;
	foreach($params as $key => $val) {
		if(is_array($val)) {
			switch($val['type']) {
				case 'in' :
					$database->setQuery($val['query']);
					$rows = (array) $database->loadResultArray();
					$in_fields = "'" . implode("', '", $rows) . "'";
					if(!empty($in_fields)) {
						$where_clause .= "$key IN (" . $in_fields . ") AND ";
					}
					break;
				case 'join' :
					$join_clause .= " JOIN `" . $val['join_table'] . "` ON " . $val['lhs_table'] . "." . $val['lhs_key'] . " = " . $val['rhs_table'] . "." . $val['rhs_key'] .(isset($val['supplement']) && !empty($val['supplement']) ? " " . $val['supplement'] : "");
					$join_clause .= " ";
					break;
				case 'left_join' :
					$join_clause .= " LEFT JOIN `" . $val['join_table'] . "` ON " . $val['lhs_table'] . "." . $val['lhs_key'] . " = " . $val['rhs_table'] . "." . $val['rhs_key'] .(isset($val['supplement']) && !empty($val['supplement']) ? " " . $val['supplement'] : "");
					$join_clause .= " ";
					break;
				case 'reference' :
					//$where_clause .= "$key ";
					//to avoid incompatability with older versions of mysql, run subqueries separately
					$database->setQuery($val['query']);
					$res = $database->loadResult();
					$where_clause .= "$key = " .(is_numeric($res) ? "$res" : "'$res'") . " AND ";
					break;
				case 'query' :
					$where_clause .= "$key " . $val['query'] . " AND ";
					break;
				case 'order' :
					if (preg_match('/\./', $val['by'])) {
						$parameters = preg_split('/\./', $val['by']);
						//table name
						$by = $parameters[0]. ".`" .$parameters[1]. "`";
					} else {
						$by = "`" . $val['by'] . "`";
					}
					$order_clause .= $by. " " . $val['direction'] . " ";
					$include_order = TRUE;
					break;
				case 'group' :
					$group_clause = " GROUP BY `" . $val['by'] . "`";
					$include_group = TRUE;
					break;
			}
		} else {
			$where_clause .= "$key = " .(is_numeric($val) ? $val : "'$val'") . " AND ";
		}
	}
	$where_clause = trim(substr($where_clause, 0, -5));
	$query_suffix =(strlen($join_clause) > 0 ? $join_clause : "");
	$query_suffix .=(strlen($where_clause) > 0 ? " WHERE $where_clause" : "");
	if($include_group === TRUE) {
		$query_suffix .= $group_clause;
	}
	if($include_order === TRUE) {
		$query_suffix .= $order_clause;
	}
	jTipsLogger::_log("Where Clause: " . $query_suffix);
	return trim($query_suffix);
}

/**
 * Replaces variables on an object with
 * @param string the text containing the variables
 * @param array
 * @param array
 */
function parseTemplate($template, $variables, $values) {
	jTipsLogger::_log('parsing template', 'INFO');
	$low_values = array();
	foreach($values as $val => $string) {
		if(is_string($string) or is_float($string) or is_float($string)) {
			$low_values[strtolower($val)] = $string;
		}
	}
	foreach($variables as $var) {
		$search = "{" . strtolower($var) . "}";
		if(array_key_exists(strtolower($var), $low_values)) {
			$template = str_replace($search, $low_values[strtolower($var)], $template);
		}
	}
	return stripslashes($template);
}

function importExists() {
	global $mosConfig_absolute_path;
	$file = $mosConfig_absolute_path . '/administrator/components/com_jtips/import.csv';
	return jTipsFileExists($file);
}

function getIndexOf($heading) {
	$row = fetchImportRow(0);
	$index = 0;
	foreach($row as $field) {
		if(cleanString($field) == $heading) {
			return $index;
		}
		$index++;
	}
	return false;
}

function fetchImportRow($row = 0) {
	global $mosConfig_absolute_path;
	if(!importExists()) {
		return array();
	}
	$filename = $mosConfig_absolute_path. '/administrator/components/com_jtips/import.csv';
	if (isJoomla15()) {
		jimport('joomla.filesystem.path');
		JPath::setPermissions($filename);
	}
	$handle = fopen($filename, 'r');
	//how many lines in csv?
	$lines = file($filename);
	if ($row > count($lines)-1) {
		jTipsLogger::_log('trying to read csv data beyond length of file, aborting', 'ERROR');
		return array();
	}
	$index = 0;
	$result = array();
	while(($data = fgetcsv($handle, 5000, ",")) !== false) {
		if ($index == $row) {
			$result = $data;
			break;
		} else {
			$index++;
		}
	}
	fclose($handle);
	return $result;;
}

function parseDataType(& $data, $column, $field) {
	global $database;
	$dataval = $data[$column];
	switch($field['type']) {
		case 'int' :
			if(is_numeric($dataval)) {
				return $dataval;
			} else {
				return 0;
			}
			break;
		case 'double' :
		case 'float' :
			if(is_float($dataval)) {
				return $dataval;
			} else {
				return 0;
			}
			break;
		case 'bool' :
			if($dataval == 1 or preg_match('/yes/i', $dataval)) {
				return 1;
			} else {
				return 0;
			}
			break;
		case 'date' :
			//return toDbDate($dataval);
			return TimeDate::toDatabaseDate($dataval);
			break;
		case 'datetime' :
			//return toDbDateTime($dataval);
			return TimeDate::toDatabaseDateTime($dataval);
			break;
		case 'time' :
			//return toDbTime($dataval);
			return TimeDate::toDatabaseTime($dataval);
			break;
		case 'relate' :
			//Locate an existing record matching the given data value
			//If nothing found create a skeleton record with the supplied data
			//Return key of supplied data in related table.

			$class = $field['related'];
			$obj = new $class($database);
			$related_fields = $field['related_fields'];
			if(count($related_fields) > 1) {
				$values = preg_split('/[\s]+/', $dataval);
			} else {
				$values = array($dataval);
			}
			$max = min(count($values), count($related_fields));
			$params = array();
			for($i = 0; $i < $max; $i++) {
				$params[$related_fields[$i]] = $values[$i];
			}
			if(isset($field['dependency']) and !empty($field['dependency'])) {
				$params[$field['dependency']['key']] = getDependentField($field, $data, $_POST['importFields']);
			}
			//jTipsDebug($_POST);
			//jTipsDebug($dataval);
			//jTipsDebug($field);
			//jTipsDebug($params);
			//die();
			$obj->loadByParams($params);
			$rel_key = $field['related_key'];
			if(isset($obj-> $rel_key) and !empty($rel_key)) {
				return $obj-> $rel_key;
			} else {
				if($field['required']) {
					$obj->bind($params);
					$obj->save();
					return $obj-> $rel_key;
				} else {
					return $field['default'];
				}
			}
			break;
		case 'virtual' :
			continue;
			break;
		case 'text';
		default :
			return mysql_real_escape_string($dataval);
			break;
	}
}

function getDependentField($def, & $data, $fields) {
	global $database;
	$i = 0;
	$dependency = $def['dependency'];
	$params = array();
	foreach($fields as $column => $attribute) {
		if($attribute == $dependency['key']) {
			$params[$dependency['field']] = $data[$i];
			break;
		} else {
			$i++;
		}
	}
	$class = $dependency['related'];
	$obj = new $class($database);
	//jTipsDebug($params);
	$obj->loadByParams($params);
	//jTipsDebug($obj);
	//die();
	$rel_key = $dependency['related_key'];
	if(isset($obj-> $rel_key) and !empty($rel_key)) {
		return $obj-> $rel_key;
	} else {
		$obj->bind($params);
		$obj->save();
		return $obj-> $rel_key;
	}
}

function parseEditField($def) {
	global $jTips;
	if (isset($def['attributes']) and !empty($def['attributes'])) {
		$attribs = array();
		foreach ($def['attributes'] as $name => $value) {
			// BUG 393 - values with quotes cause invalid HTML
			$attribs[] = "$name=\"" .str_replace('"', "'", jTipsStripslashes($value)). "\"";
		}
		$attributes = implode(" ", $attribs);
	} else {
		$attributes = '';
	}

	switch($def['type']) {
		case 'label':
			$html = "<span class='help'>" .$def['attributes']['value']. "</span>";
			break;
		case 'select':
			$html = jTipsHTML::selectList($def['options'], $def['attributes']['name'], $attributes, 'value', 'text', $def['selected']);
			break;
		case 'date':
			//BUG 263 - Date fields in J1.0 must be in YYYY-MM-DD format
			if (!isJoomla15()) {
				$def['attributes']['value'] = TimeDate::format($def['attributes']['value'], '%Y-%m-%d');
				$attributes = preg_replace("/value='[^']*'/i", "value='" .$def['attributes']['value']. "'", $attributes);
			}
			$html = "<input $attributes />&nbsp;<img src='components/com_jtips/images/calendar.png' onclick='return showCalendar(\"{$def['attributes']['name']}\", \"{$jTips['DateFormat']}\");' border='0' alt='...' align='absmiddle' />";
			break;
		case 'bool':
			$html = jTipsHTML::yesnoRadioList($def['attributes']['name'], $attributes, $def['selected']);
			break;
		case 'img':
			$html = "<img $attributes />";
			break;
		case 'textarea':
			$html = "<textarea $attributes>" .jTipsStripslashes($def['text']). "</textarea>";
			break;
		case 'editor':
			jTipsInitEditor();
			jTipsEditorArea($def['attributes']['name'], jTipsStripslashes($def['attributes']['value']), $def['attributes']['name'], '100%', '300px', 100, 25);
			jTipsHTML::keepAlive();
			return '';
			break;
		default:
			if (jTipsGetParam($def['attributes'], 'value')) {
				$def['attributes']['value'] = jTipsStripslashes($def['attributes']['value']);
			}
			$html = "<input $attributes />";
			break;
	}
	return $html;
}

function cleanString($string) {
	$string = trim($string);
	$strip = array('"', "'", "`", "\\", "\\n", "\\t");
	$string = str_replace($strip, "", $string);
	return strip_tags(html_entity_decode($string));
}

/**
 * Check for the existence of the jTips image directory.
 * If it doesn't exist, attempt to create it.
 */
function imageDirCheck() {
	global $mosConfig_absolute_path;
	$imgPath = $mosConfig_absolute_path. '/images/jtips';
	jTipsLogger::_log('checking for jtips image directory', 'ERROR');
	if (isJoomla15()) {
		jimport('joomla.filesystem.folder');
		if (!JFolder::exists($imgPath)) {
			return JFolder::create($imgPath);
		} else {
			return true;
		}
	} else {
		if (!is_dir($imgPath)) {
			return mkdir($imgPath);
		} else {
			return true;
		}
	}
}

function needsUpgrade() {
	global $database, $mosConfig_absolute_path;
	jTipsLogger::_log("checking if major upgrade is required");
	if(!file_exists($mosConfig_absolute_path . '/administrator/components/com_jtips/upgrade')) {
		$table = array('#__jtips_users');
		$fields = $database->getTableFields($table);
		jTipsLogger::_log("comparing column count of users table");
		if(count($fields['#__jtips_users']) > 9) {
			return true;
		} else {
			writeDummyUpgradeFile();
			return needsUpgrade();
		}
	} else {
		if(file_get_contents($mosConfig_absolute_path . '/administrator/components/com_jtips/upgrade') != '100') {
			jTipsLogger::_log("upgrade is required and probably failed");
			return true;
		} else {
			jTipsLogger::_log("no upgrade required");
			return false;
		}
	}
}

function writeDummyupgradeFile() {
	global $mosConfig_absolute_path;
	jTipsLogger::_log("Writing dummy upgrade file to avoid extra database query");
	$dummy = $mosConfig_absolute_path . '/administrator/components/com_jtips/upgrade';
	if (isJoomla15()) {
		jimport('joomla.filesystem.file');
		JFile::write($dummy, 100);
	} else {
		$handle = fopen($dummy, 'w');
		if(!$handle) {
			mosRedirect('index2.php', 'Unable to write to directory. Please fix permissions on administrator/components/com_jtips/');
		}
		fwrite($handle, '100');
		fclose($handle);
	}
}

function filesWritable($getArray=false) {
	global $mosConfig_absolute_path;

	if (isJoomla15()) {
		//files should be accessable from apache or FTP
		return true;
	}
	$directories = array('/administrator/components/com_jtips', '/components/com_jtips');
	clearstatcache();
	//Load all the files first
	$files = array();
	foreach ($directories as $dir) {
		$dirfiles = findAllFiles($mosConfig_absolute_path.$dir, $files, true);
		$files = array_merge($files, $dirfiles);
	}
	jTipsLogger::_log("CHECKING IF WRITABLE!!!");
	//jTipsLogger::_log($files);

	//No check if each is writable
	if (empty($files)) {
		//Should NEVER get in here
		jTipsLogger::_log("Something is very wrong with the system. No jTips files found!!!");
		return false;
	}
	$files = array_unique($files);
	$displayArray = array();
	foreach ($files as $file) {
		if (!is_writable($file) and !preg_match('/\/updates\/.+\.zip/', $file)) {
			$displayArray[] = str_replace($mosConfig_absolute_path.'/', '', $file);
		}
	}
	jTipsLogger::_log($displayArray);
	if (!empty($displayArray)) {
		if ($getArray) {
			return $displayArray;
		}
		return false;
	}
	return true;
}

function getFilePermissions($file) {
	$perms = fileperms($file);

	if (($perms & 0xC000) == 0xC000) {
	    // Socket
	    $info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
	    // Symbolic Link
	    $info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
	    // Regular
	    $info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
	    // Block special
	    $info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
	    // Directory
	    $info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
	    // Character special
	    $info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
	    // FIFO pipe
	    $info = 'p';
	} else {
	    // Unknown
	    $info = 'u';
	}

	// Owner
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
	            (($perms & 0x0800) ? 's' : 'x' ) :
	            (($perms & 0x0800) ? 'S' : '-'));

	// Group
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
	            (($perms & 0x0400) ? 's' : 'x' ) :
	            (($perms & 0x0400) ? 'S' : '-'));

	// World
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
	            (($perms & 0x0200) ? 't' : 'x' ) :
	            (($perms & 0x0200) ? 'T' : '-'));

	return $info;
}

/**
* finds all files in the passed path, but skips select directories
* @param string dir Relative path
* @param array the_array Collections of found files/dirs
* @param bool include_dir True if we want to include directories in the
* returned collection
*/
function findAllFiles($dir, $the_array, $include_dirs = false, $skip_dirs = array(), $echo = false) {
	// check skips
	foreach($skip_dirs as $skipMe) {
		if(strpos(clean_path($dir), $skipMe) !== false) {
			return $the_array;
		}
	}

	$d = dir($dir);

	while($f = $d->read()) {
		if($f == "." || $f == "..") { // skip *nix self/parent
			continue;
		}

		if(is_dir("$dir/$f")) {
			if($include_dirs) { // add the directory if flagged
				$the_array[] = clean_path("$dir/$f");
			}

			// recurse in
			$the_array = findAllFiles("$dir/$f/", $the_array, $include_dirs, $skip_dirs, $echo);
		} else {
			$the_array[] = clean_path("$dir/$f");
		}

	}
	rsort($the_array);
	return $the_array;
}

function clean_path($path) {
	// clean directory/file path with a functional equivalent
	$appendpath = '';
	if(is_windows() && strlen($path) >= 2 && $path[0] . $path[1] == "\\\\") {
		$path = substr($path, 2);
		$appendpath = "\\\\";
	}
	$path = str_replace("\\", "/", $path);
	$path = str_replace("//", "/", $path);
	$path = str_replace("/./", "/", $path);
	return($appendpath . $path);
}

/**
* tries to determine whether the Host machine is a Windows machine
*/
function is_windows() {
	if(preg_match('#WIN#i', PHP_OS)) {
		return true;
	}
	return false;
}

/**
 * Load the language file into the language variable
 *
 * @since 2.1.9
 * @param string The name of the language to load
 * @return array The populated language array
 */
function loadLanguage($language=false) {
	global $jLang, $mosConfig_absolute_path, $mosConfig_lang;
	if (!empty($jLang)) return $jLang;
	if (!$language) {
		$language = $mosConfig_lang;
	}
	include ($mosConfig_absolute_path . '/administrator/components/com_jtips/i18n/master.php');
	if (jTipsFileExists($mosConfig_absolute_path . '/administrator/components/com_jtips/i18n/' .$language. '.php')) {
		$masterLanguage = $jLang;
		include ($mosConfig_absolute_path . '/administrator/components/com_jtips/i18n/' .$language. '.php');
		// BUG 333 - merge the master lanuage array with the edited one
		$jLang = array_merge($masterLanguage, $jLang);
	}
	return $jLang;
}
?>
