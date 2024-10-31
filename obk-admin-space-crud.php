<?php
defined( 'ABSPATH' ) or die();
if (!current_user_can('manage_options')){return;}
if (is_admin() !== true) {return;}

if ($obk_act == 'insertSpace') {
	if (($SAFE_DATA['nouveau_lieu'] != '') && ($SAFE_DATA['nouveau_emplacement'] != '')){
		$row = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$wpdb->prefix}obk_locations WHERE nom = %s", $SAFE_DATA['nouveau_lieu']));
		if (is_null($row)) {
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_locations (nom) VALUES (%s)", ucfirst($SAFE_DATA['nouveau_lieu'])));
			$id_lieu = $wpdb->insert_id;
		}else{
			$id_lieu = $row->id;
		}
		$date_debut_reservation = date('Y-m-d');
		$date_fin_reservation = date('Y-m-d', strtotime('+10 year'));
		$defaultOpeningTimes = obk_getDefaultOpeningTimes();
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_spaces (label, id_lieu,form_date_debut,form_heure_debut,form_date_fin,form_heure_fin,form_personnes,form_nom,form_prenom,form_adresse,form_code_postal,form_ville,form_pays,form_email,form_telephone,form_remarques,form_nb_de_place,info_date_debut_reservation,info_date_fin_reservation,info_prix_de_la_place,info_acompte_prix,info_acompte_pourcentage,info_timeUnit,info_tps_reservation_max_heure,info_description,info_calendrier,date_debut_reservation,date_fin_reservation,statut_par_defaut_reservation,notification_email,user_minutes_interval,devise,tps_reservation_max_heure,timeUnit,nb_de_place,openingtimes,minBookingDuration,info_minBookingDuration) VALUES (%s,%d,'2','2','2','2','2','2','2','2','2','2','2','2','2','1','2','1','0','1','1','1','1','1','1','1',%s,%s,'validationinprogress','1','15','EUR','24','1','1',%s,'1','1')", ucfirst($SAFE_DATA['nouveau_emplacement']),$id_lieu,$date_debut_reservation,$date_fin_reservation,json_encode($defaultOpeningTimes)));
		obk_addAdminNotice(__('The place has been added', 'oui-booking'),1);
		$obk_act = 'displaySpace';
		$SAFE_DATA['obk_idSpace'] = $wpdb->insert_id;
	}
}

if ($obk_act == 'updateSpace') {
	(isset($SAFE_DATA['dayprice']))? $SAFE_DATA['dayprice'] = implode('',$SAFE_DATA['dayprice']) : $SAFE_DATA['dayprice'] = '';
	$SAFE_DATA['dayprice'] .= '--o--'.$SAFE_DATA['dayprice7'].';'.$SAFE_DATA['dayprice1'].';'.$SAFE_DATA['dayprice2'].';'.$SAFE_DATA['dayprice3'].';'.$SAFE_DATA['dayprice4'].';'.$SAFE_DATA['dayprice5'].';'.$SAFE_DATA['dayprice6'].'--o--';
	if (isset($SAFE_DATA['daypriceignoreperiods'])){$SAFE_DATA['dayprice'] .=  implode($SAFE_DATA['daypriceignoreperiods']);}
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET id_lieu=%d,label=%s,nb_de_place=%s,prix_de_la_place=%s,devise=%s,timeUnit=%s,tps_reservation_max_heure=%s,acompte_prix=%s,acompte_pourcentage=%s,lien_CGU=%s,date_debut_reservation=%s,date_fin_reservation=%s,description=%s,statut_par_defaut_reservation=%s,notification_email=%d,email_notification=%s,payment_instructions=%s,user_minutes_interval=%s,dayprice=%s,openingtimes=%s,minBookingDuration=%s WHERE id=%d",$SAFE_DATA['id_lieu'],$SAFE_DATA['label'],$SAFE_DATA['nb_de_place'],$SAFE_DATA['prix_de_la_place'],$SAFE_DATA['devise'],$SAFE_DATA['timeUnit'],$SAFE_DATA['tps_reservation_max_heure'],$SAFE_DATA['acompte_prix'],$SAFE_DATA['acompte_pourcentage'],$SAFE_DATA['lien_CGU'],$SAFE_DATA['date_debut_reservation'],$SAFE_DATA['date_fin_reservation'],$SAFE_DATA['description'],$SAFE_DATA['statut_par_defaut_reservation'],$SAFE_DATA['notification_email'],$SAFE_DATA['email_notification'],$SAFE_DATA['payment_instructions'],$SAFE_DATA['user_minutes_interval'],$SAFE_DATA['dayprice'],$SAFE_DATA['openingtimes'],$SAFE_DATA['minBookingDuration'],$SAFE_DATA['obk_idSpace']));
	obk_addAdminNotice(__('The place has been updated', 'oui-booking'),2);
	$obk_act = 'displaySpace';
}

