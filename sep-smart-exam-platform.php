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
require_once SEP_PLUGIN_PATH . 'includes/class-sep-admin-menu.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-course-builder.php';
require_once SEP_PLUGIN_PATH . 'includes/class-sep-curriculum.php';
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