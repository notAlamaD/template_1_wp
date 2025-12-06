<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" style="color: inherit; text-decoration: none;">Tech Digest</a></h1>
    <p class="site-description">Быстрые апдейты про технологии, AI и стартапы</p>
    <?php if (has_nav_menu('primary')) : ?>
        <nav class="nav-primary" aria-label="Primary Menu">
            <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'depth' => 1]); ?>
        </nav>
    <?php endif; ?>
</header>
