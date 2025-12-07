<?php
if (post_password_required()) {
    return;
}
?>
<section id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $count = get_comments_number();
            printf(
                esc_html(_n('%s коментар', '%s коментарі(в)', $count, 'fin-economy')),
                number_format_i18n($count)
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size'=> 48,
                'callback'   => 'fin_economy_comment_markup',
            ]);
            ?>
        </ol>

        <?php the_comments_pagination([
            'prev_text' => esc_html__('Попередні', 'fin-economy'),
            'next_text' => esc_html__('Наступні', 'fin-economy'),
        ]); ?>
    <?php endif; ?>

    <?php if (comments_open()) : ?>
        <div class="comment-form-wrapper">
            <?php comment_form(); ?>
        </div>
    <?php endif; ?>
</section>
