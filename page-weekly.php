<?php get_header();?>
<div id="central">
<?php get_sidebar('left');?>
    <section id="content">
    <article class="weekly-suggestion">
      <h2>
        <a href="#">Friends suggests...</a>
      </h2>
      <?php panorama_viewer();?>
      <h3>This week's winner is... New York City</h3>
    </article>
  </section>
<?php get_sidebar('right2');?>
</div>
<?php get_footer();?>
