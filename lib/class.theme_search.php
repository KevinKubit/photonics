<?php
class theme_search extends theme_core {
	function theme_search() {
		add_filter('pre_get_posts', array(&$this, 'search_filter'));
		add_filter('posts_join', array(&$this, 'search_metadata_join'));
		add_filter('posts_where', array(&$this, 'search_metadata_where'));
		add_filter('posts_distinct', array(&$this, 'search_distinct'));  
		add_action('wp_ajax_nopriv_ajax_search', array(&$this, 'ajax_search'));
		add_action('wp_ajax_ajax_search', array(&$this, 'ajax_search'));
	}
	
	function ajax_search() {
		$output = array('success' => 1);
		$output = json_encode($output);
		print_r($output);
		exit;
	}
	
	function search_distinct($distinct) {
		if ($distinct == '') {
			return 'DISTINCT';
		}
		return $distinct;
	}
	
	function search_metadata_where($where) {
		global $wp_query, $wpdb;
		if (is_search()) {
		
			if ($where != '') {
			
				$n = (isset($wp_query->query_vars['exact']) && $wp_query->query_vars['exact']) ? '' : '%';
				$search = '';
				$seperator = '';
				$terms = $this->get_search_terms();
				//$_SESSION['t']['exact'] = print_r($terms, true);
				$search .= '(';
				foreach($terms as $term){
					$search .= $seperator;	
					$search .= sprintf("((%s.post_title LIKE '%s%s%s') OR (%s.post_content LIKE '%s%s%s') OR ( m.meta_value LIKE '%s%s%s' AND m.meta_key = 'abstract') OR ( m.meta_value LIKE '%s%s%s' AND m.meta_key = 'full_citation') OR ( m.meta_value LIKE '%s%s%s' AND m.meta_key = 'doi') )", $wpdb->posts, $n, $term, $n, $wpdb->posts, $n, $term, $n, $n, $term, $n, $n, $term, $n, $n, $term, $n, $wpdb->posts);		
					$seperator = ' OR ';
				}
		
				$search .= ')';
				
				
				$where = preg_replace('/\(\(\(.*?\)\)\)/is', '(('.$search.'))', $where);
				//if (!isset($_SESSION['t'])) $_SESSION['t'] = array();
				//$_SESSION['t']['where'] = $where;
			}
		}
		return $where;
	}
	
	function search_metadata_join($join) {
		global $wp_query, $wpdb;

		if (!empty($wp_query->query_vars['s'])) {
			$join .= " LEFT JOIN $wpdb->postmeta AS m ON ($wpdb->posts.ID = m.post_id) ";
		}
		//$_SESSION['t']['join'] = $join;
		return $join;
	}

	function get_search_terms() {
		global $wp_query, $wpdb;
		$s = isset($wp_query->query_vars['s']) ? $wp_query->query_vars['s'] : '';
		$sentence = isset($wp_query->query_vars['sentence']) ? $wp_query->query_vars['sentence'] : false;
		$search_terms = array();

		if ( !empty($s) )
		{
			// added slashes screw with quote grouping when done early, so done later
			$s = stripslashes($s);
			if ($sentence)
			{
				$search_terms = array($s);
			} else {
				preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $s, $matches);
				$search_terms = array_map(create_function('$a', 'return trim($a, "\\"\'\\n\\r ");'), $matches[0]);
			}
		}
		return $search_terms;
	}
	
	
	
	function search_filter($query) {
		//print_r($_REQUEST);
		if ($query->is_search) {
			/*if (isset($_GET['s']) && empty($_GET['s'])) {
				$query->is_search = true;
				//echo 'sdf';
			}*/
			//$query->set('posts_per_page', 10);
			if (isset($_REQUEST['search_category'])) {
				$meta_query = array();
				switch ($_REQUEST['search_category']) {
					default:
					case 'Entire Site':
					
					break;
					
					case 'Publications':
					$query->set('post_type', 'publication');
					break;
					case 'Contacts':
					$query->set('post_type', 'contact');
					break;
				}
				
				if (isset($_REQUEST['search_publication_category'])) {
					if ($_REQUEST['search_publication_category'] != '0') {
						
						$meta_query[] = array(
								'key' => 'category',
								'value' => $_REQUEST['search_publication_category']
							);
					}
				}
				
				if (isset($_REQUEST['search_publication_authors']) && !empty($_REQUEST['search_publication_authors'])) {
					$meta_query[] = array(
							'key' => 'authors',
							'value' => $_REQUEST['search_publication_authors'],
							'compare' => 'LIKE'
						);
				}
				
				/*if (isset($_REQUEST['search_publication_doi']) && !empty($_REQUEST['search_publication_doi'])) {
					$meta_query[] = array(
							'key' => 'doi',
							'value' => $_REQUEST['search_publication_doi']
						);
				}
				
				if (isset($_REQUEST['search_publication_identifier']) && !empty($_REQUEST['search_publication_identifier'])) {
					$meta_query[] = array(
							'key' => 'identifier',
							'value' => $_REQUEST['search_publication_identifier']
						);
				}*/
				if (isset($_REQUEST['search_sort'])) {
					if ($_REQUEST['search_sort'] == 'title')
						$query->set('orderby', 'title');
					elseif ($_REQUEST['search_sort'] != 0) {
						$query->set('orderby', 'meta_value');
						$query->set('meta_key', $_REQUEST['search_sort']);
					}
				}
				
				if (isset($_REQUEST['search_sort_direction']) and !empty($_REQUEST['search_sort_direction'])) {
					$query->set('order', $_REQUEST['search_sort_direction']);
				}
				
				if (!empty($meta_query))
					$query->set('meta_query', $meta_query);
			}
		}
		return $query;
	}
}
?>