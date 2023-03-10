<?php do_action( 'enwoo_construct_top_bar' ); ?>
<?php
if ( get_theme_mod( 'enwoo_custom_header_on_off', '' ) == 'elementor' && get_theme_mod( 'enwoo_custom_header', '' ) != '' && enwoo_check_for_elementor() ) {
	$elementor_section_ID = get_theme_mod( 'enwoo_custom_header', '' );
	echo do_shortcode( '[elementor-template id="' . $elementor_section_ID . '"]' );
} elseif ( get_theme_mod( 'header_layout', (class_exists( 'WooCommerce' ) ? 'woonav' : 'busnav' ) ) == 'woonav' ) {
	?>
	<div class="site-header container-fluid woo-heading">
		<div class="<?php echo esc_attr( get_theme_mod( 'header_content_width', 'container' ) ); ?>" >
			<div class="heading-row row" >
				<?php do_action( 'enwoo_header_woo' ); ?>
			</div>
		</div>
	</div>
	<?php do_action( 'enwoo_before_second_menu' ); ?>
	<div class="main-menu">
		<nav id="second-site-navigation" class="navbar navbar-default <?php enwoo_second_menu(); ?>">
			<div class="container">   
				<?php do_action( 'enwoo_header_bar' ); ?>
			</div>
		</nav> 
	</div>
	<?php
	do_action( 'enwoo_after_second_menu' );
} else {
	?>
	<div class="site-header container-fluid business-heading">
		<div class="<?php echo esc_attr( get_theme_mod( 'header_content_width', 'container' ) ); ?>" >
			<div class="heading-row row" >
				<?php do_action( 'enwoo_header_bus' ); ?>
			</div>
		</div>
	</div>
<?php 
}
