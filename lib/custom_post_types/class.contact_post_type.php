<?php
class contact_post_type extends theme_core {
	function contact_post_type() {
		add_action('init', array(&$this, 'theme_create_post_type'));
		add_filter('post_updated_messages', array(&$this, 'updated_post_messages'));
		add_action('add_meta_boxes', array(&$this, 'theme_admin_init'));
		add_filter("manage_edit-contact_columns", array(&$this, 'theme_edit_columns'));
		add_action("manage_posts_custom_column", array(&$this, "custom_columns"));
		add_shortcode('contacts', array(&$this, 'shortcode'));
	}
	
	function updated_post_messages($messages) {
		global $post, $post_ID;
		$messages['contact'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Contact updated. <a href="%s">View contact</a>'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.'),
			3 => __('Custom field deleted.'),
			4 => __('Contact updated.'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Contact restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Contact published. <a href="%s">View contact</a>'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Contact saved.'),
			8 => sprintf( __('Contact submitted. <a target="_blank" href="%s">Preview contact</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Contact scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview contact</a>'),
		  	// translators: Publish box date format, see http://php.net/date
		  	date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Contact draft updated. <a target="_blank" href="%s">Preview contact</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  	);
		return $messages;
	}
	
	function shortcode($atts) {
		$out = '';
		$role = (isset($atts['role'])) ? $atts['role'] : 'Graduate Student';
		$args = array( 'post_type' => 'contact', 'posts_per_page' => -1, 'meta_key' => 'department_role', 'meta_value' => $role );
		$loop = new WP_Query( $args );
		$out .= '<ul class="contacts-list">';
		while ( $loop->have_posts() ) : $loop->the_post();
			/*$collections = unserialize(get_post_meta(get_the_ID(), 'project_collection', true));
			if (in_array($atts['type'], $collections)) {
				$out .= '<li><a href="'.get_permalink(get_the_ID()).'">';
				$out .= get_the_title();
				$out .= '</a>, '.get_post_meta(get_the_ID(), 'project_location', true).'</li>';
			}*/
			$if_image = (has_post_thumbnail()) ? ' class="has-image" ' : '';
			$out .= '<li ' . $if_image .'>';
			if(has_post_thumbnail()) {
        		$out .= '<div class="photo">' . get_the_post_thumbnail() . '</div>';
        	} else {
				$out .= '<div class="photo-placeholder">(No Photo)</div>';
			}
			$out .= '<div class="text">';
			$out .= '<div class="name"><a href="'.esc_url(get_permalink(get_the_ID())).'">' . esc_html(get_the_title()) . '</a></div>';
			$email = get_post_meta(get_the_ID(), 'email', true);
			$phone = get_post_meta(get_the_ID(), 'phone', true);
			$fax = get_post_meta(get_the_ID(), 'fax', true);
			$degrees = get_post_meta(get_the_ID(), 'degrees', true);
			$office = get_post_meta(get_the_ID(), 'office', true);
			
			if (!empty($email)) $out .= '<div class="email">' . str_replace('.', ' [dot] ', str_replace('@', ' [at] ', esc_html($email))) . '</div>';
        	if (!empty($phone)) $out .= '<div class="phone">(phone) ' . esc_html($phone) . '</div>';
			if (!empty($fax)) $out .= '<div class="fax">(fax) ' . esc_html($fax) . '</div>';
			if (!empty($office)) $out .= '<div class="office">' . esc_html($office) . '</div>';
        
			$out .= '</div></li>';
		endwhile;
		$out .= '</ul>';
		return $out;
	}
	
	function theme_create_post_type() {
		$labels = array(
				'name' => __('Contact Directory'),
				'singular_name' => __('Contact'),
				'add_new' => __('Add New'),
				'add_new_item' => __('Add New Contact'),
				'edit_item' => __('Edit Contact'),
				'new_item' => __('New Contact'),
				'view_item' => __('View Contact'),
				'search_items' => __('Search contacts'),
				'not_found' =>  __('No contacts found'),
				'not_found_in_trash' => __('No contacts found in trash'),
				'parent_item_colon' => ''
			);
		register_post_type('contact',
			array(
				'labels' => $labels,
				'public' => true,
				'has_archive' => false,
				'supports' => array('title', 'editor', 'thumbnail')
			)
		);
	}
	
	function theme_admin_init() {
		add_meta_box('contact_meta', 'General Information', array(&$this, 'theme_metabox_meta'), 'contact' );
		add_meta_box('private_contact_meta', 'Private Information', array(&$this, 'theme_metabox_private_meta'), 'contact' );
	}
	
	function theme_edit_columns($columns) {
		unset($columns);
		$columns['cb'] = "<input type=\"checkbox\" />";
		$columns['title'] = __('Name');
		$columns['email'] = __('Email');
		$columns['phone'] = __('Phone');
		$columns['office'] = __('Office');
		return $columns;
	}
	
	function custom_columns($column) {
		global $post;
		if ($post->post_type == 'contact')
			echo esc_html(get_post_meta($post->ID, $column, true));
	}

	function theme_metabox_private_meta() {
		global $meta_box, $post;
		$private_info = get_post_meta( $post->ID, 'private_info', true );
	?>
		<table class="private-form-table">
			<tr>
				<td>
					<label for="contact-private">Private Information:</label>
					<?php
					$args = array(
                        'textarea_name' => 'custom_meta[private_info]',
                        'media_buttons' => false,
                        'editor_class' => 'photonics-meta-editor',
                        'textarea_rows' => '6',
                        'teeny' => true
                    );
					wp_editor( $private_info, 'private-info', $args );
					?>
					<div><em>Only logged in users will be able to view this information.</em></div>
				</td>
			</tr>
		</table>
	<?php
	}
}
?>