<?php
defined( 'ABSPATH' ) or die();
if (is_admin() === true) {
	add_action( 'wp_ajax_js_getEmplacementVal', 'js_getEmplacementVal' );
	add_action( 'wp_ajax_js_deleteCoupon', 'js_deleteCoupon' );
	add_action( 'wp_ajax_js_addPeriodePrice', 'js_addPeriodePrice' );
	add_action( 'wp_ajax_js_deletePeriod', 'js_deletePeriod' );
	add_action( 'wp_ajax_js_addExceptionalClosure', 'js_addExceptionalClosure' );
	add_action( 'wp_ajax_js_deletePeriodClosure', 'js_deletePeriodClosure' );
	add_action( 'wp_ajax_js_ajouterCoupon', 'js_ajouterCoupon' );
	add_action( 'wp_ajax_js_associeCoupon', 'js_associeCoupon' );
	add_action( 'wp_ajax_js_desassocieCoupon', 'js_desassocieCoupon' );
	add_action( 'wp_ajax_js_getMP', 'js_getMP' );
	add_action( 'wp_ajax_js_chargeCalendrier', 'js_chargeCalendrier' );
	add_action( 'wp_ajax_nopriv_js_chargeCalendrier', 'js_chargeCalendrier' );
	add_action( 'wp_ajax_js_saveBooking', 'js_saveBooking' );
	add_action( 'wp_ajax_nopriv_js_saveBooking', 'js_saveBooking' );
	add_action( 'wp_ajax_js_getAvailableOptions', 'js_getAvailableOptions' );
	add_action( 'wp_ajax_nopriv_js_getAvailableOptions', 'js_getAvailableOptions' );
}

function js_getEmplacementVal() {
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG7:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '';
	$reponse = [];
	if ($obk_idSpace != ""){
		$reponse = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}obk_spaces WHERE id = %d",$obk_idSpace));
		$reponse->prix_de_la_place += 0;
		$reponse->acompte_prix += 0;
		$reponse->acompte_pourcentage += 0;			
		$reponse->timeUnit += 0;			
		
		//$today = date('Y-m-d');
		$reponse->listoption = [];
		$reponse->listtaxe = [];
		$reponse->listcoupon = [];
		$reponse->listreduction = [];
		//$resultatsMP = $wpdb->get_results($wpdb->prepare("SELECT mp.id, mp.label, mp.type FROM {$wpdb->prefix}obk_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}obk_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.obk_idSpace=%s AND mp.date_debut <= %s AND mp.date_fin >= %s AND (mp.quantite > 0 OR mp.quantite_initiale = 0)", $obk_idSpace,$today,$today));
		$resultatsMP = $wpdb->get_results($wpdb->prepare("SELECT mp.id, mp.label, mp.type FROM {$wpdb->prefix}obk_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}obk_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.obk_idSpace=%s AND (mp.quantite > 0 OR mp.quantite_initiale = 0)", $obk_idSpace));
		foreach($resultatsMP as $mp){
			switch($mp->type){
				case 'option':
					$reponse->listoption[] = [$mp->id, $mp->label];
					break;
				case 'taxe':
					$reponse->listtaxe[] = [$mp->id, $mp->label];
					break;
				case 'coupon':
					$reponse->listcoupon[] = [$mp->id, $mp->label];
					break;
				case 'reduction':
					$reponse->listreduction[] = [$mp->id, $mp->label];
					break;
			}
		}
		
	}
	echo json_encode($reponse);
	wp_die();
}

function js_deleteCoupon() {
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG8:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$id_modificationprix = (isset($SAFE_DATA['id_coupon']))? $SAFE_DATA['id_coupon'] : '';
	if ($id_modificationprix != ""){
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_modifyprice_spaces WHERE id_modificationprix = %d",$id_modificationprix));
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_modifyprice WHERE id = %d",$id_modificationprix));
	}
	wp_die();
}

