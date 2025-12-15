<?php
class SEP_Utilities {
    
    public function __construct() {
        // Initialize utility functions
    }
    
    /**
     * Format time in seconds to H:i:s format
     */
    public static function format_time($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
    }
    
    /**
     * Sanitize exam options
     */
    public static function sanitize_options($options) {
        $sanitized = array();
        
        foreach ($options as $key => $value) {
            $sanitized[$key] = sanitize_text_field($value);
        }
        
        return $sanitized;
    }
    
    /**
     * Validate exam settings
     */
    public static function validate_exam_settings($settings) {
        $errors = array();
        
        // Validate duration
        if (isset($settings['duration']) && $settings['duration'] < 1) {
            $errors[] = __('Exam duration must be at least 1 minute', 'sep-smart-exam');
        }
        
        // Validate pass percentage
        if (isset($settings['pass_percentage']) && ($settings['pass_percentage'] < 0 || $settings['pass_percentage'] > 100)) {
            $errors[] = __('Pass percentage must be between 0 and 100', 'sep-smart-exam');
        }
        
        // Validate max attempts
        if (isset($settings['max_attempts']) && $settings['max_attempts'] < 1) {
            $errors[] = __('Max attempts must be at least 1', 'sep-smart-exam');
        }
        
        return array(
            'valid' => empty($errors),
            'errors' => $errors
        );
    }
    
