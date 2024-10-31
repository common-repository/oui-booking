<?php
defined( 'ABSPATH' ) or die();
$echo .= '<div id="obk_plugin_onglets_taxes" class="obk_masked"><div id="obk_taxes_associes" class="obk_general_panel"><div>
				<h3>'.__('Taxes associated with the place', 'oui-booking').'</h3>';
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'taxe'){
				$tab_obk_idSpace = explode(",",$coupon->obk_idSpace);
				if (!in_array($obk_idSpace,$tab_obk_idSpace)){$display = "obk_masked";}else{$display = '';}
				$echo .= '
					<button class="obk_accordion '.$display.'" id="obk_taxeSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="obk_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'taxe\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="obk_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
					<div class="obk_panel" '.''.' id="obk_taxePanelSpace'.$coupon->id_modificationprix.'"><br>';		
				$echo .= '<table>';
				if (obk_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'oui-booking').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'oui-booking').' </b></td><td class="obk_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'oui-booking').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (obk_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'oui-booking').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				if (obk_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'oui-booking').' </b></td><td>'.obk_removeslashes($coupon->code).'</td></tr>';}
				if (obk_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'oui-booking').': </b><br>'.obk_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}
		$echo .= '</div></div><div class="obk_general_panel"><div><h3>'.__('List of all taxes', 'oui-booking').'</h3>';
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'taxe'){
				$tab_obk_idSpace = explode(",",$coupon->obk_idSpace);
				if (in_array($obk_idSpace,$tab_obk_idSpace)){$display = "obk_masked";}else{$display = '';}
				$echo .= '
				<button class="obk_accordion '.$display.'" id="obk_taxe'.$coupon->id_modificationprix.'"><a href="#" onclick="obk_associeCoupon(this.getAttribute(\'idcoupon\'),\'taxe\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="obk_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
				<div class="obk_panel" '.''.' id="obk_taxePanel'.$coupon->id_modificationprix.'"><br>';
				$echo .= '<table>';
					
				if (obk_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Remaining quantity', 'oui-booking').' </b></td><td>'.($coupon->quantite+0).'/'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'oui-booking').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'oui-booking').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (obk_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'oui-booking').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				if (obk_isNotEmpty($coupon->code)){ $echo .= '<tr><td><b>'.__('Code', 'oui-booking').' </b></td><td>'.obk_removeslashes($coupon->code).'</td></tr>';}
				if (obk_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'oui-booking').': </b><br>'.obk_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}
		$echo .= '<span id="obk_btnAjouttaxe">
		<button class="obk_accordion"  id="obk_btnAjouttaxebtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New tax', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			<form action="" method="post">
				<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_taxe" required><br>'.__('The name of the tax, visible to customers.', 'oui-booking').'<br><br>
				<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_taxe" required value="'.date('Y-m-d').'"><br>'.__('Start date for the tax.', 'oui-booking').'<br><br>
				<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_taxe" required value=""><br>'.__('End date for the tax.', 'oui-booking').'<br><br>
				<div class="obk_masked"><label><b>'.__('Quantity', 'oui-booking').':</b></label><br><input type="number" min="0" name="quantite_taxe"><br>'.__('Total number of times the tax can be used.', 'oui-booking').'<br><br></div>
				<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_taxe" onblur="obk_emptyPourcentage(\'taxe\')"><br>'.__('Fixed amount of tax.', 'oui-booking').'<br><br>
				<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_taxe" onblur="obk_emptyMontant(\'taxe\')"><br>'.__('Percentage of tax.', 'oui-booking').'<br><br>
				<label><b>'.__('Periodicity of the tax (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_taxe"><br>'.__('Set 0 for a fixed total amount / percentage tax or, for example, set "24" for a re-applied tax for each day booked.', 'oui-booking').'<br><br>
				<div class="obk_masked"><label><b>'.__('Code', 'oui-booking').':</b></label><br><input type="text" name="code_taxe"><br>'.__('Code to enter to benefit from the tax.', 'oui-booking').'<br><br></div>
				<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_taxe"></textarea><br><br>
				<button onclick="obk_ajouterCoupon(\'taxe\');return false;" class="button button-primary button-large">'.__('Add tax', 'oui-booking').'</button><br><br>
			</form>
		</div></span>';
		$echo .= '</div></div></div>';