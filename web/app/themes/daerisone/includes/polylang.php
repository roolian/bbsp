<?php


// define the pll_rel_hreflang_attributes callback 
function filter_pll_rel_hreflang_attributes( $hreflangs ) { 
    return []; 
}; 

// add the filter 
add_filter( 'pll_rel_hreflang_attributes', 'filter_pll_rel_hreflang_attributes', 10, 1 ); 