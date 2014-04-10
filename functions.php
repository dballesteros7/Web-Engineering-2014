<?php
// jQuery Insert From Google
if (! is_admin ())
	add_action ( "wp_enqueue_scripts", "my_jquery_enqueue", 11 );
function my_jquery_enqueue() {
	wp_deregister_script ( 'jquery' );
	wp_register_script ( 'jquery', "http" . ($_SERVER ['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js", false, null );
	wp_enqueue_script ( 'jquery' );
}

// Support thumbnails
add_theme_support ( 'post-thumbnails' );

// Support menus
add_theme_support ( 'menus' );
?>



