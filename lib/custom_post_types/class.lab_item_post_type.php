<?php
class lab_item_post_type extends theme_core {
	function lab_item_post_type() {
		add_action('init', array(&$this, 'theme_create_post_type'));
		add_filter('post_updated_messages', array(&$this, 'updated_post_messages'));
		add_action('add_meta_boxes', array(&$this, 'theme_admin_init'));
		add_filter("manage_edit-lab_item_columns", array(&$this, 'theme_edit_columns'));
		add_action("manage_posts_custom_column", array(&$this, "custom_columns"));
		add_shortcode('lab_items', array(&$this, 'shortcode'));
	}
	
	function updated_post_messages($messages) {
	
		global $post, $post_ID;
		$messages['lab_item'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Lab Item updated. <a href="%s">View lab item</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Lab Item updated.'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Lab Item restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Lab Item published. <a href="%s">View lab item</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Lab Item saved.'),
			8 => sprintf( __('Lab Item submitted. <a target="_blank" href="%s">Preview lab item</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Lab Item scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview lab item</a>'),
		  	// translators: Publish box date format, see http://php.net/date
		  	date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Lab Item draft updated. <a target="_blank" href="%s">Preview lab item</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  	);
		return $messages;
	}
	
	function shortcode($atts) {
		
		$args = array( 'post_type' => 'lab_item', 'posts_per_page' => -1);
		$loop = new WP_Query( $args );
		$out .= '<ul class="lab_items-list">';
		while ($loop->have_posts()) {
			$loop->the_post();
			$out .= '<li><p><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></p>';
			
			$location = get_post_meta(get_the_ID(), 'location', true);
			$serial_number = get_post_meta(get_the_ID(), 'serial_number', true);
			$model = get_post_meta(get_the_ID(), 'model', true);
			$line = array();
			if (!empty($location)) $line[] = 'Location: ' . esc_html($location);
			if (!empty($model)) $line[] = 'Model: ' . esc_html($model);
			if (!empty($serial_number)) $line[] = 'Serial Number: ' . esc_html($serial_number);
			
			if (!empty($line)) $out .= implode(", ", $line);
			
			
			$out .= '</li>';
		}
		$out .= '</ul>';
		return $out;
	}
	
	function theme_create_post_type() {
		if (!session_id())
			session_start();
		$labels = array(
				'name' => __('Lab Items'),
				'singular_name' => __('Lab Item'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Lab Item'),
				'edit_item' => __('Edit Lab Item'),
				'new_item' => __('New Lab Item'),
				'view_item' => __('View Lab Item'),
				'search_items' => __('Search lab items'),
				'not_found' =>  __('No lab items found'),
				'not_found_in_trash' => __('No lab items found in trash'),
				'parent_item_colon' => ''
			);
		register_post_type('lab_item',
			array(
				'labels' => $labels,
				'public' => true,
				'has_archive' => false,
				'supports' => array('title', 'editor')
			)
		);
	}
	
	function theme_admin_init() {
		add_meta_box('lab_item_meta', 'General Information', array(&$this, 'theme_metabox_meta'), 'lab_item' );
	}
	
	
	function theme_edit_columns($columns) {
		unset($columns);
		$columns['cb'] = "<input type=\"checkbox\" />";
		$columns['title'] = __('Title');
		$columns['model'] = __('Model / Brand');
		$columns['location'] = __('Location');
		$columns['serial_number'] = __('Serial Number');
		return $columns;
	}
	
	function custom_columns($column) {
		global $post;
		if ($post->post_type == 'lab_item')
			echo esc_html(get_post_meta($post->ID, $column, true));
	}
}
?>