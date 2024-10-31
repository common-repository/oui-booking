<?php
defined( 'ABSPATH' ) or die();
if (is_admin() === true) {
	add_action('admin_menu', 'obk_add_admin_menu');
}

function obk_add_admin_menu(){
    add_menu_page('Oui! Booking', 'Oui! Booking', 'manage_options', 'oui-booking', 'obk_menu_html',plugins_url( 'img/logo24.png', __FILE__ ));
	add_submenu_page( 'oui-booking', __('Bookings', 'oui-booking'), __('Bookings', 'oui-booking'), 'manage_options', 'oui-booking','obk_process_action_reservations');
	add_submenu_page( 'oui-booking', __('Shortcodes', 'oui-booking'), __('Shortcodes', 'oui-booking'), 'manage_options', 'oui-booking-shortcodes','obk_process_action_shortcodes');
	add_submenu_page( 'oui-booking', __('Price changes', 'oui-booking'), __('Price changes', 'oui-booking'), 'manage_options', 'oui-booking-price-changes','obk_process_action_price_changes');
	add_submenu_page( 'oui-booking', __('Parameters', 'oui-booking'), __('Parameters', 'oui-booking'), 'manage_options', 'oui-booking-parameters','obk_process_action_parameters');
	obk_load_front_js();
	obk_load_front_css();
	obk_load_admin_js();
	obk_load_admin_css();
}

function obk_load_admin_js(){
	if (!current_user_can('administrator')){return;}
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('oui-booking-admin-js', plugins_url( 'js/obk_admin.js', __FILE__ ), array('jquery'),'4.3.1');
	wp_localize_script('oui-booking-admin-js', 'WPJS', array(
		'pluginsUrl' => plugins_url('',__FILE__),
		'adminAjaxUrl' => admin_url('admin-ajax.php'),
		'obk_GetTexte51' => __('hrs', 'oui-booking'),
		'obk_GetTexte52' => __('Monday'), 'oui-booking',
		'obk_GetTexte53' => __('Tuesday', 'oui-booking'),
		'obk_GetTexte54' => __('Wednesday', 'oui-booking'),
		'obk_GetTexte55' => __('Thursday', 'oui-booking'),
		'obk_GetTexte56' => __('Friday', 'oui-booking'),
		'obk_GetTexte57' => __('Saturday', 'oui-booking'),
		'obk_GetTexte58' => __('Sunday', 'oui-booking'),
		'obk_GetTexte59' => __('January', 'oui-booking'),
		'obk_GetTexte60' => __('February', 'oui-booking'),
		'obk_GetTexte61' => __('March', 'oui-booking'),
		'obk_GetTexte62' => __('April', 'oui-booking'),
		'obk_GetTexte63' => __('May', 'oui-booking'),
		'obk_GetTexte64' => __('June', 'oui-booking'),
		'obk_GetTexte65' => __('July', 'oui-booking'),
		'obk_GetTexte66' => __('August', 'oui-booking'),
		'obk_GetTexte67' => __('September', 'oui-booking'),
		'obk_GetTexte68' => __('October', 'oui-booking'),
		'obk_GetTexte69' => __('November', 'oui-booking'),
		'obk_GetTexte70' => __('December', 'oui-booking'),
		'obk_TDelete' => __('Delete', 'oui-booking'),
		'obk_TConfirmDeleteItem' => __('Are you sure you want to delete this item?', 'oui-booking'),
		'obk_TPleaseFillLabel' => __('Please fill in the label', 'oui-booking'),
		'obk_TPleaseFillMaxQty' => __('Please fill in the maximum quantity', 'oui-booking'),
		'obk_TPleaseFillEndDate' => __('Please fill in the end date', 'oui-booking'),
		'obk_TFreeSpaces' => __('free place(s)', 'oui-booking'),
		'obk_TAvailable' => __('Available on', 'oui-booking'),
		'obk_TUntil' => __('Until', 'oui-booking'),
		'obk_TRemainingQuantity' => __('Remaining quantity', 'oui-booking'),
		'obk_TMax' => __('Max. qty', 'oui-booking'),
		'obk_TAmount' => __('Amount', 'oui-booking'),
		'obk_TPercentage' => __('Percentage', 'oui-booking'),
		'obk_TPeriodicity' => __('Periodicity', 'oui-booking'),
		'obk_TCode' => __('Code', 'oui-booking'),
		'obk_TDescriptionDetails' => __('Description / Details', 'oui-booking'),
		'obk_tOptions' => __('Options', 'oui-booking'),
		'obk_PluginData' => get_plugins()['oui-booking/obk.php']['Name'],
		'obk_TConfirmSaveChanges' => __('Unsaved changes! Do you want to stay on the page to save them?', 'oui-booking'),
		'obk_TFillAllFieldsInPosition' => __('Please fill in all fields in position', 'oui-booking'),
		'obk_TAutomaticQuantity' => __('Automatic quantity', 'oui-booking'),
		'obk_tUserChoice' => __('User choice', 'oui-booking'),
		'obk_TOnePerHour' => __('Per booked hour', 'oui-booking'),
		'obk_TOnePerDay' => __('Per booked day', 'oui-booking'),
		'obk_TOnePerNight' => __('Per booked night', 'oui-booking'),
		'obk_TOnePerWeek' => __('Per booked week', 'oui-booking'),
		'obk_TOnePerMonth' => __('Per booked month', 'oui-booking'),
		'obk_Closed' => __('Closed', 'oui-booking'),
		'obk_AllAvailable' => __('All available', 'oui-booking'),
		'obk_Available' => __('Available', 'oui-booking'),
		'obk_Unavailable' => __('Unavailable', 'oui-booking'),
		'obk_Arrival' => __('Arrival', 'oui-booking'),
		'obk_Departure' => __('Departure', 'oui-booking'),
		'obk_BookingUnavailable' => __('Booking unavailable', 'oui-booking'),
		'obk_spaces' => __('spaces', 'oui-booking'),
		'obk_space' => __('space', 'oui-booking'),
		'obk_OpeningTime' => __('Opening time', 'oui-booking'),
		'obk_AllowedArrivalTime' => __('Allowed arrival time', 'oui-booking'),
		'obk_RequestedDepartureTime' => __('Requested departure time', 'oui-booking'),
		));
}

