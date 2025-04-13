(function($) {
    'use strict';
    
    // Apply immediate inline style to hide all menus on script load
    const hideAllMenusImmediately = function() {
        // Use both inline styles and direct CSS property manipulation for maximum effect
        const inlineCSS = 'display:none !important; visibility:hidden !important; opacity:0 !important; height:0 !important; pointer-events:none !important;';
        $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').attr('style', inlineCSS).hide();
        
        // Purge any active menu classes to prevent theme's JavaScript from activating menus
        $('.menu-item-has-children, .menu-item--wide, .qodef-menu-item--wide').removeClass('qodef-menu-item--open qodef--opened hover-active menu-item-hover');
        
        console.log("Immediate menu hiding executed");
    };
    
    // Run this immediately on script load
    hideAllMenusImmediately();
    
    // Flag to prevent multiple rebuilds
    let menuRebuildInProgress = false;
    
    // Function to fix menu positioning and hover behavior
    function fixMenuPositioning() {
        console.log("Fixing menu positioning and hover behavior");
        
        // Aggressive cleanup of all events
        $(document).off('.menuFix');
        $('.qodef-header-navigation > ul > li').off();
        $('.qodef-header-navigation > ul > li > a').off();
        $('.qodef-header-navigation .sub-menu').off();
        $('.qodef-header-navigation .qodef-drop-down-second').off();
        
        // Debug all menu items
        console.log("All menu items: ");
        $('.qodef-header-navigation > ul > li').each(function() {
            const $item = $(this);
            const text = $item.text().trim();
            const hasDropdown = $item.find('.sub-menu, .qodef-drop-down-second').length > 0;
            const classes = $item.attr('class') || '';
            console.log(`Menu item: "${text}" - Has dropdown: ${hasDropdown} - Classes: ${classes}`);
        });
        
        // First, aggressively close all submenus immediately
        hideAllMenusImmediately();
        
        // Block any existing hover behaviors by overriding the hover pseudo-class
        // Create unique ID for the style tag to avoid duplication
        const styleId = 'menu-fix-styles';
        let styleTag = document.getElementById(styleId);
        if (styleTag) {
            styleTag.remove(); // Remove if exists to avoid duplications
        }
        
        // Add custom CSS to enforce horizontal menu
        const customStyles = `
            /* Force top navigation to be horizontal */
            .qodef-header-navigation > ul {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                justify-content: flex-start !important;
                align-items: center !important;
                width: auto !important;
            }
            
            /* Force top menu items to be horizontal */
            .qodef-header-navigation > ul > li {
                display: inline-flex !important;
                position: relative !important;
                float: none !important;
                margin: 0 25px 0 0 !important;
            }
            
            /* Ensure menu item text is properly displayed */
            .qodef-header-navigation > ul > li > a {
                white-space: nowrap !important;
                display: inline-block !important;
            }
        `;
        
        const style = document.createElement('style');
        style.id = styleId;
        style.textContent = `
            /* Emergency override - HIDE ALL SUBMENUS BY DEFAULT */
            .qodef-header-navigation .sub-menu:not(.hover-visible),
            .qodef-header-navigation .qodef-drop-down-second:not(.hover-visible),
            .qodef-drop-down-second-inner:not(.hover-visible) {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
                pointer-events: none !important;
                clip: rect(1px, 1px, 1px, 1px) !important;
                position: absolute !important;
                margin: -1px !important;
            }
            
            /* Override any potential :hover styles from theme */
            .qodef-header-navigation > ul > li:hover > .sub-menu:not(.hover-visible),
            .qodef-header-navigation > ul > li:hover > .qodef-drop-down-second:not(.hover-visible),
            .qodef-header-navigation > ul > li.menu-item-has-children:hover > .sub-menu:not(.hover-visible),
            .qodef-header-navigation > ul > li.menu-item-has-children:hover > .qodef-drop-down-second:not(.hover-visible) {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }
            
            /* Only our custom hover-active class should show submenus */
            .qodef-header-navigation > ul > li.hover-active > .sub-menu,
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second,
            .hover-visible {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
                overflow: visible !important;
                pointer-events: auto !important;
                clip: auto !important;
                position: absolute !important;
                margin: 0 !important;
                top: 100% !important;
                left: 0 !important;
                background-color: #fff !important;
                box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;
                border-radius: 0 !important;
                padding: 0 !important;
                border: none !important;
                z-index: 9999 !important;
                transition: opacity 0.2s, transform 0.2s !important;
                transform: translateY(0) !important;
            }
            
            /* Basic header layout fixes */
            #qodef-page-header-inner {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
                position: relative !important;
                z-index: 999 !important;
            }
            
            /* Navigation must be on the left */
            .qodef-header-navigation {
                order: 1 !important;
                position: relative !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                float: left !important;
                text-align: left !important;
                margin-right: auto !important;
                width: auto !important;
            }
            
            /* Logo in the center */
            .qodef-header-logo-link {
                order: 2 !important;
                position: absolute !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
            }
            
            /* Widgets on the right */
            .qodef-widget-holder {
                order: 3 !important;
                margin-left: auto !important;
            }
            
            ${customStyles}
            
            /* Force navigation elements to be visible but hide dropdowns by default */
            .qodef-header-navigation,
            .qodef-header-navigation > ul,
            .qodef-header-navigation > ul > li,
            .qodef-header-navigation > ul > li > a {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Menu items */
            .qodef-header-navigation > ul > li {
                display: inline-block !important;
                position: relative !important;
                margin: 0 25px 0 0 !important;
                float: left !important;
                text-align: left !important;
            }

            /* Menu items layout */
            .qodef-header-navigation > ul > li > a {
                padding: 25px 0 !important;
                font-weight: 500 !important;
                text-align: left !important;
                font-size: 15px !important;
                letter-spacing: 0.5px !important;
                position: relative !important;
                text-transform: uppercase !important;
                display: inline-block !important; /* Make sure the text area is clickable */
                width: auto !important;
                cursor: pointer !important;
            }
            
            /* Sub-menu styling when visible */
            .qodef-header-navigation > ul > li.hover-active > .sub-menu li,
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second .qodef-drop-down-second-inner li,
            .hover-visible li {
                margin: 0 !important;
                padding: 0 !important;
                position: relative !important;
                display: block !important;
                text-align: left !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Show inner content when parent is hovered */
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second .qodef-drop-down-second-inner,
            .hover-visible .qodef-drop-down-second-inner {
                display: flex !important;
                flex-direction: row !important;
                visibility: visible !important;
                opacity: 1 !important;
                position: relative !important;
                height: auto !important;
                overflow: visible !important;
                background-color: #fff !important;
                width: 100% !important;
            }
            
            /* Style links in submenu */
            .qodef-header-navigation > ul > li.hover-active > .sub-menu a,
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second .qodef-drop-down-second-inner a,
            .hover-visible a {
                display: block !important;
                padding: 10px 25px !important;
                font-size: 14px !important;
                line-height: 1.5 !important;
                color: #555 !important;
                white-space: nowrap !important;
                transition: all 0.2s ease !important;
                text-align: left !important;
                visibility: visible !important;
                opacity: 1 !important;
                border-radius: 0 !important;
            }
            
            /* Style links hover effect */
            .qodef-header-navigation .sub-menu a:hover,
            .qodef-drop-down-second-inner a:hover {
                background-color: #f9f9f9 !important;
                color: #000 !important;
            }
            
            /* Wide menu styling */
            .qodef-menu-item--wide .qodef-drop-down-second {
                left: 0 !important;
                width: 100vw !important;
                max-width: 100vw !important;
                margin: 0 !important;
                right: auto !important;
                transform: none !important;
                border-top: 1px solid #eee !important;
                box-shadow: 0 10px 40px rgba(0,0,0,0.08) !important;
                position: fixed !important;
                text-align: center !important;
            }
            
            /* Make sure wide menu inner content is displayed properly when parent is hovered */
            .qodef-menu-item--wide.hover-active .qodef-drop-down-second-inner,
            .hover-visible.qodef-drop-down-second .qodef-drop-down-second-inner {
                display: flex !important;
                flex-direction: row !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 100% !important;
                max-width: 1400px !important;
                margin: 0 auto !important;
                padding: 20px !important;
                box-sizing: border-box !important;
                text-align: left !important;
                transform: none !important;
                background-color: #fff !important;
                box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
                border-radius: 0 0 4px 4px !important;
            }
            
            /* Main categories column - left side */
            .mega-menu-categories {
                width: 240px !important;
                min-width: 240px !important;
                border-right: 1px solid #f0f0f0 !important;
                padding-right: 10px !important;
                margin-right: 20px !important;
            }
            
            /* Category item in the left column */
            .mega-menu-category-item {
                display: flex !important;
                align-items: center !important;
                padding: 10px 15px !important;
                font-size: 14px !important;
                color: #333 !important;
                border-radius: 4px !important;
                position: relative !important;
                cursor: pointer !important;
                transition: all 0.2s ease !important;
                margin-bottom: 5px !important;
            }
            
            /* Category item hover */
            .mega-menu-category-item:hover,
            .mega-menu-category-item.active {
                background-color: #f5f5f5 !important;
                color: #000 !important;
            }
            
            /* Category icon */
            .category-icon {
                width: 20px !important;
                height: 20px !important;
                margin-right: 10px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                color: #666 !important;
            }
            
            /* Arrow for categories with children */
            .mega-menu-category-item-with-children:after {
                content: '›' !important;
                position: absolute !important;
                right: 15px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                font-size: 16px !important;
                color: #999 !important;
            }
            
            /* Subcategories container - right side */
            .mega-menu-subcategories {
                flex: 1 !important;
                padding: 0 20px !important;
                display: none !important; /* Hide by default */
                flex-wrap: wrap !important;
                background-color: #f9f9f9 !important;
                border-radius: 4px !important;
            }
            
            /* Active subcategories container */
            .mega-menu-subcategories.active {
                display: flex !important;
                flex-direction: row !important;
            }
            
            /* Subcategory section */
            .mega-menu-subcategory-section {
                width: 25% !important;
                padding: 15px !important;
                box-sizing: border-box !important;
                background-color: #fff !important;
                margin: 10px !important;
                border-radius: 4px !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
                border-left: 3px solid #333 !important;
            }
            
            /* Subcategory section header */
            .mega-menu-subcategory-title {
                font-weight: 600 !important;
                font-size: 14px !important;
                color: #000 !important;
                margin-bottom: 10px !important;
                padding-bottom: 5px !important;
                border-bottom: 1px solid #f0f0f0 !important;
                position: relative !important;
                display: flex !important;
                align-items: center !important;
            }
            
            /* Subcategory icon */
            .subcategory-icon {
                width: 16px !important;
                height: 16px !important;
                margin-right: 8px !important;
                color: #333 !important;
            }
            
            /* Subcategory links list */
            .mega-menu-subcategory-links {
                list-style-type: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            /* Subcategory link item */
            .mega-menu-subcategory-link {
                display: flex !important;
                align-items: center !important;
                padding: 6px 0 !important;
                font-size: 13px !important;
                color: #666 !important;
                transition: all 0.2s ease !important;
                position: relative !important;
            }
            
            /* Subcategory link hover */
            .mega-menu-subcategory-link:hover {
                color: #000 !important;
                padding-left: 5px !important;
            }
            
            /* Subcategory link icon */
            .subcategory-link-icon {
                width: 14px !important;
                height: 14px !important;
                margin-right: 6px !important;
                color: #999 !important;
            }
            
            /* Featured section on the right */
            .mega-menu-featured-section {
                width: 240px !important;
                min-width: 240px !important;
                border-left: 1px solid #f0f0f0 !important;
                padding-left: 20px !important;
                margin-left: 20px !important;
            }
            
            /* Featured product in right column */
            .mega-menu-featured-product {
                margin-bottom: 15px !important;
            }
            
            /* Featured product image */
            .mega-menu-featured-product-image {
                width: 100% !important;
                height: auto !important;
                border-radius: 4px !important;
                margin-bottom: 8px !important;
            }
            
            /* Featured product title */
            .mega-menu-featured-product-title {
                font-weight: 500 !important;
                font-size: 13px !important;
                color: #333 !important;
                margin-bottom: 3px !important;
            }
            
            /* Featured product price */
            .mega-menu-featured-product-price {
                font-size: 12px !important;
                color: #f00 !important;
                font-weight: 500 !important;
            }
            
            /* Display submenu lists properly when parent is hovered */
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second .qodef-drop-down-second-inner ul,
            .hover-visible .qodef-drop-down-second-inner ul {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                visibility: visible !important;
                opacity: 1 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                align-items: flex-start !important;
            }
            
            /* Improved mega menu grid layout - now using sections */
            .hover-visible .qodef-drop-down-second-inner > ul,
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second .qodef-drop-down-second-inner > ul {
                display: flex !important;
                flex-direction: row !important;
                width: 100% !important;
            }
            
            /* Remove old category styling */
            .hover-visible .qodef-drop-down-second-inner > ul > li,
            .qodef-header-navigation > ul > li.hover-active > .qodef-drop-down-second .qodef-drop-down-second-inner > ul > li {
                display: block !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                text-align: left !important;
                border-right: none !important;
                background-color: transparent !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            /* Initialize all subcategory sections as hidden by default */
            .mega-menu-subcategory-content {
                display: none !important;
            }
            
            /* Only show the active subcategory section */
            .mega-menu-subcategory-content.active {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
            }
            
            /* Popular badge */
            .mega-menu-popular-badge {
                display: inline-block !important;
                font-size: 9px !important;
                background-color: #ff6b6b !important;
                color: #fff !important;
                padding: 1px 4px !important;
                border-radius: 2px !important;
                margin-left: 5px !important;
                vertical-align: middle !important;
                text-transform: uppercase !important;
                font-weight: 500 !important;
            }
            
            /* Category column scrollable if too many items */
            .mega-menu-categories {
                max-height: 400px !important;
                overflow-y: auto !important;
            }
            
            /* Scrollbar styling for category column */
            .mega-menu-categories::-webkit-scrollbar {
                width: 5px !important;
            }
            
            .mega-menu-categories::-webkit-scrollbar-track {
                background: #f1f1f1 !important;
            }
            
            .mega-menu-categories::-webkit-scrollbar-thumb {
                background: #ddd !important;
                border-radius: 3px !important;
            }
            
            /* Responsive adjustments */
            @media only screen and (max-width: 1200px) {
                .mega-menu-subcategory-section {
                    width: 33.33% !important;
                }
                
                .mega-menu-featured-section {
                    width: 180px !important;
                    min-width: 180px !important;
                }
                
                .mega-menu-categories {
                    width: 200px !important;
                    min-width: 200px !important;
                }
            }
            
            @media only screen and (max-width: 992px) {
                .mega-menu-subcategory-section {
                    width: 50% !important;
                }
                
                .mega-menu-featured-section {
                    display: none !important;
                }
            }
            
            @media only screen and (max-width: 768px) {
                .qodef-menu-item--wide.hover-active .qodef-drop-down-second-inner,
                .hover-visible.qodef-drop-down-second .qodef-drop-down-second-inner {
                    flex-direction: column !important;
                }
                
                .mega-menu-categories {
                    width: 100% !important;
                    border-right: none !important;
                    border-bottom: 1px solid #f0f0f0 !important;
                    padding-bottom: 15px !important;
                    margin-bottom: 15px !important;
                    max-height: 200px !important;
                }
                
                .mega-menu-subcategory-section {
                    width: 100% !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Debug info
        console.log("Menu status:");
        console.log("- Header exists: " + ($('#qodef-page-header').length > 0 ? "YES" : "NO"));
        console.log("- Navigation exists: " + ($('.qodef-header-navigation').length > 0 ? "YES" : "NO"));
        console.log("- Menu items count: " + $('.qodef-header-navigation > ul > li').length);
        
        // Get the header
        const $header = $('#qodef-page-header-inner');
        
        if ($header.length > 0) {
            console.log("Found header, applying direct manipulation");
            
            // Direct manipulation of DOM
            const $navigation = $('.qodef-header-navigation');
            const $logo = $('.qodef-header-logo-link');
            const $widgets = $('.qodef-widget-holder');
            
            // Only proceed if all elements exist
            if ($navigation.length > 0 && $logo.length > 0 && $widgets.length > 0) {
                // No need to empty/rebuild, just restyle
                
                // Apply styles directly to elements
                $navigation.css({
                    'order': '1',
                    'float': 'left',
                    'text-align': 'left',
                    'margin-right': 'auto',
                    'display': 'block'
                });
                
                $logo.css({
                    'order': '2',
                    'position': 'absolute',
                    'left': '50%',
                    'transform': 'translateX(-50%)'
                });
                
                $widgets.css({
                    'order': '3',
                    'margin-left': 'auto'
                });
                
                // Fix the navigation ul
                $navigation.find('> ul').css({
                    'display': 'flex',
                    'flex-direction': 'row',
                    'justify-content': 'flex-start',
                    'text-align': 'left',
                    'align-items': 'center'
                });
                
                // Fix navigation li items
                $navigation.find('> ul > li').css({
                    'display': 'inline-block',
                    'float': 'left',
                    'text-align': 'left'
                });
            }
        }
        
        // Stop any automatic menu triggers by removing classes the theme might use
        $('.qodef-header-navigation > ul > li').removeClass(
            'qodef-menu-item--open qodef--opened hover-active menu-item-hover current-menu-ancestor current-menu-parent'
        );
        
        // Force-close all submenus using direct DOM manipulation
        $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').each(function() {
            $(this).css({
                'display': 'none',
                'visibility': 'hidden',
                'opacity': '0',
                'pointer-events': 'none',
                'height': '0',
                'overflow': 'hidden',
                'position': 'absolute',
                'clip': 'rect(1px, 1px, 1px, 1px)'
            }).hide();
        });
        
        // Storage for timers
        var globalTimers = {};
        
        function setupMenu() {
            // Enhanced hover and display management
            $('.qodef-header-navigation > ul > li').each(function() {
                const $menuItem = $(this);
                const $menuLink = $menuItem.children('a');
                const $dropdown = $menuItem.find('.sub-menu, .qodef-drop-down-second').first();
                const menuText = $menuItem.text().trim();
                
                // Remove old events
                $menuItem.off('mouseenter mouseleave mouseover mouseout click');
                $menuLink.off('mouseenter mouseleave mouseover mouseout click');
                
                if ($dropdown.length) {
                    console.log(`Setting up direct hover for: "${menuText}" - Dropdown type: ${$dropdown.hasClass('qodef-drop-down-second') ? 'Mega Menu' : 'Standard Dropdown'}`);
                    
                    // Log the structure of the dropdown for debugging
                    console.log(`Dropdown HTML structure for "${menuText}":`);
                    const $innerContent = $dropdown.find('.qodef-drop-down-second-inner');
                    if ($innerContent.length) {
                        console.log(`- Inner content found: ${$innerContent.length} elements`);
                        console.log(`- Inner UL elements: ${$innerContent.find('ul').length}`);
                        console.log(`- Inner LI elements: ${$innerContent.find('li').length}`);
                    }
                    
                    // Apply hover handlers to both the menu item and the menu link to ensure reliable hovering
                    $menuItem.add($menuLink).on('mouseenter', function(e) {
                        console.log(`Mouseenter triggered for: "${menuText}"`);
                        
                        // Close all other dropdowns first - but ONLY close other main menu items, not submenu items
                        $('.qodef-header-navigation > ul > li').not($menuItem).removeClass('hover-active');
                        $('.qodef-header-navigation > ul > li').not($menuItem).find('> .sub-menu, > .qodef-drop-down-second')
                            .not($dropdown).hide().css({
                                'display': 'none',
                                'visibility': 'hidden',
                                'opacity': '0'
                            }).removeClass('hover-visible');
                        
                        // Show current dropdown with enhanced visibility
                        $menuItem.addClass('hover-active');
                        
                        // Don't hide the menu if it's already visible, just make sure it has the right classes
                        if (!$dropdown.hasClass('hover-visible')) {
                            // Then show it with all required classes and attributes
                            $dropdown.addClass('hover-visible').css({
                                'display': 'block',
                                'visibility': 'visible',
                                'opacity': '1',
                                'pointer-events': 'auto',
                                'height': 'auto',
                                'overflow': 'visible',
                                'clip': 'auto',
                                'position': 'absolute',
                                'margin': '0',
                                'z-index': '9999',
                                'top': '100%',
                                'left': '0',
                                'width': '100vw' // Ensure the dropdown is full width
                            }).show();
                            
                            // Handle special case for mega menus (qodef-drop-down-second)
                            if ($dropdown.hasClass('qodef-drop-down-second')) {
                                // Force inner content to be visible
                                const $innerContent = $dropdown.find('.qodef-drop-down-second-inner');
                                
                                $innerContent.addClass('hover-visible').css({
                                    'display': 'flex',
                                    'flex-direction': 'row',
                                    'visibility': 'visible',
                                    'opacity': '1',
                                    'height': 'auto',
                                    'width': '100%',
                                    'overflow': 'visible'
                                }).show();
                                
                                // Make all child elements visible
                                $innerContent.find('*').css({
                                    'visibility': 'visible',
                                    'opacity': '1'
                                }).show();
                                
                                // Specifically target nested lists to ensure they're visible
                                $innerContent.find('ul').css({
                                    'display': 'flex',
                                    'flex-direction': 'row',
                                    'flex-wrap': 'wrap',
                                    'visibility': 'visible',
                                    'opacity': '1',
                                    'height': 'auto'
                                }).addClass('hover-visible').show();
                                
                                $innerContent.find('li').css({
                                    'display': 'block',
                                    'visibility': 'visible',
                                    'opacity': '1'
                                }).show();
                                
                                $innerContent.find('a').css({
                                    'display': 'block',
                                    'visibility': 'visible',
                                    'opacity': '1'
                                }).show();
                                
                                // Ensure deeper nested content is also visible
                                $innerContent.find('ul ul').css({
                                    'display': 'block',
                                    'visibility': 'visible', 
                                    'opacity': '1',
                                    'height': 'auto'
                                }).addClass('hover-visible').show();
                            }
                        }
                        
                        e.stopPropagation();
                    });
                    
                    // Add more reliable mouse leave detection with better delay and proper containment check
                    let leaveTimer;
                    $menuItem.on('mouseleave', function(e) {
                        console.log(`Mouseleave triggered for: "${menuText}"`);
                        
                        // Clear any existing timer
                        clearTimeout(leaveTimer);
                        
                        // Get the element being moved to
                        const relatedTarget = e.relatedTarget || e.toElement;
                        
                        // Check if we're moving to the dropdown or any related elements 
                        // This checks the entire dropdown subtree, not just the immediate dropdown
                        if ($(relatedTarget).closest('.qodef-drop-down-second, .sub-menu').length || 
                            $(relatedTarget).closest('.qodef-header-navigation > ul > li').length) {
                            console.log(`Skipping hide - moved to menu-related element`);
                            return;
                        }
                        
                        // Only hide if we're completely leaving the entire menu structure
                        leaveTimer = setTimeout(function() {
                            // Check if any menus are opened without hover
                            if (!$('#custom-horizontal-menu-list > li:hover').length && 
                                !$('.qodef-header-navigation > ul > li:hover').length && 
                                !$('.sub-menu:visible').closest(':hover').length && 
                                !$('.qodef-drop-down-second:visible').closest(':hover').length &&
                                ($('.sub-menu:visible').length || $('.qodef-drop-down-second:visible').length)) {
                                console.log("Detected open menu without hover, fixing...");
                                hideAllMenusImmediately();
                            }
                            
                            // Only hide if we've completely left the ENTIRE menu structure
                            if (!$('.qodef-header-navigation:hover').length) {
                                console.log(`Hiding all dropdowns - completely left menu structure`);
                                $('.qodef-header-navigation > ul > li').removeClass('hover-active');
                                $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').hide().css({
                                    'display': 'none',
                                    'visibility': 'hidden',
                                    'opacity': '0',
                                    'pointer-events': 'none',
                                    'height': '0'
                                }).removeClass('hover-visible');
                            } else {
                                console.log(`Not hiding dropdown - some part of the menu is still being hovered`);
                            }
                        }, 250);
                    });
                    
                    // Add special handling for the dropdown itself
                    $dropdown.off('mouseenter mouseleave').on({
                        mouseenter: function() {
                            console.log(`Dropdown itself entered for "${menuText}"`);
                            // Clear any pending hide timer
                            clearTimeout(leaveTimer);
                            clearTimeout($menuItem.data('leaveTimerId'));
                            
                            // Clear related timers for any parent/child items
                            Object.keys(globalTimers).forEach(function(key) {
                                clearTimeout(globalTimers[key]);
                            });
                            
                            $(this).addClass('hover-visible').css({
                                'display': 'block',
                                'visibility': 'visible',
                                'opacity': '1',
                                'pointer-events': 'auto',
                                'height': 'auto',
                                'overflow': 'visible',
                                'clip': 'auto'
                            }).show();
                            
                            // Keep parent menu item active
                            $menuItem.addClass('hover-active');
                            
                            // Make sure all inner elements are visible and interactive
                            $(this).find('*').css({
                                'pointer-events': 'auto'
                            });
                        },
                        mouseleave: function() {
                            var menuText = $menuItem.find('> a').text().trim();
                            console.log(`Menu item mouse leave: "${menuText}"`);

                            // Clear any existing timer first
                            clearTimeout($menuItem.data('leaveTimerId'));

                            // Set a timer for hiding the dropdown
                            var leaveTimerId = setTimeout(function() {
                                // Only hide if we've completely left the ENTIRE menu structure
                                if (!$menuItem.is(':hover') && !$dropdown.is(':hover')) {
                                    // Double check if we're hovering any part of the dropdown structure
                                    if (!$dropdown.find('*:hover').length && 
                                        !$('.qodef-header-navigation .sub-menu:hover, .qodef-header-navigation .qodef-drop-down-second:hover').length) {
                                        console.log(`Hiding dropdown completely for "${menuText}"`);
                                        $menuItem.removeClass('hover-active');
                                        $dropdown.hide().css({
                                            'display': 'none',
                                            'visibility': 'hidden',
                                            'opacity': '0',
                                            'pointer-events': 'none',
                                            'height': '0'
                                        }).removeClass('hover-visible');
                                    } else {
                                        console.log(`Not hiding - element within dropdown is hovered`);
                                    }
                                } else {
                                    console.log(`Not hiding - still within menu structure`);
                                }
                            }, 150); // Slightly longer timeout for better usability

                            // Store timeout ID for cancellation
                            $menuItem.data('leaveTimerId', leaveTimerId);
                            globalTimers[menuText] = leaveTimerId;
                        }
                    });
                    
                    // Enhanced event handling for boxes inside mega menu
                    $dropdown.find('.mega-menu-categories, .mega-menu-subcategories, .mega-menu-featured-section, .mega-menu-category-item').each(function() {
                        const $box = $(this);
                        
                        $box.off('mouseenter mouseleave').on({
                            mouseenter: function() {
                                // Clear any hide timers when entering any box
                                clearTimeout(leaveTimer);
                                clearTimeout($menuItem.data('leaveTimerId'));
                                
                                // Ensure the dropdown stays visible
                                $dropdown.addClass('hover-visible').css({
                                    'display': 'block',
                                    'visibility': 'visible',
                                    'opacity': '1',
                                    'pointer-events': 'auto',
                                    'height': 'auto',
                                    'overflow': 'visible'
                                }).show();
                                
                                // Keep the parent menu item active
                                $menuItem.addClass('hover-active');
                            },
                            mouseleave: function(e) {
                                // Check if we're moving to another box or menu element
                                const relatedTarget = e.relatedTarget || e.toElement;
                                if ($(relatedTarget).closest('.qodef-header-navigation').length) {
                                    // Moving to another menu element, do nothing
                                    return;
                                }
                                
                                // Only start a timer if we're leaving the entire menu
                                if (!$(relatedTarget).closest('.qodef-header-navigation').length) {
                                    clearTimeout(leaveTimer);
                                    leaveTimer = setTimeout(function() {
                                        if (!$('.qodef-header-navigation:hover').length) {
                                            console.log(`Left all menus, hiding everything`);
                                            $('.qodef-header-navigation > ul > li').removeClass('hover-active');
                                            $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').hide().css({
                                                'display': 'none',
                                                'visibility': 'hidden',
                                                'opacity': '0',
                                                'height': '0'
                                            }).removeClass('hover-visible');
                                        }
                                    }, 250);
                                }
                            }
                        });
                    });
                    
                    // Ensure content within the dropdown is properly displayed
                    $dropdown.find('.mega-menu-categories, .mega-menu-subcategories, .mega-menu-featured-section').css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1'
                    });
                    
                    // Add click event for touchscreen devices
                    $menuLink.on('click', function(e) {
                        // Prevent default action if dropdown exists
                        if ($dropdown.length && window.innerWidth > 768) {
                            e.preventDefault();
                            $menuItem.trigger('mouseenter');
                        }
                    });
                }
            });
        }
        
        // After all the menu setup, add enhanced hover persistence
        $('.qodef-header-navigation, .qodef-header-navigation *').on('mouseenter', function() {
            // Cancel all pending dropdown hide timers when hovering any part of the menu
            Object.keys(globalTimers).forEach(function(key) {
                clearTimeout(globalTimers[key]);
            });
        });
        
        // Add hover handling for nested menu items
        $('.qodef-header-navigation .qodef-drop-down-second-inner ul li').off('mouseenter mouseleave').on({
            mouseenter: function() {
                $(this).addClass('hover-visible');
                // Keep all parent dropdowns visible
                $(this).parents('.qodef-drop-down-second, .qodef-drop-down-second-inner, .sub-menu').addClass('hover-visible').css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'pointer-events': 'auto'
                });
                
                // Keep parent menu item active
                $(this).closest('.qodef-header-navigation > ul > li').addClass('hover-active');
            },
            mouseleave: function() {
                $(this).removeClass('hover-visible');
            }
        });
        
        // Prevent hover state from getting "stuck" by adding a document click handler
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.qodef-header-navigation').length) {
                // Click outside menu - remove all hover states and hide dropdowns
                $('.qodef-header-navigation > ul > li').removeClass('hover-active');
                $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').hide().css({
                    'display': 'none',
                    'visibility': 'hidden',
                    'opacity': '0',
                    'pointer-events': 'none'
                }).removeClass('hover-visible');
            }
        });
        
        // Initialize the menu
        setupMenu();
        
        // Call the function to set up enhanced mega menu interaction
        setupMegaMenuInteraction();
    }
    
    // Run function on document ready
    $(document).ready(function() {
        console.log("Document ready - initializing menu fix");
        
        // First hide all dropdowns immediately 
        hideAllMenusImmediately();
        
        // Run the main fix function
        fixMenuPositioning();
        
        // Additional direct DOM manipulation to enforce horizontal menu
        forceHorizontalMenuLayout();
        
        // Create a MutationObserver to watch for DOM changes that might affect the menu
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && 
                    (mutation.target.closest('.qodef-header-navigation') || 
                     mutation.target.closest('.qodef-drop-down-second'))) {
                    console.log("DOM changes detected in menu area - reapplying fixes");
                    fixMenuPositioning();
                    forceHorizontalMenuLayout();
                }
            });
        });
        
        // Start observing the header for changes
        observer.observe(document.querySelector('#qodef-page-header'), { 
            childList: true, 
            subtree: true 
        });
    });
    
    // Also run when page is fully loaded
    $(window).on('load', function() {
        console.log("Window loaded - fixing menus");
        
        // Force close dropdowns again
        hideAllMenusImmediately();
        
        // Run the fix function again
        setTimeout(function() {
            fixMenuPositioning();
            forceHorizontalMenuLayout();
        }, 100);
    });
    
    // Function to force horizontal menu layout using direct DOM manipulation
    function forceHorizontalMenuLayout() {
        console.log("Forcing horizontal menu layout with nuclear approach");
        
        try {
            // Get the top navigation and menu items
            const $navigation = $('.qodef-header-navigation');
            const $menuList = $navigation.find('> ul').first();
            const $menuItems = $menuList.find('> li');
            
            if ($navigation.length && $menuList.length && $menuItems.length) {
                console.log(`Found menu with ${$menuItems.length} top-level items`);
                
                // NUCLEAR OPTION: Complete menu rebuild with hardcoded inline styles
                
                // Store the menu structure first
                const menuItems = [];
                $menuItems.each(function() {
                    const $item = $(this);
                    const $link = $item.find('> a').first();
                    const $dropdown = $item.find('> .sub-menu, > .qodef-drop-down-second').first();
                    
                    menuItems.push({
                        text: $link.text().trim(),
                        href: $link.attr('href') || '#',
                        hasDropdown: $dropdown.length > 0,
                        dropdown: $dropdown.clone(),
                        classes: $item.attr('class') || ''
                    });
                });
                
                // Remove any existing style element we've created before
                $('#custom-horizontal-menu-fix').remove();
                
                // Create a custom style tag with !important rules
                $('head').append(`
                    <style id="custom-horizontal-menu-fix">
                        /* Reset all menu styles */
                        #qodef-page-header-inner {
                            display: flex !important;
                            flex-direction: row !important;
                            align-items: center !important;
                            justify-content: space-between !important;
                            width: 100% !important;
                            position: relative !important;
                            z-index: 999 !important;
                        }
                        
                        /* Hide old navigation */
                        .qodef-header-navigation {
                            display: none !important;
                        }
                        
                        /* Custom horizontal navigation style */
                        #custom-horizontal-menu {
                            display: flex !important;
                            order: 1 !important;
                            margin-right: auto !important;
                        }
                        
                        #custom-horizontal-menu-list {
                            display: flex !important;
                            flex-direction: row !important;
                            flex-wrap: nowrap !important;
                            align-items: center !important;
                            list-style: none !important;
                            margin: 0 !important;
                            padding: 0 !important;
                        }
                        
                        #custom-horizontal-menu-list > li {
                            display: inline-block !important;
                            float: left !important;
                            margin-right: 25px !important;
                            position: relative !important;
                        }
                        
                        #custom-horizontal-menu-list > li > a {
                            display: inline-block !important;
                            padding: 25px 0 !important;
                            font-weight: 500 !important;
                            text-transform: uppercase !important;
                            white-space: nowrap !important;
                            font-size: 15px !important;
                            letter-spacing: 0.5px !important;
                            color: inherit !important;
                            text-decoration: none !important;
                        }
                    </style>
                `);
                
                // Create the custom navigation container
                const $customNav = $('<nav id="custom-horizontal-menu" class="qodef-header-navigation"></nav>');
                const $customList = $('<ul id="custom-horizontal-menu-list"></ul>');
                
                // Build new menu items with inline styles
                menuItems.forEach(function(item, index) {
                    const $newItem = $(`<li style="display: inline-block !important; float: left !important; margin-right: 25px !important; position: relative !important; height: auto !important;" class="${item.classes}"></li>`);
                    
                    const $newLink = $(`<a href="${item.href}" style="display: inline-block !important; padding: 25px 0 !important; white-space: nowrap !important; font-weight: 500 !important; text-transform: uppercase !important;">${item.text}</a>`);
                    
                    $newItem.append($newLink);
                    
                    // Add dropdown if necessary
                    if (item.hasDropdown) {
                        $newItem.append(item.dropdown);
                    }
                    
                    $customList.append($newItem);
                });
                
                $customNav.append($customList);
                
                // Replace the original navigation
                $navigation.after($customNav);
                $navigation.hide();
                
                // Apply header layout fixes
                const $header = $('#qodef-page-header-inner');
                const $logo = $('.qodef-header-logo-link');
                const $widgets = $('.qodef-widget-holder');
                
                // Force the header layout
                $header.css({
                    'display': 'flex',
                    'flex-direction': 'row',
                    'align-items': 'center',
                    'justify-content': 'space-between',
                    'width': '100%'
                });
                
                if ($logo.length) {
                    $logo.css({
                        'order': '2',
                        'position': 'absolute',
                        'left': '50%',
                        'transform': 'translateX(-50%)'
                    });
                }
                
                if ($widgets.length) {
                    $widgets.css({
                        'order': '3',
                        'margin-left': 'auto'
                    });
                }
                
                // Re-apply hover event handlers to the new menu
                $('#custom-horizontal-menu-list > li').each(function() {
                    const $menuItem = $(this);
                    const $menuLink = $menuItem.children('a');
                    const $dropdown = $menuItem.find('.sub-menu, .qodef-drop-down-second').first();
                    
                    if ($dropdown.length) {
                        // Apply hover handlers
                        $menuItem.add($menuLink).on('mouseenter', function() {
                            // Close all other dropdowns first
                            $('#custom-horizontal-menu-list > li').not($menuItem).removeClass('hover-active');
                            $('#custom-horizontal-menu-list > li').not($menuItem).find('> .sub-menu, > .qodef-drop-down-second')
                                .not($dropdown).hide().css({
                                    'display': 'none',
                                    'visibility': 'hidden',
                                    'opacity': '0'
                                }).removeClass('hover-visible');
                            
                            // Show current dropdown
                            $menuItem.addClass('hover-active');
                            $dropdown.addClass('hover-visible').css({
                                'display': 'block',
                                'visibility': 'visible',
                                'opacity': '1',
                                'pointer-events': 'auto',
                                'height': 'auto',
                                'overflow': 'visible',
                                'clip': 'auto',
                                'position': 'absolute',
                                'margin': '0',
                                'z-index': '9999',
                                'top': '100%',
                                'left': '0'
                            }).show();
                        });
                        
                        // Handle mouse leave
                        let leaveTimer;
                        $menuItem.on('mouseleave', function() {
                            clearTimeout(leaveTimer);
                            
                            leaveTimer = setTimeout(function() {
                                // Check if mouse is still outside menu structure
                                if (!$('#custom-horizontal-menu-list > li').is(':hover') && 
                                    !$('.sub-menu, .qodef-drop-down-second').is(':hover')) {
                                    
                                    $menuItem.removeClass('hover-active');
                                    $dropdown.hide().css({
                                        'display': 'none',
                                        'visibility': 'hidden',
                                        'opacity': '0'
                                    }).removeClass('hover-visible');
                                }
                            }, 250);
                        });
                        
                        // Add special handling for the dropdown itself
                        $dropdown.on('mouseenter', function() {
                            clearTimeout(leaveTimer);
                            
                            $(this).addClass('hover-visible').css({
                                'display': 'block',
                                'visibility': 'visible',
                                'opacity': '1'
                            }).show();
                            
                            $menuItem.addClass('hover-active');
                        }).on('mouseleave', function() {
                            clearTimeout(leaveTimer);
                            
                            leaveTimer = setTimeout(function() {
                                if (!$('#custom-horizontal-menu-list > li').is(':hover') && 
                                    !$('.sub-menu, .qodef-drop-down-second').is(':hover')) {
                                    
                                    $menuItem.removeClass('hover-active');
                                    $dropdown.hide().css({
                                        'display': 'none',
                                        'visibility': 'hidden',
                                        'opacity': '0'
                                    }).removeClass('hover-visible');
                                }
                            }, 250);
                        });
                    }
                });
                
                console.log("Nuclear menu rebuild complete with " + menuItems.length + " items");
            } else {
                console.log("Could not find navigation elements to enforce horizontal layout");
            }
        } catch (error) {
            console.error("Error in horizontal menu fix:", error);
        }
    }
    
    // Run periodically to ensure it stays fixed
    setInterval(function() {
        try {
            // Check if any menus are opened without hover
            if (!$('#custom-horizontal-menu-list > li:hover').length && 
                !$('.qodef-header-navigation > ul > li:hover').length && 
                !$('.sub-menu:visible').closest(':hover').length && 
                !$('.qodef-drop-down-second:visible').closest(':hover').length &&
                ($('.sub-menu:visible').length || $('.qodef-drop-down-second:visible').length)) {
                console.log("Detected open menu without hover, fixing...");
                hideAllMenusImmediately();
            }
            
            // Periodically check if the custom menu exists, rebuild if not, but prevent multiple rebuilds
            if ((!$('#custom-horizontal-menu').length || 
                $('#custom-horizontal-menu-list > li').length === 0) && !menuRebuildInProgress) {
                console.log("Custom menu missing or empty, rebuilding");
                menuRebuildInProgress = true;
                forceHorizontalMenuLayout();
                // Reset the flag after rebuild
                setTimeout(function() {
                    menuRebuildInProgress = false;
                }, 2000);
            }
        } catch (error) {
            console.error("Error in menu check interval:", error);
            menuRebuildInProgress = false;
        }
    }, 1000);
    
    // Add a manual hover test function that can be run from the console
    window.testMenuHover = function(menuSelector) {
        const $menuItem = $(menuSelector || '.qodef-header-navigation > ul > li:contains("SHOP")');
        
        if ($menuItem.length) {
            console.log(`Testing hover on ${$menuItem.text().trim()} menu item`);
            $menuItem.trigger('mouseenter');
            
            setTimeout(function() {
                const $dropdown = $menuItem.find('.sub-menu, .qodef-drop-down-second').first();
                if ($dropdown.length) {
                    console.log(`Dropdown found and is visible: ${$dropdown.is(':visible')}`);
                    console.log(`Dropdown classes: ${$dropdown.attr('class')}`);
                    console.log(`Dropdown contents:`, $dropdown.html().substring(0, 100) + '...');
                    
                    // Count elements inside
                    const $innerContent = $dropdown.find('.qodef-drop-down-second-inner');
                    if ($innerContent.length) {
                        console.log(`Inner content visible: ${$innerContent.is(':visible')}`);
                        console.log(`Inner UL elements: ${$innerContent.find('ul').length}`);
                        console.log(`Inner LI elements: ${$innerContent.find('li').length}`);
                        console.log(`Inner A elements: ${$innerContent.find('a').length}`);
                    }
                } else {
                    console.log(`No dropdown found for this menu item`);
                }
            }, 100);
        } else {
            console.log(`Menu item not found using selector: ${menuSelector || '.qodef-header-navigation > ul > li:contains("SHOP")'}`);
        }
    };
    
    // Update function inside jQuery to handle the interactive behavior
    function setupMegaMenuInteraction() {
        console.log("Setting up mega menu interaction with category hovering");
        
        // Find all mega menu containers
        $('.qodef-drop-down-second-inner').each(function() {
            const $megaMenu = $(this);
            
            // Create the mega menu structure if it doesn't exist yet
            if (!$megaMenu.find('.mega-menu-categories').length) {
                console.log("Creating mega menu structure");
                
                // Extract existing menu items
                const $originalItems = $megaMenu.find('> ul > li');
                
                if ($originalItems.length === 0) {
                    console.log("No original items found, skipping structure creation");
                    return;
                }
                
                // Create containers
                const $categoriesContainer = $('<div class="mega-menu-categories"></div>');
                const $subcategoriesContainer = $('<div class="mega-menu-subcategories"></div>');
                const $featuredSection = $('<div class="mega-menu-featured-section"></div>');
                
                // Empty the mega menu and add the new structure
                $megaMenu.empty().append($categoriesContainer).append($subcategoriesContainer).append($featuredSection);
                
                // Create featured products section
                $featuredSection.html(`
                    <h4 style="margin-top:0;margin-bottom:15px;font-size:14px;font-weight:600;">Featured Products</h4>
                    <div class="mega-menu-featured-product">
                        <img class="mega-menu-featured-product-image" src="https://via.placeholder.com/200x150" alt="Featured Product">
                        <div class="mega-menu-featured-product-title">Premium Item</div>
                        <div class="mega-menu-featured-product-price">$99.99</div>
                    </div>
                    <div class="mega-menu-featured-product">
                        <img class="mega-menu-featured-product-image" src="https://via.placeholder.com/200x150" alt="Featured Product">
                        <div class="mega-menu-featured-product-title">Best Seller</div>
                        <div class="mega-menu-featured-product-price">$59.99</div>
                    </div>
                `);
                
                // Process each original menu item
                $originalItems.each(function(index) {
                    const $item = $(this);
                    const itemTitle = $item.find('> a').text().trim();
                    const hasSubmenu = $item.find('ul').length > 0;
                    
                    // Add category item to the categories container
                    const categoryId = `category-${index}`;
                    const iconClass = getIconForCategory(itemTitle);
                    
                    const $categoryItem = $(`
                        <div class="mega-menu-category-item ${hasSubmenu ? 'mega-menu-category-item-with-children' : ''}" data-category="${categoryId}">
                            <span class="category-icon">${iconClass}</span>
                            <span>${itemTitle}</span>
                        </div>
                    `);
                    
                    $categoriesContainer.append($categoryItem);
                    
                    // Process subcategories if present
                    if (hasSubmenu) {
                        const $subcategories = $item.find('> ul > li');
                        
                        // Create subcategory container
                        const $subcategoryContent = $(`<div class="mega-menu-subcategory-content" data-category="${categoryId}"></div>`);
                        $subcategoriesContainer.append($subcategoryContent);
                        
                        // Process each subcategory
                        $subcategories.each(function() {
                            const $subItem = $(this);
                            const subItemTitle = $subItem.find('> a').text().trim();
                            const $subItemLinks = $subItem.find('> ul > li');
                            const subIconClass = getIconForSubcategory(subItemTitle);
                            
                            // Create subcategory section
                            const $subcategorySection = $(`
                                <div class="mega-menu-subcategory-section">
                                    <div class="mega-menu-subcategory-title">
                                        <span class="subcategory-icon">${subIconClass}</span>
                                        ${subItemTitle}
                                    </div>
                                    <ul class="mega-menu-subcategory-links"></ul>
                                </div>
                            `);
                            
                            const $linksList = $subcategorySection.find('.mega-menu-subcategory-links');
                            
                            // Add links if they exist
                            if ($subItemLinks.length) {
                                $subItemLinks.each(function() {
                                    const $link = $(this).find('> a');
                                    const linkText = $link.text().trim();
                                    const linkUrl = $link.attr('href');
                                    const linkIconClass = getLinkIcon(linkText);
                                    
                                    $linksList.append(`
                                        <li>
                                            <a href="${linkUrl}" class="mega-menu-subcategory-link">
                                                <span class="subcategory-link-icon">${linkIconClass}</span>
                                                ${linkText}
                                                ${isPopular(linkText) ? '<span class="mega-menu-popular-badge">Popular</span>' : ''}
                                            </a>
                                        </li>
                                    `);
                                });
                            } else {
                                // If no links, use the subcategory itself as a link
                                const $directLink = $subItem.find('> a');
                                const directLinkUrl = $directLink.attr('href');
                                
                                $linksList.append(`
                                    <li>
                                        <a href="${directLinkUrl}" class="mega-menu-subcategory-link">
                                            <span class="subcategory-link-icon">•</span>
                                            Browse All
                                        </a>
                                    </li>
                                `);
                            }
                            
                            $subcategoryContent.append($subcategorySection);
                        });
                    }
                });
                
                // Event handlers for category items
                $categoriesContainer.find('.mega-menu-category-item').on('mouseenter', function() {
                    const categoryId = $(this).data('category');
                    
                    // Remove active class from all categories and add to current
                    $categoriesContainer.find('.mega-menu-category-item').removeClass('active');
                    $(this).addClass('active');
                    
                    // Hide all subcategory contents and show current
                    $subcategoriesContainer.find('.mega-menu-subcategory-content').removeClass('active').hide();
                    const $currentSubcategory = $subcategoriesContainer.find(`.mega-menu-subcategory-content[data-category="${categoryId}"]`);
                    $currentSubcategory.addClass('active').show();
                    
                    // Show subcategories container if the current category has subcategories
                    if ($currentSubcategory.length) {
                        $subcategoriesContainer.addClass('active').show();
                    } else {
                        $subcategoriesContainer.removeClass('active').hide();
                    }
                });
                
                // Activate first category by default
                $categoriesContainer.find('.mega-menu-category-item').first().trigger('mouseenter');
            }
        });
    }
    
    // Helper function to get an appropriate icon for categories
    function getIconForCategory(categoryName) {
        categoryName = categoryName.toLowerCase();

        return '<i class="fa fa-circle"></i>'; // Default icon
    }
    
    // Helper function to get an appropriate icon for subcategories
    function getIconForSubcategory(subcategoryName) {
        subcategoryName = subcategoryName.toLowerCase();
        return '<i class="fa fa-folder"></i>'; // Default icon
    }
    
    // Helper function to get an appropriate icon for links
    function getLinkIcon(linkText) {
        linkText = linkText.toLowerCase();
        if (linkText.includes('bestseller') || linkText.includes('best seller')) return '<i class="fa fa-trophy"></i>';
        if (linkText.includes('new')) return '<i class="fa fa-star"></i>';
        if (linkText.includes('sale')) return '<i class="fa fa-percent"></i>';
        if (linkText.includes('limited')) return '<i class="fa fa-hourglass-half"></i>';
        return '<i class="fa fa-angle-right"></i>'; // Default icon
    }
    
    // Helper function to determine if a link should be marked as popular
    function isPopular(linkText) {
        linkText = linkText.toLowerCase();
        return linkText.includes('best') || 
               linkText.includes('popular') || 
               linkText.includes('featured') ||
               linkText.includes('top');
    }
    
    function setupHoverVisibleFunctionality() {
        const menuItems = document.querySelectorAll('.qodef-header-navigation > ul > li');
        
        // Remove any existing hover listeners to prevent duplication
        menuItems.forEach(item => {
            item.removeEventListener('mouseenter', handleMenuItemHover);
            item.removeEventListener('mouseleave', handleMenuItemLeave);
            
            // Add new listeners
            item.addEventListener('mouseenter', handleMenuItemHover);
            item.addEventListener('mouseleave', handleMenuItemLeave);
        });
        
        // Handle mouse enter on menu items
        function handleMenuItemHover(e) {
            // First, remove hover-visible and hover-active classes from all items
            menuItems.forEach(item => {
                item.classList.remove('hover-active');
                const subMenus = item.querySelectorAll('.sub-menu, .qodef-drop-down-second');
                subMenus.forEach(submenu => {
                    submenu.classList.remove('hover-visible');
                });
            });
            
            // Add hover-active class to current item
            const currentItem = e.currentTarget;
            currentItem.classList.add('hover-active');
            
            // Add hover-visible class to immediate submenu
            const subMenus = currentItem.querySelectorAll('.sub-menu, .qodef-drop-down-second');
            if (subMenus.length > 0) {
                subMenus[0].classList.add('hover-visible');
            }
        }
        
        // Handle mouse leave on menu items
        function handleMenuItemLeave(e) {
            const currentItem = e.currentTarget;
            
            // Short delay to handle moving between items
            setTimeout(() => {
                if (!currentItem.matches(':hover')) {
                    currentItem.classList.remove('hover-active');
                    const subMenus = currentItem.querySelectorAll('.sub-menu, .qodef-drop-down-second');
                    subMenus.forEach(submenu => {
                        submenu.classList.remove('hover-visible');
                    });
                }
            }, 50);
        }
    }

    // Call the setup function after DOM is fully loaded and other menu fixes are applied
    document.addEventListener('DOMContentLoaded', function() {
        // ... existing code ...
        
        // Setup the hover-visible functionality
        setTimeout(() => {
            setupHoverVisibleFunctionality();
            
            // Refresh the functionality if DOM changes or after AJAX calls
            const observer = new MutationObserver(() => {
                setupHoverVisibleFunctionality();
            });
            
            observer.observe(document.body, { 
                childList: true, 
                subtree: true 
            });
            
            // Log that hover-visible functionality is set up
            console.log('Hover-visible functionality initialized');
        }, 500); // Small delay to ensure other scripts have run
    });

})(jQuery); 