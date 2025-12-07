<?php get_header(); ?>
<main id="main-content" class="site-main">
    <div class="content-grid">
        <div class="content-area">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class('page-article'); ?>>
                    <header class="page-header">
                        <h1><?php the_title(); ?></h1>
                    </header>
                    <div class="page-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
