<?php

/**
 * Enwoo Demo Content Information
 *
 * @package lawyer_landing_page
 */
function enwoo_customizer_demo_content($wp_customize) {
    if (!function_exists('is_plugin_active')) {
        require_once( ABSPATH . '/wp-admin/includes/plugin.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
    }
    $wp_customize->add_section(
            'theme_demo_content',
            array(
                'title' => __('One Click Demo Import', 'enwoo'),
                'priority' => 7,
            )
    );

    $wp_customize->add_setting(
            'demo_content_instruction',
            array(
                'sanitize_callback' => 'wp_kses_post'
            )
    );
    /* translators: %s: "documentation" string */
    $demo_content_description = sprintf(__('You can import the demo content with just one click. For step-by-step video tutorial, see %1$s', 'enwoo'), '<a class="documentation" href="' . esc_url('https://enwoo-wp.com/docs/enwoo-documentation/one-click-demo-import/') . '" target="_blank">' . esc_html__('documentation', 'enwoo') . '</a>');

    $wp_customize->add_control(
            new enwoo_Info_Text(
                    $wp_customize,
                    'demo_content_instruction',
                    array(
                'section' => 'theme_demo_content',
                'description' => $demo_content_description
                    )
            )
    );

    $theme_demo_content_desc = '';
    if (is_plugin_active('envo-extra/envo-extra.php')) {
        $theme_demo_content_desc .= '<p><a class="button button-primary" href="' . esc_url(admin_url('themes.php?page=envothemes-panel-install-demos')) . '" title="">' . esc_html__('Import demo data', 'enwoo') . '</a></p>';
    } else {
        $theme_demo_content_desc .= '<p><a class="button button-primary" href="' . esc_url(admin_url('themes.php?page=enwoo')) . '" title="">' . esc_html__('Import demo data', 'enwoo') . '</a></p>';
    }
    $wp_customize->add_setting(
            'theme_demo_content_info',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post',
            )
    );

    // Demo content 
    $wp_customize->add_control(
            new enwoo_Info_Text(
				$wp_customize,
				'theme_demo_content_info',
                    array(
					'section' => 'theme_demo_content',
					'description' => $theme_demo_content_desc
                )
            )
    );
}

add_action('customize_register', 'enwoo_customizer_demo_content');