function js_addPeriodePrice() {
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG9:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '-1';
	$start = (isset($SAFE_DATA['start']))? $SAFE_DATA['start'] : '';
	$finish = (isset($SAFE_DATA['finish']))? $SAFE_DATA['finish'] : '';
	$price = (isset($SAFE_DATA['price']))? $SAFE_DATA['price'] : '0';
	
	if (($start == '')||($finish == '')){
		echo __('Please fill in the dates for the period','oui-booking');
	}else if ($start > $finish){
		echo __('Start date is after finish date','oui-booking');
	}else{
		$newtabperiodesprices = '';
		$resultatsPeriods = $wpdb->get_results($wpdb->prepare("SELECT periodesprices FROM {$wpdb->prefix}obk_spaces WHERE id = %d",$obk_idSpace));
		if (isset($resultatsPeriods[0])){
			$oldtabperiodesprices = $resultatsPeriods[0]->periodesprices;
			$oldtabperiodesprices = explode('--o--',$oldtabperiodesprices);
			$updated = false;
			foreach($oldtabperiodesprices as $period){
				if ($period != ''){
					$pp = explode(';',$period);
					if (!$updated){
						if (($finish >= $pp[0]) && ($finish <= $pp[1])){
							echo __('Error, this period is in conflict with another period','oui-booking');
							$updated = true;
						}else if (($start >= $pp[0]) && ($start <= $pp[1])){
							echo __('Error, this period is in conflict with another period','oui-booking');
							$updated = true;
						}else if (($start <= $pp[0])&&($finish >= $pp[0])){
							echo __('Error, this period is in conflict with another period','oui-booking');
							$updated = true;
						}
						if (($start < $pp[0]) && (!$updated)){
							$newtabperiodesprices .= $start.';'.$finish.';'.$price.'--o--';
							$updated = true;
						}
					}
					$newtabperiodesprices .= $pp[0].';'.$pp[1].';'.$pp[2].'--o--';
				}
			}
			if (!$updated){
				$newtabperiodesprices .= $start.';'.$finish.';'.$price.'--o--';
			}
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET periodesprices = %s WHERE id = %d",$newtabperiodesprices,$obk_idSpace));
		}
	}
	wp_die();
}

function js_deletePeriod(){
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG10:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '-1';
	$start = (isset($SAFE_DATA['start']))? $SAFE_DATA['start'] : '-1';
	$finish = (isset($SAFE_DATA['finish']))? $SAFE_DATA['finish'] : '-1';
	$price = (isset($SAFE_DATA['price']))? $SAFE_DATA['price'] : '-1';
	$newtabperiodesprices = '';
	$resultatsPeriods = $wpdb->get_results($wpdb->prepare("SELECT periodesprices FROM {$wpdb->prefix}obk_spaces WHERE id = %d",$obk_idSpace));
	if (isset($resultatsPeriods[0])){
		$oldtabperiodesprices = $resultatsPeriods[0]->periodesprices;
		$oldtabperiodesprices = explode('--o--',$oldtabperiodesprices);
		foreach($oldtabperiodesprices as $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($pp[0] != $start)||($pp[1] != $finish)||($pp[2] != $price)){
					$newtabperiodesprices .= $pp[0].';'.$pp[1].';'.$pp[2].'--o--';
				}
			}
		}
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET periodesprices = %s WHERE id = %d",$newtabperiodesprices,$obk_idSpace));
	}
	wp_die();
}

function js_addExceptionalClosure() {
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG20:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '-1';
	$start = (isset($SAFE_DATA['start']))? $SAFE_DATA['start'] : '';
	$finish = (isset($SAFE_DATA['finish']))? $SAFE_DATA['finish'] : '';
	
	if (($start == '')||($finish == '')){
		echo __('Please fill in the dates for the period','oui-booking');
	}else if ($start > $finish){
		echo __('Start date is after finish date','oui-booking');
	}else{
		$newtabperiodesclosures = '';
		$resultatsPeriods = $wpdb->get_results($wpdb->prepare("SELECT exceptionalclosure FROM {$wpdb->prefix}obk_spaces WHERE id = %d",$obk_idSpace));
		if (isset($resultatsPeriods[0])){
			$oldtabperiodesclosures = $resultatsPeriods[0]->exceptionalclosure;
			$oldtabperiodesclosures = explode('--o--',$oldtabperiodesclosures);
			$updated = false;
			foreach($oldtabperiodesclosures as $period){
				if ($period != ''){
					$pp = explode(';',$period);
					if (!$updated){
						if (($finish >= $pp[0]) && ($finish <= $pp[1])){
							echo __('Error, this period is in conflict with another period','oui-booking');
							$updated = true;
						}else if (($start >= $pp[0]) && ($start <= $pp[1])){
							echo __('Error, this period is in conflict with another period','oui-booking');
							$updated = true;
						}else if (($start <= $pp[0])&&($finish >= $pp[0])){
							echo __('Error, this period is in conflict with another period','oui-booking');
							$updated = true;
						}
						if (($start < $pp[0]) && (!$updated)){
							$newtabperiodesclosures .= $start.';'.$finish.'--o--';
							$updated = true;
						}
					}
					$newtabperiodesclosures .= $pp[0].';'.$pp[1].'--o--';
				}
			}
			if (!$updated){
				$newtabperiodesclosures .= $start.';'.$finish.'--o--';
			}
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET exceptionalclosure = %s WHERE id = %d",$newtabperiodesclosures,$obk_idSpace));
		}
	}
	wp_die();
}

