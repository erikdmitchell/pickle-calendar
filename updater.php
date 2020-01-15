<?php
    
/**

we need to load this when we check for plugin updates
make sure we set a transient
we need to pull the latest release via github and wp_remote_get - https://github.com/owner/name/releases/latest/.
ASSET NAME: pickle-calendar ~ needs to be the same as the zip 
there's an assets url so we can get the zip file - https://github.com/owner/name/releases/latest/download/asset-name.zip.

i think we need to use the latest release tag or title which is our version (perhaps the latest release url)

if the version is greater, we update
we need to unpack the zip and replace the folder

*/

/** @var array|WP_Error $response */
$response = wp_remote_get( 'http://www.example.com/index.html' ); // URL
 
if ( is_array( $response ) && ! is_wp_error( $response ) ) {
    $headers = $response['headers']; // array of http header lines
    $body    = $response['body']; // use the content
} else {
    // error
}