<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_assets() {
    wp_enqueue_style('fin-economy-style', get_stylesheet_uri(), [], FIN_ECONOMY_VERSION);
    wp_enqueue_script('fin-economy-script', get_template_directory_uri() . '/main.js', [], FIN_ECONOMY_VERSION, true);

    $tokens = fin_economy_get_design_tokens();

    $hero_surface = fin_economy_hex_to_rgba($tokens['hero_text'], 0.08);
    $hero_border  = fin_economy_hex_to_rgba($tokens['hero_text'], 0.18);

    $custom_css = sprintf(
        ':root{--hero-bg-start:%1$s;--hero-bg-end:%2$s;--hero-text:%3$s;--hero-surface:%4$s;--hero-border:%5$s;--header-bg:%6$s;--header-text:%7$s;--header-border:rgba(15,23,42,0.08);--header-muted:rgba(15,23,42,0.55);--accent:%8$s;--button-bg:%11$s;--button-text:%12$s;--button-hover:%13$s;--footer-bg:%9$s;--footer-text:%10$s;--footer-border:rgba(255,255,255,0.08);} .site-header{background:%6$s;color:%7$s;} .site-footer{background:%9$s;color:%10$s;}',
        esc_html($tokens['hero_start']),
        esc_html($tokens['hero_end']),
        esc_html($tokens['hero_text']),
        esc_html($hero_surface),
        esc_html($hero_border),
        esc_html($tokens['header_bg']),
        esc_html($tokens['header_text']),
        esc_html($tokens['accent']),
        esc_html($tokens['footer_bg']),
        esc_html($tokens['footer_text']),
        esc_html($tokens['button_bg']),
        esc_html($tokens['button_text']),
        esc_html($tokens['button_hover'])
    );

    wp_add_inline_style('fin-economy-style', $custom_css);

    if (fin_economy_get_captcha_type() === 'recaptcha' && comments_open() && is_singular()) {
        $keys = fin_economy_get_recaptcha_keys();
        if (!empty($keys['site_key'])) {
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true);
        }
    }
}
add_action('wp_enqueue_scripts', 'fin_economy_assets');
