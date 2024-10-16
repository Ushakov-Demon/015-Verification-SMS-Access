<?php
    $cookie_life_value 		= ! empty( get_option( "coockie_lifetime" ) ) ? get_option( "coockie_lifetime" ) : 1;
?>
<input type="number" 
    step="1" 
    min="1" 
    name="coockie_lifetime" 
    class="regular-text" 
    value="<?php echo $cookie_life_value ?>">