<?php
/**
 * The course builder functionality of the plugin.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/includes
 */

/**
 * The course builder functionality of the plugin.
 *
 * Defines the course builder functionality with drag-and-drop interface.
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/includes
 * @author     Seputility <contact@seputility.com>
 */
class SEP_Course_Builder {

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
	 * Register custom post type for courses.
	 *
	 * @since    1.0.0
	 */
	public function register_course_post_type() {
		$args = array(
			'labels' => array(
				'name'                  => _x( 'Courses', 'Post type general name', 'sep-smart-exam-platform' ),
				'singular_name'         => _x( 'Course', 'Post type singular name', 'sep-smart-exam-platform' ),
				'menu_name'             => _x( 'Courses', 'Admin Menu text', 'sep-smart-exam-platform' ),
				'name_admin_bar'        => _x( 'Course', 'Add New on Toolbar', 'sep-smart-exam-platform' ),
				'add_new'               => __( 'Add New', 'sep-smart-exam-platform' ),
				'add_new_item'          => __( 'Add New Course', 'sep-smart-exam-platform' ),
				'new_item'              => __( 'New Course', 'sep-smart-exam-platform' ),
				'edit_item'             => __( 'Edit Course', 'sep-smart-exam-platform' ),
				'view_item'             => __( 'View Course', 'sep-smart-exam-platform' ),
				'all_items'             => __( 'All Courses', 'sep-smart-exam-platform' ),
				'search_items'          => __( 'Search Courses', 'sep-smart-exam-platform' ),
				'parent_item_colon'     => __( 'Parent Courses:', 'sep-smart-exam-platform' ),
				'not_found'             => __( 'No courses found.', 'sep-smart-exam-platform' ),
				'not_found_in_trash'    => __( 'No courses found in Trash.', 'sep-smart-exam-platform' ),
				'featured_image'        => _x( 'Course Cover Image', 'Overrides the "Featured Image" phrase', 'sep-smart-exam-platform' ),
				'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'sep-smart-exam-platform' ),
				'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'sep-smart-exam-platform' ),
				'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'sep-smart-exam-platform' ),
				'archives'              => _x( 'Course archives', 'The post type archive label used in nav menus', 'sep-smart-exam-platform' ),
				'insert_into_item'      => _x( 'Insert into course', 'Overrides the "Insert into post" phrase', 'sep-smart-exam-platform' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this course', 'Overrides the "Uploaded to this post" phrase', 'sep-smart-exam-platform' ),
				'filter_items_list'     => _x( 'Filter courses list', 'Screen reader text for the filter links', 'sep-smart-exam-platform' ),
				'items_list_navigation' => _x( 'Courses list navigation', 'Screen reader text for the pagination', 'sep-smart-exam-platform' ),
				'items_list'            => _x( 'Courses list', 'Screen reader text for the items list', 'sep-smart-exam-platform' ),
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'course' ),
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-book-alt',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		);

		register_post_type( 'sep_course', $args );
	}

	/**
	 * Add custom meta boxes for course builder.
	 *
	 * @since    1.0.0
	 */
	public function add_course_builder_meta_box() {
		add_meta_box(
			'sep-course-builder',
			__( 'Course Builder', 'sep-smart-exam-platform' ),
			array( $this, 'course_builder_callback' ),
			'sep_course',
			'normal',
			'high'
		);
	}

	/**
	 * Callback function for course builder meta box.
	 *
	 * @since    1.0.0
	 * @param    object    $post    The post object.
	 */
	public function course_builder_callback( $post ) {
		// Add nonce for security
		wp_nonce_field( 'sep_course_builder_nonce', 'sep_course_builder_nonce' );

		// Get existing curriculum data
		$curriculum = get_post_meta( $post->ID, '_sep_curriculum', true );
		if ( ! $curriculum ) {
			$curriculum = array();
		}

		// Get all available lessons and exams
		$lessons = get_posts( array(
			'post_type' => array('sep_lesson', 'sep_quiz', 'sep_exam'),
			'posts_per_page' => -1,
			'post_status' => 'publish'
		) );

		?>
		<div id="sep-course-builder-container">
			<div class="sep-builder-toolbar">
				<button type="button" class="button button-primary" id="sep-add-section-btn"><?php _e( 'Add Section', 'sep-smart-exam-platform' ); ?></button>
				<button type="button" class="button" id="sep-add-item-btn"><?php _e( 'Add Item', 'sep-smart-exam-platform' ); ?></button>
			</div>
			
			<div id="sep-curriculum-builder" class="sep-curriculum-builder">
				<?php if ( ! empty( $curriculum ) ) : ?>
					<?php foreach ( $curriculum as $section_index => $section ) : ?>
						<div class="sep-section ui-sortable-handle" data-section-id="<?php echo esc_attr( $section['id'] ); ?>">
							<div class="sep-section-header">
								<span class="sep-section-toggle dashicons dashicons-arrow-down"></span>
								<input type="text" class="sep-section-title" value="<?php echo esc_attr( $section['title'] ); ?>" placeholder="<?php _e( 'Section Title', 'sep-smart-exam-platform' ); ?>">
								<span class="sep-section-actions">
									<button type="button" class="button-link sep-remove-section"><?php _e( 'Remove', 'sep-smart-exam-platform' ); ?></button>
								</span>
							</div>
							<div class="sep-section-content">
								<ul class="sep-items-list">
									<?php if ( ! empty( $section['items'] ) ) : ?>
										<?php foreach ( $section['items'] as $item_index => $item ) : ?>
											<li class="sep-item ui-sortable-handle" data-item-id="<?php echo esc_attr( $item['id'] ); ?>">
												<span class="sep-item-move dashicons dashicons-move"></span>
												<select class="sep-item-type" name="sep_curriculum[<?php echo esc_attr( $section['id'] ); ?>][items][<?php echo esc_attr( $item_index ); ?>][type]">
													<option value="lesson" <?php selected( $item['type'], 'lesson' ); ?>>Lesson</option>
													<option value="quiz" <?php selected( $item['type'], 'quiz' ); ?>>Quiz</option>
													<option value="exam" <?php selected( $item['type'], 'exam' ); ?>>Exam</option>
												</select>
												<select class="sep-item-content" name="sep_curriculum[<?php echo esc_attr( $section['id'] ); ?>][items][<?php echo esc_attr( $item_index ); ?>][content_id]">
													<option value="">Select Content</option>
													<?php foreach ( $lessons as $lesson ) : ?>
														<option value="<?php echo esc_attr( $lesson->ID ); ?>" <?php selected( $item['content_id'], $lesson->ID ); ?>><?php echo esc_html( $lesson->post_title ); ?></option>
													<?php endforeach; ?>
												</select>
												<span class="sep-item-actions">
													<button type="button" class="button-link sep-remove-item"><?php _e( 'Remove', 'sep-smart-exam-platform' ); ?></button>
												</span>
											</li>
										<?php endforeach; ?>
									<?php endif; ?>
								</ul>
								<button type="button" class="button button-small sep-add-item-to-section"><?php _e( 'Add Item to Section', 'sep-smart-exam-platform' ); ?></button>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p><?php _e( 'No sections added yet. Click "Add Section" to begin building your course.', 'sep-smart-exam-platform' ); ?></p>
				<?php endif; ?>
			</div>
			
			<!-- Hidden template for new sections -->
			<script type="text/template" id="tmpl-sep-new-section">
				<div class="sep-section ui-sortable-handle" data-section-id="{{SECTION_ID}}">
					<div class="sep-section-header">
						<span class="sep-section-toggle dashicons dashicons-arrow-down"></span>
						<input type="text" class="sep-section-title" value="" placeholder="<?php _e( 'Section Title', 'sep-smart-exam-platform' ); ?>">
						<span class="sep-section-actions">
							<button type="button" class="button-link sep-remove-section"><?php _e( 'Remove', 'sep-smart-exam-platform' ); ?></button>
						</span>
					</div>
					<div class="sep-section-content">
						<ul class="sep-items-list">
						</ul>
						<button type="button" class="button button-small sep-add-item-to-section"><?php _e( 'Add Item to Section', 'sep-smart-exam-platform' ); ?></button>
					</div>
				</div>
			</script>
			
			<!-- Hidden template for new items -->
			<script type="text/template" id="tmpl-sep-new-item">
				<li class="sep-item ui-sortable-handle" data-item-id="{{ITEM_ID}}">
					<span class="sep-item-move dashicons dashicons-move"></span>
					<select class="sep-item-type" name="sep_curriculum[{{SECTION_ID}}][items][{{ITEM_INDEX}}][type]">
						<option value="lesson">Lesson</option>
						<option value="quiz">Quiz</option>
						<option value="exam">Exam</option>
					</select>
					<select class="sep-item-content" name="sep_curriculum[{{SECTION_ID}}][items][{{ITEM_INDEX}}][content_id]">
						<option value="">Select Content</option>
						<?php foreach ( $lessons as $lesson ) : ?>
							<option value="<?php echo esc_attr( $lesson->ID ); ?>"><?php echo esc_html( $lesson->post_title ); ?></option>
						<?php endforeach; ?>
					</select>
					<span class="sep-item-actions">
						<button type="button" class="button-link sep-remove-item"><?php _e( 'Remove', 'sep-smart-exam-platform' ); ?></button>
					</span>
				</li>
			</script>
		</div>
		<?php
	}

	/**
	 * Save course builder data.
	 *
	 * @since    1.0.0
	 * @param    int    $post_id    The post ID.
	 */
	public function save_course_builder_data( $post_id ) {
		// Check if nonce is set
		if ( ! isset( $_POST['sep_course_builder_nonce'] ) ) {
			return;
		}

		// Verify nonce
		if ( ! wp_verify_nonce( $_POST['sep_course_builder_nonce'], 'sep_course_builder_nonce' ) ) {
			return;
		}

		// Check if user has permission to edit post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Save curriculum data
		if ( isset( $_POST['sep_curriculum'] ) ) {
			update_post_meta( $post_id, '_sep_curriculum', sanitize_text_field( wp_json_encode( $_POST['sep_curriculum'] ) ) );
		}
	}

	/**
	 * Enqueue scripts and styles for course builder.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_course_builder_assets() {
		$screen = get_current_screen();

		if ( $screen->post_type === 'sep_course' ) {
			// Enqueue jQuery UI Sortable for drag and drop
			wp_enqueue_script( 'jquery-ui-sortable' );
			
			// Enqueue course builder specific styles
			wp_enqueue_style(
				$this->plugin_name . '-course-builder-css',
				plugin_dir_url( __FILE__ ) . '../assets/css/sep-course-builder.css',
				array(),
				$this->version,
				'all'
			);

			// Enqueue course builder specific scripts
			wp_enqueue_script(
				$this->plugin_name . '-course-builder-js',
				plugin_dir_url( __FILE__ ) . '../assets/js/sep-course-builder.js',
				array( 'jquery', 'jquery-ui-sortable' ),
				$this->version,
				true
			);
		}
	}

}