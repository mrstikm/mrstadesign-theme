<?php
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

// CUSTOM MENU FIELD - Nastavit jako výchozí
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

// Ulozeni do DB
add_action('wp_update_nav_menu_item', function($menu_id, $menu_item_db_id) {
	$button_value = (isset($_POST['custom_menu_meta'][$menu_item_db_id]) && $_POST['custom_menu_meta'][$menu_item_db_id] == 'on') ? true : false;
	update_post_meta($menu_item_db_id, '_custom_menu_meta', $button_value);
}, 10, 2);