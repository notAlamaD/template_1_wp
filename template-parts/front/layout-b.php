<?php
if (!defined('ABSPATH')) {
    exit;
}

$blocks       = get_query_var('fin_economy_home_blocks', fin_economy_get_home_blocks('b'));
$show_sidebar = get_query_var('fin_economy_show_sidebar', true);

function fin_economy_render_feed_block() {
    $feed = new WP_Query([
        'posts_per_page'      => 7,
        'ignore_sticky_posts' => true,
    ]);
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('Головні матеріали', 'fin-economy'); ?></h2>
        </header>
        <?php if ($feed->have_posts()) : ?>
            <ul class="feed-list">
                <?php while ($feed->have_posts()) : $feed->the_post(); ?>
                    <li class="feed-item">
                        <?php if (has_post_thumbnail()) : ?>
                            <a class="feed-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                        <?php endif; ?>
                        <div class="feed-body">
                            <div class="post-meta-row">
                                <?php $category = get_the_category(); ?>
                                <?php if (!empty($category)) : ?>
                                    <span class="badge"><?php echo esc_html($category[0]->name); ?></span>
                                <?php endif; ?>
                                <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                            </div>
                            <h3 class="feed-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="feed-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <p><?php esc_html_e('Публікацій поки немає.', 'fin-economy'); ?></p>
        <?php endif; ?>
    </section>
    <?php
}

function fin_economy_locate_category($slug, $label) {
    $cat = get_category_by_slug($slug);
    if (!$cat) {
        $cat = get_term_by('name', $label, 'category');
    }
    return $cat;
}

function fin_economy_render_classic_sections() {
    $sections = [
        ['title' => __('Комуналка', 'fin-economy'), 'slug' => 'komunalka'],
        ['title' => __('Тарифи', 'fin-economy'), 'slug' => 'tarifi'],
        ['title' => __('Економія', 'fin-economy'), 'slug' => 'ekonomiya'],
        ['title' => __('Фінанси', 'fin-economy'), 'slug' => 'finansi'],
        ['title' => __('Інвестиції', 'fin-economy'), 'slug' => 'investytsiyi'],
    ];
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('За рубриками', 'fin-economy'); ?></h2>
        </header>
        <div class="section-columns">
            <?php foreach ($sections as $section) :
                $category = fin_economy_locate_category($section['slug'], $section['title']);
                if (!$category) {
                    continue;
                }
                $query = new WP_Query([
                    'posts_per_page'      => 3,
                    'cat'                 => $category->term_id,
                    'ignore_sticky_posts' => true,
                ]);
                if (!$query->have_posts()) {
                    continue;
                }
                ?>
                <div class="section-column">
                    <h3 class="section-subtitle"><?php echo esc_html($section['title']); ?></h3>
                    <ul class="section-list">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

function fin_economy_render_classic_popular() {
    $popular = fin_economy_get_popular_posts(5);
    if (!$popular->have_posts()) {
        return;
    }
    ?>
    <section class="section-block">
        <header class="section-heading">
            <h2><?php esc_html_e('Популярне зараз', 'fin-economy'); ?></h2>
        </header>
        <ul class="popular-list">
            <?php while ($popular->have_posts()) : $popular->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
                </li>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </ul>
    </section>
    <?php
}

$grid_open = false;
foreach ($blocks as $block) {
    if (!$grid_open) {
        echo '<div class="content-grid"><div class="content-area">';
        $grid_open = true;
    }

    switch ($block['id']) {
        case 'latest':
            fin_economy_render_feed_block();
            break;
        case 'categories':
            fin_economy_render_classic_sections();
            break;
        case 'popular':
            fin_economy_render_classic_popular();
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
