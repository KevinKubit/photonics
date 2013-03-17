<?php
require_once('meta_info.php');
class theme_core {
	function theme_core() {
		session_start();
		if (!isset($_SESSION['t'])) $_SESSION['t'] = array();
		if (!is_admin())
			add_action('wp_print_scripts', array(&$this, 'handle_JS'), 100);
		if (function_exists('register_sidebar')) {
			register_sidebar(array(
				'name' => 'Sidebar Widgets',
				'id'   => 'sidebar-widgets',
				'description'   => 'These are widgets for the sidebar.',
				'before_widget' => '<section class="sidebar-module"><div><div><div>',
				'after_widget'  => '</div></div></div></section>',
				'before_title'  => '<h2>',
				'after_title'   => '</h2>'
			));
			
		}
		add_action('admin_print_styles', array(&$this, 'handle_admin_CSS'));
		add_action('init', array(&$this, 'remove_head_links'));
		add_action('post_edit_form_tag', array(&$this, 'add_post_enctype'));
		remove_action('wp_head', 'wp_generator');
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(180, 220);
		add_action('edit_post', array(&$this, 'theme_save_custom_posttype'));
		add_action('edit_page_form', array(&$this, 'theme_save_custom_posttype'));
		add_filter('upload_mimes', array(&$this, 'custom_upload_mimes'));
		add_filter( 'request', array(&$this, 'request_filter'));
	}
	
	function request_filter($query_vars) {
		if(isset($_GET['s']) && empty( $_GET['s'])) {
			$query_vars['s'] = " ";
		}
		return $query_vars;
	}

	
	function custom_upload_mimes ($existing_mimes = array()) {
		$existing_mimes['bib'] = 'application/octet-stream';
		$existing_mimes['ris'] = 'application/octet-stream';
		return $existing_mimes;
	}
	
	function add_post_enctype() {
		echo ' enctype="multipart/form-data"';
	}
	
	function remove_head_links() {
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
	}  
	
