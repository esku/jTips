<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 02/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Allows editing of an existing comment
 */

jTipsSpoofCheck();

global $database, $jTips, $jLang;

require_once('components/com_jtips/classes/jcomment.class.php');
require_once('components/com_jtips/classes/jround.class.php');
require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/classes/juser.class.php');

$jComment = new jComment($database);

$ids = jTipsGetParam($_REQUEST, 'cid', array());
//Do we have an existing Season?
$id = array_shift($ids);

if (is_numeric($id)) {
	$jComment->load($id);
}

if (!$jComment->exists()) {
	mosRedirect('index2.php?option=com_jtips&task=list&module=Comments', $jLang['_ADMIN_COMMENT_LOAD_ERROR']);
}

$jTipsUser = new jTipsUser($database);
$jTipsUser->load($jComment->user_id);

$title = $jLang['_ADMIN_DASH_COMMENT_MANAGER']. ": " .$jLang['_ADMIN_OTHER_EDIT'];

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Comments/Comments.js'></script>");

//what seasons are there
$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams(array()));
$jSeasonOptions = array(jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']));
jTipsSortArrayObjects($jSeasons, 'name', 'ASC');
foreach ($jSeasons as $season) {
	$jSeasonOptions[] = jTipsHTML::makeOption($season->id, $season->name);
}

//which season is this in?
$jRound = new jRound($database);
$jRound->load($jComment->round_id);

$jRounds = forceArray($jRound->loadByParams(array('season_id' => $jRound->season_id)));
$jRoundOptions = array(jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']));
jTipsSortArrayObjects($jSeasons, 'name', 'ASC');
foreach ($jRounds as $round) {
	$jRoundOptions[] = jTipsHTML::makeOption($round->id, $round->round);
}

$formData = array(
	'basic' => array(
		'legend' => '_ADMIN_COMM_EDIT_COMMENT',
		'fields' => array(
			'id' => array(
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'id',
						'id' => 'id',
						'value' => $jComment->id
					)
				)
			),
			'orig_round_id' => array(
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'orig_round_id',
						'id' => 'orig_round_id',
						'value' => $jComment->round_id
					)
				)
			),
			'user_id' => array(
				'label' => '_COM_DASH_USER',
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'user_id',
						'id' => 'user_id',
						'value' => $jComment->user_id
					)
				)
			),
			'user' => array(
				'label' => '_COM_DASH_USER',
				'field' => array(
					'type' => 'label',
					'attributes' => array(
						'type' => 'label',
						'value' => $jTipsUser->getUserField('name'). " (" .$jTipsUser->getUserField('username'). ")"
					)
				)
			),
			'season_id' => array(
				'label' => '_ADMIN_ROUND_SEASON',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'name' => 'season_id',
						'id' => 'season_id',
						'class' => 'inputbox',
						'onChange' => 'getTheRounds(this);'
					),
					'options' => $jSeasonOptions,
					'selected' => $jRound->season_id
				)
			),
			'round_id' => array(
				'label' => '_ADMIN_ROUND_ROUND',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'name' => 'round_id',
						'id' => 'round_id',
						'class' => 'inputbox'
					),
					'options' => $jRoundOptions,
					'selected' => $jComment->round_id
				)
			),
			'comment' => array(
				'label' => '_ADMIN_COMM_EDIT_COMMENT',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'size' => 50,
						'name' => 'comment',
						'id' => 'comment',
						'class' => 'inputbox',
						'type' => 'text',
						'value' => jTipsStripslashes(htmlentities($jComment->comment, ENT_QUOTES))
					)
				)
			),
			'updatedlabel' => array(
				'label' => '_COM_TIPS_LASTUP',
				'field' => array(
					'type' => 'label',
					'attributes' => array(
						'type' => 'label',
						'value' => TimeDate::toDisplayDateTime($jComment->updated)
					)
				),
				'description' => '_ADMIN_COMMENTS_UDPATED_DESCRIPTION'
			)
		)
	)
);

jTipsAdminDisplay::EditView($title, $formData, 'comments');
?>