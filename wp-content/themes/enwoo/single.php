<?php get_header(); ?>

<!-- start content container -->
<div class="row single-post">      
    <article class="col-md-<?php enwoo_main_content_width_columns(); ?>">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>                         
                <div <?php post_class('single-post-content'); ?>>
                    <?php
                    do_action('enwoo_single_before');
                    $contents = get_theme_mod('single_layout', array('image', 'title', 'meta', 'content', 'cats_tags', 'nav'));

                    // Loop parts.
                    foreach ($contents as $content) {
                        do_action('enwoo_single_' . $content);
                    }
                    do_action('enwoo_single_after');
                    ?>
                </div>
            <?php endwhile; ?>        
        <?php else : ?>            
            <?php get_template_part('content', 'none'); ?>        
        <?php endif; ?>    
    </article> 
    <?php do_action('enwoo_sidebar'); ?>
</div>
<!-- end content container -->

<?php
get_footer();
