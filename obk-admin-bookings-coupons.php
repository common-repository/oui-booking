<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="obk_plugin_onglets_coupons" class="obk_masked"><div id="obk_coupons_associes" class="obk_general_panel"><div>
			<h3>'.__('Coupons associated with the place', 'oui-booking').'</h3>';
$echo .= obk_get_wp_nonce_field('desassocieCoupon','obk_mainDesassocieCoupon');
foreach ($resultats4 as $coupon) {
	if ($coupon->type == 'coupon'){
		$tab_obk_idSpace = explode(",",$coupon->obk_idSpace);
		if (!in_array($obk_idSpace,$tab_obk_idSpace)){$display = "obk_masked";}else{$display = '';}
		$echo .= '
			<button class="obk_accordion '.$display.'" id="obk_couponSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="obk_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'coupon\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="obk_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
			<div class="obk_panel" id="obk_couponPanelSpace'.$coupon->id_modificationprix.'"><br>';
		$echo .= '<table>';	
		if (obk_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
		if (obk_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
		if (obk_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'oui-booking').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
		if (obk_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount of the coupon.', 'oui-booking').' </b></td><td class="obk_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
		if (obk_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage of the coupon.', 'oui-booking').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
		if (obk_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'oui-booking').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
		if (obk_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'oui-booking').' </b></td><td>'.obk_removeslashes($coupon->code).'</td></tr>';}
		if (obk_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'oui-booking').': </b><br>'.obk_removeslashes($coupon->description).'</td></tr>';}
		$echo .= '</table><br></div>';
	}
}		
$echo .= '</div></div><div class="obk_general_panel"><div><h3>'.__('List of all coupons', 'oui-booking').'</h3>';
$echo .= obk_get_wp_nonce_field('associeCoupon','obk_mainAssocieCoupon');
foreach ($resultats4 as $coupon) {
	if ($coupon->type == 'coupon'){
		$tab_obk_idSpace = explode(",",$coupon->obk_idSpace);
		if (in_array($obk_idSpace,$tab_obk_idSpace)){$display = "obk_masked";}else{$display = '';}
		$echo .= '
		<button class="obk_accordion '.$display.'" id="obk_coupon'.$coupon->id_modificationprix.'"><a href="#" onclick="obk_associeCoupon(this.getAttribute(\'idcoupon\'),\'coupon\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="obk_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
		<div class="obk_panel" '.''.' id="obk_couponPanel'.$coupon->id_modificationprix.'"><br>';
		$echo .= '<table>';
		if (obk_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
		if (obk_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
		if (obk_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'oui-booking').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
		if (obk_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount of the coupon.', 'oui-booking').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
		if (obk_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage of the coupon.', 'oui-booking').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
		if (obk_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'oui-booking').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
		if (obk_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'oui-booking').' </b></td><td>'.obk_removeslashes($coupon->code).'</td></tr>';}
		if (obk_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'oui-booking').': </b><br>'.obk_removeslashes($coupon->description).'</td></tr>';}
		$echo .= '</table><br></div>';
	}
}
$echo .= '<span id="obk_btnAjoutcoupon">
<button class="obk_accordion" id="obk_btnAjoutcouponbtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New Coupon', 'oui-booking').'</button>
<div class="obk_panel"><br>
	<form action="" method="post">';
$echo .= obk_get_wp_nonce_field('ajouterCoupon','obk_mainAjouterCoupon');
$echo .= '<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_coupon" required><br>'.__('The name of the coupon.', 'oui-booking').'<br><br>
		<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_coupon" required value="'.date('Y-m-d').'"><br>'.__('Availability date of the coupon.', 'oui-booking').'<br><br>
		<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_coupon" required value=""><br>'.__('End of availability date of the coupon.', 'oui-booking').'<br><br>
		<label><b>'.__('Quantity', 'oui-booking').':</b></label><br><input type="number" min="0" name="quantite_coupon"><br>'.__('Total number of times the coupon can be used. Put 0 for an unlimited quantity.', 'oui-booking').'<br><br>
		<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_coupon" onblur="obk_emptyPourcentage(\'coupon\')"><br>'.__('Amount of the coupon.', 'oui-booking').'<br><br>
		<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_coupon" onblur="obk_emptyMontant(\'coupon\')"><br>'.__('Percentage of the coupon.', 'oui-booking').'<br><br>
		<label><b>'.__('Periodicity of the coupon (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_coupon"><br>'.__('Set 0 for a fixed amount / percentage coupon or, for example, set "24" for a coupon applied for each day booked.', 'oui-booking').'<br><br>
		<label><b>'.__('Code', 'oui-booking').':</b></label><br><input type="text" required name="code_coupon"><br>'.__('Code to enter to benefit from the coupon.', 'oui-booking').'<br><br>
		<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_coupon"></textarea><br><br>
		<button onclick="obk_ajouterCoupon(\'coupon\');return false;" class="button button-primary button-large">'.__('Add the coupon', 'oui-booking').'</button><br><br>
	</form>
</div></span>';	
$echo .= '</div></div></div>';