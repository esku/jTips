//declare this object for use later
var jSeason = {};
var gameRowIndex = 0;
var jTeams = [];
var jGames = [];

function initAdministration() {
	getSeasonDetails().chain(function() {
		getTeams().chain(function() {
			populateGames();
		})
	});
}

/**
 * Check if the selected round has games. If it does, populate the game table
 * and disable the round select dropdown
 */
function populateGames() {
	//if ($('season_id').value == '' || $('id').value == '') return;
	
	var pg = new Ajax("index2.php?Itemid=" + jTipsItemid, {
		method: "POST",
		data: "option=com_jtips&view=Administration&action=LoadGames&round_id=" + escape($('id').value) + "&season_id=" + escape($('season_id').value),
		onSuccess: function (res) {
			jGames = Json.evaluate(res);
			for (var i=0; i<jGames.length; i++) {
				addGameRow(jGames[i]);
			}
		}
	});
	return pg.request();
}

function addGameRow(jGame) {
	if (typeof jGame != 'object') {
		jGame = {};
	}
	
	if (jTeams.length == 0) {
		return getTeams().chain(function() {
			return addGameRow(jGame);
		});
	}
	
	var row = new Element('tr', {
		'class': 'sectiontableentry' + ((gameRowIndex%2)+1)
	});
	
	//the remove checkbox
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
	var cb = new Element('input', {
		'type': 'checkbox',
		'name': 'cb',
		'id' : 'cb' + gameRowIndex,
		'name' : 'cid[]',
		'class' : 'inputbox cb'
	});
	cbCell.adopt(cb);
	row.adopt(cbCell);
	
	//make the home team dropdown
	var homeCell = new Element('td', {
		'style':'text-align:left'
	});
	var homeDD = new Element('select', {
		'class' : 'inputbox',
		'name': 'home_id[]',
		'value': jGame.home_id
	});
	homeDD.adopt(getTeamOptions(jGame.home_id));
	homeCell.adopt(homeDD);
	
	//Add the Away Team dropdown cell
	var awayCell = new Element('td', {
		'style':'text-align:left'
	});
	var awayDD = new Element('select', {
		'class' : 'inputbox',
		'name': 'away_id[]',
		'value': jGame.away_id
	});
	awayDD.adopt(getTeamOptions(jGame.away_id));
	awayCell.adopt(awayDD);

	if (jSeason.tips_layout == 'away') {
		row.adopt(awayCell);
		row.adopt(homeCell);
	} else {
		row.adopt(homeCell);
		row.adopt(awayCell);
	}
	
	//build the available pick types multiselect
	var pickCell = new Element('td', {'class':'pick'});
	var pickDD = new Element('select', {
		'class': 'inputbox',
		'name':'pick_types[]',
		'multiple':'multiple'
	});
	var pickOpts = getPickOptions(jGame);
	pickDD.setProperty('size', pickOpts.length);
	pickDD.adopt(pickOpts);
	//pickDD.setProperty('value', )
	pickCell.adopt(pickDD);
	row.adopt(pickCell);
	if (pickOpts.length == 0) {
		$$('.pick').setStyle('display', 'none');
	}
	
	//starts cells
	//home start
	var homeStartCell = new Element('td', {
		'style':'text-align:center',
		'class':'team_starts'
	});
	var homeStartValue = '';
	if (jGame.home_start && jGame.home_start != null) {
		homeStartValue = jGame.home_start;
	}
	var homeStartField = new Element('input', {
		'class': 'inputbox',
		'size': 3,
		'type': 'text',
		'value': homeStartValue,
		'style': 'text-align:center',
		'autoComplete':'off',
		'name':'home_start[]'
	});
	homeStartCell.adopt(homeStartField);
	//row.adopt(homeStartCell);
	
	//away start
	var awayStartCell = new Element('td', {
		'style':'text-align:center',
		'class':'team_starts'
	});
	var awayStartValue = '';
	if (jGame.away_start && jGame.away_start != null) {
		awayStartValue = jGame.away_start;
	}
	var awayStartField = new Element('input', {
		'class': 'inputbox',
		'size': 3,
		'type': 'text',
		'value': awayStartValue,
		'style': 'text-align:center',
		'autoComplete':'off',
		'name':'away_start[]'
	});
	awayStartCell.adopt(awayStartField);
	//row.adopt(awayStartCell);
	
	if (jSeason.tips_layout == 'away') {
		row.adopt(awayStartCell);
		row.adopt(homeStartCell);
	} else {
		row.adopt(homeStartCell);
		row.adopt(awayStartCell);
	}
	
	//game start date and time fields in one cell
	var gameTimeCell = new Element('td', {
		'class':'date_time'
	});
	var gameTimeDateField = new Element('input', {
		'type':'text',
		'name':'start_time_date[]',
		'id':'start_time_date' + gameRowIndex,
		'class':'inputbox',
		//'onClick':onCalClick,
		'value':getDate(jGame.start_time),
		'autocomplete':'off'
	});
	var thisRowIndex = 'start_time_date' + gameRowIndex;
	gameTimeDateField.onclick = function(event) {
		if (typeof jTipsCustomCalFunc == 'undefined' || jTipsCustomCalFunc) {
			if (isJoomla15) {
				return showjTipsCalendar(thisRowIndex, unescape(DateFormat));
			} else {
				//show at the right spot, with default formating
				return showLegacyjTipsCalendar(thisRowIndex);
			}
		} else {
			return jTipsShowCalendar(thisRowIndex, unescape(DateFormat));
		}
	};
	gameTimeCell.adopt(gameTimeDateField);
	var lbr = new Element('br');
	gameTimeCell.adopt(lbr);
	var gameTimeHourDD = new Element('select', {
		'class':'inputbox',
		'name':'start_time_hour[]'
	});
	gameTimeHourDD.adopt(getIntegerOptions(1, 12, 1));
	gameTimeHourDD.setProperty('value', getHour(jGame.start_time));
	gameTimeCell.adopt(gameTimeHourDD);
	gameTimeCell.appendText(' : ');
	var gameTimeMinuteDD = new Element('select', {
		'class':'inputbox',
		'name':'start_time_minute[]'
	});
	gameTimeMinuteDD.adopt(getIntegerOptions(0, 55, 5));
	gameTimeMinuteDD.setProperty('value', getMinute(jGame.start_time));
	gameTimeCell.adopt(gameTimeMinuteDD);
	var gameTimeMeridiemDD = new Element('select', {
		'class':'inputbox',
		'name':'start_time_meridiem[]'
	});
	var meridiemOptions = new Array(
		new Element('option', {'text':'am', 'value':'am', 'label':'am'}).appendText('am'),
		new Element('option', {'text':'pm', 'value':'pm', 'label':'pm'}).appendText('pm')
	);
	gameTimeMeridiemDD.adopt(meridiemOptions);
	gameTimeMeridiemDD.setProperty('value', getMeridiem(jGame.start_time));
	gameTimeCell.appendText(' ');
	gameTimeCell.adopt(gameTimeMeridiemDD);
	row.adopt(gameTimeCell);

	// build the info box
	// show a free text field
        var infoCell = new Element('td', {
                'styles': {
			'text-align': 'center',
			'display': 'none'
		},
                'class': 'jtipsinfo'
        });
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
	gameRowIndex++;
	
	
	if (jSeason.team_starts == 0) {
		$$('.team_starts').setStyle('display', 'none');
	}
	if (pickOpts.length == 0) {
		$$('.pick').setStyle('display', 'none');
	}
}