function obk_load_admin_css(){
	if (!current_user_can('administrator')){return;}
	wp_enqueue_style('oui-booking-admin-css', plugins_url('css/obk_admin.css', __FILE__));
}

function obk_menu_html(){
	global $obk_display_menu;
	global $obk_help;
	global $obk_act;
	global $obk_linkCSV;
	global $SAFE_DATA;
	global $obk_spaceName;
	global $echo;
	global $wpdb;
	if (!$obk_display_menu){return;}
	//Security check made in obk_process_action_reservations()
	if (!isset($SAFE_DATA['obk_idSpace'])){$SAFE_DATA['obk_idSpace'] = -1;}

	echo '<div id="obk_content"><div id="obk_content1"><h1 id="obk_mainPluginTitle">';
	echo '<a href="'.admin_url('admin.php?page=oui-booking').'"><img src="'.plugins_url( 'img/logo55.png', __FILE__ ).'"></a><span>';
	if ($obk_act != ''){
		if (isset($SAFE_DATA["obk_prevAct"])){
			echo '<form action="" method="post" name="retourEmplacement">
				<input type="hidden" name="obk_act" value="'.$SAFE_DATA["obk_prevAct"].'">';
			wp_nonce_field($SAFE_DATA["obk_prevAct"]);
			echo '<input type="hidden" name="obk_idSpace" value="'.$SAFE_DATA["obk_idSpace"].'">
				<input type="hidden" name="id_lieu" value="'.$SAFE_DATA["id_lieu"].'">
				<input type="hidden" name="calendarCurrentYearMonth" value="'.$SAFE_DATA["calendarCurrentYearMonth"].'">
				</form>';
			echo '<a href="#" onclick="document.forms[\'retourEmplacement\'].submit()"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></a> ';
		}else{
			$url = admin_url('admin.php?page=oui-booking');
			echo ' <a href="'.$url.'"><img class="emoji" src="'.plugins_url( 'img/arrow.svg', __FILE__ ).'"></a> ';
		}
	}
	
	if ($obk_spaceName != ''){
		echo '<span>'.obk_removeslashes($obk_spaceName).'</span></span>';
	}else{
		echo '<span>'.get_admin_page_title().'</span></span>';
	}
	echo '</h1>';
	echo '<div class="obk_wrap">'.obk_displayAdminNotice(1).'</div>';
	
	$emplacements = $wpdb->get_results($wpdb->prepare("SELECT emp.id,emp.label,lieu.nom,emp.nb_de_place,emp.prix_de_la_place,emp.email_notification,%s FROM {$wpdb->prefix}obk_spaces emp INNER JOIN {$wpdb->prefix}obk_locations lieu ON lieu.id=emp.id_lieu ORDER BY emp.id DESC",'none'));
	if (sizeof($emplacements) == 0){	
		$obk_help = 'add_location';
		echo '<div id="obk_welcome">';
		echo '<div id="obk_welcome_en"><div>
			<h3>'.__('Welcome to the Oui! Booking plugin','oui-booking').'</h3><br>
			- '.__('To start, follow the green blocks. ','oui-booking').'<br><br>
			- '.__('If you are new to this plugin, it is advisable to consult the documentation available on the right side of this page or after this message. ','oui-booking').'<br><br>
			- '.__('Do not hesitate to consult our YouTube channel to view the different possibilities of the plugin depending on your activity.','oui-booking').'<br><br>
			</div></div>';
		echo '</div>';
	}

	echo '<div class="obk_wrap">';
	$class = 'obk_headBarEdit'; 
	if (sizeof($emplacements) == 1){
		if (($emplacements[0]->prix_de_la_place == 0) && ($emplacements[0]->email_notification == '')){
			$obk_help = 'edit_space';
		}
	}
	echo '<div class="obk_headBar">
			<div><form action="" method="POST"><input type="hidden" name="obk_act" value="newBooking">';
	wp_nonce_field('newBooking');
	echo get_submit_button(__('Add booking', 'oui-booking')).'</form></div>';
	
	echo '<input type="button" id="obk_btnAddSpace" class="button button-primary button-large '.(($obk_help == 'add_location')? 'obk_help_add_location' : '').'" value="'.__('Add place', 'oui-booking').'">';
	echo '<input type="button" id="obk_btnEditSpace" class="button button-primary button-large '.(($obk_help == 'edit_space')? 'obk_help_add_location' : '').'" value="'.__('Manage place', 'oui-booking').'"></div>';
	
	echo '<div id="obk_addSpaceForm"><div class="'.$class.'">';
	
	
	$class = 'obk_addLocationSpace';
	echo '<div class="'.$class.'"><form method="post" action="">';
	echo '<input type="hidden" name="obk_act" value="insertSpace">';
	wp_nonce_field('insertSpace');
	echo '<h2>'.__('Add place','oui-booking').'</h2>';
	echo '<input type="text" name="nouveau_lieu" required placeholder="'.__('Location', 'oui-booking').'" value="" list="locationList">
		<input type="text" name="nouveau_emplacement" required placeholder="'.__('Place', 'oui-booking').'" value="">';
	echo '<div class="obk_buttons">';
	echo get_submit_button(__('Add', 'oui-booking'));
	echo '<p class="obk_delete submit"><input type="button" id="obk_btnCancelAddSpace" class="button" value="'.__('Cancel', 'oui-booking').'"></p></div>';
	echo '<datalist id="locationList">';
	$result = $wpdb->get_results($wpdb->prepare("SELECT %s, nom FROM {$wpdb->prefix}obk_locations", 'id'));
	foreach($result as $location){
		echo '<option value="'.$location->nom.'">'.$location->nom.'</option>';
	}
	echo '</datalist></form></div>';
	
	if (($obk_help == 'add_location')){
		echo '<div class="obk_help_add_location">';
		echo __('In "Location", enter where the places you want to propose for the reservation are.', 'oui-booking') . '<br>';
		echo __('In "Place", enter the kind of place you propose. For example: Rooms, House, Restaurant table...', 'oui-booking') . '<br>';
		echo '</div>';
	}
	
	echo '</div></div>';

	echo '<div id="obk_editSpaceForm"><div class="obk_headBarEdit">';
		
	$echo3 = ''; $selected = 'selected';
	foreach ($emplacements as $emplacement) {
		$echo3 .= '<option value="'.$emplacement->id.'" '.$selected.'>#'.$emplacement->id.' - '.obk_removeslashes($emplacement->nom).' - '.obk_removeslashes($emplacement->label).'</option>';
		$selected = '';
	}
	
	echo '<div class="obk_addLocationSpace"><form method="post" action="">
	<input type="hidden" name="obk_act" value="displaySpace">			
	<input type="hidden" name="id_lieu" value="-1">';
	wp_nonce_field('displaySpace');
	echo '<h2>'.__('Manage place','oui-booking').'</h2>';
	echo '<select name="obk_idSpace" class="obk_form obk_selectOverflow" size="10">'.$echo3.'</select>';
	
	echo '<div class="obk_buttons">';
	if (sizeof($emplacements) > 0){	
		echo get_submit_button(__('Manage', 'oui-booking'));
	}
	echo '<p class="obk_delete submit"><input type="button" id="obk_btnCancelEditSpace" class="button" value="'.__('Cancel', 'oui-booking').'"></p></div>';
	echo '</form></div>';
	echo '</div></div>';

	echo '<script>obk_initDefaultActionButton()</script>';
	if (obk_isNotEmpty($echo)){
		global $obk_globalDescription;
		echo '<div id="obk_hiddenWPEditor">';
		wp_editor(stripcslashes($obk_globalDescription),'description');
		
		echo '</div>';
		echo $echo;
		echo '<script>if (document.getElementById("obk_wpeditor")){
				var content = document.getElementById("obk_wpeditor").innerHTML;
				document.getElementById("obk_parentWPEditor").innerHTML = "";
				document.getElementById("obk_parentWPEditor").appendChild(document.getElementById("wp-description-wrap"));
			}</script>';
	}else{			
		$month = date("m");
		$year = date("Y");
		$nb_jours = obk_cal_days_in_month($month, $year);
		$filterArrivalDate = "$year-$month-$nb_jours";
		$filterDepartureDate = "$year-$month-01";
		$filterBookingStatus = '';
		echo '<div class="obk_recentBookings">
				
				<div class="obk_headBarEdit">';
		echo '<div><input type="hidden" name="nospace" value="1">';
		wp_nonce_field('chargeCalendrier','obk_mainCalendar');
		echo '<form method="post" action="" name="obk_filterBookings"><input type="hidden" name="obk_act" value="filterBookings"/>';
		echo '<input type="date" placeholder="'.__('Min. departure date', 'oui-booking').'" name="filterDepartureDate" value="'.$filterDepartureDate.'"> 
			  <input type="date" placeholder="'.__('Max. arrival date', 'oui-booking').'" name="filterArrivalDate" value="'.$filterArrivalDate.'"> 
			  <select name="filterBookingStatus">
		<option value="">'.__('All booking status', 'oui-booking').'</option>
		<option value="validationinprogress" '.(($filterBookingStatus == 'validationinprogress')? 'selected':'').'>'.obk_getBookingStatus('validationinprogress').'</option>
		<option value="pendingpayment" '.(($filterBookingStatus == 'pendingpayment')? 'selected':'').'>'.obk_getBookingStatus('pendingpayment').'</option>
		<option value="confirmed" '.(($filterBookingStatus == 'confirmed')? 'selected':'').'>'.obk_getBookingStatus('confirmed').'</option>
		<option value="paid" '.(($filterBookingStatus == 'paid')? 'selected':'').'>'.obk_getBookingStatus('paid').'</option>
		<option value="canceled" '.(($filterBookingStatus == 'canceled')? 'selected':'').'>'.obk_getBookingStatus('canceled').'</option>
		</select>';
		submit_button(__('Show', 'oui-booking'));
		echo '</form>';
		echo '<script>obk_listenerFilterBookings();</script>';
		echo '<form method="post" action="" name="obk_exportCSV"><input type="hidden" name="obk_act" value="exportCSV">';
		wp_nonce_field('exportCSV');
		echo '<input type="hidden" name="obk_ead" value="1">';
		echo '<input type="hidden" name="obk_edd" value="1">';
		echo '<input type="hidden" name="obk_ebs" value="1">';
		submit_button(__('Export in CSV', 'oui-booking'));
		echo '</form>';
		echo '<script>obk_listenerExportCSV();</script>';
		echo '</div></div></div>';
		if ($obk_linkCSV != ''){
			echo '<script>window.open("'.plugins_url('exports/' . $obk_linkCSV, __FILE__).'")</script>';
		}		
		echo '<br><br><div class="obk_wrap" id="obk_contentReservations">';
		echo '</div>';	
		echo '<script>obk_initCalendar();</script>';
	}

	
	echo '</div>';
	echo '</div><div id="obk_content2"><div>';
	echo obk_getTutorialsLinks();
	echo '</div></div>';
}
