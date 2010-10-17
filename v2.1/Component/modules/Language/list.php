<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
defined('_JEXEC') or defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $database, $jTips, $mosConfig_absolute_path;

$header = array(
	'',
	$jLang['_ADMIN_LANG_KEY'],
	$jLang['_ADMIN_LANG_DEF']
);

$formData = array(
	'title' => $jLang['_ADMIN_LANGUAGE_TITLE'],
	'editTask' => '',
	'module' => 'Language',
	'icon' => 'langmanager'
);


if ($find = jTipsGetParam($_REQUEST, 'lang_val', false) and !jTipsGetParam($_REQUEST, 'clear', false)) {
	$theLanguage = array();
	foreach ($jLang as $key => $val) {
		if (preg_match('/' .$find. '/i', $val)) {
			$theLanguage[$key] = $val;
		}
	}
} else {
	$_REQUEST['lang_val'] = '';
	$theLanguage = $jLang;
}

ksort($theLanguage);

$data = array();

$limitstart = jTipsGetParam($_REQUEST, 'limitstart', 0);
$limit = jTipsGetParam($_REQUEST, 'limit', 25);

if ($limit == 0) {
	$limit = count($jLang);
}

$langKeys = array_keys($theLanguage);
$langDefs = array_values($theLanguage);
$index = 0;
for ($i=$limitstart; $i<($limitstart+$limit) and $i<count($theLanguage); $i++) {
	$data[$langKeys[$i]] = array(
		makeListLink($langKeys[$i], $index),
		htmlentities($langDefs[$i], ENT_QUOTES, 'UTF-8') // Bug 367 - encode accented characters with UTF-8
	);
	$index++;
}

$filters = array(
	'Search' => "<input type='text' name='lang_val' class='text_area' value='" .jTipsGetParam($_REQUEST, 'lang_val'). "' />",
	//'' => "<input type='submit' name='submit' value='Find' class='button' />"
);
$filters[] = "&nbsp;<input type='submit' name='search' value='Find' class='button' />";
$filters[] = "&nbsp;<input type='submit' name='clear' value='Reset' class='button' />";

$pageNav = new mosPageNav(count($theLanguage), $limitstart, $limit);

jTipsAdminDisplay::ListView($formData, $header, $data, $pageNav, '', $filters, $jLang['_ADMIN_LANGUAGE_LIST_INFO']);
?>