if ($obk_act == 'updateInfoShowSpace') {
	(isset($SAFE_DATA['show_form_date_debut']))? $form_date_debut = 1 : $form_date_debut = 0;
	(isset($SAFE_DATA['show_form_date_debut_obligatoire']) && ($form_date_debut == 1))? $form_date_debut = 2 : '';
	(isset($SAFE_DATA['show_form_heure_debut']))? $form_heure_debut = 1 : $form_heure_debut = 0;
	(isset($SAFE_DATA['show_form_heure_debut_obligatoire']) && ($form_heure_debut == 1))? $form_heure_debut = 2 : '';
	(isset($SAFE_DATA['show_form_date_fin']))? $form_date_fin = 1 : $form_date_fin = 0;
	(isset($SAFE_DATA['show_form_date_fin_obligatoire']) && ($form_date_fin == 1))? $form_date_fin = 2 : '';
	(isset($SAFE_DATA['show_form_heure_fin']))? $form_heure_fin = 1 : $form_heure_fin = 0;
	(isset($SAFE_DATA['show_form_heure_fin_obligatoire']) && ($form_heure_fin == 1))? $form_heure_fin = 2 : '';
	(isset($SAFE_DATA['show_form_personnes']))? $form_personnes = 1 : $form_personnes = 0;
	(isset($SAFE_DATA['show_form_personnes_obligatoire']) && ($form_personnes == 1))? $form_personnes = 2 : '';
	(isset($SAFE_DATA['show_form_nom']))? $form_nom = 1 : $form_nom = 0;
	(isset($SAFE_DATA['show_form_nom_obligatoire']) && ($form_nom == 1))? $form_nom = 2 : '';
	(isset($SAFE_DATA['show_form_prenom']))? $form_prenom = 1 : $form_prenom = 0;
	(isset($SAFE_DATA['show_form_prenom_obligatoire']) && ($form_prenom == 1))? $form_prenom = 2 : '';
	(isset($SAFE_DATA['show_form_adresse']))? $form_adresse = 1 : $form_adresse = 0;
	(isset($SAFE_DATA['show_form_adresse_obligatoire']) && ($form_adresse == 1))? $form_adresse = 2 : '';
	(isset($SAFE_DATA['show_form_code_postal']))? $form_code_postal = 1 : $form_code_postal = 0;
	(isset($SAFE_DATA['show_form_code_postal_obligatoire']) && ($form_code_postal == 1))? $form_code_postal = 2 : '';
	(isset($SAFE_DATA['show_form_ville']))? $form_ville = 1 : $form_ville = 0;
	(isset($SAFE_DATA['show_form_ville_obligatoire']) && ($form_ville == 1))? $form_ville = 2 : '';
	(isset($SAFE_DATA['show_form_pays']))? $form_pays = 1 : $form_pays = 0;
	(isset($SAFE_DATA['show_form_pays_obligatoire']) && ($form_pays == 1))? $form_pays = 2 : '';
	(isset($SAFE_DATA['show_form_email']))? $form_email = 1 : $form_email = 0;
	(isset($SAFE_DATA['show_form_email_obligatoire']) && ($form_email == 1))? $form_email = 2 : '';
	(isset($SAFE_DATA['show_form_telephone']))? $form_telephone = 1 : $form_telephone = 0;
	(isset($SAFE_DATA['show_form_telephone_obligatoire']) && ($form_telephone == 1))? $form_telephone = 2 : '';
	(isset($SAFE_DATA['show_form_remarques']))? $form_remarques = 1 : $form_remarques = 0;
	(isset($SAFE_DATA['show_form_remarques_obligatoire']) && ($form_remarques == 1))? $form_remarques = 2 : '';
	(isset($SAFE_DATA['show_form_nb_de_place']))? $form_nb_de_place = 1 : $form_nb_de_place = 0;
	(isset($SAFE_DATA['show_form_nb_de_place_obligatoire']) && ($form_nb_de_place == 1))? $form_nb_de_place = 2 : '';
	(isset($SAFE_DATA['info_date_debut_reservation']))? $info_date_debut_reservation = 1 : $info_date_debut_reservation = 0;
	(isset($SAFE_DATA['info_date_debut_reservation_obligatoire']) && ($info_date_debut_reservation == 1))? $info_date_debut_reservation = 2 : '';
	(isset($SAFE_DATA['info_date_fin_reservation']))? $info_date_fin_reservation = 1 : $info_date_fin_reservation = 0;
	(isset($SAFE_DATA['info_date_fin_reservation_obligatoire']) && ($info_date_fin_reservation == 1))? $info_date_fin_reservation = 2 : '';
	(isset($SAFE_DATA['info_prix_de_la_place']))? $info_prix_de_la_place = 1 : $info_prix_de_la_place = 0;
	(isset($SAFE_DATA['info_prix_de_la_place_obligatoire']) && ($info_prix_de_la_place == 1))? $info_prix_de_la_place = 2 : '';
	(isset($SAFE_DATA['info_acompte_prix']))? $info_acompte_prix = 1 : $info_acompte_prix = 0;
	(isset($SAFE_DATA['info_acompte_prix_obligatoire']) && ($info_acompte_prix == 1))? $info_acompte_prix = 2 : '';
	(isset($SAFE_DATA['info_acompte_pourcentage']))? $info_acompte_pourcentage = 1 : $info_acompte_pourcentage = 0;
	(isset($SAFE_DATA['info_acompte_pourcentage_obligatoire']) && ($info_acompte_pourcentage == 1))? $info_acompte_pourcentage = 2 : '';
	(isset($SAFE_DATA['info_timeUnit']))? $info_timeUnit = 1 : $info_timeUnit = 0;
	(isset($SAFE_DATA['info_timeUnit_obligatoire']) && ($info_timeUnit == 1))? $info_timeUnit = 2 : '';
	(isset($SAFE_DATA['info_minBookingDuration']))? $info_minBookingDuration = 1 : $info_minBookingDuration = 0;
	(isset($SAFE_DATA['info_minBookingDuration_obligatoire']) && ($info_minBookingDuration == 1))? $info_minBookingDuration = 2 : '';
	(isset($SAFE_DATA['info_tps_reservation_max_heure']))? $info_tps_reservation_max_heure = 1 : $info_tps_reservation_max_heure = 0;
	(isset($SAFE_DATA['info_tps_reservation_max_heure_obligatoire']) && ($info_tps_reservation_max_heure == 1))? $info_tps_reservation_max_heure = 2 : '';
	(isset($SAFE_DATA['info_description']))? $info_description = 1 : $info_description = 0;
	(isset($SAFE_DATA['info_description_obligatoire']) && ($info_description == 1))? $info_description = 2 : '';
	(isset($SAFE_DATA['info_calendrier']))? $info_calendrier = 1 : $info_calendrier = 0;
	(isset($SAFE_DATA['info_calendrier_obligatoire']) && ($info_calendrier == 1))? $info_calendrier = 2 : '';
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_spaces SET form_date_debut=%s,form_heure_debut=%s,form_date_fin=%s,form_heure_fin=%s,form_personnes=%s,form_nom=%s,form_prenom=%s,form_adresse=%s,form_code_postal=%s,form_ville=%s,form_pays=%s,form_email=%s,form_telephone=%s,form_remarques=%s,form_nb_de_place=%s,info_date_debut_reservation=%s,info_date_fin_reservation=%s,info_prix_de_la_place=%s,info_acompte_prix=%s,info_acompte_pourcentage=%s,info_timeUnit=%s,info_tps_reservation_max_heure=%s,info_description=%s,info_calendrier=%s,info_minBookingDuration=%s WHERE id=%d",$form_date_debut,$form_heure_debut,$form_date_fin,$form_heure_fin,$form_personnes,$form_nom,$form_prenom,$form_adresse,$form_code_postal,$form_ville,$form_pays,$form_email,$form_telephone,$form_remarques,$form_nb_de_place,$info_date_debut_reservation,$info_date_fin_reservation,$info_prix_de_la_place,$info_acompte_prix,$info_acompte_pourcentage,$info_timeUnit,$info_tps_reservation_max_heure,$info_description,$info_calendrier,$info_minBookingDuration,$SAFE_DATA['obk_idSpace'] ));
	obk_addAdminNotice(__('The information to be displayed has been updated', 'oui-booking'),2);
	$obk_act = 'displaySpace';
}

if ($obk_act == 'deleteSpace') {
	$row = $wpdb->get_row($wpdb->prepare("SELECT lieu.nom, emp.label FROM {$wpdb->prefix}obk_spaces emp LEFT JOIN {$wpdb->prefix}obk_locations lieu ON emp.id_lieu = lieu.id WHERE emp.id=%d",$SAFE_DATA['obk_idSpace']));
	$rappel = $row->nom . ' -- '. $row->label . ' ('.__('The place has been removed', 'oui-booking').') -- ';
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_bookings SET remarques = CONCAT(%s,' - ',remarques) WHERE obk_idSpace=%d",$rappel,$SAFE_DATA['obk_idSpace']));
	$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}obk_spaces WHERE id=%d",$SAFE_DATA['obk_idSpace']));
	obk_addAdminNotice(__('The place has been removed', 'oui-booking'),1);
	$obk_act = '';
}
