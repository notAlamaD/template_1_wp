<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_get_captcha_data() {
    $type = fin_economy_get_captcha_type();

    if ($type === 'recaptcha') {
        return fin_economy_get_recaptcha_keys();
    }

    static $captcha = null;

    if ($captcha === null) {
        $a            = wp_rand(1, 9);
        $b            = wp_rand(1, 9);
        $payload      = base64_encode($a . ':' . $b);
        $nonce_action = 'fin_economy_captcha_' . $a . '_' . $b;

        $captcha = [
            'type'         => 'math',
            'a'            => $a,
            'b'            => $b,
            'payload'      => $payload,
            'nonce'        => wp_create_nonce($nonce_action),
            'nonce_action' => $nonce_action,
        ];
    }

    return $captcha;
}

function fin_economy_get_captcha_type() {
    $type = get_theme_mod('fin_economy_captcha_type', 'math');

    if ($type === 'recaptcha') {
        $keys = fin_economy_get_recaptcha_keys();
        if (empty($keys['site_key']) || empty($keys['secret_key'])) {
            return 'math';
        }
    }

    return in_array($type, ['math', 'recaptcha'], true) ? $type : 'math';
}

function fin_economy_get_recaptcha_keys() {
    return [
        'site_key'   => trim((string) get_theme_mod('fin_economy_recaptcha_site_key', '')),
        'secret_key' => trim((string) get_theme_mod('fin_economy_recaptcha_secret_key', '')),
        'type'       => 'recaptcha',
    ];
}

function fin_economy_comment_form_defaults($defaults) {
    $defaults['title_reply']          = __('Додати коментар', 'fin-economy');
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

add_filter('pre_option_require_name_email', '__return_zero');

function fin_economy_comment_form_fields($fields) {
    $commenter     = wp_get_current_commenter();
    $captcha       = fin_economy_get_captcha_data();
    $aria_required = ' aria-required="true" required';

    $fields['author'] = '<p class="comment-form-author">'
        . '<label for="author">' . esc_html__('Ім’я', 'fin-economy') . '</label>'
        . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '"' . $aria_required . ' />'
        . '</p>';

    if ($captcha['type'] === 'recaptcha') {
        $fields['fin_economy_captcha'] = '<div class="comment-form-captcha comment-form-recaptcha">'
            . '<label>' . esc_html__('Підтвердіть, що ви людина', 'fin-economy') . '</label>'
            . '<div class="g-recaptcha" data-sitekey="' . esc_attr($captcha['site_key']) . '"></div>'
            . '</div>';
    } else {
        $fields['fin_economy_captcha'] = '<div class="comment-form-captcha">'
            . '<label for="fin_economy_captcha_answer">' . esc_html__('Підтвердіть, що ви людина', 'fin-economy') . '</label>'
            . '<div class="captcha-row">'
            . '<span class="captcha-question">' . esc_html(sprintf(__('Скільки буде %1$s + %2$s?', 'fin-economy'), $captcha['a'], $captcha['b'])) . '</span>'
            . '<input id="fin_economy_captcha_answer" name="fin_economy_captcha_answer" type="number" inputmode="numeric" required />'
            . '</div>'
            . '<input type="hidden" name="fin_economy_captcha_payload" value="' . esc_attr($captcha['payload']) . '" />'
            . '<input type="hidden" name="fin_economy_captcha_nonce" value="' . esc_attr($captcha['nonce']) . '" />'
            . '</div>';
    }

    unset($fields['email'], $fields['url'], $fields['cookies']);

    return $fields;
}
add_filter('comment_form_fields', 'fin_economy_comment_form_fields');

function fin_economy_validate_captcha($commentdata) {
    $type = fin_economy_get_captcha_type();

    if (is_user_logged_in() || !$type) {
        return $commentdata;
    }

    if ($type === 'recaptcha') {
        $keys = fin_economy_get_recaptcha_keys();
        $token = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';

        if (empty($token) || empty($keys['secret_key'])) {
            wp_die(esc_html__('Будь ласка, пройдіть перевірку, щоб залишити коментар.', 'fin-economy'));
        }

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret'   => $keys['secret_key'],
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
            ],
            'timeout' => 8,
        ]);

        $data = is_wp_error($response) ? null : json_decode(wp_remote_retrieve_body($response), true);

        if (empty($data['success'])) {
            wp_die(esc_html__('Перевірка reCAPTCHA не пройдена. Спробуйте ще раз.', 'fin-economy'));
        }
    } else {
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
        </article>
    </<?php echo esc_attr($tag); ?>>
    <?php
}
