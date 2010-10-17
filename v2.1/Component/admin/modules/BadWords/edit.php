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
 * Description: 
 */

jTipsSpoofCheck();

global $database, $jTips, $jLang;

require_once('components/com_jtips/classes/jbadword.class.php');

$focus = new jBadWord($database);

$ids = jTipsGetParam($_REQUEST, 'cid', array());
//Do we have an existing Season?
$id = array_shift($ids);

if (is_numeric($id)) {
	$focus->load($id);
}

$title = $jLang['_ADMIN_BW_HEADER']. ": " .($focus->exists() ? $jLang['_ADMIN_OTHER_EDIT'] : $jLang['_ADMIN_OTHER_NEW']);

$mainframe->addCustomHeadTag("<script type='text/javascript' src='components/com_jtips/modules/BadWords/Edit.js'></script>");

$formData = array(
	'basic' => array(
		'legend' => '_ADMIN_EDIT_MAIN_INFORMATION',
		'fields' => array(
			'id' => array(
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'type' => 'hidden',
						'name' => 'id',
						'id' => 'id',
						'value' => $focus->id
					)
				)
			),
			'badword' => array(
				'label' => '_ADMIN_BW_BAD_WORD',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'size' => 50,
						'name' => 'badword',
						'id' => 'badword',
						'class' => 'inputbox',
						'value' => jTipsStripslashes(htmlentities($focus->badword, ENT_QUOTES)),
						'type' => 'text'
					)
				)
			),
			'match_case' => array(
				'label' => '_ADMIN_BW_CASE_SENSITIVE',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'name' => 'match_case',
						'id' => 'match_case',
						'class' => 'inputbox'
					),
					'selected' => $focus->match_case
				)
			),
			'action' => array(
				'label' => '_ADMIN_BW_ACTION',
				'field' => array(
					'type' => 'select',
					'attributes' => array(
						'name' => 'action',
						'id' => 'action',
						'class' => 'inputbox',
						'onChange' => 'toggleReplace();'
					),
					'options' => array(
						jTipsHTML::makeOption('', $jLang['_ADMIN_CONF_NONE']),
						jTipsHTML::makeOption('replace', $jLang['_ADMIN_CONF_COMMENTSACTION_REPLACE']),
						jTipsHTML::makeOption('delete', $jLang['_ADMIN_CONF_COMMENTSACTION_DELETE'])
					),
					'selected' => $focus->action
				)
			),
			'replace' => array(
				'label' => '_ADMIN_BW_REPLACEMENT',
				'field' => array(
					'type' => 'text',
					'attributes' => array(
						'size' => 50,
						'name' => 'replace',
						'id' => 'replace',
						'class' => 'inputbox',
						'type' => 'text',
						'disabled' => 'disabled',
						'value' => jTipsStripslashes(htmlentities($focus->replace, ENT_QUOTES))
					)
				)
			),
			'reset_hits' => array(
				'label' => '_ADMIN_BW_RESET_HITS',
				'field' => array(
					'type' => 'bool',
					'attributes' => array(
						'name' => 'reset_hits',
						'id' => 'reset_hits',
						'class' => 'inputbox'
					),
					'selected' => 0
				)
			)
		)
	)
);

jTipsAdminDisplay::EditView($title, $formData, 'badwords');
?>