<?php
defined( 'ABSPATH' ) or die();
$previsualisation = '';
if ($ok){
	$nb_de_place = $SAFE_DATA['nb_de_place'];
	(isset($SAFE_DATA['acompte_prix']))? $acompte_prix = $SAFE_DATA['acompte_prix'] : $acompte_prix = 0;
	(isset($SAFE_DATA['acompte_pourcentage']))? $acompte_pourcentage = $SAFE_DATA['acompte_pourcentage'] : $acompte_pourcentage = 0;
	(isset($SAFE_DATA['form_date_debut']))? $date_arrivee = $SAFE_DATA['form_date_debut']." ".$SAFE_DATA["form_heure_debut"].':00' : $date_arrivee = "";
	if ((isset($SAFE_DATA['form_heure_fin'])) && ($SAFE_DATA['form_heure_fin'] == '24:00')){
		if (isset($SAFE_DATA['form_date_fin'])){
			$SAFE_DATA['form_heure_fin'] = '00:00';
			$SAFE_DATA["form_date_fin"] = date('Y-m-d', strtotime($SAFE_DATA["form_date_fin"] . ' +1 day'));
		}else{
			$SAFE_DATA['form_heure_fin'] = '23:59';
		}
	}
	(isset($SAFE_DATA['form_date_fin']))? $date_depart = $SAFE_DATA['form_date_fin']." ".$SAFE_DATA["form_heure_fin"].':00' : $date_depart = "";
	(isset($SAFE_DATA['form_personnes']))? $nb_de_personnes = $SAFE_DATA['form_personnes'] : $nb_de_personnes = 0;
	(isset($SAFE_DATA['form_nom']))? $nom = $SAFE_DATA['form_nom'] : $nom = "";
	(isset($SAFE_DATA['form_prenom']))? $prenom = $SAFE_DATA['form_prenom'] : $prenom = "";
	(isset($SAFE_DATA['form_adresse']))? $adresse = $SAFE_DATA['form_adresse'] : $adresse = "";
	(isset($SAFE_DATA['form_code_postal']))? $code_postal = $SAFE_DATA['form_code_postal'] : $code_postal = "";
	(isset($SAFE_DATA['form_ville']))? $ville = $SAFE_DATA['form_ville'] : $ville = "";
	(isset($SAFE_DATA['form_pays']))? $pays = $SAFE_DATA['form_pays'] : $pays = "";
	(isset($SAFE_DATA['form_email']))? $email = $SAFE_DATA['form_email'] : $email = "";
	(isset($SAFE_DATA['form_telephone']))? $telephone = $SAFE_DATA['form_telephone'] : $telephone = "";
	(isset($SAFE_DATA['form_remarques']))? $remarques = $SAFE_DATA['form_remarques'] : $remarques = "";
	if ($majBdd){
		$date = date("Y-m-d H:i:s");
		$obk_idSpace = $SAFE_DATA['obk_idSpace'];
		$statut = $rowEmp->statut_par_defaut_reservation;
		$prix_de_la_place = $rowEmp->prix_de_la_place;
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_bookings (obk_idSpace,date,nb_de_place,prix_de_la_place,acompte_prix,acompte_pourcentage,date_arrivee,date_depart,nb_de_personnes,nom,prenom,adresse,code_postal,ville,pays,email,telephone,remarques,statut,emplacement,lieu,devise,timeUnit,periodesprices,dayprice) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%s,%s)",$obk_idSpace,$date,$nb_de_place,$prix_de_la_place,$acompte_prix,$acompte_pourcentage,$date_arrivee,$date_depart,$nb_de_personnes,$nom,$prenom,$adresse,$code_postal,$ville,$pays,$email,$telephone,$remarques,$statut,$rowEmp->label,$rowEmp->localisation,$rowEmp->devise,$rowEmp->timeUnit,$rowEmp->periodesprices,$rowEmp->dayprice));
		$id_reservation = $wpdb->insert_id;
		$previsualisation = '<div id="obk_mainDiv"><h2>'.__('Booking', 'oui-booking').' #'.$id_reservation.'</h2>';
	}else{
		$previsualisation = '<div id="obk_mainDiv"><h2>'.__('Overview of the booking', 'oui-booking').'</h2>';
	}
	if ((obk_isNotEmpty($nom))||(obk_isNotEmpty($prenom))||(obk_isNotEmpty($adresse))||(obk_isNotEmpty($code_postal))||(obk_isNotEmpty($ville))||(obk_isNotEmpty($pays))||(obk_isNotEmpty($email))||(obk_isNotEmpty($telephone))){
		$previsualisation .= '<div class="obk_previewBox"><div class="obk_previewHead">'.__('Your contact details', 'oui-booking').'</div><div class="obk_previewContent">';
		if ((obk_isNotEmpty($nom))||(obk_isNotEmpty($prenom))){
			if (obk_isNotEmpty($nom)){$previsualisation .= obk_removeslashes($nom);}
			if (obk_isNotEmpty($prenom)){$previsualisation .= " ".obk_removeslashes($prenom);}
			$previsualisation .= '<br>';
		}
		if (obk_isNotEmpty($adresse)){$previsualisation .= obk_removeslashes($adresse).'<br>';}
		if ((obk_isNotEmpty($code_postal))||(obk_isNotEmpty($ville))){
			if (obk_isNotEmpty($code_postal)){$previsualisation .= obk_removeslashes($code_postal);}
			if (obk_isNotEmpty($ville)){$previsualisation .= " ".obk_removeslashes($ville);}
			$previsualisation .= '<br>';
		}
		if (obk_isNotEmpty($pays)){$previsualisation .= " ".obk_removeslashes($pays).'<br>';}
		if ((obk_isNotEmpty($telephone))||(obk_isNotEmpty($email))){
			//$previsualisation .= '<br>';
			if (obk_isNotEmpty($telephone)){$previsualisation .= __('Tel.', 'oui-booking').': '.obk_removeslashes($telephone);}
			if (obk_isNotEmpty($email)){$previsualisation .= '<br>'.__('Email', 'oui-booking').': '.obk_removeslashes($email);}
			$previsualisation .= '<br>';
		}
		$previsualisation .= '</div></div>';
	}
	if ($forEmail){
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Place', 'oui-booking').': '.obk_removeslashes($rowEmp->label).' - '.obk_removeslashes($rowEmp->localisation).'</div>
							<div class="obk_previewContent">';
		
		$ts_date_debut = obk_getTimeStamp($SAFE_DATA["form_date_debut"]." ".$SAFE_DATA["form_heure_debut"]);
		$form_date_debut = date("d/m/Y H:i", $ts_date_debut);
		$ts_date_fin = obk_getTimeStamp($SAFE_DATA["form_date_fin"]." ".$SAFE_DATA["form_heure_fin"]);
		$form_date_fin = date("d/m/Y H:i", $ts_date_fin);
		$previsualisation .= '<div class="obk_previewResume">'.__('Booking date', 'oui-booking').': '.$form_date_debut.' '.__('until', 'oui-booking').' '.$form_date_fin.'</div>';
		
		//$previsualisation .= '<div class="obk_previewResume">'.__('Booking date', 'oui-booking').': '.$SAFE_DATA["form_date_debut"].' '.__('until', 'oui-booking').' '.$SAFE_DATA["form_date_fin"].'</div>';
	}else{
		$form_heure_fin_aff = $SAFE_DATA["form_heure_fin"];
		//if ($form_heure_fin_aff == "24:00"){$form_heure_fin_aff = "23:59";}
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Place', 'oui-booking').': '.obk_removeslashes($rowEmp->label).' - '.obk_removeslashes($rowEmp->localisation).'</div>
							<div class="obk_previewContent">
								<div class="obk_previewResume"><div>'.__('Booking date', 'oui-booking').': <div class="obk_flex"><input type="date" disabled value="'.$SAFE_DATA["form_date_debut"].'"><input type="time" disabled value="'.$SAFE_DATA["form_heure_debut"].'"></div></div><div>'.__('until', 'oui-booking');
		$previsualisation .= ' <div class="obk_flex"><input type="date" disabled value="'.$SAFE_DATA["form_date_fin"].'"><input type="time" disabled value="'.$form_heure_fin_aff.'"></div></div></div>';
	}
	$date_depart = date("Y-m-d H:i:s",strtotime($date_depart . " -1 second"));
	$prixtotal = obk_getSpaceTotalPrice($date_arrivee,$date_depart,$rowEmp->periodesprices,$rowEmp->prix_de_la_place,$SAFE_DATA['nb_de_place'],$rowEmp->timeUnit,$rowEmp->dayprice);
	$previsualisation .= '<div class="obk_previewRow"><div>'.__('Number of places', 'oui-booking').':&nbsp;</div><div>'. $SAFE_DATA['nb_de_place'].'</div></div>';
	if ($duree_reservation_heures > 0){
		$previsualisation .= '<div class="obk_previewRow"><div>'.__('Duration of the booking', 'oui-booking').':&nbsp;</div><div>'. obk_heure2duree($duree_reservation_heures) . '</div></div>';
	}
	$previsualisation .= '<div class="obk_previewRow obk_total"><div>'.__('Total without taxes', 'oui-booking').':&nbsp;</div><div>';
	$previsualisation .= '<span id="obk_totalWT">' . sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixtotal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise.'</span>'.obk_displayPrice($prixtotal, $rowEmp->devise, 'obk_totalWT').'</div></div>';
	$previsualisation .= '</div></div>';
	$prixoriginal = $prixtotal;
	$previsuOption = '';
	$totalAllOptions = 0;
	foreach($resultatsMP as $mp){
		if ($mp->type == 'option'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					$finParcours = $SAFE_DATA['form_date_fin'];
					$originalQty = 0;
					if (isset($SAFE_DATA['form_option_'.$id_mp])){
						$originalQty = $SAFE_DATA['form_option_'.$id_mp];
					}
					$SAFE_DATA['form_option_'.$id_mp] = obk_getAutomaticQty($mp->code,$originalQty,$SAFE_DATA['form_date_debut'], $finParcours, $SAFE_DATA['nb_de_place']);
					if ((isset($SAFE_DATA['form_option_'.$id_mp]))&&($SAFE_DATA['form_option_'.$id_mp] > 0)){
						if (!isset($SAFE_DATA['form_option_details_texte_'.$id_mp])){
							$SAFE_DATA['form_option_details_texte_'.$id_mp] = '';
						}
						if ($majBdd){
							$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s)",$id_reservation,$mp->label,$mp->description,$SAFE_DATA['form_option_'.$id_mp],$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type,$SAFE_DATA['form_option_details_texte_'.$id_mp]));
						}
						$heure_restante = $duree_reservation_heures;
						$totalHeureOption = 0;
						$previsuMontantOption = 0;
						if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
							do{
								$prixtotal += ($mp->montant*$SAFE_DATA['form_option_'.$id_mp]);
								$previsuMontantOption += ($mp->montant*$SAFE_DATA['form_option_'.$id_mp]);
								$prixtotal += $prixoriginal * ($mp->pourcentage * $SAFE_DATA['form_option_'.$id_mp] / 100);
								$previsuMontantOption += $prixoriginal * ($mp->pourcentage * $SAFE_DATA['form_option_'.$id_mp] / 100);
								$heure_restante -= abs($mp->periode_heure);
								$totalHeureOption += abs($mp->periode_heure);
							}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
							$previsuOption .= '<div class="obk_previewResume">'.ucfirst(obk_removeslashes($mp->label)).':&nbsp;</div><div class="obk_previewRow"><div>';
							if ($mp->description != ''){
								$previsuOption .= __('Description / Details', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($mp->description).'</div></div><div class="obk_previewRow"><div>';
							}
							
							$previsuOption .= ''.__('Quantity', 'oui-booking').':&nbsp;</div><div>';
							if (isset($nbJours)){
								$previsuOption .= $nbJours . ' x (' . $SAFE_DATA['nb_de_place'] . ' ' . __('place(s)','oui-booking') . ') = ';
							}
							$previsuOption .= $SAFE_DATA['form_option_'.$id_mp];
							
							$previsuOption .='</div></div>';
							if (($mp->montant > 0) || ($mp->pourcentage > 0) || ($mp->periode_heure > 0)){
								$previsuOption .= '<div class="obk_previewRow"><div>';
								if ($mp->montant > 0){
									$previsuOption .= __('Unit price', 'oui-booking').':&nbsp;</div><div><span id="obk_optionUnitPrice'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
									$previsuOption .= obk_displayPrice($mp->montant, $rowEmp->devise, 'obk_optionUnitPrice'.$id_mp);
								}
								if ($mp->pourcentage > 0){
									$previsuOption .= __('Percentage', 'oui-booking').':&nbsp;</div><div>'.($mp->pourcentage+0)."%";
								}
								if ($mp->periode_heure > 0){
									$previsuOption .= ' '.__('for', 'oui-booking').' '.obk_heure2duree($mp->periode_heure);
								}
								$previsuOption .= '</div></div>';
							}
							if ($SAFE_DATA['form_option_details_texte_'.$id_mp] != ''){
								$previsuOption .= '<div class="obk_previewRow"><div>'.__('Your choice', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($SAFE_DATA['form_option_details_texte_'.$id_mp]).'</div></div>';
							}
							$previsuOption .= '<div class="obk_previewRow obk_total"><div>';
							$previsuOption .= __('Total without taxes', 'oui-booking').':&nbsp;</div><div>'.$SAFE_DATA['form_option_'.$id_mp].' x (';
							if ($mp->montant > 0){
								$previsuOption .= '<span id="obk_option2UnitPrice'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
								$previsuOption .= obk_displayPrice($mp->montant, $rowEmp->devise, 'obk_option2UnitPrice'.$id_mp);
							}elseif ($mp->pourcentage > 0){
								$previsuOption .= ($mp->pourcentage+0).'% x <span id="obk_originalPrice'.$id_mp.'">' . sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
								$previsuOption .= obk_displayPrice($prixoriginal, $rowEmp->devise, 'obk_originalPrice'.$id_mp);
							}else{
								$previsuOption .= '<span id="obk_option2UnitPrice'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',0).$rowEmp->devise . '</span>';
								$previsuOption .= obk_displayPrice(0, $rowEmp->devise, 'obk_option2UnitPrice'.$id_mp);
							}
							if ($mp->periode_heure > 0){
								$previsuOption .= ' x '.$totalHeureOption . __('hrs', 'oui-booking') . ' / ' . ($mp->periode_heure+0) . __('hrs', 'oui-booking');
							}
							$previsuOption .= ') = <span id="obk_totalOptionWT'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($previsuMontantOption,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise.'</span>';
							$previsuOption .= obk_displayPrice($previsuMontantOption,  $rowEmp->devise, 'obk_totalOptionWT'.$id_mp);
							$previsuOption .= '</div></div>
							';
							$totalAllOptions += $previsuMontantOption;
						}
					}
				}
			}
		}
	}
	if ($previsuOption != ''){
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Options', 'oui-booking').'</div>
							<div class="obk_previewContent">';
		$previsualisation .= $previsuOption;
		$previsualisation .= '<div class="obk_previewRow obk_total2"><div>'.__('Total with options (without taxes)', 'oui-booking').':&nbsp;</div><div><span id="obk_totalWithOptionsWT">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))). ' ' . $rowEmp->devise.'</span>';
		$previsualisation .= obk_displayPrice($prixoriginal,  $rowEmp->devise, 'obk_totalWithOptionsWT');
		$previsualisation .= ' + <span id="obk_totalAllOptions">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($totalAllOptions,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise. '</span>';
		$previsualisation .= obk_displayPrice($totalAllOptions,  $rowEmp->devise, 'obk_totalAllOptions');
		$previsualisation .= ' = <span id="obk_totalPriceWithOptions">' . sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixtotal,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
		$previsualisation .= obk_displayPrice($prixtotal,  $rowEmp->devise, 'obk_totalPriceWithOptions');
		$previsualisation .= '</div></div></div></div>';
	}
	$prixoriginal = $prixtotal;
	$previsuTaxes = '';
	$totalAllTaxes = 0;
	foreach($resultatsMP as $mp){
		if ($mp->type == 'taxe'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					if ($majBdd){
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,'')",$id_reservation,$mp->label,$mp->description,'1',$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type));
					}
					$heure_restante = $duree_reservation_heures;
					$previsuMontantTaxes = 0;
					$totalHeureTaxe = 0;
					if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
						do{
							$prixtotal += $mp->montant;
							$previsuMontantTaxes += $mp->montant;
							$prixtotal += $prixoriginal * ($mp->pourcentage / 100);
							$previsuMontantTaxes += $prixoriginal * ($mp->pourcentage / 100);
							$heure_restante -= abs($mp->periode_heure);
							$totalHeureTaxe += abs($mp->periode_heure);
						}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
						$previsuTaxes .= '<div class="obk_previewResume">'.ucfirst(obk_removeslashes($mp->label)).':&nbsp;</div><div class="obk_previewRow"><div>';
						if ($mp->description != ''){
							$previsuTaxes .= __('Description / Details', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($mp->description).'</div></div><div class="obk_previewRow"><div>';
						}
						if ($mp->montant > 0){
							$previsuTaxes .= __('Unit price', 'oui-booking').':&nbsp;</div><div><span id="obk_taxeUnitPrice'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
							$previsuTaxes .= obk_displayPrice($mp->montant,  $rowEmp->devise, 'obk_taxeUnitPrice'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuTaxes .= __('Percentage', 'oui-booking').':&nbsp;</div><div>'.($mp->pourcentage+0)."%";
						}
						if ($mp->periode_heure > 0){
							$previsuTaxes .= ' '.__('for', 'oui-booking').' '.obk_heure2duree($mp->periode_heure);
						}
						$previsuTaxes .= '</div></div><div class="obk_previewRow obk_total"><div>';
						$previsuTaxes .= __('Total', 'oui-booking').':&nbsp;</div><div>';
						if ($mp->montant > 0){
							$previsuTaxes .= '1 x <span id="obk_taxeUnit2Price'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
							$previsuTaxes .= obk_displayPrice($mp->montant,  $rowEmp->devise, 'obk_taxeUnit2Price'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuTaxes .= '<span id="obk_original2Price'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise .'</span>';
							$previsuTaxes .= obk_displayPrice($prixoriginal,  $rowEmp->devise, 'obk_original2Price'.$id_mp);
							$previsuTaxes .= ' x ';
							$previsuTaxes .= ($mp->pourcentage+0).'%';
						}
						if ($mp->periode_heure > 0){
							$previsuTaxes .= ' x '.$totalHeureTaxe . __('hrs', 'oui-booking') . ' / ' . ($mp->periode_heure+0) . __('hrs', 'oui-booking');
						}
						$previsuTaxes .= ' = <span id="obk_totalTaxes'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($previsuMontantTaxes,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise.'</span>';
						$previsuTaxes .= obk_displayPrice($previsuMontantTaxes,  $rowEmp->devise, 'obk_totalTaxes'.$id_mp);
						$previsuTaxes .= '</div></div>
						';
						$totalAllTaxes += $previsuMontantTaxes;
					}
				}
			}
		}
	}
	if ($previsuTaxes != ''){
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Taxes', 'oui-booking').'</div>
							<div class="obk_previewContent">';
		$previsualisation .= $previsuTaxes;
		$previsualisation .= '<div class="obk_previewRow obk_total2"><div>'.__('Total with taxes', 'oui-booking').':&nbsp;</div><div><span id="obk_totalWithTaxes">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))). ' ' . $rowEmp->devise.'</span>';
		$previsualisation .= obk_displayPrice($prixoriginal,  $rowEmp->devise, 'obk_totalWithTaxes');
		$previsualisation .= ' + <span id="obk_totalAllTaxes">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($totalAllTaxes,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise. '</span>';
		$previsualisation .= obk_displayPrice($totalAllTaxes,  $rowEmp->devise, 'obk_totalAllTaxes');
		$previsualisation .= ' = <span id="obk_totalPriceWithTaxes">'. sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixtotal,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span></div></div>';
		$previsualisation .= obk_displayPrice($prixtotal,  $rowEmp->devise, 'obk_totalPriceWithTaxes');
		$previsualisation .= '</div></div>';
	}
	$prixoriginal = $prixtotal;
	$previsuReduction = '';
	foreach($resultatsMP as $mp){
		if ($mp->type == 'reduction'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					if ($majBdd){
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,'')",$id_reservation,$mp->label,$mp->description,'1',$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type));
						if ($mp->quantite_initiale > 0){
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_modifyprice SET quantite = quantite - 1 WHERE id = %s",$id_mp));
						}
					}
					$heure_restante = $duree_reservation_heures;
					$previsuMontantReduction = 0;
					$totalHeureReduction = 0;
					if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
						do{
							$prixtotal -= $mp->montant;
							$previsuMontantReduction += $mp->montant;
							$prixtotal -= $prixoriginal * ($mp->pourcentage / 100);
							$previsuMontantReduction += $prixoriginal * ($mp->pourcentage / 100);
							$heure_restante -= abs($mp->periode_heure);
							$totalHeureReduction += abs($mp->periode_heure);
						}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
						$previsuReduction .= '<div class="obk_previewRow"><div>';
						if ($mp->code != ''){
							$previsuReduction .= __('Code', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($SAFE_DATA['form_reduction']).'</div></div><div class="obk_previewRow"><div>';
						}
						if ($mp->description != ''){
							$previsuReduction .= __('Description / Details', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($mp->description).'</div></div><div class="obk_previewRow"><div>';
						}
						if ($mp->montant > 0){
							$previsuReduction .= __('Amount', 'oui-booking').':&nbsp;</div><div>-<span id="obk_discountPrice'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise.'</span>';
							$previsuReduction .= obk_displayPrice($mp->montant,  $rowEmp->devise, 'obk_discountPrice'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuReduction .= __('Percentage', 'oui-booking').':&nbsp;</div><div>-'.($mp->pourcentage+0).'% x <span id="obk_original3Price'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= obk_displayPrice($prixoriginal,  $rowEmp->devise, 'obk_original3Price'.$id_mp);

							$previsuReduction .= '= -<span id="obk_discountPercentagePrice'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($mp->pourcentage/100*$prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= obk_displayPrice($mp->pourcentage/100*$prixoriginal,  $rowEmp->devise, 'obk_discountPercentagePrice'.$id_mp);
						}
						if ($mp->periode_heure > 0){
							$previsuReduction .= ' '.__('for', 'oui-booking').' '.obk_heure2duree($mp->periode_heure);
							$previsuReduction .= '<br>'.__('Total', 'oui-booking') . ': -<span id="obk_discountAmount'.$id_mp.'">' . sprintf( '%.'.obk_getParameter('roundnumber').'f', round($previsuMontantReduction,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= obk_displayPrice($previsuMontantReduction,  $rowEmp->devise, 'obk_discountAmount'.$id_mp);
							$previsuReduction .= ' '.__('for', 'oui-booking').' ' . $totalHeureReduction . __('hrs', 'oui-booking');
						}
						$previsuReduction .= '</div></div>';
						$previsuReduction .= '<div class="obk_previewRow obk_total2"><div>'.__('Total with taxes and discount', 'oui-booking').':&nbsp;</div><div><span id="obk_totalWithTaxesDiscount'.$id_mp.'">'. sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
						$previsuReduction .= obk_displayPrice($prixoriginal,  $rowEmp->devise, 'obk_totalWithTaxesDiscount'.$id_mp);
						if ($previsuMontantReduction > 0){
							$previsuReduction .= ' - <span id="obk_totalPercentDiscount'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($previsuMontantReduction,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuReduction .= obk_displayPrice($previsuMontantReduction,  $rowEmp->devise, 'obk_totalPercentDiscount'.$id_mp);
						}

						$previsuReduction .= ' = <span id="obk_totalDiscount'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixtotal,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
						$previsuReduction .= obk_displayPrice($prixtotal,  $rowEmp->devise, 'obk_totalDiscount'.$id_mp);
						$previsuReduction .= '</div></div>
						';
					}
				}
			}
		}
		
	}
	if ($previsuReduction != ''){
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Discount', 'oui-booking').'</div>
							<div class="obk_previewContent">';
		$previsualisation .= $previsuReduction;
		$previsualisation .= '</div></div>';
	}		
	$prixoriginal2 = $prixtotal;
	$previsuCoupon = '';
	foreach($resultatsMP as $mp){
		if ($mp->type == 'coupon'){
			foreach($todo as $id_mp){
				if ($id_mp == $mp->id){
					if ($majBdd){
						$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}obk_modifyprice_bookings (id_reservation,label,description,quantite,montant,pourcentage,periode_heure,code,type,details_texte) VALUES (%d,%s,%s,%s,%s,%s,%s,%s,%s,'')",$id_reservation,$mp->label,$mp->description,'1',$mp->montant,$mp->pourcentage,$mp->periode_heure,$mp->code,$mp->type));
						if ($mp->quantite_initiale > 0){
							$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_modifyprice SET quantite = quantite - 1 WHERE id = %s",$id_mp));
						}
					}
					$heure_restante = $duree_reservation_heures;
					$previsuMontantCoupon = 0;
					$totalHeureCoupon = 0;
					if (($heure_restante >= $mp->periode_heure)||($mp->periode_heure == 0)){
						do{
							$prixtotal -= $mp->montant;
							$previsuMontantCoupon += $mp->montant;
							$prixtotal -= $prixoriginal * ($mp->pourcentage / 100);
							$previsuMontantCoupon += $prixoriginal * ($mp->pourcentage / 100);
							$heure_restante -= abs($mp->periode_heure);
							$totalHeureCoupon += abs($mp->periode_heure);
						}while(($heure_restante >= $mp->periode_heure)&&($mp->periode_heure > 0));
						$previsuCoupon .= '<div class="obk_previewRow"><div>';
						$previsuCoupon .= __('Code', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($SAFE_DATA['form_coupon']).'</div></div><div class="obk_previewRow"><div>';
						if ($mp->description != ''){
							$previsuCoupon .= __('Description / Details', 'oui-booking').':&nbsp;</div><div>'.obk_removeslashes($mp->description).'</div></div><div class="obk_previewRow"><div>';
						}
						if ($mp->montant > 0){
							$previsuCoupon .= __('Amount', 'oui-booking').':&nbsp;</div><div>-<span id="obk_couponAmount'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f',($mp->montant+0)).' '.$rowEmp->devise . '</span>';
							$previsuCoupon .= obk_displayPrice($mp->montant,  $rowEmp->devise, 'obk_couponAmount'.$id_mp);
						}
						if ($mp->pourcentage > 0){
							$previsuCoupon .= __('Percentage', 'oui-booking').':&nbsp;</div><div>-'.($mp->pourcentage+0).'% x <span id="obk_original4Price'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuCoupon .= obk_displayPrice($prixoriginal,  $rowEmp->devise, 'obk_original4Price'.$id_mp);
							$previsuCoupon .= ' = -<span id="obk_couponPercentage'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($mp->pourcentage/100*$prixoriginal,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuCoupon .= obk_displayPrice($mp->pourcentage/100*$prixoriginal,  $rowEmp->devise, 'obk_couponPercentage'.$id_mp);
						}
						if ($mp->periode_heure > 0){
							$previsuCoupon .= ' '.__('for', 'oui-booking').' '.obk_heure2duree($mp->periode_heure);
							$previsuCoupon .= '<br>'.__('Total', 'oui-booking') . ': -<span id="obk_couponTotalAmount'.$id_mp.'">' . sprintf( '%.'.obk_getParameter('roundnumber').'f', round($previsuMontantCoupon,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
							$previsuCoupon .= obk_displayPrice($previsuMontantCoupon,  $rowEmp->devise, 'obk_couponTotalAmount'.$id_mp);
							$previsuCoupon .= ' '.__('for', 'oui-booking').' ' . $totalHeureCoupon . __('hrs', 'oui-booking');
						}
						$previsuCoupon .= '</div></div>';
						$previsuCoupon .= '<div class="obk_previewRow obk_total2"><div>'.__('Total with taxes and coupon', 'oui-booking').':&nbsp;</div><div><span id="obk_totalWithTaxesCoupon'.$id_mp.'">'. sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixoriginal2,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise . '</span>';
						$previsuCoupon .= obk_displayPrice($prixoriginal2,  $rowEmp->devise, 'obk_totalWithTaxesCoupon'.$id_mp);
						if ($previsuMontantCoupon > 0){
							$previsuCoupon .= ' - <span id="obk_totalCoupon'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($previsuMontantCoupon,obk_getParameter('roundnumber'))) .' '. $rowEmp->devise;
							$previsuCoupon .= obk_displayPrice($previsuMontantCoupon,  $rowEmp->devise, 'obk_totalCoupon'.$id_mp);
						}
						$previsuCoupon .= ' = <span id="obk_totalPriceCoupon'.$id_mp.'">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixtotal,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
						$previsuCoupon .= obk_displayPrice($prixtotal,  $rowEmp->devise, 'obk_totalPriceCoupon'.$id_mp);
						$previsuCoupon .= '</div></div>
						';
					}
				}
			}
		}
		
	}
	if ($previsuCoupon != ''){
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Coupon', 'oui-booking').'</div>
							<div class="obk_previewContent">';
		$previsualisation .= $previsuCoupon;
		$previsualisation .= '</div></div>';
	}
	$totalToPay = $prixtotal;
	$deposit = 0;
	if (($rowEmp->acompte_prix > 0)||($rowEmp->acompte_pourcentage > 0)){				
		if ($rowEmp->acompte_prix > 0){
			$deposit += $rowEmp->acompte_prix;
		}
		if ($rowEmp->acompte_pourcentage > 0){
			$deposit += $rowEmp->acompte_pourcentage / 100 * $prixtotal;
		}
		$previsualisation .= '<div class="obk_previewBox">
							<div class="obk_previewHead">'.__('Deposit requested', 'oui-booking').'</div>
							<div class="obk_previewContent">';
		$previsualisation .= '<div class="obk_previewRow"><div></div><div><span id="obk_deposit">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($deposit ,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
		$previsualisation .= obk_displayPrice($deposit,  $rowEmp->devise, 'obk_deposit');
		$previsualisation .= '</div></div></div></div>';
	}
	$previsualisation .= '<div class="obk_previewBox obk_total_reservation"><div class="obk_previewRow">';
	if (($deposit > 0) && ($deposit < $prixtotal)){
		$totalToPay = $deposit;
		$previsualisation .= '<div>'.__('Remaining amount to be paid on arrival', 'oui-booking').':&nbsp;</div><div><span id="obk_remaining">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($prixtotal - $totalToPay,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
		$previsualisation .= obk_displayPrice($prixtotal - $totalToPay,  $rowEmp->devise, 'obk_remaining');
		$previsualisation .= '</div></div><div class="obk_previewRow">';
	}else{
		$totalToPay = $prixtotal;
	}
	$previsualisation .= '<div>'.__('Total to pay', 'oui-booking').':&nbsp;</div><div><span id="obk_totalToPay">'.sprintf( '%.'.obk_getParameter('roundnumber').'f', round($totalToPay,obk_getParameter('roundnumber'))).' '.$rowEmp->devise.'</span>';
	$previsualisation .= obk_displayPrice($totalToPay,  $rowEmp->devise, 'obk_totalToPay');
	$previsualisation .= '</div></div></div>';
	if ($rowEmp->payment_instructions != ''){
		$previsualisation .= '<div class="obk_previewBox">
								<div class="obk_previewHead">'.__('Payment instructions', 'oui-booking').'</div>
								<div class="obk_previewContent">';
		$previsualisation .= '<div class="obk_previewRow"><div>'.obk_removeslashes($rowEmp->payment_instructions).'</div></div>';
		$previsualisation .= '</div></div>';
	}
	$previsualisation .= '<div class="obk_bookingPayment">';
	if (!$forEmail){
		$previsualisation .= '<div><form action="" method="POST" id="obk_formAllDataBooking">';	
		foreach($SAFE_DATA as $key => $value){
			if ($key != 'obk_act'){
				$previsualisation .= '<input type="hidden" name="'.$key.'" value="'.obk_removeslashes($value).'">';
			}
		}						
		$previsualisation .= '<input type="submit" value="'.__('Edit my entry', 'oui-booking').'"></form></div>';
	
		$previsualisation .= '
		<div><form action="" name="obk_saveBooking" method="post" onsubmit="return false">
			<input type="hidden" name="saveBooking">';
			
			
		$previsualisation .= obk_get_wp_nonce_field('saveBooking','obk_mainSaveBooking');
			
			
		$previsualisation .= '<input type="submit" value="'.__('Confirm the booking', 'oui-booking').'" class="obk_payBookingBtn">
		</form></div>
		';	
	
	
	}
	$previsualisation .= '<script>document.addEventListener("submit",function(e){obk_ajaxSaveBooking(e);});</script></div><div id="obk_calendarLoading"><div class="obk_loadingAnimation"></div></div></div>';
}