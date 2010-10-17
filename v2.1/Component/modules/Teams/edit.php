<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Website: www.jtips.com.au
 * @author Jeremy Roberts
 * @copyright Copyright &copy; 2009, jTips
 * @license Commercial - See website for details
 * 
 * @since 2.1 - 01/10/2008
 * @version 2.1
 * @package jTips
 * 
 * Description: Load an existing team, or prepare a new one.
 * Load the form fields and prepare for editing
 */

//This breaks the shortcuts on the dashboard
//jTipsSpoofCheck();

global $database;

require_once('components/com_jtips/classes/jseason.class.php');
require_once('components/com_jtips/classes/jteam.class.php');

$jTeam = new jTeam($database);

$ids = jTipsGetParam($_REQUEST, 'cid', array());
//Do we have an existing Season?
$id = array_shift($ids);

if (is_numeric($id)) {
	$jTeam->load($id);
}

//set the page title
$title = $jLang['_ADMIN_TEAM_TITLE'].": " .($jTeam->exists() ? $jLang['_ADMIN_OTHER_EDIT'] : $jLang['_ADMIN_OTHER_NEW']);

//set the custom javascripts
$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/Teams/Teams.js'></script>");

//what seasons are there
$jSeason = new jSeason($database);
$jSeasons = forceArray($jSeason->loadByParams(array()));
$jSeasonOptions = array(jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']));
jTipsSortArrayObjects($jSeasons, 'name', 'ASC');
foreach ($jSeasons as $season) {
	$jSeasonOptions[] = jTipsHTML::makeOption($season->id, $season->name);
}

//build the field definitions
$formData = array(
	'basic' => array( //this defines the fieldset
		'legend' => '_ADMIN_BASIC_INFORMATION', //the title for this fieldset
		'fields' => array( //now add the fields to this set
			'id' => array(	
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'id',
						'id' => 'id',
						'value' => $jTeam->id
					)
				)
			),
			'season' => array( //this is the field definition
				'label' => '_ADMIN_TEAM_SEASON', //the label for the field
				'field' => array( //set the html attributes for the field
					'type' => 'select',
					'attributes' => array(
						 'name' => 'season_id',
						 'id' => 'season_id',
						 'class' => 'inputbox'
					),
					'options' => $jSeasonOptions,
					'selected' => $jTeam->season_id
				)
			),
			'name' => array(
				'label' => '_ADMIN_TEAM_NAME',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'name' => 'name',
						'id' => 'name',
						'class' => 'inputbox',
						'size' => '50',
						'type' => 'text',
						'value' => $jTeam->name
					)
				)
			),
			'location' => array(
				'label' => '_ADMIN_TEAM_LOCATION',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'name' => 'location',
						'id' => 'location',
						'class' => 'inputbox',
						'size' => '50',
						'type' => 'text',
						'value' => $jTeam->location
					)
				)
			),
			'website' => array(
				'label' => '_ADMIN_TEAM_URL',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'name' => 'url',
						'id' => 'url',
						'class' => 'inputbox',
						'size' => '50',
						'type' => 'text',
						'value' => $jTeam->url
					)
				)
			),
			'about' => array(
				'label' => '_ADMIN_TEAM_ABOUT',
				'field' => array(
					'type' => 'editor',
					'attributes' => array(
						'name' => 'about',
						'id' => 'about',
						'class' => 'inputbox',
						'value' => jTipsStripslashes($jTeam->about)
					)
				)
			)
		)
	)
);

$formData['image'] = array(
	'legend' => '_ADMIN_TEAM_LOGO',
	'fields' => array(
		'logo' => array(
			'label' => '_ADMIN_TEAM_LOGO',
			'field' => array(
				'type' => 'file',
				'attributes' => array(
					'size' => '50',
					'type' => 'file',
					'class' => 'inputbox',
					'name' => 'logo',
					'id' => 'logo'
				)
			)
		)
	)
);
if ($jTeam->logo) {
	$formData['image']['fields']['current_logo'] = array(
		'label' => '_ADMIN_TEAM_CURRENT_LOGO',
		'field' => array(
			'type' => 'img',
			'attributes' => array(
				'alt' => 'Logo',
				'id' => 'current_logo',
				'src' => $mosConfig_live_site. '/' .getJtipsImage($jTeam->logo, 100)
			)
		)
	);
	$formData['image']['fields']['remove_logo'] = array(
		'label' => '_ADMIN_TEAM_REMOVE_LOGO',
		'field' => array(
			'type' => 'checkbox',
			'attributes' => array(
				'class' => 'inputbox',
				'name' => 'remove_logo',
				'value' => '1',
				'type' => 'checkbox',
				'onClick' => 'if (this.checked){$("logo").disabled=true;}else{$("logo").disabled=false}'
			)
		),
		'description' => '_ADMIN_TEAM_REMOVE_LOGO_DEF'
	);
}

// BUG 287 - can no longer edit team points
/*$formData['points'] = array(
	'legend' => '_ADMIN_TEAM_POINTS_ADJUST',
	'fields' => array(
		'wins' => array(
			'label' => '_ADMIN_TEAM_ADJUST_WINS',
			'field' => array(
				'type' => 'text',
				'attributes' => array(
					'name' => 'wins',
					'id' => 'wins',
					'class' => 'inputbox',
					'size' => '10',
					'type' => 'text',
					'style' => 'text-align:center',
					'value' => $jTeam->wins
				)
			)
		),
		'losses' => array(
			'label' => '_ADMIN_TEAM_ADJUST_LOSSES',
			'field' => array(
				'type' => 'text',
				'attributes' => array(
					'name' => 'losses',
					'id' => 'losses',
					'class' => 'inputbox',
					'size' => '10',
					'type' => 'text',
					'style' => 'text-align:center',
					'value' => $jTeam->losses
				)
			)
		),
		'points_for' => array(
			'label' => '_ADMIN_TEAM_ADJUST_FOR',
			'field' => array(
				'type' => 'text',
				'attributes' => array(
					'name' => 'points_for',
					'id' => 'points_for',
					'class' => 'inputbox',
					'size' => '10',
					'type' => 'text',
					'style' => 'text-align:center',
					'value' => $jTeam->points_for
				)
			)
		),
		'points_against' => array(
			'label' => '_ADMIN_TEAM_ADJUST_AGAINST',
			'field' => array(
				'type' => 'text',
				'attributes' => array(
					'name' => 'points_against',
					'id' => 'points_against',
					'class' => 'inputbox',
					'size' => '10',
					'type' => 'text',
					'style' => 'text-align:center',
					'value' => $jTeam->points_against
				)
			)
		),
		'points' => array(
			'label' => '_ADMIN_TEAM_ADJUST',
			'field' => array(
				'type' => 'text',
				'attributes' => array(
					'name' => 'points',
					'id' => 'points',
					'class' => 'inputbox',
					'size' => '10',
					'type' => 'text',
					'style' => 'text-align:center',
					'value' => $jTeam->points
				)
			)
		)
	)
);*/

jTipsAdminDisplay::EditView($title, $formData, 'team');
?>