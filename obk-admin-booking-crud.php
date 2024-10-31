<?php
defined( 'ABSPATH' ) or die();
if (!current_user_can('manage_options')){return;}
if (is_admin() !== true) {return;}

if (($obk_act == 'insertBooking') || ($obk_act == 'updateBooking')) {
	$date = $SAFE_DATA["date"]." ".$SAFE_DATA["date_heure"].":00";
	$nb_de_place = $SAFE_DATA["nb_de_place"];
	$prix_de_la_place = $SAFE_DATA["prix_de_la_place"];
	$acompte_prix = $SAFE_DATA["acompte_prix"];
	$acompte_pourcentage = $SAFE_DATA["acompte_pourcentage"];
	$getOT = $wpdb->get_row($wpdb->prepare("SELECT openingtimes, timeUnit, user_minutes_interval, exceptionalclosure FROM {$wpdb->prefix}obk_spaces WHERE id=%d",$SAFE_DATA['obk_idSpace']));
	$obk_OT = json_decode(stripslashes($getOT->openingtimes));
	if (!obk_isNotEmpty($SAFE_DATA["form_heure_debut"])){
		$SAFE_DATA["form_heure_debut"] = obk_getReferenceTimeInOT($obk_OT,$SAFE_DATA["form_date_debut"]." 12:00",1,1,"",1,$getOT->exceptionalclosure);
	}

	if (!obk_isNotEmpty($SAFE_DATA["form_heure_fin"])){
		$SAFE_DATA["form_heure_fin"] = obk_getReferenceTimeInOT($obk_OT,$SAFE_DATA["form_date_fin"]." 00:00",2,$getOT->timeUnit,$SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"],$getOT->user_minutes_interval,$getOT->exceptionalclosure);
		
	}
	$date_arrivee = $SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"].":00";
	$date_depart = $SAFE_DATA["form_date_fin"]." ".$SAFE_DATA["form_heure_fin"].":00";
	$nb_de_personnes = $SAFE_DATA["form_personnes"];
	$nom = $SAFE_DATA["form_nom"];
	$prenom = $SAFE_DATA["form_prenom"];
	$adresse = $SAFE_DATA["form_adresse"];
	$code_postal = $SAFE_DATA["form_code_postal"];
	$ville = $SAFE_DATA["form_ville"];
	$pays = $SAFE_DATA["form_pays"];
	$email = $SAFE_DATA["form_email"];
	$telephone = $SAFE_DATA["form_telephone"];
	$remarques = $SAFE_DATA["remarques"];
	$statut = $SAFE_DATA["statut"];
	$devise = $SAFE_DATA["devise"];
	$reference_interne = $SAFE_DATA["reference_interne"];
	$timeUnit = $SAFE_DATA["timeUnit"];
	$periodesprices = $SAFE_DATA["periodesprices"];
	$dayprice = $SAFE_DATA["dayprice"];
	if ($obk_act == 'updateBooking'){
		$id_reservation = $SAFE_DATA["id_reservation"];
		$getNbPlace = $wpdb->get_row($wpdb->prepare("SELECT nb_de_place FROM {$wpdb->prefix}obk_bookings WHERE id=%d",$id_reservation));
		$nb_de_place_orig = $getNbPlace->nb_de_place;
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_bookings SET nb_de_place='0' WHERE id=%d",$id_reservation));
	}
	if ($SAFE_DATA['obk_idSpace'] != -1){
		$errors = json_decode(obk_checkBookingFormPost($SAFE_DATA['obk_idSpace'],false,true));
		$msg = $errors->msg;
	}else{
		$msg = __('Please select the place', 'oui-booking').'<br>';
	}
	if ($msg != ''){
		if ($obk_act == 'insertBooking'){
			obk_addAdminNotice(str_replace('<br>','. ',$msg),1,'error');
			$obk_act = 'newBooking';
			$reservationRetry = new stdClass();
			$reservationRetry->id = -1;
			$reservationRetry->obk_idSpace = $SAFE_DATA['obk_idSpace'];
			$reservationRetry->date = date('Y-m-d H:i:s');
			$reservationRetry->nb_de_place = $nb_de_place;
			$reservationRetry->prix_de_la_place = $prix_de_la_place;
			$reservationRetry->acompte_prix = $acompte_prix;
			$reservationRetry->acompte_pourcentage = $acompte_pourcentage;
			$reservationRetry->date_arrivee = $date_arrivee;
			$reservationRetry->date_depart = $date_depart;
			$reservationRetry->nb_de_personnes = $nb_de_personnes;
			$reservationRetry->nom = $nom;
			$reservationRetry->prenom = $prenom;
			$reservationRetry->adresse = $adresse;
			$reservationRetry->code_postal = $code_postal;
			$reservationRetry->ville = $ville;
			$reservationRetry->pays = $pays;
			$reservationRetry->email = $email;
			$reservationRetry->telephone = $telephone;
			$reservationRetry->remarques = $remarques;
			$reservationRetry->statut = $statut;
			$reservationRetry->devise = $devise;
			$reservationRetry->reference_interne = $reference_interne;
			$reservationRetry->timeUnit = $timeUnit;
			$reservationRetry->periodesprices = $periodesprices;
			$reservationRetry->dayprice = $dayprice;
		}
		if ($obk_act == 'updateBooking'){
			obk_addAdminNotice(str_replace('<br>','. ',$msg),1,'error');
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_bookings SET nb_de_place=%s WHERE id=%d",$nb_de_place_orig,$id_reservation));
			$obk_act = 'displayBooking';
		}
	}else{
		$warning = $errors->warning;
		$rowEmp = $errors->rowEmp;
		if ($obk_act == 'insertBooking'){
			if ($warning != ''){
				obk_addAdminNotice(str_replace('<br>','. ',$warning),1,'warning');
			}
			$location = $rowEmp->localisation;
			$space = $rowEmp->label;
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_bookings(obk_idSpace,date,nb_de_place,prix_de_la_place,acompte_prix,acompte_pourcentage,date_arrivee,date_depart,nb_de_personnes,nom,prenom,adresse,code_postal,ville,pays,email,telephone,statut,remarques,reference_interne,devise,emplacement,lieu,timeUnit,periodesprices,dayprice) VALUES(%d,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s)",$SAFE_DATA['obk_idSpace'],$date,$nb_de_place,$prix_de_la_place,$acompte_prix,$acompte_pourcentage,$date_arrivee,$date_depart,$nb_de_personnes,$nom,$prenom,$adresse,$code_postal,$ville,$pays,$email,$telephone,$statut,$remarques,$reference_interne,$devise,$space,$location,$timeUnit,$periodesprices,$dayprice));
			obk_addAdminNotice(__('The booking has been added', 'oui-booking'),1);
			$id_reservation = $wpdb->insert_id;
			$SAFE_DATA['id_reservation'] = $id_reservation;
			$obk_act = 'displayBooking';
		}
		if ($obk_act == 'updateBooking'){
			if ($warning != ''){
				obk_addAdminNotice(str_replace('<br>','. ',$warning),1,'warning');
			}
			$location = $rowEmp->localisation;
			$space = $rowEmp->label;
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_bookings SET obk_idSpace=%d,date=%s,nb_de_place=%s,prix_de_la_place=%s,acompte_prix=%s,acompte_pourcentage=%s,date_arrivee=%s,date_depart=%s,nb_de_personnes=%d,nom=%s,prenom=%s,adresse=%s,code_postal=%s,ville=%s,pays=%s,email=%s,telephone=%s,statut=%s,remarques=%s,reference_interne=%s,devise=%s,emplacement=%s,lieu=%s,timeUnit=%d,periodesprices=%s,dayprice=%s WHERE id=%d",$SAFE_DATA['obk_idSpace'],$date,$nb_de_place,$prix_de_la_place,$acompte_prix,$acompte_pourcentage,$date_arrivee,$date_depart,$nb_de_personnes,$nom,$prenom,$adresse,$code_postal,$ville,$pays,$email,$telephone,$statut,$remarques,$reference_interne,$devise,$space,$location,$timeUnit,$periodesprices,$dayprice,$id_reservation));
			obk_addAdminNotice(__('The booking has been updated', 'oui-booking'),1);
			$obk_act = 'displayBooking';
		}
		$tabMP = ['option','taxe','coupon','reduction'];
		foreach ($SAFE_DATA as $key => $data){
			foreach ($tabMP as $typeMP){
				$keyMP = 'label'.$typeMP;
				$keyMPLength = strlen($keyMP);
				if (substr($key,0,$keyMPLength) == $keyMP){
					$idMP = substr($key,$keyMPLength);
					$labelMP = $SAFE_DATA["label".$typeMP.$idMP];
					$descriptionMP = $SAFE_DATA["description".$typeMP.$idMP];
					$quantiteMP = $SAFE_DATA["quantite".$typeMP.$idMP];
					$montantMP = $SAFE_DATA["montant".$typeMP.$idMP];
					$pourcentageMP = $SAFE_DATA["pourcentage".$typeMP.$idMP];
					$periodeMP = $SAFE_DATA["periode_heure".$typeMP.$idMP];
					$codeMP = $SAFE_DATA["code".$typeMP.$idMP];
					(isset($SAFE_DATA["details_texte".$typeMP.$idMP])) ? $details_texteMP = $SAFE_DATA["details_texte".$typeMP.$idMP] : $details_texteMP = '';
					if ($idMP == -1){
						if (($labelMP != '') && ($quantiteMP > 0)){
							$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice_bookings(id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES(%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$id_reservation,$labelMP,$descriptionMP,$quantiteMP,$montantMP,$pourcentageMP,$periodeMP,$codeMP,$typeMP,$details_texteMP));
							obk_addAdminNotice($quantiteMP . ' ' . $labelMP . ' ' .__('added', 'oui-booking'),1);
						}
					}else{
						if (($labelMP == '') || ($quantiteMP == 0)){
							$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_modifyprice_bookings WHERE id=%d",$idMP));
						}else{
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_modifyprice_bookings SET label=%s,description=%s,quantite=%s,montant=%s,pourcentage=%s,periode_heure=%s,code=%s,details_texte=%s WHERE id=%d",$labelMP,$descriptionMP,$quantiteMP,$montantMP,$pourcentageMP,$periodeMP,$codeMP,$details_texteMP,$idMP));
						}
					}
				}
			}
		}
	}
}

if ($obk_act == "deleteBooking"){
	$id_reservation = $SAFE_DATA["id_reservation"];
	$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_bookings WHERE id=%d",$id_reservation ));
	obk_addAdminNotice(__('The booking has been removed', 'oui-booking'),1);
}

if (($obk_act == 'displayBooking') || ($obk_act == 'newBooking')){
	if ($obk_act == 'displayBooking'){
		$id_reservation = $SAFE_DATA['id_reservation'];
		$reservation = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}obk_bookings WHERE id = %d", $id_reservation));
		$action = 'updateBooking';
	}
	if ($obk_act == 'newBooking'){
		$id_reservation = -1;
		if (isset($reservationRetry)){
			$reservation = $reservationRetry;
		}else{
			$reservation = new stdClass();
			$reservation->id = $id_reservation;
			$reservation->obk_idSpace = '';
			$reservation->nom = '';
			$reservation->date = date('Y-m-d H:i:s');
			$reservation->nb_de_place = '';
			$reservation->prix_de_la_place = '';
			$reservation->acompte_prix = '';
			$reservation->acompte_pourcentage = '';
			$reservation->date_arrivee = '';
			$reservation->date_depart = '';
			$reservation->nb_de_personnes = '';
			$reservation->nom = '';
			$reservation->prenom = '';
			$reservation->adresse = '';
			$reservation->code_postal = '';
			$reservation->ville = '';
			$reservation->pays = '';
			$reservation->email = '';
			$reservation->telephone = '';
			$reservation->remarques = '';
			$reservation->statut = '';
			$reservation->devise = '';
			$reservation->reference_interne = '';
			$reservation->timeUnit = '';
			$reservation->periodesprices = '';
			$reservation->dayprice = '';
		}
		$action = 'insertBooking';
	}
	$echo .= '
	<div class="obk_wrap">
		<div>';
			if (isset($SAFE_DATA["calendarCurrentYearMonth"])){
				$echo .= '<input type="hidden" id="obk_currentMonth" value="'.(substr($SAFE_DATA["calendarCurrentYearMonth"],5,2)-1).'">';
				$echo .= '<input type="hidden" id="obk_currentYear" value="'.substr($SAFE_DATA["calendarCurrentYearMonth"],0,4).'">';
			}
			$echo .= '
			<div id="obk_navBar">
				<form name="when">
					<input type="hidden" id="obk_nbDePlace" value="0">';
			$echo .= obk_get_wp_nonce_field('getEmplacementVal','obk_mainGetEmplacementVal');
			$echo .= obk_get_wp_nonce_field('chargeCalendrier','obk_mainCalendar');
			$echo .= '
					<table>
						<tr>
						   <td><span class="obk_norotate_arrow_calendar">
									<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="obk_calendarSkip(\'-\')">
								</span>
							</td>
						   <td> </td>
						   <td><select name="month" onChange="obk_calendarOnMonth()">';
						   $echo .= obk_getMonthsOption(date('Y'),date('m'));
						   $echo .= '</select>
						   </td>
						   <td colspan="2"><input class="obk_calendarYear" type="text" name="year" size=4 maxlength=4 onKeyPress="return obk_calendarCheckNums()" onKeyUp="obk_calendarOnYear()"></td>
						   <td><span class="obk_rotate_arrow_calendar">
									<img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'" onClick="obk_calendarSkip(\'+\')">
								</span>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<div id="obk_calendar"></div><script>obk_getEmplacementVal("'.$reservation->obk_idSpace.'",true)</script>';
			$echo .= '<form action="" method="post" name="obk_formAddEditBookingSpace">';
			$echo .= '<input type="hidden" name="obk_act" value="'.$action.'">';
			$echo .= '<input type="hidden" name="id_reservation" value="'.$reservation->id.'">';
			$echo .= '<input type="hidden" name="periodesprices" value="'.$reservation->periodesprices.'">';
			$echo .= '<input type="hidden" name="dayprice" value="'.$reservation->dayprice.'">';
			$echo .= obk_get_wp_nonce_field($action);
			if (isset($SAFE_DATA["obk_prevAct"])){
				$echo .= '<input type="hidden" name="obk_prevAct" value="'.$SAFE_DATA["obk_prevAct"].'"><input type="hidden" name="id_lieu" value="'.$SAFE_DATA["id_lieu"].'"><input type="hidden" name="calendarCurrentYearMonth" value="'.$SAFE_DATA['calendarCurrentYearMonth'].'">';
			}
			$echo .= '<button class="obk_accordion">';
			if ($id_reservation != -1){
				$echo .= __('Booking', 'oui-booking').' #'.$reservation->id;
			}
			$echo .= '</button>
						<div class="obk_panel">';		
			$echo .= '<div class="obk_booking_column"><br>';
			$echo3 = '<option></option>';
			$emplacements = $wpdb->get_results($wpdb->prepare("SELECT emp.id,emp.label,lieu.nom,%s FROM {$wpdb->prefix}obk_spaces emp INNER JOIN {$wpdb->prefix}obk_locations lieu ON lieu.id=emp.id_lieu ORDER BY emp.id DESC",'none'));
			foreach ($emplacements as $emplacement) {
				$echo3 .= '<option value="'.$emplacement->id.'" '.(($emplacement->id == $reservation->obk_idSpace)? 'selected':'').'>#'.$emplacement->id.' - '.obk_removeslashes($emplacement->nom).' - '.obk_removeslashes($emplacement->label).'</option>';
			}
			$echo .= '
			<label><b>'.__('Place', 'oui-booking').'</b></label><br><select name="obk_idSpace" id="obk_idSpace" onchange="obk_getEmplacementVal(this.value)" required>'.$echo3.'</select><br><br>
			<label><b>'.__('Price', 'oui-booking').'</b></label><br><input type="number" min="0" step="any" name="prix_de_la_place" value="'.($reservation->prix_de_la_place+0).'"><br><br>
			<label><b>'.__('Currency', 'oui-booking').'</b></label><br><select name="devise">'.obk_getAllCurrencies($reservation->devise).'</select><br><br>
			<label><b>'.__('Time unit (in hours)', 'oui-booking').'</b></label><br><input type="number" name="timeUnit" min="0" value="'.($reservation->timeUnit+0).'"><br><br>
			<label><b>'.__('Deposit requested (amount)', 'oui-booking').'</b></label><br><input type="number" min="0" step="any" name="acompte_prix" value="'.($reservation->acompte_prix+0).'"><br><br>
			<label><b>'.__('Deposit requested (percentage)', 'oui-booking').'</b></label><br><input type="number" min="0" step="any" name="acompte_pourcentage" value="'.($reservation->acompte_pourcentage+0).'"><br><br>
			</div><div class="obk_booking_column"><br>
			<label><b>'.__('Number of places', 'oui-booking').'</b></label><br><input type="number" min="0" step="any" name="nb_de_place" value="'.($reservation->nb_de_place+0).'"><br><br>
			<label><b>'.__('Arrival date', 'oui-booking').'</b></label><br><input type="date" name="form_date_debut" required value="'.substr($reservation->date_arrivee,0,10).'"><br><br>
			<label><b>'.__('Arrival time', 'oui-booking').'</b></label><br><input type="time" name="form_heure_debut" value="'.substr($reservation->date_arrivee,11,5).'"><br><br>
			<label><b>'.__('Departure date', 'oui-booking').'</b></label><br><input type="date" name="form_date_fin" required value="'.substr($reservation->date_depart,0,10).'"><br><br>
			<label><b>'.__('Departure time', 'oui-booking').'</b></label><br><input type="time" name="form_heure_fin" value="'.substr($reservation->date_depart,11,5).'"><br><br>
			<label><b>'.__('Number of people', 'oui-booking').'</b></label><br><input type="number" name="form_personnes" min="0" value="'.($reservation->nb_de_personnes+0).'"><br><br>
			</div><div class="obk_booking_column"><br>
			<label><b>'.__('Last name', 'oui-booking').'</b></label><br><input type="text" name="form_nom" value="'.obk_removeslashes($reservation->nom).'"><br><br>
			<label><b>'.__('First name', 'oui-booking').'</b></label><br><input type="text" name="form_prenom" value="'.obk_removeslashes($reservation->prenom).'"><br><br>
			<label><b>'.__('Address', 'oui-booking').'</b></label><br><input type="text" name="form_adresse" value="'.obk_removeslashes($reservation->adresse).'"><br><br>
			<label><b>'.__('Zip code', 'oui-booking').'</b></label><br><input type="text" name="form_code_postal" value="'.obk_removeslashes($reservation->code_postal).'"><br><br>
			<label><b>'.__('City', 'oui-booking').'</b></label><br><input type="text" name="form_ville" value="'.obk_removeslashes($reservation->ville).'"><br><br>
			<label><b>'.__('Country', 'oui-booking').'</b></label><br><input type="text" name="form_pays" value="'.obk_removeslashes($reservation->pays).'"><br><br>
			<label><b>'.__('Email', 'oui-booking').'</b></label><br><input type="email" name="form_email" value="'.$reservation->email.'"><br><a href="mailto:'.$reservation->email.'">'.__('Send an email', 'oui-booking').'</a><br><br>
			<label><b>'.__('Tel.', 'oui-booking').'</b></label><br><input type="text" name="form_telephone" value="'.obk_removeslashes($reservation->telephone).'"><br><br>
			</div><div class="obk_booking_column"><br>
			<label><b>'.__('Status', 'oui-booking').'</b></label><br>
			<select name="statut">
				<option value="validationinprogress" '.(($reservation->statut == 'validationinprogress')? 'selected':'').'>'.obk_getBookingStatus('validationinprogress').'</option>
				<option value="pendingpayment" '.(($reservation->statut == 'pendingpayment')? 'selected':'').'>'.obk_getBookingStatus('pendingpayment').'</option>
				<option value="confirmed" '.(($reservation->statut == 'confirmed')? 'selected':'').'>'.obk_getBookingStatus('confirmed').'</option>
				<option value="paid" '.(($reservation->statut == 'paid')? 'selected':'').'>'.obk_getBookingStatus('paid').'</option>
				<option value="canceled" '.(($reservation->statut == 'canceled')? 'selected':'').'>'.obk_getBookingStatus('canceled').'</option>
			</select><br><br>
			<label><b>'.__('Internal reference', 'oui-booking').'</b></label><br><input type="text" name="reference_interne" value="'.$reservation->reference_interne.'"><br><br>
			<label><b>'.__('Registration date', 'oui-booking').'</b></label><br><input type="date" name="date" required value="'.substr($reservation->date,0,10).'"><br><br>
			<label><b>'.__('Registration time', 'oui-booking').'</b></label><br><input type="time" name="date_heure" required value="'.substr($reservation->date,11,5).'"><br><br>
			<label><b>'.__('Comments').'</b></label><br><textarea name="remarques" rows="10">'.obk_removeslashes($reservation->remarques).'</textarea><br><br>
			</div><br></div>';
			$tabPrices = obk_bookingPrices($reservation->id,$reservation->nb_de_place,$reservation->prix_de_la_place,$reservation->date_arrivee,$reservation->date_depart,$reservation->devise,$reservation->obk_idSpace,$reservation->timeUnit,$reservation->periodesprices,$reservation->dayprice);
			$echo .= $tabPrices["echo"];
			$echo .= '<button class="obk_accordion">'.__('Total', 'oui-booking').'</button><div class="obk_panel"><br>';
			$echo .= "<div class='obk_bloc'><label><b>".__('Initial price', 'oui-booking')."</b></label><br><input id='obk_initialPrice' disabled type='text' value='".round($tabPrices['prixsansoptionsanstaxe'],sprintf( '%.'.obk_getParameter('roundnumber').'f', obk_getParameter('roundnumber')))." ".$reservation->devise."'></div>";
			$echo .= obk_displayPrice($tabPrices['prixsansoptionsanstaxe'], $reservation->devise, 'obk_initialPrice', 'value');
			$echo .= "<div class='obk_bloc'><label><b>".__('Price with options', 'oui-booking')."</b></label><br><input id='obk_priceWithOptions' disabled type='text' value='".round($tabPrices['prixsanstaxe'],sprintf( '%.'.obk_getParameter('roundnumber').'f', obk_getParameter('roundnumber'))).' '.$reservation->devise."'></div>";
			$echo .= obk_displayPrice($tabPrices['prixsanstaxe'], $reservation->devise, 'obk_priceWithOptions', 'value');
			$echo .= "<div class='obk_bloc'><label><b>".__('Price with taxes', 'oui-booking')."</b></label><br><input id='obk_priceWithTaxes' disabled type='text' value='".round($tabPrices['prixsansremise'],sprintf( '%.'.obk_getParameter('roundnumber').'f', obk_getParameter('roundnumber'))).' '.$reservation->devise."'></div>";
			$echo .= obk_displayPrice($tabPrices['prixsansremise'], $reservation->devise, 'obk_priceWithTaxes', 'value');
			$echo .= "<div class='obk_bloc'><label><b>".__('Price with deductions', 'oui-booking')."</b></label><br><input id='obk_priceWithDeductions' disabled type='text' value='".round($tabPrices['prixavecremise'],sprintf( '%.'.obk_getParameter('roundnumber').'f', obk_getParameter('roundnumber'))).' '.$reservation->devise."'></div>";
			$echo .= obk_displayPrice($tabPrices['prixavecremise'], $reservation->devise, 'obk_priceWithDeductions', 'value');
			$echo .= "<div class='obk_bloc'><div><label><b>".__('Total to pay', 'oui-booking')."</b></label><br><input id='obk_totalToPay' disabled type='text' value='".sprintf( '%.'.obk_getParameter('roundnumber').'f', round($tabPrices['prixfinal'],obk_getParameter('roundnumber'))).' '.$reservation->devise."'></div></div>";
			$echo .= obk_displayPrice($tabPrices['prixfinal'], $reservation->devise, 'obk_totalToPay', 'value');
			$echo .= "<br><br></div>";	
			$echo .= '<div class="obk_buttons"><div class="obk_pad10">';
			$echo .= get_submit_button(__('Save', 'oui-booking')).'
			</form>
			<form action="" method="post" class="obk_delete" onsubmit="return confirm(\''.__('Are you sure you want to delete this booking?', 'oui-booking').'\');">
				<input type="hidden" name="obk_act" value="deleteBooking">
				<input type="hidden" name="id_reservation" value="'.$id_reservation.'">';
			$echo .= obk_get_wp_nonce_field('deleteBooking');
			if (isset($SAFE_DATA["obk_prevAct"])){
				$echo .= '<input type="hidden" name="obk_prevAct" value="'.$SAFE_DATA["obk_prevAct"].'"><input type="hidden" name="id_lieu" value="'.$SAFE_DATA["id_lieu"].'"><input type="hidden" name="obk_idSpace" value="'.$SAFE_DATA["obk_idSpace"].'"><input type="hidden" name="calendarCurrentYearMonth" value="'.$SAFE_DATA['calendarCurrentYearMonth'].'">';
			}
			$echo .= get_submit_button(__('Delete', 'oui-booking'),'delete').'
			</form>
			</div></div>
		</div></div>';
}
