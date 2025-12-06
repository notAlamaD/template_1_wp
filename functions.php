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
        'footer'  => __('Footer Menu', 'global-bulletin'),
    ]);
}
add_action('after_setup_theme', 'global_bulletin_setup');

function global_bulletin_assets() {
    wp_enqueue_style('global-bulletin-style', get_stylesheet_uri(), [], '1.0.0');

    $header_bg = get_theme_mod('global_bulletin_header_bg_color', '#ffffff');
    $header_text = get_theme_mod('global_bulletin_header_text_color', '#0f172a');
    $footer_bg = get_theme_mod('global_bulletin_footer_bg_color', '#0b1220');
    $footer_text = get_theme_mod('global_bulletin_footer_text_color', '#e2e8f0');

    $custom_css = sprintf(
        ':root{--header-bg:%1$s;--header-text:%2$s;--header-border:rgba(15,23,42,0.08);--header-muted:rgba(15,23,42,0.55);--footer-bg:%3$s;--footer-text:%4$s;--footer-border:rgba(255,255,255,0.08);} .site-header{background:%1$s;color:%2$s;} .site-footer{background:%3$s;color:%4$s;}',
        esc_html($header_bg),
        esc_html($header_text),
        esc_html($footer_bg),
        esc_html($footer_text)
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

    $wp_customize->add_section('global_bulletin_footer_section', [
        'title'    => __('Footer', 'global-bulletin'),
        'priority' => 40,
    ]);

    $wp_customize->add_setting('global_bulletin_footer_text', [
        'default'           => __('Stay informed with our latest coverage.', 'global-bulletin'),
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('global_bulletin_footer_text', [
        'label'   => __('Footer text', 'global-bulletin'),
        'section' => 'global_bulletin_footer_section',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('global_bulletin_footer_bg_color', [
        'default'           => '#0b1220',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_bulletin_footer_bg_color', [
        'label'   => __('Footer background color', 'global-bulletin'),
        'section' => 'global_bulletin_footer_section',
    ]));

    $wp_customize->add_setting('global_bulletin_footer_text_color', [
        'default'           => '#e2e8f0',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'global_bulletin_footer_text_color', [
        'label'   => __('Footer text color', 'global-bulletin'),
        'section' => 'global_bulletin_footer_section',
    ]));

    $social_networks = [
        'facebook'  => __('Facebook URL', 'global-bulletin'),
        'twitter'   => __('X / Twitter URL', 'global-bulletin'),
        'instagram' => __('Instagram URL', 'global-bulletin'),
        'linkedin'  => __('LinkedIn URL', 'global-bulletin'),
        'youtube'   => __('YouTube URL', 'global-bulletin'),
    ];

    foreach ($social_networks as $key => $label) {
        $setting_id = 'global_bulletin_social_' . $key;
        $wp_customize->add_setting($setting_id, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $label,
            'section' => 'global_bulletin_footer_section',
            'type'    => 'url',
        ]);
    }

    $wp_customize->add_setting('global_bulletin_social_rss', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control('global_bulletin_social_rss', [
        'label'       => __('RSS feed URL', 'global-bulletin'),
        'description' => __('Leave empty to hide the RSS icon. You can paste your site feed or any other public feed URL.', 'global-bulletin'),
        'section'     => 'global_bulletin_footer_section',
        'type'        => 'url',
    ]);
}
add_action('customize_register', 'global_bulletin_customize_register');
