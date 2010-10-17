<?php
if (!defined('_JEXEC') and !defined('_VALID_MOS')) die('Restricted Access');
/**
 * Author: Jeremy Roberts
 * Package: jTicket
 * Website: www.jtips.com.au
 * Created: 30/09/2008
 * 
 * Description: Build and render the Preferences form
 */

global $mosConfig_absolute_path;

require_once($mosConfig_absolute_path. '/administrator/components/com_jtips/classes/jseason.class.php');

if (jTipsFileExists($mosConfig_absolute_path. '/components/com_jtips/custom/views/UserPreferences/tmpl/default.ext.php')) {
	require_once($mosConfig_absolute_path. '/components/com_jtips/custom/views/UserPreferences/tmpl/default.ext.php');
} else {
	require_once($mosConfig_absolute_path. '/components/com_jtips/views/UserPreferences/tmpl/default.php');
}

//Trash anything we have so far
//ob_clean();
//ob_start();

$render = new jTipsRenderUserPreferences();
/*
$timezones = timezone_abbreviations_list();
$tzlist = array();
foreach ($timezones as $area => $tzone) {
	foreach ($tzone as $tz) {
		$offset = round($tz['offset'] / (60 * 60), 1);
		if (isset($tz['dst']) and $tz['dst'] != 0) {
			$offset -= $tz['dst'];
		}
		if ($offset >= 0) {
			$offset = "+" .$offset;
		}
		if (isset($tz['timezone_id']) and !empty($tz['timezone_id'])) {
			$tzlist[$tz['timezone_id']] = preg_replace('/[_]+/', ' ', $tz['timezone_id']). " (GMT $offset)";
		}
	}
}
*/
$tzlist = array(
	'-12' => '(UTC -12:00) International Date Line West',
	'-11' => '(UTC -11:00) Midway Island, Samoa',
	'-10' => '(UTC -10:00) Hawaii',
	'-9.5' => '(UTC -09:30) Taiohae, Marquesas Islands',
	'-9' => '(UTC -09:00) Alaska',
	'-8' => '(UTC -08:00) Pacific Time (US &amp; Canada)',
	'-7' => '(UTC -07:00) Mountain Time (US &amp; Canada)',
	'-6' => '(UTC -06:00) Central Time (US &amp; Canada), Mexico City',
	'-5' => '(UTC -05:00) Eastern Time (US &amp; Canada), Bogota, Lima',
	'-4' => '(UTC -04:00) Atlantic Time (Canada), Caracas, La Paz',
	'-3.5' => '(UTC -03:30) St. John`s, Newfoundland and Labrador',
	'-3' => '(UTC -03:00) Brazil, Buenos Aires, Georgetown',
	'-2' => '(UTC -02:00) Mid-Atlantic',
	'-1' => '(UTC -01:00 hour) Azores, Cape Verde Islands',
	'0' => '(UTC 00:00) Western Europe Time, London, Lisbon, Casablanca',
	'1' => '(UTC +01:00 hour) Berlin, Brussels, Copenhagen, Madrid, Paris',
	'2' => '(UTC +02:00) Kaliningrad, South Africa',
	'3' => '(UTC +03:00) Baghdad, Riyadh, Moscow, St. Petersburg',
	'3.5' => '(UTC +03:30) Tehran',
	'4' => '(UTC +04:00) Abu Dhabi, Muscat, Baku, Tbilisi',
	'4.5' => '(UTC +04:30) Kabul',
	'5' => '(UTC +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
	'5.5' => '(UTC +05:30) Bombay, Calcutta, Madras, New Delhi',
	'5.75' => '(UTC +05:45) Kathmandu',
	'6' => '(UTC +06:00) Almaty, Dhaka, Colombo',
	'6.5' => '(UTC +06:30) Yagoon',
	'7' => '(UTC +07:00) Bangkok, Hanoi, Jakarta',
	'8' => '(UTC +08:00) Beijing, Perth, Singapore, Hong Kong',
	'8.75' => '(UTC +08:45) Western Australia',
	'9' => '(UTC +09:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
	'9.5' => '(UTC +09:30) Adelaide, Darwin, Yakutsk',
	'10' => '(UTC +10:00) Eastern Australia, Guam, Vladivostok',
	'10.5' => '(UTC +10:30) Lord Howe Island (Australia)',
	'11' => '(UTC +11:00) Magadan, Solomon Islands, New Caledonia',
	'11.5' => '(UTC +11:30) Norfolk Island',
	'12' => '(UTC +12:00) Auckland, Wellington, Fiji, Kamchatka',
	'12.75' => '(UTC +12:45) Chatham Island',
	'13' => '(UTC +13:00) Tonga',
	'14' => '(UTC +14:00) Kiribati'
);
//asort($tzlist);
$render->assign('timezones', $tzlist);

//Set the selected timezone, or set to default timezone (if not set in preferences)
if ($jTipsCurrentUser->getPreference('timezone')) {
	$render->assign('timezone', $jTipsCurrentUser->getPreference('timezone'));
} else {
	$render->assign('timezone', 0);
}

$render->display();

if (!isJoomla15()) {
	die();
}
?>
