<?php
defined( 'ABSPATH' ) or die();
function obk_process_action_price_changes(){
	global $wpdb;
	global $SAFE_DATA;
	global $obk_act;
	
	if (!current_user_can('administrator')){return;}
	$isDataFormSafe = obk_secureDataForm();
	if (!$isDataFormSafe[0]){
		echo 'DEBUG3:------------------- '. $isDataFormSafe[1].'<br>';
		error_log('DEBUG3:------------------- '. $isDataFormSafe[1]);
	}else{
		$SAFE_DATA = $isDataFormSafe[2];
	}
	if (isset($SAFE_DATA['obk_act'])){
		$obk_act = $SAFE_DATA['obk_act'];
		check_admin_referer($obk_act);
	}
	if ($obk_act == 'updateMP'){
		$id_mp = $SAFE_DATA['id_mp'];
		$type = $SAFE_DATA['type_mp'];
		$label = (isset($SAFE_DATA['label_'.$type.$id_mp]))? $SAFE_DATA['label_'.$type.$id_mp] : '';
		$description = (isset($SAFE_DATA['description_'.$type.$id_mp]))? $SAFE_DATA['description_'.$type.$id_mp] : '';
		$details_texte = (isset($SAFE_DATA['details_texte_'.$type.$id_mp]))? $SAFE_DATA['details_texte_'.$type.$id_mp] : '';
		$date_debut = (isset($SAFE_DATA['date_debut_'.$type.$id_mp]))? $SAFE_DATA['date_debut_'.$type.$id_mp] : '';
		$date_fin = (isset($SAFE_DATA['date_fin_'.$type.$id_mp]))? $SAFE_DATA['date_fin_'.$type.$id_mp] : '';
		$quantite = (isset($SAFE_DATA['quantite_'.$type.$id_mp]))? $SAFE_DATA['quantite_'.$type.$id_mp] : '';
		$quantite_initiale = $quantite;
		$montant = (isset($SAFE_DATA['montant_'.$type.$id_mp]))? $SAFE_DATA['montant_'.$type.$id_mp] : '';
		$pourcentage = (isset($SAFE_DATA['pourcentage_'.$type.$id_mp]))? $SAFE_DATA['pourcentage_'.$type.$id_mp] : '';
		$periode_heure = (isset($SAFE_DATA['periode_heure_'.$type.$id_mp]))? $SAFE_DATA['periode_heure_'.$type.$id_mp] : '';
		$code = (isset($SAFE_DATA['code_'.$type.$id_mp]))? $SAFE_DATA['code_'.$type.$id_mp] : '';
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}obk_modifyprice SET label=%s,description=%s,date_debut=%s,date_fin=%s,quantite=%s,quantite_initiale=%s,montant=%s,pourcentage=%s,periode_heure=%s,code=%s,type=%s,details_texte=%s WHERE id=%d",$label,$description,$date_debut,$date_fin,$quantite,$quantite_initiale,$montant,$pourcentage,$periode_heure,$code,$type,$details_texte,$id_mp));
	}
	echo '<div id="obk_content"><div id="obk_content1"><h1 id="obk_mainPluginTitle">';
	echo '<a href="'.admin_url('admin.php?page=oui-booking').'"><img src="'.plugins_url( 'img/logo55.png', __FILE__ ).'"></a>';
	echo '<span>'.get_admin_page_title().'</span></h1>';
	echo '<div class="obk_wrap">';
	echo '<ul class="obk_plugin_onglets obk_plugin_onglets_mp">
			<li id="obk_plugin_onglets_options2_li" class="obk_isactive"><a href="#" onclick="obk_showOnglet(\'obk_plugin_onglets_options2\')">'.__('Options', 'oui-booking').' / '.__('Taxes', 'oui-booking').'</a></li>
			<!--<li id="obk_plugin_onglets_taxes2_li"><a href="#" onclick="obk_showOnglet(\'obk_plugin_onglets_taxes2\')">'.__('Taxes', 'oui-booking').'</a></li>-->
			<li id="obk_plugin_onglets_coupons2_li"><a href="#" onclick="obk_showOnglet(\'obk_plugin_onglets_coupons2\')">'.__('Coupons', 'oui-booking').' / '.__('Discounts', 'oui-booking').'</a></li>
			<!--<li id="obk_plugin_onglets_reductions2_li"><a href="#" onclick="obk_showOnglet(\'obk_plugin_onglets_reductions2\')">'.__('Discounts', 'oui-booking').'</a></li>-->	
		  </ul>';
	$resultats4 = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}obk_modifyprice ORDER BY label");
	echo '<div id="obk_plugin_onglets_options2"><div class="obk_general_panel">';
	echo '<div><h3>'.__('Options', 'oui-booking').'</h3>';
	wp_nonce_field('deleteCoupon','obk_deleteCoupon'); 
	$nbItem = 0;
	foreach($resultats4 as $coupon){
		if ($coupon->type == 'option'){
			$nbItem++;
			echo '
				<button class="obk_accordion obk_isactive" id="obk_coupon_'.$coupon->id.'"><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
				<div class="obk_panel" id="obk_coupon_panel_'.$coupon->id.'"><br>
					<form action="" method="post">
						<input type="hidden" name="obk_act" value="updateMP">';
			wp_nonce_field('updateMP');			
			echo '		<input type="hidden" name="id_mp" value="'.$coupon->id.'">
						<input type="hidden" name="type_mp" value="option">
						<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_option'.$coupon->id.'" required value="'.obk_removeslashes($coupon->label).'"><br>'.__('The name of the option, visible to customers', 'oui-booking').'<br><br>
						<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_option'.$coupon->id.'" required value="'.$coupon->date_debut.'"><br>'.__('Availability date for the option.', 'oui-booking').'<br><br>
						<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_option'.$coupon->id.'" required value="'.$coupon->date_fin.'"><br>'.__('End date of availability for the option.', 'oui-booking').'<br><br>
						<label><b>'.__('Maximum quantity per booking', 'oui-booking').':</b></label><br><input type="number" min="0" name="quantite_option'.$coupon->id.'" value="'.($coupon->quantite+0).'"><br>'.__('Number of times the option can be used per booking.', 'oui-booking').'<br><br>
						
						
						<label><b>'.__('Automatic quantity', 'oui-booking').':</b></label><br>
						<select name="code_option'.$coupon->id.'">
						<option value="userchoice" '.(($coupon->code == 'userchoice')? 'selected' : '' ).'>'.__('User choice','oui-booking').'</option> 
						<option value="oneperhour" '.(($coupon->code == 'oneperhour')? 'selected' : '' ).'>'.__('Per booked hour','oui-booking').'</option> 
						<option value="oneperday" '.(($coupon->code == 'oneperday')? 'selected' : '' ).'>'.__('Per booked day','oui-booking').'</option> 
						<option value="onepernight" '.(($coupon->code == 'onepernight')? 'selected' : '' ).'>'.__('Per booked night','oui-booking').'</option> 
						<option value="oneperweek" '.(($coupon->code == 'oneperweek')? 'selected' : '' ).'>'.__('Per booked week','oui-booking').'</option> 
						<option value="onepermonth" '.(($coupon->code == 'onepermonth')? 'selected' : '' ).'>'.__('Per booked month','oui-booking').'</option> 
						</select>
						
						<br>'.__('Allows the creation of packages per day or per night. For example, if user booked for 3 days, the option will be applied 3 times.', 'oui-booking').'<br><br>
						
						
						<label><b>'.__('Details about options', 'oui-booking').':</b></label><br><textarea rows="5" name="details_texte_option'.$coupon->id.'">'.obk_removeslashes($coupon->details_texte).'</textarea><br>'.__('Optional - Enter here the label for each option (1 line per option)', 'oui-booking').'<br><br>
						<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_option'.$coupon->id.'" onblur="obk_emptyPourcentage(\'option\',\''.$coupon->id.'\')" value="'.($coupon->montant+0).'"><br>'.__('Unit amount of the option.', 'oui-booking').'<br><br>
						<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_option'.$coupon->id.'" onblur="obk_emptyMontant(\'option\',\''.$coupon->id.'\')" value="'.($coupon->pourcentage+0).'"><br>'.__('Percentage of the option.', 'oui-booking').'<br><br>
						<label><b>'.__('Periodicity of the option (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_option'.$coupon->id.'" value="'.($coupon->periode_heure+0).'"><br>'.__('Set 0 for a fixed amount/percentage option or, for example, set "24" for an option applied for each day reserved.', 'oui-booking').'<br><br>
						<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_option'.$coupon->id.'">'.obk_removeslashes($coupon->description).'</textarea><br><br>
						'.get_submit_button(__('Save', 'oui-booking')).'
						<span class="obk_deleteAjax" onclick="obk_delete_mp(\''.$coupon->id.'\')">'.__('Delete', 'oui-booking').'</span>
					</form><br><br>
				</div>	
			';
		}
	}
	if ($nbItem == 0){
		echo '<br><b>'.__('Go to places edition to add items here', 'oui-booking') . '</b>';
	}
	echo '</div></div>';
	echo '<div class="obk_general_panel">';
	echo '<div><h3>'.__('Taxes', 'oui-booking').'</h3>';
	$nbItem = 0;
	foreach($resultats4 as $coupon){
		if ($coupon->type == 'taxe'){					
			$nbItem++;
			echo '
				<button class="obk_accordion obk_isactive" id="obk_coupon_'.$coupon->id.'"><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
				<div class="obk_panel" id="obk_coupon_panel_'.$coupon->id.'"><br>
					<form action="" method="post">
						<input type="hidden" name="obk_act" value="updateMP">';
			wp_nonce_field('updateMP');	
			echo '		<input type="hidden" name="id_mp" value="'.$coupon->id.'">
						<input type="hidden" name="type_mp" value="taxe">
						<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_taxe'.$coupon->id.'" required value="'.obk_removeslashes($coupon->label).'"><br>'.__('The name of the tax, visible to customers.', 'oui-booking').'<br><br>
						<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_taxe'.$coupon->id.'" required value="'.$coupon->date_debut.'"><br>'.__('Start date for the tax.', 'oui-booking').'<br><br>
						<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_taxe'.$coupon->id.'" required value="'.$coupon->date_fin.'"><br>'.__('End date for the tax.', 'oui-booking').'<br><br>
						<input type="hidden" name="quantite_taxe'.$coupon->id.'" value="'.($coupon->quantite+0).'">
						<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_taxe'.$coupon->id.'" onblur="obk_emptyPourcentage(\'taxe\',\''.$coupon->id.'\')" value="'.($coupon->montant+0).'"><br>'.__('Fixed amount of tax.', 'oui-booking').'<br><br>
						<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_taxe'.$coupon->id.'" onblur="obk_emptyMontant(\'taxe\',\''.$coupon->id.'\')" value="'.($coupon->pourcentage+0).'"><br>'.__('Percentage of tax.', 'oui-booking').'<br><br>
						<label><b>'.__('Periodicity of the tax (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_taxe'.$coupon->id.'" value="'.($coupon->periode_heure+0).'"><br>'.__('Set 0 for a fixed total amount / percentage tax or, for example, set "24" for a re-applied tax for each day booked.', 'oui-booking').'<br><br>
						<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_taxe'.$coupon->id.'">'.obk_removeslashes($coupon->description).'</textarea><br><br>
						'.get_submit_button(__('Save', 'oui-booking')).'
						<span class="obk_deleteAjax" onclick="obk_delete_mp(\''.$coupon->id.'\')">'.__('Delete', 'oui-booking').'</span>
					</form><br><br>
				</div>	
			';
		}
	}
	if ($nbItem == 0){
		echo '<br><b>'.__('Go to places edition to add items here', 'oui-booking') . '</b>';
	}
	echo '</div></div></div>';
	echo '<div id="obk_plugin_onglets_coupons2" class="obk_masked"><div class="obk_general_panel">';
	echo '<div><h3>'.__('Coupons', 'oui-booking').'</h3>';
	$nbItem = 0;
	foreach($resultats4 as $coupon){
		if ($coupon->type == 'coupon'){	
			$nbItem++;
			echo '
				<button class="obk_accordion obk_isactive" id="obk_coupon_'.$coupon->id.'"><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
				<div class="obk_panel" id="obk_coupon_panel_'.$coupon->id.'"><br>
					<form action="" method="post">
						<input type="hidden" name="obk_act" value="updateMP">';
			wp_nonce_field('updateMP');	
			echo '		<input type="hidden" name="id_mp" value="'.$coupon->id.'">
						<input type="hidden" name="type_mp" value="coupon">
						<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_coupon'.$coupon->id.'" required value="'.obk_removeslashes($coupon->label).'"><br>'.__('The name of the coupon.', 'oui-booking').'<br><br>
						<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_coupon'.$coupon->id.'" required value="'.$coupon->date_debut.'"><br>'.__('Availability date of the coupon.', 'oui-booking').'<br><br>
						<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_coupon'.$coupon->id.'" required value="'.$coupon->date_fin.'"><br>'.__('End of availability date of the coupon.', 'oui-booking').'<br><br>
						<label><b>'.__('Quantity', 'oui-booking').':</b></label><br><input type="number" min="0" name="quantite_coupon'.$coupon->id.'" value="'.($coupon->quantite+0).'"> / '.($coupon->quantite_initiale+0).'<br>'.__('Total number of times the coupon can be used. Put 0 for an unlimited quantity.', 'oui-booking').'<br><br>
						<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_coupon'.$coupon->id.'" onblur="obk_emptyPourcentage(\'coupon\',\''.$coupon->id.'\')" value="'.($coupon->montant+0).'"><br>'.__('Amount of the coupon.', 'oui-booking').'<br><br>
						<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_coupon'.$coupon->id.'" onblur="obk_emptyMontant(\'coupon\',\''.$coupon->id.'\')" value="'.($coupon->pourcentage+0).'"><br>'.__('Percentage of the coupon.', 'oui-booking').'<br><br>
						<label><b>'.__('Periodicity of the coupon (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_coupon'.$coupon->id.'" value="'.($coupon->periode_heure+0).'"><br>'.__('Set 0 for a fixed amount / percentage coupon or, for example, set "24" for a coupon applied for each day booked.', 'oui-booking').'<br><br>
						<label><b>'.__('Code', 'oui-booking').':</b></label><br><input type="text" required name="code_coupon'.$coupon->id.'" value="'.obk_removeslashes($coupon->code).'"><br>'.__('Code to enter to benefit from the coupon.', 'oui-booking').'<br><br>
						<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_coupon'.$coupon->id.'">'.obk_removeslashes($coupon->description).'</textarea><br><br>
						'.get_submit_button(__('Save', 'oui-booking')).'
						<span class="obk_deleteAjax" onclick="obk_delete_mp(\''.$coupon->id.'\')">'.__('Delete', 'oui-booking').'</span>
					</form><br><br>
				</div>	
			';
		}
	}
	if ($nbItem == 0){
		echo '<br><b>'.__('Go to places edition to add items here', 'oui-booking') . '</b>';
	}
	echo '</div></div>';
	echo '<div class="obk_general_panel">';
	echo '<div><h3>'.__('Discounts', 'oui-booking').'</h3>';
	$nbItem = 0;
	foreach($resultats4 as $coupon){
		if ($coupon->type == 'reduction'){
			$nbItem++;
			echo '
				<button class="obk_accordion obk_isactive" id="obk_coupon_'.$coupon->id.'"><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
				<div class="obk_panel" id="obk_coupon_panel_'.$coupon->id.'"><br>
					<form action="" method="post">
						<input type="hidden" name="obk_act" value="updateMP">';
			wp_nonce_field('updateMP');	
			echo '		<input type="hidden" name="id_mp" value="'.$coupon->id.'">
						<input type="hidden" name="type_mp" value="reduction">
						<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_reduction'.$coupon->id.'" required value="'.obk_removeslashes($coupon->label).'"><br>'.__('The name of the discount.', 'oui-booking').'<br><br>
						<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_reduction'.$coupon->id.'" required value="'.$coupon->date_debut.'"><br>'.__('Availability date of the discount.', 'oui-booking').'<br><br>
						<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_reduction'.$coupon->id.'" required value="'.$coupon->date_fin.'"><br>'.__('End date of availability of the discount.', 'oui-booking').'<br><br>
						<label><b>'.__('Quantity', 'oui-booking').':</b></label><br><input type="number" min="0" name="quantite_reduction'.$coupon->id.'" value="'.($coupon->quantite+0).'"> / '.($coupon->quantite_initiale+0).'<br>'.__('Total number of times the discount can be used. Put 0 for an unlimited quantity.', 'oui-booking').'<br><br>
						<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_reduction'.$coupon->id.'" onblur="obk_emptyPourcentage(\'reduction\',\''.$coupon->id.'\')" value="'.($coupon->montant+0).'"><br>'.__('Discount amount', 'oui-booking').'<br><br>
						<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_reduction'.$coupon->id.'" onblur="obk_emptyMontant(\'reduction\',\''.$coupon->id.'\')" value="'.($coupon->pourcentage+0).'"><br>'.__('Discount percentage', 'oui-booking').'<br><br>
						<label><b>'.__('Periodicity of the discount (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_reduction'.$coupon->id.'" value="'.($coupon->periode_heure+0).'"><br>'.__('Set 0 for a fixed amount / percentage discount or, for example, set "24" for a discount applied for each day booked.', 'oui-booking').'<br><br>
						<label><b>'.__('Code', 'oui-booking').':</b></label><br><input type="text" name="code_reduction'.$coupon->id.'" value="'.obk_removeslashes($coupon->code).'"><br>'.__('Code to enter to benefit from the discount. Leave empty for an automatic discount.', 'oui-booking').'<br><br>
						<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_reduction'.$coupon->id.'">'.obk_removeslashes($coupon->description).'</textarea><br><br>
						'.get_submit_button(__('Save', 'oui-booking')).'
						<span class="obk_deleteAjax" onclick="obk_delete_mp(\''.$coupon->id.'\')">'.__('Delete', 'oui-booking').'</span>
					</form><br><br>
				</div>	
			';
		}
	}
	if ($nbItem == 0){
		echo '<br><b>'.__('Go to places edition to add items here', 'oui-booking') . '</b>';
	}
	echo '</div></div></div>';
	echo '</div>';
	echo '</div><div id="obk_content2"><div>';
	echo obk_getTutorialsLinks();
	echo '</div></div></div>';
}
