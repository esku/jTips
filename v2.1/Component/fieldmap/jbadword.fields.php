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
		'label' => '_ADMIN_JBADWORD_ID',
		'import' => false
	),
	'badword' => array (
		'type' => 'text',
		'label' => '_ADMIN_JBADWORD_WORD',
		'import' => true,
		'default' => '',
		'required' => true
	),
	'match_case' => array (
		'type' => 'bool',
		'label' => '_ADMIN_JBADWORD_MATCH_CASE',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'action' => array (
		'type' => 'text',
		'label' => '_ADMIN_JBADWORD_ACTION',
		'import' => true,
		'default' => 'delete',
		'required' => false
	),
	'replace' => array (
		'type' => 'text',
		'label' => '_ADMIN_JBADWORD_REPALCE',
		'import' => true,
		'default' => '',
		'required' => false
	),
	'hits' => array (
		'type' => 'int',
		'label' => '_ADMIN_JBADOWRD_HITS',
		'import' => true,
		'default' => 0,
		'required' => false
	),
	'updated' => array (
		'type' => 'datetime',
		'label' => '_ADMIN_JBADWORD_UPDATED',
		'import' => false,
		'default' => NULL,
		'required' => false
	)
);
?>