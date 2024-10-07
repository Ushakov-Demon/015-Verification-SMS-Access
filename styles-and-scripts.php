<?php
add_action( 'admin_enqueue_scripts', 'aav_plugin_scripts' );
function aav_plugin_scripts(){
    wp_enqueue_script( 'aav-admin-scripts', plugin_dir_url( __FILE__ ) . '/admin-scripts.js', array( 'jquery' ), false, true );
    wp_enqueue_style( 'aav_admin_styles', plugin_dir_url( __FILE__ ) . '/admin-styles.css', array() );
}

add_action( 'wp_enqueue_scripts', 'aav_frontend_scripts' );
function aav_frontend_scripts(){
	wp_enqueue_script( 'aav_frontend_scripts', plugin_dir_url( __FILE__ ) . '/frontend-scripts.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'aav_frontend_styles', plugin_dir_url( __FILE__ ) . '/frontend-styles.css', array() );

	wp_localize_script( 'aav_frontend_scripts', 'aav_vars', array(
			'ajax_url' 				=> admin_url( 'admin-ajax.php' ),
	        'verify_form_id' 		=> AAV_VERIFY_FORM_ID,
	        'verify_code_lifetime'	=> AAV_CODE_LIFITIME,
        )
    );
}