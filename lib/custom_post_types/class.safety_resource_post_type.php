<?php
class safety_resource_post_type extends theme_core {
	function safety_resource_post_type() {
		add_action('init', array(&$this, 'theme_create_post_type'));
		add_filter('post_updated_messages', array(&$this, 'updated_post_messages'));
		add_action('add_meta_boxes', array(&$this, 'theme_admin_init'));
		add_filter("manage_edit-safety_resource_columns", array(&$this, 'theme_edit_columns'));
		add_action("manage_posts_custom_column", array(&$this, "custom_columns"));
		add_shortcode('safety_resources', array(&$this, 'shortcode'));
	}
	
	function updated_post_messages($messages) {
	
		global $post, $post_ID;
		$messages['safety_resource'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Safety Resource updated. <a href="%s">View safety resource</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Safety Resource updated.'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Safety Resource restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Safety Resource published. <a href="%s">View safety resource</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Safety Resource saved.'),
			8 => sprintf( __('Safety Resource submitted. <a target="_blank" href="%s">Preview safety resource</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Safety Resource scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview safety resource</a>'),
		  	// translators: Publish box date format, see http://php.net/date
		  	date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Safety Resource draft updated. <a target="_blank" href="%s">Preview safety resource</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  	);
		return $messages;
	}
	
	function shortcode($atts) {
		
		$args = array( 'post_type' => 'safety_resource', 'posts_per_page' => -1);
		$loop = new WP_Query( $args );
		$out .= '<ul class="safety_resources-list">';
		while ($loop->have_posts()) {
			$loop->the_post();
			$out .= '<li><p><a href="' . get_permalink() . '">' . get_the_title() . '</a></p>';
			
			$location = get_post_meta(get_the_ID(), 'location', true);
			$serial_number = get_post_meta(get_the_ID(), 'serial_number', true);
			$model = get_post_meta(get_the_ID(), 'model', true);
			$line = array();
			if (!empty($location)) $line[] = 'Location: ' . $location;
			if (!empty($model)) $line[] = 'Model: ' . $model;
			if (!empty($serial_number)) $line[] = 'Serial Number: ' . $serial_number;
			
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
				'name' => __('Safety Resources'),
				'singular_name' => __('Safety Resource'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Safety Resource'),
				'edit_item' => __('Edit Safety Resource'),
				'new_item' => __('New Safety Resource'),
				'view_item' => __('View Safety Resource'),
				'search_items' => __('Search safety resources'),
				'not_found' =>  __('No safety resources found'),
				'not_found_in_trash' => __('No safety resources found in trash'),
				'parent_item_colon' => ''
			);
		register_post_type('safety_resource',
			array(
				'labels' => $labels,
				'public' => true,
				'has_archive' => false,
				'supports' => array('title', 'editor')
			)
		);
	}
	
	function theme_admin_init() {
		add_meta_box('safety_resource_meta', 'General Information', array(&$this, 'theme_metabox_meta'), 'safety_resource' );
	}
	
	
	function theme_edit_columns($columns) {
		unset($columns);
		$columns['cb'] = "<input type=\"checkbox\" />";
		$columns['title'] = __('Title');
		return $columns;
	}
	
	function custom_columns($column) {
		global $post;
		if ($post->post_type == 'safety_resource')
			echo esc_html(get_post_meta($post->ID, $column, true));
	}
}
?>