<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
    $bupr_spinner_src = includes_url().'/images/spinner.gif';
    /* get display tab setting from db */
    $bupr_star_color    = '#FFC400';
    $bupr_review_title  = 'Reviews';
    $bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_review_title'])) {
        $bupr_review_title  = $bupr_display_settings['bupr_review_title'];
    }
    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_star_color'])) {
        $bupr_star_color    = $bupr_display_settings['bupr_star_color'];
    }

?>
<div class="bupr-adming-setting ">
    <p class="description">
        <b><?php _e( 'Note', BUPR_TEXT_DOMAIN ); ?>:</b>
        <?php _e( ' Rating will be displayed in Review form, if and only if you added criteria for review.', BUPR_TEXT_DOMAIN );?>
    </p>
    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Labels', BUPR_TEXT_DOMAIN ); ?>
        </h3>
        <input type="hidden" class="bupr-tab-active" value="display"/>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-4 bupr-label">
                    <?php _e( 'Reviews', BUPR_TEXT_DOMAIN ); ?>
                </div>
                <div class="bupr-admin-col-8">
                    <input type="text" name="bupr_member_tab_title" id="bupr_member_tab_title" placeholder="Enter Tab title for fron-end." value="<?php echo !empty($bupr_review_title) ? $bupr_review_title : 'Review'; ?>">
                    <p class="description">
                        <?php _e("Change Labels from BuddyPress tab and review form." , BUPR_TEXT_DOMAIN); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bupr-tab-header">
        <h3>
            <?php _e( 'Colors ', BUPR_TEXT_DOMAIN ); ?>
        </h3>
    </div>

    <div class="bupr-admin-settings-block">
        <div id="bupr-settings-tbl" class="bupr-table">
            <div class="bupr-admin-row border">
                <div class="bupr-admin-col-4 bupr-label">
                    <?php _e( 'Rating color', BUPR_TEXT_DOMAIN ); ?>
                </div>
                <div class="bupr-admin-col-8 bupr-label">
                   <input type="text" id="bupr_display_color" class="bupr-admin-color-picker" value="<?php echo !empty($bupr_star_color) ? $bupr_star_color : '#FFC400'; ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="bupr-admin-row">
        <div class="bupr-admin-col-6">
            <input type="button" class="button button-primary" id="bupr-save-display-settings" value="<?php _e( 'Save Settings', BUPR_TEXT_DOMAIN );?>">
            <img src="<?php echo $bupr_spinner_src;?>" class="bupr-admin-settings-spinner" />
        </div>
    </div>

</div>