<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="header-inner">
        <div class="brand">
            <?php if (function_exists('the_custom_logo') && has_custom_logo()) : ?>
                <div class="site-title logo-only"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
            <?php endif; ?>
            <p class="site-description"><?php bloginfo('description'); ?></p>
        </div>

        <?php if (has_nav_menu('primary')) : ?>
            <button class="nav-toggle" aria-expanded="false" aria-controls="primary-menu">
                <span class="sr-only"><?php esc_html_e('Toggle navigation', 'global-bulletin'); ?></span>
                <svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 8h16M4 12h10M4 16h16" stroke-linecap="round"></path>
                </svg>
            </button>

            <nav class="nav-primary" aria-label="Primary Menu">
                <?php wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'menu',
                    'depth'          => 2,
                ]); ?>
            </nav>
        <?php endif; ?>
    </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.nav-primary');
    const submenuLinks = nav ? nav.querySelectorAll('.menu-item-has-children > a') : [];
    const mobileBreakpoint = 960;
    if (!toggle || !nav) return;

    toggle.addEventListener('click', function () {
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!isOpen));
      nav.classList.toggle('nav-open', !isOpen);

      if (isOpen) {
        submenuLinks.forEach((link) => {
          link.parentElement.classList.remove('nav-sub-open');
          link.setAttribute('aria-expanded', 'false');
        });
      }
    });

    submenuLinks.forEach((link) => {
      link.setAttribute('aria-expanded', 'false');
      link.addEventListener('click', function (event) {
        if (window.innerWidth <= mobileBreakpoint) {
          event.preventDefault();
          const parent = link.parentElement;
          const isOpen = parent.classList.toggle('nav-sub-open');
          link.setAttribute('aria-expanded', String(isOpen));
        }
      });
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > mobileBreakpoint) {
        toggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('nav-open');
        submenuLinks.forEach((link) => {
          link.parentElement.classList.remove('nav-sub-open');
          link.setAttribute('aria-expanded', 'false');
        });
      }
    });
  });
</script>
