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
                
                if ( $loop->have_posts() ) { 

                    while ( $loop->have_posts() ) : $loop->the_post();                        
                        if( $gallery = get_post_gallery( $post->ID, false )) { 
                            $ids = wp_parse_id_list( $gallery['ids'] );
                        };?>
                        <a href="<?php echo get_permalink() ?>" title="<?php echo get_the_title() ?>">
                            <figure>
                                <?php the_post_thumbnail(); ?>
                                <figcaption class="img-count">
                                <?php 
                                    if ( ($gallery) ) { 
                                        echo count($ids) + 1;
                                    } else { 
                                       echo 1;
                                    } 
                                ?> 
                                </figcaption>
                            </figure>
                        </a>
                    <?php $ids = array(); 
                    endwhile;
                };?>

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