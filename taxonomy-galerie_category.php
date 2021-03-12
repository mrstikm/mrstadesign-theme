<?php get_header(); ?>

    <main class="container gallery">
        
        <div id="gallery-set">

            <?php $current_page = get_query_var( 'term' ); ?>
            
            <?php $loop = new WP_Query( array( 
                'post_type' => 'galerie', 
                'showposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'galerie_category',
                        'field' => 'slug',
                        'terms' => $current_page
                    )
                )
            ) );
            
            while ( $loop->have_posts() ) : $loop->the_post();?>
                <a href="<?php echo get_permalink() ?>" title="<?php echo get_the_title() ?>">
                    <?php the_post_thumbnail(); ?>
                </a>
            <?php endwhile;
            ?>

        </div>
    </main>
 
<?php get_footer(); ?>