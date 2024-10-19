<form class="aav-form" id="smsmode_settings" data-action="default_send_settings">    
    <?php
		if( file_exists( AAV_PLUGIN_DIR . "images/smsmode.png" ) ) {
			echo "<img src='" .AAV_PLUGIN_DIR_URL. "images/smsmode.png' width='150' style='margin-top: 1rem;'/>";
		} else {
			echo "<h2>Smsmode</h2>";
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
                <?php echo __( "Api key" ) ?>*
            </th>

            <td>
                <input type="text" 
                    name="smsmode_api_key" 
                    required class="regular-text" 
                    value="<?php echo esc_attr( $crypto->Decode( SMSMODE_APIKEY ) ) ?>">
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
