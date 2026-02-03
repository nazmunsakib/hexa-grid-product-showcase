jQuery(document).ready(function ($) {
    // Tabs (if used)
    $('.hexagrid-settings-tab-link').on('click', function (e) {
        e.preventDefault();
        var tabId = $(this).data('tab');

        // Remove active class
        $('.hexagrid-settings-tab-link').removeClass('active');
        $('.hexagrid-settings-tab-content').removeClass('active');

        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });

    // --- Section Toggle (Accordion) - Copied and renamed from library ---
    $('.hexagrid-settings-section-header').on('click', function () {
        var $section = $(this).closest('.hexagrid-settings-section');
        $section.toggleClass('closed');
        $(this).next('.hexagrid-settings-section-body').slideToggle(200);
    });

    // Copy Shortcode functionality
    $('.hexagrid-settings-copy-btn').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var targetId = $btn.data('clipboard-target');
        var $target = $(targetId);
        var text = $target.text();

        // Create temporary textarea
        var $temp = $("<textarea>");
        $("body").append($temp);
        $temp.val(text).select();

        try {
            document.execCommand("copy");
            var originalText = $btn.html();
            $btn.html('<span class="dashicons dashicons-yes"></span> Copied!');
            setTimeout(function () {
                $btn.html(originalText);
            }, 2000);
        } catch (err) {
            console.error('Failed to copy', err);
        }

        $temp.remove();
    });

    // Range Slider Output (if used)
    $('#hexagrid_columns').on('input', function () {
        $('#hexagrid_columns_output').text($(this).val());
    });

    /*
     * -------------------------------------------------------------------------
     * Layout Variation Logic (Specific to HexaGrid)
     * -------------------------------------------------------------------------
     * This logic manages the showing/hiding of the specific variation groups
     * based on the selected parent layout (Grid, List, etc).
     */
    function updateLayoutVariations() {
        // We look for the checked radio input inside the 'hexagrid_layout_type' container
        // Since we are using the generic builder, we target the name attribute directly
        var selectedLayout = $('input[name="hexagrid_layout_type"]:checked').val();

        // Hide all first
        $('.hexagrid-settings-layout-variation-group').hide();
        $('.hexagrid-settings-no-variations').hide();

        if (!selectedLayout) return;

        var $targetGroup = $('.hexagrid-settings-layout-variation-group[data-parent-layout="' + selectedLayout + '"]');

        if ($targetGroup.length) {
            $targetGroup.fadeIn(200);

            // If no variation in this group is checked, check the first one
            if (!$targetGroup.find('input[type="radio"]:checked').length) {
                // Determine if we need to target generic builder classes (aksbuilder-)
                $targetGroup.find('input[type="radio"]').first().prop('checked', true).trigger('change');
            }
        } else {
            $('.hexagrid-settings-no-variations').show();
        }
    }

    // Bind to the change event of the layout type radio buttons
    $(document).on('change', 'input[name="hexagrid_layout_type"]', updateLayoutVariations);

    // Initial Run for Variations
    updateLayoutVariations();
});
