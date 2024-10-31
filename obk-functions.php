<?php
defined( 'ABSPATH' ) or die();
$obk_parameters = array();
$obk_adminNotice = array();
$activating = false;

//----CONSTANTS
function obk_getTutorialsLinks(){
	return '<h3>Tutorials</h3><br>
		<a target="_blank" href="https://oui-booking.com/demo/"><img src="'.plugins_url( 'img/0.png', __FILE__ ).'"></a><br><br>
		<a target="_blank" href="https://oui-booking.com/create-your-first-booking-space/"><img src="'.plugins_url( 'img/1.png', __FILE__ ).'"></a><br><br>
		<a target="_blank" href="https://oui-booking.com/add-price-changes/"><img src="'.plugins_url( 'img/2.png', __FILE__ ).'"></a><br><br>
		<a target="_blank" href="https://oui-booking.com/customize-the-booking-form/"><img src="'.plugins_url( 'img/3.png', __FILE__ ).'"></a><br><br>
		<a target="_blank" href="https://oui-booking.com/add-a-new-booking/"><img src="'.plugins_url( 'img/4.png', __FILE__ ).'"></a><br><br>';
}

function obk_getBookingStatus($status){
	$obk_bookingStatus =  array( 'validationinprogress' => __('Validation in progress', 'oui-booking'),
							'pendingpayment' => __('Pending payment', 'oui-booking'),
							'confirmed' => __('Confirmed', 'oui-booking'),
							'paid' => __('Paid', 'oui-booking'),
							'canceled' => __('Canceled', 'oui-booking'));
	return $obk_bookingStatus[$status];
}

function obk_getAllCurrencies($selected=""){
	return '<option value=""></option>
			<option value="AUD" '.(($selected == 'AUD')? 'selected':'').'>AUD - Australian Dollar</option>
			<option value="BRL" '.(($selected == 'BRL')? 'selected':'').'>BRL - Brazilian Real</option>
			<option value="CAD" '.(($selected == 'CAD')? 'selected':'').'>CAD - Canadian Dollar</option>
			<option value="CZK" '.(($selected == 'CZK')? 'selected':'').'>CZK - Czech Koruna</option>
			<option value="DKK" '.(($selected == 'DKK')? 'selected':'').'>DKK - Danish Krone</option>
			<option value="EUR" '.(($selected == 'EUR')? 'selected':'').'>EUR - Euro</option>
			<option value="HKD" '.(($selected == 'HKD')? 'selected':'').'>HKD - Hong Kong Dollar</option>
			<option value="HUF" '.(($selected == 'HUF')? 'selected':'').'>HUF - Hungarian Forint</option>
			<option value="ILS" '.(($selected == 'ILS')? 'selected':'').'>ILS - Israeli New Sheqel</option>
			<option value="JPY" '.(($selected == 'JPY')? 'selected':'').'>JPY - Japanese Yen</option>
			<option value="MYR" '.(($selected == 'MYR')? 'selected':'').'>MYR - Malaysian Ringgit</option>
			<option value="MXN" '.(($selected == 'MXN')? 'selected':'').'>MXN - Mexican Peso</option>
			<option value="NOK" '.(($selected == 'NOK')? 'selected':'').'>NOK - Norwegian Krone</option>
			<option value="NZD" '.(($selected == 'NZD')? 'selected':'').'>NZD - New Zealand Dollar</option>
			<option value="PHP" '.(($selected == 'PHP')? 'selected':'').'>PHP - Philippine Peso</option>
			<option value="PLN" '.(($selected == 'PLN')? 'selected':'').'>PLN - Polish Zloty</option>
			<option value="GBP" '.(($selected == 'GBP')? 'selected':'').'>GBP - Pound Sterling</option>
			<option value="RUB" '.(($selected == 'RUB')? 'selected':'').'>RUB - Russian Ruble</option>
			<option value="SGD" '.(($selected == 'SGD')? 'selected':'').'>SGD - Singapore Dollar</option>
			<option value="SEK" '.(($selected == 'SEK')? 'selected':'').'>SEK - Swedish Krona</option>
			<option value="CHF" '.(($selected == 'CHF')? 'selected':'').'>CHF - Swiss Franc</option>
			<option value="TWD" '.(($selected == 'TWD')? 'selected':'').'>TWD - Taiwan New Dollar</option>
			<option value="THB" '.(($selected == 'THB')? 'selected':'').'>THB - Thai Baht</option>
			<option value="USD" '.(($selected == 'USD')? 'selected':'').'>USD - U.S. Dollar</option>';
}

function obk_getMonthsOption($year,$month){
	$labelMonths = array(__('January', 'oui-booking'),__('February', 'oui-booking'),__('March', 'oui-booking'),	__('April', 'oui-booking'),	__('May', 'oui-booking'),
		__('June', 'oui-booking'), __('July', 'oui-booking'), __('August', 'oui-booking'), __('September', 'oui-booking'), __('October', 'oui-booking'),
		__('November', 'oui-booking'), __('December', 'oui-booking'));
	$displayed = 0;
	$months = '';
	while($displayed < 12){
		$months .= '<option value="'.$year.'-'.sprintf("%02d",$month).'">'.$labelMonths[$month-1].' '.$year.'</option>';
		$month++;
		if ($month > 12){$month = 1;$year++;}
		$displayed++;
	}
	return $months;
}

