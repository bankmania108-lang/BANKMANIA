<?php
/**
 * Provide a admin area view for curriculum management
 *
 * This file is used to markup the admin-facing aspects of the plugin for curriculum.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'Curriculum Management', 'sep-smart-exam-platform' ); ?></h2>
    
    <div class="sep-curriculum-container">
        <div class="sep-curriculum-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-curriculum-overview" class="nav-tab nav-tab-active"><?php _e( 'Curriculum Overview', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-lessons" class="nav-tab"><?php _e( 'Lessons', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-quizzes" class="nav-tab"><?php _e( 'Quizzes', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-assignments" class="nav-tab"><?php _e( 'Assignments', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-curriculum-tab-content">
            <div id="sep-curriculum-overview" class="sep-curriculum-tab-pane active">
                <div class="sep-curriculum-stats">
                    <div class="sep-stats-grid">
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Courses', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php $count = wp_count_posts( 'sep_course' ); echo isset($count->publish) ? $count->publish : 0; ?></p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Lessons', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php $count = wp_count_posts( 'sep_lesson' ); echo isset($count->publish) ? $count->publish : 0; ?></p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Quizzes', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php $count = wp_count_posts( 'sep_quiz' ); echo isset($count->publish) ? $count->publish : 0; ?></p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Assignments', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php $count = wp_count_posts( 'sep_exams' ); echo isset($count->publish) ? $count->publish : 0; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="sep-course-curriculum-mapping">
                    <h3><?php _e( 'Course Curriculum Mapping', 'sep-smart-exam-platform' ); ?></h3>
                    <div class="sep-curriculum-mapping-controls">
                        <select id="curriculum-course-select">
                            <option value=""><?php _e( 'Select a Course', 'sep-smart-exam-platform' ); ?></option>
                            <?php
                            $courses = get_posts( array(
                                'post_type' => 'sep_course',
                                'posts_per_page' => -1,
                                'post_status' => 'publish'
                            ) );
                            foreach ( $courses as $course ) {
                                echo '<option value="' . $course->ID . '">' . esc_html( $course->post_title ) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div id="sep-curriculum-display" class="sep-curriculum-display">
                        <p class="sep-curriculum-placeholder"><?php _e( 'Select a course to view its curriculum structure.', 'sep-smart-exam-platform' ); ?></p>
                    </div>
                </div>
            </div>
            
            <div id="sep-lessons" class="sep-curriculum-tab-pane">
                <h3><?php _e( 'Manage Lessons', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-lessons-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Lesson Title', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Course', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Section', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $lessons = get_posts( array(
                                'post_type' => 'sep_lesson',
                                'posts_per_page' => 20,
                                'post_status' => 'any'
                            ) );
                            
                            if ( ! empty( $lessons ) ) {
                                foreach ( $lessons as $lesson ) {
                                    // Find which course this lesson belongs to
                                    $course_id = null;
                                    $section_title = 'N/A';
                                    
                                    // This would require searching through all courses' curriculum
                                    $all_courses = get_posts( array(
                                        'post_type' => 'sep_course',
                                        'posts_per_page' => -1,
                                        'post_status' => 'any'
                                    ) );
                                    
                                    foreach ( $all_courses as $course ) {
                                        $curriculum = get_post_meta( $course->ID, '_sep_curriculum', true );
                                        $curriculum = $curriculum ? json_decode( $curriculum, true ) : array();
                                        
                                        if ( ! empty( $curriculum ) ) {
                                            foreach ( $curriculum as $section ) {
                                                if ( ! empty( $section['items'] ) ) {
                                                    foreach ( $section['items'] as $item ) {
                                                        if ( $item['content_id'] == $lesson->ID ) {
                                                            $course_id = $course->ID;
                                                            $section_title = $section['title'];
                                                            break 3; // Break out of all loops
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $lesson->ID; ?></td>
                                        <td><strong><?php echo esc_html( $lesson->post_title ); ?></strong></td>
                                        <td>
                                            <?php if ( $course_id ) : ?>
                                                <a href="<?php echo get_edit_post_link( $course_id ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a>
                                            <?php else : ?>
                                                <?php _e( 'Not assigned', 'sep-smart-exam-platform' ); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo esc_html( $section_title ); ?></td>
                                        <td><?php echo esc_html( ucfirst( $lesson->post_status ) ); ?></td>
                                        <td>
                                            <a href="<?php echo get_edit_post_link( $lesson->ID ); ?>" class="button button-small"><?php _e( 'Edit', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="<?php echo get_permalink( $lesson->ID ); ?>" class="button button-small" target="_blank"><?php _e( 'View', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6"><?php _e( 'No lessons found.', 'sep-smart-exam-platform' ); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="sep-quizzes" class="sep-curriculum-tab-pane">
                <h3><?php _e( 'Manage Quizzes', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-quizzes-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Quiz Title', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Course', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Section', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Questions', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $quizzes = get_posts( array(
                                'post_type' => 'sep_quiz',
                                'posts_per_page' => 20,
                                'post_status' => 'any'
                            ) );
                            
                            if ( ! empty( $quizzes ) ) {
                                foreach ( $quizzes as $quiz ) {
                                    // Find which course this quiz belongs to
                                    $course_id = null;
                                    $section_title = 'N/A';
                                    $question_count = 0;
                                    
                                    // Count questions in this quiz
                                    $quiz_questions = get_post_meta( $quiz->ID, '_sep_quiz_questions', true );
                                    $question_count = is_array( $quiz_questions ) ? count( $quiz_questions ) : 0;
                                    
                                    // Find course this quiz belongs to
                                    $all_courses = get_posts( array(
                                        'post_type' => 'sep_course',
                                        'posts_per_page' => -1,
                                        'post_status' => 'any'
                                    ) );
                                    
                                    foreach ( $all_courses as $course ) {
                                        $curriculum = get_post_meta( $course->ID, '_sep_curriculum', true );
                                        $curriculum = $curriculum ? json_decode( $curriculum, true ) : array();
                                        
                                        if ( ! empty( $curriculum ) ) {
                                            foreach ( $curriculum as $section ) {
                                                if ( ! empty( $section['items'] ) ) {
                                                    foreach ( $section['items'] as $item ) {
                                                        if ( $item['content_id'] == $quiz->ID ) {
                                                            $course_id = $course->ID;
                                                            $section_title = $section['title'];
                                                            break 3; // Break out of all loops
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $quiz->ID; ?></td>
                                        <td><strong><?php echo esc_html( $quiz->post_title ); ?></strong></td>
                                        <td>
                                            <?php if ( $course_id ) : ?>
                                                <a href="<?php echo get_edit_post_link( $course_id ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a>
                                            <?php else : ?>
                                                <?php _e( 'Not assigned', 'sep-smart-exam-platform' ); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo esc_html( $section_title ); ?></td>
                                        <td><?php echo $question_count; ?></td>
                                        <td><?php echo esc_html( ucfirst( $quiz->post_status ) ); ?></td>
                                        <td>
                                            <a href="<?php echo get_edit_post_link( $quiz->ID ); ?>" class="button button-small"><?php _e( 'Edit', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="<?php echo get_permalink( $quiz->ID ); ?>" class="button button-small" target="_blank"><?php _e( 'View', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7"><?php _e( 'No quizzes found.', 'sep-smart-exam-platform' ); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="sep-assignments" class="sep-curriculum-tab-pane">
                <h3><?php _e( 'Manage Assignments', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-assignments-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Assignment Title', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Course', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Section', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Questions', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $assignments = get_posts( array(
                                'post_type' => 'sep_exams',
                                'posts_per_page' => 20,
                                'post_status' => 'any'
                            ) );
                            
                            if ( ! empty( $assignments ) ) {
                                foreach ( $assignments as $assignment ) {
                                    // Find which course this assignment belongs to
                                    $course_id = null;
                                    $section_title = 'N/A';
                                    $question_count = 0;
                                    
                                    // Count questions in this assignment
                                    $assignment_questions = get_post_meta( $assignment->ID, '_sep_exam_questions', true );
                                    $question_count = is_array( $assignment_questions ) ? count( $assignment_questions ) : 0;
                                    
                                    // Find course this assignment belongs to
                                    $all_courses = get_posts( array(
                                        'post_type' => 'sep_course',
                                        'posts_per_page' => -1,
                                        'post_status' => 'any'
                                    ) );
                                    
                                    foreach ( $all_courses as $course ) {
                                        $curriculum = get_post_meta( $course->ID, '_sep_curriculum', true );
                                        $curriculum = $curriculum ? json_decode( $curriculum, true ) : array();
                                        
                                        if ( ! empty( $curriculum ) ) {
                                            foreach ( $curriculum as $section ) {
                                                if ( ! empty( $section['items'] ) ) {
                                                    foreach ( $section['items'] as $item ) {
                                                        if ( $item['content_id'] == $assignment->ID ) {
                                                            $course_id = $course->ID;
                                                            $section_title = $section['title'];
                                                            break 3; // Break out of all loops
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $assignment->ID; ?></td>
                                        <td><strong><?php echo esc_html( $assignment->post_title ); ?></strong></td>
                                        <td>
                                            <?php if ( $course_id ) : ?>
                                                <a href="<?php echo get_edit_post_link( $course_id ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a>
                                            <?php else : ?>
                                                <?php _e( 'Not assigned', 'sep-smart-exam-platform' ); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo esc_html( $section_title ); ?></td>
                                        <td><?php echo $question_count; ?></td>
                                        <td><?php echo esc_html( ucfirst( $assignment->post_status ) ); ?></td>
                                        <td>
                                            <a href="<?php echo get_edit_post_link( $assignment->ID ); ?>" class="button button-small"><?php _e( 'Edit', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="<?php echo get_permalink( $assignment->ID ); ?>" class="button button-small" target="_blank"><?php _e( 'View', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7"><?php _e( 'No assignments found.', 'sep-smart-exam-platform' ); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.sep-curriculum-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-curriculum-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-curriculum-tab-content {
    padding: 20px;
}

.sep-curriculum-tab-pane {
    display: none;
}

.sep-curriculum-tab-pane.active {
    display: block;
}

.sep-curriculum-stats {
    margin-bottom: 30px;
}

.sep-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.sep-stat-card {
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 20px;
    text-align: center;
    border-radius: 4px;
}

.sep-stat-card h4 {
    margin: 0 0 10px 0;
    color: #666;
}

.sep-stat-number {
    font-size: 2em;
    font-weight: bold;
    margin: 0;
    color: #0073aa;
}

.sep-curriculum-mapping-controls {
    margin-bottom: 20px;
}

.sep-curriculum-display {
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    min-height: 300px;
}

.sep-curriculum-placeholder {
    text-align: center;
    color: #999;
    padding: 40px 0;
}

.sep-lessons-table-container,
.sep-quizzes-table-container,
.sep-assignments-table-container {
    overflow-x: auto;
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
        $('.sep-curriculum-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Course selection for curriculum view
    $('#curriculum-course-select').on('change', function() {
        var courseId = $(this).val();
        
        if (courseId) {
            // In a real implementation, this would fetch the curriculum via AJAX
            // For now, we'll just simulate it
            $('#sep-curriculum-display').html('<p>Loading curriculum for course ID: ' + courseId + '...</p>');
            
            // Simulate loading
            setTimeout(function() {
                var curriculumHtml = '<h4>Course Curriculum Structure</h4>';
                curriculumHtml += '<div class="sep-curriculum-structure">';
                curriculumHtml += '<div class="sep-curriculum-section">';
                curriculumHtml += '<h5>Section 1: Introduction</h5>';
                curriculumHtml += '<ul>';
                curriculumHtml += '<li><span class="dashicons dashicons-media-document"></span> Lesson 1: Getting Started</li>';
                curriculumHtml += '<li><span class="dashicons dashicons-clipboard"></span> Quiz 1: Basic Concepts</li>';
                curriculumHtml += '</ul>';
                curriculumHtml += '</div>';
                curriculumHtml += '<div class="sep-curriculum-section">';
                curriculumHtml += '<h5>Section 2: Advanced Topics</h5>';
                curriculumHtml += '<ul>';
                curriculumHtml += '<li><span class="dashicons dashicons-media-document"></span> Lesson 2: In-depth Analysis</li>';
                curriculumHtml += '<li><span class="dashicons dashicons-media-document"></span> Lesson 3: Practical Applications</li>';
                curriculumHtml += '<li><span class="dashicons dashicons-clipboard"></span> Quiz 2: Advanced Concepts</li>';
                curriculumHtml += '<li><span class="dashicons dashicons-welcome-learn-more"></span> Assignment 1: Final Assessment</li>';
                curriculumHtml += '</ul>';
                curriculumHtml += '</div>';
                curriculumHtml += '</div>';
                
                $('#sep-curriculum-display').html(curriculumHtml);
            }, 1000);
        } else {
            $('#sep-curriculum-display').html('<p class="sep-curriculum-placeholder"><?php _e( 'Select a course to view its curriculum structure.', 'sep-smart-exam-platform' ); ?></p>');
        }
    });
});
</script>