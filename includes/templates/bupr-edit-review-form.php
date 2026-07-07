<?php
/**
 * Edit review form template.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $bupr;
?>
<div id="bupr-edit-review-modal" class="bupr-review-modal">	
	<div class="bupr-review-modal-content">
		<button type="button" class="bupr-review-modal-close-button" aria-label="<?php esc_attr_e( 'Close', 'bp-member-reviews' ); ?>">&times;</button>
	<div class="bupr-edit-review-form-wrapper">
		<form id="bupr-edit-review-form" action="" method="post">
			<div id="bupr-edit-review"></div>
		</form>
	</div>
	</div>
</div>
