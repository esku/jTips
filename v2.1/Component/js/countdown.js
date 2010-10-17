function jTipsCountdown(year, month, day, hour, minute, format, dom_id, isModule) {
	Today = new Date();
	var o = Today.getTimezoneOffset();
	Todays_Date = Today.getTime()+(o*60*1000);                             
	Target = new Date(year, month, day, hour, minute, 0);
	Target_Date = Target.getTime();
	Time_Left = Math.round((Target_Date - Todays_Date) / 1000);
	if (dom_id) {
		element = $(dom_id);
	} else {
		element = document.createElement('span');
	}
	if (dom_id) {
		$(dom_id).removeClass('highlight');
	}
	if (!isModule) {
		isModule = false;
	}
	var stop = false;
	if (Time_Left < 3600 && dom_id) {
		//make the countdown red if less than 1 hour to go
		$(dom_id).addClass('highlight');
	}
	if(Time_Left <= 0) {
		if (!isModule) {
			try {
			//disabled all elements in form.
			var formElements = $('tips_form').getElements('input');
			for (var i=0; i<formElements.length; i++) {
				formElements[i].disabled = true;
			}
			
			$('submittipsbuttonarea').disabled = true;
			$('submittipsbuttonarea').effect('opacity', {duration: 2000}).start(0);
			//document.getElementById('submittipsbuttonarea').innerHTML = 'Round now in progress';
			} catch (e) {
				// in the locked view so no form available
			}
		}
		Time_Left = 0;
		stop = true;
	}
	switch(format) {
		case 0:
			//The simplest way to display the time left.
			element.innerHTML = Time_Left + ' seconds';
			break;
		case 1:
			//More datailed.
			days = Math.floor(Time_Left / (60 * 60 * 24));
			Time_Left %= (60 * 60 * 24);
			hours = Math.floor(Time_Left / (60 * 60));
			Time_Left %= (60 * 60);
			minutes = Math.floor(Time_Left / 60);
			Time_Left %= 60;
			seconds = Time_Left;
			dps = 's'; hps = 's'; mps = 's'; sps = 's';
			//ps is short for plural suffix.
			if(days == 1) dps ='';
			if(hours == 1) hps ='';
			if(minutes == 1) mps ='';
			if(seconds == 1) sps ='';
			
			element.innerHTML = days + ' day' + dps + ' ';
			//element.innerHTML += hours + ' hour' + hps + ' ';
			//element.innerHTML += minutes + ' minute' + mps + ' and ';
			//element.innerHTML += seconds + ' second' + sps;
			element.innerHTML += hours + ':';
			if (minutes < 10) minutes = '0' + minutes.toString();
			element.innerHTML += minutes + ':';
			if (seconds < 10) seconds = '0' + seconds.toString();
			element.innerHTML += seconds;
			break;
          case 2:
			days = Math.floor(Time_Left / (60 * 60 * 24));
			Time_Left %= (60 * 60 * 24);
			hours = Math.floor(Time_Left / (60 * 60)) + days * 24;
			Time_Left %= (60 * 60);
			minutes = Math.floor(Time_Left / 60);
			Time_Left %= 60;
			seconds = Time_Left;
			if (hours < 10) {
				hours = "0" + hours;
			}
			if (minutes < 10) {
				minutes = "0" + minutes;
			}
			if (seconds < 10) {
				seconds = "0" + seconds;
			}
			element.innerHTML = hours + ':';
			element.innerHTML += minutes + ':';
			element.innerHTML += seconds ;
			break;
		default: 
			element.innerHTML = Time_Left + ' seconds';
			break;
	}
	if (stop == false) {
		//Recursive call, keeps the clock ticking.
		if (typeof dom_id == 'boolean') {
			var theDom = false;
		} else {
			var theDom = '\'' + dom_id + '\'';
		}
		setTimeout('jTipsCountdown(' + year + ',' + month + ',' + day + ',' + hour + ',' + minute + ',' + format + ',' + theDom + ', ' + isModule + ');', 1000);
	} else {
		//element.innerHTML = '&nbsp;';
		if (dom_id) {
			//$(element).fx = $(element).effect('opacity', {duration: 2000}).start(0);
			$(element).setText(jTipsLang._ADMIN_ROUND_STATUS_IP);
		} else {
			element = null;
		}
	}
}
