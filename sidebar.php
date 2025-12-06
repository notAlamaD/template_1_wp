<aside class="sidebar">
    <?php if (is_active_sidebar('main_sidebar')) : ?>
        <?php dynamic_sidebar('main_sidebar'); ?>
    <?php else : ?>
        <section class="widget widget_categories">
            <h3 class="widget-title"><?php esc_html_e('Категорії', 'fin-economy'); ?></h3>
            <ul>
                <?php wp_list_categories(['title_li' => '']); ?>
            </ul>
        </section>

        <section class="widget widget_popular">
            <h3 class="widget-title"><?php esc_html_e('Популярне', 'fin-economy'); ?></h3>
            <ul class="popular-list">
                <?php
                $popular = fin_economy_get_popular_posts(4);
                if ($popular->have_posts()) :
                    while ($popular->have_posts()) :
                        $popular->the_post();
                        ?>
                        <li>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <span class="meta"><?php echo esc_html(get_the_date()); ?></span>
                        </li>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>
        </section>
    <?php endif; ?>
</aside>
