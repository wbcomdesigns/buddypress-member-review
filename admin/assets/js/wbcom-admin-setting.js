jQuery( document ).ready(
	function(){
	jQuery( "#toplevel_page_wbcomplugins .wp-submenu li" ).each( function () {
		var link = jQuery( this ).find('a').attr('href');
		if( link == 'admin.php?page=wbcom-plugins-page' || link == 'admin.php?page=wbcom-themes-page' || link == 'admin.php?page=wbcom-support-page' ) {
			jQuery( this ).addClass('hidden');
		}			
	});

	jQuery( 'button.wbcom-plugin-action-button' ).click( function( event ) {
		event.preventDefault();
		var thisRef = jQuery( this );

		if( thisRef.hasClass( 'already-active' ) ) {
			return;
		}

		jQuery.ajax({
			url : wbcom_theme_demo_installer_params.ajax_url,
			type : 'post',
			dataType : 'json',
			data : {
				action : 'wbcom_admin_plugin_installation',
				plugin_action : thisRef.siblings( 'input.plugin-action').val(),
				plugin_slug : thisRef.siblings( 'input.plugin-slug').val()
			},
			success : function( response ) {
				if( response.success ) {
					thisRef.siblings( 'p.plugin-status').html( 'Active' );
					thisRef.siblings( 'p.plugin-status').addClass( 'already-active' );
					thisRef.html( 'Already Installed & Activated' );
					thisRef.attr( 'class', 'wbcom-plugin-action-button button already-active' );
				}
				else {
					alert( 'There was a problem performing the action.' );
				}
			},
			'error' : function( response ) {
				alert( 'There was a problem performing the action.' );
			}
		});
	});
});