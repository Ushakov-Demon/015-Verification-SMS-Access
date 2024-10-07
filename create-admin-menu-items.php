<?php
add_action( 'admin_menu', 'aav_create_menu_items' );

function aav_create_menu_items(){
	add_menu_page(
		__( 'Access Verification' ), 		// title
		__( 'Access Verification' ), 		// menu title
		'manage_options',		   		// capability 	
		'access-verification-main',     // menu slug
		'aav_main_page_view_callback' , // callback function
		'dashicons-welcome-view-site', 	// icon
	);

	add_submenu_page(
        'access-verification-main',			// parent slug
        'Settings Access Verification', 	// page title
        'Settings Access Verification', 	// menu title
        'manage_options',					// capability
        'access_verification-settings', 	// menu slug
        'aav_settings_page_view_callback', 	// callback function
    );
}

function aav_main_page_view_callback(){
	if ( is_file( plugin_dir_path( __FILE__ ) . 'views/access_verification_admin_main.php' ) ) {
        include_once plugin_dir_path( __FILE__ ) . 'views/access_verification_admin_main.php';
    }
}

function aav_settings_page_view_callback(){
	if ( is_file( plugin_dir_path( __FILE__ ) . 'views/access_verification_admin_settings.php' ) ) {
        include_once plugin_dir_path( __FILE__ ) . 'views/access_verification_admin_settings.php';
    }
}