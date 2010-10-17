<?php
defined('_JEXEC') or die('Restricted Access');

/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 *
 * @since 1.0 - 30/04/2009
 * @version 1.0.0
 * @package jTips
 *
 * Description: Helper functions to build the data set and additional details
 */

class modJTipsOverallLadderModule {
	/**
	 * Get a list of jTipsUser objects for competitions in progress
	 *
	 * @param object The parameters for the module
	 * @return array A list of objects
	 */
	function getList(&$params) {
		$db =& JFactory::getDBO();

		$users = array();

		// get a list of current seasons
		$query = "SELECT id FROM #__jtips_seasons WHERE start_time <= NOW() AND end_time >= NOW()";
		$db->setQuery($query);
		$list = $db->loadResultArray();
		if (empty($list)) return array();

		$season_ids = implode("', '", $list);

		$query = "SELECT #__jtips_users.id, #__users.id AS user_id, #__users." .$params->get('display'). " AS display,
					SUM(`" .$params->get('field'). "`) AS score FROM #__jtips_users
					JOIN #__jtips_history ON #__jtips_users.id = #__jtips_history.user_id
					JOIN #__users ON #__jtips_users.user_id = #__users.id
					WHERE season_id IN ('" .$season_ids. "')
					GROUP BY #__jtips_users.user_id
					ORDER BY score " .$params->get('order');
		$db->setQuery($query, 0, $params->get('limit'));
		$users = $db->loadObjectList();
		if (empty($users)) return array();

		return $users;
	}

	/**
	 * Parse the user's name and include a link to profile if required.
	 * 
	 * @param $user
	 * @param $params
	 * @return unknown_type
	 */
	function getName(&$user, &$params) {
		if ($params->get('link')) {
			$link = modJTipsOverallLadderModule::getProfileLink($user->user_id, $params->get('link'));
			if (!empty($link)) {
				return "<a href='$link' title='{$user->display}'>{$user->display}</a>";
			}
		}
		return $user->display;
	}

	/**
	 * Get the parsed URL to the user's profile page.
	 * 
	 * @param $userid
	 * @param $app
	 * @return unknown_type
	 */
	function getProfileLink($userid, $app) {
		if ($app == 'JomSocial') {
			$jspath = JPATH_BASE.DS.'components'.DS.'com_community';
			$jscore = $jspath.DS.'libraries'.DS.'core.php';
			jimport('joomla.filesystem.file');
			if (JFile::exists($jscore)) {
				include_once($jscore);
				// Get CUser object
				return CRoute::_('index.php?option=com_community&view=profile&userid='.$userid);
			}
		} else if ($app == 'CommunityBuilder') {
			// check CB is installed
			jimport('joomla.filesystem.folder');
			if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comprofiler')) {
				return JRoute::_("index.php?option=com_comprofiler&task=userProfile&user=" .$userid);
			}
		}
		return '';
	}

	/**
	 * Return the HTML img tag with the user's profile avatar
	 * 
	 * @param $userid
	 * @param $app
	 * @return unknown_type
	 */
	function getProfileImage($userid, $app) {
		if ($app == 'JomSocial') {
			$jspath = JPATH_BASE.DS.'components'.DS.'com_community';
			$jscore = $jspath.DS.'libraries'.DS.'core.php';
			jimport('joomla.filesystem.file');
			if (JFile::exists($jscore)) {
				include_once($jscore);
				$user =& CFactory::getUser($userid);
				$avatarUrl = $user->getThumbAvatar();
				return "<img src='$avatarUrl' />";
			}
		} else if ($app == 'CommunityBuilder') {
			// check CB is installed
			jimport('joomla.filesystem.folder');
			$cbpath = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_comprofiler';
			if (JFolder::exists($cbpath)) {
				global $ueConfig;
				// get CB version
				include( $cbpath . DS . 'ue_config.php' );
				if (preg_match('/\b1\.1/', $ueConfig['version'])) {
					// version 1.1
					require_once($cbpath.DS.'plugin.class.php');
					require_once($cbpath.DS.'comprofiler.class.php');
					// get the user data
					$query = "SELECT * FROM #__comprofiler WHERE id = $userid";
					$db =& JFactory::getDBO();
					$db->setQuery($query, 0, 1);
					$user = $database->loadObject();
					$path = getFieldValue('image', $user->avatar, $user);
					return $path;
				} else if (preg_match('/\b1\.2/', $ueConfig['version'])) {
					// version 1.2
					include_once($cbpath.DS.'ue_config.php');
					include_once($cbpath.DS.'plugin.foundation.php');
					cbimport('cb.database');
					cbimport('language.front');
					$cbUser =& CBUser::getInstance($userid);
					$path = $cbUser->avatarFilePath( 2 );
					return "<img src='$path' />";
				}
			}
		}
		return "&nbsp;";
	}
}
