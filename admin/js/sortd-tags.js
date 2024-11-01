(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	const tags = {


		syncTag: function () {

			let id = $(this).attr('id');
			let flag = $(this).prop('checked');

			var wp_domain = $(this).attr('data-wp_domain');
            var project_slug = $(this).attr('data-project_slug');
            var current_user = $(this).attr('data-current_user');

            if (typeof gtag === 'function') {
                gtag('event', 'sortd_action', {
                    'sortd_page_title': 'Tags Screen',
                    'sortd_feature': 'Sync/Un-Sync Tag',
                    'sortd_domain': wp_domain,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                });
            }

			if (flag == true) {

				tags.synctagajax(id);

			} else {

				tags.unsynctagajax(id);
			}


		},

		synctagajax: function (id) {

			$.ajax({
				url: sortd_ajax_obj_tags.ajax_url,
				data: { 'id': id, 'action': 'sortd_sync_tag', 'sortd_nonce': sortd_ajax_obj_tags.nonce },
				type: 'post',
				success: function (result) {

					let response = JSON.parse(result);


					if (response.status == true) {

						$("#catSpan_" + id).show();
						$(".succmsg img").show();
						$(".succmsg").show();
						$(".img" + id).show();

						$('.sortcheckclass' + id).attr('checked', true);


					} else if (response.status == false && response.error.errorCode === 503) {
						$(".taxSyncUnsyncNotice").html(response.error.message);
						$(".taxSyncUnsyncNotice").show();
						setTimeout(function () {
							$('.taxSyncUnsyncNotice').fadeOut(500);
						}, 3000);
						// $('.sortcheckclass'+id).attr('checked',false);
						// $('.sorttagcheckclass36145').prop('checked',false);

					} else {
						$('.sortcheckclass' + id).attr('checked', false);

					}



				}
			});

		},

		unsynctagajax: function (id) {

			$.ajax({
				url: sortd_ajax_obj_tags.ajax_url,
				data: { 'id': id, 'action': 'sortd_unsync_tag', 'sortd_nonce': sortd_ajax_obj_tags.nonce },
				type: 'post',
				success: function (result) {

					let response = JSON.parse(result);

					// console.log(response);

					if (response.status == true) {

						$("#catSpan_" + id).hide();
						$(".succmsg img").hide();
						$(".succmsg").hide();
						$(".img" + id).hide();

						$('.sortcheckclass' + id).attr('checked', false);


					} else if (response.status == false && response.error.errorCode === 503) {
						$(".taxSyncUnsyncNotice").html(response.error.message);
						$(".taxSyncUnsyncNotice").show();
						setTimeout(function () {
							$('.taxSyncUnsyncNotice').fadeOut(500);
						}, 3000);
						// $('.sortcheckclass'+id).attr('checked',false);
						// $('.sorttagcheckclass36145').prop('checked',false);

					} else {

						$('.sortcheckclass' + id).attr('checked', true);

					}



				}
			});

		},



		loadDefaults: function () {
			let url_string = location.href;
			let url = new URL(url_string);
			let action = url.searchParams.get("action");


			var urlParams = url.searchParams;



			if (urlParams.has('taxonomy') == true && urlParams.get('taxonomy') == 'post_tag') {


				$.ajax({
					url: sortd_ajax_obj_tags.ajax_url,
					data: { 'action': 'list_tags', 'sortd_nonce': sortd_ajax_obj_tags.nonce },
					type: 'post',
					success: function (result) {
						let response = JSON.parse(result);
						//	console.log(response);return false;
						if (response.status == true) {

							$(".tags tr").find('input[name="tagsyncname"]').each(function () {
								let id = $(this).attr('id');

								$.each(response.data.tags, function (i, j) {

									if (j.guid == id) {
										$("#" + id).attr('checked', true);
									}
									/* else {
										$("#"+id).attr('checked',false);
									}*/
								});


							});

						}
					}
				});
			}

		}

	}



	$(document).on('change', '.tagcatsync', tags.syncTag);



	$(document).ready(tags.loadDefaults);


	$(document).on('click', '.row-actions .inline .editinline', function () {
		var row = $(this).closest('tr'); // Get the row containing the quick edit form
		// console.log(row[0]);
		var tagId = $(row[0]).attr('id'); // Get the category ID


		$.ajax({
			url: sortd_ajax_obj_category.ajax_url,
			data: {
				'action': 'refresh_custom_column_for_tag',
				'sortd_nonce': sortd_ajax_obj_category.nonce,
				'tagId': tagId
			},

			type: 'post',
			success: function (result) {


				var response = JSON.parse(result);
				var status = response['status'];
				var cat = response['value'];

				if (status == 1) {

					$(document).ajaxSuccess(function (event, xhr, settings) {
						if (settings.data && settings.data.indexOf('action=inline-save') !== -1) {
							// Quick edit category save action detected
							// Perform your desired action here

							$('.sorttagcheckclass' + cat).attr('checked', true);

						}
					})

					// $(".button-primary").click(function(){
					// 	console.log("pleasee");
					// 	$('.sortcheckclass'+cat).attr('checked',true);
					// })

				}



				console.log("reached");
			}
		})


	});




})(jQuery);
