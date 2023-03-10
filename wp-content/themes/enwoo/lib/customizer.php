<?php
/**
 * Enwoo Theme Customizer
 *
 * @package Enwoo
 */

$enwoo_sections = array( 'info', 'demo' );

foreach( $enwoo_sections as $section ){
    require get_template_directory() . '/lib/customizer/' . $section . '.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
}

function enwoo_customizer_scripts() {
    wp_enqueue_style( 'enwoo-customize',get_template_directory_uri().'/lib/customizer/css/customize.css', '', 'screen' );
    wp_enqueue_script( 'enwoo-customize', get_template_directory_uri() . '/lib/customizer/js/customize.js', array( 'jquery' ), '20170404', true );
}
add_action( 'customize_controls_enqueue_scripts', 'enwoo_customizer_scripts' );
