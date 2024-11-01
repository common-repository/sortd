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

	const domains = {
		verifyCname: function () {

		},

		saveRedirectionValues: function () {
			console.log("saveredirect called");
			var siteurl = $("#siteUrlId").val();
			var wp_domain = $(this).attr("data-wp_domain");
			var current_user = $(this).attr("data-current_user");
			var project_slug = $(this).attr("data-project_slug");

			if (typeof gtag === 'function') {
                gtag('event', 'sortd_action', {
                    'sortd_page_title': 'sortd_redirection',
                    'sortd_feature': 'Save Redirection',
                    'sortd_domain': wp_domain,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                });
            }
			// let nonceValue = $("#nonce_value").val();
			const searchParams = new URLSearchParams(window.location.search);
    
            let nonceValue = searchParams.get('_wpnonce');
			var exclude_url = [];
			var validationCount = [];
			var emptyCount = [];
			
			var sortd_redirection = $(".enable_sortd_redirection").is(':checked');
			var sortd_amp_links = $(".enable_amp_links").is(':checked');
			var sortd_service = $('input[name=inlineRadioOptions]:checked').val()
		
			
			var domain_name = $("#domain_code").val();
			var countV = 1;
			var countEmpty = 1;
			var redirectValue = sortd_redirection;
			$("#excludeUrls input[type=text]")
				.each(function () {
					var my_string = this.value;
					var dataAttr = $(this).attr("data-exclude");
					var count = dataAttr.split('_');
					if (my_string.match(/^\s+$/) === null) {
						if (my_string == '' || !my_string) {
							emptyCount.push({
								id: this.id,
								count: countEmpty
							});
							countEmpty++;
						} else {
							$("#exclude_url_span_add" + count[1]).hide();
							exclude_url.push(my_string);
						}
					} else {
						$("#exclude_url_span_add" + count[1]).show();
						validationCount.push(countV);
						countV++;
						return false;
					}

				});

			if (emptyCount.length == 1 && emptyCount[0].id == "exclude_url_add1") {
				$(".emptyString").html("Please enter value or remove the fields");
				$(".emptyString").hide();
			} else if (emptyCount.length >= 1) {
				$.each(emptyCount, function (i, j) {
					var newid = j.id.split('add');
					$("#exclude_url_spanvalida_add" + newid[1]).show();
				});
				return false;
			}

			if ((exclude_url.length != 0 && validationCount.length == 0 && redirectValue == 1) || (redirectValue == 0) || (redirectValue == 1 && exclude_url.length == 0 && validationCount.length == 0)) {
				let data_obj= {
					'action': 'save_redirection_values',
					'exclude_url': exclude_url,
					'domain_name': domain_name,
					'sortd_nonce': sortd_ajax_obj_domain.nonce,
					'enable_sortd_redirection':sortd_redirection,
					'enable_amp_links':sortd_amp_links,
					'sortd_service':sortd_service
				}
				if(sortd_service == 'pwa_only' && sortd_amp_links == true && sortd_redirection == false){
				domains.getWarningMsg(data_obj,siteurl);
				return false;
				}
				
				$.ajax({
					url: sortd_ajax_obj_domain.ajax_url,
					data: 	{'action': 'show_warning_msg','sortd_nonce': sortd_ajax_obj_domain.nonce},
					type: 'post',
					success: function (result) {
						var res = JSON.parse(result);
						console.log("success");
						if((sortd_service == 'amp_only' && res.sortd_service == 'pwa_and_amp_both') ||(sortd_service == 'amp_only' && res.sortd_service == 'pwa_only')){
							console.log("if");
							swal({
							
								text: 'Making your whole website AMP will change your urls !!!',
								//timer: 3000,
							
								type: "warning", //type and imageUrl have been replaced with a single icon option.
								icon:'warning', //The right way
								showCancelButton: true, //showCancelButton and showConfirmButton are no longer needed. Instead, you can set buttons: true to show both buttons, or buttons: false to hide all buttons. By default, only the confirm button is shown.
								confirmButtonColor: '#d33', //you should specify all stylistic changes through CSS. As a useful shorthand, you can set dangerMode: true to make the confirm button red. Otherwise, you can specify a class in the button object.
								confirmButtonText: "Yes", // everything is in the buttons argument now
								closeOnConfirm: false,
								buttons:true,//The right way
								buttons: ["No", "Yes"]
							}).then((result) => {

								if(result == true){
									$.ajax({
										url: sortd_ajax_obj_domain.ajax_url,
										data: data_obj,
										type: 'post',
										success: function (result) {
										//	console.log(result);return false;
											window.leavepageflag = true;
					
											location.href = siteurl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_redirection&_wpnonce='+nonceValue+'&leavepage=false'
										}
									});
								} else {
									$("input[name=inlineRadioOptions][value=" + res.sortd_service + "]").prop('checked', true);
								}
						   });
						} else {
							console.log("else");
							$.ajax({
								url: sortd_ajax_obj_domain.ajax_url,
								data: data_obj,
								type: 'post',
								success: function (result) {

									//console.log(result);return false;
									window.leavepageflag = true;

									location.href = siteurl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_redirection&_wpnonce='+nonceValue+'&leavepage=false'
								}
							});
						}
						
						
					} 
				});

				
			}

			
		},

		getWarningMsg:function(data_obj,siteurl){

				
				swal({
				
					text: 'Please Select AMP Service For AMP !!!',
					//timer: 3000,
				
					type: "warning", //type and imageUrl have been replaced with a single icon option.
					icon:'warning', //The right way
					showCancelButton: true, //showCancelButton and showConfirmButton are no longer needed. Instead, you can set buttons: true to show both buttons, or buttons: false to hide all buttons. By default, only the confirm button is shown.
					confirmButtonColor: '#d33', //you should specify all stylistic changes through CSS. As a useful shorthand, you can set dangerMode: true to make the confirm button red. Otherwise, you can specify a class in the button object.
					confirmButtonText: "Yes", // everything is in the buttons argument now
					closeOnConfirm: false,
					//buttons:true,//The right way
					//buttons: ["No", "Yes"]
				}).then((result) => {

					$.ajax({
						url: sortd_ajax_obj_domain.ajax_url,
						data: {'action' : 'get_sortd_service','sortd_nonce': sortd_ajax_obj_domain.nonce,},
						type: 'post',
						success: function (result) {
							var res = JSON.parse(result);

						$("input[name=inlineRadioOptions][value=" + res + "]").prop('checked', true);
						}
					});

					// if(result == true){
					// 	$.ajax({
					// 		url: sortd_ajax_obj_domain.ajax_url,
					// 		data: data_obj,
					// 		type: 'post',
					// 		success: function (result) {
					// 		//	console.log(result);return false;
					// 			window.leavepageflag = true;
		
								
					// 			location.href = siteurl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_redirection&leavepage=false'
					// 		}
					// 	});
					// } 
					// else {
						
					//}
			   });
		

			return false;
		},

		createDomain: function () {
			var site_url = $(this).attr('data-siteurl');
			var public_host = $("#public_host").val().trim();
			var flagdomain = frmValidate(public_host);
			var domainurl = $("#hiddendomain").val();


			if (flagdomain == false) {
				$(".validdomain").show();

				return false;
			}

			if (public_host == '') {

				$(".hostRequired").show();

				return false;

			} else {

				$(".hostRequired").hide();
				$(".infoAlrt").show();
				$(this).attr('disabled', false);
				$.ajax({
					url: sortd_ajax_obj_domain.ajax_url,
					//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
					data: {
						'action': 'sortd_create_domain',
						'public_host': public_host,
						'sortd_nonce': sortd_ajax_obj_domain.nonce
					},
					type: 'post',
					success: function (result) {
						
						try {
							var remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								var dataresult = result.substring(0, remove_after + 1);
								var res = JSON.parse(result);
							} else {
								console.log("This is not valid JSON format")
							}

							if (res.status == "true" || res.status == true) {

								$(".infoAlrt").hide();


								location.href = domainurl;//site_url + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'

								$(this).removeAttr('disabled');

							} else {
								$(".infoAlrt").hide();
								swal({
									icon: 'error',
									text: res.error.message, //'Domain could not be created !!!',
									timer: 3000
								});
								$(this).removeAttr('disabled');

							}



						} catch (e) {
							console.log(e);
							return false;
						}
					}

				});
			}
		},

		generateSsl: function () {
			var site_url = $(this).attr('data-siteurl');

			var domainurl = $("#hiddendomain").val();
			//$(this).prop('disabled',true);
			var publichostval = $(".editpublichostinput").val();

			if (publichostval == "" || publichostval == undefined) {
				$(".generate_ssl").prop('disabled', true);
				swal({
					icon: 'error',
					text: 'Public host cannot be empty!!!',
					timer: 3000
				});

				return false;

			} else {


				$(".infoAlrt").show();
				$.ajax({
					url: sortd_ajax_obj_domain.ajax_url,
					//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
					data: {
						'action': 'generate_ssl',
						'sortd_nonce': sortd_ajax_obj_domain.nonce
					},
					type: 'post',
					success: function (result) {
						//console.log(result);return false;
						try {
							var remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								var dataresult = result.substring(0, remove_after + 1);
								var res = JSON.parse(result);
							} else {
								console.log("This is not valid JSON format")
							}
							//console.log(res.error);return false;
							if (res.data == "true" || res.data == true || res.status == true) {

								setTimeout(function () {
									$(".infoAlrt").hide();
									location.href= domainurl;//site_url + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'
								}, 3000);
								$(this).removeAttr('disabled');

							} else {



								if (res.error.errorCode == 1012) {
								
									$('.onClik_lft').removeClass('heigtDv');

									var html = '';
									html += `<span>${res.error.message.msg}.</span><span>  Kindly refresh after adding CAA record</span><table class="table">
																	  <thead>
																	    <tr>
																	      <th scope="col">Record</th>
																	      <th scope="col">Key</th>
																	      <th scope="col">Type</th>
																	      <th scope="col">Value</th>
																		  <th scope="col">Flags</th>
																	      <th scope="col">Tag</th>
																	    </tr>
																	  </thead>
																	  <tbody>
																	    <tr>
																	      <th scope="row">1</th>
																	      <td>${res.error.message.records.key}</td>
																	      <td>${res.error.message.records.type}</td>
																	      <td>${res.error.message.records.value}</td>
																		  <td>${res.error.message.records.flags}</td>
																		  <td>${res.error.message.records.tag}</td>
																	    </tr>
																	   
																	  </tbody>
																	</table>`;
									var errormsg = html;
									$(".keypairtable").html(html).html();

									$(".keypairtable").show();
									$(".generate_ssl").prop('disabled', false);
									$(".generate_ssl").hide();
									$(".refresh_caa").show();

								} else {

									var errormsg = res.error.message;
									swal({

										icon: 'error',
										text: errormsg,
										timer: 7000

									});

									$(this).prop('disabled', false);
								}
								$(".infoAlrt").hide();


							}

						} catch (e) {
							console.log(e);
							return false;
						}
					}

				});

			}
		},

		validateSsl: function () {
			var site_url = $(this).attr('data-siteurl');

			var domainurl = $("#hiddendomain").val();

			//console.log(domainurl);return false;
			//var nonce = $("#sortd-domains-nonce").val();
			var public_host = $("#public_host").val();
			console.log('control came here');
			$(this).attr('disabled', true);
			$(".infoAlrt").show();
			$.ajax({
				url: sortd_ajax_obj_domain.ajax_url,
				//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
				data: {
					'action': 'validate_ssl',
					'sortd_nonce': sortd_ajax_obj_domain.nonce
				},
				type: 'post',
				success: function (result) {

					try {
						var remove_after = result.lastIndexOf('}');
						if (remove_after != -1) {
							var dataresult = result.substring(0, remove_after + 1);
							var res = JSON.parse(result);
						} else {
							console.log("This is an invalid JSON format")
						}
						//console.log(result);
						const parsedResponse = JSON.parse(result);
						const x = JSON.stringify(parsedResponse, null, 2);
						console.log(x);


						if (res.status == "true" || res.status == true) {
							
							if(res.data=="PENDING_VALIDATION" || res.data=="INACTIVE" || res.data=="EXPIRED" || res.data=="VALIDATION_TIMED_OUT" || res.data=="REVOKED" || res.data=="FAILED" ){
								$(".infoAlrt").hide();
								swal({
									icon: 'error',
									title: res.data,
									timer: 3000
								}).then(function () {
									location.href = domainurl;
								});
								$(this).removeAttr('disabled');
							} else{
								$(".infoAlrt").hide();
								swal({
									icon: 'success',
									title: res.data,
									timer: 3000
								}).then(function () {
									location.href = domainurl;
								});
								$(this).removeAttr('disabled');
							}

							
						} else {
							$(".infoAlrt").hide();
							swal({
								icon: 'error',
								text: 'Validate SSL could not be completed !!!',
								timer: 3000
							});
							$(this).removeAttr('disabled');
						}

					} catch (e) {
						console.log(e);
						return false;
					}
				}

			});
		},

		deployCdn: function () {
			var site_url = $(this).attr('data-siteurl');
			//var nonce = $("#sortd-domains-nonce").val();
			var public_host = $("#public_host").val();
			var domainurl = $("#hiddendomain").val();

			$(this).attr('disabled', true);
			$(".infoAlrt").show();
			$.ajax({
				url: sortd_ajax_obj_domain.ajax_url,
				//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
				data: {
					'action': 'deploy_cdn',
					'sortd_nonce': sortd_ajax_obj_domain.nonce
				},
				type: 'post',
				success: function (result) {
				
					try {
						var remove_after = result.lastIndexOf('}');
						if (remove_after != -1) {
							var dataresult = result.substring(0, remove_after + 1);
							var res = JSON.parse(result);
						} else {
							console.log("This is not valid JSON format")
						}

						if (res.data == "true" || res.data == true) {

							$(".infoAlrt").hide();
							location.href = domainurl;
							$(this).removeAttr('disabled');
						} else {
							$(".infoAlrt").hide();
							swal({
								icon: 'error',
								text: 'CDN was already deployed or there is some error !!!',
								timer: 3000
							});

							$(this).removeAttr('disabled');
						}

					} catch (e) {
						console.log(e);
						return false;
					}
				}

			});
		},

		removeExludeUrlLink: function () {

		} ,

		editDomain: function () {
			$(".editpublichostinput,.editdomaintick").show();
			$(".spanhost").hide();
			$(".editpublichostinput").attr('disabled',false);
			$(".editpublic").hide();
			$(".crosspublichosticon").show();
		} ,

		editDomainInput: function () {
			var currentText = $(this).val();
			// Setting the Div content
			$(".domaintypespan").text(currentText);
		} ,


		cancelEditDomain: function () {
			$(".spanhost,.editpublic").show();
			$(".crosspublichosticon,.editpublichostinput,.editdomaintick").hide();
			$(".domaintypespan").text($(".spanhost").text());
			$(".editpublichostinput").val($(".spanhost").text());
			$(".generate_ssl").prop('disabled',false);
		} ,

		saveUpdatedDomain: function () {
			var publichostval = $(".editpublichostinput").val();

			var flagdomain = frmValidate(publichostval);


			if (flagdomain == false) {
				$(".validdomain").show();
				$(".generate_ssl").prop('disabled',true);
				return false;
			} else {
				$(".validdomain").hide();
			if(publichostval == "" || publichostval == undefined){
				$(".generate_ssl").prop('disabled',true);
				swal({
					icon: 'error',
					text: 'Public host cannot be empty!!!',
					timer: 3000
				});

				return false;
			} else {

				$(".keypairtable").hide();
				$.ajax({

					url: sortd_ajax_obj_domain.ajax_url,
					data: { 'action': 'sortd_update_public_host','domain':publichostval, 'sortd_nonce': sortd_ajax_obj_domain.nonce },
					type: 'post',
					success: function (result) {

						var remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								var dataresult = result.substring(0, remove_after + 1);
								var res = JSON.parse(result);
							} else {
								console.log("This is not valid JSON format")
							}

							if (res.status == true || res.data == true) {

								$(".editpublichostinput").val(publichostval);	
								$(".editdomaintick").hide();
								$(".editpublichostinput").attr('disabled',true);
								$(".crosspublichosticon").hide();
								$(".editpublic").show();

								$(".succmsgdomain").show();
								$(".spanhost").text(publichostval)

								setTimeout(function(){
									$(".succmsgdomain").hide()
								},3000);
								$(".generate_ssl").prop('disabled',false);

					
								
							} else {

							
								
								swal({
									icon: 'error',
									text: res.error.message,//'Public host not updated!!!',
									timer: 3000
								});

									$(".generate_ssl").prop('disabled',true);
							}

						

						}

					});

				}
			}
		} ,
		verifyCname : function(){
			var site_url = $(this).attr('data-siteurl');
			var domainurl = $("#hiddendomain").val();


		$.ajax({
			//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
			url: sortd_ajax_obj_domain.ajax_url,
			data: { 'action': 'verify_cname', 'sortd_nonce': sortd_ajax_obj_domain.nonce },
			type: 'post',
			success: function (result) {

				//console.log(result);return false;
				try {
					var remove_after = result.lastIndexOf('}');
					if (remove_after != -1) {
						var dataresult = result.substring(0, remove_after + 1);
						var res = JSON.parse(result);
					} else {
						console.log("This is not valid JSON format")
					}

					if (res.data == "true" || res.data == true) {

						$(".infoAlrt").hide();
						location.href = domainurl;

					} else {
						$(".infoAlrt").hide();
						swal({
							icon: 'error',
							text: 'DNS record not found!!!',
							timer: 3000
						});
					}

				}
				catch (e) {
					console.log(e);
					return false;
				}
			}
		});
		} ,
		checksslvalidation: function(){

			var enableSubmit = function (ele) {
				$(ele).removeAttr("disabled");
			}
			
			var that = this;
			$(this).attr("disabled", true);
			$(".imgload").show();
			setTimeout(function () {
				enableSubmit(that),
					$(".imgload").show(),
					location.reload();
			}, 10000);
		},
		syncAuthors : function(){

			//console.log("heyyyy");return false;
			$(this).hide();
			$(".dataloader").show();
			$(".dataSuccess").show();
			$.ajax({
				//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
				url: sortd_ajax_obj_domain.ajax_url,
				data: { 'action': 'sortd_sync_authors', 'sortd_nonce': sortd_ajax_obj_domain.nonce },
				type: 'post',
				success: function (result) {

					console.log(result);
					//return false;
					try {
						var res = JSON.parse(result);
						if ((res.flag == "true" || res.flag== true) && !res.maintain_error) {
							if(res.synced_count == 0){
								var msg = "No authors found."
							} else {
								var msg = `${res.synced_count} authors synced`;
							}
							$(".dataSuccess").html(`<p style="color:green">Authors action completed , ${msg} </p>`);
							$(".dataloader").hide();
							setTimeout(function(){
								$(".dataSuccess").hide();
								$(".manageAuthors").hide();
							}, 5000);

							setTimeout(function(){
								$(".authorsSettings").hide();
							}, 10000);
							
						} else if((res.flag == "true" || res.flag== true) && res.maintain_error) {
							$(".dataSuccess").html(`<p style="color:red">` + res.maintain_error + `</p>`);
							$(".dataloader").hide();
							setTimeout(function(){
								$(".dataSuccess").hide();
								$(".manageAuthors").show();
							}, 5000);
						} else {
							$(".dataSuccess").html(`<p style="color:red">Some Error occured</p>`);
							$(".dataloader").hide();
							setTimeout(function(){
								$(".dataSuccess").hide();
								$(".manageAuthors").show();
							}, 5000);
						}

					}
					catch (e) {
						console.log(e);
						return false;
					}
				}
			});
		},

		saveShortsCategory : function(){
			let catShortsId = $("#mySelectShortsCat").val();

			let wp_domain = $(this).attr('data-wp_domain');
                let project_slug = $(this).attr('data-project_slug');
                let current_user = $(this).attr('data-current_user');

                if (typeof gtag === 'function') {
                    gtag('event', 'sortd_action', {
                        'sortd_page_title': 'sortd_manage_settings',
                        'sortd_feature': 'Select Shorts Category',
                        'sortd_domain': wp_domain,
                        'sortd_project_slug': project_slug,
                        'sortd_user': current_user
                    });
                }

			// if(catShortsId == ''){

				
			// } else {
				$("#spanshorts").hide();
				$.ajax({
					url: sortd_ajax_obj_domain.ajax_url,
					data: { 'action': 'save_shors_cat', 'id':catShortsId,'sortd_nonce': sortd_ajax_obj_domain.nonce },
					type: 'post',
					success: function (response) {
						
						let result = JSON.parse(response);
						console.log(result)
						if(result.status != false && result.update_response == true){
							console.log("updated");
							$("#successimgshorts").show();
							setTimeout(function () {
								$('#successimgshorts').fadeOut(500);
							}, 3000);
						} else {
							$(".taxSyncUnsyncNotice").html(result.error.message);
							$(".taxSyncUnsyncNotice").show();
							setTimeout(function () {
								$('.taxSyncUnsyncNotice').fadeOut(500);
							}, 3000);
						}
					}
				});	

			// }
			
			$(this).hide();
			$("#cancelBtnShorts").hide();
			
		}
	}

	$(".saveRedirection").click(domains.saveRedirectionValues);
	$(".create_domain").click(domains.createDomain);
	$(".generate_ssl").click(domains.generateSsl);
	$(".validate_ssl").click(domains.validateSsl);
	$(".deploy_cdn").click(domains.deployCdn);
	$(".editpublic").click(domains.editDomain);
	$(".editpublichostinput").keyup(domains.editDomainInput);
	$(".crosspublichosticon").click(domains.cancelEditDomain);
	$(".editdomaintick").click(domains.saveUpdatedDomain);
	$(".verify-cname").click(domains.verifyCname);
	$(".refresh").click(domains.checksslvalidation);
	$(".refresh_caa").click(function(){
		location.reload();
	});

	$('#mySelectShortsCat').click(function() {
		$("#saveBtnShorts").show();
		$("#cancelBtnShorts").show();
	  });

	  $("#cancelBtnShorts").click(function(){

		
		$("#saveBtnShorts").hide();
		$(this).hide();
		$("#successimgshorts").hide();

		$.ajax({
			url: sortd_ajax_obj_domain.ajax_url,
			data: { 'action': 'get_shors_cat','sortd_nonce': sortd_ajax_obj_domain.nonce },
			type: 'post',
			success: function (response) {
			
				let result = JSON.parse(response);

				if(result == undefined || result == ''){

					result = '';
				}
				$("#mySelectShortsCat").val(result);
			}
		});	


	  });
	$(".manageAuthors").click(domains.syncAuthors);
	$("#saveBtnShorts").click(domains.saveShortsCategory);

	

	function frmValidate(domain) {
        var val = domain;//document.frmDomin.name.value;
        if (/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i.test(val)) {
            //alert("Valid Domain Name");
            return true;
        } else {
           // alert("Enter Valid Domain Name");
          //  val.name.focus();
            return false;
        }
    }

})(jQuery);