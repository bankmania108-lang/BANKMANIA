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