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
		'label' => '_ADMIN_JROUND_ID',
		'import' => false
	),
	'round' => array (
		'type' => 'int',
		'label' => '_ADMIN_JROUND_ROUND',
		'import' => true,
		'default' => NULL,
		'required' => true
	),
	'season_id' => array (
		'type' => 'relate',
		'label' => '_ADMIN_JROUND_SEASON_ID',
		'import' => true,
		'default' => NULL,
		'required' => false,
		'related' => 'jSeason',
		'related_key' => 'id',
		'related_fields' => array('name')
	),
	'start_time' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JROUND_START_TIME',
		'import' => true,
		'default' => NULL,
		'required' => true
	),
	'end_time' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JROUND_END_TIME',
		'import' => true,
		'default' => NULL,
		'required' => true
	),
	'scored' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JROUND_SCORED',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'updated' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JROUND_UPDATED',
		'import' => false,
		'default' => NULL,
		'required' => false
	)
);
?>