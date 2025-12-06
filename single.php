<?php get_header(); ?>
<main id="main-content" class="site-main">
    <div class="content-grid single-layout">
        <div class="content-area">
            <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumbs', 'fin-economy'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Головна', 'fin-economy'); ?></a>
                <span>→</span>
                <?php $category = get_the_category(); ?>
                <?php if (!empty($category)) : ?>
                    <a href="<?php echo esc_url(get_category_link($category[0]->term_id)); ?>"><?php echo esc_html($category[0]->name); ?></a>
                    <span>→</span>
                <?php endif; ?>
                <span><?php the_title(); ?></span>
            </nav>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class('single-article'); ?>>
                    <header class="single-header">
                        <div class="post-meta-row">
                            <?php if (!empty($category)) : ?>
                                <span class="badge"><?php echo esc_html($category[0]->name); ?></span>
                            <?php endif; ?>
                            <span class="meta-text"><?php echo esc_html(get_the_date()); ?></span>
                        </div>
                        <h1 class="single-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="single-thumb">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="single-content">
                        <?php the_content(); ?>
                    </div>
                </article>

                <?php
                $related = new WP_Query([
                    'posts_per_page' => 3,
                    'post__not_in'   => [get_the_ID()],
                    'cat'            => !empty($category) ? $category[0]->term_id : '',
                ]);
                if ($related->have_posts()) :
                    ?>
                    <section class="related-posts">
                        <h2><?php esc_html_e('Ще по темі', 'fin-economy'); ?></h2>
                        <div class="card-grid">
                            <?php while ($related->have_posts()) : $related->the_post(); ?>
                                <article <?php post_class('post-card'); ?>>
                                    <a class="post-thumb" href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) :
                                            the_post_thumbnail('medium');
                                        else : ?>
                                            <div class="thumb-placeholder" aria-hidden="true"></div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="post-info">
                                        <div class="post-meta-row">
                                            <?php $category_inner = get_the_category(); ?>
                                            <?php if (!empty($category_inner)) : ?>
                                                <span class="badge"><?php echo esc_html($category_inner[0]->name); ?></span>
                                            <?php endif; ?>
                                            <span class="meta-text"><?php echo esc_html(get_the_date()); ?></span>
                                        </div>
                                        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </section>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>
            <?php endwhile; endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
