<?php get_header(); ?>
<main id="main-content" class="site-main">
    <?php
    $hero_category   = absint(get_theme_mod('fin_economy_hero_featured_category', 0));
    $accent_category = absint(get_theme_mod('fin_economy_hero_accent_category', 0));
    $accent_count    = max(1, min(6, absint(get_theme_mod('fin_economy_hero_accent_count', 3))));

    $hero_args = [
        'posts_per_page'      => 1,
        'ignore_sticky_posts' => true,
    ];

    if ($hero_category) {
        $hero_args['cat'] = $hero_category;
    }

    $hero_query = new WP_Query($hero_args);
    ?>

    <?php if ($hero_query->have_posts()) : ?>
        <?php $hero_query->the_post(); ?>
        <?php $hero_post_id = get_the_ID(); ?>
        <section class="hero">
            <div class="hero-featured">
                <div class="hero-meta">
                    <?php $category = get_the_category(); ?>
                    <?php if (!empty($category)) : ?>
                        <span class="badge"><?php echo esc_html($category[0]->name); ?></span>
                    <?php endif; ?>
                    <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                </div>
                <?php if (has_post_thumbnail()) : ?>
                    <a class="hero-thumb" href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('large'); ?>
                    </a>
                <?php endif; ?>
                <h1 class="hero-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                <p class="hero-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
                <a class="button" href="<?php the_permalink(); ?>"><?php esc_html_e('Читати →', 'fin-economy'); ?></a>
            </div>

            <div class="hero-accents" aria-label="<?php esc_attr_e('Quick highlights', 'fin-economy'); ?>">
                <?php
                $accent_args = [
                    'posts_per_page'      => $accent_count,
                    'ignore_sticky_posts' => true,
                    'post__not_in'        => [$hero_post_id],
                ];

                if ($accent_category) {
                    $accent_args['cat'] = $accent_category;
                } elseif ($hero_category) {
                    $accent_args['cat'] = $hero_category;
                }

                $accent_query = new WP_Query($accent_args);
                ?>

                <?php if ($accent_query->have_posts()) : ?>
                    <?php while ($accent_query->have_posts()) : $accent_query->the_post(); ?>
                        <article class="accent-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a class="accent-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            <?php endif; ?>
                            <div class="accent-body">
                                <h3 class="accent-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="meta-text accent-date"><?php echo esc_html(fin_economy_get_localized_date()); ?></div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

    <div class="content-grid">
        <div class="content-area">
            <header class="section-heading">
                <h2><?php esc_html_e('Останні матеріали', 'fin-economy'); ?></h2>
            </header>
            <div class="card-grid">
                <?php
                $latest = new WP_Query([
                    'posts_per_page'      => 9,
                    'ignore_sticky_posts' => true,
                ]);
                if ($latest->have_posts()) :
                    while ($latest->have_posts()) :
                        $latest->the_post();
                        ?>
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
                                    <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                                </div>
                                <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="post-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <p><?php esc_html_e('Публікацій поки немає.', 'fin-economy'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
