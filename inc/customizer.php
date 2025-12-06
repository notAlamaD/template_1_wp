<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_customize_register($wp_customize) {
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
        'default'           => '#0f172a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_bg_start', [
        'label'       => __('Hero background start', 'fin-economy'),
        'description' => __('Left/top gradient color for the hero block.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_setting('fin_economy_hero_bg_end', [
        'default'           => '#1e3a8a',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_hero_bg_end', [
        'label'       => __('Hero background end', 'fin-economy'),
        'description' => __('Right/bottom gradient color for the hero block.', 'fin-economy'),
        'section'     => 'fin_economy_hero_section',
    ]));

    $wp_customize->add_setting('fin_economy_hero_text_color', [
        'default'           => '#e2e8f0',
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

    $wp_customize->add_setting('fin_economy_button_bg_color', [
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_bg_color', [
        'label'       => __('Button background color', 'fin-economy'),
        'description' => __('Applies to primary buttons and hero CTAs.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_hover_color', [
        'default'           => '#1d4ed8',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_hover_color', [
        'label'       => __('Button hover color', 'fin-economy'),
        'description' => __('Used for hover and focus states.', 'fin-economy'),
        'section'     => 'colors',
    ]));

    $wp_customize->add_setting('fin_economy_button_text_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'fin_economy_button_text_color', [
        'label'       => __('Button text color', 'fin-economy'),
        'description' => __('Adjust label contrast for buttons sitewide.', 'fin-economy'),
        'section'     => 'colors',
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
