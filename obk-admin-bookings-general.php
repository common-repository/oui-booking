<?php
	defined( 'ABSPATH' ) or die();
	$display = 'class="obk_masked"';
	$echo .= '<div id="obk_plugin_onglets_principal" '.$display.'><form method="post" action="" name="obk_formAddEditBookingSpace">';	
	$echo .= '<div class="obk_general_panel"><div>
			<input type="hidden" name="obk_act" value="updateSpace"/><input type="hidden" name="id_lieu" value="'.$lieu->id.'"/>';
	
	$echo .= obk_get_wp_nonce_field('updateSpace');
	$echo .= '<input type="hidden" name="obk_idSpace" value="'.$obk_idSpace.'"/>
			<button class="obk_accordion">'.__('Information', 'oui-booking').'</button>
			<div class="obk_panel"><br>';
	$echo .= '<label><b>'.__('Shortcode', 'oui-booking').': </b></label><label id="obk_labelShortcode">[obk_shortcode id="'.$obk_idSpace.'"]</label>
				<br><b>'.__('Copy-paste this shortcode into a post or page to display the booking form.', 'oui-booking').'</b><br><br>';
	global $obk_globalDescription;
	$obk_globalDescription = $description;
	$echo .= '
				<label><b>'.__('Label', 'oui-booking').': </b><br></label><input type="text" name="label" value="'.obk_removeslashes($label).'" required/>
				<br>'.__('The name displayed to visitors (e.g. "Classic houses", "Mobil-homes", "Guest houses", "Menus XL", "Holiday Home", "Departure for Paris", ...)', 'oui-booking').'<br><br>
				<label><b>'.__('Number of places', 'oui-booking').': </b><br></label><input type="number" min="0" step="any" name="nb_de_place" value="'.($nb_de_place+0).'"/>
				<br>'.__('The total number of available places which can be booked at the same time. Put for example "20" if you have 20 places. Leave to zero if you do not want to set a limit. The number of people can be specified by the tenants on the form or in the "Options", for example to differentiate between adults and children or to define a maximum number of people per place.', 'oui-booking').'<br><br>
				<label><b>'.__('Time unit (in hours)', 'oui-booking').': </b><br></label><input type="number" step="any" min="0" name="timeUnit" value="'.($timeUnit+0).'"/>
				<br>'.__('For example, if the unit price includes 24hrs rental, set "24". For a day or overnight package, use the "Options". Set "0" for a fixed amount regardless of the booking duration.', 'oui-booking').'<br><br>
				<label><b>'.__('Min. booking duration (in hours)', 'oui-booking').': </b><br></label><input type="number" step="any" min="0" name="minBookingDuration" value="'.($minBookingDuration+0).'"/>
				<br>'.__('For example if you rent two hours minimum, in this case put "2". Leave at zero if there is no minimum booking duration.', 'oui-booking').'<br><br>
				<label><b>'.__('Max. booking duration (in hours)', 'oui-booking').': </b><br></label><input type="number" step="any" min="0" name="tps_reservation_max_heure" value="'.($tps_reservation_max_heure+0).'"/>
				<br>'.__('For example if you rent one week maximum, in this case put "168". Leave at zero if there is no maximum booking duration.', 'oui-booking').'<br><br>
				<label><b>'.__('Link to the general Terms of Use', 'oui-booking').': </b><br></label><input type="text" name="lien_CGU" value="'.obk_removeslashes($lien_CGU).'"/>
				<br>'.__('Link to an internal or external web page presenting your GCU. Leave blank if you do not want to see a link to the general terms of use.', 'oui-booking').'<br><br>
				<label><b>'.__('Description / Details', 'oui-booking').': </b><br></label><span id="obk_parentWPEditor"><textarea name="description" rows="7" id="obk_wpeditor">'.obk_removeslashes($description).'</textarea></span>
				<br>'.__('Use this field to describe your property, for example.', 'oui-booking').'<br><br><br><br><br>
			</div>

			<button class="obk_accordion">'.__('Prices', 'oui-booking').'</button>
			<div class="obk_panel"><br>
				<label><b>'.__('Unit price of the place', 'oui-booking').': </b><br></label><input type="number" min="0" step="any" name="prix_de_la_place" value="'.($prix_de_la_place+0).'"/>
				<br>'.__('Unit price of the place. Leave to zero if you do not want to set a price.', 'oui-booking').'<br><br>
				<label><b>'.__('Currency', 'oui-booking').': </b><br></label><select name="devise">'.obk_getAllCurrencies($devise).'</select>
				<br>'.__('Currency used to display prices.', 'oui-booking').'<br><br>
				<label><b>'.__('Deposit requested (amount)', 'oui-booking').': </b><br></label><input type="number" step="any" min="0" name="acompte_prix" value="'.($acompte_prix+0).'"/>
				<br>'.__('The amount you request for the booking to be confirmed. Leave to zero if you do not ask for a fixed deposit.', 'oui-booking').'<br><br>
				<label><b>'.__('Deposit requested (percentage)', 'oui-booking').': </b><br></label><input type="number" step="any" min="0" name="acompte_pourcentage" value="'.($acompte_pourcentage+0).'"/>
				<br>'.__('The percentage of the price you request for the booking to be confirmed. Leave to zero if you do not ask for a percentage deposit.', 'oui-booking').'<br><br>
				<label><b>'.__('Payment instructions', 'oui-booking').': </b><br></label><textarea name="payment_instructions" rows="3">'.obk_removeslashes($payment_instructions).'</textarea>
				<br>'.__('Tell your customers the instructions to follow to make the payment: check payable to, IBAN, ...', 'oui-booking').'<br><br>
			</div>
			</div>';	
		$dayprice = explode('--o--',$dayprice);
		if (isset($dayprice[1])){
			$dayprice[1] = explode(';',$dayprice[1]);
		}else{
			$dayprice[1] = array_fill(0,7,0);
		}
		if (!isset($dayprice[2])){
			$dayprice[2] = '';
		}
		$echo .= '<button class="obk_accordion">'.__('Prices according to days', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			'.obk_get_wp_nonce_field('addPeriodePrice','obk_mainAddPeriodePrice').'
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"1") !== false)? "checked" : "").' value="1" id="obk_dayprice1"> <label for="obk_dayprice1" class="obk_priceDay">'.__('Monday', 'oui-booking').'</label><input type="number" name="dayprice1" min="0" step="any" value="'.$dayprice[1][1].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"1") !== false)? "checked" : "").' value="1" id="obk_ignoreperiods1">
			<label for="obk_ignoreperiods1">'.__('Ignore periods','oui-booking').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"2") !== false)? "checked" : "").' value="2" id="obk_dayprice2"> <label for="obk_dayprice2" class="obk_priceDay">'.__('Tuesday', 'oui-booking').'</label><input type="number" name="dayprice2" min="0" step="any" value="'.$dayprice[1][2].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"2") !== false)? "checked" : "").' value="2" id="obk_ignoreperiods2">
			<label for="obk_ignoreperiods2">'.__('Ignore periods','oui-booking').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"3") !== false)? "checked" : "").' value="3" id="obk_dayprice3"> <label for="obk_dayprice3" class="obk_priceDay">'.__('Wednesday', 'oui-booking').'</label><input type="number" name="dayprice3" min="0" step="any" value="'.$dayprice[1][3].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"3") !== false)? "checked" : "").' value="3" id="obk_ignoreperiods3">
			<label for="obk_ignoreperiods3">'.__('Ignore periods','oui-booking').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"4") !== false)? "checked" : "").' value="4" id="obk_dayprice4"> <label for="obk_dayprice4" class="obk_priceDay">'.__('Thursday', 'oui-booking').'</label><input type="number" name="dayprice4" min="0" step="any" value="'.$dayprice[1][4].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"4") !== false)? "checked" : "").' value="4" id="obk_ignoreperiods4">
			<label for="obk_ignoreperiods4">'.__('Ignore periods','oui-booking').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"5") !== false)? "checked" : "").' value="5" id="obk_dayprice5"> <label for="obk_dayprice5" class="obk_priceDay">'.__('Friday', 'oui-booking').'</label><input type="number" name="dayprice5" min="0" step="any" value="'.$dayprice[1][5].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"5") !== false)? "checked" : "").' value="5" id="obk_ignoreperiods5">
			<label for="obk_ignoreperiods5">'.__('Ignore periods','oui-booking').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"6") !== false)? "checked" : "").' value="6" id="obk_dayprice6"> <label for="obk_dayprice6" class="obk_priceDay">'.__('Saturday', 'oui-booking').'</label><input type="number" name="dayprice6" min="0" step="any" value="'.$dayprice[1][6].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"6") !== false)? "checked" : "").' value="6" id="obk_ignoreperiods6">
			<label for="obk_ignoreperiods6">'.__('Ignore periods','oui-booking').'</label><br>
			
			<input type="checkbox" name="dayprice[]" '.((strpos($dayprice[0],"0") !== false)? "checked" : "").' value="0" id="obk_dayprice7"> <label for="obk_dayprice7" class="obk_priceDay">'.__('Sunday', 'oui-booking').'</label><input type="number" name="dayprice7" min="0" step="any" value="'.$dayprice[1][0].'">
			<input type="checkbox" name="daypriceignoreperiods[]" '.((strpos($dayprice[2],"0") !== false)? "checked" : "").' value="0" id="obk_ignoreperiods7">
			<label for="obk_ignoreperiods7">'.__('Ignore periods','oui-booking').'</label><br><br>		
		</div>
		<button class="obk_accordion">'.__('Prices according to periods', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			'.obk_get_wp_nonce_field('addPeriodePrice','obk_mainAddPeriodePrice').'
			<label><b>'.__('Start date', 'oui-booking').': </b><br></label><input type="date" id="obk_periodPriceStartDate" value=""><input type="time" id="obk_periodPriceStartTime" value="00:00"><br><br>
			<label><b>'.__('Finish date', 'oui-booking').': </b><br></label><input type="date" id="obk_periodPriceFinishDate" value=""><input type="time" id="obk_periodPriceFinishTime" value="00:00"><br><br>
			<label><b>'.__('Unit price of the place', 'oui-booking').': </b><br></label><input type="number" id="obk_periodPrice" min="0" step="any" value="0"><br>
			'.__('You can define new prices here according to different periods', 'oui-booking').'<br><br>
			<button onclick="obk_ajouterPeriodePrix();return false;" class="button button-primary button-large">'.__('Add period', 'oui-booking').'</button><br><br>
			<div id="obk_listperiodesprices">
				<label><b>'.__('List of periods','oui-booking').'</b></label><br><br>';
				$echo .= obk_get_wp_nonce_field('deletePeriod','obk_deletePeriod');
				$tabPeriod = explode('--o--',$periodesprices);
				foreach($tabPeriod as $index => $period){
					if ($period != ''){
						$pp = explode(';',$period);
						$pp[0] = explode(' ',$pp[0]);
						$pp[1] = explode(' ',$pp[1]);
						$echo .= '<div class="obk_periodList" id="obk_period'.$index.'">
									<div>
										<div>
											<input type="date" disabled value="'.$pp[0][0].'">
											<input type="time" disabled value="'.$pp[0][1].'">
										</div>
										<div>
											<input disabled type="date" value="'.$pp[1][0].'">
											<input disabled type="time" value="'.$pp[1][1].'">
										</div>
									</div>
									<div>
										<div>'.__('Price','oui-booking').': <span class="obk_displayLocalPrice">' . $pp[2] . '</span></div>
									</div>
									<div>
										<div class="obk_delete">
											<span class="obk_deleteAjax" onclick="obk_delete_period(\''.$index.'\',\''.$obk_idSpace.'\',\''.$pp[0][0].' '.$pp[0][1].'\',\''.$pp[1][0].' '.$pp[1][1].'\',\''.$pp[2].'\')">'.__('Delete','oui-booking').'</span>
										</div>
									</div>
								</div>';
					}
				}
			$echo .= '<br></div>
		</div>';
	$echo .= '</div><div class="obk_general_panel"><div>
		<button class="obk_accordion">'.__('Availability', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			<label><b>'.__('Start date of availability', 'oui-booking').': </b><br></label><input type="date" required name="date_debut_reservation" value="'.$date_debut_reservation.'"/>
			<br>'.__('When can people book?', 'oui-booking').'<br><br>
			<label><b>'.__('End date of availability', 'oui-booking').': </b><br></label><input type="date" required name="date_fin_reservation" value="'.$date_fin_reservation.'"/>
			<br>'.__('Until when can people book?', 'oui-booking').'<br><br>
			<label><b>'.__('Days and hours of opening, arrival and departure','oui-booking').':</b><br></label>
			<select size="7" class="obk_selectOverflow" id="obk_OTDays" style="font-size:13px">
				<option id="obk_OTDay1" obk_day="'.__('Monday', 'oui-booking').'"></option>
				<option id="obk_OTDay2" obk_day="'.__('Tuesday', 'oui-booking').'"></option>
				<option id="obk_OTDay3" obk_day="'.__('Wednesday', 'oui-booking').'"></option>
				<option id="obk_OTDay4" obk_day="'.__('Thursday', 'oui-booking').'"></option>
				<option id="obk_OTDay5" obk_day="'.__('Friday', 'oui-booking').'"></option>
				<option id="obk_OTDay6" obk_day="'.__('Saturday', 'oui-booking').'"></option>
				<option id="obk_OTDay0" obk_day="'.__('Sunday', 'oui-booking').'"></option>
			</select>
			&#10140;
			<select size="3" class="obk_selectOverflow" id="obk_OTType" style="font-size:13px">
				<option id="obk_OTType0" obk_type="'.__('Open','oui-booking').'"></option>
				<option id="obk_OTType1" obk_type="'.__('Arrival','oui-booking').'"></option>
				<option id="obk_OTType2" obk_type="'.__('Departure','oui-booking').'"></option>
			</select>
			&#10140;
			<div style="display: inline-block;background:#eaeaea;margin:10px 0;padding: 5px;vertical-align: middle;text-align:right;">
				<div style="display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-orient: vertical; -webkit-box-direction: normal; -webkit-flex-direction: column; -ms-flex-direction: column; flex-direction: column;-webkit-box-pack: justify; -webkit-justify-content: space-between; -ms-flex-pack: justify; justify-content: space-between;min-width:350px;min-height: 170px;">
					<div style="display: -webkit-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-box-pack: justify; -webkit-justify-content: space-between; -ms-flex-pack: justify; justify-content: space-between;">
						<div>
							<input type="checkbox" id="obk_OTOpened">
							<label for="obk_OTOpened" id="obk_OTOpenedLabel">'.__('Open','oui-booking').'</label>
						</div>
						<div>
							<a href="#" style="text-decoration:none;font-size: 16px;" id="obk_OTBtnAddTime">&#10010;</a>
						</div>
					</div>
					<div id="obk_OTPeriods"></div>
					<input type="button" value="'.__('Duplicate on the whole week','oui-booking').'" id="obk_OTBtnDuplicate">
				</div>
			</div>
			<br>'.__('Indicate here the days and times your establishment is open. You can also define the days and times during which you accept arrivals, as well as for requested departures. If no items are entered for arrivals / departures, then it is the opening days that determine when arrivals / departures will be made. You can also choose a reference schedule for bookings. Thus, whatever the time of arrival or departure of your customers, this is the reference that will serve as a basis for calculating the duration and price','oui-booking').'
			<br><br>
			<textarea id="obk_OT" name="openingtimes">'.obk_removeslashes($openingtimes).'</textarea>
			<script>obk_initOpeningTime()</script>
			';
		$echo .= '	
			<label><b>'.__('Interval in minutes for the user', 'oui-booking').': </b><br></label><input type="number" step="any" min="1" max="60" name="user_minutes_interval" value="'.($user_minutes_interval+0).'"/>
			<br>'.__('For example, put "30" if you want an interval of 30 minutes to let the user choose the start and end time.', 'oui-booking').'<br><br>
		</div>';
		$echo .= '<button class="obk_accordion">'.__('Exceptional closure', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			'.obk_get_wp_nonce_field('addExceptionalClosure','obk_mainAddExceptionalClosure').'
			<label><b>'.__('Start date', 'oui-booking').': </b><br></label><input type="date" id="obk_periodClosureStartDate" value=""><input type="time" id="obk_periodClosureStartTime" value="00:00"><br><br>
			<label><b>'.__('Finish date', 'oui-booking').': </b><br></label><input type="date" id="obk_periodClosureFinishDate" value=""><input type="time" id="obk_periodClosureFinishTime" value="00:00"><br><br>
			<button onclick="obk_ajouterExceptionalClosure();return false;" class="button button-primary button-large">'.__('Add closure', 'oui-booking').'</button><br><br>
			<div id="obk_listperiodesclosures">
				<label><b>'.__('List of closures','oui-booking').'</b></label><br><br>';
				$echo .= obk_get_wp_nonce_field('deletePeriodClosure','obk_deletePeriodClosure');
				$tabPeriod = explode('--o--',$exceptionalclosure);
				foreach($tabPeriod as $index => $period){
					if ($period != ''){
						$pp = explode(';',$period);
						$pp[0] = explode(' ',$pp[0]);
						$pp[1] = explode(' ',$pp[1]);
						$echo .= '<div class="obk_periodListClosure" id="obk_periodclosure'.$index.'">
									<div>
										<div>
											<input type="date" disabled value="'.$pp[0][0].'">
											<input type="time" disabled value="'.$pp[0][1].'">
										</div>
										<div>
											<input disabled type="date" value="'.$pp[1][0].'">
											<input disabled type="time" value="'.$pp[1][1].'">
										</div>
									</div>
									<div>
										<div class="obk_deleteclosure">
											<span class="obk_deleteAjax" onclick="obk_delete_period_closure(\''.$index.'\',\''.$obk_idSpace.'\',\''.$pp[0][0].' '.$pp[0][1].'\',\''.$pp[1][0].' '.$pp[1][1].'\')">'.__('Delete','oui-booking').'</span>
										</div>
									</div>
								</div>';
					}
				}
			$echo .= '<br></div>
		</div>';
		$echo .= '<button class="obk_accordion">'.__('Booking registration', 'oui-booking').'</button>
		<div class="obk_panel"><br>
			<label><b>'.__('Default status of a new booking', 'oui-booking').': </b><br></label><select name="statut_par_defaut_reservation">
			<option value="validationinprogress" '.(($statut_par_defaut_reservation == "validationinprogress")? "selected" : "").'>'.__('Validation in progress', 'oui-booking').'</option>
			<option value="pendingpayment" '.(($statut_par_defaut_reservation == "pendingpayment")? "selected" : "").'>'.__('Pending payment', 'oui-booking').'</option>
			<option value="confirmed" '.(($statut_par_defaut_reservation == "confirmed")? "selected" : "").'>'.__('Confirmed', 'oui-booking').'</option>
			<option value="paid" '.(($statut_par_defaut_reservation == "paid")? "selected" : "").'>'.__('Paid', 'oui-booking').'</option>
			<option value="canceled" '.(($statut_par_defaut_reservation == "canceled")? "selected" : "").'>'.__('Canceled', 'oui-booking').'</option>
			</select>
			<br>'.__('Select the initial status of the booking when a customer makes a booking request.', 'oui-booking').'<br><br>
			
			<label><b>'.__('Receive email', 'oui-booking').': </b><br></label><select name="notification_email">
			<option value="1" '.(($notification_email == "1")? "selected" : "").'>'.__('Yes', 'oui-booking').'</option>
			<option value="0" '.(($notification_email == "0")? "selected" : "").'>'.__('No', 'oui-booking').'</option></select>
			<br>'.__('Receive notification by email of a new booking','oui-booking.').'<br><br>
			<label><b>'.__('Email notification', 'oui-booking').': </b><br></label><input type="email" name="email_notification" value="'.$email_notification.'"/>
			<br>'.__('Email address receiving bookings requests. If not specified, the default address stored in WordPress will be used.', 'oui-booking').'<br><br>
		</div>';	
	$echo .= '</div></div>';
	$echo .= '<div class="obk_buttons"><br><div class="obk_pad10">';
	$echo .= get_submit_button(__('Save general information', 'oui-booking')).'</form>';
	$echo .= '<form action="" method="post" class="obk_delete" onsubmit="return confirm(\''.__('Are you sure you want to delete this place?', 'oui-booking').'\');"><input type="hidden" name="obk_idSpace" value="'.$obk_idSpace.'"/><input type="hidden" name="obk_act" value="deleteSpace"/>';
	$echo .= obk_get_wp_nonce_field('deleteSpace');
	$echo .= get_submit_button(__('Delete place', 'oui-booking'),"delete").'</form>';
	$echo .= '</div></div></div>';