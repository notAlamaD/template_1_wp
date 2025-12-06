<?php get_header(); ?>

<main>
    <?php if (have_posts()) : ?>
        <?php $hero_post = get_posts(['numberposts' => 1])[0] ?? null; ?>
        <?php if ($hero_post) : setup_postdata($hero_post); ?>
            <section class="hero">
                <div>
                    <p class="post-meta">Свежий материал</p>
                    <h2 class="hero-title"><a href="<?php echo get_permalink($hero_post); ?>" style="color: inherit;">
                        <?php echo esc_html(get_the_title($hero_post)); ?>
                    </a></h2>
                    <p class="hero-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($hero_post), 24)); ?></p>
                </div>
                <?php if (has_post_thumbnail($hero_post)) : ?>
                    <div>
                        <?php echo get_the_post_thumbnail($hero_post, 'large', ['style' => 'width:100%;height:auto;border-radius:12px;']); ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php wp_reset_postdata(); endif; ?>

        <div class="content" aria-label="Latest stories">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </a>
                    <?php endif; ?>
                    <div>
                        <p class="post-meta"><?php echo get_the_date(); ?> · <?php the_author(); ?></p>
                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="content"><p>Публикаций пока нет.</p></div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
