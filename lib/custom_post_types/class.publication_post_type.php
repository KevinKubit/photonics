<?php
class publication_post_type extends theme_core {
	
    function publication_post_type() {
		add_action('init', array(&$this, 'theme_create_post_type'));
		add_filter('post_updated_messages', array(&$this, 'updated_post_messages'));
		add_action('add_meta_boxes', array(&$this, 'theme_admin_init'));
		add_filter("manage_edit-publication_columns", array(&$this, 'theme_edit_columns'));
		add_action("manage_posts_custom_column", array(&$this, "custom_columns"));
		add_shortcode('publications', array(&$this, 'shortcode'));
		//add_action( 'admin_enqueue_scripts', array( &$this, 'load_admin_javascript' ), 99 );
	}

    function load_admin_javascript() {
        wp_enqueue_script( 'photonics-admin-publications', get_template_directory_uri() . '/js/admin-publications.js', array( 'jquery' ), 1.0, true );
    }
	
	function updated_post_messages($messages) {
	
		global $post, $post_ID;
		$messages['publication'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Publication updated. <a href="%s">View publication</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Publication updated.'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Publication restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Publication published. <a href="%s">View publication</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Publication saved.'),
			8 => sprintf( __('Publication submitted. <a target="_blank" href="%s">Preview publication</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Publication scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview publication</a>'),
		  	// translators: Publish box date format, see http://php.net/date
		  	date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Publication draft updated. <a target="_blank" href="%s">Preview publication</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  	);
		return $messages;
	}
	
	function shortcode($atts) {
		
		$cat = (isset($atts['category'])) ? $atts['category'] : 'Journal Articles';
		$args = array(
				'post_type' => 'publication',
				'posts_per_page' => -1,
				'orderby' => 'meta_value_num',
				'meta_key' => 'publication_date',
				'meta_query' => array(
						array(
							'key' => 'category',
							'value' => $cat
						)
				)
		);
		$loop = new WP_Query( $args );
		$out .= '<ul class="publications-list">';
		while ( $loop->have_posts() ) : $loop->the_post();

			$out .= '<li><p>';
			$out .= '<a href="'.esc_url(get_permalink(get_the_ID())).'">(' . esc_html(get_post_meta(get_the_ID(), 'identifier', true)) . ')</a> ';
			$out .= ' ' . esc_html(get_post_meta(get_the_ID(), 'full_citation', true)) . '</p>';
			
			$line = array();
			$manuscript = get_post_meta(get_the_ID(), 'manuscript', true);
			$doi = get_post_meta(get_the_ID(), 'doi', true);
			$supp_files = get_post_meta(get_the_ID(), 'supplemental_files', true);
			if (!empty($manuscript)) $line[] = '<a href="'.esc_url(wp_get_attachment_url($manuscript)).'">PDF</a>';
			if (!empty($supp_files)) {
				foreach ($supp_files as $f) {
					$file_url = esc_url(wp_get_attachment_url($f['aid']));
					$line[] = '<a href="' . $file_url . '">' . strtoupper(preg_replace('/^.*\.(.*)$/is', '$1', basename($file_url))) . '</a>';
				}
			}
			if (!empty($doi)) $line[] = 'doi: <a href="http://dx.doi.org/' . esc_url($doi) . '">' . esc_html($doi) . '</a>';
			
			if (!empty($line)) $out .= '<p>' . implode(" | ", $line) . '</p>';
			
			
			$out .= 'Pub date: ' . date("F d, Y", intval(get_post_meta(get_the_ID(), 'publication_date', true))) . ' - ' . get_post_meta(get_the_ID(), 'publication_date', true);
			$out .= '</li>';
		endwhile;
		$out .= '</ul>';
		return $out;
	}
	
	function theme_create_post_type() {
		if (!session_id())
			session_start();
		$labels = array(
				'name' => __('Publications'),
				'singular_name' => __('Publication'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Publication'),
				'edit_item' => __('Edit Publication'),
				'new_item' => __('New Publication'),
				'view_item' => __('View Publication'),
				'search_items' => __('Search publications'),
				'not_found' =>  __('No publications found'),
				'not_found_in_trash' => __('No publications found in trash'),
				'parent_item_colon' => ''
			);
		register_post_type('publication',
			array(
				'labels' => $labels,
				'public' => true,
				'has_archive' => false,
				'supports' => array('title', 'editor')
			)
		);
	}
	
	function theme_admin_init() {
		add_meta_box('publication_meta', 'General Information', array(&$this, 'theme_metabox_meta'), 'publication' );
		add_meta_box('publication_supplemental_files', 'Supplemental Files', array(&$this, 'theme_supplemental_meta'), 'publication' );
	}
	
	function theme_supplemental_meta() {
		global $post;
		
		$files = get_post_meta($post->ID, 'supplemental_files', true);
		
		if (!empty($files)) {
			echo '<table class="form-table supplemental-file-table">';
			echo '<thead><tr><th>File Name</th><th>File</th><th>Description</th><th width="30">Remove</th></tr></thead>';
			foreach ($files as $f) {
				echo '<tr><td>' . esc_html($f['name']) . '</td>';
				echo '<td><a href="' . esc_url(wp_get_attachment_url($f['aid'])) . '">' . esc_html(basename(wp_get_attachment_url($f['aid']))) . '</a></td>';
				echo '<td>' . esc_html($f['description']) . '</td>';
				echo '<td><input name="remove_supplemental_files[]" type="checkbox" value="' . esc_attr($f['aid']) . '" /></td></tr>';
			}
			echo '</table>';
		}
		echo '<table class="new-file-table">';
		echo '<thead><tr><td>Add a File</td></tr></thead>';
		echo '<tr><td><label>Name:</label> <input type="text" name="supplemental_files[0][name]" /></td><td><label>File:</label> <input type="file" name="supplemental_files[file0]" /></td><td><label>Description:</label> <input type="text" name="supplemental_files[0][description]" /></td></tr>';
		echo '</table>';
	}
	
	function theme_edit_columns($columns) {
		unset($columns);
		$columns['cb'] = "<input type=\"checkbox\" />";
		$columns['title'] = __('Title');
		$columns['authors'] = __('Authors');
		$columns['doi'] = __('DOI');
		$columns['category'] = __('Category');
		return $columns;
	}
	
	function custom_columns($column) {
		global $post;
		if ($post->post_type == 'publication')
			echo esc_html(get_post_meta($post->ID, $column, true));
	}
}
?>