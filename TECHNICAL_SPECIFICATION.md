# SEP (Smart Exam Platform) - Technical Specification

## Project Structure
```
sep-smart-exam-platform/
├── sep-smart-exam-platform.php          # Main plugin file
├── includes/                            # Backend logic
│   ├── class-sep-core.php              # Core functionality
│   ├── class-sep-exams.php             # Exam management
│   ├── class-sep-questions.php         # Question management
│   ├── class-sep-attempts.php          # Attempt tracking
│   ├── class-sep-shortcodes.php        # Shortcode handlers
│   ├── class-sep-dashboard.php         # Dashboard functionality
│   └── class-sep-utilities.php         # Utility functions
├── assets/                              # Static assets
│   ├── css/
│   │   ├── sep-admin.css              # Admin styles
│   │   └── sep-frontend.css           # Frontend styles
│   ├── js/
│   │   ├── sep-admin.js               # Admin scripts
│   │   ├── sep-frontend.js            # Frontend scripts
│   │   ├── sep-exam-timer.js          # Timer functionality
│   │   └── sep-mobile-optimizations.js # Mobile enhancements
│   └── images/
├── templates/                           # Template files
│   ├── exam-single.php                 # Single exam display
│   ├── exam-list.php                   # Exam listing
│   ├── result-display.php              # Result page
│   └── dashboard.php                   # Student dashboard
├── languages/                          # Translation files
├── admin/                              # Admin interface
│   ├── class-sep-admin.php             # Admin menu and settings
│   ├── views/                          # Admin views
│   └── assets/                         # Admin assets
└── readme.txt                          # WordPress plugin info
```

## Database Schema

### Custom Post Types
1. **sep_exams** - Exam posts
2. **sep_questions** - Question posts
3. **sep_attempts** - User attempts

### Custom Taxonomies
1. **sep_subjects** - Academic subjects
2. **sep_chapters** - Chapters/topics
3. **sep_difficulty** - Difficulty levels
4. **sep_question_types** - MCQ, True/False, etc.

### Meta Fields
```sql
-- Exam meta fields
sep_exam_duration
sep_total_questions
sep_pass_percentage
sep_negative_marking
sep_randomize_questions
sep_randomize_options
sep_show_correct_immediately
sep_allow_review
sep_retake_allowed
sep_max_attempts

-- Question meta fields
sep_question_type
sep_difficulty_level
sep_subject
sep_chapter
sep_marks
sep_negative_marks
sep_explanation
sep_option_e_enabled

-- Attempt meta fields
sep_exam_id
sep_user_id
sep_answers
sep_score
sep_percentage
sep_time_taken
sep_started_at
sep_completed_at
sep_status
```

## Main Plugin File

### sep-smart-exam-platform.php
```php
<?php
/**
 * Plugin Name: Smart Exam Platform (SEP)
 * Description: Advanced WordPress LMS plugin for competitive exam preparation targeting Banking, SSC, JAIIB, and other Indian competitive exams.
 * Version: 1.0.0
 * Author: Your Company
 * Text Domain: sep-smart-exam
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('SEP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SEP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SEP_VERSION', '1.0.0');

// Load plugin files
require_once SEP_PLUGIN_PATH . 'includes/class-sep-core.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-exams.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-questions.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-attempts.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-shortcodes.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-dashboard.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-utilities.php';

// Initialize plugin
class SEP_Smart_Exam_Platform {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Initialize core components
        new SEP_Core();
        new SEP_Exams();
        new SEP_Questions();
        new SEP_Attempts();
        new SEP_Shortcodes();
        new SEP_Dashboard();
        
        // Load text domain for translations
        load_plugin_textdomain('sep-smart-exam', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    public function activate() {
        // Create custom post types and taxonomies
        $this->init();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Create necessary database tables if needed
        $this->create_tables();
    }
    
    public function deactivate() {
        // Cleanup if necessary
        flush_rewrite_rules();
    }
    
    private function create_tables() {
        global $wpdb;
        
        // Create custom tables if needed (though we'll primarily use CPTs)
        // Additional analytics or ranking tables could go here
    }
}

// Initialize the plugin
new SEP_Smart_Exam_Platform();
```

## Core Functionality Class

