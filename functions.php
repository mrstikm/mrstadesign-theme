<?php
/**
 * Functions and definitions
 *
 * @package mrstadesign-theme
 */

/*
* CUSTOM SCRIPTS AND STYLES
*/
function add_theme_scripts() {
    wp_enqueue_script( 
        'mrstadesign-app',
        get_template_directory_uri() . '/js/script.js',
        array(), null, true
    );

    wp_enqueue_style( 
        'mrstadesign-normalize-style',
        get_template_directory_uri() . '/css/normalize.css'
    );

    wp_enqueue_style( 
        'mrstadesign-default-style',
        get_stylesheet_uri()
    );

    wp_add_inline_style( 'mrstadesign-default-style', 'header { background: url(' . get_header_image() . ') center no-repeat }' );


}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

/*
* THEME SETTINGS
*/
function mrstadesign_custom_header_setup() {
    add_theme_support( 'custom-header',  
        array(
            'default-image'      => get_template_directory_uri() . '/img/header-background.jpg',
            'header-text'        => false,
            'default-text-color' => 'fff',
            'width'              => 1600,
            'height'             => 667,
            'flex-width'         => true,
            'flex-height'        => true,
        )
    );

    add_theme_support('custom-logo',
        array(
            'height'               => 100,
            'width'                => 640,
            'flex-width'           => true,
            'flex-height'          => true,
            'unlink-homepage-logo' => false,
        )
    );

    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 320, 320, false );
}
add_action( 'after_setup_theme', 'mrstadesign_custom_header_setup' );

/*
* CUSTOMIZER
*/
function mrstadesign_customize_register( $wp_customize ) {
    $wp_customize->add_section('header_description', array(
        'title' => 'Popisek',
        'priority' => 30,
        'description' => 'Popisek v hlavičce stránky',
        'capability' => 'edit_theme_options',
    ));
    
    $wp_customize->add_setting( 'first_p', array(
        'type' => 'theme_mod',
        'transport' => 'postMessage', // or refresh
        'sanitize_callback' => function( $content ) {
            return sanitize_text_field( $content );
        }
    ) );
    $wp_customize->add_setting( 'second_p', array(
        'type' => 'theme_mod',
        'transport' => 'postMessage', // or refresh
        'sanitize_callback' => function( $content ) {
            return sanitize_text_field( $content );
        }
    ) );
    $wp_customize->add_setting( 'tel', array(
        'type' => 'theme_mod',
        'transport' => 'postMessage', // or refresh
        'sanitize_callback' => function( $content ) {
            return sanitize_text_field( $content );
        }
    ) );
    $wp_customize->add_setting( 'mail', array(
        'type' => 'theme_mod',
        'transport' => 'postMessage', // or refresh
        'sanitize_callback' => function( $content ) {
            return sanitize_email( $content );
        }
    ) );

    $wp_customize->add_control( 'first_p', array(
        'type' => 'text',
        'priority' => 10, // Within the section.
        'section' => 'header_description', // Required, core or custom.
        'label' => 'První odstavec'
    ) );
    $wp_customize->add_control( 'second_p', array(
        'type' => 'text',
        'priority' => 20, // Within the section.
        'section' => 'header_description', // Required, core or custom.
        'label' => 'Druhý odstavec'
    ) );
    $wp_customize->add_control( 'tel', array(
        'type' => 'tel',
        'priority' => 30, // Within the section.
        'section' => 'header_description', // Required, core or custom.
        'label' => 'Telefon'
    ) );
    $wp_customize->add_control( 'mail', array(
        'type' => 'email',
        'priority' => 40, // Within the section.
        'section' => 'header_description', // Required, core or custom.
        'label' => 'Email'
    ) );
}
add_action( 'customize_register', 'mrstadesign_customize_register' );

/*
* ALLOW SVGs
*/
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
   }
add_filter('upload_mimes', 'cc_mime_types');

