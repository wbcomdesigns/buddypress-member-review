<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' );

	$request_url_free_plugins = BUPR_PLUGIN_URL . "admin/wbcom/inc/plugins-free.json";
	$response_free_plugins    = wp_remote_get( $request_url_free_plugins, array( 'timeout' => 120 ) );

	$request_url_paid_plugins = BUPR_PLUGIN_URL . "admin/wbcom/inc/plugins-paid.json";
	$response_paid_plugins    = wp_remote_get( $request_url_paid_plugins, array( 'timeout' => 120 ) );

	?>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Free Addons', 'bp-member-reviews' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php
			if ( is_wp_error( $response_free_plugins ) ) {
				$error_message = $response_free_plugins->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				if ( isset( $response_free_plugins['response']['code'] ) &&  ( $response_free_plugins['response']['code'] == 200 ) ) {
					$response_free_plugins = isset( $response_free_plugins['body'] ) ? $response_free_plugins['body'] : '';
					if( !empty( $response_free_plugins ) ) {
						$response_free_plugins = json_decode( $response_free_plugins, true );
					}
					if( !empty( $response_free_plugins ) && is_array( $response_free_plugins ) ) {
						foreach ( $response_free_plugins as $key => $plugin_details ) {
							if ( 'not_installed' == $plugin_details[ 'status' ] ) {
								$plugin_btn_text = esc_html__( 'Install', 'bp-member-reviews' );
								$toggle_class	 = 'fas fa-toggle-off';
								$plugin_action	 = 'install_plugin';
							} else if ( 'installed' == $plugin_details[ 'status' ] ) {
								$plugin_btn_text = esc_html__( 'Activate', 'bp-member-reviews' );
								$toggle_class	 = 'fas fa-toggle-off';
								$plugin_action	 = 'activate_plugin';
							} else {
								$plugin_btn_text = esc_html__( 'Deactivate', 'bp-member-reviews' );
								$toggle_class	 = 'fas fa-toggle-on';
								$plugin_action	 = 'deactivate_plugin';
							}
							?>
							<div class="wbcom-req-plugin-card">
								<div class="wbcom_single_left">
									<div class="wbcom_single_icon_wrapper">
										<i class="<?php echo esc_attr( $plugin_details[ 'icon' ] ); ?>" aria-hidden="true"></i>
									</div>
								</div>
								<div class="wbcom_single_right">
									<h3><a href="<?php echo esc_url( $plugin_details[ 'wp_url' ] ); ?>"><?php echo esc_html( $plugin_details[ 'name' ] ); ?></a></h3>
									<p class="plugin-description"><?php echo esc_html( $plugin_details[ 'description' ] ); ?></p>
									<input type="hidden" class="plugin-slug" name="plugin-slug" value="<?php echo esc_attr( $plugin_details[ 'slug' ] ); ?>">
									<input type="hidden" class="plugin-action" name="plugin-action" value="<?php echo esc_attr( $plugin_action ); ?>">
									<div class="activation_button_wrap">
										<a href="" class="wbcom-plugin-action-button wb_btn wb_btn_default" >
											<i class="<?php echo $toggle_class; ?>"></i>
											<?php echo $plugin_btn_text; ?>
											<i class="fas fa-spinner fa-pulse" style="display:none"></i>
										</a>
									</div>
								</div>
							</div>
							<?php
						}
					}
					else {
						esc_html_e( 'No Free Plugins Available', 'bp-member-reviews' );
					}
				}
			}
			?>
		</div>
	</div>
	<h4 class="wbcom-plugin-heading"><?php esc_html_e( 'Paid Addons', 'bp-member-reviews' ); ?></h4>
	<div class="reign-demos-wrapper reign-importer-section">
		<div class="reign-demos-inner-wrapper">
			<?php
			if ( is_wp_error( $response_paid_plugins ) ) {
				$error_message = $response_paid_plugins->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				if ( isset( $response_paid_plugins['response']['code'] ) &&  ( $response_paid_plugins['response']['code'] == 200 ) ) {
					$response_paid_plugins = isset( $response_paid_plugins['body'] ) ? $response_paid_plugins['body'] : '';
					if( !empty( $response_paid_plugins ) ) {
						$response_paid_plugins = json_decode( $response_paid_plugins, true );
					}
					if( !empty( $response_paid_plugins ) && is_array( $response_paid_plugins ) ) {
						foreach ( $response_paid_plugins as $key => $plugin_details ) {
						?>
						<div class="wbcom-req-plugin-card">
							<div class="wbcom_single_left">
								<div class="wbcom_single_icon_wrapper">
									<i class="<?php echo esc_attr( $plugin_details[ 'icon' ] ); ?>" aria-hidden="true"></i>
								</div>
							</div>
							<div class="wbcom_single_right">
								<h3><?php echo esc_html( $plugin_details[ 'name' ] ); ?></h3>
								<p class="plugin-description"><?php echo esc_html( $plugin_details[ 'description' ] ); ?></p>
								<div class="activation_button_wrap">
									<a href="<?php echo esc_url( $plugin_details[ 'download_url' ] ); ?>" class="wb_btn wb_btn_default" target="_blank" >
										<i class="far fa-eye"></i>
										<?php esc_html_e( 'View', 'bp-member-reviews' ); ?>
									</a>
								</div>
							</div>
						</div>
					<?php }
					} else {
						esc_html_e( 'No Paid Plugins Available', 'bp-member-reviews' );
					}
				}
			}
			?>
		</div>
	</div>
</div>