### includes/class-sep-core.php
```php
<?php
class SEP_Core {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function register_post_types() {
        // Register exams post type
        register_post_type('sep_exams', array(
            'labels' => array(
                'name' => __('Exams', 'sep-smart-exam'),
                'singular_name' => __('Exam', 'sep-smart-exam'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'exams'),
            'supports' => array('title', 'editor', 'custom-fields'),
            'show_in_rest' => true,
        ));
        
        // Register questions post type
        register_post_type('sep_questions', array(
            'labels' => array(
                'name' => __('Questions', 'sep-smart-exam'),
                'singular_name' => __('Question', 'sep-smart-exam'),
            ),
            'public' => false, // Not publicly accessible
            'show_ui' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
            'show_in_rest' => true,
        ));
        
        // Register attempts post type
        register_post_type('sep_attempts', array(
            'labels' => array(
                'name' => __('Attempts', 'sep-smart-exam'),
                'singular_name' => __('Attempt', 'sep-smart-exam'),
            ),
            'public' => false, // Not publicly accessible
            'show_ui' => false, // Hidden from admin menu
            'supports' => array(),
            'capability_type' => 'post',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
        ));
    }
    
    public function register_taxonomies() {
        // Register subjects taxonomy
        register_taxonomy('sep_subjects', 'sep_exams', array(
            'labels' => array(
                'name' => __('Subjects', 'sep-smart-exam'),
                'singular_name' => __('Subject', 'sep-smart-exam'),
            ),
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
        ));
        
        // Register chapters taxonomy
        register_taxonomy('sep_chapters', 'sep_questions', array(
            'labels' => array(
                'name' => __('Chapters', 'sep-smart-exam'),
                'singular_name' => __('Chapter', 'sep-smart-exam'),
            ),
            'hierarchical' => true,
            'public' => false,
            'show_in_rest' => true,
        ));
        
        // Register difficulty levels
        register_taxonomy('sep_difficulty', array('sep_questions'), array(
            'labels' => array(
                'name' => __('Difficulty Levels', 'sep-smart-exam'),
                'singular_name' => __('Difficulty', 'sep-smart-exam'),
            ),
            'hierarchical' => false,
            'public' => false,
            'show_in_rest' => true,
        ));
        
        // Register question types
        register_taxonomy('sep_question_types', 'sep_questions', array(
            'labels' => array(
                'name' => __('Question Types', 'sep-smart-exam'),
                'singular_name' => __('Question Type', 'sep-smart-exam'),
            ),
            'hierarchical' => false,
            'public' => false,
            'show_in_rest' => true,
        ));
    }
    
    public function enqueue_frontend_assets() {
        wp_enqueue_style('sep-frontend', SEP_PLUGIN_URL . 'assets/css/sep-frontend.css', array(), SEP_VERSION);
        wp_enqueue_script('sep-frontend', SEP_PLUGIN_URL . 'assets/js/sep-frontend.js', array('jquery'), SEP_VERSION, true);
        
        // Localize script for AJAX
        wp_localize_script('sep-frontend', 'sep_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sep_nonce')
        ));
    }
    
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'sep') !== false) {
            wp_enqueue_style('sep-admin', SEP_PLUGIN_URL . 'assets/css/sep-admin.css', array(), SEP_VERSION);
            wp_enqueue_script('sep-admin', SEP_PLUGIN_URL . 'assets/js/sep-admin.js', array('jquery'), SEP_VERSION, true);
        }
    }
}
```

## Exam Management Class

