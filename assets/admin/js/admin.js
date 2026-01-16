jQuery(document).ready(function ($) {
    // Tabs
    $('.psw-tab-link').on('click', function (e) {
        e.preventDefault();
        var tabId = $(this).data('tab');

        // Remove active class
        $('.psw-tab-link').removeClass('active');
        $('.psw-tab-content').removeClass('active');

        // Add active class
        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });

    // Color Picker
    $('.psw-color-picker').wpColorPicker();

    // Copy Shortcode functionality
    $('.psw-copy-btn').on('click', function (e) {
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

    // Range Slider Output
    $('#psw_columns').on('input', function () {
        $('#psw_columns_output').text($(this).val());
    });
});
