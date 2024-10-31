document.addEventListener("DOMContentLoaded", obk_initForm);
function obk_initForm(){
	if (document.getElementById("obk_form_date_debut")){
		document.getElementById("obk_form_date_debut").addEventListener("change",obk_getAvailableArrivalHours);
		if (document.getElementById("obk_form_date_debut").value != ""){
			var e = {target: {value: document.getElementById("obk_form_date_debut").value}};
			obk_getAvailableArrivalHours(e);
		}
	}
	if (document.getElementById("obk_form_date_fin")){
		document.getElementById("obk_form_date_fin").addEventListener("change",obk_getAvailableDepartureHours);
		if (document.getElementById("obk_form_date_fin").value != ""){
			var e = {target: {value: document.getElementById("obk_form_date_fin").value}};
			obk_getAvailableDepartureHours(e);
		}
	}
	
}

function obk_getAvailableArrivalHours(e){
	if (document.getElementById("obk_form_heure_debutp1")){
		var hourSelected = "99:99";
		if ((document.getElementById("obk_form_heure_debut")) && (document.getElementById("obk_form_heure_debut").value)){
			hourSelected = document.getElementById("obk_form_heure_debut").value;
		}
		var arrivalDate = e.target.value;
		arrivalDate = arrivalDate.split("-");
		var arrival = new Date(arrivalDate[0],arrivalDate[1]-1,arrivalDate[2]);
		if (arrival.getFullYear() >= 2018){
			var day = arrival.getDay();
			var OTDay = obk_openingtimes[day];
			var minHourArrival = '23:59';
			var maxHourArrival = '00:00';
			if ((OTDay[1].length > 1) && (OTDay[1][0] != "0")){
				for(var i = 1; i < OTDay[1].length; i++){
					if (OTDay[1][i][0] < minHourArrival){
						minHourArrival = OTDay[1][i][0];
					}
					if (OTDay[1][i][1] > maxHourArrival){
						maxHourArrival = OTDay[1][i][1];
					}
				}
			}else{
				for(var i = 1; i < OTDay[0].length; i++){
					if (OTDay[0][i][0] < minHourArrival){
						minHourArrival = OTDay[0][i][0];
					}
					if (OTDay[0][i][1] > maxHourArrival){
						maxHourArrival = OTDay[0][i][1];
					}
				}
			}		
			if (minHourArrival == '23:59'){minHourArrival = '00:00';}
			if (maxHourArrival == '00:00'){maxHourArrival = '24:00';}
			minHourArrival = minHourArrival.substr(0,2);
			maxHourArrival = maxHourArrival.substr(0,2);
			var selectHourContent = '';
			for(var i=minHourArrival; i<=maxHourArrival;i++){
				selected = "";
				if (hourSelected.substr(0,2) == ("0" + i).slice(-2)){
					selected = "selected";
				}
				selectHourContent += '<option ' + selected + ' value="' + ("0" + i).slice(-2) + '">' + ("0" + i).slice(-2) + '</option>';
			}
			document.getElementById("obk_form_heure_debutp1").innerHTML = selectHourContent;
			document.getElementById("obk_form_heure_debutp1").disabled = false;
			document.getElementById("obk_form_heure_debutp2").disabled = false;
			
		}else{
			document.getElementById("obk_form_heure_debutp1").disabled = true;
			document.getElementById("obk_form_heure_debutp2").disabled = true;
		}
	}
	obk_getAvailableOptions();
}

function obk_getAvailableOptions(){
	if (document.getElementById("obk_getAvailableOptions")){
		var wpnonce = document.getElementById("obk_getAvailableOptions").value;
		var obk_idSpace = document.getElementById("obk_idSpace").value; 
		var arrivalDate = document.getElementById("obk_form_date_debut").value; 
		var data = {
			'action' : 'js_getAvailableOptions',
			'_wpnonce': wpnonce,
			'obk_idSpace': obk_idSpace,
			'arrivalDate': arrivalDate
		};
		if (document.getElementById("obk_optionsBackup")){
			var optionsBackup = document.getElementById("obk_optionsBackup").innerHTML;
			var tabOB = optionsBackup.split("**");
			for(var i = 0; i < tabOB.length; i++){
				if (tabOB[i] != ""){
					var tabOption = tabOB[i].split("++");
					data["form_option_" + tabOption[0]] = tabOption[1];
				}
			}
		}
		if (document.getElementById("obk_couponBackup")){
			data["form_coupon"] = document.getElementById("obk_couponBackup").innerHTML;
		}
		if (document.getElementById("obk_reductionBackup")){
			data["form_reduction"] = document.getElementById("obk_reductionBackup").innerHTML;
		}
		var ajaxurl = WPJS.adminAjaxUrl;
		jQuery.post(ajaxurl, data, function(response) {
			var rep = JSON.parse(response);
			document.getElementById("obk_displayOptions").innerHTML = rep[0];
			for(var i=0; i < rep[1].length; i++){
				var script = document.createElement("script");
				var text = 'var x = ' + rep[1][i][0] + ';';
				text += 'x = x.toLocaleString("none",{ style: "currency", currency: "' + rep[1][i][1] + '"});';
				text += 'document.getElementById("' + rep[1][i][2] + '").innerHTML = x;';
				script.innerHTML = text;
				document.body.appendChild(script);
			}
		});
	}
}

