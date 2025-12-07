<?php get_header(); ?>
<main id="main-content" class="site-main">
    <?php
    $layout       = fin_economy_get_home_layout();
    $blocks       = fin_economy_get_home_blocks($layout);
    $show_sidebar = (bool) get_theme_mod('fin_economy_show_sidebar', true);

    set_query_var('fin_economy_home_blocks', $blocks);
    set_query_var('fin_economy_show_sidebar', $show_sidebar);

    get_template_part('template-parts/front/layout', $layout);
    ?>
</main>
<?php get_footer(); ?>
