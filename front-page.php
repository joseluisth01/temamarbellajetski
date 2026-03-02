<?php
/**
 * Front Page Template - English Homepage
 */
get_header(); ?>

<main id="main-content">
    <?php echo mjsk_load_page_content('home.html'); ?>
</main>

<?php
// Boat Detail Modal
$modal_file = get_template_directory() . '/page-content/home-modal.html';
if (file_exists($modal_file)) {
    echo mjsk_load_page_content('home-modal.html');
}
?>

<?php get_footer(); ?>
