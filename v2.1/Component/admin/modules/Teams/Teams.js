function submitbutton(pressbutton) {
	if (pressbutton == 'save' && $("season_id").value == "") {
		alert(jTipsLang._ADMIN_SEASON_SELECT_ALERT);
		return;
	} else {
		submitform(pressbutton);
	}
}