function js_deletePeriodClosure(){
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG21:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '-1';
	$start = (isset($SAFE_DATA['start']))? $SAFE_DATA['start'] : '-1';
	$finish = (isset($SAFE_DATA['finish']))? $SAFE_DATA['finish'] : '-1';
	$newtabperiodesclosures = '';
	$resultatsPeriods = $wpdb->get_results($wpdb->prepare("SELECT exceptionalclosure FROM {$wpdb->prefix}obk_spaces WHERE id = %d",$obk_idSpace));
	if (isset($resultatsPeriods[0])){
		$oldtabperiodesclosures = $resultatsPeriods[0]->exceptionalclosure;
		$oldtabperiodesclosures = explode('--o--',$oldtabperiodesclosures);
		foreach($oldtabperiodesclosures as $period){
			if ($period != ''){
				$pp = explode(';',$period);
				if (($pp[0] != $start)||($pp[1] != $finish)){
					$newtabperiodesclosures .= $pp[0].';'.$pp[1].'--o--';
				}
			}
		}
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET exceptionalclosure = %s WHERE id = %d",$newtabperiodesclosures,$obk_idSpace));
	}
	wp_die();
}

function js_ajouterCoupon(){
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG12:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$label = (isset($SAFE_DATA['label']))? $SAFE_DATA['label'] : '';
	$description = (isset($SAFE_DATA['description']))? $SAFE_DATA['description'] : '';
	$details_texte = (isset($SAFE_DATA['details_texte']))? $SAFE_DATA['details_texte'] : '';
	$date_debut = (isset($SAFE_DATA['date_debut']))? $SAFE_DATA['date_debut'].' 00:00:00' : '';
	$date_fin = (isset($SAFE_DATA['date_fin']))? $SAFE_DATA['date_fin'].' 23:59:59' : '';
	$quantite = (isset($SAFE_DATA['quantite']))? $SAFE_DATA['quantite'] : '';
	$quantite_initiale = $quantite;
	$montant = (isset($SAFE_DATA['montant']))? $SAFE_DATA['montant'] : '';
	$pourcentage = (isset($SAFE_DATA['pourcentage']))? $SAFE_DATA['pourcentage'] : '';
	$periode_heure = (isset($SAFE_DATA['periode_heure']))? $SAFE_DATA['periode_heure'] : '';
	$code = (isset($SAFE_DATA['code']))? $SAFE_DATA['code'] : '';
	$type = (isset($SAFE_DATA['type']))? $SAFE_DATA['type'] : '';
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '';
	$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice(label,description,date_debut,date_fin,quantite,quantite_initiale,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$label,$description,$date_debut,$date_fin,$quantite,$quantite_initiale,$montant,$pourcentage,$periode_heure,$code,$type,$details_texte));
	$id_modificationprix = $wpdb->insert_id;
	echo $id_modificationprix;
	js_associeCoupon($id_modificationprix);
	wp_die();
}

function js_associeCoupon($id_modificationprix){
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG13:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '';
	if ($id_modificationprix == ''){
		$id_modificationprix = (isset($SAFE_DATA['id_coupon']))? $SAFE_DATA['id_coupon'] : '';
	}
	if (($obk_idSpace != "")&&($id_modificationprix != "")){
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice_spaces(obk_idSpace,id_modificationprix) VALUES (%d,%d)",$obk_idSpace,$id_modificationprix));
	}
	wp_die();
}

function js_desassocieCoupon(){
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG14:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '';
	$id_modificationprix = (isset($SAFE_DATA['id_coupon']))? $SAFE_DATA['id_coupon'] : '';
	if (($obk_idSpace != "")&&($id_modificationprix != "")){
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_modifyprice_spaces WHERE obk_idSpace = %d AND id_modificationprix = %d",$obk_idSpace,$id_modificationprix));
	}
	echo "OK";
	wp_die();
}

function js_getMP(){
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG15:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	global $wpdb;
	global $SAFE_DATA;
	if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG16:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$idMP = (isset($SAFE_DATA['idMP']))? $SAFE_DATA['idMP'] : '';
	$reponse = [];
	if ($idMP != ""){
		$reponse = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}obk_modifyprice WHERE id = %d",$idMP));
		$reponse->montant += 0;
		$reponse->pourcentage += 0;
		$reponse->periode_heure += 0;
	}
	echo json_encode($reponse);
	wp_die();
}


//----------------------------FRONT----------------------------------

