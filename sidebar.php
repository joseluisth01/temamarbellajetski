<?php
/**
 * Sidebar Template
 *
 * Renders the blog sidebar widget area if it has active widgets.
 *
 * @package MarbellaJetSki
 */
if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>
<aside id="sidebar" class="blog-sidebar" role="complementary">
    <?php dynamic_sidebar( 'blog-sidebar' ); ?>
</aside>
<?php endif; ?>
