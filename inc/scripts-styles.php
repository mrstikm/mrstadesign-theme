<?php
/*
* CUSTOM SCRIPTS AND STYLES
*/
function add_theme_scripts() {
    wp_enqueue_script( 
        'mrstadesign-app',
        get_template_directory_uri() . '/assets/js/script.js',
        array(), null, true
    );
    wp_localize_script( 'mrstadesign-app', 'MyAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'mrstadesign_custom_ajax_nonce' )
    ) );

    wp_enqueue_style( 
        'mrstadesign-normalize-style',
        get_template_directory_uri() . '/assets/css/normalize.css'
    );

    wp_enqueue_style( 
        'mrstadesign-default-style',
        get_stylesheet_uri()
    );

    wp_add_inline_style( 'mrstadesign-default-style', 'header { background: url(' . get_header_image() . ') center no-repeat }' );


}
add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );