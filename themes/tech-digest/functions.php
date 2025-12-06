<?php
function tech_digest_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'tech-digest'),
    ]);
}
add_action('after_setup_theme', 'tech_digest_setup');

function tech_digest_assets() {
    wp_enqueue_style('tech-digest-style', get_stylesheet_uri(), [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'tech_digest_assets');
