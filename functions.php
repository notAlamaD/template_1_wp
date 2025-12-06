<?php
function global_bulletin_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 64,
        'width'       => 64,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    register_nav_menus([
        'primary' => __('Primary Menu', 'global-bulletin'),
    ]);
}
add_action('after_setup_theme', 'global_bulletin_setup');

function global_bulletin_assets() {
    wp_enqueue_style('global-bulletin-style', get_stylesheet_uri(), [], '1.0.0');

    $header_bg = get_theme_mod('global_bulletin_header_bg_color', '#ffffff');
    $header_text = get_theme_mod('global_bulletin_header_text_color', '#0f172a');

    $custom_css = sprintf(
        ':root{--header-bg:%1$s;--header-text:%2$s;--header-border:rgba(15,23,42,0.08);--header-muted:rgba(15,23,42,0.55);} .site-header{background:%1$s;color:%2$s;}',
        esc_html($header_bg),
        esc_html($header_text)
    );

    wp_add_inline_style('global-bulletin-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'global_bulletin_assets');

function global_bulletin_customize_register($wp_customize) {
    $wp_customize->add_section('global_bulletin_header_section', [
        'title'    => __('Header Styling', 'global-bulletin'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('global_bulletin_header_bg_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_bulletin_header_bg_color', [
        'label'   => __('Header background color', 'global-bulletin'),
        'section' => 'global_bulletin_header_section',
    ]));

    $wp_customize->add_setting('global_bulletin_header_text_color', [
        'default'           => '#0f172a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_bulletin_header_text_color', [
        'label'   => __('Header text color', 'global-bulletin'),
        'section' => 'global_bulletin_header_section',
    ]));
}
add_action('customize_register', 'global_bulletin_customize_register');
