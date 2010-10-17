editAreaLoader.init({
	id : 'filecontent'		// textarea id
	,syntax: 'php'			// syntax to be uses for highgliting
	,start_highlight: true		// to display with highlight mode on start-up
});

function submitbutton(pressbutton) {
	$('filecontent').setProperty('value', editAreaLoader.getValue("filecontent"));
	try {
		if ($('view').value == '' && pressbutton != 'list') {
			alert(jTipsLang._ADMIN_CSTM_SELECT_VIEW);
		} else {
			submitform(pressbutton);
		}
	} catch (err) {
		submitform(pressbutton);
	}
}

function toggleFileName(obj) {
	$('filename').disabled = obj.checked;
}
