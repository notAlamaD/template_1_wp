<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_hex_to_rgba($color, $alpha = 1) {
    $color = sanitize_hex_color($color);
    if (!$color) {
        return 'rgba(0,0,0,' . floatval($alpha) . ')';
    }

    $color = ltrim($color, '#');
    if (strlen($color) === 3) {
        $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
    }

    $rgb = [
        hexdec(substr($color, 0, 2)),
        hexdec(substr($color, 2, 2)),
        hexdec(substr($color, 4, 2)),
    ];

    return sprintf('rgba(%1$d,%2$d,%3$d,%4$.2f)', $rgb[0], $rgb[1], $rgb[2], max(0, min(1, floatval($alpha))));
}

function fin_economy_get_localized_date($post_id = null, $format = '') {
    $post_id   = $post_id ?: get_the_ID();
    $format    = $format ?: get_option('date_format');
    $timestamp = get_post_time('U', false, $post_id);

    if (!$timestamp) {
        return '';
    }

    return wp_date($format, $timestamp);
}

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

function fin_economy_get_home_layout() {
    $layout = get_theme_mod('fin_economy_home_layout', 'a');

    if (!in_array($layout, ['a', 'b', 'c'], true)) {
        $layout = 'a';
    }

    return $layout;
}

function fin_economy_get_block_setting($key, $default = true) {
    return (bool) get_theme_mod($key, $default);
}

function fin_economy_get_block_priority($key, $default) {
    $value = absint(get_theme_mod($key, $default));
    return $value > 0 ? $value : $default;
}

function fin_economy_get_home_blocks($layout) {
    $blocks = [];

    $show_hero       = fin_economy_get_block_setting('fin_economy_show_hero', true);
    $show_latest     = fin_economy_get_block_setting('fin_economy_show_latest', true);
    $show_categories = fin_economy_get_block_setting('fin_economy_show_categories', true);
    $show_popular    = fin_economy_get_block_setting('fin_economy_show_popular', true);

    if ('a' === $layout || 'c' === $layout) {
        if ($show_hero) {
            $blocks[] = [
                'id'       => 'hero',
                'priority' => fin_economy_get_block_priority('fin_economy_block_order_hero', 10),
            ];
        }
    }

    if ($show_latest) {
        $blocks[] = [
            'id'       => 'latest',
            'priority' => fin_economy_get_block_priority('fin_economy_block_order_latest', 20),
        ];
    }

    if ($show_categories) {
        $blocks[] = [
            'id'       => 'categories',
            'priority' => fin_economy_get_block_priority('fin_economy_block_order_categories', 30),
        ];
    }

    if ($show_popular) {
        $blocks[] = [
            'id'       => 'popular',
            'priority' => fin_economy_get_block_priority('fin_economy_block_order_popular', 40),
        ];
    }

    usort($blocks, function ($a, $b) {
        return $a['priority'] <=> $b['priority'];
    });

    return $blocks;
}

function fin_economy_get_color_palette($scheme = 'classic') {
    $palettes = [
        'classic' => [
            'accent'      => '#2563eb',
            'header_bg'   => '#ffffff',
            'header_text' => '#0f172a',
            'footer_bg'   => '#0b1220',
            'footer_text' => '#e2e8f0',
            'hero_start'  => '#0f172a',
            'hero_end'    => '#1e3a8a',
            'hero_text'   => '#e2e8f0',
        ],
        'green'   => [
            'accent'      => '#16a34a',
            'header_bg'   => '#f8fdf0',
            'header_text' => '#0f172a',
            'footer_bg'   => '#0b2e13',
            'footer_text' => '#e8f5e9',
            'hero_start'  => '#0b2e13',
            'hero_end'    => '#15803d',
            'hero_text'   => '#e8f5e9',
        ],
        'dark'    => [
            'accent'      => '#7f1d1d',
            'header_bg'   => '#0f172a',
            'header_text' => '#e2e8f0',
            'footer_bg'   => '#0b0f1a',
            'footer_text' => '#e5e7eb',
            'hero_start'  => '#0f172a',
            'hero_end'    => '#1f2937',
            'hero_text'   => '#e5e7eb',
        ],
    ];

    return $palettes[$scheme] ?? $palettes['classic'];
}

function fin_economy_get_design_tokens() {
    $scheme  = get_theme_mod('fin_economy_color_scheme', 'classic');
    $palette = fin_economy_get_color_palette($scheme);

    $header_bg   = get_theme_mod('fin_economy_header_bg_color', '');
    $header_text = get_theme_mod('fin_economy_header_text_color', '');
    $accent      = get_theme_mod('fin_economy_accent_color', '');
    $button_bg   = get_theme_mod('fin_economy_button_bg_color', '');
    $button_text = get_theme_mod('fin_economy_button_text_color', '');
    $button_hover = get_theme_mod('fin_economy_button_hover_color', '');
    $footer_bg   = get_theme_mod('fin_economy_footer_bg_color', '');
    $footer_text = get_theme_mod('fin_economy_footer_text_color', '');
    $hero_start  = get_theme_mod('fin_economy_hero_bg_start', '');
    $hero_end    = get_theme_mod('fin_economy_hero_bg_end', '');
    $hero_text   = get_theme_mod('fin_economy_hero_text_color', '');

    return [
        'header_bg'   => $header_bg ?: $palette['header_bg'],
        'header_text' => $header_text ?: $palette['header_text'],
        'accent'      => $accent ?: $palette['accent'],
        'button_bg'   => $button_bg ?: ($accent ?: $palette['accent']),
        'button_text' => $button_text ?: '#ffffff',
        'button_hover' => $button_hover ?: fin_economy_hex_to_rgba($accent ?: $palette['accent'], 0.9),
        'footer_bg'   => $footer_bg ?: $palette['footer_bg'],
        'footer_text' => $footer_text ?: $palette['footer_text'],
        'hero_start'  => $hero_start ?: $palette['hero_start'],
        'hero_end'    => $hero_end ?: $palette['hero_end'],
        'hero_text'   => $hero_text ?: $palette['hero_text'],
    ];
}

function fin_economy_body_classes($classes) {
    $scheme      = get_theme_mod('fin_economy_color_scheme', 'classic');
    $typography  = get_theme_mod('fin_economy_typography', 'modern');
    $header_type = get_theme_mod('fin_economy_header_variant', 'compact');
    $footer_type = get_theme_mod('fin_economy_footer_variant', 'extended');

    $classes[] = 'scheme-' . sanitize_html_class($scheme);
    $classes[] = 'typography-' . sanitize_html_class($typography);
    $classes[] = 'header-' . sanitize_html_class($header_type);
    $classes[] = 'footer-' . sanitize_html_class($footer_type);

    if (is_front_page()) {
        $classes[] = 'layout-' . fin_economy_get_home_layout();
    }

    return $classes;
}
add_filter('body_class', 'fin_economy_body_classes');
