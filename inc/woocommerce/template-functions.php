<?php

/**
 * Global templates hooks
 */

if ( ! function_exists( 'askka_add_main_woo_page_template_holder' ) ) {
	/**
	 * Function that render additional content for main shop page
	 */
	function askka_add_main_woo_page_template_holder() {
		echo '<main id="qodef-page-content" class="qodef-grid qodef-layout--template qodef--no-bottom-space ' . esc_attr( askka_get_grid_gutter_classes() ) . '" role="main"><div class="qodef-grid-inner clear">';
	}
}

if ( ! function_exists( 'askka_add_main_woo_page_template_holder_end' ) ) {
	/**
	 * Function that render additional content for main shop page
	 */
	function askka_add_main_woo_page_template_holder_end() {
		echo '</div></main>';
	}
}

if ( ! function_exists( 'askka_add_main_woo_page_holder' ) ) {
	/**
	 * Function that render additional content around WooCommerce pages
	 */
	function askka_add_main_woo_page_holder() {
		$classes = array();

		// add class to pages with sidebar and on single page
		if ( askka_is_woo_page( 'archive' ) || askka_is_woo_page( 'single' ) ) {
			$classes[] = 'qodef-grid-item';
		}

		// add class to pages with sidebar
		if ( askka_is_woo_page( 'archive' ) ) {
			$classes[] = askka_get_page_content_sidebar_classes();
		}

		$classes[] = askka_get_woo_main_page_classes();

		echo '<div id="qodef-woo-page" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
	}
}

if ( ! function_exists( 'askka_add_main_woo_page_holder_end' ) ) {
	/**
	 * Function that render additional content around WooCommerce pages
	 */
	function askka_add_main_woo_page_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_woo_top_filters' ) ) {
	/**
	 * Function that render price filter and search at the top of product listings
	 */
	function askka_add_woo_top_filters() {
		if ( ! askka_is_woo_page( 'single' ) && askka_is_woo_page( 'archive' ) ) {
			// Remove the default breadcrumbs output in other locations
			remove_action('qodef_filter_breadcrumbs_template', 'askka_get_breadcrumbs');
			
			// Create a compact breadcrumbs section at the top
			echo '<div class="qodef-woo-top-breadcrumbs">';
			
			// Custom breadcrumbs that always include the Shop page
			$shop_page_id = wc_get_page_id('shop');
			$shop_page_url = get_permalink($shop_page_id);
			$shop_page_title = get_the_title($shop_page_id);
			
			echo '<div class="qodef-breadcrumbs">';
			echo '<a href="' . esc_url(home_url('/')) . '">Home</a>';
			echo '<span class="qodef-breadcrumbs-separator"></span>';
			
			// Always add the Shop link for category and product pages
			echo '<a href="' . esc_url($shop_page_url) . '">' . esc_html($shop_page_title) . '</a>';
			
			// For category pages, add the category
			if (is_product_category()) {
				$category = get_queried_object();
				echo '<span class="qodef-breadcrumbs-separator"></span>';
				echo '<span class="qodef-breadcrumbs-current">' . esc_html($category->name) . '</span>';
			} 
			// For product pages, add both category and product
			else if (is_product()) {
				global $post;
				$terms = get_the_terms($post->ID, 'product_cat');
				if ($terms && !is_wp_error($terms)) {
					$product_cat = $terms[0];
					echo '<span class="qodef-breadcrumbs-separator"></span>';
					echo '<a href="' . esc_url(get_term_link($product_cat)) . '">' . esc_html($product_cat->name) . '</a>';
				}
				echo '<span class="qodef-breadcrumbs-separator"></span>';
				echo '<span class="qodef-breadcrumbs-current">' . get_the_title() . '</span>';
			} 
			// For main shop page, mark it as current
			else if (is_shop()) {
				echo '<span class="qodef-breadcrumbs-separator"></span>';
				echo '<span class="qodef-breadcrumbs-current">' . esc_html($shop_page_title) . '</span>';
			}
			
			echo '</div>';
			echo '</div>';
			
			echo '<div class="qodef-woo-top-filters">';
			
			// Output the product search widget - now using a direct form instead of shortcode
			echo '<div class="qodef-woo-top-search">';
			echo '<h5 class="qodef-widget-title">'. esc_html__('Search Products', 'askka') .'</h5>';
			
			// Direct search form instead of shortcode
			echo '<form role="search" method="get" class="woocommerce-product-search qodef-search-form" action="' . esc_url( home_url( '/' ) ) . '">';
			echo '<div class="qodef-search-form-inner">';
			echo '<input type="search" id="woocommerce-product-search-field-top" class="search-field qodef-search-form-field" placeholder="' . esc_attr__( 'Search products...', 'askka' ) . '" value="' . get_search_query() . '" name="s" />';
			echo '<button type="submit" class="qodef-search-form-button qodef--button-inside qodef--has-icon">';
			echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="25px" viewBox="0 0 30 25" enable-background="new 0 0 30 25" xml:space="preserve"><circle cx="15.224" cy="11.08" r="5.412"></circle><line x1="18.275" y1="15.55" x2="20.454" y2="18.72"></line></svg>';
			echo '</button>';
			echo '</div>';
			echo '<input type="hidden" name="post_type" value="product" />';
			echo '</form>';
			echo '</div>';
			
			// Output the price filter widget
			echo '<div class="qodef-woo-top-price-filter">';
			
			// Get price filter directly
			ob_start();
			the_widget('WC_Widget_Price_Filter', array(
				'title' => esc_html__('Filter by Price', 'askka'),
			));
			$price_filter = ob_get_clean();
			
			echo $price_filter;
			echo '</div>';
			
			echo '</div>';
		}
	}
	
	// Hook the top filters before the product list
	add_action('woocommerce_before_shop_loop', 'askka_add_woo_top_filters', 15);
}

