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
