<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 09/09/2008
 * 
 * Description: 
 * 
 * 
 */
global $jLang, $database, $jTips;

//This breaks the shortcuts on the dashboard
//jTipsSpoofCheck();

require_once('components/com_jtips/classes/jseason.class.php');

$ids = jTipsGetParam($_REQUEST, 'cid', array());
//Do we have an existing Season?
$id = array_shift($ids);

$jSeason = new jSeason($database);
if (is_numeric($id)) {
	$jSeason->load($id);
} else {
	$jSeason->start_time = $jSeason->end_time = gmdate('Y-m-d H:i:s');
}

$title = $jLang['_ADMIN_SEASON_MANAGER'].": " .($jSeason->exists() ? $jLang['_ADMIN_OTHER_EDIT'] : $jLang['_ADMIN_OTHER_NEW']);

$formData = array(
	'core' => array(
		'legend' => '_ADMIN_SEASON_CORE_FIELDS_FS',
		'fields' => array(
			'id' => array(	
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'id',
						'id' => 'id',
						'value' => $jSeason->id
					)
				)
			),
			'name' => array(	
				'label' => '_ADMIN_SEASON_NAME',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'size' => '50',
						'type' => 'text',
						'class' => 'inputbox',
						'name' => 'name',
						'id' => 'name',
						'value' => $jSeason->name
					)
				),
				//'description' => 'The name of the competition. For example, Rugby World Cup 2009'//this should be a jLang key
			),
			'url' => array(
				'label' => '_ADMIN_SEASON_LINKURL',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'type' => 'text',
						'size' => '50',
						'class' => 'inputbox',
						'name' => 'url',
						'id' => 'url',
						'value' => $jSeason->url
					)
				)
			),
			'tip_display' => array(
				'label' => '_ADMIN_SEASON_TIPSDISPLAY',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'tip_display',
						'id' => 'tip_display'
					),
					'options' => array(
						jTipsHTML::makeOption(0, $jLang['_ADMIN_SEASON_NAMEONLY']),
						jTipsHTML::makeOption(1, $jLang['_ADMIN_SEASON_LOGOONLY']),
						jTipsHTML::makeOption(2, $jLang['_ADMIN_SEASON_NAMELOGO'])
					),
					'selected' => $jSeason->tip_display
				)
			),
			'tips_layout' => array(
				'label' => '_ADMIN_CONF_TIPS_PANEL_LAYOUT',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'tips_layout',
						'id' => 'tips_layout'
					),
					'options' => array(
						jTipsHTML::makeOption('home', $jLang['_ADMIN_CONF_HOME']),
						jTipsHTML::makeOption('away', $jLang['_ADMIN_CONF_AWAY'])
					),
					'selected' => $jSeason->tips_layout
				),
				'description' => '_ADMIN_CONF_TIPS_PANEL_LAYOUT_DEF'
			),
			'start_time' => array(
				'label' => '_ADMIN_SEASON_START_DATE',
				'field' => array(
					'type' => 'date',
					'attributes' => array(
						'name' => 'start_time',
						'id' => 'start_time',
						'value' => TimeDate::toDisplayDate($jSeason->start_time),
						'class' => 'inputbox',
						'type' => 'text'
					)
				),
				'description' => '_ADMIN_SEASON_START_DATE_DEF'
			),
			'end_time' => array(
				'label' => '_ADMIN_SEASON_END_DATE',
				'field' => array(
					'type' => 'date',
					'attributes' => array(
						'name' => 'end_time',
						'id' => 'end_time',
						'value' => TimeDate::toDisplayDate($jSeason->end_time),
						'class' => 'inputbox',
						'type' => 'text'
					)
				),
				'description' => '_ADMIN_SEASON_END_DATE_DEF'
			),
			'rounds' => array(
				'label' => '_ADMIN_SEASON_ROUNDS',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'rounds',
						'id' => 'rounds',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->rounds
					)
				)
			),
			/* @deprecated
			 * 'games_per_round' => array(
				'label' => '_ADMIN_SEASON_GPR',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'games_per_round',
						'id' => 'games_per_round',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->games_per_round
					)
				)
			),*/
			'disable_tips' => array(
				'label' => '_ADMIN_SEASON_DISABLE_TIPS',
				'description' => '_ADMIN_SEASON_DISABLE_TIPS_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'disable_tips',
						'id' => 'disable_tips'
					),
					'selected' => $jSeason->disable_tips
				)
			),
			'scorer_id' => array(
				'label' => '_ADMIN_SEASON_SCORER',
				'description' => '_ADMIN_SEASON_SCORER_DEF',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'id' => 'scorer_id',
						'name' => 'scorer_id',
						'class' => 'inputbox'
					),
					'selected' => $jSeason->scorer_id,
					'options' => jTipsGetAvailableScorers()
				)
			),
			'description' => array(
				'label' => '_ADMIN_SEASON_DESCR',
				'field' => array(
					'type' => 'editor',
					'attributes' => array(
						'name' => 'description',
						'id' => 'description',
						'rows' => 10,
						'cols' => 50,
						'value' => jTipsStripslashes($jSeason->description)
					)
				)
			)
		)
	)
);

