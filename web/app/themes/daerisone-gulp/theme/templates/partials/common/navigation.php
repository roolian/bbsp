<div class="row justify-content-between align-items-center">
  <div class="logo-wrapper col">
    <div class="logo-container">
      
      <?php the_custom_logo()  ?>

    </div>
  </div>

  <div class="navigation-wrapper col-auto">
    <nav class="navbar" role="navigation">
      


        <div class="menu-toggle-container  ">
          <button id="menu-toggle" type="button" class=" menu-trigger button-burger button-icon" data-target="#main-menu">
            <div class="icon-burger">
              <div class="bar"></div>
              <div class="bar"></div>
              <div class="bar"></div>
            </div>
          </button>
        </div>

        <?php
        wp_nav_menu(array(
          'menu'              => 'main_menu',
          'theme_location'    => 'main_menu',
          'depth'             => 2,
          'container'         => 'div',
          'container_class'   => 'main-menu',
          //'container_id'      => 'main-menu',
          'menu_class'        => 'clear'
        ));
        ?>

    </nav>
  </div>
</div>

<div class="navigation-mobile-wrapper">

  <?php
        wp_nav_menu(array(
          'menu'              => 'main_menu',
          'theme_location'    => 'main_menu',
          'depth'             => 2,
          'container'         => 'div',
          'container_class'   => 'mobile-main-menu',
          //'container_id'      => 'main-menu',
          'menu_class'        => 'clear'
        ));
        ?>
</div>