function getOptions(obj) {
	$('loading').style.display = '';
	$('match_row').style.display = 'none';
	var fields = $$('select.importFields');
	var table = obj.options[obj.selectedIndex].value;
	$("match_on").options.length = 0;
	$("match_on").options[0] = new Option('--Import All--', '');
	var has_opts = false;
	new Ajax("index3.php", {
		method: "POST",
		data: "table=" + table + "&task=GetFields&option=com_jtips&module=Import",
		onSuccess: function(msg){
			for (i=0; i<fields.length; i++) { //>
				var curr_select = fields[i];
				curr_select.options.length = 0;
				curr_select.options[0] = new Option('--Do Not Import--', '-1');
				eval(msg);
				if (opts.length == 0) {
					curr_select.disabled = true;
				} else {
					has_opts = true;
					for (x=0; x<opts.length; x++) { //>
						temp_opt = opts[x];
						curr_select.options[x+1] = new Option();
						curr_select.options[x+1].value = opts[x].value;
						curr_select.options[x+1].text = opts[x].text;
					}
					curr_select.disabled = false;
				}
			}
			for (y=0; y<opts.length; y++) { //>
				$("match_on").options[y+1] = opts[y];
			}
			$('match_on').size = $('match_on').options.length;
			if (has_opts) {
				$("match_on").disabled=false;
				$('match_row').setStyle('display', '');
			} else {
				$("match_on").disabled=true;
				$('match_row').setStyle('display', 'none');
			}
		}
	}).request();
	$('loading').setStyle('display', 'none');
	$('match_on').size = $('match_on').options.length;
}

function submitbutton(pressbutton) {
	if (pressbutton == 'upload') {
		if ($('importFile').value == '') {
			alert('Select a file to upload');
			return false;
		} else if ($('importFile').value == 1) {
			alert('File is already uploaded');
			return false;
		}
	} else if (pressbutton == 'remove') {
		if ($('hasFile').value == '0') {
			alert('Nothing to delete');
			return false;
		} else {
			if (!confirm('Are you sure you wish to delete the import file?')) {
				return false;
			}
		}
	}
	submitform(pressbutton);
}