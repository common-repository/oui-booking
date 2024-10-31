<?php
/*
* Plugin Name: Oui! Booking
* Plugin URI: https://oui-booking.com
* Description: EN/FR/... Manage your bookings with this plugin that easily adapts to hotels, restaurants, camping, gites, trips, events, shows ...
* Version: 1.6.14
* Author: CréaLion.NET
* Author URI: https://crealion.net
* Text Domain: oui-booking
* Domain Path: /languages
*/
defined( 'ABSPATH' ) or die();
define('WPOBK_VERSION','17'); //DB version
$obk_custom_texts = array();
$obk_display_menu = false;
$obk_help = '';
$obk_linkCSV = '';
$obk_act ='';
$SAFE_DATA = [];
$obk_globalDescription = '';
$obk_spaceName = '';
include 'obk-functions.php';
if (obk_getParameter('timezone') != ''){
	date_default_timezone_set(obk_getParameter('timezone'));
}

if (is_admin() === true) {
	register_activation_hook(__FILE__, 'obk_install');
	register_deactivation_hook(__FILE__, 'obk_deactivation');
	register_uninstall_hook(__FILE__, 'obk_uninstall');
	add_action('admin_init','obk_checkUpgrade');
	include 'obk-installation.php';
	include 'obk-admin-ui.php';
	include 'obk-admin-bookings.php';
	include 'obk-admin-shortcodes.php';
	include 'obk-admin-pricechanges.php';
	include 'obk-admin-parameters.php';
	include 'obk-wpajax.php';
}else{
	include 'obk-shortcode.php';
}


function obk_load_front_css(){
	wp_enqueue_style('oui-booking-front-css', plugins_url('css/obk_front.css', __FILE__));
}

function obk_load_front_js(){
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui');
	wp_enqueue_script('oui-booking-front-js', plugins_url( 'js/obk_front.js', __FILE__ ), array('jquery'),'4.3.1');
	wp_localize_script('oui-booking-front-js', 'WPJS', array(
		'pluginsUrl' => plugins_url('',__FILE__),
		'adminAjaxUrl' => admin_url('admin-ajax.php'),
		'obk_GetTexte37' => __('This arrival time is not allowed', 'oui-booking'),
		'obk_GetTexte38' => __('Thank you for planning your arrival before', 'oui-booking'),
		'obk_GetTexte39' => __('This departure time is not allowed', 'oui-booking'),
		'obk_GetTexte40' => __('Thank you for planning your departure before', 'oui-booking'),
		'obk_GetTexte41' => __('Booking only possible from', 'oui-booking'),
		'obk_GetTexte42' => __('Booking only possible until', 'oui-booking'),
		'obk_GetTexte43' => __('Booking not possible before today.', 'oui-booking'),
		'obk_GetTexte44' => __('Duration of the booking insufficient. Minimum duration', 'oui-booking'),
		'obk_GetTexte45' => __('Duration of the booking too long. Maximum duration', 'oui-booking'),
		'obk_GetTexte46' => __('Booking is not possible from', 'oui-booking'),
		'obk_GetTexte51' => __('hrs', 'oui-booking'),
		'obk_GetTexte52' => __('Monday', 'oui-booking'),
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
		'obk_GetTexte75' => __('Arrival is not possible on', 'oui-booking'),
		'obk_GetTexte76' => __('Departure is not possible on', 'oui-booking'),
		'obk_TArrivalDateAfterDepartureDate' => __('The arrival date is after the departure date', 'oui-booking'),
		'obk_PluginData' => get_plugins()['oui-booking/obk.php']['Name'],
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
