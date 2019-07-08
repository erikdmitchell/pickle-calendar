<?php
/**
 * The template for displaying single events
 *
 * @package PickleCalendar
 * @since   1.0.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php
            while ( have_posts() ) :
                the_post();
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                    <header class="entry-header">
                        <?php

                            the_title( '<h1 class="entry-title">', '</h1>' );

                        ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php
                        /* translators: %s: Name of current post */
                        the_content(
                            sprintf(
                                __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
                                get_the_title()
                            )
                        );
                        ?>
                    </div><!-- .entry-content -->
                    
                    <?php pc_get_template_part( 'event-details' ); ?>
                
                </article><!-- #post-## -->

            <?php endwhile; // End of the loop. ?>

        </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php
get_footer();
