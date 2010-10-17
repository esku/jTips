<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
$configuration = array(
	'general' => array(
		/*'SubDirectory' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_LOCATION',
			'definition' => '_ADMIN_CONF_LOCATION_DEF'
		),*/
		'LoadMooTools' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_LOAD_MOOTOOLS',
			'definition' => '_ADMIN_CONF_LOAD_MOOTOOLS_DEF'
		),
		/*'LoadCustomCSS' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_LOAD_CUSTOMCSS',
			'definition' => '_ADMIN_CONF_LOAD_CUSTOMCSS_DEF'
		),*/
		'AllowReg' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_ALLOWREG',
			'definition' => '_ADMIN_CONF_ALLOWREG_DEF'
		),
		'NoRegMessage' => array(
			'type' => 'textarea',
			'encode' => true,
			'default' => '',
			'label' => '_ADMIN_CONF_RES_DIS',
			'definition' => '_ADMIN_CONF_RES_DIS_DEF'
		),
		'AutoReg' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_AUTOAPP',
			'definition' => '_ADMIN_CONF_AUTOAPP_DEF'
		),
		'RegisterAllCompetitions' => array(
			'type' => 'yesno',
			'default' => 0,
			'encode' => true,
			'label' => '_ADMIN_CONF_REGISTERALLCOMP',
			'definition' => '_ADMIN_CONF_REGISTERALLCOMP_DEF'
		),
		'PostDashboardText' => array(
			'type' => 'textarea',
			'default' => '',
			'label' => '_ADMIN_CONF_POSTDASHTEXT',
			'definition' => '_ADMIN_CONF_POSTDASHTEXT_DEF'
		),
		'DoubleUp' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_DOUBLE',
			'definition' => '_ADMIN_CONF_DOUBLE_DEF'
		),
		'EnableComments' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_COMMENTS',
			'definition' => '_ADMIN_CONF_COMMENTSE_DEF'
		),
		'EnableCommentFilter' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_COMMENTSFILTER',
			'definition' => '_ADMIN_CONF_COMMENTSFILETER_DEF'
		),
		/*'ImageOutputFormat' => array(
			'type' => 'select',
			'options' => array(
				'THUMB_PNG' => 'PNG',
				'THUMB_GIF' => 'GIF',
				'THUMB_JPG' => 'JPG'
			),
			'default' => 'THUMB_PNG',
			'label' => '_ADMIN_CONF_IMAGEOUTPUTFORMAT',
			'definition' => '_ADMIN_CONF_IMAGEOUTPUTFORMAT_DEF'
		),*/
		'Payments' => array(
			'type' => 'select',
			'options' => array(
				'0' => $jLang['_ADMIN_CONF_NONE'],
				'manual' => $jLang['_AMDIN_CONF_MANUAL_OPT'],
				'paypal' => $jLang['_AMDIN_CONF_PAYPAL_OPT']
			),
			'default' => '0',
			'label' => '_ADMIN_CONF_EP',
			'definition' => '_ADMIN_CONF_EP_DEF'
		),
		/*'CBIntegration' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_CBI',
			'definition' => '_ADMIN_CONF_CBI_DEF'
		),*/
		'DebugLevel' => array(
			'type' => 'select',
			'attributes' => 'multiple size="4"',
			'is_array' => true,
			'options' => array(
				'' => $jLang['_ADMIN_CONF_NONE'],
				'INFO' => $jLang['_ADMIN_CONF_LOG_INFO'],
				'DEBUG' => $jLang['_ADMIN_CONF_LOG_DEBUG'],
				'ERROR' => $jLang['_ADMIN_CONF_LOG_ERROR']
			),
			'label' => '_ADMIN_CONF_DEBUG_LEVEL',
			'definition' => '_ADMIN_CONF_DEBUG_LEVEL_DEF',
			'default' => array(
				'INFO', 'DEBUG', 'ERROR'
			)
		),
		'AutoUpgrade' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_AUTO_UPGRADE',
			'definition' => '_ADMIN_CONF_AUTO_UPGRADE_DEF'
		)
	),
	'display' => array(
		'Title' => array(
			'type' => 'text',
			'default' => 'jTips - The Ultimate Tipping System for Joomla!',
			'label' => '_ADMIN_CONF_COM_TITLE',
			'definition' => '_ADMIN_CONF_COM_TITLE_DEF'
		),
		'LogoPos' => array(
			'type' => 'select',
			'options' => array (
				'center' => $jLang['_ADMIN_CONF_LOGO_CENTRE'],
				'left' => $jLang['_ADMIN_CONF_LOGO_LEFT'],
				'right' => $jLang['_ADMIN_CONF_LOGO_RIGHT']
			),
			'label' => '_ADMIN_CONF_LOGO_POS',
			'definition' => '_ADMIN_CONF_LOGO_POS_DEF'
		),
		'DisableMenu' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_DISABLE_MENU',
			'definition' => '_ADMIN_CONF_DISABLE_MENU_DEF'
		),
		'Menu[Dashboard]' => array(
			'type' => 'text',
			'default' => 'Dashboard',
			'label' => '_ADMIN_CONF_DASH_TITLE',
			'definition' => '_ADMIN_CONF_DASH_TITLE_DEF'
		),
		'Menu[CompetitionLadder]' => array(
			'type' => 'text',
			'default' => 'Competition Ladder',
			'label' => '_ADMIN_CONF_COMP_TITLE',
			'definition' => '_ADMIN_CONF_COMP_TITLE_DEF'
		),
		'Menu[TeamLadder]' => array(
			'type' => 'text',
			'default' => 'Team Ladder',
			'label' => '_ADMIN_CONF_TEAM_TITLE',
			'definition' => '_ADMIN_CONF_TEAM_TITLE_DEF'
		),
		'Menu[Tips]' => array(
			'type' => 'text',
			'default' => 'Tips Panel',
			'label' => '_ADMIN_CONF_TIPS_TITLE',
			'definition' => '_ADMIN_CONF_TIPS_TITLE_DEF'
		),
		'HideTeamSelect' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_HIDE_TEAM_SELECT',
			'definition' => '_ADMIN_CONF_HIDE_TEAM_SELECT_DEF'
		),
		'EnableShowTips' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_SHOWTIPS',
			'definition' => '_ADMIN_CONF_SHOWTIPS_DEF'
		),
		'ShowTipsStats' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_SHOWTIPSSTATS',
			'definition' => '_ADMIN_CONF_SHOWTIPSSTATS_DEF'
		),
		'ShowTipsAccess' => array(
			'type' => 'select',
			'options' => array(
				'processed' => $jLang['_ADMIN_CONF_SHOWTIPS_PROCESSED'],
				'tipped' => $jLang['_ADMIN_CONF_SHOWTIPS_TIPPED'],
				'any' => $jLang['_ADMIN_CONF_SHOWTIPS_ANY'],
				'inprogress' => $jLang['_ADMIN_CONF_SHOWTIPS_INPROGRESS']
			),
			'default' => 'processed',
			'label' => '_ADMIN_CONF_SHOWTIPS_ACCESS',
			'definition' => '_ADMIN_CONF_SHOWTIPS_ACCESS_DEF'
		),
		'ShowTipsWidth' => array(
			'type' => 'text',
			'default' => 640,
			'label' => '_ADMIN_CONF_SHOWTIPS_WIDTH',
			'definition' => '_ADMIN_CONF_SHOWTIPS_WIDTH_DEF'
		),
		'ShowTipsHeight' => array(
			'type' => 'text',
			'default' => 480,
			'label' => '_ADMIN_CONF_SHOWTIPS_HEIGHT',
			'definition' => '_ADMIN_CONF_SHOWTIPS_HEIGHT_DEF'
		),
		'ShowTipsPadding' => array(
			'type' => 'yesno',
			'default' => true,
			'label' => '_ADMIN_CONF_SHOWTIPS_PADDING',
			'definition' => '_ADMIN_CONF_SHOWTIPS_PADDING_DEF'
		),
		'TeamLadderPopup' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_TEAM_LADDER_POPUP',
			'definition' => '_ADMIN_CONF_TEAM_LADDER_POPUP_DEF'
		),
		'TipLockout' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_TIP_LOCKOUT',
			'definition' => '_ADMIN_CONF_TIP_LOCKOUT_DEF'
		),
		'DateFormat' => array(
			'type' => 'text',
			'default' => '%Y-%m-%d',
			'label' => '_ADMIN_CONF_DATEF',
			'definition' => '_ADMIN_CONF_DATEF_DEF'
		),
		'TimeFormat' => array(
			'type' => 'text',
			'default' => '%H:%M%p',
			'label' => '_ADMIN_CONF_TIMEF',
			'definition' => '_ADMIN_CONF_DATEF_DEF'
		),
		'Is24Hour' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_IS24HOUR',
			'definition' => '_ADMIN_CONF_IS24HOUR_DEF'
		),
		'MinuteValues' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_TIME_MINUTES',
			'definition' => '_ADMIN_CONF_TIME_MINUTES_DEF'
		),
		'ShowJSCountdown' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_JSTIME',
			'definition' => '_ADMIN_CONF_JSTIME_DEF'
		),
		'JsLadder' => array(
			'type' => 'select',
			'options' => array(
				'none' => $jLang['_ADMIN_CONF_JS_NONE'],
				'linear' => $jLang['_ADMIN_MT_LINEAR'],
				'Quad' => $jLang['_ADMIN_MT_QUADRATIC'],
				'Cubic' => $jLang['_ADMIN_MT_CUBIC'],
				'Quart' => $jLang['_ADMIN_MT_QUARTIC'],
				'Quint' => $jLang['_ADMIN_MT_QUNITIC'],
				'Sine' => $jLang['_ADMIN_MT_SINUSOIDAL'],
				'Pow' => $jLang['_ADMIN_MT_EXPONENTIAL'],
				'Circ' => $jLang['_ADMIN_MT_CIRCULAR'],
				'Bounce' => $jLang['_ADMIN_MT_BOUNCING'],
				'Back' => $jLang['_ADMIN_MT_BACK'],
				'Elastic' => $jLang['_ADMIN_MT_ELASTIC']
			),
			'default' => 'none',
			'label' => '_ADMIN_CONF_LADSTYLE',
			'definition' => '_ADMIN_CONF_LADSTYLE_DEF'
		),
		'JsLadderStyle' => array(
			'type' => 'select',
			'options' => array(
				'easeIn' => $jLang['_ADMIN_MT_EASEIN'],
				'easeOut' => $jLang['_ADMIN_MT_EASEOUT'],
				'easeInOut' => $jLang['_ADMIN_MT_EASEINOUT']
			),
			'default' => 'easeIn',
			'label' => '_ADMIN_CONF_LADSTYLE2',
			'definition' => '_ADMIN_CONF_LADSTYLE2_DEF'
		),
		'JsLadderDuration' => array(
			'type' => 'text',
			'default' => '1',
			'label' => '_ADMIN_CONF_LADSTYLE3',
			'definition' => '_ADMIN_CONF_LADSTYLE3_DEF'
		),
		'LastWin' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_SHOWLAST',
			'definition' => '_ADMIN_CONF_SHOWLAST_DEF'
		),
		'LastWinCurrSeason' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_SHOWLAST_SEAS',
			'definition' => '_ADMIN_CONF_SHOWLAST_SEAS_DEF'
		),
		'NumDefault' => array(
			//'type' => 'text',
			'default' => 10,
			'label' => '_ADMIN_CONF_SHOWDEF',
			'definition' => '_ADMIN_CONF_SHOWDEF_DEF',
			'type' => 'select',
			'options' => array(
				5 => 5,
				10 => 10,
				15 => 15,
				20 => 20,
				25 => 25,
				30 => 30,
				50 => 50,
				100 => 100,
				0 => 'All'
			)
		),
		'NumMax' => array(
			//'type' => 'text',
			'default' => 25,
			'label' => '_ADMIN_CONF_SHOWMAX',
			'definition' => '_ADMIN_CONF_SHOWMAX_DEF',
			'type' => 'select',
			'options' => array(
				5 => 5,
				10 => 10,
				15 => 15,
				20 => 20,
				25 => 25,
				30 => 30,
				50 => 50,
				100 => 100,
				0 => 'All'
			)
		),
		'DisplayName' => array(
			'type' => 'select',
			'options' => array(
				'real' => $jLang['_ADMIN_CONF_NAMER'],
				'user' => $jLang['_ADMIN_CONF_NAMEU']
			),
			'default' => 'user',
			'label' => '_ADMIN_CONF_DN',
			'definition' => '_ADMIN_CONF_DN_DEF'
		)/*,
		'ShowLicence' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_LICENCE',
			'definition' => '_ADMIN_CONF_LICENCE'
		)*/
	),
	'columns' => array(
		'ScoreSummary' => array(
			'type' => 'select',
			'attributes' => 'multiple size="8"',
			'is_array' => true,
			'options' => array(
				'season' => $jLang['_ADMIN_SEASON_NAME'],
				'rank' => $jLang['_COM_DASH_RANK'],
				'score' => $jLang['_COM_DASH_POINTST'],
				'average' => $jLang['_ADMIN_USERS_AVERAGE'],
				'precision' => $jLang['_COM_DASH_PREC'],
				'projected' => $jLang['_ADMIN_CONF_SSUMM_PROJECTED_SCORE'],
				'doubleup' => $jLang['_ADMIN_CONF_SS_DBLUP_STATUS'],
				'paid' => $jLang['_ADMIN_CONF_SS_PAID_STATUS']
			),
			'label' => '_ADMIN_CONF_SUMM_SEL',
			'definition' => '_ADMIN_CONF_SUMM_SEL_DEF'
		),
		'ScoreSummaryButtons' => array(
			'type' => 'buttons',
			'label' => '_ADMIN_CONF_SUMM_BTNS',
			'definition' => '_ADMIN_CONF_SUMM_BTNS_DEF',
			'buttons' => array(
				array(
					'name' => 'ScoreSummaryUpButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_UP'],
					'attributes' => "onClick='moveOptionsUp(\"ScoreSummary\");'"
				),
				array(
					'name' => 'ScoreSummaryDownButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_DOWN'],
					'attributes' => "onClick='moveOptionsDown(\"ScoreSummary\");'"
				)
			)
		),
		'LastRoundSummary' => array(
			'type' => 'select',
			'attributes' => 'multiple size="7"',
			'is_array' => true,
			'options' => array(
				'season' => $jLang['_ADMIN_SEASON_NAME'],
				'last_won' => $jLang['_COM_DASH_USER'],
				'last_prec' => $jLang['_COM_DASH_LAST_PREC'],
				'last_round' => $jLang['_ADMIN_CONF_LRS_LRS'],
				'last_prect' => $jLang['_COM_DASH_LAST_PRECT'],
				'last_tot' => $jLang['_COM_DASH_POINTST'],
				'last_comm' => $jLang['_MOD_LAST_ROUND_COMMENT']
			),
			'label' => '_ADMIN_CONF_LAST_SUMM_SEL',
			'definition' => '_ADMIN_CONF_LAST_SUMM_SEL_DEF'
		),
		'LastRoundSummaryButtons' => array(
			'type' => 'buttons',
			'label' => '_ADMIN_CONF_LAST_SUMM_BTNS',
			'definition' => '_ADMIN_CONF_LAST_SUMM_BTNS_DEF',
			'buttons' => array(
				array(
					'name' => 'LastRoundSummaryUpButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_UP'],
					'attributes' => "onClick='moveOptionsUp(\"LastRoundSummary\");'"
				),
				array(
					'name' => 'LastRoundSummaryDownButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_DOWN'],
					'attributes' => "onClick='moveOptionsDown(\"LastRoundSummary\");'"
				)
			)
		),
		'CompetitionLadderColumns' => array(
			'type' => 'select',
			'attributes' => 'multiple',
			'is_array' => true,
			'options' => array(
				'rank' => $jLang['_COM_DASH_RANK'],
				'user' => $jLang['_COM_DASH_USER'],
				'prec' => $jLang['_COM_COMP_PRECISION'],
				'points' => $jLang['_COM_ROUND_SCORE'],
				'prect' => $jLang['_COM_DASH_PRECT'],
				'pointst' => $jLang['_COM_DASH_POINTST'],
				'comment' => $jLang['_COM_DASH_COMMENT'],
				'moved' => $jLang['_COM_MOVEMENT'],
				'doubleup' => $jLang['_COM_DOUBLEUP_USED']
			),
			'label' => '_ADMIN_CONF_COMP_LADD_SEL',
			'definition' => '_ADMIN_CONF_COMP_LADD_SEL_DEF'
		),
		'CompetitionLadderColumnsButtons' => array(
			'type' => 'buttons',
			'label' => '_ADMIN_CONF_COMP_LADD_BTNS',
			'definition' => '_ADMIN_CONF_COMP_LADD_BTNS_DEF',
			'buttons' => array(
				array(
					'name' => 'CompetitionLadderColumnsUpButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_UP'],
					'attributes' => "onClick='moveOptionsUp(\"CompetitionLadderColumns\");'"
				),
				array(
					'name' => 'CompetitionLadderColumnsDownButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_DOWN'],
					'attributes' => "onClick='moveOptionsDown(\"CompetitionLadderColumns\");'"
				)
			)
		),
		'TeamLadderColumns' => array(
			'type' => 'select',
			'attributes' => 'multiple',
			'is_array' => true,
			'options' => array(
				'played' => $jLang['_COM_TLD_PLAYED'],
				'wins' => $jLang['_COM_TLD_WINS'],
				'draws' => $jLang['_COM_TLD_DRAWS'],
				'losses' => $jLang['_COM_TLD_LOSSES'],
				'points_for' => $jLang['_COM_TLD_POINTS_FOR'],
				'points_against' => $jLang['_COM_TLD_POINTS_AGAINST'],
				'for_against' => $jLang['_COM_TLD_POINTS_FORAGAINST'],
				'points' => $jLang['_COM_TLD_POINTS_SHORT'],
				'percentage' => $jLang['_COM_TLD_PERCENTAGE']
			),
			'label' => '_ADMIN_CONF_TEAM_LADD_SEL',
			'definition' => '_ADMIN_CONF_TEAM_LADD_SEL_DEF'
		),
		'TeamLadderColumnsButtons' => array(
			'type' => 'buttons',
			'label' => '_ADMIN_CONF_TEAM_LADD_BTNS',
			'definition' => '_ADMIN_CONF_TEAM_LADD_BTNS_DEF',
			'buttons' => array(
				array(
					'name' => 'TeamLadderColumnsUpButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_UP'],
					'attributes' => "onClick='moveOptionsUp(\"TeamLadderColumns\");'"
				),
				array(
					'name' => 'TeamLadderColumnsDownButton',
					'default' => $jLang['_ADMIN_CONF_BUTTON_DOWN'],
					'attributes' => "onClick='moveOptionsDown(\"TeamLadderColumns\");'"
				)
			)
		)
	),
	'integration' => array(
		'AlphaUserPoints' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_ALPHAUSERPOINTS',
			'definition' => '_ADMIN_CONF_ALPHAUSERPOINTS_DEF'
		),
		// BUG 293 - JomSocial Integration
		'SocialIntegration' => array(
			'type' => 'select',
			'default' => null,
			'options' => array(
				'' => $jLang['_ADMIN_CONF_NONE'],
				'cb' => $jLang['_ADMIN_CONF_SOCIAL_CBI'],
				'cc' => $jLang['_ADMIN_CONF_SOCIAL_CCI']
			),
			'label' => '_ADMIN_CONF_SOCIAL_INTEGRATION',
			'definition' => '_ADMIN_CONF_SOCIAL_INTEGRATION_DEF'
		),
		'JomSocialActivities' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_JOMSOCIAL_ACTIVITIES',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ACTIVITIES_DEF'
		),
		'JomSocialCompetitionLinkTarget' => array(
			'type' => 'select',
			'options' => array(
				'' => $jLang['_ADMIN_CONF_NONE'],
				'Dashboard' => $jLang['_COM_DASHBOARD'],
				'CompetitionLadder' => $jLang['_COM_COMPETITION_LADDER']
			),
			'default' => 'Dashboard',
			'label' => '_ADMIN_CONF_JOMSOCIAL_LINK',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_LINK_DEF'
		),
		'JomSocialOnUserJoin' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN_DEF'
		),
		'JomSocialJoinMessage' => array(
			'type' => 'text',
			'default' => '{multiple}{actors}{/multiple}{single}{actor}{/single} joined the {season} competition',
			'label' => '_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN_MSG',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ONUSERJOIN_MSG_DEF'
		),
		'JomSocialOnSaveTips' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS_DEF'
		),
		'JomSocialOnSaveTipsMessage' => array(
			'type' => 'text',
			'default' => '{multiple}{actors}{/multiple}{single}{actor}{/single} submitted their tips for round {round} of the {season}',
			'label' => '_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS_MSG',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ONSAVETIPS_MSG_DEF'
		),
		'JomSocialUserResults' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_JOMSOCIAL_USER_RESULTS',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_USER_RESULTS_DEF'
		),
		'JomSocialRoundResultMessage' => array(
			'type' => 'text',
			'default' => '{multiple}{actors}{/multiple}{single}{actor}{/single} scored {score} in round {round} of the {season} competition',
			'label' => '_ADMIN_CONF_JOMSOCIAL_RESULTS_MSG',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_RESULTS_MSG_DEF'
		),
		'JomSocialRoundWinnersMessage' => array(
			'type' => 'text',
			'default' => '{multiple}{actors}{/multiple}{single}{actor}{/single} won round {round} of the {season} competition',
			'label' => '_ADMIN_CONF_JOMSOCIAL_WINNERS_MSG',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_WINNERS_MSG_DEF'
		),
		'JomSocialOnNoTips' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_JOMSOCIAL_ONNOTIPS',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ONNOTIPS_DEF'
		),
		'JomSocialOnNoTipsMessage' => array(
			'type' => 'text',
			'default' => '{multiple}{actors}{/multiple}{single}{actor}{/single} forgot to submit their tips for {round} of the {season} competition',
			'label' => '_ADMIN_CONF_JOMSOCIAL_ONNOTIPS_MSG',
			'definition' => '_ADMIN_CONF_JOMSOCIAL_ONNOTIPS_MSG_DEF'
		)
	),
	'notifications' => array(
		'TipsNotifyEnable' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_NOTIFY_TIPS',
			'definition' => '_ADMIN_CONF_NOTIFY_TIPS_DEF'
		),
		'UserNotifyEnable' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_NOTIFY_UOR',
			'definition' => '_ADMIN_CONF_NOTIFY_UOR_DEF'
		),
		'UserNotifyFromName' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTOFY_FROMN',
			'definition' => '_ADMIN_CONF_NOTIFY_FROMN_DEF'
		),
		'UserNotifyFromEmail' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_FROMA',
			'defintion' => '_ADMIN_CONF_NOTIFY_FROMA_DEF'
		),
		'UserNotifySubject' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_SUBJ',
			'definition' => '_ADMIN_CONF_NOTIFY_SUBJU_DEF'
		),
		'UserNotifyMessage' => array(
			'type' => 'textarea',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_MSG',
			'definition' => '_ADMIN_CONF_NOTIFY_MSGU_DEF',
			'attributes' => 'cols="40" rows="8"'
		),
		'AdminNotifyEnable' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_NOTIFY_AOR',
			'definition' => '_ADMIN_CONF_NOTIFY_UOR_DEF'
		),
		'AdminNotifyToEmail' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_TO',
			'definition' => '_ADMIN_CONF_NOTIFY_TO_DEF'
		),
		'AdminNotifyFromName' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTOFY_FROMN',
			'definition' => '_ADMIN_CONF_NOTOFY_FROMN_DEF'
		),
		'AdminNotifyFromEmail' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_FROMA',
			'definition' => '_ADMIN_CONF_NOTIFY_FROMA_DEF'
		),
		'AdminNotifySubject' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_SUBJ',
			'definition' => '_ADMIN_CONF_NOTIFY_SUBJA_DEF'
		),
		'AdminNotifyMessage' => array(
			'type' => 'textarea',
			'default' => '',
			'label' => '_ADMIN_CONF_NOTIFY_MSG',
			'definition' => '_ADMIN_CONF_NOTIFY_MSGA_DEF',
			'attributes' => 'cols="40" rows="8"'
		),
		'UserNotifyApprovalSubject' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_NOTIFY_APPSUB',
			'definition' => '_ADMIN_NOTIFY_APPSUB_DEF'
		),
		'UserNotifyApprovalMessage' => array(
			'type' => 'textarea',
			'default' => '',
			'label' => '_ADMIN_NOTIFY_APPMSG',
			'definition' => '_ADMIN_NOTIFY_APPMSG_DEF',
			'attributes' => 'cols="40" rows="8"'
		),
		'UserNotifyRejectSubject' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_NOTIFY_UAPPSUB',
			'definition' => '_ADMIN_NOTIFY_UAPPSUB_DEF'
		),
		'UserNotifyRejectMessage' => array(
			'type' => 'textarea',
			'default' => '',
			'label' => '_ADMIN_NOTIFY_UAPPMSG',
			'definition' => '_ADMIN_NOTIFY_UAPPMSG_DEF',
			'attributes' => 'cols="40" rows="8"'
		),
		'EnableEmailReminders' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_REMIND_ENABLE',
			'definition' => '_ADMIN_REMIND_ENABLE_DEF'
		),
		'DefaultReminders' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_REMINDER_DEFAULT',
			'definition' => '_ADMIN_REMINDER_DEFAULT_DEF'
		),
		'EmailManKey' => array(
			'type' => 'text',
			'label' => '_ADMIN_REMINDER_EMAILMANKEY',
			'definition' => '_ADMIN_REMINDER_EMAILMANKEY_DEF'
		),
		'EmailRemindFromName' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_REMIND_FROMNAME',
			'definition' => '_ADMIN_REMIND_FROMNAME_DEF'
		),
		'EmailRemindFromAddress' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_REMIND_FROMADDRESS',
			'definition' => '_ADMIN_REMIND_FROMADDRESS_DEF'
		),
		'EmailRemindSubject' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_REMIND_SUBJECT',
			'definition' => '_ADMIN_REMIND_SUBJECT_DEF'
		),
		'EmailRemindBody' => array(
			'type' => 'textarea',
			'default' => '',
			'label' => '_ADMIN_REMIND_BODY',
			'definition' => '_ADMIN_REMIND_BODY_DEF',
			'attributes' => 'cols="40" rows="8"'
		)
	),
	'paypal' => array(
		'PayPalIsSub' => array(
			'type' => 'yesno',
			'default' => 0,
			'label' => '_ADMIN_CONF_PAYPALTYPE',
			'definition' => '_ADMIN_CONF_PAYPALTYPE_DEF'
		),
		'PayPal' => array(
			'type' => 'textarea',
			'attributes' => 'cols="40" rows="8"',
			'label' => '_ADMIN_CONF_PAYPAL',
			'definition' => '_ADMIN_CONF_PAYPAL_DEF',
			'encode' => true
		),
		'PayPalUnSub' => array(
			'type' => 'textarea',
			'attributes' => 'cols="40" rows="8"',
			'label' => '_ADMIN_CONF_PAYPALUNSUB',
			'definition' => '_ADMIN_CONF_PAYPALUNSUB_DEF',
			'encode' => true
		)
	),
	'activate' => array(
		/*'ActivationStatus' => array(
			'type' => 'div',
			'default' => '__function__',
			'function' => 'getActivationStatusText',
			'arguments' => array(
				$jLang['_ADMIN_CONF_ACT_DONE'],
				$jLang['_ADMIN_CONF_ACT_NA']
			),
			'label' => '_ADMIN_CONF_ACT_STATUS',
			'definition' => '_ADMIN_CONF_ACT_STATUS_DEF'
		),*/
		'ActivationVersion' => array(
			'type' => 'div',
			'default' => '__function__',
			'function' => 'getFullVersion',
			'arguments' => array(),
			'label' => '_ADMIN_CONF_JTIP_VER',
			'definition' => ''
		),
		'ActivationEmail' => array(
			'type' => 'text',
			'default' => '',
			'label' => '_ADMIN_CONF_ACT_EMAIL',
			'definition' => '_ADMIN_CONF_ACT_EMAIL_DEF',
		),
		'ActivationKey' => array(
			'type' => 'text',
			'default' => '',
			'attributes' => "style='width:90%;'",
			'label' => '_ADMIN_CONF_ACT_KEY',
			'definition' => '_ADMIN_CONF_ACT_KEY_DEF'
		),
		'SSLValidation' => array(
			'type' => 'yesno',
			'default' => 1,
			'label' => '_ADMIN_CONF_SSL',
			'definition' => '_ADMIN_CONF_SSL_DEF'
		)
	)
);
?>
