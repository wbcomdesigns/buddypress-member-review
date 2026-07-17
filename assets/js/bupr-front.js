/**
 * buprFrontToast — lightweight, accessible toast for the review front end.
 *
 * Replaces the three native alert() calls (skill Part 6 rules 10 + 12: no
 * browser alert()/confirm()). Self-contained so it works in every BuddyPress
 * theme without a dependency. Tones: 'info' | 'success' | 'error'.
 */
window.buprFrontToast = function (message, tone) {
  tone = tone || "info";
  var host = document.querySelector(".bupr-front-toast-host");
  if (!host) {
    host = document.createElement("div");
    host.className = "bupr-front-toast-host";
    document.body.appendChild(host);
  }
  var el = document.createElement("div");
  el.className = "bupr-front-toast bupr-front-toast--" + tone;
  el.setAttribute("role", "status");
  el.setAttribute("aria-live", "polite");
  el.textContent = String(message);
  host.appendChild(el);
  window.requestAnimationFrame(function () {
    el.classList.add("bupr-front-toast--visible");
  });
  window.setTimeout(function () {
    el.classList.remove("bupr-front-toast--visible");
    window.setTimeout(function () {
      if (el.parentNode) {
        el.parentNode.removeChild(el);
      }
    }, 250);
  }, 3600);
};

