(function($) {
    'use strict';
    
    // Extremely simple function to make menus visible
    function emergencyMenuFix() {
        console.log("Applying emergency menu fix...");
        
        // Add CSS directly to head with !important for all properties
        const style = document.createElement('style');
        style.textContent = `
            /* Force header to be visible at all times */
            #qodef-page-header {
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
            }
            
            /* Three-column grid layout - FIXED */
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
            
            /* Force navigation elements to be visible */
            .qodef-header-navigation-area,
            .qodef-header-navigation,
            .qodef-header-navigation > ul,
            .qodef-header-navigation > ul > li,
            .qodef-header-navigation > ul > li > a,
            .qodef-header-standard--right .qodef-header-navigation-area {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
                position: relative !important;
                transform: none !important;
                pointer-events: auto !important;
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
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* Widget holder in THIRD column (right) */
            .qodef-widget-holder {
                grid-column: 3 !important;
                display: flex !important;
                justify-content: flex-end !important;
                align-items: center !important;
                visibility: visible !important;
                opacity: 1 !important;
                padding-left: 20px !important;
                margin-left: auto !important;
            }
            
            /* Fix search and cart icons */
            .qodef-widget-holder .widget,
            .qodef-widget-holder .qodef-woo-dropdown-cart-widget,
            .qodef-widget-holder .qodef-search-opener-widget {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                visibility: visible !important;
                opacity: 1 !important;
                margin: 0 5px !important;
            }
            
            /* Icon sizing and spacing */
            .qodef-woo-dropdown-cart .qodef-m-opener,
            .qodef-search-opener {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 8px !important;
                position: relative !important;
            }
            
            /* Icon SVG sizing */
            .qodef-woo-dropdown-cart .qodef-m-opener svg,
            .qodef-search-opener svg {
                width: 22px !important;
                height: 22px !important;
            }
            
            /* Control logo image size */
            .qodef-header-logo-link img {
                max-width: 150px !important;
                max-height: 80px !important;
                width: auto !important;
                height: auto !important;
                object-fit: contain !important;
                visibility: visible !important;
                opacity: 1 !important;
                display: block !important;
                margin: 0 auto !important;
            }
            
            /* Menu items */
            .qodef-header-navigation > ul {
                display: flex !important;
                flex-wrap: nowrap !important;
                margin: 0 !important;
                padding: 0 !important;
                list-style: none !important;
                justify-content: flex-start !important;
            }
            
            .qodef-header-navigation > ul > li {
                display: inline-block !important;
                margin: 0 15px 0 0 !important;
                position: relative !important;
            }
            
            .qodef-header-navigation > ul > li > a {
                display: block !important;
                padding: 5px 0 !important;
                position: relative !important;
                font-weight: 500 !important;
            }
            
            /* Dropdown menus */
            .qodef-header-navigation .sub-menu {
                position: absolute !important;
                top: 100% !important;
                left: 0 !important;
                z-index: 9999 !important;
                background: white !important;
                border: 1px solid #eee !important;
                padding: 10px !important;
                min-width: 200px !important;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
            }
            
            /* Force dropdowns to be visible on hover */
            .qodef-header-navigation > ul > li.menu-item-has-children:hover > .sub-menu,
            .qodef-header-navigation > ul > li:hover > .sub-menu {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                transform: translateY(0) !important;
                pointer-events: auto !important;
            }
            
            /* Force search and cart to ALWAYS be in 3rd column */
            .qodef-search-opener-widget {
                grid-column: 3 !important;
                margin-left: auto !important;
            }
            
            .qodef-woo-dropdown-cart-widget {
                grid-column: 3 !important;
                margin-left: auto !important;
            }
            
            /* Fix any search/cart that might be in wrong column */
            .qodef-header-navigation-area .qodef-search-opener-widget,
            .qodef-header-navigation-area .qodef-woo-dropdown-cart-widget {
                display: none !important; /* Hide if in wrong place */
            }
            
            /* Remove any margins that might push things out of place */
            .qodef-header-navigation > ul > li:last-child {
                margin-right: 0 !important;
            }
            
            /* Mobile view */
            @media (max-width: 1024px) {
                #qodef-page-header-inner {
                    display: flex !important;
                    flex-direction: column !important;
                    text-align: center !important;
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
                
                /* Force the items to be centered on mobile */
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
            .qodef-header-navigation > ul,
            .qodef-header-navigation > ul > li {
                transition: none !important;
                animation: none !important;
            }
        `;
        document.head.appendChild(style);
        
        // Debug info
        console.log("Header status:");
        console.log("- Header exists: " + ($('#qodef-page-header').length > 0 ? "YES" : "NO"));
        console.log("- Header inner exists: " + ($('#qodef-page-header-inner').length > 0 ? "YES" : "NO"));
        console.log("- Navigation exists: " + ($('.qodef-header-navigation').length > 0 ? "YES" : "NO"));
        console.log("- Menu items count: " + $('.qodef-header-navigation > ul > li').length);
        console.log("- Logo exists: " + ($('.qodef-header-logo-link').length > 0 ? "YES" : "NO"));
        console.log("- Widget holder exists: " + ($('.qodef-widget-holder').length > 0 ? "YES" : "NO"));
        
        // Check for cart and search icons
        const hasCart = $('.qodef-woo-dropdown-cart').length > 0;
        const hasSearch = $('.qodef-search-opener').length > 0;
        console.log("- Cart icon exists: " + (hasCart ? "YES" : "NO"));
        console.log("- Search icon exists: " + (hasSearch ? "YES" : "NO"));
        
        // Make sure widget holder exists
        if ($('.qodef-widget-holder').length === 0) {
            console.log("Widget holder missing - creating one");
            if ($('#qodef-page-header-inner').length > 0) {
                $('#qodef-page-header-inner').append('<div class="qodef-widget-holder" style="grid-column: 3; display: flex; justify-content: flex-end; margin-left: auto;"></div>');
            }
        }
        
        // Move any misplaced search and cart widgets to the widget holder
        $('.qodef-search-opener-widget, .qodef-woo-dropdown-cart-widget').each(function() {
            const $widget = $(this);
            if (!$widget.closest('.qodef-widget-holder').length) {
                console.log("Moving misplaced widget to widget holder");
                $widget.appendTo('.qodef-widget-holder');
            }
        });
        
        // If widget holder exists but without icons, we'll add them
        if ($('.qodef-widget-holder').length > 0) {
            if (!hasCart) {
                console.log("Adding cart icon");
                $('.qodef-widget-holder').append(`
                    <div class="widget qodef-woo-dropdown-cart-widget">
                        <div class="qodef-woo-dropdown-cart qodef-m">
                            <a class="qodef-m-opener" href="/cart/" title="Cart">
                                <span class="qodef-m-opener-icon">
                                    <svg class="qodef-svg--cart" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19.656 19.546">
                                        <path d="M10.688,14a.359.359,0,0,1,.248.1A.341.341,0,0,1,11,14.35v3.3a.341.341,0,0,1-.69.248.359.359,0,0,1-.248.1H7.969a.359.359,0,0,1-.248-.1A.341.341,0,0,1,7.656,17.6v-3.3a.341.341,0,0,1,.065-.248.359.359,0,0,1,.248-.1Z"/>
                                    </svg>
                                </span>
                                <span class="qodef-m-opener-count">0</span>
                            </a>
                        </div>
                    </div>
                `);
            }
            
            if (!hasSearch) {
                console.log("Adding search icon");
                $('.qodef-widget-holder').append(`
                    <div class="widget qodef-search-opener-widget">
                        <a href="javascript:void(0)" class="qodef-search-opener">
                            <span class="qodef-search-opener-inner">
                                <svg class="qodef-svg--search" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 18.759 18.75">
                                    <path d="M19.3,18.6l-4.531-4.531a.671.671,0,0,1-.156-.246,7.5,7.5,0,1,1,.949-.949.639.639,0,0,1,.246.156L20.339,17.56A1.242,1.242,0,0,1,19.3,18.6Z"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                `);
            }
        }
        
        // Force proper DOM order (Logo in middle, Navigation on left, Widgets on right)
        const $header = $('#qodef-page-header-inner');
        if ($header.length > 0) {
            const $navArea = $('.qodef-header-navigation-area');
            const $logo = $('.qodef-header-logo-link');
            const $widgets = $('.qodef-widget-holder');
            
            // Only rearrange if all three elements exist
            if ($navArea.length > 0 && $logo.length > 0 && $widgets.length > 0) {
                // Check if order is wrong
                if (!($navArea.index() < $logo.index() && $logo.index() < $widgets.index())) {
                    console.log("Fixing DOM order");
                    $navArea.detach();
                    $logo.detach();
                    $widgets.detach();
                    
                    // Empty header first to ensure clean placement
                    $header.empty();
                    
                    // Add in correct order
                    $header.append($navArea);
                    $header.append($logo);
                    $header.append($widgets);
                    
                    // Apply grid column CSS forcefully
                    $navArea.css('grid-column', '1');
                    $logo.css('grid-column', '2');
                    $widgets.css('grid-column', '3');
                }
            }
        }
        
        // Make sure widget holder displays correctly with !important flags
        $('.qodef-widget-holder').attr('style', 'display: flex !important; justify-content: flex-end !important; align-items: center !important; visibility: visible !important; opacity: 1 !important; grid-column: 3 !important;  margin-left: auto !important;');
        
        // Hide any icons that might appear in the wrong place
        $('.qodef-header-navigation-area .qodef-search-opener, .qodef-header-navigation-area .qodef-woo-dropdown-cart').hide();
        
        // Observe header visibility
        if ($('#qodef-page-header').length > 0) {
            const headerElement = $('#qodef-page-header')[0];
            
            // Create a MutationObserver to monitor style changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'style' || 
                        mutation.attributeName === 'class') {
                        
                        const computedStyle = getComputedStyle(headerElement);
                        const isHidden = 
                            computedStyle.display === 'none' ||
                            computedStyle.visibility === 'hidden' ||
                            computedStyle.opacity === '0';
                        
                        if (isHidden) {
                            console.warn("Header was hidden! Restoring visibility...");
                            $(headerElement).css({
                                'display': 'block !important',
                                'visibility': 'visible !important',
                                'opacity': '1 !important'
                            });
                        }
                    }
                });
            });
            
            // Start observing
            observer.observe(headerElement, { 
                attributes: true, 
                attributeFilter: ['style', 'class'] 
            });
            
            // Also check every second
            setInterval(function() {
                const computedStyle = getComputedStyle(headerElement);
                if (computedStyle.display === 'none' || 
                    computedStyle.visibility === 'hidden' || 
                    computedStyle.opacity === '0') {
                    
                    console.warn("Header hidden detected in interval check! Restoring...");
                    $(headerElement).css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1'
                    });
                }
            }, 1000);
        } else {
            console.error("Header element not found in DOM!");
            
            // If header is missing entirely, create a basic one
            if ($('#qodef-page-header').length === 0) {
                $('body').prepend(`
                    <header id="qodef-page-header" style="display:block !important; visibility:visible !important; opacity:1 !important;">
                        <div id="qodef-page-header-inner" style="display:grid !important; grid-template-columns:1fr auto 1fr !important; align-items:center !important; padding:10px 30px !important;">
                            <nav class="qodef-header-navigation-area">
                                <div class="qodef-header-navigation">
                                    <ul>
                                        <li><a href="${window.location.origin}">Home</a></li>
                                    </ul>
                                </div>
                            </nav>
                            <div class="qodef-header-logo-link">
                                <a href="${window.location.origin}">
                                    <img src="${window.location.origin}/wp-content/uploads/logo.png" alt="Logo">
                                </a>
                            </div>
                            <div class="qodef-widget-holder">
                                <div class="widget qodef-search-opener-widget">
                                    <a href="javascript:void(0)" class="qodef-search-opener">
                                        <span class="qodef-search-opener-inner">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 18.759 18.75">
                                                <path d="M19.3,18.6l-4.531-4.531a.671.671,0,0,1-.156-.246,7.5,7.5,0,1,1,.949-.949.639.639,0,0,1,.246.156L20.339,17.56A1.242,1.242,0,0,1,19.3,18.6Z"/>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                                <div class="widget qodef-woo-dropdown-cart-widget">
                                    <div class="qodef-woo-dropdown-cart qodef-m">
                                        <a class="qodef-m-opener" href="/cart/" title="Cart">
                                            <span class="qodef-m-opener-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19.656 19.546">
                                                    <path d="M10.688,14a.359.359,0,0,1,.248.1A.341.341,0,0,1,11,14.35v3.3a.341.341,0,0,1-.69.248.359.359,0,0,1-.248.1H7.969a.359.359,0,0,1-.248-.1A.341.341,0,0,1,7.656,17.6v-3.3a.341.341,0,0,1,.065-.248.359.359,0,0,1,.248-.1Z"/>
                                                </svg>
                                            </span>
                                            <span class="qodef-m-opener-count">0</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                `);
                console.log("Created emergency header replacement");
            }
        }
    }
    
    // Run on document ready
    $(document).ready(function() {
        console.log("Document ready - applying emergency header fix");
        emergencyMenuFix();
    });
    
    // Run when all resources are loaded
    $(window).on('load', function() {
        console.log("Window loaded - reapplying emergency header fix");
        emergencyMenuFix();
    });
    
    // Run after short delay
    setTimeout(function() {
        console.log("Delayed check - reapplying emergency header fix");
        emergencyMenuFix();
    }, 1000);
    
    // Run again after content likely loaded
    setTimeout(function() {
        console.log("Final check - reapplying emergency header fix");
        emergencyMenuFix();
    }, 3000);
    
    // Also run periodically to ensure the header remains fixed
    setInterval(function() {
        emergencyMenuFix();
    }, 5000);
    
})(jQuery); 