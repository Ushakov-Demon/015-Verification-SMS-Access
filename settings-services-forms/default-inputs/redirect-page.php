<?php
    $pages_opt = apply_filters( "aav_get_pages_opt", true );
?>
<select name="aav_redirect_page">
    <?php echo $pages_opt ?>
</select>

<p class="description">
    <?php echo __( "Select the page for redirection on access denial." ) ?>
</p>

<p class="description warning">
    <strong style="color: #6e3704">
        <?php echo __( "!!! WARNING !!!: We allow you to display the form on a secure page, but this is not the most secure method. WE RECOMMEND USING THE REDIRECT METHOD instead." ) ?>
    </strong>
</p>