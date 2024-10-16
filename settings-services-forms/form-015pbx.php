<form class="aav-form" id="015pbx_settings" data-action="default_send_settings">
    <?php
		if( file_exists( AAV_PLUGIN_DIR . "images/twilio.png" ) ) {
			echo "<img src='" .AAV_PLUGIN_DIR_URL. "images/015pbx.png' width='150' style='padding-top: 1rem;'/>";
		} else {
			echo "<h2>Twilio</h2>";
		}
	?>

    <table class="form-table">
        <tr>
            <th scope="row">
                <?php echo __( "Code Lifetime (minute)" ) ?>
            </th>

            <td>
                <?php include_once AAV_PLUGIN_DIR . "/settings-services-forms/default-inputs/code-lifetime.php"; ?>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <?php echo __( "Verifiation coockie Lifetime (period)" ) ?>
            </th>

            <td>
                <?php include_once AAV_PLUGIN_DIR . "/settings-services-forms/default-inputs/coockie-lifetime-period.php"; ?>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <?php echo __( "Verifiation coockie Lifetime (value)" ) ?>
            </th>

            <td>
                <?php include_once AAV_PLUGIN_DIR . "/settings-services-forms/default-inputs/coockie-lifetime.php"; ?>
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
                <?php include_once AAV_PLUGIN_DIR . "/settings-services-forms/default-inputs/message-tmpl.php"; ?>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <?php echo __( "Redirect user to page if access is denied." ) ?>
            </th>

            <td>
                <?php include_once AAV_PLUGIN_DIR . "/settings-services-forms/default-inputs/redirect-page.php"; ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <?php include_once AAV_PLUGIN_DIR . "/settings-services-forms/default-inputs/submit.php"; ?>
            </td>
        </tr>
    </table>
</form>