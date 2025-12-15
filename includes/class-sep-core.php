<?php
class SEP_Core {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Initialize admin menu
        $admin_menu = new SEP_Admin_Menu('sep-smart-exam-platform', '1.0.0');
        add_action('admin_menu', array($admin_menu, 'add_plugin_admin_menu'));
        add_filter('plugin_action_links_sep-smart-exam-platform/sep-smart-exam-platform.php', array($admin_menu, 'add_action_links'));
        add_action('admin_enqueue_scripts', array($admin_menu, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($admin_menu, 'enqueue_scripts'));
        
        // Initialize course builder
        $course_builder = new SEP_Course_Builder('sep-smart-exam-platform', '1.0.0');
        add_action('init', array($course_builder, 'register_course_post_type'));
        add_action('add_meta_boxes', array($course_builder, 'add_course_builder_meta_box'));
        add_action('save_post', array($course_builder, 'save_course_builder_data'));
        add_action('admin_enqueue_scripts', array($course_builder, 'enqueue_course_builder_assets'));
        
        // Initialize curriculum management
        $curriculum = new SEP_Curriculum('sep-smart-exam-platform', '1.0.0');
        add_action('init', array($curriculum, 'register_lesson_post_type'));
        add_action('init', array($curriculum, 'register_quiz_post_type'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        // General settings
        register_setting('sep_general_settings', 'sep_platform_name');
        register_setting('sep_general_settings', 'sep_platform_description');
        register_setting('sep_general_settings', 'sep_currency');
        register_setting('sep_general_settings', 'sep_email_exam_results');
        register_setting('sep_general_settings', 'sep_email_course_completion');
        register_setting('sep_general_settings', 'sep_email_announcements');
        register_setting('sep_general_settings', 'sep_maintenance_mode');
        
        // Exam settings
        register_setting('sep_exam_settings', 'sep_enable_time_limit');
        register_setting('sep_exam_settings', 'sep_randomize_questions');
        register_setting('sep_exam_settings', 'sep_randomize_options');
        register_setting('sep_exam_settings', 'sep_allow_back_navigation');
        register_setting('sep_exam_settings', 'sep_allow_skip_questions');
        register_setting('sep_exam_settings', 'sep_results_display');
        register_setting('sep_exam_settings', 'sep_default_passing_grade');
        
        // User settings
        register_setting('sep_user_settings', 'sep_enable_registration');
        register_setting('sep_user_settings', 'sep_registration_approval');
        register_setting('sep_user_settings', 'sep_enable_student_dashboard');
        register_setting('sep_user_settings', 'sep_profile_phone');
        register_setting('sep_user_settings', 'sep_profile_address');
        register_setting('sep_user_settings', 'sep_profile_education');
        
        // Payment settings
        register_setting('sep_payment_settings', 'sep_payment_gateway');
        register_setting('sep_payment_settings', 'sep_razorpay_key_id');
        register_setting('sep_payment_settings', 'sep_razorpay_key_secret');
        register_setting('sep_payment_settings', 'sep_stripe_publishable_key');
        register_setting('sep_payment_settings', 'sep_stripe_secret_key');
        register_setting('sep_payment_settings', 'sep_paypal_client_id');
        register_setting('sep_payment_settings', 'sep_paypal_secret');
        register_setting('sep_payment_settings', 'sep_enable_tax');
        register_setting('sep_payment_settings', 'sep_tax_rate');
        
        // Integration settings
        register_setting('sep_integration_settings', 'sep_google_analytics_id');
        register_setting('sep_integration_settings', 'sep_email_service');
        register_setting('sep_integration_settings', 'sep_smtp_host');
        register_setting('sep_integration_settings', 'sep_smtp_port');
        register_setting('sep_integration_settings', 'sep_smtp_encryption');
        register_setting('sep_integration_settings', 'sep_smtp_username');
        register_setting('sep_integration_settings', 'sep_smtp_password');
        register_setting('sep_integration_settings', 'sep_enable_quiz_integration');
        register_setting('sep_integration_settings', 'sep_enable_lms_sync');
        register_setting('sep_integration_settings', 'sep_enable_scorm');
        
        // Appearance settings
        register_setting('sep_appearance_settings', 'sep_primary_color');
        register_setting('sep_appearance_settings', 'sep_secondary_color');
        register_setting('sep_appearance_settings', 'sep_custom_css');
        register_setting('sep_appearance_settings', 'sep_enable_fullscreen');
        register_setting('sep_appearance_settings', 'sep_show_question_numbers');
        register_setting('sep_appearance_settings', 'sep_enable_question_flagging');
        register_setting('sep_appearance_settings', 'sep_platform_logo');
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