### includes/class-sep-exams.php
```php
<?php
class SEP_Exams {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_exam_metaboxes'));
        add_action('save_post_sep_exams', array($this, 'save_exam_meta'));
        add_action('wp_ajax_sep_start_exam', array($this, 'handle_start_exam'));
        add_action('wp_ajax_nopriv_sep_start_exam', array($this, 'handle_start_exam'));
        add_action('wp_ajax_sep_submit_exam', array($this, 'handle_submit_exam'));
        add_action('wp_ajax_nopriv_sep_submit_exam', array($this, 'handle_submit_exam'));
    }
    
    public function add_exam_metaboxes() {
        add_meta_box(
            'sep_exam_settings',
            __('Exam Settings', 'sep-smart-exam'),
            array($this, 'exam_settings_callback'),
            'sep_exams',
            'normal',
            'high'
        );
    }
    
    public function exam_settings_callback($post) {
        wp_nonce_field('sep_save_exam_meta', 'sep_exam_meta_nonce');
        
        $duration = get_post_meta($post->ID, '_sep_exam_duration', true);
        $total_questions = get_post_meta($post->ID, '_sep_total_questions', true);
        $pass_percentage = get_post_meta($post->ID, '_sep_pass_percentage', true);
        $negative_marking = get_post_meta($post->ID, '_sep_negative_marking', true);
        $randomize_questions = get_post_meta($post->ID, '_sep_randomize_questions', true);
        $randomize_options = get_post_meta($post->ID, '_sep_randomize_options', true);
        $show_correct_immediately = get_post_meta($post->ID, '_sep_show_correct_immediately', true);
        $allow_review = get_post_meta($post->ID, '_sep_allow_review', true);
        $retake_allowed = get_post_meta($post->ID, '_sep_retake_allowed', true);
        $max_attempts = get_post_meta($post->ID, '_sep_max_attempts', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="sep_exam_duration"><?php _e('Duration (minutes)', 'sep-smart-exam'); ?></label></th>
                <td><input type="number" id="sep_exam_duration" name="sep_exam_duration" value="<?php echo esc_attr($duration); ?>" min="1" /></td>
            </tr>
            <tr>
                <th><label for="sep_total_questions"><?php _e('Total Questions', 'sep-smart-exam'); ?></label></th>
                <td><input type="number" id="sep_total_questions" name="sep_total_questions" value="<?php echo esc_attr($total_questions); ?>" min="1" readonly /></td>
            </tr>
            <tr>
                <th><label for="sep_pass_percentage"><?php _e('Pass Percentage', 'sep-smart-exam'); ?></label></th>
                <td><input type="number" id="sep_pass_percentage" name="sep_pass_percentage" value="<?php echo esc_attr($pass_percentage); ?>" min="0" max="100" step="0.01" /></td>
            </tr>
            <tr>
                <th><?php _e('Negative Marking', 'sep-smart-exam'); ?></th>
                <td>
                    <label><input type="checkbox" name="sep_negative_marking" <?php checked($negative_marking, 'on'); ?> /> <?php _e('Enable negative marking', 'sep-smart-exam'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Randomize Questions', 'sep-smart-exam'); ?></th>
                <td>
                    <label><input type="checkbox" name="sep_randomize_questions" <?php checked($randomize_questions, 'on'); ?> /> <?php _e('Randomize question order', 'sep-smart-exam'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Randomize Options', 'sep-smart-exam'); ?></th>
                <td>
                    <label><input type="checkbox" name="sep_randomize_options" <?php checked($randomize_options, 'on'); ?> /> <?php _e('Randomize option order', 'sep-smart-exam'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Show Correct Immediately', 'sep-smart-exam'); ?></th>
                <td>
                    <label><input type="checkbox" name="sep_show_correct_immediately" <?php checked($show_correct_immediately, 'on'); ?> /> <?php _e('Show correct answer immediately after submission', 'sep-smart-exam'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Allow Review', 'sep-smart-exam'); ?></th>
                <td>
                    <label><input type="checkbox" name="sep_allow_review" <?php checked($allow_review, 'on'); ?> /> <?php _e('Allow student to review answers during exam', 'sep-smart-exam'); ?></label>
                </td>
            </tr>
            <tr>
                <th><?php _e('Retake Allowed', 'sep-smart-exam'); ?></th>
                <td>
                    <label><input type="checkbox" name="sep_retake_allowed" <?php checked($retake_allowed, 'on'); ?> /> <?php _e('Allow retakes', 'sep-smart-exam'); ?></label>
                </td>
            </tr>
            <tr>
                <th><label for="sep_max_attempts"><?php _e('Max Attempts', 'sep-smart-exam'); ?></label></th>
                <td><input type="number" id="sep_max_attempts" name="sep_max_attempts" value="<?php echo esc_attr($max_attempts ? $max_attempts : 1); ?>" min="1" /></td>
            </tr>
        </table>
        <?php
    }
    
    public function save_exam_meta($post_id) {
        if (!isset($_POST['sep_exam_meta_nonce']) || !wp_verify_nonce($_POST['sep_exam_meta_nonce'], 'sep_save_exam_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save meta fields
        $meta_fields = array(
            'sep_exam_duration',
            'sep_total_questions', 
            'sep_pass_percentage',
            'sep_max_attempts'
        );
        
        foreach ($meta_fields as $field) {
            $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            update_post_meta($post_id, '_'.$field, $value);
        }
        
        // Handle checkbox fields
        $checkbox_fields = array(
            'sep_negative_marking',
            'sep_randomize_questions',
            'sep_randomize_options',
            'sep_show_correct_immediately',
            'sep_allow_review',
            'sep_retake_allowed'
        );
        
        foreach ($checkbox_fields as $field) {
            $value = isset($_POST[$field]) ? 'on' : 'off';
            update_post_meta($post_id, '_'.$field, $value);
        }
    }
    
    public function handle_start_exam() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'sep_nonce')) {
            wp_die(__('Security check failed', 'sep-smart-exam'));
        }
        
        $exam_id = intval($_POST['exam_id']);
        $user_id = get_current_user_id();
        
        // Check if user can take the exam
        if (!$this->can_take_exam($exam_id, $user_id)) {
            wp_send_json_error(__('Cannot take this exam', 'sep-smart-exam'));
        }
        
        // Create attempt record
        $attempt_id = wp_insert_post(array(
            'post_type' => 'sep_attempts',
            'post_status' => 'publish',
            'post_title' => sprintf(__('Attempt by %s for %s', 'sep-smart-exam'), get_userdata($user_id)->display_name, get_the_title($exam_id)),
        ));
        
        if (is_wp_error($attempt_id)) {
            wp_send_json_error(__('Failed to create exam attempt', 'sep-smart-exam'));
        }
        
        // Set attempt metadata
        update_post_meta($attempt_id, '_sep_exam_id', $exam_id);
        update_post_meta($attempt_id, '_sep_user_id', $user_id);
        update_post_meta($attempt_id, '_sep_status', 'in_progress');
        update_post_meta($attempt_id, '_sep_started_at', current_time('mysql'));
        
        // Get exam settings
        $exam_data = array(
            'id' => $exam_id,
            'title' => get_the_title($exam_id),
            'duration' => get_post_meta($exam_id, '_sep_exam_duration', true),
            'attempt_id' => $attempt_id,
            'questions' => $this->get_exam_questions($exam_id)
        );
        
        wp_send_json_success($exam_data);
    }
    
    public function handle_submit_exam() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'sep_nonce')) {
            wp_die(__('Security check failed', 'sep-smart-exam'));
        }
        
        $attempt_id = intval($_POST['attempt_id']);
        $answers = $_POST['answers'];
        $time_taken = intval($_POST['time_taken']);
        $user_id = get_current_user_id();
        
        // Verify this attempt belongs to current user
        $attempt_user_id = get_post_meta($attempt_id, '_sep_user_id', true);
        if ($attempt_user_id != $user_id) {
            wp_send_json_error(__('Invalid attempt', 'sep-smart-exam'));
        }
        
        // Update attempt status
        wp_update_post(array(
            'ID' => $attempt_id,
            'post_status' => 'completed'
        ));
        
        update_post_meta($attempt_id, '_sep_answers', $answers);
        update_post_meta($attempt_id, '_sep_time_taken', $time_taken);
        update_post_meta($attempt_id, '_sep_completed_at', current_time('mysql'));
        update_post_meta($attempt_id, '_sep_status', 'submitted');
        
        // Calculate score
        $score_data = $this->calculate_score($attempt_id);
        update_post_meta($attempt_id, '_sep_score', $score_data['score']);
        update_post_meta($attempt_id, '_sep_percentage', $score_data['percentage']);
        update_post_meta($attempt_id, '_sep_passed', $score_data['passed']);
        
        wp_send_json_success($score_data);
    }
    
    private function can_take_exam($exam_id, $user_id) {
        // Check if exam exists and is published
        $exam = get_post($exam_id);
        if (!$exam || $exam->post_status !== 'publish') {
            return false;
        }
        
        // Check if retakes are allowed
        $retake_allowed = get_post_meta($exam_id, '_sep_retake_allowed', true);
        $max_attempts = intval(get_post_meta($exam_id, '_sep_max_attempts', true));
        
        if (!$retake_allowed && $max_attempts <= 1) {
            // Check if user already took this exam
            $existing_attempts = new WP_Query(array(
                'post_type' => 'sep_attempts',
                'posts_per_page' => 1,
                'meta_query' => array(
                    array(
                        'key' => '_sep_exam_id',
                        'value' => $exam_id,
                        'compare' => '='
                    ),
                    array(
                        'key' => '_sep_user_id',
                        'value' => $user_id,
                        'compare' => '='
                    )
                )
            ));
            
            if ($existing_attempts->have_posts()) {
                return false;
            }
        } else {
            // Count existing attempts
            $existing_attempts = new WP_Query(array(
                'post_type' => 'sep_attempts',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_sep_exam_id',
                        'value' => $exam_id,
                        'compare' => '='
                    ),
                    array(
                        'key' => '_sep_user_id',
                        'value' => $user_id,
                        'compare' => '='
                    )
                )
            ));
            
            if ($existing_attempts->post_count >= $max_attempts) {
                return false;
            }
        }
        
        return true;
    }
    
    private function get_exam_questions($exam_id) {
        // Get all questions associated with this exam
        // This would depend on how you associate questions with exams
        // For now, we'll assume they're connected via a relationship field or taxonomy
        
        $args = array(
            'post_type' => 'sep_questions',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_sep_exam_id', // Assuming there's a connection
                    'value' => $exam_id,
                    'compare' => '='
                )
            )
        );
        
        $questions = get_posts($args);
        $result = array();
        
        foreach ($questions as $question) {
            $result[] = array(
                'id' => $question->ID,
                'title' => $question->post_title,
                'content' => $question->post_content,
                'type' => get_post_meta($question->ID, '_sep_question_type', true),
                'options' => array(
                    'a' => get_post_meta($question->ID, '_sep_option_a', true),
                    'b' => get_post_meta($question->ID, '_sep_option_b', true),
                    'c' => get_post_meta($question->ID, '_sep_option_c', true),
                    'd' => get_post_meta($question->ID, '_sep_option_d', true),
                    'e' => get_post_meta($question->ID, '_sep_option_e', true), // Optional 5th option
                ),
                'correct_answer' => get_post_meta($question->ID, '_sep_correct_answer', true),
                'marks' => get_post_meta($question->ID, '_sep_marks', true),
                'negative_marks' => get_post_meta($question->ID, '_sep_negative_marks', true),
                'explanation' => get_post_meta($question->ID, '_sep_explanation', true),
                'option_e_enabled' => get_post_meta($question->ID, '_sep_option_e_enabled', true) === 'on'
            );
        }
        
        // Randomize if needed
        $randomize_questions = get_post_meta($exam_id, '_sep_randomize_questions', true);
        if ($randomize_questions === 'on') {
            shuffle($result);
        }
        
        return $result;
    }
    
    private function calculate_score($attempt_id) {
        $exam_id = get_post_meta($attempt_id, '_sep_exam_id', true);
        $answers = get_post_meta($attempt_id, '_sep_answers', true);
        $questions = $this->get_exam_questions($exam_id);
        
        $score = 0;
        $total_marks = 0;
        $correct_count = 0;
        
        foreach ($questions as $index => $question) {
            $question_id = $question['id'];
            $user_answer = isset($answers[$index]) ? $answers[$index] : '';
            $correct_answer = $question['correct_answer'];
            $marks = floatval($question['marks'] ?: 1);
            $negative_marks = floatval($question['negative_marks'] ?: 0);
            
            $total_marks += $marks;
            
            if (strtolower($user_answer) === strtolower($correct_answer)) {
                $score += $marks;
                $correct_count++;
            } else if ($user_answer && $negative_marks > 0) {
                // Apply negative marking if user answered incorrectly
                $score -= $negative_marks;
            }
        }
        
        // Ensure score doesn't go negative
        $score = max(0, $score);
        
        $percentage = $total_marks > 0 ? ($score / $total_marks) * 100 : 0;
        
        // Check if passed
        $pass_percentage = floatval(get_post_meta($exam_id, '_sep_pass_percentage', true));
        $passed = $percentage >= $pass_percentage;
        
        return array(
            'score' => round($score, 2),
            'total' => round($total_marks, 2),
            'percentage' => round($percentage, 2),
            'passed' => $passed,
            'correct_count' => $correct_count,
            'total_questions' => count($questions)
        );
    }
}
```

## Frontend Assets Structure

Let me create the necessary frontend files structure:
