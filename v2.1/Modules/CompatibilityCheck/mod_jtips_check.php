<?php
/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
global $mosConfig_live_site;
include(dirname(__FILE__).'/mod_jtips_check/get_version_info.php');
$exts = get_loaded_extensions();
//Starting building the layout
?>
<table width="100%" border="0">
<tr>
<td>Joomla! Version</td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/<?php
if (_J1 == 1 and $breed < '12') {
	echo "publish_x";
    $alt = "Fail";
} else {
    echo "tick";
    $alt = "OK";
}
?>.png" border="0" alt="<?php echo $alt; ?>" /></td>
</tr>
<tr>
<td>PHP Version</td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/<?php
$phpversion = str_replace('.', '', phpversion());
if ($phpversion < '400') {
    echo "publish_x";
    $alt = "Fail";
} else {
    echo "tick";
    $alt = "OK";
}
?>.png" border="0" alt="<?php echo $alt; ?>" /></td>
</tr>
<tr>
<td>MySQL Version</td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/<?php
$mysqlversion = preg_replace('/[^\d]/', '', mysql_get_server_info());
if ($mysqlversion < '500') {
    echo "publish_x";
    $alt = "Fail";
} else {
    echo "tick";
    $alt = "OK";
}
?>.png" border="0" alt="<?php echo $alt; ?>" /></td>
</tr>
<tr>
<td>GD Library</td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/<?php
if (in_array('gd', $exts) or in_array('gd2', $exts)) {
	echo "tick";
    $alt = "OK";
} else {
    echo "publish_x";
    $alt = "Fail";
}
?>.png" border="0" alt="<?php echo $alt; ?>" /></td>
</tr>
<tr>
<td>ZIP Support</td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/<?php
if (in_array('zip', $exts)) {
	echo "tick";
    $alt = "OK";
} else {
    echo "publish_x";
    $alt = "Fail";
}
?>.png" border="0" alt="<?php echo $alt; ?>" /></td>
</tr>
<tr>
<td>cURL Support</td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/<?php
if (in_array('curl', $exts)) {
	echo "tick";
    $alt = "OK";
} else {
    echo "publish_x";
    $alt = "Fail";
}
?>.png" border="0" alt="<?php echo $alt; ?>" /></td>
</tr>
<tr>
<td>Javascript<sup>1</sup></td>
<td align="center"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/publish_x.png" border="0" id ="jtips_jscheck" /></td>
</tr>
<tr>
<td>Domain<sup>2</sup></td>
<td><?php echo preg_replace('/(www\.)|(www)/i', '', $_SERVER['SERVER_NAME']); ?></td>
</tr>
</table>
<small><sup>1</sup> Javascript can be enabled (and disabled) in your browset settings.</small>
<br /><small><sup>2</sup> Enter this domain when purchasing jTips.</small>
<p align="center"><input type="button" onclick="window.location.href='http://www.jtips.com.au/store.html';" value="Buy Now!" />
<br /><a href="http://www.jtips.com.au" title="Your Ultimate Tipping System">jTips 2.0</a></p>
<script type="text/javascript">
document.getElementById('jtips_jscheck').src = '<?php echo $mosConfig_live_site; ?>/administrator/images/tick.png';
</script>