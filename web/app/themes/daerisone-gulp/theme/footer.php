<div class="footer-wrapper">
<div class="footer-container container">
   <?php
      wp_nav_menu(array(
        'menu'              => 'footer_menu',
        'theme_location'    => 'footer_menu',
        'depth'             => 2,
        'container'         => 'div',
        'container_class'   => 'footer-menu',
        //'container_id'      => 'main-menu',
        'menu_class'        => 'clear'
      ));
      ?>
  
</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
