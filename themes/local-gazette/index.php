<?php get_header(); ?>

<main class="content">
    <?php if (have_posts()) : ?>
        <div class="grid" aria-label="Latest local updates">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
                    <?php endif; ?>
                    <p class="post-meta"><?php echo get_the_date(); ?> · <?php the_author(); ?> · <?php echo esc_html(get_the_category_list(', ')); ?></p>
                    <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 26)); ?></p>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p>Пока нет новостей.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
