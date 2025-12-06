<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_assets() {
    wp_enqueue_style('fin-economy-style', get_stylesheet_uri(), [], FIN_ECONOMY_VERSION);
    wp_enqueue_script('fin-economy-script', get_template_directory_uri() . '/main.js', [], FIN_ECONOMY_VERSION, true);

    $hero_bg_start = get_theme_mod('fin_economy_hero_bg_start', '#0f172a');
    $hero_bg_end   = get_theme_mod('fin_economy_hero_bg_end', '#1e3a8a');
    $hero_text     = get_theme_mod('fin_economy_hero_text_color', '#e2e8f0');
    $header_bg     = get_theme_mod('fin_economy_header_bg_color', '#ffffff');
    $header_text   = get_theme_mod('fin_economy_header_text_color', '#0f172a');
    $accent        = get_theme_mod('fin_economy_accent_color', '#2563eb');
    $button_bg     = get_theme_mod('fin_economy_button_bg_color', $accent);
    $button_text   = get_theme_mod('fin_economy_button_text_color', '#ffffff');
    $button_hover  = get_theme_mod('fin_economy_button_hover_color', '#1d4ed8');
    $footer_bg     = get_theme_mod('fin_economy_footer_bg_color', '#0b1220');
    $footer_text   = get_theme_mod('fin_economy_footer_text_color', '#e2e8f0');

    $hero_surface = fin_economy_hex_to_rgba($hero_text, 0.08);
    $hero_border  = fin_economy_hex_to_rgba($hero_text, 0.18);

    $custom_css = sprintf(
        ':root{--hero-bg-start:%1$s;--hero-bg-end:%2$s;--hero-text:%3$s;--hero-surface:%4$s;--hero-border:%5$s;--header-bg:%6$s;--header-text:%7$s;--header-border:rgba(15,23,42,0.08);--header-muted:rgba(15,23,42,0.55);--accent:%8$s;--button-bg:%11$s;--button-text:%12$s;--button-hover:%13$s;--footer-bg:%9$s;--footer-text:%10$s;--footer-border:rgba(255,255,255,0.08);} .site-header{background:%6$s;color:%7$s;} .site-footer{background:%9$s;color:%10$s;}',
        esc_html($hero_bg_start),
        esc_html($hero_bg_end),
        esc_html($hero_text),
        esc_html($hero_surface),
        esc_html($hero_border),
        esc_html($header_bg),
        esc_html($header_text),
        esc_html($accent),
        esc_html($footer_bg),
        esc_html($footer_text),
        esc_html($button_bg),
        esc_html($button_text),
        esc_html($button_hover)
    );

    wp_add_inline_style('fin-economy-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'fin_economy_assets');
