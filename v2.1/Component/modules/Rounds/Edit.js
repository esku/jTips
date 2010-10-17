//declare this object for use later
var jSeason = {};
var gameRowIndex = 0;
var jTeams = [];
var jGames = [];

function showTable() {
	var defaultShowTableStyle = 'table'
	if (window.ie) {
		defaultShowTableStyle = 'block';
	}
	$('round_loading').setStyle('display', 'none');
	$('games_list').setStyle('display', defaultShowTableStyle);
	$('games_list').effect('opacity', {duration: 5000}).start(100);
}

/**
 * Retrieve a JSON object of the selected jSeason.
 * Display or hide additional columns as per the setting on the jSeason object
 */
function getSeasonDetails() {
	if ($('season_id').value === '') {
		return '';
	}
	
	var gsd = new Ajax("index3.php", {
		method: "POST",
		data: "season_id=" + escape($('season_id').value) + "&task=GetSeasonObject&option=com_jtips&module=Rounds",
		//evalResponse: true,
		onSuccess: function(msg) {
			jSeason = Json.evaluate(msg);
			//return true;
		}
	});
	return gsd.request();
}

function toggleColumns() {
	//which browser
	var defaultShowCellStyle = 'table-cell'
	if (window.ie) {
		defaultShowCellStyle = '';
	}
	
	//hide columns that shouldnt be here
	if (jSeason.pick_score === '1') {
		$$('.pick_score').setStyle('display', defaultShowCellStyle);
	} else {
		$$('.pick_score').setStyle('display', 'none');
	}
	if (jSeason.pick_margin === '1') {
		$$('.pick_margin').setStyle('display', defaultShowCellStyle);
	} else {
		$$('.pick_margin').setStyle('display', 'none');
	}
	if (parseInt(jSeason.pick_bonus) > 0) {
		$$('.pick_bonus').setStyle('display', defaultShowCellStyle);
	} else {
		$$('.pick_bonus').setStyle('display', 'none');
	}
	if (jSeason.team_starts === '1') {
		$$('.team_starts').setStyle('display', defaultShowCellStyle);
	} else {
		$$('.team_starts').setStyle('display', 'none');
	}
	//BUG 248 - Tough Score
	if (jSeason.tough_score === '1') {
		$$('.tough_score').removeClass('hide');
	} else {
		$$('.tough_score').addClass('hide');
	}
	if (jSeason.game_times === '1') {
		$$('.date_time').removeClass('hide');
	} else {
		$$('.date_time').addClass('hide');
	}
	//set the column headers
	var leftKey = 'HOME';
	var rightKey = 'AWAY';
	if (jSeason.tips_layout === 'away') {
		leftKey = 'AWAY';
		rightKey = 'HOME';
	}
	
	$('left_team_th').setHTML(eval("jTipsLang._ADMIN_ROUND_" + leftKey));
	$('right_team_th').setHTML(eval("jTipsLang._ADMIN_ROUND_" + rightKey));
	$('left_start_th').setHTML(eval("jTipsLang._ADMIN_GAME_" + leftKey + "_START"));
	$('right_start_th').setHTML(eval("jTipsLang._ADMIN_GAME_" + rightKey + "_START"));
	$('tough_score_th').setHTML(eval("jTipsLang._ADMIN_GAME_TOUGH_SCORE"));
	$('left_score_th').setHTML(eval("jTipsLang._ADMIN_GAME_" + leftKey + "SCORE"));
	$('right_score_th').setHTML(eval("jTipsLang._ADMIN_GAME_" + rightKey + "SCORE"));
	
	//ishe round over or should the additional columns be shown
	var d = new Date();
	//var date = d.getDate();
	//if (date < 10) {
	//	date = '0' + date;
	//}
	//var now = d.getFullYear() + '-' + (d.getMonth()+1) + '-' + date + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
	var now = getTimeNow();
	if ($('season_id').value !== '' && (jRound.scored === '1' || (jRound.end_time < now && jRound.scored === '0'))) {
		$$('.results').setStyle('display', defaultShowCellStyle);
		if (parseInt(jSeason.pick_bonus) > 0) {
			$$('.bonus_results').setStyle('display', defaultShowCellStyle);
		}
	} else {
		$$('.results').setStyle('display', 'none');
		$$('.bonus_results').setStyle('display', 'none');
	}
	return showTable();
}

