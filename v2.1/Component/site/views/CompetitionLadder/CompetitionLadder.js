function populateRounds(obj, el_id, rid) {
	if (parseInt(obj)) {
		var season_id = obj;
	} else {
		var season_id = $(obj).value;
	}
	if (!rid) {
		rid = 0;
	}
	//var url = "round_id=" + rid + "&season=" + season_id + "&element=" + el_id + "&option=com_jtips&task=ajax&func=populateRounds";
	var url = "round_id=" + rid + "&season_id=" + season_id + "&element=" + el_id + "&option=com_jtips&view=CompetitionLadder&action=GetRounds";
	new Ajax('index2.php', {
		method: 'POST',
		data: url,
		evalResponse: true
	}).request();
}

function goToRound(obj) {
	document.adminForm.submit();
}