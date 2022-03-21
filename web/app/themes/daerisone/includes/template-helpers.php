<?php
/*=================================*/
/*===========   Home    ===========*/
/*=================================*/


/*======================================================*/
/*========================Util==========================*/
/*======================================================*/
function reduce_text_lenght($text, $limit){
  $text_len = strlen ( $text );
  if ( $text_len > $limit){
    if ( $pos = strpos($text, ' ', $limit) ){
      $text= substr($text, 0, $pos);
      $text .= '...';
    }

  }
  return $text;
}


/*======================================================*/
/*========================Test language==========================*/
/*======================================================*/
function is_current_language($locale){
    $current_locale = function_exists('pll_current_language')? pll_current_language() : substr(get_locale(),0,2);
    
    //echo $current_locale;

    return $current_locale === $locale;
}


/**
 * Archive Navigation
 *
 * @author Bill Erickson
 * @see https://www.billerickson.net/custom-pagination-links/
 *
 */
function daerisone_archive_navigation() {
    $settings = array(
    'count' => 6,
    'prev_text' => '<span aria-hidden="true">&laquo;</span>',
    'next_text' => '<span aria-hidden="true">&raquo;</span>'
  );
  global $wp_query;
  $current = max( 1, get_query_var( 'paged' ) );
  $total = $wp_query->max_num_pages;
  $links = array();
  // Offset for next link
  if( $current < $total )
    $settings['count']--;
  // Previous
  if( $current > 1 ) {
    $settings['count']--;
    $links[] = daerisone_archive_navigation_link( $current - 1, 'prev', $settings['prev_text'] );
  }
  // Current
  $links[] = daerisone_archive_navigation_link( $current, 'active' );
  // Next Pages
  for( $i = 1; $i < $settings['count']; $i++ ) {
    $page = $current + $i;
    if( $page <= $total ) {
      $links[] = daerisone_archive_navigation_link( $page );
    }
  }
  // Next
  if( $current < $total ) {
    $links[] = daerisone_archive_navigation_link( $current + 1, 'next', $settings['next_text'] );
  }
  echo '<nav class="navigation posts-navigation" role="navigation" aria-label="Pagination">';
      echo '<ul class="pagination justify-content-center">' . join( '', $links ) . '</ul>';
  echo '</nav>';
}



/**
 * Archive Navigation Link
 *
 * @author Bill Erickson
 * @see https://www.billerickson.net/custom-pagination-links/
 *
 * @param int $page
 * @param string $class
 * @param string $label
 * @return string $link
 */
function daerisone_archive_navigation_link( $page = false, $class = '', $label = '' ) {
  if( ! $page )
    return;
  $classes = array( 'page-numbers' );
  if( !empty( $class ) )
    $classes[] = $class;
  $classes = array_map( 'sanitize_html_class', $classes );
  $label = $label ? $label : $page;
  $link = esc_url_raw( get_pagenum_link( $page ) );
  return '<li class="page-item  ' . join ( ' ', $classes ) . '"><a class="page-link" href="' . $link . '">' . $label . '</a></li>';
}


/*======================================================*/
/*=================Change default queries===============*/
/*======================================================*/
function lc_modify_main_query( $query ) {
  if ( !is_admin()
        && $query->is_page()
        && $query->get( 'pagename' ) == 'blog'
        && $query->is_main_query()
      )
  {
     $query->set( 'posts_per_page', 10 );
     $query->set( 'post_type', 'post' );
  }

}
// Hook my above function to the pre_get_posts action
//add_action( 'pre_get_posts', 'lc_modify_main_query' );
//
function be_menu_item_classes( $classes, $item, $args, $depth  ) {
  if( 'main_menu' !== $args->theme_location )
    return $classes;
  if( is_singular( 'case' )  && 'Work' == $item->title )
    $classes[] = 'current-menu-item';

  return array_unique( $classes );
}
add_filter( 'nav_menu_css_class', 'be_menu_item_classes', 10, 4 );



/*======================================================*/
/*=========================Shortcode====================*/
/*======================================================*/

//add_shortcode( 'custom-template', 'custom_template_shortcode' );
function custom_template_shortcode($param) {
  extract(
    shortcode_atts(
      array(
        'id' => ''
      ),
      $param
    )
  );
  if ($id != '')
    return custom_template_shortcode_output($id);
  else
    return $id;
}

function custom_template_shortcode_output($id) {
  $shortcode = '[elementor-template id="'.$id.'"]';
  ob_start();
  switch_to_blog(1);
  echo do_shortcode($shortcode);
  restore_current_blog();
  return ob_get_clean();
}


/*======================================================*/
/*=====Chargement des données pour les tempaltes========*/
/*======================================================*/
add_action( 'wp', 'lc_init_usefull_functions' );

function lc_init_usefull_functions(){


  function last_posts_widget(){

    $args = array(
      'numberposts' => 5,
      'post_type'   => 'post'
    );

    $posts = get_posts( $args );

    if ( $posts ) {
      echo '<h3>';
      echo __('Derniers Articles', 'daerisone');
      echo '</h3>';
      echo '<ul>';
      foreach ( $posts as $post ) :
          echo '<li class="thumb-section-link">';
            echo '<a href="'.get_permalink($post).'" class="btCases" id="post'.$post->ID.'" >';
            echo get_the_title($post->ID);
            echo '</a>';
          echo '</li>';
      endforeach;
      echo '</ul>';
    }

  }



  if ( !function_exists( 'daerisone_the_posts_navigation' ) ) {
    function daerisone_the_posts_navigation($pages = false) {
      if ($pages) $GLOBALS['wp_query']->max_num_pages = $pages;
      daerisone_archive_navigation();
    }
  }


}
