function submitbutton(pressbutton) {
	if (pressbutton == 'edit' && document.adminForm.boxchecked.value == 0) {
		alert('Please make a selection from the list to edit');
	} else {
		hideMainMenu();
		if (pressbutton == 'new') {
			pressbutton = 'edit';
		}
		submitform(pressbutton);
	}
}