<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <div class="sep-admin-container">
        <div class="sep-admin-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-dashboard" class="nav-tab nav-tab-active"><?php _e( 'Dashboard', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-general-settings" class="nav-tab"><?php _e( 'General Settings', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-exam-settings" class="nav-tab"><?php _e( 'Exam Settings', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-integration" class="nav-tab"><?php _e( 'Integrations', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-appearance" class="nav-tab"><?php _e( 'Appearance', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-advanced" class="nav-tab"><?php _e( 'Advanced', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-admin-tab-content">
            <div id="sep-dashboard" class="sep-admin-tab-pane active">
                <div class="sep-dashboard-overview">
                    <h3><?php _e( 'Platform Overview', 'sep-smart-exam-platform' ); ?></h3>
                    <div class="sep-stats-grid">
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Exams', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php echo wp_count_posts( 'sep_exams' )->publish; ?></p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Questions', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php echo wp_count_posts( 'sep_questions' )->publish; ?></p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Attempts', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php echo wp_count_posts( 'sep_attempts' )->publish; ?></p>
                        </div>
                        <div class="sep-stat-card">
                            <h4><?php _e( 'Total Courses', 'sep-smart-exam-platform' ); ?></h4>
                            <p class="sep-stat-number"><?php echo wp_count_posts( 'sep_course' )->publish; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="sep-quick-actions">
                    <h3><?php _e( 'Quick Actions', 'sep-smart-exam-platform' ); ?></h3>
                    <div class="sep-quick-actions-grid">
                        <a href="<?php echo admin_url( 'post-new.php?post_type=sep_exams' ); ?>" class="sep-quick-action-card">
                            <span class="dashicons dashicons-welcome-learn-more"></span>
                            <h4><?php _e( 'Create New Exam', 'sep-smart-exam-platform' ); ?></h4>
                            <p><?php _e( 'Create a new exam or test', 'sep-smart-exam-platform' ); ?></p>
                        </a>
                        <a href="<?php echo admin_url( 'post-new.php?post_type=sep_questions' ); ?>" class="sep-quick-action-card">
                            <span class="dashicons dashicons-editor-ul"></span>
                            <h4><?php _e( 'Add Questions', 'sep-smart-exam-platform' ); ?></h4>
                            <p><?php _e( 'Add new questions to your question bank', 'sep-smart-exam-platform' ); ?></p>
                        </a>
                        <a href="<?php echo admin_url( 'post-new.php?post_type=sep_course' ); ?>" class="sep-quick-action-card">
                            <span class="dashicons dashicons-book-alt"></span>
                            <h4><?php _e( 'Create Course', 'sep-smart-exam-platform' ); ?></h4>
                            <p><?php _e( 'Build a structured course with lessons and exams', 'sep-smart-exam-platform' ); ?></p>
                        </a>
                        <a href="<?php echo admin_url( 'edit.php?post_type=sep_exams' ); ?>" class="sep-quick-action-card">
                            <span class="dashicons dashicons-list-view"></span>
                            <h4><?php _e( 'Manage Content', 'sep-smart-exam-platform' ); ?></h4>
                            <p><?php _e( 'View and manage all exams and questions', 'sep-smart-exam-platform' ); ?></p>
                        </a>
                    </div>
                </div>
            </div>
            
            <div id="sep-general-settings" class="sep-admin-tab-pane">
                <h3><?php _e( 'General Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'sep_general_settings' );
                        do_settings_sections( 'sep_general_settings' );
                        submit_button();
                    ?>
                </form>
            </div>
            
            <div id="sep-exam-settings" class="sep-admin-tab-pane">
                <h3><?php _e( 'Exam Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'sep_exam_settings' );
                        do_settings_sections( 'sep_exam_settings' );
                        submit_button();
                    ?>
                </form>
            </div>
            
            <div id="sep-integration" class="sep-admin-tab-pane">
                <h3><?php _e( 'Integrations', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'sep_integration_settings' );
                        do_settings_sections( 'sep_integration_settings' );
                        submit_button();
                    ?>
                </form>
            </div>
            
            <div id="sep-appearance" class="sep-admin-tab-pane">
                <h3><?php _e( 'Appearance Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'sep_appearance_settings' );
                        do_settings_sections( 'sep_appearance_settings' );
                        submit_button();
                    ?>
                </form>
            </div>
            
            <div id="sep-advanced" class="sep-admin-tab-pane">
                <h3><?php _e( 'Advanced Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'sep_advanced_settings' );
                        do_settings_sections( 'sep_advanced_settings' );
                        submit_button();
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sep-admin-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-admin-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-admin-tab-content {
    padding: 20px;
}

.sep-admin-tab-pane {
    display: none;
}

.sep-admin-tab-pane.active {
    display: block;
}

.sep-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.sep-stat-card {
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 20px;
    text-align: center;
    border-radius: 4px;
}

.sep-stat-card h4 {
    margin: 0 0 10px 0;
    color: #666;
}

.sep-stat-number {
    font-size: 2em;
    font-weight: bold;
    margin: 0;
    color: #0073aa;
}

.sep-quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.sep-quick-action-card {
    display: block;
    background: #f9f9f9;
    border: 1px solid #ccd0d4;
    padding: 20px;
    text-align: center;
    border-radius: 4px;
    text-decoration: none;
    color: #32373c;
    transition: all 0.3s ease;
}

.sep-quick-action-card:hover {
    background: #0073aa;
    color: #fff;
    text-decoration: none;
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.sep-quick-action-card .dashicons {
    font-size: 3em;
    margin-bottom: 10px;
    display: block;
}

.sep-quick-action-card h4 {
    margin: 10px 0;
}

.sep-quick-action-card p {
    margin: 0;
    font-size: 0.9em;
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
        $('.sep-admin-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
});
</script>