function obk_getAvailableDepartureHours(e){
	if (document.getElementById("obk_form_heure_finp1")){
		var hourSelected = "99:99";
		if ((document.getElementById("obk_form_heure_fin")) && (document.getElementById("obk_form_heure_fin").value)){
			hourSelected = document.getElementById("obk_form_heure_fin").value;
		}
		var departureDate = e.target.value;
		departureDate = departureDate.split("-");
		var departure = new Date(departureDate[0],departureDate[1]-1,departureDate[2]);
		if (departure.getFullYear() >= 2018){
			var day = departure.getDay();
			var OTDay = obk_openingtimes[day];
			var minHourDeparture = '23:59';
			var maxHourDeparture = '00:00';
			if ((OTDay[2].length > 1) && (OTDay[2][0] != "0")){
				for(var i = 1; i < OTDay[2].length; i++){
					if (OTDay[2][i][0] < minHourDeparture){
						minHourDeparture = OTDay[2][i][0];
					}
					if (OTDay[2][i][1] > maxHourDeparture){
						maxHourDeparture = OTDay[2][i][1];
					}
				}
			}else{
				for(var i = 1; i < OTDay[0].length; i++){
					if (OTDay[0][i][0] < minHourDeparture){
						minHourDeparture = OTDay[0][i][0];
					}
					if (OTDay[0][i][1] > maxHourDeparture){
						maxHourDeparture = OTDay[0][i][1];
					}
				}
			}		
			if (minHourDeparture == '23:59'){minHourDeparture = '00:00';}
			if (maxHourDeparture == '00:00'){maxHourDeparture = '24:00';}
			minHourDeparture = minHourDeparture.substr(0,2);
			maxHourDeparture = maxHourDeparture.substr(0,2);
			var selectHourContent = '';
			for(var i=minHourDeparture; i<=maxHourDeparture;i++){
				selected = "";
				if (hourSelected.substr(0,2) == ("0" + i).slice(-2)){
					selected = "selected";
				}
				selectHourContent += '<option ' + selected + '  value="' + ("0" + i).slice(-2) + '">' + ("0" + i).slice(-2) + '</option>';
			}
			document.getElementById("obk_form_heure_finp1").innerHTML = selectHourContent;
			document.getElementById("obk_form_heure_finp1").disabled = false;
			document.getElementById("obk_form_heure_finp2").disabled = false;
			
		}else{
			document.getElementById("obk_form_heure_finp1").disabled = true;
			document.getElementById("obk_form_heure_finp2").disabled = true;
		}
	}
}

function obk_bookingConfirmation(msg){
	if (document.getElementById("obk_nb_de_place")){document.getElementById("obk_nb_de_place").value = ""};
	if (document.getElementById("obk_form_date_debut")){document.getElementById("obk_form_date_debut").value = ""};
	if (document.getElementById("obk_form_heure_debut")){document.getElementById("obk_form_heure_debut").value = ""};
	if (document.getElementById("obk_form_date_fin")){document.getElementById("obk_form_date_fin").value = ""};
	if (document.getElementById("obk_form_heure_fin")){document.getElementById("obk_form_heure_fin").value = ""};
	if (document.getElementById("obk_form_personnes")){document.getElementById("obk_form_personnes").value = ""};
	if (document.getElementById("obk_form_nom")){document.getElementById("obk_form_nom").value = ""};
	if (document.getElementById("obk_form_prenom")){document.getElementById("obk_form_prenom").value = ""};
	if (document.getElementById("obk_form_adresse")){document.getElementById("obk_form_adresse").value = ""};
	if (document.getElementById("obk_form_code_postal")){document.getElementById("obk_form_code_postal").value = ""};
	if (document.getElementById("obk_form_ville")){document.getElementById("obk_form_ville").value = ""};
	if (document.getElementById("obk_form_pays")){document.getElementById("obk_form_pays").value = ""};
	if (document.getElementById("obk_form_email")){document.getElementById("obk_form_email").value = ""};
	if (document.getElementById("obk_form_telephone")){document.getElementById("obk_form_telephone").value = ""};
	if (document.getElementById("obk_form_remarques")){document.getElementById("obk_form_remarques").value = ""};
	alert(msg);
	window.location.href = window.location.href;
}

var obk_saveInputsError = new Array();
function obk_show_inputs_error(codedArray){
	//for(var i in obk_saveInputsError){
	for(var i = 0; i < obk_saveInputsError.length; i++){
		if (document.getElementsByName(obk_saveInputsError[i])[0]){
			document.getElementsByName(obk_saveInputsError[i])[0].style.border = '';
		}else{
			document.getElementById(obk_saveInputsError[i]).style.border = '';
		}
	}
	var tab = JSON.parse(codedArray);
	//for(var i in tab){
	for(var i = 0; i < tab.length; i++){
		if (document.getElementsByName(tab[i])[0]){
			document.getElementsByName(tab[i])[0].style.border = '2px solid red';
		}else{
			document.getElementById(tab[i]).style.border = '2px solid red';
		}
	}
	obk_saveInputsError = tab;
}