function getTeamName(id) {
	for (var i=0; i<jTeams.length; i++) {
		if (jTeams[i].id === id) {
			return jTeams[i].location + ' ' + jTeams[i].name;
		}
	}
	return jTipsLang._COM_BYE;
}

function getBoolOptions() {
	var boolOptions = [];
	for (var i=0; i<2; i++) {
		var text = jTipsLang._COM_YES;
		if (i === 0) {
			text = jTipsLang._COM_NO;
		}
		var opt = new Element('option', {
			'value': i,
			'label': text,
			'text':text
		}).appendText(text);
		boolOptions.push(opt);
	}
	return boolOptions;
}

function getTimeNow() {
	var d = new Date();
	var date = d.getDate();
	if (date < 10) date = '0' + date;
	var month = d.getMonth() + 1;
	if (month < 10) month = '0' + month;
	var hours = d.getHours();
	if (hours < 10) hours = '0' + hours;
	var mins = d.getMinutes();
	if (mins < 10) mins = '0' + mins;
	var secs = d.getSeconds();
	if (secs < 10) secs = '0' + secs;
	var now = d.getFullYear() + '-' + month + '-' + date + ' ' + hours + ':' + mins + ':' + secs;
	//alert(now);
	return now;
}   

function addGameRow(jGame) {
	if (typeof jGame !== 'object') {
		jGame = {};
	}
	
	//is the round over or should the additional columns be shown
	var d = new Date();
//	var date = d.getDate();
//	if (date < 10) {
//		date = '0' + date;
//	}
//	var now = d.getFullYear() + '-' + (d.getMonth()+1) + '-' + date + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
	var now = getTimeNow();
	var isDisabled = false;
	if (jRound.scored === '1' || (jRound.end_time < now && jRound.scored === '0')) {
		isDisabled = true;
	}
	
	var row = new Element('tr', {
		'class': 'row' + (gameRowIndex%2)
	});
	
	//Add the integer cell (the leftmost cell)
	//var intCell = new Element('td');
	//intCell.setHTML(gameRowIndex+1);
	//row.adopt(intCell);
	
	//Add the checkbox cell
	var cbCell = new Element('td', {
		'style':'text-align:center'
	});
	var game_id = null;
	if (jGame.id) {
		game_id = jGame.id;
	}
	cbField = new Element('input', {
		'type':'hidden',
		'name':'game[]',
		'value':game_id
	});
	cbCell.adopt(cbField);
	if (!isDisabled) {
		var cb = new Element('input', {
			'type': 'checkbox',
			'name': 'cb',
			'id' : 'cb' + gameRowIndex,
			'name' : 'cid[]',
			'class' : 'inputbox cb'
		});
		cbCell.adopt(cb);
	} else {
		cbCell.appendText(gameRowIndex+1);
	}
	row.adopt(cbCell);
	
	//Add the Home Team dropdown cell
	var homeCell = new Element('td');
	if (!isDisabled) {
		var homeDD = new Element('select', {
			'class' : 'inputbox',
			'name': 'home_id[]',
			'value': jGame.home_id
		});
		homeDD.adopt(getTeamOptions(jGame.home_id));
		homeCell.adopt(homeDD);
	} else {
		homeField = new Element('input', {
			'name': 'home_id[]',
			'value': jGame.home_id,
			'type': 'hidden'
		});
		homeCell.adopt(homeField);
		homeCell.appendText(getTeamName(jGame.home_id));
	}
	
	//Add the Away Team dropdown cell
	var awayCell = new Element('td');
	if (!isDisabled) {
		var awayDD = new Element('select', {
			'class' : 'inputbox',
			'name': 'away_id[]',
			'value': jGame.away_id
		});
		awayDD.adopt(getTeamOptions(jGame.away_id));
		awayCell.adopt(awayDD);
	} else {
		awayField = new Element('input', {
			'name': 'away_id[]',
			'value': jGame.away_id,
			'type': 'hidden'
		});
		awayCell.adopt(awayField);
		awayCell.appendText(getTeamName(jGame.away_id));
	}
	if (jSeason.tips_layout === 'away') {
		row.adopt(awayCell);
		row.adopt(homeCell);
	} else {
		row.adopt(homeCell);
		row.adopt(awayCell);
	}
		
	
	//Add the Order cell
	var orderCell = new Element('td', {
		'style': 'text-align:center'
	});
	var position = '';
	if (jGame.position) {
		position = jGame.position;
	}
	var orderField = new Element('input', {
		'class': 'inputbox',
		'size': 5,
		'type': 'text',
		'value': position,
		'style': 'text-align:center',
		'disabled': isDisabled,
		'autoComplete':'off',
		'name':'position[]'
	});
	orderCell.adopt(orderField);
	row.adopt(orderCell);
	
	//Pick the score
	var scoreCell = new Element('td', {
		'style': 'text-align:center',
		'class': 'pick_score'
	});
	
	if (isDisabled) {
		if (jGame.has_score == '1') {
			scoreCell.appendText(jTipsLang._COM_YES);
		} else {
			scoreCell.appendText(jTipsLang._COM_NO);
		}
	} else {
		var scoreDD = new Element('select', {
			'class':'inputbox score',
			'name':'has_score[]',
			'disabled': isDisabled
		});
		scoreDD.adopt(getBoolOptions());
		scoreDD.setProperty('value', jGame.has_score);
		scoreCell.adopt(scoreDD);
	}
	row.adopt(scoreCell);
	
	//Pick the margin
	var marginCell = new Element('td', {
		'style': 'text-align:center',
		'class': 'pick_margin'
	});
	if (isDisabled) {
		if (jGame.has_margin == '1') {
			marginCell.appendText(jTipsLang._COM_YES);
		} else {
			marginCell.appendText(jTipsLang._COM_NO);
		}
	} else {
		var marginDD = new Element('select', {
			'class':'inputbox margin',
			'name':'has_margin[]',
			'disabled': isDisabled
		});
		marginDD.adopt(getBoolOptions());
		marginDD.setProperty('value', jGame.has_margin);
		marginCell.adopt(marginDD);
	}
	row.adopt(marginCell);
	
	//Pick the bonus
	var bonusCell = new Element('td', {
		'style': 'text-align:center',
		'class': 'pick_bonus'
	});
	if (isDisabled) {
		if (jGame.has_bonus == '1') {
			bonusCell.appendText(jTipsLang._COM_YES);
		} else {
			bonusCell.appendText(jTipsLang._COM_NO);
		}
	} else {
		var bonusDD = new Element('select', {
			'class':'inputbox bonus',
			'name':'has_bonus[]',
			'disabled': isDisabled,
			'value':jGame.has_bonus
		});
		bonusDD.adopt(getBoolOptions());
		bonusDD.setProperty('value', jGame.has_bonus);
		bonusCell.adopt(bonusDD);
	}
	row.adopt(bonusCell);
	
	//home start
	var homeStartCell = new Element('td', {
		'style':'text-align:center',
		'class':'team_starts'
	});
	var homeStartValue = '';
	if (jGame.home_start) {
		homeStartValue = jGame.home_start;
	}
	var homeStartField = new Element('input', {
		'class': 'inputbox',
		'size': 5,
		'type': 'text',
		'value': homeStartValue,
		'style': 'text-align:center',
		'autoComplete':'off',
		'name':'home_start[]',
		'disabled':isDisabled
	});
	homeStartCell.adopt(homeStartField);
	//row.adopt(homeStartCell);
	
	//away start
	var awayStartCell = new Element('td', {
		'style':'text-align:center',
		'class':'team_starts'
	});
	var awayStartValue = '';
	if (jGame.away_start) {
		awayStartValue = jGame.away_start;
	}
	var awayStartField = new Element('input', {
		'class': 'inputbox',
		'size': 5,
		'type': 'text',
		'value': awayStartValue,
		'style': 'text-align:center',
		'autoComplete':'off',
		'name':'away_start[]',
		'disabled':isDisabled
	});
	awayStartCell.adopt(awayStartField);
	//row.adopt(awayStartCell);
	
	if (jSeason.tips_layout === 'away') {
		row.adopt(awayStartCell);
		row.adopt(homeStartCell);
	} else {
		row.adopt(homeStartCell);
		row.adopt(awayStartCell);
	}
	
	//TOUGH SCORE FIELD
	var toughScoreCell = new Element('td', {
		'class':'tough_score',
		'style':'text-align:center'
	});
	if (isDisabled) {
		toughScoreCell.appendText(jGame.tough_score);
	} else {
		var toughScoreField = new Element('input', {
			'class': 'inputbox',
			'size': 5,
			'type': 'text',
			'value': jGame.tough_score,
			'style': 'text-align:center',
			'autoComplete':'off',
			'name':'tough_score[]'
		});
		toughScoreCell.adopt(toughScoreField);
	}
	row.adopt(toughScoreCell);
	
	//game start date and time fields in one cell
	var gameTimeCell = new Element('td', {
		'class':'date_time'
	});
	var gameTimeDateField = new Element('input', {
		'type':'text',
		'name':'start_time_date[]',
		'id':'start_time_date' + gameRowIndex,
		'class':'inputbox',
		'autoComplete':'off',
		'value':getDate(jGame.start_time)
	});
	//position:absolute; width:200px; height:100px; left:50%; top:50%; margin-top:-50px; margin-left:-100px;
	gameTimeCell.adopt(gameTimeDateField);
	gameTimeCell.appendText(' ');
	var gameTimeCalendar = new Element('img', {
		'src':'components/com_jtips/images/calendar.png',
		'border':0,
		'alt':'...',
		'align':'absmiddle'
	});
	var thisRowIndex = 'start_time_date' + gameRowIndex;
	gameTimeCalendar.onclick = function(event) {
		return showCalendar(thisRowIndex, unescape(DateFormat));
	};
	gameTimeCell.adopt(gameTimeCalendar);
	gameTimeCell.appendText(' ');
	var gameTimeHourDD = new Element('select', {
		'class':'inputbox',
		'name':'start_time_hour[]'
	});
	if (Is24Hour) {
		var end_hour = 24;
	} else {
		var end_hour = 12;
	}
	gameTimeHourDD.adopt(getIntegerOptions(1, end_hour, 1));
	gameTimeHourDD.setProperty('value', getHour(jGame.start_time));
	gameTimeCell.adopt(gameTimeHourDD);
	gameTimeCell.appendText(' : ');
	var gameTimeMinuteDD = new Element('select', {
		'class':'inputbox',
		'name':'start_time_minute[]'
	});
	if (MinuteValues == 1) {
		// use all minutes
		var min_end = 59;
		var min_step = 1;
	} else {
		var min_end = 55;
		var min_step = 5;
	}
	gameTimeMinuteDD.adopt(getIntegerOptions(0, min_end, min_step));
	gameTimeMinuteDD.setProperty('value', getMinute(jGame.start_time));
	gameTimeCell.adopt(gameTimeMinuteDD);
	if (!Is24Hour) {
		var gameTimeMeridiemDD = new Element('select', {
			'class':'inputbox',
			'name':'start_time_meridiem[]'
		});
		var meridiemOptions = [
			new Element('option', {'text':'am', 'value':'am', 'label':'am'}).appendText('am'),
			new Element('option', {'text':'pm', 'value':'pm', 'label':'pm'}).appendText('pm')
		];
		gameTimeMeridiemDD.adopt(meridiemOptions);
		gameTimeMeridiemDD.setProperty('value', getMeridiem(jGame.start_time));
		gameTimeCell.appendText(' ');
		gameTimeCell.adopt(gameTimeMeridiemDD);
	}
	row.adopt(gameTimeCell);
	
	//show extra fields depending on the status of the round
	if (isDisabled) {
		//show extra field inputs
		
		//the home score
		var homeScoreCell = new Element('td', {
			'style': 'text-align:center'
		});
		var homeScoreField = new Element('input', {
			'class': 'inputbox',
			'size': 5,
			'type': 'text',
			'value': jGame.home_score,
			'style': 'text-align:center',
			'autoComplete':'off',
			'name':'home_score[]'
		});
		homeScoreCell.adopt(homeScoreField);
		//row.adopt(homeScoreCell);
		
		//the away score
		var awayScoreCell = new Element('td', {
			'style': 'text-align:center'
		});
		var awayScoreField = new Element('input', {
			'class': 'inputbox',
			'size': 5,
			'type': 'text',
			'value': jGame.away_score,
			'style': 'text-align:center',
			'autoComplete':'off',
			'name':'away_score[]'
		});
		awayScoreCell.adopt(awayScoreField);
		//row.adopt(awayScoreCell);
		//alert(jSeason.tips_layout);
		if (jSeason.tips_layout === 'away') {
			row.adopt(awayScoreCell);
			row.adopt(homeScoreCell);
		} else {
			row.adopt(homeScoreCell);
			row.adopt(awayScoreCell);
		}
		
		//the bonus point selector
		var bonusPointCell = new Element('td');
		var bonusPointDD = new Element('select', {
			'class':'inputbox bonus',
			'name':'bonus_id[]',
			'style':'width:100%'
		});
		var bonusPointOptions = [
			new Element('option', {'text':jTipsLang._ADMIN_CONF_NONE, 'value':''}).appendText(jTipsLang._ADMIN_CONF_NONE),
			new Element('option', {'text':getTeamName(jGame.home_id), 'value':jGame.home_id}).appendText(getTeamName(jGame.home_id)),
			new Element('option', {'text':getTeamName(jGame.away_id), 'value':jGame.away_id}).appendText(getTeamName(jGame.away_id)),
			new Element('option', {'text':jTipsLang._ADMIN_SEASON_EPTB_BOT, 'value':-2}).appendText(jTipsLang._ADMIN_SEASON_EPTB_BOT)
		];
		bonusPointDD.adopt(bonusPointOptions);
		bonusPointDD.setProperty('value', jGame.bonus_id);
		bonusPointCell.adopt(bonusPointDD);
		row.adopt(bonusPointCell);
		
	}

	// show a free text field
	var infoCell = new Element('td', {
		'style': 'text-align:center',
		'class': 'info hide'
	});
	// BUG 395 - description shows up as undefined
	if (!jGame.description || typeof jGame.description == undefined) {
		jGame.description = '';
	}
	var info = new Element('textarea', {
		'rows': 3,
		'cols': 25,
		'name':'description[]',
		'class':'inputbox',
		'value':unescape(jGame.description).replace(/\\/g, '')
	});
	infoCell.adopt(info);
	row.adopt(infoCell);
	
	//add the row to the table
	$('table').adopt(row);
	//toggleColumns();
	gameRowIndex++;
}



