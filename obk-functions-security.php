<?php
defined( 'ABSPATH' ) or die();

//----SECURITY
function obk_secureData($data, $type, $destination='output'){
	// Sanitize
	if (($type == 'text')||($type == 'hour')||($type == 'date')||($type == 'currency')||($type == 'noalpha')||($type == 'color')){
		$data = sanitize_text_field($data);
	}else if (($type == 'int')||($type == 'id')){
		$data = (int) $data;
	}else if ($type == 'float'){
		$data = (float) $data;
	}else if ($type == 'email'){
		$data = sanitize_email($data);
	}else if ($type == 'url'){
		$data = esc_url_raw($data);
	}else if ($type == 'array'){
		$data = (array) $data;
	}else if ($type == 'form'){
		$data = $data;
	}else{
		return [false,'S:Unknown type:' . $type, ''];
	}
	//Escape	
	if ($destination == 'output'){
		if (($type == "text")||($type == "email")||($type == "hour")||($type == "date")||($type == 'currency')||($type == 'noalpha')||($type == 'color')){
			$data = esc_html($data);
		}else if (($type == 'int')||($type == 'float')||($type == 'id')){
			//Nothing to do
		}else if ($type == 'url'){
			$data = esc_url($data);
		}else if (($type == 'array')||($type == 'form')){
			//Nothing to do
		}else{
			return [false,'E:Unknown type:' . $type, ''];
		}
		//JS
		//HTML ATTRIBUTE
	}else{
		return [false,'E:Unknown destination:' . $destination, ''];
	}
	//Validate
	if (!isset($data)){
		return [false,'V:Unknown data:' . $data, ''];
	}
	if (($data != 0)&&(empty($data))){
		return [false,'V:Empty data:' . $data.'-'.$type, ''];
	}
	$length = (!is_array($data))? strlen($data) : sizeof($data);
	if ($type == 'hour'){
		if (($length != 0)&&($length != 5)&&($length != 8)){
			return [false,'V:Wrong length data for hours type:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'text'){
		return [true,'',$data];
	}else if ($type == 'int'){
		if ($data < -1){
			return [false,'V:Wrong data value for int type:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'id'){
		if ($data < -1){
			return [false,'V:Wrong data value for id type:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'float'){
		if ($data < 0){
			return [false,'V:Wrong data value for float type:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'currency'){
		if ($length > 3){
			return [false,'V:Wrong length data for currency type:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'date'){
		if ($length > 10){
			return [false,'V:Wrong length data for date type:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'email'){
		if (($data != '') && (!is_email($data))){
			return [false,'V:Data is not a correct Email:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'noalpha'){
		if (preg_match('/^([a-z])+$/i',$data)){
			return [false,'V:Data should not countain alpha character:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'url'){
		if (($data != '') && (!preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i',$data))){
			return [false,'V:Data is not a correct URL:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'array'){
		if (!is_array($data)){
			return [false,'V:Data is not a correct array:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'form'){
		if (($data != '') && ((substr($data,0,5) != '<form')||(substr($data,-7) != '</form>'))) {
			return [false,'V:Data is not a correct form:' . $data, ''];
		}
		return [true,'',$data];
	}else if ($type == 'color'){
		if (($data != '') && ((strlen($data) > 7)||(substr($data,0,1) != '#'))) {
			return [false,'V:Data is not a correct color:' . $data, ''];
		}
		return [true,'',$data];
	}else{
		return [false,'V:Unknown type:' . $type, ''];
	}
	return [false,'You should not be here','----------You should not be here------------'];
}

function obk_secureDataForm(){
	global $SAFE_DATA;
	$_SECPOST = $SAFE_DATA; 
	if (isset($_POST)){
		foreach($_POST as $key => $value){
			$type = '';
			switch($key){
				case 'obk_act': $type = 'text'; break;
				case 'action': $type = 'text'; break;
				case '_wpnonce': $type = 'text'; break;
				case 'obk_getAvailableOptions': $type = 'text'; break;
				case '_wp_http_referer': $type = 'text'; break;
				case 'obk_prevAct': $type = 'text'; break;
				case 'id_lieu': $type = 'id'; break;
				case 'id_reservation': $type = 'id'; break;
				case 'obk_idSpace': $type = 'id'; break;
				case 'label': $type = 'text'; break;
				case 'timeUnit': $type = 'float'; break;
				case 'minBookingDuration': $type = 'float'; break;
				case 'tps_reservation_max_heure': $type = 'float'; break;
				case 'lien_CGU': $type = 'url'; break;
				case 'description': $type = 'text'; break;
				case 'details_texte': $type = 'text'; break;
				case 'payment_instructions': $type = 'text'; break;
				case 'date_debut_reservation': $type = 'date'; break;
				case 'date_fin_reservation': $type = 'date'; break;
				case 'user_minutes_interval': $type = 'int'; break;
				case 'statut_par_defaut_reservation': $type = 'text'; break;
				case 'notification_email': $type = 'int'; break;
				case 'email_notification': $type = 'email'; break;
				case 'nouveau_lieu': $type = 'text'; break;
				case 'nouveau_emplacement': $type = 'text'; break;
				case 'filterArrivalDate': $type = 'date'; break;
				case 'filterDepartureDate': $type = 'date'; break;
				case 'filterBookingStatus': $type = 'text'; break;
				case 'info_date_debut_reservation': $type = 'int'; break;
				case 'info_date_fin_reservation': $type = 'int'; break;
				case 'info_prix_de_la_place': $type = 'int'; break;
				case 'info_acompte_prix': $type = 'int'; break;
				case 'info_acompte_pourcentage': $type = 'int'; break;
				case 'info_timeUnit': $type = 'int'; break;
				case 'info_minBookingDuration': $type = 'int'; break;
				case 'info_tps_reservation_max_heure': $type = 'int'; break;
				case 'info_description': $type = 'int'; break;
				case 'info_calendrier': $type = 'int'; break;
				case 'info_date_debut_reservation_obligatoire': $type = 'int'; break;
				case 'info_date_fin_reservation_obligatoire': $type = 'int'; break;
				case 'info_prix_de_la_place_obligatoire': $type = 'int'; break;
				case 'info_acompte_prix_obligatoire': $type = 'int'; break;
				case 'info_acompte_pourcentage_obligatoire': $type = 'int'; break;
				case 'info_timeUnit_obligatoire': $type = 'int'; break;
				case 'info_minBookingDuration_obligatoire': $type = 'int'; break;
				case 'info_tps_reservation_max_heure_obligatoire': $type = 'int'; break;
				case 'info_description_obligatoire': $type = 'int'; break;
				case 'info_calendrier_obligatoire': $type = 'int'; break;
				case 'show_form_date_debut': $type = 'int'; break;
				case 'show_form_heure_debut': $type = 'int'; break;
				case 'show_form_date_fin': $type = 'int'; break;
				case 'show_form_heure_fin': $type = 'int'; break;
				case 'show_form_personnes': $type = 'int'; break;
				case 'show_form_nom': $type = 'int'; break;
				case 'show_form_prenom': $type = 'int'; break;
				case 'show_form_adresse': $type = 'int'; break;
				case 'show_form_code_postal': $type = 'int'; break;
				case 'show_form_ville': $type = 'int'; break;
				case 'show_form_pays': $type = 'int'; break;
				case 'show_form_email': $type = 'int'; break;
				case 'show_form_telephone': $type = 'int'; break;
				case 'show_form_remarques': $type = 'int'; break;
				case 'show_form_nb_de_place': $type = 'int'; break;
				case 'show_form_date_debut_obligatoire': $type = 'int'; break;
				case 'show_form_heure_debut_obligatoire': $type = 'int'; break;
				case 'show_form_date_fin_obligatoire': $type = 'int'; break;
				case 'show_form_heure_fin_obligatoire': $type = 'int'; break;
				case 'show_form_personnes_obligatoire': $type = 'int'; break;
				case 'show_form_nom_obligatoire': $type = 'int'; break;
				case 'show_form_prenom_obligatoire': $type = 'int'; break;
				case 'show_form_adresse_obligatoire': $type = 'int'; break;
				case 'show_form_code_postal_obligatoire': $type = 'int'; break;
				case 'show_form_ville_obligatoire': $type = 'int'; break;
				case 'show_form_pays_obligatoire': $type = 'int'; break;
				case 'show_form_email_obligatoire': $type = 'int'; break;
				case 'show_form_telephone_obligatoire': $type = 'int'; break;
				case 'show_form_remarques_obligatoire': $type = 'int'; break;
				case 'show_form_nb_de_place_obligatoire': $type = 'int'; break;
				case 'calendarCurrentYearMonth' : $type = 'noalpha'; break;
				case 'arrivee_acceptee': $type = 'text'; break;
				case 'depart_demande': $type = 'text'; break;
				case 'prix_de_la_place': $type = 'float'; break;
				case 'devise': $type = 'currency'; break;
				case 'acompte_prix': $type = 'float'; break;
				case 'acompte_pourcentage': $type = 'float'; break;
				case 'nb_de_place': $type = 'int'; break;
				case 'form_date_debut': $type = 'date'; break;
				case 'form_heure_debut': $type = 'hour'; break;
				case 'form_heure_debutp1': $type = 'int'; break;
				case 'form_heure_debutp2': $type = 'int'; break;
				case 'form_date_fin': $type = 'date'; break;
				case 'form_heure_fin': $type = 'hour'; break;
				case 'form_heure_finp1': $type = 'int'; break;
				case 'form_heure_finp2': $type = 'int'; break;
				case 'form_booking_duration': $type = 'int'; break;
				case 'form_personnes': $type = 'int'; break;
				case 'form_nom': $type = 'text'; break;
				case 'form_prenom': $type = 'text'; break;
				case 'form_adresse': $type = 'text'; break;
				case 'form_code_postal': $type = 'text'; break;
				case 'form_ville': $type = 'text'; break;
				case 'form_remarques': $type = 'text'; break;
				case 'form_pays': $type = 'text'; break;
				case 'form_email': $type = 'email'; break;
				case 'form_telephone': $type = 'text'; break;
				case 'form_coupon': $type = 'text'; break;
				case 'form_reduction': $type = 'text'; break;
				case 'statut': $type = 'text'; break;
				case 'reference_interne': $type = 'text'; break;
				case 'date': $type = 'date'; break;
				case 'date_heure': $type = 'hour'; break;
				case 'remarques': $type = 'text'; break;
				case 'submit': $type = 'text'; break;
				case 'id_mp': $type = 'id'; break;
				case 'type_mp': $type = 'text'; break;
				case 'roundnumber': $type = 'int'; break;
				case 'decimalseparator': $type = 'text'; break;
				case 'timezone1': $type = 'int'; break;
				case 'timezone': $type = 'text'; break;
				case 'customcss': $type = 'text'; break;
				case 'year': $type = 'int'; break;
				case 'month': $type = 'int'; break;
				case 'date_debut': $type = 'date'; break;
				case 'date_fin': $type = 'date'; break;
				case 'quantite': $type = 'int'; break;
				case 'montant': $type = 'float'; break;
				case 'pourcentage': $type = 'float'; break;
				case 'periode_heure': $type = 'float'; break;
				case 'code': $type = 'text'; break;
				case 'type': $type = 'text'; break;
				case 'id_coupon': $type = 'id'; break;
				case 'idMP': $type = 'id'; break;
				case 'start': $type = 'text'; break;
				case 'finish': $type = 'text'; break;
				case 'status': $type = 'text'; break;
				case 'obk_get_MP': $type = 'text'; break;
				case 'obk_mainAddPeriodePrice': $type = 'text'; break;
				case 'obk_mainAddExceptionalClosure': $type = 'text'; break;
				case 'price': $type = 'float'; break;
				case 'dayprice': $type = 'array'; break;
				case 'dayprice1': $type = 'float'; break;
				case 'dayprice2': $type = 'float'; break;
				case 'dayprice3': $type = 'float'; break;
				case 'dayprice4': $type = 'float'; break;
				case 'dayprice5': $type = 'float'; break;
				case 'dayprice6': $type = 'float'; break;
				case 'dayprice7': $type = 'float'; break;
				case 'daypriceignoreperiods': $type = 'array'; break;
				case 'resetTexts': $type = 'int'; break;
				case 'obk_ead': $type = 'date'; break;
				case 'obk_edd': $type = 'date'; break;
				case 'obk_ebs': $type = 'text'; break;
				case 'shortcodename': $type = 'text'; break;
				case 'shortcodedisplay': $type = 'text'; break;
				case 'tabEmplacements': $type = 'text'; break;
				case 'obk_deletePeriod': $type = 'text'; break;
				case 'obk_deletePeriodClosure': $type = 'text'; break;
				case 'periodesprices': $type = 'text'; break;
				case 'exceptionalclosure': $type = 'text'; break;
				case 'openingtimes': $type = 'text'; break;
				case 'id_shortcode': $type = 'int'; break;
				case 'tableschoice': $type = 'text'; break;
				case 'CurrentDate': $type = 'date'; break;
				case 'obk_init': $type = 'int'; break;
				case 'arrivalDate': $type = 'date'; break;
				case 'pll_ajax_backend': $type = 'text'; break; //Compatibility with polylang
				default: 
					$mps = array('option','taxe','coupon','reduction');
					foreach($mps as $mp){
						if (substr($key,0,5+strlen($mp)) == 'label'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,6+strlen($mp)) == 'label_'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,8+strlen($mp)) == 'quantite'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,9+strlen($mp)) == 'quantite_'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,7+strlen($mp)) == 'montant'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,8+strlen($mp)) == 'montant_'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,11+strlen($mp)) == 'pourcentage'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,12+strlen($mp)) == 'pourcentage_'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,13+strlen($mp)) == 'periode_heure'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,14+strlen($mp)) == 'periode_heure_'.$mp){$type = 'float'; break 2;}
						if (substr($key,0,4+strlen($mp)) == 'code'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,5+strlen($mp)) == 'code_'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,11+strlen($mp)) == 'description'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,12+strlen($mp)) == 'description_'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,14+strlen($mp)) == 'details_texte_'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,13+strlen($mp)) == 'details_texte'.$mp){$type = 'text'; break 2;}
						if (substr($key,0,11+strlen($mp)) == 'date_debut_'.$mp){$type = 'date'; break 2;}
						if (substr($key,0,9+strlen($mp)) == 'date_fin_'.$mp){$type = 'date'; break 2;}
					}
					if (substr($key,0,8) == 'lngText_'){$type = 'text'; break ;}
					if (substr($key,0,12) == 'form_option_'){$type = 'int'; break ;}
					if (substr($key,0,5) == 'color'){$type = 'color'; break ;}
					echo '-----------------------------------------------------------DANGER-'.$key.'-';
					return array(false,'Unknown key form: '.$key,$_SECPOST);
			}
			//SECURIZE
			$secureData = obk_secureData($value,$type);
			if (!$secureData[0]){return array(false,$secureData[1],$_SECPOST);}
			$_SECPOST[$key] = $value;
		}
		unset($_POST);
	}
	return array(true,'OK',$_SECPOST);	//Form is safe
}

function obk_get_wp_nonce_field($action,$nonceName='_wpnonce'){
	$nonce = wp_create_nonce($action);
	return '<input type="hidden" id="'.$nonceName.'" name="'.$nonceName.'" value="'.$nonce.'" />
			<input type="hidden" name="_wp_http_referer" value="'.$_SERVER['REQUEST_URI'].'" />';
}
