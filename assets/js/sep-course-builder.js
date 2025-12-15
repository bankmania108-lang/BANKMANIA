jQuery(document).ready(function($) {
    // Course builder functionality
    
    // Toggle section visibility
    $(document).on('click', '.sep-section-header', function() {
        var content = $(this).siblings('.sep-section-content');
        var toggle = $(this).find('.sep-section-toggle');
        
        content.toggleClass('expanded');
        toggle.toggleClass('expanded');
    });
    
    // Add new section
    $('#sep-add-section-btn').on('click', function() {
        var sectionId = 'section_' + Date.now();
        var newSection = $('#tmpl-sep-new-section').html();
        newSection = newSection.replace('{{SECTION_ID}}', sectionId);
        
        $('#sep-curriculum-builder').append(newSection);
    });
    
    // Add item to section
    $(document).on('click', '.sep-add-item-to-section', function() {
        var section = $(this).closest('.sep-section');
        var sectionId = section.data('section-id');
        var itemIndex = section.find('.sep-item').length;
        var itemId = 'item_' + Date.now();
        
        var newItem = $('#tmpl-sep-new-item').html();
        newItem = newItem.replace('{{SECTION_ID}}', sectionId);
        newItem = newItem.replace('{{ITEM_INDEX}}', itemIndex);
        newItem = newItem.replace('{{ITEM_ID}}', itemId);
        
        section.find('.sep-items-list').append(newItem);
    });
    
    // Remove section
    $(document).on('click', '.sep-remove-section', function() {
        if (confirm('Are you sure you want to remove this section and all its items?')) {
            $(this).closest('.sep-section').remove();
        }
    });
    
    // Remove item
    $(document).on('click', '.sep-remove-item', function() {
        $(this).closest('.sep-item').remove();
    });
    
    // Make items sortable within sections
    $('.sep-items-list').sortable({
        placeholder: 'ui-sortable-placeholder',
        cursor: 'move',
        opacity: 0.8,
        tolerance: 'pointer',
        axis: 'y'
    });
    
    // Make sections sortable
    $('#sep-curriculum-builder').sortable({
        placeholder: 'ui-sortable-placeholder',
        cursor: 'move',
        opacity: 0.8,
        tolerance: 'pointer',
        axis: 'y',
        handle: '.sep-section-header'
    });
    
    // Initialize existing sections to be expandable
    $('.sep-section-header').each(function() {
        $(this).find('.sep-section-toggle').addClass('expanded');
        $(this).siblings('.sep-section-content').addClass('expanded');
    });
});