(function($){
	$( document ).ready( function() {
	  let verifyForm = $( "#"+aav_vars['verify_form_id'] );

	  if( $('body').hasClass('verifications-only') ) {
		document.oncontextmenu = function() {
			return false;
		};

		document.addEventListener('keydown', (event) => {
			const isCtrlOrMeta = event.ctrlKey || event.metaKey;
			const isUKey = event.key === 'u';
		
			if ((isCtrlOrMeta && isUKey) || event.code === 'F12') {
				event.preventDefault();
			}
		});
	  }
  
	  verifyForm.on( 'submit', function( e ) {
		e.preventDefault();
		let formInputsData = $( this ).serialize();
  
		$.ajax({
				type: 'POST',
				dataType: 'json',
				url: aav_vars['ajax_url'],
				data: {
				  action: 'processing_verification_data',
				  verifyFormData: formInputsData
				},
				success: function( resp ) {
					if ( true === resp['success'] ) {
						$( "#veryfication-form-wrap" ).html( resp['data'] );
						if( parseInt( aav_vars[ 'verify_code_lifetime' ] ) > 0 ) {
						  let time = parseInt( aav_vars['verify_code_lifetime'] )*60*1000;
						  setTimeout( function() {
							window.location.reload();
						  }, time );
						}
					}else{
						alert( resp['data'] );
					}
				}
			} );
	  } )
  
	  $( document ).on( 'click', '.reload-page', function( e ) {
		e.preventDefault();
		window.location.reload();
	  } )
  
	  $(document).on( 'submit', '#verify_confirm_form', function( e ) {
		e.preventDefault();
  
		let formInputsData = $( this ).serialize();
		let formContainer  = $( ".full-window-container" );
  
		$.ajax( {
				type: 'POST',
				dataType: 'json',
				url: aav_vars['ajax_url'],
				data: {
				  action: 'confirm_sms_code',
				  confirmVerifyCode: formInputsData
				},
				success: function( resp ) {
					if( true === resp['success'] ) {
						$( "#veryfication-form-wrap" ).html( "<p>Congratulations! You have successfully passed verification</p>" );
  
						if ( formContainer.length > 0 ) {
						  $( "#veryfication-form-wrap" ).append( "<button class='reload-page'>Close</button>" );
						}
					} else {
						alert( resp['data'] );
					}
				}
			} );
	  	} )
	} )
  } ( jQuery ) )