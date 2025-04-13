// Immediately Invoked Function Expression to isolate our code
(function($) {
    'use strict';

    // Function to force apply styles to dropdowns
    function forceMegaMenuStyles() {
        console.log("Forcing mega menu styles...");
        
        // Find the main menu items we want to target
        const menuItems = {
            shop: $('.qodef-header-navigation > ul > li.menu-item-has-children:contains("Shop")'),
            lumanari: $('.qodef-header-navigation > ul > li.menu-item-has-children:contains("Lumanari")'),
            tarot: $('.qodef-header-navigation > ul > li.menu-item-has-children:contains("Tarot")')
        };
        
        console.log("Menu items found:", {
            shop: menuItems.shop.length,
            lumanari: menuItems.lumanari.length,
            tarot: menuItems.tarot.length
        });
        
        // Apply styles to each menu item's dropdown
        $.each(menuItems, function(name, menuItem) {
            if (!menuItem.length) return;
            
            const dropdown = menuItem.find('.sub-menu');
            
            // Check if dropdown exists
            if (dropdown.length) {
                console.log("Applying styles to " + name + " dropdown");
                
                // Force dropdown to be visible on hover
                menuItem.on('mouseenter', function() {
                    console.log(name + " hover triggered");
                    
                    dropdown.css({
                        'display': 'block !important',
                        'visibility': 'visible !important',
                        'opacity': '1 !important',
                        'transform': 'translateY(0) !important',
                        'pointer-events': 'auto !important',
                        'z-index': '999 !important'
                    });
                    
                    // Add custom class if not present
                    if (!dropdown.hasClass('qodef-drop-down--start')) {
                        dropdown.addClass('qodef-drop-down--start');
                    }
                });
                
                menuItem.on('mouseleave', function() {
                    console.log(name + " hover out");
                    
                    // Allow some time before hiding the dropdown
                    setTimeout(function() {
                        if (!menuItem.is(':hover')) {
                            dropdown.css({
                                'display': '',
                                'visibility': '',
                                'opacity': '',
                                'transform': '',
                                'pointer-events': '',
                                'z-index': ''
                            });
                        }
                    }, 200);
                });
                
                // Also trigger dropdown visibility on click for mobile devices
                menuItem.on('click', function(e) {
                    if ($(window).width() > 1024) return;
                    e.preventDefault();
                    
                    console.log(name + " click triggered on mobile");
                    
                    dropdown.css({
                        'display': 'block !important',
                        'visibility': 'visible !important',
                        'opacity': '1 !important',
                        'transform': 'translateY(0) !important',
                        'pointer-events': 'auto !important',
                        'z-index': '999 !important'
                    });
                });
            }
        });
    }
    
    // Run our function when the document is ready
    $(document).ready(function() {
        console.log("Document ready - initializing dropdown fix");
        forceMegaMenuStyles();
        
        // Also run a bit after the page has loaded to ensure all elements are there
        setTimeout(function() {
            forceMegaMenuStyles();
        }, 500);
        
        // And again after more time has passed
        setTimeout(function() {
            forceMegaMenuStyles();
        }, 1500);
    });
    
    // Handle window resize - reinitialize
    $(window).on('resize', function() {
        forceMegaMenuStyles();
    });
    
    // Handle scrolling - menus sometimes get reinitialized on scroll
    $(window).on('scroll', function() {
        forceMegaMenuStyles();
    });
    
    // Handle clicks on menu items since they might trigger changes
    $('.qodef-header-navigation > ul > li').on('click', function() {
        setTimeout(forceMegaMenuStyles, 100);
    });
    
    // Set an interval for the first 10 seconds to force the styles
    let styleInterval = setInterval(function() {
        forceMegaMenuStyles();
    }, 2000);
    
    // Clear the interval after 10 seconds
    setTimeout(function() {
        clearInterval(styleInterval);
    }, 10000);
    
})(jQuery); 