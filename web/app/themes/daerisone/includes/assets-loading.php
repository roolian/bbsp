<?php


/*===============================*/
/*=========CSS du theme==========*/
/*===============================*/

if (!function_exists('drs_theme_styles')) :
    function drs_theme_styles()
    {
        wp_enqueue_style('app-css', get_template_directory_uri() . '/css/app.min.css');
    }
endif;
add_action('wp_enqueue_scripts', 'drs_theme_styles');



/*========================================*/
/*=========JAVASCRIPTS du theme==========*/
/*======================================*/

// Remove jQuery Migrate Script from header and Load jQuery from Google API
add_action('init', 'lc_stop_loading_wp_embed_and_jquery');
function lc_stop_loading_wp_embed_and_jquery()
{ }

add_action('wp_enqueue_scripts', 'add_custom_script');
function add_custom_script()
{

    if (!is_admin()) {
        wp_deregister_script('wp-embed');
        wp_deregister_script('jquery');  // we use our own jquery file
        wp_enqueue_script('jquery', get_template_directory_uri() . '/js/vendor.min.js');
        //wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.2/TweenMax.min.js', array('jquery'));
        //wp_enqueue_script('modernizr', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js', array('jquery'));
        //wp_enqueue_script('scrollTo', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.2/plugins/ScrollToPlugin.min.js', array('jquery', 'gsap'));
        wp_enqueue_script('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'));
        wp_enqueue_script('app-js', get_template_directory_uri() . '/js/app.min.js', array(
            //'gsap', 
            'jquery', 
            'bootstrap', 
            //'scrollTo', 
            //'modernizr'
        ));
    }

    global $wp_query;
    $args = [
        'url'   => admin_url('admin-ajax.php'),
        'query' => $wp_query->query,
    ];

    //wp_localize_script('app-js', 'ajaxParam', $args);
}


/*
add_action('elementor/frontend/after_register_scripts', function() {
  //wp_deregister_script('jquery-slick');
  wp_enqueue_script('jquery-slick');
  wp_enqueue_script('jquery-swiper');
  wp_enqueue_script('jquery-numerator');
  wp_enqueue_script('imagesloaded');
  wp_enqueue_script('flatpickr');
},15);
*/
