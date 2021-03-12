<?php
/**
 * Functions and definitions
 *
 * @package mrstadesign-theme
 */

$roots_includes = array(
    '/inc/scripts-styles.php',
    '/inc/theme-settings.php',
    '/inc/custom-types.php'
);

foreach($roots_includes as $file){
    if(!$filepath = locate_template($file)) {
      trigger_error("Error locating `$file` for inclusion!", E_USER_ERROR);
    }
  
    require_once $filepath;
}
unset($file, $filepath);




function mrstadesogn_frontpage_categories( $query ) 
{
    if ( $query->is_main_query() && is_front_page() ) 
    {
        // upravit podle slugu "Nastavit jako vychozi z menu
        $query->set( 'tax_query', array(
            array(
                'taxonomy' => 'galerie_category',
                'field' => 'slug',
                'terms' => 'motocykly'
            )
        ) );
    }
    
    return $query;
}
add_action( 'pre_get_posts', 'mrstadesogn_frontpage_categories' );

function mrstadesign_category_link( $link, $term )
{
    if ( 'motocykly' === $term->slug )
    return home_url();
    
    return $link;
}
add_filter( 'term_link', 'mrstadesign_category_link', 10, 2 );






