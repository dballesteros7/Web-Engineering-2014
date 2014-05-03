<?php get_header();?>
<div id="central">
<?php get_sidebar('left');?>
  <section id="content">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article <?php post_class('article')?>>
      <h2>
        <a href="<?php the_permalink();?>"><?php the_title();?></a>
      </h2>
      <?php if (has_post_thumbnail ()) :
			the_post_thumbnail ();
      	endif;?>
      <?php the_content('Read more...');?>
      <footer class="article-author">
        <!-- This div looks more like a footer, semantically speaking -->
        <a href="#"><?php echo get_the_date('l, F j, Y');?> by <?php the_author();?></a>
      </footer>
    </article>
    <?php endwhile;?>
    <?php endif;?>
    <?php $previous = get_previous_posts_link();?>
    <?php $next = get_next_posts_link();?>
    <?php if($previous || $next) :?>
    <ul class="article link">
      <li class="previous"><?php previous_posts_link('&laquo; Newer');?></li>
      <li class="next"><?php next_posts_link('Older &raquo;');?></li>
    </ul>
    <?php endif;?>
  </section>
<?php get_sidebar('right1')?>
</div>
<?php get_footer();?>