<?php
$editor_settings_arr = [
	'media_buttons' => false,
];
$annotation_str 		= sprintf( __( "Use the shortcode %s to insert the form into the content." ), "[print_verify_form]" );
$pages_opt  			= apply_filters( "aav_get_pages_opt", true );
$random_num 			= apply_filters( "generate_random_number", 5 );
$cookie_life_period 	= ! empty( get_option( "coockie_lifetime_period" ) ) ? get_option( "coockie_lifetime_period" ) : "hours";
$cookie_life_value 		= ! empty( get_option( "coockie_lifetime" ) ) ? get_option( "coockie_lifetime" ) : 1;
$cookie_period_options 	= apply_filters( 'get_cookie_period_options', true );
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

	<form class="aav-form" id="015pbx_settings" data-action="default_send_settings">
		<h2>015pbx</h2>

		<table class="form-table">
			<tr>
				<th scope="row">
					<?php echo __( "Code Lifetime (minute)" ) ?>
				</th>

				<td>
					<input type="number" 
						step="1" 
						min="5" 
						max="60" 
						name="code_lifetime" 
						class="regular-text" 
						value="<?php echo esc_attr( AAV_CODE_LIFITIME ) ?>">

					<p class="description">
						<?php echo __( "Enter the lifetime in minutes." ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "Verifiation coockie Lifetime (period)" ) ?>
				</th>

				<td>
					<select name="coockie_lifetime_period" class="regular-text">
						<?php echo $cookie_period_options ?>
					</select>	
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "Verifiation coockie Lifetime (value)" ) ?>
				</th>

				<td>
					<input type="number" 
						step="1" 
						min="1" 
						name="coockie_lifetime" 
						class="regular-text" 
						value="<?php echo $cookie_life_value ?>">
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "Authentication username" ) ?>*
				</th>

				<td>
					<input type="text" 
						name="<?php echo esc_attr( AAV_015_PREFIX ) ?>_auth_username" 
						required class="regular-text" 
						value="<?php echo esc_attr( $crypto->Decode( AAV_015_AUTH_USERNAME ) ) ?>">
					
					<p class="description">
						<?php echo __( "Enter your authentication username." ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "Authentication password" ) ?>*
				</th>

				<td>
					<input type="text" 
						name="<?php echo esc_attr( AAV_015_PREFIX ) ?>_auth_password" 
						required class="regular-text" 
						value="<?php echo esc_attr( $crypto->Decode( AAV_015_AUTH_PASSWORD ) ) ?>">

					<p class="description">
						<?php echo __( "Enter your authentication password." ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "The telephone line or number to send from" ) ?>*
				</th>

				<td>
					<input type="text" 
						name="<?php echo esc_attr( AAV_015_PREFIX ) ?>_snumber" 
						required class="regular-text" 
						value="<?php echo esc_attr( $crypto->Decode( AAV_015_SNUMBER ) ) ?>">
					
					<p class="description">
						<?php echo __( "Enter the phone number or line to send from." ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "Message template." ) ?>
				</th>

				<td>
					<textarea name="sms_template" cols="50" rows="10" class="large-text">
						<?php echo AAV_SMS_TEMPLATE ?>
					</textarea>

					<p class="description">
						<?php echo __( "Enter the template for your SMS message." ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<?php echo __( "Redirect user to page if access is denied." ) ?>
				</th>

				<td>
					<select name="aav_redirect_page">
						<?php echo $pages_opt ?>
					</select>

					<p class="description">
						<?php echo __( "Select the page for redirection on access denial." ) ?>
					</p>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<input class="button button-primary" type="submit" value="<?php echo __( 'Send' ) ?>">
				</td>
			</tr>
		</table>
	</form>

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