function obk_getDefaultOpeningTimes(){
	return [
		[
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		],
		[
			[1,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]],
			[0,["08:00","12:00",false,false],["14:00","18:00",false,false]]
		]
	];

}

//----PRICE
function obk_displayPrice($price, $currency, $idSpan, $property="innerHTML"){
	if ($currency == ''){return '';}
	$script = '<script>var x = '.$price.';';
	$script .= 'x = x.toLocaleString("none",{ style: "currency", currency: "'.$currency.'"});';
	$script .= 'document.getElementById("'.$idSpan.'").'.$property.' = x;';
	$script .= '</script>';
	return $script;
}

function obk_getSpaceTotalPrice($date_arrivee,$date_depart,$periodesprices,$prix_de_la_place,$nb_de_place,$timeUnit,$dayprice){
	$total = 0;
	$tabPeriodes = explode('--o--',$periodesprices);
	$periodespricesnew = '';
	$tabDayprice = explode('--o--',$dayprice);
	$dateParcours = substr($date_arrivee,0,16);
	$dateParcoursFinish = date('Y-m-d H:i', strtotime($dateParcours . ' +1 day'));
	$indexLastPeriod = -1;
	while($dateParcours <= $date_depart){
		if ($indexLastPeriod > -1){
			$pplp = explode(';', $tabPeriodes[$indexLastPeriod]);
			if ($dateParcours > $pplp[1]){
				$periodespricesnew .= $pplp[0] . ';' . $pplp[1] . ';' . $pplp[2] . '--o--';
				$indexLastPeriod = -1;
			}
		}
		
		$start = '';
		for($i = 0; $i < strlen($tabDayprice[0]); $i++){
			$numDay = substr($tabDayprice[0],$i,1);
			if (date("w", strtotime($dateParcours)) == $numDay){
				$start = $dateParcours;
				$finish = date('Y-m-d H:i', strtotime($start . ' +1 day'));
				$tabPrices = explode(';',$tabDayprice[1]);
				$ignorePeriod = false;
				for($j = 0; $j < strlen($tabDayprice[2]); $j++){
					if (substr($tabDayprice[2],$j,1) == substr($tabDayprice[0],$i,1)){
						$ignorePeriod = true;
					}
				}
				break;
			}
		}		
		$indexPeriode = -1;
		foreach($tabPeriodes as $index => $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($dateParcours <= $pp[0]) && ($dateParcoursFinish > $pp[0])){
					$indexPeriode = $index;
					$indexLastPeriod = $index;
					break;
				}
				if (($dateParcours >= $pp[0]) && ($dateParcours < $pp[1])){
					$indexPeriode = $index;
					$indexLastPeriod = $index;
					break;
				}
			}
		}
		if ($start != ''){
			if ($indexPeriode == -1){
				if ($indexLastPeriod > -1){
					$pplp = explode(';', $tabPeriodes[$indexLastPeriod]);
					$periodespricesnew .= $pplp[0] . ';' . $pplp[1] . ';' . $pplp[2] . '--o--';
					$indexLastPeriod = -1;
				}
				$periodespricesnew .= $start . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
			}else{
				$pp = explode(';',$tabPeriodes[$indexPeriode]);
				if ($ignorePeriod){
					if (($start < $pp[0]) && ($finish >= $pp[0])){
						$periodespricesnew .= $start . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						if ($finish < $pp[1]){
							$tabPeriodes[$indexPeriode] = $finish . ';' . $pp[1] . ';' . $pp[2];
						}else{
							$indexLastPeriod = -1;
						}
					}
					if (($start >= $pp[0]) && ($start < $pp[1])){
						$periodespricesnew .= $pp[0] . ';' . $start . ';' . $pp[2] . '--o--';
						$periodespricesnew .= $start . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						if ($finish < $pp[1]){
							$tabPeriodes[$indexPeriode] = $finish . ';' . $pp[1] . ';' . $pp[2];
						}else{
							$indexLastPeriod = -1;
						}
						
					}
				}else{
					if (($start < $pp[0]) && ($finish >= $pp[0])){
						$periodespricesnew .= $start . ';' . $pp[0] . ';' . $tabPrices[$numDay] . '--o--';
						if ($finish > $pp[1]){
							$periodespricesnew .= $pp[0] . ';' . $pp[1] . ';' . $pp[2] . '--o--';
							$indexLastPeriod = -1;
							$periodespricesnew .= $pp[1] . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						}else{
							$tabPeriodes[$indexPeriode] = $finish . ';' . $pp[1] . ';' . $pp[2];
						}
					}
					if (($start >= $pp[0]) && ($start < $pp[1])){
						if ($finish > $pp[1]){
							$periodespricesnew .= $pp[1] . ';' . $finish . ';' . $tabPrices[$numDay] . '--o--';
						}
					}
				}
			}
		}
		$dateParcours = date('Y-m-d H:i', strtotime($dateParcours . ' +1 day'));
		$dateParcoursFinish = date('Y-m-d H:i', strtotime($dateParcours . ' +1 day'));
	}
	if ($indexLastPeriod > -1){
		$pplp = explode(';', $tabPeriodes[$indexLastPeriod]);
		$periodespricesnew .= $pplp[0] . ';' . $pplp[1] . ';' . $pplp[2] . '--o--';
	}
	$tabPeriodes = explode('--o--',$periodespricesnew);
	if ($timeUnit == 0){
		foreach($tabPeriodes as $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($date_arrivee >= $pp[0]) && ($date_arrivee <= $pp[1])){
					return $pp[2];
				}
			}
		}
		return $prix_de_la_place;
	}else{
		$cursorDeb = $date_arrivee;
		$cursorFin = $date_depart;
		foreach($tabPeriodes as $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($cursorDeb <= $pp[0]) && ($cursorFin >= $pp[0])){
					$total += obk_getTotalPeriod($cursorDeb,$pp[0],$prix_de_la_place,$timeUnit);
					if ($cursorFin <= $pp[1]){
						$total += obk_getTotalPeriod($pp[0],$cursorFin,$pp[2],$timeUnit);
						return $total;
					}else{
						$total += obk_getTotalPeriod($pp[0],$pp[1],$pp[2],$timeUnit);
						$cursorDeb = $pp[1];
					}
				}elseif (($cursorDeb >= $pp[0]) && ($cursorDeb <= $pp[1])){
					if ($cursorFin <= $pp[1]){
						$total += obk_getTotalPeriod($cursorDeb,$cursorFin,$pp[2],$timeUnit);
						return $total;
					}else{
						$total += obk_getTotalPeriod($cursorDeb,$pp[1],$pp[2],$timeUnit);
						$cursorDeb = $pp[1];
					}
				}
			}
		}
		$total += obk_getTotalPeriod($cursorDeb,$cursorFin,$prix_de_la_place,$timeUnit);
		return $total;
	}
}

