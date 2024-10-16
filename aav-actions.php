<?php
//TODO: add default forms ids meta.

function has_verification( $type = "sms" ) {
		$out = false;

		if ( AAV_FOR_USER_LOGGED ) {
			if ( ! is_user_logged_in() ) {
				return $out;
			}

			$user 	= wp_get_current_user();
			$status = get_user_meta( $user->ID, 'verification_status', true );

			if ( ! empty( $status ) ) {
				$out = $status;
			}
		} else {
			if ( ! isset( $_COOKIE['SMSVerificationStatus'] ) ) {
				$out = false;
			} elseif ( isset( $_COOKIE['SMSVerificationStatus'] ) && 'verified' == $_COOKIE['SMSVerificationStatus'] ) {
				$out = 'verified';
			}
		}

		return $out;
}
add_filter( 'has_verification', 'has_verification' );

function aav_create_verify_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'aav_verifications';
    $chadset 	= $wpdb->get_charset_collate();

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $sql = "CREATE TABLE $table_name (
        id  bigint(20) unsigned NOT NULL auto_increment,
        phone text(55) NOT NULL,
        code int(5) NOT NULL DEFAULT 0,
        status text(55),
        user bigint(20) DEFAULT 0,
        send_date datetime,
        PRIMARY KEY  (id)
    ) $charset_collate";

    dbDelta( $sql );
}
aav_create_verify_table(); 

function default_send_settings() {
	if ( ! isset( $_POST['formData'] ) ) {
		return;
	}

	parse_str( $_POST['formData'], $inputs );

	$crypto_fields = [
		AAV_015_PREFIX . "_auth_username",
		AAV_015_PREFIX . "_auth_password",
		AAV_015_PREFIX . "_snumber",
		"twilio_account_sid",
        "twilio_account_token",
        "twilio_number_from",
	];

	foreach ( $inputs as $key => $value ) {
		$is_crypto = in_array( $key, $crypto_fields );

		if ( $is_crypto && class_exists( 'AAV_CRYPTO' ) ) {
			$crypto = new AAV_CRYPTO();
			$v = $crypto->Encode( $value );
		} else {
			$v = trim( $value );
		}

		update_option( $key, $v );
	}

	wp_send_json_success( 'ok' );
}
add_action( 'wp_ajax_default_send_settings', 'default_send_settings' );

function check_tel_input( $content ) {
    $pattern = '/<input type="tel" name="(.*?)"/i';

    if ( preg_match( $pattern, $content, $matches ) ) {
        $name = $matches[1];
        return $name;
    } else {
        return false;
    }
}

function save_form_html_settings() {
	if ( ! isset( $_POST['formData'] ) ) {
		return;
	}

	parse_str( $_POST['formData'], $inputs );

	foreach ( $inputs as $key => $value ) {
		if ( ! empty( $value ) ) {
			if ( 'aav_form_code' == $key ) {
				preg_match( '/<form id="(.*?)"/', $value, $matches );

				if ( empty( $matches ) || ! isset( $matches[1] ) || empty( $matches[1] ) ) {
					wp_send_json_error( __( "Specify the 'id' attribute for the 'form' tag (verification form)" ) );
				}

				$input_tel = check_tel_input( $value );

				if ( ! $input_tel ) {
					wp_send_json_error( __( "<input type='tel'> element or name attribute not found (verification form)" ) );
				}

				update_option( "form_verirication_input_tel_name", trim( $input_tel ) );
				update_option( "form_verirication_id", trim( $matches[1] ) );
			} elseif ( 'aav_confirm_form_code' == $key ) {
				preg_match( '/<form id="verify_confirm_form"/i', $value, $matchesF );
				preg_match( '/<input type="text" name="confirm_code"/i', $value, $matchesI );

				if ( empty( $matchesF ) || empty( $matchesI ) ) {
					wp_send_json_error( __( "Make sure the confirmation form has all required tags and attributes." ) );
					continue;
				}
			}
		}
		update_option( $key, htmlspecialchars( trim( $value ) ) );
	}

	wp_send_json_success( 'ok' );
}
add_action( 'wp_ajax_save_form_html_settings', 'save_form_html_settings' );

function print_verify_form() {
	$status 	= apply_filters( 'has_verification', false );
	$content 	= AAV_VERIFY_FORM;

	if ( 'sending' == $status ) {
		$content = AAV_CONFIRM_VERIFY;
	} elseif ( 'expired' == $status ) {
		$content = "<div class='alert warning'>" . __( 'The code has expired. Please try again to continue.' ) . "</div>" . AAV_VERIFY_FORM;
	}

	$out = "<div id='veryfication-form-wrap'>{$content}</div>";

	return $out;
}
add_shortcode( 'print_verify_form', 'print_verify_form' );

