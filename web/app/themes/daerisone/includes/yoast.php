<?php

add_filter( 'wpseo_robots', 'yoast_seo_robots' );

function yoast_seo_robots( $robots ) {

    if ( is_front_page() && get_locale() == 'fr-FR' ) {
        $robots = 'noindex, follow';
    }

    return $robots;

}