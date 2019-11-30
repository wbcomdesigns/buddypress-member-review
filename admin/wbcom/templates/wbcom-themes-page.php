<div class="wrap">
	<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
	<div class="reign-demos-wrapper">
			<?php
				$request_url_themes = BUPR_PLUGIN_URL . "admin/wbcom/inc/themes.json";
				$response_themes    = wp_remote_get( $request_url_themes, array( 'timeout' => 120 ) );

				if ( is_wp_error( $response_themes ) ) {
					$error_message = $response_themes->get_error_message();
					echo "Something went wrong: $error_message";
				} else {
					if ( isset( $response_themes['response']['code'] ) &&  ( $response_themes['response']['code'] == 200 ) ) {
						$response_themes = isset( $response_themes['body'] ) ? $response_themes['body'] : '';
						if( !empty( $response_themes ) ) {
							$response_themes = json_decode( $response_themes, true );
						}
						if( !empty( $response_themes ) && is_array( $response_themes ) ) {
							foreach ( $response_themes as $key => $theme_details ) {
								?>
								<h4 class="wbcom-demo-name">
									<?php echo $key; ?>
								</h4>
								<div class="wbcom-demo-content-wrap">
									<div class="wbcom-demo-importer">			
										<div class="container">
											<div class="wbcom-image-wrapper">
												<img src="<?php echo BUPR_PLUGIN_URL . $theme_details['image_url']; ?>" alt="Image" class="image" style="width:100%">
											</div>
											<div class="wbcom-demo-title">
												<h2><?php echo $theme_details['name']; ?></h2>
												<ul class="wbcom_theme_features_list">
													<?php
													if ( ! empty( $theme_details['features'] ) ) {
														foreach( $theme_details['features'] as $single_feature ) { ?>
						                            	<li><?php echo $single_feature; ?></li>
						                            	<?php }
						                        	}
						                            ?>
						                        </ul>
												<div class="wbcom-middle">
													<a href="<?php echo $theme_details['purchase_url']; ?>" class="wbcom-button wbcom-purchase" target="_blank"><?php esc_html_e( 'Purchase', 'bp-member-reviews' ); ?></a>
													<a target="_blank" href="<?php echo $theme_details['demo_url']; ?>" class="wbcom-button"><?php esc_html_e( 'Preview
													', 'bp-member-reviews' ); ?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
							}
						}
						else {
							esc_html_e( 'No Theme Available', 'bp-member-reviews' );
						}
					}
				}
			?>
	</div>
</div>