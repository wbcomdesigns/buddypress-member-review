jQuery( document ).ready(
	function(){
	jQuery( "#toplevel_page_wbcomplugins .wp-submenu li" ).each( function () {
		var link = jQuery( this ).find('a').attr('href');
		console.log( 'ttt' + link );
		if( link == 'admin.php?page=wbcom-plugins-page' || link == 'admin.php?page=wbcom-themes-page' || link == 'admin.php?page=wbcom-support-page' ) {
			jQuery( this ).addClass('hidden');
		}			
	});
	}
);