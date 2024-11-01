

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

	$(document).ready(function () {




		$(".verifycredentialsbtn").click(function () {
			var verifylicense = $("#licensekey").val();
			var siteurl = $("#siteurl").val();
			var sortd_cre_key = $("#sortd_cre_key").val();
			var htmlappend = '';



			if (!verifylicense) {
				$("#validationspan").html('Enter Credentials');
			} else {
				$(this).attr('disabled',true);

				var reslicense = JSON.parse(verifylicense);

				//console.log(reslicense);return false;

				var accessKey = reslicense.access_key;
				var secretKey = reslicense.secret_key;
				var str = '';

				var project_name = reslicense.project_name;
				var project_id = reslicense.project_id;
				var host = reslicense.host;


				$.ajax({
					url: sortd_ajax_obj_setup.ajax_url,
					//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
					data: { 'action': 'verifycredentials', 'access_key': accessKey, 'secret_key': secretKey, 'project_name': project_name, 'project_id': project_id, 'host': host, 'license_data': reslicense, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
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

							if (res.status == true) {

								$(".already_verified").show();
								$(".already_verified_onload").hide();
							} else {


								if (res.verify.status === true) {

									if (sortd_cre_key == 0) {

										$.ajax({
											url: sortd_ajax_obj_setup.ajax_url,
											//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
											data: { 'action': 'getContractDetailsAfterVerify', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
											type: 'post',
											success: function (result) {

												var resJson = JSON.parse(result);

												var gettimestart = getDateFormat(resJson.data.plan_start_date);
												var gettimeend = getDateFormat(resJson.data.plan_expire_date);
												var startdatesplit = gettimestart.split('-');
												var enddatesplit = gettimeend.split('-');
												var startdate;
												var enddate;
												var monthNames={"01":"January", "02":"February", "03":"March","04": "April", "05":"May", "06":"June",
            									"07":"July","08": "August","09": "September","10": "October","11": "November", "12":"December"};
												$.each(monthNames,function(i,j){

													if(startdatesplit[1] == i){
														startdate = startdatesplit[0]+' '+j+' '+startdatesplit[2]
													}
													if(enddatesplit[1] == i){
														enddate = enddatesplit[0]+' '+j+' '+enddatesplit[2]
													}
												
												});

												//console.log(gettimeend,enddatesplit);return false;

												htmlappend += `<h3> Plan : ${resJson.data.plan_name}</h3>`;
												var plandetailshtml = '';
												plandetailshtml+= startdate+'-'+enddate;
												$("#activeplandiv").append(htmlappend);
												$("#plandetailspan").append(plandetailshtml);

												$("#congratsdiv").show();
												$("#opacityBox").show();
											}

										});

										
										//$(".verifyprojectdetialsfinal").show();
										$(".verifyprojectdetials").hide();

										//$(this).text('Redirecting soon....');


										var delay = 7000;
										var url = siteurl + '/wp-admin/admin.php?page=sortd_manage_templates'

										setTimeout(function () { window.location = url; }, delay);

									} else {


										$("#successfully_verified").show();
										$("#opacityBox").show();
										$(".verifyprojectdetialsfinal").show();
										$(".verifyprojectdetials").hide();

										if (res.screenstatus == 1) {
											var delay = 5000;
											var url = siteurl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_setup'
											setTimeout(function () { window.location = url; }, delay);
										} else if (res.screenstatus == 0) {
											var delay = 5000;
											var url = siteurl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_templates'
											setTimeout(function () { window.location = url; }, delay);
										} else {
											location.reload();
										}



									}




									if (res.project.status == true) {

										if (res.project.data.status == 1) {
											res.project.data.status = 'Active';
										} else {
											res.project.data.status = 'Inactive';
										}

										str += '<form id="" action=""  method="post">' +
											' <div class="form-group">' +
											'<h5 class="sortHed mb1">Project Details</h5>' +

											'<br>' +
											' <div class="trow">' +
											' <span class="thead">Name:</span>' +
											'<span>' + res.project.data.name + '</span>' +

											' </div>' +
											' <div class="trow">' +
											' <span class="thead">Desc:</span>' +
											'<span>' + res.project.data.desc + '</span>' +

											' </div>' +

											'<div class="trow">' +
											' <span class="thead">Status:</span>' +
											'<span>' + res.project.data.status + '</span>' +
											'</div>' +
											'<div class="trow">' +
											' <span class="thead">Slug:</span>' +
											'<span>' + res.project.data.slug + '</span>' +
											'</div>' +



											'</div>' +
											' <div class="form-group">' +
											'<h5 class="sortHed mb1"></h5>';


										if (res.project.data.domain != undefined) {
											if (res.project.data.domain.public_host != undefined) {
												str += '<label class="domLb">' +
													'<h5 class="sortHed">Domain</h5>' +
													'</label>' +
													'<br>' +
													'<div class="trow sadaasd">' +
													' <span class="thead">Public Host:</span>' +
													'<span>' + res.project.data.domain.public_host + '</span>' +
													' </div>';
											}


											if (res.project.data.domain.demo_host != undefined) {

												str += ' <div class="trow awwqewqe">' +
													' <span class="thead">Demo Host:</span>' +
													'<span>' + res.project.data.domain.demo_host + '</span>' +

													' </div>';

											}

										}

										str += '</div>';

										if (res.contract.data != undefined) {

											str += '  <div class="form-group">' +
												'<h5 class="sortHed mb1">Plan Details</h5>' +
												'<div class="trow">' +
												'<span class="thead">Plan Name</span>' +
												'<span>' + res.contract.data.plan_name + '</span>' +
												'</div>' +
												'<div class="trow">' +
												' <span class="thead">Plan Type</span>' +
												' <span>' + res.contract.data.plan_type + '</span>' +
												'  </div>' +
												' <div class="trow">' +
												'<span class="thead">Plan Start Date</span>' +
												'<span>' + res.contract.data.plan_start_date + '</span>' +
												'</div>' +
												'<div class="trow">' +
												'<span class="thead">Plan End Date</span>' +
												' <span>' + res.contract.data.plan_expire_date + '</span>' +
												' </div>' +
												' </div>';

										}

										str += '</form>';

										$('.verifyprojectdetialsfinal').html(str);
									} else {

										$(".message").prepend('<div class="notice notice-error is-dismissible"><p>' + res.project.error.message + '</p><span class="closeicon" aria-hidden="true">&times;</span></div>');

									}

								} else if (res.verify.status == false) {
									console.log("test")
									$(".verifyprojectdetialsfinal").hide();
									$(".message").prepend('<div class="notice notice-error is-dismissible"><p>' + res.verify.error.message + '</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
									$(".notice-error").delay(7000).fadeOut(500);
									$(".verifyprojectdetials").hide();
									$("#successfully_verified").hide();
									$(".already_verified_onload").hide();
									$(".verifycredentialsbtn").prop('disabled',false);


								}

							}

						} catch (e) {

							console.log(e);
							return false;
						}
					}
				});

			}
		});






	});

	function getDateFormat(date) {
		var d = new Date(date),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear();

		if (month.length < 2)
			month = '0' + month;
		if (day.length < 2)
			day = '0' + day;
		var date = new Date();
		date.toLocaleDateString();

		return [day, month, year].join('-');
	}
	;


	$(".previewbtn").click(function () {

		var siteURL = $("#siteurl").val();
		var demo_host = $("#demohost").val();
		$("#loader").show();
		$(".spanpreviewloader").show();



		$(".mobDevice").show();
		$('#imgscreenshot_iframe').attr('src', demo_host);
		$(".spanpreviewloader").hide();
		$("#loader").hide();



	});


	$(".syncCat").click(function () {

		var siteurl = $("#siteurl").val();

		$(".categorysyncmanage").show();
		$(".categoryreordermanage").hide();
		$(".reor-renameCat").removeClass('manageCategory-active');
		$(this).addClass('manageCategory-active');


		window.leavepageflag = true;

		var currURL = window.location.href;
		var url = (currURL.split(window.location.host)[1]).split("&")[0];
		window.history.pushState({}, document.title, url)
		var ht = '';
		var idattribute;

		$.ajax({
			url: sortd_ajax_obj_setup.ajax_url,
			data: { 'action': 'reorderandrenameajax', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
			type: 'post',
			success: function (result) {


				try {
					var remove_after = result.lastIndexOf('}');
					if (remove_after != -1) {
						var dataresult = result.substring(0, remove_after + 1);
						var res = JSON.parse(result);
						//	console.log(res);return false;

					} else {
						console.log("This is not valid JSON format")
					}


					$.each(res.data, function (i, j) {


						if (j.sub_categories.length == 0) {

							$(".categorytbody tr td").each(function (a, b) {

								idattribute = $(this).attr('id');

								if (idattribute != undefined && j.cat_guid == idattribute) {
									$(".catSyncHead_" + idattribute).text(j.name);
									console.log(j);

								}


							});

						} else {

							$(".categorytbody tr td").each(function (a, b) {

								idattribute = $(this).attr('id');

								if (idattribute != undefined && j.cat_guid == idattribute) {
									$(".catSyncHead_" + idattribute).text(j.name);
									console.log(j);

								}


							});
							$.each(j.sub_categories, function (k, l) {
								$(".categorytbody tr td").each(function (a, b) {

									idattribute = $(this).attr('id');

									if (idattribute != undefined && l.cat_guid == idattribute) {
										$(".catSyncHead_" + idattribute).text(l.name);


									}


								});
							});
						}




					});

					ht += '</li>';

					$('.catreorderrenameol').html(ht);

					tick();

					edit();

					crossicon();

				}
				catch (e) {
					console.log(e);
					return false;
				}
			}
		});

	});

	$(".reor-renameCat").click(function () {

		var siteurl = $("#siteurl").val();

		$(".categorysyncmanage").hide();
		$(".categoryreordermanage").show();
		$(".syncCat").removeClass('manageCategory-active');
		$(this).addClass('manageCategory-active')

		window.leavepageflag = false;

		var currURL = window.location.href;
		var url = (currURL.split(window.location.host)[1]).split("&")[0];
		window.history.pushState({}, document.title, url)

		var idattr;
		var splitattr;
		var ht = '';
		$.ajax({
			url: sortd_ajax_obj_setup.ajax_url,
			data: { 'action': 'reorderandrenameajax', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
			type: 'post',
			success: function (result) {


				try {
					var remove_after = result.lastIndexOf('}');
					if (remove_after != -1) {
						var dataresult = result.substring(0, remove_after + 1);
						var res = JSON.parse(result);
						//console.log(res);return false;

					} else {
						console.log("This is not valid JSON format")
					}


					$.each(res.data, function (i, j) {

						ht += appendreorderrenameCategorieshtml(j._id, j.name, j.alias, siteurl);
						if (j.sub_categories.length != 0) {
							ht += '<ol>';
							$.each(j.sub_categories, function (k, l) {



								ht += appendsubcathtml(l._id, l.name, l.alias, siteurl);


							});

							ht += '</ol>'



						}

					});

					ht += '</li>';

					$('.catreorderrenameol').html(ht);

					tick();

					edit();

					crossicon();

					minimizeandmaximize();
				}
				catch (e) {
					console.log(e);
					return false;
				}
			}
		});

	});



	function appendreorderrenameCategorieshtml(catid, name, alias, siteurl) {
		var caturl = $("#pluginurlpath").val();

		var html = '';
		html += `<li id="menuItem_` + catid + `" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded licatreorderrename"><div class="menuDiv ">
                          <span  contenteditable="true" title="Click to show/hide children" class="disclose ui-icon-sort ui-icon-minusthick">
                           
                          </span>
                         
                           <span id="heading_`+ catid + `" class="heading_name` + catid + ` headingspanclass">` + name + `</span>  <span id="heading_` + catid + `" class="heading_alias` + catid + ` headingspanclass">` + alias + `</span><span class="editclickicon headingspanclass" title="Edit category name and alias" id="editicon` + catid + `"><span class="hovrTol">edit category name and alias</span><span class="sucMsg messagespansuccess` + catid + `" style = "display:none;color:green">Successfully renamed</span><img src="${caturl}css/edit--v1.png"/></span> <input type="text" required name="editinput_name[name_` + catid + `]" class="editinput_` + catid + ` editclassspan" id="heading_` + catid + `" value="` + name + `" style="display:none"><input type="text" required  name="editinput_alias[alias_` + catid + `]" class="editinput_alias` + catid + ` editclassspan" id="heading_` + catid + `" value="` + alias + `" style="display:none"><span class="btn crossicon" id="btnclose` + catid + `" style="display:none"><i class="bi bi-x"></i></span><span class="btn tickicon" id="btntick` + catid + `" data-nonce="'.wp_create_nonce('rw-sortd-rename-cat-'` + catid + `).'" style="display:none"><i class="bi bi-check"></i><span class="wrngMsg messagespan` + catid + `" style = "display:none;color:red"></span></span></div>`;


		return html;


	}

	function appendsubcathtml(catid, name, alias, siteurl) {
		var caturl = $("#pluginurlpath").val();
		var htmlsub = '';
		htmlsub += `<li id="menuItem_${catid}" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded licatreorderrename"><div class="menuDiv "><span contenteditable="true" title="Click to show/hide children" class="disclose ui-icon-sort ui-icon-minusthick">
	                             <span></span>
	                             </span><span id="heading_${catid}" class="heading_name${catid} spansubhead">${name}</span>  <span id="heading_${catid}" class="heading_alias${catid} spansubhead">${name}</span><span class="editclickicon spansubhead editsubcat" id="editicon${catid}"><span class="sucMsg messagespansuccess${catid}" style = "display:none;color:green">Successfully renamed</span><img title="Edit category name and alias" src="${caturl}css/edit--v1.png"/></span> <input type="text" name="editinput_name[name_${catid}]" class="editinput_${catid} editclassspan " id="heading_${catid}" value="${name}" style="display:none"><input type="text"  name="editinput_alias[alias_${alias}]" class="editinput_alias${catid} editclassspan" id="heading_${catid}" value="${alias}" style="display:none"><span class="btn crossicon" id = "btnclose${catid}" style="display:none"><i class="bi bi-x"></i></span><span class="btn tickicon" id="btntick${catid}" data-nonce="'.wp_create_nonce('rw-sortd-rename-cat-${catid}).'" style="display:none"><i class="bi bi-check"></i><span class="wrngMsg messagespan${catid}" style = "display:none;color:red"></span></span></div></li>`;
		//console.log(htmlsub);
		return htmlsub;
	}








	$(document).ready(function () {

		$(".buttonsmanage").show();

		var url_string = window.location.href;
		var url = new URL(url_string);
		var spliturl = url_string.split('/wp-admin');

		var page = url.searchParams.get("page");
		var action = url.searchParams.get("action");
		var section = url.searchParams.get("section");

		var idattribute;
		var splitattr;

		var ht = '';
		var hts = '';

		if (section == 'sortd_manage_categories' && action == null) {
			$(".syncCat").addClass('manageCategory-active');
			$(".categorysyncmanage").show();
			$(".categoryreordermanage").hide();

			window.leavepageflag = true;
		} else if (section == 'sortd_manage_categories' && action == 'reorder') {
			$(".reor-renameCat").addClass('manageCategory-active');
			$(".categorysyncmanage").hide();
			$(".categoryreordermanage").show();

			window.leavepageflag = true;

			$.ajax({
				//url: spliturl[0]+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
				url: sortd_ajax_obj_setup.ajax_url,
				data: { 'action': 'reorderandrenameajax', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
				type: 'post',
				success: function (result) {

					//console.log("fff",result);return false;
					try {
						var remove_after = result.lastIndexOf('}');
						if (remove_after != -1) {
							var dataresult = result.substring(0, remove_after + 1);
							var res = JSON.parse(result);
							//	console.log(res);

						} else {
							console.log("This is not valid JSON format")
						}


						$.each(res.data, function (i, j) {

							ht += appendreorderrenameCategorieshtml(j._id, j.name, j.alias, spliturl[0]);
							if (j.sub_categories.length != 0) {
								ht += '<ol>';
								$.each(j.sub_categories, function (k, l) {



									ht += appendsubcathtml(l._id, l.name, l.alias, spliturl[0]);


								});

								ht += '</ol>'



							}

						});

						ht += '</li>';

						$('.catreorderrenameol').html(ht);



						tick();

						edit();

						crossicon();

						minimizeandmaximize();

					}
					catch (e) {
						console.log(e);
						return false;
					}
				}
			});


		}

		if (section == 'sortd_manage_domains') {
			var enableSubmit = function (ele) {
				$(ele).removeAttr("disabled");
			}


			$(".refresh").click(function () {
				var that = this;
				$(this).attr("disabled", true);
				$(".imgload").show();
				setTimeout(function () {
					enableSubmit(that),
						$(".imgload").show(),
						location.reload();
				}, 10000);
			});

		}
	});


	$(".verify-cname").click(function () {
		var site_url = $(this).attr('data-siteurl');


		$.ajax({
			//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
			url: sortd_ajax_obj_setup.ajax_url,
			data: { 'action': 'verifycname', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
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
						window.location = site_url + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'

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
	});

		function tick() {
		$(".tickicon").click(function () {
			var tickid = this.id;
			var spliticontick = tickid.split('btntick');

			// console.log(spliticontick);return false;
			var namevalue = $(".editinput_" + spliticontick[1]).val();
			var aliasval = $(".editinput_alias" + spliticontick[1]).val();
			var site_url = $("#hiddenurl").val();
			var nonce = $(this).attr('data-nonce');

			var my_string = namevalue;
			var spaceCount = (my_string.split(" ").length - 1);
			var string = (my_string.split(" "));
			var namespace = my_string.match(/^\s+$/);
			var namelength = my_string.length;


			var my_stringalias = aliasval;
			var spaceCountalias = (my_stringalias.split(" ").length - 1);
			var stringalias = (my_stringalias.split(" "));
			var aliasspace = my_stringalias.match(/^\s+$/);
			var alaislength = my_stringalias.length;
			//console.log(my_string.length);return false;

			if ((namespace === null) && (aliasspace === null)) {
				if ((namevalue == '') && (aliasval != '')) {
					console.log(1);
					$(".messagespan" + spliticontick[1]).show();
					$(".messagespan" + spliticontick[1]).text('Name is required');
					//return false;

				} else if ((namevalue != '') && (aliasval == '')) {
					console.log(2);
					$(".messagespan" + spliticontick[1]).text('Alias is required');
					$(".messagespan" + spliticontick[1]).show();
					// return false;
				} else if ((namevalue == '') && (aliasval == '')) {
					console.log(3);
					$(".messagespan" + spliticontick[1]).text('Name and alias is required');
					$(".messagespan" + spliticontick[1]).show();
					// return false;
				} else {

					$.ajax({
						//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
						url: sortd_ajax_obj_setup.ajax_url,
						data: { 'action': 'renameCat', 'id': spliticontick[1], 'name': namevalue, 'alias': aliasval, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
						type: 'post',
						success: function (result) {

							//  console.log(result);return false;

							try {


								var remove_after = result.lastIndexOf('}');
								if (remove_after != -1) {
									var dataresult = result.substring(0, remove_after + 1);
									var res = JSON.parse(result);


									$.each(res.responseCat.data, function (i, j) {


										if (j._id == spliticontick[1]) {

											// console.log('yes',j.name);

											$(".heading_name" + spliticontick[1]).text(j.name);
											$(".heading_alias" + spliticontick[1]).text(j.alias);





										}

										if (j.sub_categories.length > 0) {

											$.each(j.sub_categories, function (k, l) {

												if (l._id == spliticontick[1]) {
													$(".heading_name" + spliticontick[1]).text(l.name);
													$(".heading_alias" + spliticontick[1]).text(l.alias);
												}
											});
										}
									});

									$(".editinput_" + spliticontick[1]).hide();
									$(".editinput_alias" + spliticontick[1]).hide();
									$(".heading_name" + spliticontick[1]).show();
									$(".heading_alias" + spliticontick[1]).show();
									$("#editicon" + spliticontick[1]).show();
									$("#btnclose" + spliticontick[1]).hide();
									$("#btntick" + spliticontick[1]).hide();
									$(".messagespan" + spliticontick[1]).hide()
									$(".messagespansuccess" + spliticontick[1]).show();
									//  $(".messagespansuccess"+spliticontick[1]).hide(6000);
									$(".messagespansuccess" + spliticontick[1]).delay(3200).fadeOut(300);

								} else {
									console.log("This is not valid JSON format")
								}



								return false;





								if (res.status == false && (res.error.errorCode == 1004 || res.error.errorCode == 1005)) {
									window.location.href = site_url + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings';
								} else {

								}


							} catch (e) {

								console.log(e);
								return false;
							}
						}
					});

				}

			} else {

				$(".messagespan" + spliticontick[1]).show();
				$(".messagespan" + spliticontick[1]).text('Space is not allowed');
			}



		});
	}

	function edit() {
		$(".editclickicon").click(function () {

			var id = this.id;

			var spliticon = id.split('editicon');

			$(".editinput_" + spliticon[1]).show();
			$(".editinput_alias" + spliticon[1]).show();
			$(".heading_name" + spliticon[1]).hide();
			$(".heading_alias" + spliticon[1]).hide();
			$("#editicon" + spliticon[1]).hide();


			$("#btnclose" + spliticon[1]).show();
			$("#btntick" + spliticon[1]).show();



			// $(".editinput").show();
		});
	}

	function crossicon() {
		$(".crossicon").click(function () {
			var idcross = this.id;
			var spliticoncross = idcross.split('btnclose');

			$(".editinput_" + spliticoncross[1]).hide();
			$(".editinput_alias" + spliticoncross[1]).hide();
			$(".heading_name" + spliticoncross[1]).show();
			$(".heading_alias" + spliticoncross[1]).show();
			$("#editicon" + spliticoncross[1]).show();


			$("#btnclose" + spliticoncross[1]).hide();
			$("#btntick" + spliticoncross[1]).hide();


		});
	}

	function minimizeandmaximize() {


		var ns = $('ol.sortable').nestedSortable({
			forcePlaceholderSize: true,
			handle: 'div',
			helper: 'clone',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
			maxLevels: 2,
			isTree: true,
			expandOnHover: 700,
			startCollapsed: false,
			update: function () {
				var hiered = $('ol.sortable').nestedSortable('toHierarchy', { startDepthCount: 0 });
				var newOrder = JSON.stringify(hiered);
				$("#category_order").val(newOrder);

				$(".reorderClick").click(function () {


					var category_order = $("#category_order").val();
					var category_order_old = $("#category_order_old").val();
					var hiddenurl = $("#hiddenurl").val();

					window.leavepageflag = true;
					//console.log(category_order_old);return false;

					$.ajax({
						//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
						url: sortd_ajax_obj_setup.ajax_url,
						data: { 'action': 'categoryReorder', 'category_order_old': category_order_old, 'category_order': category_order, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
						type: 'post',

						success: function (result) {
							//console.log(result);return false;
							try {


								window.location.href = hiddenurl + "admin.php?page=sortd-manage-settings&section=sortd_manage_categories&action=reorder";

								// var remove_after= result.lastIndexOf('}');
								// 	if(remove_after != -1){
								// 	  	var dataresult =  result.substring(0,remove_after +1);
								// 		 	var res = JSON.parse(result);
								// 	}else {
								// 	  console.log("This is not valid JSON format")
								// 	} 




							} catch (e) {

								console.log(e);
								return false;
							}
						}
					});
				});
			}
		});

		$('.disclose').on('click', function () {
			$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
			$(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');

		});
	}

	$(".imageArticle").click(function () {

		var restoreid = this.id;
		var site_url = $(".hiddenurl").val();

		var post_id = $(this).attr('data-guid');





		$.ajax({
			url: sortd_ajax_obj_setup.ajax_url,
			//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
			data: { 'action': 'sortdrestore', 'post_id': post_id, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
			type: 'post',
			success: function (result) {


				var incStr = result.includes("<!--");
				try {
					var remove_after = result.lastIndexOf('}');
					if (remove_after != -1) {
						var dataresult = result.substring(0, remove_after + 1);
						var res = JSON.parse(result);
					} else {
						console.log("This is not valid JSON format")
					}




					if (res.status === true) {


						swal("Successfully Synced Article");
						// $("#articleiddiv"+post_id).css("background-color", "white");
						// $("#imagearticle"+post_id).hide();
						// $("#"+post_id).show(); 	

						$("#row_" + post_id).removeClass('table-dlt');

						$("#imageArticlerestore" + post_id).hide();

						// $("#articleiddiv"+guid).css("background-color", "#f8d7da");
						// $("#imagearticle"+guid).show();
						$("#" + post_id).show();

					} else if (res.status == false) {


						if (res.error.errorCode != 1004 && res.error.errorCode != 1005) {
							$("#sync_" + post_id).prepend('<div class="notice notice-error is-dismissible"><p>' + res.error.message + '</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
							$(".notice-error").delay(2000).fadeOut(500);
						} else {

							//console.log("ddsds");return false;
							$('.modal-body').text(res.error.message);
							$('#server_msg_modal_' + post_id).modal('show');

						}
					}
				} catch (e) {

					console.log(e);
					return false;
				}
			}
		});

		return false;

	});
	$(".closeicon").click(function () {
		$(".bulksortdaction").hide();
	});

	$(".one_click").click(function () {

		var site_url = $(this).attr('data-siteurl');
		var nonce = $("#sortd-oneclick-nonce").val();
		var category_quota = $("#category_quota").val();
		var article_quota = $("#article_quota").val();
		var date_till = $("#date_till").val();
		$('.one_click').prop('disabled', true);

		$(".sync_cat_label").show();
		$(".minute_label").show();

		$(".inprogresssetup").show();
		$(".startsetup").hide();
		$.ajax({
			//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
			url: sortd_ajax_obj_setup.ajax_url,
			data: { 'action': 'syncCategories', 'category_quota': category_quota, 'article_quota': article_quota, 'date_till': date_till, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
			type: 'post',
			success: function (result) {

				//console.log(result);return false;
				//		$(".mesnotify").prepend('<div class="notice notice-error is-dismissible"><p>'+res.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');	
				try {
					var remove_after = result.lastIndexOf('}');
					if (remove_after != -1) {
						var dataresult = result.substring(0, remove_after + 1);
						var res = JSON.parse(result);
					} else {
						console.log("This is not valid JSON format")
					}

					if (res.flag == "true" || res.flag == true) {

						$(".sync_cat_label").hide();
						$(".sync_cat_result").html(res.count + ' Categories have been synced to SORTD').show();
						syncArticles(site_url, nonce, res.total_posts);

					} else {

						/*if(res.response.status == false && res.response.error.errorCode){
								Swal.fire({
								  icon: 'error',
								  text: res.response.error.message,//'Articles could not be synced !!!',
								 timer: 3000
								});	
						} else {*/
						swal({
							icon: 'error',
							text: 'Categories could not be synced !!!',
							timer: 3000
						});
						//}

					}

				}
				catch (e) {
					console.log(e);
					return false;
				}
			}

		});

	});


function syncArticles(site_url, nonce, total_posts) {

		var category_quota = $("#category_quota").val();
		var article_quota = $("#article_quota").val();
		var date_till = $("#date_till").val();
		var synced_posts = 0;
		var counter = 0;
		var post_count = 10;
		var num_loops = Math.ceil(total_posts / post_count);

		$(".sync_article_label").html('Starting to sync ' + total_posts + ' articles.....').show();
		$(".progress-bar").attr('aria-valuemax', total_posts);
		$(".prgDiv").show();


		for (let page = 0; page < num_loops; page++) {

			$.ajax({
				//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
				url: sortd_ajax_obj_setup.ajax_url,
				data: { 'action': 'syncArticles', 'page': page, 'post_count': post_count, 'date_till': date_till, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
				type: 'post',
				success: function (result) {
					console.log(result);
					try {
						var remove_after = result.lastIndexOf('}');
						if (remove_after != -1) {
							var dataresult = result.substring(0, remove_after + 1);
							var res = JSON.parse(result);
						} else {
							console.log("This is not valid JSON format")
						}

						if (res.flag == "true" || res.flag == true) {

							//$(".sync_article_label").hide();
							synced_posts += res.count;

							//	console.log(synced_posts,"dsdsds",res.flag);
							var percent = (synced_posts / total_posts) * 100;
							$(".progress-bar").attr('style', 'width:' + percent + '%');
							$(".sync_article_result").html(synced_posts + '/' + total_posts + ' Articles have been synced to SORTD').show();

							if (counter == (num_loops - 1)) {
								setTimeout(function () {
									configsetup(site_url);
								}, 1000);
							}
							counter++;

							$(".sync_article_label").hide();

							setTimeout(function () {
								$(".minute_label").hide();
							}, 5000);

						} else {

							/*if(res.response.status == false && res.response.error.errorCode){
							Swal.fire({
								  icon: 'error',
								  text: res.response.error.message,//'Articles could not be synced !!!',
								 timer: 3000
								});
						} else {*/
							swal({
								icon: 'error',
								text: 'Articles could not be synced !!!',
								timer: 3000
							});

							//}
						}

					}
					catch (e) {
						
						counter++;
						if (counter == (num_loops - 1)) {
								setTimeout(function () {
									configsetup(site_url);
								}, 1000);
							}
						console.log(e);
						console.log(counter)
						return false;
					}
				}

			});
			
				setTimeout(function(){
				
			  }, 2000);
		}

	}





	function configsetup(site_url) {

		var site_title = $("#sitetitle").val();
		var site_description = $("#sitedescription").val();

		$.ajax({
	  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
	  		url: sortd_ajax_obj_setup.ajax_url,
	  		data : {'action':'configbuiltup','site_title':site_title,'site_description' : site_description,'sortd_nonce' : sortd_ajax_obj_setup.nonce},
	  		type : 'post',
	  		beforeSend:function(){
	  				$(".infoAlrt").show();
	  			
	  		},
	  		success: function(result){

	  				try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  	var dataresult =  result.substring(0,remove_after +1);
									 	var res = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 

								if(res.status == true){


									setTimeout(function () {
										$(".infoAlrt").hide();
										$(".prgDiv").css('display','none');
							     	$(".setupAction").show();
							     	$(".container_confetti").show();

							     	$(".finalsetup").show();
							     	$(".inprogresssetup").hide();
							     	
							  	},3000);

								

									setTimeout(function () {
										var demo_host = $("#demohost").val();
								$("#loader").show();
								$(".spanpreviewloader").show();

								$(".mobDevice").show();
								$('#imgscreenshot_iframe').attr('src', demo_host);
								$(".spanpreviewloader").hide();
								$("#loader").hide();
								$(".current").hide();
									    $('html, body').animate({
								        'scrollTop' : $(".setupAction").position().top
								    });
							  	},3000);

						
						  
						     


								} else {
										swal({
															  icon: 'error',
															  text: res.error.message,//'Articles could not be synced !!!',
															 timer: 3000
															});
								}
			    			

	    				}   catch (e)
						
						{
							
							console.log(e);
							return false;
						} 			
	  		}
		});	

}




	$(".sortdbulkaction").click(function () {

		var postidbulk = [];

		var synccount = 0;
		var request_count = 0;

		var checkbox = $('[name="post[]"]:checked').length;//document.getElementsByName('post[]')
		var ln = checkbox;

		if(ln == 0){

			$(".bulk_validation").show();
			return false;
		}

		$('input[name="post[]"]:checked').each(function () {
				$(".bulk_validation").hide();

			$.ajax({

				url: sortd_ajax_obj_setup.ajax_url,
				data: { 'action': 'syncArticlesInBulk', 'page': 0, 'post_count': synccount, 'postids': this.value, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
				type: 'post',
				success: function (result) {

					//console.log(result);return false;

					// 	try
					// {		

					$(".bulkactionloader").show();
					// 		var remove_after= result.lastIndexOf('}');
					// 		if(remove_after != -1){
					// 		  var dataresult =  result.substring(0,remove_after +1);
					var res = JSON.parse(result);
					// 		}else {
					// 		  console.log("This is not valid JSON format")
					// 		}
					request_count++;
					if (res.status == "true" || res.status == true) {

						// 				//location.reload();


						synccount++;
						$.ajax({

							url: sortd_ajax_obj_setup.ajax_url,
							data: { 'action': 'updateBulkCOunt', 'post_count': synccount, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
							type: 'post',
							success: function (result) {

								console.log(result, "lklk");

							}

						});





					} else {

						console.log("failed");
						swal({
							icon: 'error',
							text: 'Articles could not be synced !!!',
							timer: 5000
						});

						$(".bulkactionloader").hide();

					}

					// 	}  
					// 	catch (e) {																
					// 		console.log(e);
					// 		return false;



					if (ln == request_count) {

						$.ajax({

							url: sortd_ajax_obj_setup.ajax_url,
							data: { 'action': 'updateBulkFlag', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
							type: 'post',
							success: function (result) {

								$(".bulkactionloader").hide();
								//location.reload();
								setTimeout(function(){
								   window.location.reload(1);
								}, 5000);

							}

						});


					}


					// } 	
				}

			});


		});




		
	});


	$(".themebtn").click(function () {

		var templateid = $(this).attr('id');

		let adminurl = $("#adminurl").val();

		var delay = 3000;

		window.leavepageflag = true;
		//console.log(adminurl);return false;


		$.ajax({

			url: sortd_ajax_obj_setup.ajax_url,
			data: { 'action': 'saveTemplate', 'templateId': templateid, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
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

					if (res.response.status == "true" || res.response.status == true) {
						//console.log(result);return false;

						if (res.flag == 1) {
							var url = adminurl + 'admin.php?page=sortd_setup'
							setTimeout(function () { window.location = url; }, delay);

						} else {
							var url = adminurl + 'admin.php?page=sortd-manage-settings&section=sortd_manage_templates&leavepage=true'
							setTimeout(function () { window.location = url; }, delay);
						}




					} else {


						swal({
							icon: 'error',
							text: 'template not updated !!!',
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
	});


	$("#demohostclick").click(function () {
		window.leavepageflag = true;

	});


	$(window).load(function () {

		var url_string = window.location.href;
		var url = new URL(url_string);
		var page = url.searchParams.get("page");
		var section = url.searchParams.get("section");
		var paramsconfig = url.searchParams.get("parameter");
		var leavepage = url.searchParams.get("leavepage");



		if (section == 'sortd_manage_templates') {

			$.ajax({

				url: sortd_ajax_obj_setup.ajax_url,
				data: { 'action': 'getTemplateId', 'sortd_nonce': sortd_ajax_obj_setup.nonce },
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
						var projectId = res.data.template_id;

						$(".themeselecteddiv_" + projectId).addClass("activetheme");
					}
					catch (e) {
						console.log(e);
						return false;
					}
				}

			});





		}

		if(page == 'sortd-settings'){

		$.ajax({
			  		
			url: sortd_ajax_obj_setup.ajax_url,
			data : {'action':'getAlertCount','sortd_nonce' : sortd_ajax_obj_setup.nonce},
			type : 'post', 
			success: function(result){
					
					//console.log(result);return false;
				try
					{													
							var remove_after= result.lastIndexOf('}');
							if(remove_after != -1){
							var dataresult =  result.substring(0,remove_after +1);
								var res = JSON.parse(result);
							}else {
							console.log("This is not valid JSON format")
							}
							//if(res.data.count !== 0){
								//$(".notifycount").text(res.data.count)
							//} 
							
				}  
				catch (e) {																
							console.log(e);
							return false;
					} 	
			}
	
		});

	}


		if (page == 'sortd_notification') {


			$('html, body').animate({
				scrollTop: $("#notifyform").offset().top - 80
			}, 1000);
		}



		if (section == 'sortd_config' && paramsconfig == 'home') {
			window.leavepageflag == false
			var referrer = document.referrer;

			if (referrer == url.origin + url.pathname + '?page=sortd-settings') {

				setTimeout(function () { $('#navlink_Home_Screen_Ads').click(); }, 500);


				$("#navlink_Home_Screen_Ads").addClass("active").siblings().removeClass('active');
			}




		}


		if (section == 'sortd_config' && paramsconfig == 'category') {
			window.leavepageflag == false
			var dynamicId = paramsconfig;

			var referrer = document.referrer;

			if (referrer == url.origin + url.pathname + '?page=sortd-settings') {


				setTimeout(function () { $('#navlink_Category_Ads').click(); }, 500);

				$("#navlink_Category_Ads").addClass("active").siblings().removeClass('active');

			}




		}

		if (section == 'sortd_config' && paramsconfig == 'article') {
			window.leavepageflag == false
			var referrer = document.referrer;

			if (referrer == url.origin + url.pathname + '?page=sortd-settings') {

				setTimeout(function () { $('#navlink_Article_Ads').click(); }, 500);




				$("#navlink_Article_Ads").addClass("active").siblings().removeClass('active');

			}




		}



		var referrer = document.referrer;

		if (section == 'sortd_config' || section == 'sortd_setup' || section == 'sortd_manage_templates' || section == 'sortd_redirection' || section == 'sortd_credential_settings' || section == 'sortd_manage_categories' || page == 'sortd_notification') {




			if (section == 'sortd_redirection') {


				var hiddenhost = $("#hiddenhost").val();

				if(leavepage == 'false'){
					window.leavepageflag = true;
				}

				console.log(window.leavepageflag);
				window.addEventListener("beforeunload", function (e) {
					if (window.leavepageflag == false) {

						var confirmationMessage = 'It looks like you have been editing something. '
							+ 'If you leave before saving, your changes will be lost.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

					}
				});




			} else if (section == 'sortd_credential_settings') {


				var hiddenhost = $("#hiddenhost").val();

				//console.log(hiddenhost);return false;




				window.addEventListener("beforeunload", function (e) {

					if (window.leavepageflag == false) {
						var confirmationMessage = 'It looks like you have been editing something. '
							+ 'If you leave before saving, your changes will be lost.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

					}
				});




			} else if (section == 'sortd_config') {

				

				if (referrer == url.origin + url.pathname + '?page=sortd-settings') {
					
					if (paramsconfig === null) {
						window.leavepageflag = false;
					} 
					// else {
					// 	window.leavepageflag = true;
					// }

				} else {

					
					if(paramsconfig === null){
						window.leavepageflag = false;
					} else{
						window.leavepageflag = true;
					}
				
				}
				


			
				window.addEventListener("beforeunload", function (e) {
					if (window.leavepageflag == false) {

						var confirmationMessage = 'It looks like you have been editing something. '
							+ 'If you leave before saving, your changes will be lost.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

					}
				});






			} else if (page == 'sortd_notification') {

				window.addEventListener("beforeunload", function (e) {
					if (window.leavepageflag == false) {

						var confirmationMessage = 'It looks like you have been editing something. '
							+ 'If you leave before saving, your changes will be lost.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

					}
				});






			} else if (section == 'sortd_manage_categories') {

				window.addEventListener("beforeunload", function (e) {
					if (window.leavepageflag == false) {

						var confirmationMessage = 'It looks like you have been editing something. '
							+ 'If you leave before saving, your changes will be lost.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

					}
				});






			} else if (section == 'sortd_manage_templates') {

				if(leavepage == 'false'){
					window.leavepageflag = true;
				}


				window.addEventListener("beforeunload", function (e) {
					if (window.leavepageflag == false) {

						var confirmationMessage = 'It looks like you have been editing something. '
							+ 'If you leave before saving, your changes will be lost.';

						(e || window.event).returnValue = confirmationMessage; //Gecko + IE
						return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

					}
				});






			} else {

				window.addEventListener("beforeunload", function (e) {


					var confirmationMessage = 'It looks like you have been editing something. '
						+ 'If you leave before saving, your changes will be lost.';

					(e || window.event).returnValue = confirmationMessage; //Gecko + IE
					return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
				});
			}




		}


	});


$(".alertbutton").click(function(){
	//$(".sortdalerts").show();
	$(".sortdalerts").toggle(); 
	 $(".notifycount").hide();
});

  $(document).mouseup(function(e) 
{
    var container = $(".sortdalerts");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        container.hide();
         $(".notifycount").hide();
    }
});

$(".editpublic").click(function(){
	$(".editpublichostinput,.editdomaintick").show();
	$(".spanhost").hide();
	$(".editpublichostinput").attr('disabled',false);
	$(".editpublic").hide();
	$(".crosspublichosticon").show();

});

  $(".editpublichostinput").keyup(function(){
        // Getting the current value of textarea
        var currentText = $(this).val();
        
        // Setting the Div content
        $(".domaintypespan").text(currentText);
    });
$(".crosspublichosticon").click(function(){
	$(".spanhost,.editpublic").show();
	$(".crosspublichosticon,.editpublichostinput,.editdomaintick").hide();
	$(".domaintypespan").text($(".spanhost").text());
	$(".editpublichostinput").val($(".spanhost").text());
	// $(".crosspublichosticon").hide();
	// $(".editpublichostinput").hide();
	$(".generate_ssl").prop('disabled',false);
	// $(".editdomaintick").hide();
});

$(".editdomaintick").click(function(){
	var publichostval = $(".editpublichostinput").val();

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

			url: sortd_ajax_obj_setup.ajax_url,
			data: { 'action': 'updatePublicHost','domain':publichostval, 'sortd_nonce': sortd_ajax_obj_setup.nonce },
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

					// 		$(".crosspublichosticon").click(function(){
					// 	$(".spanhost,.editpublic").show();
					// 	$(".crosspublichosticon,.editpublichostinput,.editdomaintick").hide();
					// 	$(".domaintypespan").text(publichostval);
					// 	$(".editpublichostinput").val(publichostval);
					// 	// $(".crosspublichosticon").hide();
					// 	// $(".editpublichostinput").hide();
					// 	// $(".editdomaintick").hide();
					// });
						
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
});



	// Check

})(jQuery);