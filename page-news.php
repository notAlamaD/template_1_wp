<?php
/*
Template Name: News List
*/

get_header();

$paged = max(1, get_query_var('paged'), get_query_var('page'));
$news  = new WP_Query([
    'post_type'           => 'post',
    'posts_per_page'      => 10,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
]);
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
                    <?php if ($news->have_posts()) : ?>
                        <ul class="feed-list news-feed-list">
                            <?php while ($news->have_posts()) : $news->the_post(); ?>
                                <li <?php post_class('feed-item news-feed-item'); ?>>
                                    <a class="feed-thumb" href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium'); ?>
                                        <?php else : ?>
                                            <span class="thumb-placeholder" aria-hidden="true"></span>
                                        <?php endif; ?>
                                    </a>
                                    <div class="feed-body">
                                        <div class="post-meta-row">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                <?php echo esc_html(fin_economy_get_localized_date()); ?>
                                            </time>
                                        </div>
                                        <h2 class="feed-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                        <?php $tags = get_the_tags(); ?>
                                        <?php if ($tags) : ?>
                                            <div class="post-tags">
                                                <span class="post-tags-label"><?php echo esc_html__('Tags', 'fin-economy'); ?>:</span>
                                                <div class="post-tags-list">
                                                    <?php foreach ($tags as $tag) : ?>
                                                        <a class="tag-chip" href="<?php echo esc_url(get_tag_link($tag)); ?>"><?php echo esc_html($tag->name); ?></a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <div class="pagination">
                            <?php
                            echo paginate_links([
                                'total'     => $news->max_num_pages,
                                'current'   => $paged,
                                'mid_size'  => 1,
                                'prev_text' => esc_html__('Previous', 'fin-economy'),
                                'next_text' => esc_html__('Next', 'fin-economy'),
                            ]);
                            ?>
                        </div>
                    <?php else : ?>
                        <p class="empty-text"><?php echo esc_html__('No posts found.', 'fin-economy'); ?></p>
                    <?php endif; ?>
                </article>
            <?php endwhile; endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
