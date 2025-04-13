<?php

if ( ! class_exists( 'Askka_Handler' ) ) {
	/**
	 * Main theme class with configuration
	 */
	class Askka_Handler {
		private static $instance;

		public function __construct() {

			// Include required files
			require_once get_template_directory() . '/constants.php';
			require_once ASKKA_ROOT_DIR . '/helpers/helper.php';

			// Include theme's style and inline style
			add_action( 'wp_enqueue_scripts', array( $this, 'include_css_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_style' ) );

			// Include theme's script and localize theme's main js script
			add_action( 'wp_enqueue_scripts', array( $this, 'include_js_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'localize_js_scripts' ) );

			// Include theme's 3rd party plugins styles
			add_action( 'askka_action_before_main_css', array( $this, 'include_plugins_styles' ) );

			// Include theme's 3rd party plugins scripts
			add_action( 'askka_action_before_main_js', array( $this, 'include_plugins_scripts' ) );

			// Add pingback header
			add_action( 'wp_head', array( $this, 'add_pingback_header' ), 1 );

			// Include theme's skip link
			add_action( 'askka_action_after_body_tag_open', array( $this, 'add_skip_link' ), 5 );

			// Include theme's Google fonts
			add_action( 'askka_action_before_main_css', array( $this, 'include_google_fonts' ) );

			// Add theme's supports feature
			add_action( 'after_setup_theme', array( $this, 'set_theme_support' ) );

			// Enqueue supplemental block editor styles
			add_action( 'enqueue_block_editor_assets', array( $this, 'editor_customizer_styles' ) );

			// Add theme's body classes
			add_filter( 'body_class', array( $this, 'add_body_classes' ) );

			// Include modules
			add_action( 'after_setup_theme', array( $this, 'include_modules' ) );
		}

		/**
		 * @return Askka_Handler
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function include_css_scripts() {
			// CSS dependency variable
			$main_css_dependency = apply_filters( 'askka_filter_main_css_dependency', array( 'swiper' ) );

			// Hook to include additional scripts before theme's main style
			do_action( 'askka_action_before_main_css' );

			// Enqueue theme's main style
			wp_enqueue_style( 'askka-main', ASKKA_ASSETS_CSS_ROOT . '/main.min.css', $main_css_dependency );

			// Enqueue theme's style
			wp_enqueue_style( 'askka-style', ASKKA_ROOT . '/style.css' );

			// Hook to include additional scripts after theme's main style
			do_action( 'askka_action_after_main_css' );
		}

		function add_inline_style() {
			$style = apply_filters( 'askka_filter_add_inline_style', $style = '' );

			if ( ! empty( $style ) ) {
				wp_add_inline_style( 'askka-style', $style );
			}
		}

		function include_js_scripts() {
			// JS dependency variable
			$main_js_dependency = apply_filters( 'askka_filter_main_js_dependency', array( 'jquery' ) );

			// Hook to include additional scripts before theme's main script
			do_action( 'askka_action_before_main_js', $main_js_dependency );

			// Enqueue theme's main script
			wp_enqueue_script( 'askka-main-js', ASKKA_ASSETS_JS_ROOT . '/main.min.js', $main_js_dependency, false, true );

			// Hook to include additional scripts after theme's main script
			do_action( 'askka_action_after_main_js' );
			
			// Add menu fix script with a unique version for cache busting
			if (!wp_script_is('menu-fix-direct', 'enqueued')) {
				wp_enqueue_script( 'menu-fix-direct', get_template_directory_uri() . '/js/menu-fix-direct.js', array('jquery'), '1.0.1', true );
			}

			// Include comment reply script
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}

		function localize_js_scripts() {
			$global = apply_filters(
				'askka_filter_localize_main_js',
				array(
					'adminBarHeight' => is_admin_bar_showing() ? 32 : 0,
					'iconArrowLeft'  => askka_get_svg_icon( 'slider-arrow-left' ),
					'iconArrowRight' => askka_get_svg_icon( 'slider-arrow-right' ),
					'iconClose'      => askka_get_svg_icon( 'close' ),
				)
			);

			wp_localize_script(
				'askka-main-js',
				'qodefGlobal',
				array(
					'vars' => $global,
				)
			);
		}

		function include_plugins_styles() {

			// Enqueue 3rd party plugins style
			wp_enqueue_style( 'swiper', ASKKA_ASSETS_ROOT . '/plugins/swiper/swiper.min.css' );
		}

		function include_plugins_scripts() {

			// JS dependency variables
			$js_3rd_party_dependency = apply_filters( 'askka_filter_js_3rd_party_dependency', 'jquery' );

			// Enqueue 3rd party plugins script
			wp_enqueue_script( 'jquery-waitforimages', ASKKA_ASSETS_ROOT . '/plugins/waitforimages/jquery.waitforimages.js', array( $js_3rd_party_dependency ), false, true );
			wp_enqueue_script( 'swiper', ASKKA_ASSETS_ROOT . '/plugins/swiper/swiper.min.js', array( $js_3rd_party_dependency ), false, true );
		}

		function add_pingback_header() {
			if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
				<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
				<?php
			}
		}

		function add_skip_link() {
			echo '<a class="skip-link screen-reader-text" href="#qodef-page-content">' . esc_html__( 'Skip to the content', 'askka' ) . '</a>';
		}

		function include_google_fonts() {
			$is_enabled = boolval( apply_filters( 'askka_filter_enable_google_fonts', true ) );

			if ( $is_enabled ) {
				$font_subset_array = array(
					'latin-ext',
				);

				$font_weight_array = array(
					'300',
					'400',
					'500',
					'600',
					'700',
				);

				$default_font_family = array(
					'Work Sans',
				);

				$font_weight_str = implode( ',', array_unique( apply_filters( 'askka_filter_google_fonts_weight_list', $font_weight_array ) ) );
				$font_subset_str = implode( ',', array_unique( apply_filters( 'askka_filter_google_fonts_subset_list', $font_subset_array ) ) );
				$fonts_array     = apply_filters( 'askka_filter_google_fonts_list', $default_font_family );

				if ( ! empty( $fonts_array ) ) {
					$modified_default_font_family = array();

					foreach ( $fonts_array as $font ) {
						$modified_default_font_family[] = $font . ':' . $font_weight_str;
					}

					$default_font_string = implode( '|', $modified_default_font_family );

					$fonts_full_list_args = array(
						'family'  => urlencode( $default_font_string ),
						'subset'  => urlencode( $font_subset_str ),
						'display' => 'swap',
					);

					$google_fonts_url = add_query_arg( $fonts_full_list_args, 'https://fonts.googleapis.com/css' );
					wp_enqueue_style( 'askka-google-fonts', esc_url_raw( $google_fonts_url ), array(), '1.0.0' );
				}
			}
		}

		function set_theme_support() {

			// Make theme available for translation
			load_theme_textdomain( 'askka', ASKKA_ROOT_DIR . '/languages' );

			// Add support for feed links
			add_theme_support( 'automatic-feed-links' );

			// Add support for title tag
			add_theme_support( 'title-tag' );

			// Add support for post thumbnails
			add_theme_support( 'post-thumbnails' );

			// Add theme support for Custom Logo
			add_theme_support( 'custom-logo' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			// Set the default content width
			global $content_width;
			if ( ! isset( $content_width ) ) {
				$content_width = apply_filters( 'askka_filter_set_content_width', 1300 );
			}

			// Add support for post formats
			add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio', 'link', 'quote' ) );

			// Add theme support for editor style
			add_editor_style( ASKKA_ASSETS_CSS_ROOT . '/editor-style.min.css' );
		}

		function editor_customizer_styles() {

			// Include theme's Google fonts for Gutenberg editor
			$this->include_google_fonts();

			// Add editor customizer style
			wp_enqueue_style( 'askka-editor-customizer-styles', ASKKA_ASSETS_CSS_ROOT . '/editor-customizer-style.css' );

			// Add Gutenberg blocks style
			wp_enqueue_style( 'askka-gutenberg-blocks-style', ASKKA_INC_ROOT . '/gutenberg/assets/admin/css/gutenberg-blocks.css' );
		}

		function add_body_classes( $classes ) {
			$current_theme = wp_get_theme();
			$theme_name    = esc_attr( str_replace( ' ', '-', strtolower( $current_theme->get( 'Name' ) ) ) );
			$theme_version = esc_attr( $current_theme->get( 'Version' ) );

			// Check is child theme activated
			if ( $current_theme->parent() ) {

				// Add child theme version
				$child_theme_suffix = strpos( $theme_name, 'child' ) === false ? '-child' : '';

				$classes[] = $theme_name . $child_theme_suffix . '-' . $theme_version;

				// Get main theme variables
				$current_theme = $current_theme->parent();
				$theme_name    = esc_attr( str_replace( ' ', '-', strtolower( $current_theme->get( 'Name' ) ) ) );
				$theme_version = esc_attr( $current_theme->get( 'Version' ) );
			}

			if ( $current_theme->exists() ) {
				$classes[] = $theme_name . '-' . $theme_version;
			}

			// Set default grid size value
			$classes['grid_size'] = 'qodef-content-grid-1100';

			return apply_filters( 'askka_filter_add_body_classes', $classes );
		}

		function include_modules() {

			// Hook to include additional files before modules inclusion
			do_action( 'askka_action_before_include_modules' );

			foreach ( glob( ASKKA_INC_ROOT_DIR . '/*/include.php' ) as $module ) {
				include_once $module; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

			// Hook to include additional files after modules inclusion
			do_action( 'askka_action_after_include_modules' );
		}
	}

	Askka_Handler::get_instance();
}

/**
 * Add custom filter scripts
 */
function askka_add_filter_custom_scripts() {
	wp_add_inline_script( 'askka-main-js', '
		(function($) {
			"use strict";
			
			$(document).ready(function() {
				askkaInitFilterAnimation();
			});
			
			$(document).on("askka_trigger_get_new_posts", function() {
				askkaInitFilterAnimation();
			});
			
			function askkaInitFilterAnimation() {
				var filterItems = $(".qodef-m-filter-item");
				
				if (filterItems.length) {
					// Initial setup - ensure underline is visible for active item
					$(".qodef-m-filter-item.qodef--active").find(".qodef-m-filter-item-underline").css({
						"transform": "scaleX(1)",
						"transition": "none"
					});
					
					// Force repaint to ensure transition works properly
					setTimeout(function() {
						$(".qodef-m-filter-item.qodef--active").find(".qodef-m-filter-item-underline").css("transition", "");
					}, 10);
					
					// Handle click events
					filterItems.off("click.filterAnimation").on("click.filterAnimation", function(e) {
						var $clickedItem = $(this);
						
						// Avoid animations if already active
						if (!$clickedItem.hasClass("qodef--active")) {
							// Reset all items
							filterItems.removeClass("qodef--active");
							filterItems.find(".qodef-m-filter-item-underline").css("transform", "scaleX(0)");
							
							// Activate clicked item
							$clickedItem.addClass("qodef--active");
							$clickedItem.find(".qodef-m-filter-item-underline").css("transform", "scaleX(1)");
							
							// Show spinner with subtle fade-in
							$(".qodef-filter-pagination-spinner-wrapper").fadeIn(200);
						}
					});
					
					// Add subtle hover effects
					filterItems.on({
						"mouseenter": function() {
							if (!$(this).hasClass("qodef--active")) {
								$(this).find(".qodef-m-filter-item-underline").css("transform", "scaleX(0.4)");
							}
						},
						"mouseleave": function() {
							if (!$(this).hasClass("qodef--active")) {
								$(this).find(".qodef-m-filter-item-underline").css("transform", "scaleX(0)");
							}
						}
					});
				}
			}
		})(jQuery);
	', 'after' );
}
add_action( 'wp_enqueue_scripts', 'askka_add_filter_custom_scripts', 20 );
require_once get_stylesheet_directory() . '/assets/custom-menu-assets.php';


/**
 * Add custom filter styles
 */
function askka_add_filter_custom_styles() {
	$custom_css = '
	/* Enhanced Filter Styles */
	.qodef-m-filter {
		position: relative;
		display: inline-block;
		width: 100%;
		vertical-align: top;
		margin: 0 0 60px;
		text-align: center;
	}
	
	.qodef-m-filter .qodef-m-filter-items {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-wrap: wrap;
		margin: 0 -20px;
	}
	
	.qodef-m-filter .qodef-m-filter-item {
		position: relative;
		display: inline-block;
		margin: 8px 20px;
		padding: 5px 0;
		font-family: "Work sans", sans-serif;
		text-transform: uppercase;
		font-size: 13px;
		line-height: 1.3em;
		letter-spacing: 0.25em;
		font-weight: 500;
		color: #444;
		transition: all 0.35s ease;
	}
	
	.qodef-m-filter .qodef-m-filter-item-name {
		position: relative;
		display: inline-block;
	}
	
	.qodef-m-filter .qodef-m-filter-item-underline {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 2px;
		background-color: var(--qode-main-color, #e25c2c);
		transform: scaleX(0);
		transform-origin: center;
		transition: transform 0.4s cubic-bezier(0.22, 0.61, 0.36, 1);
	}
	
	.qodef-m-filter .qodef-m-filter-item:hover,
	.qodef-m-filter .qodef-m-filter-item.qodef--active {
		color: #000;
	}
	
	.qodef-m-filter .qodef-m-filter-item:hover .qodef-m-filter-item-underline,
	.qodef-m-filter .qodef-m-filter-item.qodef--active .qodef-m-filter-item-underline {
		transform: scaleX(1);
	}
	
	.qodef-filter-pagination-spinner-wrapper {
		position: absolute;
		top: 100%;
		left: 0;
		width: 100%;
		height: 40px;
		display: flex;
		justify-content: center;
		align-items: center;
		margin-top: 25px;
		display: none; /* Initially hidden */
	}
	
	.qodef-filter-pagination-spinner {
		animation: qodef-rotate 2s linear infinite;
		color: var(--qode-main-color, #e25c2c);
	}
	
	@keyframes qodef-rotate {
		100% {
			transform: rotate(360deg);
		}
	}
	
	@media only screen and (max-width: 1024px) {
		.qodef-m-filter .qodef-m-filter-items {
			margin: 0 -15px;
		}
		
		.qodef-m-filter .qodef-m-filter-item {
			margin: 6px 15px;
			font-size: 12px;
			letter-spacing: 0.2em;
		}
	}
	
	@media only screen and (max-width: 680px) {
		.qodef-m-filter {
			margin: 0 0 45px;
		}
		
		.qodef-m-filter .qodef-m-filter-items {
			margin: 0 -10px;
		}
		
		.qodef-m-filter .qodef-m-filter-item {
			margin: 5px 10px;
			font-size: 11px;
			letter-spacing: 0.15em;
		}
		
		.qodef-m-filter .qodef-m-filter-item-underline {
			height: 1px;
		}
	}
	';
	
	return $custom_css;
}

// Hook into the inline style filter
add_filter('askka_filter_add_inline_style', function($style) {
	$style .= askka_add_filter_custom_styles();
	return $style;
});

?>
