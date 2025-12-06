<?php get_header(); ?>
<main id="main-content" class="site-main">
    <div class="content-grid">
        <div class="content-area">
            <header class="section-heading">
                <h1><?php single_cat_title(); ?></h1>
                <?php if (category_description()) : ?>
                    <p class="archive-description"><?php echo wp_kses_post(category_description()); ?></p>
                <?php endif; ?>
            </header>

            <?php if (have_posts()) : ?>
                <div class="card-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('post-card'); ?>>
                            <a class="post-thumb" href="<?php the_permalink(); ?>">
                                <?php if (has_post_thumbnail()) :
                                    the_post_thumbnail('medium_large');
                                else : ?>
                                    <div class="thumb-placeholder" aria-hidden="true"></div>
                                <?php endif; ?>
                            </a>
                            <div class="post-info">
                                <div class="post-meta-row">
                                    <?php $category = get_the_category(); ?>
                                    <?php if (!empty($category)) : ?>
                                        <span class="badge"><?php echo esc_html($category[0]->name); ?></span>
                                    <?php endif; ?>
                                    <span class="meta-text"><?php echo esc_html(get_the_date()); ?></span>
                                </div>
                                <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination(); ?>
            <?php else : ?>
                <p><?php esc_html_e('Матеріалів поки немає.', 'fin-economy'); ?></p>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
