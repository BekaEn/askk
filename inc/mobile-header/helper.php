<?php

if ( ! function_exists( 'askka_load_page_mobile_header' ) ) {
	/**
	 * Function which loads page template module
	 */
	function askka_load_page_mobile_header() {
		// Include mobile header template
		echo apply_filters( 'askka_filter_mobile_header_template', askka_get_template_part( 'mobile-header', 'templates/mobile-header' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	add_action( 'askka_action_page_header_template', 'askka_load_page_mobile_header' );
}

if ( ! function_exists( 'askka_register_mobile_navigation_menus' ) ) {
	/**
	 * Function which registers navigation menus
	 */
	function askka_register_mobile_navigation_menus() {
		$navigation_menus = apply_filters( 'askka_filter_register_mobile_navigation_menus', array( 'mobile-navigation' => esc_html__( 'Mobile Navigation', 'askka' ) ) );

		if ( ! empty( $navigation_menus ) ) {
			register_nav_menus( $navigation_menus );
		}
	}

	add_action( 'askka_action_after_include_modules', 'askka_register_mobile_navigation_menus' );
}
