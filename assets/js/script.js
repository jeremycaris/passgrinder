// Check for jQuery
if(typeof jQuery == 'undefined'){
    document.write('<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></'+'script>');
} else {
    console.log( "PassGrinder: jQuery loaded." );
}



// Check for font-awesome
// Variation based on https://codepen.io/AllThingsSmitty/pen/YqjBqW
jQuery( document ).ready( function($) {
    function css(element, property) {
        return window.getComputedStyle(element, null).getPropertyValue(property);
    }
    
    var span = document.createElement('span');

    span.className = 'fa';
    span.style.display = 'none';
    document.body.insertBefore(span, document.body.firstChild);

    if ((css(span, 'font-family')) !== 'FontAwesome') {
        document.write('<link rel="stylesheet" id="fontawesome-css"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css" type="text/css" media="all" />');
    } else {
        console.log("PassGrinder: Font-Awesome loaded.");
    }
    document.body.removeChild(span);
});



// Check for Bootstrap
jQuery( document ).ready( function($) {
    // Will be true if Bootstrap 3-4 is loaded, false if Bootstrap 2 or no Bootstrap
    var bootstrap_enabled = (typeof $().emulateTransitionEnd == 'function');
    if (bootstrap_enabled == 'false') {
        document.write('<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></'+'script>');
        document.write('<link rel="stylesheet" id="bootstrap-css  href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/cerulean/bootstrap.min.css" type="text/css" />');
    } else {
        console.log( "PassGrinder: Bootstrap loaded." );
    }
});



// Toggle password field visibility
jQuery( document ).ready( function($) {
    $("body").on('click', '.toggle-password', function() {
        $("i", this).toggleClass("fa-eye-slash");
        
        var input = $(this).parent().prev();
        
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});


    
// Hide generated password field and response on reset
jQuery( document ).ready( function($) {
    $("input[type='reset']").closest('form').on('reset', function(event) {
        $("#pg-result").hide();
        $('#pg-message #success').html("");
        $('#pg-message #fail').html("");
        $('#pg-message #reset').html("");
    });
});



// shortcode_form functionality
jQuery( document ).ready( function($) {
    $( '#passgrinder-form' ).submit(function(e){
        e.preventDefault();
        
        // Hash the password/s
        var md5;
        if ( $.md5($('#pg-password').val(), null, true) !== '' ) {
            md5 = $.md5($('#pg-password').val(), null, true);
        }
        if ( $.md5($('#pg-salt').val(), null, true) !== '' ) {
            md5 = md5 + $.md5($('#pg-salt').val(), null, true);
        }
        if ( $.md5($('[name="pg-variation"]:checked').val(), null, true) !== '0' ) {
            md5 = md5 + $.md5($('[name="pg-variation"]:checked').val(), null, true);
        }
        if ( md5 !== '' ) {
            md5 = $.md5(md5, null, true);
        }
        var pg_z85=encodeZ85.encode(md5);
        
        
        // Set ajax data
        var data = {
            'action' : 'eval_helper',
            'pass'   : $('#pg-password').val(),
            'salt'   : $('#pg-salt').val(),
            'vari'   : $('[name="pg-variation"]:checked').val(),
            'nonce'  : settings.ajaxnonce,
        };
        
        
        // Form post action and response handling
        $.post(settings.ajaxurl, data, function(response) {
//            console.log('Encoded: '+pg_z85);
//            console.log( data );
            console.log( "PassGrinder: " );
            console.log( response );
            
            $("#pg-result").show();
            $("#pg-result-pass").val(pg_z85);
            
            // Copy pass to clipboard automatically
            var input = $("#pg-result-pass"), userstate;
            if (input.attr("type") === "password") { // Check for current field type (password or test)
                input.attr("type", "text"); // If password, change to text so we can copy it
                userstate = 1; // Indicate previous user-set state so we know whether or not to change back
            }
            $("#pg-result-pass").select();
            document.execCommand("copy");
            if (userstate == 1) {
                input.attr("type", "password"); 
                userstate = null;
            }
            
            // Send response
            if ( response.success == true ) {
                $('#pg-message #success').html( response.data );
            } else {
                $('#pg-message #fail').html( response.data );
            }
            
            // Auto reset form after time
            if ( $("#pg-result-pass").val() ) {
                setTimeout( function() { 
                    $("#passgrinder-form").trigger('reset'); 
                    $('#pg-message #reset').html("Form has automatically reset to protect your password."); 
                    console.log( "PassGrinder: For automatically reset" ); 
                }, 30000);
            }
            
        });
        
    });
    
});



