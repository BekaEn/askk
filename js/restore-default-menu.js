(function($) {
    'use strict';
    
    // Function to restore the default theme menu
    function restoreDefaultMenu() {
        console.log("Attempting to restore default theme menu...");
        
        // 1. Look for any custom styling that might be hiding the theme's menu
        const styleTag = document.createElement('style');
        styleTag.textContent = `
            /* Restore visibility for theme's menu elements */
            .qodef-header-navigation,
            .qodef-header-navigation .menu-item,
            .qodef-header-navigation .sub-menu,
            .qodef-header-navigation ul,
            .qodef-header-navigation li,
            .qodef-header-navigation a,
            header .qodef-header-navigation,
            .qodef-dropdown,
            .qodef-mobile-header-navigation,
            .qodef-mobile-header {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
                overflow: visible !important;
            }
            
            /* Additional override for potential hiding of menu */
            header nav,
            nav.qodef-header-navigation,
            .qodef-header-standard--right .qodef-header-navigation-area {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Fix any custom menu that might be overlapping */
            .custom-menu-container {
                display: none !important;
            }
        `;
        document.head.appendChild(styleTag);
        
        console.log("Applied CSS overrides to restore menu visibility");
        
        // 2. Re-enable the default theme's menu events
        $('.qodef-header-navigation > ul > li.menu-item-has-children').each(function() {
            const menuItem = $(this);
            const dropdown = menuItem.find('.sub-menu').first();
            
            console.log("Found theme menu item with dropdown:", menuItem.text().trim());
            
            // Remove any custom event handlers that might interfere
            menuItem.off('mouseenter mouseleave click');
            
            // Add proper hover events
            menuItem.on('mouseenter', function() {
                dropdown.addClass('qodef-drop-down--start');
                dropdown.css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1',
                    'transform': 'translateY(0)',
                    'pointer-events': 'auto'
                });
                console.log("Hover added to:", menuItem.text().trim());
            });
            
            menuItem.on('mouseleave', function() {
                setTimeout(function() {
                    if (!menuItem.is(':hover')) {
                        dropdown.removeClass('qodef-drop-down--start');
                        dropdown.css({
                            'display': '',
                            'visibility': '',
                            'opacity': '',
                            'transform': '',
                            'pointer-events': ''
                        });
                    }
                }, 300);
            });
        });
        
        // 3. Check if we're finding the menu items and debug information
        console.log("Menu structure detected:", {
            headerNav: $('.qodef-header-navigation').length,
            menuItems: $('.qodef-header-navigation > ul > li').length,
            dropdowns: $('.qodef-header-navigation .sub-menu').length
        });
    }
    
    // Function to run on various events
    function init() {
        restoreDefaultMenu();
    }
    
    // Run on document ready
    $(document).ready(function() {
        console.log("Document ready - restoring default menu");
        init();
        
        // Run again after delays
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