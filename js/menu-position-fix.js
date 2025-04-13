(function($) {
    'use strict';
    
    function fixMenuPositioning() {
        console.log("Applying simplified menu fix to ensure visibility...");
        
        // Create style element for our custom CSS
        const styleEl = document.createElement('style');
        styleEl.id = 'menu-position-fix-styles';
        styleEl.textContent = `
            /* Make elements visible first */
            #qodef-page-header,
            .qodef-header-navigation,
            .qodef-header-navigation > ul,
            .qodef-header-navigation > ul > li,
            .qodef-header-navigation > ul > li > a,
            .qodef-header-navigation-area,
            .qodef-header-logo-link {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Basic layout - no absolute positioning */
            #qodef-page-header-inner {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                padding: 0 30px !important;
            }
            
            /* Navigation on the left */
            .qodef-header-navigation-area {
                order: 1 !important;
                display: flex !important;
                justify-content: flex-start !important;
                flex: 1 !important;
                margin-right: 15px !important;
            }
            
            /* Logo in the center */
            .qodef-header-logo-link {
                order: 2 !important;
                flex: 0 0 auto !important;
                position: relative !important;
                left: 0 !important;
                transform: none !important;
            }
            
            /* Widget area on the right */
            .qodef-widget-holder {
                order: 3 !important;
                flex: 1 !important;
                display: flex !important;
                justify-content: flex-end !important;
                margin-left: 15px !important;
            }
            
            /* Menu items */
            .qodef-header-navigation > ul {
                display: flex !important;
                flex-direction: row !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            
            .qodef-header-navigation > ul > li {
                display: inline-block !important;
                float: none !important;
                margin: 0 15px 0 0 !important;
            }
            
            .qodef-header-navigation > ul > li > a {
                display: inline-block !important;
                padding: 5px 0 !important;
            }
            
            /* Make dropdowns appear below their parent items */
            .qodef-header-navigation > ul > li.menu-item-has-children > .sub-menu {
                top: 100% !important;
                left: 0 !important;
                display: none;
            }
            
            /* Show dropdown on hover */
            .qodef-header-navigation > ul > li.menu-item-has-children:hover > .sub-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Mobile adjustments */
            @media only screen and (max-width: 1024px) {
                #qodef-page-header-inner {
                    flex-direction: column !important;
                    padding: 15px !important;
                }
                
                .qodef-header-navigation-area {
                    order: 2 !important;
                    width: 100% !important;
                    margin: 15px 0 !important;
                }
                
                .qodef-header-logo-link {
                    order: 1 !important;
                }
                
                .qodef-widget-holder {
                    order: 3 !important;
                    width: 100% !important;
                    justify-content: center !important;
                    margin: 10px 0 0 !important;
                }
            }
        `;
        document.head.appendChild(styleEl);
        
        // Do not reorganize the DOM, just check for menu visibility
        if ($('.qodef-header-navigation').length) {
            console.log("Menu found, ensuring visibility with CSS only");
            
            // Check if menus are visible
            setTimeout(() => {
                if ($('.qodef-header-navigation').is(':visible')) {
                    console.log("Menu is now visible");
                } else {
                    console.log("Menu may still be hidden, applying additional fixes");
                    
                    // Add additional visibility fixes if menu still hidden
                    const additionalStyles = document.createElement('style');
                    additionalStyles.textContent = `
                        .qodef-header-navigation {
                            display: block !important;
                            visibility: visible !important;
                            opacity: 1 !important;
                            height: auto !important;
                            overflow: visible !important;
                            position: static !important;
                        }
                    `;
                    document.head.appendChild(additionalStyles);
                }
            }, 500);
        } else {
            console.log("Menu not found in the DOM");
        }
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        fixMenuPositioning();
        
        // Run again after delays to ensure everything has loaded
        setTimeout(fixMenuPositioning, 500);
        setTimeout(fixMenuPositioning, 1500);
    });
    
    // Also run on window resize
    $(window).on('resize', function() {
        fixMenuPositioning();
    });
    
    // Run on load
    $(window).on('load', function() {
        fixMenuPositioning();
    });
    
})(jQuery); 