function obk_saveBooking(){
	document.addEventListener("submit",obk_ajaxSaveBooking);
}

function obk_ajaxSaveBooking(e){
	if (e.target.name == "obk_saveBooking") {
		e.preventDefault();	
		if (document.getElementById("obk_calendarLoading")){
			document.getElementById("obk_calendarLoading").style.display = "flex";
		}
		var wpnonce = document.getElementById("obk_mainSaveBooking").value; 
		var data = {
			'action' : 'js_saveBooking',
			'_wpnonce': wpnonce
		};
		var formInput = document.getElementById("obk_formAllDataBooking").getElementsByTagName("input");
		//for(var i in formInput){
		for(var i = 0; i < formInput.length; i++){
			if (formInput[i].type == "hidden"){
				var name = formInput[i].name;
				if (name != '_wpnonce'){
					var value = formInput[i].value;
					data[name] = value;
				}
			}
		}
		var ajaxurl = WPJS.adminAjaxUrl;
		jQuery.post(ajaxurl, data, function(response) {
			var rep = JSON.parse(response);
			if (!rep[0]){
				alert(rep[1]);
				return;
			}
			if (document.querySelector("[name=item_name]")){
				document.querySelector("[name=item_name]").value = "#" + rep[2] + " " + document.querySelector("[name=item_name]").value;
			}
			if (document.getElementById("obk_calendarLoading")){
				document.getElementById("obk_calendarLoading").style.display = "none";
			}
			alert(rep[1]);
			window.location.href = window.location.href;
		});
	}
}

var obk_currentDate = new Date();
obk_currentDate = obk_currentDate.getFullYear() + "-" + obk_2digits(obk_currentDate.getMonth()+1) + "-" + obk_2digits(obk_currentDate.getDate());

function obk_initCalendar(){
	document.addEventListener("DOMContentLoaded", obk_calendarDefaults, false);
}

function obk_calendarOnMonth(){
	obk_calendarMakeCalendar(document.forms["when"].month.value + "-01",false);
}

function obk_calendarDefaults(){
	obk_calendarMakeCalendar(obk_currentDate,true);
}

function obk_calendarSkip(Direction){
	var currDate = new Date(obk_currentDate.substr(0,4),obk_currentDate.substr(5,2)-1,obk_currentDate.substr(8,2));
	if (Direction == "+") {
		currDate.setDate(currDate.getDate() + 7);
	}else{
		currDate.setDate(currDate.getDate() - 7);
	}
	obk_currentDate = currDate.getFullYear() + "-" + obk_2digits(currDate.getMonth()+1) + "-" + obk_2digits(currDate.getDate());
	currDate.setDate(currDate.getDate() + 6);
	var getOption = currDate.getFullYear() + "-" + obk_2digits(currDate.getMonth()+1);
	document.forms["when"].month.value = getOption;
	obk_calendarMakeCalendar(obk_currentDate,false);
}

function obk_calendarMakeCalendar(CurrentDate,init){
	if (document.getElementById("obk_idSpace")){
		var obk_idSpace = document.getElementById("obk_idSpace").value; 
	}else if (document.forms["obk_formAddEditBookingSpace"]){
		var obk_idSpace = document.forms["obk_formAddEditBookingSpace"].obk_idSpace.value;
	}else if (document.getElementsByName("nospace")[0]){
		var obk_idSpace = -1;
	}else{
		return;
	}
	var wpnonce = document.getElementById("obk_mainCalendar").value; 
	var data = {
		'action': 'js_chargeCalendrier',
		'obk_idSpace': obk_idSpace,
		'obk_init': init,
		'CurrentDate': CurrentDate,
		'_wpnonce': wpnonce
	};
	
	if (typeof(ajaxurl) == "undefined"){
		var ajaxurl = WPJS.adminAjaxUrl;
	}
	if (document.getElementById("obk_calendarLoading")){
		document.getElementById("obk_calendarLoading").style.display = "flex";
	}
	if (document.forms["obk_filterBookings"]){
		var start = document.forms["obk_filterBookings"].filterDepartureDate.value;
		var finish = document.forms["obk_filterBookings"].filterArrivalDate.value;
		var status = document.forms["obk_filterBookings"].filterBookingStatus.value;
		data['filterDepartureDate']  = start;
		data['filterArrivalDate']  = finish;
		data['filterBookingStatus']  = status;
	}
	jQuery.post(ajaxurl, data, function(response) {
		obk_calendatMakeCalendarResa(JSON.parse(response));
	});
}

