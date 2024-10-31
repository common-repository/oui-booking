
document.addEventListener("DOMContentLoaded", obk_initAccordions, false);
document.addEventListener("DOMContentLoaded", obk_initTextarea, false);
document.addEventListener("DOMContentLoaded", obk_initCSS, false);
document.addEventListener("DOMContentLoaded", obk_initDisplayPrice, false);

function obk_initDisplayPrice(){
	var tab = document.getElementsByClassName("obk_displayLocalPrice");
	if (document.getElementsByName("devise")[0]){
		var devise = document.getElementsByName("devise")[0].value;
		for(var i = 0; i < tab.length; i++){
			if (!isNaN(tab[i].innerHTML)){
				var script = document.createElement("script");
				var text = 'var x = ' + tab[i].innerHTML + ';';
				text += 'x = x.toLocaleString("none",{ style: "currency", currency: "' + devise + '"});';
				text += 'document.getElementsByClassName("obk_displayLocalPrice")['+i+'].innerHTML = x;';
				script.innerHTML = text;
				document.body.appendChild(script);
			}
		}
	}
}

function obk_initCSS(){
	if (document.getElementById("obk_content")){
		document.getElementById("wpcontent").style.backgroundColor = "#FFFFFF";
		document.body.style.backgroundColor = "#FFFFFF";
		if (document.getElementById("wpfooter")){
			document.getElementById("wpfooter").style.display = "none";
		}
	}
}

function obk_initAccordions(){
	var acc = document.getElementsByClassName("obk_accordion");
	var i;

	for (i = 0; i < acc.length; i++){
		if ((!acc[i].classList.contains("obk_hideAccordion")) && (!acc[i].classList.contains("obk_masked"))){
			acc[i].classList.toggle("obk_isactive");
			var panel = acc[i].nextElementSibling;
			panel.style.maxHeight = "initial";
		}
		
		acc[i].onclick = function(){
			this.classList.toggle("obk_isactive");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight){
				panel.style.maxHeight = null;
			}else{
				panel.style.maxHeight = panel.scrollHeight + "px";
			} 
			return false;
		}
	}
}

function obk_initTextarea() {
	tabTextarea = document.getElementsByTagName('textarea');
	for(var i=0; i<tabTextarea.length; i++){
		tabTextarea[i].onkeydown = function(e) {
			if (e.keyCode === 9) {
				var val = this.value,
				start = this.selectionStart,
				end = this.selectionEnd;
				this.value = val.substring(0, start) + '\t' + val.substring(end);
				this.selectionStart = this.selectionEnd = start + 1;
				return false;

			}
		};
	}
}

function obk_initOpeningTime(){
	document.getElementById("obk_OTDays").addEventListener("change",obk_OTLoadDay);
	document.getElementById("obk_OTType").addEventListener("change",obk_OTLoadDayType);
	document.getElementById("obk_OTOpened").addEventListener("change",obk_OTToogleOpened);
	document.getElementById("obk_OTBtnAddTime").addEventListener("click",obk_OTAddTime);
	document.getElementById("obk_OTBtnDuplicate").addEventListener("click",obk_OTDuplicateTime);
	obk_OTLoadAllDays();
	document.getElementById("obk_OTDays").selectedIndex = 0;
	obk_OTLoadDay();
}

function obk_OTLoadAllDays(){
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	for(var i = 0; i < tabDays.length; i++){
		if (tabDays[i][0][0] == "0"){
			document.getElementById("obk_OTDay" + i).innerHTML = "&#10060; ";
		}else{
			document.getElementById("obk_OTDay" + i).innerHTML = "&#9989; ";
		}
		document.getElementById("obk_OTDay" + i).innerHTML += document.getElementById("obk_OTDay" + i).getAttribute("obk_day");
	}
}

function obk_OTLoadDay(){
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	var day = tabDays[numDay];
	document.getElementById("obk_OTType").selectedIndex = 0;
	for(var i = 0; i < day.length; i++){
		if (day[i][0] == "0"){
			document.getElementById("obk_OTType" + i).innerHTML = "#10060; ";
		}else{
			document.getElementById("obk_OTType" + i).innerHTML = "#9989; ";
		}
		document.getElementById("obk_OTType" + i).innerHTML = "&" + document.getElementById("obk_OTType" + i).innerHTML + document.getElementById("obk_OTType" + i).getAttribute("obk_type");
	}
	document.getElementById("obk_OTType").selectedIndex = 0;
	obk_OTLoadDayType();
}

function obk_OTLoadDayType(){
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	var type = tabDays[numDay][document.getElementById("obk_OTType").selectedIndex];
	
	document.getElementById("obk_OTPeriods").innerHTML = "";
	var tabLabel = [WPJS.obk_OpeningTime,WPJS.obk_AllowedArrivalTime,WPJS.obk_RequestedDepartureTime];
	document.getElementById("obk_OTOpenedLabel").innerHTML = tabLabel[document.getElementById("obk_OTType").selectedIndex];
	if (type[0] == "0"){
		document.getElementById("obk_OTOpened").checked = false;
	}else{
		document.getElementById("obk_OTOpened").checked = true;
	}
	//for(var i in type){
	for(var i = 0; i < type.length; i++){
		if (i > 0){
			document.getElementById("obk_OTPeriods").innerHTML += '<div id="obk_OTTimeBlock'+i+'">De <input type="time" id="obk_OTStartTime'+i+'" value="'+type[i][0]+'" class="obk_OTStartTime"> à <input type="time" id="obk_OTEndTime'+i+'" value="'+type[i][1]+'" class="obk_OTEndTime"><select style="top:-2px" id="obk_OTChangeReference'+i+'" class="obk_OTChangeReference"><option value="0"></option><option value="1">Réf. debut</option><option value="2">Réf. fin</option><option value="3">Réf. debut et fin</option></select><a href="#" style="text-decoration:none;font-size: 16px;color:red" id="obk_OTRemoveTime'+i+'" class="obk_OTRemoveTime">&#10008;</a></div>';
		}
	}
	var tabInputTime = document.getElementsByClassName("obk_OTStartTime");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("change",obk_OTChangeStartTime);
	}
	var tabInputTime = document.getElementsByClassName("obk_OTEndTime");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("change",obk_OTChangeEndTime);
	}
	var tabInputTime = document.getElementsByClassName("obk_OTChangeReference");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("change",obk_OTChangeReference);
	}
	var tabInputTime = document.getElementsByClassName("obk_OTRemoveTime");
	for(var i = 0; i < tabInputTime.length; i++){
		tabInputTime[i].addEventListener("click",obk_OTRemoveTime);
	}
	
	var tabInputTime = document.getElementsByClassName("obk_OTStartTime");
	for(var numTime = 1; numTime <= tabInputTime.length; numTime++){
		obk_checkOT(numTime)
	}
	
}

