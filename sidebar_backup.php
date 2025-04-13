<?php
/**
 * The sidebar containing widget areas
 *
 * @package Askka
 */

// Get the sidebar name from theme's function
$sidebar_name = apply_filters('askka_filter_sidebar_name', 'qodef-main-sidebar');

// Don't continue if sidebar is not active
if (!is_active_sidebar($sidebar_name) && !class_exists('WooCommerce')) {
    return;
}
?>

<aside id="qodef-page-sidebar" class="widget-area sidebar">
    <div class="sidebar-inner">
        
        <?php if (class_exists('WooCommerce')): ?>
        <!-- Product Categories - only with products -->
        <div class="widget sidebar-widget product-categories-widget">
            <h5 class="qodef-widget-title"><?php esc_html_e('Product Categories', 'askka'); ?></h5>
            <ul class="category-list product-categories">
                <?php
                // Get product categories that have products
                $product_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => 1, // Only show categories with products
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));
                
                if (!empty($product_categories) && !is_wp_error($product_categories)) {
                    foreach ($product_categories as $category) {
                        // Check if category has products
                        if ($category->count > 0) {
                            $cat_link = get_term_link($category);
                            echo '<li class="cat-item cat-item-' . esc_attr($category->term_id) . '">';
                            echo '<a href="' . esc_url($cat_link) . '">';
                            echo esc_html($category->name);
                            echo '<span class="post-count"> (' . esc_html($category->count) . ')</span>';
                            echo '</a>';
                            echo '</li>';
                        }
                    }
                } else {
                    echo '<li class="no-categories">' . esc_html__('No product categories found', 'askka') . '</li>';
                }
                ?>
            </ul>
        </div>
        <?php else: ?>
        <!-- Blog Categories - only with posts -->
        <div class="widget sidebar-widget categories-widget">
            <h5 class="qodef-widget-title"><?php esc_html_e('Blog Categories', 'askka'); ?></h5>
            <ul class="category-list">
                <?php
                // Get post categories that have posts
                $categories = get_categories(array(
                    'hide_empty' => 1, // Only show categories with posts
                    'orderby'    => 'name',
                    'order'      => 'ASC'
                ));
                
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        echo '<li class="cat-item cat-item-' . esc_attr($category->term_id) . '">';
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">';
                        echo esc_html($category->name);
                        echo '<span class="post-count"> (' . esc_html($category->count) . ')</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="no-categories">' . esc_html__('No categories found', 'askka') . '</li>';
                }
                ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <!-- Tags -->
        <div class="widget sidebar-widget tags-widget">
            <h5 class="qodef-widget-title"><?php esc_html_e('Popular Tags', 'askka'); ?></h5>
            <div class="tagcloud">
                <?php
                wp_tag_cloud(array(
                    'smallest' => 12,
                    'largest'  => 15,
                    'unit'     => 'px',
                    'number'   => 15,
                    'format'   => 'flat',
                    'orderby'  => 'count',
                    'order'    => 'DESC',
                ));
                ?>
            </div>
        </div>
        
        <!-- Best Sellers -->
        <?php if (class_exists('WooCommerce')): ?>
        <div class="widget sidebar-widget bestsellers-widget">
            <h5 class="qodef-widget-title"><?php esc_html_e('Best Sellers', 'askka'); ?></h5>
            <?php
            // Display best selling products if WooCommerce is active
            $args = array(
                'post_type'      => 'product',
                'meta_key'       => 'total_sales',
                'orderby'        => 'meta_value_num',
                'posts_per_page' => 5,
            );
            
            $loop = new WP_Query($args);
            
            if ($loop->have_posts()) {
                echo '<ul class="bestseller-products">';
                
                while ($loop->have_posts()) : $loop->the_post();
                    global $product;
                    ?>
                    <li class="bestseller-product">
                        <a href="<?php the_permalink(); ?>" class="product-link">
                            <div class="product-thumbnail">
                                <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('thumbnail');
                                } else {
                                    echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="' . esc_attr__('Placeholder', 'askka') . '">';
                                }
                                ?>
                            </div>
                            <div class="product-info">
                                <h4 class="product-title"><?php the_title(); ?></h4>
                                <div class="product-price"><?php echo $product->get_price_html(); ?></div>
                            </div>
                        </a>
                    </li>
                    <?php
                endwhile;
                
                echo '</ul>';
                wp_reset_postdata();
            } else {
                echo '<p class="no-products">' . esc_html__('No products found', 'askka') . '</p>';
            }
            ?>
        </div>
        <?php endif; ?>
        
        <?php 
        // Custom function to filter out specific widgets
        if (function_exists('dynamic_sidebar') && $sidebar_name) {
            // Directly access registered widgets and remove unwanted ones
            global $wp_registered_widgets;
            
            // Create a temporary array to store widget ids to be removed
            $widgets_to_remove = array();
            
            // Find widgets to remove
            foreach ($wp_registered_widgets as $widget_id => $widget_data) {
                // Remove search widget
                if (strpos($widget_id, 'woocommerce_product_search') !== false) {
                    $widgets_to_remove[] = $widget_id;
                }
                
                // Remove price filter widget
                if (strpos($widget_id, 'woocommerce_price_filter') !== false) {
                    $widgets_to_remove[] = $widget_id;
                }
                
                // Remove blog list widget
                if (strpos($widget_id, 'askka_core_blog_list') !== false) {
                    $widgets_to_remove[] = $widget_id;
                }
                
                // Remove separator widgets
                if (strpos($widget_id, 'askka_core_separator') !== false) {
                    $widgets_to_remove[] = $widget_id;
                }
            }
            
            // Temporarily backup the global widgets array
            $original_widgets = $wp_registered_widgets;
            
            // Remove unwanted widgets
            foreach ($widgets_to_remove as $remove_id) {
                unset($wp_registered_widgets[$remove_id]);
            }
            
            // Display the sidebar without unwanted widgets
            dynamic_sidebar($sidebar_name);
            
            // Restore original widgets
            $wp_registered_widgets = $original_widgets;
        }
        ?>
    </div>
