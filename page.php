<?php
/**
 * Generic Page Template
 * 
 * Automatically loads the correct content file based on the page slug.
 * Handles all pages: EN, ES, FR, NL — subpages and language homepages.
 */
get_header();

$content_file = mjsk_get_content_file_for_page();
$is_lang_home = mjsk_is_homepage();
?>

<main id="main-content">
    <?php
    if ($content_file) {
        // Auto-include shared styles for non-EN pages (e.g. es-booking.html → booking-styles.html)
        $slug = get_post_field('post_name', get_the_ID());
        $lang  = mjsk_get_lang();
        if ($lang !== 'en') {
            $styles_file = $slug . '-styles.html';
            $styles_path = get_template_directory() . '/page-content/' . $styles_file;
            if (file_exists($styles_path)) {
                echo mjsk_load_page_content($styles_file);
            }
        }
        echo mjsk_load_page_content($content_file);
    } else {
        // Fallback: show WP editor content
        while (have_posts()) : the_post();
            the_content();
        endwhile;
    }
    ?>
</main>

<?php
// Load boat detail modal for language homepages (fall back to English modal)
if ($is_lang_home) {
    $lang = mjsk_get_lang();
    $modal_file = $lang . '-home-modal.html';
    $modal_path = get_template_directory() . '/page-content/' . $modal_file;
    if (file_exists($modal_path)) {
        echo mjsk_load_page_content($modal_file);
    } else {
        // Fallback to English modal
        $en_modal = get_template_directory() . '/page-content/home-modal.html';
        if (file_exists($en_modal)) {
            echo mjsk_load_page_content('home-modal.html');
        }
    }
}
?>

<?php get_footer(); ?>
