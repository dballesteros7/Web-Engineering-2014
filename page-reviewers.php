<?php get_header();?>
    <div id="central">
<?php get_sidebar('left');?>
      <section id="content">
        <article class="article">
          <h2>
            <a href="#">Evaluate Thy Hotel</a>
          </h2>
          <?php $args = array( 'post_type' => 'reviewer', 'posts_per_page' => 6);
				$loop = new WP_Query( $args );?>
		  <?php if ($loop->have_posts()) : ?>
		  <?php while($loop->have_posts()) : $loop->the_post()?>
            <div class="main-profile">
			<?php if(has_post_thumbnail()) :
       			the_post_thumbnail();
      			endif;?>
                <h3><?php the_title()?></h3>
                <?php the_content()?>
            </div>
            <?php endwhile; endif;?>
        </article>
      </section>
<?php get_sidebar('right2');?>
</div>
<?php get_footer();?>