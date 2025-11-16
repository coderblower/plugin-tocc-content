/**
 * assets/elementor-fix.js
 * -------------------------------------------------------
 * Handle CUSTOM "Add Card" button that doesn't conflict with parent repeater
 */
jQuery(document).ready(function($) {

    console.log('Custom Add Card handler loaded');

    /* ------------------------------------------------------------
       Handle our CUSTOM "Add Card" button (not Elementor's default)
       ------------------------------------------------------------ */
    $(document).on('click', '.custom-add-card-button', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        console.log('Custom Add Card button clicked');

        const $customBtn = $(this);
        
        // Find the parent repeater row (this is the TAB row)
        const $tabRow = $customBtn.closest('.elementor-repeater-row');
        
        if (!$tabRow.length) {
            console.error('Could not find tab row');
            return false;
        }

        // Find the nested cards repeater control
        const $cardsRepeater = $tabRow.find('.elementor-control[data-control-name="tab_cards"]');
        
        if (!$cardsRepeater.length) {
            console.error('Could not find cards repeater');
            return false;
        }

        // Find the real Elementor "Add Item" button for the CARDS repeater only
        const $realAddButton = $cardsRepeater.find('.elementor-repeater-add').first();
        
        if (!$realAddButton.length) {
            console.error('Could not find real add button');
            return false;
        }

        console.log('Triggering real card add button');

        // Trigger the real button's click
        $realAddButton[0].click();

        // Keep the parent tab row CLOSED after adding
        setTimeout(function() {
            $tabRow.removeClass('elementor-open');
            
            // Also close the newly added card row
            const $allCardRows = $cardsRepeater.find('.elementor-repeater-row');
            const $newCardRow = $allCardRows.last();
            $newCardRow.removeClass('elementor-open');
            
            // Scroll to the new card
            if ($newCardRow.length) {
                $('html, body').animate({
                    scrollTop: $newCardRow.offset().top - 100
                }, 300);
            }
            
            console.log('Card added successfully');
        }, 100);

        return false;
    });

    /* ------------------------------------------------------------
       Optional: Hide Elementor's default "Add Item" button for cards
       to avoid confusion (users should use our custom button)
       ------------------------------------------------------------ */
    function hideDefaultCardAddButton() {
        // Hide the default add button that appears at the bottom of the cards repeater
        $('.elementor-control[data-control-name="tab_cards"] > .elementor-repeater-fields > .elementor-button-wrapper').hide();
    }

    // Run on load
    setTimeout(hideDefaultCardAddButton, 500);
    
    // Run when panel changes
    $(document).on('elementor/panel/loaded', hideDefaultCardAddButton);
    
    // Watch for DOM changes
    const observer = new MutationObserver(hideDefaultCardAddButton);
    const panelContent = document.getElementById('elementor-panel-content-wrapper');
    if (panelContent) {
        observer.observe(panelContent, { childList: true, subtree: true });
    }

    /* ------------------------------------------------------------
       Prevent accidental tab creation when clicking around cards area
       ------------------------------------------------------------ */
    $(document).on('click', '.elementor-control[data-control-name="tab_cards"]', function(e) {
        // Stop all clicks in the cards area from bubbling to parent tab repeater
        e.stopPropagation();
    });

    /* ------------------------------------------------------------
       Add hover effect to custom button
       ------------------------------------------------------------ */
    $(document).on('mouseenter', '.custom-add-card-button', function() {
        $(this).css({
            'background': '#7a0031',
            'transform': 'translateY(-1px)',
            'box-shadow': '0 2px 5px rgba(0,0,0,0.2)'
        });
    });

    $(document).on('mouseleave', '.custom-add-card-button', function() {
        $(this).css({
            'background': '#93003c',
            'transform': 'translateY(0)',
            'box-shadow': 'none'
        });
    });

    console.log('Custom Add Card handler initialized');
});