function showLegacyjTipsCalendar(id) {
	var el = document.getElementById(id);
    if (calendar != null) {
            // we already have one created, so just update it.
            calendar.hide();                // hide the existing calendar
            calendar.parseDate(el.value); // set it to a new date
    } else {
            // first-time call, create the calendar
            var cal = new Calendar(true, null, selected, closeHandler);
            calendar = cal;         // remember the calendar in the global
            cal.setRange(1900, 2070);       // min/max year allowed
            calendar.create();              // create a popup calendar
            calendar.parseDate(el.value); // set it to a new date
    }
    calendar.sel = el;              // inform it about the input field in use
    calendar.showAtElement($('addgame'));     // show the calendar next to the input field

    // catch mousedown on the document
    Calendar.addEvent(document, "mousedown", checkCalendar);
    return false;
}

/**
 * Override the standard showcalendar function so we can show it at the same spot
 * every time
 */
function showjTipsCalendar(id, dateFormat) {
	var el = document.getElementById(id);
	if (calendar != null) {
		// we already have one created, so just update it.
		calendar.hide();		// hide the existing calendar
		calendar.parseDate(el.value); // set it to a new date
	} else {
		// first-time call, create the calendar
		var cal = new Calendar(true, null, selected, closeHandler);
		calendar = cal;		// remember the calendar in the global
		cal.setRange(1900, 2070);	// min/max year allowed

		if ( dateFormat )	// optional date format
		{
			cal.setDateFormat(dateFormat);
		}

		calendar.create();		// create a popup calendar
		calendar.parseDate(el.value); // set it to a new date
	}
	calendar.sel = el;		// inform it about the input field in use
	calendar.showAtElement($('addgame'));	// show the calendar next to the input field

	// catch mousedown on the document
	Calendar.addEvent(document, "mousedown", checkCalendar);
	return false;
}

