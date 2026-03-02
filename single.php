<?php
/**
 * Single Post Template
 *
 * Displays individual blog posts.
 * Fully translated for EN, ES, FR, NL.
 */
get_header();
$lang = mjsk_get_lang();
$blog_url = $lang === 'en' ? home_url('/blog/') : home_url('/' . $lang . '/blog/');
?>

<main id="main-content" class="blog-main">

    <!-- Breadcrumb -->
    <nav class="blog-breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <a href="<?php echo esc_url(mjsk_get_home_url($lang)); ?>"><?php echo mjsk_t('home'); ?></a>
            <i class="fas fa-chevron-right"></i>
            <a href="<?php echo esc_url($blog_url); ?>">Blog</a>
            <i class="fas fa-chevron-right"></i>
            <span><?php the_title(); ?></span>
        </div>
    </nav>

    <?php while (have_posts()) : the_post(); ?>

    <article class="blog-single">
        <!-- Hero -->
        <?php if (has_post_thumbnail()) : ?>
        <div class="blog-single-hero">
            <?php the_post_thumbnail('full', ['class' => 'blog-single-hero-img', 'loading' => 'eager']); ?>
            <div class="blog-single-hero-overlay"></div>
        </div>
        <?php endif; ?>

        <div class="container">
            <div class="blog-single-layout">
                <!-- Content Column -->
                <div class="blog-single-content">
                    <header class="blog-single-header">
                        <div class="blog-single-meta">
                            <?php
                            $categories = get_the_category();
                            if ($categories) : ?>
                            <span class="blog-single-category"><?php echo esc_html($categories[0]->name); ?></span>
                            <?php endif; ?>
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                            <span>&middot;</span>
                            <span><?php echo mjsk_reading_time(); ?></span>
                        </div>
                        <h1><?php the_title(); ?></h1>
                    </header>

                    <div class="blog-single-body entry-content">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tags -->
                    <?php
                    $tags = get_the_tags();
                    if ($tags) : ?>
                    <div class="blog-single-tags">
                        <?php foreach ($tags as $tag) : ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="blog-tag">#<?php echo esc_html($tag->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Author Box -->
                    <div class="blog-author-box">
                        <?php echo get_avatar(get_the_author_meta('ID'), 64, '', '', ['class' => 'blog-author-avatar']); ?>
                        <div class="blog-author-info">
                            <span class="blog-author-label"><?php echo mjsk_t('blog_written_by'); ?></span>
                            <strong class="blog-author-name"><?php the_author(); ?></strong>
                        </div>
                    </div>

                    <!-- Post Navigation -->
                    <nav class="blog-post-nav">
                        <?php
                        $prev = get_previous_post();
                        $next = get_next_post();
                        ?>
                        <?php if ($prev) : ?>
                        <a href="<?php echo get_permalink($prev); ?>" class="blog-post-nav-link prev">
                            <i class="fas fa-arrow-left"></i>
                            <div>
                                <span class="nav-label"><?php echo mjsk_t('blog_prev'); ?></span>
                                <span class="nav-title"><?php echo esc_html($prev->post_title); ?></span>
                            </div>
                        </a>
                        <?php endif; ?>

                        <?php if ($next) : ?>
                        <a href="<?php echo get_permalink($next); ?>" class="blog-post-nav-link next">
                            <div>
                                <span class="nav-label"><?php echo mjsk_t('blog_next'); ?></span>
                                <span class="nav-title"><?php echo esc_html($next->post_title); ?></span>
                            </div>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php endif; ?>
                    </nav>
                </div>

                <!-- Sidebar -->
                <aside class="blog-sidebar">
                    <?php if (is_active_sidebar('blog-sidebar')) : ?>
                        <?php dynamic_sidebar('blog-sidebar'); ?>
                    <?php else : ?>
                        <!-- Default sidebar content -->
                        <div class="blog-sidebar-widget">
                            <h3><?php echo mjsk_t('blog_recent'); ?></h3>
                            <ul class="blog-sidebar-posts">
                            <?php
                            $recent = new WP_Query([
                                'posts_per_page' => 5,
                                'post__not_in'   => [get_the_ID()],
                                'no_found_rows'  => true,
                            ]);
                            while ($recent->have_posts()) : $recent->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('thumbnail', ['class' => 'sidebar-thumb', 'loading' => 'lazy']); ?>
                                        <?php endif; ?>
                                        <div>
                                            <span class="sidebar-post-title"><?php the_title(); ?></span>
                                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile;
                            wp_reset_postdata();
                            ?>
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
        </div>

    <?php endwhile; ?>

    </article>

</main>

<?php get_footer(); ?>
