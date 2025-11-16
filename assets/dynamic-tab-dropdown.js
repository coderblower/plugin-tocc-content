/**
 * Dynamic Tab Dropdown Updater
 * Automatically updates the "Assign to Tab" dropdown when tabs are added/modified
 * 
 * Place in: /assets/dynamic-tab-dropdown.js
 */

(function($) {
    'use strict';

    console.log('🎯 Dynamic Tab Dropdown: Loading...');

    let updateTimeout = null;

    $(window).on('elementor:init', function() {
        console.log('✅ Elementor ready, initializing dynamic dropdown...');
        
        setTimeout(function() {
            initDynamicDropdown();
        }, 1000);
    });

    function initDynamicDropdown() {
        
        // Watch for changes in the tabs repeater
        $(document).on('input change', '.elementor-control-tabs input, .elementor-control-tabs textarea', function() {
            console.log('📝 Tab field changed, scheduling dropdown update...');
            scheduleDropdownUpdate();
        });

        // Watch for tab additions/deletions
        $(document).on('click', '.elementor-control-tabs .elementor-repeater-add, .elementor-control-tabs .elementor-repeater-tool-remove', function() {
            console.log('➕/➖ Tab added or removed, scheduling dropdown update...');
            scheduleDropdownUpdate();
        });

        // Watch for DOM changes in the tabs section
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length || mutation.removedNodes.length) {
                    const $target = $(mutation.target);
                    if ($target.closest('.elementor-control-tabs').length) {
                        scheduleDropdownUpdate();
                    }
                }
            });
        });

        // Start observing
        const panel = document.getElementById('elementor-panel-content-wrapper');
        if (panel) {
            observer.observe(panel, {
                childList: true,
                subtree: true
            });
            console.log('👀 Watching for tab changes...');
        }

        console.log('✅ Dynamic dropdown initialized');
    }

    /**
     * Schedule dropdown update with debounce
     */
    function scheduleDropdownUpdate() {
        if (updateTimeout) {
            clearTimeout(updateTimeout);
        }

        updateTimeout = setTimeout(function() {
            updateCardDropdowns();
        }, 500); // Wait 500ms after last change
    }

    /**
     * Update all "Assign to Tab" dropdowns with current tab data
     */
    function updateCardDropdowns() {
        console.log('🔄 Updating card dropdowns...');

        // Get all tab IDs and titles from the tabs repeater
        const tabs = getTabsData();

        if (tabs.length === 0) {
            console.log('⚠️ No tabs found');
            return;
        }

        console.log('📋 Found tabs:', tabs);

        // Find all "assigned_tab" dropdowns in the cards repeater
        $('.elementor-control-cards .elementor-control-assigned_tab select').each(function() {
            const $select = $(this);
            const currentValue = $select.val();

            // Clear existing options
            $select.empty();

            // Add new options based on current tabs
            tabs.forEach(function(tab) {
                const $option = $('<option></option>')
                    .attr('value', tab.id)
                    .text(tab.title);
                
                // Preserve selection if it still exists
                if (tab.id === currentValue) {
                    $option.attr('selected', 'selected');
                }

                $select.append($option);
            });

            // Trigger change event to update Elementor's internal state
            $select.trigger('change');
        });

        console.log('✅ Dropdowns updated with', tabs.length, 'tabs');
    }

    /**
     * Extract tab data from the tabs repeater
     */
    function getTabsData() {
        const tabs = [];

        // Find all tab rows in the tabs repeater
        $('.elementor-control-tabs .elementor-repeater-row').each(function() {
            const $row = $(this);

            // Get tab ID
            const $idInput = $row.find('.elementor-control-tab_id input');
            const tabId = $idInput.val();

            // Get tab title
            const $titleInput = $row.find('.elementor-control-tab_title input');
            const tabTitle = $titleInput.val() || tabId || 'Untitled Tab';

            if (tabId) {
                tabs.push({
                    id: tabId,
                    title: tabTitle
                });
            }
        });

        return tabs;
    }

    /**
     * Force update when panel opens or widget is selected
     */
    elementor.on('panel:init', function() {
        setTimeout(updateCardDropdowns, 1000);
    });

    // Also update when any repeater is opened
    $(document).on('click', '.elementor-repeater-row-controls', function() {
        const $row = $(this).closest('.elementor-repeater-row');
        if ($row.closest('.elementor-control-cards').length) {
            setTimeout(updateCardDropdowns, 300);
        }
    });

})(jQuery);

/**
 * BACKUP METHOD: Force refresh on save
 * This ensures dropdowns are always in sync after saving
 */
jQuery(window).on('elementor:init', function() {
    
    elementor.channels.data.on('document:save:before', function() {
        console.log('💾 Document saving, final dropdown update...');
        
        // Get latest tab data and store it
        const tabs = [];
        jQuery('.elementor-control-tabs .elementor-repeater-row').each(function() {
            const $row = jQuery(this);
            const tabId = $row.find('.elementor-control-tab_id input').val();
            const tabTitle = $row.find('.elementor-control-tab_title input').val() || tabId;
            
            if (tabId) {
                tabs.push({ id: tabId, title: tabTitle });
            }
        });

        // Update all card dropdowns one final time
        jQuery('.elementor-control-cards .elementor-control-assigned_tab select').each(function() {
            const $select = jQuery(this);
            const currentValue = $select.val();
            
            $select.empty();
            tabs.forEach(function(tab) {
                const $option = jQuery('<option></option>')
                    .attr('value', tab.id)
                    .text(tab.title);
                
                if (tab.id === currentValue) {
                    $option.attr('selected', 'selected');
                }
                
                $select.append($option);
            });
        });
    });
});