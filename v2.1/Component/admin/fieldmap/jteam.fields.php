<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */

$fields = array (
	'id' => array (
		'type' => 'index',
		'label' => '_ADMIN_JTEAM_ID',
		'import' => false
	),
	'season_id' => array (
		'type' => 'relate',
		'label' => '_ADMIN_JTEAM_SEASON_ID',
		'import' => true,
		'default' => NULL,
		'required' => false,
		'related' => 'jSeason',
		'related_key' => 'id',
		'related_fields' => array('name')
	),
	'name' => array (
		'type' => 'text',
		'label' => '_ADMIN_JTEAM_NAME',
		'import' => true,
		'default' => '',
		'required' => true
	),
	'location' => array (
		'type' => 'text',
		'label' => '_ADMIN_JTEAM_LOCATION',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'about' => array (
		'type' => 'text',
		'label' => '_ADMIN_JTEAM_ABOUT',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'logo' => array (
		'type' => 'text',
		'label' => '_ADMIN_JTEAM_LOGO',
		'import' => false,
		'default' => NULL,
		'required' => false
	),
	'url' => array (
		'type' => 'text',
		'label' => '_ADMIN_JTEAM_URL',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	/*'wins' => array (
		'type' => 'int',
		'label' => '_ADMIN_JTEAM_WINS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'draws' => array (
		'type' => 'int',
		'label' => '_ADMIN_JTEAM_DRAWS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'losses' => array (
		'type' => 'int',
		'label' => '_ADMIN_JTEAM_LOSSES',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'points_for' => array (
		'type' => 'int',
		'label' => '_ADMIN_JTEAM_POINTS_FOR',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'points_against' => array (
		'type' => 'int',
		'label' => '_ADMIN_JTEAM_POINTS_AGAINST',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'points' => array (
		'type' => 'int',
		'label' => '_ADMIN_JTEAM_POINTS',
		'import' => true,
		'default' => 0,
		'required' => false
	),*/
	'updated' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JTEAM_UPDATED',
		'import' => false,
		'default' => NULL,
		'required' => false
	)
);
?>