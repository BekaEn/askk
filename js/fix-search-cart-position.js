(function($) {
    'use strict';
    
    // Function to fix search and cart widgets positioning
    function fixSearchCartPosition() {
        console.log("Fixing search and cart icons positioning...");
        
        // FORCE CORRECT HEADER LAYOUT FIRST
        const layoutStyle = document.createElement('style');
        layoutStyle.textContent = `
            /* Force correct layout structure */
            #qodef-page-header-inner {
                display: grid !important;
                grid-template-columns: 1fr auto 1fr !important;
                align-items: center !important;
                justify-items: stretch !important;
                width: 100% !important;
            }
            
            /* Left column: Navigation */
            .qodef-header-navigation-area {
                grid-column: 1 !important;
                justify-self: flex-start !important;
            }
            
            /* Center column: Logo */
            .qodef-header-logo-link {
                grid-column: 2 !important;
                justify-self: center !important;
            }
            
            /* Right column: Widget holder */
            .qodef-widget-holder {
                grid-column: 3 !important;
                justify-self: flex-end !important;
                display: flex !important;
                justify-content: flex-end !important;
                align-items: center !important;
            }
            
            /* Ensure icons are visible */
            .qodef-search-opener, 
            .qodef-woo-dropdown-cart {
                opacity: 1 !important;
                visibility: visible !important;
            }
        `;
        document.head.appendChild(layoutStyle);
        
        // Find widget holder
        let $widgetHolder = $('.qodef-widget-holder');
        
        // If widget holder doesn't exist, create it 
        if ($widgetHolder.length === 0) {
            console.log("Creating missing widget holder");
            const $headerInner = $('#qodef-page-header-inner');
            if ($headerInner.length > 0) {
                $headerInner.append('<div class="qodef-widget-holder" style="grid-column: 3; display: flex; justify-content: flex-end;"></div>');
                $widgetHolder = $('.qodef-widget-holder');
            }
        }
        
        // FORCE PLACEMENT - First detach and reattach elements in correct order
        const $headerInner = $('#qodef-page-header-inner');
        const $navArea = $('.qodef-header-navigation-area');
        const $logo = $('.qodef-header-logo-link');
        
        if ($headerInner.length && $navArea.length && $logo.length && $widgetHolder.length) {
            // Detach all elements
            $navArea.detach();
            $logo.detach();
            $widgetHolder.detach();
            
            // Clear any existing content
            $headerInner.empty();
            
            // Add elements in correct order
            $headerInner.append($navArea);
            $headerInner.append($logo);
            $headerInner.append($widgetHolder);
            
            console.log("Forced correct element order in header");
            
            // Apply grid column attributes
            $navArea.css('grid-column', '1');
            $logo.css('grid-column', '2');
            $widgetHolder.css('grid-column', '3');
        }
        
        // Find search and cart widgets
        const $searchOpener = $('.qodef-search-opener').closest('.widget');
        const $cartWidget = $('.qodef-woo-dropdown-cart').closest('.widget');
        
        // Move widgets to widget holder if they exist but are in the wrong place
        if ($widgetHolder.length > 0) {
            // Move search widget to widget holder
            if ($searchOpener.length > 0 && !$searchOpener.closest('.qodef-widget-holder').length) {
                console.log("Moving search widget to correct position");
                $searchOpener.appendTo($widgetHolder);
            }
            
            // Move cart widget to widget holder
            if ($cartWidget.length > 0 && !$cartWidget.closest('.qodef-widget-holder').length) {
                console.log("Moving cart widget to correct position");
                $cartWidget.appendTo($widgetHolder);
            }
        }
        
        // If search opener doesn't exist at all, create it
        if ($('.qodef-search-opener').length === 0 && $widgetHolder.length > 0) {
            console.log("Creating missing search opener");
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
        
        // If cart widget doesn't exist at all, create it
        if ($('.qodef-woo-dropdown-cart').length === 0 && $widgetHolder.length > 0) {
            console.log("Creating missing cart widget");
            $widgetHolder.append(`
                <div class="widget qodef-woo-dropdown-cart-widget">
                    <div class="qodef-woo-dropdown-cart qodef-m">
                        <a class="qodef-m-opener" href="/cart/" title="Cart">
                            <span class="qodef-m-opener-icon">
                                <svg class="qodef-svg--cart" xmlns="http://www.w3.org/2000/svg" width="19.656" height="19.546" viewBox="0 0 19.656 19.546">
                                    <path d="M10.688,14a.359.359,0,0,1,.248.1A.341.341,0,0,1,11,14.35v3.3a.341.341,0,0,1-.69.248.359.359,0,0,1-.248.1H7.969a.359.359,0,0,1-.248-.1A.341.341,0,0,1,7.656,17.6v-3.3a.341.341,0,0,1,.065-.248.359.359,0,0,1,.248-.1Z"/>
                                </svg>
                            </span>
                            <span class="qodef-m-opener-count">0</span>
                        </a>
                    </div>
                </div>
            `);
        }
        
        // Get references to any icons that might be in incorrect places
        const $wrongSearchIcons = $('.qodef-search-opener').not($widgetHolder.find('.qodef-search-opener'));
        const $wrongCartIcons = $('.qodef-woo-dropdown-cart').not($widgetHolder.find('.qodef-woo-dropdown-cart'));
        
        // Hide any incorrect icons
        if ($wrongSearchIcons.length > 0) {
            console.log("Hiding incorrectly placed search icons");
            $wrongSearchIcons.hide();
        }
        
        if ($wrongCartIcons.length > 0) {
            console.log("Hiding incorrectly placed cart icons");
            $wrongCartIcons.hide();
        }
        
        // Add styles to ensure proper display
        const style = document.createElement('style');
        style.textContent = `
            /* Widget holder positioning */
            .qodef-widget-holder {
                grid-column: 3 !important;
                display: flex !important;
                justify-content: flex-end !important;
                align-items: center !important;
                visibility: visible !important;
                opacity: 1 !important;
                min-height: 40px !important;
                margin-left: auto !important;
            }
            
            /* Icon widget styling */
            .qodef-widget-holder .widget {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                margin: 0 6px !important;
            }
            
            /* Icon specific styling */
            .qodef-search-opener, 
            .qodef-woo-dropdown-cart .qodef-m-opener {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 5px !important;
                color: #000 !important;
                position: relative !important;
            }
            
            /* Icons size */
            .qodef-search-opener svg, 
            .qodef-woo-dropdown-cart svg {
                width: 22px !important;
                height: 22px !important;
            }
            
            /* Cart counter styling */
            .qodef-m-opener-count {
                font-size: 10px !important;
                background: #e25c2c !important;
                color: white !important;
                border-radius: 50% !important;
                width: 18px !important;
                height: 18px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                position: absolute !important;
                top: -5px !important;
                right: -5px !important;
            }
            
            /* Hide any search/cart icons outside of widget holder */
            .qodef-header-navigation-area .qodef-search-opener,
            .qodef-header-navigation-area .qodef-woo-dropdown-cart {
                display: none !important;
            }
        `;
        document.head.appendChild(style);
        
        // Make sure widget holder is properly displayed with !important rules
        $widgetHolder.attr('style', 'display: flex !important; justify-content: flex-end !important; align-items: center !important; visibility: visible !important; opacity: 1 !important; grid-column: 3 !important; margin-left: auto !important;');
        
        // Force correct order of search and cart
        const $searchInHolder = $widgetHolder.find('.qodef-search-opener-widget');
        const $cartInHolder = $widgetHolder.find('.qodef-woo-dropdown-cart-widget');
        
        if ($searchInHolder.length && $cartInHolder.length) {
            // Always put search first, then cart
            $searchInHolder.detach();
            $cartInHolder.detach();
            $widgetHolder.append($searchInHolder);
            $widgetHolder.append($cartInHolder);
        }
    }
    
    // Run function on document ready
    $(document).ready(function() {
        fixSearchCartPosition();
    });
    
    // Also run when page is fully loaded
    $(window).on('load', function() {
        setTimeout(fixSearchCartPosition, 100);
    });
    
    // Run periodically to ensure it stays fixed
    setInterval(fixSearchCartPosition, 1000);
    
})(jQuery); 