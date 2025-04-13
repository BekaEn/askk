<?php

if ( ! function_exists( 'askka_is_page_title_enabled' ) ) {
	/**
	 * Function that check is module enabled
	 */
	function askka_is_page_title_enabled() {
		return apply_filters( 'askka_filter_enable_page_title', true );
	}
}

if ( ! function_exists( 'askka_load_page_title' ) ) {
	/**
	 * Function which loads page template module
	 */
	function askka_load_page_title() {

		if ( askka_is_page_title_enabled() ) {
			// Include title template
			echo apply_filters( 'askka_filter_title_template', askka_get_template_part( 'title', 'templates/title' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	add_action( 'askka_action_page_title_template', 'askka_load_page_title' );
}

if ( ! function_exists( 'askka_get_page_title_classes' ) ) {
	/**
	 * Function that return classes for page title area
	 *
	 * @return string
	 */
	function askka_get_page_title_classes() {
		$classes = apply_filters( 'askka_filter_page_title_classes', array() );

		return implode( ' ', $classes );
	}
}

if ( ! function_exists( 'askka_get_page_title_text' ) ) {
	/**
	 * Function that returns current page title text
	 */
	function askka_get_page_title_text() {
		$title = get_the_title( askka_get_page_id() );

		if ( ( is_home() && is_front_page() ) || ( is_singular( 'post' ) && ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) ) ) {
			$title = get_option( 'blogname' );
		} elseif ( is_singular( 'post' ) && ! ( askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ) ) {
			$title = get_the_title( get_the_ID() );
		} elseif ( is_tag() ) {
			$title = single_term_title( '', false ) . esc_html__( ' Tag', 'askka' );
		} elseif ( is_date() ) {
			$title = get_the_time( 'F Y' );
		} elseif ( is_author() ) {
			$title = esc_html__( 'Author: ', 'askka' ) . get_the_author();
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_archive() ) {
			$title = esc_html__( 'Archive', 'askka' );
		} elseif ( is_search() ) {
			$title = esc_html__( 'Search results for: ', 'askka' ) . get_search_query();
		} elseif ( is_404() ) {
			$title = esc_html__( '404 - Page not found', 'askka' );
		}

		return apply_filters( 'askka_filter_page_title_text', $title );
	}
}
