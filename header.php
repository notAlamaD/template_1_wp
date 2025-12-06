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
            <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>">Global Bulletin</a>
            <p class="site-description"><?php bloginfo('description'); ?></p>
        </div>

        <?php if (has_nav_menu('primary')) : ?>
            <button class="nav-toggle" aria-expanded="false" aria-controls="primary-menu">
                <span class="sr-only"><?php esc_html_e('Toggle navigation', 'global-bulletin'); ?></span>
                <span class="nav-toggle-line"></span>
                <span class="nav-toggle-line"></span>
                <span class="nav-toggle-line"></span>
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
