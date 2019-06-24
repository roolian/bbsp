<header class="navbar-wrapper">
  <div class="navbar-container">
    <div class="button-burger">
      <div class="svg-container">
        <?xml version="1.0" encoding="utf-8"?>
        <svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           viewBox="0 0 44 28" xml:space="preserve">
          <rect id="bar1"  y="0" width="44" height="2"/>
          <rect id="bar2" y="13" width="44" height="2"/>
          <rect id="bar3"  y="26" width="44" height="2"/>
        </svg>

      </div>
    </div>
    <div class="brand">
      <a class="" href="{{ home_url('/') }}" title="{{ get_bloginfo('name', 'display') }}">{{ get_bloginfo('name', 'display') }}</a>
    </div>
    <div class="button-contact">
      <div class="svg-container">
        <?xml version="1.0" encoding="utf-8"?>
        <svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           viewBox="0 0 22.8 14.6" xml:space="preserve">
          <g id="XMLID_2_">
            <path id="XMLID_3_" style="fill:#2D2D2D;" class="st0" d="M22.8,0.4c0,0,0-0.1-0.1-0.1c0,0,0-0.1,0-0.1c0,0,0,0,0,0c0,0-0.1-0.1-0.1-0.1
              c0,0-0.1-0.1-0.1-0.1c0,0,0,0,0,0h-22c0,0,0,0,0,0C0.3,0,0.3,0,0.2,0.1c0,0-0.1,0-0.1,0.1c0,0,0,0,0,0c0,0,0,0.1,0,0.1
              C0,0.3,0,0.4,0,0.4v13.8c0,0,0,0.1,0.1,0.1c0,0,0,0.1,0,0.1c0,0,0,0,0,0c0.1,0.1,0.2,0.1,0.3,0.1h22c0.1,0,0.2-0.1,0.3-0.1
              c0,0,0,0,0,0c0,0,0-0.1,0-0.1c0,0,0.1-0.1,0.1-0.1V0.4z M0.8,1.2l7.8,6l-7.8,6V1.2z M13.3,7C13.3,7,13.3,7,13.3,7l-1.8,1.4
              l-0.1,0.1l-0.1-0.1L9.5,7c0,0,0,0,0,0L1.6,0.8h19.6L13.3,7z M9.3,7.8l1.9,1.5c0,0,0.1,0,0.1,0c0,0,0,0,0.1,0c0,0,0,0,0,0
              c0,0,0,0,0,0c0,0,0.1,0,0.1,0c0,0,0.1,0,0.1-0.1l1.9-1.5l7.7,6H1.6L9.3,7.8z M14.2,7.3l7.8-6v12.1L14.2,7.3z"/>
          </g>
        </svg>
      </div>
    </div>
    @if (has_nav_menu('primary_navigation'))
    <nav class="nav-primary">
      
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']) !!}
      
    </nav>
    @endif
  </div>
</header>
