<?php
/**
 * Template Name: Service Landing Page
 * 
 * Used for individual service SEO landing pages like
 * /closed-circuit-jet-ski/, /wakeboarding-experience/, etc.
 * 
 * Content is loaded from page-content/service-{slug}.html (EN)
 * or page-content/{lang}-service-{slug}.html (ES/FR/NL).
 */
get_header();

$slug = get_post_field( 'post_name', get_the_ID() );
$lang = mjsk_get_lang();
$content_file = mjsk_get_content_file_for_page();
$booking_url  = mjsk_get_booking_url( $lang );
$home_url     = mjsk_get_home_url( $lang );
?>

<main id="main-content">
    <?php
    if ( $content_file ) {
        // Load shared service landing page styles
        $styles_path = get_template_directory() . '/page-content/service-styles.html';
        if ( file_exists( $styles_path ) ) {
            echo mjsk_load_page_content( 'service-styles.html' );
        }
        echo mjsk_load_page_content( $content_file );
    } else {
        // Fallback: show WP editor content
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    }
    ?>
</main>

<?php get_footer(); ?>
