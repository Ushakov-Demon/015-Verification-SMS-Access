<?php
$editor_settings_arr = [
	'media_buttons' => false,
];
$annotation_str 		= sprintf( __( "Use the shortcode %s to insert the form into the content." ), "[print_verify_form]" );
$services_options 		= apply_filters( "get_sms_services_opt", true );
$service                = AAV_SMS_SERVICE;
$random_num 			= apply_filters( "generate_random_number", 5 );
$cookie_life_period 	= ! empty( get_option( "coockie_lifetime_period" ) ) ? get_option( "coockie_lifetime_period" ) : "hours";
$crypto     			= new AAV_CRYPTO();
?>

<div class='aaf-settings-container'>
	<h1>
		<?php echo __( get_admin_page_title() ) ?>
	</h1>

	<?php
		if( AAV_VERIFY_FORM !== "" ) :
			echo "<p>
					<strong style='color:green;'>**{$annotation_str}</strong>
				</p>";
		endif;	
	?>

	<select name="aav_sms_service" class="regular-text services-select">
		<option value=""><?php echo __( 'Select SMS service' )?></option>
		<?php echo $services_options?>
	</select>

	<?php
		include_once AAV_PLUGIN_DIR . "/settings-services-forms/form-$service.php";
	?>

	<form class="aav-form" id="aav_form_code_settings" data-action="save_form_html_settings">
		<h2>
			<?php echo __( "Verification form HTML." ) ?>
		</h2>

		<h3>
			<?php echo sprintf( __( "*The %s tag must contain the id attribute." ), "<code>&lt;form&gt;</code>" ) ?>
			<br/>

			<?php echo sprintf( __( "*The form must contain the %s tag, and the 'name' attribute." ), "<code>&lt;input type='tel'&gt;</code>" ) ?>
		</h3>

		<?php
			wp_editor( AAV_VERIFY_FORM , 'aav_form_code', $editor_settings_arr );
		?>

		<h2>
			<?php echo __( "Confirm code form HTML." ) ?>
		</h2>

		<h3>
			<?php echo sprintf( __( "*The %s tag must contain the %s, && %s." ), 
									"<code>&lt;form&gt;</code>", 
									"<code>id='verify_confirm_form'</code>",
									"<code>&lt;input type='text' name='confirm_code'&gt;</code>" ) ?>
		</h3>

		<?php
			wp_editor( AAV_CONFIRM_VERIFY , 'aav_confirm_form_code', $editor_settings_arr );
		?>

		<fieldset>
			<p>
				<input class="button button-primary" type='submit' value="<?php echo __( 'Send' ) ?>">
			</p>
		</fieldset>
	</form>	
</div>