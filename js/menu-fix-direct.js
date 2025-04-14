(function($) {
    'use strict';
    
    // Immediately hide all original menus and show only our custom menu
    const hideOriginalMenusImmediately = function() {
        // Hide all original navigation menus (but not our custom one)
        $('.qodef-header-navigation').not('#custom-horizontal-menu').hide();
    };
    
    // Run this immediately
    hideOriginalMenusImmediately();
    
    // Apply immediate inline style to hide all menus on script load
    const hideAllMenusImmediately = function() {
        // Use both inline styles and direct CSS property manipulation for maximum effect
        const inlineCSS = 'display:none !important; visibility:hidden !important; opacity:0 !important; height:0 !important; pointer-events:none !important;';
        $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').attr('style', inlineCSS).hide();
        
        // Purge any active menu classes to prevent theme's JavaScript from activating menus
        $('.menu-item-has-children, .menu-item--wide, .qodef-menu-item--wide').removeClass('qodef-menu-item--open qodef--opened hover-active menu-item-hover');
        
        // Also hide all original navigation menus (but not our custom one)
        hideOriginalMenusImmediately();
    };
    
    // Run this immediately on script load
    hideAllMenusImmediately();
    
    // Flag to prevent multiple rebuilds
    let menuRebuildInProgress = false;
    
    // Function to fix menu positioning and hover behavior
    function fixMenuPositioning() {
        // Add direct click handler for mega menu category items
        $(document).off('click', '.mega-menu-category-item').on('click', '.mega-menu-category-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const href = $(this).data('href');
            if (href && href !== '#' && href !== 'undefined') {
                window.location.href = href;
            }
        });
        
        // Aggressive cleanup of all events
        $(document).off('.menuFix');
        $('.qodef-header-navigation > ul > li').off();
        $('.qodef-header-navigation > ul > li > a').off();
        $('.qodef-header-navigation .sub-menu').off();
        $('.qodef-header-navigation .qodef-drop-down-second').off();
        
        // Debug all menu items
        $('.qodef-header-navigation > ul > li').each(function() {
            const $item = $(this);
            const text = $item.text().trim();
            const hasDropdown = $item.find('.sub-menu, .qodef-drop-down-second').length > 0;
            const classes = $item.attr('class') || '';
        });
        
        // First, aggressively close all submenus immediately
        hideAllMenusImmediately();
        
        // Get the header
        const $header = $('#qodef-page-header-inner');
        
        if ($header.length > 0) {
            // Direct manipulation of DOM
            const $navigation = $('.qodef-header-navigation');
            const $logo = $('.qodef-header-logo-link');
            const $widgets = $('.qodef-widget-holder');
            
            // Only proceed if all elements exist
            if ($navigation.length > 0 && $logo.length > 0 && $widgets.length > 0) {
                // Apply styles directly to elements
                $navigation.css({
                    'order': '1',
                    'float': 'left',
                    'text-align': 'left',
                    'margin-right': 'auto',
                    'margin-left': '100px',
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
                    'margin-left': 'auto',
                    'margin-right': '100px'
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
            $(this).hide().removeClass('hover-visible');
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
                
                // Add click handler for mega menu category items
                $menuItem.find('.mega-menu-category-item').each(function() {
                    const $categoryItem = $(this);
                    const hasChildren = $categoryItem.hasClass('mega-menu-category-item-with-children');
                    const href = $categoryItem.data('href');
                    
                    $categoryItem.off('click').on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Only redirect if there's a valid href and it's not a placeholder
                        if (href && href !== '#' && href !== 'undefined') {
                            window.location.href = href;
                        }
                    });
                    
                    // Add hover handler for categories with children
                    if (hasChildren) {
                        let isHovering = false;
                        
                        $categoryItem.off('mouseenter mouseleave').on({
                            mouseenter: function() {
                                isHovering = true;
                                const categoryId = $(this).data('category');
                                
                                // First hide everything
                                $('.mega-menu-subcategory-content, .mega-menu-subcategory-section').removeClass('active');
                                
                                // Remove active class from all categories and add to current
                                $('.mega-menu-category-item').removeClass('active');
                                $(this).addClass('active');
                                
                                // Get the current subcategory content
                                const $currentSubcategory = $('.mega-menu-subcategory-content[data-category="' + categoryId + '"]');
                                
                                if ($currentSubcategory.length) {
                                    // Show the subcategories container
                                    $('.mega-menu-subcategories').addClass('active');
                                    
                                    // Show only the current subcategory content
                                    $currentSubcategory.addClass('active').attr('aria-hidden', 'false');
                                    
                                    // Show the subcategory sections within this category
                                    $currentSubcategory.find('.mega-menu-subcategory-section').attr('aria-hidden', 'false');
                                }
                            },
                            mouseleave: function() {
                                isHovering = false;
                                setTimeout(function() {
                                    if (!isHovering) {
                                        $('.mega-menu-subcategories').removeClass('active');
                                        $('.mega-menu-subcategory-content, .mega-menu-subcategory-section').removeClass('active');
                                    }
                                }, 100);
                            }
                        });
                        
                        // Add hover handlers for subcategories container
                        $('.mega-menu-subcategories').off('mouseenter mouseleave').on({
                            mouseenter: function() {
                                isHovering = true;
                            },
                            mouseleave: function() {
                                isHovering = false;
                                setTimeout(function() {
                                    if (!isHovering) {
                                        $('.mega-menu-subcategories').removeClass('active');
                                        $('.mega-menu-subcategory-content, .mega-menu-subcategory-section').removeClass('active');
                                    }
                                }, 100);
                            }
                        });
                    }
                });

                if ($dropdown.length) {
                    const $innerContent = $dropdown.find('.qodef-drop-down-second-inner');
                    
                    // Apply hover handlers to both the menu item and the menu link
                    $menuItem.add($menuLink).on('mouseenter', function(e) {
                        // Close all other dropdowns first
                        $('.qodef-header-navigation > ul > li').not($menuItem).removeClass('hover-active');
                        $('.qodef-header-navigation > ul > li').not($menuItem).find('> .sub-menu, > .qodef-drop-down-second')
                            .not($dropdown).hide().removeClass('hover-visible');
                        
                        // Show current dropdown
                        $menuItem.addClass('hover-active');
                        
                        // Don't hide the menu if it's already visible
                        if (!$dropdown.hasClass('hover-visible')) {
                            $dropdown.addClass('hover-visible').show();
                            
                            // Handle special case for mega menus
                            if ($dropdown.hasClass('qodef-drop-down-second')) {
                                const $innerContent = $dropdown.find('.qodef-drop-down-second-inner');
                                
                                $innerContent.addClass('hover-visible').show();
                                
                                // Make all child elements visible
                                $innerContent.find('*').show();
                                
                                // Specifically target nested lists
                                $innerContent.find('ul').addClass('hover-visible').show();
                                
                                $innerContent.find('li').show();
                                
                                $innerContent.find('a').show();
                                
                                // Ensure deeper nested content is also visible
                                $innerContent.find('ul ul').addClass('hover-visible').show();
                            }
                        }
                        
                        e.stopPropagation();
                    });
                    
                    // Add more reliable mouse leave detection
                    let leaveTimer;
                    $menuItem.on('mouseleave', function(e) {
                        clearTimeout(leaveTimer);
                        
                        const relatedTarget = e.relatedTarget || e.toElement;
                        
                        if ($(relatedTarget).closest('.qodef-drop-down-second, .sub-menu').length || 
                            $(relatedTarget).closest('.qodef-header-navigation > ul > li').length) {
                            return;
                        }
                        
                        leaveTimer = setTimeout(function() {
                            if (!$('.qodef-header-navigation').filter(function() { return $(this).is(':hover'); }).length) {
                                $('.qodef-header-navigation > ul > li').removeClass('hover-active');
                                $('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second')
                                    .hide().removeClass('hover-visible');
                            }
                        }, 250);
                    });
                    
                    // Add special handling for the dropdown itself
                    $dropdown.off('mouseenter mouseleave').on({
                        mouseenter: function() {
                            clearTimeout(leaveTimer);
                            clearTimeout($menuItem.data('leaveTimerId'));
                            
                            Object.keys(globalTimers).forEach(function(key) {
                                clearTimeout(globalTimers[key]);
                            });
                            
                            $(this).addClass('hover-visible').show();
                            $menuItem.addClass('hover-active');
                        },
                        mouseleave: function() {
                            clearTimeout($menuItem.data('leaveTimerId'));
                            
                            var leaveTimerId = setTimeout(function() {
                                if (!$menuItem.is(':hover') && !$dropdown.is(':hover')) {
                                    if (!$dropdown.find('*').filter(function() { return $(this).is(':hover'); }).length && 
                                        !$('.qodef-header-navigation .sub-menu, .qodef-header-navigation .qodef-drop-down-second').filter(function() { return $(this).is(':hover'); }).length) {
                                        $menuItem.removeClass('hover-active');
                                        $dropdown.hide().removeClass('hover-visible');
                                    }
                                }
                            }, 150);
                            
                            $menuItem.data('leaveTimerId', leaveTimerId);
                            globalTimers[menuText] = leaveTimerId;
                        }
                    });
                }
            });
        }
        
        // Initialize the menu
        setupMenu();
        
        // Call the function to set up enhanced mega menu interaction
        setupMegaMenuInteraction();
    }
    
    // Run function on document ready
    $(document).ready(function() {
        
        // First hide all dropdowns immediately 
        hideAllMenusImmediately();
        
        // Hide all subcategory sections
        hideAllSubcategorySections();
        
        // Clean up any duplicate menus
        cleanupDuplicateMenus();
        
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
                   
                    hideAllSubcategorySections();
                    cleanupDuplicateMenus();
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
        
        // Force close dropdowns again
        hideAllMenusImmediately();
        
        // Hide all subcategory sections
        hideAllSubcategorySections();
        
        // Clean up empty menu sections
        cleanupEmptyMenuSections();
        
        // Clean up any duplicate menus
        cleanupDuplicateMenus();
        
        // Run the fix function again
        setTimeout(function() {
            fixMenuPositioning();
            forceHorizontalMenuLayout();
            
            // Hide subcategory sections again after a delay
            setTimeout(function() {
                hideAllSubcategorySections();
                cleanupEmptyMenuSections();
            }, 500);
        }, 100);
    });
    
    // Function to clean up duplicate menus
    function cleanupDuplicateMenus() {
        
        // Remove duplicate custom menus
        if ($('#custom-horizontal-menu').length > 1) {
           
            $('#custom-horizontal-menu').not(':first').remove();
        }
        
        // Hide all original navigation menus if custom menu exists
        if ($('#custom-horizontal-menu').length > 0) {
           
            $('.qodef-header-navigation').not('#custom-horizontal-menu').hide();
        }
        
        // Ensure we have exactly one custom menu visible
        if ($('.qodef-header-navigation:visible').length > 1) {
           
            // Keep only the custom menu or the first original
            if ($('#custom-horizontal-menu').length > 0) {
                $('.qodef-header-navigation:visible').not('#custom-horizontal-menu').hide();
            } else {
                $('.qodef-header-navigation:visible').not(':first').hide();
            }
        }
    }
    
    // Function to force horizontal menu layout using direct DOM manipulation
    function forceHorizontalMenuLayout() {
        
        try {
            // Get the top navigation and menu items
            const $navigation = $('.qodef-header-navigation');
            const $menuList = $navigation.find('> ul').first();
            const $menuItems = $menuList.find('> li');
            
            // First, remove any extra existing custom menus to prevent duplication
            $('#custom-horizontal-menu').not(':first').remove();
            
            if ($navigation.length && $menuList.length && $menuItems.length) {
                
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
                        
                        /* Hide original navigation - we'll only use our custom one */
                        .qodef-header-navigation:not(#custom-horizontal-menu) {
                            display: none !important;
                            visibility: hidden !important;
                            position: absolute !important;
                            overflow: hidden !important;
                            clip: rect(0, 0, 0, 0) !important;
                            width: 1px !important;
                            height: 1px !important;
                            margin: -1px !important;
                            border: 0 !important;
                            padding: 0 !important;
                        }
                        
                        /* Custom horizontal navigation style - with priority flag */
                        #custom-horizontal-menu {
                            display: flex !important;
                            order: 1 !important;
                            margin-right: auto !important;
                            margin-left: 100px !important; /* Position the menu 100px from the left */
                            visibility: visible !important;
                            opacity: 1 !important;
                            position: relative !important;
                            float: left !important;
                            width: auto !important;
                            height: auto !important;
                            pointer-events: auto !important;
                            z-index: 100 !important;
                        }
                        
                        #custom-horizontal-menu-list {
                            display: flex !important;
                            flex-direction: row !important;
                            flex-wrap: nowrap !important;
                            align-items: center !important;
                            list-style: none !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            width: auto !important;
                            height: auto !important;
                            visibility: visible !important;
                            opacity: 1 !important;
                        }
                        
                        #custom-horizontal-menu-list > li {
                            display: inline-block !important;
                            float: left !important;
                            margin-right: 25px !important;
                            position: relative !important;
                            height: auto !important;
                            visibility: visible !important;
                            opacity: 1 !important;
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
                            visibility: visible !important;
                            opacity: 1 !important;
                            cursor: pointer !important;
                        }
                        
                        /* Hide subcategory content by default */
                        .mega-menu-subcategory-content {
                            display: none !important;
                            visibility: hidden !important;
                            opacity: 0 !important;
                            pointer-events: none !important;
                            height: 0 !important;
                        }
                        
                        /* Hide subcategory sections by default */
                        .mega-menu-subcategory-section {
                            display: none !important;
                            visibility: hidden !important;
                            opacity: 0 !important;
                            pointer-events: none !important;
                            height: 0 !important;
                        }
                        
                        /* Show subcategory content only when active and hovered */
                        .mega-menu-subcategory-content.active {
                            display: flex !important;
                            flex-direction: row !important;
                            flex-wrap: wrap !important;
                            visibility: visible !important;
                            opacity: 1 !important;
                            pointer-events: auto !important;
                            height: auto !important;
                        }
                        
                        /* Show subcategory sections only when active and hovered */
                        .mega-menu-subcategory-content.active .mega-menu-subcategory-section {
                            display: block !important;
                            visibility: visible !important;
                            opacity: 1 !important;
                            pointer-events: auto !important;
                            height: auto !important;
                        }
                        
                        /* Hide subcategory sections only when active and hovered */
                        .mega-menu-subcategory-content.active .mega-menu-subcategory-section {
                            display: block !important;
                            visibility: visible !important;
                            opacity: 1 !important;
                            pointer-events: auto !important;
                            height: auto !important;
                        }
                        
                        /* Widget holder positioning */
                        .qodef-widget-holder {
                            order: 3 !important;
                            margin-left: auto !important;
                            margin-right: 100px !important;
                        }
                    </style>
                `);
                
                // Only create the custom menu if it doesn't already exist
                if (!$('#custom-horizontal-menu').length) {
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
                    
                    // Apply 100px margin directly to the element
                    $customNav.css('margin-left', '100px');
                }
                
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
                        'margin-left': 'auto',
                        'margin-right': '100px'
                    });
                }
                
                // Re-apply hover event handlers to the menu
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
                                .not($dropdown).hide().removeClass('hover-visible');
                            
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
                                if (!$('#custom-horizontal-menu-list > li').filter(function() { return $(this).is(':hover'); }).length && 
                                    !$('.sub-menu, .qodef-drop-down-second').filter(function() { return $(this).is(':hover'); }).length) {
                                    
                                    $menuItem.removeClass('hover-active');
                                    $dropdown.hide().removeClass('hover-visible');
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
                                if (!$('#custom-horizontal-menu-list > li').filter(function() { return $(this).is(':hover'); }).length && 
                                    !$('.sub-menu, .qodef-drop-down-second').filter(function() { return $(this).is(':hover'); }).length) {
                                    
                                    $menuItem.removeClass('hover-active');
                                    $dropdown.hide().removeClass('hover-visible');
                                }
                            }, 250);
                        });
                    }
                });
                
            } else {
            }
        } catch (error) {
        }
    }
    
    // Run periodically to ensure it stays fixed
    setInterval(function() {
        try {
            // Check if any menus are opened without hover
            if (($('.sub-menu:visible').length || $('.qodef-drop-down-second:visible').length) && 
                !$('#custom-horizontal-menu-list > li').filter(function() { return $(this).is(':hover'); }).length &&
                !$('.qodef-header-navigation > ul > li').filter(function() { return $(this).is(':hover'); }).length && 
                !$('.sub-menu:visible').closest('li').filter(function() { return $(this).is(':hover'); }).length && 
                !$('.qodef-drop-down-second:visible').closest('li').filter(function() { return $(this).is(':hover'); }).length) {
               
                hideAllMenusImmediately();
            }
            
            // Clean up any duplicate menus
            cleanupDuplicateMenus();
            
            // Only rebuild menu if it's completely missing
            if ($('#custom-horizontal-menu').length === 0 && !menuRebuildInProgress) {

                menuRebuildInProgress = true;
                forceHorizontalMenuLayout();
                // Reset the flag after rebuild
                setTimeout(function() {
                    menuRebuildInProgress = false;
                }, 2000);
            }
        } catch (error) {
            
            menuRebuildInProgress = false;
        }
    }, 1000);
    
    // Add a manual hover test function that can be run from the console
    window.testMenuHover = function(menuSelector) {
        const $menuItem = $(menuSelector || '.qodef-header-navigation > ul > li:contains("SHOP")');
        
        if ($menuItem.length) {
           
            $menuItem.trigger('mouseenter');
            
            setTimeout(function() {
                const $dropdown = $menuItem.find('.sub-menu, .qodef-drop-down-second').first();
                if ($dropdown.length) {
                    
                    // Count elements inside
                    const $innerContent = $dropdown.find('.qodef-drop-down-second-inner');
                    if ($innerContent.length) {

                    }
                } else {
                }
            }, 100);
        } else {
        }
    };
    
    // Update function inside jQuery to handle the interactive behavior
    function setupMegaMenuInteraction() {
        // Find all mega menu containers
        $('.qodef-drop-down-second-inner').each(function() {
            const $megaMenu = $(this);
            
            // Get the original menu structure before modifying
            const $originalMenu = $megaMenu.find('> ul').first();

            // Create the mega menu structure if it doesn't exist yet
            if (!$megaMenu.find('.mega-menu-categories').length) {
                // Extract existing menu items from the original structure
                const $originalItems = $originalMenu.find('> li');
                
                if ($originalItems.length === 0) {
                    return;
                }
                
                // Create containers
                const $categoriesContainer = $('<div class="mega-menu-categories"></div>');
                const $featuredSection = $('<div class="mega-menu-featured-section"></div>');
                
                // Check if any items have submenus
                let hasSubmenus = false;
                $originalItems.each(function() {
                    if ($(this).find('> ul').length > 0) {
                        hasSubmenus = true;
                        return false; // break the loop
                    }
                });
                
                // Only create subcategories container if there are submenus
                let $subcategoriesContainer;
                if (hasSubmenus) {
                    $subcategoriesContainer = $('<div class="mega-menu-subcategories"></div>');
                }
                
                // Process each original menu item
                $originalItems.each(function(index) {
                    const $item = $(this);
                    const $link = $item.find('> a').first();
                    const itemTitle = $link.find('.qodef-menu-item-text').text().trim();
                    const itemUrl = $link.attr('href') || '#';
                    const hasSubmenu = $item.find('> ul').length > 0;
                    const $submenu = $item.find('> ul').first();
                    
                    if (!itemTitle) return;

                    // Create category item
                    const $categoryItem = $(`
                        <div class="mega-menu-category-item${hasSubmenu ? ' mega-menu-category-item-with-children' : ''} clickable" 
                             data-category="category-${index}" 
                             data-href="${itemUrl}">
                            <span class="category-icon"><i class="fa fa-circle"></i></span>
                            <span class="category-title">${itemTitle}</span>
                        </div>
                    `);
                    
                    $categoriesContainer.append($categoryItem);

                    // Process submenu if exists
                    if (hasSubmenu && $subcategoriesContainer) {
                        const $subcategoryContent = $(`<div class="mega-menu-subcategory-content" data-category="category-${index}"></div>`);
                        
                        // Process each subcategory
                        $submenu.find('> li').each(function() {
                            const $subItem = $(this);
                            const $subLink = $subItem.find('> a').first();
                            const subTitle = $subLink.find('.qodef-menu-item-text').text().trim();
                            const subUrl = $subLink.attr('href');
                            const hasSubSubMenu = $subItem.find('> ul').length > 0;
                            
                            if (!subTitle) return;

                            const $subcategorySection = $(`
                                <div class="mega-menu-subcategory-section">
                                    <div class="mega-menu-subcategory-title">
                                        <span class="subcategory-icon"><i class="fa fa-folder"></i></span>
                                        ${subTitle}
                                    </div>
                                    <ul class="mega-menu-subcategory-links"></ul>
                                </div>
                            `);

                            const $linksList = $subcategorySection.find('.mega-menu-subcategory-links');

                            // Add the main subcategory link
                            if (subUrl && subUrl !== '#') {
                                $linksList.append(`
                                    <li>
                                        <a href="${subUrl}" class="mega-menu-subcategory-link">
                                            <span class="subcategory-link-icon">•</span>
                                            ${subTitle}
                                        </a>
                                    </li>
                                `);
                            }

                            // Add sub-subcategory links if they exist
                            if (hasSubSubMenu) {
                                $subItem.find('> ul > li').each(function() {
                                    const $subSubItem = $(this);
                                    const $subSubLink = $subSubItem.find('> a').first();
                                    const subSubTitle = $subSubLink.find('.qodef-menu-item-text').text().trim();
                                    const subSubUrl = $subSubLink.attr('href');

                                    if (subSubUrl && subSubUrl !== '#') {
                                        $linksList.append(`
                                            <li>
                                                <a href="${subSubUrl}" class="mega-menu-subcategory-link">
                                                    <span class="subcategory-link-icon">•</span>
                                                    ${subSubTitle}
                                                </a>
                                            </li>
                                        `);
                                    }
                                });
                            }

                            $subcategoryContent.append($subcategorySection);
                        });

                        $subcategoriesContainer.append($subcategoryContent);
                    }
                });
                
                // Empty the mega menu and add the new structure
                $megaMenu.empty()
                        .append($categoriesContainer);
                
                // Only append subcategories container if it exists
                if ($subcategoriesContainer) {
                    $megaMenu.append($subcategoriesContainer);
                }
                
                $megaMenu.append($featuredSection);

                // Initialize featured section
                loadPopularProducts($featuredSection);

                // Add event handlers for category items
                $categoriesContainer.find('.mega-menu-category-item').on('mouseenter click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const $item = $(this);
                    const categoryId = $item.data('category');
                    const hasChildren = $item.hasClass('mega-menu-category-item-with-children');
                    
                    // Remove active class from all categories and add to current
                    $('.mega-menu-category-item').removeClass('active');
                    $item.addClass('active');
                    
                    // Hide all subcategory content first
                    $('.mega-menu-subcategory-content').removeClass('active').attr('aria-hidden', 'true');
                    $('.mega-menu-subcategories').removeClass('active').attr('aria-hidden', 'true');
                    
                    if (hasChildren) {
                        // Show subcategories for this category
                        const $subcategory = $(`.mega-menu-subcategory-content[data-category="${categoryId}"]`);
                        if ($subcategory.length) {
                            $('.mega-menu-subcategories').addClass('active').attr('aria-hidden', 'false');
                            $subcategory.addClass('active').attr('aria-hidden', 'false');
                            $subcategory.find('.mega-menu-subcategory-section').attr('aria-hidden', 'false');
                        }
                    }
                });
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
    
    // Function to load popular products via AJAX
    function loadPopularProducts($container) {
        // Only proceed if container exists
        if (!$container || $container.length === 0) {
            return;
        }
        
        // Prevent duplicate AJAX calls
        if ($container.data('loading') === true) {
            return;
        }
        
        // Set loading flag
        $container.data('loading', true);
        
        // Show loading state
        $container.html('<div class="loading-message">Loading popular products...</div>');
        
        // Get the AJAX URL
        var ajax_url = '';
        if (typeof askka_params !== 'undefined' && askka_params.ajaxurl) {
            ajax_url = askka_params.ajaxurl;
        } else if (typeof askka_mega_menu_vars !== 'undefined' && askka_mega_menu_vars.ajaxurl) {
            ajax_url = askka_mega_menu_vars.ajaxurl;
        } else if (typeof qodefGlobal !== 'undefined' && qodefGlobal.vars && qodefGlobal.vars.ajaxUrl) {
            ajax_url = qodefGlobal.vars.ajaxUrl;
        } else if (typeof ajaxurl !== 'undefined') {
            ajax_url = ajaxurl;
        } else {
            ajax_url = '/wp-admin/admin-ajax.php'; // Fallback
        }
        
        // Make AJAX request
        $.ajax({
            url: ajax_url,
            type: 'POST',
            data: {
                action: 'get_popular_products',
                limit: 3
            },
            success: function(response) {
                console.log('Popular products loaded:', response);
                
                // Parse response if it's a string
                let data = response;
                if (typeof response === 'string') {
                    try {
                        data = JSON.parse(response);
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                    }
                }
                
                // Check if we have valid data
                if (data && data.success && data.data && data.data.length > 0) {
                    // Update the featured section with products
                    updateProductsDisplay($container, data.data);
                    
                    // Ensure the featured section is visible
                    $container.css({
                        'display': 'block',
                        'visibility': 'visible',
                        'opacity': '1',
                        'height': 'auto'
                    });
                    
                    // Add active class to make sure it's shown
                    $container.addClass('active');
                } else {
                    showNoProductsMessage($container);
                }
                
                // Reset loading flag
                $container.data('loading', false);
            },
            error: function(xhr, status, error) {
                console.error('Error loading popular products:', error);
                showNoProductsMessage($container);
                $container.data('loading', false);
            }
        });
    }
    
    // Function to show a message when no products are available
    function showNoProductsMessage($container) {
        $container.html(`
            <div class="bestseller-heading">Popular Products</div>
            <div class="no-products-message">No products available at the moment.</div>
        `);
    }
    
    // Function to update the products display
    function updateProductsDisplay($container, products) {
        // Clear container
        $container.empty();
        
        // Add the heading and products grid
        $container.html(`
            <div class="bestseller-heading">Popular Products</div>
            <div class="bestseller-products-grid">
                ${products.map(product => {
                    // Clean up the price string - extract just the number and currency
                    const priceText = product.price.replace(/<[^>]*>/g, '').trim();
                    
                    return `
                        <div class="bestseller-product">
                            <a href="${product.url}" class="product-link">
                                <div class="product-thumbnail">
                                    <img src="${product.image}" alt="${product.title}">
                                    <div class="product-thumbnail-overlay">
                                        <span class="view-product">View Product</span>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h5 class="product-title">${product.title}</h5>
                                    <div class="product-price">${priceText}</div>
                                </div>
                            </a>
                        </div>
                    `;
                }).join('')}
            </div>
        `);

        // Make sure the featured section is visible
        $container.css({
            'display': 'block',
            'visibility': 'visible',
            'opacity': '1',
            'width': '300px',
            'min-width': '300px',
            'padding-left': '2rem',
            'margin-left': '2rem',
            'border-left': '1px solid rgba(0,0,0,0.08)'
        });
    }
    
    // Function to hide all subcategory sections and content
    function hideAllSubcategorySections() {
        // Hide all subcategory-related elements by removing active class
        $('.mega-menu-subcategories, .mega-menu-subcategory-content, .mega-menu-subcategory-section')
            .removeClass('active')
            .attr('aria-hidden', 'true');
        
        // Hide empty sections
        $('.mega-menu-subcategory-title:empty').closest('.mega-menu-subcategory-section').hide();
        
        // Hide links with undefined URLs
        $('a[href="undefined"]').closest('li').hide();
    }

    // Function to clean up empty or invalid menu sections
    function cleanupEmptyMenuSections() {
        // Hide empty sections or sections with empty titles
        $('.mega-menu-subcategory-title:empty').closest('.mega-menu-subcategory-section').remove();
        
        // Hide sections with no content or only whitespace
        $('.mega-menu-subcategory-title').each(function() {
            if ($(this).text().trim() === '') {
                $(this).closest('.mega-menu-subcategory-section').remove();
            }
        });
        
        // Remove sections with undefined URLs
        $('a[href="undefined"]').closest('.mega-menu-subcategory-section').remove();
        
        // Remove empty subcategory contents
        $('.mega-menu-subcategory-content').each(function() {
            if ($(this).children().length === 0) {
                $(this).remove();
            }
        });
    }
    
    // Run this function periodically to ensure menu stays clean
    setInterval(function() {
        try {
            // Run cleanup and hide functions to keep menu tidy
            cleanupEmptyMenuSections();
            hideAllSubcategorySections();
        } catch (error) {
        }
    }, 2000);
    
    // Also run when the window is loaded
    $(window).on('load', function() {
        
        // Force close dropdowns again
        hideAllMenusImmediately();
        
        // Hide all subcategory sections
        hideAllSubcategorySections();
        
        // Clean up empty menu sections
        cleanupEmptyMenuSections();
        
        // Clean up any duplicate menus
        cleanupDuplicateMenus();
        
        // Run the fix function again
        setTimeout(function() {
            fixMenuPositioning();
            forceHorizontalMenuLayout();
            
            // Hide subcategory sections again after a delay
            setTimeout(function() {
                hideAllSubcategorySections();
                cleanupEmptyMenuSections();
            }, 500);
        }, 100);
    });


    // Improve overall pointer-events handling
    function fixPointerEvents() {
        // Ensure clickable elements have proper pointer-events by adding class
        $('.mega-menu-category-item, .mega-menu-subcategory-link').addClass('clickable');
    }

    // Run pointer events fix on load and periodically
    $(document).ready(fixPointerEvents);
    $(window).on('load', fixPointerEvents);
    setInterval(fixPointerEvents, 2000);

    // Add this new function to initialize featured sections
    function initializeFeaturedSections() {
        $('.mega-menu-featured-section').each(function() {
            const $container = $(this);
            if (!$container.data('initialized')) {
                loadPopularProducts($container);
                $container.data('initialized', true);
            }
        });
    }

    // Call initialization when menu is rebuilt
    $(document).on('menuRebuilt', function() {
        initializeFeaturedSections();
    });

    // Also call it on document ready and window load
    $(document).ready(function() {
        initializeFeaturedSections();
    });

    $(window).on('load', function() {
        initializeFeaturedSections();
    });

    // Add periodic check for featured sections
    setInterval(function() {
        $('.mega-menu-featured-section').each(function() {
            const $container = $(this);
            if ($container.is(':visible') && !$container.data('initialized')) {
                loadPopularProducts($container);
                $container.data('initialized', true);
            }
        });
    }, 1000);

})(jQuery); 