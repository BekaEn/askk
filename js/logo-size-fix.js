(function($) {
    'use strict';
    
    function fixLogoSize() {
        console.log("Applying logo size fix...");
        
        // Add CSS to control logo size
        const styleEl = document.createElement('style');
        styleEl.textContent = `
            /* Force logo size control */
            .qodef-header-logo-link {
                max-width: 150px !important;
                max-height: 80px !important;
                width: auto !important;
                height: auto !important;
                overflow: visible !important;
            }
            
            /* Force logo image size */
            .qodef-header-logo-link img,
            .qodef-header-logo-link a img,
            #qodef-page-header .qodef-header-logo-link img,
            #qodef-page-header-inner .qodef-header-logo-link img,
            .qodef-header-logo-image,
            img.qodef-header-logo-image {
                max-width: 150px !important;
                max-height: 80px !important;
                width: auto !important;
                height: auto !important;
                object-fit: contain !important;
                display: block !important;
            }
            
            /* Even more specific selectors */
            body .qodef-header-logo-link img,
            body #qodef-page-header img {
                max-width: 150px !important;
                max-height: 80px !important;
            }
        `;
        document.head.appendChild(styleEl);
        
        // Try to find the logo image
        const logoElements = $('.qodef-header-logo-link, .qodef-header-logo-link img');
        
        if (logoElements.length) {
            console.log("Logo elements found, applying size constraints directly");
            
            // Apply direct styles to logo elements
            logoElements.css({
                'maxWidth': '150px',
                'maxHeight': '80px',
                'width': 'auto',
                'height': 'auto'
            });
            
            // Specifically target images within the logo container
            $('.qodef-header-logo-link img').css({
                'maxWidth': '150px',
                'maxHeight': '80px',
                'width': 'auto',
                'height': 'auto',
                'objectFit': 'contain'
            });
            
            // Add attributes to ensure proper scaling
            $('.qodef-header-logo-link img').attr('width', '150').attr('height', 'auto');
        } else {
            console.log("Logo elements not found");
        }
        
        // Add resize handler to ensure logo stays properly sized
        $(window).on('resize', function() {
            $('.qodef-header-logo-link, .qodef-header-logo-link img').css({
                'maxWidth': '150px',
                'maxHeight': '80px',
                'width': 'auto',
                'height': 'auto'
            });
        });
    }
    
    // Run on document ready
    $(document).ready(function() {
        fixLogoSize();
    });
    
    // Run on load
    $(window).on('load', function() {
        fixLogoSize();
        
        // Apply again after short delay to catch any dynamic logo changes
        setTimeout(fixLogoSize, 500);
    });
    
})(jQuery); 