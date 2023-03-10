<?php
if (!function_exists('enwoo_cart_link')) {

    function enwoo_cart_link() {
        ?>	
        <a class="cart-contents" href="#" data-tooltip="<?php esc_attr_e('Cart', 'enwoo'); ?>" title="<?php esc_attr_e('Cart', 'enwoo'); ?>">
            <i class="la la-shopping-bag"><span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span></i>
            <div class="amount-cart hidden-xs"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></div> 
        </a>
        <?php
    }

}

if (!function_exists('enwoo_header_cart')) {

    add_action('enwoo_header_woo', 'enwoo_header_cart', 30);
	add_action('enwoo_header_bus', 'enwoo_header_cart', 30);
	
    function enwoo_header_cart() {
        if (get_theme_mod('woo_header_cart', 1) == 1) {
            ?>
            <div class="header-cart">
                <div class="header-cart-block">
                    <div class="header-cart-inner">
                        <?php enwoo_cart_link(); ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }

}
if (!function_exists('enwoo_cart_content')) {

    add_action('wp_footer', 'enwoo_cart_content', 30);

    function enwoo_cart_content() {
        if (get_theme_mod('woo_header_cart', 1) == 1) {
            ?>
            <ul class="site-header-cart list-unstyled">
                <i class="la la-times-circle"></i>
                <li>
                    <?php the_widget('WC_Widget_Cart', 'title='); ?>
                </li>
            </ul>
            <?php
        }
    }

}
if (!function_exists('enwoo_header_add_to_cart_fragment')) {
    add_filter('woocommerce_add_to_cart_fragments', 'enwoo_header_add_to_cart_fragment');

    function enwoo_header_add_to_cart_fragment($fragments) {
        ob_start();

        enwoo_cart_link();

        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }

}

if (!function_exists('enwoo_my_account')) {

    add_action('enwoo_header_woo', 'enwoo_my_account', 40);
	add_action('enwoo_header_bus', 'enwoo_my_account', 40);

    function enwoo_my_account() {
        $login_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
        ?>
        <div class="header-my-account">
            <div class="header-login"> 
                <a href="<?php echo esc_url($login_link); ?>" data-tooltip="<?php esc_attr_e('My Account', 'enwoo'); ?>" title="<?php esc_attr_e('My Account', 'enwoo'); ?>">
                    <i class="la la-user"></i>
                </a>
            </div>
        </div>
        <?php
    }

}

if (!function_exists('enwoo_head_wishlist')) {

    add_action('enwoo_header_bus', 'enwoo_head_wishlist', 50);
	add_action('enwoo_header_woo', 'enwoo_head_wishlist', 50);

    function enwoo_head_wishlist() {
        if (function_exists('YITH_WCWL')) {
            $wishlist_url = YITH_WCWL()->get_wishlist_url();
            ?>
            <div class="header-wishlist">
                <a href="<?php echo esc_url($wishlist_url); ?>" data-tooltip="<?php esc_attr_e('Wishlist', 'enwoo'); ?>" title="<?php esc_attr_e('Wishlist', 'enwoo'); ?>">
                    <i class="lar la-heart"></i>
                </a>
            </div>
            <?php
        }
    }

}

if (!function_exists('enwoo_head_compare')) {

    add_action('enwoo_header_woo', 'enwoo_head_compare', 60);
	add_action('enwoo_header_bus', 'enwoo_head_compare', 60);
	
    function enwoo_head_compare() {
        if (function_exists('yith_woocompare_constructor')) {
            global $yith_woocompare;
            ?>
            <div class="header-compare product">
                <a class="compare added" rel="nofollow" href="<?php echo esc_url($yith_woocompare->obj->view_table_url()); ?>" data-tooltip="<?php esc_attr_e('Compare', 'enwoo'); ?>" title="<?php esc_attr_e('Compare', 'enwoo'); ?>">
                    <i class="la la-sync"></i>
                </a>
            </div>
            <?php
        }
    }

}

add_action('woocommerce_before_add_to_cart_quantity', 'enwoo_display_quantity_minus');

function enwoo_display_quantity_minus() {
    global $product;
    if (($product->get_stock_quantity() > 1 && !$product->managing_stock() ) || !$product->is_sold_individually()) {
        echo '<button type="button" class="minus" >-</button>';
    }
}

add_action('woocommerce_after_add_to_cart_quantity', 'enwoo_display_quantity_plus');

function enwoo_display_quantity_plus() {
    global $product;
    if (($product->get_stock_quantity() > 1 && !$product->managing_stock() ) || !$product->is_sold_individually()) {
        echo '<button type="button" class="plus" >+</button>';
    }
}

if (!function_exists('enwoo_categories_menu')) {

    /**
     * Categories menu. Displayed only if exists.
     */
    add_action('enwoo_header_bar', 'enwoo_categories_menu', 10);

    function enwoo_categories_menu() {
        if (has_nav_menu('main_menu_cats')) {
            ?>
            <ul class="envo-categories-menu nav navbar-nav navbar-left">
                <li class="menu-item menu-item-has-children dropdown">
                    <a class="envo-categories-menu-first" href="#">
                        <?php esc_html_e('Categories', 'enwoo'); ?>
                    </a>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'main_menu_cats',
                        'depth' => 5,
                        'container_id' => 'menu-right',
                        'container' => 'ul',
                        'container_class' => '',
                        'menu_class' => 'dropdown-menu',
                        'fallback_cb' => 'Enwoo_WP_Bootstrap_Navwalker::fallback',
                        'walker' => new Enwoo_WP_Bootstrap_Navwalker(),
                    ));
                    ?>
                </li>
            </ul>
            <?php
        } else {
            ?>
            <ul class="envo-categories-menu nav navbar-nav navbar-left">
                <li class="envo-categories-menu-item menu-item menu-item-has-children dropdown">
                    <a class="envo-categories-menu-first" href="#">
                        <?php esc_html_e('Categories', 'enwoo'); ?>
                    </a>
                    <ul id="menu-categories-menu" class="menu-categories-menu dropdown-menu">
                        <?php
                        $categories = get_categories('taxonomy=product_cat');
                        foreach ($categories as $category) {
                            $category_link = get_category_link($category->cat_ID);
                            $option = '<li class="menu-item ' . esc_attr($category->category_nicename) . '">';
                            $option .= '<a href="' . esc_url($category_link) . '" class="nav-link">';
                            $option .= esc_html($category->cat_name);
                            $option .= '</a>';
                            $option .= '</li>';
                            echo $option; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        }
                        ?>
                    </ul>
                </li>
            </ul>
            <?php
        }
    }

}

