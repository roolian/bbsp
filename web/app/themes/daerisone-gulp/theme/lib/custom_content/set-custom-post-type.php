<?php

// Hooking up our functions to theme setup
/*
add_action( 'init', 'lc_create_posttypes' );
add_action( 'init', 'lc_create_taxonomies' );
*/

// Our custom post type function
function lc_create_posttypes() {

  register_post_type( LC_POST_TYPE_CASE,
  // CPT Options
    array(
      'labels' => array(
        'name' => __( 'Cases', 'backend' ),
        'singular_name' => __( 'Case', 'backend' ),
        'all_items' => __( 'All cases', 'backend' ), /* the all items menu item */
        'add_new' => __( 'Add', 'backend' ), /* The add new menu item */
        'add_new_item' => __( 'Add', 'backend' ), /* Add New Display Title */
        'edit' => __( 'Edit', 'backend' ), /* Edit Dialog */
        'edit_item' => __( 'Edit', 'backend' ), /* Edit Display Title */
        'new_item' => __( 'New case', 'backend' ), /* New Display Title */
        'view_item' => __( 'View', 'backend' ), /* View Display Title */
        'search_items' => __( 'Search a case', 'backend' ), /* Search Custom Type Title */
        'not_found' =>  __( 'No case', 'backend' ), /* This displays if there are no entries yet */
        'not_found_in_trash' => __( 'No case in trash', 'backend' ), /* This displays if there is nothing in the trash */
        'parent_item_colon' => ''
      ),
      'taxonomies' => [],
      'public' => true,
      'has_archive' => false,
      'exclude_from_search' => false,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array('slug' => 'case'),
      'supports' => array( 'title',  'editor', 'thumbnail' ),
      'menu_icon' => 'dashicons-book-alt'
    )
  );
}

// Our custom taxonomies function
function lc_create_taxonomies() {
  /*
  register_taxonomy(
    LCW_POST_TAXO_THEME,
    array('post'),
    //array(),
    array(
      'labels' => array(
        'name' => __( 'Thèmes', 'backend' ),
        'singular_name' => __( 'Thème', 'backend' ),
        'all_items' => __( 'Tous les thèmes', 'backend' ),
        'edit_item' => __( 'Editer le thème', 'backend' ),
        'view_item' => __( 'Voir le thème', 'backend' ),
        'update_item' => __( 'Mettre à jour le thème', 'backend' ),
        'add_new_item' => __( 'Ajouter un thème', 'backend' ),
        'new_item_name' => __( 'Nouveau thème', 'backend' ),
        'search_items' => __( 'Rechercher parmi les thèmes', 'backend' ),
        'popular_items' => __( 'Thèmes les plus utilisés', 'backend' ),
      ),
      'hierarchical' => true,
      'public'  => true,
      'rewrite'      => array('slug' => 'theme', 'with_front' => true),
      'show_admin_column' => true,
      'show_in_menu' => false
    )
  );*/
}


// Our custom rewrite rules
