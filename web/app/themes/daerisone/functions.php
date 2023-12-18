<?php

/*======================================================*/
/*================ PREFIXE FONCTIONS : LC ==============*/
/*======================================================*/

// define constants
define('THEME_ROOT_URI', get_template_directory_uri());
define('THEME_ROOT_DIR', get_template_directory());
define('THEME_LIB_DIR', THEME_ROOT_DIR . '/includes');
define('THEME_TEMPLATE_DIR', get_template_directory() . '/template-parts');


//include_once THEME_LIB_DIR.'/lc-custom-content.php';
include_once THEME_LIB_DIR . '/hello-elementor.php';
include_once THEME_LIB_DIR . '/setup.php';
include_once THEME_LIB_DIR . '/navwalker.php';
include_once THEME_LIB_DIR . '/cleanup.php';
include_once THEME_LIB_DIR . '/acf-actions.php';
include_once THEME_LIB_DIR . '/template-helpers.php';
include_once THEME_LIB_DIR . '/assets-loading.php';
include_once THEME_LIB_DIR . '/polylang.php';
include_once THEME_LIB_DIR . '/yoast.php';



/*======================================================*/
/*============= MASQUER MISE A JOUR WORDPRESS ==========*/
/*======================================================*/

// supprimer les notifications du core
//add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );

// supprimer les notifications de thèmes
//remove_action( 'load-update-core.php', 'wp_update_themes' );
//add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );

// supprimer les notifications de plugins
//remove_action( 'load-update-core.php', 'wp_update_plugins' );
//add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

/*======================================================*/
/*======================== AUTRES ======================*/
/*======================================================*/
