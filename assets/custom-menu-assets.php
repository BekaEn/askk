<?php
/**
 * Custom Menu Assets
 * Registers and enqueues custom menu CSS and JS files
 */

function custom_menu_assets() {
    // Register CSS files
    wp_register_style(
        'custom-menu-style',
        get_stylesheet_directory_uri() . '/assets/css/custom-menu.css',
        array(),
        '1.0.0',
        'all'
    );
    
    wp_register_style(
        'custom-menu-responsive',
        get_stylesheet_directory_uri() . '/assets/css/custom-menu-responsive.css',
        array('custom-menu-style'),
        '1.0.0',
        'all'
    );
    
    // Register Menu Fixes CSS
    wp_register_style(
        'menu-fixes',
        get_stylesheet_directory_uri() . '/assets/css/menu-fixes.css',
        array(),
        '1.0.1',
        'all'
    );
    
    // Register Logo Size Override CSS
    wp_register_style(
        'logo-size-override',
        get_stylesheet_directory_uri() . '/assets/logo-size-override.css',
        array(),
        '1.0.0',
        'all'
    );
    
    // Register JS file
    wp_register_script(
        'custom-menu-script',
        get_stylesheet_directory_uri() . '/assets/js/custom-menu.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Register menu fix scripts
    wp_register_script(
        'menu-fix-direct',
        get_stylesheet_directory_uri() . '/js/menu-fix-direct.js',
        array('jquery'),
        '1.0.2', // Increment version to force cache refresh
        true
    );
    
    wp_register_script(
        'restore-default-menu',
        get_stylesheet_directory_uri() . '/js/restore-default-menu.js',
        array('jquery'),
        '1.0.1',
        true
    );
    
    wp_register_script(
        'fix-theme-menu',
        get_stylesheet_directory_uri() . '/js/fix-theme-menu.js',
        array('jquery'),
        '1.0.1',
        true
    );
    
    // Register new positioning fix script
    wp_register_script(
        'menu-position-fix',
        get_stylesheet_directory_uri() . '/js/menu-position-fix.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Register emergency menu fix script
    wp_register_script(
        'menu-fix-emergency',
        get_stylesheet_directory_uri() . '/js/menu-fix-emergency.js',
        array('jquery'),
        '1.0.2', // Increment version to force cache refresh
        true
    );
    
    // Register logo size fix script
    wp_register_script(
        'logo-size-fix',
        get_stylesheet_directory_uri() . '/js/logo-size-fix.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Register search and cart position fix script
    wp_register_script(
        'fix-search-cart-position',
        get_stylesheet_directory_uri() . '/js/fix-search-cart-position.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    // Enqueue the files - SIMPLIFIED for debugging
    wp_enqueue_style('custom-menu-style');
    wp_enqueue_style('custom-menu-responsive');
    wp_enqueue_style('menu-fixes'); // Enable menu fixes CSS 
    wp_enqueue_style('logo-size-override'); // Control logo size
    
    // IMPORTANT: Only use one menu fix approach - using multiple can cause conflicts
    // Use only the emergency fix with highest priority and dequeue any theme scripts that might conflict
    //wp_enqueue_script('menu-fix-emergency', '', array('jquery'), '1.0.2', true);
    
    // Disable other menu fix scripts to avoid conflicts
    //wp_enqueue_script('menu-fix-direct');
    //wp_enqueue_script('restore-default-menu');
    //wp_enqueue_script('fix-theme-menu');
    //wp_enqueue_script('menu-position-fix');
    
    // Keep logo size fix
    wp_enqueue_script('logo-size-fix');
    
    // Add search and cart position fix
    wp_enqueue_script('fix-search-cart-position');
    
    // Dequeue theme scripts that might be causing conflicts
    add_action('wp_print_scripts', function() {
        wp_dequeue_script('askka-main-js'); // Try to dequeue main theme script that might be causing conflicts
    }, 100);
}
add_action('wp_enqueue_scripts', 'custom_menu_assets', 20); // Priority 20 to load after theme scripts

/**
 * Add debug CSS to identify menu issues
 */
function add_menu_debug_css() {
    ?>
    <style>
        /* Highlight menu elements for debugging */
        .qodef-header-navigation {
            border: 1px solid rgba(255, 0, 0, 0.2);
        }
        
        .qodef-header-navigation > ul > li {
            border: 1px solid rgba(0, 255, 0, 0.2);
        }
        
        .qodef-header-navigation .sub-menu {
            border: 1px solid rgba(0, 0, 255, 0.2);
        }
        
        /* Force menus to be visible */
        #qodef-page-header,
        .qodef-header-navigation {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
    <?php
}
//add_action('wp_head', 'add_menu_debug_css'); // Uncomment for debugging

/**
 * Fix PHP code in menu-fixes.css
 * This is needed because we can't use PHP code directly in CSS files
 */
function fix_menu_fixes_css() {
    ?>
    <style>
        /* HOME Link - with proper home URL */
        a[href="<?php echo esc_url(home_url('/')); ?>"] {
            display: inline-block !important;
            font-size: 16px !important;
            font-weight: 500 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            margin-right: 30px !important;
            color: #333 !important;
            text-decoration: none !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'fix_menu_fixes_css', 30); // Priority 30 to run after the CSS file is loaded

/**
 * Add specific fix for left-aligned menu
 */
function add_left_aligned_menu_fix() {
    ?>
    <style>
        /* CRITICAL FIX: Ensure header is ALWAYS visible and never hidden */
        body #qodef-page-header {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            z-index: 999 !important;
            transform: none !important;
            height: auto !important;
            min-height: 80px !important;
            width: 100% !important;
            top: 0 !important;
            left: 0 !important;
            pointer-events: auto !important;
            background-color: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
        }
        
        /* Ensure all header elements are visible */
        #qodef-page-header *,
        .qodef-header-navigation,
        .qodef-header-navigation > ul,
        .qodef-header-navigation > ul > li,
        .qodef-header-navigation > ul > li > a,
        .qodef-header-navigation-area,
        .qodef-header-logo-link,
        .qodef-widget-holder {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            overflow: visible !important;
        }
        
        /* Three-column grid layout for perfect positioning */
        #qodef-page-header-inner {
            display: grid !important;
            grid-template-columns: 1fr auto 1fr !important;
            align-items: center !important;
            justify-items: stretch !important;
            padding: 0 30px !important;
            min-height: 80px !important;
            width: 100% !important;
            position: relative !important;
            z-index: 999 !important;
            max-width: 1200px !important;
            margin: 0 auto !important;
        }
        
        /* Navigation area in FIRST column (left) */
        .qodef-header-navigation-area {
            grid-column: 1 !important;
            display: flex !important;
            justify-content: flex-start !important; 
            align-items: center !important;
            padding-right: 20px !important;
        }
        
        /* Logo in SECOND column (center) */
        .qodef-header-logo-link {
            grid-column: 2 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            max-width: 180px !important;
            margin: 0 auto !important;
        }
        
        /* Widget holder in THIRD column (right) */
        .qodef-widget-holder {
            grid-column: 3 !important;
            display: flex !important;
            justify-content: flex-end !important;
            align-items: center !important;
            padding-left: 20px !important;
        }
        
        /* Make sure cart and search icons are visible */
        .qodef-widget-holder .widget,
        .qodef-widget-holder .qodef-woo-dropdown-cart-widget,
        .qodef-widget-holder .qodef-search-opener-widget {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            visibility: visible !important;
            opacity: 1 !important;
            margin: 0 5px !important;
            vertical-align: middle !important;
        }
        
        /* Icon sizing and spacing */
        .qodef-woo-dropdown-cart .qodef-m-opener,
        .qodef-search-opener {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 8px !important;
        }
        
        /* Icon SVG sizing */
        .qodef-woo-dropdown-cart .qodef-m-opener svg,
        .qodef-search-opener svg {
            width: 22px !important;
            height: 22px !important;
        }
        
        /* Logo image size control */
        .qodef-header-logo-link img {
            max-width: 150px !important;
            max-height: 80px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            margin: 0 auto !important;
        }
        
        /* Menu items style */
        .qodef-header-navigation > ul {
            display: flex !important;
            flex-direction: row !important;
            margin: 0 !important;
            padding: 0 !important;
            justify-content: flex-start !important;
            flex-wrap: nowrap !important;
        }
        
        .qodef-header-navigation > ul > li {
            display: inline-block !important;
            vertical-align: middle !important;
            margin: 0 15px 0 0 !important;
        }
        
        /* Remove margin from last menu item */
        .qodef-header-navigation > ul > li:last-child {
            margin-right: 0 !important;
        }
        
        /* Make menu items bold */
        .qodef-header-navigation > ul > li > a {
            font-weight: 500 !important;
        }
        
        /* Dropdown menus */
        .qodef-header-navigation > ul > li.menu-item-has-children > .sub-menu {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            display: none;
            z-index: 9999 !important;
            min-width: 200px !important;
            background: white !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }
        
        /* Show dropdown on hover */
        .qodef-header-navigation > ul > li.menu-item-has-children:hover > .sub-menu {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            transform: none !important;
            pointer-events: auto !important;
        }
        
        /* Force search to ALWAYS be in 3rd column */
        .qodef-search-opener-widget {
            grid-column: 3 !important;
            margin-left: auto !important;
        }
        
        /* Force cart to ALWAYS be in 3rd column */
        .qodef-woo-dropdown-cart-widget {
            grid-column: 3 !important;
            margin-left: auto !important;
        }
        
        /* Hide icons if they appear in wrong places */
        .qodef-header-navigation-area .qodef-search-opener-widget,
        .qodef-header-navigation-area .qodef-woo-dropdown-cart-widget {
            display: none !important;
        }
        
        /* Mobile view */
        @media only screen and (max-width: 1024px) {
            #qodef-page-header-inner {
                display: flex !important;
                flex-direction: column !important;
                padding: 15px !important;
            }
            
            .qodef-header-logo-link {
                order: 1 !important;
                margin: 0 auto 15px !important;
            }
            
            .qodef-header-navigation-area {
                order: 2 !important;
                width: 100% !important;
                margin: 15px 0 !important;
                justify-content: center !important;
                padding-right: 0 !important;
            }
            
            .qodef-widget-holder {
                order: 3 !important;
                width: 100% !important;
                justify-content: center !important;
                margin: 10px 0 0 !important;
                padding-left: 0 !important;
            }
            
            /* Center menu items on mobile */
            .qodef-header-navigation > ul {
                justify-content: center !important;
            }
            
            /* Fix icon spacing on mobile */
            .qodef-widget-holder .widget {
                margin: 0 10px !important;
            }
        }
        
        /* Prevent any animations or transitions that might cause flickering */
        #qodef-page-header,
        #qodef-page-header-inner,
        .qodef-header-navigation,
        .qodef-header-navigation-area {
            transition: none !important;
            animation: none !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'add_left_aligned_menu_fix', 999); // Set priority to 999 to override ALL other styles

/**
 * Add direct CSS for fixing menu layout - This is now mostly moved to the menu-fixes.css file
 * Only keeping essential fixes that need PHP processing
 */
function add_menu_layout_fixes() {
    ?>
    <style>
        /* Force proper home link URL */
        #qodef-page-header a[href="<?php echo esc_url(home_url('/')); ?>"] {
            display: inline-block !important;
            font-weight: 500 !important;
        }
        
        /* Make HOME menu link properly positioned for the current theme */
        body header #qodef-page-header-inner nav ul li a[href="<?php echo esc_url(home_url('/')); ?>"] {
            margin-right: 30px !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'add_menu_layout_fixes', 25); // Priority 25 to run after standard theme styles

/**
 * Custom Menu Template
 * Outputs the HTML structure for the custom menu
 */
function custom_menu_template() {
    ?>
    <div class="custom-menu-container">
        <nav class="custom-menu">
            <div class="custom-menu-logo">
                <?php
                // Display site logo if available
                if (function_exists('the_custom_logo')) {
                    the_custom_logo();
                } else {
                    echo '<a href="' . esc_url(home_url('/')) . '">' . get_bloginfo('name') . '</a>';
                }
                ?>
            </div>
            <div class="custom-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="custom-menu-items">
                <?php
                // Display the custom menu
                wp_nav_menu(array(
                    'theme_location' => 'custom-main-menu',
                    'container' => false,
                    'fallback_cb' => false,
                    'depth' => 2, // Limit to 2 levels
                ));
                ?>
            </div>
        </nav>
    </div>
    <?php
}

/**
 * Register Custom Menu Location
 */
function register_custom_menu_location() {
    register_nav_menu('custom-main-menu', __('Custom Main Menu'));
}
add_action('init', 'register_custom_menu_location');

/**
 * Add Custom Menu to Top of the Page
 * Adjust the hook based on your theme structure
 */
function add_custom_menu_to_page() {
    // Uncomment to enable custom menu
    //custom_menu_template();
}
//add_action('wp_body_open', 'add_custom_menu_to_page');

/**
 * DO NOT REMOVE DEFAULT THEME NAVIGATION
 * This was the cause of the menu issues
 * 
 * Instead, we'll modify the menu CSS/JS to make it work
 */
function modify_theme_navigation() {
    // Instead of removing the default actions, we'll add our fixes
    
    // Add custom CSS to fix menu styles
    add_action('wp_head', function() {
        ?>
        <style>
            /* Ensure menus are visible */
            .qodef-header-navigation,
            .qodef-header-navigation > ul,
            .qodef-header-navigation > ul > li,
            .qodef-header-navigation > ul > li > a {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Force dropdowns to be visible on hover */
            .qodef-header-navigation > ul > li.menu-item-has-children:hover > .sub-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                transform: translateY(0) !important;
                pointer-events: auto !important;
            }
            
            /* Show menu area */
            .qodef-header-standard--right .qodef-header-navigation-area {
                display: flex !important;
            }
            
            /* Fix the display of the HOME link */
            body:not(.home) a[href="<?php echo esc_url(home_url('/')); ?>"] {
                display: inline-block !important;
                font-weight: 500 !important;
            }
        </style>
        <?php
    });
    
    // Keep breadcrumbs - we'll handle them separately if needed
    // Only remove if absolutely necessary
    //remove_action('askka_action_after_page_header_inner', 'askka_breadcrumbs');
    //remove_action('woocommerce_before_main_content', 'askka_add_breadcrumbs_to_product_list');
    //remove_action('woocommerce_before_single_product', 'askka_add_breadcrumbs_to_product_single');
}
add_action('init', 'modify_theme_navigation', 20);

/**
 * Add Custom Classes to Menu Items
 * Adds a 'has-dropdown' class to parent menu items
 */
function add_custom_menu_classes($items, $args) {
    // Process all menus, not just custom menus
    foreach ($items as $item) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $item->classes[] = 'has-dropdown';
        }
    }
    
    return $items;
}
add_filter('wp_nav_menu_objects', 'add_custom_menu_classes', 10, 2);

/**
 * Add emergency inline script to restore header elements if they are missing
 */
function add_menu_emergency_script() {
    ?>
    <script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            // Check if header navigation exists
            if ($('.qodef-header-navigation').length === 0 || $('.qodef-header-navigation > ul > li').length === 0) {
                console.log("Emergency: Header navigation missing, attempting to restore...");
                
                // Get reference to the page header
                var $pageHeader = $('#qodef-page-header');
                var $pageHeaderInner = $('#qodef-page-header-inner');
                
                if ($pageHeaderInner.length === 0 && $pageHeader.length > 0) {
                    // Create header inner if missing
                    $pageHeader.append('<div id="qodef-page-header-inner"></div>');
                    $pageHeaderInner = $('#qodef-page-header-inner');
                }
                
                if ($pageHeader.length > 0) {
                    // Create minimal header structure
                    if ($('.qodef-header-logo-link').length === 0) {
                        $pageHeaderInner.append('<div class="qodef-header-logo-link"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url(get_theme_mod('custom_logo')); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" /></a></div>');
                    }
                    
                    if ($('.qodef-header-navigation-area').length === 0) {
                        $pageHeaderInner.append('<nav class="qodef-header-navigation-area"><div class="qodef-header-navigation"><ul><li><a href="<?php echo esc_url(home_url('/')); ?>">HOME</a></li></ul></div></nav>');
                    }
                } else {
                    // If page header is missing, create the entire structure
                    $('body').prepend('<header id="qodef-page-header"><div id="qodef-page-header-inner"><div class="qodef-header-logo-link"><a href="<?php echo esc_url(home_url('/')); ?>">LOGO</a></div><nav class="qodef-header-navigation-area"><div class="qodef-header-navigation"><ul><li><a href="<?php echo esc_url(home_url('/')); ?>">HOME</a></li></ul></div></nav></div></header>');
                }
                
                // Apply emergency styles
                $('<style type="text/css">#qodef-page-header { display: block !important; visibility: visible !important; opacity: 1 !important; } #qodef-page-header-inner { display: flex !important; align-items: center !important; justify-content: space-between !important; padding: 15px !important; } .qodef-header-navigation-area { order: 1; } .qodef-header-logo-link { order: 2; } .qodef-widget-holder { order: 3; } .qodef-header-navigation > ul { display: flex !important; } .qodef-header-navigation > ul > li { display: inline-block !important; margin: 0 15px !important; }</style>').appendTo('head');
            }
        });
    })(jQuery);
    </script>
    <?php
}
add_action('wp_footer', 'add_menu_emergency_script');

