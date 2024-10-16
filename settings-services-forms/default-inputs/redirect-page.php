<?php
    $pages_opt = apply_filters( "aav_get_pages_opt", true );
?>
<select name="aav_redirect_page">
    <?php echo $pages_opt ?>
</select>

<p class="description">
    <?php echo __( "Select the page for redirection on access denial." ) ?>
</p>