<?php
/**
 * Provide a admin area view for reports management
 *
 * This file is used to markup the admin-facing aspects of the plugin for reports.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'Reports & Analytics', 'sep-smart-exam-platform' ); ?></h2>
    
    <div class="sep-reports-container">
        <div class="sep-reports-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-overview" class="nav-tab nav-tab-active"><?php _e( 'Overview', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-exam-performance" class="nav-tab"><?php _e( 'Exam Performance', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-student-analytics" class="nav-tab"><?php _e( 'Student Analytics', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-course-analytics" class="nav-tab"><?php _e( 'Course Analytics', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-exports" class="nav-tab"><?php _e( 'Export Reports', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-reports-tab-content">
            <div id="sep-overview" class="sep-reports-tab-pane active">
                <div class="sep-reports-overview">
                    <div class="sep-reports-stats-grid">
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Students', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number">1,245</p>
                            <p class="sep-stat-change sep-stat-increase">+12% from last month</p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Courses', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number">24</p>
                            <p class="sep-stat-change sep-stat-increase">+3 new this month</p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Exams Taken', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number">3,867</p>
                            <p class="sep-stat-change sep-stat-increase">+24% from last month</p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Avg. Score', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number">72%</p>
                            <p class="sep-stat-change sep-stat-decrease">-2% from last month</p>
                        </div>
                    </div>
                    
                    <div class="sep-reports-charts">
                        <div class="sep-chart-row">
                            <div class="sep-chart-container">
                                <h4><?php _e( 'Monthly Activity', 'sep-smart-exam-platform' ); ?></h4>
                                <div class="sep-chart-placeholder">
                                    <p><?php _e( 'Activity chart would appear here', 'sep-smart-exam-platform' ); ?></p>
                                </div>
                            </div>
                            <div class="sep-chart-container">
                                <h4><?php _e( 'Course Completion Rate', 'sep-smart-exam-platform' ); ?></h4>
                                <div class="sep-chart-placeholder">
                                    <p><?php _e( 'Completion chart would appear here', 'sep-smart-exam-platform' ); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sep-chart-row">
                            <div class="sep-chart-container">
                                <h4><?php _e( 'Exam Performance by Subject', 'sep-smart-exam-platform' ); ?></h4>
                                <div class="sep-chart-placeholder">
                                    <p><?php _e( 'Subject performance chart would appear here', 'sep-smart-exam-platform' ); ?></p>
                                </div>
                            </div>
                            <div class="sep-chart-container">
                                <h4><?php _e( 'Student Engagement', 'sep-smart-exam-platform' ); ?></h4>
                                <div class="sep-chart-placeholder">
                                    <p><?php _e( 'Engagement chart would appear here', 'sep-smart-exam-platform' ); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="sep-exam-performance" class="sep-reports-tab-pane">
                <h3><?php _e( 'Exam Performance Reports', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-exam-performance-filters">
                    <select id="exam-select">
                        <option value=""><?php _e( 'All Exams', 'sep-smart-exam-platform' ); ?></option>
                        <?php
                        $exams = get_posts( array(
                            'post_type' => 'sep_exams',
                            'posts_per_page' => -1,
                            'post_status' => 'publish'
                        ) );
                        foreach ( $exams as $exam ) {
                            echo '<option value="' . $exam->ID . '">' . esc_html( $exam->post_title ) . '</option>';
                        }
                        ?>
                    </select>
                    <input type="date" id="date-from" placeholder="<?php _e( 'From Date', 'sep-smart-exam-platform' ); ?>" />
                    <input type="date" id="date-to" placeholder="<?php _e( 'To Date', 'sep-smart-exam-platform' ); ?>" />
                    <button type="button" class="button" id="apply-exam-filters"><?php _e( 'Apply Filters', 'sep-smart-exam-platform' ); ?></button>
                </div>
                
                <div class="sep-exam-performance-data">
                    <div class="sep-performance-summary">
                        <h4><?php _e( 'Performance Summary', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-performance-stats">
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Total Attempts', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">1,245</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Average Score', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">72%</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Pass Rate', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">68%</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Completion Rate', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">85%</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sep-exam-detailed-report">
                        <h4><?php _e( 'Detailed Report', 'sep-smart-exam-platform' ); ?></h4>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Student', 'sep-smart-exam-platform' ); ?></th>
                                    <th><?php _e( 'Exam', 'sep-smart-exam-platform' ); ?></th>
                                    <th><?php _e( 'Date', 'sep-smart-exam-platform' ); ?></th>
                                    <th><?php _e( 'Score', 'sep-smart-exam-platform' ); ?></th>
                                    <th><?php _e( 'Time Taken', 'sep-smart-exam-platform' ); ?></th>
                                    <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // This would be populated with actual exam attempt data
                                for ( $i = 1; $i <= 10; $i++ ) {
                                    $student = get_user_by( 'ID', rand( 10, 50 ) );
                                    $exam = get_post( rand( 100, 200 ) );
                                    $score = rand( 50, 100 );
                                    $time_taken = rand( 30, 120 ) . ' mins';
                                    $status = $score >= 70 ? 'Pass' : 'Fail';
                                    ?>
                                    <tr>
                                        <td><?php echo $student ? esc_html( $student->display_name ) : 'Student ' . $i; ?></td>
                                        <td><?php echo $exam ? esc_html( $exam->post_title ) : 'Exam ' . $i; ?></td>
                                        <td><?php echo date( 'M j, Y', strtotime( '-' . rand( 1, 30 ) . ' days' ) ); ?></td>
                                        <td><?php echo $score; ?>%</td>
                                        <td><?php echo $time_taken; ?></td>
                                        <td><span class="sep-status-badge <?php echo $status === 'Pass' ? 'sep-status-pass' : 'sep-status-fail'; ?>"><?php echo $status; ?></span></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div id="sep-student-analytics" class="sep-reports-tab-pane">
                <h3><?php _e( 'Student Analytics', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-student-analytics-filters">
                    <select id="student-select">
                        <option value=""><?php _e( 'All Students', 'sep-smart-exam-platform' ); ?></option>
                        <?php
                        $students = get_users( array(
                            'role' => 'student',
                            'number' => 50,
                            'orderby' => 'registered',
                            'order' => 'DESC'
                        ) );
                        foreach ( $students as $student ) {
                            echo '<option value="' . $student->ID . '">' . esc_html( $student->display_name ) . '</option>';
                        }
                        ?>
                    </select>
                    <select id="course-filter">
                        <option value=""><?php _e( 'All Courses', 'sep-smart-exam-platform' ); ?></option>
                        <?php
                        foreach ( $exams as $exam ) {
                            echo '<option value="' . $exam->ID . '">' . esc_html( $exam->post_title ) . '</option>';
                        }
                        ?>
                    </select>
                    <button type="button" class="button" id="apply-student-filters"><?php _e( 'Apply Filters', 'sep-smart-exam-platform' ); ?></button>
                </div>
                
                <div class="sep-student-analytics-data">
                    <div class="sep-student-engagement">
                        <h4><?php _e( 'Student Engagement', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-engagement-stats">
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Avg. Login Frequency', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">3.2 times/week</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Avg. Time Spent', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">45 mins/day</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Course Completion Rate', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">78%</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Exam Pass Rate', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">71%</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sep-student-progress-trends">
                        <h4><?php _e( 'Progress Trends', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-chart-placeholder">
                            <p><?php _e( 'Progress trend chart would appear here', 'sep-smart-exam-platform' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="sep-course-analytics" class="sep-reports-tab-pane">
                <h3><?php _e( 'Course Analytics', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-course-analytics-filters">
                    <select id="course-analytics-select">
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
                    <button type="button" class="button" id="apply-course-filters"><?php _e( 'Apply Filters', 'sep-smart-exam-platform' ); ?></button>
                </div>
                
                <div class="sep-course-analytics-data">
                    <div class="sep-course-performance">
                        <h4><?php _e( 'Course Performance', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-course-stats">
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Enrollments', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">245</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Completion Rate', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">68%</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Avg. Rating', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">4.3/5</p>
                            </div>
                            <div class="sep-stat-card">
                                <h5><?php _e( 'Revenue', 'sep-smart-exam-platform' ); ?></h5>
                                <p class="sep-stat-number">₹2,45,000</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sep-course-engagement">
                        <h4><?php _e( 'Course Engagement', 'sep-smart-exam-platform' ); ?></h4>
                        <div class="sep-engagement-breakdown">
                            <div class="sep-engagement-item">
                                <span class="sep-engagement-label"><?php _e( 'Video Views', 'sep-smart-exam-platform' ); ?></span>
                                <div class="sep-progress-bar">
                                    <div class="sep-progress-fill" style="width: 78%"></div>
                                </div>
                                <span class="sep-engagement-value">78%</span>
                            </div>
                            <div class="sep-engagement-item">
                                <span class="sep-engagement-label"><?php _e( 'Quiz Attempts', 'sep-smart-exam-platform' ); ?></span>
                                <div class="sep-progress-bar">
                                    <div class="sep-progress-fill" style="width: 65%"></div>
                                </div>
                                <span class="sep-engagement-value">65%</span>
                            </div>
                            <div class="sep-engagement-item">
                                <span class="sep-engagement-label"><?php _e( 'Assignment Submissions', 'sep-smart-exam-platform' ); ?></span>
                                <div class="sep-progress-bar">
                                    <div class="sep-progress-fill" style="width: 52%"></div>
                                </div>
                                <span class="sep-engagement-value">52%</span>
                            </div>
                            <div class="sep-engagement-item">
                                <span class="sep-engagement-label"><?php _e( 'Course Completion', 'sep-smart-exam-platform' ); ?></span>
                                <div class="sep-progress-bar">
                                    <div class="sep-progress-fill" style="width: 45%"></div>
                                </div>
                                <span class="sep-engagement-value">45%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="sep-exports" class="sep-reports-tab-pane">
                <h3><?php _e( 'Export Reports', 'sep-smart-exam-platform' ); ?></h3>
                <div class="sep-export-options">
                    <div class="sep-export-card">
                        <h4><?php _e( 'Student Performance', 'sep-smart-exam-platform' ); ?></h4>
                        <p><?php _e( 'Export detailed student performance data', 'sep-smart-exam-platform' ); ?></p>
                        <button type="button" class="button button-primary sep-export-btn" data-type="student-performance"><?php _e( 'Export', 'sep-smart-exam-platform' ); ?></button>
                    </div>
                    <div class="sep-export-card">
                        <h4><?php _e( 'Exam Results', 'sep-smart-exam-platform' ); ?></h4>
                        <p><?php _e( 'Export exam results and statistics', 'sep-smart-exam-platform' ); ?></p>
                        <button type="button" class="button button-primary sep-export-btn" data-type="exam-results"><?php _e( 'Export', 'sep-smart-exam-platform' ); ?></button>
                    </div>
                    <div class="sep-export-card">
                        <h4><?php _e( 'Course Progress', 'sep-smart-exam-platform' ); ?></h4>
                        <p><?php _e( 'Export course progress and engagement data', 'sep-smart-exam-platform' ); ?></p>
                        <button type="button" class="button button-primary sep-export-btn" data-type="course-progress"><?php _e( 'Export', 'sep-smart-exam-platform' ); ?></button>
                    </div>
                    <div class="sep-export-card">
                        <h4><?php _e( 'Revenue Report', 'sep-smart-exam-platform' ); ?></h4>
                        <p><?php _e( 'Export revenue and enrollment data', 'sep-smart-exam-platform' ); ?></p>
                        <button type="button" class="button button-primary sep-export-btn" data-type="revenue"><?php _e( 'Export', 'sep-smart-exam-platform' ); ?></button>
                    </div>
                </div>
                
                <div class="sep-export-history">
                    <h4><?php _e( 'Export History', 'sep-smart-exam-platform' ); ?></h4>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'Report Type', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Date Generated', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Format', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Status', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $report_types = array(
                                'Student Performance',
                                'Exam Results',
                                'Course Progress',
                                'Revenue Report',
                                'Engagement Metrics'
                            );
                            
                            for ( $i = 0; $i < 5; $i++ ) {
                                $type = $report_types[rand(0, count($report_types)-1)];
                                $date = date( 'M j, Y g:i A', strtotime( '-' . rand( 1, 30 ) . ' days' ) );
                                $format = rand( 0, 1 ) ? 'CSV' : 'Excel';
                                $status = 'Completed';
                                ?>
                                <tr>
                                    <td><?php echo $type; ?></td>
                                    <td><?php echo $date; ?></td>
                                    <td><?php echo $format; ?></td>
                                    <td><span class="sep-status-badge sep-status-active"><?php echo $status; ?></span></td>
                                    <td>
                                        <a href="#" class="button button-small"><?php _e( 'Download', 'sep-smart-exam-platform' ); ?></a>
                                    </td>
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
.sep-reports-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-reports-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-reports-tab-content {
    padding: 20px;
}

.sep-reports-tab-pane {
    display: none;
}

.sep-reports-tab-pane.active {
    display: block;
}

.sep-reports-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.sep-stat-card {
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 20px;
    text-align: center;
    border-radius: 4px;
}

.sep-stat-card h4, .sep-stat-card h5 {
    margin: 0 0 10px 0;
    color: #666;
}

.sep-stat-number {
    font-size: 2em;
    font-weight: bold;
    margin: 0;
    color: #0073aa;
}

.sep-stat-change {
    margin: 5px 0 0 0;
    font-size: 0.9em;
}

.sep-stat-increase {
    color: #0a6516;
}

.sep-stat-decrease {
    color: #d63638;
}

.sep-reports-charts {
    margin-top: 30px;
}

.sep-chart-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.sep-chart-container {
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 20px;
    border-radius: 4px;
}

.sep-chart-placeholder {
    background: #fff;
    border: 1px solid #e5e5e5;
    padding: 40px;
    text-align: center;
    border-radius: 4px;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sep-exam-performance-filters,
.sep-student-analytics-filters,
.sep-course-analytics-filters {
    margin-bottom: 20px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.sep-performance-stats,
.sep-engagement-stats,
.sep-course-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.sep-status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8em;
}

.sep-status-pass {
    background-color: #d7f0db;
    color: #0a6516;
}

.sep-status-fail {
    background-color: #fdd;
    color: #d63638;
}

.sep-export-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.sep-export-card {
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 20px;
    border-radius: 4px;
    text-align: center;
}

.sep-export-card h4 {
    margin-top: 0;
}

.sep-engagement-breakdown {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.sep-engagement-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sep-engagement-label {
    flex: 1;
    text-align: left;
}

.sep-progress-bar {
    width: 200px;
    height: 10px;
    background-color: #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
}

.sep-progress-fill {
    height: 100%;
    background-color: #0073aa;
    transition: width 0.3s ease;
}

.sep-engagement-value {
    width: 40px;
    text-align: right;
    font-weight: bold;
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
        $('.sep-reports-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Apply filters for different report types
    $('#apply-exam-filters').on('click', function() {
        console.log('Applying exam filters...');
        // In a real implementation, this would update the report data via AJAX
    });
    
    $('#apply-student-filters').on('click', function() {
        console.log('Applying student filters...');
        // In a real implementation, this would update the report data via AJAX
    });
    
    $('#apply-course-filters').on('click', function() {
        console.log('Applying course filters...');
        // In a real implementation, this would update the report data via AJAX
    });
    
    // Export functionality
    $('.sep-export-btn').on('click', function() {
        var reportType = $(this).data('type');
        alert('Exporting ' + reportType + ' report...');
        // In a real implementation, this would trigger the export process
    });
});
</script>