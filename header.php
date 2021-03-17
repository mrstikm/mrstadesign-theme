<!DOCTYPE html>
<html <?php language_attributes() ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Mrstadesign, lakování motocyklů, helem, veteránů a dalšího, opravy plastů, kompozitních materiálů, hliníkových částí">
    <title>
        <?php 
            wp_title( '|', true, 'right' ); 
            bloginfo( 'name' );
            if ( is_front_page(  ) ) echo ' | ' . get_bloginfo( 'description' );
        ?>
    </title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jura&display=swap" rel="stylesheet">

    <?php wp_head() ?>
</head>

<body>  
    <header>
    
        <div class="contact-wrapper">
            <div class="container flex contacts">
                <a href="mailto:<?php echo get_theme_mod( 'mail' ) ?>"><?php echo get_theme_mod( 'mail' ) ?></a>
                <a href="tel:<?php echo get_theme_mod( 'tel' ) ?>"><?php echo get_theme_mod( 'tel' ) ?></a>
            </div>
        </div>

        <div class="container flex main-header">
            <h1>
                <?php echo get_custom_logo() ?>
            </h1>
            <hr>
            <div class="description">
                <p><?php echo get_theme_mod( 'first_p' ) ?></p>
                <p><?php echo get_theme_mod( 'second_p' ) ?></p>
            </div>
        </div>

    </header>

    <?php wp_nav_menu( array( 
        'theme_location' => 'header-menu', 
        'menu_id' => 'nav-primary',
        'container_class' => 'container nav-wrapper',
        'container' => 'nav' 
    ) ); ?>