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
 */
function getCurrentTabs() {
    const tabs = [];
    
    // Wait a bit for Elementor to fully render
    const $tabsRepeater = $('.elementor-control-tabs .elementor-repeater-fields');
    
    console.log('Looking for tabs in repeater fields...');
    
    $tabsRepeater.each(function(index) {
        const $field = $(this);
        
        // Find the title input within this repeater field
        const $titleInput = $field.find('input[data-setting="tab_title"]');
        const title = $titleInput.val();
        
        console.log('Tab ' + index + ' title:', title);
        
        if (title && title.trim() !== '') {
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
    $(document).on('input keyup change', 'input[data-setting="tab_title"]', function() {
        console.log('Tab title changed, updating dropdowns...');
        setTimeout(updateCardDropdowns, 500);
    });

    // Monitor tab add
    $(document).on('click', '.elementor-control-tabs .elementor-repeater-add', function() {
        console.log('Tab added, updating dropdowns...');
        setTimeout(updateCardDropdowns, 1000);
    });

    // Monitor tab remove
    $(document).on('click', '.elementor-control-tabs .elementor-repeater-tool-remove', function() {
        console.log('Tab removed, updating dropdowns...');
        setTimeout(updateCardDropdowns, 500);
    });

    // Monitor when cards section is opened
    $(document).on('click', '.elementor-control-cards_section', function() {
        console.log('Cards section opened, updating dropdowns...');
        setTimeout(updateCardDropdowns, 300);
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
  // Initialize - WAIT LONGER
setTimeout(function() {
    console.log('Initial load - updating dropdowns...');
    updateCardDropdowns();
    watchTabChanges();
}, 20000); // Increased from 1000 to 2000ms

    console.log('✅ Auto-refresh system initialized');
    console.log('📌 Dropdowns will update automatically when tabs change');

});