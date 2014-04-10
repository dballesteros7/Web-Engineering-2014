<?php get_header();?>
<div id="central">
<?php get_sidebar('left');?>
    <section id="content">
    <article class="weekly-suggestion">
      <h2>
        <a href="#">ETH suggests...</a>
      </h2>
      <div id="main-image">
        <img draggable="false"
          src='<?php echo get_template_directory_uri(); ?>/images/sydney-harbour-panorama1bl.jpg' />
      </div>
      <div id="thumbnail">
        <div class="overlay"></div>
        <img src='<?php echo get_template_directory_uri(); ?>/images/sydney-harbour-panorama1bl-thumbnail.jpg' />
      </div>
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
  <section id="right-sidebar">
    <aside class="sidebar-content">
      <h3>Our last reviews!</h3>
      <figure class="review">
        <img src="<?php echo get_template_directory_uri(); ?>/images/mec-paestum-1.jpg" alt="" />
        <figcaption>
          <span>Mec Paestum Hotel</span>
        </figcaption>
      </figure>
      <figure class="review">
        <img src="<?php echo get_template_directory_uri(); ?>/images/hotelbern.jpg" alt="" />
        <figcaption>
          <span>Bellevue Palace Bern</span>
        </figcaption>
      </figure>
    </aside>
    <aside class="sidebar-content weekly-contest">
      <h3>Weekly Location Contest</h3>
      <figure>
        <img src="<?php echo get_template_directory_uri(); ?>/images/sydney-harbour-panorama1bl-thumbnail.jpg"
          alt="" />
        <figcaption>
          <span> This week's location is:</span><br /> <span
            class="weekly-location">Sydney, Australia</span> <br /> <a
            href="#">Have a look!</a>
        </figcaption>
      </figure>
    </aside>
    <nav class="social">
      <a class="fb-logo" href="http://facebook.com/"><img
        src='<?php echo get_template_directory_uri(); ?>/images/facebook-icon.png' /></a>
      <!--  
       -->
      <a class="tweet-logo" href="http://twitter.com/"><img
        src='<?php echo get_template_directory_uri(); ?>/images/twitter-icon.png' /></a>
    </nav>
  </section>
</div>
<?php get_footer('weekly');?>
