<?php
if (!defined('FIN_ECONOMY_VERSION')) {
    define('FIN_ECONOMY_VERSION', '1.0.0');
}

function fin_economy_setup() {
    load_theme_textdomain('fin-economy', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 72,
        'width'       => 72,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'fin-economy'),
        'footer'  => __('Footer Menu', 'fin-economy'),
    ]);
}
add_action('after_setup_theme', 'fin_economy_setup');

function fin_economy_widgets_init() {
    register_sidebar([
        'name'          => __('Main Sidebar', 'fin-economy'),
        'id'            => 'main_sidebar',
        'description'   => __('Right column for categories and popular posts.', 'fin-economy'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'fin_economy_widgets_init');

function fin_economy_assets() {
    wp_enqueue_style('fin-economy-style', get_stylesheet_uri(), [], FIN_ECONOMY_VERSION);
    wp_enqueue_script('fin-economy-script', get_template_directory_uri() . '/main.js', [], FIN_ECONOMY_VERSION, true);

    $header_bg   = get_theme_mod('fin_economy_header_bg_color', '#ffffff');
    $header_text = get_theme_mod('fin_economy_header_text_color', '#0f172a');
    $accent      = get_theme_mod('fin_economy_accent_color', '#2563eb');
    $footer_bg   = get_theme_mod('fin_economy_footer_bg_color', '#0b1220');
    $footer_text = get_theme_mod('fin_economy_footer_text_color', '#e2e8f0');

    $custom_css = sprintf(
        ':root{--header-bg:%1$s;--header-text:%2$s;--header-border:rgba(15,23,42,0.08);--header-muted:rgba(15,23,42,0.55);--accent:%3$s;--footer-bg:%4$s;--footer-text:%5$s;--footer-border:rgba(255,255,255,0.08);} .site-header{background:%1$s;color:%2$s;} .site-footer{background:%4$s;color:%5$s;}',
        esc_html($header_bg),
        esc_html($header_text),
        esc_html($accent),
        esc_html($footer_bg),
        esc_html($footer_text)
    );

    wp_add_inline_style('fin-economy-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'fin_economy_assets');

function fin_economy_customize_register($wp_customize) {
    $wp_customize->add_section('fin_economy_header_section', [
        'title'    => __('Header Styling', 'fin-economy'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('fin_economy_header_bg_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_header_bg_color', [
        'label'   => __('Header background color', 'fin-economy'),
        'section' => 'fin_economy_header_section',
    ]));

    $wp_customize->add_setting('fin_economy_header_text_color', [
        'default'           => '#0f172a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_header_text_color', [
        'label'   => __('Header text color', 'fin-economy'),
        'section' => 'fin_economy_header_section',
    ]));

    $wp_customize->add_setting('fin_economy_accent_color', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_accent_color', [
        'label'   => __('Accent color', 'fin-economy'),
        'section' => 'colors',
    ]));

    $wp_customize->add_section('fin_economy_footer_section', [
        'title'    => __('Footer', 'fin-economy'),
        'priority' => 40,
    ]);

    $wp_customize->add_setting('fin_economy_footer_text', [
        'default'           => __('© 2025 «Фінанси та економія».', 'fin-economy'),
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('fin_economy_footer_text', [
        'label'   => __('Footer text', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('fin_economy_footer_bg_color', [
        'default'           => '#0b1220',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_footer_bg_color', [
        'label'   => __('Footer background color', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
    ]));

    $wp_customize->add_setting('fin_economy_footer_text_color', [
        'default'           => '#e2e8f0',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_footer_text_color', [
        'label'   => __('Footer text color', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
    ]));

    $social_networks = [
        'facebook'  => __('Facebook URL', 'fin-economy'),
        'twitter'   => __('X / Twitter URL', 'fin-economy'),
        'instagram' => __('Instagram URL', 'fin-economy'),
        'linkedin'  => __('LinkedIn URL', 'fin-economy'),
        'youtube'   => __('YouTube URL', 'fin-economy'),
    ];

    foreach ($social_networks as $key => $label) {
        $setting_id = 'fin_economy_social_' . $key;
        $wp_customize->add_setting($setting_id, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control($setting_id, [
            'label'   => $label,
            'section' => 'fin_economy_footer_section',
            'type'    => 'url',
        ]);
    }

    $wp_customize->add_setting('fin_economy_social_rss', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('fin_economy_social_rss', [
        'label'       => __('RSS feed URL', 'fin-economy'),
        'description' => __('Leave empty to hide the RSS icon.', 'fin-economy'),
        'section'     => 'fin_economy_footer_section',
        'type'        => 'url',
    ]);

    $wp_customize->add_setting('fin_economy_head_scripts', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('fin_economy_head_scripts', [
        'label'       => __('Custom scripts in <head>', 'fin-economy'),
        'description' => __('Paste analytics or verification snippets. Avoid <script> tags with remote sources when possible.', 'fin-economy'),
        'section'     => 'title_tagline',
        'type'        => 'textarea',
    ]);
}
add_action('customize_register', 'fin_economy_customize_register');

function fin_economy_head_scripts() {
    $scripts = get_theme_mod('fin_economy_head_scripts', '');
    if (!empty($scripts)) {
        echo '<!-- Custom head scripts -->' . wp_kses_post($scripts);
    }
}
add_action('wp_head', 'fin_economy_head_scripts');

function fin_economy_get_popular_posts($count = 4) {
    $query = new WP_Query([
        'posts_per_page'      => $count,
        'orderby'             => 'comment_count',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    ]);

    if (!$query->have_posts()) {
        $query = new WP_Query([
            'posts_per_page'      => $count,
            'ignore_sticky_posts' => true,
        ]);
    }

    return $query;
}