/**
 * Add specific styling for logo size
 */
function add_logo_size_fix() {
    ?>
    <style>
        /* Control logo container size */
        .qodef-header-logo-link {
            max-width: 150px !important;
            max-height: 80px !important;
            overflow: visible !important;
        }
        
        /* Control logo image size */
        .qodef-header-logo-link img,
        .qodef-header-logo-link a img,
        #qodef-page-header .qodef-header-logo-link img,
        .qodef-header-logo-image {
            max-width: 150px !important;
            max-height: 80px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
        }
        
        /* Fix logo size on mobile */
        @media only screen and (max-width: 1024px) {
            .qodef-header-logo-link,
            .qodef-header-logo-link img,
            .qodef-mobile-header-logo-link img {
                max-width: 120px !important;
                max-height: 60px !important;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'add_logo_size_fix', 999); // Very high priority to override everything

/**
 * Add HTML attributes to constrain logo size
 */
function modify_logo_html_output($html) {
    // Check if this is a logo
    if (strpos($html, 'header-logo') !== false || strpos($html, 'logo-link') !== false) {
        // Add style attributes to both the link and the image
        $html = str_replace('<a ', '<a style="max-width:150px;max-height:80px;display:block;" ', $html);
        $html = str_replace('<img ', '<img style="max-width:150px;max-height:80px;width:auto;height:auto;" width="150" height="80" ', $html);
    }
    return $html;
}
add_filter('get_custom_logo', 'modify_logo_html_output');
add_filter('the_content', 'modify_logo_html_output');
add_filter('wp_get_attachment_image', 'modify_logo_html_output');

/**
 * Add critical JavaScript monitor to keep the header visible at all times
 * This is a last resort method that uses JavaScript to continually check
 * if the header is visible and force it to display if it becomes hidden
 */
function add_header_visibility_monitor() {
    ?>
    <script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            console.log("Header visibility monitor initialized");
            
            // Function to check header visibility
            function checkHeaderVisibility() {
                const $header = $('#qodef-page-header');
                
                if ($header.length === 0) {
                    console.error("Header element not found!");
                    return;
                }
                
                const headerStyle = window.getComputedStyle($header[0]);
                const isHidden = 
                    headerStyle.display === 'none' || 
                    headerStyle.visibility === 'hidden' || 
                    parseFloat(headerStyle.opacity) < 0.1;
                
                if (isHidden) {
                    console.warn("Header is hidden! Forcing visibility...");
                    
                    // Force inline styles with !important
                    $header.attr('style', 'display: block !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 999 !important;');
                    
                    // Also apply to inner container
                    $('#qodef-page-header-inner').attr('style', 'display: flex !important; visibility: visible !important; opacity: 1 !important;');
                    
                    // Force menu area to be visible
                    $('.qodef-header-navigation-area, .qodef-header-navigation').attr('style', 'display: block !important; visibility: visible !important; opacity: 1 !important;');
                }
            }
            
            // Initial check
            checkHeaderVisibility();
            
            // Set up continuous monitoring every 500ms
            const visibilityInterval = setInterval(checkHeaderVisibility, 500);
            
            // After 30 seconds, reduce frequency to save resources
            setTimeout(function() {
                clearInterval(visibilityInterval);
                setInterval(checkHeaderVisibility, 2000);
            }, 30000);
            
            // Also check on events that might affect visibility
            $(window).on('load resize scroll', checkHeaderVisibility);
            
            // Check after AJAX events complete
            $(document).ajaxComplete(function() {
                setTimeout(checkHeaderVisibility, 100);
            });
            
            // Add a MutationObserver to monitor the header for style/class changes
            if (window.MutationObserver) {
                const headerElement = $('#qodef-page-header')[0];
                if (headerElement) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'style' || mutation.attributeName === 'class') {
                                checkHeaderVisibility();
                            }
                        });
                    });
                    
                    observer.observe(headerElement, { 
                        attributes: true,
                        attributeFilter: ['style', 'class'],
                        subtree: true
                    });
                }
            }
        });
    })(jQuery);
    </script>
    <?php
}
add_action('wp_footer', 'add_header_visibility_monitor', 999); // Super high priority to run last

