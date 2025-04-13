(function($) {
    'use strict';
    
    // Debug function to inspect menu structure
    function debugMenuStructure() {
        console.log("Debugging menu structure...");
        
        // Check for navigation elements
        const headerNav = $('.qodef-header-navigation');
        const mobileNav = $('.qodef-mobile-header-navigation');
        
        console.log("Header navigation found:", headerNav.length);
        console.log("Mobile navigation found:", mobileNav.length);
        
        // If we have the header navigation, inspect its structure
        if (headerNav.length) {
            const menuItems = headerNav.find('> ul > li');
            const itemsWithChildren = headerNav.find('li.menu-item-has-children');
            const dropdowns = headerNav.find('.sub-menu');
            
            console.log("Menu items in header navigation:", menuItems.length);
            console.log("Items with children:", itemsWithChildren.length);
            console.log("Dropdown menus:", dropdowns.length);
            
            // Log the text of each menu item with children
            itemsWithChildren.each(function() {
                console.log("Menu item with children:", $(this).find('> a').text().trim());
            });
            
            // Check for specific menu items we're interested in
            console.log("Shop menu item found:", headerNav.find('li:contains("Shop")').length);
            console.log("Lumanari menu item found:", headerNav.find('li:contains("Lumanari")').length);
            console.log("Tarot menu item found:", headerNav.find('li:contains("Tarot")').length);
        }
        
        // Check all CSS that might be affecting dropdowns
        const computedStyle = getComputedStyle(document.documentElement);
        console.log("CSS Variables potentially affecting dropdowns:", {
            "--qodef-header-height": computedStyle.getPropertyValue('--qodef-header-height'),
            "--qodef-header-dropdown-top-offset": computedStyle.getPropertyValue('--qodef-header-dropdown-top-offset')
        });
    }
    
    // Fix the theme's menu dropdowns
    function fixThemeMenu() {
        console.log("Fixing theme menu dropdowns...");
        
        // 1. Add CSS to ensure dropdowns are visible on hover
        const styleTag = document.createElement('style');
        styleTag.textContent = `
            /* Force dropdowns to be visible on hover */
            .qodef-header-navigation > ul > li.menu-item-has-children:hover > .sub-menu,
            .qodef-header-navigation > ul > li.menu-item-has-children.qodef--hovered > .sub-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                transform: translateY(0) !important;
                pointer-events: auto !important;
                z-index: 999 !important;
            }
            
            /* Ensure header is visible */
            .qodef-header-navigation,
            #qodef-page-header,
            .qodef-header-standard--right .qodef-header-navigation-area {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                overflow: visible !important;
            }
            
            /* Adjust dropdown positioning if needed */
            .qodef-header-navigation .sub-menu {
                top: 100% !important;
                left: 0 !important;
            }
        `;
        document.head.appendChild(styleTag);
        
        console.log("Applied CSS fixes for menu dropdowns");
        
        // 2. Fix menu item hover functionality
        const menuItems = $('.qodef-header-navigation > ul > li.menu-item-has-children');
        
        console.log("Found menu items with children:", menuItems.length);
        
        menuItems.each(function() {
            const menuItem = $(this);
            const dropdown = menuItem.find('.sub-menu').first();
            const menuText = menuItem.find('> a').text().trim();
            
            console.log("Processing menu item:", menuText);
            
            // Remove any existing event handlers
            menuItem.off('mouseenter mouseleave click');
            
            // Add mouseenter handler
            menuItem.on('mouseenter', function() {
                console.log("Mouse entered:", menuText);
                
                // Add hover class
                menuItem.addClass('qodef--hovered');
                
                // Force dropdown to be visible
                dropdown.css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'transform': 'translateY(0)',
                    'pointer-events': 'auto',
                    'z-index': '999'
                });
                
                // Add start class if theme uses it
                if (!dropdown.hasClass('qodef-drop-down--start')) {
                    dropdown.addClass('qodef-drop-down--start');
                }
            });
            
            // Add mouseleave handler
            menuItem.on('mouseleave', function() {
                console.log("Mouse left:", menuText);
                
                // Delay hiding to allow moving to submenu
                setTimeout(function() {
                    if (!menuItem.is(':hover') && !dropdown.is(':hover')) {
                        menuItem.removeClass('qodef--hovered');
                        
                        if (!menuItem.hasClass('qodef--touched')) {
                            dropdown.css({
                                'display': '',
                                'visibility': '',
                                'opacity': '',
                                'transform': '',
                                'pointer-events': '',
                                'z-index': ''
                            });
                            
                            dropdown.removeClass('qodef-drop-down--start');
                        }
                    }
                }, 200);
            });
            
            // Add click handler for mobile
            menuItem.on('click', function(e) {
                if ($(window).width() <= 1024) {
                    e.preventDefault();
                    
                    console.log("Mobile click on:", menuText);
                    
                    menuItem.addClass('qodef--touched');
                    dropdown.css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1',
                        'transform': 'translateY(0)',
                        'pointer-events': 'auto',
                        'z-index': '999'
                    });
                    
                    dropdown.addClass('qodef-drop-down--start');
                }
            });
        });
    }
    
    // Run the menu fix
    function init() {
        debugMenuStructure();
        fixThemeMenu();
    }
    
    // Run on document ready
    $(document).ready(function() {
        console.log("Document ready - fixing theme menu");
        init();
        
        // Run again after short delays
        setTimeout(init, 500);
        setTimeout(init, 1500);
    });
    
    // Run on window load
    $(window).on('load', function() {
        init();
    });
    
    // Run on resize
    $(window).on('resize', function() {
        init();
    });
    
    // Set interval for the first 10 seconds
    let interval = setInterval(init, 2000);
    setTimeout(function() {
        clearInterval(interval);
    }, 10000);
    
})(jQuery); 