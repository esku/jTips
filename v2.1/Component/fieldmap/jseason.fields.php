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
		'label' => '_ADMIN_JSEASON_ID',
		'import' => false
	),
	'name' => array (
		'type' => 'text',
		'label' => '_ADMIN_JSEASON_NAME',
		'import' => true,
		'default' => '',
		'required' => true
	),
	'description' => array (
		'type' => 'text',
		'label' => '_ADMIN_JSEASON_DESCRIPTION',
		'import' => true,
		'default' => null,
		'required' => false
	),
	'start_time' => array (
		'type' => 'date',
		'label' => '_ADMIN_JSEASON_START',
		'import' => true,
		'default' => null,
		'required' => true
	),
	'end_time' => array (
		'type' => 'date',
		'label' => '_ADMIN_JSEASON_END',
		'import' => true,
		'default' => null,
		'required' => true
	),
	'rounds' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_ROUNDS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'games_per_round' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_GAME_PER_ROUND',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'pick_score' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JSEASON_PICK_SCORE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'pick_margin' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JSEASON_PICK_MARGIN',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'pick_bonus' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JSEASON_PICK_BONUS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'pick_draw' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JSEASON_PICK_DRAW',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'team_bonus' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_TEAM_BONUS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'team_win' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_TEAM_WIN',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'team_lose' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_TEAM_LOSE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'team_draw' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_TEAM_DRAW',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'team_bye' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_TEAM_BYE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_correct' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_CORRECT',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_draw' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_DRAW',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_bonus' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_BONUS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_none' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_NONE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_pick_score' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_PICK_SCORE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_pick_margin' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_PICK_MARGIN',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'user_pick_bonus' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_USER_PICK_BONUS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'url' => array (
		'type' => 'text',
		'label' => '_ADMIN_JSEASON_URL',
		'import' => true,
		'default' => null,
		'required' => false
	),
	'image' => array (
		'type' => 'text',
		'label' => '_ADMIN_JSEASON_IMAGE',
		'import' => false,
		'default' => null,
		'required' => false
	),
	'precision_score' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JSEASON_PRECISION_SCORE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'tip_display' => array (
		'type' => 'int',
		'label' => '_ADMIN_JSEASON_TIP_DISPLAY',
		'import' => true,
		'default' => 2,
		'required' => false
	),
	'updated' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JSEASON_UPDATED',
		'import' => false,
		'default' => null,
		'required' => false
	),
    'team_starts' => array (
        'type' => 'bool',
        'label' => '_ADMIN_JSEASON_TEAM_STARTS',
        'import' => true,
        'default' => 0,
        'required' => false
    ),
);
?>