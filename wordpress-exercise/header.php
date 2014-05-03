<!DOCTYPE html>
<html manifest="manifest.appcache">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no">
<link type="text/css" rel="stylesheet"
  href="<?php echo get_stylesheet_directory_uri();?>/css/reset.css" />
<link type="text/css" rel="stylesheet"
  href="<?php echo get_stylesheet_uri();?>" />
<link type="text/css" rel="stylesheet"
  href="<?php echo get_stylesheet_directory_uri();?>/css/header.css" />
<link type="text/css" rel="stylesheet"
  href="<?php echo get_stylesheet_directory_uri();?>/css/footer.css" />
<link type="text/css" rel="stylesheet"
  href="<?php echo get_stylesheet_directory_uri();?>/css/sidebars.css" />
<link type="text/css" rel="stylesheet"
  href="<?php echo get_stylesheet_directory_uri();?>/css/content.css" />
<title><?php wp_title('&laquo', true, 'right'); ?><?php bloginfo('name')?></title>
<?php wp_head();?>
</head>
<body>
  <div id="main">
    <header id="header">
      <?php if(get_theme_mod('logo_image')): ?>
      <img src="<?php echo get_theme_mod('logo_image');?>" >
      <?php endif;?>
      <h1>
        <a href="#"><?php bloginfo('name');?></a>
      </h1>
      <h4>
      	<?php bloginfo('description')?>
      </h4>
      <nav>
        <ul class="nav">
          <li><a href="/">Home</a></li>
          <li><a href="/?page_id=14">Weekly Location</a></li>
          <li><a href="/?page_id=19">Reviewers</a></li>
        </ul>
      </nav>
    </header>