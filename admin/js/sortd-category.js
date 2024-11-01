(function( $ ) {
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
        
        const category ={
            categoryList:[],
			unsyncCatId:'',
			taxonomytypeslugCount:'category',
        	// syncTermCount : 0,
            syncCategory : function(){

            	var wp_domain = $(this).attr('data-wp_domain');
				var project_slug = $(this).attr('data-project_slug');
				var current_user = $(this).attr('data-current_user');

				if (typeof gtag === 'function') {
					gtag('event', 'sortd_action', {
						'sortd_page_title': 'sortd_manage_categories',
						'sortd_feature': 'Sync/Un-Sync Category',
						'sortd_domain': wp_domain,
						'sortd_project_slug': project_slug,
						'sortd_user': current_user
					});
				}
                
                let id = $(this).attr('id');
				category.unsyncCatId=id;
				let parent_id = $(this).data('parent');
                let typeId = $(".catDynamic_"+id).attr('data-sortdindex');
                let taxonomytypeslug = $(this).attr('data-taxonomytypeslug');



              	    category.taxonomytypeslug = taxonomytypeslug
                let flag = false;
                if($(this).is(":checked")) {
                      flag = true;
                      typeId = 0;
                } 

				

				let after_cat_id = null;

                let siteUrl = $("#siteurl").val();
				var term_count = $('#'+taxonomytypeslug+'_count').attr('value');
				
				// category.syncTermCount =parseInt( $('#'+taxonomytypeslug+'_count').attr('value'));

				// console.log(category.syncTermCount,'1');
				if(flag == false){
					
					$.ajax({
						url: sortd_ajax_obj_category.ajax_url,
						data : {'id':id,'action':'get_all_heirarchy_cat_children','taxonomytypeslug':taxonomytypeslug,'sortd_nonce' : sortd_ajax_obj_category.nonce},
						type : 'post', 
						success: function(result){
							let response = JSON.parse(result);
							
							$(".img"+id).hide();
							
							if(response.length == 0){
								
								$.ajax({
									url: sortd_ajax_obj_category.ajax_url,
									data : {'id':id,'action':'sortd_sync_unsync_category','flag' : false,'typeId':typeId,'sortd_nonce' : sortd_ajax_obj_category.nonce},
									type : 'post',
									success: function(result){
									
										let response = JSON.parse(result);

										console.log(response,"ttttesssstttt");
										if(response.response.error.errorCode == 503) {
											$(".taxSyncUnsyncNotice").html(response.response.error.message);
											$(".taxSyncUnsyncNotice").show();
											setTimeout(function () {
												$('.taxSyncUnsyncNotice').fadeOut(500);
											}, 3000);
										} else {
											$("#catSpan_"+id).hide();
										
											//$(".succmsg").hide();
											$(".img"+id).hide();
											$('.sortcheckclass'+id).attr('checked',false);

									
										
											$(".succmsg img"+id).hide();
											$(".thtick,.succmsg"+id).hide();
										}
										// console.log(category.syncTermCount,'test');
										//var updated_count = parseInt(term_count) - 1;
										// var updated_count = category.syncTermCount - 1;
										// var activeCheckboxCount = $('.sortCatCheck:checked').length;
										// console.log(activeCheckboxCount);
										// $('#'+category.taxonomytypeslug+'_count').attr("value",activeCheckboxCount);
										// var updated_count = activeCheckboxCount.toString();
										// $('#'+category.taxonomytypeslug+'_count').text(updated_count);

										//category.syncTermCount =updated_count;
										


										// console.log(category.syncTermCount,'2');
										// console.log(updated_count,'3');
										// $('.sortCatCheck').on('change', function() {
											
										// });


									}
								});
								return false;
							} else {

								$.each(response,function(i,j){
	
									$('.sortcheckclass'+j).attr("disabled",true);

									$(".thtick,.succmsg"+id).hide();
									


								
								});
								$.ajax({
									url: sortd_ajax_obj_category.ajax_url,
									data : {'id':response,'action':'check_for_synced','flag' : false,'typeId':typeId,'sortd_nonce' : sortd_ajax_obj_category.nonce},
									type : 'post', 
									success: function(result){
										
										let response = JSON.parse(result);
										console.log("EHUIFBUIRHUIHRUUOVNRJNVO", response);
										
										
										$(".img"+id).hide();
										if(response == false){
										$.ajax({
											url: sortd_ajax_obj_category.ajax_url,
											data : {'id':id,'action':'sortd_sync_unsync_category','flag' : false,'typeId':typeId,'sortd_nonce' : sortd_ajax_obj_category.nonce},
											type : 'post', 
											success: function(result){
												console.log("BIBYIBHIBHJVUV", result);
											$('.sortcheckclass'+id).attr('checked',false);
											$(".img"+id).hide();
											}
										});
										}
										if(response == true){
										
											$("#mi-modal").modal('show');

											$(document).click(function (e) {
											    if ($(e.target).is('#mi-modal')) {
											      $(".sortcheckclass"+id).prop('checked',true);
											 	  $('.sortCatCheck[data-parent="'+id+'"]').attr("disabled",false);

											    }

											      	id = undefined;

												});

											$("#modal-btn-no").click(function(){
												$(".sortcheckclass"+id).prop('checked',true);
												$('.sortCatCheck[data-parent="'+id+'"]').attr("disabled",false);

												id = undefined;

												console.log(id);

												category.getSyncCount();
																								
											});
										}
									
									}
								});
								return false;
								//$("#mi-modal").modal('show');
							}
						}

					});
				
						

				} else {
					var parent; 
					var catid ;
					var sync_flag ;
					$('.table tbody tr').each((index, tr)=> {
						parent = $(tr).data('parent');
						 catid =  $(tr).data('cat_id');
						sync_flag = $(tr).data('sync_flag');
						if(parent == parent_id){
							
							if(catid == id){
								return false;
							} else if(sync_flag == "1") {
								
								after_cat_id = catid; 
							}
						} 
				

					});
					
				
					let urlString  = location.href;
                    let url = new URL(urlString);
					var urlParams = url.searchParams;


					if(urlParams.has('taxonomy') == true ){


					
						parent = $('.sortcheckclass'+id).data('parent');
						catid =  $('.sortcheckclass'+id).data('cat_id');
						sync_flag = $('.sortcheckclass'+id).data('sync_flag');
					
						if(parent == parent_id){
							
							// if(catid == id){
								
							// 	return false;
							// } else 
							if(sync_flag == "1") {
								
								after_cat_id = catid; 
							}

						
						} 
		
					}

					
					var data = {'id':id,'action':'sortd_sync_unsync_category','flag' : flag,'typeId':typeId,'parent_id':parent,'after_cat_id':after_cat_id,'taxonomytypeslug':taxonomytypeslug,'sortd_nonce' : sortd_ajax_obj_category.nonce};
					//var term_count = $('#'+taxonomytypeslug+'_count').attr('value');
					
					$.ajax({
						url: sortd_ajax_obj_category.ajax_url,
						data : data, //{'id':id,'action':'sortd_sync_unsync_category','flag' : flag,'typeId':typeId,'sortd_nonce' : sortd_ajax_obj_category.nonce},
						type : 'post', 
						success: function(result){
							let response = JSON.parse(result);
							
							console.log(response);
							
							
							if((response.flag == "true" || response.flag == true)){

								if(response.response.status == false && (response.response.error.errorCode != 408 || response.response.error.errorCode != 503) && (response.response.error.errorCode == 1004 || response.response.error.errorCode == 1005)){
									location.href = siteUrl+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";
								}else if(response.response.status == false && (response.response.error.errorCode == 408 || response.response.error.errorCode == 503)){
									$(".taxSyncUnsyncNotice").html(response.response.error.message);
									$(".taxSyncUnsyncNotice").show();
									setTimeout(function () {
										$('.taxSyncUnsyncNotice').fadeOut(500);
									}, 3000);
								} else {

									
									$(".catDynamic_"+id).attr('data-sortdindex',response.response.data._id);
									$("#catSpan_"+id).show();
									$(".thtick,.succmsg"+id).show();

									// var activeCheckboxCount = $('.sortCatCheck:checked').length;
									// console.log(activeCheckboxCount);
									// $('#'+taxonomytypeslug+'_count').attr("value",activeCheckboxCount);
									// var updated_count = activeCheckboxCount.toString();
									// $('#'+taxonomytypeslug+'_count').text(updated_count);
									//var updated_count = parseInt(term_count) + 1;

									// var updated_count = category.syncTermCount + 1;

									// console.log(category.syncTermCount,'5');


									// category.syncTermCount = updated_count;

									// console.log(updated_count,'6');

									// console.log(updated_count,"teeetet");
									// $('#'+taxonomytypeslug+'_count').attr("value",updated_count);
									// updated_count = updated_count.toString();
									// $('#'+taxonomytypeslug+'_count').text(updated_count);

									$("#tr-"+id).attr('data-sync_flag','1');

									$('.sortcheckclass'+id).attr('data-sync_flag','1');
									$('.sortcheckclass'+id).attr('checked',true);
									

									
									$.ajax({

										url: sortd_ajax_obj_category.ajax_url,
										data : {'id':id,'action':'get_cat_children','taxonomy_slug':taxonomytypeslug,'sortd_nonce' : sortd_ajax_obj_category.nonce},
										type : 'post', 
										success: function(result){
											let response = JSON.parse(result);
										
											$.each(response,function(i,j){
												
												$('.sortcheckclass'+j.term_id).attr("disabled",false);
												
												
											});
										}
									});
								}
								
							} else if(response.response.error.errorCode == 503) {
								
							}
						}

					});
				}

				category.getSyncCount();

				return false;

            },
        
            appendCategoriesHtml : function(catid, name, alias,content_type,parent_id,tax_slug){
                let caturl = $("#pluginurlpath").val();

				let html = '';
				if(content_type == 5){
					var classnameMainCat = 'weblinkeditmain';
				 } else {
					 	var classnameMainCat = '';
				 }
				html += `<input type="hidden" id="taxonomy_slug_hidden" value="${tax_slug}"><li data-parent_item="${parent_id}" id="menuItem_` + catid + `" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded licatreorderrename"><div class="menuDiv ${classnameMainCat} ">
								<span  contenteditable="true" title="Click to show/hide children" class="disclose ui-icon-sort ui-icon-minusthick">
								
								</span>`;

						if(content_type == 5){
							html +=	`<span class="weblinkspan"><i class="bi bi-link-45deg"></i></span>`;
						}
								
							html += `<span id="heading_`+ catid + `" class="heading_name` + catid + ` headingspanclass">` + name + `</span>  <span id="heading_` + catid + `" class="heading_alias` + catid + ` headingspanclass">` + alias + `</span><span class="editclickicon headingspanclass" title="Edit category name and alias" id="editicon` + catid + `"><span class="hovrTol">edit category name and alias</span><span class="sucMsg messagespansuccess` + catid + `" style = "display:none;color:green">Successfully renamed</span><span class="sucMsg maintain_rename${catid}" style = "display:none;color:red"></span><img src="${caturl}css/edit--v1.png"/></span> <input type="text" required name="editinput_name[name_` + catid + `]" class="editinput_` + catid + ` editclassspan" id="heading_` + catid + `" value="` + name + `" style="display:none"><input type="text" required  name="editinput_alias[alias_` + catid + `]" class="editinput_alias` + catid + ` editclassspan" id="heading_` + catid + `" value="` + alias + `" style="display:none"><span class="btn crossicon" id="btnclose` + catid + `" style="display:none"><i class="bi bi-x"></i></span><span class="btn tickicon" id="btntick` + catid + `" data-nonce="'.wp_create_nonce('rw-sortd-rename-cat-'` + catid + `).'" style="display:none"><i class="bi bi-check"></i><span class="wrngMsg messagespan` + catid + `" style = "display:none;color:red"></span></span></div>
							`;


				return html;
            },
        
            appendSubCategoriesHtml : function(catid, name, alias,content_type){
                let caturl = $("#pluginurlpath").val();
				let htmlsub = '';
				if(content_type == 5){
					var classnameMain = 'weblinkeditmainsub';
				 } else {
					 	var classnameMain = '';
				 }
				htmlsub += `<li id="menuItem_${catid}" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded licatreorderrename"><div class="menuDiv ${classnameMain}""><span contenteditable="true" title="Click to show/hide children" class="disclose ui-icon-sort ui-icon-minusthick">
	                             </span>`;
								 
								 if(content_type == 5){
									htmlsub +=	`<span class="weblinkspan"><i class="bi bi-link-45deg"></i></span>`;
								}
								htmlsub += `</span><span id="heading_${catid}" class="heading_name${catid} spansubhead">${name}</span>  <span id="heading_${catid}" class="heading_alias${catid} spansubhead">${name}</span><span class="editclickicon spansubhead editsubcat" id="editicon${catid}"><span class="sucMsg messagespansuccess${catid}" style = "display:none;color:green">Successfully renamed</span><span class="sucMsg maintain_rename${catid}" style = "display:none;color:red"></span><img title="Edit category name and alias" src="${caturl}css/edit--v1.png"/></span> <input type="text" name="editinput_name[name_${catid}]" class="editinput_${catid} editclassspan " id="heading_${catid}" value="${name}" style="display:none"><input type="text"  name="editinput_alias[alias_${alias}]" class="editinput_alias${catid} editclassspan" id="heading_${catid}" value="${alias}" style="display:none"><span class="btn crossicon" id = "btnclose${catid}" style="display:none"><i class="bi bi-x"></i></span><span class="btn tickicon" id="btntick${catid}" data-nonce="'.wp_create_nonce('rw-sortd-rename-cat-${catid}).'" style="display:none"><i class="bi bi-check"></i><span class="wrngMsg messagespan${catid}" style = "display:none;color:red"></span></span></div></li>`;

				return htmlsub;
            },
        
            renameCategory : function(){
                $(".tickicon").click(function () {
					let tickid = this.id;
					let getCategoryId = tickid.split('btntick');

					let categoryName = $(".editinput_" + getCategoryId[1]).val();
					let categoryAlias = $(".editinput_alias" + getCategoryId[1]).val();
			
					let categoryString = categoryName;
					let categoryNameSpace = categoryString.match(/^\s+$/);
					let categoryStringAlias = categoryAlias;
					let categoryAliasSpace = categoryStringAlias.match(/^\s+$/);
					let taxonomy_slug = $("#taxonomy_slug_hidden").val();

					if ((categoryNameSpace === null) && (categoryAliasSpace === null)) {
						if ((categoryName == '') && (categoryAlias != '')) {
							
							$(".messagespan" + getCategoryId[1]).show();
							$(".messagespan" + getCategoryId[1]).text('Name is required');

						} else if ((categoryName != '') && (categoryAlias == '')) {
							
							$(".messagespan" + getCategoryId[1]).text('Alias is required');
							$(".messagespan" + getCategoryId[1]).show();
						} else if ((categoryName == '') && (categoryAlias == '')) {
							
							$(".messagespan" + getCategoryId[1]).text('Name and alias is required');
							$(".messagespan" + getCategoryId[1]).show();
						} else {

							$.ajax({
								url: sortd_ajax_obj_category.ajax_url,
								data: { 'action': 'sortd_ajax_rename_category', 'id': getCategoryId[1], 'name': categoryName, 'alias': categoryAlias,'taxonomy_slug':taxonomy_slug, 'sortd_nonce': sortd_ajax_obj_category.nonce },
								type: 'post',
								success: function (result) {
								
									try {

										let remove_after = result.lastIndexOf('}');
										if (remove_after != -1) {
											let dataresult = result.substring(0, remove_after + 1);
											let response = JSON.parse(result);
											
											if (typeof gtag === 'function') {
												gtag('event', 'sortd_action', {
													'sortd_page_title': 'sortd_manage_categories',
													'sortd_feature': 'Rename Category',
													'sortd_domain': response.wp_domain,
													'sortd_project_slug': response.project_slug,
													'sortd_user': response.current_user
												});
											}
											
											$.each(response.responseCat.data.taxonomies, function (i, categoryDetails) {

												
												if (categoryDetails._id == getCategoryId[1]) {
												
														$(".heading_name" + getCategoryId[1]).text(categoryDetails.name);
													$(".heading_alias" + getCategoryId[1]).text(categoryDetails.alias);
												}

											});

											$(".editinput_" + getCategoryId[1]).hide();
											$(".editinput_alias" + getCategoryId[1]).hide();
											$(".heading_name" + getCategoryId[1]).show();
											$(".heading_alias" + getCategoryId[1]).show();
											$("#editicon" + getCategoryId[1]).show();
											$("#btnclose" + getCategoryId[1]).hide();
											$("#btntick" + getCategoryId[1]).hide();
											$(".messagespan" + getCategoryId[1]).hide();
											if(response.response.error && response.response.error.errorCode === 503) {
												$(".maintain_rename" + getCategoryId[1]).html(response.response.error.message);
												$(".maintain_rename" + getCategoryId[1]).show();
												$(".maintain_rename" + getCategoryId[1]).delay(3200).fadeOut(300);

											} else {
												$(".messagespansuccess" + getCategoryId[1]).show();
												$(".messagespansuccess" + getCategoryId[1]).delay(3200).fadeOut(300);
											}
											

										} else {
											console.log("This is not valid JSON format")
										}

										return false;
									} catch (e) {
										console.log(e);
										return false;
									}
								}
							});

						}
					} else {
						$(".messagespan" + getCategoryId[1]).show();
						$(".messagespan" + getCategoryId[1]).text('Space is not allowed');
					}

				});
            },
        
            editCategory : function(){
                $(".editclickicon").click(function () {

					let id = this.id;

					let splitIcon = id.split('editicon');

					$(".editinput_" + splitIcon[1]).show();
					$(".editinput_alias" + splitIcon[1]).show();
					$(".heading_name" + splitIcon[1]).hide();
					$(".heading_alias" + splitIcon[1]).hide();
					$("#editicon" + splitIcon[1]).hide();


					$("#btnclose" + splitIcon[1]).show();
					$("#btntick" + splitIcon[1]).show();

				});
            },
        
            cancelEditCategory : function(){
                $(".crossicon").click(function () {
					let idCross = this.id;
					let splitIconCross = idCross.split('btnclose');

					$(".editinput_" + splitIconCross[1]).hide();
					$(".editinput_alias" + splitIconCross[1]).hide();
					$(".heading_name" + splitIconCross[1]).show();
					$(".heading_alias" + splitIconCross[1]).show();
					$("#editicon" + splitIconCross[1]).show();


					$("#btnclose" + splitIconCross[1]).hide();
					$("#btntick" + splitIconCross[1]).hide();


				});
            },
        
            minMaxCategoryView : function(){
                let ns = $('ol.sortable').nestedSortable({
						forcePlaceholderSize: true,
						handle: 'div',
						helper: 'clone',
						items: 'li',
						opacity: .5,
						placeholder: 'placeholder',
						revert: 250,
						tabSize: 50,
						cursor: "move",
						tolerance: 'pointer',
						toleranceElement: '> div',
						maxLevels: 10,
						isTree: true,
						expandOnHover: 700,
						startCollapsed: false,
						disableParentChange : true,
						protectRoot:true,

					
					
						update: function (e,ui) {
							let hiered = $('ol.sortable').nestedSortable('toHierarchy', { startDepthCount: 0 });
							let newOrder = JSON.stringify(hiered);
							$("#category_order").val(newOrder);

						
							const cat_id = ui.item.attr('id').replace('menuItem_','');
							const parent_id = ui.item.attr('data-parent_item');
							let after_cat_id = null;

							
							$('.licatreorderrename').each((index, li)=> {
								const current_parent = $(li).data('parent_item');
							 	let current_cat_id =  $(li).attr('id')	
								 if(current_cat_id)	{
									current_cat_id = current_cat_id.replace('menuItem_','');
								
								 if(parent_id == current_parent){
											
				
											if(cat_id == current_cat_id){
												return false;
											} else  {
												
												after_cat_id = current_cat_id; 
											}
									} else if(parent_id == "null" && current_parent == null){
										if(cat_id == current_cat_id){
											return false;
										} else  {
											
											after_cat_id = current_cat_id; 
										}
										
									}
								 }						

							});

								let categoryOrder = $("#category_order").val();
								let categoryOrderOld = $("#category_order_old").val();
								let hiddenUrl = $("#hiddenurl").val();

								window.leavepageflag = true;

								$.ajax({
									url: sortd_ajax_obj_category.ajax_url,
									data: { 'action': 'sortd_ajax_save_reorder_category', 'cat_id':cat_id,'after_cat_id':after_cat_id,'category_order_old': categoryOrderOld, 'category_order': categoryOrder, 'sortd_nonce': sortd_ajax_obj_category.nonce },
									type: 'post',

									success: function (result) {
										let res = JSON.parse(result);
										if(res.status == false && res.error.errorCode == 503) {	
											$(".taxSyncUnsyncNotice").html(res.error.message);
											$(".taxSyncUnsyncNotice").show();
											setTimeout(function () {
												$('.taxSyncUnsyncNotice').fadeOut(500);
											}, 3000);
										} else {
											if (typeof gtag === 'function') {
												gtag('event', 'sortd_action', {
													'sortd_page_title': 'sortd_manage_categories',
													'sortd_feature': 'Reorder Category',
													'sortd_domain': res.wp_domain,
													'sortd_project_slug': res.project_slug,
													'sortd_user': res.current_user
												});
											}
										}
										
								
										
										
								
									}

								});
						},
						
				});

		
				$("ol.sortable").disableSelection();
				$('.disclose').on('click', function () {
					$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
					$(this).toggleClass('ui-icon-plusthick').toggleClass('ui-icon-minusthick');

				});
            },
            
            showSyncCategoryView : function(){


				category.categoryHtml ='',
				$(".categorysyncmanage").show();
				$(".categoryreordermanage").hide();
				$(".reor-renameCat").removeClass('manageCategory-active');
				$(this).addClass('manageCategory-active');
				$(".syncCat").attr("disabled",true);
				$(".reor-renameCat").attr("disabled",false);
				$(".syncCat").css('cursor', 'not-allowed');
				$(".reor-renameCat").css('cursor', 'pointer');
				let nonce = $("#nonce_input").val(); // Retrieve the nonce from the hidden input.

				
				
			

				window.leavepageflag = true;

				let url = "/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_categories&action=sync&_wpnonce="+nonce;
				window.history.pushState({}, document.title, url);
            },
            categoryHtml : '',
            showReorderRenameCategoryView : function(){
                let siteurl = $("#siteurl").val();
				let nonce = $("#nonce_input").val(); // Retrieve the nonce from the hidden input.
				$(".categorysyncmanage").hide();
				$(".categoryreordermanage").show();
				$(".syncCat").removeClass('manageCategory-active');
				$(this).addClass('manageCategory-active');
				$(".syncCat").attr("disabled",false);
				$(".reor-renameCat").attr("disabled",true);
				$(".reor-renameCat").css('cursor', 'not-allowed');
				$(".syncCat").css('cursor', 'pointer');

				window.leavepageflag = false;

				let url = "/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_categories&action=reorder&_wpnonce="+nonce;
				window.history.pushState({}, document.title, url);

				$.ajax({
					url: sortd_ajax_obj_category.ajax_url,
					data: { 'action': 'sortd_ajax_reorder_rename_category', 'sortd_nonce': sortd_ajax_obj_category.nonce },
					type: 'post',
					success: function (result) {
						
					category.categoryHtml = '';
						try {
							let remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								let dataresult = result.substring(0, remove_after + 1);
								let response = JSON.parse(result);

								

								var responseObject = JSON.parse(response.taxonomy_terms);	

								category.categoryList=responseObject.data.taxonomies;

								let taxomonomy_types = JSON.parse(response.taxonomy_types)

								if(response.status == false){
									$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+response.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
									$(".notice-error").delay(2000).fadeOut(500);
								} else {
									category.categoryHtml += '<div class="card_group taxCard_group">';

									$.each(taxomonomy_types.data.taxonomy_types,function(i,j){

										if(j.slug == 'category'){

											var classtaxname = 'newClassHighlight';
										} else {
											var classtaxname = '';
										}

                        category.categoryHtml += `<div class="catCrd-Tax">                           
                           <span id="taxid_${j.slug}" class="taxonomyclass_reorder ${classtaxname}" data-name="${j.name}" data-taxonomySlug="${j.slug}">${j.name}<b id='${j.slug}_count' value=${j.count}>${j.count}</b></span>
                        </div>`;
									});


									category.categoryHtml += '</div>';
									
									category.renderCategories(responseObject.data.taxonomies, null,'category');
										category.categoryHtml += '</li>';
    

										$('.catreorderrenameol').html(category.categoryHtml);

										category.renameCategory();

										category.editCategory();

										category.cancelEditCategory();

										category.minMaxCategoryView();
									}
							} else {
								console.log("This is not valid JSON format")
							}
						}
						catch (e) {
							console.log(e);
							return false;
						}
					}
				});
            },
			catArray :[],
            
            loadDefaults : function(){
                let url_string = location.href;
		        let url = new URL(url_string);
		        let action = url.searchParams.get("action");
				
                
                if(action == 'reorder'){
                    category.showReorderRenameCategoryView();

               
               
                }else if(action == 'sync'){
                    category.showSyncCategoryView();
                }


				var urlParams = url.searchParams;
				
				if(urlParams.has('taxonomy') == true && urlParams.get('taxonomy') == 'category' && !(action)){
					category.syncDataOnCategoryTaxonomy();

				
				}

					category.getSyncedTaxonomiesTypesList();
            },

			

			renderCategories :  (categories, parent_id,tax_slug)=>{
				
				
				categories.forEach(element => {
						if(!parent_id && !element.parent_id){
						
							category.categoryHtml +=category.appendCategoriesHtml(element._id, element.name, element.alias, element.content_type,null,tax_slug);
							category.categoryHtml += '<ol >';
							category.renderCategories(categories, element._id,tax_slug);
							category.categoryHtml += '</li></ol>';
						}else if(element.parent_id && element.parent_id._id == parent_id){
							category.categoryHtml +=category.appendCategoriesHtml(element._id, element.name, element.alias,element.content_type,element.parent_id._id,tax_slug);
							category.categoryHtml += '<ol>';
							category.renderCategories(categories, element._id);
							category.categoryHtml += '</li></ol>';
						}
				});
			 },

			 categoryUnsyncAllChildren : function(){
				$.ajax({
					url: sortd_ajax_obj_category.ajax_url,
					data : {'id':category.unsyncCatId,'action':'sortd_sync_unsync_category','flag' : false,'typeId':'','sortd_nonce' : sortd_ajax_obj_category.nonce},
					type : 'post', 
					success: function(result){
						let response = JSON.parse(result);
						//var term_count = $('#'+category.taxonomytypeslug+'_count').attr('value');
						// category.syncTermCount =parseInt( $('#'+category.taxonomytypeslug+'_count').attr('value'));
						
						// console.log(category.syncTermCount,'7');
						// var updated_count = parseInt(term_count) - 1;
						// $('#'+category.taxonomytypeslug+'_count').attr("value",updated_count);
						// updated_count = updated_count.toString();
						// $('#'+category.taxonomytypeslug+'_count').text(updated_count);
						
				
						if(response.flag === "false" || response.flag === false){
							
								console.log("BRVHIUHIURHUIHI",response);
							if(response.response.status == false && response.response.error.errorCode != 408 && (response.response.error.errorCode == 1004 || response.response.error.errorCode == 1005)){
							  
								location.href = siteUrl+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";
							}else if(response.response.status == false && (response.response.error.errorCode == 408 || response.response.error.errorCode == 503)){
							   
								// $('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+response.response.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
								// $(".notice-error").delay(2000).fadeOut(500);

								$(".taxSyncUnsyncNotice").html(response.response.error.message);
								$(".taxSyncUnsyncNotice").show();
								setTimeout(function () {
									$('.taxSyncUnsyncNotice').fadeOut(500);
								}, 3000);
							} else {

									$("#catSpan_"+category.unsyncCatId).hide();
									$("#tr-"+category.unsyncCatId).attr('data-sync_flag','0')
									$.each( response.response.data.cat_guid, function( i, l ){
			
									
										$('.table tbody tr').each((index, tr)=> {
											$(tr).children('td').each ((index, td) => {
												$(".catSyncHead_"+l).hide();
												$(".sortcheckclass"+l).prop("checked", false);
												$(".img"+l).hide();
											}); 
			
										});

									
									});
									$(".img"+category.unsyncCatId).hide();
			
										$.ajax({
											url: sortd_ajax_obj_category.ajax_url,
											data : {'id':category.unsyncCatId,'action':'get_all_heirarchy_cat_children','taxonomytypeslug': category.taxonomytypeslug,'sortd_nonce' : sortd_ajax_obj_category.nonce},
											type : 'post', 
											success: function(result){
												let response = JSON.parse(result);
											
			
												if(response.length == 0){
													return false;
												} else {

													// var updated_count = category.syncTermCount -(response.length+1)

													// category.syncTermCount = updated_count;

													// console.log(category.syncTermCount,'8');
													// console.log(updated_count,'9');
													// $('#'+category.taxonomytypeslug+'_count').attr("value",updated_count);
													// updated_count = updated_count.toString();
													// $('#'+category.taxonomytypeslug+'_count').text(updated_count);
												

												$.each(response,function(i,j){
													$('.sortcheckclass'+j).attr("disabled",true);


													console.log(response.length,"eee");
													//var updated_count = parseInt(term_count) - (response.length+1);

													
			
													
													$.ajax({
														url: sortd_ajax_obj_category.ajax_url,
														data : {'id':j,'action':'sortd_sync_unsync_category','flag' : false,'typeId':'','sortd_nonce' : sortd_ajax_obj_category.nonce},
														type : 'post', 
														success: function(result){
															
															
															
															$(".tags tr").find('input[name="catsortdcheckbox"]') .each(function(){
																var activeCheckboxCount = $('.sortCatCheck:checked').length;
																console.log(activeCheckboxCount);
																$('#'+category.taxonomytypeslug+'_count').attr("value",activeCheckboxCount);
																var updated_count = activeCheckboxCount.toString();
																$('#'+category.taxonomytypeslug+'_count').text(updated_count);
																
																
															
																$(".sortcheckclass"+j).prop("checked", false);
																$(".img"+j).hide();
															});
															
																$('.table tbody tr').each((index, tr)=> {

																	var activeCheckboxCount = $('.sortCatCheck:checked').length;
																	console.log(activeCheckboxCount);
																	$('#'+category.taxonomytypeslug+'_count').attr("value",activeCheckboxCount);
																	var updated_count = activeCheckboxCount.toString();
																	$('#'+category.taxonomytypeslug+'_count').text(updated_count);
					
																	$(tr).children('td').each ((index, td) => {
																		$(".catSyncHead_"+j).hide();
																		$(".sortcheckclass"+j).prop("checked", false);
																		$("#catSpan_"+j).hide();
																		$(".img"+j).hide();
																	}); 
				
																});
															
														}
													});
												});
											//$("#mi-modal").modal('show');
											}
											
											
											
										}
									});
									
									return false;
								
			
							}
			
						} 
					}
				});
			},
			syncDataOnCategoryTaxonomy : function(){

					$.ajax({
						url: sortd_ajax_obj_category.ajax_url,
						data : {'action':'get_categories','sortd_nonce' : sortd_ajax_obj_category.nonce},
						type : 'post', 
						async: false ,
						success: function(result){
							let response = JSON.parse(result);
							category.catArray=response.categories.data.categories;
						
							$.each(category.catArray,function(i,j){
								
								$(".tags tr").find('input[name="catsortdcheckbox"]') .each(function() {
									let ids = $(this).attr('id');
									$(this).prop("disabled",true);
									let data_parent = $(this).attr('data-parent');

									if( data_parent == 0){
										$(this).prop("disabled",false);
									}
									if( j.cat_guid == ids){
										$("#"+ids).prop("checked",true)
										$("#"+ids).attr("disabled",false)

										$.ajax({
											url: sortd_ajax_obj_category.ajax_url,
											data : {'id':ids,'action':'get_cat_children','taxonomy_slug':taxonomytypeslug,'sortd_nonce' : sortd_ajax_obj_category.nonce},
											type : 'post', 
											success: function(result){
												let response = JSON.parse(result);
											
												
											
												$.each(response,function(i,j){

													$(".tags tr").find('input[name="catsortdcheckbox"]') .each(function() {
														
														$('.sortcheckclass'+j.term_id).attr("disabled",false);
														//$(".img"+j.term_id).hide();
														
													});
													
												});
											}
										});
									}else {
										//$("#"+ids).prop("checked",false)
									}
								});
							});
							
						}
					});

				

					
					category.catArray = [];

			},

			categoryUrlRedirection: async function() {
	
				//let finalToggleValue = $('.categoryUrlRedirection').attr("data-update_flag");
				let finalToggleValue = $(".categoryUrlRedirection").prop('checked');
				console.log(finalToggleValue)
				let wp_domain = $(this).attr('data-wp_domain');
                let project_slug = $(this).attr('data-project_slug');
                let current_user = $(this).attr('data-current_user');

                if (typeof gtag === 'function') {
                    gtag('event', 'sortd_action', {
                        'sortd_page_title': 'sortd_manage_settings',
                        'sortd_feature': 'Update Category URL',
                        'sortd_domain': wp_domain,
                        'sortd_project_slug': project_slug,
                        'sortd_user': current_user
                    });
                }
				$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_category_url_redirection',
						'category_toggle_value': finalToggleValue,
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {
						let response = JSON.parse(result);
						if(response.status == false && response.error.errorCode == 503) {
							$(".taxSyncUnsyncNotice").html(response.error.message);
							$(".taxSyncUnsyncNotice").show();
							setTimeout(function () {
								$('.taxSyncUnsyncNotice').fadeOut(500);
							}, 3000);
							if(finalToggleValue == false) {
								$(".categoryUrlRedirection").prop('checked', true);
							} else {
								$(".categoryUrlRedirection").prop('checked', false);
							}
						}
					}                
	
				});
				
			},

			articleUrlRedirection: async function() {

			
				let finalToggleValue = $(".articleUrlRedirection").prop('checked');
				let wp_domain = $(this).attr('data-wp_domain');
                let project_slug = $(this).attr('data-project_slug');
                let current_user = $(this).attr('data-current_user');

                if (typeof gtag === 'function') {
                    gtag('event', 'sortd_action', {
                        'sortd_page_title': 'sortd_manage_settings',
                        'sortd_feature': 'Update Article URL',
                        'sortd_domain': wp_domain,
                        'sortd_project_slug': project_slug,
                        'sortd_user': current_user
                    });
                }
				$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_article_url_redirection',
						'article_toggle_value': finalToggleValue,
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {
						let response = JSON.parse(result);
						if(response.status == false && response.error.errorCode == 503) {
							$(".taxSyncUnsyncNotice").html(response.error.message);
							$(".taxSyncUnsyncNotice").show();
							setTimeout(function () {
								$('.taxSyncUnsyncNotice').fadeOut(500);
							}, 3000);
							if(finalToggleValue == false) {
								$(".articleUrlRedirection").prop('checked', true);
							} else {
								$(".articleUrlRedirection").prop('checked', false);
							}
						}
					}                
	
				});
				
			},

			canonicalUrlRedirection: async function() {

				

				let catCanonicalFlag = $(".categoryUrlCanonical").prop('checked');

				let wp_domain = $(this).attr('data-wp_domain');
                let project_slug = $(this).attr('data-project_slug');
                let current_user = $(this).attr('data-current_user');

                if (typeof gtag === 'function') {
                    gtag('event', 'sortd_action', {
                        'sortd_page_title': 'sortd_manage_settings',
                        'sortd_feature': 'Enable Canonical URL',
                        'sortd_domain': wp_domain,
                        'sortd_project_slug': project_slug,
                        'sortd_user': current_user
                    });
                }
	
				$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_canonical_url_redirection',
						'canonical_toggle_value': catCanonicalFlag,
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {
						let response = JSON.parse(result);
						if(response.status == false && response.error.errorCode == 503) {
							$(".taxSyncUnsyncNotice").html(response.error.message);
							$(".taxSyncUnsyncNotice").show();
							setTimeout(function () {
								$('.taxSyncUnsyncNotice').fadeOut(500);
							}, 3000);
							if(catCanonicalFlag == false) {
								$(".categoryUrlCanonical").prop('checked', true);
							} else {
								$(".categoryUrlCanonical").prop('checked', false);
							}
						}
					}                
	
				});
				
			},

			taxonomyTypeSave : function(){


				let post_name=$(this).attr('data-postname');
				let taxonomy_name=$(this).attr('data-taxonomyname');
				let taxonomy_slug=$(this).attr('data-taxonomyslug');
				let post_slug=$(this).attr('data-postslug');
				let wp_domain=$(this).attr('data-wp_domain');
				let project_slug=$(this).attr('data-project_slug');
				let current_user=$(this).attr('data-current_user');

				if (typeof gtag === 'function') {
					gtag('event', 'sortd_action', {
						'sortd_page_title': 'sortd_manage_taxonomies',
						'sortd_feature': 'Sync/Un-Sync Taxonomy',
						'sortd_domain': wp_domain,
						'sortd_project_slug': project_slug,
						'sortd_user': current_user
					});
				}
				var check_flag = $(this).prop('checked') ;
			

					$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_sync_taxonomy_type',
						'post_name': post_name,
						'taxonomy_name':taxonomy_name,
						'taxonomy_slug':taxonomy_slug,
						'post_slug':post_slug,
						'check_flag':check_flag,
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {

						let response = JSON.parse(result);

						if(check_flag == true){

							if(response.status == true){

								$("#succ_tax_msg"+taxonomy_slug).html("successfully synced");
								$("#succ_tax_msg"+taxonomy_slug).show();
							} else {
								if(response.error.errorCode === 503) {
									$("#succ_tax_msg"+taxonomy_slug).css('color','red');
									$("#succ_tax_msg"+taxonomy_slug).html(response.error.message);
									$("#succ_tax_msg"+taxonomy_slug).show();
								} else {
									$("#succ_tax_msg"+taxonomy_slug).css('color','red');
									$("#succ_tax_msg"+taxonomy_slug).html("not synced, error occured");
									$("#succ_tax_msg"+taxonomy_slug).show();
								}
								
							}

						} else {

							if(response.status == true){

								$("#succ_tax_msg"+taxonomy_slug).html("successfully unsynced");
								$("#succ_tax_msg"+taxonomy_slug).show();
							} else {
								if(response.error.errorCode === 503) {
									$("#succ_tax_msg"+taxonomy_slug).css('color','red');
									$("#succ_tax_msg"+taxonomy_slug).html(response.error.message);
									$("#succ_tax_msg"+taxonomy_slug).show();
								} else {
									$("#succ_tax_msg"+taxonomy_slug).css('color','red');
									$("#succ_tax_msg"+taxonomy_slug).html("not synced, error occured");
									$("#succ_tax_msg"+taxonomy_slug).show();
								}
								
							}

						}

						 setInterval(function () {
					       $("#succ_tax_msg"+taxonomy_slug).hide(); // show next div
					    }, 2000);
						
					}                
	
				});

			},

			getViewTaxonomies : function (){


				let taxonomy_slug = $(this).attr('data-taxonomySlug');
				let taxonomy_name = $(this).attr('data-name');
				if(taxonomy_name != 'Categories') {
					$("#categoryHeading").html(taxonomy_name);
				} else {
					$("#categoryHeading").html("Wordpress Categories");
				}
				
				$(".taxonomyclass").removeClass('newClassHighlight');
				$("#taxid_"+taxonomy_slug).addClass('newClassHighlight');

				category.taxonomytypeslugCount = taxonomy_slug;

				console.log(category.taxonomytypeslugCount)

					// category.syncTermCount  = 0;

				$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_get_taxonomy_view',
						
						'taxonomy_slug':taxonomy_slug,
						
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {

						

					
						if(result == 0){

							
							$(".categorytbody").html("<div class='taxonomynodatacat'>No data found</div>");

						} else {
							$(".categorytbody").html(result);
							
							
						}


						

						$(".sortCatCheck").on('change',category.syncCategory);

						$(".syncCat").click(category.showSyncCategoryView);

							$(".reor-renameCat").click(category.showReorderRenameCategoryView);
        
						    let url_string = location.href;
					        let url = new URL(url_string);
					        let action = url.searchParams.get("action");
							
			                
			                if(action == 'reorder'){
			                    category.showReorderRenameCategoryView();
			                }else if(action == 'sync'){
			                    category.showSyncCategoryView();
			                }


							var urlParams = url.searchParams;
							
							if(urlParams.has('taxonomy') == true && urlParams.get('taxonomy') == 'category' && !(action)){
								category.syncDataOnCategoryTaxonomy();

							
							}

									
					}                
	
				});
			} ,

				getSyncedTaxonomiesTypesList : function (){


				$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_get_synced_taxonomytype_list',
						
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {

						let response = JSON.parse(result);

						
					
						var checkboxes = $('.taxonomycontainer input[type="checkbox"]');
    					$.each(response.data.taxonomy_types,function(i,j){
						    
						    checkboxes.each(function() {

						    	let slug = $(this).attr('data-taxonomyslug');

						    			if(j.slug == slug){

						    				$(this).prop('checked',true);
						    			}
						    	});
						      
						    });
						
						
					}                
	
				});
			},

			getTerms : function(){


				var taxonomySlug = $(this).data('taxonomyslug');
				



				$.ajax({
	
					url: sortd_ajax_obj_category.ajax_url,
					data: {
						'action': 'sortd_get_synced_taxonomomies',
						'taxonomy_slug' : taxonomySlug,
						'sortd_nonce': sortd_ajax_obj_category.nonce
					},
					type: 'post',
					success: function(result) {

						let response = JSON.parse(result);


						
						category.categoryHtml = '';

		                var responseObject = response.taxonomy_terms; 

		                let taxomonomy_types = JSON.parse(response.taxonomy_types);	 



		                category.categoryHtml += '<div class="card_group taxCard_group">';                
		             	
		                $.each(taxomonomy_types.data.taxonomy_types,function(i,j){

		                	  var classNewActive = '';

		                	if(taxonomySlug === j.slug){

		                		classNewActive = 'newClassHighlight';
		                	}

	                        category.categoryHtml += `<div class="catCrd-Tax">	                           
	                           <span id="taxid_${j.slug}" class="taxonomyclass_reorder ${classNewActive}" data-name="${j.name}" data-taxonomySlug="${j.slug}">${j.name}<b id='${j.slug}_count' value=${j.count}>${j.count}</b></span>
	                        </div>`;
						});	

		           



						category.categoryHtml += '</div>';	                

		                if(responseObject.data.taxonomies.length == 0){

		                	
		                	 category.categoryHtml += ` <div class="taxonomynodatacat">No data found</div>`;

		                		$('.catreorderrenameol').html( category.categoryHtml);

		                } else {

		                	


						category.renderCategories(responseObject.data.taxonomies, null,taxonomySlug);
							category.categoryHtml += '</li>';

								$('.catreorderrenameol').html(category.categoryHtml);

		                }

		                	
						
							category.renameCategory();

							category.editCategory();

							category.cancelEditCategory();

							category.minMaxCategoryView();
						
					}                
	
				});


			},

			getSyncCount : function(){

			
				var activeCheckboxCount = $('.sortCatCheck:checked').length;
				console.log("TEST: ",'#'+category.taxonomytypeslugCount+'_count');
				$('#'+category.taxonomytypeslugCount.trim()+'_count').attr("value",activeCheckboxCount);
				var updated_count = activeCheckboxCount.toString();
				$('#'+category.taxonomytypeslugCount.trim()+'_count').text(updated_count);
	
				// Update the text to display the count
				$('#activeCheckboxCount').text('Active Checkboxes: ' + activeCheckboxCount);
			}

			
    	}

	$("#modal-btn-si").click(category.categoryUnsyncAllChildren);

	
	
	$(".categoryUrlRedirection").click(category.categoryUrlRedirection);
	$(".articleUrlRedirection").click(category.articleUrlRedirection);
	$(".categoryUrlCanonical").click(category.canonicalUrlRedirection);
	
	$(".sortCatCheck").on('change',category.syncCategory);

	
	$(document).on('change', '.categorysync', category.syncCategory);


        
	$(".syncCat").click(category.showSyncCategoryView);

	$(".reor-renameCat").click(category.showReorderRenameCategoryView);
        
        $( document ).ready(category.loadDefaults);

		$("form").on("submit", function(event){
			let urlString  = location.href;
			let url = new URL(urlString);
			var urlParams = url.searchParams;
			// var urlParams = DOMPurify.sanitize(urlParams);

				if(urlParams.has('taxonomy') == true && urlParams.get('taxonomy') == 'category' && !(action)){
					category.syncDataOnCategoryTaxonomy();

				
				}
		});
        
        $(".cancelRedorder").click(function(){
                location.href = location.href;
        });

		var modalConfirm = function(callback){
  
		
		  
			$("#modal-btn-si").on("click", function(){
			  callback(true);
			  $("#mi-modal").modal('hide');
			});
			
			$("#modal-btn-no").on("click", function(){
			  callback(false);
			  $("#mi-modal").modal('hide');
			});
		  };
		  
	
		  modalConfirm(function(confirm){
			if(confirm){
			  
			}else{
			  
			}
		  });


		  	$(document).on('click', '.taxonomyclass_reorder', category.getTerms);
			
		  	$(".custom_taxonomy_class").on('change',category.taxonomyTypeSave);
		  	$(".taxonomyclass").click(category.getViewTaxonomies)

		  
		
	
			  

		
})( jQuery );