function obk_checkOT(numTime){
	if ((document.getElementById("obk_OTStartTime"+numTime).value > document.getElementById("obk_OTEndTime"+numTime).value) && (document.getElementById("obk_OTEndTime"+numTime).value != "00:00")){
			document.getElementById("obk_OTStartTime"+numTime).style.backgroundColor = "red";
			document.getElementById("obk_OTEndTime"+numTime).style.backgroundColor = "red";
			document.getElementById("obk_OTStartTime"+numTime).style.color = "white";
			document.getElementById("obk_OTEndTime"+numTime).style.color = "white";
		}else{
			document.getElementById("obk_OTStartTime"+numTime).style.backgroundColor = "white";
			document.getElementById("obk_OTEndTime"+numTime).style.backgroundColor = "white";
			document.getElementById("obk_OTStartTime"+numTime).style.color = "#32373c";
			document.getElementById("obk_OTEndTime"+numTime).style.color = "#32373c";
		}
}

function obk_OTToogleOpened(e){
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	
	tabDays[numDay][currentType][0] = 1 - tabDays[numDay][currentType][0];
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
	
	if (tabDays[numDay][currentType][0] == "0"){
		if (currentType == 0){
			document.getElementById("obk_OTDay" + numDay).innerHTML = "&#10060; ";
			document.getElementById("obk_OTDay" + numDay).innerHTML += document.getElementById("obk_OTDay" + numDay).getAttribute("obk_day");
		}
		document.getElementById("obk_OTType" + currentType).innerHTML = "&#10060; ";
		document.getElementById("obk_OTType" + currentType).innerHTML += document.getElementById("obk_OTType" + currentType).getAttribute("obk_type");
	}else{
		if (currentType == 0){
			document.getElementById("obk_OTDay" + numDay).innerHTML = "&#9989; ";
			document.getElementById("obk_OTDay" + numDay).innerHTML += document.getElementById("obk_OTDay" + numDay).getAttribute("obk_day");
		}
		document.getElementById("obk_OTType" + currentType).innerHTML = "&#9989; ";
		document.getElementById("obk_OTType" + currentType).innerHTML += document.getElementById("obk_OTType" + currentType).getAttribute("obk_type");
	}
	

	
	
}

function obk_OTChangeStartTime(e){
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	var numTime = e.target.id.replace("obk_OTStartTime","");
	
	tabDays[numDay][currentType][numTime][0] = e.target.value;
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
	
	obk_checkOT(numTime);
}

function obk_OTChangeEndTime(e){
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	var numTime = e.target.id.replace("obk_OTEndTime","");
	
	tabDays[numDay][currentType][numTime][1] = e.target.value;
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
	obk_checkOT(numTime)
}

function obk_OTChangeReference(e){
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	var numReference = e.target.id.replace("obk_OTChangeReference","");
	
	var refStart = false;
	var refEnd = false;
	if ((e.target.value == "1")||(e.target.value == "3")){
		refStart = true;
	}
	if ((e.target.value == "2")||(e.target.value == "3")){
		refEnd = true;
	}
	tabDays[numDay][currentType][numReference][2] = refStart;
	tabDays[numDay][currentType][numReference][3] = refEnd;
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
}

function obk_OTRemoveTime(e){
	e.preventDefault();
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	var numTime = e.target.id.replace("obk_OTRemoveTime","");
	
	tabDays[numDay][currentType].splice(numTime,1);
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
	obk_OTLoadDayType();
}

function obk_OTAddTime(e){
	e.preventDefault();
	var timeCount = document.getElementsByClassName("obk_OTStartTime").length + 1;
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	
	tabDays[numDay][currentType].push(["08:00","12:00",false,false]);
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
	obk_OTLoadDayType();
}

function obk_OTDuplicateTime(e){
	var label = document.getElementById("obk_OTOpenedLabel").innerHTML;
	var currentType = document.getElementById("obk_OTType").selectedIndex;
	var tabDays = JSON.parse(document.getElementById("obk_OT").innerHTML);
	var numDay = (document.getElementById("obk_OTDays").selectedIndex + 1) % 7;
	
	if (confirm("Voulez-vous vraiment dupliquer les " + label + " sur toute la semaine?")){
		var duplicate = tabDays[numDay][currentType];
		for(var i=0; i < 7; i++){
			tabDays[i][currentType] = duplicate;
		}
	}
	document.getElementById("obk_OT").innerHTML = JSON.stringify(tabDays);
	obk_OTLoadAllDays();
}

function obk_txt2color(e){
	var id = e.target.id.substr(12);
	document.getElementById("obk_color" + id).value = e.target.value;
}

function obk_color2txt(e){
	var id = e.target.id.substr(9);
	document.getElementById("obk_colortxt" + id).value = e.target.value;
}

function obk_getEmplacementVal(id_emplacement,initOnlyNbDePlace){
	var initOnlyNbDePlace = (typeof initOnlyNbDePlace !== 'undefined') ? initOnlyNbDePlace : false;
	if ((id_emplacement != "")&&(document.getElementById("obk_mainGetEmplacementVal"))){	
		var wpnonce = document.getElementById("obk_mainGetEmplacementVal").value;
		var data = {
			'action': 'js_getEmplacementVal',
			'obk_idSpace': id_emplacement,
			'_wpnonce': wpnonce
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			var reponse = JSON.parse(response);
			if (!initOnlyNbDePlace){
				document.getElementsByName("devise")[0].value = reponse.devise;
				document.getElementsByName("prix_de_la_place")[0].value = reponse.prix_de_la_place;
				document.getElementsByName("devise")[0].value = reponse.devise;
				document.getElementsByName("acompte_prix")[0].value = reponse.acompte_prix;
				document.getElementsByName("acompte_pourcentage")[0].value = reponse.acompte_pourcentage;	
			}
			var option = "<option></option>";
			for(var i=0;i<reponse.listoption.length;i++){
				option += '<option mpid="'+reponse.listoption[i][0]+'" value="'+reponse.listoption[i][1]+'">'+reponse.listoption[i][1]+'</option>';
			}
			document.getElementsByName("labeloption-1")[0].innerHTML = option;
			var option = "<option></option>";
			for(var i=0;i<reponse.listtaxe.length;i++){
				option += '<option mpid="'+reponse.listtaxe[i][0]+'" value="'+reponse.listtaxe[i][1]+'">'+reponse.listtaxe[i][1]+'</option>';
			}
			document.getElementsByName("labeltaxe-1")[0].innerHTML = option;
			var option = "<option></option>";
			for(var i=0;i<reponse.listcoupon.length;i++){
				option += '<option mpid="'+reponse.listcoupon[i][0]+'" value="'+reponse.listcoupon[i][1]+'">'+reponse.listcoupon[i][1]+'</option>';
			}
			document.getElementsByName("labelcoupon-1")[0].innerHTML = option;
			var option = "<option></option>";
			for(var i=0;i<reponse.listreduction.length;i++){
				option += '<option mpid="'+reponse.listreduction[i][0]+'" value="'+reponse.listreduction[i][1]+'">'+reponse.listreduction[i][1]+'</option>';
			}
			document.getElementsByName("labelreduction-1")[0].innerHTML = option;
			document.getElementById("obk_nbDePlace").value = reponse.nb_de_place;
			document.getElementsByName("timeUnit")[0].value = reponse.timeUnit;
			document.getElementsByName("periodesprices")[0].value = reponse.periodesprices;
			document.getElementsByName("dayprice")[0].value = reponse.dayprice;
			obk_calendarDefaults();
		});
	}
}

