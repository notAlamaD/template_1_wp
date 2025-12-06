<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_customize_register($wp_customize) {
    $wp_customize->add_section('fin_economy_layouts_section', [
        'title'    => __('Layout & Variants', 'fin-economy'),
        'priority' => 10,
    ]);

    $wp_customize->add_setting('fin_economy_home_layout', [
        'default'           => 'a',
        'sanitize_callback' => function ($value) {
            $allowed = ['a', 'b', 'c'];
            return in_array($value, $allowed, true) ? $value : 'a';
        },
    ]);
    $wp_customize->add_control('fin_economy_home_layout', [
        'label'       => __('Homepage layout', 'fin-economy'),
        'description' => __('Switch between hero-led, classic feed, or thematic block layouts.', 'fin-economy'),
        'section'     => 'fin_economy_layouts_section',
        'type'        => 'select',
        'choices'     => [
            'a' => __('Layout A — Hero + accents', 'fin-economy'),
            'b' => __('Layout B — Classic feed', 'fin-economy'),
            'c' => __('Layout C — Thematic blocks', 'fin-economy'),
        ],
    ]);

    $wp_customize->add_setting('fin_economy_header_variant', [
        'default'           => 'compact',
        'sanitize_callback' => function ($value) {
            return in_array($value, ['compact', 'centered'], true) ? $value : 'compact';
        },
    ]);
    $wp_customize->add_control('fin_economy_header_variant', [
        'label'   => __('Header variant', 'fin-economy'),
        'section' => 'fin_economy_layouts_section',
        'type'    => 'select',
        'choices' => [
            'compact'  => __('Compact (logo left, menu inline)', 'fin-economy'),
            'centered' => __('Centered (logo center with lower menu)', 'fin-economy'),
        ],
    ]);

    $wp_customize->add_setting('fin_economy_footer_variant', [
        'default'           => 'extended',
        'sanitize_callback' => function ($value) {
            return in_array($value, ['extended', 'simple'], true) ? $value : 'extended';
        },
    ]);
    $wp_customize->add_control('fin_economy_footer_variant', [
        'label'   => __('Footer variant', 'fin-economy'),
        'section' => 'fin_economy_layouts_section',
        'type'    => 'select',
        'choices' => [
            'extended' => __('Extended (multi-column)', 'fin-economy'),
            'simple'   => __('Simple (single row)', 'fin-economy'),
        ],
    ]);

    $wp_customize->add_section('fin_economy_style_section', [
        'title'    => __('Color schemes & typography', 'fin-economy'),
        'priority' => 15,
    ]);

    $wp_customize->add_setting('fin_economy_color_scheme', [
        'default'           => 'classic',
        'sanitize_callback' => function ($value) {
            return in_array($value, ['classic', 'green', 'dark'], true) ? $value : 'classic';
        },
    ]);
    $wp_customize->add_control('fin_economy_color_scheme', [
        'label'   => __('Color scheme', 'fin-economy'),
        'section' => 'fin_economy_style_section',
        'type'    => 'select',
        'choices' => [
            'classic' => __('Classic blue', 'fin-economy'),
            'green'   => __('Green economy', 'fin-economy'),
            'dark'    => __('Dark accent', 'fin-economy'),
        ],
    ]);

    $wp_customize->add_setting('fin_economy_typography', [
        'default'           => 'modern',
        'sanitize_callback' => function ($value) {
            return in_array($value, ['modern', 'news', 'soft'], true) ? $value : 'modern';
        },
    ]);
    $wp_customize->add_control('fin_economy_typography', [
        'label'   => __('Typography', 'fin-economy'),
        'section' => 'fin_economy_style_section',
        'type'    => 'select',
        'choices' => [
            'modern' => __('Modern Sans', 'fin-economy'),
            'news'   => __('News Serif', 'fin-economy'),
            'soft'   => __('Soft Sans', 'fin-economy'),
        ],
    ]);

    $wp_customize->add_section('fin_economy_hero_section', [
        'title'    => __('Hero Block', 'fin-economy'),
        'priority' => 25,
    ]);

    $wp_customize->add_setting('fin_economy_hero_featured_category', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_hero_featured_category', [
        'label'       => __('Featured post category', 'fin-economy'),
        'description' => __('Choose which category feeds the large hero article. Leave empty for the latest post from any category.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
        'type'        => 'dropdown-categories',
    ]);

    $wp_customize->add_setting('fin_economy_hero_accent_category', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_hero_accent_category', [
        'label'       => __('Quick accents category', 'fin-economy'),
        'description' => __('Select a category for the smaller highlight cards. Falls back to the featured category when empty.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
        'type'        => 'dropdown-categories',
    ]);

    $wp_customize->add_setting('fin_economy_hero_accent_count', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_hero_accent_count', [
        'label'       => __('Number of quick accents', 'fin-economy'),
        'description' => __('Control how many smaller cards appear beside the hero (1–6).', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
        'type'        => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 6,
        ],
    ]);

    $wp_customize->add_setting('fin_economy_hero_bg_start', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_bg_start', [
        'label'       => __('Hero background start', 'fin-economy'),
        'description' => __('Left/top gradient color for the hero block.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_setting('fin_economy_hero_bg_end', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_bg_end', [
        'label'       => __('Hero background end', 'fin-economy'),
        'description' => __('Right/bottom gradient color for the hero block.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_setting('fin_economy_hero_text_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_text_color', [
        'label'       => __('Hero text color', 'fin-economy'),
        'description' => __('Applies to hero headings, excerpts, and accent cards.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_section('fin_economy_header_section', [
        'title'    => __('Header Styling', 'fin-economy'),
        'priority' => 30,
    ]);

    $wp_customize->add_setting('fin_economy_header_bg_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_header_bg_color', [
        'label'   => __('Header background color', 'fin-economy'),
        'section' => 'fin_economy_header_section',
    ]));

    $wp_customize->add_setting('fin_economy_header_text_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_header_text_color', [
        'label'   => __('Header text color', 'fin-economy'),
        'section' => 'fin_economy_header_section',
    ]));

    $wp_customize->add_setting('fin_economy_accent_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_accent_color', [
        'label'   => __('Accent color', 'fin-economy'),
        'section' => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_bg_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_bg_color', [
        'label'       => __('Button background color', 'fin-economy'),
        'description' => __('Applies to primary buttons and hero CTAs.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_hover_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_hover_color', [
        'label'       => __('Button hover color', 'fin-economy'),
        'description' => __('Used for hover and focus states.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_text_color', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_text_color', [
        'label'       => __('Button text color', 'fin-economy'),
        'description' => __('Adjust label contrast for buttons sitewide.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_section('fin_economy_blocks_section', [
        'title'    => __('Homepage blocks', 'fin-economy'),
        'priority' => 32,
    ]);

    $wp_customize->add_setting('fin_economy_show_hero', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_show_hero', [
        'label'       => __('Show hero / highlights', 'fin-economy'),
        'description' => __('Enable or disable the hero/highlights block (Layout A & C).', 'fin-economy'),
        'section'     => 'fin_economy_blocks_section',
        'type'        => 'checkbox',
    ]);

    $wp_customize->add_setting('fin_economy_show_latest', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_show_latest', [
        'label'   => __('Show latest articles block', 'fin-economy'),
        'section' => 'fin_economy_blocks_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('fin_economy_show_categories', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_show_categories', [
        'label'   => __('Show category sections', 'fin-economy'),
        'section' => 'fin_economy_blocks_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('fin_economy_show_popular', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_show_popular', [
        'label'   => __('Show popular block', 'fin-economy'),
        'section' => 'fin_economy_blocks_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('fin_economy_block_order_hero', [
        'default'           => 10,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_block_order_hero', [
        'label'       => __('Hero priority', 'fin-economy'),
        'description' => __('Lower numbers render earlier.', 'fin-economy'),
        'section'     => 'fin_economy_blocks_section',
        'type'        => 'number',
    ]);

    $wp_customize->add_setting('fin_economy_block_order_latest', [
        'default'           => 20,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_block_order_latest', [
        'label'       => __('Latest block priority', 'fin-economy'),
        'description' => __('Lower numbers render earlier.', 'fin-economy'),
        'section'     => 'fin_economy_blocks_section',
        'type'        => 'number',
    ]);

    $wp_customize->add_setting('fin_economy_block_order_categories', [
        'default'           => 30,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_block_order_categories', [
        'label'       => __('Category block priority', 'fin-economy'),
        'description' => __('Lower numbers render earlier.', 'fin-economy'),
        'section'     => 'fin_economy_blocks_section',
        'type'        => 'number',
    ]);

    $wp_customize->add_setting('fin_economy_block_order_popular', [
        'default'           => 40,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('fin_economy_block_order_popular', [
        'label'       => __('Popular block priority', 'fin-economy'),
        'description' => __('Lower numbers render earlier.', 'fin-economy'),
        'section'     => 'fin_economy_blocks_section',
        'type'        => 'number',
    ]);

    $wp_customize->add_section('fin_economy_sidebar_section', [
        'title'    => __('Sidebar', 'fin-economy'),
        'priority' => 35,
    ]);

    $wp_customize->add_setting('fin_economy_show_sidebar', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_show_sidebar', [
        'label'       => __('Show sidebar on homepage', 'fin-economy'),
        'description' => __('Disable to use a full-width layout on thematic pages.', 'fin-economy'),
        'section'     => 'fin_economy_sidebar_section',
        'type'        => 'checkbox',
    ]);

    $wp_customize->add_setting('fin_economy_sidebar_show_categories', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_sidebar_show_categories', [
        'label'   => __('Show default categories block', 'fin-economy'),
        'section' => 'fin_economy_sidebar_section',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('fin_economy_sidebar_show_popular', [
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
    $wp_customize->add_control('fin_economy_sidebar_show_popular', [
        'label'   => __('Show default popular block', 'fin-economy'),
        'section' => 'fin_economy_sidebar_section',
        'type'    => 'checkbox',
    ]);

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
        'default'           => '',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_footer_bg_color', [
        'label'   => __('Footer background color', 'fin-economy'),
        'section' => 'fin_economy_footer_section',
    ]));

    $wp_customize->add_setting('fin_economy_footer_text_color', [
        'default'           => '',
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
