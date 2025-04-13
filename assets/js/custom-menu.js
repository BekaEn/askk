/**
 * Custom Menu JavaScript
 * Handles menu interactions, especially for mobile
 */

(function($) {
    'use strict';
    
    // Document ready
    $(document).ready(function() {
        // Mobile menu toggle button
        $('.custom-menu-toggle').on('click', function() {
            $(this).toggleClass('active');
            $('.custom-menu-items').toggleClass('active');
        });
        
        // Mobile dropdown toggle
        $('.custom-menu-items > ul > li.menu-item-has-children > a').on('click', function(e) {
            // Only apply for mobile view
            if ($(window).width() <= 767) {
                e.preventDefault();
                var $parent = $(this).parent();
                $parent.toggleClass('active');
                $parent.find('> ul.sub-menu').slideToggle(300);
            }
        });
        
        // Handle window resize
        $(window).on('resize', function() {
            if ($(window).width() > 767) {
                // Reset mobile menu states when switching to desktop
                $('.custom-menu-toggle').removeClass('active');
                $('.custom-menu-items').removeClass('active');
                $('.custom-menu-items > ul > li').removeClass('active');
                $('.custom-menu-items > ul > li > ul.sub-menu').css('display', '');
            }
        });
        
        // Add dropdown hover effects for desktop
        $('.custom-menu-items > ul > li.menu-item-has-children').hover(
            function() {
                if ($(window).width() > 767) {
                    $(this).addClass('hover');
                }
            },
            function() {
                if ($(window).width() > 767) {
                    $(this).removeClass('hover');
                }
            }
        );
    });
    
})(jQuery);
