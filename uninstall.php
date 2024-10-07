<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . 'aav_verifications';

try {
    $wpdb->query( "DROP TABLE IF EXISTS $table" );

    delete_metadata( 'post', '', 'show_verifications', '', true );
    delete_metadata( 'user', '', 'verification_status', '', true );
    delete_metadata( 'user', '', 'verification_code', '', true );
    delete_metadata( 'user', '', 'verification_phone', '', true );

    delete_option( "015_auth_username" );
    delete_option( "015_auth_password" );
    delete_option( "015_snumber" );
    delete_option( "code_lifetime" );
    delete_option( "sms_template" );
    delete_option( "aav_confirm_form_code" );
    delete_option( "aav_form_code" );
    delete_option( "form_verirication_id" );
    delete_option( "form_verirication_input_tel_name" );
    delete_option( "aav_redirect_page" );
} catch (Exception $e) {
    die($e->getMessage());
}