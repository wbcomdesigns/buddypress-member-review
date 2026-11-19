if (typeof wp !== 'undefined' && wp.i18n) {
	const { __ } = wp.i18n;
}

jQuery.noConflict();
jQuery(document).ready(
	function ($) {
		"use strict";
		/*----------------------------------------
		* Make Draggable admin criteria input fields
		*-----------------------------------------*/
		jQuery("#buprTextBoxContainer").sortable();
		jQuery("#buprTextBoxContainer").disableSelection();
		jQuery('#bupr_exc_member').select2();
		jQuery('#bupr_add_member').select2();

		// jQuery('.bupr_mt_criteria_select').select2({
		// placeholder: "Select criterias",
		// plugins: ['remove_button']
		// });

		/*----------------------------------------
		* Support tab accordian js
		*-----------------------------------------*/
		var acc = document.getElementsByClassName("bupr-accordion");
		var i;
		for (i = 0; i < acc.length; i++) {
			acc[i].onclick = function () {
				this.classList.toggle("active");
				var panel = this.nextElementSibling;
				if (panel.style.maxHeight) {
					panel.style.maxHeight = null;
				} else {
					panel.style.maxHeight = panel.scrollHeight + "px";
				}
			}
		}

		/*----------------------------------------
		* Add active class in admin setting tab
		*-----------------------------------------*/
		var bupr_current_tab = jQuery('.bupr-tab-active').val();
		jQuery('.' + bupr_current_tab).addClass('nav-tab-active');

		/*
		 * The General / Criteria / Display tabs used to save over AJAX from
		 * here. They were migrated to the Settings API (real form posts to
		 * options.php via settings_fields() + submit_button(), registered in
		 * includes/admin/class-bupr-admin-panel.php), which left three handlers
		 * posting to actions no PHP ever registered (bupr_admin_tab_generals /
		 * _criteria / _display) from buttons no template ever rendered
		 * (#bupr-save-{general,criteria,display}-settings). Removed 2026-07-17.
		 */

		/*--------------------------------------------------------------
		* Add checkbox value of enbale/desable in criteria tab
		*---------------------------------------------------------------*/
		/* */
		jQuery('.bupr_enable_criteria').change(
			function () {
				if (jQuery(this).is(":checked")) {
					jQuery(this).val("yes");
				} else {
					jQuery(this).val("no");
				}
			}
		);

		/*-------------------------------------------------------
		* Show criteria only when allowed
		*--------------------------------------------------------*/
		jQuery(document).on(
			'change',
			'#bupr_allow_multiple_criteria',
			function () {
				// One wrapper holds the rows and the Add button; the server renders the same class on load.
				jQuery('#bupr-criteria-builder').toggleClass('bupr-show-if-allowed', !this.checked);
			}
		);

		// Approval email fields only apply while reviews wait for approval.
		jQuery(document).on(
			'change',
			'#bupr_review_auto_approval',
			function () {
				jQuery('.review-approve-email-section').toggleClass('bupr-show-if-allowed', this.checked);
			}
		);

		/* Add extra criteria fields */
		jQuery("#bupr-btnAdd").bind(
			"click",
			function () {
				jQuery("#buprTextBoxContainer").append(GetDynamicTextBox(""));
			}
		);
		/* Remove extra criteria fields */
		jQuery("body").on(
			"click",
			".bupr-remove",
			function () {
				jQuery(this).parent().parent().parent("div").remove();
			}
		);

		jQuery(document).on(
			'click',
			'.bupr-approve-review',
			function () {
				var review_id = jQuery(this).data('rid');
				jQuery(this).html(bupr_admin_ajax_object.approving_text);
				jQuery.post(
					bupr_admin_ajax_object.ajaxurl,
					{
						'action': 'bupr_approve_review',
						'review_id': review_id,
						'nonce' : bupr_admin_ajax_object.nonce,
					},
					function (response) {
						// Branch on the stable response code, never the translated message.
						if (response && response.success && response.data && 'review-approved-successfully' === response.data.code) {
							window.location.reload();
						} else {
							console.log(response && response.data ? response.data.message : response);
						}
					}
				);
			}
		);



		jQuery(document).on(
			'keyup',
			'#bupr_member_tab_title',
			function () {
				var value = jQuery(this).val();
				var matches = value.match(/\d+/g);
				if (matches != null) {
					$(this).next().text(bupr_admin_ajax_object.number_validation);
					$(this).parent().append('<span class="wbcom-field-error"></span>');
					$('#submit').attr("disabled",'disabled');
				}else{
					$(this).siblings('wbcom-field-error').remove();
					$(this).next().text('');
					$('#submit').removeAttr("disabled",'disabled');
				}
			}
		);

		jQuery(document).on(
			'keyup',
			'#bupr_member_tab_title_plural',
			function () {
				var value = jQuery(this).val();
				var matches = value.match(/\d+/g);
				if (matches != null) {
					$(this).next().text(bupr_admin_ajax_object.number_validation);
					$(this).parent().append('<span class="wbcom-field-error"></span>');
					$('#submit').attr("disabled",'disabled');
				}else{
					$(this).siblings('wbcom-field-error').remove();
					$(this).next().text('');
					$('#submit').removeAttr("disabled",'disabled');
				}
			}
		);
		

	}
);

/*----------------------------------------
 * Add extra criteria fields
 *-----------------------------------------*/
function GetDynamicTextBox(value) {
	var rand_num = Math.random();
	var i18n = window.bupr_admin_ajax_object || {};
	// English literals are fallbacks only; the translated values come from wp_localize_script.
	var placeholder = i18n.criteria_placeholder || 'Add Review criteria eg. Member Response. ';
	var ariaLabel = i18n.criteria_aria_label || 'Review criteria name';
	var removeLabel = i18n.criteria_remove || 'Remove';
	var removeAria = i18n.criteria_remove_aria || 'Remove this review criteria';

	function attr(text) {
		return jQuery('<div/>').text(text).html();
	}

	return '<div class="bupr-admin-row-criteria bupr-admin-row bupr-criteria bupr-criteria-fields border draggable"><div class="bupr-admin-col-6"><span class="move-icons">&equiv;</span><input name="bupr_admin_settings[rating_field_name][' + rand_num + ']" class = "buprDynamicTextBox" type="text" value = "' + attr(value) + '" placeholder="' + attr(placeholder) + '" aria-label="' + attr(ariaLabel) + '" /></div>' +
		'<div class="bupr-admin-col-6 buprcriteria"><p class="bupr-delete-tag"><input type="button" value="' + attr(removeLabel) + '" aria-label="' + attr(removeAria) + '" class="bupr-criteria-remove-button bupr-remove bupr-btn bupr-btn-danger" /></p></div></div>'
}