function obk_getTotalPeriod($depart,$fin, $prix, $unite){
	$dateDeb = new DateTime($depart);
	$dateFin = new DateTime($fin);
	$duree = round(($dateFin->format('U') - $dateDeb->format('U')) / 3600,3);
	$total = $duree * $prix / $unite;
	return $total;
}

//----DATE & TIME

function obk_displayDate($date, $idSpan, $property="innerHTML"){
	$year = substr($date,0,4);
	$month = substr($date,5,2);
	$day = substr($date,8,2);
	if (strlen($date) > 10){
		$hour = substr($date,11,2);
		$minute = substr($date,14,2);
	}else{
		$hour = 0; $minute = 0; $second = 0;
	}
	$script = '<script>var d = new Date('.$year.', '.($month-1).', '.$day.', '.$hour.', '.$minute.', 0);';
	$script .= 'd = d.toLocaleString();';
	$script .= 'd = d.substr(0,d.length-3);';
	$script .= 'document.getElementById("'.$idSpan.'").'.$property.' = d;';
	$script .= '</script>';
	return $script;
}

function obk_formListHours($selected, $start=0, $end=23){
	$start = (int) substr($start,0,2);
	$end = (int) substr($end,0,2);
	if ($end >= 24){return '';}
	$list = '';
	$i = $start;
	do{
		$j = sprintf("%02d",$i);
		$selectedOption = ($j == $selected)? 'selected' : '';
		$list .= '<option value="'.$j.'" '.$selectedOption.'>'.$j.'</option>';
		$i++;
		if ($i == 24){
			$i = 0;
		}
	}while($i != $end);
	$j = sprintf("%02d",$i);
	$selectedOption = ($j == $selected)? 'selected' : '';
	$list .= '<option value="'.$j.'" '.$selectedOption.'>'.$j.'</option>';
	return $list;
}

function obk_formListMinutes($selected, $step){
	$list = '';
	for($i = 0; $i < 60; $i+=$step){
		$j = sprintf("%02d",$i);
		$selectedOption = ($j == $selected)? 'selected' : '';
		$list .= '<option value="'.$j.'" '.$selectedOption.'>'.$j.'</option>';
	}
	return $list;
}

function obk_formListDuration($selected, $min, $max, $step){
	$list = '';
	for($i = $min; $i <= $max; $i+=$step){
		$j = obk_heure2duree($i / 60,false);
		$selectedOption = ($i == $selected)? 'selected' : '';
		$list .= '<option value="'.$i.'" '.$selectedOption.'>'.$j.'</option>';
	}
	return $list;
}

function obk_cal_days_in_month($month,$year){
	return date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
}

function obk_heure2duree($heures, $displayTotalHours=true){
	$val = $heures;
	if (floor($val) <= 1){
		$unit = __('hr', 'oui-booking');
	}else{
		$unit = __('hrs', 'oui-booking');
	}
	$coeff = 1;
	if ($val >= 24){
		$coeff = 24;
		$val = $val / 24;
		if (floor($val) <= 1){
			$unit = __('day', 'oui-booking');
		}else{
			$unit = __('days', 'oui-booking');
		}
		if ($val >= 7){
			$coeff = 168;
			$val = $val / 7;
			if (floor($val) <= 1){
				$unit = __('week', 'oui-booking');
			}else{
				$unit = __('weeks', 'oui-booking');
			}
		}
	}
	$val = floor($val);
	$reste = $heures - ($val * $coeff);
	if ($reste >= 1){
		$reste = ' ' . __('and','oui-booking') . ' ' . obk_heure2duree($reste,false);
	}else if ($reste > 0){
		$reste = sprintf("%02d", round($reste*60)). ' ' . __('min','oui-booking');
	}else{
		$reste = '';
	}
	if (($displayTotalHours)&&($heures >= 24)){
		$reste .=  ' ('.round(($heures+0),2). ' ' . __('hours', 'oui-booking').')';
	}
	if (($val == 0) && ($reste != '')){
		$val = '';
		$unit = '';
		if (substr($reste,0,2) == ', '){
			$reste = substr($reste,2);
		}
	}
	return $val . ' ' . $unit. $reste;
}

