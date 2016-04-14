<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
            <span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?></span>
        <?php endif; ?>

        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
    </header><!-- .entry-header -->

    <?php twentysixteen_excerpt(); ?>

    <?php twentysixteen_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
            if ( ! is_archive() ) {
                $begin_date = DateTime::createFromFormat( 'Ymd', get_field( 'course_begin_date' ) );
                $end_date = DateTime::createFromFormat( 'Ymd', get_field( 'course_end_date' ) );
                $begin_date_formatted = $begin_date->format( 'F j' );
                if ( $begin_date->format( 'Y' ) != $end_date->format( 'Y' ) ) {
                    $begin_date_formatted .= $begin_date->format( ', Y' );
                }
                $end_date_formatted = $end_date->format( 'j, Y' );
                if ( $begin_date->format( 'm' ) != $end_date->format( 'm' ) ) {
                    $end_date_formatted = $end_date->format( 'F ' ) . $end_date_formatted;
                }

                printf( '<h2 id="date">Dates: %1$s&ndash;%2$s</h2>',
                    $begin_date_formatted,
                    $end_date_formatted
                );

            }

            /* translators: %s: Name of current post */
            the_content( sprintf(
                __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
                get_the_title()
            ) );

            if ( ! is_archive() ) {
                // instructor info
                if ( get_field( 'instructor_name' ) ) {
                    echo '<h2 id="instructor">Instructor: ' . get_field( 'instructor_name' );
                    if ( get_field( 'instructor_photo' ) ) {
                        $instructor_photo = get_field( 'instructor_photo' );
                        printf( '<span class="author vcard">
                            <figure id="%3$s" class="wp-caption alignright">
                                <a href="%4$s">%1$s</a>
                                <figcaption class="wp-caption-text">%2$s</figcaption>
                            </figure>
                            </span>',
                                wp_get_attachment_image( $instructor_photo['id'], array( 150, 300 ) ),
                                $instructor_photo['title'],
                                $instructor_photo['id'],
                                get_permalink()
                            );
                    }
                    echo '</h2>';

                    if ( get_field( 'instructor_bio' ) ) {
                        the_field( 'instructor_bio' );
                    }
                }

                // cost info
                if ( get_field( 'cost' ) ) {
                    echo '<h2 id="cost">Cost</h2>';
                    the_field( 'cost' );
                }

                // application info
                if ( get_field( 'application' ) ) {
                    $application = get_field( 'application' );
                    echo '<section class="download dashicons-before dashicons-media-document">
                        <h2><a href="' . $application['url'] . '">Download an application</a></h2>
                        <p>Complete and email to <a href="mailto:' . antispambot( 'admissions@ambassadors.edu' ) . '">' . antispambot( 'admissions@ambassadors.edu' ) . '</a> or <a href="' . home_url( '/contact-us/' ) . '">mail to the college</a>.</p>
                    </section>';
                }
            }

            wp_link_pages( array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
                'separator'   => '<span class="screen-reader-text">, </span>',
            ) );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php master_module_entry_meta(); ?>
        <?php
            edit_post_link(
                sprintf(
                    /* translators: %s: Name of current post */
                    __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
