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
        const oneclick = {
            
            previewWebsite : function(){
                
                let demoHost = $("#demohost").val();
				$("#loader").show();
				$(".spanpreviewloader").show();
				$(".mobDevice").show();
				$('#imgscreenshot_iframe').attr('src', demoHost);
				$(".spanpreviewloader").hide();
				$("#loader").hide();
                
            },

            syncCategories : function(){
                let siteUrl = $(this).attr('data-siteurl');
              
				let categoryQuota = $("#category_quota").val();
				let articleQuota = $("#article_quota").val();
				let dateTill = $("#date_till").val();
				$('.one_click').prop('disabled', true);

				$(".sync_cat_label").show();
				$(".minute_label").show();

				$(".inprogresssetup").show();
				$(".startsetup").hide();

				$.ajax({
					url: sortd_ajax_obj_oneclick.ajax_url,
					data: { 'action': 'sortd_sync_relevant_categories', 'category_quota': categoryQuota, 'article_quota': articleQuota, 'date_till': dateTill, 'sortd_nonce': sortd_ajax_obj_oneclick.nonce },
					type: 'post',
					success: function (result) {
				//	console.log(result);return false;
						try {
							let remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								let dataresult = result.substring(0, remove_after + 1);
								let response = JSON.parse(dataresult);
							
								if (response.flag == "true" || response.flag == true) {

									$(".sync_cat_label").hide();
									$(".sync_cat_result").html(response.count + ' Categories have been synced to SORTD').show();
									oneclick.syncArticles(siteUrl, response.total_posts);

								} else {

									swal({
										icon: 'error',
										text: 'Categories could not be synced !!!',
										timer: 3000
									});
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
			syncedPosts : 0,
			skipFlag : false,
			resyncFlag : false,
			resyncCounter : 0,


            syncArticles : async function(siteUrl, totalPosts){
                
                let dateTill = $("#date_till").val();
				//let syncedPosts = 0;
				let counter = 0;
				let postCount = 10;
				let numLoops = Math.ceil(totalPosts / postCount);
				let percent = 0;

				$(".sync_article_label").html('Starting to sync ' + totalPosts + ' articles.....').show();
				$(".progress-bar").attr('aria-valuemax', totalPosts);
				$(".prgDiv").show();


				for (let page = 0; page < numLoops; page++) {

					
					if(page > 0){
						$(".skiporcontinue").show();
					}

					if(oneclick.skipFlag == true){
						$(".skiporcontinue").hide();
						break;
					} else if(oneclick.resyncFlag == true){
						page = -1;
							oneclick.resyncFlag = false;
							oneclick.syncedPosts = 0;
						if(oneclick.resyncCounter > 3){
							$(".retrysync").attr("disabled",true);
							$(".retrysync").css("color","grey");
							$(".skipfornow").show();
							//console.log("resync loop break");
							//break;
						} 
							
							console.log(oneclick.syncedPosts,"resync loop");
							$(".sync_article_result").html(oneclick.syncedPosts + '/' + totalPosts + ' Articles have been synced to SORTD').show();
							$(".progress-bar").attr('style', 'width:' + 0 + '%');
							$(".skiporcontinue").hide();
						
						

						
					} else {

						const response = await oneclick.postsSync(page,postCount,dateTill,counter,numLoops,siteUrl,totalPosts)
						counter = response;
					
					
					}

						
				}

			

			
				oneclick.configSetup(siteUrl);
				oneclick.resyncCounter = 0;

            },
		
			postsSync : function (page,postCount,dateTill,counter,numLoops,siteUrl,totalPosts){
				return new Promise((resolve,reject)=>{
					const response = oneclick.managePostSync(page,postCount,dateTill,counter,numLoops,siteUrl,totalPosts);
					return resolve(response);
				})
			},

			managePostSync : function(page,postCount,dateTill,counter,numLoops,siteUrl,totalPosts){

			return new Promise((resolve,reject)=>{

				$.ajax({
					url: sortd_ajax_obj_oneclick.ajax_url,
					data: { 'action': 'sortd_sync_relevant_articles', 'page': page, 'post_count': postCount, 'date_till': dateTill, 'sortd_nonce': sortd_ajax_obj_oneclick.nonce },
					type: 'post',
					success: function (result) {
						
						try {
							let remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								let dataresult = result.substring(0, remove_after + 1);
								let response = JSON.parse(result);

								if (response.flag == "true" || response.flag == true) {
									oneclick.syncedPosts += response.count;

								
									let percent = (oneclick.syncedPosts / totalPosts) * 100;
									$(".progress-bar").attr('style', 'width:' + percent + '%');
									$(".sync_article_result").html(oneclick.syncedPosts + '/' + totalPosts + ' Articles have been synced to SORTD').show();
									
							
									counter++;

									$(".sync_article_label").hide();

									setTimeout(function () {
										$(".minute_label").hide();
									}, 5000);

								} else {
									counter++;
								
								}

							} else {
								console.log("This is not valid JSON format")
							}
							return resolve(counter);


						}catch (e) {
							counter++;
								return resolve(counter);

						}

						
					}

				});
			})

			},

            configSetup : function(){

				
                let siteTitle = $("#sitetitle").val();
				let siteDescription = $("#sitedescription").val();

				$.ajax({
					url: sortd_ajax_obj_oneclick.ajax_url,
					data : {'action':'sortd_build_default_config','site_title':siteTitle,'site_description' : siteDescription,'sortd_nonce' : sortd_ajax_obj_oneclick.nonce},
					type : 'post',
					beforeSend:function(){
						$(".infoAlrt").show();
					},
					success: function(result){
							try{
									
								let remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
									let dataresult =  result.substring(0,remove_after +1);
									let res = JSON.parse(result);
								

										if(res.status == true){
											$("#toplevel_page_sortd-settings ul").children().hide();
											$(".current").show();
											setTimeout(function () {
												$(".infoAlrt").hide();
												$(".prgDiv").css('display','none');
												$(".setupAction").show();
												$(".container_confetti").show();

												$(".finalsetup").show();
												$(".inprogresssetup").hide();
											
											},3000);

										

											setTimeout(function () {
												let demoHost = $("#demohost").val();
												$("#loader").show();
												$(".spanpreviewloader").show();

												$(".mobDevice").show();
												$('#imgscreenshot_iframe').attr('src', demoHost);
												$(".spanpreviewloader").hide();
												$("#loader").hide();
												//$(".current").hide();
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
								}else {
									console.log("This is not valid JSON format")
								} 

							}catch (e){
								console.log(e);
								return false;
							} 			
					}
				});	
            },


			resyncArticles : function(){
			
				oneclick.resyncFlag = true;
				oneclick.resyncCounter++;
			},
			skipOneClick : function(){
				oneclick.skipFlag = true;
			},
			pageSpeedInsights : function(){

				$(".NextSpeedBtn").attr("disabled",true);

				let site_url = $("#site_url_hidden").val();
				let redirect_url = $("#hiddenpageinsightsnonce").val();
				location.href = redirect_url;

			
				
			}
			
        }
        
		$(".one_click").click(oneclick.syncCategories);
		$(".skipfornow").click(oneclick.skipOneClick);
		$(".retrysync").click(oneclick.resyncArticles);
		$(".NextSpeedBtn").click(oneclick.pageSpeedInsights);
       

	
       

})( jQuery );









