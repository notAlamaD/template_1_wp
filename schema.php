<?php
/**
 * JSON-LD schema generation for Fin Economy theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

function fin_economy_schema_enabled() {
    $has_seo_plugin = defined('WPSEO_VERSION') || class_exists('WPSEO_Options') || class_exists('RankMath');

    return !is_admin() && !$has_seo_plugin && apply_filters('fin_economy_enable_schema', true);
}

function fin_economy_schema_logo_url() {
    $logo_id = get_theme_mod('custom_logo');
    if (!$logo_id) {
        return '';
    }

    $logo = wp_get_attachment_image_src($logo_id, 'full');
    return $logo ? $logo[0] : '';
}

function fin_economy_schema_output($data) {
    if (empty($data) || !fin_economy_schema_enabled()) {
        return;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

function fin_economy_schema_organization() {
    if (!fin_economy_schema_enabled()) {
        return;
    }

    $logo_url = fin_economy_schema_logo_url();

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => get_bloginfo('name'),
        'url'      => home_url('/'),
    ];

    if ($logo_url) {
        $schema['logo'] = [
            '@type' => 'ImageObject',
            'url'   => esc_url_raw($logo_url),
        ];
    }

    fin_economy_schema_output($schema);
}

function fin_economy_schema_website() {
    if (!fin_economy_schema_enabled()) {
        return;
    }

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'WebSite',
        'url'             => home_url('/'),
        'name'            => get_bloginfo('name'),
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => add_query_arg('s', '{search_term_string}', home_url('/')),
            'query-input' => 'required name=search_term_string',
        ],
    ];

    fin_economy_schema_output($schema);
}

function fin_economy_schema_breadcrumbs() {
    if (!fin_economy_schema_enabled()) {
        return;
    }

    if (!(is_single() || is_category() || is_archive())) {
        return;
    }

    $items = [];
    $items[] = [
        '@type'   => 'ListItem',
        'position'=> 1,
        'name'    => __('Головна', 'fin-economy'),
        'item'    => home_url('/'),
    ];

    $position = 2;

    if (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $primary = $categories[0];
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $primary->name,
                'item'     => get_category_link($primary),
            ];
        }

        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        ];
    } elseif (is_category()) {
        $category = get_queried_object();
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => $category->name,
            'item'     => get_category_link($category),
        ];
    } elseif (is_archive()) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => wp_strip_all_tags(get_the_archive_title()),
            'item'     => is_post_type_archive() ? get_post_type_archive_link(get_post_type()) : get_pagenum_link(1),
        ];
    }

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    ];

    fin_economy_schema_output($schema);
}

function fin_economy_schema_article() {
    if (!fin_economy_schema_enabled() || !is_single()) {
        return;
    }

    $post_id = get_the_ID();
    $image   = get_the_post_thumbnail_url($post_id, 'full');
    $excerpt = get_the_excerpt($post_id);
    $description = $excerpt ? wp_trim_words(wp_strip_all_tags($excerpt), 55) : '';
    $categories  = get_the_category($post_id);
    $section     = !empty($categories) ? $categories[0]->name : '';

    $publisher_logo = fin_economy_schema_logo_url();

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'NewsArticle',
        'headline'        => get_the_title($post_id),
        'description'     => $description,
        'datePublished'   => get_the_date(DATE_W3C, $post_id),
        'dateModified'    => get_the_modified_date(DATE_W3C, $post_id),
        'author'          => [
            '@type' => 'Person',
            'name'  => get_the_author_meta('display_name', get_post_field('post_author', $post_id)),
        ],
        'publisher'       => [
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
        ],
        'mainEntityOfPage'=> get_permalink($post_id),
    ];

    if ($section) {
        $schema['articleSection'] = $section;
    }

    if ($image) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url'   => esc_url_raw($image),
        ];
    }

    if ($publisher_logo) {
        $schema['publisher']['logo'] = [
            '@type' => 'ImageObject',
            'url'   => esc_url_raw($publisher_logo),
        ];
    }

    fin_economy_schema_output($schema);
}

function fin_economy_schema_itemlist() {
    if (!fin_economy_schema_enabled()) {
        return;
    }

    $posts = [];

    if (is_home() || is_front_page()) {
        $query = new WP_Query([
            'posts_per_page'      => 9,
            'ignore_sticky_posts' => true,
        ]);
        $posts = $query->posts;
    } elseif (is_category() || is_archive()) {
        global $wp_query;
        $posts = $wp_query->posts;
    }

    if (empty($posts)) {
        return;
    }

    $items = [];
    $position = 1;
    foreach ($posts as $post) {
        $url = get_permalink($post);
        if (!$url) {
            continue;
        }

        $items[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => get_the_title($post),
            'url'      => $url,
        ];
    }

    if (empty($items)) {
        return;
    }

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'itemListElement' => $items,
    ];

    fin_economy_schema_output($schema);
}

add_action('wp_head', 'fin_economy_schema_organization');
add_action('wp_head', 'fin_economy_schema_website');
add_action('wp_head', 'fin_economy_schema_breadcrumbs');
add_action('wp_head', 'fin_economy_schema_article');
add_action('wp_head', 'fin_economy_schema_itemlist');
