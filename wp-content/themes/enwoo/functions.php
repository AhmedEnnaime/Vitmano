<?php
/**
 * The current version of the theme.
 */
$the_theme = wp_get_theme();
define('ENWOO_VERSION', $the_theme->get( 'Version' ));

add_action('after_setup_theme', 'enwoo_setup');

if (!function_exists('enwoo_setup')) :

    /**
     * Global functions
     */
    function enwoo_setup() {

        // Theme lang.
        load_theme_textdomain('enwoo', get_template_directory() . '/languages');

        // Add Title Tag Support.
        add_theme_support('title-tag');
        $menus = array('main_menu' => esc_html__('Main Menu', 'enwoo'));
        if (class_exists('WooCommerce') && get_theme_mod('header_layout', 'woonav') == 'woonav') {
            $woo_menus = array(
                'main_menu_right' => esc_html__('Menu Right', 'enwoo'),
                'main_menu_cats' => esc_html__('Categories Menu', 'enwoo'),
            );
        } else {
            $woo_menus = array(); // not displayed if Woo not installed
        }
        $all_menus = array_merge($menus, $woo_menus);

        // Register Menus.
        register_nav_menus($all_menus);

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(300, 300, true);
        add_image_size('enwoo-img', 1140, 540, true);

        // Add Custom Background Support.
        $args = array(
            'default-color' => 'ffffff',
        );
        add_theme_support('custom-background', $args);

        add_theme_support('custom-logo', array(
            'height' => 60,
            'width' => 200,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('site-title', 'site-description'),
        ));

        // Adds RSS feed links to for posts and comments.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         */
        add_theme_support('title-tag');

        // Set the default content width.
        $GLOBALS['content_width'] = 1140;

        add_theme_support('custom-header', apply_filters('enwoo_custom_header_args', array(
            'width' => 2000,
            'height' => 200,
            'default-text-color' => '',
            'wp-head-callback' => 'enwoo_header_style',
        )));

        // WooCommerce support.
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        add_theme_support('html5', array('search-form'));
		    add_theme_support('align-wide');
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('css/bootstrap.css', enwoo_fonts_url(), 'css/editor-style.css'));

    }

endif;

if (!function_exists('enwoo_header_style')) :

    /**
     * Styles the header image and text displayed on the blog.
     */
    function enwoo_header_style() {
        $header_image = get_header_image();
        $header_text_color = get_header_textcolor();
        if (get_theme_support('custom-header', 'default-text-color') !== $header_text_color || !empty($header_image)) {
            ?>
            <style type="text/css" id="enwoo-header-css">
            <?php
            // Has a Custom Header been added?
            if (!empty($header_image)) :
                ?>
                    .site-header {
                        background-image: url(<?php header_image(); ?>);
                        background-repeat: no-repeat;
                        background-position: 50% 50%;
                        -webkit-background-size: cover;
                        -moz-background-size:    cover;
                        -o-background-size:      cover;
                        background-size:         cover;
                    }
            <?php endif; ?>	
            <?php
            // Has the text been hidden?
            if ('blank' === $header_text_color) :
                ?>
                    .site-title,
                    .site-description {
                        position: absolute;
                        clip: rect(1px, 1px, 1px, 1px);
                    }
            <?php elseif ('' !== $header_text_color) : ?>
                    .site-title a, 
                    .site-title, 
                    .site-description {
                        color: #<?php echo esc_attr($header_text_color); ?>;
                    }
            <?php endif; ?>	
            </style>
            <?php
        }
    }

endif; // enwoo_header_style

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function enwoo_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}

add_action('wp_head', 'enwoo_pingback_header');

/**
 * Set Content Width
 */
function enwoo_content_width() {

    $content_width = $GLOBALS['content_width'];

    if (is_active_sidebar('enwoo-right-sidebar')) {
        $content_width = 847;
    } else {
        $content_width = 1140;
    }

    /**
     * Filter content width of the theme.
     */
    $GLOBALS['content_width'] = apply_filters('enwoo_content_width', $content_width);
}

