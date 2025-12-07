<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_get_captcha_data() {
    static $captcha = null;

    if ($captcha === null) {
        $a            = wp_rand(1, 9);
        $b            = wp_rand(1, 9);
        $payload      = base64_encode($a . ':' . $b);
        $nonce_action = 'fin_economy_captcha_' . $a . '_' . $b;

        $captcha = [
            'a'            => $a,
            'b'            => $b,
            'payload'      => $payload,
            'nonce'        => wp_create_nonce($nonce_action),
            'nonce_action' => $nonce_action,
        ];
    }

    return $captcha;
}

function fin_economy_comment_form_defaults($defaults) {
    $defaults['title_reply']          = __('Коментарі', 'fin-economy');
    $defaults['title_reply_to']       = __('Відповідь на %s', 'fin-economy');
    $defaults['cancel_reply_link']    = __('Скасувати відповідь', 'fin-economy');
    $defaults['label_submit']         = __('Надіслати', 'fin-economy');
    $defaults['class_form']           = 'comment-form styled-comment-form';
    $defaults['comment_notes_before'] = '';
    $defaults['comment_notes_after']  = '';

    $defaults['comment_field'] = '<p class="comment-form-comment">'
        . '<label for="comment">' . esc_html__('Коментар', 'fin-economy') . '</label>'
        . '<textarea id="comment" name="comment" rows="5" required></textarea>'
        . '</p>';

    return $defaults;
}
add_filter('comment_form_defaults', 'fin_economy_comment_form_defaults');

function fin_economy_comment_form_fields($fields) {
    $commenter     = wp_get_current_commenter();
    $require_name  = (bool) get_option('require_name_email');
    $aria_required = $require_name ? ' aria-required="true" required' : '';
    $captcha       = fin_economy_get_captcha_data();

    $fields['author'] = '<p class="comment-form-author">'
        . '<label for="author">' . esc_html__('Ім’я', 'fin-economy') . '</label>'
        . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '"' . $aria_required . ' />'
        . '</p>';

    $fields['email'] = '<p class="comment-form-email">'
        . '<label for="email">' . esc_html__('Email', 'fin-economy') . '</label>'
        . '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '"' . $aria_required . ' />'
        . '</p>';

    $fields['fin_economy_captcha'] = '<div class="comment-form-captcha">'
        . '<label for="fin_economy_captcha_answer">' . esc_html__('Підтвердіть, що ви людина', 'fin-economy') . '</label>'
        . '<div class="captcha-row">'
        . '<span class="captcha-question">' . esc_html(sprintf(__('Скільки буде %1$s + %2$s?', 'fin-economy'), $captcha['a'], $captcha['b'])) . '</span>'
        . '<input id="fin_economy_captcha_answer" name="fin_economy_captcha_answer" type="number" inputmode="numeric" required />'
        . '</div>'
        . '<input type="hidden" name="fin_economy_captcha_payload" value="' . esc_attr($captcha['payload']) . '" />'
        . '<input type="hidden" name="fin_economy_captcha_nonce" value="' . esc_attr($captcha['nonce']) . '" />'
        . '</div>';

    return $fields;
}
add_filter('comment_form_fields', 'fin_economy_comment_form_fields');

function fin_economy_validate_captcha($commentdata) {
    if (is_user_logged_in()) {
        return $commentdata;
    }

    $answer  = isset($_POST['fin_economy_captcha_answer']) ? sanitize_text_field(wp_unslash($_POST['fin_economy_captcha_answer'])) : '';
    $payload = isset($_POST['fin_economy_captcha_payload']) ? sanitize_text_field(wp_unslash($_POST['fin_economy_captcha_payload'])) : '';
    $nonce   = isset($_POST['fin_economy_captcha_nonce']) ? sanitize_text_field(wp_unslash($_POST['fin_economy_captcha_nonce'])) : '';

    if ($answer === '' || $payload === '' || $nonce === '') {
        wp_die(esc_html__('Будь ласка, розв’яжіть приклад, щоб залишити коментар.', 'fin-economy'));
    }

    $decoded = base64_decode($payload);
    if (!$decoded || strpos($decoded, ':') === false) {
        wp_die(esc_html__('Невдала перевірка безпеки. Спробуйте ще раз.', 'fin-economy'));
    }

    list($a, $b) = array_map('absint', explode(':', $decoded));
    $nonce_action = 'fin_economy_captcha_' . $a . '_' . $b;

    if (!wp_verify_nonce($nonce, $nonce_action)) {
        wp_die(esc_html__('Перевірка безпеки не пройдена. Оновіть сторінку та спробуйте знову.', 'fin-economy'));
    }

    if ((int) $answer !== ($a + $b)) {
        wp_die(esc_html__('Неправильна відповідь на приклад. Спробуйте ще раз.', 'fin-economy'));
    }

    return $commentdata;
}
add_filter('preprocess_comment', 'fin_economy_validate_captcha');

function fin_economy_comment_markup($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo esc_attr($tag); ?> <?php comment_class('comment'); ?> id="comment-<?php comment_ID(); ?>">
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author">
                    <?php echo get_avatar($comment, 48); ?>
                    <div class="comment-author-info">
                        <b class="fn"><?php comment_author(); ?></b>
                        <span class="comment-date"><?php echo esc_html(get_comment_date(get_option('date_format'), $comment)); ?></span>
                    </div>
                </div>
            </footer>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <div class="comment-actions">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        [
                            'reply_text' => __('Відповісти', 'fin-economy'),
                            'add_below'  => 'div-comment',
                            'depth'      => $depth,
                            'max_depth'  => $args['max_depth'],
                        ]
                    )
                );
                ?>
            </div>
        </article>
    </<?php echo esc_attr($tag); ?>>
    <?php
}
