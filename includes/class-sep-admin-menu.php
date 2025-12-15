<?php
/**
 * The admin menu functionality of the plugin.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/includes
 */

/**
 * The admin menu functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin stylesheet and JavaScript.
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin
 * @author     Seputility <contact@seputility.com>
 */
class SEP_Admin_Menu {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
	    // Add top-level menu
	    add_menu_page(
	        'Smart Exam Platform',
	        'SEP Platform',
	        'manage_options',
	        $this->plugin_name,
	        array($this, 'display_plugin_setup_page'),
	        'dashicons-welcome-learn-more',
	        30
	    );

	    // Add submenu pages
	    add_submenu_page(
	        $this->plugin_name,
	        'Exams',
	        'Exams',
	        'manage_options',
	        $this->plugin_name,
	        array($this, 'display_plugin_setup_page')
	    );

	    add_submenu_page(
	        $this->plugin_name,
	        'Questions',
	        'Questions',
	        'manage_options',
	        'sep-questions',
	        array($this, 'display_questions_page')
	    );

	    add_submenu_page(
	        $this->plugin_name,
	        'Courses',
	        'Courses',
	        'manage_options',
	        'sep-courses',
	        array($this, 'display_courses_page')
	    );

	    add_submenu_page(
	        $this->plugin_name,
	        'Curriculum',
	        'Curriculum',
	        'manage_options',
	        'sep-curriculum',
	        array($this, 'display_curriculum_page')
	    );

	    add_submenu_page(
	        $this->plugin_name,
	        'Students',
	        'Students',
	        'manage_options',
	        'sep-students',
	        array($this, 'display_students_page')
	    );

	    add_submenu_page(
	        $this->plugin_name,
	        'Reports',
	        'Reports',
	        'manage_options',
	        'sep-reports',
	        array($this, 'display_reports_page')
	    );

	    add_submenu_page(
	        $this->plugin_name,
	        'Settings',
	        'Settings',
	        'manage_options',
	        'sep-settings',
	        array($this, 'display_settings_page')
	    );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
	    include_once 'admin/partials/sep-admin-display.php';
	}

	public function display_questions_page() {
	    include_once 'admin/partials/sep-questions-display.php';
	}

	public function display_courses_page() {
	    include_once 'admin/partials/sep-courses-display.php';
	}

	public function display_curriculum_page() {
	    include_once 'admin/partials/sep-curriculum-display.php';
	}

	public function display_students_page() {
	    include_once 'admin/partials/sep-students-display.php';
	}

	public function display_reports_page() {
	    include_once 'admin/partials/sep-reports-display.php';
	}

	public function display_settings_page() {
	    include_once 'admin/partials/sep-settings-display.php';
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		/*
		 *  Adding a new settings link to the plugin list.
		 */
		$settings_link = array(
			'<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();
		
		if ( strpos( $screen->id, $this->plugin_name ) !== false || strpos( $screen->id, 'sep-' ) !== false ) {
			wp_enqueue_style(
				$this->plugin_name . '-admin-css',
				plugin_dir_url( __FILE__ ) . '../assets/css/sep-admin.css',
				array(),
				$this->version,
				'all'
			);
			
			// Enqueue select2 CSS
			wp_enqueue_style(
				'select2-css',
				'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css',
				array(),
				'4.0.13',
				'all'
			);
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_scripts() {

		$screen = get_current_screen();
		
		if ( strpos( $screen->id, $this->plugin_name ) !== false || strpos( $screen->id, 'sep-' ) !== false ) {
			wp_enqueue_script(
				$this->plugin_name . '-admin-js',
				plugin_dir_url( __FILE__ ) . '../assets/js/sep-admin.js',
				array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ),
				$this->version,
				false
			);
			
			// Enqueue select2 JS
			wp_enqueue_script(
				'select2-js',
				'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js',
				array( 'jquery' ),
				'4.0.13',
				true
			);
			
			// Localize script with AJAX URL
			wp_localize_script(
				$this->plugin_name . '-admin-js',
				'sep_ajax',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'sep_admin_nonce' )
				)
			);
		}
	}

}