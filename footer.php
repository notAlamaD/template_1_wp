<?php $footer_variant = get_theme_mod('fin_economy_footer_variant', 'extended'); ?>
<footer class="site-footer footer-variant-<?php echo esc_attr($footer_variant); ?>">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'footer-logo-img']); ?>
                <?php else : ?>
                    <div class="footer-fallback-logo">
                        <span class="footer-logo-title"><?php bloginfo('name'); ?></span>
                        <span class="footer-logo-tagline"><?php bloginfo('description'); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <?php $footer_text = get_theme_mod('fin_economy_footer_text', __('© 2025 «Фінанси та економія».', 'fin-economy')); ?>
            <div class="footer-text"><?php echo wp_kses_post(wpautop($footer_text)); ?></div>
        </div>

        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-nav" aria-label="<?php esc_attr_e('Footer menu', 'fin-economy'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                ]);
                ?>
            </nav>
        <?php endif; ?>

        <?php
        $social_icons = [
            'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 10.25V8.5c0-.6.4-.75.65-.75h1.35V5h-2.25C11.15 5 10 6.45 10 8v2.25H8v3h2v6h3v-6h2.05l.45-3z"/></svg>',
            'twitter'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 6.5c-.7.3-1.4.5-2.2.6a3.6 3.6 0 0 0 1.6-2 7.1 7.1 0 0 1-2.3.9 3.6 3.6 0 0 0-6.2 3.3 10.2 10.2 0 0 1-7.4-3.7 3.6 3.6 0 0 0 1.1 4.8 3.5 3.5 0 0 1-1.6-.4v.1c0 1.7 1.2 3.2 2.9 3.6a3.6 3.6 0 0 1-1.6.1 3.6 3.6 0 0 0 3.4 2.5A7.2 7.2 0 0 1 3 17.5 10.1 10.1 0 0 0 8.5 19c6.8 0 10.5-5.7 10.5-10.6v-.5c.7-.5 1.4-1.1 2-1.8z"/></svg>',
            'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 4h8a4 4 0 0 1 4 4v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4zm0 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H8zm4 2.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5zm0 2A1.5 1.5 0 1 0 13.5 12 1.5 1.5 0 0 0 12 10.5zm4-3.75a.75.75 0 1 1-.75.75.75.75 0 0 1 .75-.75z"/></svg>',
            'linkedin'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 9H4v11h3zm.3-3.5a1.8 1.8 0 1 1-3.6 0 1.8 1.8 0 0 1 3.6 0zM20 20h-3v-5.6c0-1.5-.6-2.3-1.7-2.3a1.8 1.8 0 0 0-1.6 1 2 2 0 0 0-.2.9V20h-3s.1-9 0-11h3v1.6l-.1.2h.1a3 3 0 0 1 2.8-1.6c1.9 0 3.7 1.2 3.7 4z"/></svg>',
            'youtube'   => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.8-1.8C18 5 12 5 12 5s-6 0-7.8.4a2.5 2.5 0 0 0-1.8 1.8A26.2 26.2 0 0 0 2 12a26.2 26.2 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.8 1.8C6 19 12 19 12 19s6 0 7.8-.4a2.5 2.5 0 0 0 1.8-1.8 26.2 26.2 0 0 0 .4-4.8 26.2 26.2 0 0 0-.4-4.8zM10 15.5v-7l6 3.5z"/></svg>',
            'rss'       => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 18.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zM4.5 6.8v2.2A10.5 10.5 0 0 1 14.9 17h2.1A12.7 12.7 0 0 0 4.5 6.8zm0 4.2V13a6.6 6.6 0 0 1 6.6 6.6h2.1A8.7 8.7 0 0 0 4.5 11z"/></svg>',
        ];

        $social_links = [];
        foreach (['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'] as $network) {
            $url = trim(get_theme_mod('fin_economy_social_' . $network, ''));
            if (!empty($url)) {
                $social_links[$network] = $url;
            }
        }

        $rss_custom = trim(get_theme_mod('fin_economy_social_rss', ''));
        if (!empty($rss_custom)) {
            $social_links['rss'] = $rss_custom;
        }
        ?>

        <?php if (!empty($social_links)) : ?>
            <div class="footer-meta">
                <div class="footer-social" aria-label="<?php esc_attr_e('Social links', 'fin-economy'); ?>">
                    <?php foreach ($social_links as $network => $url) : ?>
                        <a class="footer-social-link" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                            <span class="screen-reader-text"><?php echo esc_html(ucfirst($network)); ?></span>
                            <?php echo $social_icons[$network]; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
