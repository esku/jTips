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
		'label' => '_ADMIN_JGAME_ID',
		'import' => false
	),
	'round_id' => array (
		'type' => 'relate',
		'name' => 'id',
		'label' => '_ADMIN_JGAME_ROUND_ID',
		'import' => true,
		'default' => NULL,
		'required' => true,
		'related' => 'jRound',
		'related_key' => 'id',
		'related_fields' => array('round'),
		'dependency' => array(
			'key' => 'season_id',
			'related' => 'jSeason',
			'related_key' => 'id',
			'field' => 'name'
		)
	),
	'season_id' => array(
		'type' => 'virtual',
		'label' => '_ADMIN_JGAME_SEASON_ID',
		'key' => 'season_id',
		'import' => true,
		'related' => 'jSeason',
		'related_key' => 'id',
		'related_fields' => array('name')
	),
	'home_id' => array (
		'type' => 'relate',
		'label' => '_ADMIN_JGAME_HOME_ID',
		'import' => true,
		'default' => NULL,
		'required' => false,
		'related' => 'jTeam',
		'related_key' => 'id',
		'related_fields' => array(
			'location',
			'name'
		)
	),
	'away_id' => array (
		'type' => 'relate',
		'label' => '_ADMIN_JGAME_AWAY_ID',
		'import' => true,
		'default' => NULL,
		'required' => false,
		'related' => 'jTeam',
		'related_key' => 'id',
		'related_fields' => array(
			'location',
			'name'
		)
	),
	'position' => array (
		'type' => 'int',
		'label' => '_ADMIN_JGAME_POSITION',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'winner_id' => array (
		'type' => 'relate',
		'label' => '_ADMIN_JGAME_WINNER_ID',
		'import' => true,
		'default' => NULL,
		'required' => false,
		'related' => 'jTeam',
		'related_key' => 'id',
		'related_fields' => array(
			'location',
			'name'
		)
	),
	'draw' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JGAME_DRAW',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'home_score' => array (
		'type' => 'int',
		'label' => '_ADMIN_JGAME_HOME_SCORE',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'away_score' => array (
		'type' => 'int',
		'label' => '_ADMIN_JGAME_AWAY_SCORE',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'bonus_id' => array (
		'type' => 'relate',
		'label' => '_ADMIN_JGAME_BONUS_ID',
		'import' => true,
		'default' => NULL,
		'required' => false,
		'related' => 'jTeam',
		'related_key' => 'id',
		'related_fields' => array(
			'location',
			'name'
		)
	),
	'has_bonus' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JGAME_HAS_BONUS',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'has_margin' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JGAME_HAS_MARGIN',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'has_score' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JGAME_HAS_SCORE',
		'import' => true,
		'default' => NULL,
		'required' => false
	),
	'updated' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JGAME_UPDATED',
		'import' => false,
		'default' => NULL,
		'required' => false
	),
    'home_start' => array (
        'type' => 'int',
        'label' => '_ADMIN_JGAME_HOME_START',
        'import' => true,
        'default' => NULL,
        'required' => false
    ),
    'away_start' => array (
        'type' => 'int',
        'label' => '_ADMIN_JGAME_AWAY_START',
        'import' => true,
        'default' => NULL,
        'required' => false
    )
);
?>