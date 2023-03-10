<article class="content-article">                   
    <div <?php post_class('news-item archive-item'); ?>>
        <?php
        do_action('enwoo_archive_before_posts');
        $contents = get_theme_mod('blog_layout', array('image', 'title', 'meta', 'excerpt'));

        // Loop parts.
        foreach ($contents as $content) {
            do_action('enwoo_archive_' . $content);
        }
        do_action('enwoo_archive_after_posts');
        ?>
    </div>
</article>