/**
 * Check if the selected round has games. If it does, populate the game table
 * and disable the round select dropdown
 */
function populateGames() {
	//if ($('season_id').value === '' || $('id').value === '') return;
	var pg = new Ajax("index3.php", {
		method: "POST",
		data: "option=com_jtips&module=Rounds&task=LoadGames&round_id=" + escape($('id').value),
		onSuccess: function (res) {
			jGames = Json.evaluate(res);
			for (var i=0; i<jGames.length; i++) {
				addGameRow(jGames[i]);
			}
		}
	});
	return pg.request();
}

/**
 * Delete the selcted rows from the games table
 */
function removeGameRow() {
	//which checkboxes are selected
	var cb = $$('.cb');
	var showAlert = true;
	for (var i=0; i<cb.length; i++) {
		if (cb[i].checked) {
			$(cb[i]).getParent().getParent().remove();
			//gameRowIndex--;
			showAlert = false;
		}
	}
	if (showAlert) {
		alert(jTipsLang._ADMIN_CONFG_GAME_REMOVE_NONE);
	}
}





/**
 * Get an array of Options to be added to a dropdow
 * 
 * @param id The id of the selected option
 * 
 * @return array An array of Option objects
 */
function getTeamOptions(id) {
	var teamOptions = [];
	for (var x=0; x<jTeams.length; x++) {
		var opt = new Element('option', {
			label: (jTeams[x].location + ' ' + jTeams[x].name).replace(/\\/g, ''),
			value: jTeams[x].id
		}).appendText((jTeams[x].location + ' ' + jTeams[x].name).replace(/\\/g, ''));
		if (id === jTeams[x].id) {
			opt.setProperty('selected', true);
		}
		teamOptions.push(opt);
	}
	// BUG 291 - no BYE option available
	var byeOpt = new Element('option', {
		label: '--Bye--',
		value: ''
	}).appendText('--Bye--');
	if (id == '' || !id || id == '0') {
		byeOpt.setProperty('selected', true);
	}
	teamOptions.push(byeOpt);
	return teamOptions;
}