</aside><!-- #qodef-page-sidebar -->

<style>
/* Custom Sidebar Styling */
#qodef-page-sidebar .widget {
    margin-bottom: 35px;
    padding: 25px;
    background-color: #fff;
    border-radius: 6px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

#qodef-page-sidebar .qodef-widget-title {
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e5e5;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* Categories styling */
#qodef-page-sidebar .category-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

#qodef-page-sidebar .category-list li {
    padding: 8px 0;
    border-bottom: 1px solid #f2f2f2;
}

#qodef-page-sidebar .category-list li:last-child {
    border-bottom: none;
}

#qodef-page-sidebar .category-list a {
    display: flex;
    justify-content: space-between;
    color: #444;
    font-size: 14px;
    transition: color 0.3s ease;
    text-decoration: none;
}

#qodef-page-sidebar .category-list a:hover {
    color: var(--qode-main-color, #e25c2c);
}

#qodef-page-sidebar .post-count {
    color: #909090;
    font-size: 12px;
}

/* Tags styling */
#qodef-page-sidebar .tagcloud {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

#qodef-page-sidebar .tagcloud a {
    display: inline-block;
    padding: 5px 12px;
    background-color: #f5f5f5;
    border-radius: 3px;
    color: #555;
    font-size: 12px !important;
    text-decoration: none;
    transition: all 0.3s ease;
}

#qodef-page-sidebar .tagcloud a:hover {
    background-color: var(--qode-main-color, #e25c2c);
    color: #fff;
}

/* Best Sellers styling */
#qodef-page-sidebar .bestseller-products {
    margin: 0;
    padding: 0;
    list-style: none;
}

#qodef-page-sidebar .bestseller-product {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #f2f2f2;
}

#qodef-page-sidebar .bestseller-product:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

#qodef-page-sidebar .product-link {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: inherit;
}

#qodef-page-sidebar .product-thumbnail {
    flex: 0 0 60px;
    width: 60px;
    height: 60px;
}

#qodef-page-sidebar .product-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

#qodef-page-sidebar .product-info {
    flex: 1;
}

#qodef-page-sidebar .product-title {
    margin: 0 0 5px;
    font-size: 14px;
    line-height: 1.3;
    font-weight: 500;
    color: #333;
    transition: color 0.3s ease;
}

