<?php get_header();?>
    <div id="central">
<?php get_sidebar('left');?>
      <section id="content">
        <article class="article">
          <h2>
            <a href="#">Evaluate Thy Hotel</a>
          </h2>
          <div class="profile-row">
            <!-- Not a section, it is a div for styling purposes -->
            <figure class="main-profile">
              <img src="<?php echo get_template_directory_uri(); ?>/images/mranderson.jpg" alt="" />
              <figcaption>
                <h3>Mr. Anderson</h3>
                <p>
                  CEO<br>Main Reviewer<br>
                </p>
                <p>I will show you a world in which every trip is
                  possible..</p>
              </figcaption>
            </figure><!--
            --><figure class="main-profile">
              <img src="<?php echo get_template_directory_uri(); ?>/images/smith.jpg" alt="" />
              <figcaption>
                <h3>Mr. Smith</h3>
                <p>
                  Board Member<br>Main Reviewer<br>
                </p>
                <p>That's what humanity does... travels.</p>
              </figcaption>
            </figure>
          </div>
          <div class="profile-row">
            <figure class="main-profile">
              <img src="<?php echo get_template_directory_uri(); ?>/images/trinity.jpg" alt="" />
              <figcaption>
                <h3>Ms Trinity</h3>
                <p>Assistant Reviewer</p>
                <p>I love the chosen ones, so we love sending people
                  to the chosen places!</p>
              </figcaption>
            </figure><!--
            --><figure class="main-profile">
              <img src="<?php echo get_template_directory_uri(); ?>/images/morpheus.jpg" alt="" />
              <figcaption>
                <h3>Mr. Morpheus</h3>
                <p>Assistant Reviewer</p>
                <p>Every man has the keys to the fun... start
                  travelling!</p>
              </figcaption>
            </figure>
          </div>
          <div class="profile-row">
            <figure class="main-profile">
              <img src="<?php echo get_template_directory_uri(); ?>/images/mouse.jpg" alt="" />
              <figcaption>
                <h3>Mr. Mouse</h3>
                <p>Draft Optimiser</p>
                <p>You need more! Refusing travelling is refusing
                  the only thing that makes us humans.</p>
              </figcaption>
            </figure><!--
         --><figure class="main-profile">
              <img src="<?php echo get_template_directory_uri(); ?>/images/chong.jpg" alt="" />
              <figcaption>
                <h3>Mr. Tank</h3>
                <p>Administrative Assistant</p>
                <p>There's always a way out.. of your stress! You
                  just have to reach that fast! Start travelling!</p>
              </figcaption>
            </figure>
          </div>
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
                class="weekly-location">Sydney, Australia</span> <br />
              <a href="#">Have a look!</a>
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
    </div>
<?php get_footer();?>