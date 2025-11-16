/**
 * Prevent nested repeater from auto-expanding parent accordion
 */
jQuery(document).ready(function($) {
    // Listen for when items are added to nested repeaters
    $(document).on('click', '.elementor-repeater-add', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Get the repeater container
        var $repeater = $(this).closest('.elementor-repeater-fields');
        
        // Check if this is a nested repeater
        if ($repeater.closest('.elementor-repeater-row').length > 0) {
            // This is a nested repeater, don't expand parent
            var $addBtn = $(this);
            
            setTimeout(function() {
                // Trigger the actual add without expanding parent
                $repeater.find('.elementor-add-new-field').trigger('click');
            }, 100);
            
            return false;
        }
    });
    
    // Prevent accordion from opening when adding to nested repeaters
    $(document).on('elementor:repeater:add', function() {
        // Keep accordion closed
        $('.elementor-control-type-repeater .elementor-repeater-row.ui-sortable').each(function() {
            if ($(this).closest('.elementor-repeater-row').length > 0) {
                // This is nested, keep it collapsed
                $(this).removeClass('elementor-open');
            }
        });
    });
});
