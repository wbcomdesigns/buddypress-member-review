<?php
/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/buddypress-member-review/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/buddypress-member-review/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param   string $template_name          Template to load.
 * @param   string $template_path          Path to templates.
 * @param   string $default_path           Default path to template files.
 * @return  string                          Path to the template file.
 */
function bupr_locate_template( $template_name, $template_path = '', $default_path = '' ) {

    if ( ! $template_path ) :
        $template_path = 'buddypress-member-review/';
    endif;

    // Set default plugin templates path.
    if ( ! $default_path ) :
        $default_path = BUPR_PLUGIN_PATH . 'includes/templates/';
    endif;

    // Search template file in theme folder.
    $template = locate_template(
        array(
            $template_path . $template_name,
            $template_name,
        )
    );

    // Get plugin template file if not found in theme.
    if ( ! $template ) :
        $template = $default_path . $template_name;
    endif;

    return apply_filters( 'bupr_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @param string $template_name          Template to load.
 * @param array  $args                   Arguments to pass to the template file.
 * @param string $template_path          Path to templates.
 * @param string $default_path           Default path to template files.
 */
function bupr_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
    if ( is_array( $args ) && ! empty( $args ) ) :
        extract( $args ); // Extract the args into variables.
    endif;

    $template_file = bupr_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $template_file ) ) :
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $template_file ) ), '1.0.0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        return;
    endif;

    include $template_file;
}

/**
 * Get the singular review tab name on a member's profile.
 *
 * @return string Singular tab name.
 */
function bupr_profile_review_singular_tab_name() {
    global $bupr;

    $tab_name = isset( $bupr['review_label'] ) ? esc_html( $bupr['review_label'] ) : __( 'Review', 'bp-member-reviews' );

    return apply_filters( 'bupr_profile_review_singular_tab_name', $tab_name );
}

/**
 * Get the plural review tab name on a member's profile.
 *
 * @return string Plural tab name.
 */
function bupr_profile_review_tab_name() {
    global $bupr;

    $tab_name = isset( $bupr['review_label_plural'] ) ? esc_html( $bupr['review_label_plural'] ) : __( 'Reviews', 'bp-member-reviews' );

    return apply_filters( 'bupr_review_tab_name', $tab_name );
}

/**
 * Get the slug for the plural review tab on a member's profile.
 *
 * @return string Slug for plural tab.
 */
function bupr_profile_review_tab_plural_slug() {
    global $bupr;

    $tab_slug = isset( $bupr['review_label_plural'] ) ? sanitize_title( $bupr['review_label_plural'] ) : 'reviews';

    return apply_filters( 'bupr_review_tab_plural_slug', esc_html( $tab_slug ) );
}

/**
 * Get the slug for the singular review tab on a member's profile.
 *
 * @return string Slug for singular tab.
 */
function bupr_profile_review_tab_singular_slug() {
    global $bupr;

    $tab_slug = isset( $bupr['review_label'] ) ? sanitize_title( $bupr['review_label'] ) : 'review';

    return apply_filters( 'bupr_review_tab_singular_slug', esc_html( $tab_slug ) );
}

/**
 * Register BuddyPress Member Review triggers for GamiPress integration.
 *
 * @param array $triggers GamiPress triggers.
 * @return array Modified triggers.
 */
function buddypress_member_review_bp_activity_triggers( $triggers ) {
    $triggers[ __( 'BuddyPress Member Review', 'bp-member-reviews' ) ] = array(
        'gamipress_bp_member_review' => __( 'Give Member Review', 'bp-member-reviews' ),
    );

    return $triggers;
}
add_filter( 'gamipress_activity_triggers', 'buddypress_member_review_bp_activity_triggers' );

/**
 * Get the user ID for the review trigger in GamiPress.
 *
 * @param int    $user_id The current user ID.
 * @param string $trigger The trigger identifier.
 * @param array  $args    Trigger arguments.
 * @return int Modified user ID.
 */
function buddypress_member_review_trigger_get_user_id( $user_id, $trigger, $args ) {
    if ( 'gamipress_bp_member_review' === $trigger ) {
        $user_id = $args[0];
    }

    return $user_id;
}
add_filter( 'gamipress_trigger_get_user_id', 'buddypress_member_review_trigger_get_user_id', 10, 3 );
