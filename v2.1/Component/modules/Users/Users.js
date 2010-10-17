function filterUsers(obj) {
	var season = obj.options[obj.selectedIndex].value;
	var suffix = '';
	if (season != '') {
		suffix = '&season_id=' + season;
	}
	window.location.href='index2.php?option=com_jtips&task=list&module=Users' + suffix;
}

function toggleBoolean(user_id, field) {
	new Ajax("index3.php", {
		method: "POST",
		data: "user_id=" + user_id + "&field=" + field + "&task=ToggleBoolean&option=com_jtips&module=Users",
		onSuccess: function(msg){
			$(field + '_' + user_id).src = 'images/' + msg + '.png';
		}
	}).request();
}

function submitbutton(pressbutton) {
	if (pressbutton=='remove') { slider.show(); }
	submitform(pressbutton);
}

window.addEvent('domready', function() {
	slider = new Fx.Slide('progressbar', {duration: 500});
	slider.hide();
});