	function handle_JS() {
		global $post;
		if ($post->ID == SEARCH_PAGE_ID) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('photonics_advanced_search', get_template_directory_uri() . '/js/advanced_search.js', array('jquery'));
		} elseif (is_search()) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('photonics_search', get_template_directory_uri() . '/js/search.js', array('jquery'));
			wp_localize_script('photonics_search', 'Photonics', array('ajaxurl' => admin_url('admin-ajax.php')));
		}
			
	}
	
	function handle_admin_CSS() {
		wp_enqueue_style('Photonics_admin_css', get_template_directory_uri() . '/css/admin_style.css', false, '1.1', 'all');
	}
	
	function theme_metabox_meta() {
		global $meta_box, $post;
        // Use nonce for verification
        //echo 'posts: ' . wp_get_attachment_url(get_post_meta($post->ID, 'pdf', true));
        //print_r($_SESSION);
        //echo wp_get_attachment_url( 12 );
 
        echo '<table class="form-table">';
        foreach ($meta_box[$post->post_type]['fields'] as $field) {
            // get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);
     
            echo '<tr>',
                    '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], ':</label></th>',
                    '<td>';
            switch ($field['type']) {		
                case 'text':
                    echo '<input type="text" name="custom_meta[', $field['id'], ']" id="', $field['id'], '" value="', esc_attr($meta), '" size="30" />',
                        ' <div class="description">', $field['desc'], '</div>';
                    break;
                    
                case 'dropdown':
                    $options = '';
                    foreach ($field['options'] as $k => $v) {
                        $sel = ($meta == $v) ? ' selected="selected"' : '';
                        $options .= '<option'.$sel.'>' . esc_attr($v) . '</option>';
                    }
                    echo '<select name="custom_meta[', $field['id'], ']" id="', $field['id'], '">' . $options . '</select>',
                        ' <div class="description">', $field['desc'], '</div>';
                    break;
                    
                case 'date':
                    $months_array = array('January', 'February', 'March', 'April' , 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                    $months = '';
                    foreach ($months_array as $m) {
                        if ($m == date('F', $meta)) $months .= '<option selected="selected">' . $m . '</option>';
                        else $months .= '<option>' . $m . '</option>';
                    }
                    $day = (!empty($meta)) ? date('j', intval($meta)) : '';
                    $year = (!empty($meta)) ? date('Y', intval($meta)) : '';
                    echo '<select name="custom_meta[', $field['id'], '][month]" id="', $field['id'], '-month">' . $months . '</select> ',
                        '<input type="text" value="' . $day . '" class="day-field" maxlength="2" name="custom_meta[', $field['id'], '][day]" id="', $field['id'], '-day" /> ',
                        '<input type="text" value="' . $year . '" class="year-field" maxlength="4" name="custom_meta[', $field['id'], '][year]" id="', $field['id'], '-year" /> ',
                        ' <div class="description">', $field['desc'], '</div>';
                    break;
                    
                case 'file':
                    
                    echo '<input type="file" name="custom_meta[', $field['id'], ']" id="', $field['id'], '" size="30" />',
                        ' <div class="description">', $field['desc'], '</div>';
                    if (!empty($meta) && intval($meta) > 0) {
                        echo '<p class="current-file"><span>Current File:</span> ' . basename(wp_get_attachment_url(intval($meta))) . ' (<input type="checkbox" name="custom_meta[', $field['id'], '_remove]" value="' . intval($meta) . '" /> Remove File)</p>';
                    }
                    break;
            
                case 'textarea':
                    if ( empty( $field['tinymce'] )  ) {
                        echo '<textarea name="custom_meta[', $field['id'], ']" id="', $field['id'], '" cols="40" rows="4">', esc_attr($meta), '</textarea>',
                            ' <div class="description">', $field['desc'], '</div>';
                        break;
                    } else {
                        $args = array(
                            'textarea_name' => 'custom_meta[' . $field['id'] . ']',
                            'media_buttons' => false,
                            'editor_class' => 'photonics-meta-editor',
                            'textarea_rows' => '6',
                            'teeny' => true
                        );
						wp_editor( $meta, $field['id'], $args );

                    }
     
            }
            echo 	'<td>',
                '</tr>';
        }
     
        echo '</table>';
	}
	
	function handle_file($file_array) {
		
		//if (!isset($_FILES['custom_meta'])) return;
		
		
		if(isset($file_array['name'])) {
			global $post;
			// check filetype is pdf and return if not
        	$file_type = $file_array['type'];
			
			// upload the file
			$overrides = array( 'test_form' => false);
			$file = wp_handle_upload($file_array, $overrides);
			$_SESSION[debug][] = $file;
			//$_SESSION[debug][] = print_r($_FILES, true);
      		if(isset($file['file'])) {
            	// Gather data for attachment
            	$title = preg_replace("^(.*)\..*$","$1",$file_array['name']);
				$attachment = array(
                	'post_mime_type' => $file_type,
                	'post_title' => addslashes($title),
                	'post_content' => '',
                	'post_status' => 'inherit',
                	'post_parent' => $post->ID
            	);
            	//create attachment & update metadata
            	$attach_id = wp_insert_attachment( $attachment, $file['file'] );
            	// Before we update the post meta, trash any previously uploaded pdfs for this post.
				
            	// Now, update the post meta to associate the new pdf with the post
				return $attach_id;
            	//update_post_meta($post->ID, $field_name, $attach_id);
        	}
    	}
	}
	
	function theme_save_custom_posttype($post_id) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return;

		global $wp_rewrite, $meta_box;
		if ((isset($_REQUEST['custom_meta']) && !empty($_REQUEST['custom_meta'])) || (isset($_FILES['custom_meta']) && !empty($_FILES['custom_meta']))) {
			$this_post = get_post($post_id);
			if (!wp_verify_nonce( $_POST['_wpnonce'], 'update-' . $this_post->post_type . '_' . $post_id))
				return $post_id;
			
            if ( isset( $_REQUEST['custom_meta']['private_info'] ) ) {
                update_post_meta( $post_id, 'private_info', $_REQUEST['custom_meta']['private_info'] );
            }
            
			$files = theme_util::multiple($_FILES);
			$files = $files['custom_meta'];	
			$update_data = $_REQUEST['custom_meta'];
			$meta = $meta_box[$this_post->post_type]['fields'];
            $_SESSION[debug][c] = print_r($meta, true);
			foreach ($meta as $meta_key => $meta_array) {
				if ($meta_array['type'] == 'file') {
					if (isset($_REQUEST['custom_meta'][$meta_key . '_remove'])) {
						wp_delete_attachment($_REQUEST['custom_meta'][$meta_key . '_remove']);
						update_post_meta($post_id, $meta_key, '');
					}
					if (isset($files[$meta_key]) && !empty($files[$meta_key]) && !empty($files[$meta_key]['name'])) {
						$old_attachment = get_post_meta($post_id, $meta_key, true);
						if (!empty($old_attachment))
							wp_delete_attachment($old_attachment);
						update_post_meta($post_id, $meta_key, $this->handle_file($files[$meta_key]));
					}
				} elseif ($meta_array['type'] == 'date') {
					if (isset($update_data[$meta_key]) && is_array($update_data[$meta_key])) {
						if (isset($update_data[$meta_key]['day']) && isset($update_data[$meta_key]['month']) && isset($update_data[$meta_key]['year']) &&
							!empty($update_data[$meta_key]['day']) && !empty($update_data[$meta_key]['month']) && !empty($update_data[$meta_key]['year']) ) {
							$time_value = strtotime($update_data[$meta_key]['month'] . " " . $update_data[$meta_key]['day'] . ", " . $update_data[$meta_key]['year']);
							update_post_meta($post_id, $meta_key, $time_value);
						}
					}
				} else {
					update_post_meta($post_id, $meta_key, $update_data[$meta_key]);
				}
			}
			
		}
       

		if (isset($_REQUEST['supplemental_files'])) {
			$this_post = get_post($post_id);
			if (!wp_verify_nonce( $_POST['_wpnonce'], 'update-' . $this_post->post_type . '_' . $post_id))
				return $post_id;
			
			//$fcopy = $_FILES;
			//unset($fcopy['custom_meta']);
			$files = theme_util::multiple($_FILES);
			$files = $files['supplemental_files'];
			
			$current_files = get_post_meta($this_post->ID, 'supplemental_files', true);
			
			
			foreach ($_REQUEST['supplemental_files'] as $key => $file) {
				if (isset($file['name']) && isset($files['file' . $key]) && !empty($files['file' . $key]['name'])) {
					$file['aid'] = $this->handle_file($files['file' . $key]);
					$current_files[] = $file;
				}
			}
			
			if (isset($_REQUEST['remove_supplemental_files'])) {
				$current_files_dup = $current_files;
				foreach ($_REQUEST['remove_supplemental_files'] as $fid) {
					foreach ($current_files_dup as $k => $v) {
						if ($v['aid'] == $fid) {
							wp_delete_attachment($fid);
							unset($current_files[$k]);
						}
					}
				}
			}
			update_post_meta($this_post->ID, 'supplemental_files', $current_files);
			//$_SESSION[debug][] = print_r($current_files, true);
		}
		return true;
	}
	
}
?>
