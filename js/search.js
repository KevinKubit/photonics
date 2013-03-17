$j = jQuery.noConflict();

$j(document).ready(function() {
	$j("form.ajax-search").show().submit(function(event) {
		
		$j.ajax({
			url: Photonics.ajaxurl,
			data:{
				'action':'ajax_search',
				'fn':'get_latest_posts',
				'count':10
			},
			dataType: 'JSON',
			success:function(data){
				alert("Data: " + data);
			},
			error: function(errorThrown){
				alert('error');
				console.log(errorThrown);
			}
		});
		
		event.preventDefault();
		return false;
	});
});