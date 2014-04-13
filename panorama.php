<?php
/**
 * @package panorama
 * @version 0.1
 */
/*
 Plugin Name: Panorama Viewer
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Some panorama viewer for web engineering.
Author: We
Version: 0.1
*/

// create custom plugin settings menu
add_action('admin_menu', 'p_create_menu');

function p_create_menu() {

	//create new top-level menu
	add_menu_page('Panorama Viewer Plugin Settings', 'Panorama Settings', 'administrator', __FILE__, 'panorama_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_panorama_settings' );
}


function register_panorama_settings() {
	//register our settings
	register_setting( 'panorama-settings-group', 'main_image' );
	register_setting( 'panorama-settings-group', 'thumbnail' );
}

function panorama_settings_page() {
?>
<div class="wrap">
<h2>Panorama Viewer Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'panorama-settings-group' ); ?>
    <?php do_settings_sections( 'panorama-settings-group' ); ?>
    <label for="main_image">
    <input id="main_image" type="text" size="36" name="main_image" value="http://" /> 
    <input id="main_image_button" class="button button-uploader" type="button" value="Upload Image" />
    <br>Enter a URL or upload an image for the main panorama image <br>
	<label for="thumbnail">
    <input id="thumbnail" type="text" size="36" name="thumbnail" value="http://" /> 
    <input id="thumbnail_button" class="button button-uploader" type="button" value="Upload Image" />
	<br>Enter a URL or upload an image for the thumbnail image <br>
</label>
    <?php submit_button(); ?>
</form>
</div>
<?php } ?>

<?php 
add_action('admin_enqueue_scripts', 'my_admin_scripts');

function my_admin_scripts() {
	if (isset($_GET['page']) && $_GET['page'] == 'panorama/panorama.php') {
		wp_enqueue_media();
		wp_register_script('options-js', plugins_url().'/panorama/options.js', array('jquery'));
		wp_enqueue_script('options-js');
	}
}
?>

<?php 
function add_scripts(){
	wp_register_script ( 'interactive', plugins_url().'/panorama/interactive-viewport.js', array('jquery'), null, true );
	wp_enqueue_script ( 'interactive' );
	wp_register_script ( 'weekly', plugins_url().'/panorama/weekly.js', array('interactive'), null, true );
	wp_enqueue_script ( 'weekly' );
}
?>

<?php
function panorama_viewer(){
add_scripts();
?>
<div id="main-image">
        <img draggable="false"
          src='<?php echo get_option('main_image', plugins_url().'/panorama/sydney-harbour-panorama1bl.jpg') ?>' />
      </div>
      <div id="thumbnail">
        <div class="overlay"></div>
        <img src='<?php echo get_option('thumbnail', plugins_url().'/panorama/sydney-harbour-panorama1bl-thumbnail.jpg') ?>' />
      </div>
<?php
}
?>