add_action('template_redirect', 'enwoo_content_width', 0);

/**
 * Register custom fonts.
 */
function enwoo_fonts_url() {
    $fonts_url = '';

    /**
     * Translators: If there are characters in your language that are not
     * supported by Lato, translate this to 'off'. Do not translate
     * into your own language.
     */
    $font = get_theme_mod('main_typographydesktop', '');

    if ('' == $font) {
        $font_families = array();

        $font_families[] = 'Lato:300,400,700';

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return esc_url_raw($fonts_url);
}

/**
 * Add preconnect for Google Fonts.
 */
function enwoo_resource_hints($urls, $relation_type) {
    if (wp_style_is('enwoo-fonts', 'queue') && 'preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}

add_filter('wp_resource_hints', 'enwoo_resource_hints', 10, 2);

/**
 * Enqueue Styles (normal style.css and bootstrap.css)
 */
function enwoo_theme_stylesheets() {
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('enwoo-fonts', enwoo_fonts_url(), array(), null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.3.7');
    wp_enqueue_style('mmenu-light', get_template_directory_uri() . '/css/mmenu-light.min.css', array(), ENWOO_VERSION);
    // Theme stylesheet.
    wp_enqueue_style('enwoo-stylesheet', get_stylesheet_uri(), array('bootstrap'), ENWOO_VERSION);
    // WooCommerce stylesheet.
	if (class_exists('WooCommerce')) {
		wp_enqueue_style('enwoo-woo-stylesheet', get_template_directory_uri() . '/css/woocommerce.css', array('enwoo-stylesheet', 'woocommerce-general'), ENWOO_VERSION);
	}
    // Load Line Awesome css.
    wp_enqueue_style('line-awesome', get_template_directory_uri() . '/css/line-awesome.min.css', array(), '1.3.0');
}

add_action('wp_enqueue_scripts', 'enwoo_theme_stylesheets');

/**
 * Register jquery
 */
function enwoo_theme_js() {
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
    wp_enqueue_script('enwoo-theme-js', get_template_directory_uri() . '/js/customscript.js', array('jquery'), ENWOO_VERSION, true);
    wp_enqueue_script('mmenu', get_template_directory_uri() . '/js/mmenu-light.min.js', array('jquery'), ENWOO_VERSION, true);
}

add_action('wp_enqueue_scripts', 'enwoo_theme_js');

if (!function_exists('enwoo_is_pro_activated')) {

    /**
     * Query Enwoo activation
     */
    function enwoo_is_pro_activated() {
        return defined('ENWOO_PRO_CURRENT_VERSION') ? true : false;
    }

}

if ( !function_exists( 'envo_extra_is_activated' ) ) {

	/**
	 * Query Enwoo extra activation
	 */
	function envo_extra_is_activated() {
		return defined( 'ENVO_EXTRA_CURRENT_VERSION' ) ? true : false;
	}

}

if (!function_exists('enwoo_title_logo')) {
    
    add_action('enwoo_header_woo', 'enwoo_title_logo', 10);
	add_action('enwoo_header_bus', 'enwoo_title_logo', 10);
    /**
     * Title, logo code
     */
    function enwoo_title_logo() {
        ?>
        <div class="site-heading" >    
            <div class="site-branding-logo">
                <?php the_custom_logo(); ?>
            </div>
            <div class="site-branding-text">
                <?php if (is_front_page()) : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                <?php else : ?>
                    <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                <?php endif; ?>

                <?php
                $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()) :
                    ?>
                    <p class="site-description">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
            </div><!-- .site-branding-text -->
        </div>
		<div class="header-heading-shrink"></div>
            <?php if (is_active_sidebar('enwoo-header-area') && !class_exists('WooCommerce') && get_theme_mod( 'header_layout', (class_exists('WooCommerce') ? 'woonav' : 'busnav') ) != 'busnav' ) {  ?>
                <div class="site-heading-sidebar hidden-xs" >
                    <?php dynamic_sidebar('enwoo-header-area'); ?>
                </div>
            <?php } ?>
        <?php
    }

}

if (!function_exists('enwoo_menu')) {
    
    add_action('enwoo_header_bar', 'enwoo_menu', 20);

    /**
     * Title, logo code
     */
    function enwoo_menu() {
        ?>
        <div class="menu-heading">
            <nav id="site-navigation" class="navbar navbar-default">
                <?php
				if (is_front_page() && has_nav_menu('main_menu_home')) {
					$menu_loc = 'main_menu_home';
				} else {
					$menu_loc = 'main_menu';
				}
                wp_nav_menu(array(
                    'theme_location' => $menu_loc,
                    'depth' => 5,
                    'container_id' => 'theme-menu',
                    'container' => 'div',
                    'container_class' => 'menu-container',
                    'menu_class' => 'nav navbar-nav navbar-' . get_theme_mod('menu_position', 'left'),
                    'fallback_cb' => 'Enwoo_WP_Bootstrap_Navwalker::fallback',
                    'walker' => new Enwoo_WP_Bootstrap_Navwalker(),
                ));
                ?>
            </nav>
        </div>
        <?php
    }

}

if (!function_exists('enwoo_menu_business')) {

		add_action('enwoo_header_bus', 'enwoo_menu_business', 20);


    /**
     * Title, logo code
     */
    function enwoo_menu_business() {
        ?>
        <div class="menu-heading">
            <nav id="site-navigation" class="navbar navbar-default">
                <?php
                if (is_front_page() && has_nav_menu('main_menu_home')) {
					$menu_loc = 'main_menu_home';
				} else {
					$menu_loc = 'main_menu';
				}
                wp_nav_menu(array(
                    'theme_location' => $menu_loc,
                    'depth' => 5,
                    'container_id' => 'theme-menu',
                    'container' => 'div',
                    'container_class' => 'menu-container',
                    'menu_class' => 'nav navbar-nav navbar-' . get_theme_mod('menu_position', 'left'),
                    'fallback_cb' => 'Enwoo_WP_Bootstrap_Navwalker::fallback',
                    'walker' => new Enwoo_WP_Bootstrap_Navwalker(),
                ));
                ?>
            </nav>
        </div>
        <?php
    }

}

add_action('enwoo_header_bus', 'enwoo_head_start', 25);
add_action('enwoo_header_woo', 'enwoo_head_start', 25);
function enwoo_head_start() {
    echo '<div class="header-right" >';
}

add_action('enwoo_header_bus', 'enwoo_head_end', 80);
add_action('enwoo_header_woo', 'enwoo_head_end', 80);
function enwoo_head_end() {
    echo '</div>';
}
if (!function_exists('enwoo_menu_button')) {
    
    add_action('enwoo_header_woo', 'enwoo_menu_button', 28);
	add_action('enwoo_header_bus', 'enwoo_menu_button', 28);
    /**
     * Mobile menu button
     */
    function enwoo_menu_button() {
        ?>
        <div class="menu-button visible-xs" >
            <div class="navbar-header">
                <?php if (function_exists('max_mega_menu_is_enabled') && max_mega_menu_is_enabled('main_menu')) : // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
                    // do nothing 
                else : ?>
                    <a href="#" id="main-menu-panel" class="open-panel" data-panel="main-menu-panel">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Register Custom Navigation Walker include custom menu widget to use walkerclass
 */
require_once( trailingslashit(get_template_directory()) . 'lib/wp_bootstrap_navwalker.php' );

/**
 * Register Theme Info Page
 */
require_once( trailingslashit(get_template_directory()) . 'lib/enwoo-dashboard.php' );
if ( is_admin() ) {
	require_once( trailingslashit( get_template_directory() ) . 'lib/enwoo-plugin-install.php' );
}
/**
 * Customizer options
 */
require_once( trailingslashit(get_template_directory()) . 'lib/customizer.php' );
require_once( trailingslashit(get_template_directory()) . 'lib/customizer-recommend.php' );

if (class_exists('WooCommerce')) {

    /**
     * WooCommerce options
     */
    require_once( trailingslashit(get_template_directory()) . 'lib/woocommerce.php' );
}

add_action('widgets_init', 'enwoo_widgets_init');

/**
 * Register the Sidebar(s)
 */
function enwoo_widgets_init() {
    register_sidebar(
            array(
                'name' => esc_html__('Sidebar', 'enwoo'),
                'id' => 'enwoo-right-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
    register_sidebar(
            array(
                'name' => esc_html__('Top Bar Section', 'enwoo'),
                'id' => 'enwoo-top-bar-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s col-sm-4">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
	if (get_theme_mod( 'header_layout', (class_exists('WooCommerce') ? 'woonav' : 'busnav') ) != 'busnav') {
		register_sidebar(
				array(
					'name' => esc_html__('Header Section', 'enwoo'),
					'id' => 'enwoo-header-area',
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<div class="widget-title"><h3>',
					'after_title' => '</h3></div>',
				)
		);
	}
    register_sidebar(
            array(
                'name' => esc_html__('Footer Section', 'enwoo'),
                'id' => 'enwoo-footer-area',
                'before_widget' => '<div id="%1$s" class="widget %2$s col-md-3">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title"><h3>',
                'after_title' => '</h3></div>',
            )
    );
}

/**
 * Set the content width based on enabled sidebar
 */
function enwoo_main_content_width_columns() {

    $columns = '12';
	$hide_sidebar = get_post_meta( get_the_ID(), 'envo_extra_hide_sidebar', true );
	if (is_active_sidebar('enwoo-right-sidebar') && is_singular() && $hide_sidebar == 'on' ) {
		$columns = '12';
	} elseif (is_active_sidebar('enwoo-right-sidebar')) {
        $columns = $columns - 3;
    }

    echo absint($columns);
}

if (!function_exists('enwoo_entry_footer')) :

    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    add_action('enwoo_single_cats_tags', 'enwoo_entry_footer');

    function enwoo_entry_footer() {

        // Get Categories for posts.
        $categories_list = get_the_category_list(' ');

        // Get Tags for posts.
        $tags_list = get_the_tag_list('', ' ');

        // We don't want to output .entry-footer if it will be empty, so make sure its not.
        if ($categories_list || $tags_list ) {

            echo '<div class="entry-footer">';

            if ('post' === get_post_type()) {
                if ($categories_list || $tags_list) {

                    // Make sure there's more than one category before displaying.
                    if ($categories_list) {
                        echo '<div class="cat-links"><span class="space-right">' . esc_html__('Category', 'enwoo') . '</span>' . wp_kses_data($categories_list) . '</div>';
                    }

                    if ($tags_list) {
                        echo '<div class="tags-links"><span class="space-right">' . esc_html__('Tags', 'enwoo') . '</span>' . wp_kses_data($tags_list) . '</div>';
                    }
                }
            }

            echo '</div>';
        }
    }

endif;

if (!function_exists('enwoo_generate_construct_footer_widgets')) :
    /**
     * Build footer widgets
     */
    add_action('enwoo_generate_footer', 'enwoo_generate_construct_footer_widgets', 10);

    function enwoo_generate_construct_footer_widgets() {
        if (is_active_sidebar('enwoo-footer-area')) {
            ?>  				
            <div id="content-footer-section" class="container-fluid clearfix">
                <div class="container">
                    <?php dynamic_sidebar('enwoo-footer-area'); ?>
                </div>	
            </div>		
        <?php
        }
    }

endif;

if (!function_exists('enwoo_generate_construct_footer')) :
    /**
     * Build footer
     */
    add_action('enwoo_generate_footer', 'enwoo_generate_construct_footer', 20);

    function enwoo_generate_construct_footer() {
        ?>
        <footer id="colophon" class="footer-credits container-fluid">
            <div class="container">    
                <div class="footer-credits-text text-center list-unstyled">
                    <ul class="list-inline">
                        <?php wp_list_pages( array( 'title_li' => '' ) ); ?>
                    </ul>
                </div>
            </div>	
        </footer>
        <?php
    }

endif;

if (!function_exists('enwoo_featured_image')) :

    /**
     * Generate featured image.
     */
    add_action('enwoo_single_image', 'enwoo_featured_image', 10);
    add_action('enwoo_archive_image', 'enwoo_featured_image', 10);
    add_action('enwoo_page_content', 'enwoo_featured_image', 10);
    
    function enwoo_featured_image() {
        if ( is_singular( ) ) {
            enwoo_thumb_img('enwoo-img', '', false, true);
        } else {
            enwoo_thumb_img('enwoo-img');
        }
    }

endif;

if (!function_exists('enwoo_title')) :

    /**
     * Generate title.
     */
    add_action('enwoo_single_title', 'enwoo_title', 20);
    add_action('enwoo_archive_title', 'enwoo_title', 20);
    add_action('enwoo_page_content', 'enwoo_title', 20);

    function enwoo_title() {
		$title = get_post_meta( get_the_ID(), 'envo_extra_hide_title', true );
		if ( $title != 'on' ) {
        ?>
        <div class="single-head">
            <?php 
            if ( is_singular( ) ) {
                the_title('<h1 class="single-title">', '</h1>');
            } else {
                 the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            }
            ?> 
            <time class="posted-on published" datetime="<?php the_time('Y-m-d'); ?>"></time>
        </div>
        <?php
		}
    }

endif;

if (!function_exists('enwoo_meta_before')) :

    /**
     * Div for meta
     */
    add_action('enwoo_single_meta', 'enwoo_meta_before', 25);
    add_action('enwoo_archive_meta', 'enwoo_meta_before', 25);

    function enwoo_meta_before() {
        ?>
        <div class="article-meta">
        <?php
    }

endif;
if (!function_exists('enwoo_meta_after')) :

    /**
     * Div for meta
     */
    add_action('enwoo_single_meta', 'enwoo_meta_after', 55);
    add_action('enwoo_archive_meta', 'enwoo_meta_after', 55);

    function enwoo_meta_after() {
        ?>
        </div>
        <?php
    }

endif;

if (!function_exists('enwoo_date')) :

    /**
     * Returns date.
     */
    add_action('enwoo_single_meta', 'enwoo_date', 30);
    add_action('enwoo_archive_meta', 'enwoo_date', 30);

    function enwoo_date() {
        ?>
        <span class="posted-date">
            <?php echo esc_html(get_the_date()); ?>
        </span>
        <?php
    }

endif;

if (!function_exists('enwoo_author_meta')) :

    /**
     * Post author meta funciton
     */
    add_action('enwoo_single_meta', 'enwoo_author_meta', 40);
    add_action('enwoo_archive_meta', 'enwoo_author_meta', 40);

    function enwoo_author_meta() {
        ?>
        <span class="author-meta">
            <span class="author-meta-by"><?php esc_html_e('By', 'enwoo'); ?></span>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>">
                <?php the_author(); ?>
            </a>
        </span>
        <?php
    }

endif;

if (!function_exists('enwoo_comments')) :

    /**
     * Returns comments.
     */
    add_action('enwoo_single_meta', 'enwoo_comments', 50);
    add_action('enwoo_archive_meta', 'enwoo_comments', 50);

    function enwoo_comments() {
        ?>
        <span class="comments-meta">
            <?php
            if (!comments_open()) {
                esc_html_e('Off', 'enwoo');
            } else {
                ?>
                <a href="<?php the_permalink(); ?>#comments" rel="nofollow" title="<?php esc_attr_e('Comment on ', 'enwoo') . the_title_attribute(); ?>">
                <?php echo absint(get_comments_number()); ?>
                </a>
            <?php } ?>
            <i class="la la-comments-o"></i>
        </span>
        <?php
    }

endif;

if (!function_exists('enwoo_post_author')) :

    /**
     * Returns post author
     */
    add_action('enwoo_construct_post_author', 'enwoo_post_author');

    function enwoo_post_author() {
        ?>
        <div class="postauthor-container">			  
            <div class="postauthor-title">
                <h4 class="about">
                    <?php esc_html_e('About The Author', 'enwoo'); ?>
                </h4>
                <div class="">
                    <span class="fn">
                        <?php the_author_posts_link(); ?>
                    </span>
                </div> 				
            </div>        	
            <div class="postauthor-content">	             						           
                <p>
                    <?php the_author_meta('description') ?>
                </p>					
            </div>	 		
        </div>
        <?php
    }

endif;

if (!function_exists('enwoo_content')) :

    /**
     * Generate content.
     */
    add_action('enwoo_single_content', 'enwoo_content', 60);
    add_action('enwoo_page_content', 'enwoo_content', 60);

    function enwoo_content() {
        ?>
        <div class="single-content">
            <div class="single-entry-summary">
                <?php do_action('enwoo_before_content'); ?> 
                <?php the_content(); ?>
                <?php do_action('enwoo_after_content'); ?> 
            </div><!-- .single-entry-summary -->
            <?php wp_link_pages(); ?>
        </div>
        <?php if (get_edit_post_link()) {            
            edit_post_link();
        }
    }

endif;

if (!function_exists('enwoo_excerpt')) :

    /**
     * Generate content.
     */
    add_action('enwoo_archive_excerpt', 'enwoo_excerpt', 60);

    function enwoo_excerpt() {
        ?>
        <div class="post-excerpt">
            <?php the_excerpt(); ?>
        </div>
        <?php    
    }

endif;

if (!function_exists('enwoo_breadcrumbs')) :

    /**
     * Returns yoast breadcrumbs
     */
    add_action('enwoo_page_area', 'enwoo_breadcrumbs');

    function enwoo_breadcrumbs() {
        if (function_exists('yoast_breadcrumb') && (!is_home() && !is_front_page() )) {
            yoast_breadcrumb('<p id="breadcrumbs" class="text-left">', '</p>');
        }
    }

endif;

if (!function_exists('enwoo_top_bar')) :

    /**
     * Returns top bar
     */
    add_action('enwoo_construct_top_bar', 'enwoo_top_bar');

    function enwoo_top_bar() {
        if (is_active_sidebar('enwoo-top-bar-area')) { ?>
            <div class="top-bar-section container-fluid">
                <div class="<?php echo esc_attr(get_theme_mod('top_bar_content_width', 'container')); ?>">
                    <div class="row">
                        <?php dynamic_sidebar('enwoo-top-bar-area'); ?>
                    </div>
                </div>
            </div>
        <?php }
    }

endif;

if (!function_exists('enwoo_generate_construct_the_content')) :
    /**
     * Build footer widgets
     */
    add_action('enwoo_generate_the_content', 'enwoo_generate_construct_the_content');

    function enwoo_generate_construct_the_content() {
        if (have_posts()) :
            while (have_posts()) : the_post();
                get_template_part('content', get_post_format());
            endwhile;
            the_posts_pagination();
        else :
            get_template_part('content', 'none');
        endif;
    }

endif;

if (!function_exists('enwoo_prev_next_links')) :
    
    /**
    * Single previous next links
    */
    
    add_action('enwoo_single_nav', 'enwoo_prev_next_links', 70);

    function enwoo_prev_next_links() {
        the_post_navigation(
            array(
                'prev_text' => '<span class="screen-reader-text">' . __('Previous Post', 'enwoo') . '</span><span aria-hidden="true" class="nav-subtitle">' . __('Previous', 'enwoo') . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper"><i class="la la-angle-double-left" aria-hidden="true"></i></span>%title</span>',
                'next_text' => '<span class="screen-reader-text">' . __('Next Post', 'enwoo') . '</span><span aria-hidden="true" class="nav-subtitle">' . __('Next', 'enwoo') . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper"><i class="la la-angle-double-right" aria-hidden="true"></i></span></span>',
            )
        );
    }

endif;

if (!function_exists('enwoo_generate_construct_author_comments')) :
    /**
     * Build author and comments area
     */
    add_action('enwoo_single_after', 'enwoo_generate_construct_author_comments', 80);
    add_action('enwoo_page_content', 'enwoo_generate_construct_author_comments', 80);

    function enwoo_generate_construct_author_comments() {
        $authordesc = get_the_author_meta('description');
        if (!empty($authordesc)) {
            ?>
            <div class="single-footer row">
                <div class="col-md-4">
                    <?php do_action('enwoo_construct_post_author'); ?> 
                </div>
                <div class="col-md-8">
                    <?php comments_template(); ?> 
                </div>
            </div>
        <?php } else { ?>
            <div class="single-footer">
                <?php comments_template(); ?> 
            </div>
        <?php }
    }

endif;

if (!function_exists('enwoo_generate_sidebar')) :
    /**
     * Build author and comments area
     */
    add_action('enwoo_sidebar', 'enwoo_generate_sidebar');

    function enwoo_generate_sidebar() {
        $hide_sidebar = get_post_meta( get_the_ID(), 'envo_extra_hide_sidebar', true );
        if ($hide_sidebar != 'on') {
            get_sidebar('right');
		}
    }

endif;

if (!function_exists('enwoo_excerpt_length')) :

    /**
     * Excerpt limit.
     */
    function enwoo_excerpt_length($length) {
        $num = get_theme_mod('blog_posts_excerpt_number_words', 45);
        return absint($num);
    }

    add_filter('excerpt_length', 'enwoo_excerpt_length', 999);

endif;

if (!function_exists('enwoo_excerpt_more')) :

    /**
     * Excerpt more.
     */
    function enwoo_excerpt_more($more) {
        return '&hellip;';
    }

    add_filter('excerpt_more', 'enwoo_excerpt_more');

endif;

if (!function_exists('enwoo_thumb_img')) :

    /**
     * Returns featured image.
     */
    function enwoo_thumb_img($img = 'full', $col = '', $link = true, $single = false) {
        if (function_exists('enwoo_pro_thumb_img')) {
            enwoo_pro_thumb_img($img, $col, $link, $single);
        } elseif (( has_post_thumbnail() && $link == true)) {
            ?>
            <div class="news-thumb <?php echo esc_attr($col); ?>">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
            <?php the_post_thumbnail($img); ?>
                </a>
            </div><!-- .news-thumb -->
            <?php } elseif (has_post_thumbnail()) { ?>
            <div class="news-thumb <?php echo esc_attr($col); ?>">
            <?php the_post_thumbnail($img); ?>
            </div><!-- .news-thumb -->	
            <?php
        }
    }

endif;

if (!function_exists('wp_body_open')) :

    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     *
     */
    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         *
         */
        do_action('wp_body_open');
    }

endif;

/**
 * Skip to content link
 */
function enwoo_skip_link() {
    echo '<a class="skip-link screen-reader-text" href="#site-content">' . esc_html__('Skip to the content', 'enwoo') . '</a>';
}

add_action('wp_body_open', 'enwoo_skip_link', 5);

function enwoo_second_menu() {
    $class = '';
    if (class_exists('WooCommerce')) {
        $class .= 'search-on ';
    }
    if (has_nav_menu('main_menu_cats')) {
        $class .= 'menu-cats-on ';
    }
    if (has_nav_menu('main_menu_right')) {
        $class .= 'menu-right-on ';
    }
    echo esc_html($class);
}

/**
 * Check Elementor plugin
 */
function enwoo_check_for_elementor() {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	return is_plugin_active( 'elementor/elementor.php' );
}