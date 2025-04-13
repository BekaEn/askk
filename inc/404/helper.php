<?php

if ( ! function_exists( 'askka_set_404_page_inner_classes' ) ) {
	/**
	 * Function that return classes for the page inner div from header.php
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function askka_set_404_page_inner_classes( $classes ) {

		if ( is_404() ) {
			$classes = 'qodef-content-full-width';
		}

		return $classes;
	}

	add_filter( 'askka_filter_page_inner_classes', 'askka_set_404_page_inner_classes' );
}

if ( ! function_exists( 'askka_get_404_page_parameters' ) ) {
	/**
	 * Function that set 404 page area content parameters
	 */
	function askka_get_404_page_parameters() {

		$params = array(
			'tagline'         => esc_html__( 'MAYBE IT\'S MELTED', 'askka' ),
			'title'           => esc_html__( '404', 'askka' ),
			'text'            => esc_html__( 'The page you are looking for doesn\'t exist. It may have been moved or removed altogether. Please try searching for some other page, or return to the website\'s homepage to find what you\'re looking for.', 'askka' ),
			'button_text'     => esc_html__( 'Homepage', 'askka' ),
			'background_text' => esc_html__( 'Error Page', 'askka' ),
		);

		return apply_filters( 'askka_filter_404_page_template_params', $params );
	}
}
