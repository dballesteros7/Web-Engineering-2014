<?php get_header();?>
<div id="central">
<?php get_sidebar('left');?>
    <section id="content">
    <article class="weekly-suggestion">
      <h2>
        <a href="#">ETH suggests...</a>
      </h2>
      <?php panorama_viewer();?>
      <h3>This week's winner is... Sydney, Australia</h3>
      <p>Sydney is the state capital of New South Wales and the most
        populous city in Australia. It is on Australia's south-east
        coast, on the Tasman Sea. In June 2010 the greater metropolitan
        area had an approximate population of 4.6 million people.
        Inhabitants of Sydney are called Sydneysiders, comprising a
        cosmopolitan and international population.</p>
      <p class="quote">"A life without travel is a life unlived"</p>
      <figure class="signature">
        <img src="<?php echo get_template_directory_uri(); ?>/images/mranderson.jpg" alt="" />
        <figcaption>
          <span>Mr. Anderson</span>
        </figcaption>
      </figure>
    </article>
  </section>
<?php get_sidebar('right2');?>
</div>
<?php get_footer();?>