function obk_calendatMakeCalendarResa(liste_resa) {
	var joursSemaine = [WPJS.obk_GetTexte58,WPJS.obk_GetTexte52,WPJS.obk_GetTexte53,WPJS.obk_GetTexte54,WPJS.obk_GetTexte55,WPJS.obk_GetTexte56,WPJS.obk_GetTexte57];
	if ((document.getElementById("obk_contentReservations")) && (liste_resa["echo"])){
		document.getElementById("obk_contentReservations").innerHTML = liste_resa["echo"];
	}
	if (liste_resa[0]){
		var tabMonth = [WPJS.obk_GetTexte59,WPJS.obk_GetTexte60,WPJS.obk_GetTexte61,WPJS.obk_GetTexte62,WPJS.obk_GetTexte63,WPJS.obk_GetTexte64,WPJS.obk_GetTexte65,WPJS.obk_GetTexte66,WPJS.obk_GetTexte67,WPJS.obk_GetTexte68,WPJS.obk_GetTexte69,WPJS.obk_GetTexte70];
		var minHour = [0,0,0,0,0,0,0];
		var maxHour = [1440,1440,1440,1440,1440,1440,1440];
		if ((liste_resa[0]["timeUnit"] == 0) || (liste_resa[0]["timeUnit"] == null)){
			liste_resa[0]["timeUnit"] = 1;
		}
		for(var day = 0; day < 7; day++){
			var hour = 0;
			while((hour < 1440) && (liste_resa[day]["planning"][hour][1] == "c")){
				hour++;
			}
			minHour[day] = hour;
			var hour = 1439;
			while((hour > 0) && (liste_resa[day]["planning"][hour][1] == "c")){
				hour--;
			}
			maxHour[day] = hour+1;
		}
		//OSX compatibility
		Array.prototype.obk_max = function() {
			return Math.max.apply(null, this);
		};
		Array.prototype.obk_min = function() {
			return Math.min.apply(null, this);
		};
		//minHour = Math.min(...minHour);
		//maxHour = Math.max(...maxHour);
		minHour = minHour.obk_min();
		maxHour = maxHour.obk_max();
		if (maxHour == 1){
			minHour = 0;
			maxHour = 1439;
		}
		coeff = 6;
		if (liste_resa[0]["timeUnit"] < 1){
			coeff = coeff * liste_resa[0]["timeUnit"];
		}
		HTML_String = '<div class="obk_weekHeader"><div class="obk_w12"></div>';
		for(i = 1; i < 8; i++){
			HTML_String += '<div class="obk_w12"><span class="obk_calendarLabelDay">' + joursSemaine[liste_resa[i % 7]["labelDay"]].substr(0,3) + '.</span><br><span class="obk_calendarDateDay">' + liste_resa[i % 7]["dateDay"] + '</span></div>';
			if (i == 1){
				obk_currentDate = liste_resa[i % 7]["date"];
			}
		}
		HTML_String += '</div><div class="obkCalendarContent"><div class="obk_w12">';
		for(i = minHour; i <= maxHour; i++){
			if ((i % 60) == 0){
				HTML_String += '<div class="obk_calendarTimes" style="height:'+(120/coeff)+'px">' + obk_2digits(i/60) + ':00</div>';
			}
		}
		HTML_String += '</div>';
		for(i = 1; i < 8; i++){
			HTML_String += '<div class="obk_w12 obk_calendarAllDay">';
			hour = minHour;
			fullZero = true;color = "obk_calendarAllAvailable";
			while(hour < maxHour){
				startBloc = hour;
				valBloc = liste_resa[i % 7]["planning"][hour][1];
				content = liste_resa[i % 7]["planning"][hour][0];
				if (content != 0){
					fullZero = false;
				}
				duree = 0;
				if (liste_resa[i % 7]){
					while((valBloc == liste_resa[i % 7]["planning"][hour][1]) && (hour < maxHour) && (duree < (liste_resa[0]["timeUnit"] * 60))){
						hour++;
						duree++;
					}
				}
				color = "obk_calendarAllAvailable";
				contentClass = "obk_bookable";
				
				if (content < liste_resa[i % 7]["nb_de_place"]){
					color = "obk_calendarAvailable";
				}
				if (valBloc == "d"){
					color = "obk_calendarDeparture";
				}
				if (valBloc == "a"){
					color = "obk_calendarArrival";
				}
				if (content <= 0){
					color = "obk_calendarUnavailable";
					contentClass = "";
					content = 0;
				}
				if (content > 1){
					content += " " + WPJS.obk_spaces;
				}else{
					content += " " + WPJS.obk_space;
				}
				if (valBloc == "c"){
					content = "";
					color = "obk_calendarClosed";
					contentClass = "";
				}
				
				HTML_String += '<div obk_data="'+liste_resa[i % 7]["date"]+';'+startBloc+'" class="'+contentClass+' obk_calendarOnePeriod ' + color + '" style="height:' + ((duree) * 2 / coeff) + 'px;line-height: ' + ((duree) * 2 / coeff) + 'px;">' + content + '</div>';
			}
			if (fullZero){
				HTML_String += '<div class="obk_calendarFullZero ' + color + '" style="height:' + ((liste_resa[0]["planning"].length-minHour-(1440-maxHour)) * 2 / coeff) + 'px;line-height: ' + ((liste_resa[0]["planning"].length-minHour-(1440-maxHour)) * 2 / coeff) + 'px;">'+content+'</div>';
			}
			HTML_String += '</div>';
		}
		HTML_String += '<div class="obk_calendarGrid">';
		for(i = minHour; i <= maxHour; i+=60){
			HTML_String += '<div class="obk_calendarGridRow" style="height:' + (120 / coeff) + 'px"></div>';
		}
		HTML_String += '</div>';
		HTML_String += '</div>';
		if (minHour == 1440){
			HTML_String += '<span class="obk_calendarBookingUnavailable">'+WPJS.obk_BookingUnavailable+'</span>';
		}
		HTML_String += '<div style="font-size:11px;text-align:right">'+String.fromCharCode(86)+String.fromCharCode(105)+String.fromCharCode(97)+' ' + WPJS.obk_PluginData + '</div><div class="obk_CalendarLegend"><div class="obk_calendarClosed">'+WPJS.obk_Closed+'</div><div class="obk_calendarAllAvailable">'+WPJS.obk_AllAvailable+'</div><div class="obk_calendarAvailable">'+WPJS.obk_Available+'</div><div class="obk_calendarUnavailable">'+WPJS.obk_Unavailable+'</div><div class="obk_calendarArrival">'+WPJS.obk_Arrival+'</div><div class="obk_calendarDeparture">'+WPJS.obk_Departure+'</div></div>';
		HTML_String += '<div id="obk_calendarLoading"><div class="obk_loadingAnimation"></div></div>';
		var currDate = new Date(obk_currentDate.substr(0,4),obk_currentDate.substr(5,2)-1,obk_currentDate.substr(8,2));
		currDate.setDate(currDate.getDate() + 6);
		var getOption = currDate.getFullYear() + "-" + obk_2digits(currDate.getMonth()+1);
		var optionMissing = true;
		var monthLength = document.forms["when"].month.options.length;
		for (var i = 0; i <= monthLength - 1; i++){
			if (document.forms["when"].month.options[i].value == getOption){
			   optionMissing = false;
			}
		}
		if (optionMissing){
			var opt = new Option((tabMonth[currDate.getMonth()]) + " " + currDate.getFullYear(), getOption);
			if (getOption > document.forms["when"].month.options[monthLength - 1].value){
				document.forms["when"].month.options[monthLength] = opt;
			}else{
				document.forms["when"].month.insertBefore(opt, document.forms["when"].month.firstChild);
			}
		}
		document.forms["when"].month.value = getOption;
		cross_el = document.getElementById("obk_calendar");
		cross_el.innerHTML = HTML_String;
		var tabBookable = document.getElementsByClassName("obk_bookable");
		for(i = 0; i < tabBookable.length; i++){
			tabBookable[i].addEventListener("click",obk_dateToForm);
		}
		document.getElementById("obk_calendarLoading").style.display = "none";
	}
}

