<?php
defined( 'ABSPATH' ) or die();
$echo .= '
			<div id="obk_plugin_onglets_options" class="obk_masked"><div id="obk_options_associes" class="obk_general_panel"><div>
				<h3>'.__('Options associated with the place', 'oui-booking').'</h3>';	
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'option'){
				$tab_obk_idSpace = explode(",",$coupon->obk_idSpace);
				if (!in_array($obk_idSpace,$tab_obk_idSpace)){$display = "obk_masked";}else{$display = '';}
				$echo .= '
					<button class="obk_accordion '.$display.'" id="obk_optionSpace'.$coupon->id_modificationprix.'"><a href="#" onclick="obk_desassocieCoupon(this.getAttribute(\'idcoupon\'),\'option\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="obk_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
					<div class="obk_panel" '.''.' id="obk_optionPanelSpace'.$coupon->id_modificationprix.'"><br>';
				$echo .= '<table>';
				
				if (obk_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Max. qty', 'oui-booking').' </b></td><td>'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->code)){ 
					$echo .= '<tr>	<td><b>'.__('Automatic quantity', 'oui-booking').' </b></td>
						<td><select disabled>
							<option value="userchoice" '.(($coupon->code == 'userchoice')? 'selected' : '' ).'>'.__('User choice','oui-booking').'</option> 
							<option value="oneperhour" '.(($coupon->code == 'oneperhour')? 'selected' : '' ).'>'.__('Per booked hour','oui-booking').'</option> 
							<option value="oneperday" '.(($coupon->code == 'oneperday')? 'selected' : '' ).'>'.__('Per booked day','oui-booking').'</option> 
							<option value="onepernight" '.(($coupon->code == 'onepernight')? 'selected' : '' ).'>'.__('Per booked night','oui-booking').'</option> 
							<option value="oneperweek" '.(($coupon->code == 'oneperweek')? 'selected' : '' ).'>'.__('Per booked week','oui-booking').'</option> 
							<option value="onepermonth" '.(($coupon->code == 'onepermonth')? 'selected' : '' ).'>'.__('Per booked month','oui-booking').'</option> 
						</select></td></tr>';
				}
				if (obk_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'oui-booking').' </b></td><td class="obk_displayLocalPrice">'.($coupon->montant+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'oui-booking').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (obk_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'oui-booking').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				
				if (obk_isNotEmpty($coupon->details_texte)){
					$echo .= '<tr><td colspan="2"><b>'.__('Options', 'oui-booking').': </b><br>';
					$displayDetails = explode("<br />",nl2br(obk_removeslashes($coupon->details_texte)));
					for($i=0;$i<sizeof($displayDetails);$i++){
						$echo .= '#'.($i+1).': ';
						$echo .= $displayDetails[$i];
						$echo .= '<br>';
					}
					$echo .= '</td></tr>';
				}
				if (obk_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'oui-booking').': </b><br>'.obk_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}		
		$echo .= '</div></div><div class="obk_general_panel"><div><h3>'.__('List of all options', 'oui-booking').'</h3>';
		foreach ($resultats4 as $coupon) {
			if ($coupon->type == 'option'){
				$tab_obk_idSpace = explode(",",$coupon->obk_idSpace);
				if (in_array($obk_idSpace,$tab_obk_idSpace)){$display = "obk_masked";}else{$display = '';}
				$echo .= '
				<button class="obk_accordion '.$display.'" id="obk_option'.$coupon->id_modificationprix.'"><a href="#" onclick="obk_associeCoupon(this.getAttribute(\'idcoupon\'),\'option\')" idcoupon="'.$coupon->id_modificationprix.'"><span class="obk_no_rotate_arrow_price"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></span></a><b>'.ucfirst(obk_removeslashes($coupon->label)).'</b></button>
				<div class="obk_panel" '.''.' id="obk_optionPanel'.$coupon->id_modificationprix.'"><br>';
				$echo .= '<table>';
				if (obk_isNotEmpty($coupon->date_debut)){ $echo .= '<tr><td><b>'.__('Available on', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_debut.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->date_fin)){ $echo .= '<tr><td><b>'.__('Until', 'oui-booking').' </b></td><td><input type="date" disabled value="'.$coupon->date_fin.'"></td></tr>';}
				if (obk_isNotEmpty($coupon->quantite_initiale)){ $echo .= '<tr><td><b>'.__('Max. qty', 'oui-booking').' </b></td><td>'.($coupon->quantite_initiale+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->code)){ 
					$echo .= '<tr>	<td><b>'.__('Automatic quantity', 'oui-booking').' </b></td>
						<td><select disabled>
							<option value="userchoice" '.(($coupon->code == 'userchoice')? 'selected' : '' ).'>'.__('User choice','oui-booking').'</option> 
							<option value="oneperhour" '.(($coupon->code == 'oneperhour')? 'selected' : '' ).'>'.__('Per booked hour','oui-booking').'</option> 
							<option value="oneperday" '.(($coupon->code == 'oneperday')? 'selected' : '' ).'>'.__('Per booked day','oui-booking').'</option> 
							<option value="onepernight" '.(($coupon->code == 'onepernight')? 'selected' : '' ).'>'.__('Per booked night','oui-booking').'</option> 
							<option value="oneperweek" '.(($coupon->code == 'oneperweek')? 'selected' : '' ).'>'.__('Per booked week','oui-booking').'</option> 
							<option value="onepermonth" '.(($coupon->code == 'onepermonth')? 'selected' : '' ).'>'.__('Per booked month','oui-booking').'</option> 
						</select></td></tr>';
				}
				if (obk_isNotEmpty($coupon->montant)){ $echo .= '<tr><td><b>'.__('Amount', 'oui-booking').' </b></td><td>'.($coupon->montant+0).'</td></tr>';}
				if (obk_isNotEmpty($coupon->pourcentage)){ $echo .= '<tr><td><b>'.__('Percentage', 'oui-booking').' </b></td><td>'.($coupon->pourcentage+0).'%</td></tr>';}
				if (obk_isNotEmpty($coupon->periode_heure)){ $echo .= '<tr><td><b>'.__('Periodicity', 'oui-booking').' </b></td><td>'.($coupon->periode_heure+0).'h</td></tr>';}
				if (obk_isNotEmpty($coupon->details_texte)){
					$echo .= '<tr><td colspan="2"><b>'.__('Options', 'oui-booking').': </b><br>';
					$displayDetails = explode("<br />",nl2br(obk_removeslashes($coupon->details_texte)));
					for($i=0;$i<sizeof($displayDetails);$i++){
						$echo .= '#'.($i+1).': ';
						$echo .= $displayDetails[$i];
						$echo .= '<br>';
					}
					$echo .= '</td></tr>';
				}
				if (obk_isNotEmpty($coupon->description)){ $echo .= '<tr><td colspan="2"><br><b>'.__('Description / Details', 'oui-booking').': </b><br>'.obk_removeslashes($coupon->description).'</td></tr>';}
				$echo .= '</table><br></div>';
			}
		}
		$echo .= '<span id="obk_btnAjoutoption">
		<button class="obk_accordion" id="obk_btnAjoutoptionbtn"><span><img class="emoji" src="'.plugins_url( 'img/add.svg', __FILE__ ).'"></span>'.__('New option', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			<form action="" method="post">
				<label><b>'.__('Label', 'oui-booking').':</b></label><br><input type="text" name="label_option" required><br>'.__('The name of the option, visible to customers', 'oui-booking').'<br><br>
				<label><b>'.__('Start date', 'oui-booking').':</b></label><br><input type="date" name="date_debut_option" required value="'.date('Y-m-d').'"><br>'.__('Availability date for the option.', 'oui-booking').'<br><br>
				<label><b>'.__('End date', 'oui-booking').':</b></label><br><input type="date" name="date_fin_option" required value=""><br>'.__('End date of availability for the option.', 'oui-booking').'<br><br>
				<label><b>'.__('Maximum quantity per booking', 'oui-booking').':</b></label><br><input type="number" min="0" name="quantite_option" required><br>'.__('Number of times the option can be used per booking.', 'oui-booking').'<br><br>
				<label><b>'.__('Automatic quantity', 'oui-booking').':</b></label><br><select name="code_option">
							<option value="userchoice">'.__('User choice','oui-booking').'</option> 
							<option value="oneperhour">'.__('Per booked hour','oui-booking').'</option> 
							<option value="oneperday">'.__('Per booked day','oui-booking').'</option> 
							<option value="onepernight">'.__('Per booked night','oui-booking').'</option> 
							<option value="oneperweek">'.__('Per booked week','oui-booking').'</option> 
							<option value="onepermonth">'.__('Per booked month','oui-booking').'</option> 
						</select><br>'.__('Allows the creation of packages per day or per night. For example, if user booked for 3 days, the option will be applied 3 times.', 'oui-booking').'<br><br>
				
				<label><b>'.__('Options details', 'oui-booking').':</b></label><br><textarea rows="5" name="details_texte_option"></textarea><br>'.__('Optional - Enter here the label for each option (1 line per option)', 'oui-booking').'<br><br>
				<label><b>'.__('Amount', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="montant_option" onblur="obk_emptyPourcentage(\'option\')"><br>'.__('Unit amount of the option.', 'oui-booking').'<br><br>
				<label><b>'.__('Percentage', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="pourcentage_option" onblur="obk_emptyMontant(\'option\')"><br>'.__('Percentage of the option.', 'oui-booking').'<br><br>
				<label><b>'.__('Periodicity of the option (in hours)', 'oui-booking').':</b></label><br><input type="number" step="any" min="0" name="periode_heure_option"><br>'.__('Set 0 for a fixed amount/percentage option or, for example, set "24" for an option applied for each day reserved.', 'oui-booking').'<br><br>
				
				<label><b>'.__('Description / Details', 'oui-booking').':</b></label><br><textarea name="description_option"></textarea><br><br>
				<button onclick="obk_ajouterCoupon(\'option\');return false;" class="button button-primary button-large">'.__('Add option', 'oui-booking').'</button><br><br>
			</form>
		</div></span>';	
		$echo .= '</div></div></div>';