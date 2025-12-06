<?php get_header(); ?>
<main id="main-content" class="site-main">
    <div class="error-404">
        <h1><?php esc_html_e('404 — сторінку не знайдено', 'fin-economy'); ?></h1>
        <p><?php esc_html_e('Сторінка, яку ви шукаєте, не існує або була переміщена.', 'fin-economy'); ?></p>
        <a class="button" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('На головну', 'fin-economy'); ?></a>
    </div>
</main>
<?php get_footer(); ?>
