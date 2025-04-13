<?php

if ( ! function_exists( 'askka_is_installed' ) ) {
	/**
	 * Function that checks if forward plugin installed
	 *
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function askka_is_installed( $plugin ) {

		switch ( $plugin ) {
			case 'framework':
				return class_exists( 'QodeFramework' );
			case 'core':
				return class_exists( 'AskkaCore' );
			case 'woocommerce':
				return class_exists( 'WooCommerce' );
			case 'gutenberg-page':
				$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : array();

				return method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();
			case 'gutenberg-editor':
				return class_exists( 'WP_Block_Type' );
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'askka_include_theme_is_installed' ) ) {
	/**
	 * Function that set case is installed element for framework functionality
	 *
	 * @param bool   $installed
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function askka_include_theme_is_installed( $installed, $plugin ) {

		if ( 'theme' === $plugin ) {
			return class_exists( 'Askka_Handler' );
		}

		return $installed;
	}

	add_filter( 'qode_framework_filter_is_plugin_installed', 'askka_include_theme_is_installed', 10, 2 );
}

if ( ! function_exists( 'askka_template_part' ) ) {
	/**
	 * Function that echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params array of parameters to pass to template
	 */
	function askka_template_part( $module, $template, $slug = '', $params = array() ) {
		echo askka_get_template_part( $module, $template, $slug, $params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_get_template_part' ) ) {
	/**
	 * Function that load module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function askka_get_template_part( $module, $template, $slug = '', $params = array() ) {
		// HTML Content from template
		$html          = '';
		$template_path = ASKKA_INC_ROOT_DIR . '/' . $module;

		$temp = $template_path . '/' . $template;

		// The array of parameters to pass to the template
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params, EXTR_SKIP ); // @codingStandardsIgnoreLine
		}

		$template = '';

		if ( ! empty( $temp ) ) {
			if ( ! empty( $slug ) ) {
				$template = "{$temp}-{$slug}.php";

				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}

		if ( $template ) {
			ob_start();
			include $template; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			$html = ob_get_clean();
		}
		return $html;
	}
}

if ( ! function_exists( 'askka_get_page_id' ) ) {
	/**
	 * Function that returns current page id
	 * Additional conditional is to check if current page is any wp archive page (archive, category, tag, date etc.) and returns -1
	 *
	 * @return int
	 */
	function askka_get_page_id() {
		$page_id = get_queried_object_id();

		if ( askka_is_wp_template() ) {
			$page_id = - 1;
		}

		return apply_filters( 'askka_filter_page_id', $page_id );
	}
}

if ( ! function_exists( 'askka_is_wp_template' ) ) {
	/**
	 * Function that checks if current page default wp page
	 *
	 * @return bool
	 */
	function askka_is_wp_template() {
		return is_archive() || is_search() || is_404() || ( is_front_page() && is_home() );
	}
}

if ( ! function_exists( 'askka_get_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 *
	 * @param string       $status - success or error
	 * @param string       $message - ajax message value
	 * @param string|array $data - returned value
	 * @param string       $redirect - url address
	 */
	function askka_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array(
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => ! empty( $redirect ) ? esc_url( $redirect ) : '',
		);

		$output = json_encode( $response );

		exit( $output ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_get_button_element' ) ) {
	/**
	 * Function that returns button with provided params
	 *
	 * @param array $params - array of parameters
	 *
	 * @return string - string representing button html
	 */
	function askka_get_button_element( $params ) {
		if ( class_exists( 'AskkaCore_Button_Shortcode' ) ) {
			return AskkaCore_Button_Shortcode::call_shortcode( $params );
		} else {
			$link   = isset( $params['link'] ) ? $params['link'] : '#';
			$target = isset( $params['target'] ) ? $params['target'] : '_self';
			$text   = isset( $params['text'] ) ? $params['text'] : '';

			if ( isset( $params['button_layout'] ) && 'textual' === $params['button_layout'] ) {
				$button  = '<a itemprop="url" class="qodef-theme-button qodef-layout--textual" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">';
				$button .= '<span class="qodef-m-text">';
				$button .= esc_html( $text );
				$button .= '</span>';
				$button .= '</a>';
			} else {
				$button = '<a itemprop="url" class="qodef-theme-button" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $text ) . '</a>';
			}

			return $button;
		}
	}
}

if ( ! function_exists( 'askka_render_button_element' ) ) {
	/**
	 * Function that render button with provided params
	 *
	 * @param array $params - array of parameters
	 */
	function askka_render_button_element( $params ) {
		echo askka_get_button_element( $params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_class_attribute' ) ) {
	/**
	 * Function that render class attribute
	 *
	 * @param string|array $class
	 */
	function askka_class_attribute( $class ) {
		echo askka_get_class_attribute( $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_get_class_attribute' ) ) {
	/**
	 * Function that return class attribute
	 *
	 * @param string|array $class
	 *
	 * @return string|mixed
	 */
	function askka_get_class_attribute( $class ) {
		return askka_is_installed( 'framework' ) ? qode_framework_get_class_attribute( $class ) : '';
	}
}

if ( ! function_exists( 'askka_get_post_value_through_levels' ) ) {
	/**
	 * Function that returns meta value if exists
	 *
	 * @param string $name name of option
	 * @param int    $post_id id of
	 *
	 * @return string value of option
	 */
	function askka_get_post_value_through_levels( $name, $post_id = null ) {
		return askka_is_installed( 'framework' ) && askka_is_installed( 'core' ) ? askka_core_get_post_value_through_levels( $name, $post_id ) : '';
	}
}

if ( ! function_exists( 'askka_get_space_value' ) ) {
	/**
	 * Function that returns spacing value based on selected option
	 *
	 * @param string $text_value - textual value of spacing
	 *
	 * @return int
	 */
	function askka_get_space_value( $text_value ) {
		return askka_is_installed( 'core' ) ? askka_core_get_space_value( $text_value ) : 0;
	}
}

if ( ! function_exists( 'askka_wp_kses_html' ) ) {
	/**
	 * Function that does escaping of specific html.
	 * It uses wp_kses function with predefined attributes array.
	 *
	 * @param string $type - type of html element
	 * @param string $content - string to escape
	 *
	 * @return string escaped output
	 * @see wp_kses()
	 */
	function askka_wp_kses_html( $type, $content ) {
		return askka_is_installed( 'framework' ) ? qode_framework_wp_kses_html( $type, $content ) : $content;
	}
}

if ( ! function_exists( 'askka_render_svg_icon' ) ) {
	/**
	 * Function that print svg html icon
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 */
	function askka_render_svg_icon( $name, $class_name = '' ) {
		echo askka_get_svg_icon( $name, $class_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_get_svg_icon' ) ) {
	/**
	 * Returns svg html
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 *
	 * @return string - string containing svg html
	 */
	function askka_get_svg_icon( $name, $class_name = '' ) {
		$html  = '';
		$class = isset( $class_name ) && ! empty( $class_name ) ? 'class="' . esc_attr( $class_name ) . '"' : '';

		switch ( $name ) {
			case 'menu':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 30 30" enable-background="new 0 0 30 30" xml:space="preserve"><rect  x="0" y="6" width="30" height="1"/><rect x="0" y="14" width="30" height="1"/><rect x="0" y="22" width="30" height="1"/></svg>';
				break;
			case 'side-area':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px" height="9px" viewBox="0 0 20 9" enable-background="new 0 0 20 9" xml:space="preserve"><rect width="20" height="1"/><rect y="4" width="20" height="1"/><rect y="8" width="20" height="1"/></svg>';
				break;
			case 'search':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="25px" viewBox="0 0 30 25" enable-background="new 0 0 30 25" xml:space="preserve"><circle cx="15.224" cy="11.08" r="5.412"/><line x1="18.275" y1="15.55" x2="20.454" y2="18.72"/></svg>';
				break;
			case 'search-close':
				$html = '<svg ' . $class . '  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve"><rect x="0" y="8" transform="matrix(0.7071 0.7071 -0.7071 0.7071 8.5006 -3.5206)" width="17" height="1"/><rect x="0" y="7.999" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -3.5206 8.4999)" width="17" height="1.001"/></svg>';
				break;
			case 'wishlist':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="25px" viewBox="0 0 30 25" enable-background="new 0 0 30 25" xml:space="preserve"><g><path d="M18.812,6.65c1.184,0,2.155,0.347,2.913,1.038   c0.758,0.692,1.138,1.641,1.138,2.846c0,1.228-0.457,2.4-1.372,3.516c-0.893,1.094-2.01,2.076-3.35,2.947   c-0.535,0.357-1.037,0.652-1.506,0.887s-0.804,0.385-1.004,0.453l-0.269,0.1c-0.624-0.156-1.552-0.636-2.778-1.439   c-1.34-0.871-2.457-1.853-3.35-2.947c-0.915-1.116-1.372-2.288-1.372-3.516c0-1.206,0.38-2.154,1.138-2.846   c0.76-0.691,1.729-1.038,2.914-1.038c0.69,0,1.355,0.157,1.992,0.469c0.636,0.313,1.121,0.749,1.456,1.306   c0.335-0.557,0.82-0.993,1.457-1.306C17.455,6.807,18.12,6.65,18.812,6.65z"/></g></svg>';
				break;
			case 'star':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><g><path d="M 20.756,11.768L 15.856,1.84L 10.956,11.768L0,13.36L 7.928,21.088L 6.056,32L 15.856,26.848L 25.656,32L 23.784,21.088L 31.712,13.36 z"></path></g></svg>';
				break;
			case 'cart':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16.6px" height="17.304px" viewBox="5.874 3.82 16.6 17.304" enable-background="new 5.874 3.82 16.6 17.304" xml:space="preserve"><polygon stroke-miterlimit="10" points="7.791,8.559 20.789,8.559 21.789,20.559 6.791,20.559 "/><path stroke-miterlimit="10" d="M10.791,11.059V7.601c0,0,0.084-3.042,3.542-3.042 c3.457,0,3.457,3.042,3.457,3.042v3.458"/><rect x="9.291" y="11.059" width="3" height="2"/><rect x="16.289" y="11.059" width="3" height="2"/></svg>';
				break;
			case 'cart-remove':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve"><rect x="0" y="8" transform="matrix(0.7071 0.7071 -0.7071 0.7071 8.5006 -3.5206)" width="17" height="1"/><rect x="0" y="7.999" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -3.5206 8.4999)" width="17" height="1.001"/></svg>';
				break;
			case 'menu-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="20px" viewBox="0 0 10 20" enable-background="new 0 0 10 20" xml:space="preserve"><polyline points="3.608,3.324 7,10 3.608,16.676 "/></svg>';
				break;
			case 'slider-arrow-left':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="70px" height="100px" viewBox="0 0 70 100" enable-background="new 0 0 70 100" xml:space="preserve"><polyline stroke-miterlimit="10" points="39.245,17.69 24.245,53.126 39.245,88.562 "/></svg>';
				break;
			case 'slider-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="70px" height="100px" viewBox="0 0 70 100" enable-background="new 0 0 70 100" xml:space="preserve"><polyline stroke-miterlimit="10" points="29.245,17.69 44.245,53.126 29.245,88.562 "/></svg>';
				break;
			case 'navigation-arrow-left':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="9.989" height="35.014" viewBox="0 0 9.989 35.014"><g transform="matrix(-1, 0, 0, -1, 1044.253, -760.869)"><path d="M1034.7-761.1l8.992-17.279-8.992-17.278" fill="none" stroke-miterlimit="10" stroke-width="0.99"/></g></svg>';
				break;
			case 'navigation-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="9.989" height="35.014" viewBox="0 0 9.989 35.014"><g transform="translate(-1034.265 -760.869)"><path d="M1034.7,761.1l8.992,17.279-8.992,17.278" fill="none" stroke-miterlimit="10" stroke-width="0.99"/></g></svg>';
				break;
			case 'pagination-arrow-left':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="9.989" height="35.014" viewBox="0 0 9.989 35.014"><g transform="matrix(-1, 0, 0, -1, 1044.253, -760.869)"><path d="M1034.7-761.1l8.992-17.279-8.992-17.278" fill="none" stroke-miterlimit="10" stroke-width="0.99"/></g></svg>';
				break;
			case 'pagination-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="9.989" height="35.014" viewBox="0 0 9.989 35.014"><g transform="translate(-1034.265 -760.869)"><path d="M1034.7,761.1l8.992,17.279-8.992,17.278" fill="none" stroke-miterlimit="10" stroke-width="0.99"/></g></svg>';
				break;
			case 'close':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="26px" height="26px" viewBox="0 0 26 26" enable-background="new 0 0 26 26" xml:space="preserve"><line x1="4.344" y1="4.656" x2="22.022" y2="22.333"/><line x1="4.344" y1="22.333" x2="22.022" y2="4.656"/></svg>';
				break;
			case 'spinner':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>';
				break;
			case 'link':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="20px" viewBox="0 0 60 20" enable-background="new 0 0 60 20" xml:space="preserve"><g><path fill="#231F20" d="M52.089,2.904H35.563c-2.534,0-4.596,2.062-4.596,4.596v0.629h2.916V7.5c0-0.927,0.753-1.68,1.68-1.68 h16.526c0.927,0,1.68,0.753,1.68,1.68v6.119c0,0.926-0.753,1.68-1.68,1.68H35.563c-0.927,0-1.68-0.754-1.68-1.68V12.99h-2.916 v0.629c0,2.533,2.062,4.596,4.596,4.596h16.526c2.534,0,4.596-2.063,4.596-4.596V7.5C56.685,4.966,54.623,2.904,52.089,2.904z"/><path fill="#231F20" d="M25.283,12.99v0.629c0,0.926-0.754,1.68-1.68,1.68H7.077c-0.927,0-1.681-0.754-1.681-1.68V7.5 c0-0.927,0.754-1.68,1.681-1.68h16.526c0.926,0,1.68,0.753,1.68,1.68v0.629h2.916V7.5c0-2.534-2.062-4.596-4.596-4.596H7.077 C4.542,2.904,2.48,4.966,2.48,7.5v6.119c0,2.533,2.062,4.596,4.597,4.596h16.526c2.534,0,4.596-2.063,4.596-4.596V12.99H25.283z"/><path fill="#231F20" d="M37.967,12.018H21.198c-0.805,0-1.457-0.652-1.457-1.458c0-0.805,0.652-1.458,1.457-1.458h16.769 c0.806,0,1.458,0.653,1.458,1.458C39.425,11.365,38.772,12.018,37.967,12.018z"/></g></svg>';
				break;
			case 'quote':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="26px" viewBox="9.467 13.695 30 26" enable-background="new 9.467 13.695 30 26" xml:space="preserve"><g><path d="M21.056,16.707c1.266,1.7,1.899,4.117,1.899,7.25c0,2.934-0.648,5.635-1.949,8.1c-1.3,2.467-3.217,4.937-5.75,7.4   c-0.067,0.066-0.167,0.1-0.3,0.1c-0.2,0-0.367-0.1-0.5-0.299c-0.134-0.201-0.134-0.367,0-0.5c3.266-3.334,4.9-7.266,4.9-11.801   c0-1.866-0.334-3.233-1-4.1c-0.6,1-1.767,1.5-3.5,1.5c-1.467,0-2.634-0.45-3.5-1.35c-0.867-0.9-1.3-2.116-1.3-3.65   c0-1.666,0.516-2.95,1.55-3.85c1.033-0.9,2.45-1.35,4.25-1.35C18.056,14.157,19.789,15.007,21.056,16.707z M37.455,16.707   c1.266,1.7,1.898,4.117,1.898,7.25c0,2.934-0.648,5.635-1.947,8.1c-1.303,2.467-3.219,4.937-5.75,7.4   c-0.068,0.066-0.168,0.1-0.303,0.1c-0.197,0-0.365-0.1-0.5-0.299c-0.135-0.201-0.135-0.367,0-0.5c3.268-3.334,4.9-7.266,4.9-11.801   c0-1.866-0.334-3.233-1-4.1c-0.6,1-1.768,1.5-3.5,1.5c-1.467,0-2.635-0.45-3.5-1.35c-0.867-0.9-1.301-2.116-1.301-3.65   c0-1.666,0.516-2.95,1.551-3.85c1.033-0.9,2.449-1.35,4.25-1.35C34.455,14.157,36.188,15.007,37.455,16.707z"/></g></svg>';
				break;
			case 'play':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="202px" height="202px" viewBox="0 0 202 202" enable-background="new 0 0 202 202" xml:space="preserve"><g><path fill="#FFFFFF" d="M91.667,88.25l20,12.5l-20,12.5V88.25z"/></g><circle fill="none" stroke="#FFFFFF" stroke-miterlimit="10" cx="101" cy="100.75" r="100"/></svg>';
				break;
			case 'back-to-top':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="7.669px" viewBox="8.748 16.417 25 7.669" xml:space="preserve"><polyline fill="none" stroke-miterlimit="10" points="9.131,23.379 21.25,17.224 33.365,23.379 "/></svg>';
				break;
			case 'portfolio':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28"><path d="M4.5,0A4.5,4.5,0,1,1,0,4.5,4.5,4.5,0,0,1,4.5,0Z"/><path d="M4.5,0A4.5,4.5,0,1,1,0,4.5,4.5,4.5,0,0,1,4.5,0Z" transform="translate(19)"/><path d="M4.5,0A4.5,4.5,0,1,1,0,4.5,4.5,4.5,0,0,1,4.5,0Z" transform="translate(19 19)"/><path d="M4.5,0A4.5,4.5,0,1,1,0,4.5,4.5,4.5,0,0,1,4.5,0Z" transform="translate(0 19)"/></svg>';
				break;
			case 'share':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="15" height="16.563" viewBox="0 0 15 16.563"><path d="M10.859-3.437A2.218,2.218,0,0,1,12.5-4.062a2.338,2.338,0,0,1,1.719.7,2.338,2.338,0,0,1,.7,1.719,2.338,2.338,0,0,1-.7,1.719,2.338,2.338,0,0,1-1.719.7,2.338,2.338,0,0,1-1.719-.7,2.338,2.338,0,0,1-.7-1.719,2.3,2.3,0,0,1,.078-.547L4.219-5.625A2.5,2.5,0,0,1,2.5-4.961,2.4,2.4,0,0,1,.742-5.7,2.4,2.4,0,0,1,0-7.461,2.435,2.435,0,0,1,.723-9.219a2.339,2.339,0,0,1,1.738-.742A2.5,2.5,0,0,1,4.18-9.3l5.9-3.4A3.29,3.29,0,0,1,10-13.281a2.4,2.4,0,0,1,.742-1.758,2.4,2.4,0,0,1,1.758-.742,2.4,2.4,0,0,1,1.758.742A2.4,2.4,0,0,1,15-13.281a2.4,2.4,0,0,1-.742,1.758,2.4,2.4,0,0,1-1.758.742,2.5,2.5,0,0,1-1.719-.664l-5.9,3.4a3.29,3.29,0,0,1,.078.586,3.29,3.29,0,0,1-.078.586Z" transform="translate(0 15.781)"/></svg>';
				break;
			case 'instagram':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="16.563px" height="16.542px" viewBox="0 0 16.563 16.542" enable-background="new 0 0 16.563 16.542" xml:space="preserve"><g><path d="M4.855,4.897c-0.602,0.602-1.002,1.275-1.203,2.02H0V2.104c0-0.602,0.201-1.088,0.602-1.461   c0.459-0.4,0.959-0.602,1.504-0.602H14.48c0.545,0,1.018,0.201,1.418,0.602c0.4,0.401,0.602,0.889,0.602,1.461v4.813h-3.609   c-0.201-0.744-0.602-1.418-1.203-2.02C10.742,3.952,9.596,3.479,8.25,3.479C6.932,3.479,5.801,3.952,4.855,4.897z M11.688,11.686   c0.916-0.916,1.375-2.047,1.375-3.395H16.5v6.188c0,0.516-0.201,1.004-0.602,1.461c-0.373,0.402-0.846,0.602-1.418,0.602H2.105   c-0.572,0-1.061-0.199-1.461-0.602C0.215,15.51,0,15.024,0,14.479V8.292h3.48c0,1.348,0.459,2.479,1.375,3.395   c0.945,0.945,2.076,1.418,3.395,1.418C9.596,13.104,10.742,12.631,11.688,11.686z M8.25,11.729c-2.291,0-3.438-1.145-3.438-3.438   C4.813,6,5.959,4.854,8.25,4.854S11.688,6,11.688,8.292C11.688,10.584,10.541,11.729,8.25,11.729z M15.125,3.651V1.975   c0-0.143-0.057-0.271-0.172-0.387c-0.115-0.114-0.244-0.172-0.387-0.172h-1.633c-0.143,0-0.271,0.058-0.387,0.172   c-0.115,0.115-0.172,0.244-0.172,0.387v1.676c0,0.144,0.057,0.272,0.172,0.387c0.086,0.086,0.215,0.129,0.387,0.129h1.633   c0.172,0,0.301-0.043,0.387-0.129C15.068,3.923,15.125,3.794,15.125,3.651z"/></g></svg>';
				break;
		}

		return apply_filters( 'askka_filter_svg_icon', $html );
	}
}
