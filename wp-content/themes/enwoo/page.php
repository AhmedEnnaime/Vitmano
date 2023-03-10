<?php get_header(); ?>

<!-- start page content container -->
<div class="row single-page">
    <article class="col-md-<?php enwoo_main_content_width_columns(); ?>">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>                          
                <div <?php post_class(); ?>>
                    <?php do_action('enwoo_page_content'); ?>
                </div>
            <?php endwhile; ?>        
        <?php else : ?>            
            <?php get_template_part('content', 'none'); ?>        
        <?php endif; ?>    
    </article>       
    <?php do_action('enwoo_sidebar'); ?>
</div>
<!-- end page content container -->

<?php 
get_footer();
