<?php
/**
 * Blog Archive / Category / Tag Template
 *
 * Displays blog listing, category pages, and tag pages.
 * Fully translated for EN, ES, FR, NL.
 */
get_header();
$lang = mjsk_get_lang();
$blog_url = $lang === 'en' ? home_url('/blog/') : home_url('/' . $lang . '/blog/');
?>

<main id="main-content" class="blog-main">

    <!-- Archive Hero -->
    <section class="blog-archive-hero">
        <div class="container">
            <?php if (is_category()) : ?>
                <div class="blog-archive-badge"><i class="fas fa-folder"></i> <?php echo mjsk_t('blog_category'); ?></div>
                <h1><?php single_cat_title(); ?></h1>
                <?php if (category_description()) : ?>
                <p class="blog-archive-desc"><?php echo category_description(); ?></p>
                <?php endif; ?>
            <?php elseif (is_tag()) : ?>
                <div class="blog-archive-badge"><i class="fas fa-tag"></i> <?php echo mjsk_t('blog_tag'); ?></div>
                <h1>#<?php single_tag_title(); ?></h1>
            <?php elseif (is_author()) : ?>
                <div class="blog-archive-badge"><i class="fas fa-user"></i> <?php echo mjsk_t('blog_author'); ?></div>
                <h1><?php the_author(); ?></h1>
            <?php elseif (is_date()) : ?>
                <div class="blog-archive-badge"><i class="fas fa-calendar"></i> <?php echo mjsk_t('blog_archive'); ?></div>
                <h1><?php echo get_the_date('F Y'); ?></h1>
            <?php else : ?>
                <h1>Blog</h1>
                <p class="blog-archive-desc"><?php echo mjsk_t('blog_desc'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <div class="container">
        <div class="blog-archive-layout">

            <!-- Posts Grid -->
            <div class="blog-archive-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>

                    <article class="blog-card">
                        <a href="<?php the_permalink(); ?>" class="blog-card-link">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="blog-card-image">
                                <?php the_post_thumbnail('medium_large', ['loading' => 'lazy']); ?>
                            </div>
                            <?php else : ?>
                            <div class="blog-card-image blog-card-image-placeholder">
                                <i class="fas fa-water"></i>
                            </div>
                            <?php endif; ?>

                            <div class="blog-card-body">
                                <?php
                                $categories = get_the_category();
                                if ($categories) : ?>
                                <span class="blog-card-category"><?php echo esc_html($categories[0]->name); ?></span>
                                <?php endif; ?>

                                <h2 class="blog-card-title"><?php the_title(); ?></h2>

                                <p class="blog-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>

                                <div class="blog-card-footer">
                                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                    <span class="blog-card-read"><?php echo mjsk_reading_time(); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>

                    <?php endwhile; ?>
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
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <?php if (is_active_sidebar('blog-sidebar')) : ?>
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                <?php else : ?>
                    <!-- Categories -->
                    <div class="blog-sidebar-widget">
                        <h3><?php echo mjsk_t('blog_categories'); ?></h3>
                        <ul class="blog-sidebar-categories">
                            <?php wp_list_categories([
                                'title_li'   => '',
                                'show_count' => true,
                            ]); ?>
                        </ul>
                    </div>

                    <!-- CTA Widget -->
                    <div class="blog-sidebar-widget blog-sidebar-cta">
                        <h3><?php echo mjsk_t('blog_cta_title'); ?></h3>
                        <p><?php echo mjsk_t('blog_cta_text'); ?></p>
                        <a href="<?php echo esc_url(mjsk_get_booking_url($lang)); ?>" class="btn-primary">
                            <i class="fas fa-calendar-check"></i>
                            <?php echo mjsk_t('book_now'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </aside>

        </div>

        <!-- Pagination -->
        <?php if (have_posts()) : ?>
        <nav class="blog-pagination" aria-label="<?php echo mjsk_t('blog_pagination'); ?>">
            <?php
            echo paginate_links([
                'prev_text' => '<i class="fas fa-arrow-left"></i> ' . mjsk_t('blog_prev'),
                'next_text' => mjsk_t('blog_next') . ' <i class="fas fa-arrow-right"></i>',
                'type'      => 'list',
            ]);
            ?>
        </nav>
        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