jQuery(document).ready(function () {
  jQuery(".add_review_msg").hide();
  jQuery(document).on("click", "#bupr-add-review-btn a", function (e) {
    var show_content = jQuery(this).attr("show");
    localStorage.setItem("bupr_show_form", show_content);
  });
  /*----------------------------------------
   * Add Placeholder in search box
   *-----------------------------------------*/
  jQuery('.dataTables_filter input[type="search"]').attr(
    "placeholder",
    mail_title.search_placeholder || "Enter Keywords...."
  );

  /*----------------------------------------
   * Select star on mouse enter
   *-----------------------------------------*/
  var reviews_pluginurl = jQuery("#reviews_pluginurl").val();

  jQuery(".member_stars").mouseenter(function () {
    jQuery(this).parent().children().eq(0).val("not_clicked");
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    for (i = 1; i <= id; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }
  });

  /*----------------------------------------
   * Remove Color on stars
   *-----------------------------------------*/
  jQuery(".member_stars").mouseleave(function () {
    var clicked_id = jQuery(this).parent().children().eq(1).val();
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    if (jQuery(this).parent().children().eq(0).val() !== "clicked") {
      var j = parseInt(clicked_id) + 1;
      for (i = j; i <= 5; i++) {
        jQuery("#" + parent_id)
          .children("." + i)
          .addClass("far")
          .removeClass("fas");
      }
    }
  });

  /*----------------------------------------
   * Color the stars on click
   *-----------------------------------------*/
  jQuery(".member_stars").on("click", function () {
    var attr = jQuery(this).attr("data-attr");
    var clicked_id = attr;
    var parent_id = jQuery(this).parent().attr("id");
    jQuery(this).parent().children().eq(1).val(attr);
    jQuery(this).parent().children().eq(0).val("clicked");
    for (i = 1; i <= attr; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }

    var k = parseInt(attr) + 1;
    for (j = k; j <= 5; j++) {
      jQuery("#" + parent_id)
        .children("." + j)
        .addClass("far")
        .removeClass("fas");
    }
  });

  jQuery("#bupr_member_review_id").on("change", function () {
    jQuery( '.bupr-review-error-fields' ).hide(); 
  });

  jQuery("#review_desc").on("keydown", function () {
    jQuery( '.bupr-review-error-fields' ).hide();
  });

  jQuery(".member_stars").on("click", function () {
    jQuery( '.bupr-review-error-fields' ).hide();
  });
  
  /*----------------------------------------
   * Add new review in member profiles
   *-----------------------------------------*/
  jQuery(document).on("click", "#bupr_save_review", function (event) {
    event.preventDefault();
    var rating_exist = [];
    var bupr_member_id = jQuery("#bupr_member_review_id").val();
    var bupr_current_user = jQuery("#bupr_current_user_id").val();
    // Whole-phrase format string so translators control word order (no concatenation).
    var bupr_review_title = (
      mail_title.review_title_format || "%1$s received a %2$s"
    )
      .replace("%1$s", mail_title.cur_username)
      .replace("%2$s", mail_title.review_title);
    var bupr_review_desc = jQuery("#review_desc").val();
    var bupr_review_count = jQuery("#member_rating_field_counter").val();
    var bupr_review_rating = {};
    // var bupr_review_rating_text = {};
    var empty_rate = 0;

    /* Send anonymous review. */
    var bupr_anonymous_review = "";
    if (jQuery("#bupr_anonymous_review").is(":checked")) {
      bupr_anonymous_review = "yes";
    } else {
      bupr_anonymous_review = "no";
    }

    jQuery(".bupr-star-member-rating").each(function (index) {
      bupr_review_rating[index] = jQuery(this).val();
    });

    // jQuery( '.member_rating_text' ).each(
    // function(index) {
    // bupr_review_rating_text[jQuery( this ).data('id')] = jQuery( this ).val();
    // }
    // );
    jQuery(".bupr-star-member-rating").each(function () {
      var rate_val = jQuery(this).val();
      if (rate_val > 0) {
        empty_rate = empty_rate + 1;
      }

      if( ! bupr_review_desc.trim() && rate_val == 0 ){
        jQuery( '.bupr-review-error-fields' ).show();
      }
      rating_exist.push(rate_val);
    });
   
    if (bupr_review_count > 0) {
      if (bupr_member_id == "") {
        jQuery( '.bupr-review-error-fields' ).show();
      } else {
        if (jQuery.inArray("0", rating_exist) == -1 || bupr_review_desc ) {
          jQuery(".bupr-save-reivew-spinner").show();
          jQuery.post(
            ajaxurl,
            {
              action: "allow_bupr_member_review_update",
              bupr_member_id: bupr_member_id,
              bupr_current_user: bupr_current_user,
              bupr_review_title: bupr_review_title,
              bupr_review_desc: bupr_review_desc,
              bupr_review_rating: bupr_review_rating,
              bupr_field_counter: bupr_review_count,
              bupr_anonymous_review: bupr_anonymous_review,
              nonce: mail_title.review_nonce,
            },
            function (response) {
              jQuery(".bupr-save-reivew-spinner").hide();
              sessionStorage.reloadAfterPageLoad = true;
              var date = new Date();
              date.setTime(date.getTime() + 20 * 1000);
              // jQuery.cookie("response", response, {
              //   expires: date,
              // });
              jQuery(".add_review_msg").show();
              jQuery(".add_review_msg p").html(response.data);
              reset_review_form_data();
              setTimeout(() => {
                jQuery(".add_review_msg p").html('');
                jQuery(".add_review_msg").hide();
              }, 2000);
              // window.location.reload();
            }
          );
        }
      }
    } else {
      if (bupr_member_id == "" || bupr_review_desc == "") {
        if (bupr_member_id == "") {
          jQuery( '.bupr-review-error-fields' ).show();
          event.preventDefault();
        }

        if (bupr_review_desc == "") {
          jQuery( '.bupr-review-error-fields' ).show();
          event.preventDefault();
        }
      } else {
        jQuery(".bupr-save-reivew-spinner").show();
        jQuery.post(
          ajaxurl,
          {
            action: "allow_bupr_member_review_update",
            bupr_member_id: bupr_member_id,
            bupr_current_user: bupr_current_user,
            bupr_review_title: bupr_review_title,
            bupr_review_desc: bupr_review_desc,
            bupr_review_rating: bupr_review_rating,
            bupr_field_counter: bupr_review_count,
            bupr_anonymous_review: bupr_anonymous_review,
            nonce: mail_title.review_nonce,
          },
          function (response) {
            jQuery(".bupr-save-reivew-spinner").hide();
            sessionStorage.reloadAfterPageLoad = true;
            var date = new Date();
            date.setTime(date.getTime() + 20 * 1000);
            // jQuery.cookie("response", response, {
            //   expires: date,
            // });
            jQuery(".add_review_msg").show();
            reset_review_form_data();
            jQuery(".add_review_msg p").html(response.data);
            setTimeout(() => {
              jQuery(".add_review_msg p").html('');
              jQuery(".add_review_msg").hide();
            }, 200);
          }
        );
      }
    }
    // }
  });

  /*----------------------------------------
   * Edit Review Pop form
   *-----------------------------------------*/
  jQuery(document).on("click", "#bupr-review-edit-btn", function (event) {
    event.preventDefault();
    var reviewwrapper = document.getElementById("bupr-edit-review");
    var modal = document.querySelector(".bupr-review-modal");
    var button = jQuery(this);
    var review_id = button.data("review");
    if (review_id) {
      jQuery.post(
        ajaxurl,
        {
          action: "bupr_edit_review",
          review: review_id,
          nonce: mail_title.review_nonce,
        },
        function (response) {
          if (response.success && "" != response.data.review) {
            if ("" !== reviewwrapper.innerHTML) {
              reviewwrapper.innerHTML = "";
            }
            let review = response.data.review;
            jQuery("#bupr-edit-review").append(review);
            modal.classList.add("bupr-show-modal");

            // Add "bupr-show-modal" class to 'youzify-content' if not already present
            var youzifyContent = document.querySelector(".youzify-content");
            if (youzifyContent && !youzifyContent.classList.contains("bupr-youzify-show-modal")) {
                youzifyContent.classList.add("bupr-youzify-show-modal");
            }
          }
        }
      );
    }
  });

  jQuery(document).on(
    "click",
    ".bupr-review-modal-close-button",
    function (event) {
      event.preventDefault();
      var modal = document.querySelector(".bupr-review-modal");
      modal.classList.remove("bupr-show-modal");

      // Remove "bupr-show-modal" class from 'youzify-content' when modal is closed
      var youzifyContent = document.querySelector(".youzify-content");
      if (youzifyContent && youzifyContent.classList.contains("bupr-youzify-show-modal")) {
        youzifyContent.classList.remove("bupr-youzify-show-modal");
      }
    }
  );

  jQuery(window).click(function (e) {
    var modal = document.querySelector(".bupr-review-modal");
    if (e.target === modal) {
      modal.classList.remove("bupr-show-modal");

      // Remove "bupr-show-modal" class from 'youzify-content' when clicking outside of the modal
      var youzifyContent = document.querySelector(".youzify-content");
      if (youzifyContent && youzifyContent.classList.contains("bupr-youzify-show-modal")) {
        youzifyContent.classList.remove("bupr-youzify-show-modal");
      }
    }
  });

  /*----------------------------------------
   * Play with starts on review edit popup form
   *-----------------------------------------*/
  jQuery(document).on("mouseenter", ".member-edit-stars", function () {
    jQuery(this).parent().children().eq(0).val("not_clicked");
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    for (i = 1; i <= id; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }
  });

  jQuery(document).on("mouseleave", ".member-edit-stars", function () {
    var clicked_id = jQuery(this).parent().children().eq(1).val();
    var id = jQuery(this).attr("data-attr");
    var parent_id = jQuery(this).parent().attr("id");
    if (jQuery(this).parent().children().eq(0).val() !== "clicked") {
      var j = parseInt(clicked_id) + 1;
      for (i = j; i <= 5; i++) {
        jQuery("#" + parent_id)
          .children("." + i)
          .addClass("far")
          .removeClass("fas");
      }
    }
  });

  jQuery(document).on("click", ".member-edit-stars", function () {
    var attr = jQuery(this).attr("data-attr");
    var clicked_id = attr;
    var parent_id = jQuery(this).parent().attr("id");
    jQuery(this).parent().children().eq(1).val(attr);
    jQuery(this).parent().children().eq(0).val("clicked");
    for (i = 1; i <= attr; i++) {
      jQuery("#" + parent_id)
        .children("." + i)
        .addClass("fas")
        .removeClass("far");
    }

    var k = parseInt(attr) + 1;
    for (j = k; j <= 5; j++) {
      jQuery("#" + parent_id)
        .children("." + j)
        .addClass("far")
        .removeClass("fas");
    }
  });

  /*----------------------------------------
   * Update review
   *-----------------------------------------*/
  jQuery(document).on("click", "#bupr_upodate_review", function (event) {
    event.preventDefault();
    var modal = document.querySelector(".bupr-review-modal");
    var review_id = jQuery("#bupr-edit-review-field-wrapper").data("review");
    var bupr_review_desc = jQuery("#review_desc").val();
    var bupr_review_rating = {};
    jQuery(".bupr-star-member-rating").each(function (index, item) {
      let critaria = jQuery(this).data("critaria");
      bupr_review_rating[critaria] = jQuery(this).val();
    });

    // Collect endorsed skills (if present - for endorsement mode).
    var bupr_endorsed_skills = [];
    jQuery('input[name="bupr_endorsed_skills[]"]:checked').each(function () {
      bupr_endorsed_skills.push(jQuery(this).val());
    });

    jQuery(".bupr-save-reivew-spinner").show();
    jQuery.post(
      ajaxurl,
      {
        action: "bupr_update_review",
        review_id: review_id,
        bupr_review_desc: bupr_review_desc,
        bupr_review_rating: bupr_review_rating,
        bupr_endorsed_skills: bupr_endorsed_skills,
        nonce: mail_title.review_nonce,
      },
      function (response) {
        jQuery(".bupr-save-reivew-spinner").hide();

        if (response.success) {
          modal.classList.remove("bupr-show-modal");
          window.location.reload();
        }
      }
    );
  });

    function reset_review_form_data(){
      jQuery("#bupr_review_form_public")[0].reset();
      jQuery("#bupr_review_form_public .member_rated_stars").val(0);
      jQuery(".member_stars").each(function (index) {
        jQuery(this).parent().children().eq(0).val("not_clicked");
        var id = jQuery(this).attr("data-attr");
        var parent_id = jQuery(this).parent().attr("id");
        for (i = 1; i <= id; i++) {
          jQuery("#" + parent_id)
            .children("." + i)
            .removeClass("fas")
            .addClass("far");
        }
      });
    }

});

