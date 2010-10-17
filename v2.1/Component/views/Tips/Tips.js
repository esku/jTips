var teamLadder;

function validateComment(cid) {
	var comment = $(cid).value;
	if (comment.length == 0) {
		document.tips_form.submit();
		return;
	}
	$('ajaxloading').setHTML("<img src='" + jTipsLiveSite + "/components/com_jtips/images/saving.gif' alt='Saving...' border='0' /><br /><strong>" + jTipsLang._COM_COMMENT_VALIDATING + "</strong>");
	$('ajaxloading').setStyle('display', 'block');
	var url = "comment=" + escape(comment) + "&option=com_jtips&view=Tips&action=CheckComment";
	new Ajax('index2.php?Itemid=' + jTipsItemid, {
		method: 'POST',
		data: url,
		onComplete: validateComRes
	}).request();
}

function validateComRes(result) {
	if (result == -1) {
		$('ajaxloading').innerHTML = "<span style='font-weight:bold;'>" + jTipsLang._COM_COMMENT_VALIDATED + "</span>";
		document.tips_form.submit();
	} else if (result == 0) {
		$('ajaxloading').innerHTML = "<span style='font-weight:bold;color:red;'>" + jTipsLang._COM_COMMENT_NOT_ALLOWED + "</span>";
		return false;
	} else if (result == 1) {
		$('ajaxloading').innerHTML = "<span style='font-weight:bold;'>" + jTipsLang._COM_COMMENT_REPLACED + "</span>";
		document.tips_form.submit();
	}
	return false;
}

function setSelectedTeam(gid) {
	try {
		var homeScore = $('home' + gid).value;
		var awayScore = $('away' + gid).value;
		//BUG 215 - Remove default 0 when empty
		if (!parseInt(homeScore) && homeScore != '0' && homeScore != '') {
			$('home' + gid).value = '';
			setSelectedTeam(gid);
		}
		if (!parseInt(awayScore) && awayScore != 0 && awayScore != '') {
			$('away' + gid).value = '';
			setSelectedTeam(gid);
		}
		var parsedHomeScore = parseInt(homeScore);
		var parsedAwayScore = parseInt(awayScore)
		if (parsedHomeScore > parsedAwayScore) {
			$('home_game' + gid).checked = true;
		} else if (parsedHomeScore < parsedAwayScore) { //>
			$('away_game' + gid).checked = true;
		} else if (parsedHomeScore == parsedAwayScore) {
			try {
				$('draw_game' + gid).checked = true;
			} catch (e) {
				//draws can not be selected
				$('away_game' + gid).checked = false;
				$('home_game' + gid).checked = false;
				//alert(e);
			}
		}
	} catch (err) {
		//the team select radio fields are disabled/hidden
	}
}

function switchScore(gid) {
	try {
	    var homeScore = $('home' + gid).value;
		var awayScore = $('away' + gid).value;
		$('away_game' + gid);
		$('home_game' + gid);
		if ($('away_game' + gid).checked && parseInt(homeScore) > parseInt(awayScore)) {
		    //away team selected, make sure it has the highest score
		    $('home' + gid).value = awayScore;
		    $('away' + gid).value = homeScore;
		} else if ($('home_game' + gid).checked && parseInt(homeScore) < parseInt(awayScore)) { //>
		    //home team selected, make sure it has the highest score
		    $('home' + gid).value = awayScore;
		    $('away' + gid).value = homeScore;
		} else {
		    try {
		        if ($('draw_game' + gid).checked && parseInt(homeScore) != parseInt(awayScore)) {
				    //draw selected, make sure it has the highest score
				    $('home' + gid).value = awayScore;
				    $('away' + gid).value = awayScore;
		        }
		    } catch (e) {
		        //draw not available
		        //do nothing
		    }
		}
		setSelectedTeam(gid);
	} catch (err) {
		//the team select radio fields are disabled
	}
}