function obk_getTimeStamp($date){
	$year = substr($date,0,4);
	$month = substr($date,5,2);
	$day = substr($date,8,2);
	$hour = substr($date,11,2);
	$minute = substr($date,14,2);
	$second = substr($date,17,2);
	return mktime((int)$hour,(int)$minute,(int)$second,(int)$month,(int)$day,(int)$year);
}

function obk_getAutomaticQty($code,$originalQty,$dateParcours,$finParcours,$nb_de_place){
	if ($code == 'oneperhour'){
		$nbHeures = 0;
		while($dateParcours < $finParcours){
			$nbHeures++;
			$dateParcours = date('Y-m-d H:i:s', strtotime($dateParcours . ' +1 hour'));
		}
		if ($nbHeures == 0){$nbHeures = 1;}
		return $nbHeures * $nb_de_place;
	}

	if (($code == 'oneperday') || ($code == 'onepernight')){
		$nbJours = 0;
		while($dateParcours < $finParcours){
			$nbJours++;
			$dateParcours = date('Y-m-d', strtotime($dateParcours . ' +1 day'));
		}
		if ($code == 'onepernight'){$nbJours--;}
		if ($nbJours == 0){$nbJours = 1;}
		return $nbJours * $nb_de_place;
	}
	
	if ($code == 'oneperweek'){
		$nbSemaines = 0;
		while($dateParcours < $finParcours){
			$nbSemaines++;
			$dateParcours = date('Y-m-d', strtotime($dateParcours . ' +1 week'));
		}
		if ($nbSemaines == 0){$nbSemaines = 1;}
		return $nbSemaines * $nb_de_place;
	}
	
	if ($code == 'onepermonth'){
		$nbMois = 0;
		while($dateParcours < $finParcours){
			$nbMois++;
			$dateParcours = date('Y-m-d', strtotime($dateParcours . ' +1 month'));
		}
		if ($nbMois == 0){$nbMois = 1;}
		return $nbMois * $nb_de_place;
	}
	
	
	
	return $originalQty;
}

function obk_checkDateInOT($OT,$datetime,$type,$EC){
	$ts_date_parcours = mktime((int)substr($datetime,11,2),(int)substr($datetime,14,2),0,(int)substr($datetime,5,2),(int)substr($datetime,8,2),(int)substr($datetime,0,4));
	$time = substr($datetime,11,5);
	$numDay = getdate($ts_date_parcours)["wday"];
	
	$arrivalPresent = false;
	for($i = 0; $i < sizeof($OT); $i++){
		if (($OT[$i][1][0] != "0") && (sizeof($OT[$i][1]) > 1)){
			$arrivalPresent = true;
		}
	}
	$departurePresent = false;
	for($i = 0; $i < sizeof($OT); $i++){
		if (($OT[$i][2][0] != "0") && (sizeof($OT[$i][2]) > 1)){
			$departurePresent = true;
		}
	}
	$tabClosure = explode('--o--',$EC);
	foreach($tabClosure as $closure){
		$close = explode(';',$closure);
		if (isset($close[1])){
			$debut = $close[0];
			$fin = $close[1];
			if (($datetime >= $debut) && ($datetime < $fin)){
				return false;
			}
		}
	}
	if ((sizeof($OT[$numDay][$type]) > 1) && ($OT[$numDay][$type][0] != "0")){
		for($i = 1; $i < sizeof($OT[$numDay][$type]); $i++){
			if ($OT[$numDay][$type][$i][1] == '00:00'){
				$OT[$numDay][$type][$i][1] = '24:00';
			}
			if (($time >= $OT[$numDay][$type][$i][0]) && ($time <= $OT[$numDay][$type][$i][1])){
				return true;
			}
		}
	}elseif (($type > 0) && (!$arrivalPresent) && (!$departurePresent)){
		$type = 0;
		if ($OT[$numDay][$type][0] == "0"){return false;}
		if (sizeof($OT[$numDay][$type]) > 1){
			for($i = 1; $i < sizeof($OT[$numDay][$type]); $i++){
				if ($OT[$numDay][$type][$i][1] == '00:00'){
					$OT[$numDay][$type][$i][1] = '24:00';
				}
				if (($time >= $OT[$numDay][$type][$i][0]) && ($time <= $OT[$numDay][$type][$i][1])){
					return true;
				}
			}
		}	
	}
	return false;
}