/*----------------------------------------
 * Display message after review submit
 *-----------------------------------------*/
jQuery(function () {
  if (jQuery.cookie("response") && jQuery.cookie("response") !== "") {
    jQuery(".add_review_msg p").html(jQuery.cookie("response"));
    jQuery(".add_review_msg").show();
    jQuery.cookie("response", "", -1);
    jQuery("#review_subject").val("");
    jQuery("#review_desc").val("");
  }
});

/*----------------------------------------
 * Owner Reply Functionality
 *-----------------------------------------*/

// Show reply form
jQuery(document).on("click", ".bupr-show-reply-form", function (e) {
  e.preventDefault();
  var wrapper = jQuery(this).closest(".bupr-reply-form-wrapper");
  jQuery(this).hide();
  wrapper.find(".bupr-reply-form").slideDown();
});

// Cancel reply
jQuery(document).on("click", ".bupr-cancel-reply", function (e) {
  e.preventDefault();
  var wrapper = jQuery(this).closest(".bupr-reply-form-wrapper");
  wrapper.find(".bupr-reply-form").slideUp(function () {
    wrapper.find(".bupr-show-reply-form").show();
    wrapper.find(".bupr-reply-textarea").val("");
  });
});

// Submit reply
jQuery(document).on("click", ".bupr-submit-reply", function (e) {
  e.preventDefault();
  var button = jQuery(this);
  var wrapper = button.closest(".bupr-reply-form-wrapper");
  var replySection = button.closest(".bupr-reply-section");
  var reviewId = button.data("review");
  var replyContent = wrapper.find(".bupr-reply-textarea").val().trim();

  if (!replyContent) {
    window.buprFrontToast(
      mail_title.reply_empty || "Please enter a reply.",
      "error"
    );
    return;
  }

  button.prop("disabled", true);
  wrapper.find(".bupr-reply-spinner").show();

  jQuery.post(
    ajaxurl,
    {
      action: "bupr_add_owner_reply",
      review_id: reviewId,
      reply_content: replyContent,
      nonce: mail_title.review_nonce,
    },
    function (response) {
      button.prop("disabled", false);
      wrapper.find(".bupr-reply-spinner").hide();

      if (response.success) {
        // Replace the form with the reply
        wrapper.remove();
        replySection.html(response.data.reply_html);
      } else {
        window.buprFrontToast(
          (response.data && response.data.message) ||
            mail_title.reply_failed ||
            "Failed to add reply.",
          "error"
        );
      }
    }
  ).fail(function () {
    button.prop("disabled", false);
    wrapper.find(".bupr-reply-spinner").hide();
    window.buprFrontToast(
      mail_title.generic_error || "An error occurred. Please try again.",
      "error"
    );
  });
});

