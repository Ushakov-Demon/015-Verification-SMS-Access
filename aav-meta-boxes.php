<?php
function aav_add_metabox() {
    add_meta_box(
        'show_verifications',
        'Show SMS Verifications users only',
        'aav_veri_metabox_callback',
        ['post', 'page'],
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aav_add_metabox' );

function aav_veri_metabox_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'show_verifications_nonce' );

    $show_verifications = get_post_meta( $post->ID, 'show_verifications', true );
    $options = [
        'off' => [
            'label' => 'Off',
            'selected' => $show_verifications === 'off',
        ],
        'on'  => [
            'label' => 'On',
            'selected' => $show_verifications === 'on',
        ],
    ];
    ?>
    <select name="show_verifications">
        <?php foreach ( $options as $v => $option ) : ?>
            <option value="<?php echo esc_attr( $v ); ?>" <?php echo $option['selected'] ? 'selected' : ''; ?>>
                <?php echo esc_html( $option['label'] ) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function aav_sms_veri_metabox_data( $post_id ) {
    if ( ! isset( $_POST['show_verifications'] ) || ! wp_verify_nonce( $_POST['show_verifications_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	if ( ! isset( $_POST['show_verifications'] ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
	 	return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

    if( AAV_REDIRECT_PAGE == $post_id ){
    	delete_post_meta( $post_id, 'show_verifications' );
		return;
	}

    update_post_meta( $post_id, 'show_verifications', sanitize_text_field( $_POST['show_verifications'] ) );
}
add_action( 'save_post', 'aav_sms_veri_metabox_data' );

function add_restricted_page_class( $classes ) {
    $classes[] = 'verifications-only';
    return $classes;
}

function aav_restrict_page_access() {
    global $post;

    $is_for_verifications = get_post_meta( $post->ID, "show_verifications", true );
    $is_verification_user = apply_filters( 'has_verification', false );

    if( 'on' == $is_for_verifications && 'verified' !== $is_verification_user ){
    	if( "" !== AAV_REDIRECT_PAGE && AAV_REDIRECT_PAGE > 0 ){
    		$url = get_the_permalink( AAV_REDIRECT_PAGE );
    		wp_redirect( $url );
         	exit();
    	}else{
			if ( function_exists( 'wp_cache_disable' ) ) {
            	wp_cache_disable();
        	};
			add_filter( 'body_class', 'add_restricted_page_class' );

    		$content = do_shortcode( '[print_verify_form]' );

    		echo "<div class='full-window-container'>
					<div class='centered-content-wrapper'>
						{$content}
					</div>
    			</div>";
    	}
    }
}
add_action( 'template_redirect', 'aav_restrict_page_access' );