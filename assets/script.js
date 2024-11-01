jQuery(document).ready(function($){ 
	$('body').on('click', '#disconnect', function(){
			$.ajax({
				type: "POST",                 
				url: disconnect_url,      // URL to "wp-admin/admin-ajax.php"
				data: {
					action     : 'disconnect' // wp_ajax_*, wp_ajax_nopriv_* 
				},
				success:function( data ) {
					$( '#connect' ).html( data );
				},
				error: function(){
					console.log(errorThrown); 
				}
			});
			return false;
		}		
	);
});