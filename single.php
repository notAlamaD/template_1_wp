<?php get_header(); ?>
<main id="main-content" class="site-main">
    <div class="content-grid single-layout">
        <div class="content-area">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php $category = get_the_category(); ?>
                <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumbs', 'fin-economy'); ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Головна', 'fin-economy'); ?></a>
                    <span>→</span>
                    <?php if (!empty($category)) : ?>
                        <a href="<?php echo esc_url(get_category_link($category[0]->term_id)); ?>"><?php echo esc_html($category[0]->name); ?></a>
                        <span>→</span>
                    <?php endif; ?>
                    <span><?php the_title(); ?></span>
                </nav>

                <article <?php post_class('single-article'); ?>>
                    <header class="single-header">
                        <div class="post-meta-row">
                            <?php if (!empty($category)) : ?>
                                <span class="badge"><?php echo esc_html($category[0]->name); ?></span>
                            <?php endif; ?>
                            <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
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

                    <?php
                    $share_url   = rawurlencode(get_permalink());
                    $share_title = rawurlencode(get_the_title());
                    ?>
                    <div class="share-bar" aria-label="<?php esc_attr_e('Поділитися новиною', 'fin-economy'); ?>">
                        <span class="share-label"><?php esc_html_e('Поділитися', 'fin-economy'); ?></span>
                        <div class="share-actions">
                            <a class="share-button" href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u=' . $share_url); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e('Поділитися у Facebook', 'fin-economy'); ?></span>
                                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M13.5 9H16V6h-2.5C11.57 6 10 7.57 10 9.5V11H8v3h2v6h3v-6h2.1l.4-3H13v-1.5c0-.55.45-1 1-1Z"></path>
                                </svg>
                            </a>
                            <a class="share-button" href="<?php echo esc_url('https://twitter.com/intent/tweet?url=' . $share_url . '&text=' . $share_title); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e('Поділитися у X (Twitter)', 'fin-economy'); ?></span>
                                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="m6.5 5 4.9 6.4L6.6 19h2.1l3.8-5 3 5H17l-5-7 5-7h-2.1l-3.3 4.5L9 5H6.5Z"></path>
                                </svg>
                            </a>
                            <a class="share-button" href="<?php echo esc_url('https://t.me/share/url?url=' . $share_url . '&text=' . $share_title); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e('Поділитися у Telegram', 'fin-economy'); ?></span>
                                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19.8 4.2 3.7 10.7c-.9.4-.9 1.3-.1 1.6l3.7 1.3 1.4 4.2c.2.5.8.6 1.2.2l2-1.9 3.3 2.5c.4.3.9.1 1-.4l3.4-13.6c.2-.8-.4-1.3-1.1-1.1ZM8.3 13.1l9.2-5.7c.2-.1.4.1.2.2l-7.6 6.9c-.2.2-.3.3-.4.7l-.4 1.3-.9-2.8c-.1-.3 0-.5.2-.6Z"></path>
                                </svg>
                            </a>
                            <a class="share-button" href="<?php echo esc_url('https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url . '&title=' . $share_title); ?>" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e('Поділитися у LinkedIn', 'fin-economy'); ?></span>
                                <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5 4a2 2 0 1 1 0 4 2 2 0 0 1 0-4Zm-1.5 5h3v11h-3V9ZM10 9h2.8v1.6h.1c.4-.7 1.5-1.5 3-1.5C18.6 9.1 20 10.7 20 13.3V20h-3v-6c0-1.4-.5-2.3-1.7-2.3-1 0-1.6.7-1.9 1.4 0 .2-.1.4-.1.7V20h-3V9Z"></path>
                                </svg>
                            </a>
                        </div>
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
                                            <span class="meta-text"><?php echo esc_html(fin_economy_get_localized_date()); ?></span>
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
