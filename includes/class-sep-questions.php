<?php
class SEP_Questions {
    
    public function __construct() {
        add_action('init', array($this, 'register_question_post_type'));
        add_action('add_meta_boxes', array($this, 'add_question_metaboxes'));
        add_action('save_post_sep_questions', array($this, 'save_question_meta'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_question_admin_assets'));
    }
    
    public function register_question_post_type() {
        // This is handled in the core class, but we can add specific functionality here if needed
    }
    
    public function add_question_metaboxes() {
        add_meta_box(
            'sep_question_basic',
            __('Question Details', 'sep-smart-exam'),
            array($this, 'question_basic_callback'),
            'sep_questions',
            'normal',
            'high'
        );
        
        add_meta_box(
            'sep_question_options',
            __('Question Options', 'sep-smart-exam'),
            array($this, 'question_options_callback'),
            'sep_questions',
            'normal',
            'high'
        );
        
        add_meta_box(
            'sep_question_advanced',
            __('Advanced Settings', 'sep-smart-exam'),
            array($this, 'question_advanced_callback'),
            'sep_questions',
            'normal',
            'default'
        );
    }
    
    public function question_basic_callback($post) {
        wp_nonce_field('sep_save_question_meta', 'sep_question_meta_nonce');
        
        $question_type = get_post_meta($post->ID, '_sep_question_type', true);
        $difficulty = get_post_meta($post->ID, '_sep_difficulty_level', true);
        $subject = get_post_meta($post->ID, '_sep_subject', true);
        $chapter = get_post_meta($post->ID, '_sep_chapter', true);
        $marks = get_post_meta($post->ID, '_sep_marks', true);
        $negative_marks = get_post_meta($post->ID, '_sep_negative_marks', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="sep_question_type"><?php _e('Question Type', 'sep-smart-exam'); ?></label></th>
                <td>
                    <select id="sep_question_type" name="sep_question_type">
                        <option value="mcq_single" <?php selected($question_type, 'mcq_single'); ?>>Multiple Choice (Single Answer)</option>
                        <option value="mcq_multiple" <?php selected($question_type, 'mcq_multiple'); ?>>Multiple Choice (Multiple Answers)</option>
                        <option value="true_false" <?php selected($question_type, 'true_false'); ?>>True/False</option>
                        <option value="fill_blank" <?php selected($question_type, 'fill_blank'); ?>>Fill in the Blank</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="sep_difficulty_level"><?php _e('Difficulty Level', 'sep-smart-exam'); ?></label></th>
                <td>
                    <select id="sep_difficulty_level" name="sep_difficulty_level">
                        <option value="easy" <?php selected($difficulty, 'easy'); ?>>Easy</option>
                        <option value="medium" <?php selected($difficulty, 'medium'); ?>>Medium</option>
                        <option value="hard" <?php selected($difficulty, 'hard'); ?>>Hard</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="sep_marks"><?php _e('Marks', 'sep-smart-exam'); ?></label></th>
                <td><input type="number" id="sep_marks" name="sep_marks" value="<?php echo esc_attr($marks ? $marks : 1); ?>" min="0.5" step="0.5" /></td>
            </tr>
            <tr>
                <th><label for="sep_negative_marks"><?php _e('Negative Marks', 'sep-smart-exam'); ?></label></th>
                <td><input type="number" id="sep_negative_marks" name="sep_negative_marks" value="<?php echo esc_attr($negative_marks ? $negative_marks : 0); ?>" min="0" step="0.5" /></td>
            </tr>
        </table>
        <?php
    }
    
    public function question_options_callback($post) {
        $option_a = get_post_meta($post->ID, '_sep_option_a', true);
        $option_b = get_post_meta($post->ID, '_sep_option_b', true);
        $option_c = get_post_meta($post->ID, '_sep_option_c', true);
        $option_d = get_post_meta($post->ID, '_sep_option_d', true);
        $option_e = get_post_meta($post->ID, '_sep_option_e', true);
        $correct_answer = get_post_meta($post->ID, '_sep_correct_answer', true);
        $option_e_enabled = get_post_meta($post->ID, '_sep_option_e_enabled', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="sep_option_a">Option A</label></th>
                <td><input type="text" id="sep_option_a" name="sep_option_a" value="<?php echo esc_attr($option_a); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="sep_option_b">Option B</label></th>
                <td><input type="text" id="sep_option_b" name="sep_option_b" value="<?php echo esc_attr($option_b); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="sep_option_c">Option C</label></th>
                <td><input type="text" id="sep_option_c" name="sep_option_c" value="<?php echo esc_attr($option_c); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="sep_option_d">Option D</label></th>
                <td><input type="text" id="sep_option_d" name="sep_option_d" value="<?php echo esc_attr($option_d); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="sep_option_e">Option E (Optional)</label></th>
                <td>
                    <input type="text" id="sep_option_e" name="sep_option_e" value="<?php echo esc_attr($option_e); ?>" style="width: 100%;" />
                    <label style="display: block; margin-top: 5px;">
                        <input type="checkbox" name="sep_option_e_enabled" <?php checked($option_e_enabled, 'on'); ?> /> Enable 5th option
                    </label>
                </td>
            </tr>
            <tr>
                <th><label for="sep_correct_answer">Correct Answer</label></th>
                <td>
                    <select id="sep_correct_answer" name="sep_correct_answer">
                        <option value="a" <?php selected($correct_answer, 'a'); ?>>A</option>
                        <option value="b" <?php selected($correct_answer, 'b'); ?>>B</option>
                        <option value="c" <?php selected($correct_answer, 'c'); ?>>C</option>
                        <option value="d" <?php selected($correct_answer, 'd'); ?>>D</option>
                        <option value="e" <?php selected($correct_answer, 'e'); ?>>E</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function question_advanced_callback($post) {
        $explanation = get_post_meta($post->ID, '_sep_explanation', true);
        $exam_id = get_post_meta($post->ID, '_sep_exam_id', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="sep_explanation"><?php _e('Explanation', 'sep-smart-exam'); ?></label></th>
                <td>
                    <textarea id="sep_explanation" name="sep_explanation" rows="4" style="width: 100%;"><?php echo esc_textarea($explanation); ?></textarea>
                    <p class="description"><?php _e('Explanation shown to student after answering (if enabled)', 'sep-smart-exam'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="sep_exam_id"><?php _e('Associated Exam', 'sep-smart-exam'); ?></label></th>
                <td>
                    <?php
                    $exams = get_posts(array(
                        'post_type' => 'sep_exams',
                        'posts_per_page' => -1,
                        'post_status' => 'publish'
                    ));
                    ?>
                    <select id="sep_exam_id" name="sep_exam_id">
                        <option value="">-- Select Exam --</option>
                        <?php foreach ($exams as $exam): ?>
                            <option value="<?php echo $exam->ID; ?>" <?php selected($exam->ID, $exam_id); ?>><?php echo $exam->post_title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_question_meta($post_id) {
        if (!isset($_POST['sep_question_meta_nonce']) || !wp_verify_nonce($_POST['sep_question_meta_nonce'], 'sep_save_question_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save basic fields
        $basic_fields = array(
            'sep_question_type',
            'sep_difficulty_level',
            'sep_subject',
            'sep_chapter',
            'sep_marks',
            'sep_negative_marks',
            'sep_correct_answer',
            'sep_exam_id'
        );
        
        foreach ($basic_fields as $field) {
            $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            update_post_meta($post_id, '_'.$field, $value);
        }
        
        // Save option fields
        $option_fields = array(
            'sep_option_a',
            'sep_option_b',
            'sep_option_c',
            'sep_option_d',
            'sep_option_e'
        );
        
        foreach ($option_fields as $field) {
            $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            update_post_meta($post_id, '_'.$field, $value);
        }
        
        // Handle checkbox fields
        $checkbox_fields = array(
            'sep_option_e_enabled'
        );
        
        foreach ($checkbox_fields as $field) {
            $value = isset($_POST[$field]) ? 'on' : 'off';
            update_post_meta($post_id, '_'.$field, $value);
        }
        
        // Save explanation (textarea)
        if (isset($_POST['sep_explanation'])) {
            $explanation = sanitize_textarea_field($_POST['sep_explanation']);
            update_post_meta($post_id, '_sep_explanation', $explanation);
        }
    }
    
    public function enqueue_question_admin_assets($hook) {
        if ($hook !== 'post-new.php' && $hook !== 'post.php') {
            return;
        }
        
        global $post;
        if ($post && $post->post_type === 'sep_questions') {
            // Add any specific question admin assets here
        }
    }
}