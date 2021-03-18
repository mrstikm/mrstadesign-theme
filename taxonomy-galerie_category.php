<?php get_header(); ?>
    
    <main class="container gallery">
        
        <div id="gallery-set">

            <?php 
                $current_page = get_query_var( 'term' );
            
                $loop = new WP_Query( array( 
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
                        <figure>
                            <?php the_post_thumbnail(); ?>
                            <figcaption class="img-count"><?php echo count(get_attached_media( 'image', $post->ID )); ?> </figcaption>
                        </figure>
                    </a>
                <?php endwhile;
            ?>

        </div>
    </main>

    <div id="lightbox">
        <div class="cross">
            &times
        </div>
        <div id="lightbox-content" class="container">
        </div>
    </div>
 
<?php get_footer(); ?>