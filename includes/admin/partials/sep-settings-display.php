<?php
/**
 * Provide a admin area view for settings management
 *
 * This file is used to markup the admin-facing aspects of the plugin for settings.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'Platform Settings', 'sep-smart-exam-platform' ); ?></h2>
    
    <div class="sep-settings-container">
        <div class="sep-settings-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-general-settings" class="nav-tab nav-tab-active"><?php _e( 'General', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-exam-settings" class="nav-tab"><?php _e( 'Exam Settings', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-user-settings" class="nav-tab"><?php _e( 'User Settings', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-payment-settings" class="nav-tab"><?php _e( 'Payment', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-integration-settings" class="nav-tab"><?php _e( 'Integrations', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-appearance-settings" class="nav-tab"><?php _e( 'Appearance', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-settings-tab-content">
            <div id="sep-general-settings" class="sep-settings-tab-pane active">
                <h3><?php _e( 'General Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields( 'sep_general_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="sep_platform_name"><?php _e( 'Platform Name', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <input type="text" id="sep_platform_name" name="sep_platform_name" value="<?php echo esc_attr( get_option( 'sep_platform_name', 'Smart Exam Platform' ) ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'Name of your exam platform', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sep_platform_description"><?php _e( 'Platform Description', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <textarea id="sep_platform_description" name="sep_platform_description" rows="3" class="large-text"><?php echo esc_textarea( get_option( 'sep_platform_description', 'Online exam and course platform' ) ); ?></textarea>
                                <p class="description"><?php _e( 'Brief description of your platform', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Currency', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <select name="sep_currency">
                                    <option value="INR" <?php selected( get_option( 'sep_currency', 'INR' ), 'INR' ); ?>>Indian Rupee (₹)</option>
                                    <option value="USD" <?php selected( get_option( 'sep_currency', 'INR' ), 'USD' ); ?>>US Dollar ($)</option>
                                    <option value="EUR" <?php selected( get_option( 'sep_currency', 'INR' ), 'EUR' ); ?>>Euro (€)</option>
                                    <option value="GBP" <?php selected( get_option( 'sep_currency', 'INR' ), 'GBP' ); ?>>British Pound (£)</option>
                                </select>
                                <p class="description"><?php _e( 'Currency for payments and pricing', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Email Notifications', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <fieldset>
                                    <label>
                                        <input type="checkbox" name="sep_email_exam_results" value="1" <?php checked( get_option( 'sep_email_exam_results', 1 ), 1 ); ?> />
                                        <?php _e( 'Send exam results to students', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="sep_email_course_completion" value="1" <?php checked( get_option( 'sep_email_course_completion', 1 ), 1 ); ?> />
                                        <?php _e( 'Send course completion certificates', 'sep-smart-exam-platform' ); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="sep_email_announcements" value="1" <?php checked( get_option( 'sep_email_announcements', 1 ), 1 ); ?> />
                                        <?php _e( 'Send platform announcements', 'sep-smart-exam-platform' ); ?>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Maintenance Mode', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_maintenance_mode" value="1" <?php checked( get_option( 'sep_maintenance_mode', 0 ), 1 ); ?> />
                                    <?php _e( 'Enable maintenance mode (only admins can access)', 'sep-smart-exam-platform' ); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            
            <div id="sep-exam-settings" class="sep-settings-tab-pane">
                <h3><?php _e( 'Exam Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields( 'sep_exam_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Time Limit', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_enable_time_limit" value="1" <?php checked( get_option( 'sep_enable_time_limit', 1 ), 1 ); ?> />
                                    <?php _e( 'Enable time limits for exams', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'When enabled, exams will have time limits as set per exam', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Question Randomization', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_randomize_questions" value="1" <?php checked( get_option( 'sep_randomize_questions', 0 ), 1 ); ?> />
                                    <?php _e( 'Randomize question order', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_randomize_options" value="1" <?php checked( get_option( 'sep_randomize_options', 0 ), 1 ); ?> />
                                    <?php _e( 'Randomize answer options for MCQs', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Randomize the order of questions and/or answer options', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Navigation', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_allow_back_navigation" value="1" <?php checked( get_option( 'sep_allow_back_navigation', 1 ), 1 ); ?> />
                                    <?php _e( 'Allow students to navigate back to previous questions', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_allow_skip_questions" value="1" <?php checked( get_option( 'sep_allow_skip_questions', 1 ), 1 ); ?> />
                                    <?php _e( 'Allow students to skip questions and return later', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Control how students can navigate through exam questions', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Results Display', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <select name="sep_results_display">
                                    <option value="immediate" <?php selected( get_option( 'sep_results_display', 'immediate' ), 'immediate' ); ?>><?php _e( 'Show immediately after exam', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="scheduled" <?php selected( get_option( 'sep_results_display', 'immediate' ), 'scheduled' ); ?>><?php _e( 'Show on scheduled date', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="manual" <?php selected( get_option( 'sep_results_display', 'immediate' ), 'manual' ); ?>><?php _e( 'Show manually by admin', 'sep-smart-exam-platform' ); ?></option>
                                </select>
                                <p class="description"><?php _e( 'When to show exam results to students', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Passing Grade', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="number" name="sep_default_passing_grade" value="<?php echo esc_attr( get_option( 'sep_default_passing_grade', 70 ) ); ?>" min="0" max="100" />%
                                <p class="description"><?php _e( 'Default minimum grade required to pass an exam', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            
            <div id="sep-user-settings" class="sep-settings-tab-pane">
                <h3><?php _e( 'User Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields( 'sep_user_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'User Registration', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_enable_registration" value="1" <?php checked( get_option( 'sep_enable_registration', 1 ), 1 ); ?> />
                                    <?php _e( 'Allow new user registration', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Enable or disable new user registration', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Registration Approval', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="radio" name="sep_registration_approval" value="automatic" <?php checked( get_option( 'sep_registration_approval', 'automatic' ), 'automatic' ); ?> />
                                    <?php _e( 'Automatic approval', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="radio" name="sep_registration_approval" value="manual" <?php checked( get_option( 'sep_registration_approval', 'automatic' ), 'manual' ); ?> />
                                    <?php _e( 'Manual approval by admin', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'How to handle new user registrations', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Student Dashboard', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_enable_student_dashboard" value="1" <?php checked( get_option( 'sep_enable_student_dashboard', 1 ), 1 ); ?> />
                                    <?php _e( 'Enable student dashboard', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Enable the student dashboard for tracking progress', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Profile Fields', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_profile_phone" value="1" <?php checked( get_option( 'sep_profile_phone', 1 ), 1 ); ?> />
                                    <?php _e( 'Phone number', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_profile_address" value="1" <?php checked( get_option( 'sep_profile_address', 0 ), 1 ); ?> />
                                    <?php _e( 'Address', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_profile_education" value="1" <?php checked( get_option( 'sep_profile_education', 0 ), 1 ); ?> />
                                    <?php _e( 'Education background', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Additional fields to collect in user profiles', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            
            <div id="sep-payment-settings" class="sep-settings-tab-pane">
                <h3><?php _e( 'Payment Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields( 'sep_payment_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Payment Gateway', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <select name="sep_payment_gateway">
                                    <option value="none" <?php selected( get_option( 'sep_payment_gateway', 'none' ), 'none' ); ?>><?php _e( 'No Payment Required', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="razorpay" <?php selected( get_option( 'sep_payment_gateway', 'none' ), 'razorpay' ); ?>>Razorpay</option>
                                    <option value="stripe" <?php selected( get_option( 'sep_payment_gateway', 'none' ), 'stripe' ); ?>>Stripe</option>
                                    <option value="paypal" <?php selected( get_option( 'sep_payment_gateway', 'none' ), 'paypal' ); ?>>PayPal</option>
                                </select>
                                <p class="description"><?php _e( 'Select payment gateway for paid courses/exams', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr id="razorpay_settings" style="display: <?php echo get_option( 'sep_payment_gateway', 'none' ) === 'razorpay' ? 'table-row' : 'none'; ?>;">
                            <th scope="row"><?php _e( 'Razorpay Settings', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_razorpay_key_id" value="<?php echo esc_attr( get_option( 'sep_razorpay_key_id' ) ); ?>" placeholder="<?php _e( 'Key ID', 'sep-smart-exam-platform' ); ?>" class="regular-text" /><br><br>
                                <input type="password" name="sep_razorpay_key_secret" value="<?php echo esc_attr( get_option( 'sep_razorpay_key_secret' ) ); ?>" placeholder="<?php _e( 'Key Secret', 'sep-smart-exam-platform' ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'Enter your Razorpay API credentials', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr id="stripe_settings" style="display: <?php echo get_option( 'sep_payment_gateway', 'none' ) === 'stripe' ? 'table-row' : 'none'; ?>;">
                            <th scope="row"><?php _e( 'Stripe Settings', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_stripe_publishable_key" value="<?php echo esc_attr( get_option( 'sep_stripe_publishable_key' ) ); ?>" placeholder="<?php _e( 'Publishable Key', 'sep-smart-exam-platform' ); ?>" class="regular-text" /><br><br>
                                <input type="password" name="sep_stripe_secret_key" value="<?php echo esc_attr( get_option( 'sep_stripe_secret_key' ) ); ?>" placeholder="<?php _e( 'Secret Key', 'sep-smart-exam-platform' ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'Enter your Stripe API credentials', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr id="paypal_settings" style="display: <?php echo get_option( 'sep_payment_gateway', 'none' ) === 'paypal' ? 'table-row' : 'none'; ?>;">
                            <th scope="row"><?php _e( 'PayPal Settings', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_paypal_client_id" value="<?php echo esc_attr( get_option( 'sep_paypal_client_id' ) ); ?>" placeholder="<?php _e( 'Client ID', 'sep-smart-exam-platform' ); ?>" class="regular-text" /><br><br>
                                <input type="password" name="sep_paypal_secret" value="<?php echo esc_attr( get_option( 'sep_paypal_secret' ) ); ?>" placeholder="<?php _e( 'Secret', 'sep-smart-exam-platform' ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'Enter your PayPal API credentials', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Tax Settings', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_enable_tax" value="1" <?php checked( get_option( 'sep_enable_tax', 0 ), 1 ); ?> />
                                    <?php _e( 'Enable tax calculation', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <input type="number" name="sep_tax_rate" value="<?php echo esc_attr( get_option( 'sep_tax_rate', 0 ) ); ?>" min="0" max="100" step="0.01" placeholder="<?php _e( 'Tax Rate (%)', 'sep-smart-exam-platform' ); ?>" class="small-text" />
                                <p class="description"><?php _e( 'Enable tax calculation for payments', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            
            <div id="sep-integration-settings" class="sep-settings-tab-pane">
                <h3><?php _e( 'Integration Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields( 'sep_integration_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Google Analytics', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_google_analytics_id" value="<?php echo esc_attr( get_option( 'sep_google_analytics_id' ) ); ?>" placeholder="<?php _e( 'GA4 Measurement ID (e.g., G-XXXXXXXXXX)', 'sep-smart-exam-platform' ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'Enter your Google Analytics Measurement ID to track platform usage', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Email Service', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <select name="sep_email_service">
                                    <option value="wp_mail" <?php selected( get_option( 'sep_email_service', 'wp_mail' ), 'wp_mail' ); ?>><?php _e( 'WordPress Default', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="smtp" <?php selected( get_option( 'sep_email_service', 'wp_mail' ), 'smtp' ); ?>><?php _e( 'Custom SMTP', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="sendgrid" <?php selected( get_option( 'sep_email_service', 'wp_mail' ), 'sendgrid' ); ?>>SendGrid</option>
                                    <option value="mailgun" <?php selected( get_option( 'sep_email_service', 'wp_mail' ), 'mailgun' ); ?>>Mailgun</option>
                                </select>
                                <p class="description"><?php _e( 'Email service for sending notifications', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr id="smtp_settings" style="display: <?php echo get_option( 'sep_email_service', 'wp_mail' ) === 'smtp' ? 'table-row' : 'none'; ?>;">
                            <th scope="row"><?php _e( 'SMTP Settings', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_smtp_host" value="<?php echo esc_attr( get_option( 'sep_smtp_host' ) ); ?>" placeholder="<?php _e( 'SMTP Host', 'sep-smart-exam-platform' ); ?>" class="regular-text" /><br><br>
                                <input type="number" name="sep_smtp_port" value="<?php echo esc_attr( get_option( 'sep_smtp_port', 587 ) ); ?>" placeholder="<?php _e( 'Port', 'sep-smart-exam-platform' ); ?>" class="small-text" />
                                <select name="sep_smtp_encryption" class="small-text">
                                    <option value="" <?php selected( get_option( 'sep_smtp_encryption' ), '' ); ?>><?php _e( 'None', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="ssl" <?php selected( get_option( 'sep_smtp_encryption' ), 'ssl' ); ?>>SSL</option>
                                    <option value="tls" <?php selected( get_option( 'sep_smtp_encryption' ), 'tls' ); ?>>TLS</option>
                                </select><br><br>
                                <input type="text" name="sep_smtp_username" value="<?php echo esc_attr( get_option( 'sep_smtp_username' ) ); ?>" placeholder="<?php _e( 'Username', 'sep-smart-exam-platform' ); ?>" class="regular-text" /><br><br>
                                <input type="password" name="sep_smtp_password" value="<?php echo esc_attr( get_option( 'sep_smtp_password' ) ); ?>" placeholder="<?php _e( 'Password', 'sep-smart-exam-platform' ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'SMTP server settings for sending emails', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Third-party Integrations', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_enable_quiz_integration" value="1" <?php checked( get_option( 'sep_enable_quiz_integration', 0 ), 1 ); ?> />
                                    <?php _e( 'Enable quiz integration API', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_enable_lms_sync" value="1" <?php checked( get_option( 'sep_enable_lms_sync', 0 ), 1 ); ?> />
                                    <?php _e( 'Sync with external LMS', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_enable_scorm" value="1" <?php checked( get_option( 'sep_enable_scorm', 0 ), 1 ); ?> />
                                    <?php _e( 'Enable SCORM compliance', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Enable various integration options for your platform', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            
            <div id="sep-appearance-settings" class="sep-settings-tab-pane">
                <h3><?php _e( 'Appearance Settings', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields( 'sep_appearance_settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e( 'Primary Color', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_primary_color" value="<?php echo esc_attr( get_option( 'sep_primary_color', '#0073aa' ) ); ?>" class="sep-color-picker" />
                                <p class="description"><?php _e( 'Primary color for buttons, links, and highlights', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Secondary Color', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_secondary_color" value="<?php echo esc_attr( get_option( 'sep_secondary_color', '#2271b1' ) ); ?>" class="sep-color-picker" />
                                <p class="description"><?php _e( 'Secondary color for additional elements', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Custom CSS', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <textarea name="sep_custom_css" rows="10" class="large-text code"><?php echo esc_textarea( get_option( 'sep_custom_css' ) ); ?></textarea>
                                <p class="description"><?php _e( 'Add custom CSS to style your platform', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Exam Interface', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="sep_enable_fullscreen" value="1" <?php checked( get_option( 'sep_enable_fullscreen', 0 ), 1 ); ?> />
                                    <?php _e( 'Enable fullscreen exam mode', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_show_question_numbers" value="1" <?php checked( get_option( 'sep_show_question_numbers', 1 ), 1 ); ?> />
                                    <?php _e( 'Show question numbers in exam', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="sep_enable_question_flagging" value="1" <?php checked( get_option( 'sep_enable_question_flagging', 1 ), 1 ); ?> />
                                    <?php _e( 'Enable question flagging', 'sep-smart-exam-platform' ); ?>
                                </label>
                                <p class="description"><?php _e( 'Customize the exam-taking interface', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Logo & Branding', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <input type="text" name="sep_platform_logo" value="<?php echo esc_attr( get_option( 'sep_platform_logo' ) ); ?>" placeholder="<?php _e( 'Logo URL', 'sep-smart-exam-platform' ); ?>" class="regular-text" />
                                <p class="description"><?php _e( 'URL to your platform logo image', 'sep-smart-exam-platform' ); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sep-settings-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-settings-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-settings-tab-content {
    padding: 20px;
}

.sep-settings-tab-pane {
    display: none;
}

.sep-settings-tab-pane.active {
    display: block;
}

.sep-color-picker {
    width: 100px;
    height: 30px;
    border: 1px solid #ccc;
    padding: 0;
    background: white;
    cursor: pointer;
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
        $('.sep-settings-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Payment gateway settings toggle
    $('select[name="sep_payment_gateway"]').on('change', function() {
        var selectedGateway = $(this).val();
        
        // Hide all gateway settings
        $('#razorpay_settings, #stripe_settings, #paypal_settings').hide();
        
        // Show selected gateway settings
        if (selectedGateway !== 'none') {
            $('#'+selectedGateway+'_settings').show();
        }
    });
    
    // Email service settings toggle
    $('select[name="sep_email_service"]').on('change', function() {
        var selectedService = $(this).val();
        
        // Hide SMTP settings by default
        $('#smtp_settings').hide();
        
        // Show SMTP settings if selected
        if (selectedService === 'smtp') {
            $('#smtp_settings').show();
        }
    });
    
    // Initialize color picker
    $('.sep-color-picker').wpColorPicker();
    
    // Trigger change events on page load to show correct settings
    $('select[name="sep_payment_gateway"]').trigger('change');
    $('select[name="sep_email_service"]').trigger('change');
    if ($('select[name="sep_email_service"]').val() === 'smtp') {
        $('#smtp_settings').show();
    }
});
</script>