function js_getAvailableOptions(){
	global $wpdb;
	global $SAFE_DATA;
	//if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG22:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = $SAFE_DATA['obk_idSpace'];
	$arrivalDate = $SAFE_DATA['arrivalDate'];
	if (isset($SAFE_DATA['form_coupon'])){$form_coupon = $SAFE_DATA['form_coupon'];}else{$form_coupon = "";}
	if (isset($SAFE_DATA['form_reduction'])){$form_reduction = $SAFE_DATA['form_reduction'];}else{$form_reduction = "";}
	$result = '';
	$tabDisplayPrice = [];
	$results = $wpdb->get_results($wpdb->prepare("SELECT mp.id, mp.type, mp.quantite_initiale, mp.label, mp.montant, mp.pourcentage, mp.description, mp.details_texte, mp.code FROM {$wpdb->prefix}obk_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}obk_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.obk_idSpace=%s AND mp.date_debut <= %s AND mp.date_fin >= %s AND (mp.quantite > 0 OR mp.quantite_initiale = 0)", $obk_idSpace,$arrivalDate,$arrivalDate));
	
	if (sizeof($results)>0){
		$resultCoupon = '';
		$resultReduction = '';
		$resultOption = '';
		$convert = array("coupon" => __('coupon', 'oui-booking'),"reduction" => __('discount', 'oui-booking'),"taxe" => __('tax', 'oui-booking'));
		$couponsAff = false;
		$reductionsAff = false;
		
		$row = $wpdb->get_row($wpdb->prepare("SELECT devise FROM {$wpdb->prefix}obk_spaces emp WHERE id = %s", $obk_idSpace));
		foreach($results as $mp){
			if (($mp->type == "coupon")&&(!$couponsAff)){
				$resultCoupon .= '<div class="obk_formRow"><div>&nbsp;'.ucfirst($convert[$mp->type]).' ('.__('Code', 'oui-booking').'): </div><div><input type="text" name="form_coupon" value="'.$form_coupon.'"></div></div>';
				$couponsAff = true;
			}
			if (($mp->type == "reduction")&&(!$reductionsAff)){
				$resultReduction .= '<div class="obk_formRow"><div>&nbsp;'.ucfirst($convert[$mp->type]).' ('.__('Code', 'oui-booking').'): </div><div><input type="text" name="form_reduction" value="'.$form_reduction.'"></div></div>';
				$reductionsAff = true;
			}
			if ($mp->type == "option"){
				if (($mp->code != 'userchoice') || ($mp->quantite_initiale > 0)){
					$resultOption .= '<div class="obk_formRow"><div><b>'.ucfirst(obk_removeslashes($mp->label)).'</b>:</div><div><div>';
					$displaySelect = false;
					if (($mp->code == 'userchoice') && ($mp->quantite_initiale > 0)){
						$displaySelect = true;
						$resultOption .= '<select name="form_option_'.$mp->id.'">';
						(isset($SAFE_DATA["form_option_".$mp->id]))? $form_option = $SAFE_DATA["form_option_".$mp->id]: $form_option = "";
						for($i = 0; $i <= $mp->quantite_initiale; $i++){
							($form_option == $i)? $selected = 'selected' : $selected = '';
							$resultOption .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
						}
						$resultOption .= '</select>';
					}
					if ($mp->montant != 0){
						if ($displaySelect){$resultOption .= ' x ';}
						$resultOption .= '<span id="obk_option'.$mp->id.'">'.($mp->montant+0) . ' '.$row->devise.'</span>';
						//$resultOption .= obk_displayPrice($mp->montant, $row->devise, 'obk_option'.$mp->id);
						$tabDisplayPrice[] = [$mp->montant, $row->devise, 'obk_option'.$mp->id];
					}
					if ($mp->pourcentage != 0){
						if ($displaySelect){$resultOption .= ' x ';}
						$resultOption .= ($mp->pourcentage+0).'%'; 
					}
					
					if ($mp->code != 'userchoice'){
						if ($mp->code  == 'oneperhour'){ $resultOption .= ' (' . __('Per booked hour','oui-booking') . ')'; }
						if ($mp->code  == 'oneperday'){ $resultOption .= ' (' . __('Per booked day','oui-booking') . ')'; }
						if ($mp->code  == 'onepernight'){ $resultOption .= ' (' . __('Per booked night','oui-booking') . ')'; }
						if ($mp->code  == 'oneperweek'){ $resultOption .= ' (' . __('Per booked week','oui-booking') . ')'; }
						if ($mp->code  == 'onepermonth'){ $resultOption .= ' (' . __('Per booked month','oui-booking') . ')'; }
					}
					
					$displayDetails = explode("<br />",nl2br(obk_removeslashes($mp->details_texte)));
					if ((sizeof($displayDetails) > 0) && ($displayDetails[0] != '')){
						$resultOption .= '<br><span><select name="form_option_details_texte_'.$mp->id.'">';
						(isset($SAFE_DATA["form_option_details_texte_".$mp->id]))? $form_option_details_texte = $SAFE_DATA["form_option_details_texte_".$mp->id]: $form_option_details_texte = "";
						$resultOption .= '<option>'.__('Your choice', 'oui-booking').'</option>';
						for($i = 0; $i < sizeof($displayDetails); $i++){
							if (trim($displayDetails[$i]) != ''){
								($form_option_details_texte == trim($displayDetails[$i]))? $selected = 'selected' : $selected = '';
								$resultOption .= '<option value="'.trim($displayDetails[$i]).'" '.$selected.'>'.trim($displayDetails[$i]).'</option>';
							}
						}
						$resultOption .= '</select>';
						$resultOption .= '</span>';
					}
					if ($mp->description != ''){
						$resultOption .= '<div class="obk_formRow">' . nl2br(ucfirst(obk_removeslashes($mp->description))) . '</div>';
					}
					$resultOption .= '</div><br>';
					$resultOption .= '</div></div>';
				}
			}
		}
		$result .= $resultCoupon . $resultReduction;
		if ($resultOption != ''){
			$result .= $resultOption;
		}
	}
	echo json_encode([$result,$tabDisplayPrice]);
	wp_die();
}

