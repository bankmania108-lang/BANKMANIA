<?php
/**
 * Provide a admin area view for questions management
 *
 * This file is used to markup the admin-facing aspects of the plugin for questions.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/admin/partials
 */
?>

<div class="wrap">
    <h2><?php _e( 'Questions Management', 'sep-smart-exam-platform' ); ?></h2>
    
    <div class="sep-questions-container">
        <div class="sep-questions-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#sep-questions-list" class="nav-tab nav-tab-active"><?php _e( 'Questions List', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-add-question" class="nav-tab"><?php _e( 'Add Question', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-import-questions" class="nav-tab"><?php _e( 'Import Questions', 'sep-smart-exam-platform' ); ?></a>
                <a href="#sep-export-questions" class="nav-tab"><?php _e( 'Export Questions', 'sep-smart-exam-platform' ); ?></a>
            </h2>
        </div>
        
        <div class="sep-questions-tab-content">
            <div id="sep-questions-list" class="sep-questions-tab-pane active">
                <div class="sep-questions-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e( 'ID', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Question', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Type', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Subject', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Difficulty', 'sep-smart-exam-platform' ); ?></th>
                                <th><?php _e( 'Actions', 'sep-smart-exam-platform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $questions = get_posts( array(
                                'post_type' => 'sep_questions',
                                'posts_per_page' => 20,
                                'post_status' => 'publish'
                            ) );
                            
                            if ( ! empty( $questions ) ) {
                                foreach ( $questions as $question ) {
                                    $question_data = get_post_meta( $question->ID, '_sep_question_data', true );
                                    $question_text = ! empty( $question_data['question'] ) ? $question_data['question'] : $question->post_title;
                                    $question_type = ! empty( $question_data['type'] ) ? $question_data['type'] : 'multiple_choice';
                                    $difficulty = ! empty( $question_data['difficulty'] ) ? $question_data['difficulty'] : 'medium';
                                    
                                    $subjects = wp_get_post_terms( $question->ID, 'sep_subjects' );
                                    $subject_names = array();
                                    foreach ( $subjects as $subject ) {
                                        $name = is_object($subject) ? (isset($subject->name) ? $subject->name : '') : (isset($subject['name']) ? $subject['name'] : '');
                                        if (!empty($name)) {
                                            $subject_names[] = $name;
                                        }
                                    }
                                    $subject_list = implode( ', ', $subject_names );
                                    ?>
                                    <tr>
                                        <td><?php echo $question->ID; ?></td>
                                        <td><?php echo esc_html( wp_trim_words( $question_text, 10 ) ); ?></td>
                                        <td><?php echo esc_html( ucfirst( str_replace( '_', ' ', $question_type ) ) ); ?></td>
                                        <td><?php echo esc_html( $subject_list ); ?></td>
                                        <td><?php echo esc_html( ucfirst( $difficulty ) ); ?></td>
                                        <td>
                                            <a href="<?php echo get_edit_post_link( $question->ID ); ?>" class="button button-small"><?php _e( 'Edit', 'sep-smart-exam-platform' ); ?></a>
                                            <a href="<?php echo get_delete_post_link( $question->ID ); ?>" class="button button-small button-link-delete"><?php _e( 'Delete', 'sep-smart-exam-platform' ); ?></a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6"><?php _e( 'No questions found.', 'sep-smart-exam-platform' ); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div id="sep-add-question" class="sep-questions-tab-pane">
                <h3><?php _e( 'Add New Question', 'sep-smart-exam-platform' ); ?></h3>
                <form method="post" action="">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="question_title"><?php _e( 'Question Title', 'sep-smart-exam-platform' ); ?></label></th>
                            <td><input type="text" id="question_title" name="question_title" class="regular-text" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="question_text"><?php _e( 'Question Text', 'sep-smart-exam-platform' ); ?></label></th>
                            <td><textarea id="question_text" name="question_text" class="large-text" rows="4"></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="question_type"><?php _e( 'Question Type', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="question_type" name="question_type">
                                    <option value="multiple_choice"><?php _e( 'Multiple Choice (MCQ)', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="true_false"><?php _e( 'True/False', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="short_answer"><?php _e( 'Short Answer', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="essay"><?php _e( 'Essay', 'sep-smart-exam-platform' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr id="options_section">
                            <th scope="row"><label for="question_options"><?php _e( 'Options (A, B, C, D, E)', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <input type="text" name="option_a" class="regular-text" placeholder="<?php _e( 'Option A', 'sep-smart-exam-platform' ); ?>" /><br><br>
                                <input type="text" name="option_b" class="regular-text" placeholder="<?php _e( 'Option B', 'sep-smart-exam-platform' ); ?>" /><br><br>
                                <input type="text" name="option_c" class="regular-text" placeholder="<?php _e( 'Option C', 'sep-smart-exam-platform' ); ?>" /><br><br>
                                <input type="text" name="option_d" class="regular-text" placeholder="<?php _e( 'Option D', 'sep-smart-exam-platform' ); ?>" /><br><br>
                                <input type="text" name="option_e" class="regular-text" placeholder="<?php _e( 'Option E (Optional)', 'sep-smart-exam-platform' ); ?>" /><br><br>
                                <label for="correct_option"><?php _e( 'Correct Option:', 'sep-smart-exam-platform' ); ?></label>
                                <select name="correct_option">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="question_subject"><?php _e( 'Subject', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="question_subject" name="question_subject">
                                    <?php
                                    $subjects = get_terms( array(
                                        'taxonomy' => 'sep_subjects',
                                        'hide_empty' => false,
                                    ) );
                                    foreach ( $subjects as $subject ) {
                                        $term_id = is_object($subject) ? (isset($subject->term_id) ? $subject->term_id : '') : (isset($subject['term_id']) ? $subject['term_id'] : '');
                                        $name = is_object($subject) ? (isset($subject->name) ? $subject->name : '') : (isset($subject['name']) ? $subject['name'] : '');
                                        if (!empty($term_id)) {
                                            echo '<option value="' . esc_attr( $term_id ) . '">' . esc_html( $name ) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="question_difficulty"><?php _e( 'Difficulty', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="question_difficulty" name="question_difficulty">
                                    <option value="easy"><?php _e( 'Easy', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="medium" selected><?php _e( 'Medium', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="hard"><?php _e( 'Hard', 'sep-smart-exam-platform' ); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Add Question', 'sep-smart-exam-platform' ) ); ?>
                </form>
            </div>
            
            <div id="sep-import-questions" class="sep-questions-tab-pane">
                <h3><?php _e( 'Import Questions', 'sep-smart-exam-platform' ); ?></h3>
                <p><?php _e( 'Import questions from a CSV file. Download the sample CSV template to see the required format.', 'sep-smart-exam-platform' ); ?></p>
                <a href="#" class="button"><?php _e( 'Download Sample CSV Template', 'sep-smart-exam-platform' ); ?></a>
                <form method="post" action="" enctype="multipart/form-data" style="margin-top: 20px;">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="import_file"><?php _e( 'CSV File', 'sep-smart-exam-platform' ); ?></label></th>
                            <td><input type="file" id="import_file" name="import_file" accept=".csv" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Options', 'sep-smart-exam-platform' ); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="update_existing" value="1" /> 
                                    <?php _e( 'Update existing questions if found', 'sep-smart-exam-platform' ); ?>
                                </label><br>
                                <label>
                                    <input type="checkbox" name="clear_before_import" value="1" /> 
                                    <?php _e( 'Clear all existing questions before import', 'sep-smart-exam-platform' ); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Import Questions', 'sep-smart-exam-platform' ) ); ?>
                </form>
            </div>
            
            <div id="sep-export-questions" class="sep-questions-tab-pane">
                <h3><?php _e( 'Export Questions', 'sep-smart-exam-platform' ); ?></h3>
                <p><?php _e( 'Export questions to a CSV file.', 'sep-smart-exam-platform' ); ?></p>
                <form method="post" action="">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="export_subject"><?php _e( 'Filter by Subject', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="export_subject" name="export_subject">
                                    <option value=""><?php _e( 'All Subjects', 'sep-smart-exam-platform' ); ?></option>
                                    <?php
                                    $subjects = get_terms( array(
                                        'taxonomy' => 'sep_subjects',
                                        'hide_empty' => false,
                                    ) );
                                    foreach ( $subjects as $subject ) {
                                        $term_id = is_object($subject) ? (isset($subject->term_id) ? $subject->term_id : '') : (isset($subject['term_id']) ? $subject['term_id'] : '');
                                        $name = is_object($subject) ? (isset($subject->name) ? $subject->name : '') : (isset($subject['name']) ? $subject['name'] : '');
                                        if (!empty($term_id)) {
                                            echo '<option value="' . esc_attr( $term_id ) . '">' . esc_html( $name ) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="export_difficulty"><?php _e( 'Filter by Difficulty', 'sep-smart-exam-platform' ); ?></label></th>
                            <td>
                                <select id="export_difficulty" name="export_difficulty">
                                    <option value=""><?php _e( 'All Difficulties', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="easy"><?php _e( 'Easy', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="medium"><?php _e( 'Medium', 'sep-smart-exam-platform' ); ?></option>
                                    <option value="hard"><?php _e( 'Hard', 'sep-smart-exam-platform' ); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button( __( 'Export Questions', 'sep-smart-exam-platform' ) ); ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.sep-questions-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.sep-questions-tabs .nav-tab-wrapper {
    margin: 0;
    padding: 0 20px;
    border-bottom: 1px solid #ccd0d4;
}

.sep-questions-tab-content {
    padding: 20px;
}

.sep-questions-tab-pane {
    display: none;
}

.sep-questions-tab-pane.active {
    display: block;
}

.sep-questions-table-container {
    overflow-x: auto;
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
        $('.sep-questions-tab-pane').removeClass('active');
        $(tabId).addClass('active');
    });
    
    // Toggle options section based on question type
    $('#question_type').on('change', function() {
        if ($(this).val() === 'multiple_choice') {
            $('#options_section').show();
        } else {
            $('#options_section').hide();
        }
    });
    
    // Initialize on page load
    if ($('#question_type').val() !== 'multiple_choice') {
        $('#options_section').hide();
    }
});
</script>