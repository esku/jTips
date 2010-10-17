function toggleReplace() {
	var action = $('action').value;
	if (action == 'replace') {
		var replace = false;
	} else {
		var replace = true;
	}
	$('replace').disabled = replace;
}
window.addEvent('domready', toggleReplace);