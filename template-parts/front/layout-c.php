<?php
if (!defined('ABSPATH')) {
    exit;
}

$blocks       = get_query_var('fin_economy_home_blocks', fin_economy_get_home_blocks('c'));
$show_sidebar = get_query_var('fin_economy_show_sidebar', true);

function fin_economy_render_highlights() {
    $highlight = new WP_Query([
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
    ]);
    if (!$highlight->have_posts()) {
        return;
    }
    ?>
    <section class="section-block compact-hero">
        <header class="section-heading">
            <h2><?php esc_html_e('Ключові матеріали', 'fin-economy'); ?></h2>
        </header>
        <div class="highlight-grid">
            <?php while ($highlight->have_posts()) : $highlight->the_post(); ?>
                <article class="highlight-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <a class="highlight-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                    <?php endif; ?>
                    <div class="highlight-body">
                        <div class="post-meta-row">
                            <?php $category = get_the_category(); ?>
                            <?php if (!empty($category)) : ?>
                                <span class="badge"><?php echo esc_html($category[0]->name); ?></span>
                            <?php endif; ?>
                            <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                        </div>
                        <h3 class="highlight-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p class="highlight-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
}

function fin_economy_locate_category_c($slug, $label) {
    $cat = get_category_by_slug($slug);
    if (!$cat) {
        $cat = get_term_by('name', $label, 'category');
    }
    return $cat;
}

function fin_economy_render_thematic_sections() {
    $sections = [
        ['title' => __('Комунальні послуги', 'fin-economy'), 'slug' => 'komunalni-poslugy'],
        ['title' => __('Економія в побуті', 'fin-economy'), 'slug' => 'ekonomiya-v-pobuti'],
        ['title' => __('Особисті фінанси', 'fin-economy'), 'slug' => 'osobysti-finansy'],
    ];
    ?>
    <section class="section-block thematic-blocks">
        <div class="thematic-grid">
            <?php foreach ($sections as $section) :
                $category = fin_economy_locate_category_c($section['slug'], $section['title']);
                if (!$category) {
                    continue;
                }
                $query = new WP_Query([
                    'posts_per_page'      => 4,
                    'cat'                 => $category->term_id,
                    'ignore_sticky_posts' => true,
                ]);
                if (!$query->have_posts()) {
                    continue;
                }
                ?>
                <div class="thematic-column">
                    <h3 class="section-subtitle"><?php echo esc_html($section['title']); ?></h3>
                    <div class="card-grid mini">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <article class="post-card mini">
                                <a class="post-thumb" href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) :
                                        the_post_thumbnail('medium');
                                    else : ?>
                                        <div class="thumb-placeholder" aria-hidden="true"></div>
                                    <?php endif; ?>
                                </a>
                                <div class="post-info">
                                    <div class="post-meta-row">
                                        <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                                    </div>
                                    <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

function fin_economy_render_thematic_popular() {
    $popular = fin_economy_get_popular_posts(4);
    if (!$popular->have_posts()) {
        return;
    }
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('Популярне', 'fin-economy'); ?></h2>
        </header>
        <div class="popular-grid two-col">
            <?php while ($popular->have_posts()) : $popular->the_post(); ?>
                <article class="popular-card">
                    <h3 class="popular-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
    <?php
}

function fin_economy_render_thematic_latest() {
    $latest = new WP_Query([
        'posts_per_page'      => 6,
        'ignore_sticky_posts' => true,
    ]);
    if (!$latest->have_posts()) {
        return;
    }
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('Останні матеріали', 'fin-economy'); ?></h2>
        </header>
        <div class="card-grid mini">
            <?php while ($latest->have_posts()) : $latest->the_post(); ?>
                <article <?php post_class('post-card mini'); ?>>
                    <a class="post-thumb" href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) :
                            the_post_thumbnail('medium');
                        else : ?>
                            <div class="thumb-placeholder" aria-hidden="true"></div>
                        <?php endif; ?>
                    </a>
                    <div class="post-info">
                        <div class="post-meta-row">
                            <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                        </div>
                        <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
}

$grid_open = false;
foreach ($blocks as $block) {
    if ('hero' === $block['id']) {
        fin_economy_render_highlights();
        continue;
    }

    if (!$grid_open) {
        echo '<div class="content-grid"><div class="content-area">';
        $grid_open = true;
    }

    switch ($block['id']) {
        case 'latest':
            fin_economy_render_thematic_latest();
            break;
        case 'categories':
            fin_economy_render_thematic_sections();
            break;
        case 'popular':
            fin_economy_render_thematic_popular();
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
