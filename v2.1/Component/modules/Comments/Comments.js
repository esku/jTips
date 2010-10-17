function getTheRounds(obj) {
	//$('ajaxLoading').innerHTML = '<img src="components/com_jtips/images/loading.gif" /> <strong>' + jTipsLang._ADMIN_COMM_POSTED + '</strong>';
	var season = obj.options[obj.selectedIndex].value;
	var roundid = $('orig_round_id').value;
	if (roundid == '') {
		roundid = 0;
	}
	var select_list = $('round_id');
	select_list.length = 0;
	select_list.disabled = true;
	select_list.options[0] = new Option('Loading...', '');
	new Ajax("index3.php", {
		method: "POST",
		data: "season_id=" + season + "&round_id=" + roundid + "&task=GetExistingRounds&option=com_jtips&module=Comments",
		evalResponse: true,
		onSuccess: function(msg){
			//$('ajaxLoading').innerHTML = '';
		}
	}).request();
}
function addOptions(result) {
	eval(result);
	//$('ajaxLoading').setStyle('display', 'none');
}

function submitbutton(pressbutton) {
	if (pressbutton == 'save' && $("season_id").value == "") {
		alert(jTipsLang._ADMIN_SEASON_SELECT_ALERT);
		return;
	} else {
		submitform(pressbutton);
	}
}

window.addEvent('domready', function() {
	getTheRounds($('season_id'));
});