<?php
if ( ! defined( "WP_UNINSTALL_PLUGIN" ) ) {
    exit;
}

global $wpdb;
$table = $wpdb->prefix . "aav_verifications";

try {
    $wpdb->query( "DROP TABLE IF EXISTS $table" );

    delete_metadata( "post", "", "show_verifications", "", true );
    delete_metadata( "user", "", "verification_status", "", true );
    delete_metadata( "user", "", "verification_code", "", true );
    delete_metadata( "user", "", "verification_phone", "", true );

    $options = [
        "aav_sms_service",
        "015_auth_username",
        "015_auth_password",
        "015_snumber",
        "code_lifetime",
        "sms_template",
        "aav_confirm_form_code",
        "aav_form_code",
        "form_verirication_id",
        "form_verirication_input_tel_name",
        "aav_redirect_page",
        "twilio_account_sid",
        "twilio_account_token",
        "twilio_number_from",
        "smsmode_api_key",
    ];

    foreach( $options as $option ) {
        delete_option( $option );
    }
    
} catch (Exception $e) {
    die($e->getMessage());
}