$formData['image'] = array(
	'legend' => '_ADMIN_SEASON_LOGO_PATH',
	'fields' => array(
		'imageupload' => array(
			'label' => '_ADMIN_SEASON_LOGO_PATH',
			'field' => array(
				'type' => 'file',
				'attributes' => array(
					'size' => '50',
					'type' => 'file',
					'class' => 'inputbox',
					'name' => 'image',
					'id' => 'image',
					'value' => $jSeason->image
				)
			)
		)
	)
);

if ($jSeason->image) {
	$formData['image']['fields']['current_image'] = array(
		'label' => '_ADMIN_TEAM_CURRENT_LOGO',
		'field' => array(
			'type' => 'img',
			'attributes' => array(
				'alt' => 'Logo',
				'id' => 'current_logo',
				'src' => $mosConfig_live_site. '/' .$jSeason->image
			)
		)
	);
	$formData['image']['fields']['remove_image'] = array(
		'label' => '_ADMIN_TEAM_REMOVE_LOGO',
		'field' => array(
			'type' => 'checkbox',
			'attributes' => array(
				'class' => 'inputbox',
				'name' => 'remove_image',
				'value' => '1',
				'type' => 'checkbox',
				'onClick' => 'if (this.checked){$("image").disabled=true;}else{$("image").disabled=false}'
			)
		),
		'description' => '_ADMIN_TEAM_REMOVE_LOGO_DEF'
	);
}

$formData['team_points'] = array(
		'legend' => '_ADMIN_SEASON_TEAM_POINTS_FS',
		'fields' => array(
			'team_win' => array(
				'label' => '_ADMIN_SEASON_TWP',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'team_win',
						'id' => 'team_win',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->team_win
					)
				),
				'description' => '_ADMIN_SEASON_TWP_DEF'
			),
			'team_draw' => array(
				'label' => '_ADMIN_SEASON_TDP',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'team_draw',
						'id' => 'team_draw',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->team_draw
					)
				),
				'description' => '_ADMIN_SEASON_TDP_DEF'
			),
			'team_lose' => array(
				'label' => '_ADMIN_SEASON_TLP',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'team_lose',
						'id' => 'team_lose',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->team_lose
					)
				),
				'description' => '_ADMIN_SEASON_TLP_DEF'
			),
			'team_bye' => array(
				'label' => '_ADMIN_SEASON_TBP',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'team_bye',
						'id' => 'team_bye',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->team_bye
					)
				),
				'description' => '_ADMIN_SEASON_TBP_DEF'
			),
			'team_bonus' => array(
				'label' => '_ADMIN_SEASON_BONUS_TEAM',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'team_bonus',
						'id' => 'team_bonus',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->team_bonus
					)
				)
			),
			'team_starts' => array(
				'label' => '_ADMIN_SEASON_ETS',
				'description' => '_ADMIN_SEASON_ETS_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'team_starts',
						'id' => 'team_starts'
					),
					'selected' => $jSeason->team_starts
				)
			)
		)
	);