function get_sms_services_opt() {
	$options = [
		'015pbx' => [
			'label' 	=> 'Hallo 015',
			'selected'  => '015pbx' === AAV_SMS_SERVICE ? 'selected' : '',
		],
		'twilio' => [
			'label' 	=> 'Twilio',
			'selected'  => 'twilio' === AAV_SMS_SERVICE ? 'selected' : '',
		],
	];

	$html = "";

	foreach ( $options as $key => $option ) {
		$html .= "<option value='{$key}' {$option['selected']}>{$option['label']}</option>";
	}

	return $html;
}
add_filter( 'get_sms_services_opt', 'get_sms_services_opt' );

function get_pages_opt() {
	$res = "<option>" . __( 'Select a page' ) . "</option>";
	$quey_arrs = [
		'post_type' 	=> 'page',
		'post_per_page' => -1,
		'post_status' 	=> 'publish',
	];

	$pages = new WP_Query( $quey_arrs );
	wp_reset_postdata();

	if ( $pages->have_posts() ) {
		foreach ( $pages->posts as $post ) {
			$id 		= $post->ID;
			$name 		= $post->post_title;
			$selected 	= AAV_REDIRECT_PAGE == $id ? 'selected' : '';
			$res 	   .= "<option value='{$id}' {$selected}>{$name}</option>";
		}
	}

	return $res;
}
add_filter( 'aav_get_pages_opt', 'get_pages_opt' );

function generate_random_number( $length = 5 ) {
    $min = 1;
    $max = pow( 10, $length ) - 1;
    $random_number = mt_rand( $min, $max );
    return str_pad( $random_number, $length, '0', STR_PAD_LEFT );
}
add_filter( 'generate_random_number', 'generate_random_number' );

function replace_placeholders( $code ) {
    $pattern = '/\{{([a-zA-Z0-9_]+)\}}/';

    $replacement = function( $matches ) use ( $code ) {
    	if ( $matches ) {
    		return $code;
    	}
    };

    return preg_replace_callback( $pattern, $replacement, AAV_SMS_TEMPLATE );
}
add_filter( 'replace_placeholders', 'replace_placeholders', 10, 1 );

function send_sms_code( $user_phone_number ) {
	global $wpdb;
	$crypt      = new AAV_CRYPTO();
	$code 		= apply_filters( 'generate_random_number', 4 );
	$message 	= apply_filters( 'replace_placeholders', $code );
	$out        = [];
    $table 		= $wpdb->prefix . 'aav_verifications';
    $test_q 	= $wpdb->get_col( "SELECT id FROM $table WHERE phone='" . $user_phone_number . "' AND status = 'sending'" );

    if ( ! empty( $test_q ) ) {
    	$out['SUCCESS'] = $code;
    	return $out;
    }

	if ( '015pbx' === AAV_SMS_SERVICE ) {
		$send_response = new PBX_015( $message, $user_phone_number, $code );
		$out = $send_response->message_response;
	} elseif ( 'twilio' === AAV_SMS_SERVICE ) {
		$send_response = new AAV_TWILIO( $message, $user_phone_number, $code );
		$out = $send_response->response;
	}

	return $out;
}

function save_sms_code( $code, $phone ) {
	$out = 0;
	if( AAV_FOR_USER_LOGGED ) {
		if ( ! is_user_logged_in() ) {
			return -1;
		}

		$user 	= wp_get_current_user();
		$status = update_user_meta( $user->ID, 'verification_status', 'sending' );
		$v_code = update_user_meta( $user->ID, 'verification_code', $code );
		if ( $phone ) {
			update_user_meta( $user->ID, 'verification_phone', $phone );
		}

		$out = 200;
	} else {
		global $wpdb;
        $table = $wpdb->prefix . 'aav_verifications';
        $test_q = $wpdb->get_col( "SELECT id FROM $table WHERE phone='" . $phone . "'" );
        $uid    = 0;
        if ( is_user_logged_in() ) {
			$user 	= wp_get_current_user();
			$uid    = $user->ID;
		}

        $data['phone'] 		= $phone;
        $data['code'] 		= $code;
        $data['status'] 	= 'sending';
        $data['user'] 		= $uid;
        $data['send_date'] 	= date( 'Y-m-d H:i:s' );

        if ( empty( $test_q ) ) {
        	$q = $wpdb->insert( $table, $data );
        } else {
        	$where = array( "phone" => "$phone" );
        	$q = $wpdb->update( $table, $data, $where );
        }
        if ( $wpdb->last_error ) {
        	$out = 400;
        } else {
        	$out = 200;
        }
	}

	return $out;
}

