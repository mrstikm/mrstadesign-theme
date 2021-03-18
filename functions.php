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

// AJAX
add_action( 'wp_ajax_change_category', 'mrstadesign_change_category' );
add_action( 'wp_ajax_nopriv_change_category', 'mrstadesign_change_category' );
function mrstadesign_change_category() {
    // check_ajax_referer( 'mrstadesign_custom_ajax_nonce', 'security' );
    $slug = $_POST['href'];

    $loop = new WP_Query( array( 
        'post_type' => 'galerie', 
        'showposts' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'galerie_category',
                'field' => 'slug',
                'terms' => $slug
            )
        )
    ) );
    
    if ( $loop->have_posts() ) {

        while ( $loop->have_posts() ) : $loop->the_post();
            $gallery = get_post_gallery( $post->ID, false );
            $ids = wp_parse_id_list( $gallery['ids'] );
            echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
            echo '<figure>';
            echo    the_post_thumbnail();
            echo    '<figcaption class="img-count">';
            echo        count( $ids ) + 1;
            echo    '</figcaption>';
            echo '</figure>';
            echo '</a>';
        endwhile;

    } else {
        echo '<p>Litujeme, daná kategorie neobsahuje žádné příspěvky.';
    }

    die();
}

add_action( 'wp_ajax_post_content', 'mrstadesign_post_content' );
add_action( 'wp_ajax_nopriv_post_content', 'mrstadesign_post_content' );
function mrstadesign_post_content() {
    // check_ajax_referer( 'mrstadesign_custom_ajax_nonce', 'security' );
    $href = $_POST['href'];
    $postID = url_to_postid($href);
    $post = get_post( $postID );
    $gallery = get_post_gallery( $post, false );
    
    echo '<div id="preview">';
    echo    '<div id="arrow-left"><</div>';
    echo        '<div id="main-picture">';
    echo            get_the_post_thumbnail($post, 'extra-large');
    echo        '</div>';
    echo    '<div id="arrow-right">></div>';
    echo '</div>';
    echo '<div id="slides">';
    echo '<a href="' . get_the_post_thumbnail_url( $post, 'extra-large' ) . '" id="actual">';
    echo    get_the_post_thumbnail( $post, 'post-thumbnail' );   
    echo '</a>';

    $ids = wp_parse_id_list( $gallery['ids'] );
    foreach ( $ids as $id ) {
        echo '<a href="' . wp_get_attachment_image_url( $id, 'large' ) . '">';
        echo wp_get_attachment_image( $id, 'post-thumbnail' );
        echo '</a>';
    }

    die();
}