/**
 * Remove the default breadcrumbs from the shop page
 */
add_action('init', function() {
    // Remove WooCommerce default breadcrumbs
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    
    // Remove theme breadcrumbs from title area for WooCommerce pages
    add_action('wp', function() {
        if (function_exists('askka_is_woo_page') && (askka_is_woo_page('archive') || askka_is_woo_page('single'))) {
            remove_action('qodef_action_page_title_content', 'askka_render_breadcrumbs');
            remove_action('qodef_filter_breadcrumbs_template', 'askka_get_breadcrumbs');
            
            // Remove breadcrumbs from title area
            add_filter('qodef_filter_enable_page_title', function() {
                return false;
            });
        }
    });
    
    // Add CSS to hide the duplicate breadcrumbs in the page title
    add_action('wp_head', function() {
        if (function_exists('askka_is_woo_page') && (askka_is_woo_page('archive') || askka_is_woo_page('single'))) {
            echo '<style>
                /* Hide the page title breadcrumbs */
                .qodef-page-title.qodef-title--breadcrumbs { 
                    display: none !important; 
                }
                
                /* Remove excess whitespace at the top of shop pages */
                body.woocommerce-page #qodef-page-outer,
                body.woocommerce-page .qodef-page-title {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                }
                
                /* Reduce space around content */
                .qodef-woo-top-breadcrumbs {
                    margin-bottom: 20px;
                }
                
                /* Adjust padding to remove excess whitespace */
                #qodef-page-inner {
                    padding-top: 30px !important;
                }
                
                /* Remove top margin from content containers */
                div#qodef-page-inner {
                    margin-top: 0 !important;
                }
                
                /* Fix spacing in the shop header area */
                .qodef-grid.qodef-layout--template {
                    padding-top: 0 !important;
                }
                
                /* Ensure the header sticks to the top with no gap */
                #qodef-page-header {
                    margin-bottom: 0 !important;
                }
                
                /* Clean up the content area below breadcrumbs */
                .qodef-woo-top-filters {
                    margin-top: 0 !important;
                }
                
                /* Reduce space between title and content on category pages */
                .qodef-m-title {
                    margin-bottom: 20px !important;
                }
                
                /* Remove space above product category title */
                .woocommerce-products-header {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                }
                
                /* Remove extra padding around term description */
                .term-description {
                    margin-top: 0 !important;
                    padding-top: 0 !important;
                    margin-bottom: 15px !important;
                }
                
                /* Remove space below page title */
                .qodef-m-content {
                    padding-bottom: 0 !important;
                }
                
                /* Fix margins in category description area */
                .term-description p {
                    margin-top: 0 !important;
                    margin-bottom: 0 !important;
                }
                
                /* Remove extra space in page wrapper */
                #qodef-page-wrapper {
                    padding-top: 0 !important;
                }
                
                /* Simple Filter Button Styling */
                .widget_price_filter .button {
                    background-color: #e25c2c !important;
                    color: white !important;
                    border-radius: 30px !important;
                    padding: 10px 20px !important;
                    text-transform: uppercase !important;
                    font-weight: 500 !important;
                    border: none !important;
                    box-shadow: 0 4px 10px rgba(226, 92, 44, 0.2) !important;
                    transition: all 0.3s ease !important;
                    min-width: 120px !important;
                    text-align: center !important;
                    letter-spacing: 0.5px !important;
                    display: inline-block !important;
                }
                
                /* Hover effect */
                .widget_price_filter .button:hover {
                    background-color: #d24b1b !important;
                    transform: translateY(-2px) !important;
                    box-shadow: 0 6px 12px rgba(226, 92, 44, 0.3) !important;
                }
                
                /* Improve filter slider look */
                .price_slider_wrapper .ui-slider {
                    background-color: #f0f0f0 !important;
                    height: 6px !important;
                    border-radius: 10px !important;
                }
                
                .price_slider_wrapper .ui-slider .ui-slider-range {
                    background-color: #e25c2c !important;
                    height: 6px !important;
                }
                
                .price_slider_wrapper .ui-slider .ui-slider-handle {
                    background-color: white !important;
                    border: 2px solid #e25c2c !important;
                    border-radius: 50% !important;
                    width: 16px !important;
                    height: 16px !important;
                    top: -5px !important;
                }
                
                /* Center the price label and button */
                .price_slider_amount {
                    text-align: center !important;
                }
                
                .price_slider_amount .price_label {
                    display: block !important;
                    width: 100% !important;
                    margin-bottom: 15px !important;
                }
            </style>';
        }
    });
});

