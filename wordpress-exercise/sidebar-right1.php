<section id="right-sidebar">
    <aside class="sidebar-content">
      <h3>Meet our reviewers!</h3>
      <?php $args = array( 'post_type' => 'reviewer', 'posts_per_page' => 2,
      					   'orderby' => 'meta_value_num', 'meta_key' => 'relevance');
      				$loop = new WP_Query ( $args );
				?>
	  <?php if ($loop->have_posts()) : ?>
	  <?php while($loop->have_posts()) : $loop->the_post()?>
	  <div class="profile">
	  <?php if(has_post_thumbnail()) :
       			the_post_thumbnail();
      			endif;?>
	  <h4><?php the_title();?></h4>
	  <span><?php the_content();?></span>
	  </div>
	  <?php endwhile; endif;?>
      <a href="#">Meet them all!</a>
    </aside>
    <aside class="sidebar-content weekly-contest">
      <h3>Weekly Location Contest</h3>
      <figure>
        <img
          src="<?php echo get_template_directory_uri(); ?>/images/sydney-harbour-panorama1bl-thumbnail.jpg"
          alt="" />
        <figcaption>
          <span> This week's location is:</span><br /> <span
            class="weekly-location">Sydney, Australia</span><br /> <a
            href="#">Have a look!</a>
        </figcaption>
      </figure>
    </aside>
    <nav class="social">
      <a class="fb-logo" href="http://facebook.com/"><img
        src='<?php echo get_template_directory_uri(); ?>/images/facebook-icon.png' /></a><!--  
       --><a class="tweet-logo" href="http://twitter.com/"><img
        src='<?php echo get_template_directory_uri(); ?>/images/twitter-icon.png' /></a>
    </nav>
  </section>