function obk_getReferenceTimeInOT($OT,$datetime,$type,$dureeMin,$dateDebut,$user_minutes_interval,$exceptionalclosure){
	$datetime_bak = $datetime;
	$datetime = mktime((int)substr($datetime,11,2),(int)substr($datetime,14,2),0,(int)substr($datetime,5,2),(int)substr($datetime,8,2),(int)substr($datetime,0,4));
	$dateDebut = mktime((int)substr($dateDebut,11,2),(int)substr($dateDebut,14,2),0,(int)substr($dateDebut,5,2),(int)substr($dateDebut,8,2),(int)substr($dateDebut,0,4));
	$numDay = getdate($datetime)["wday"];
	$refTime = '00:00';	
	if (($type == "2") && ($user_minutes_interval > 0)){
		while($datetime <= $dateDebut){
			$datetime += $user_minutes_interval * 60;
		}
		if ($dureeMin > 0){
			while(($datetime-$dateDebut) < ($dureeMin * 3600)){
				$datetime += $user_minutes_interval * 60;
			}
		}
		$nbTentative = 10080 / $user_minutes_interval;
		$compteur = 0;
		while(($compteur < $nbTentative) && (!obk_checkDateInOT($OT,date('Y-m-d H:i:s',$datetime),$type,$exceptionalclosure))){
			$datetime += $user_minutes_interval * 60;
			$compteur++;
		}
		if ($compteur < $nbTentative){
			return sprintf("%02d",getdate($datetime)["hours"]) . ":" . sprintf("%02d",getdate($datetime)["minutes"]);
		}
	}
	if ((sizeof($OT[$numDay][$type]) > 1) && ($OT[$numDay][$type][0] != "0")){
		for($i = 1; $i < sizeof($OT[$numDay][$type]); $i++){
			if ($i == 1){
				$refTime = $OT[$numDay][$type][$i][0];
			}
			if ($OT[$numDay][$type][$i][2] == "true"){
				return $OT[$numDay][$type][$i][0];
			}
			if ($OT[$numDay][$type][$i][3] == "true"){
				if ($OT[$numDay][$type][$i][1] == "00:00"){return "24:00";}
				return $OT[$numDay][$type][$i][1];
			}
		}
	}else{
		for($i = 1; $i < sizeof($OT[$numDay][0]); $i++){
			if ($i == 1){
				$refTime = $OT[$numDay][0][$i][0];
			}
			if ($OT[$numDay][0][$i][2] == "true"){
				return $OT[$numDay][0][$i][0];
			}
			if ($OT[$numDay][0][$i][3] == "true"){
				if ($OT[$numDay][0][$i][1] == "00:00"){return "24:00";}
				return $OT[$numDay][0][$i][1];
			}
		}
	}
	if (($type == "2") && ($refTime == "00:00")){$refTime = "24:00";}
	return $refTime;
}


//----TOOLS
function obk_removeslashes($text){
	$text = stripslashes($text);
	$text = htmlspecialchars($text);
	return $text;
}

function obk_isNotEmpty($val){
	if (!isset($val)){return false;}
	if (($val == null) || ($val == 'null')){return false;}
	if ($val == "0"){return false;}
	if ($val == array()){return false;}
	if ($val == ""){return false;}
	return true;
}

function obk_email($to, $subject, $message){
	$from = "no-reply@".$_SERVER['HTTP_HOST'];
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers[] = 'From: "'.(explode('@',$from)[1]).'"<'.$from.'>';
	wp_mail( $to, $subject, $message, $headers );
}

function disable_emojis() {
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
}
add_action( 'init', 'disable_emojis' );

//----ADMIN NOTICES
function obk_addAdminNotice($text,$level,$type=''){
	global $obk_adminNotice;
	$obk_adminNotice[] = array("text" => $text, "level" => $level, "type" => $type);
}

function obk_displayAdminNotice($level=-1){
	global $obk_adminNotice;
	$aff = '';
	foreach($obk_adminNotice as $notice){
		$class = "obk_adminNotice";
		$img = '<img src="'.plugins_url( 'img/check.png', __FILE__ ).'"> ';
		if ($notice["type"] == "error"){
			$class = "obk_adminNoticeError";
			$img = '';
		}
		if ($notice["type"] == "warning"){
			$class = "obk_adminNoticeWarning";
			$img = '';
		}
		if (($level == -1)||($level == $notice["level"])){
			$aff .= '<div class="'.$class.'">' . $img . $notice["text"] . '<img class="obk_btnClose" onclick="obk_closeAdminNotice(this)" src="'.plugins_url( 'img/close.png', __FILE__ ).'"></div>';
			unset($notice);
		}
	}
	return $aff;
}

//----LOAD PARAMETERS
function obk_getAllParameters(){
	global $wpdb;
	global $activating;
	$options = array();
	$result = $wpdb->get_results("SHOW TABLES LIKE '{$wpdb->prefix}obk_parameters'");
	if (sizeof($result) > 0){
		$results = $wpdb->get_results("SELECT nom, val FROM {$wpdb->prefix}obk_parameters");
		foreach($results as $result){
			$options[$result->nom] = $result->val;
		}
	}else{
		$activating = true;
	}
	return $options;
}

function obk_getParameter($name){
	global $obk_parameters;
	if (!obk_isNotEmpty($obk_parameters)){
		$obk_parameters = obk_getAllParameters();
	}
	if (isset($obk_parameters[$name])){
		return obk_removeslashes($obk_parameters[$name]);
	}else{
		return '';
	}
}

