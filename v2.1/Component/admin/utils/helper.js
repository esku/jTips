// needed for Table Column ordering
function tableOrdering( order, dir, task ) {
	var form = document.adminForm;
	form.filter_order.value 	= order;
	form.filter_order_Dir.value	= dir;
	submitform( task );
}

function jTipsShowCalendar(el, format) {
	if (isJoomla15) {
		return showCalendar(el, format);
	} else {
		return showCalendar(el);
	}
}