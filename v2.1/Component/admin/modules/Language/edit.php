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
global $jLang;

jTipsSpoofCheck();

$title = $jLang['_ADMIN_LANGUAGE_EDITING'];

$langKeys = jTipsGetParam($_REQUEST, 'cid', array());
$key = array_shift($langKeys);

$formData = array();
if (isset($jLang[$key])) {
	$formData = array(
		'fieldset' => array(
			'legend' => '_ADMIN_LANG_EDIT_VALUE',
			'fields' => array(
				'label' => array(
					'label' => '_ADMIN_LANGUAGE_SYSKEY',
					'field' => array(
						'type' => 'label',
						'attributes' => array(
							'value' => $key,
							'type' => 'label'
						)
					)
				),
				'data' => array(
					'label' => '_ADMIN_LANGUAGE_EDIT',
					'field' => array(
						'type' => 'text',
						'attributes' => array(
							'size' => '50',
							'type' => 'text',
							'class' => 'inputbox',
							'name' => 'languageVar',
							'value' => htmlentities($jLang[$key], ENT_QUOTES, 'UTF-8') // Bug 367 - encode accented characters with UTF-8
						)
					)
				),
				'key' => array(
					'type' => 'hidden',
					'field' => array(
						'type' => 'hidden',
						'attributes' => array(
							'type' => 'hidden',
							'name' => 'languageKey',
							'value' => $key
						)
					)
				)
			)
		)
	);
}

jTipsAdminDisplay::EditView($title, $formData, 'langmanager');
?>