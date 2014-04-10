<?php
wp_nav_menu ( array (
		'menu' => 'Navigation menu',
		'container' => 'nav',
		'container_class' => 'sidebar',
		'container_id' => 'left-menu',
		'menu_class' => 'menu',
		'sort_column' => 'menu_order',
		'items_wrap' => '<ul id="%1$s" class="%2$s"><li><h3>
              Menu
            </h3></li>%3$s</ul>' 
) );
?>