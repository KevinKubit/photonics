// JavaScript Document

$j = jQuery.noConflict();

$j(document).ready(function() {
	
	var current_search_category_sel = null;
	
	function search_do() {
		if (current_search_category_sel != null) {
			current_search_category_sel.hide();
			current_search_category_sel.find("input, select, textarea").attr("disabled", "disabled");
			
		}
		
		if ($j("select[name=search_category]").val() == 'Publications') {
			current_search_category_sel = $j("#publication-options, #publication-sort");
			current_search_category_sel.find("input, select, textarea").removeAttr("disabled");
			current_search_category_sel.show();
		} else if ($j("select[name=search_category]").val() == 'Entire Site') {
			current_search_category_sel = $j("#entire_site-sort");
			current_search_category_sel.find("input, select, textarea").removeAttr("disabled");
			current_search_category_sel.show();
		}
		/*else if ($j(this).val() == 'Entire Site') {
			current_search_category_sel = $j("#publication-options");
			current_search_category_sel.show();
		}*/
	};
	
	search_do();
	
	
	$j("select[name=search_category]").change(search_do);
	
});