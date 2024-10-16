<?php
$cookie_period_options 	= apply_filters( 'get_cookie_period_options', true );
?>
<select name="coockie_lifetime_period" class="regular-text">
    <?php echo $cookie_period_options ?>
</select>