if ( ! function_exists( 'askka_add_main_woo_page_sidebar_holder' ) ) {
	/**
	 * Function that render sidebar layout for main shop page
	 */
	function askka_add_main_woo_page_sidebar_holder() {

		if ( ! askka_is_woo_page( 'single' ) ) {
			// Include page content sidebar
			askka_template_part( 'sidebar', 'templates/sidebar' );
		}
	}
}

if ( ! function_exists( 'askka_woo_render_product_categories' ) ) {
	/**
	 * Function that render product categories
	 *
	 * @param string $before
	 * @param string $after
	 */
	function askka_woo_render_product_categories( $before = '', $after = '' ) {
		echo askka_woo_get_product_categories( $before, $after ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_woo_get_product_categories' ) ) {
	/**
	 * Function that render product categories
	 *
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	function askka_woo_get_product_categories( $before = '', $after = '' ) {
		$product = askka_woo_get_global_product();

		return ! empty( $product ) ? wc_get_product_category_list( $product->get_id(), '<span class="qodef-info-separator-single"></span>', $before, $after ) : '';
	}
}

/**
 * Shop page templates hooks
 */

if ( ! function_exists( 'askka_add_results_and_ordering_holder' ) ) {
	/**
	 * Function that render additional content around results and ordering templates on main shop page
	 */
	function askka_add_results_and_ordering_holder() {
		echo '<div class="qodef-woo-results">';
	}
}

if ( ! function_exists( 'askka_add_results_and_ordering_holder_end' ) ) {
	/**
	 * Function that render additional content around results and ordering templates on main shop page
	 */
	function askka_add_results_and_ordering_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_holder' ) ) {
	/**
	 * Function that render additional content around product list item on main shop page
	 */
	function askka_add_product_list_item_holder() {
		echo '<div class="qodef-woo-product-inner">';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_holder_end' ) ) {
	/**
	 * Function that render additional content around product list item on main shop page
	 */
	function askka_add_product_list_item_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_image_holder' ) ) {
	/**
	 * Function that render additional content around image template on main shop page
	 */
	function askka_add_product_list_item_image_holder() {
		echo '<div class="qodef-woo-product-image">';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_image_holder_end' ) ) {
	/**
	 * Function that render additional content around image template on main shop page
	 */
	function askka_add_product_list_item_image_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_additional_image_holder' ) ) {
	/**
	 * Function that render additional content around image and sale templates on main shop page
	 */
	function askka_add_product_list_item_additional_image_holder() {
		echo '<div class="qodef-woo-product-image-inner">';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_additional_image_holder_end' ) ) {
	/**
	 * Function that render additional content around image and sale templates on main shop page
	 */
	function askka_add_product_list_item_additional_image_holder_end() {
		// Hook to include additional content inside product list item image
		do_action( 'askka_action_product_list_item_additional_image_content' );

		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_content_holder' ) ) {
	/**
	 * Function that render additional content around product info on main shop page
	 */
	function askka_add_product_list_item_content_holder() {
		echo '<div class="qodef-woo-product-content">';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_content_holder_end' ) ) {
	/**
	 * Function that render additional content around product info on main shop page
	 */
	function askka_add_product_list_item_content_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_product_list_item_categories' ) ) {
	/**
	 * Function that render product categories
	 */
	function askka_add_product_list_item_categories() {
		askka_woo_render_product_categories( '<div class="qodef-woo-product-categories">', '<div class="qodef-info-separator-end"></div></div>' );
	}
}

/**
 * Product single page templates hooks
 */

if ( ! function_exists( 'askka_add_product_single_content_holder' ) ) {
	/**
	 * Function that render additional content around image and summary templates on single product page
	 */
	function askka_add_product_single_content_holder() {
		echo '<div class="qodef-woo-single-inner">';
	}
}

if ( ! function_exists( 'askka_add_product_single_content_holder_end' ) ) {
	/**
	 * Function that render additional content around image and summary templates on single product page
	 */
	function askka_add_product_single_content_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_add_product_single_image_holder' ) ) {
	/**
	 * Function that render additional content around featured image on single product page
	 */
	function askka_add_product_single_image_holder() {
		echo '<div class="qodef-woo-single-image">';
	}
}

if ( ! function_exists( 'askka_add_product_single_image_holder_end' ) ) {
	/**
	 * Function that render additional content around featured image on single product page
	 */
	function askka_add_product_single_image_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_woo_product_render_social_share_html' ) ) {
	/**
	 * Function that render social share html
	 */
	function askka_woo_product_render_social_share_html() {

		if ( class_exists( 'AskkaCore_Social_Share_Shortcode' ) ) {
			$params           = array();
			$params['layout'] = 'list';
			$params['title']  = esc_html__( 'Share:', 'askka' );

			echo AskkaCore_Social_Share_Shortcode::call_shortcode( $params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

/**
 * Override default WooCommerce templates
 */

if ( ! function_exists( 'askka_woo_disable_page_heading' ) ) {
	/**
	 * Function that disable heading template on main shop page
	 *
	 * @return bool
	 */
	function askka_woo_disable_page_heading() {
		return false;
	}
}

if ( ! function_exists( 'askka_add_product_list_holder' ) ) {
	/**
	 * Function that add additional content around product lists on main shop page
	 *
	 * @param string $html
	 *
	 * @return string which contains html content
	 */
	function askka_add_product_list_holder( $html ) {
		$classes = array();
		$layout  = askka_get_post_value_through_levels( 'qodef_product_list_item_layout' );
		$option  = askka_get_post_value_through_levels( 'qodef_woo_product_list_columns_space' );

		if ( ! empty( $layout ) ) {
			$classes[] = 'qodef-item-layout--' . $layout;
		}

		if ( ! empty( $option ) ) {
			$classes[] = 'qodef-gutter--' . $option;
		}

		return '<div class="qodef-woo-product-list ' . esc_attr( implode( ' ', $classes ) ) . '">' . $html;
	}
}

if ( ! function_exists( 'askka_add_product_list_holder_end' ) ) {
	/**
	 * Function that add additional content around product lists on main shop page
	 *
	 * @param string $html
	 *
	 * @return string which contains html content
	 */
	function askka_add_product_list_holder_end( $html ) {
		return $html . '</div>';
	}
}

if ( ! function_exists( 'askka_woo_product_list_columns' ) ) {
	/**
	 * Function that set number of columns for main shop page
	 *
	 * @return int
	 */
	function askka_woo_product_list_columns() {
		$option = askka_get_post_value_through_levels( 'qodef_woo_product_list_columns' );

		if ( ! empty( $option ) ) {
			$columns = intval( $option );
		} else {
			$columns = 3;
		}

		return $columns;
	}
}

if ( ! function_exists( 'askka_woo_products_per_page' ) ) {
	/**
	 * Function that set number of items for main shop page
	 *
	 * @param int $products_per_page
	 *
	 * @return int
	 */
	function askka_woo_products_per_page( $products_per_page ) {
		$option = askka_get_post_value_through_levels( 'qodef_woo_product_list_products_per_page' );

		if ( ! empty( $option ) ) {
			$products_per_page = intval( $option );
		}

		return $products_per_page;
	}
}

if ( ! function_exists( 'askka_woo_pagination_args' ) ) {
	/**
	 * Function that override pagination args on main shop page
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function askka_woo_pagination_args( $args ) {
		$args['prev_text'] = askka_get_svg_icon( 'pagination-arrow-left' );
		$args['next_text'] = askka_get_svg_icon( 'pagination-arrow-right' );
		$args['type']      = 'plain';

		return $args;
	}
}

if ( ! function_exists( 'askka_add_single_product_classes' ) ) {
	/**
	 * Function that render additional content around WooCommerce pages
	 *
	 * @param array  $classes Default argument array
	 * @param string $class
	 * @param int    $post_id
	 *
	 * @return array
	 */
	function askka_add_single_product_classes( $classes, $class = '', $post_id = 0 ) {
		if ( ! $post_id || ! in_array( get_post_type( $post_id ), array( 'product', 'product_variation' ), true ) ) {
			return $classes;
		}

		$product = wc_get_product( $post_id );

		if ( $product ) {
			$new = get_post_meta( $post_id, 'qodef_show_new_sign', true );

			if ( 'yes' === $new ) {
				$classes[] = 'new';
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'askka_add_sale_flash_on_product' ) ) {
	/**
	 * Function for adding on sale template for product
	 */
	function askka_add_sale_flash_on_product() {
		echo askka_woo_set_sale_flash(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'askka_woo_set_sale_flash' ) ) {
	/**
	 * Function that override on sale template for product
	 *
	 * @return string which contains html content
	 */
	function askka_woo_set_sale_flash() {
		$product = askka_woo_get_global_product();

		if ( ! empty( $product ) && $product->is_on_sale() && $product->is_in_stock() ) {
			return askka_woo_get_woocommerce_sale( $product );
		}

		return '';
	}
}

if ( ! function_exists( 'askka_woo_get_woocommerce_sale' ) ) {
	/**
	 * Function that return sale mark label
	 *
	 * @param object $product
	 *
	 * @return string
	 */
	function askka_woo_get_woocommerce_sale( $product ) {
		$enable_percent_mark = askka_get_post_value_through_levels( 'qodef_woo_enable_percent_sign_value' );
		$price               = floatval( $product->get_regular_price() );
		$sale_price          = floatval( $product->get_sale_price() );

		if ( $price > 0 && 'yes' === $enable_percent_mark ) {
			$sale_label = '-' . ( 100 - round( ( $sale_price * 100 ) / $price ) ) . '%';
		} else {
			$sale_label = esc_html__( 'Sale', 'askka' );
		}

		return '<span class="qodef-woo-product-mark qodef-woo-onsale">' . $sale_label . '</span>';
	}
}

if ( ! function_exists( 'askka_add_out_of_stock_mark_on_product' ) ) {
	/**
	 * Function for adding out of stock template for product
	 */
	function askka_add_out_of_stock_mark_on_product() {
		$product = askka_woo_get_global_product();

		if ( ! empty( $product ) && ! $product->is_in_stock() ) {
			echo askka_get_out_of_stock_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'askka_get_out_of_stock_mark' ) ) {
	/**
	 * Function for adding out of stock template for product
	 *
	 * @return string
	 */
	function askka_get_out_of_stock_mark() {
		return '<span class="qodef-woo-product-mark qodef-out-of-stock">' . esc_html__( 'Sold', 'askka' ) . '</span>';
	}
}

if ( ! function_exists( 'askka_add_new_mark_on_product' ) ) {
	/**
	 * Function for adding out of stock template for product
	 */
	function askka_add_new_mark_on_product() {
		$product = askka_woo_get_global_product();

		if ( ! empty( $product ) && $product->get_id() !== '' ) {
			echo askka_get_new_mark( $product->get_id() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'askka_get_new_mark' ) ) {
	/**
	 * Function for adding out of stock template for product
	 *
	 * @param int $product_id
	 *
	 * @return string
	 */
	function askka_get_new_mark( $product_id ) {
		$option = get_post_meta( $product_id, 'qodef_show_new_sign', true );

		if ( 'yes' === $option ) {
			return '<span class="qodef-woo-product-mark qodef-new">' . esc_html__( 'New', 'askka' ) . '</span>';
		}

		return false;
	}
}

if ( ! function_exists( 'askka_woo_shop_loop_item_title' ) ) {
	/**
	 * Function that override product list item title template
	 */
	function askka_woo_shop_loop_item_title() {
		$option    = askka_get_post_value_through_levels( 'qodef_woo_product_list_title_tag' );
		$title_tag = ! empty( $option ) ? esc_attr( $option ) : 'h5';

		echo '<' . esc_attr( $title_tag ) . ' class="qodef-woo-product-title woocommerce-loop-product__title">' . wp_kses_post( get_the_title() ) . '</' . esc_attr( $title_tag ) . '>';
	}
}

if ( ! function_exists( 'askka_woo_template_single_title' ) ) {
	/**
	 * Function that override product single item title template
	 */
	function askka_woo_template_single_title() {
		$option    = askka_get_post_value_through_levels( 'qodef_woo_single_title_tag' );
		$title_tag = ! empty( $option ) ? esc_attr( $option ) : 'h1';

		echo '<' . esc_attr( $title_tag ) . ' class="qodef-woo-product-title product_title entry-title">' . wp_kses_post( get_the_title() ) . '</' . esc_attr( $title_tag ) . '>';
	}
}

if ( ! function_exists( 'askka_woo_single_thumbnail_images_columns' ) ) {
	/**
	 * Function that set number of columns for thumbnail images on single product page
	 *
	 * @param int $columns
	 *
	 * @return int
	 */
	function askka_woo_single_thumbnail_images_columns( $columns ) {
		$option = askka_get_post_value_through_levels( 'qodef_woo_single_thumbnail_images_columns' );

		if ( ! empty( $option ) ) {
			$columns = intval( $option );
		}

		return $columns;
	}
}

if ( ! function_exists( 'askka_woo_single_thumbnail_images_size' ) ) {
	/**
	 * Function that set thumbnail images size on single product page
	 *
	 * @return string
	 */
	function askka_woo_single_thumbnail_images_size() {
		return apply_filters( 'askka_filter_woo_single_thumbnail_size', 'woocommerce_thumbnail' );
	}
}

if ( ! function_exists( 'askka_woo_single_thumbnail_images_wrapper' ) ) {
	/**
	 * Function that add additional wrapper around thumbnail images on single product
	 */
	function askka_woo_single_thumbnail_images_wrapper() {
		echo '<div class="qodef-woo-thumbnails-wrapper">';
	}
}

if ( ! function_exists( 'askka_woo_single_thumbnail_images_wrapper_end' ) ) {
	/**
	 * Function that add additional wrapper around thumbnail images on single product
	 */
	function askka_woo_single_thumbnail_images_wrapper_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'askka_woo_single_related_product_list_columns' ) ) {
	/**
	 * Function that set number of columns for related product list on single product page
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function askka_woo_single_related_product_list_columns( $args ) {
		$option = askka_get_post_value_through_levels( 'qodef_woo_single_related_product_list_columns' );

		if ( ! empty( $option ) ) {
			$args['posts_per_page'] = intval( $option );
			$args['columns']        = intval( $option );
		}

		return $args;
	}
}

if ( ! function_exists( 'askka_woo_product_get_rating_html' ) ) {
	/**
	 * Function that override ratings templates
	 *
	 * @param string $html - contains html content
	 * @param float  $rating
	 *
	 * @return string
	 */
	function askka_woo_product_get_rating_html( $html, $rating ) {
		if ( ! empty( $rating ) ) {
			$html  = '<div class="qodef-woo-ratings qodef-m"><div class="qodef-m-inner">';
			$html .= '<div class="qodef-m-star qodef--initial">';
			for ( $i = 0; $i < 5; $i ++ ) {
				$html .= askka_get_svg_icon( 'star', 'qodef-m-star-item' );
			}
			$html .= '</div>';
			$html .= '<div class="qodef-m-star qodef--active" style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';
			for ( $i = 0; $i < 5; $i ++ ) {
				$html .= askka_get_svg_icon( 'star', 'qodef-m-star-item' );
			}
			$html .= '</div>';
			$html .= '</div></div>';
		}

		return $html;
	}
}

if ( ! function_exists( 'askka_woo_get_product_search_form' ) ) {
	/**
	 * Function that override product search widget form
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_product_search_form() {
		return askka_get_template_part( 'woocommerce', 'templates/product-searchform' );
	}
}

if ( ! function_exists( 'askka_woo_get_content_widget_product' ) ) {
	/**
	 * Function that override product content widget
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_content_widget_product( $located, $template_name ) {

		if ( 'content-widget-product.php' === $template_name && file_exists( ASKKA_INC_ROOT_DIR . '/woocommerce/templates/content-widget-product.php' ) ) {
			$located = ASKKA_INC_ROOT_DIR . '/woocommerce/templates/content-widget-product.php';
		}

		return $located;
	}
}

if ( ! function_exists( 'askka_woo_get_quantity_input' ) ) {
	/**
	 * Function that override quantity input
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_quantity_input( $located, $template_name ) {

		if ( 'global/quantity-input.php' === $template_name && file_exists( ASKKA_INC_ROOT_DIR . '/woocommerce/templates/global/quantity-input.php' ) ) {
			$located = ASKKA_INC_ROOT_DIR . '/woocommerce/templates/global/quantity-input.php';
		}

		return $located;
	}
}

if ( ! function_exists( 'askka_woo_get_single_product_meta' ) ) {
	/**
	 * Function that override single product meta
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_single_product_meta( $located, $template_name ) {

		if ( 'single-product/meta.php' === $template_name && file_exists( ASKKA_INC_ROOT_DIR . '/woocommerce/templates/single-product/meta.php' ) ) {
			$located = ASKKA_INC_ROOT_DIR . '/woocommerce/templates/single-product/meta.php';
		}

		return $located;
	}
}

if ( ! function_exists( 'askka_woo_get_cart_empty' ) ) {
	/**
	 * Function that override single product meta
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_cart_empty( $located, $template_name ) {

		if ( 'cart/cart-empty.php' === $template_name && file_exists( ASKKA_INC_ROOT_DIR . '/woocommerce/templates/cart/cart-empty.php' ) ) {
			$located = ASKKA_INC_ROOT_DIR . '/woocommerce/templates/cart/cart-empty.php';
		}

		return $located;
	}
}

if ( ! function_exists( 'askka_woo_get_myaccount_form_login' ) ) {
	/**
	 * Function that override single product meta
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_myaccount_form_login( $located, $template_name ) {

		if ( 'myaccount/form-login.php' === $template_name && file_exists( ASKKA_INC_ROOT_DIR . '/woocommerce/templates/myaccount/form-login.php' ) ) {
			$located = ASKKA_INC_ROOT_DIR . '/woocommerce/templates/myaccount/form-login.php';
		}

		return $located;
	}
}

if ( ! function_exists( 'askka_woo_get_order_form_tracking' ) ) {
	/**
	 * Function that override single product meta
	 *
	 * @param string $located
	 * @param string $template_name
	 *
	 * @return string which contains html content
	 */
	function askka_woo_get_order_form_tracking( $located, $template_name ) {

		if ( 'order/form-tracking.php' === $template_name && file_exists( ASKKA_INC_ROOT_DIR . '/woocommerce/templates/order/form-tracking.php' ) ) {
			$located = ASKKA_INC_ROOT_DIR . '/woocommerce/templates/order/form-tracking.php';
		}

		return $located;
	}
}
