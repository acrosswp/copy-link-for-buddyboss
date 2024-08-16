/**
 * Primary editor script. Imports all of the various features so that they can
 * be bundled into a final file during the build process.
 *
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2023, Justin Tadlock
 * @license   GPL-2.0-or-later
 */

jQuery( 'document' ).ready( function( $ ){

    /**
     * Copy the Link
     */
    jQuery( "body" ).on( "click", ".copy-link-for-buddyboss",  function( event ) {
        event.preventDefault();
        navigator.clipboard.writeText( jQuery(this).attr( "href" ) );
    });
});