function obk_dateToForm(e){
	data = e.target.getAttribute("obk_data").split(";");
	if ((document.getElementById("obk_form_date_debut")) && (document.getElementById("obk_form_date_debut").value == "")){
		document.getElementById("obk_form_date_debut").value = data[0];
		document.getElementById("obk_form_date_debut").focus();
		var e = {target: {value: document.getElementById("obk_form_date_debut").value}};
		obk_getAvailableArrivalHours(e);
		if (document.getElementById("obk_form_heure_debutp1")){
			hour = data[1];
			h = Math.floor(hour / 60);
			m = hour % 60;
			document.getElementById("obk_form_heure_debutp1").value = obk_2digits(h);
			document.getElementById("obk_form_heure_debutp2").value = obk_2digits(m);
		}
	}else if (document.getElementById("obk_form_date_fin")){
		document.getElementById("obk_form_date_fin").value = data[0];
		document.getElementById("obk_form_date_fin").focus();
		var e = {target: {value: document.getElementById("obk_form_date_fin").value}};
		obk_getAvailableDepartureHours(e);
		if (document.getElementById("obk_form_heure_finp1")){
			hour = data[1];
			h = Math.floor(hour / 60);
			m = hour % 60;
			document.getElementById("obk_form_heure_finp1").value = obk_2digits(h);
			document.getElementById("obk_form_heure_finp2").value = obk_2digits(m);
		}
	}
}