function obk_showOnglet(onglet){
	var onglets = document.getElementsByClassName("obk_plugin_onglets");
	for(var i=0; i < onglets.length; i++){
		var ulChildren = onglets[i].children;                  
		for(var j = 0; j < ulChildren.length; j++){
			if(ulChildren[j].nodeName.toLowerCase() === 'li'){
				
				document.getElementById(ulChildren[j].id).className = "";
				document.getElementById(ulChildren[j].id.slice(0,-3)).style.display = "none";
			}
		}
	}
	//document.getElementById("obk_plugin_onglets_formulaire").classList.remove("obk_flex");
	document.getElementById(onglet+"_li").className = "obk_isactive";
	//if (onglet == "obk_plugin_onglets_formulaire"){
		//document.getElementById(onglet).style.display = "flex";
	//	document.getElementById(onglet).classList.add("obk_flex");
	//}else{
		document.getElementById(onglet).style.display = "block";
	//}
	
}

function obk_delete_mp(id){
	if (confirm(WPJS.obk_TConfirmDeleteItem)){
		document.getElementById("obk_coupon_"+id).style.display = "none";
		document.getElementById("obk_coupon_panel_"+id).style.display = "none";		
		var wpnonce = document.getElementById("obk_deleteCoupon").value;
		var data = {
			'action': 'js_deleteCoupon',
			'id_coupon': id,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function obk_closeAdminNotice(elt){
	elt.parentNode.style.display = "none";
}

function obk_reload_page(){
	window.location.reload();
}

function obk_eventFire(el, etype){
	if (el.fireEvent) {
		el.fireEvent("on" + etype);
	}else{
		var evObj = document.createEvent("Events");
		evObj.initEvent(etype, true, false);
		el.dispatchEvent(evObj);
	}
}

function obk_currentdate(day){
	var today = new Date();
	today.setDate(today.getDate() + day);
	var dd = today.getDate();
	var mm = today.getMonth()+1;
	var yyyy = today.getFullYear();
	if(dd<10) {
		dd = "0"+dd
	} 
	if(mm<10) {
		mm = "0"+mm
	}
	return yyyy + "-" + mm + "-" + dd;
}

function obk_ucfirst(str) {
	if (str.length > 0) {
		return str[0].toUpperCase() + str.substring(1);
	}else{
		return str;
	}
}

function obk_ajouterCoupon(type){
	if (document.getElementById("obk_mainAjouterCoupon")){
		var label = document.getElementsByName("label_"+type)[0].value;
		var date_debut = document.getElementsByName("date_debut_"+type)[0].value;
		var date_fin = document.getElementsByName("date_fin_"+type)[0].value;
		var quantite = document.getElementsByName("quantite_"+type)[0].value;
		if (document.getElementsByName("details_texte_"+type)[0]){
			var details_texte = document.getElementsByName("details_texte_"+type)[0].value;
		}else{
			var details_texte = '';
		}
		var montant = document.getElementsByName("montant_"+type)[0].value;
		var pourcentage = document.getElementsByName("pourcentage_"+type)[0].value;
		var periode_heure = document.getElementsByName("periode_heure_"+type)[0].value;
		var code = document.getElementsByName("code_"+type)[0].value;
		var description = document.getElementsByName("description_"+type)[0].value;
		var obk_idSpace = document.getElementsByName("obk_idSpace")[1].value;
		if (label == ""){
			alert(WPJS.obk_TPleaseFillLabel);
			document.getElementsByName("label_"+type)[0].focus();
			return;
		}
		if ((quantite == "") && (type == "option") && (code == "userchoice")){
			alert(WPJS.obk_TPleaseFillMaxQty);
			document.getElementsByName("quantite_"+type)[0].focus();
			return;
		}
		if (date_fin == ""){
			alert(WPJS.obk_TPleaseFillEndDate);
			document.getElementsByName("date_fin_"+type)[0].focus();
			return;
		}
		var wpnonce = document.getElementById("obk_mainAjouterCoupon").value;
		var data = {
			'action': 'js_ajouterCoupon',
			'label': label,
			'date_debut': date_debut,
			'date_fin': date_fin,
			'quantite': quantite,
			'details_texte': details_texte,
			'montant': montant,
			'pourcentage': pourcentage,
			'periode_heure': periode_heure,
			'code': code,
			'description': description,
			'obk_idSpace': obk_idSpace,
			'type': type,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {
			var id_coupon = response;
			var div = document.createElement("div");
			div.id = "obk_" + type + "Panel" + id_coupon;
			div.className = "obk_panel";
			var innerHTML = '<br><table>';
			if (date_debut != ""){ innerHTML += "<tr><td><b>"+WPJS.obk_TAvailable+" </b></td><td>"+date_debut+"</td></tr>";}
			if (date_fin != ""){ innerHTML += "<tr><td><b>"+WPJS.obk_TUntil+" </b></td><td>"+date_fin+"</td></tr>";}
			if ((quantite > 0)&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.obk_TRemainingQuantity+" </b></td><td>"+quantite+"/"+quantite+"</td></tr>";}
			if ((code != "")&&(type == "option")){ 
				innerHTML += "<tr><td><b>"+WPJS.obk_TAutomaticQuantity+" </b></td><td><select disabled>";
				innerHTML += '<option value="userchoice" '+((code == 'userchoice') ? 'selected' : '' )+'>'+WPJS.obk_tUserChoice+'</option>';
				innerHTML += '<option value="oneperhour" '+((code == 'oneperhour') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerHour+'</option>';
				innerHTML += '<option value="oneperday" '+((code == 'oneperday') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerDay+'</option>';
				innerHTML += '<option value="onepernight" '+((code == 'onepernight') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerNight+'</option>';
				innerHTML += '<option value="oneperweek" '+((code == 'oneperweek') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerWeek+'</option>';
				innerHTML += '<option value="onepermonth" '+((code == 'onepermonth') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerMonth+'</option>';
				innerHTML += "</select></td></tr>";
			}
			if (montant > 0){ innerHTML += "<tr><td><b>"+WPJS.obk_TAmount+" </b></td><td class='obk_displayLocalPrice'>"+montant+"</td></tr>";}
			if (pourcentage > 0){ innerHTML += "<tr><td><b>"+WPJS.obk_TPercentage+" </b></td><td>"+pourcentage+"%</td></tr>";}
			if (periode_heure > 0){ innerHTML += "<tr><td><b>"+WPJS.obk_TPeriodicity+" </b></td><td>"+periode_heure+"h</td></tr>";}
			if ((code != "")&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.obk_TCode+" </b></td><td>"+code+"</td></tr>";}
			if (details_texte != ""){
				innerHTML += "<tr><td colspan='2'><b>"+WPJS.obk_tOptions+': </b><br>';
				var displayDetails = details_texte.split("\n");
				for(var i=0;i<displayDetails.length;i++){
					innerHTML += '#'+(i+1)+': ';
					innerHTML += displayDetails[i];
					innerHTML += '<br>';
				}
				innerHTML += '</td></tr>';
			}
			if (description != ""){ innerHTML += "<tr><td colspan='2'><br><b>"+WPJS.obk_TDescriptionDetails+": </b><br>"+description+"</td></tr>";}
			div.innerHTML = innerHTML + "</table><br>";
			document.getElementById("obk_btnAjout"+type).prepend(div);
			var bouton = document.createElement("button");
			bouton.id = "obk_" + type + id_coupon;
			bouton.className = "obk_accordion";
			bouton.style.display = "none";
			var tmp = "'idCoupon'";
			var tmp2 = "'"+type+"'";
			bouton.innerHTML = 
				'<a href="#" onclick="obk_associeCoupon(this.getAttribute('+tmp+'),'+tmp2+')" idcoupon="'+id_coupon+'"><span class="obk_no_rotate_arrow_price"><img class="emoji" src="' + WPJS.pluginsUrl + '/img/arrow.svg' + '"></span></a><b>' + obk_ucfirst(label) + '</b>';
			document.getElementById("obk_btnAjout"+type).prepend(bouton);
			
			bouton.onclick = function() {
				this.classList.toggle("obk_isactive");
				var panel = this.nextElementSibling;
				if (panel.style.maxHeight){
					panel.style.maxHeight = null;
				}else{
					panel.style.maxHeight = panel.scrollHeight + "px";
				} 
			}
			var bouton = document.createElement("button");
			bouton.id = "obk_" + type + "Space" + id_coupon;
			bouton.className = "obk_accordion";
			var tmp = "'idCoupon'";
			var tmp2 = "'"+type+"'";
			bouton.innerHTML = 
				'<a href="#" onclick="obk_desassocieCoupon(this.getAttribute('+tmp+'),'+tmp2+')" idcoupon="'+id_coupon+'"><span class="obk_rotate_arrow_price"><img class="emoji" src="' + WPJS.pluginsUrl + '/img/arrow.svg' + '"></span></a><b>' + obk_ucfirst(label) + '</b>';
			document.getElementById("obk_" + type + "s_associes").firstChild.appendChild(bouton);
			bouton.onclick = function() {
				this.classList.toggle("obk_isactive");
				var panel = this.nextElementSibling;
				if (panel.style.maxHeight){
					panel.style.maxHeight = null;
				}else{
					panel.style.maxHeight = panel.scrollHeight + "px";
				} 
			}
			var div = document.createElement("div");
			div.id = "obk_" + type + "PanelSpace" + id_coupon;
			div.className = "obk_panel";
			var innerHTML = '<br><table>';
			if (date_debut != ""){ innerHTML += "<tr><td><b>"+WPJS.obk_TAvailable+" </b></td><td>"+date_debut+"</td></tr>";}
			if (date_fin != ""){ innerHTML += "<tr><td><b>"+WPJS.obk_TUntil+" </b></td><td>"+date_fin+"</td></tr>";}
			if ((quantite > 0)&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.obk_TRemainingQuantity+" </b></td><td>"+quantite+"/"+quantite+"</td></tr>";}
			if ((quantite > 0)&&(type == "option")){ innerHTML += "<tr><td><b>"+WPJS.obk_TMax+" </b></td><td>"+quantite+"</td></tr>";}
			if ((code != "")&&(type == "option")){ 
				innerHTML += "<tr><td><b>"+WPJS.obk_TAutomaticQuantity+" </b></td><td><select disabled>";
				innerHTML += '<option value="userchoice" '+((code == 'userchoice') ? 'selected' : '' )+'>'+WPJS.obk_tUserChoice+'</option>';
				innerHTML += '<option value="oneperhour" '+((code == 'oneperhour') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerHour+'</option>';
				innerHTML += '<option value="oneperday" '+((code == 'oneperday') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerDay+'</option>';
				innerHTML += '<option value="onepernight" '+((code == 'onepernight') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerNight+'</option>';
				innerHTML += '<option value="oneperweek" '+((code == 'oneperweek') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerWeek+'</option>';
				innerHTML += '<option value="onepermonth" '+((code == 'onepermonth') ? 'selected' : '' )+'>'+WPJS.obk_TOnePerMonth+'</option>';
				innerHTML += "</select></td></tr>";
			}
			if (montant > 0){ innerHTML += "<tr><td><b>"+WPJS.obk_TAmount+" </b></td><td class='obk_displayLocalPrice'>"+montant+"</td></tr>";}
			if (pourcentage > 0){ innerHTML += "<tr><td><b>"+WPJS.obk_TPercentage+" </b></td><td>"+pourcentage+"%</td></tr>";}
			if (periode_heure > 0){ innerHTML += "<tr><td><b>"+WPJS.obk_TPeriodicity+" </b></td><td>"+periode_heure+"h</td></tr>";}
			if ((code != "")&&(type != "option")){ innerHTML += "<tr><td><b>"+WPJS.obk_TCode+" </b></td><td>"+code+"</td></tr>";}
			if (details_texte != ""){
				innerHTML += "<tr><td colspan='2'><b>"+WPJS.obk_tOptions+': </b><br>';
				var displayDetails = details_texte.split("\n");
				for(var i=0;i<displayDetails.length;i++){
					innerHTML += '#'+(i+1)+': ';
					innerHTML += displayDetails[i];
					innerHTML += '<br>';
				}
				innerHTML += '</td></tr>';
			}
			if (description != ""){ innerHTML += "<tr><td colspan='2'><br><b>"+WPJS.obk_TDescriptionDetails+": </b><br>"+description+"</td></tr>";}
			div.innerHTML = innerHTML + "</table><br>";
			div.style.maxHeight = "initial";
			document.getElementById("obk_" + type + "s_associes").firstChild.appendChild(div);
			window.scrollTo(0, 0);
			obk_initDisplayPrice();
			document.getElementsByName("label_"+type)[0].value = "";
			document.getElementsByName("date_debut_"+type)[0].value = obk_currentdate(0);
			document.getElementsByName("date_fin_"+type)[0].value = "";
			document.getElementsByName("quantite_"+type)[0].value = "";
			if (document.getElementsByName("details_texte_"+type)[0]){
				document.getElementsByName("details_texte_"+type)[0].value = "";
			}
			document.getElementsByName("montant_"+type)[0].value = "";
			document.getElementsByName("pourcentage_"+type)[0].value = "";
			document.getElementsByName("periode_heure_"+type)[0].value = "";
			document.getElementsByName("code_"+type)[0].value = "userchoice";
			document.getElementsByName("description_"+type)[0].value = "";
		});
	}
}

function obk_associeCoupon(id_coupon,type){
	if (document.getElementById("obk_mainDesassocieCoupon")){
		document.getElementById("obk_" + type + id_coupon).style.display = "none";
		document.getElementById("obk_" + type + "Panel" + id_coupon).style.maxHeight = "1px";
		document.getElementById("obk_" + type + "Space" + id_coupon).style.display = "block";
		document.getElementById("obk_" + type + "PanelSpace" + id_coupon).style.maxHeight = "initial";
		var obk_idSpace = document.getElementsByName("obk_idSpace")[1].value;
		var wpnonce = document.getElementById("obk_mainAssocieCoupon").value;
		var data = {
			'action': 'js_associeCoupon',
			'obk_idSpace': obk_idSpace,
			'id_coupon': id_coupon,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function obk_desassocieCoupon(id_coupon,type){
	if (document.getElementById("obk_mainDesassocieCoupon")){
		document.getElementById("obk_" + type + id_coupon).style.display = "block";
		document.getElementById("obk_" + type + "Panel" + id_coupon).style.maxHeight = "initial";
		document.getElementById("obk_" + type + "Space" + id_coupon).style.display = "none";
		document.getElementById("obk_" + type + "PanelSpace" + id_coupon).style.maxHeight = "1px";
		var obk_idSpace = document.getElementsByName("obk_idSpace")[1].value;
		var wpnonce = document.getElementById("obk_mainDesassocieCoupon").value;
		var data = {
			'action': 'js_desassocieCoupon',
			'obk_idSpace': obk_idSpace,
			'id_coupon': id_coupon,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function obk_emptyMontant(type,id){
	var id = (typeof id !== 'undefined') ? id : '';
	if (document.getElementsByName("pourcentage_"+type+id)[0].value != ""){
		document.getElementsByName("montant_"+type+id)[0].value = "";
	}
}

function obk_emptyPourcentage(type,id){
	var id = (typeof id !== 'undefined') ? id : '';
	if (document.getElementsByName("montant_"+type+id)[0].value != ""){
		document.getElementsByName("pourcentage_"+type+id)[0].value = "";
	}
}

function obk_listenerExportCSV(){
	document.forms["obk_exportCSV"].addEventListener("submit",obk_exportCSV);
}

function obk_listenerSaveShortcode(){
	document.forms["obk_saveShortcode"].addEventListener("submit",obk_saveShortcode);
}

function obk_listenerFilterBookings(){
	document.forms["obk_filterBookings"].addEventListener("submit",obk_filterBookings);
}

function obk_filterBookings(e){
	e.preventDefault();
	var wpnonce = encodeURIComponent(document.getElementById("obk_mainCalendar").value);
	var data = {
		'action': 'js_chargeCalendrier',
		'obk_idSpace': "-1",
		'obk_init': "false",
		'CurrentDate': "0000-00-00",
		'_wpnonce': wpnonce,
		'filterDepartureDate': document.getElementsByName('filterDepartureDate')[0].value,
		'filterArrivalDate': document.getElementsByName('filterArrivalDate')[0].value,
		'filterBookingStatus': document.getElementsByName('filterBookingStatus')[0].value
	};
	if (typeof(ajaxurl) == "undefined"){
		var ajaxurl = WPJS.adminAjaxUrl;
	}
	jQuery.post(ajaxurl, data, function(response) {
		obk_calendatMakeCalendarResa(JSON.parse(response));
	});
}

function obk_exportCSV(e){
	document.getElementsByName('obk_ead')[0].value = document.getElementsByName('filterArrivalDate')[0].value;
	document.getElementsByName('obk_edd')[0].value = document.getElementsByName('filterDepartureDate')[0].value;
	document.getElementsByName('obk_ebs')[0].value = document.getElementsByName('filterBookingStatus')[0].value;
}
var panels;
function obk_saveShortcode(e){
	var tabEmplacements = new Array();
	panels = document.getElementsByClassName("obk_panel");
	var ok = true;
	for(var i=0; i<panels.length; i++){
		var table = new Array('','','','','');
		var posttype = -1;
		var customlink = '';
		
		var inputs = panels[i].getElementsByTagName("input");
		for (var j=0; j < inputs.length; j++){
			if (inputs[j].name.substr(0,22) == "shortcodespaceposttype"){
				if (inputs[j].checked){
					posttype = inputs[j].value;
					table[2] = posttype;
				}
			}
			if (inputs[j].name == "shortcodespacecustomlink"){
				customlink = inputs[j].value;
			}
		}
		if (posttype == "link"){
			table[3] = customlink;
		}
		var selects = panels[i].getElementsByTagName("select");
		for (var j=0; j < selects.length; j++){
			if (selects[j].name == "shortcodespace"){
				table[0] = selects[j].value;
			}
			if (selects[j].name == "shortcodespaceposition"){
				table[1] = selects[j].value;
			}
			if ((selects[j].name == "shortcodeRedirectionSelectPage")&&(posttype == "page")){
				table[3] = selects[j].value;
			}
			if ((selects[j].name == "shortcodeRedirectionSelectPost")&&(posttype == "post")){
				table[3] = selects[j].value;
			}
		}
		var textareas = panels[i].getElementsByTagName("textarea");
		for (var j=0; j < textareas.length; j++){
			if (textareas[j].name.substr(0,25) == "shortcodespacedescription"){
				table[4] = obk_getWPEditorContent(textareas[j].id);
			}
		}
		if ((table[0] != "")&&(table[2] != "")&&(table[3] != "")){
			tabEmplacements.push(table);
		}else if ((table[0] != "")||(table[2] != "")||(table[3] != "")||(table[4] != "")){
			ok = false;
			alert(WPJS.obk_TFillAllFieldsInPosition + " " + table[1]);
		}
	}
	if (ok){
		for(var i=0; i<panels.length; i++){
			panels[i].innerHTML = "";
		}
		document.getElementById("tabEmplacements").innerHTML = JSON.stringify(tabEmplacements);
	}else{
		e.preventDefault();
	}
}

function obk_getWPEditorContent(id) {
    var content;
    var inputid = id;
    var editor = tinyMCE.get(inputid);
    var textArea = jQuery('textarea#' + inputid);    
    if (textArea.length>0 && textArea.is(':visible')) {
        content = textArea.val();        
    } else {
        content = editor.getContent();
    }
    return content;
}

function obk_getMP(elt,list,type){
	var list = document.getElementsByName(list)[0];
	var value = elt.value;
	//for(var i in list.options){
	for(var i = 0; i < list.options.length; i++){
		if (list.options[i].value == value){
			var idMP = list.options[i].getAttribute("mpid");
			var wpnonce = document.getElementById("obk_get_MP").value;
			var data = {
				'action': 'js_getMP',
				'idMP': idMP,
				'_wpnonce': wpnonce
			};
			jQuery.post(ajaxurl, data, function(response) {
				var reponse = JSON.parse(response);
				document.getElementsByName("montant"+type+"-1")[0].value = reponse.montant;
				document.getElementsByName("pourcentage"+type+"-1")[0].value = reponse.pourcentage;
				document.getElementsByName("periode_heure"+type+"-1")[0].value = reponse.periode_heure;
				document.getElementsByName("code"+type+"-1")[0].value = reponse.code;
				document.getElementsByName("description"+type+"-1")[0].value = reponse.description;
			});
			
			break;
		}
	}
}

var obk_tzafrica = new Array('Africa/Abidjan','Africa/Accra','Africa/Addis_Ababa','Africa/Algiers','Africa/Asmara','Africa/Asmera','Africa/Bamako','Africa/Bangui','Africa/Banjul','Africa/Bissau','Africa/Blantyre','Africa/Brazzaville','Africa/Bujumbura','Africa/Cairo','Africa/Casablanca','Africa/Ceuta','Africa/Conakry','Africa/Dakar','Africa/Dar_es_Salaam','Africa/Djibouti','Africa/Douala','Africa/El_Aaiun','Africa/Freetown','Africa/Gaborone','Africa/Harare','Africa/Johannesburg','Africa/Juba','Africa/Kampala','Africa/Khartoum','Africa/Kigali','Africa/Kinshasa','Africa/Lagos','Africa/Libreville','Africa/Lome','Africa/Luanda','Africa/Lubumbashi','Africa/Lusaka','Africa/Malabo','Africa/Maputo','Africa/Maseru','Africa/Mbabane','Africa/Mogadishu','Africa/Monrovia','Africa/Nairobi','Africa/Ndjamena','Africa/Niamey','Africa/Nouakchott','Africa/Ouagadougou','Africa/Porto-Novo','Africa/Sao_Tome','Africa/Timbuktu','Africa/Tripoli','Africa/Tunis','Africa/Windhoek');
var obk_tzamerica = new Array('America/Adak','America/Anchorage','America/Anguilla','America/Antigua','America/Araguaina','America/Argentina/Buenos_Aires','America/Argentina/Catamarca','America/Argentina/ComodRivadavia','America/Argentina/Cordoba','America/Argentina/Jujuy','America/Argentina/La_Rioja','America/Argentina/Mendoza','America/Argentina/Rio_Gallegos','America/Argentina/Salta','America/Argentina/San_Juan','America/Argentina/San_Luis','America/Argentina/Tucuman','America/Argentina/Ushuaia','America/Aruba','America/Asuncion','America/Atikokan','America/Atka','America/Bahia','America/Bahia_Banderas','America/Barbados','America/Belem','America/Belize','America/Blanc-Sablon','America/Boa_Vista','America/Bogota','America/Boise','America/Buenos_Aires','America/Cambridge_Bay','America/Campo_Grande','America/Cancun','America/Caracas','America/Catamarca','America/Cayenne','America/Cayman','America/Chicago','America/Chihuahua','America/Coral_Harbour','America/Cordoba','America/Costa_Rica','America/Creston','America/Cuiaba','America/Curacao','America/Danmarkshavn','America/Dawson','America/Dawson_Creek','America/Denver','America/Detroit','America/Dominica','America/Edmonton','America/Eirunepe','America/El_Salvador','America/Ensenada','America/Fort_Wayne','America/Fortaleza','America/Glace_Bay','America/Godthab','America/Goose_Bay','America/Grand_Turk','America/Grenada','America/Guadeloupe','America/Guatemala','America/Guayaquil','America/Guyana','America/Halifax','America/Havana','America/Hermosillo','America/Indiana/Indianapolis','America/Indiana/Knox','America/Indiana/Marengo','America/Indiana/Petersburg','America/Indiana/Tell_City','America/Indiana/Vevay','America/Indiana/Vincennes','America/Indiana/Winamac','America/Indianapolis','America/Inuvik','America/Iqaluit','America/Jamaica','America/Jujuy','America/Juneau','America/Kentucky/Louisville','America/Kentucky/Monticello','America/Knox_IN','America/Kralendijk','America/La_Paz','America/Lima','America/Los_Angeles','America/Louisville','America/Lower_Princes','America/Maceio','America/Managua','America/Manaus','America/Marigot','America/Martinique','America/Matamoros','America/Mazatlan','America/Mendoza','America/Menominee','America/Merida','America/Metlakatla','America/Mexico_City','America/Miquelon','America/Moncton','America/Monterrey','America/Montevideo','America/Montreal','America/Montserrat','America/Nassau','America/New_York','America/Nipigon','America/Nome','America/Noronha','America/North_Dakota/Beulah','America/North_Dakota/Center','America/North_Dakota/New_Salem','America/Ojinaga','America/Panama','America/Pangnirtung','America/Paramaribo','America/Phoenix','America/Port-au-Prince','America/Port_of_Spain','America/Porto_Acre','America/Porto_Velho','America/Puerto_Rico','America/Rainy_River','America/Rankin_Inlet','America/Recife','America/Regina','America/Resolute','America/Rio_Branco','America/Rosario','America/Santa_Isabel','America/Santarem','America/Santiago','America/Santo_Domingo','America/Sao_Paulo','America/Scoresbysund','America/Shiprock','America/Sitka','America/St_Barthelemy','America/St_Johns','America/St_Kitts','America/St_Lucia','America/St_Thomas','America/St_Vincent','America/Swift_Current','America/Tegucigalpa','America/Thule','America/Thunder_Bay','America/Tijuana','America/Toronto','America/Tortola','America/Vancouver','America/Virgin','America/Whitehorse','America/Winnipeg','America/Yakutat','America/Yellowknife');
var obk_tzantarctica = new Array('Antarctica/Casey','Antarctica/Davis','Antarctica/DumontDUrville','Antarctica/Macquarie','Antarctica/Mawson','Antarctica/McMurdo','Antarctica/Palmer','Antarctica/Rothera','Antarctica/South_Pole','Antarctica/Syowa','Antarctica/Troll','Antarctica/Vostok');
var obk_tzarctic = new Array('Arctic/Longyearbyen');
var obk_tzasia = new Array('Asia/Aden','Asia/Almaty','Asia/Amman','Asia/Anadyr','Asia/Aqtau','Asia/Aqtobe','Asia/Ashgabat','Asia/Ashkhabad','Asia/Baghdad','Asia/Bahrain','Asia/Baku','Asia/Bangkok','Asia/Beirut','Asia/Bishkek','Asia/Brunei','Asia/Calcutta','Asia/Chita','Asia/Choibalsan','Asia/Chongqing','Asia/Chungking','Asia/Colombo','Asia/Dacca','Asia/Damascus','Asia/Dhaka','Asia/Dili','Asia/Dubai','Asia/Dushanbe','Asia/Gaza','Asia/Harbin','Asia/Hebron','Asia/Ho_Chi_Minh','Asia/Hong_Kong','Asia/Hovd','Asia/Irkutsk','Asia/Istanbul','Asia/Jakarta','Asia/Jayapura','Asia/Jerusalem','Asia/Kabul','Asia/Kamchatka','Asia/Karachi','Asia/Kashgar','Asia/Kathmandu','Asia/Katmandu','Asia/Khandyga','Asia/Kolkata','Asia/Krasnoyarsk','Asia/Kuala_Lumpur','Asia/Kuching','Asia/Kuwait','Asia/Macao','Asia/Macau','Asia/Magadan','Asia/Makassar','Asia/Manila','Asia/Muscat','Asia/Nicosia','Asia/Novokuznetsk','Asia/Novosibirsk','Asia/Omsk','Asia/Oral','Asia/Phnom_Penh','Asia/Pontianak','Asia/Pyongyang','Asia/Qatar','Asia/Qyzylorda','Asia/Rangoon','Asia/Riyadh','Asia/Saigon','Asia/Sakhalin','Asia/Samarkand','Asia/Seoul','Asia/Shanghai','Asia/Singapore','Asia/Srednekolymsk','Asia/Taipei','Asia/Tashkent','Asia/Tbilisi','Asia/Tehran','Asia/Tel_Aviv','Asia/Thimbu','Asia/Thimphu','Asia/Tokyo','Asia/Ujung_Pandang','Asia/Ulaanbaatar','Asia/Ulan_Bator','Asia/Urumqi','Asia/Ust-Nera','Asia/Vientiane','Asia/Vladivostok','Asia/Yakutsk','Asia/Yekaterinburg','Asia/Yerevan');
var obk_tzatlantic = new Array('Atlantic/Azores','Atlantic/Bermuda','Atlantic/Canary','Atlantic/Cape_Verde','Atlantic/Faeroe','Atlantic/Faroe','Atlantic/Jan_Mayen','Atlantic/Madeira','Atlantic/Reykjavik','Atlantic/South_Georgia','Atlantic/St_Helena','Atlantic/Stanley');
var obk_tzaustralia = new Array('Australia/ACT','Australia/Adelaide','Australia/Brisbane','Australia/Broken_Hill','Australia/Canberra','Australia/Currie','Australia/Darwin','Australia/Eucla','Australia/Hobart','Australia/LHI','Australia/Lindeman','Australia/Lord_Howe','Australia/Melbourne','Australia/North','Australia/NSW','Australia/Perth','Australia/Queensland','Australia/South','Australia/Sydney','Australia/Tasmania','Australia/Victoria','Australia/West','Australia/Yancowinna');
var obk_tzeurope = new Array('Europe/Amsterdam','Europe/Andorra','Europe/Athens','Europe/Belfast','Europe/Belgrade','Europe/Berlin','Europe/Bratislava','Europe/Brussels','Europe/Bucharest','Europe/Budapest','Europe/Busingen','Europe/Chisinau','Europe/Copenhagen','Europe/Dublin','Europe/Gibraltar','Europe/Guernsey','Europe/Helsinki','Europe/Isle_of_Man','Europe/Istanbul','Europe/Jersey','Europe/Kaliningrad','Europe/Kiev','Europe/Lisbon','Europe/Ljubljana','Europe/London','Europe/Luxembourg','Europe/Madrid','Europe/Malta','Europe/Mariehamn','Europe/Minsk','Europe/Monaco','Europe/Moscow','Europe/Nicosia','Europe/Oslo','Europe/Paris','Europe/Podgorica','Europe/Prague','Europe/Riga','Europe/Rome','Europe/Samara','Europe/San_Marino','Europe/Sarajevo','Europe/Simferopol','Europe/Skopje','Europe/Sofia','Europe/Stockholm','Europe/Tallinn','Europe/Tirane','Europe/Tiraspol','Europe/Uzhgorod','Europe/Vaduz','Europe/Vatican','Europe/Vienna','Europe/Vilnius','Europe/Volgograd','Europe/Warsaw','Europe/Zagreb','Europe/Zaporozhye','Europe/Zurich');
var obk_tzindian = new Array('Indian/Antananarivo','Indian/Chagos','Indian/Christmas','Indian/Cocos','Indian/Comoro','Indian/Kerguelen','Indian/Mahe','Indian/Maldives','Indian/Mauritius','Indian/Mayotte','Indian/Reunion');
var obk_tzpacific = new Array('Pacific/Apia','Pacific/Auckland','Pacific/Bougainville','Pacific/Chatham','Pacific/Chuuk','Pacific/Easter','Pacific/Efate','Pacific/Enderbury','Pacific/Fakaofo','Pacific/Fiji','Pacific/Funafuti','Pacific/Galapagos','Pacific/Gambier','Pacific/Guadalcanal','Pacific/Guam','Pacific/Honolulu','Pacific/Johnston','Pacific/Kiritimati','Pacific/Kosrae','Pacific/Kwajalein','Pacific/Majuro','Pacific/Marquesas','Pacific/Midway','Pacific/Nauru','Pacific/Niue','Pacific/Norfolk','Pacific/Noumea','Pacific/Pago_Pago','Pacific/Palau','Pacific/Pitcairn','Pacific/Pohnpei','Pacific/Ponape','Pacific/Port_Moresby','Pacific/Rarotonga','Pacific/Saipan','Pacific/Samoa','Pacific/Tahiti','Pacific/Tarawa','Pacific/Tongatapu','Pacific/Truk','Pacific/Wake','Pacific/Wallis','Pacific/Yap');
var obk_tzothers = new Array('UTC');

function showTimezone(value,selected){
	var tab = [];
	if (value == 1){tab = obk_tzafrica;}
	if (value == 2){tab = obk_tzamerica;}
	if (value == 3){tab = obk_tzantarctica;}
	if (value == 4){tab = obk_tzarctic;}
	if (value == 5){tab = obk_tzasia;}
	if (value == 6){tab = obk_tzatlantic;}
	if (value == 7){tab = obk_tzaustralia;}
	if (value == 8){tab = obk_tzeurope;}
	if (value == 9){tab = obk_tzindian;}
	if (value == 10){tab = obk_tzpacific;}
	if (value == 11){tab = obk_tzothers;}
	
	var options = '';
	//for(var i in tab){
	for(var i = 0; i < tab.length; i++){
		var sel = '';
		if (tab[i] == selected){sel = "selected";}
		options += '<option ' + sel + ' value="'+tab[i]+'">'+tab[i]+'</option>';
	}
	document.getElementById('obk_timezone').innerHTML = options;	
	//document.getElementById('obk_timezone').value = '';	
}

function obk_shortcodeChangeLanguage(select){
	var id = select.name.split("-")[1];
	document.getElementById("obk_shortcodeShortcode" + id).innerHTML = '[obk_shortcode id="' + id + '" lang="' + select.value + '"]';
}

function obk_initDefaultActionButton(){
	if (document.getElementById("obk_btnAddSpace")){
		document.getElementById("obk_btnAddSpace").addEventListener("click",obk_showAddSpaceForm);
		document.getElementById("obk_btnCancelAddSpace").addEventListener("click",obk_hideAddSpaceForm);
	}
	if (document.getElementById("obk_btnEditSpace")){
		document.getElementById("obk_btnEditSpace").addEventListener("click",obk_showEditSpaceForm);
		document.getElementById("obk_btnCancelEditSpace").addEventListener("click",obk_hideEditSpaceForm);
	}
}

function obk_showAddSpaceForm(){
	//document.getElementById("obk_addSpaceForm").style.display = "flex";
	document.getElementById("obk_addSpaceForm").classList.add("obk_flex");
}
function obk_showEditSpaceForm(){
	//document.getElementById("obk_editSpaceForm").style.display = "flex";
	document.getElementById("obk_editSpaceForm").classList.add("obk_flex");
}
function obk_hideAddSpaceForm(){
	//document.getElementById("obk_addSpaceForm").style.display = "none";
	document.getElementById("obk_addSpaceForm").classList.remove("obk_flex");
}
function obk_hideEditSpaceForm(){
	//document.getElementById("obk_editSpaceForm").style.display = "none";
	document.getElementById("obk_editSpaceForm").classList.remove("obk_flex");
}

function obk_ajouterPeriodePrix(){
	if ((document.getElementById("obk_periodPrice")) &&
		(document.getElementById("obk_periodPriceStartDate")) && (document.getElementById("obk_periodPriceStartDate").value != "") &&
		(document.getElementById("obk_periodPriceFinishDate")) && (document.getElementById("obk_periodPriceFinishDate").value != "")	
	){
		var start = document.getElementById("obk_periodPriceStartDate").value + " " + document.getElementById("obk_periodPriceStartTime").value;
		var finish = document.getElementById("obk_periodPriceFinishDate").value + " " + document.getElementById("obk_periodPriceFinishTime").value;
		var price = document.getElementById("obk_periodPrice").value;
		var obk_idSpace = document.getElementsByName("obk_idSpace")[1].value;
		var wpnonce = document.getElementById("obk_mainAddPeriodePrice").value;
		var data = {
			'action': 'js_addPeriodePrice',
			'start': start,
			'finish': finish,
			'price': price,
			'obk_idSpace': obk_idSpace,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {
			if (response != ''){
				alert(response);
			}else{
				var index = document.getElementsByClassName('obk_periodList').length + 1;
				var div = document.createElement('div');
				div.className = 'obk_periodList';
				div.id = 'obk_period' + index;
				var innerHTML =	'<div><div><input type="date" disabled value="'+start.split(' ')[0]+'"><input type="time" disabled value="'+start.split(' ')[1]+'"></div><div><input disabled type="date" value="'+finish.split(' ')[0]+'"><input disabled type="time" value="'+finish.split(' ')[1]+'"></div></div>';
				innerHTML +=	'<div><div>'+'Price'+': ' + price + '</div></div>';
				innerHTML +=	'<div><div class="obk_delete"><span class="obk_deleteAjax" onclick="obk_delete_period(\''+index+'\',\''+obk_idSpace+'\',\''+start+'\',\''+finish+'\',\''+price+'\')">'+WPJS.obk_TDelete+'</span></div></div>';
				div.innerHTML = innerHTML;
				document.getElementById('obk_listperiodesprices').appendChild(div);
				document.getElementById("obk_periodPriceStartDate").value = '',
				document.getElementById("obk_periodPriceStartTime").value = '00:00',
				document.getElementById("obk_periodPriceFinishDate").value = '';
				document.getElementById("obk_periodPriceFinishTime").value = '00:00';
				document.getElementById("obk_periodPrice").value = 0;
			}
			
		});
	}
}

function obk_delete_period(index,obk_idSpace,start,finish,price){
	if (confirm(WPJS.obk_TConfirmDeleteItem)){
		document.getElementById("obk_period"+index).style.display = "none";	
		var wpnonce = document.getElementById("obk_deletePeriod").value;
		var data = {
			'action': 'js_deletePeriod',
			'obk_idSpace': obk_idSpace,
			'start': start,
			'finish': finish,
			'price': price,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}

function obk_ajouterExceptionalClosure(){
	if ((document.getElementById("obk_periodClosureStartDate")) && (document.getElementById("obk_periodClosureStartDate").value != "") &&
		(document.getElementById("obk_periodClosureFinishDate")) && (document.getElementById("obk_periodClosureFinishDate").value != "")
	){
		var start = document.getElementById("obk_periodClosureStartDate").value + " " + document.getElementById("obk_periodClosureStartTime").value;
		var finish = document.getElementById("obk_periodClosureFinishDate").value + " " + document.getElementById("obk_periodClosureFinishTime").value;
		var obk_idSpace = document.getElementsByName("obk_idSpace")[1].value;
		var wpnonce = document.getElementById("obk_mainAddExceptionalClosure").value;
		var data = {
			'action': 'js_addExceptionalClosure',
			'start': start,
			'finish': finish,
			'obk_idSpace': obk_idSpace,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {
			if (response != ''){
				alert(response);
			}else{
				var index = document.getElementsByClassName('obk_periodListClosure').length + 1;
				var div = document.createElement('div');
				div.className = 'obk_periodListClosure';
				div.id = 'obk_periodclosure' + index;
				var innerHTML =	'<div><div><input type="date" disabled value="'+start.split(' ')[0]+'"><input type="time" disabled value="'+start.split(' ')[1]+'"></div><div><input disabled type="date" value="'+finish.split(' ')[0]+'"><input disabled type="time" value="'+finish.split(' ')[1]+'"></div></div>';
				innerHTML +=	'<div><div class="obk_deleteclosure"><span class="obk_deleteAjax" onclick="obk_delete_period_closure(\''+index+'\',\''+obk_idSpace+'\',\''+start+'\',\''+finish+'\')">'+WPJS.obk_TDelete+'</span></div></div>';
				div.innerHTML = innerHTML;
				document.getElementById('obk_listperiodesclosures').appendChild(div);
				document.getElementById("obk_periodClosureStartDate").value = '',
				document.getElementById("obk_periodClosureStartTime").value = '00:00',
				document.getElementById("obk_periodClosureFinishDate").value = '';
				document.getElementById("obk_periodClosureFinishTime").value = '00:00';
			}
			
		});
	}
}

function obk_delete_period_closure(index,obk_idSpace,start,finish){
	if (confirm(WPJS.obk_TConfirmDeleteItem)){
		document.getElementById("obk_periodclosure"+index).style.display = "none";	
		var wpnonce = document.getElementById("obk_deletePeriodClosure").value;
		var data = {
			'action': 'js_deletePeriodClosure',
			'obk_idSpace': obk_idSpace,
			'start': start,
			'finish': finish,
			'_wpnonce': wpnonce
		};
		jQuery.post(ajaxurl, data, function(response) {});
	}
}