/*----------------------------------------
 * Report Review Functionality
 *-----------------------------------------*/

// Open report modal (supports both new .bupr-report-button and legacy .bupr-flag-button)
jQuery(document).on("click", ".bupr-report-button, .bupr-flag-button", function (e) {
  e.preventDefault();
  var reviewId = jQuery(this).data("review");
  jQuery("#bupr-flag-review-id").val(reviewId);
  jQuery('input[name="bupr_flag_reason"]').prop("checked", false);
  jQuery(".bupr-flag-error").hide().text("");
  jQuery(".bupr-flag-modal").fadeIn();
});

// Close flag modal
jQuery(document).on(
  "click",
  ".bupr-flag-modal-close, .bupr-flag-cancel",
  function (e) {
    e.preventDefault();
    jQuery(".bupr-flag-modal").fadeOut();
  }
);

// Close modal on outside click
jQuery(document).on("click", ".bupr-flag-modal", function (e) {
  if (jQuery(e.target).hasClass("bupr-flag-modal")) {
    jQuery(".bupr-flag-modal").fadeOut();
  }
});

// Submit flag
jQuery(document).on("click", ".bupr-flag-submit", function (e) {
  e.preventDefault();
  var reviewId = jQuery("#bupr-flag-review-id").val();
  var flagReason = jQuery('input[name="bupr_flag_reason"]:checked').val();

  if (!flagReason) {
    jQuery(".bupr-flag-error")
      .text(mail_title.flag_select_reason || "Please select a reason.")
      .show();
    return;
  }

  jQuery(".bupr-flag-submit").prop("disabled", true);
  jQuery(".bupr-flag-spinner").show();

  jQuery.post(
    ajaxurl,
    {
      action: "bupr_flag_review",
      review_id: reviewId,
      flag_reason: flagReason,
      nonce: mail_title.review_nonce,
    },
    function (response) {
      jQuery(".bupr-flag-submit").prop("disabled", false);
      jQuery(".bupr-flag-spinner").hide();

      if (response.success) {
        jQuery(".bupr-flag-modal").fadeOut();
        // Show inline success message instead of alert
        var $reportBtn = jQuery('.bupr-report-button[data-review="' + reviewId + '"], .bupr-flag-button[data-review="' + reviewId + '"]');
        var reportedLabel = jQuery('<div/>').text(mail_title.reported || "Reported").html();
        $reportBtn.replaceWith('<span class="bupr-report-success"><span class="dashicons dashicons-yes"></span> ' + reportedLabel + '</span>');
        // Auto-fade after 3 seconds
        setTimeout(function() {
          jQuery('.bupr-report-success').fadeOut();
        }, 3000);
      } else {
        jQuery(".bupr-flag-error").text(response.data.message).show();
      }
    }
  ).fail(function () {
    jQuery(".bupr-flag-submit").prop("disabled", false);
    jQuery(".bupr-flag-spinner").hide();
    jQuery(".bupr-flag-error")
      .text(mail_title.generic_error || "An error occurred. Please try again.")
      .show();
  });
});

