<?php
/**
 * Template Name: Blog Page
 *
 * Displays blog listing for any language.
 * Assigned to /blog/, /es/blog/, /fr/blog/, /nl/blog/.
 */
get_header();
$lang = mjsk_get_lang();

// Query posts
$paged = max(1, get_query_var('paged'));
$blog_query = new WP_Query([
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
]);

// Category icons for placeholders
$cat_icons = [
    'jet ski'            => 'fas fa-water',
    'water activities'   => 'fas fa-swimming-pool',
    'boat hire'          => 'fas fa-ship',
    'travel guide'       => 'fas fa-map-marked-alt',
    'news'               => 'fas fa-newspaper',
];

// Category gradient colors for placeholders
$cat_colors = [
    'jet ski'            => ['#0ea5e9', '#0369a1'],
    'water activities'   => ['#06b6d4', '#0e7490'],
    'boat hire'          => ['#1a2d50', '#0a1628'],
    'travel guide'       => ['#f59e0b', '#d97706'],
    'news'               => ['#8b5cf6', '#6d28d9'],
];

// Blog title per language
$blog_titles = ['en' => 'Our Blog', 'es' => 'Nuestro Blog', 'fr' => 'Notre Blog', 'nl' => 'Ons Blog'];
$blog_title  = $blog_titles[$lang] ?? 'Our Blog';
?>

<main id="main-content" class="blog-main">

    <!-- Blog Hero -->
    <section class="blog-archive-hero">
        <div class="container">
            <div class="blog-hero-badge"><i class="fas fa-blog"></i> BLOG</div>
            <h1><?php echo $blog_title; ?></h1>
            <p class="blog-archive-desc"><?php echo mjsk_t('blog_desc'); ?></p>
        </div>
    </section>

    <div class="container">

        <?php if ($blog_query->have_posts()) : ?>

        <!-- Featured Post (first post gets special layout) -->
        <?php $blog_query->the_post(); ?>
        <?php
            $feat_cats = get_the_category();
            $feat_cat_name = $feat_cats ? strtolower($feat_cats[0]->name) : '';
            $feat_icon = $cat_icons[$feat_cat_name] ?? 'fas fa-water';
            $feat_grad = $cat_colors[$feat_cat_name] ?? ['#0ea5e9', '#0369a1'];
        ?>
        <article class="blog-featured">
            <a href="<?php the_permalink(); ?>" class="blog-featured-link">
                <?php if (has_post_thumbnail()) : ?>
                <div class="blog-featured-image">
                    <?php the_post_thumbnail('large', ['loading' => 'eager']); ?>
                    <div class="blog-featured-overlay"></div>
                </div>
                <?php else : ?>
                <div class="blog-featured-image blog-featured-placeholder" style="background: linear-gradient(135deg, <?php echo $feat_grad[0]; ?>, <?php echo $feat_grad[1]; ?>);">
                    <i class="<?php echo $feat_icon; ?>"></i>
                    <div class="blog-featured-overlay"></div>
                </div>
                <?php endif; ?>
                <div class="blog-featured-content">
                    <?php if ($feat_cats) : ?>
                    <span class="blog-card-category"><?php echo esc_html($feat_cats[0]->name); ?></span>
                    <?php endif; ?>
                    <h2><?php the_title(); ?></h2>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    <div class="blog-featured-meta">
                        <time datetime="<?php echo get_the_date('c'); ?>"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></time>
                        <span><i class="far fa-clock"></i> <?php echo mjsk_reading_time(); ?></span>
                    </div>
                </div>
            </a>
        </article>

        <!-- Posts Grid (remaining posts) -->
        <div class="blog-grid-section">
            <div class="blog-archive-grid">
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                <?php
                    $cats = get_the_category();
                    $cat_name = $cats ? strtolower($cats[0]->name) : '';
                    $icon = $cat_icons[$cat_name] ?? 'fas fa-water';
                    $grad = $cat_colors[$cat_name] ?? ['#0ea5e9', '#0369a1'];
                ?>

                <article class="blog-card">
                    <a href="<?php the_permalink(); ?>" class="blog-card-link">
                        <?php if (has_post_thumbnail()) : ?>
                        <div class="blog-card-image">
                            <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
                        </div>
                        <?php else : ?>
                        <div class="blog-card-image blog-card-image-placeholder" style="background: linear-gradient(135deg, <?php echo $grad[0]; ?>, <?php echo $grad[1]; ?>);">
                            <i class="<?php echo $icon; ?>"></i>
                        </div>
                        <?php endif; ?>

                        <div class="blog-card-body">
                            <?php if ($cats) : ?>
                            <span class="blog-card-category"><?php echo esc_html($cats[0]->name); ?></span>
                            <?php endif; ?>

                            <h2 class="blog-card-title"><?php the_title(); ?></h2>

                            <p class="blog-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>

                            <div class="blog-card-footer">
                                <time datetime="<?php echo get_the_date('c'); ?>"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></time>
                                <span class="blog-card-read"><i class="far fa-clock"></i> <?php echo mjsk_reading_time(); ?></span>
                            </div>
                        </div>
                    </a>
                </article>

                <?php endwhile; ?>
            </div>
        </div>

        <?php else : ?>

        <div class="blog-no-posts">
            <i class="fas fa-newspaper"></i>
            <h2><?php echo mjsk_t('blog_no_posts_title'); ?></h2>
            <p><?php echo mjsk_t('blog_no_posts_text'); ?></p>
            <a href="<?php echo esc_url(mjsk_get_home_url($lang)); ?>" class="btn-primary">
                <?php echo mjsk_t('blog_back_home'); ?>
            </a>
        </div>

        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($blog_query->max_num_pages > 1) : ?>
        <nav class="blog-pagination" aria-label="<?php echo mjsk_t('blog_pagination'); ?>">
            <?php
            echo paginate_links([
                'total'     => $blog_query->max_num_pages,
                'current'   => $paged,
                'prev_text' => '<i class="fas fa-arrow-left"></i> ' . mjsk_t('blog_prev'),
                'next_text' => mjsk_t('blog_next') . ' <i class="fas fa-arrow-right"></i>',
                'type'      => 'list',
            ]);
            ?>
        </nav>
        <?php endif; ?>

        <!-- CTA Section -->
        <section class="blog-cta-section">
            <div class="blog-cta-inner">
                <i class="fas fa-anchor blog-cta-icon"></i>
                <h2><?php echo mjsk_t('blog_cta_title'); ?></h2>
                <p><?php echo mjsk_t('blog_cta_text'); ?></p>
                <a href="<?php echo esc_url(mjsk_get_booking_url($lang)); ?>" class="blog-cta-btn">
                    <i class="fas fa-calendar-check"></i>
                    <?php echo mjsk_t('book_now'); ?>
                </a>
            </div>
        </section>

    </div>

</main>

<?php
wp_reset_postdata();
get_footer();
?>