/**
 * Use ajax to retrieve the array of teams for the selected season
 */
function getTeams() {
	if ($('season_id').value === '') {
		return '';
	}
	//return false;
	var gt = new Ajax("index3.php", {
		method: "POST",
		data: "option=com_jtips&module=Rounds&task=LoadTeams&season_id=" + escape($('season_id').value),
		onSuccess: function(res) {
			jTeams = Json.evaluate(res);
			//return true;
		}
	});
	return gt.request();
}

function toggleYesNo(obj, key) {
	var items = $$('.' + key);
	for (var i=0; i<items.length; i++) {
		if ($(obj).checked) {
			items[i].selectedIndex = 1;
		} else {
			items[i].selectedIndex = 0;
		}
	}
}

function getIntegerOptions(start, end, step) {
	var intOptions = [];
	for (var i=start; i<=end; i+=step) {
		var d = i;
		if (d === 0) {
			d = '00';
		}
		var opt = new Element('option', {
			'text':d,
			'value':i,
			'label':d
		}).appendText(d);
		intOptions.push(opt);
	}
	return intOptions;
}

function getDate(datetime) {
	if (!datetime || datetime === '0000-00-00 00:00:00') {
		return '';
	}
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	return d.strftime(unescape(DateFormat));
}

function getHour(datetime) {
	if (!datetime || datetime === '0000-00-00 00:00:00') {
		return '';
	}
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	if (Is24Hour) {
		return d.toString('H');
	} else {
		return d.toString('h');
	}
}

