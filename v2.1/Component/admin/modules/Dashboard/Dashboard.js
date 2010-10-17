function updatePackage() {
	$('liveupdatespan').innerHTML = '<img src=\"components/com_jtips/images/loading.gif\" alt=\"Updating\" border=\"0\" />';
	new Ajax("index3.php", {
		method: "POST",
		data: "task=OneClickUpgrade&option=com_jtips&module=Dashboard",
		onSuccess: function(msg){
			if (msg == 1) {
				var message = jTipsLang._ADMIN_DASH_UPG_SUCCESS;
			} else {
				var message = jTipsLang._ADMIN_DASH_UPG_FAILED + ': ' + msg;
			}
			$('liveupdatespan').innerHTML = message;
			if (msg == 1) {
				setTimeout("window.location.href='index2.php?option=com_jtips&module=Dashboard&task=Validate';", 1500);
			}
		}
	}).request();
}

function sendRebuildRequest(dom_id) {
	var confirmStr = jTipsLang._ADMIN_DASH_REBUILD_WARNING;
	if (!confirm(confirmStr)) {
		return;
	}
	$(dom_id).innerHTML = '<img src=\"components/com_jtips/images/loading.gif\" alt=\"Rebuilding...\" title=\"Rebuilding\" border=\"0\" />';
	new Ajax("index3.php", {
		method: "POST",
		data: "task=RebuildSQL&option=com_jtips&module=Dashboard",
		onSuccess: function(msg){
			$(dom_id).innerHTML = jTipsLang._ADMIN_DASH_REBUILD_TITLE + ': ' + msg;
		}
	}).request();
}

function updateResult(result) {
	document.location.href='index3.php?option=com_jtips&mosmsg=' + escape(result);
}

function liveCheckLatestVersion(dom_id) {
	$(dom_id).innerHTML = '<img src=\"components/com_jtips/images/loading.gif\" alt=\"Checking...\" title=\"Checking\" border=\"0\" />';
	new Ajax("index3.php", {
		method: "POST",
		data: "task=CheckLatestVersion&option=com_jtips&module=Dashboard",
		onSuccess: function(msg){
			//msg = 'error'; //For testing only
			$(dom_id).innerHTML = msg;
			
			//check we can parse as int first
			if (!parseInt(msg.toString().replace(/[^\d]/ig, ''))) {
				return;
			}
			
			//Split on decimals
			var latestVersion = msg.toString().split('.', 3);
			var thisVersion = jTipsCurrentVersion.split('.', 3);
			
			//now compare each part
			var doUpgrade = false;
			for (var i=0; i<5; i++) { // -->
				//make sure both parts are numbers
				if ((!parseInt(latestVersion[i]) && latestVersion[i] != 0) || (!parseInt(thisVersion[i]) && thisVersion[i] != 0)) {
					continue;
				}
				//alert('comparing ' + latestVersion[i] + ' v ' + thisVersion[i]); //Debugging only
				if (parseInt(latestVersion[i]) > parseInt(thisVersion[i])) {
					doUpgrade = true;
					break;
				}
			}
			if (doUpgrade) {
				$('liveupdate').value = jTipsLang._ADMIN_CONF_UPDATE_NOW;
				$('liveupdate').disabled = false;
			}
		}
	}).request();
}