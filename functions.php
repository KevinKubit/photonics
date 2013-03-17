<?php

define('SEARCH_PAGE_ID', 5);

require_once(TEMPLATEPATH . '/lib/class.theme_util.php');
require_once(TEMPLATEPATH . '/lib/class.theme_core.php');
require_once(TEMPLATEPATH . '/lib/custom_post_types/class.publication_post_type.php');
require_once(TEMPLATEPATH . '/lib/custom_post_types/class.contact_post_type.php');
require_once(TEMPLATEPATH . '/lib/custom_post_types/class.lab_item_post_type.php');
require_once(TEMPLATEPATH . '/lib/custom_post_types/class.safety_resource_post_type.php');

$core = new theme_core();
new publication_post_type();
new contact_post_type();
new lab_item_post_type();
new safety_resource_post_type();



require_once(TEMPLATEPATH . '/lib/class.theme_search.php');
new theme_search();


function get_ID_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}

function get_advanced_search_form() {
	require_once(ABSPATH . '/wp-content/themes/Photonics/advanced-search-form.php');
}

function require_login() {
    if ( !is_user_logged_in() ) {
        nocache_headers();
        header("HTTP/1.1 302 Moved Temporarily");
        header('Location: ' . get_settings('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
        header("Status: 302 Moved Temporarily");
        exit();
    }
}

?>
