<?php
defined( 'ABSPATH' ) or die();
//----TEXTS AND LANGUAGE

function obk_load_plugin_textdomain() {
    load_plugin_textdomain( 'oui-booking', NULL, 'oui-booking/languages' );
}
add_action( 'plugins_loaded', 'obk_load_plugin_textdomain' );