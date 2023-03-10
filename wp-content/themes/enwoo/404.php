<?php get_header(); ?>
<?php
if ( get_theme_mod( 'enwoo_custom_404_on_off', '' ) == 'elementor' && get_theme_mod( 'enwoo_custom_404', '' ) != '' && enwoo_check_for_elementor() ) {
	$elementor_section_ID = get_theme_mod( 'enwoo_custom_404', '' );
	echo do_shortcode( '[elementor-template id="' . $elementor_section_ID . '"]' );
} else {
	?>
	<!-- start content container -->
	<div class="row">
		<div class="col-md-<?php enwoo_main_content_width_columns(); ?>">
			<div class="main-content-page">
				<div class="error-template text-center">
					<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'enwoo' ); ?></h1>
					<p class="error-details">
						<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'enwoo' ); ?>
					</p>
					<div class="error-actions">
						<?php get_search_form(); ?>    
					</div>
				</div>
			</div>
		</div>
		<?php get_sidebar( 'right' ); ?>
	</div>
	<!-- end content container -->
	<?php
}
get_footer();
