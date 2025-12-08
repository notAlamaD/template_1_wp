<?php
if (!defined('ABSPATH')) {
    exit;
}

$blocks       = get_query_var('fin_economy_home_blocks', fin_economy_get_home_blocks('a'));
$show_sidebar = get_query_var('fin_economy_show_sidebar', true);

function fin_economy_render_hero_block() {
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

    if (!$hero_query->have_posts()) {
        return;
    }

    $hero_query->the_post();
    $hero_post_id = get_the_ID();
    ?>
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
                <a class="hero-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
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
                        <?php else : ?>
                            <span class="accent-thumb thumb-placeholder" aria-hidden="true"></span>
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
    <?php
    wp_reset_postdata();
}

function fin_economy_render_latest_grid() {
    ?>
    <section class="section-block">
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
    </section>
    <?php
}

function fin_economy_render_category_block() {
    $categories = get_categories([
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 12,
        'hide_empty' => true,
    ]);
    if (empty($categories)) {
        return;
    }
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('По категоріях', 'fin-economy'); ?></h2>
        </header>
        <div class="category-chips">
            <?php foreach ($categories as $category) : ?>
                <a class="category-chip" href="<?php echo esc_url(get_category_link($category)); ?>">
                    <span class="category-chip-name"><?php echo esc_html($category->name); ?></span>
                    <span class="category-chip-count"><?php echo esc_html($category->count); ?> <?php esc_html_e('матеріалів', 'fin-economy'); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

function fin_economy_render_popular_block() {
    $popular = fin_economy_get_popular_posts(5);
    if (!$popular->have_posts()) {
        return;
    }
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('Популярне', 'fin-economy'); ?></h2>
        </header>
        <div class="popular-grid">
            <?php while ($popular->have_posts()) : $popular->the_post(); ?>
                <article class="popular-card">
                    <div class="popular-meta">
                        <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                    </div>
                    <h3 class="popular-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
}
?>
<?php
$grid_open = false;
foreach ($blocks as $block) {
    if ('hero' === $block['id']) {
        if ($grid_open) {
            echo '</div>'; // close content-area
            if ($show_sidebar) {
                get_sidebar();
            }
            echo '</div>'; // close content-grid
            $grid_open = false;
        }
        fin_economy_render_hero_block();
        continue;
    }

    if (!$grid_open) {
        echo '<div class="content-grid"><div class="content-area">';
        $grid_open = true;
    }

    switch ($block['id']) {
        case 'latest':
            fin_economy_render_latest_grid();
            break;
        case 'categories':
            fin_economy_render_category_block();
            break;
        case 'popular':
            fin_economy_render_popular_block();
            break;
    }
}

if ($grid_open) {
    echo '</div>'; // close content-area
    if ($show_sidebar) {
        get_sidebar();
    }
    echo '</div>'; // close content-grid
}
?>