function obk_getCSS($foremail=false){
	global $wpdb;
	$result = '<style>';
	if ($foremail){
		$result .= '.obk_previewBox{max-width: 600px;}
					.obk_previewRow{display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;}
					.obk_previewHead{padding: 5px; font-weight: bold;}
					.obk_previewContent{padding: 0 0 20px 30px;}
					form, input[type="text"],input[type="submit"]{display:none;}
					input[disabled]{background-color: transparent!important;border: none!important;width: 100%!important;}
					';
	}
	$rowcss = $wpdb->get_results("SELECT val FROM {$wpdb->prefix}obk_parameters WHERE nom='customcss'");
	if ((isset($rowcss[0]->val)) && (obk_isNotEmpty($rowcss[0]->val))){
		$result .= $rowcss[0]->val;
	}
	$result .= '</style>';
	return $result;
}

include 'obk-functions-security.php';
include 'obk-functions-language.php';
include 'obk-functions-booking.php';

add_action( 'init', 'obk_checkCSVExport' );

function obk_checkCSVExport(){
	global $wpdb;
	global $obk_linkCSV;
	if ((isset($_POST['obk_act'])) && ($_POST['obk_act'] == 'exportCSV')){
		check_admin_referer($_POST['obk_act']);
		function obk_fieldCSV($field,$type){
			if ($type == "s"){
				$field = obk_removeslashes(str_replace(';',',',$field));
			}
			if ($type == "d"){
				$field = str_replace('.',obk_getParameter('decimalseparator'),$field);
			}
			if ($type == "tab"){
				$field = obk_removeslashes(str_replace(';','++o++',$field));
			}
			return $field;
		}
		$exportArrivalDate = '';
		$exportDepartureDate = '';
		$exportBookingStatus = '';
		$tableschoice = '';
		if (isset($_POST['obk_ead'])){
			$exportArrivalDate = obk_secureData($_POST['obk_ead'],'date');
			if ($exportArrivalDate[0]){
				$exportArrivalDate = $exportArrivalDate[2];
			}
		}
		if (isset($_POST['obk_edd'])){
			$exportDepartureDate = obk_secureData($_POST['obk_edd'],'date');
			if ($exportDepartureDate[0]){
				$exportDepartureDate = $exportDepartureDate[2];
			}
		}
		if (isset($_POST['obk_ebs'])){
			$exportBookingStatus = obk_secureData($_POST['obk_ebs'],'text');
			if ($exportBookingStatus[0]){
				$exportBookingStatus = $exportBookingStatus[2];
			}
		}
		if (isset($_POST['tableschoice'])){
			$tableschoice = obk_secureData($_POST['tableschoice'],'text');
			if ($tableschoice[0]){
				$tableschoice = $tableschoice[2];
			}
		}
		$sqlVar = ['none'];
		$sqlWhere = '';
		if ($exportDepartureDate != ''){
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}else{$sqlWhere = 'WHERE ';}
			$sqlWhere .= 'date_depart >= %s';
			$sqlVar[] = $exportDepartureDate.' 00:00:00';
		}
		if ($exportArrivalDate != ''){
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}else{$sqlWhere = 'WHERE ';}
			$sqlWhere .= 'date_arrivee <= %s';
			$sqlVar[] = $exportArrivalDate.' 23:59:59';
		}
		if ($exportBookingStatus != ''){
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}else{$sqlWhere = 'WHERE ';}
			$sqlWhere .= 'statut = %s';
			$sqlVar[] = $exportBookingStatus;
		}
		$resultats = $wpdb->get_results($wpdb->prepare("SELECT %s, res.*, GROUP_CONCAT(CONCAT(mpb.id,',',mpb.label,',',mpb.description,',',mpb.quantite,',',mpb.montant,',',mpb.pourcentage,',',mpb.periode_heure,',',mpb.code,',',mpb.type,',',mpb.details_texte) SEPARATOR '--o--') AS tabmpb FROM {$wpdb->prefix}obk_bookings res LEFT JOIN {$wpdb->prefix}obk_modifyprice_bookings mpb ON mpb.id_reservation = res.id $sqlWhere GROUP BY res.id ORDER BY date DESC, obk_idSpace DESC",$sqlVar));		
		$domain = $_SERVER['SERVER_NAME'];
		$filename = 'ouibooking-' . $domain . '-bookings.csv';
		if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
			fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
			fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
			$content_CSV = array('bookings',
				__('Id','oui-booking'),
				__('Internal reference','oui-booking'),
				__('Date','oui-booking'),
				__('Number of places','oui-booking'),
				__('Unit price','oui-booking'),
				__('Currency','oui-booking'),
				__('Deposit requested (amount)','oui-booking'),
				__('Deposit requested (percentage)','oui-booking'),
				__('Arrival date','oui-booking'),
				__('Departure date','oui-booking'),
				__('Number of people','oui-booking'),
				__('Last name','oui-booking'),
				__('First name','oui-booking'),
				__('Address','oui-booking'),
				__('Zip code','oui-booking'),
				__('City','oui-booking'),
				__('Country','oui-booking'),
				__('Email','oui-booking'),
				__('Tel.','oui-booking'),
				__('Status','oui-booking'),
				__('Id place','oui-booking'),
				__('Location','oui-booking'),
				__('Place','oui-booking'),
				__('Comments','oui-booking'),
				__('Price changes','oui-booking'),
				__('Price according to periods','oui-booking'),
				__('Price according to days','oui-booking'),
				__('Time unit (in hours)','oui-booking')
				);
			fputcsv( $fh, $content_CSV, ';');
			foreach($resultats as $r){				
				$content_CSV = array('',obk_fieldCSV($r->id,'d'), obk_fieldCSV($r->reference_interne,'s'), obk_fieldCSV($r->date,'s'), obk_fieldCSV($r->nb_de_place,'d'), obk_fieldCSV($r->prix_de_la_place,'d'), obk_fieldCSV($r->devise,'s'), obk_fieldCSV($r->acompte_prix,'d'), obk_fieldCSV($r->acompte_pourcentage,'d'), obk_fieldCSV($r->date_arrivee,'s'), obk_fieldCSV($r->date_depart,'s'), obk_fieldCSV($r->nb_de_personnes,'d'), obk_fieldCSV($r->nom,'s'), obk_fieldCSV($r->prenom,'s'), obk_fieldCSV($r->adresse,'s'), obk_fieldCSV($r->code_postal,'s'), obk_fieldCSV($r->ville,'s'), obk_fieldCSV($r->pays,'s'), obk_fieldCSV($r->email,'s'), obk_fieldCSV($r->telephone,'s'), obk_fieldCSV($r->statut,'s'), obk_fieldCSV($r->obk_idSpace,'d'), obk_fieldCSV($r->lieu,'s'), obk_fieldCSV($r->emplacement,'s'), obk_fieldCSV($r->remarques,'s'), $r->tabmpb, obk_fieldCSV($r->periodesprices,'tab'), obk_fieldCSV($r->dayprice,'tab'), obk_fieldCSV($r->timeUnit,'d'));
				fputcsv( $fh, $content_CSV, ';');
			}
			fclose( $fh );
		}
		$obk_linkCSV = $filename;		
		if ($tableschoice == 'all'){
			$obk_linkCSV = "";			
			//----------------------PLACES----------------------------//
			$resultats = $wpdb->get_results("SELECT emp.*, loc.id as id_location, loc.nom, GROUP_CONCAT(mps.id_modificationprix) AS PriceChangesListIds FROM {$wpdb->prefix}obk_spaces emp INNER JOIN {$wpdb->prefix}obk_locations loc ON loc.id=emp.id_lieu LEFT JOIN {$wpdb->prefix}obk_modifyprice_spaces mps ON mps.obk_idSpace = emp.id GROUP BY emp.id");
			$filename = 'ouibooking-' . $domain . '-places.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('places',
					__('Id','oui-booking'),
					__('Id location','oui-booking'),
					__('Location name','oui-booking'),
					__('Place label','oui-booking'),
					__('Number of places','oui-booking'),
					__('Unit price of the place','oui-booking'),
					__('Currency','oui-booking'),
					__('Time unit (in hours)','oui-booking'),
					__('Min. booking duration (in hours)','oui-booking'),
					__('Max. booking duration (in hours)','oui-booking'),
					__('Deposit requested (amount)','oui-booking'),
					__('Deposit requested (percentage)','oui-booking'),
					__('Link to the general Terms of Use','oui-booking'),
					__('Start date of availability','oui-booking'),
					__('End date of availability','oui-booking'),
					__('Description / Details','oui-booking'),
					__('Default status of a new booking','oui-booking'),
					__('Receive email','oui-booking'),
					__('Email notification','oui-booking'),
					__('Payment instructions','oui-booking'),
					__('Interval in minutes for the user','oui-booking'),
					__('Prices according to days','oui-booking'),
					__('Prices according to periods','oui-booking'),
					__('Form: Booking start time','oui-booking'),
					__('Form: Booking end time','oui-booking'),
					__('Form: Number of people','oui-booking'),
					__('Form: Last name','oui-booking'),
					__('Form: First name','oui-booking'),
					__('Form: Address','oui-booking'),
					__('Form: Zip code','oui-booking'),
					__('Form: City','oui-booking'),
					__('Form: Country','oui-booking'),
					__('Form: Email','oui-booking'),
					__('Form: Tel.','oui-booking'),
					__('Form: Notes','oui-booking'),
					__('Form: Number of places','oui-booking'),
					__('Display: Start date of availability','oui-booking'),
					__('Display: End date of availability','oui-booking'),
					__('Display: Price of the place','oui-booking'),
					__('Display: Deposit (Price)','oui-booking'),
					__('Display: Deposit (Percentage)','oui-booking'),
					__('Display: Time unit','oui-booking'),
					__('Display: Min. booking duration','oui-booking'),
					__('Display: Max. booking duration','oui-booking'),
					__('Display: Description / Details','oui-booking'),
					__('Display: Calendar','oui-booking'),
					__('Opening Times','oui-booking'),
					__('Exceptional closure','oui-booking'),
					__('Price changes Ids','oui-booking')
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						obk_fieldCSV($r->id,'d'), 
						obk_fieldCSV($r->id_location,'d'), 
						obk_fieldCSV($r->nom,'s'), 
						obk_fieldCSV($r->label,'s'), 
						obk_fieldCSV($r->nb_de_place,'d'), 
						obk_fieldCSV($r->prix_de_la_place,'d'), 
						obk_fieldCSV($r->devise,'s'), 
						obk_fieldCSV($r->timeUnit,'d'), 
						obk_fieldCSV($r->minBookingDuration,'d'), 
						obk_fieldCSV($r->tps_reservation_max_heure,'d'), 
						obk_fieldCSV($r->acompte_prix,'d'),
						obk_fieldCSV($r->acompte_pourcentage,'d'),
						obk_fieldCSV($r->lien_CGU,'s'),
						obk_fieldCSV($r->date_debut_reservation,'s'),
						obk_fieldCSV($r->date_fin_reservation,'s'),
						obk_fieldCSV($r->description,'s'),
						obk_fieldCSV($r->statut_par_defaut_reservation,'s'),
						obk_fieldCSV($r->notification_email,'s'),
						obk_fieldCSV($r->email_notification,'s'),
						obk_fieldCSV($r->payment_instructions,'s'),
						obk_fieldCSV($r->user_minutes_interval,'d'),
						obk_fieldCSV($r->dayprice,'tab'),
						obk_fieldCSV($r->periodesprices,'tab'),
						obk_fieldCSV($r->form_heure_debut,'d'),
						obk_fieldCSV($r->form_heure_fin,'d'),
						obk_fieldCSV($r->form_personnes,'d'),
						obk_fieldCSV($r->form_nom,'d'),
						obk_fieldCSV($r->form_prenom,'d'),
						obk_fieldCSV($r->form_adresse,'d'),
						obk_fieldCSV($r->form_code_postal,'d'),
						obk_fieldCSV($r->form_ville,'d'),
						obk_fieldCSV($r->form_pays,'d'),
						obk_fieldCSV($r->form_email,'d'),
						obk_fieldCSV($r->form_telephone,'d'),
						obk_fieldCSV($r->form_remarques,'d'),
						obk_fieldCSV($r->form_nb_de_place,'d'),
						obk_fieldCSV($r->info_date_debut_reservation,'d'),
						obk_fieldCSV($r->info_date_fin_reservation,'d'),
						obk_fieldCSV($r->info_prix_de_la_place,'d'),
						obk_fieldCSV($r->info_acompte_prix,'d'),
						obk_fieldCSV($r->info_acompte_pourcentage,'d'),
						obk_fieldCSV($r->info_timeUnit,'d'),
						obk_fieldCSV($r->info_minBookingDuration,'d'),
						obk_fieldCSV($r->info_tps_reservation_max_heure,'d'),
						obk_fieldCSV($r->info_description,'d'),
						obk_fieldCSV($r->info_calendrier,'d'),
						obk_fieldCSV($r->openingtimes,'s'),
						obk_fieldCSV($r->exceptionalclosure,'tab'),
						obk_fieldCSV($r->PriceChangesListIds,'s')
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}
			
			//----------------------SHORTCODES----------------------------//
			$resultats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}obk_shortcodes");
			$filename = 'ouibooking-' . $domain . '-shortcodes.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('shortcodes',
					__('Id','oui-booking'),
					__('Name','oui-booking'),
					__('Display','oui-booking'),
					__('Places','oui-booking'),
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						obk_fieldCSV($r->id,'d'), 
						obk_fieldCSV($r->nom,'s'), 
						obk_fieldCSV($r->affichage,'s'), 
						obk_fieldCSV(str_replace('"',"'",$r->tabEmplacements),'s')
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}
			
			//----------------------PRICE CHANGES----------------------------//
			$resultats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}obk_modifyprice");
			$filename = 'ouibooking-' . $domain . '-pricechanges.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('pricechanges',
					__('Id','oui-booking'),
					__('Label','oui-booking'),
					__('Description','oui-booking'),
					__('Start date','oui-booking'),
					__('End date','oui-booking'),
					__('Quantity / Max. quantity','oui-booking'),
					__('Initial quantity','oui-booking'),
					__('Amount','oui-booking'),
					__('Percentage','oui-booking'),
					__('Periodicity','oui-booking'),
					__('Code / Automatic Quantity','oui-booking'),
					__('Type','oui-booking'),
					__('Options details','oui-booking'),
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						obk_fieldCSV($r->id,'d'),
						obk_fieldCSV($r->label,'s'),
						obk_fieldCSV($r->description,'s'),
						obk_fieldCSV($r->date_debut,'s'),
						obk_fieldCSV($r->date_fin,'s'),
						obk_fieldCSV($r->quantite,'d'),
						obk_fieldCSV($r->quantite_initiale,'d'),
						obk_fieldCSV($r->montant,'d'),
						obk_fieldCSV($r->pourcentage,'d'),
						obk_fieldCSV($r->periode_heure,'d'),
						obk_fieldCSV($r->code,'s'),
						obk_fieldCSV($r->type,'s'),
						obk_fieldCSV($r->details_texte,'s'),
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}	
			//----------------------PARAMETERS----------------------------//
			$resultats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}obk_parameters");
			$filename = 'ouibooking-' . $domain . '-parameters.csv';
			if ($fh = fopen( plugin_dir_path(__FILE__) . 'exports/' . $filename, 'w+' )){
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
				$content_CSV = array('parameters',
					__('Id','oui-booking'),
					__('Name','oui-booking'),
					__('Value','oui-booking'),
					);
				fputcsv( $fh, $content_CSV, ';');
				foreach($resultats as $r){
					$content_CSV = array('',
						obk_fieldCSV($r->id,'d'),
						obk_fieldCSV($r->nom,'s'),
						obk_fieldCSV($r->val,'s'),
					);
					fputcsv( $fh, $content_CSV, ';');
				}
				fclose( $fh );
			}
		}
	}
}