<?php
if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_meta_enabled() {
    $has_seo_plugin = defined('WPSEO_VERSION') || class_exists('WPSEO_Frontend') || class_exists('RankMath');

    return !is_admin() && !$has_seo_plugin && apply_filters('fin_economy_enable_meta', true);
}

function fin_economy_meta_description() {
    if (is_singular()) {
        $excerpt = get_the_excerpt();
        if ($excerpt) {
            return wp_strip_all_tags($excerpt);
        }
    }

    if (is_category() || is_tag()) {
        $term = get_queried_object();
        if ($term && !empty($term->description)) {
            return wp_strip_all_tags($term->description);
        }
    }

    $blog_description = get_bloginfo('description');
    if (!empty($blog_description)) {
        return wp_strip_all_tags($blog_description);
    }

    return '';
}

function fin_economy_meta_image() {
    if (is_singular() && has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if (!empty($image[0])) {
            return esc_url_raw($image[0]);
        }
    }

    $logo_id = get_theme_mod('custom_logo');
    if ($logo_id) {
        $logo = wp_get_attachment_image_src($logo_id, 'full');
        if (!empty($logo[0])) {
            return esc_url_raw($logo[0]);
        }
    }

    return '';
}

function fin_economy_meta_url() {
    if (is_singular()) {
        return get_permalink();
    }

    if (is_search()) {
        return get_search_link();
    }

    if (is_category() || is_tag() || is_tax()) {
        $term_link = get_term_link(get_queried_object());
        if (!is_wp_error($term_link)) {
            return $term_link;
        }
    }

    if (is_home() || is_front_page()) {
        return home_url('/');
    }

    global $wp;
    return home_url(add_query_arg([], $wp->request));
}

function fin_economy_meta_tags() {
    if (!fin_economy_meta_enabled()) {
        return;
    }

    $title       = wp_get_document_title();
    $description = fin_economy_meta_description();
    $url         = fin_economy_meta_url();
    $image       = fin_economy_meta_image();
    $type        = is_singular() ? 'article' : 'website';

    if (!empty($description)) {
        printf('<meta name="description" content="%s" />' . "\n", esc_attr(wp_trim_words($description, 40, '…')));
    }

    printf('<meta property="og:title" content="%s" />' . "\n", esc_attr($title));
    printf('<meta property="og:description" content="%s" />' . "\n", esc_attr(wp_trim_words($description ? $description : $title, 40, '…')));
    printf('<meta property="og:url" content="%s" />' . "\n", esc_url($url));
    printf('<meta property="og:type" content="%s" />' . "\n", esc_attr($type));
    printf('<meta property="og:site_name" content="%s" />' . "\n", esc_attr(get_bloginfo('name')));

    if (!empty($image)) {
        printf('<meta property="og:image" content="%s" />' . "\n", esc_url($image));
    }

    printf('<meta name="twitter:card" content="%s" />' . "\n", $image ? 'summary_large_image' : 'summary');
    printf('<meta name="twitter:title" content="%s" />' . "\n", esc_attr($title));
    printf('<meta name="twitter:description" content="%s" />' . "\n", esc_attr(wp_trim_words($description ? $description : $title, 40, '…')));
    if (!empty($image)) {
        printf('<meta name="twitter:image" content="%s" />' . "\n", esc_url($image));
    }
}
add_action('wp_head', 'fin_economy_meta_tags', 5);
