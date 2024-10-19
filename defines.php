<?php
$verify_form_def = '<form id="sms_verify_form">
		<label>
			Enter your phone number
			<input type="tel" name="verify_number" required>
		</label>
		<input type="submit" value="Submit">
	</form>';

$verify_confirm_form_def = '<form id="verify_confirm_form">
			<label>
				Enter the code from the SMS
				<input type="text" name="confirm_code">
			</label>
		<input type="submit" value="Submit">
	</form>';

$message_template_def = 'Your verification code is {{XXXX}}. See you on the site "' . get_option( 'blogname' ) . '"';

if( ! defined( "AAV_PLUGIN_DIR" ) ){
	define( "AAV_PLUGIN_DIR", plugin_dir_path(__FILE__) );
}

if( ! defined( "AAV_PLUGIN_DIR_URL" ) ){
	define( "AAV_PLUGIN_DIR_URL", plugin_dir_url(__FILE__) );
}

if( ! defined( "AAV_FOR_USER_LOGGED" ) ){
	define( "AAV_FOR_USER_LOGGED", false );
}

$aav_sms_service = ! empty( get_option( "aav_sms_service" ) ) ? get_option( "aav_sms_service" ) : "015pbx";
if( ! defined( "AAV_SMS_SERVICE" ) ){
	define( "AAV_SMS_SERVICE", $aav_sms_service );
}

if( ! defined( "AAV_015_PREFIX" ) ){
	define( "AAV_015_PREFIX", "015" );
}

$auth_username = ! empty( get_option( AAV_015_PREFIX . "_auth_username" ) ) ? get_option( AAV_015_PREFIX . "_auth_username" ) : "";
if(!defined("AAV_015_AUTH_USERNAME")){
	define("AAV_015_AUTH_USERNAME", $auth_username);
}

$code_lifetime = ! empty( get_option( "code_lifetime" ) ) ? get_option( "code_lifetime" ) : "10";
if( ! defined( "AAV_CODE_LIFITIME" )){
	define( "AAV_CODE_LIFITIME" , $code_lifetime );
}

$auth_password = ! empty( get_option( AAV_015_PREFIX . "_auth_password" ) ) ? get_option( AAV_015_PREFIX . "_auth_password" ) : "";
if( ! defined( "AAV_015_AUTH_PASSWORD" ) ){
	define( "AAV_015_AUTH_PASSWORD" , $auth_password );
}

$snumber = ! empty( get_option( AAV_015_PREFIX . "_snumber" ) ) ? get_option( AAV_015_PREFIX . "_snumber" ) : "";
if( ! defined( "AAV_015_SNUMBER" ) ){
	define( "AAV_015_SNUMBER" , $snumber );
}

$sms_template = ! empty( get_option( "sms_template" ) ) ? get_option( "sms_template" ) : $message_template_def;
if( ! defined( "AAV_SMS_TEMPLATE" ) ){
	define( "AAV_SMS_TEMPLATE", $sms_template );
}

$verify_confirm_form = ! empty( get_option( "aav_confirm_form_code" ) ) ? htmlspecialchars_decode( get_option( "aav_confirm_form_code" ) ) : $verify_confirm_form_def;
if( ! defined( "AAV_CONFIRM_VERIFY" ) ){
	define( "AAV_CONFIRM_VERIFY", $verify_confirm_form );
}

$verify_form = ! empty( get_option( "aav_form_code" ) ) ? htmlspecialchars_decode( get_option( "aav_form_code" ) ) : $verify_form_def;
if( ! defined( "AAV_VERIFY_FORM" ) ){
	define( "AAV_VERIFY_FORM", $verify_form );
}

$verify_form_id = ! empty( get_option( "form_verirication_id" )) ? get_option( "form_verirication_id" ) : "";
if( ! defined( "AAV_VERIFY_FORM_ID" ) ){
	define( "AAV_VERIFY_FORM_ID" , $verify_form_id );
}

$verify_form_input_name = ! empty( get_option( "form_verirication_input_tel_name" ) ) ? get_option( "form_verirication_input_tel_name" ) : "";
if( ! defined( "AAV_VERIFY_INPUT_TEL_NAME" ) ){
	define( "AAV_VERIFY_INPUT_TEL_NAME" , $verify_form_input_name );
}

$ridirect_page_id = ! empty( get_option( "aav_redirect_page" ) ) ? get_option( "aav_redirect_page" ) : "";
if( ! defined( "AAV_REDIRECT_PAGE" ) ){
	define( "AAV_REDIRECT_PAGE", intval( $ridirect_page_id ) );
}

$twilio_account_sid = ! empty( get_option( "twilio_account_sid" ) ) ? get_option( "twilio_account_sid" ) : "";
if( ! defined( "TWILIO_ACCOUNT_SID" ) ){
	define( "TWILIO_ACCOUNT_SID", $twilio_account_sid );
}

$twilio_account_token = ! empty( get_option( "twilio_account_token" ) ) ? get_option( "twilio_account_token" ) : "";
if( ! defined( "TWILIO_ACCOUNT_TOKEN" ) ){
	define( "TWILIO_ACCOUNT_TOKEN", $twilio_account_token );
}

$twilio_number_from = ! empty( get_option( "twilio_number_from" ) ) ? get_option( "twilio_number_from" ) : "";
if( ! defined( "TWILIO_NUMBER_FROM" ) ){
	define( "TWILIO_NUMBER_FROM", $twilio_number_from );
}

$smsmode_api_key = ! empty( get_option( "smsmode_api_key" ) ) ? get_option( "smsmode_api_key" ) : "";
if( ! defined( "SMSMODE_APIKEY" ) ){
	define( "SMSMODE_APIKEY", $smsmode_api_key );
}