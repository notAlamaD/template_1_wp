<?php
/*
Template Name: Tag Index
*/

get_header();

$paged     = max(1, get_query_var('paged'), get_query_var('page'));
$per_page  = 50;
$offset    = ($paged - 1) * $per_page;
$tags      = get_terms([
    'taxonomy'   => 'post_tag',
    'hide_empty' => false,
    'number'     => $per_page,
    'offset'     => $offset,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);
$total_terms = wp_count_terms('post_tag', ['hide_empty' => false]);
$total_tags  = is_wp_error($total_terms) ? 0 : (int) $total_terms;
$total_pages = $total_tags > 0 ? (int) ceil($total_tags / $per_page) : 1;
?>
<main id="main-content" class="site-main">
    <div class="content-grid">
        <div class="content-area">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class('page-article'); ?>>
                    <header class="page-header">
                        <h1><?php the_title(); ?></h1>
                    </header>
                    <?php if (get_the_content()) : ?>
                        <div class="page-content">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                    <div class="tag-archive">
                        <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                            <div class="tag-archive-grid">
                                <?php foreach ($tags as $tag) : ?>
                                    <a class="tag-card" href="<?php echo esc_url(get_term_link($tag)); ?>">
                                        <div class="tag-card-name"><?php echo esc_html($tag->name); ?></div>
                                        <div class="tag-card-meta">
                                            <?php
                                            echo sprintf(
                                                _n('%s post', '%s posts', $tag->count, 'fin-economy'),
                                                number_format_i18n($tag->count)
                                            );
                                            ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="empty-text"><?php echo esc_html__('No tags found.', 'fin-economy'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($total_pages > 1) : ?>
                        <div class="pagination">
                            <?php
                            echo paginate_links([
                                'total'   => $total_pages,
                                'current' => $paged,
                                'mid_size'=> 1,
                                'prev_text' => esc_html__('Previous', 'fin-economy'),
                                'next_text' => esc_html__('Next', 'fin-economy'),
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endwhile; endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
