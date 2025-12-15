<?php
class SEP_Attempts {
    
    public function __construct() {
        // Add hooks for attempt management
        add_action('init', array($this, 'register_attempt_post_type'));
        add_action('wp_ajax_sep_get_attempt_results', array($this, 'handle_get_attempt_results'));
        add_action('wp_ajax_nopriv_sep_get_attempt_results', array($this, 'handle_get_attempt_results'));
    }
    
    public function register_attempt_post_type() {
        // This is handled in the core class, but we can add specific functionality here if needed
    }
    
    public function handle_get_attempt_results() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'sep_nonce')) {
            wp_die(__('Security check failed', 'sep-smart-exam'));
        }
        
        $attempt_id = intval($_POST['attempt_id']);
        $user_id = get_current_user_id();
        
        // Verify this attempt belongs to current user
        $attempt_user_id = get_post_meta($attempt_id, '_sep_user_id', true);
        if ($attempt_user_id != $user_id) {
            wp_send_json_error(__('Invalid attempt', 'sep-smart-exam'));
        }
        
        // Get attempt data
        $attempt_data = array(
            'id' => $attempt_id,
            'exam_id' => get_post_meta($attempt_id, '_sep_exam_id', true),
            'score' => get_post_meta($attempt_id, '_sep_score', true),
            'percentage' => get_post_meta($attempt_id, '_sep_percentage', true),
            'passed' => get_post_meta($attempt_id, '_sep_passed', true),
            'time_taken' => get_post_meta($attempt_id, '_sep_time_taken', true),
            'started_at' => get_post_meta($attempt_id, '_sep_started_at', true),
            'completed_at' => get_post_meta($attempt_id, '_sep_completed_at', true),
            'status' => get_post_meta($attempt_id, '_sep_status', true)
        );
        
        wp_send_json_success($attempt_data);
    }
    
    /**
     * Get user's attempts for a specific exam
     */
    public function get_user_attempts($user_id, $exam_id) {
        $args = array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_sep_user_id',
                    'value' => $user_id,
                    'compare' => '='
                ),
                array(
                    'key' => '_sep_exam_id',
                    'value' => $exam_id,
                    'compare' => '='
                )
            ),
            'orderby' => 'meta_value',
            'meta_key' => '_sep_started_at',
            'order' => 'DESC'
        );
        
        return get_posts($args);
    }
    
    /**
     * Get user's best attempt for a specific exam
     */
    public function get_best_attempt($user_id, $exam_id) {
        $attempts = $this->get_user_attempts($user_id, $exam_id);
        
        if (empty($attempts)) {
            return null;
        }
        
        $best_attempt = null;
        $best_score = -1;
        
        foreach ($attempts as $attempt) {
            $score = get_post_meta($attempt->ID, '_sep_score', true);
            if ($score > $best_score) {
                $best_score = $score;
                $best_attempt = $attempt;
            }
        }
        
        return $best_attempt;
    }
    
    /**
     * Get exam statistics for a user
     */
    public function get_user_exam_stats($user_id, $exam_id) {
        $attempts = $this->get_user_attempts($user_id, $exam_id);
        
        if (empty($attempts)) {
            return array(
                'total_attempts' => 0,
                'best_score' => 0,
                'average_score' => 0,
                'passed_count' => 0,
                'last_attempt_date' => null
            );
        }
        
        $total_score = 0;
        $best_score = 0;
        $passed_count = 0;
        $last_attempt_date = null;
        
        foreach ($attempts as $attempt) {
            $score = floatval(get_post_meta($attempt->ID, '_sep_score', true));
            $passed = get_post_meta($attempt->ID, '_sep_passed', true);
            $date = get_post_meta($attempt->ID, '_sep_completed_at', true);
            
            $total_score += $score;
            
            if ($score > $best_score) {
                $best_score = $score;
            }
            
            if ($passed) {
                $passed_count++;
            }
            
            if (!$last_attempt_date || $date > $last_attempt_date) {
                $last_attempt_date = $date;
            }
        }
        
        return array(
            'total_attempts' => count($attempts),
            'best_score' => $best_score,
            'average_score' => round($total_score / count($attempts), 2),
            'passed_count' => $passed_count,
            'last_attempt_date' => $last_attempt_date
        );
    }
}