#qodef-page-sidebar .product-price {
    font-size: 13px;
    color: var(--qode-main-color, #e25c2c);
    font-weight: 600;
}

#qodef-page-sidebar .product-link:hover .product-title {
    color: var(--qode-main-color, #e25c2c);
}

#qodef-page-sidebar .no-products,
#qodef-page-sidebar .woocommerce-required,
#qodef-page-sidebar .no-categories {
    margin: 0;
    font-size: 14px;
    color: #777;
}

/* Hide any additional product category widgets that might be added by the theme or plugins */
#qodef-page-sidebar .widget_product_categories,
#qodef-page-sidebar .widget_tag_cloud,
#qodef-page-sidebar .widget_products,
#qodef-page-sidebar .widget_askka_core_blog_list {
    display: none !important;
}

/* Price Filter Widget Enhanced Styling */
.widget_price_filter {
    padding-bottom: 30px !important;
}

.widget_price_filter .price_slider_wrapper {
    padding-top: 15px;
}

.widget_price_filter .price_slider {
    height: 6px !important;
    background-color: #f0f0f0 !important;
    border-radius: 10px !important;
    margin-bottom: 25px !important;
    position: relative;
    border: none !important;
}

.widget_price_filter .ui-slider-range {
    background: linear-gradient(to right, #e25c2c, #ff7a50) !important;
    opacity: 1 !important;
    border-radius: 10px !important;
    height: 6px !important;
    top: 0 !important;
}

.widget_price_filter .ui-slider-handle {
    background: #ffffff !important;
    border: 2px solid #e25c2c !important;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1) !important;
    border-radius: 50% !important;
    width: 18px !important;
    height: 18px !important;
    top: -6px !important;
    cursor: pointer !important;
    transform: translateX(-50%);
    transition: box-shadow 0.2s ease, background-color 0.2s ease !important;
}

.widget_price_filter .ui-slider-handle:hover, 
.widget_price_filter .ui-slider-handle.ui-state-active {
    background-color: #e25c2c !important;
    box-shadow: 0 0 0 3px rgba(226, 92, 44, 0.2) !important;
}

.widget_price_filter .ui-slider-handle:focus {
    outline: none !important;
}

.widget_price_filter .price_slider_amount {
    display: flex;
    flex-direction: column;
    text-align: center;
    position: relative;
}

.widget_price_filter .price_label {
    margin-bottom: 15px;
    color: #666;
    font-size: 14px;
    order: 1;
}

.widget_price_filter .price_label .from,
.widget_price_filter .price_label .to {
    font-weight: 600;
    color: #333;
    background-color: #f7f7f7;
    padding: 3px 8px;
    border-radius: 4px;
    margin: 0 2px;
}

.widget_price_filter button.button {
    background-color: #e25c2c !important;
    color: white !important;
    border: none !important;
    border-radius: 30px !important;
    padding: 10px 20px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: auto !important;
    margin: 0 auto !important;
    transition: all 0.3s ease !important;
    order: 2;
    box-shadow: 0 4px 10px rgba(226, 92, 44, 0.2) !important;
    height: 55px !important;
    line-height: 45px !important;
    position: relative !important;
    overflow: hidden !important;
}

.widget_price_filter button.button:before {
    content: "" !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(to right, rgba(255,255,255,0.1), rgba(255,255,255,0.2)) !important;
    transform: skewX(-25deg) translateX(-100%) !important;
    transition: all 0.5s ease !important;
}

.widget_price_filter button.button:hover:before {
    transform: skewX(-25deg) translateX(100%) !important;
}

.widget_price_filter button.button:hover {
    background-color: #d24b1b !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 12px rgba(226, 92, 44, 0.3) !important;
}

.widget_price_filter button.button:after {
    content: "→" !important;
    margin-left: 8px !important;
    font-size: 18px !important;
    transition: transform 0.3s ease !important;
}

.widget_price_filter button.button:hover:after {
    transform: translateX(3px) !important;
}

/* Custom animations for slider */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(226, 92, 44, 0.4);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(226, 92, 44, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(226, 92, 44, 0);
    }
}

.widget_price_filter .ui-slider-handle:active {
    animation: pulse 1.5s infinite;
}

