<?php
/*
Plugin Name: Access to the page after verification
Version: 1.0
Author: UDaemon
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', 'aav_init', 99 );

function aav_init(){
	include_once __DIR__ . '/defines.php' ;
	include_once __DIR__ . '/aav-meta-boxes.php' ;
	include_once __DIR__ . '/styles-and-scripts.php' ;
	include_once __DIR__ . '/class-aav-crypto.php' ;
	include_once __DIR__ . '/aav-actions.php' ;
	include_once __DIR__ . '/create-admin-menu-items.php' ;
}