function getPickOptions(game) {
	var opts = [];
	//add the empty option
	//opts.push(new Option(jTipsLang._ADMIN_CONF_NONE, ''));
	
	// BUG 365 - use mootools to add options
	
	//pick the score
	if (jSeason.pick_score == 1) {
		var ps = new Element('option', {
			label:jTipsLang._ADMIN_GAME_HAS_SCORE,
			value:'score'
		}).appendText(jTipsLang._ADMIN_GAME_HAS_SCORE);
		if (game.has_score == 1) {
			ps.setProperty('selected', true);
		}
		//opts.push(new Option(jTipsLang._ADMIN_GAME_HAS_SCORE, 'score'));
		opts.push(ps);
	}
	
	//pick the margin
	if (jSeason.pick_margin == 1) {
		var pm = new Element('option', {
			label:jTipsLang._ADMIN_GAME_HAS_MARGIN,
			value:'margin'
		}).appendText(jTipsLang._ADMIN_GAME_HAS_MARGIN);
		if (game.has_margin == 1) {
			pm.setProperty('selected', true);
		}
		//opts.push(new Option(jTipsLang._ADMIN_GAME_HAS_MARGIN, 'margin'));
		opts.push(pm);
	}
	
	//pick the bonus
	if (jSeason.pick_bonus > 0) {
		var pb = new Element('option', {
			label:jTipsLang._ADMIN_GAME_HAS_BONUS,
			value:'bonus'
		}).appendText(jTipsLang._ADMIN_GAME_HAS_BONUS);
		if (game.has_bonus == 1) {
			pb.setProperty('selected', true);
		}
		//opts.push(new Option(jTipsLang._ADMIN_GAME_HAS_BONUS, 'bonus'));
		opts.push(pb);
	}
	
	return opts;
}

/**
 * Retrieve a JSON object of the selected jSeason.
 * Display or hide additional columns as per the setting on the jSeason object
 */
function getSeasonDetails() {
	if ($('season_id').value == '') return '';
	
	var gsd = new Ajax("index2.php", {
		method: "POST",
		data: "season_id=" + escape($('season_id').value) + "&action=GetSeasonObject&option=com_jtips&view=Administration",
		//evalResponse: true,
		onSuccess: function(msg) {
			jSeason = Json.evaluate(msg);
			return true;
		}
	});
	return gsd.request();
}

/**
 * Get an array of Options to be added to a dropdow
 * 
 * @param id The id of the selected option
 * 
 * @return array An array of Option objects
 */
function getTeamOptions(id, forceStop) {
	var teamOptions = [];
	if (jTeams.length == 0 && !forceStop) {
		getTeams().chain(function() {
			return getTeamOptions(id, true);
		});
	} else {
		for (var x=0; x<jTeams.length; x++) {
			var opt = new Element('option', {
				label: jTeams[x].location + ' ' + jTeams[x].name,
				value: jTeams[x].id
			}).appendText(jTeams[x].location + ' ' + jTeams[x].name);
			if (id == jTeams[x].id) {
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
}

/**
 * Use ajax to retrieve the array of teams for the selected season
 */
function getTeams() {
	if ($('season_id').value == '') return;
	var gt = new Ajax("index2.php", {
		method: "POST",
		data: "option=com_jtips&view=Administration&action=LoadTeams&season_id=" + escape($('season_id').value),
		onSuccess: function(res) {
			jTeams = Json.evaluate(res);
			return true;
		}
	});
	return gt.request();
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

function getIntegerOptions(start, end, step) {
	var intOptions = [];
	for (var i=start; i<=end; i+=step) {
		//var opt = new Option(i, i);
		var d = i;
		if (d == 0) {
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
	if (!datetime || datetime == '0000-00-00 00:00:00') return '';
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	return d.strftime(unescape(DateFormat));
}

function getHour(datetime) {
	if (!datetime || datetime == '0000-00-00 00:00:00') return '';
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	return d.toString('h');
}

function getMinute(datetime) {
	if (!datetime || datetime == '0000-00-00 00:00:00') return '';
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	return d.toString('m');
}

function getMeridiem(datetime) {
	if (!datetime || datetime == '0000-00-00 00:00:00') return '';
	
	var d = new Date.parse(datetime);
	d.add({seconds:Offset});
	var hour = d.toString('H');
	if (hour >= 12) {
		return 'pm';
	} else {
		return 'am';
	}
}

function addRoundEditFormSubmit() {
	$('jTipsRoundEditForm').addEvent('submit', function(e) {
		new Event(e).stop();
		$('jTipsRoundEditForm').setStyle('display', 'none');
		var log = $('loader3').empty().addClass('ajax-loading');
		this.send({
			update: log,
			onComplete: function() {
				log.removeClass('ajax-loading');
			}
		});
	});
}

function addRoundScoreFormSubmit() {
	$('jTipsRoundProcessForm').addEvent('submit', function(e) {
		try {
			new Event(e).stop();
			if ($('roundnum').value=='') return false;
			var res = $('games_table').setStyle('display', 'none');
			var round_id = $('roundnum').value;
			$('round_select').empty().appendText(jTipsLang._COM_ADMIN_RESULTS_PROCESS_ROUND + ' ' + round_id);
			$('loader2').addClass('ajax-loading');
			this.send({
				update: $('loader2'),
				onComplete: function() {
					$('loader2').removeClass('ajax-loading');
					if ($('season_id')) {
						//we are on the popup
						setTimeout("$('mb_close_link').onclick();", 2500);
					} else {
						//we are on the full component page
						//reload window
						setTimeout("window.location.reload();", 1500);
					}
				}
			});
		} catch (err) {
			alert(err);
			return false;
		}
	});
}
