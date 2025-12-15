jQuery(document).ready(function($) {
    // Main admin functionality for the Smart Exam Platform
    
    // Tab functionality for all admin pages
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var tabId = $(this).attr('href');
        
        // Update active tab
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Show active pane
        $('.sep-admin-tab-pane, .sep-questions-tab-pane, .sep-courses-tab-pane, .sep-curriculum-tab-pane, .sep-students-tab-pane, .sep-reports-tab-pane, .sep-settings-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Initialize Select2 for dropdowns if available
    if (typeof jQuery().select2 !== 'undefined') {
        $('.sep-select2').select2();
    }
    
    // Course curriculum section toggle
    $(document).on('click', '.sep-section-header', function() {
        var content = $(this).siblings('.sep-section-content');
        var toggle = $(this).find('.sep-section-toggle');
        
        content.slideToggle();
        toggle.toggleClass('expanded');
    });
    
    // Initialize course curriculum sections
    $('.sep-section-header').each(function() {
        $(this).find('.sep-section-toggle').addClass('expanded');
        $(this).siblings('.sep-section-content').show();
    });
    
    // Handle AJAX requests if nonce is available
    if (typeof sep_ajax !== 'undefined') {
        // Example AJAX handler - can be expanded based on needs
        $(document).on('click', '.sep-ajax-action', function(e) {
            e.preventDefault();
            
            var action = $(this).data('action');
            var data = {
                'action': action,
                'nonce': sep_ajax.nonce
            };
            
            $.post(sep_ajax.ajax_url, data, function(response) {
                // Handle response
                console.log(response);
            });
        });
    }
    
    // Initialize color pickers
    if (typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function') {
        $('.sep-color-picker').wpColorPicker();
    }
    
    // Handle form submissions with AJAX if needed
    $('.sep-ajax-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        
        $.post(form.attr('action'), formData, function(response) {
            // Handle response
            console.log('Form submitted successfully');
        });
    });
    
    // Initialize tooltips if available
    if (typeof jQuery.ui === 'object' && typeof jQuery.ui.tooltip === 'object') {
        $('.sep-tooltip').tooltip();
    }
    
    // Handle bulk actions
    $('.sep-bulk-actions').on('change', function() {
        var action = $(this).val();
        if (action !== '-1') {
            if (confirm('Are you sure you want to perform this bulk action?')) {
                // Process bulk action
                console.log('Processing bulk action: ' + action);
            }
        }
    });
    
    // Initialize date pickers if available
    $('.sep-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});