function obk_checkDateInOT(OT,datetime,type,exceptionalclosure){
	var numDay = datetime.getDay();
	var time = obk_2digits(datetime.getHours()) + ":" + obk_2digits(datetime.getMinutes());
	var datetimestr = datetime.getFullYear() + "-" + obk_2digits(datetime.getMonth()+1) + "-" + obk_2digits(datetime.getDate()) + " " + obk_2digits(datetime.getHours()) + ":" + obk_2digits(datetime.getMinutes());
	arrivalPresent = false;
	for(i = 0; i < OT.length; i++){
		if ((OT[i][1][0] != "0") && (OT[i][1].length > 1)){
			arrivalPresent = true;
		}
	}
	departurePresent = false;
	for(i = 0; i < OT.length; i++){
		if ((OT[i][2][0] != "0") && (OT[i][2].length > 1)){
			departurePresent = true;
		}
	}
	var tabClosure = exceptionalclosure.split("--o--");
	for(i = 0; i < tabClosure.length; i++){
		close = tabClosure[i].split(";");
		if (close[1]){
			debut = close[0];
			fin = close[1];
			if ((datetimestr >= debut) && (datetimestr < fin)){
				return false;
			}
		}
	}
	if ((OT[numDay][type].length > 1) && (OT[numDay][type][0] != "0")){
		for(i = 1; i < OT[numDay][type].length; i++){
			if (OT[numDay][type][i][1] == '00:00'){
				OT[numDay][type][i][1] = '24:00';
			}
			if ((time >= OT[numDay][type][i][0]) && (time <= OT[numDay][type][i][1])){
				return true;
			}
		}
	}else if ((type > 0) && (!arrivalPresent) && (!departurePresent)){
		type = 0;
		if (OT[numDay][type][0] == "0"){return false;}
		if (OT[numDay][type].length > 1){
			for(i = 1; i < OT[numDay][type].length; i++){
				if (OT[numDay][type][i][1] == '00:00'){
					OT[numDay][type][i][1] = '24:00';
				}
				if ((time >= OT[numDay][type][i][0]) && (time <= OT[numDay][type][i][1])){
					return true;
				}
			}
		}	
	}
	return false;
}

function dateDiffInMinutes(a, b) {
  var utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate(), a.getHours(), a.getMinutes(), a.getSeconds());
  var utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate(), b.getHours(), b.getMinutes(), b.getSeconds());
  return Math.floor((utc2 - utc1) / (1000 * 60));
}

