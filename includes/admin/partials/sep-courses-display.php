<?php
/**
 * Provide a admin area view for courses management
 *
 * This file is used to markup the admin-facing aspects of the plugin for courses.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'Courses Management', 'sep-smart-exam-platform' ); ?></h2>
    
    <div class="sep-courses-container">
        <div class="sep-courses-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-courses-list" class="nav-tab nav-tab-active"><?php _e( 'Courses List', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-add-course" class="nav-tab"><?php _e( 'Add Course', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-course-structure" class="nav-tab"><?php _e( 'Course Structure', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-course-settings" class="nav-tab"><?php _e( 'Course Settings', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-courses-tab-content">
            <div id="sep-courses-list" class="sep-courses-tab-pane active">
                <div class="sep-courses-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Course Title', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Sections', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Lessons', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Students', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $courses = get_posts( array(
                                'post_type' => 'sep_course',
                                'posts_per_page' => 20,
                                'post_status' => 'any'
                            ) );
                            
                            if ( ! empty( $courses ) ) {
                                foreach ( $courses as $course ) {
                                    $curriculum = get_post_meta( $course->ID, '_sep_curriculum', true );
                                    $curriculum = $curriculum ? json_decode( $curriculum, true ) : array();
                                    
                                    $section_count = count( $curriculum );
                                    $lesson_count = 0;
                                    
                                    if ( ! empty( $curriculum ) ) {
                                        foreach ( $curriculum as $section ) {
                                            if ( ! empty( $section['items'] ) ) {
                                                $lesson_count += count( $section['items'] );
                                            }
                                        }
                                    }
                                    
                                    $student_count = 0; // Would need to implement enrollment tracking
                                    ?>
                                    <tr>
                                        <td><?php echo $course->ID; ?></td>
                                        <td><strong><?php echo esc_html( $course->post_title ); ?></strong></td>
                                        <td><?php echo esc_html( ucfirst( $course->post_status ) ); ?></td>
                                        <td><?php echo $section_count; ?></td>
                                        <td><?php echo $lesson_count; ?></td>
                                        <td><?php echo $student_count; ?></td>
                                        <td>
                                            <a href="<?php echo get_edit_post_link( $course->ID ); ?>" class="button button-small"><?php _e( 'Edit', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="<?php echo get_permalink( $course->ID ); ?>" class="button button-small" target="_blank"><?php _e( 'View', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7"><?php _e( 'No courses found.', 'sep-smart-exam-platform' ); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="sep-add-course" class="sep-courses-tab-pane">
                <h3><?php _e( 'Add New Course', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="course_title"><?php _e( 'Course Title', 'sep-smart-exam-platform' ); ?></label></th>
                            <td><input type="text" id="course_title" name="course_title" class="large-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="course_description"><?php _e( 'Description', 'sep-smart-exam-platform' ); ?></label></th>
                            <td><textarea id="course_description" name="course_description" class="large-text" rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="course_category"><?php _e( 'Category', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="course_category" name="course_category">
                                    <option value="banking"><?php _e( 'Banking Exams', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="ssc"><?php _e( 'SSC Exams', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="jaiib"><?php _e( 'JAIIB/CAIIB', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="railway"><?php _e( 'Railway Exams', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="defence"><?php _e( 'Defence Exams', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="teaching"><?php _e( 'Teaching Exams', 'sep-smart-exam-platform' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="course_difficulty"><?php _e( 'Difficulty Level', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="course_difficulty" name="course_difficulty">
                                    <option value="beginner"><?php _e( 'Beginner', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="intermediate"><?php _e( 'Intermediate', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="advanced"><?php _e( 'Advanced', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="all"><?php _e( 'All Levels', 'sep-smart-exam-platform' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="course_price"><?php _e( 'Price (₹)', 'sep-smart-exam-platform' ); ?></label></th>
                            <td><input type="number" id="course_price" name="course_price" value="0" min="0" step="10" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Featured', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="course_featured" value="1" /> 
                                    <?php _e( 'Mark as featured course', 'sep-smart-exam-platform' ); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Add Course', 'sep-smart-exam-platform' ) ); ?>
                </form>
            </div>
            
            <div id="sep-course-structure" class="sep-courses-tab-pane">
                <h3><?php _e( 'Course Structure Builder', 'sep-smart-exam-platform' ); ?></h3>
                <p><?php _e( 'Drag and drop to organize your course content.', 'sep-smart-exam-platform' ); ?></p>
                
                <div class="sep-course-structure-builder">
                    <div class="sep-available-content">
                        <h4><?php _e( 'Available Content', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-content-search">
                            <input type="text" id="content-search" placeholder="<?php _e( 'Search content...', 'sep-smart-exam-platform' ); ?>" />
                        </div>
                        <ul class="sep-content-list">
                            <?php
                            $lessons = get_posts( array(
                                'post_type' => array('sep_lesson', 'sep_quiz', 'sep_exams'),
                                'posts_per_page' => -1,
                                'post_status' => 'publish'
                            ) );
                            
                            foreach ( $lessons as $lesson ) {
                                $type_icon = 'dashicons-media-default';
                                switch ( $lesson->post_type ) {
                                    case 'sep_lesson':
                                        $type_icon = 'dashicons-media-document';
                                        break;
                                    case 'sep_quiz':
                                        $type_icon = 'dashicons-clipboard';
                                        break;
                                    case 'sep_exams':
                                        $type_icon = 'dashicons-welcome-learn-more';
                                        break;
                                }
                                ?>
                                <li class="sep-content-item" data-content-id="<?php echo $lesson->ID; ?>" data-content-type="<?php echo $lesson->post_type; ?>">
                                    <span class="sep-content-icon dashicons <?php echo $type_icon; ?>"></span>
                                    <span class="sep-content-title"><?php echo esc_html( $lesson->post_title ); ?></span>
                                    <span class="sep-content-type"><?php echo esc_html( ucfirst( str_replace( 'sep_', '', $lesson->post_type ) ) ); ?></span>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    
                    <div class="sep-course-curriculum">
                        <h4><?php _e( 'Course Curriculum', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-curriculum-actions">
                            <button type="button" class="button button-primary" id="add-section-btn"><?php _e( 'Add Section', 'sep-smart-exam-platform' ); ?></button>
                        </div>
                        <div class="sep-curriculum-container">
                            <!-- Curriculum will be built here -->
                            <div class="sep-curriculum-placeholder">
                                <p><?php _e( 'Add sections and drag content here to build your course curriculum.', 'sep-smart-exam-platform' ); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="sep-course-settings" class="sep-courses-tab-pane">
                <h3><?php _e( 'Course Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Enrollment', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" name="allow_self_enrollment" value="1" checked /> 
                                        <?php _e( 'Allow self-enrollment', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="require_prerequisites" value="1" /> 
                                        <?php _e( 'Require prerequisites', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="enrollment_approval" value="1" /> 
                                        <?php _e( 'Require enrollment approval', 'sep-smart-exam-platform' ); ?>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Progress Tracking', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" name="track_progress" value="1" checked /> 
                                        <?php _e( 'Enable progress tracking', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="require_lesson_completion" value="1" /> 
                                        <?php _e( 'Require lesson completion before next', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="show_completion_badges" value="1" /> 
                                        <?php _e( 'Show completion badges', 'sep-smart-exam-platform' ); ?>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Certificates', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" name="issue_certificate" value="1" /> 
                                        <?php _e( 'Issue certificate upon completion', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="require_passing_grade" value="1" /> 
                                        <?php _e( 'Require passing grade for certificate', 'sep-smart-exam-platform' ); ?>
                                    </label>
                                    <p>
                                        <label for="passing_grade"><?php _e( 'Minimum passing grade: ', 'sep-smart-exam-platform' ); ?></label>
                                        <input type="number" id="passing_grade" name="passing_grade" value="70" min="0" max="100" />%
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Save Settings', 'sep-smart-exam-platform' ) ); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sep-courses-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-courses-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-courses-tab-content {
    padding: 20px;
}

.sep-courses-tab-pane {
    display: none;
}

.sep-courses-tab-pane.active {
    display: block;
}

.sep-courses-table-container {
    overflow-x: auto;
}

.sep-course-structure-builder {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.sep-available-content, .sep-course-curriculum {
    flex: 1;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 15px;
}

.sep-content-list {
    list-style: none;
    padding: 0;
    margin: 10px 0;
    max-height: 400px;
    overflow-y: auto;
}

.sep-content-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
    cursor: move;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sep-content-item:hover {
    background-color: #f9f9f9;
}

.sep-content-icon {
    width: 20px;
    text-align: center;
}

.sep-content-type {
    margin-left: auto;
    font-size: 0.8em;
    color: #666;
}

.sep-curriculum-container {
    min-height: 300px;
    border: 2px dashed #ccd0d4;
    border-radius: 4px;
    padding: 15px;
    margin-top: 15px;
}

.sep-curriculum-placeholder {
    text-align: center;
    color: #999;
    padding: 40px 0;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tab functionality
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        
        var tabId = $(this).attr('href');
        
        // Update active tab
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        // Show active pane
        $('.sep-courses-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Make content items draggable
    $('.sep-content-item').draggable({
        helper: 'clone',
        appendTo: 'body',
        cursor: 'move',
        zIndex: 1000,
        start: function(event, ui) {
            $(this).addClass('dragging');
        },
        stop: function(event, ui) {
            $(this).removeClass('dragging');
        }
    });
    
    // Make curriculum container droppable
    $('.sep-curriculum-container').droppable({
        accept: '.sep-content-item',
        hoverClass: 'drop-hover',
        drop: function(event, ui) {
            var contentId = ui.draggable.data('content-id');
            var contentType = ui.draggable.data('content-type');
            var contentTitle = ui.draggable.find('.sep-content-title').text();
            
            // Remove placeholder if it exists
            $(this).find('.sep-curriculum-placeholder').remove();
            
            // Create curriculum item
            var curriculumItem = $('<div class="sep-curriculum-item" data-content-id="' + contentId + '">' +
                '<span class="sep-item-move dashicons dashicons-move"></span>' +
                '<span class="sep-item-title">' + contentTitle + '</span>' +
                '<span class="sep-item-type">(' + contentType.replace('sep_', '') + ')</span>' +
                '<span class="sep-item-actions">' +
                    '<button type="button" class="button-link sep-remove-item">Remove</button>' +
                '</span>' +
            '</div>');
            
            $(this).append(curriculumItem);
        }
    });
    
    // Remove item functionality
    $(document).on('click', '.sep-remove-item', function() {
        $(this).closest('.sep-curriculum-item').remove();
        
        // Show placeholder if no items
        var container = $('.sep-curriculum-container');
        if (container.find('.sep-curriculum-item').length === 0) {
            container.append('<div class="sep-curriculum-placeholder"><p><?php _e( 'Add sections and drag content here to build your course curriculum.', 'sep-smart-exam-platform' ); ?></p></div>');
        }
    });
    
    // Add section functionality
    $('#add-section-btn').on('click', function() {
        var sectionCount = $('.sep-curriculum-section').length + 1;
        var sectionHtml = '<div class="sep-curriculum-section" data-section-id="section-' + sectionCount + '">' +
            '<div class="sep-section-header">' +
                '<input type="text" class="sep-section-title" placeholder="<?php _e( 'Section Title', 'sep-smart-exam-platform' ); ?>" value="<?php _e( 'Section', 'sep-smart-exam-platform' ); ?> ' + sectionCount + '" />' +
                '<span class="sep-section-actions">' +
                    '<button type="button" class="button-link sep-remove-section"><?php _e( 'Remove', 'sep-smart-exam-platform' ); ?></button>' +
                '</span>' +
            '</div>' +
            '<div class="sep-section-content">' +
                '<div class="sep-curriculum-items-container">' +
                    '<!-- Items will be dropped here -->' +
                '</div>' +
            '</div>' +
        '</div>';
        
        $('.sep-curriculum-container').append(sectionHtml);
    });
    
    // Remove section functionality
    $(document).on('click', '.sep-remove-section', function() {
        $(this).closest('.sep-curriculum-section').remove();
    });
});
</script>