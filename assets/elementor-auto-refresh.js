/**
 * assets/elementor-auto-refresh.js
 */
jQuery(document).ready(function($) {

    console.log('Tabbed USP Auto-refresh handler loaded');

    /**
     * Generate slug from title (same logic as PHP)
     */
    function generateTabId(title, index) {
        if (!title) return 'tab-' + index;
        
        let slug = title.toLowerCase();
        slug = slug.replace(/[^a-z0-9]+/g, '-');
        slug = slug.replace(/^-+|-+$/g, '');
        
        return slug || 'tab-' + index;
    }

    /**
     * Get all current tabs with their IDs
     */
/**
 * Get all current tabs with their IDs
 */
function getCurrentTabs() {
    const tabs = [];
    
    // Try multiple selectors to find tabs
    const $tabRows = $('.elementor-control[data-control-name="tabs"] .elementor-repeater-row');
    
    console.log('Found tab rows:', $tabRows.length);
    
    $tabRows.each(function(index) {
        const $row = $(this);
        
        // Try different ways to get the title input
        let title = $row.find('input[data-setting="tab_title"]').val();
        
        if (!title) {
            title = $row.find('.elementor-control-tab_title input').val();
        }
        
        if (!title) {
            title = $row.find('[data-control-name="tab_title"] input').val();
        }
        
        console.log('Tab ' + index + ' title:', title);
        
        if (title) {
            const id = generateTabId(title, index);
            tabs.push({ id: id, title: title });
        }
    });
    
    console.log('Total tabs found:', tabs.length);
    
    return tabs;
}

   /**
 * Update all card dropdowns with current tabs
 */
function updateCardDropdowns() {
    const tabs = getCurrentTabs();
    
    console.log('Updating dropdowns with tabs:', tabs);

    // Find all card repeater rows
    $('.elementor-control[data-control-name="cards"] .elementor-repeater-row').each(function() {
        // Find the select dropdown inside this card row
        const $select = $(this).find('.elementor-control[data-control-name="assign_to_tab"] select');
        
        if ($select.length) {
            const currentValue = $select.val(); // Save current selection
            
            // Clear and rebuild options
            $select.empty();
            
            // Add default option
            $select.append('<option value="">-- Select Tab --</option>');
            
            // Add tab options
            tabs.forEach(function(tab) {
                const $option = $('<option></option>')
                    .attr('value', tab.id)
                    .text(tab.title);
                $select.append($option);
            });
            
            // Restore previous selection if still exists
            if (currentValue) {
                $select.val(currentValue);
            }
            
            // Trigger change event so Elementor knows
            $select.trigger('change');
        }
    });

    console.log('Dropdowns updated! Found ' + tabs.length + ' tabs');
}
    /**
     * Initialize dropdowns when cards section opens
     */
    function initializeOnCardsOpen() {
        const cardsSection = $('.elementor-control[data-control-name="cards_section"]');
        
        if (cardsSection.length && cardsSection.is(':visible')) {
            updateCardDropdowns();
        }
    }

    /**
     * Watch for tab changes
     */
    function watchTabChanges() {
        // Monitor tab title changes
        $(document).on('input change', '.elementor-control[data-control-name="tabs"] [data-setting="tab_title"]', function() {
            setTimeout(updateCardDropdowns, 300);
        });

        // Monitor tab add/remove
        $(document).on('click', '.elementor-control[data-control-name="tabs"] .elementor-repeater-add', function() {
            setTimeout(updateCardDropdowns, 800);
        });

        $(document).on('click', '.elementor-control[data-control-name="tabs"] .elementor-repeater-remove', function() {
            setTimeout(updateCardDropdowns, 500);
        });

        // Monitor when new card is added
        $(document).on('click', '.elementor-control[data-control-name="cards"] .elementor-repeater-add', function() {
            setTimeout(updateCardDropdowns, 800);
        });
    }

    /**
     * Initialize on Elementor panel load
     */
    $(document).on('elementor/panel/loaded', function() {
        setTimeout(function() {
            updateCardDropdowns();
            initializeOnCardsOpen();
        }, 500);
    });

    /**
     * Watch for section visibility changes
     */
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const $target = $(mutation.target);
                if ($target.hasClass('elementor-open') && $target.data('control-name') === 'cards_section') {
                    setTimeout(updateCardDropdowns, 300);
                }
            }
        });
    });

    // Observe the panel for changes
    const panelContent = document.getElementById('elementor-panel-content-wrapper');
    if (panelContent) {
        observer.observe(panelContent, {
            attributes: true,
            subtree: true,
            attributeFilter: ['class']
        });
    }

    // Initialize
    setTimeout(function() {
        updateCardDropdowns();
        watchTabChanges();
    }, 1000);

    console.log('✅ Auto-refresh system initialized');
    console.log('📌 Dropdowns will update automatically when tabs change');

});