function obk_getReferenceTimeInOT(OT,numDay,datetime,type,dureeMin,dateDebut,user_minutes_interval){
	user_minutes_interval = parseInt(user_minutes_interval);
	dureeMin = dureeMin * 60;
	var refTime = '00:00';
	if ((type == "2") && (user_minutes_interval > 0)){
		while(datetime <= dateDebut){
			datetime.setMinutes(datetime.getMinutes() + user_minutes_interval);
		}
		if (dureeMin > 0){
			while(dateDiffInMinutes(dateDebut,datetime) < dureeMin){
				datetime.setMinutes(datetime.getMinutes() + user_minutes_interval);
			}
		}
		var nbTentative = 10080 / user_minutes_interval;
		var compteur = 0;
		while((compteur < nbTentative) && (!obk_checkDateInOT(OT,datetime,type))){
			datetime.setMinutes(datetime.getMinutes() + user_minutes_interval);
			compteur++;
		}
		if (compteur < nbTentative){
			return obk_2digits(datetime.getHours()) + ":" + obk_2digits(datetime.getMinutes());
		}
	}
	if ((OT[numDay][type].length > 1) && (OT[numDay][type][0] != "0")){
		for(var i = 1; i < OT[numDay][type].length; i++){
			if (i == 1){
				refTime = OT[numDay][type][i][0];
			}
			if (OT[numDay][type][i][2] == "true"){
				return OT[numDay][type][i][0];
			}
			if (OT[numDay][type][i][3] == "true"){
				if (OT[numDay][type][i][1] == "00:00"){return "24:00";}
				return OT[numDay][type][i][1];
			}
		}
	}else{
		for(i = 1; i < OT[numDay][0].length; i++){
			if (i == 1){
				refTime = OT[numDay][0][i][0];
			}
			if (OT[numDay][0][i][2] == "true"){
				return OT[numDay][0][i][0];
			}
			if (OT[numDay][0][i][3] == "true"){
				if (OT[numDay][0][i][1] == "00:00"){return "24:00";}
				return OT[numDay][0][i][1];
			}
		}
	}
	if ((type == "2") && (refTime == "00:00")){refTime = "24:00";}
	return refTime;
}
function obk_checkForm(timeUnit,minBookingDuration,tps_reservation_max_heure,date_debut_reservation,date_fin_reservation,user_minutes_interval,exceptionalclosure){
	var ok = true;
	var msg = "";
	var inputsError = new Array();
	openingtimes = obk_openingtimes;
	
	var date_debut_reservation_aff = date_debut_reservation.substr(8,2) + '/' + date_debut_reservation.substr(5,2) + '/' + date_debut_reservation.substr(0,4);
	var date_fin_reservation_aff = date_fin_reservation.substr(8,2) + '/' + date_fin_reservation.substr(5,2) + '/' + date_fin_reservation.substr(0,4);
	var date_debut_reservation = new Date(date_debut_reservation.substr(0,4),date_debut_reservation.substr(5,2)-1,date_debut_reservation.substr(8,2));
	var date_fin_reservation = new Date(date_fin_reservation.substr(0,4),date_fin_reservation.substr(5,2)-1,date_fin_reservation.substr(8,2),00,00);
	date_fin_reservation.setDate(date_fin_reservation.getDate() + 1);
	var joursSemaine = [WPJS.obk_GetTexte58,WPJS.obk_GetTexte52,WPJS.obk_GetTexte53,WPJS.obk_GetTexte54,WPJS.obk_GetTexte55,WPJS.obk_GetTexte56,WPJS.obk_GetTexte57];
	if ((document.getElementsByName("form_date_debut")[0])&&(document.getElementsByName("form_date_debut")[0].value)){
		var d = document.getElementsByName("form_date_debut")[0].value;
		if (document.getElementsByName("form_heure_debut")[0]){
			var h = obk_2digits(document.getElementById("obk_form_heure_debutp1").value) + ":" + obk_2digits(document.getElementById("obk_form_heure_debutp2").value);
			document.getElementsByName("form_heure_debut")[0].value = h;
			var form_date_debut = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
		}else{
			var tempDate = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),"12","00");
			var numDay = tempDate.getDay();
			var h = obk_getReferenceTimeInOT(openingtimes,numDay,tempDate,1,minBookingDuration,"",1);
			var form_date_debut = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
		}
	}
	if (document.getElementsByName("form_date_fin")[0]){
		if (document.getElementById("obk_booking_duration")){
			var d = document.getElementsByName("form_date_debut")[0].value;
			var h = obk_2digits(document.getElementById("obk_form_heure_debutp1").value) + ":" + obk_2digits(document.getElementById("obk_form_heure_debutp2").value);
			var form_date_fin = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
			form_date_fin.setTime(form_date_fin.getTime() + (document.getElementById("obk_booking_duration").value * 60 * 1000));
			document.getElementsByName("form_date_fin")[0].value = form_date_fin.getFullYear() + "-" + obk_2digits(form_date_fin.getMonth()+1) + "-" + obk_2digits(form_date_fin.getDate());
			document.getElementsByName("form_heure_fin")[0].value = obk_2digits(form_date_fin.getHours()) + ":" + obk_2digits(form_date_fin.getMinutes());
			document.getElementById("obk_form_heure_finp1").value = obk_2digits(form_date_fin.getHours());
			document.getElementById("obk_form_heure_finp2").value = obk_2digits(form_date_fin.getMinutes());
		}else if (document.getElementsByName("form_date_fin")[0].value){
			var d = document.getElementsByName("form_date_fin")[0].value;
			if (document.getElementsByName("form_heure_fin")[0]){
				var h = obk_2digits(document.getElementById("obk_form_heure_finp1").value) + ":" + obk_2digits(document.getElementById("obk_form_heure_finp2").value);
				document.getElementsByName("form_heure_fin")[0].value = h;
				var form_date_fin = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
			}else{
				var tempDate = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),"00","00");
				var numDay = tempDate.getDay();
				var h = obk_getReferenceTimeInOT(openingtimes,numDay,tempDate,2,minBookingDuration,form_date_debut,user_minutes_interval);
				var form_date_fin = new Date(d.substr(0,4),d.substr(5,2)-1,d.substr(8,2),h.substr(0,2),h.substr(3,2));
			}
		}
	}
	if (document.getElementById("obk_form_heure_debut")){
		document.getElementById("obk_form_heure_debut").value = obk_2digits(document.getElementById("obk_form_heure_debutp1").value) + ":" + obk_2digits(document.getElementById("obk_form_heure_debutp2").value);
		var form_heure_debut = document.getElementById("obk_form_heure_debut").value;
		if (form_heure_debut != ""){
			//var dd = new Date(document.getElementsByName("form_date_debut")[0].value + " " + form_heure_debut);
			var dd = document.getElementsByName("form_date_debut")[0].value.split("-");
			var hh = form_heure_debut.split(":");
			dd = new Date(dd[0],dd[1]-1,dd[2],hh[0],hh[1]);
			if (!obk_checkDateInOT(openingtimes,dd,1,exceptionalclosure)){
				msg += WPJS.obk_GetTexte37 + "\n";
				ok = false;
				inputsError.push("obk_form_heure_debutp1"); 
				inputsError.push("obk_form_heure_debutp2");
			}
		}
	}
	if (document.getElementById("obk_form_heure_fin")){
		document.getElementById("obk_form_heure_fin").value = obk_2digits(document.getElementById("obk_form_heure_finp1").value) + ":" + obk_2digits(document.getElementById("obk_form_heure_finp2").value);
		var form_heure_fin = document.getElementById("obk_form_heure_fin").value;
		if (form_heure_fin != ""){
			//var dd = new Date(document.getElementsByName("form_date_fin")[0].value + " " + form_heure_fin);
			//var dd2 = new Date(document.getElementsByName("form_date_fin")[0].value + " " + form_heure_fin);
			var dd = document.getElementsByName("form_date_fin")[0].value.split("-");
			var dd2 = document.getElementsByName("form_date_fin")[0].value.split("-");
			var hh = form_heure_fin.split(":");
			dd = new Date(dd[0],dd[1]-1,dd[2],hh[0],hh[1]);
			dd2 = new Date(dd2[0],dd2[1]-1,dd2[2],hh[0],hh[1]);
			dd.setSeconds(dd.getSeconds() - 1);
			if ((!obk_checkDateInOT(openingtimes,dd,2,exceptionalclosure)) && (!obk_checkDateInOT(openingtimes,dd2,2,exceptionalclosure))){
				msg += WPJS.obk_GetTexte39 + "\n";
				ok = false;
				inputsError.push("obk_form_heure_finp1"); 
				inputsError.push("obk_form_heure_finp2");
			}
		}
	}
	if (form_date_debut){						
		if (form_date_debut < date_debut_reservation){
			msg += WPJS.obk_GetTexte41 + " " + date_debut_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_debut"); 
		}
		if (form_date_debut > date_fin_reservation){
			msg += WPJS.obk_GetTexte42 + " " + date_fin_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_debut"); 
		}
	}
	if (form_date_fin){
		if (form_date_fin < date_debut_reservation){
			msg += WPJS.obk_GetTexte41 + " " + date_debut_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_fin"); 
		}
		if (form_date_fin > date_fin_reservation){
			msg += WPJS.obk_GetTexte42 + " " + date_fin_reservation_aff + "\n";
			ok = false;
			inputsError.push("form_date_fin"); 
		}
	}
	if (form_date_debut < (new Date())){
		msg += WPJS.obk_GetTexte43 + " \n";
		ok = false;
		inputsError.push("form_date_debut"); 
	}
	if (form_date_debut && form_date_fin){
		var reservation_duree_heure = ((form_date_fin - form_date_debut) / 1000) / 3600;
		if ((minBookingDuration > 0)&&(reservation_duree_heure < minBookingDuration)){
			ok = false;
			msg += WPJS.obk_GetTexte44 + " " + minBookingDuration + WPJS.obk_GetTexte51 + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin"); 
		}
		if ((tps_reservation_max_heure > 0)&&(reservation_duree_heure > tps_reservation_max_heure)){
			ok = false;
			msg += WPJS.obk_GetTexte45 + " " + tps_reservation_max_heure + WPJS.obk_GetTexte51 + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin");
		}
		if (reservation_duree_heure < 0){
			ok = false;
			msg += WPJS.obk_TArrivalDateAfterDepartureDate + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin");
		}
		
		var dateParcours = new Date(form_date_debut.getTime());
		var ok2 = true;
		var spaceInterval = parseInt(user_minutes_interval);
		if (spaceInterval < 1){spaceInterval = 1;}
		while ((dateParcours < form_date_fin) && ok2){
			if (!obk_checkDateInOT(openingtimes,dateParcours,0,"")){
				ok2 = false;
				ok = false;
				msg += WPJS.obk_GetTexte46 + " " + joursSemaine[dateParcours.getDay()] + " " + obk_2digits(dateParcours.getHours())+":"+obk_2digits(dateParcours.getMinutes()) + "\n";
				inputsError.push("form_date_debut"); 
				inputsError.push("form_date_fin");
			}
			dateParcours.setMinutes(dateParcours.getMinutes()+spaceInterval);
		}
	}
	if ((form_date_debut) && (!form_date_fin)){
		if (!obk_checkDateInOT(openingtimes,form_date_debut,0,"")){
			ok = false;
			msg += WPJS.obk_GetTexte46 + " " + joursSemaine[form_date_debut.getDay()] + " " + obk_2digits(form_date_debut.getHours())+":"+obk_2digits(form_date_debut.getMinutes()) + "\n";
			inputsError.push("form_date_debut"); 
			inputsError.push("form_date_fin");
		}
	}
	if (form_date_debut){
		if (!obk_checkDateInOT(openingtimes,form_date_debut,1,"")){
			ok = false;
			msg += WPJS.obk_GetTexte75 + " " + joursSemaine[form_date_debut.getDay()] + " " + obk_2digits(form_date_debut.getHours())+":"+obk_2digits(form_date_debut.getMinutes()) + "\n";
			inputsError.push("form_date_debut"); 
		}
	}
	if (form_date_fin){
		var form_date_fin_tmp = new Date(form_date_fin.getTime());
		form_date_fin_tmp.setSeconds(form_date_fin.getSeconds() - 1);
		if ((!obk_checkDateInOT(openingtimes,form_date_fin_tmp,2,"")) && (!obk_checkDateInOT(openingtimes,form_date_fin,2,""))){
			ok = false;
			msg += WPJS.obk_GetTexte76 + " " + joursSemaine[form_date_fin.getDay()] + " " + obk_2digits(form_date_fin.getHours())+":"+obk_2digits(form_date_fin.getMinutes()) + "\n";
			inputsError.push("form_date_fin"); 
		}
	}
	if (!ok){
		obk_show_inputs_error(JSON.stringify(inputsError));
		alert(msg);
	}
	return ok;
}

function obk_2digits(val) {
  return ('0' + val).slice(-2);
}