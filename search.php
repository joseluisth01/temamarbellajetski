<?php
/**
 * Search Results Template
 */
get_header();
$lang = mjsk_get_lang();
?>

<main id="main-content" class="blog-main">

    <!-- Search Hero -->
    <section class="blog-archive-hero">
        <div class="container">
            <div class="blog-archive-badge"><i class="fas fa-search"></i> <?php echo $lang === 'es' ? 'Resultados' : 'Search Results'; ?></div>
            <h1><?php echo $lang === 'es' ? 'Resultados para' : 'Results for'; ?>: <span>&ldquo;<?php echo esc_html(get_search_query()); ?>&rdquo;</span></h1>
            <p class="blog-archive-desc">
                <?php
                $found = $wp_query->found_posts;
                if ($lang === 'es') {
                    printf('%d resultado%s encontrado%s', $found, $found !== 1 ? 's' : '', $found !== 1 ? 's' : '');
                } else {
                    printf('%d result%s found', $found, $found !== 1 ? 's' : '');
                }
                ?>
            </p>

            <!-- Search Again -->
            <form class="blog-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>"
                       placeholder="<?php echo $lang === 'es' ? 'Buscar...' : 'Search...'; ?>" class="blog-search-input">
                <button type="submit" class="blog-search-btn"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </section>

    <div class="container">
        <div class="blog-archive-layout">

            <!-- Results Grid -->
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
                                <i class="fas fa-<?php echo get_post_type() === 'page' ? 'file-alt' : 'water'; ?>"></i>
                            </div>
                            <?php endif; ?>

                            <div class="blog-card-body">
                                <span class="blog-card-category">
                                    <?php echo get_post_type() === 'page'
                                        ? ($lang === 'es' ? 'Página' : 'Page')
                                        : ($lang === 'es' ? 'Artículo' : 'Post'); ?>
                                </span>
                                <h2 class="blog-card-title"><?php the_title(); ?></h2>
                                <p class="blog-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <div class="blog-card-footer">
                                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                </div>
                            </div>
                        </a>
                    </article>

                    <?php endwhile; ?>
                <?php else : ?>

                    <div class="blog-no-posts">
                        <i class="fas fa-search"></i>
                        <h2><?php echo $lang === 'es' ? 'Sin resultados' : 'No results found'; ?></h2>
                        <p><?php echo $lang === 'es'
                            ? 'No encontramos nada para tu búsqueda. Intenta con otros términos.'
                            : 'We couldn\'t find anything for your search. Try different terms.'; ?></p>
                        <a href="<?php echo esc_url(mjsk_get_home_url($lang)); ?>" class="btn-primary">
                            <?php echo $lang === 'es' ? 'Volver al Inicio' : 'Back to Home'; ?>
                        </a>
                    </div>

                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <?php if (is_active_sidebar('blog-sidebar')) : ?>
                    <?php dynamic_sidebar('blog-sidebar'); ?>
                <?php else : ?>
                    <div class="blog-sidebar-widget blog-sidebar-cta">
                        <h3><?php echo $lang === 'es' ? '¿Listo para la Aventura?' : 'Ready for Adventure?'; ?></h3>
                        <p><?php echo $lang === 'es'
                            ? 'Reserva tu experiencia acuática hoy.'
                            : 'Book your water experience today.'; ?></p>
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
        <nav class="blog-pagination" aria-label="<?php echo $lang === 'es' ? 'Paginación' : 'Pagination'; ?>">
            <?php
            echo paginate_links([
                'prev_text' => '<i class="fas fa-arrow-left"></i> ' . ($lang === 'es' ? 'Anterior' : 'Previous'),
                'next_text' => ($lang === 'es' ? 'Siguiente' : 'Next') . ' <i class="fas fa-arrow-right"></i>',
                'type'      => 'list',
            ]);
            ?>
        </nav>
        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>