function js_chargeCalendrier($CurrentDate='', $try=12){
	global $wpdb;
	global $SAFE_DATA;
	//if (!current_user_can('administrator')){return;}
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG17:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = (isset($SAFE_DATA['obk_idSpace']))? $SAFE_DATA['obk_idSpace'] : '';
	$init = (isset($SAFE_DATA['obk_init']))? $SAFE_DATA['obk_init'] : '';
	if ($CurrentDate == ''){
		$CurrentDate = (isset($SAFE_DATA['CurrentDate']))? $SAFE_DATA['CurrentDate'] : $CurrentDate;
	}
	
	if (!current_user_can('administrator')){
		while ($CurrentDate < date("Y-m-d",strtotime("- 7 day"))){
			$CurrentDate = date("Y-m-d",strtotime($CurrentDate . ' + 7 day'));
		}
	}
	$ts_CurrentDate = mktime((int)substr($CurrentDate,11,2),(int)substr($CurrentDate,14,2),(int)substr($CurrentDate,17,2),(int)substr($CurrentDate,5,2),(int)substr($CurrentDate,8,2),(int)substr($CurrentDate,0,4));
	$numDay = getdate($ts_CurrentDate)['wday'];
	while($numDay != 1){
		$CurrentDate = date("Y-m-d H:i:s",$ts_CurrentDate - 24 * 3600);
		$ts_CurrentDate = mktime((int)substr($CurrentDate,11,2),(int)substr($CurrentDate,14,2),(int)substr($CurrentDate,17,2),(int)substr($CurrentDate,5,2),(int)substr($CurrentDate,8,2),(int)substr($CurrentDate,0,4));
		$numDay = getdate($ts_CurrentDate)['wday'];
	}
	$year = substr($CurrentDate,0,4);
	$month = substr($CurrentDate,5,2);
	$day = substr($CurrentDate,8,2);
	$date_debut_recherche = "$year-$month-$day 00:00:00";
	$ts_date_fin = mktime(0,0,0,(int)$month,(int)$day,(int)$year);
	$date_fin_recherche = date("Y-m-d H:i:s",$ts_date_fin + 7 * 24 * 3600 - 1);
	$ok = true;
	if ($obk_idSpace != -1){
		$row_space = $wpdb->get_row($wpdb->prepare("SELECT nb_de_place, date_debut_reservation, date_fin_reservation, openingtimes, timeUnit, user_minutes_interval, minBookingDuration, exceptionalclosure FROM {$wpdb->prefix}obk_spaces WHERE id = %s", $obk_idSpace));
		$results_bookings = $wpdb->get_results($wpdb->prepare("SELECT nb_de_place,date_arrivee,date_depart FROM {$wpdb->prefix}obk_bookings WHERE obk_idSpace = %s AND date_depart >= %s AND date_arrivee <= %s AND statut <> 'canceled'", $obk_idSpace, $date_debut_recherche, $date_fin_recherche));
		$results_discount = $wpdb->get_results($wpdb->prepare("SELECT mp.* FROM {$wpdb->prefix}obk_modifyprice_spaces mpe LEFT JOIN {$wpdb->prefix}obk_modifyprice mp ON mpe.id_modificationprix = mp.id WHERE mpe.obk_idSpace=%s AND mp.date_debut <= %s AND mp.date_fin >= %s AND (mp.quantite > 0 OR mp.quantite_initiale = 0) ORDER BY mp.type", $obk_idSpace,substr($date_debut_recherche,0,10),substr($date_fin_recherche,0,10)));
		$dates = array();
		$dateParcours = $date_debut_recherche;
		while($dateParcours <= $date_fin_recherche){
			$ts_date_parcours = mktime((int)substr($dateParcours,11,2),(int)substr($dateParcours,14,2),(int)substr($dateParcours,17,2),(int)substr($dateParcours,5,2),(int)substr($dateParcours,8,2),(int)substr($dateParcours,0,4));
			$numDay = getdate($ts_date_parcours)['wday'];
			$labelDay = $numDay;
			$dateDay = (int)substr($dateParcours,8,2);
			$status = 'ok';
			$OT = json_decode(stripslashes($row_space->openingtimes));
			$OTDay = $OT[$numDay][0];
			if (($row_space->date_debut_reservation > $dateParcours) 
			|| ($row_space->date_fin_reservation.' 23:59:59' < $dateParcours)
			|| ($OTDay[0] == '0')
			){
				$status = 'c';
			}
			$planningMinutes = array_fill(0,1440,[$row_space->nb_de_place+0,$status]);
			if ($status == 'ok'){
				$depart = 0;	
				for($i = 1; $i < sizeof($OTDay); $i++){
					$debutMinutes = (int)substr($OTDay[$i][0],0,2) * 60 + (int)substr($OTDay[$i][0],3,2);
					$finMinutes = (int)substr($OTDay[$i][1],0,2) * 60 + (int)substr($OTDay[$i][1],3,2);
					if ($finMinutes == 0){$finMinutes = 1439;}
					for($j = $depart; $j < $debutMinutes; $j++){
						$planningMinutes[$j][1] = 'c';
					}
					$depart = $finMinutes;
				}
				if ($depart < 1440){
					for($i = $depart; $i < 1440; $i++){
						$planningMinutes[$i][1] = 'c';
					}
				}
				$DepartureDay = $OT[$numDay][2];
				if ($DepartureDay[0] != '0'){ 
					for($i = 1; $i < sizeof($DepartureDay); $i++){
						$debutMinutes = (int)substr($DepartureDay[$i][0],0,2) * 60 + (int)substr($DepartureDay[$i][0],3,2);
						$finMinutes = (int)substr($DepartureDay[$i][1],0,2) * 60 + (int)substr($DepartureDay[$i][1],3,2);
						if ($finMinutes == 0){$finMinutes = 1439;}
						for($j = $debutMinutes; $j < $finMinutes; $j++){
							if ($planningMinutes[$j][1] != 'c'){
								$planningMinutes[$j][1] = 'd';
							}
						}
					}
				}
				$ArrivalDay = $OT[$numDay][1];
				if ($ArrivalDay[0] != '0'){ 
					for($i = 1; $i < sizeof($ArrivalDay); $i++){
						$debutMinutes = (int)substr($ArrivalDay[$i][0],0,2) * 60 + (int)substr($ArrivalDay[$i][0],3,2);
						$finMinutes = (int)substr($ArrivalDay[$i][1],0,2) * 60 + (int)substr($ArrivalDay[$i][1],3,2);
						if ($finMinutes == 0){$finMinutes = 1439;}
						for($j = $debutMinutes; $j < $finMinutes; $j++){
							if ($planningMinutes[$j][1] != 'c'){
								$planningMinutes[$j][1] = 'a';
							}
						}
					}
				}
			}
			$dates[$numDay] = array("timeUnit" => $row_space->timeUnit, "nb_de_place" => $row_space->nb_de_place+0, "labelDay" => $labelDay, "dateDay" => $dateDay, "planning" => $planningMinutes, "date" => substr($dateParcours,0,10));
			$dateParcours = date("Y-m-d H:i:s",$ts_date_parcours + 24 * 3600);
		}
		foreach ($results_bookings as $reservation) {
			$dateParcours = $reservation->date_arrivee;
			$dateParcoursFin = date('Y-m-d H:i:s', strtotime($reservation->date_depart));
			while (($dateParcours <= $dateParcoursFin) && ($dateParcours <= $date_fin_recherche)){
				$ts_date_parcours = mktime((int)substr($dateParcours,11,2),(int)substr($dateParcours,14,2),(int)substr($dateParcours,17,2),(int)substr($dateParcours,5,2),(int)substr($dateParcours,8,2),(int)substr($dateParcours,0,4));
				if ($dateParcours >= $date_debut_recherche){
					$numDay = getdate($ts_date_parcours)["wday"];
					$debutMinutes = (int)substr($dateParcours,11,2) * 60 + (int)substr($dateParcours,14,2);
					$finMinutes = 1440;
					if (substr($dateParcours,0,10) == substr($dateParcoursFin,0,10)){
						$finMinutes = (int)substr($dateParcoursFin,11,2) * 60 + (int)substr($dateParcoursFin,14,2);
					}
					for($i = $debutMinutes; $i < $finMinutes; $i++){
						$dates[$numDay]["planning"][$i][0] -= $reservation->nb_de_place+0;
					}
				}
				$dateParcours = date("Y-m-d",$ts_date_parcours + 24 * 3600);
				$dateParcours .= " 00:00:00";
			}
		}
		$tabClosure = explode('--o--',$row_space->exceptionalclosure);
		foreach($tabClosure as $closure){
			$close = explode(';',$closure);
			if (isset($close[1])){
				$dateParcours = $close[0];
				$dateParcoursFin = $close[1];
				while (($dateParcours <= $dateParcoursFin) && ($dateParcours <= $date_fin_recherche)){
					$ts_date_parcours = mktime((int)substr($dateParcours,11,2),(int)substr($dateParcours,14,2),(int)substr($dateParcours,17,2),(int)substr($dateParcours,5,2),(int)substr($dateParcours,8,2),(int)substr($dateParcours,0,4));
					if (($dateParcours >= $date_debut_recherche) && ($dateParcours <= $date_fin_recherche)){
						$numDay = getdate($ts_date_parcours)["wday"];
						$debutMinutes = (int)substr($dateParcours,11,2) * 60 + (int)substr($dateParcours,14,2);
						$finMinutes = 1440;
						if (substr($dateParcours,0,10) == substr($dateParcoursFin,0,10)){
							$finMinutes = (int)substr($dateParcoursFin,11,2) * 60 + (int)substr($dateParcoursFin,14,2);
						}
						for($i = $debutMinutes; $i < $finMinutes; $i++){
							$dates[$numDay]["planning"][$i][1] = 'c';
						}
					}
					$dateParcours = date("Y-m-d",$ts_date_parcours + 24 * 3600);
					$dateParcours .= " 00:00:00";
				}
			}
		}
		$debut = -1;
		$duree = 0;
		foreach ($dates as $numDay => $date){
			foreach ($date['planning'] as $index => $plan){
				if (($debut == -1)&&($plan[0] > 0)){
					$debut = $index;
				}
				if ($plan[0] > 0){
					$duree++;
				}
				if ((($plan[0] == 0) && ($debut != -1))){
					if ($duree < ($row_space->minBookingDuration * 60)){
						for($i = $debut;$i <= ($debut + $duree);$i++){
							$dates[$numDay]['planning'][$i][0] = 0;
						}
					}
					$debut = -1;
					$duree = 0;
				}
			}
		}
		$ok = false;
		foreach ($dates as $date){
			foreach ($date['planning'] as $plan){
				if (($plan[0] > 0) && ($plan[1] != 'c')){
					$ok = true;
					break(2);
				}
			}
		}
	}
	

	if (($ok) || ($try < 1) || ($init == "false")){
		if (current_user_can('administrator')){
			$sqlVar = [];
			$sqlWhere = '';
			if (isset($SAFE_DATA['filterDepartureDate'])){
				$date_debut_recherche = $SAFE_DATA['filterDepartureDate'] . ' 00:00:00';
				$date_fin_recherche = $SAFE_DATA['filterArrivalDate'] . ' 23:59:59';
				$status = $SAFE_DATA['filterBookingStatus'];
				if ($status != ''){
					if ($sqlWhere != ''){$sqlWhere .= ' AND ';}
					$sqlWhere .= 'res.statut = %s';
					$sqlVar[] = $status;
				}
			}
			$sqlVar[] = $date_debut_recherche;
			$sqlVar[] = $date_fin_recherche;
			if ($sqlWhere != ''){$sqlWhere .= ' AND ';}
			$sqlWhere .= 'res.date_depart >= %s AND res.date_arrivee <= %s';
					
			if ($obk_idSpace != -1){
				if ($sqlWhere != ''){$sqlWhere .= ' AND ';}
				$sqlWhere .= 'res.obk_idSpace = %s';
				$sqlVar[] = $obk_idSpace;
			}
			$resultats = $wpdb->get_results($wpdb->prepare("SELECT res.id, res.nom, res.date, res.emplacement, res.lieu as localisation, lieu.id as id_lieu, res.date_arrivee, res.nb_de_place, res.date_depart, res.nb_de_personnes, res.statut, res.telephone, res.remarques, res.reference_interne FROM {$wpdb->prefix}obk_bookings res LEFT JOIN {$wpdb->prefix}obk_spaces emp ON res.obk_idSpace = emp.id LEFT JOIN {$wpdb->prefix}obk_locations lieu ON emp.id_lieu = lieu.id WHERE $sqlWhere ORDER BY res.date_arrivee", $sqlVar));
			$listing = array_fill(1,obk_cal_days_in_month($month,$year),0);
			foreach ($resultats as $reservation) {			
				$dateParcours = $reservation->date_arrivee;
				$dateParcoursFin = date('Y-m-d', strtotime($reservation->date_depart . ' +1 day'));
				while (($dateParcours <= $dateParcoursFin)){
					if ((substr($dateParcours,5,2) == $month) && (substr($dateParcours,0,4) == $year)){
						$day = (int)substr($dateParcours,8,2);
						$listing[$day] += (int)$reservation->nb_de_place;
					}
					$ts_date_parcours = mktime(substr($dateParcours,11,2),substr($dateParcours,14,2),0,substr($dateParcours,5,2),substr($dateParcours,8,2),substr($dateParcours,0,4));
					$dateParcours = date("Y-m-d H:i:s",$ts_date_parcours + 24 * 3600);
				}
			}
			$echo = '<table class="obk_widefat"><thead><tr><th>#</th><th class="obk_column-primary">'.__('Name', 'oui-booking').'</th><th>'.__('Arrival', 'oui-booking').'<span class="dashicons dashicons-arrow-up"></span></th><th>'.__('Departure', 'oui-booking').'</th><th>'.__('Status', 'oui-booking').'</th>';
			if ($obk_idSpace == -1){
				$echo .= '<th>'.__('Place', 'oui-booking').'</th>';
			}		
			$echo .= '<th>'.__('Qty', 'oui-booking').'</th><th>'.__('People', 'oui-booking').'</th><th>'.__('Ph.', 'oui-booking').'.</th></tr></thead>';
			foreach ($resultats as $reservation) {
				$echo .= '<tr><td>#'.$reservation->id;
				if ($reservation->reference_interne != ''){
					$echo .= ' - '.$reservation->reference_interne;
				}
				$echo .= '</td><td class="obk_column-primary"><form method="post" action=""><input type="hidden" name="obk_act" value="displayBooking"><input type="hidden" name="id_reservation" value="'.$reservation->id.'">';
				$echo .= obk_get_wp_nonce_field('displayBooking');
				if ($obk_idSpace != -1){
					$echo .= '
					<input type="hidden" name="obk_prevAct" value="displaySpace">
					<input type="hidden" name="id_lieu" value="'.$reservation->id_lieu.'">
					<input type="hidden" name="obk_idSpace" value="'.$obk_idSpace.'">
					<input type="hidden" name="calendarCurrentYearMonth" value="'.$year.'-'.$month.'">';
				}
				$echo .= get_submit_button(__('Mrs/Mr.', 'oui-booking').' '.ucfirst(obk_removeslashes($reservation->nom))).'</form></td><td><input type="date" disabled value="'.substr($reservation->date_arrivee,0,10).'"><input type="time" disabled value="'.substr($reservation->date_arrivee,11,5).'"></td><td><input type="date" disabled value="'.substr($reservation->date_depart,0,10).'"><input type="time" disabled value="'.substr($reservation->date_depart,11,5).'"></td><td>'.obk_getBookingStatus($reservation->statut).'</td>';
				if ($obk_idSpace == -1){
					$echo .= '<td>'.ucfirst(obk_removeslashes($reservation->localisation)).', '.ucfirst(obk_removeslashes($reservation->emplacement)).'</td>';
				}
				$echo .= '<td>'.($reservation->nb_de_place+0).'</td><td>'.$reservation->nb_de_personnes.'</td><td>'.$reservation->telephone.'</td></tr>';
			}
			$echo .= '</table>';
			$dates['echo'] = $echo;
		}	
		echo json_encode($dates);
		wp_die();
	}else{
		$CurrentDate = date("Y-m-d",strtotime($CurrentDate . "+ 7 day"));
		js_chargeCalendrier($CurrentDate, $try-1);
	}
}

