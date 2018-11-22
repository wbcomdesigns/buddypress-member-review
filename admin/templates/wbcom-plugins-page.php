<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
	<?php $wbcom_setting_obj = new Wbcom_Admin_Settings();
	$free_plugins = $wbcom_setting_obj->wbcom_all_free_plugins(); ?>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Components', 'bp-member-reviews' ); ?></h4>

	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php foreach( $free_plugins as $key=> $plugin_details ) { 
					$slug = $wbcom_setting_obj->_get_wbcom_plugin_file_path_from_slug( $plugin_details['slug'] );
				?>
				<div class="wbcom-req-plugin-card">
					<div class="wbcom_single_left">
						<div class="wbcom_single_icon_wrapper">
							<i class="fa fa-bandcamp" aria-hidden="true"></i>
						</div>
					</div>
					<div class="plugin-container">
						<h3><?php echo esc_html( $plugin_details['name'] ); ?></h3>
						<p class="plugin-description"><?php echo esc_html( $plugin_details['description'] ); ?></p>
						<input type="hidden" class="plugin-slug" name="plugin-slug" value="<?php echo esc_attr( $plugin_details['slug'] ); ?>">
						<input type="hidden" class="plugin-action" name="plugin-action" value="install_plugin">						
						<div class="activation_button_wrap">
	                        <a href="" class="wbcom-plugin-action-button eo_btn eo_btn_default" >
				                <i class="fa fa-toggle-off"></i>
				                <?php esc_html_e( 'Install', 'bp-member-reviews' ); ?>
				            </a>
	                    </div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

</div>