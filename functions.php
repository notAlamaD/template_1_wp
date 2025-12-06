<?php
function global_bulletin_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'global-bulletin'),
    ]);
}
add_action('after_setup_theme', 'global_bulletin_setup');

function global_bulletin_assets() {
    wp_enqueue_style('global-bulletin-style', get_stylesheet_uri(), [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'global_bulletin_assets');