/* Top Filters Styling */
.qodef-woo-top-filters {
    display: flex;
    flex-direction: column;
    margin-bottom: 30px;
    background-color: #fff;
    border-radius: 6px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    width: 100%;
}

/* Top Search Styling */
.qodef-woo-top-search {
    width: 100%;
    margin-bottom: 20px;
}

.qodef-woo-top-search .qodef-widget-title {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.qodef-woo-top-search .woocommerce-product-search,
.qodef-woo-top-search .qodef-search-form {
    display: flex;
    width: 100%;
}

.qodef-woo-top-search .qodef-search-form-inner {
    display: flex;
    width: 100%;
    position: relative;
}

.qodef-woo-top-search .search-field,
.qodef-woo-top-search .qodef-search-form-field {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #e5e5e5;
    border-radius: 30px 0 0 30px;
    font-size: 14px;
    transition: all 0.3s ease;
    width: 100%;
    height: 55px !important;
    line-height: 55px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.qodef-woo-top-search .search-field:focus,
.qodef-woo-top-search .qodef-search-form-field:focus {
    outline: none;
    border-color: #e25c2c;
    box-shadow: 0 0 0 3px rgba(226, 92, 44, 0.1);
}

.qodef-woo-top-search button {
    background-color: #e25c2c;
    color: white;
    border: none;
    border-radius: 0 30px 30px 0;
    padding: 0 20px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qodef-woo-top-search button:hover {
    background-color: #d24b1b;
}

.qodef-woo-top-search button svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
}

/* Top Price Filter Styling */
.qodef-woo-top-price-filter {
    width: 100%;
}

.qodef-woo-top-price-filter .widget_price_filter {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.qodef-woo-top-price-filter .price_slider_wrapper {
    padding-top: 0;
}

/* Make search and filter widgets responsive */
@media (max-width: 768px) {
    .qodef-woo-top-filters {
        flex-direction: column;
    }
    
    .qodef-woo-top-search,
    .qodef-woo-top-price-filter {
        width: 100%;
    }
}

/* Make sure buttons in the top filters have consistent height */
.qodef-woo-top-search button,
.qodef-woo-top-price-filter button.button {
    height: 55px !important;
    line-height: 45px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

/* Top Breadcrumbs Styling */
.qodef-woo-top-breadcrumbs {
    padding: 10px 20px;
    background-color: #f7f7f7;
    border-radius: 6px 6px 0 0;
    margin-bottom: 0;
    width: 100%;
}

.qodef-woo-top-breadcrumbs .qodef-breadcrumbs {
    font-size: 12px;
    color: #888;
    display: flex;
    align-items: center;
}

.qodef-woo-top-breadcrumbs .qodef-breadcrumbs a {
    color: #555;
    text-decoration: none;
    transition: color 0.2s ease;
}

.qodef-woo-top-breadcrumbs .qodef-breadcrumbs a:hover {
    color: #e25c2c;
}

.qodef-woo-top-breadcrumbs .qodef-breadcrumbs-separator {
    display: inline-block;
    width: 4px;
    height: 4px;
    background-color: #ccc;
    border-radius: 50%;
    margin: 0 8px;
    vertical-align: middle;
}

/* Update top filters to connect with breadcrumbs */
.qodef-woo-top-filters {
    border-radius: 0 0 6px 6px;
    margin-top: 0;
}
</style>

<script>
// Custom JavaScript to enhance the price filter interaction
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is available (WooCommerce uses jQuery for sliders)
    if (typeof jQuery !== 'undefined') {
        (function($) {
            // Add a subtle animation when price filter values change
            $('.price_slider').on('slidechange', function() {
                $('.price_label').css('opacity', '0.5');
                setTimeout(function() {
                    $('.price_label').css({
                        'opacity': '1',
                        'transition': 'opacity 0.3s ease'
                    });
                }, 100);
            });
            
            // Add active class to handles on mousedown
            $('.ui-slider-handle').on('mousedown touchstart', function() {
                $(this).addClass('ui-state-active');
            });
            
            // Remove active class on mouseup anywhere on document
            $(document).on('mouseup touchend', function() {
                $('.ui-slider-handle').removeClass('ui-state-active');
            });
        })(jQuery);
    }
});
</script>