/*----------------------------------------
 * ACCESS-1: keyboard + screen-reader support for the star rating widget.
 * Progressive enhancement - the widget is JS-driven already. Turns the
 * .bupr-criteria-content group into an ARIA radiogroup and each star
 * (.member_stars / .member-edit-stars) into an operable radio: focusable,
 * arrow-key navigable, Enter/Space selectable, with aria-checked kept in
 * sync with the existing hidden-input value. Covers AJAX-injected edit
 * stars via a MutationObserver.
 *-----------------------------------------*/
jQuery(function ($) {
  var A = window.bupr_a11y || { rate_label: 'Rate %d out of 5', group_label: 'Rating' };
  var STAR = '.member_stars, .member-edit-stars';

  function groupValue($group) {
    return parseInt($group.find('.bupr-star-member-rating').val(), 10) || 0;
  }

  function starIndex($star) {
    return parseInt($star.attr('data-attr'), 10) || 0;
  }

  function syncGroup($group) {
    var val = groupValue($group);
    var $stars = $group.find(STAR);
    var focusTarget = val > 0 ? val : 1;
    $stars.each(function () {
      var i = starIndex($(this));
      $(this)
        .attr('aria-checked', i === val ? 'true' : 'false')
        .attr('tabindex', i === focusTarget ? '0' : '-1');
    });
  }

  function enhance(group) {
    var $group = $(group);
    if ($group.data('buprA11y')) {
      syncGroup($group);
      return;
    }
    $group.attr('role', 'radiogroup');
    if (!$group.attr('aria-label')) {
      var lbl = $group.closest('.multi-review').find('.bupr-criteria-label').first().text().trim();
      $group.attr('aria-label', lbl ? lbl : A.group_label);
    }
    $group.find(STAR).each(function () {
      var i = starIndex($(this));
      $(this).attr('role', 'radio').attr('aria-label', A.rate_label.replace('%d', i));
    });
    $group.data('buprA11y', true);
    syncGroup($group);
  }

  function enhanceAll() {
    $(STAR).each(function () {
      var $group = $(this).closest('.bupr-criteria-content');
      if ($group.length) {
        enhance($group);
      }
    });
  }

  enhanceAll();

  if (window.MutationObserver) {
    var mo = new MutationObserver(function () { enhanceAll(); });
    mo.observe(document.body, { childList: true, subtree: true });
  }

  // Keep aria-checked in sync after a mouse click (existing handlers set the value).
  $(document).on('click', STAR, function () {
    syncGroup($(this).closest('.bupr-criteria-content'));
  });

  // Keyboard operation.
  $(document).on('keydown', STAR, function (e) {
    var $star = $(this);
    var $group = $star.closest('.bupr-criteria-content');
    var $stars = $group.find(STAR);
    var idx = $stars.index($star);
    var key = e.key;
    var move = null;

    if (key === 'Enter' || key === ' ' || key === 'Spacebar') {
      e.preventDefault();
      $star.trigger('click');
      syncGroup($group);
      $star.attr('tabindex', '0').focus();
      return;
    }
    if (key === 'ArrowRight' || key === 'ArrowUp') { move = Math.min($stars.length - 1, idx + 1); }
    else if (key === 'ArrowLeft' || key === 'ArrowDown') { move = Math.max(0, idx - 1); }
    else if (key === 'Home') { move = 0; }
    else if (key === 'End') { move = $stars.length - 1; }
    else { return; }

    e.preventDefault();
    var $next = $stars.eq(move);
    $next.trigger('click');
    syncGroup($group);
    $next.attr('tabindex', '0').focus();
  });
});
