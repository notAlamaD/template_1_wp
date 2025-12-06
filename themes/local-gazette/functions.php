<?php
function local_gazette_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'local-gazette'),
    ]);
}
add_action('after_setup_theme', 'local_gazette_setup');

function local_gazette_assets() {
    wp_enqueue_style('local-gazette-style', get_stylesheet_uri(), [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'local_gazette_assets');