function clear_verify_code( $param ) {
	if ( AAV_FOR_USER_LOGGED ) {
		update_user_meta( $param, 'verification_status', 'expired' );
		delete_user_meta( $param, 'verification_code' );
	} else {
		global $wpdb;
        $table 	= $wpdb->prefix . 'aav_verifications';
        $status = $wpdb->get_col( "SELECT status FROM $table WHERE phone='" . $param . "'" );

        if ( 'sending' == $status[0] ) {
        	$new_data['code'] 		= 0;
		    $new_data['status'] 	= 'expired';
		    $new_data['send_date'] 	= date( 'Y-m-d H:i:s' );
			$where 					= array( 'phone' => $param );
	        $wpdb->update( $table, $new_data, $where );
        }
	}
}
add_action( 'clear_verify_code', 'clear_verify_code' );

function processing_verification_data() {
	if ( ! isset( $_POST['verifyFormData'] ) ) {
		return;
	}

	parse_str( $_POST['verifyFormData'], $data );
	foreach ( $data as $k => $v ) {
		if ( AAV_VERIFY_INPUT_TEL_NAME == $k ) {
			$user 		   = wp_get_current_user();
			$send_code_res = send_sms_code( $v );

			if ( array_key_exists( 'ERROR', $send_code_res ) ) {
				wp_send_json_error( $send_code_res['ERROR'] );
			} elseif ( array_key_exists( 'SUCCESS', $send_code_res ) ) {
				$save = save_sms_code( $send_code_res['SUCCESS'], $v );

				if ( 200 == $save ) {
					if ( intval( AAV_CODE_LIFITIME ) > 0 ) {
						$arg = AAV_FOR_USER_LOGGED ? $user->ID : $v;
						wp_schedule_single_event( time() + 60 * intval( AAV_CODE_LIFITIME ), 'clear_verify_code' , [ $arg ] );
					}

					wp_send_json_success( AAV_CONFIRM_VERIFY );
				} elseif ( -1 == $save ) {
					wp_send_json_error( __( 'Register or log in to your account' ) );
				} elseif ( 400 == $save ) {
					wp_send_json_error( __( 'ERROR' ) );
				}
			}
		}
	}
}
add_action( 'wp_ajax_processing_verification_data', 'processing_verification_data' );
add_action( 'wp_ajax_nopriv_processing_verification_data', 'processing_verification_data' );

function get_cookie_lifetime() {
	$cookie_life_period = ! empty( get_option( "coockie_lifetime_period" ) ) ? get_option( "coockie_lifetime_period" ) : "hours";
	$cookie_life_value = ! empty( get_option( "coockie_lifetime" ) ) ? get_option( "coockie_lifetime" ) : 1;

	$period_seconds = [
		"hours" 	=> 60 * 60,
		"days"  	=> 60 * 60 * 24,
		"weeks" 	=> 60 * 60 * 24 * 7,
		"months" 	=> 60 * 60 * 24 * 7 * 30,
	];

	return $period_seconds[ $cookie_life_period ] * intval( $cookie_life_value );
}
add_filter( 'get_cookie_lifetime', 'get_cookie_lifetime' );

function confirm_sms_code() {
	if ( ! isset( $_POST['confirmVerifyCode'] ) ) {
		return;
	}	

	parse_str( $_POST['confirmVerifyCode'], $data );
	if ( '' == $data['confirm_code'] ) {
		wp_send_json_error( __( 'Enter code' ) );
	}
	$data_code = intval( $data['confirm_code'] );

	if ( AAV_FOR_USER_LOGGED ) {
		$user 	= wp_get_current_user();
		$v_code = get_user_meta( $user->ID, 'verification_code' );

		if ( $data['confirm_code'] == $v_code ) {
			update_user_meta( $user->ID, 'verification_status', 'verified' );
			delete_user_meta( $user->ID, 'verification_code' );

			wp_send_json_success( 'verified' );
		} else {
			wp_send_json_error( __( 'The code you entered is incorrect.' ) );
		}
	} else {
		global $wpdb;
        $table 			= $wpdb->prefix . 'aav_verifications';
		$revision_code  = $wpdb->get_col( "SELECT id FROM $table WHERE code = $data_code" );

		if ( ! empty( $revision_code ) ) {
			$cookie_time 			= apply_filters( 'get_cookie_lifetime', true );
			$id 					= intval( $revision_code[0] );
	        $new_data['code'] 		= 0;
	        $new_data['status'] 	= 'verified';
	        $new_data['send_date'] 	= date( 'Y-m-d H:i:s' );
			$where 					= array( 'id' => $id );
        	$q 						= $wpdb->update( $table, $new_data, $where );

        	setcookie( 'SMSVerificationStatus', 'verified', time() + $cookie_time, '/', $_SERVER['HTTP_HOST'], false, true );
        	wp_send_json_success( 'verified' );
		} else {
			wp_send_json_error( __('The code you entered is incorrect.') );
		}
	}
}
add_action( 'wp_ajax_confirm_sms_code', 'confirm_sms_code' );
add_action( 'wp_ajax_nopriv_confirm_sms_code', 'confirm_sms_code' );

