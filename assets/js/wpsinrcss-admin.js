jQuery(document).ready(function($) {
    // select all 
    $('#cb-select-all-1').click(function(event) {   
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;                        
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;                       
            });
        }
    });
    // #doaction2 click prevent default.
    $('#doaction2').click(function(event) {
        event.preventDefault();
        // get selected action.
        var action = $('#bulk-action-selector-bottom').val();
        // get selected subscriptions.
        var subscriptions = [];
        $('input[name="subscriptions[]"]:checked').each(function() {
            subscriptions.push($(this).val());
        });
        // if no action selected.
        if ( '-1' === action ) {
            alert('Please select an action.');
            return;
        }
        // if no subscriptions selected.
        if ( 0 === subscriptions.length ) {
            alert('Please select at least one subscription.');
            return;
        }
        // if restore action selected.
        if ( 'restore' === action ) {
            // confirm.
            var confirm = window.confirm('Are you sure you want to restore the selected subscriptions?');
            if ( confirm ) {
                let nonce = $('#wpsinrcss-bulk-action-nonce').val();
                // ajax.
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'rcp_restore_cancelled_subscriptions',
                        subscriptions: subscriptions,
                        nonce: nonce,
                    },
                    success: function( response ) {
                        if ( response.success ) {
                            alert( response.data + ' were restored successfully.' );
                        } else {
                            alert( response.data );
                        }
                        // reload page.
                        location.reload();
                    },
                    error: function( response ) {
                        // confirm the error message.
                        alert( response.data );
                        console.log( response );
                    }
                });
            }
        }
    });

});