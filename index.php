<?php get_header();?>
<div id="central">
<?php get_sidebar('left');?>
  <section id="content">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article <?php post_class('article')?>>
      <h2>
        <a href="<?php the_permalink();?>"><?php the_title();?></a>
      </h2>
      <?php if(has_post_thumbnail()) :
       the_post_thumbnail();
      endif;?>
      <?php the_content('Read more...');?>
      <footer class="article-author">
        <!-- This div looks more like a footer, semantically speaking -->
        <a href="#"><?php the_date('l, F j, Y');?> by <?php the_author();?></a>
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
  <section id="right-sidebar">
    <!-- This is a div for grouping the content inside, rather than a piece of aside content. -->
    <aside class="sidebar-content">
      <!-- On the other hand, this is a real aside content. It represents a related piece of content to the main articles -->
      <!-- No need for a header on every h* element, unless there is something else -->
      <h3>Meet our reviewers!</h3>
      <figure class="profile">
        <!-- Figure feels appropriate here, it is also a new HTML5 element -->
        <!-- Not real semantic sections, they were created for styling purposes -->
        <img src="<?php echo get_template_directory_uri(); ?>/images/mranderson.jpg" alt="" />
        <figcaption>
          <h4>Mr Anderson</h4>
          <span>CEO, Main Reviewer</span>
        </figcaption>
      </figure>
      <figure class="profile">
        <img src="<?php echo get_template_directory_uri(); ?>/images/trinity.jpg" alt="" />
        <figcaption>
          <h4>Ms Trinity</h4>
          <span>Assistant, Reviewer, Board Member</span>
        </figcaption>
      </figure>
      <a href="#">Meet them all!</a>
    </aside>
    <aside class="sidebar-content weekly-contest">
      <h3>Weekly Location Contest</h3>
      <figure>
        <img src="<?php echo get_template_directory_uri(); ?>/images/sydney-harbour-panorama1bl-thumbnail.jpg"
          alt="" />
        <figcaption>
          <span> This week's location is:</span><br /> <span
            class="weekly-location">Sydney, Australia</span><br /> <a
            href="#">Have a look!</a>
        </figcaption>
      </figure>
    </aside>
    <nav class="social">
      <!-- This is a set of secondary navigation links, they go on a nav -->
      <a class="fb-logo" href="http://facebook.com/"><img
        src='<?php echo get_template_directory_uri(); ?>/images/facebook-icon.png' /></a>
      <!--  
       -->
      <a class="tweet-logo" href="http://twitter.com/"><img
        src='<?php echo get_template_directory_uri(); ?>/images/twitter-icon.png' /></a>
    </nav>
  </section>
</div>
<?php get_footer();?>