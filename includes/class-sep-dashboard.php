<?php
class SEP_Dashboard {
    
    public function __construct() {
        add_action('init', array($this, 'register_dashboard_endpoints'));
        add_action('wp', array($this, 'handle_dashboard_page'));
        add_action('wp_ajax_sep_get_dashboard_data', array($this, 'handle_dashboard_ajax'));
        add_action('wp_ajax_nopriv_sep_get_dashboard_data', array($this, 'handle_dashboard_ajax'));
    }
    
    public function register_dashboard_endpoints() {
        // Add rewrite rules for dashboard pages if needed
        add_rewrite_rule(
            'sep-dashboard/([^/]+)/?$',
            'index.php?pagename=sep-dashboard&sep_user=$matches[1]',
            'top'
        );
    }
    
    public function handle_dashboard_page() {
        // Handle dashboard page requests if using custom endpoints
    }
    
    public function handle_dashboard_ajax() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'sep_nonce')) {
            wp_die(__('Security check failed', 'sep-smart-exam'));
        }
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(__('User not logged in', 'sep-smart-exam'));
        }
        
        // Get dashboard data
        $dashboard_data = array(
            'user_info' => $this->get_user_info($user_id),
            'exam_progress' => $this->get_user_exam_progress($user_id),
            'recent_activity' => $this->get_recent_activity($user_id),
            'achievements' => $this->get_user_achievements($user_id)
        );
        
        wp_send_json_success($dashboard_data);
    }
    
    /**
     * Get user information for dashboard
     */
    private function get_user_info($user_id) {
        $user = get_userdata($user_id);
        
        return array(
            'id' => $user_id,
            'name' => $user->display_name,
            'email' => $user->user_email,
            'join_date' => $user->user_registered,
            'exam_count' => $this->get_user_exam_count($user_id)
        );
    }
    
    /**
     * Get user's exam progress
     */
    private function get_user_exam_progress($user_id) {
        // Get all exams
        $all_exams = get_posts(array(
            'post_type' => 'sep_exams',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        
        $total_exams = count($all_exams);
        $completed_exams = 0;
        $passed_exams = 0;
        $total_score = 0;
        $exam_details = array();
        
        foreach ($all_exams as $exam) {
            $attempts = new WP_Query(array(
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
                'meta_key' => '_sep_completed_at',
                'orderby' => 'meta_value',
                'order' => 'DESC'
            ));
            
            if ($attempts->have_posts()) {
                $completed_exams++;
                $attempts->the_post();
                
                $score = get_post_meta(get_the_ID(), '_sep_score', true);
                $passed = get_post_meta(get_the_ID(), '_sep_passed', true);
                
                $total_score += floatval($score);
                
                if ($passed) {
                    $passed_exams++;
                }
                
                $exam_details[] = array(
                    'id' => $exam->ID,
                    'title' => $exam->post_title,
                    'score' => $score,
                    'passed' => $passed,
                    'percentage' => get_post_meta(get_the_ID(), '_sep_percentage', true)
                );
                
                wp_reset_postdata();
            } else {
                $exam_details[] = array(
                    'id' => $exam->ID,
                    'title' => $exam->post_title,
                    'score' => null,
                    'passed' => null,
                    'percentage' => null
                );
            }
        }
        
        $avg_score = $completed_exams > 0 ? $total_score / $completed_exams : 0;
        
        return array(
            'total_exams' => $total_exams,
            'completed_exams' => $completed_exams,
            'passed_exams' => $passed_exams,
            'completion_rate' => $total_exams > 0 ? round(($completed_exams / $total_exams) * 100, 2) : 0,
            'pass_rate' => $completed_exams > 0 ? round(($passed_exams / $completed_exams) * 100, 2) : 0,
            'avg_score' => round($avg_score, 2),
            'exams' => $exam_details
        );
    }
    
    /**
     * Get user's recent activity
     */
    private function get_recent_activity($user_id) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => 10,
            'author' => $user_id,
            'meta_key' => '_sep_completed_at',
            'orderby' => 'meta_value',
            'order' => 'DESC'
        ));
        
        $activity = array();
        
        if ($attempts->have_posts()) {
            while ($attempts->have_posts()) {
                $attempts->the_post();
                
                $exam_id = get_post_meta(get_the_ID(), '_sep_exam_id', true);
                
                $activity[] = array(
                    'attempt_id' => get_the_ID(),
                    'exam_id' => $exam_id,
                    'exam_title' => get_the_title($exam_id),
                    'score' => get_post_meta(get_the_ID(), '_sep_score', true),
                    'percentage' => get_post_meta(get_the_ID(), '_sep_percentage', true),
                    'passed' => get_post_meta(get_the_ID(), '_sep_passed', true),
                    'completed_at' => get_post_meta(get_the_ID(), '_sep_completed_at', true)
                );
            }
            wp_reset_postdata();
        }
        
        return $activity;
    }
    
    /**
     * Get user's achievements
     */
    private function get_user_achievements($user_id) {
        // Placeholder for achievements system
        // This could include badges, certificates, etc.
        
        return array(
            'total_exams_taken' => $this->get_user_exam_count($user_id),
            'exams_passed' => $this->get_user_passed_exam_count($user_id),
            'perfect_scores' => $this->get_user_perfect_score_count($user_id),
            'streak' => $this->get_user_streak($user_id)
        );
    }
    
    /**
     * Get count of exams taken by user
     */
    private function get_user_exam_count($user_id) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => -1,
            'author' => $user_id,
            'fields' => 'ids'
        ));
        
        return $attempts->post_count;
    }
    
    /**
     * Get count of exams passed by user
     */
    private function get_user_passed_exam_count($user_id) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => -1,
            'author' => $user_id,
            'meta_query' => array(
                array(
                    'key' => '_sep_passed',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'fields' => 'ids'
        ));
        
        return $attempts->post_count;
    }
    
    /**
     * Get count of perfect scores by user
     */
    private function get_user_perfect_score_count($user_id) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => -1,
            'author' => $user_id,
            'meta_query' => array(
                array(
                    'key' => '_sep_percentage',
                    'value' => '100',
                    'compare' => '>='
                )
            ),
            'fields' => 'ids'
        ));
        
        return $attempts->post_count;
    }
    
    /**
     * Get user's current streak (consecutive exams passed)
     */
    private function get_user_streak($user_id) {
        // This would require more complex logic to track consecutive passes
        // For now, returning a placeholder
        return 0;
    }
}