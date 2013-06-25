<?php

if ( function_exists ( dynamic_sidebar(7) ) ) : ?>
<!-- ... regular html ... -->
<!-- ... regular html ... -->


<?php dynamic_sidebar (7); ?>

<?php endif; ?>

<div class="fb-container">

  <script>
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
  </script>
  
  <div class="fb-like-box" data-href="https://www.facebook.com/EchoSimulator" data-width="292" data-show-faces="true" data-stream="false" data-show-border="true" data-header="false"></div>
</div>