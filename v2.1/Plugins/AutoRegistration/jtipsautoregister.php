<?php
defined('_JEXEC') or die('Restricted access');
/**
 * Author:			jTips
 * Contributors:	Jeremy Roberts
 * Created on:		20/01/2009
 * 
 * When a new user registers on the website, they will be 
 * automatically added to the configured competition.
 */

class plgUserJtipsAutoRegister extends JPlugin {
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param       array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function plgUserJtipsAutoRegister(& $subject, $config) {
        parent :: __construct($subject, $config);
    }

    /**
     * Method is called after user data is stored in the database
     *
     * @param       array           holds the new user data
     * @param       boolean         true if a new user is stored
     * @param       boolean         true if user was succesfully stored in the database
     * @param       string          message
     */
    function onAfterStoreUser($user, $isnew, $success, $msg) {
        global $mainframe, $database, $mosConfig_absolute_path;
        if (!JFolder :: exists(JPATH_ADMINISTRATOR . '/components/com_jtips')) {
            return JText :: _('jTips is not currently installed');
        }
        if (!$success) {
            return JText :: _('Failed to save user. Aborted');
        }

        //Make sure this variable is available for use in included classes
        if (!$mosConfig_absolute_path) {
            $mosConfig_absolute_path = JPATH_SITE;
        }

        //now we can load the right jTips classes
        require_once (JPATH_ADMINISTRATOR . '/components/com_jtips/utils/logger.php');
        require_once (JPATH_ADMINISTRATOR . '/components/com_jtips/utils/compat.php');
        require_once (JPATH_ADMINISTRATOR . '/components/com_jtips/utils/timedate.php');
        require_once (JPATH_ADMINISTRATOR . '/components/com_jtips/utils/functions.inc.php');
        require_once (JPATH_ADMINISTRATOR . '/components/com_jtips/classes/juser.class.php');

        // convert the user parameters passed to the event
        // to a format the external application
        $args = array ();
        $args['id'] = $user['id'];
        $args['username'] = $user['username'];
        $args['email'] = $user['email'];
        $args['fullname'] = $user['name'];
        $args['password'] = $user['password'];
        if ($isnew) {
            //which season to subscribe to
            if ($this->params->get('auto_all')) {
	            // add to all current competitions
	            $query = "SELECT id FROM #__jtips_seasons WHERE start_time <= DATE(NOW()) AND end_time > DATE(NOW())";
	            $db =& JFactory::getDBO();
	            $db->setQuery($query);
	            $list = $db->loadResultArray();
	            foreach ($list as $id) {
	            	$jTipsUser = new jTipsUser($database);
		            $jTipsUser->user_id = $args['id'];
		            $jTipsUser->season_id = $id;//$this->params->get('season_id');
		            $jTipsUser->status = $this->params->get('approve');
		            $jTipsUser->save();
	            }
	            return;
            }
            $jTipsUser = new jTipsUser($database);
            $jTipsUser->user_id = $args['id'];
            $jTipsUser->season_id = $this->params->get('season_id');
            $jTipsUser->status = $this->params->get('approve');
            $jTipsUser->paid = 0; //Mark as unpaid by default
            if ($jTipsUser->save()) {
                return JText :: _('User added to competition!');
            } else {
                return JText :: _('Error adding user to competition: ' . $jTipsUser->_db->getErrorMsg());
            }
      }
   }
}
?>
