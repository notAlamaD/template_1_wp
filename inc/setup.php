<?php
if (!defined('ABSPATH')) {
    exit;
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
