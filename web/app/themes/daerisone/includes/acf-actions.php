<?php

//Auto synchronisation ACF
if(function_exists( 'get_field' )){
  //include_once THEME_LIB_DIR.'/lc-acf-auto-sync.php';
}
//Pages option ACF
if( function_exists('acf_add_options_page') ) {

  $option_page = acf_add_options_page(array(
    'page_title'  => __( 'Options du site', 'backend' ),
    'menu_title'  => __( 'Site options', 'backend' ),
    'menu_slug'   => 'site-options',
    'capability'  => 'edit_posts',
    'icon_url' => 'dashicons-admin-generic',
    'redirect'  => false,
    'post_id' => 'site_options',
  ));

  // add sub page
  /*
  acf_add_options_sub_page(array(
    'page_title'  => 'Home Settings',
    'menu_title'  => 'Home Settings',
    'parent_slug'   => $option_page['menu_slug'],
  ));*/

}

//Hook ACF
if( is_admin() ){


  function acf_load_color_field_choices( $field ) {

    // reset choices
    $field['choices'] = array();

    switch_to_blog(1);

    $args = array(
      'numberposts' => -1,
      'post_type'   => 'case'
    );

    $cases = get_posts( $args );

    if ( $cases ) {
      foreach ( $cases as $post ) {
        $field['choices'][ $post->ID ] = $post->post_title;
      }
    }

    restore_current_blog();

    // return the field
    return $field;

  }

  // filter for a specific field based on it's name
  //add_filter('acf/load_field/name=case_list', 'acf_load_color_field_choices', 10, 1);

}