function aav_pagination( int $current_page, int $max_page, string $url_param ) {
    $page_url = get_the_permalink();
    $html = "<div class='pagination content__pagination' data-max_pages='{$max_page}'>";
            $p = paginate_links( [
                    "base"               => wp_normalize_path( "?{$url_param}=%#%" ),
                    "format"             => "?{$url_param}=%#%",
                    "total"              => $max_page,
                    "current"            => $current_page,
                    "aria_current"       => "page",
                    "show_all"           => false,
                    "prev_next"          => true,
                    "prev_text"          => "<",
                    "next_text"          => ">",
                    "end_size"           => 2,
                    "mid_size"           => 2,
                    "type"               => "array",
                ] );

            	if ( ! is_null( $p ) ) {
            		foreach ( $p as $l ) {
	                    $current_class = "";
	                    if ( '<span aria-current="page" class="pagination__item">' . $current_page . '</span>' == $l ) {
	                        $current_class = "pagination__item active";
	                    }
	                    $html .= "<li class='{$current_class}'>{$l}</li>";
                	}
            	}

            $html .= "</div>";

        return $html;
}
add_filter( 'aav_pagination', 'aav_pagination', 10, 3) ;

function get_verify_rows() {
    global $wpdb;
    $table                      = $wpdb->prefix . 'aav_verifications';
    $limit                      = 100;
    $page                       = isset( $_REQUEST['vpage'] ) ? intval( $_REQUEST['vpage'] ) : 1;
	$order                      = "send_date";
	$order_by 					= "DESC"; // ASC||DESC
    $offset                     = ( $page-1 ) * $limit;
    $query                      = "SELECT * FROM $table";
    $count_query                = "SELECT COUNT(*) FROM $table";

    $users_count = (int) $wpdb->get_var( $count_query );
    $pages       = ceil( $users_count / $limit );

	$query .= " ORDER BY $order $order_by";
    $query .= " LIMIT $limit OFFSET $offset";

    $res = $wpdb->get_results( $query );

    $out = [
        'page'          => $page,
        'pages'         => $pages,
        'count'         => $users_count,
        'res'         	=> $res,
    ];

    return $out;
}
add_filter( 'aav_get_verify_rows', 'get_verify_rows' );

function cookie_period_options() {
	$cookie_life_period 	= ! empty( get_option( "coockie_lifetime_period" ) ) ? get_option( "coockie_lifetime_period" ) : "hours";
	$cookie_life_value 		= ! empty( get_option( "coockie_lifetime" ) ) ? get_option( "coockie_lifetime" ) : 1;
	$options_array 	= [
		"hours"	=> [
			"label" 	=> __( "Hours" ),
			"selected"	=> "hours" == $cookie_life_period ? "selected" : "",
		],
		"days"	=> [
			"label" 	=> __( "Days" ),
			"selected"	=> "days" == $cookie_life_period ? "selected" : "",
		],
		"weeks"	=> [
			"label" 	=> __( "Weeks" ),
			"selected"	=> "weeks" == $cookie_life_period ? "selected" : "",
		],
		"months"	=> [
			"label" 	=> __( "Months (30 days)" ),
			"selected"	=> "weeks" == $cookie_life_period ? "selected" : "",
		],
	];

	$html = "";
	foreach ( $options_array as $key => $args ) {
		$selected = $args["selected"];
		$label 	  = $args["label"];
		$html .= "<option value='{$key}' {$selected}>{$label}</option>";
	}
	return $html;
}
add_filter( 'get_cookie_period_options', 'cookie_period_options' );

function bulk_contacts_form_action() {
	if( ! isset( $_POST[ 'applyAction' ] )  || ! isset( $_POST[ 'uids' ] ) ) {
		return;
	}
	global $wpdb;
	$table 		 = $wpdb->prefix . 'aav_verifications';
	$action 	 = $_POST[ 'applyAction' ];
	$uids   	 = array_map( 'intval', $_POST[ 'uids' ] );
	$userIds_str = implode( ',', $uids );

	if( 'delete' === $action ) {
		$query = "DELETE FROM $table WHERE id IN ( $userIds_str )";
        $result = $wpdb->query( $query );

		if ( false === $result ) {
			wp_send_json_error( "Failed to operation." );
        } else {
			wp_send_json_success( 200 );
        }
	}
}
add_action( 'wp_ajax_bulk_contacts_form_action', 'bulk_contacts_form_action' );