if (!function_exists('enwoo_head_search_bar')) {

    add_action('enwoo_header_woo', 'enwoo_head_search_bar', 20);

    function enwoo_head_search_bar() {
        ?>
        <div class="header-search-widget">
            <?php if (is_active_sidebar('enwoo-header-area')) { ?>
                <div class="site-heading-sidebar hidden-xs" >
                    <?php dynamic_sidebar('enwoo-header-area'); ?>
                </div>
            <?php } ?>
            <div class="head-form hidden-xs">
                <div class="header-search-form">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="hidden" name="post_type" value="product" />
                        <input class="header-search-input" name="s" type="text" placeholder="<?php esc_attr_e('Search products...', 'enwoo'); ?>"/>
                        <select class="header-search-select" name="product_cat">
                            <option value=""><?php esc_html_e('All Categories', 'enwoo'); ?></option> 
                            <?php
                            $categories = get_categories('taxonomy=product_cat');
                            foreach ($categories as $category) {
                                $option = '<option value="' . esc_attr($category->category_nicename) . '">';
                                $option .= esc_html($category->cat_name);
                                $option .= ' <span>(' . absint($category->category_count) . ')</span>';
                                $option .= '</option>';
                                echo $option; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                            ?>
                        </select>
                        <button class="header-search-button" type="submit"><i class="la la-search" aria-hidden="true"></i></button>
                    </form>
                </div>
            </div>    
        </div>
        <?php
    }

}
if (!function_exists('enwoo_search_button')) {
    
    add_action('enwoo_header_woo', 'enwoo_search_button', 70);

    /**
     * Search menu button
     */
    function enwoo_search_button() {
        ?>
        <div class="header-search visible-xs">
            <a class="search-button" rel="nofollow" href="#" data-tooltip="<?php esc_attr_e('Search', 'enwoo'); ?>" title="<?php esc_attr_e('Search', 'enwoo'); ?>">
                <i class="la la-search"></i>
            </a>
        </div>
        <?php
    }
}
if (!function_exists('enwoo_the_second_menu')) {

    add_action('enwoo_header_bar', 'enwoo_the_second_menu', 30);

    function enwoo_the_second_menu() {
        if (has_nav_menu('main_menu_right')) {
            wp_nav_menu(array(
                'theme_location' => 'main_menu_right',
                'depth' => 1,
                'container_id' => 'theme-menu-second',
                'container' => 'div',
                'container_class' => 'menu-container',
                'menu_class' => 'nav navbar-nav navbar-right',
                'fallback_cb' => 'Enwoo_WP_Bootstrap_Navwalker::fallback',
                'walker' => new Enwoo_WP_Bootstrap_Navwalker(),
            ));
        }
    }

}

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

add_action('woocommerce_before_main_content', 'enwoo_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'enwoo_wrapper_end', 10);

function enwoo_wrapper_start() {
    ?>
    <div class="row">
        <article class="col-md-<?php enwoo_main_content_width_columns(); ?>">
            <?php
}

function enwoo_wrapper_end() {
            ?>
        </article>       
        <?php get_sidebar('right'); ?>
    </div>
    <?php
}