    /**
     * Get exam statistics
     */
    public static function get_exam_statistics($exam_id) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_sep_exam_id',
                    'value' => $exam_id,
                    'compare' => '='
                )
            )
        ));
        
        if ($attempts->post_count === 0) {
            return array(
                'total_attempts' => 0,
                'average_score' => 0,
                'pass_rate' => 0,
                'highest_score' => 0,
                'lowest_score' => 0
            );
        }
        
        $scores = array();
        $passed_count = 0;
        
        while ($attempts->have_posts()) {
            $attempts->the_post();
            $score = get_post_meta(get_the_ID(), '_sep_score', true);
            $passed = get_post_meta(get_the_ID(), '_sep_passed', true);
            
            $scores[] = floatval($score);
            
            if ($passed) {
                $passed_count++;
            }
        }
        wp_reset_postdata();
        
        return array(
            'total_attempts' => count($scores),
            'average_score' => round(array_sum($scores) / count($scores), 2),
            'pass_rate' => round(($passed_count / count($scores)) * 100, 2),
            'highest_score' => max($scores),
            'lowest_score' => min($scores)
        );
    }
    
    /**
     * Export exam results to CSV
     */
    public static function export_exam_results_to_csv($exam_id) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_sep_exam_id',
                    'value' => $exam_id,
                    'compare' => '='
                )
            ),
            'orderby' => 'meta_value',
            'meta_key' => '_sep_completed_at',
            'order' => 'ASC'
        ));
        
        if ($attempts->post_count === 0) {
            return false;
        }
        
        $filename = 'exam_results_' . $exam_id . '_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = wp_upload_dir()['path'] . '/' . $filename;
        
        $file = fopen($filepath, 'w');
        
        // Write CSV header
        fputcsv($file, array(
            'Student Name',
            'Email',
            'Exam Title',
            'Score',
            'Percentage',
            'Passed',
            'Time Taken (seconds)',
            'Started At',
            'Completed At'
        ));
        
        while ($attempts->have_posts()) {
            $attempts->the_post();
            
            $user_id = get_post_meta(get_the_ID(), '_sep_user_id', true);
            $user = get_userdata($user_id);
            $exam = get_post(get_post_meta(get_the_ID(), '_sep_exam_id', true));
            
            $row = array(
                $user ? $user->display_name : 'N/A',
                $user ? $user->user_email : 'N/A',
                $exam ? $exam->post_title : 'N/A',
                get_post_meta(get_the_ID(), '_sep_score', true),
                get_post_meta(get_the_ID(), '_sep_percentage', true),
                get_post_meta(get_the_ID(), '_sep_passed', true) ? 'Yes' : 'No',
                get_post_meta(get_the_ID(), '_sep_time_taken', true),
                get_post_meta(get_the_ID(), '_sep_started_at', true),
                get_post_meta(get_the_ID(), '_sep_completed_at', true)
            );
            
            fputcsv($file, $row);
        }
        wp_reset_postdata();
        
        fclose($file);
        
        return $filepath;
    }
    
    /**
     * Import questions from CSV
     */
    public static function import_questions_from_csv($file_path, $exam_id = null) {
        if (!file_exists($file_path)) {
            return array('success' => false, 'message' => 'File not found');
        }
        
        $file = fopen($file_path, 'r');
        if (!$file) {
            return array('success' => false, 'message' => 'Could not open file');
        }
        
        // Read header to determine columns
        $header = fgetcsv($file);
        if (!$header) {
            fclose($file);
            return array('success' => false, 'message' => 'Invalid CSV format');
        }
        
        $imported_count = 0;
        $error_count = 0;
        $errors = array();
        
        while (($row = fgetcsv($file)) !== false) {
            // Map CSV columns to question fields
            // Assuming standard format: Question, Option A, Option B, Option C, Option D, Option E (optional), Correct Answer, Explanation
            if (count($row) < 6) {
                $error_count++;
                $errors[] = "Row has insufficient data: " . implode(', ', $row);
                continue;
            }
            
            $question_data = array(
                'question' => $row[0] ?? '',
                'option_a' => $row[1] ?? '',
                'option_b' => $row[2] ?? '',
                'option_c' => $row[3] ?? '',
                'option_d' => $row[4] ?? '',
                'option_e' => $row[5] ?? '', // Optional
                'correct_answer' => $row[6] ?? '',
                'explanation' => $row[7] ?? '',
                'marks' => $row[8] ?? 1,
                'negative_marks' => $row[9] ?? 0
            );
            
            // Create question post
            $question_post = array(
                'post_title' => wp_strip_all_tags($question_data['question']),
                'post_content' => '', // We'll store question content in meta
                'post_type' => 'sep_questions',
                'post_status' => 'publish'
            );
            
            $question_id = wp_insert_post($question_post);
            
            if (is_wp_error($question_id)) {
                $error_count++;
                $errors[] = "Error creating question: " . $question_id->get_error_message();
                continue;
            }
            
            // Save question meta
            update_post_meta($question_id, '_sep_question_content', $question_data['question']);
            update_post_meta($question_id, '_sep_option_a', $question_data['option_a']);
            update_post_meta($question_id, '_sep_option_b', $question_data['option_b']);
            update_post_meta($question_id, '_sep_option_c', $question_data['option_c']);
            update_post_meta($question_id, '_sep_option_d', $question_data['option_d']);
            update_post_meta($question_id, '_sep_option_e', $question_data['option_e']);
            update_post_meta($question_id, '_sep_correct_answer', $question_data['correct_answer']);
            update_post_meta($question_id, '_sep_explanation', $question_data['explanation']);
            update_post_meta($question_id, '_sep_marks', $question_data['marks']);
            update_post_meta($question_id, '_sep_negative_marks', $question_data['negative_marks']);
            
            // Set option E enabled if option E exists
            if (!empty($question_data['option_e'])) {
                update_post_meta($question_id, '_sep_option_e_enabled', 'on');
            }
            
            // Associate with exam if specified
            if ($exam_id) {
                update_post_meta($question_id, '_sep_exam_id', $exam_id);
            }
            
            $imported_count++;
        }
        
        fclose($file);
        
        return array(
            'success' => true,
            'imported_count' => $imported_count,
            'error_count' => $error_count,
            'errors' => $errors
        );
    }
    
    /**
     * Get ranking for an exam
     */
    public static function get_exam_ranking($exam_id, $limit = 10) {
        $attempts = new WP_Query(array(
            'post_type' => 'sep_attempts',
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_sep_exam_id',
                    'value' => $exam_id,
                    'compare' => '='
                ),
                array(
                    'key' => '_sep_percentage',
                    'compare' => 'EXISTS'
                )
            ),
            'meta_key' => '_sep_percentage',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ));
        
        $rankings = array();
        $rank = 1;
        
        while ($attempts->have_posts()) {
            $attempts->the_post();
            
            $user_id = get_post_meta(get_the_ID(), '_sep_user_id', true);
            $user = get_userdata($user_id);
            
            $rankings[] = array(
                'rank' => $rank,
                'user_id' => $user_id,
                'user_name' => $user ? $user->display_name : 'Anonymous',
                'score' => get_post_meta(get_the_ID(), '_sep_score', true),
                'percentage' => get_post_meta(get_the_ID(), '_sep_percentage', true),
                'attempt_date' => get_post_meta(get_the_ID(), '_sep_completed_at', true)
            );
            
            $rank++;
        }
        wp_reset_postdata();
        
        return $rankings;
    }
}