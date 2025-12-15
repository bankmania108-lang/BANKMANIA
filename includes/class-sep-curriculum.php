<?php
/**
 * The curriculum management functionality of the plugin.
 *
 * @link       https://seputility.com
 * @since      1.0.0
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/includes
 */

/**
 * The curriculum management functionality of the plugin.
 *
 * Defines the curriculum functionality with proper course structure.
 *
 * @package    Sep_Smart_Exam_Platform
 * @subpackage Sep_Smart_Exam_Platform/includes
 * @author     Seputility <contact@seputility.com>
 */
class SEP_Curriculum {

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
	 * Register custom post type for lessons.
	 *
	 * @since    1.0.0
	 */
	public function register_lesson_post_type() {
		$args = array(
			'labels' => array(
				'name'                  => _x( 'Lessons', 'Post type general name', 'sep-smart-exam-platform' ),
				'singular_name'         => _x( 'Lesson', 'Post type singular name', 'sep-smart-exam-platform' ),
				'menu_name'             => _x( 'Lessons', 'Admin Menu text', 'sep-smart-exam-platform' ),
				'name_admin_bar'        => _x( 'Lesson', 'Add New on Toolbar', 'sep-smart-exam-platform' ),
				'add_new'               => __( 'Add New', 'sep-smart-exam-platform' ),
				'add_new_item'          => __( 'Add New Lesson', 'sep-smart-exam-platform' ),
				'new_item'              => __( 'New Lesson', 'sep-smart-exam-platform' ),
				'edit_item'             => __( 'Edit Lesson', 'sep-smart-exam-platform' ),
				'view_item'             => __( 'View Lesson', 'sep-smart-exam-platform' ),
				'all_items'             => __( 'All Lessons', 'sep-smart-exam-platform' ),
				'search_items'          => __( 'Search Lessons', 'sep-smart-exam-platform' ),
				'parent_item_colon'     => __( 'Parent Lessons:', 'sep-smart-exam-platform' ),
				'not_found'             => __( 'No lessons found.', 'sep-smart-exam-platform' ),
				'not_found_in_trash'    => __( 'No lessons found in Trash.', 'sep-smart-exam-platform' ),
				'featured_image'        => _x( 'Lesson Cover Image', 'Overrides the "Featured Image" phrase', 'sep-smart-exam-platform' ),
				'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'sep-smart-exam-platform' ),
				'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'sep-smart-exam-platform' ),
				'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'sep-smart-exam-platform' ),
				'archives'              => _x( 'Lesson archives', 'The post type archive label used in nav menus', 'sep-smart-exam-platform' ),
				'insert_into_item'      => _x( 'Insert into lesson', 'Overrides the "Insert into post" phrase', 'sep-smart-exam-platform' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this lesson', 'Overrides the "Uploaded to this post" phrase', 'sep-smart-exam-platform' ),
				'filter_items_list'     => _x( 'Filter lessons list', 'Screen reader text for the filter links', 'sep-smart-exam-platform' ),
				'items_list_navigation' => _x( 'Lessons list navigation', 'Screen reader text for the pagination', 'sep-smart-exam-platform' ),
				'items_list'            => _x( 'Lessons list', 'Screen reader text for the items list', 'sep-smart-exam-platform' ),
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => 'edit.php?post_type=sep_course',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'lesson' ),
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-media-document',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
		);

		register_post_type( 'sep_lesson', $args );
	}

	/**
	 * Register custom post type for quizzes.
	 *
	 * @since    1.0.0
	 */
	public function register_quiz_post_type() {
		$args = array(
			'labels' => array(
				'name'                  => _x( 'Quizzes', 'Post type general name', 'sep-smart-exam-platform' ),
				'singular_name'         => _x( 'Quiz', 'Post type singular name', 'sep-smart-exam-platform' ),
				'menu_name'             => _x( 'Quizzes', 'Admin Menu text', 'sep-smart-exam-platform' ),
				'name_admin_bar'        => _x( 'Quiz', 'Add New on Toolbar', 'sep-smart-exam-platform' ),
				'add_new'               => __( 'Add New', 'sep-smart-exam-platform' ),
				'add_new_item'          => __( 'Add New Quiz', 'sep-smart-exam-platform' ),
				'new_item'              => __( 'New Quiz', 'sep-smart-exam-platform' ),
				'edit_item'             => __( 'Edit Quiz', 'sep-smart-exam-platform' ),
				'view_item'             => __( 'View Quiz', 'sep-smart-exam-platform' ),
				'all_items'             => __( 'All Quizzes', 'sep-smart-exam-platform' ),
				'search_items'          => __( 'Search Quizzes', 'sep-smart-exam-platform' ),
				'parent_item_colon'     => __( 'Parent Quizzes:', 'sep-smart-exam-platform' ),
				'not_found'             => __( 'No quizzes found.', 'sep-smart-exam-platform' ),
				'not_found_in_trash'    => __( 'No quizzes found in Trash.', 'sep-smart-exam-platform' ),
				'featured_image'        => _x( 'Quiz Cover Image', 'Overrides the "Featured Image" phrase', 'sep-smart-exam-platform' ),
				'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase', 'sep-smart-exam-platform' ),
				'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase', 'sep-smart-exam-platform' ),
				'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase', 'sep-smart-exam-platform' ),
				'archives'              => _x( 'Quiz archives', 'The post type archive label used in nav menus', 'sep-smart-exam-platform' ),
				'insert_into_item'      => _x( 'Insert into quiz', 'Overrides the "Insert into post" phrase', 'sep-smart-exam-platform' ),
				'uploaded_to_this_item' => _x( 'Uploaded to this quiz', 'Overrides the "Uploaded to this post" phrase', 'sep-smart-exam-platform' ),
				'filter_items_list'     => _x( 'Filter quizzes list', 'Screen reader text for the filter links', 'sep-smart-exam-platform' ),
				'items_list_navigation' => _x( 'Quizzes list navigation', 'Screen reader text for the pagination', 'sep-smart-exam-platform' ),
				'items_list'            => _x( 'Quizzes list', 'Screen reader text for the items list', 'sep-smart-exam-platform' ),
			),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => 'edit.php?post_type=sep_course',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'quiz' ),
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-clipboard',
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
		);

		register_post_type( 'sep_quiz', $args );
	}

	/**
	 * Get curriculum structure for a course.
	 *
	 * @since    1.0.0
	 * @param    int    $course_id    The course ID.
	 * @return   array               The curriculum structure.
	 */
	public function get_course_curriculum( $course_id ) {
		$curriculum = get_post_meta( $course_id, '_sep_curriculum', true );
		
		if ( ! $curriculum ) {
			return array();
		}
		
		$curriculum = json_decode( $curriculum, true );
		
		// Process curriculum to add additional metadata
		foreach ( $curriculum as $section_key => $section ) {
			if ( isset( $section['items'] ) ) {
				foreach ( $section['items'] as $item_key => $item ) {
					if ( isset( $item['content_id'] ) ) {
						$content_post = get_post( $item['content_id'] );
						if ( $content_post ) {
							$curriculum[$section_key]['items'][$item_key]['title'] = $content_post->post_title;
							$curriculum[$section_key]['items'][$item_key]['content_type'] = $content_post->post_type;
						}
					}
				}
			}
		}
		
		return $curriculum;
	}

	/**
	 * Get formatted curriculum for frontend display.
	 *
	 * @since    1.0.0
	 * @param    int    $course_id    The course ID.
	 * @param    int    $user_id      The user ID (optional).
	 * @return   string               The formatted curriculum HTML.
	 */
	public function get_formatted_curriculum( $course_id, $user_id = null ) {
		$curriculum = $this->get_course_curriculum( $course_id );
		
		if ( empty( $curriculum ) ) {
			return '<p>' . __( 'No curriculum available for this course.', 'sep-smart-exam-platform' ) . '</p>';
		}
		
		ob_start();
		
		echo '<div class="sep-course-curriculum">';
		
		foreach ( $curriculum as $section ) {
			echo '<div class="sep-curriculum-section">';
			echo '<h3 class="sep-section-title">' . esc_html( $section['title'] ) . '</h3>';
			
			if ( ! empty( $section['items'] ) ) {
				echo '<ul class="sep-curriculum-items">';
				
				foreach ( $section['items'] as $item ) {
					$item_class = 'sep-curriculum-item';
					
					// Check if user has completed this item
					if ( $user_id ) {
						$completed = $this->is_item_completed( $item['content_id'], $user_id );
						if ( $completed ) {
							$item_class .= ' sep-item-completed';
						}
					}
					
					echo '<li class="' . esc_attr( $item_class ) . '" data-item-id="' . esc_attr( $item['content_id'] ) . '">';
					
					// Determine icon based on content type
					$icon = 'dashicons-media-default';
					switch ( $item['content_type'] ) {
						case 'sep_lesson':
							$icon = 'dashicons-media-document';
							break;
						case 'sep_quiz':
							$icon = 'dashicons-clipboard';
							break;
						case 'sep_exam':
							$icon = 'dashicons-welcome-learn-more';
							break;
					}
					
					echo '<span class="sep-item-icon dashicons ' . esc_attr( $icon ) . '"></span>';
					echo '<a href="' . get_permalink( $item['content_id'] ) . '">' . esc_html( $item['title'] ) . '</a>';
					
					if ( $user_id && isset( $completed ) && $completed ) {
						echo '<span class="sep-item-status dashicons dashicons-yes"></span>';
					}
					
					echo '</li>';
				}
				
				echo '</ul>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
		
		return ob_get_clean();
	}

	/**
	 * Check if an item is completed by a user.
	 *
	 * @since    1.0.0
	 * @param    int    $item_id    The item ID.
	 * @param    int    $user_id    The user ID.
	 * @return   bool               Whether the item is completed.
	 */
	public function is_item_completed( $item_id, $user_id ) {
		$post_type = get_post_type( $item_id );
		
		switch ( $post_type ) {
			case 'sep_lesson':
				// Check if lesson has been viewed
				$viewed = get_user_meta( $user_id, '_sep_lesson_viewed_' . $item_id, true );
				return ! empty( $viewed );
				
			case 'sep_quiz':
			case 'sep_exam':
				// Check if there are any attempts for this quiz/exam
				$attempts = get_posts( array(
					'post_type' => 'sep_attempt',
					'meta_query' => array(
						array(
							'key' => '_sep_exam_id',
							'value' => $item_id,
						),
						array(
							'key' => '_sep_user_id',
							'value' => $user_id,
						),
					),
					'posts_per_page' => 1,
				) );
				return count( $attempts ) > 0;
				
			default:
				return false;
		}
	}

	/**
	 * Get curriculum statistics for a course.
	 *
	 * @since    1.0.0
	 * @param    int    $course_id    The course ID.
	 * @param    int    $user_id      The user ID.
	 * @return   array                Curriculum statistics.
	 */
	public function get_curriculum_stats( $course_id, $user_id ) {
		$curriculum = $this->get_course_curriculum( $course_id );
		
		$total_items = 0;
		$completed_items = 0;
		
		foreach ( $curriculum as $section ) {
			if ( ! empty( $section['items'] ) ) {
				foreach ( $section['items'] as $item ) {
					$total_items++;
					
					if ( $this->is_item_completed( $item['content_id'], $user_id ) ) {
						$completed_items++;
					}
				}
			}
		}
		
		$completion_percentage = $total_items > 0 ? round( ( $completed_items / $total_items ) * 100 ) : 0;
		
		return array(
			'total_items' => $total_items,
			'completed_items' => $completed_items,
			'completion_percentage' => $completion_percentage,
		);
	}

}