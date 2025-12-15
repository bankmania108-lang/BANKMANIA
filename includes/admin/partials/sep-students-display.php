<?php
/**
 * Provide a admin area view for student management
 *
 * This file is used to markup the admin-facing aspects of the plugin for students.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'Student Management', 'sep-smart-exam-platform' ); ?></h2>
    
    <div class="sep-students-container">
        <div class="sep-students-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-students-list" class="nav-tab nav-tab-active"><?php _e( 'Students List', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-enrollments" class="nav-tab"><?php _e( 'Enrollments', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-progress" class="nav-tab"><?php _e( 'Progress Tracking', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-certificates" class="nav-tab"><?php _e( 'Certificates', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-students-tab-content">
            <div id="sep-students-list" class="sep-students-tab-pane active">
                <div class="sep-students-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Name', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Email', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Courses Enrolled', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Registration Date', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $students = get_users( array(
                                'role' => 'student',
                                'number' => 20,
                                'orderby' => 'registered',
                                'order' => 'DESC'
                            ) );
                            
                            if ( ! empty( $students ) ) {
                                foreach ( $students as $student ) {
                                    $courses_enrolled = count( get_user_meta( $student->ID, '_sep_enrolled_courses', true ) ?: array() );
                                    ?>
                                    <tr>
                                        <td><?php echo $student->ID; ?></td>
                                        <td><strong><?php echo esc_html( $student->display_name ); ?></strong></td>
                                        <td><?php echo esc_html( $student->user_email ); ?></td>
                                        <td><?php echo $courses_enrolled; ?></td>
                                        <td><?php echo date( 'M j, Y', strtotime( $student->user_registered ) ); ?></td>
                                        <td><?php echo ucfirst( $student->user_status ); ?></td>
                                        <td>
                                            <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $student->ID ); ?>" class="button button-small"><?php _e( 'Edit', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="#" class="button button-small sep-view-student-progress" data-user-id="<?php echo $student->ID; ?>"><?php _e( 'View Progress', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7"><?php _e( 'No students found.', 'sep-smart-exam-platform' ); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="sep-enrollments" class="sep-students-tab-pane">
                <h3><?php _e( 'Student Enrollments', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-enrollment-filters">
                    <select id="enrollment-course-filter">
                        <option value=""><?php _e( 'All Courses', 'sep-smart-exam-platform' ); ?></option>
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
                    <select id="enrollment-status-filter">
                        <option value=""><?php _e( 'All Statuses', 'sep-smart-exam-platform' ); ?></option>
                        <option value="active"><?php _e( 'Active', 'sep-smart-exam-platform' ); ?></option>
                        <option value="completed"><?php _e( 'Completed', 'sep-smart-exam-platform' ); ?></option>
                        <option value="incomplete"><?php _e( 'In Progress', 'sep-smart-exam-platform' ); ?></option>
                        <option value="cancelled"><?php _e( 'Cancelled', 'sep-smart-exam-platform' ); ?></option>
                    </select>
                </div>
                
                <div class="sep-enrollments-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'Student', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Course', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Enrollment Date', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Progress', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // This would be populated with actual enrollment data
                            for ( $i = 1; $i <= 10; $i++ ) {
                                $student = get_user_by( 'ID', $i );
                                $course = get_post( rand( 100, 200 ) ); // Simulated course
                                $progress = rand( 0, 100 );
                                $status = $progress == 100 ? 'completed' : ( $progress > 0 ? 'incomplete' : 'active' );
                                
                                if ( $student && $course ) {
                                    ?>
                                    <tr>
                                        <td><?php echo esc_html( $student->display_name ); ?></td>
                                        <td><?php echo esc_html( $course->post_title ); ?></td>
                                        <td><?php echo date( 'M j, Y' ); ?></td>
                                        <td>
                                            <div class="sep-progress-bar">
                                                <div class="sep-progress-fill" style="width: <?php echo $progress; ?>%"></div>
                                            </div>
                                            <span class="sep-progress-percent"><?php echo $progress; ?>%</span>
                                        </td>
                                        <td><?php echo ucfirst( $status ); ?></td>
                                        <td>
                                            <a href="#" class="button button-small"><?php _e( 'Manage', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="sep-progress" class="sep-students-tab-pane">
                <h3><?php _e( 'Progress Tracking', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-progress-filters">
                    <select id="progress-student-filter">
                        <option value=""><?php _e( 'All Students', 'sep-smart-exam-platform' ); ?></option>
                        <?php
                        foreach ( $students as $student ) {
                            echo '<option value="' . $student->ID . '">' . esc_html( $student->display_name ) . '</option>';
                        }
                        ?>
                    </select>
                    <select id="progress-course-filter">
                        <option value=""><?php _e( 'All Courses', 'sep-smart-exam-platform' ); ?></option>
                        <?php
                        foreach ( $courses as $course ) {
                            echo '<option value="' . $course->ID . '">' . esc_html( $course->post_title ) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <div class="sep-progress-overview">
                    <div class="sep-progress-summary">
                        <h4><?php _e( 'Overall Progress', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-progress-stats">
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Total Students', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">125</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Active Courses', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">8</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Avg. Progress', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">68%</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Certificates Issued', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">24</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sep-student-progress-chart">
                        <h4><?php _e( 'Progress Distribution', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-chart-placeholder">
                            <p><?php _e( 'Chart visualization would appear here', 'sep-smart-exam-platform' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="sep-certificates" class="sep-students-tab-pane">
                <h3><?php _e( 'Certificate Management', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-certificate-actions">
                    <button type="button" class="button button-primary" id="sep-generate-certificate"><?php _e( 'Generate Certificate', 'sep-smart-exam-platform' ); ?></button>
                    <button type="button" class="button" id="sep-bulk-generate-certs"><?php _e( 'Bulk Generate', 'sep-smart-exam-platform' ); ?></button>
                </div>
                
                <div class="sep-certificates-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'Certificate ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Student', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Course', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Issue Date', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // This would be populated with actual certificate data
                            for ( $i = 1; $i <= 5; $i++ ) {
                                $student = get_user_by( 'ID', $i );
                                $course = get_post( rand( 100, 200 ) ); // Simulated course
                                
                                if ( $student && $course ) {
                                    ?>
                                    <tr>
                                        <td>CERT-<?php echo date( 'Y' ) . sprintf( '%05d', $i ); ?></td>
                                        <td><?php echo esc_html( $student->display_name ); ?></td>
                                        <td><?php echo esc_html( $course->post_title ); ?></td>
                                        <td><?php echo date( 'M j, Y' ); ?></td>
                                        <td><span class="sep-status-badge sep-status-active"><?php _e( 'Active', 'sep-smart-exam-platform' ); ?></span></td>
                                        <td>
                                            <a href="#" class="button button-small"><?php _e( 'View', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="#" class="button button-small"><?php _e( 'Download', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
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
.sep-students-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-students-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-students-tab-content {
    padding: 20px;
}

.sep-students-tab-pane {
    display: none;
}

.sep-students-tab-pane.active {
    display: block;
}

.sep-students-table-container,
.sep-enrollments-table-container,
.sep-certificates-table-container {
    overflow-x: auto;
}

.sep-enrollment-filters,
.sep-progress-filters {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
}

.sep-progress-bar {
    width: 100px;
    height: 10px;
    background-color: #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
    display: inline-block;
    margin-right: 10px;
}

.sep-progress-fill {
    height: 100%;
    background-color: #0073aa;
    transition: width 0.3s ease;
}

.sep-progress-percent {
    font-size: 0.9em;
    color: #666;
}

.sep-progress-overview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 20px;
}

.sep-progress-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.sep-status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8em;
}

.sep-status-active {
    background-color: #d7f0db;
    color: #0a6516;
}

.sep-chart-placeholder {
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 40px;
    text-align: center;
    border-radius: 4px;
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
        $('.sep-students-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // View student progress
    $('.sep-view-student-progress').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
        alert('Viewing progress for student ID: ' + userId);
    });
    
    // Filter enrollment table
    $('#enrollment-course-filter, #enrollment-status-filter').on('change', function() {
        // In a real implementation, this would filter the table via AJAX
        console.log('Filtering enrollments...');
    });
    
    // Filter progress table
    $('#progress-student-filter, #progress-course-filter').on('change', function() {
        // In a real implementation, this would filter the progress view
        console.log('Filtering progress...');
    });
});
</script>