/*
* CPT
*/
function custom_post_type() {
 
    $labels = array(
        'name'                => _x( 'Galerie', 'Post Type General Name', 'mrstadesign-theme' ),
        'singular_name'       => _x( 'Galerie', 'Post Type Singular Name', 'mrstadesign-theme' ),
        'menu_name'           => __( 'Galerie', 'mrstadesign-theme' ),
        'parent_item_colon'   => __( 'Parent Movie', 'mrstadesign-theme' ),
        'all_items'           => __( 'Všechny galerie', 'mrstadesign-theme' ),
        'view_item'           => __( 'Zobrazit galerii', 'mrstadesign-theme' ),
        'add_new_item'        => __( 'Přidat galerii', 'mrstadesign-theme' ),
        'add_new'             => __( 'Přidat nové', 'mrstadesign-theme' ),
        'edit_item'           => __( 'Upravit galerii', 'mrstadesign-theme' ),
        'update_item'         => __( 'Aktualizovat galerii', 'mrstadesign-theme' ),
        'search_items'        => __( 'Vyhledat galerii', 'mrstadesign-theme' ),
        'not_found'           => __( 'Nenalezeno', 'mrstadesign-theme' ),
        'not_found_in_trash'  => __( 'Nenalezeno v koši', 'mrstadesign-theme' ),
    );
         
    $args = array(
        'label'               => __( 'galerie', 'mrstadesign-theme' ),
        'description'         => __( 'Galerie', 'mrstadesign-theme' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields' ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
    
    );
         
    // Registering your Custom Post Type
    register_post_type( 'galerie', $args );
     
}    
add_action( 'init', 'custom_post_type', 0 );

// CPT Category
function mrstadesign_taxonomies() {
    $labels = array(
      'name'              => _x( 'Kategorie', 'taxonomy general name' ),
      'singular_name'     => _x( 'Kategorie', 'taxonomy singular name' ),
      'search_items'      => __( 'Vyhledat v kategorii' ),
      'all_items'         => __( 'Všechny kategorie' ),
      'parent_item'       => __( 'Parent Product Category' ),
      'parent_item_colon' => __( 'Parent Product Category:' ),
      'edit_item'         => __( 'Edit Product Category' ), 
      'update_item'       => __( 'Update Product Category' ),
      'add_new_item'      => __( 'Add New Product Category' ),
      'new_item_name'     => __( 'New Product Category' ),
      'menu_name'         => __( 'Kategorie' ),
    );
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'     => array('slug' => 'kategorie')
    );
    register_taxonomy( 'galerie_category', 'galerie', $args );
  }
add_action( 'init', 'mrstadesign_taxonomies', 0 );

// Galerie meta boxes
function post_gallery_box() {
    add_meta_box( 
        'post_gallery_box',
        __( 'Fotky položky', 'myplugin_textdomain' ),
        'post_gallery_box_content',
        'galerie',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'post_gallery_box' );

function post_gallery_box_content() {
    wp_nonce_field( plugin_basename( __FILE__ ), 'post_gallery_box_content_nonce' );
    echo '<label for="product_price">Cena</label>';
    echo '<input type="text" id="product_price" name="product_price" placeholder="enter a price" />';
}

/*
* REGISTER MENU
*/
function mrstadesign_register_menu() {
    register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'mrstadesign_register_menu' );

// REDIRECT HOME PAGE
// function mrstadesign_redirect_homepage() {
//     // Check for blog posts index
//     // NOT site front page, 
//     // which would be is_front_page()
//     if ( is_home() ) {
//         wp_redirect( get_category_link( 7 ) );
//         exit();
//     }
// }
// add_action( 'template_redirect', 'mrstadesign_redirect_homepage' );


function mrstadesogn_frontpage_categories( $query ) 
{
    if ( $query->is_main_query() && is_front_page() ) 
    {
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

// function mrstadesign_category_link( $link, $term )
// {
//     if ( 'motocykly' === $term->slug )
//     return home_url();
    
//     return $link;
// }
// add_filter( 'term_link', 'mrstadesign_category_link', 10, 2 );


// CUSTOM MENU FIELD
function mrstadesign_menu_custom_fields( $item_id, $item ) {

	wp_nonce_field( 'custom_menu_meta_nonce', '_custom_menu_meta_nonce_name' );
	$custom_menu_meta = get_post_meta( $item_id, '_custom_menu_meta', true );
	?>
	<div class="field-custom_menu_meta description-wide" style="margin: 5px 0;">
	    <span class="description"><?php _e( "Výchozí", 'custom-menu-meta' ); ?></span>
	    <br />

	    <input type="hidden" class="nav-menu-id" value="<?php echo $item_id ;?>" />

	    <div class="logged-input-holder">
	        <input type="checkbox" name="custom_menu_meta[<?php echo $item_id ;?>]" id="custom-menu-meta-for-<?php echo $item_id ;?>"
                <?php checked($custom_menu_meta, true); ?> > 
	        <label for="custom-menu-meta-for-<?php echo $item_id ;?>">
	            <?php _e( 'Nastavit jako výchozí', 'custom-menu-meta'); ?>
	        </label>
	    </div>

	</div>

	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'mrstadesign_menu_custom_fields', 10, 2 );

add_action('wp_update_nav_menu_item', function($menu_id, $menu_item_db_id) {
	$button_value = (isset($_POST['custom_menu_meta'][$menu_item_db_id]) && $_POST['custom_menu_meta'][$menu_item_db_id] == 'on') ? true : false;
	update_post_meta($menu_item_db_id, '_custom_menu_meta', $button_value);
}, 10, 2);