function js_saveBooking(){
	global $wpdb;
	global $SAFE_DATA;
	/*if (!current_user_can('administrator')){return;}*/
	if (empty($SAFE_DATA)){
		$isDataFormSafe = obk_secureDataForm();
		if (!$isDataFormSafe[0]){
			error_log('DEBUG18:------------------- '. $isDataFormSafe[1]);
		}else{
			$SAFE_DATA = $isDataFormSafe[2];
		}
	}
	if (isset($SAFE_DATA['action'])){
		$action = str_replace('js_','',$SAFE_DATA['action']);
		check_admin_referer($action);
	}
	$obk_idSpace = $SAFE_DATA['obk_idSpace'];
	$bookingContent = json_decode(obk_checkBookingFormPost($obk_idSpace, true, false, true));
	$msg = $bookingContent->msg;
	if ($msg != ''){
		echo JSON_encode(array(false,$msg,0));
	}else{
		$style = obk_getCSS(true);
		$content = '<!doctype html><html><head>'.$style.'</head><body>'.$bookingContent->previsualisation.'</body></html>';
		$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
		$content = str_replace(' EUR<',' €<',$content);
		if ((isset($SAFE_DATA['form_email']))&&(obk_isNotEmpty($SAFE_DATA['form_email']))){
			obk_email($SAFE_DATA['form_email'],__('Booking confirmation', 'oui-booking'),$content);
		}
		if ($bookingContent->rowEmp->notification_email == '1'){
			$emailDest = get_option('admin_email');
			if (trim($bookingContent->rowEmp->email_notification) != ''){
				$emailDest = trim($bookingContent->rowEmp->email_notification);
			}
			obk_email($emailDest,__('New booking', 'oui-booking'),$content);
		}
		echo JSON_encode(array(true,__('Booking has been saved', 'oui-booking'),$bookingContent->id_reservation));
	}
	wp_die();
}