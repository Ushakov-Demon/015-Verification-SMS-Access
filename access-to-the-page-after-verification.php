<?php
/*
Plugin Name: Access verification
Description: Show page content only after SMS verification.
Version: 1.1
Author: Bazadev
Plugin URI: https://bazadev.com.ua/en/case/verification-sms-access/
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'aav_init', 99 );

function aav_init(){
	$services = __DIR__ . '/services';

	include_once __DIR__ . '/defines.php' ;
	include_once __DIR__ . '/aav-meta-boxes.php' ;
	include_once __DIR__ . '/styles-and-scripts.php' ;
	include_once __DIR__ . '/class-aav-crypto.php' ;
	include_once __DIR__ . '/aav-actions.php' ;

	foreach (glob("$services/*.php") as $service) {
		include_once($service);
	}

	include_once __DIR__ . '/create-admin-menu-items.php' ;

	setDefOptionsData();
}

function setDefOptionsData() {
	if( empty( get_option( "aav_confirm_form_code" ) ) ) {
		update_option( 'aav_confirm_form_code', htmlspecialchars( AAV_CONFIRM_VERIFY ) );
	}

	if( empty( get_option( "aav_form_code" ) ) ) {
		update_option( 'aav_form_code', htmlspecialchars( AAV_VERIFY_FORM ) );
	}

	if( empty( get_option( "form_verirication_id" ) ) ) {
		update_option( 'form_verirication_id', 'sms_verify_form' );
	}

	if( empty( get_option( "form_verirication_input_tel_name" ) ) ) {
		update_option( 'form_verirication_input_tel_name', 'verify_number' );
	}
}