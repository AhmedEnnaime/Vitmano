( function ( $ ) {
    // Menu fixes
    function onResizeMenuLayout() {
        if ( $( window ).width() > 767 ) {
            $( ".main-menu" ).on( 'hover', '.dropdown', function () {
                $( this ).addClass( 'open' )
            },
                function () {
                    $( this ).removeClass( 'open' )
                }
            );
            $( ".dropdown" ).on( 'focusin',
                function () {
                    $( this ).addClass( 'open' )
                }
            );
            $( ".dropdown" ).on( 'focusout',
                function () {
                    $( this ).removeClass( 'open' )
                }
            );

        } else {
            $( ".dropdown" ).on( 'hover',
                function () {
                    $( this ).removeClass( 'open' )
                }
            );
        }

        $( '#menu-categories-menu' ).on( 'focusout', function ( e ) {
            setTimeout( function () { // needed because nothing has focus during 'focusout'
                if ( $( ':focus' ).closest( '#menu-categories-menu' ).length <= 0 ) {
                    $( "#menu-categories-menu" ).removeClass( "open" );
                }
            }, 0 );
        } );
    }
    ;
    // initial state
    onResizeMenuLayout();
    // on resize
    $( window ).on( 'resize', onResizeMenuLayout );
    
    $( ".envo-categories-menu-first" ).on( 'click hover', function () {
        $( "#menu-categories-menu" ).toggleClass( "open" );
    } );

    $( ".main-menu" ).on( 'hover', '.navbar .dropdown-toggle', function () {
        $( this ).addClass( 'disabled' );
    } );
    $( '.navbar .dropdown-toggle' ).on( 'focus', function () {
        $( this ).addClass( 'disabled' );
    } );

    var $myDiv = $( '#theme-menu' );

    $( document ).ready( function () {
        if ( $myDiv.length ) {
            mmenu = mmlight( document.querySelector( "#theme-menu" ) );
            mmenu.create( "(max-width: 767px)" );
            mmenu.init( "selected" );
            $( "#main-menu-panel" ).on( 'click', function ( e ) {
                e.preventDefault();
                $( "#theme-menu" ).appendTo( ".navbar-header" );
                if ( $( "#theme-menu" ).hasClass( "mm--open" ) ) {
                    mmenu.close();
                } else {
                    mmenu.open();
                    $( "#theme-menu li:first" ).focus();
                    $( "a.dropdown-toggle" ).focusin(
                        function () {
                            $( '.dropdown' ).addClass( 'open' )
                        }
                    );
                    $( "#theme-menu li:last" ).on( 'focusout',
                        function () {
                            mmenu.close();
                        }
                    );
                    $( '#theme-menu' ).on( 'focusout', function ( e ) {
                        setTimeout( function () { // needed because nothing has focus during 'focusout'
                            if ( $( ':focus' ).closest( '#theme-menu' ).length <= 0 ) {
                                mmenu.close();
                                $( "a#main-menu-panel" ).focus();
                            }
                        }, 0 );
                    } );
                    $( "#main-menu-panel" ).on( 'focuin',
                        function () {
                            mmenu.close();
                        }
                    );
                    $( "#main-menu-panel" ).on( 'keydown blur', function ( e ) {
                        if ( e.shiftKey && e.keyCode === 9 ) {
                            mmenu.close();
                        }
                    } );
                }
                e.stopPropagation();
            } );
        }
    } );

    $( 'form.cart' ).on( 'click', 'button.plus, button.minus', function () {
        // Get current quantity values
        var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
        var val = parseFloat( qty.val() );
        var max = parseFloat( qty.attr( 'max' ) );
        var min = parseFloat( qty.attr( 'min' ) );
        var step = parseFloat( qty.attr( 'step' ) );

        // Change the value if plus or minus
        if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
                qty.val( max );
            } else {
                qty.val( val + step );
            }
        } else {
            if ( min && ( min >= val ) ) {
                qty.val( min );
            } else if ( val > 1 ) {
                qty.val( val - step );
            }
        }
    } );
    $( document ).ready( function () {
        $( '.cart-open .page-wrap' ).on( 'click', function () {
            $( "body" ).removeClass( "cart-open" );
        } );
        $( '.site-header-cart .la-times-circle' ).on( 'click', function () {
            $( "body" ).toggleClass( "cart-open" );
        } );
        $( '.header-cart' ).on( 'click', function () {
            $( "body" ).addClass( "cart-open" );
        } );
    } );
    $( '.search-button' ).on( 'click', function ( e ) {
        $( ".head-form" ).appendTo( ".heading-row" );
        $( ".head-form" ).toggleClass( "visible-xs hidden-xs" );
        $( ".search-button .la" ).toggleClass( "la-times la-search" );
        $( ".header-search-input" ).focus();
    } );
    $( '.head-form' ).on( 'focusout', function ( e ) {
        setTimeout( function () { // needed because nothing has focus during 'focusout'
            if ( $( ':focus' ).closest( '.head-form' ).length <= 0 ) {
                $( ".head-form" ).removeClass( 'visible-xs' ).addClass( 'hidden-xs' );
                $( ".search-button .la" ).removeClass( 'la-times' ).addClass( 'la-search' );
                $( ".search-button" ).focus();
                $( ".head-form" ).appendTo( ".header-search-widget" );
            }
        }, 0 );
    } );
} )( jQuery );