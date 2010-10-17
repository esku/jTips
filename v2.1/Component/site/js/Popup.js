/**
 * Author: Jeremy Roberts
 * Company: jTips
 * Website: www.jtips.com.au
 * Licence: Commercial. May not be copied, modified or redistributed
 */
 
function openPopup(url, title, width, height) {
	if (!width) var width = jTipsShowTipsWidth;
	if (!height) var height = jTipsShowTipsHeight;
	MOOdalBox.open( // case matters
		jTipsSitePath + "index2.php?option=com_jtips&" + url, // the link URL
		title, // the caption (link's title) - can be blank
		width + " " + height // width and height of the box - can be left blank
	);
}

function loadTipsPopup(user_id, round_id, obj, data) {
	if (!data) var data = '';
	var url = 'view=CompetitionLadder&action=ShowTips&uid=' + user_id + '&rid=' + round_id + data;
	openPopup(url, obj.innerHTML);
}

function loadPreferencesPopup(task, itemid, season_url, obj) {
	var url = 'view=UserPreferences&return=' + task + '&Itemid=' + itemid + season_url;
	openPopup(url, $(obj).title);
}

function loadSavingPopup() {
	var url = 'task=saving';
	openPopup(url);
}

/**
 * Legacy function. Available in Joomla! 1.5 by default.
 */
if (typeof tableOrdering != 'function') {
	function tableOrdering( order, dir, task ) {
		var form = document.adminForm;
	
		form.filter_order.value 	= order;
		form.filter_order_Dir.value	= dir;
		if (task) {
			document.adminForm.task.value=task;
		}
		if (typeof document.adminForm.onsubmit == "function") {
			document.adminForm.onsubmit();
		}
		document.adminForm.submit();
	}
}

function toggleMore(idKey, obj) {
	var dom_id = 'moreInfo' + idKey;
	if ($(dom_id).style.display == 'none') {
		$(dom_id).setStyle('display', '');
	} else {
		$(dom_id).setStyle('display', 'none');
	}
	var imgSrc = obj.src;
	if (imgSrc.match('show')) {
		$(obj).setAttribute('src', imgSrc.replace(/show/, 'hide'));
	} else {
		$(obj).setAttribute('src', imgSrc.replace(/hide/, 'show'));
	}
}

function jTipsShowCalendar(el, format) {
	if (isJoomla15) {
		return showCalendar(el, format);
	} else {
		return showCalendar(el);
	}
}