$formData['points_configuration'] = array(
		'legend' => '_ADMIN_SEASON_TIPS_CONFIG_FS',
		'fields' => array(
			'pick_score' => array(
				'label' => '_ADMIN_SEASON_EPTS',
				'description' => '_ADMIN_SEASON_EPTS_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'pick_score',
						'id' => 'pick_score'
					),
					'selected' => $jSeason->pick_score
				)
			),
			'pick_margin' => array(
				'label' => '_ADMIN_SEASON_EPTM',
				'description' => '_ADMIN_SEASON_EPTM_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'pick_margin',
						'id' => 'pick_margin'
					),
					'selected' => $jSeason->pick_margin
				)
			),
			'pick_draw' => array(
				'label' => '_ADMIN_SEASON_EPTD',
				'description' => '_ADMIN_SEASON_EPTD_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'pick_draw',
						'id' => 'pick_draw'
					),
					'selected' => $jSeason->pick_draw
				)
			),
			'pick_bonus' => array(
				'label' => '_ADMIN_SEASON_EPTB',
				'description' => '_ADMIN_SEASON_EPTB_DEF',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'pick_bonus',
						'id' => 'pick_bonus'
					),
					'selected' => $jSeason->pick_bonus,
					'options' => array(
						jTipsHTML::makeOption(0, $jLang['_ADMIN_SEASON_EPTB_DIS']),
						jTipsHTML::makeOption(1, $jLang['_ADMIN_SEASON_EPTB_SIN']),
						jTipsHTML::makeOption(2, $jLang['_ADMIN_SEASON_EPTB_BOT'])
					)
				)
			),
			'game_times' => array(
				'label' => '_ADMIN_SEASON_GAME_TIMES',
				'description' => '_ADMIN_SEASON_GAME_TIMES_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'game_times',
						'id' => 'game_times'
					),
					'selected' => $jSeason->game_times
				)
			),
			'tough_score' => array(//BUG 248 - Tough Game bonus points
				'label' => '_ADMIN_SEASON_TOUGH_SCORE',
				'description' => '_ADMIN_SEASON_TOUGH_SCORE_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'tough_score',
						'id' => 'tough_score'
					),
					'selected' => $jSeason->tough_score
				)
			)
		)
	);

$formData['user_points'] = array(
		'legend' => '_ADMIN_SEASON_USER_POINTS_FS',
		'fields' => array(
			'user_correct' => array(
				'label' => '_ADMIN_SEASON_UCORR',
				'description' => '_ADMIN_SEASON_UCORR_DEF',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_correct',
						'id' => 'user_correct',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_correct
					)
				)
			),
			'user_draw' => array(
				'label' => '_ADMIN_SEASON_UDRAW',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_draw',
						'id' => 'user_draw',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_draw
					)
				)
			),
			'user_none' => array(
				'label' => '_ADMIN_SEASON_UNONE',
				'description' => '_ADMIN_SEASON_UNONE_DEF',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_none',
						'id' => 'user_none',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_none
					)
				)
			),
			'precision_score' => array(
				'label' => '_ADMIN_SEASON_PRECISION',
				'description' => '_ADMIN_SEASON_PRECISION_DEF',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'name' => 'precision_score',
						'id' => 'precision_score'
					),
					'selected' => $jSeason->precision_score
				)
			),
			'user_bonus' => array(
				'label' => '_ADMIN_SEASON_UPERF',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_bonus',
						'id' => 'user_bonus',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_bonus
					)
				)
			),
			'user_pick_score' => array(
				'label' => '_ADMIN_SEASON_USCOR',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_pick_score',
						'id' => 'user_pick_score',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_pick_score
					)
				)
			),
			'user_pick_margin' => array(
				'label' => '_ADMIN_SEASON_UMARG',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_pick_margin',
						'id' => 'user_pick_margin',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_pick_margin
					)
				)
			),
			'user_pick_bonus' => array(
				'label' => '_ADMIN_SEASON_UBONU',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'class' => 'inputbox',
						'name' => 'user_pick_bonus',
						'id' => 'user_pick_bonus',
						'style' => 'text-align:center',
						'size' => 10,
						'value' => $jSeason->user_pick_bonus
					)
				)
			),
			'default_points' => array(
				'label' => '_ADMIN_SEASON_DEFAULT_POINTS',
				'description' => '_ADMIN_SEASON_DEFAULT_POINTS_DEF',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'name' => 'default_points',
						'id' => 'default_points',
						'class' => 'inputbox',
					),
					'options' => array(
						jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']),
						jTipsHTML::makeOption('low', $jLang['_ADMIN_SEASON_DEFAULT_POINTS_LOW']),
						jTipsHTML::makeOption('avg', $jLang['_ADMIN_SEASON_DEFAULT_POINTS_AVG'])
					),
					'selected' => $jSeason->default_points
				)
			)
		)
	);

jTipsAdminDisplay::EditView($title, $formData, 'season');
?>