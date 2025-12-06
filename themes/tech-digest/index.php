<?php get_header(); ?>

<main>
    <?php if (have_posts()) : ?>
        <?php $spotlight = get_posts(['numberposts' => 1, 'category_name' => 'featured'])[0] ?? null; ?>
        <?php if ($spotlight) : setup_postdata($spotlight); ?>
            <section class="hero">
                <span class="badge">Spotlight</span>
                <h2><a href="<?php echo get_permalink($spotlight); ?>" style="color: inherit;">
                    <?php echo esc_html(get_the_title($spotlight)); ?>
                </a></h2>
                <p><?php echo esc_html(wp_trim_words(get_the_excerpt($spotlight), 26)); ?></p>
            </section>
        <?php wp_reset_postdata(); endif; ?>

        <div class="content">
            <div class="grid" aria-label="Latest technology stories">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                        <?php endif; ?>
                        <p class="post-meta"><?php echo get_the_date(); ?> · <?php the_author(); ?></p>
                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="content"><p>Нет публикаций.</p></div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
