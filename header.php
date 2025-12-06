<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link sr-only" href="#main-content"><?php esc_html_e('Skip to content', 'fin-economy'); ?></a>
<header class="site-header">
    <div class="header-inner">
        <div class="brand">
            <?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
                <div class="site-title logo-only"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                <p class="site-description"><?php bloginfo('description'); ?></p>
            <?php endif; ?>
        </div>

        <form role="search" method="get" class="header-search" action="<?php echo esc_url(home_url('/')); ?>">
            <label class="sr-only" for="header-search-field"><?php esc_html_e('Пошук', 'fin-economy'); ?></label>
            <input id="header-search-field" type="search" name="s" placeholder="<?php esc_attr_e('Пошук...', 'fin-economy'); ?>" />
            <button type="submit" class="search-button"><?php esc_html_e('Пошук', 'fin-economy'); ?></button>
        </form>

        <?php if (has_nav_menu('primary')) : ?>
            <button class="nav-toggle" aria-expanded="false" aria-controls="primary-menu">
                <span class="sr-only"><?php esc_html_e('Toggle navigation', 'fin-economy'); ?></span>
                <svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"></path>
                </svg>
            </button>

            <nav class="nav-primary" aria-label="<?php esc_attr_e('Primary menu', 'fin-economy'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'menu',
                    'depth'          => 2,
                ]);
                ?>
            </nav>
        <?php endif; ?>
    </div>
</header>
