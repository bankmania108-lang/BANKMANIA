<?php
class SEP_Shortcodes {
    
    public function __construct() {
        add_shortcode('sep_exam', array($this, 'exam_shortcode'));
        add_shortcode('sep_exam_list', array($this, 'exam_list_shortcode'));
        add_shortcode('sep_result', array($this, 'result_shortcode'));
        add_shortcode('sep_dashboard', array($this, 'dashboard_shortcode'));
        add_shortcode('sep_leaderboard', array($this, 'leaderboard_shortcode'));
        add_shortcode('sep_course', array($this, 'course_shortcode'));
        add_shortcode('sep_curriculum', array($this, 'curriculum_shortcode'));
    }
    
    /**
     * Exam shortcode - Displays a single exam
     */
    public function exam_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'show_instructions' => true
        ), $atts);
        
        $exam_id = intval($atts['id']);
        
        if (!$exam_id) {
            return '<p>' . __('Please provide a valid exam ID', 'sep-smart-exam') . '</p>';
        }
        
        $exam = get_post($exam_id);
        
        if (!$exam || $exam->post_type !== 'sep_exams' || $exam->post_status !== 'publish') {
            return '<p>' . __('Exam not found', 'sep-smart-exam') . '</p>';
        }
        
        ob_start();
        
        // Trigger before exam hook
        do_action('sep_before_exam', array('exam_id' => $exam_id, 'exam' => $exam));
        
        ?>
        <div class="sep-container">
            <div class="sep-exam-container">
                <?php if ($atts['show_instructions']): ?>
                    <div class="sep-exam-intro">
                        <h2><?php echo esc_html($exam->post_title); ?></h2>
                        <div class="sep-exam-description">
                            <?php echo apply_filters('the_content', $exam->post_content); ?>
                        </div>
                        <div class="sep-exam-meta">
                            <p><strong><?php _e('Duration:', 'sep-smart-exam'); ?></strong> <?php echo get_post_meta($exam_id, '_sep_exam_duration', true); ?> <?php _e('minutes', 'sep-smart-exam'); ?></p>
                            <p><strong><?php _e('Total Questions:', 'sep-smart-exam'); ?></strong> <?php echo get_post_meta($exam_id, '_sep_total_questions', true); ?></p>
                            <p><strong><?php _e('Passing Percentage:', 'sep-smart-exam'); ?></strong> <?php echo get_post_meta($exam_id, '_sep_pass_percentage', true); ?>%</p>
                        </div>
                        <?php if (is_user_logged_in()): ?>
                            <button class="sep-btn sep-btn-primary sep-start-exam-btn" data-exam-id="<?php echo $exam_id; ?>">
                                <?php _e('Start Exam', 'sep-smart-exam'); ?>
                            </button>
                        <?php else: ?>
                            <p><?php _e('Please log in to take this exam.', 'sep-smart-exam'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <button class="sep-btn sep-btn-primary sep-start-exam-btn" data-exam-id="<?php echo $exam_id; ?>">
                        <?php _e('Start Exam', 'sep-smart-exam'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php
        
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * Exam list shortcode - Displays a list of exams
     */
    public function exam_list_shortcode($atts) {
        $atts = shortcode_atts(array(
            'subject' => '',
            'chapter' => '',
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ), $atts);
        
        $args = array(
            'post_type' => 'sep_exams',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        );
        
        // Add taxonomy filters if specified
        $tax_query = array();
        
        if (!empty($atts['subject'])) {
            $tax_query[] = array(
                'taxonomy' => 'sep_subjects',
                'field' => 'slug',
                'terms' => $atts['subject']
            );
        }
        
        if (!empty($atts['chapter'])) {
            $tax_query[] = array(
                'taxonomy' => 'sep_chapters',
                'field' => 'slug',
                'terms' => $atts['chapter']
            );
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }
        
        $exams = get_posts($args);
        
        if (empty($exams)) {
            return '<p>' . __('No exams found', 'sep-smart-exam') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="sep-container">
            <div class="sep-exam-list-container">
                <h2><?php _e('Available Exams', 'sep-smart-exam'); ?></h2>
                <ul class="sep-exam-list">
                    <?php foreach ($exams as $exam): ?>
                        <li>
                            <a href="#" class="sep-start-exam-btn sep-exam-link" data-exam-id="<?php echo $exam->ID; ?>">
                                <?php echo esc_html($exam->post_title); ?>
                            </a>
                            <div class="sep-exam-meta">
                                <?php
                                $duration = get_post_meta($exam->ID, '_sep_exam_duration', true);
                                $questions = get_post_meta($exam->ID, '_sep_total_questions', true);
                                $pass_percent = get_post_meta($exam->ID, '_sep_pass_percentage', true);
                                ?>
                                <span><?php echo $duration; ?> <?php _e('min', 'sep-smart-exam'); ?> • <?php echo $questions; ?> <?php _e('Qs', 'sep-smart-exam'); ?> • <?php echo $pass_percent; ?>% <?php _e('pass', 'sep-smart-exam'); ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
        
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * Result shortcode - Displays exam results
     */
    public function result_shortcode($atts) {
        $atts = shortcode_atts(array(
            'attempt' => 'auto' // 'auto' for current user's latest, or specific attempt ID
        ), $atts);
        
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view results.', 'sep-smart-exam') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $attempt_id = null;
        
        if ($atts['attempt'] === 'auto') {
            // Get the latest attempt for the current user
            $latest_attempt = new WP_Query(array(
                'post_type' => 'sep_attempts',
                'posts_per_page' => 1,
                'author' => $user_id,
                'meta_key' => '_sep_completed_at',
                'orderby' => 'meta_value',
                'order' => 'DESC'
            ));
            
            if ($latest_attempt->have_posts()) {
                $attempt_id = $latest_attempt->posts[0]->ID;
            }
        } else {
            $attempt_id = intval($atts['attempt']);
            // Verify this attempt belongs to the current user
            $attempt_user_id = get_post_meta($attempt_id, '_sep_user_id', true);
            if ($attempt_user_id != $user_id) {
                return '<p>' . __('You are not authorized to view this result.', 'sep-smart-exam') . '</p>';
            }
        }
        
        if (!$attempt_id) {
            return '<p>' . __('No exam attempts found.', 'sep-smart-exam') . '</p>';
        }
        
        $score = get_post_meta($attempt_id, '_sep_score', true);
        $percentage = get_post_meta($attempt_id, '_sep_percentage', true);
        $passed = get_post_meta($attempt_id, '_sep_passed', true);
        $total = get_post_meta($attempt_id, '_sep_total', true);
        $exam_id = get_post_meta($attempt_id, '_sep_exam_id', true);
        $exam_title = get_the_title($exam_id);
        
        ob_start();
        ?>
        <div class="sep-container">
            <div class="sep-results-container">
                <div class="sep-result-summary">
                    <div class="sep-score-circle <?php echo $passed ? 'pass' : 'fail'; ?>">
                        <?php echo $percentage; ?>%
                    </div>
                    <h2><?php echo $passed ? __('Congratulations!', 'sep-smart-exam') : __('Keep Practicing!', 'sep-smart-exam'); ?></h2>
                    <p><?php printf(__('You scored %s out of %s (%s%%)', 'sep-smart-exam'), $score, $total, $percentage); ?></p>
                    <p><?php echo $exam_title; ?></p>
                </div>
                
                <div class="sep-result-metrics">
                    <div class="sep-metric-card">
                        <div class="sep-metric-value"><?php echo $score; ?>/<?php echo $total; ?></div>
                        <div class="sep-metric-label"><?php _e('Score', 'sep-smart-exam'); ?></div>
                    </div>
                    <div class="sep-metric-card">
                        <div class="sep-metric-value"><?php echo $percentage; ?>%</div>
                        <div class="sep-metric-label"><?php _e('Percentage', 'sep-smart-exam'); ?></div>
                    </div>
                    <div class="sep-metric-card">
                        <div class="sep-metric-value"><?php echo $passed ? __('PASS', 'sep-smart-exam') : __('FAIL', 'sep-smart-exam'); ?></div>
                        <div class="sep-metric-label"><?php _e('Status', 'sep-smart-exam'); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * Dashboard shortcode - Displays student dashboard
     */
    public function dashboard_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to access your dashboard.', 'sep-smart-exam') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="sep-container">
            <div class="sep-dashboard-container">
                <h2><?php _e('Your Dashboard', 'sep-smart-exam'); ?></h2>
                
                <div class="sep-dashboard-grid">
                    <div class="sep-dashboard-card">
                        <h3><?php _e('Recent Activity', 'sep-smart-exam'); ?></h3>
                        <?php $this->render_recent_activity(); ?>
                    </div>
                    
                    <div class="sep-dashboard-card">
                        <h3><?php _e('Exam Progress', 'sep-smart-exam'); ?></h3>
                        <?php $this->render_exam_progress(); ?>
                    </div>
                </div>
                
                <div class="sep-dashboard-card">
                    <h3><?php _e('Available Exams', 'sep-smart-exam'); ?></h3>
                    <?php echo $this->exam_list_shortcode(array('limit' => 5)); ?>
                </div>
            </div>
        </div>
        <?php
        
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * Leaderboard shortcode - Displays rankings
     */
    public function leaderboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'all_india', // 'all_india', 'subject', 'exam'
            'subject' => '',
            'exam_id' => 0,
            'limit' => 10
        ), $atts);
        
        // For now, just show a placeholder - this would require more complex implementation
        ob_start();
        ?>
        <div class="sep-container">
            <div class="sep-leaderboard-container">
                <h2><?php _e('Leaderboard', 'sep-smart-exam'); ?></h2>
                <p><?php _e('Leaderboard functionality coming soon...', 'sep-smart-exam'); ?></p>
            </div>
        </div>
        <?php
        
        $content = ob_get_clean();
        return $content;
    }
    
    /**
     * Course shortcode - Displays a single course
     */
    public function course_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'show_curriculum' => 'true'
        ), $atts);

        if (!$atts['id']) {
            return '<p>' . __('Please specify a course ID.', 'sep-smart-exam-platform') . '</p>';
        }

        $course = get_post($atts['id']);
        if (!$course || $course->post_type !== 'sep_course') {
            return '<p>' . __('Course not found.', 'sep-smart-exam-platform') . '</p>';
        }

        // Get curriculum if needed
        $curriculum = array();
        if ($atts['show_curriculum'] === 'true') {
            $curriculum_obj = new SEP_Curriculum('sep-smart-exam-platform', '1.0.0');
            $curriculum = $curriculum_obj->get_course_curriculum($atts['id']);
        }

        ob_start();
        ?>
        <div class="sep-container">
            <div class="sep-course-container">
                <div class="sep-course-header">
                    <?php if (has_post_thumbnail($course->ID)) : ?>
                        <div class="sep-course-thumbnail">
                            <?php echo get_the_post_thumbnail($course->ID, 'large'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="sep-course-info">
                        <h1><?php echo esc_html($course->post_title); ?></h1>
                        <?php if (!empty($course->post_content)) : ?>
                            <div class="sep-course-description">
                                <?php echo apply_filters('the_content', $course->post_content); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="sep-course-meta">
                            <p><strong><?php _e('Duration:', 'sep-smart-exam-platform'); ?></strong> <?php echo get_post_meta($course->ID, '_sep_course_duration', true); ?></p>
                            <p><strong><?php _e('Lessons:', 'sep-smart-exam-platform'); ?></strong> <?php echo get_post_meta($course->ID, '_sep_course_lessons_count', true); ?></p>
                            <p><strong><?php _e('Quizzes:', 'sep-smart-exam-platform'); ?></strong> <?php echo get_post_meta($course->ID, '_sep_course_quizzes_count', true); ?></p>
                        </div>
                    </div>
                </div>
                
                <?php if ($atts['show_curriculum'] === 'true' && !empty($curriculum)) : ?>
                    <div class="sep-course-curriculum">
                        <h2><?php _e('Course Curriculum', 'sep-smart-exam-platform'); ?></h2>
                        <?php
                        $curriculum_obj = new SEP_Curriculum('sep-smart-exam-platform', '1.0.0');
                        echo $curriculum_obj->get_formatted_curriculum($atts['id']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="sep-course-actions">
                    <?php if (is_user_logged_in()) : ?>
                        <button class="sep-btn sep-btn-primary sep-enroll-course-btn" data-course-id="<?php echo $course->ID; ?>">
                            <?php _e('Enroll in Course', 'sep-smart-exam-platform'); ?>
                        </button>
                        <button class="sep-btn sep-start-course-btn" data-course-id="<?php echo $course->ID; ?>">
                            <?php _e('Start Learning', 'sep-smart-exam-platform'); ?>
                        </button>
                    <?php else : ?>
                        <a href="<?php echo wp_login_url(get_permalink($course->ID)); ?>" class="sep-btn sep-btn-primary">
                            <?php _e('Login to Enroll', 'sep-smart-exam-platform'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Curriculum shortcode - Displays curriculum for a course
     */
    public function curriculum_shortcode($atts) {
        $atts = shortcode_atts(array(
            'course_id' => 0
        ), $atts);

        if (!$atts['course_id']) {
            return '<p>' . __('Please specify a course ID.', 'sep-smart-exam-platform') . '</p>';
        }

        $curriculum_obj = new SEP_Curriculum('sep-smart-exam-platform', '1.0.0');
        $curriculum = $curriculum_obj->get_formatted_curriculum($atts['course_id']);

        return '<div class="sep-container"><div class="sep-curriculum-shortcode">' . $curriculum . '</div></div>';
    }
    
    /**
     * Render recent activity for dashboard
     */
    private function render_recent_activity() {
        $user_id = get_current_user_id();
        
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => 5,
            'author' => $user_id,
            'meta_key' => '_sep_completed_at',
            'orderby' => 'meta_value',
            'order' => 'DESC'
        ));
        
        if ($attempts->have_posts()) {
            echo '<ul>';
            while ($attempts->have_posts()) {
                $attempts->the_post();
                $exam_id = get_post_meta(get_the_ID(), '_sep_exam_id', true);
                $score = get_post_meta(get_the_ID(), '_sep_score', true);
                $percentage = get_post_meta(get_the_ID(), '_sep_percentage', true);
                $completed_at = get_post_meta(get_the_ID(), '_sep_completed_at', true);
                
                echo '<li>';
                echo '<strong>' . get_the_title($exam_id) . '</strong>';
                echo ' - ' . $score . '/' . get_post_meta($exam_id, '_sep_total', true);
                echo ' (' . $percentage . '%)';
                echo ' on ' . date('M j, Y', strtotime($completed_at));
                echo '</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No recent activity', 'sep-smart-exam') . '</p>';
        }
    }
    
    /**
     * Render exam progress for dashboard
     */
    private function render_exam_progress() {
        $user_id = get_current_user_id();
        
        // Get all exams
        $exams = get_posts(array(
            'post_type' => 'sep_exams',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        
        if (empty($exams)) {
            echo '<p>' . __('No exams available', 'sep-smart-exam') . '</p>';
            return;
        }
        
        echo '<ul>';
        foreach ($exams as $exam) {
            // Get user's best attempt for this exam
            $best_attempt = new WP_Query(array(
                'post_type' => 'sep_attempts',
                'posts_per_page' => 1,
                'author' => $user_id,
                'meta_query' => array(
                    array(
                        'key' => '_sep_exam_id',
                        'value' => $exam->ID,
                        'compare' => '='
                    )
                ),
                'meta_key' => '_sep_score',
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
            ));
            
            echo '<li>';
            echo '<a href="#" class="sep-start-exam-btn" data-exam-id="' . $exam->ID . '">' . $exam->post_title . '</a>';
            
            if ($best_attempt->have_posts()) {
                $best_attempt->the_post();
                $score = get_post_meta(get_the_ID(), '_sep_score', true);
                $percentage = get_post_meta(get_the_ID(), '_sep_percentage', true);
                $passed = get_post_meta(get_the_ID(), '_sep_passed', true);
                
                $status_class = $passed ? 'passed' : 'failed';
                $status_text = $passed ? __('Passed', 'sep-smart-exam') : __('Failed', 'sep-smart-exam');
                
                echo ' - <span class="sep-exam-status ' . $status_class . '">' . $status_text . ' (' . $percentage . '%)</span>';
                wp_reset_postdata();
            } else {
                echo ' - <span class="sep-exam-status not-taken">' . __('Not taken', 'sep-smart-exam') . '</span>';
            }
            
            echo '</li>';
        }
        echo '</ul>';
    }
}