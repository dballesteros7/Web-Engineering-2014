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

// Add custom type for reviewers
function reviewers_post_type() {
	$labels = array(
			'name'               => 'Reviewers',
			'singular_name'      => 'Reviewer',
			'add_new_item'       => 'Add New Reviewer',
			'new_item'           => 'New Reviewer',
			'edit_item'          => 'Edit Reviewer',
			'view_item'          => 'View Reviewer',
			'all_items'          => 'All Reviewers',
			'search_items'       => 'Search Reviewers',
			'parent_item_colon'  => 'Senior Reviewers:',
			'not_found'          => 'No reviewers found.',
			'not_found_in_trash' => 'No reviewers found in Trash.',
	);

	$args = array(
			'labels'             => $labels,
			'public'             => true,
			'rewrite'            => array( 'slug' => 'reviewer' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' )
	);

	register_post_type( 'reviewer', $args );
}
add_action( 'init', 'reviewers_post_type' );

function color_customization( $wp_customize ) {
	//All our sections, settings, and controls will be added here
	$wp_customize->add_setting( 'color_1' , array(
			'default'     => '#FFF0F5',
			'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'color_2' , array(
			'default'     => '#FFA500',
			'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'text_color' , array(
			'default'     => '#00000',
			'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'footer_color' , array(
			'default'     => '#808080',
			'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'headings_color' , array(
			'default'     => '#000000',
			'transport'   => 'refresh',
	) );
	$wp_customize->add_setting( 'logo_image' , array(
			'default'     => '',
			'transport'   => 'refresh',
	) );
	$wp_customize->add_section( 'webeng_images' , array(
			'title'     => 'Images',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_1', array(
			'label'        => __( 'Primary Color', 'mytheme' ),
			'section'    => 'colors',
			'settings'   => 'color_1',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_2', array(
			'label'        => __( 'Secondary Color', 'mytheme' ),
			'section'    => 'colors',
			'settings'   => 'color_2',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
			'label'        => __( 'Text Color', 'mytheme' ),
			'section'    => 'colors',
			'settings'   => 'text_color',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color', array(
			'label'        => __( 'Footer Color', 'mytheme' ),
			'section'    => 'colors',
			'settings'   => 'footer_color',
	) ) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'headings_color', array(
			'label'        => __( 'Headings Color', 'mytheme' ),
			'section'    => 'colors',
			'settings'   => 'headings_color',
	) ) );
	$wp_customize->add_control(
			new WP_Customize_Image_Control(
					$wp_customize,
					'logo_image',
					array(
							'label'      => __( 'Upload a logo', 'theme_name' ),
							'section'    => 'webeng_images',
							'settings'   => 'logo_image',
					)
			)
	);
}
add_action( 'customize_register', 'color_customization' );

function customize_css()
{
	?>
         <style type="text/css">
             #content { background-color: <?php echo get_theme_mod('color_2'); ?>; }
             .article { background-color: <?php echo get_theme_mod('color_1'); ?>; }
             .weekly-suggestion { background-color: <?php echo get_theme_mod('color_1'); ?>; }
             #header .nav li { background-color: <?php echo get_theme_mod('color_2')?> }
             #left-menu { background-color: <?php echo get_theme_mod('color_2'); ?>; }
             #left-menu .menu li { border-bottom-color: <?php echo get_theme_mod('color_1'); ?>; }
             #left-menu .menu li:first-child { background-color: <?php echo get_theme_mod('color_1'); ?>; }
             .sidebar-content { background-color: <?php echo get_theme_mod('color_2'); ?>; }
             body { background-color: <?php echo get_theme_mod('color_1'); ?>; }
             body { color : <?php echo get_theme_mod('text_color');?>; }
             #footer { color : <?php echo get_theme_mod('footer_color');?>; }
             h1, h2, h3, h4 { color : <?php echo get_theme_mod('headings_color');?>; }
         </style>
    <?php
}
add_action( 'wp_head', 'customize_css');
?>



