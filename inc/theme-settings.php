<?php
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
* REGISTER MENU
*/
function mrstadesign_register_menu() {
    register_nav_menu('header-menu',__( 'Header Menu' ));
}
add_action( 'init', 'mrstadesign_register_menu' );

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

// Pridani current class menu pro frontpage
add_filter('nav_menu_css_class', function($classes, $menu_item) {
	$vychozi = get_post_meta($menu_item->ID, '_custom_menu_meta', true);
	if ($vychozi && is_front_page() ) {
		$classes[] = 'current-menu-item';
	}
	return $classes;
}, 10, 2);