// Add a function to restore missing header icons
function restore_header_icons() {
    ?>
    <script type="text/javascript">
    (function($) {
        $(document).ready(function() {
            console.log("Checking for missing header icons...");
            
            // Check if widget holder exists
            let $widgetHolder = $('.qodef-widget-holder');
            
            // If widget holder doesn't exist, create it
            if ($widgetHolder.length === 0) {
                const $headerInner = $('#qodef-page-header-inner');
                if ($headerInner.length > 0) {
                    console.log("Creating missing widget holder");
                    $headerInner.append('<div class="qodef-widget-holder"></div>');
                    $widgetHolder = $('.qodef-widget-holder');
                }
            }
            
            // Check for cart icon
            if ($widgetHolder.length > 0 && $('.qodef-woo-dropdown-cart').length === 0) {
                console.log("Restoring cart icon");
                $widgetHolder.append(`
                    <div class="widget qodef-woo-dropdown-cart-widget">
                        <div class="qodef-woo-dropdown-cart qodef-m">
                            <a class="qodef-m-opener" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php echo esc_attr__('Cart', 'askka'); ?>">
                                <span class="qodef-m-opener-icon">
                                    <svg class="qodef-svg--cart" xmlns="http://www.w3.org/2000/svg" width="19.656" height="19.546" viewBox="0 0 19.656 19.546">
                                        <path d="M10.688,14a.359.359,0,0,1,.248.1A.341.341,0,0,1,11,14.35v3.3a.341.341,0,0,1-.69.248.359.359,0,0,1-.248.1H7.969a.359.359,0,0,1-.248-.1A.341.341,0,0,1,7.656,17.6v-3.3a.341.341,0,0,1,.065-.248.359.359,0,0,1,.248-.1ZM18.531,3.7l-.25.137a.688.688,0,0,1-.356.162.719.719,0,0,1-.38-.039L17.5,3.925a.7.7,0,0,1-.2-.137.676.676,0,0,1-.138-.2.73.73,0,0,1-.063-.209A1.014,1.014,0,0,1,17.094,3V.7a.352.352,0,0,0-.1-.25A.332.332,0,0,0,16.75.35h-1.1a.344.344,0,0,0-.346.346V3a.635.635,0,0,1-.2.5.656.656,0,0,1-.505.2.75.75,0,0,1-.308-.63.7.7,0,0,1-.2-.136l-2.2-1.925a.359.359,0,0,0-.248-.1.359.359,0,0,0-.248.1l-.778.778a.359.359,0,0,0-.1.248.359.359,0,0,0,.1.248l1.925,2.2a.7.7,0,0,1,.136.2.657.657,0,0,1,.64.309.675.675,0,0,1-.64.308.6.6,0,0,1-.136.209.656.656,0,0,1-.2.138.614.614,0,0,1-.234.064q-.052,0-.112.006c-.039,0-.078,0-.112,0H3.031a.33.33,0,0,0-.245.1.336.336,0,0,0-.1.242v1.1a.332.332,0,0,0,.1.246.335.335,0,0,0,.245.1H11.67q.056,0,.112,0c.039,0,.078,0,.112,0a.614.614,0,0,1,.234.064.656.656,0,0,1,.2.138.6.6,0,0,1,.136.209.666.666,0,0,1,0,.617.6.6,0,0,1-.136.209.656.656,0,0,1-.2.138.614.614,0,0,1-.234.064q-.053,0-.112.007c-.039,0-.078,0-.112,0h-8.7a.309.309,0,0,0-.345.346v.778a.309.309,0,0,0,.345.346h8.7c.074,0,.145,0,.213.007.068.005.139.012.213.019a.8.8,0,0,1,.307.12.83.83,0,0,1,.248.212.778.778,0,0,1,.111.167.874.874,0,0,1,.83.192.727.727,0,0,1,.27.161.663.663,0,0,1,.14.174.869.869,0,0,1,.0088.192.664.664,0,0,1-.0088.174.669.669,0,0,1-.14.161.727.727,0,0,1-.27.161.874.874,0,0,1-.83.192.812.812,0,0,1-.111.167.764.764,0,0,1-.248.205,1.059,1.059,0,0,1-.307.127c-.074.007-.145.014-.213.019s-.139.007-.213.007H5.2a.668.668,0,0,0-.488.2A.643.643,0,0,0,4.5,10.8v6.8a.643.643,0,0,0,.205.488.668.668,0,0,0,.488.2H14.469a.668.668,0,0,0,.488-.2.643.643,0,0,0,.2-.488V14.35a.341.341,0,0,1,.065-.248.359.359,0,0,1,.248-.1h2.719a.359.359,0,0,1,.248.1.341.341,0,0,1,.65.248v3.3a1.329,1.329,0,0,1-.4.972,1.317,1.317,0,0,1-.972.4H5.2a1.317,1.317,0,0,1-.972-.4,1.329,1.329,0,0,1-.4-.972V10.8a1.329,1.329,0,0,1,.4-.972,1.317,1.317,0,0,1,.972-.4h.5a.351.351,0,0,0,.248-.1.337.337,0,0,0,.1-.245V7.969a.309.309,0,0,0-.345-.346h-.5a.341.341,0,0,1-.248-.65.359.359,0,0,1-.1-.248V6.531a.359.359,0,0,1,.1-.248.341.341,0,0,1,.248-.065h.5a.309.309,0,0,0,.345-.346V4.984a.337.337,0,0,0-.1-.245.351.351,0,0,0-.248-.1H3.031A1.318,1.318,0,0,1,2.06,4.238a1.318,1.318,0,0,1-.4-.969V2.166a1.318,1.318,0,0,1,.4-.969A1.318,1.318,0,0,1,3.031.8H4.5a.342.342,0,0,0,.246-.1.336.336,0,0,0,.1-.245A.726.726,0,0,1,5.07.068.752.752,0,0,1,5.555,0,.773.773,0,0,1,5.921.64.745.745,0,0,1,6.2.241a.738.738,0,0,1,.271.209.742.742,0,0,1,.177.271.755.755,0,0,1,.64.367A.342.342,0,0,0,6.816.335a.336.336,0,0,0,.245.1h5.875a.342.342,0,0,0,.246-.1A.336.336,0,0,0,13.281.09a.761.761,0,0,1,.241-.367.775.775,0,0,1,.367-.64.755.755,0,0,1,.271.064.738.738,0,0,1,.271.209A.709.709,0,0,1,14.6.209a.742.742,0,0,1,.64.366.342.342,0,0,0,.1.246.336.336,0,0,0,.245.1h1.466a.342.342,0,0,0,.246-.1.336.336,0,0,0,.1-.245V.35a.332.332,0,0,0-.1-.25A.332.332,0,0,0,16.484,0H13a.359.359,0,0,1-.248-.1A.341.341,0,0,1,12.687-.35a.341.341,0,0,1,.065-.248.359.359,0,0,1,.248-.1h3.75a1.007,1.007,0,0,1,.741.308,1.007,1.007,0,0,1,.308.74V2.784a.669.669,0,0,0,.2.488.664.664,0,0,0,.488.2l.017,0a.375.375,0,0,1,.1.014.337.337,0,0,1,.092.03.35.35,0,0,1,.088.054A.328.328,0,0,1,18.8,3.65.335.335,0,0,1,18.859,3.75a.351.351,0,0,1,.02.1.3.3,0,0,1-.17.1.344.344,0,0,1-.1.248A.369.369,0,0,1,18.531,3.7ZM10.687,17.6a.359.359,0,0,0,.248-.1.341.341,0,0,0,.065-.248v-3.3a.341.341,0,0,0-.065-.248.359.359,0,0,0-.248-.1H7.969a.359.359,0,0,0-.248.1.341.341,0,0,0-.65.248v3.3a.341.341,0,0,0,.65.248.359.359,0,0,0,.248.1Z" transform="translate(0 0.7)"/>
                                    </svg>
                                </span>
                                <span class="qodef-m-opener-count">
                                    <?php echo WC()->cart->cart_contents_count; ?>
                                </span>
                            </a>
                            <div class="qodef-m-dropdown">
                                <div class="qodef-m-dropdown-inner">
                                    <!-- Cart content will be dynamically populated -->
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
            
            // Check for search icon
            if ($widgetHolder.length > 0 && $('.qodef-search-opener').length === 0) {
                console.log("Restoring search icon");
                $widgetHolder.append(`
                    <div class="widget qodef-search-opener-widget">
                        <a href="javascript:void(0)" class="qodef-search-opener">
                            <span class="qodef-search-opener-inner">
                                <svg class="qodef-svg--search" xmlns="http://www.w3.org/2000/svg" width="18.759" height="18.75" viewBox="0 0 18.759 18.75">
                                    <path d="M19.3,18.6l-4.531-4.531a.671.671,0,0,1-.156-.246,7.5,7.5,0,1,1,.949-.949.639.639,0,0,1,.246.156L20.339,17.56A1.242,1.242,0,0,1,19.3,18.6ZM12.222,13.3A5.625,5.625,0,1,0,6.6,7.678,5.629,5.629,0,0,0,12.222,13.3Z" transform="translate(-1.621 -1.621)"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                `);
            }
            
            // Make sure widget holder is properly displayed
            $('.qodef-widget-holder').css({
                'display': 'flex !important',
                'visibility': 'visible !important',
                'opacity': '1 !important',
                'align-items': 'center !important',
                'justify-content': 'flex-end !important'
            });
        });
    })(jQuery);
    </script>
    <?php
}
add_action('wp_footer', 'restore_header_icons', 20); // Run before other emergency scripts
