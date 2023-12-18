<?php

// Le sigh...
if ( ! isset( $content_width ) ) $content_width = 837;

/* * * * * * * * * * * * * * *
 * =========Widgets Sidebars==========
 * https://developer.wordpress.org/reference/functions/register_sidebar/
 * * * * * * * * * * * * * * */

add_action('init', 'drs_start_session', 1);
function drs_start_session() {
    if(!session_id()) {
        session_start();
    }
}


/* =========================Element de customisation du theme============
 * https://developer.wordpress.org/reference/functions/add_theme_support/
 * https://developer.wordpress.org/reference/functions/register_nav_menus/
 * https://developer.wordpress.org/reference/functions/add_editor_style/
========================================================================*/
add_action( 'after_setup_theme', 'drs_theme_setup' );
if ( ! function_exists( 'drs_theme_setup' ) ) :
  function drs_theme_setup() {

    //add_theme_support( 'custom-background', array('default-color' => 'ffffff', ) );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );

    add_theme_support( 'html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
    ) );

    register_nav_menus( array(
      'main_menu' => __( 'Main Menu', 'daerisone' ),
      'footer_menu' => __( 'Footer Menu', 'daerisone' ),
      'sitemap' => __( 'Plan de site', 'daerisone' )
    ) );

    if ( function_exists('register_sidebar')){
      register_sidebar(array(
        'name'=> __('Sidebar de droite','daerisone'),
        'id'=>'right_sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
      ));
    }

    if ( function_exists('register_sidebar')){
      register_sidebar(array(
        'name'=> __('Sidebar accueil de droite','daerisone'),
        'id'=>'home_sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
      ));
    }


    add_editor_style( 'css/bootstrap.min.css' );
    add_theme_support( 'woocommerce', array(
    'thumbnail_image_width' => 150,
    'single_image_width'    => 300,

        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
  ) );

      add_theme_support( 'wc-product-gallery-zoom' );
      add_theme_support( 'wc-product-gallery-lightbox' );
      add_theme_support( 'wc-product-gallery-slider' );
}
endif; // lc_theme_setup


/* * * * * * * * * * * * * * *
 * =========Pagination==========
 * * * * * * * * * * * * * * */

function bootstrap_four_get_posts_pagination( $args = '' ) {
  global $wp_query;
  $pagination = '';

  if ( $GLOBALS['wp_query']->max_num_pages > 1 ) :
    $defaults = array(
      'total'     => isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1,
      'current'   => get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1,
      'type'      => 'array',
      'prev_text' => '&laquo;',
      'next_text' => '&raquo;',
    );

    $params = wp_parse_args( $args, $defaults );

    $paginate = paginate_links( $params );

    if( $paginate ) :
      $pagination .= "<ul class='pagination'>";
      foreach( $paginate as $page ) :
        if( strpos( $page, 'current' ) ) :
          $pagination .= "<li class='pagination-link active'>$page</li>";
        else :
          $pagination .= "<li class='pagination-link'>$page</li>";
        endif;
      endforeach;
      $pagination .= "</ul>";
    endif;

  endif;

  return $pagination;
}


function bootstrap_four_the_posts_pagination( $args = '' ) {
  echo bootstrap_four_get_posts_pagination( $args );
}


// Suppression des accents dans les urls des médias
function wpc_sanitize_french_chars($filename) {
	/* Force the file name in UTF-8 (encoding Windows / OS X / Linux) */
	$filemane = mb_convert_encoding($filename, "UTF-8");
	$char_not_clean = array('/À/','/Á/','/Â/','/Ã/','/Ä/','/Å/','/Ç/','/È/','/É/','/Ê/','/Ë/','/Ì/','/Í/','/Î/','/Ï/','/Ò/','/Ó/','/Ô/','/Õ/','/Ö/','/Ù/','/Ú/','/Û/','/Ü/','/Ý/','/à/','/á/','/â/','/ã/','/ä/','/å/','/ç/','/è/','/é/','/ê/','/ë/','/ì/','/í/','/î/','/ï/','/ð/','/ò/','/ó/','/ô/','/õ/','/ö/','/ù/','/ú/','/û/','/ü/','/ý/','/ÿ/', '/©/');
	$clean = array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','u','u','u','u','y','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','o','o','o','o','o','o','u','u','u','u','y','y','copy');
	$friendly_filename = preg_replace($char_not_clean, $clean, $filename);
	/* After replacement, we destroy the last residues */
	$friendly_filename = utf8_decode($friendly_filename);
	$friendly_filename = preg_replace('/\?/', '', $friendly_filename);
	/* Lowercase */
	$friendly_filename = strtolower($friendly_filename);
	return $friendly_filename;
}
add_filter('sanitize_file_name', 'remove_accents' );
add_filter('sanitize_file_name', 'wpc_sanitize_french_chars', 10);

function contactform_dequeue_scripts() {

    $load_scripts = false;

    if( is_singular() ) {
      $post = get_post();

      if( has_shortcode($post->post_content, 'contact-form-7') ) {
          $load_scripts = true;
      
    }

    }

    if( ! $load_scripts ) {
        wp_dequeue_script( 'contact-form-7' );
  wp_dequeue_script('google-recaptcha');
        wp_dequeue_style( 'contact-form-7' );
    
    }

}
add_action( 'wp_enqueue_scripts', 'contactform_dequeue_scripts', 99 );