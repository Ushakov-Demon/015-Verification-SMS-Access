(function( $ ) {
    // Vars
    let selectAllContacts  = $( 'input[name="select-all-contacts"]' );
    let contactsListTable  = $( 'table.verify-contacts' );
    let contactsCheckboxes = contactsListTable.find( '.column-checkbox input[type="checkbox"]' );
    let bulkContactsForm   = $( '.bulk-verify-contacts' );

    $( '.services-select' ).on( 'change', function() {
        let inputName = $( this ).attr( 'name' );
        let inputVal  = $( this ).val();
        
        let data = inputName + '=' + encodeURIComponent( inputVal );

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
            	action: 'default_send_settings',
            	formData: data,
            },
            success: function(resp){
                if( true == resp[ 'success' ] ){
                    window.location.reload();
                }else{
                    alert( resp[ 'data' ] );
                }
            }
        });
     } )

    // Default ajax action
	$('.aav-form').submit( function(e) {
		e.preventDefault();
		let action 		= $( this ).data( 'action' );
		let formData 	= $( this ).serialize();

		$.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajaxurl,
            data: {
            	action: action,
            	formData: formData,
            },
            success: function(resp){
                if( true == resp[ 'success' ] ){
                    window.location.reload();
                }else{
                    alert( resp[ 'data' ] );
                }
            }
        });
	})

    // Cecked||unchecked all verifyde contacts
    selectAllContacts.change( function(e) {
        let checkedStatus = $( this ).prop( 'checked' );
        contactsCheckboxes.each( function( i, el ) {
            $( el ).prop( 'checked', checkedStatus );
        } );
    } );

    // Bulk action
    bulkContactsForm.on( 'submit', function(event) {
        event.preventDefault();
        let checkedContacts = contactsListTable.find( '.column-checkbox input[type="checkbox"]:checked' );
        let bulkAction = $( this ).find( '[name="action"]' ).val();
        let ids = [];
        let alertText = 'You are initiating a "'+ bulkAction +'" operation. Kindly note that this action is irreversible upon confirmation. Would you like to continue?';

        if( checkedContacts.length == 0 || '' === bulkAction ) {
            return;
        }

        checkedContacts.each( function( inx, input ) {
            ids.push( $( input ).val() );
        } );

        if( confirm( alertText ) ) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    action: 'bulk_contacts_form_action',
                    applyAction: bulkAction,
                    uids: ids,
                },
                success: function(resp){
                    if( true == resp[ 'success' ] ){
                        window.location.reload();
                    } else if ( false == resp[ 'success' ] ) {
                        alert( resp[ 'data' ] );
                    } else {
                        return;
                    }
                }
            });
        } else {
            return;
        }
    });

}( jQuery ))