function getMinute(datetime) {
	if (!datetime || datetime === '0000-00-00 00:00:00') {
		return '';
	}
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	return d.toString('m');
}

function getMeridiem(datetime) {
	if (!datetime || datetime === '0000-00-00 00:00:00') {
		return '';
	}
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	var hour = d.toString('H');
	if (hour >= 12) {
		return 'pm';
	} else {
		return 'am';
	}
}

/**
 * Override the default seubmit function to make sure a season has been selected
 * 
 * @param string The task to execute, or the button that has been pressed
 */
function submitbutton(pressbutton) {
	if (pressbutton === 'save' && $('season_id').value === '') {
		alert(jTipsLang._ADMIN_SEASON_SELECT_ALERT);
		return;
	} else {
		submitform(pressbutton);
	}
}

function getTheRounds(obj) {
	//$('ajaxLoading').innerHTML = '<img src="components/com_jtips/images/loading.gif" /> <strong>Loading...</strong>';
	//BUG 257 - prevent 'options' undefined error
	var season = $(obj).value;
	var roundid = $('id').value;
	if (roundid === 'false') {
		roundid = 0;
	}
	
	jSeason = {};
	gameRowIndex = 0;
	jTeams = [];
	var select_list = $('roundnum');
	select_list.length = 0;
	select_list.disabled = true;
	select_list.options[0] = new Option('Loading...', '');
	var myAjax = new Ajax("index3.php", {
		method: "POST",
		data: "season_id=" + season + "&round_id=" + roundid + "&task=GetRounds&option=com_jtips&module=Rounds",
		evalResponse: true,
		onSuccess: function(msg){
			//document.getElementById('ajaxLoading').innerHTML = '';
			
		}
	});
	var seasonID = $('season_id').getProperty('value');
	//Execute each request after the other
	myAjax.request().chain(function() {
		if (seasonID !== '') {
			getSeasonDetails().chain(function() {
				getTeams().chain(function() {
					if (seasonID !== '') {
						populateGames().chain(function() {
							toggleColumns();
							//showTable();
						});
					} else {
						toggleColumns();
						//showTable();
					}
				});
			});
		} else {
			toggleColumns();
			//showTable();
		}
	});
	try {
		if (seasonID !== '') {
			$('addgame').setProperty('disabled', false);
			$('removegame').setProperty('disabled', false);
		} else {
			$('addgame').setProperty('disabled', true);
			$('removegame').setProperty('disabled', true);
		}
	} catch (err) {
		//alert(err);
		//buttons are not being displayed
	}
}
