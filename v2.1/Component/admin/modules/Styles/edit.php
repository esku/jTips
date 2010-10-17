<?php
defined('_JEXEC') or defined('_VALID_MOS') or die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * @since 08 July 2009
 *
 * Description:
 *
 *
 */
global $jLang;

global $mosConfig_absolute_path;

if (isJoomla15()) {
	$dir = $mosConfig_absolute_path.DS.'components'.DS.'com_jtips'.DS.'css'.DS;
	jimport('joomla.filesystem.folder');
	$files = JFolder::files($dir);
} else {

	$dir = $mosConfig_absolute_path.'/components/com_jtips/css/';
	$files = findAllFiles($dir);
}

// get the key of the index.html file
$flipped = array_flip($files);
unset($files[$flipped['index.html']]);
sort($files);

$filekeys = jTipsGetParam($_REQUEST, 'cid', array());
$key = array_shift($filekeys);

if (isJoomla15()) {
	jimport('joomla.filesystem.file');
	$content = JFile::read($dir.$files[$key]);
} else {
	$content = file_get_contents($dir.$files[$key]);
}

$title = $jLang['_ADMIN_STYLE_EDIT'];

$formData = array(
	'fieldset' => array(
		'legend' => '_ADMIN_STYLE_EDIT',
		'fields' => array(
			'label' => array(
				'label' => '_ADMIN_STYLE_FILENAME',
				'field' => array(
					'type' => 'label',
					'attributes' => array(
						'value' => $files[$key],
						'type' => 'label'
					)
				)
			),
			'content' => array(
				'label' => '_ADMIN_STYLE_CONTENT',
				'field' => array(
					'type' => 'textarea',
					'attributes' => array(
						'style' => "width:100%;height:400px;",
						'name' => 'jstyle'
					),
					'text' => $content
				)
			),
			'cid' => array(
				'label' => '',
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'value' => $key,
						'name' => 'cid',
						'type' => 'hidden'
					)
				)
			),
			'filename' => array(
				'label' => '',
				'field' => array(
					'type' => 'hidden',
					'attributes' => array(
						'value' => $files[$key],
						'name' => 'filename',
						'type' => 'hidden'
					)
				)
			)
		)
	)
);

jTipsAdminDisplay::EditView($title, $formData, 'css');
