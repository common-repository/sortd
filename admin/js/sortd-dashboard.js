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
        
        const dashboard = {
            
            showHideAlerts : function(){
				$(".sortdalerts").toggle(); 
	            $(".notifycount").hide();
            },

			onmouseupalert : function(e){
				let container = $(".sortdalerts");
				// if the target of the click isn't the container nor a descendant of the container
				if (!container.is(e.target) && container.has(e.target).length === 0) {
					container.hide();
					$(".notifycount").hide();
				}
			},

            dynamicDataAppendOnChart : function(labels, dataval){
				/*======== 27. ACQUISITION3 ========*/
				let acquisition3 = document.getElementById("bar3");

				if (acquisition3 !== null) {
				  let acChart3 = new Chart(acquisition3, {
					// The type of chart we want to create
					type: "bar",

					// The data for our dataset
					data: {
					  labels: labels,//["2021-04-23", "2021-04-25", "2021-06-28", "2021-06-29", "2021-06-30", "2021-06-23", "2021-07-02"],
					  datasets: [
						{
						  label: "Referral",
						  backgroundColor: "rgb(76, 132, 255)",
						  borderColor: "rgba(76, 132, 255,0)",
						  data:dataval,// ["2","5","3","3","6","4","8"],
						  pointBackgroundColor: "rgba(76, 132, 255,0)",
						  pointHoverBackgroundColor: "rgba(76, 132, 255,1)",
						  pointHoverRadius: 3,
						  pointHitRadius: 30
						},
					   
					  ]
					},

					// Configuration options go here
					options: {
					  responsive: true,
					  maintainAspectRatio: false,
					  legend: {
						display: false
							}, 
							layout: {
					padding: {
					right: 50
					}
					},
					  scales: {
						xAxes: [
						  {
							gridLines: {
							  display: false
							},
							 ticks: 
							  {

								   autoSkip: false,
								   suggestedMin: 0,

							  }
						  
						  },

						],
						yAxes: [
						  {
							gridLines: {
							  display: true
							},
							ticks: {
							  beginAtZero: true,
							  stepSize: 2,
							  autoSkip: false,
							  fontColor: "#8a909d",
							  fontFamily: "Roboto, sans-serif",
							  max: 8,

							}
						  }
						]
					  },
					  tooltips: {}
					}
				  });
				  //document.getElementById("customLegend").innerHTML = acChart3.generateLegend();
				}
            },

            getPieChart : function(pieLabels,pieCounts,piecolors){
				 /*======== 11. DOUGHNUT CHART ========*/
				 let doughnut = document.getElementById("doChart");
				 if (doughnut !== null) {
				   let myDoughnutChart = new Chart(doughnut, {
					 type: "doughnut",
					 data: {
					   labels: pieLabels,
					   datasets: [
						 {
						   label:pieLabels,
						   data: pieCounts,
						   backgroundColor: piecolors,//["green", "red", "orange", "blue"],
						   borderWidth: 1,
						   
						 }
					   ]
					 },
					 options: {
					   responsive: true,
					   // cutoutPercentage: 90,
					   percentageInnerCutout : 90,
					   maintainAspectRatio: false,
					   legend: {
						 display: false,
					   },
					   tooltips: {
						 callbacks: {
						   title: function(tooltipItem, data) {
							 return "Stats : " + data["labels"][tooltipItem[0]["index"]];
						   },
						   label: function(tooltipItem, data) {
							 return data["datasets"][0]["data"][tooltipItem["index"]];
						   }
						 },
						 titleFontColor: "#888",
						 bodyFontColor: "#555",
						 titleFontSize: 12,
						 bodyFontSize: 14,
						 backgroundColor: "rgba(256,256,256,0.95)",
						 displayColors: true,
						 borderColor: "rgba(220, 220, 220, 0.9)",
						 borderWidth: 2
					   }
					 }
				   });
				 }
            },

			getPieChartWebstory : function(pieLabels,pieCounts,piecolors){
				/*======== 11. DOUGHNUT CHART ========*/
				let doughnut = document.getElementById("WebStoryChart");
				if (doughnut !== null) {
				  let myDoughnutChart = new Chart(doughnut, {
					type: "doughnut",
					data: {
					  labels: pieLabels,
					  datasets: [
						{
						  label:pieLabels,
						  data: pieCounts,
						  backgroundColor: piecolors,//["green", "red", "orange", "blue"],
						  borderWidth: 1,
						  
						}
					  ]
					},
					options: {
					  responsive: true,
					  // cutoutPercentage: 90,
					  percentageInnerCutout : 90,
					  maintainAspectRatio: false,
					  legend: {
						display: false,
					  },
					  tooltips: {
						callbacks: {
						  title: function(tooltipItem, data) {
							return "Stats : " + data["labels"][tooltipItem[0]["index"]];
						  },
						  label: function(tooltipItem, data) {
							return data["datasets"][0]["data"][tooltipItem["index"]];
						  }
						},
						titleFontColor: "#888",
						bodyFontColor: "#555",
						titleFontSize: 12,
						bodyFontSize: 14,
						backgroundColor: "rgba(256,256,256,0.95)",
						displayColors: true,
						borderColor: "rgba(220, 220, 220, 0.9)",
						borderWidth: 2
					  }
					}
				  });
				}
		   },


            getPieChartForStats : function(pieLabelsStats, pieCountsStats,piecolorsStats,idStas){
					   /*======== 11. DOUGHNUT CHART ========*/
						  let doughnut = document.getElementById(idStas);
						  if (doughnut !== null) {
							let myDoughnutChart = new Chart(doughnut, {
							  type: "doughnut",
							  data: {
								labels: pieLabelsStats,
								datasets: [
								  {
									label:pieLabelsStats,
									data: pieCountsStats,
									backgroundColor: piecolorsStats,//["green", "red", "orange", "blue"],
									borderWidth: 1
									
								  }
								]
							  },
							  options: {
								responsive: true,
								cutoutPercentage: 90,
								percentageInnerCutout: 90,
								maintainAspectRatio: false,
								legend: {
								  display: false
								},
								
								tooltips: {
								  callbacks: {
									title: function(tooltipItem, data) {
									  return  data["labels"][tooltipItem[0]["index"]];
									},
									label: function(tooltipItem, data) {
									  return data["datasets"][0]["data"][tooltipItem["index"]];
									}
								  },
								  titleFontColor: "#888",
								  bodyFontColor: "#555",
								  titleFontSize: 12,
								  bodyFontSize: 14,
								  backgroundColor: "rgba(256,256,256,0.95)",
								  displayColors: true,
								  borderColor: "rgba(220, 220, 220, 0.9)",
								  borderWidth: 2
								}
							  }
							});
						  }
            },

            changeTemplate : function(){
              
                let templateId = $(this).attr('id');
 
				let adminurl = $("#adminurl").val();
				
				let nonceValue = $("#nonce_input").val();

				let delay = 3000;

				window.leavepageflag = true;
				$.ajax({

					url: sortd_ajax_obj_dashboard.ajax_url,
					data: { 'action': 'sortd_save_template', 'templateId': templateId, 'sortd_nonce': sortd_ajax_obj_dashboard.nonce },
					type: 'post',
					success: function (result) {
						
						try {
							let remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								let dataresult = result.substring(0, remove_after + 1);
								let res = JSON.parse(result);
								console.log(res);
							
								if (res.response.status == "true" || res.response.status == true) {
									
									if (res.flag == 1) {
										console.log("if called");
										let url = adminurl + 'admin.php?page=sortd-settings'
										setTimeout(function () { location.href = url; }, delay);
									} else {
										console.log("else called");
										let url = adminurl + 'admin.php?page=sortd-manage-settings&section=sortd_manage_templates&_wpnonce=' + nonceValue + '&leavepage=true'
										setTimeout(function () { location.href = url; }, delay);
									}

								} else if((res.response.status == "false" || res.response.status == false) && res.response.error.errorCode == 503) {
									swal({
										icon: 'error',
										text: res.response.error.message,
										timer: 3000
									});
								} else {
									swal({
										icon: 'error',
										text: 'template not updated !!!',
										timer: 3000
									});
								}
							} else {
								console.log("This is not a valid JSON format")
							}

						}
						catch (e) {
							console.log(e);
							return false;
						}
					}

				});
            },

            deleteArticle : function(){
				let nonceValue = $("#nonce_input").val();
                let guid = this.id;

				var site_url = $(this).attr('data-site_url');

				var project_slug = $(this).attr('data-project_slug');
                var current_user = $(this).attr('data-current_user');
                if (typeof gtag === 'function') {
                  gtag('event', 'sortd_action', {
                    'sortd_page_title': 'sortd_settings',
                    'sortd_feature': 'Un-Sync Article',
                    'sortd_domain': site_url,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                  });
                }
				$.ajax({
					url: sortd_ajax_obj_dashboard.ajax_url,
					data : {'_wpnonce':nonceValue,'action':'sortd_unsync_article','guid':guid,'sortd_nonce' : sortd_ajax_obj_dashboard.nonce},
					type : 'post', 
					success: function(result){
						//console.log(result);return false;
						let incStr = result.includes("<!--");  
						try{
														
							let remove_after= result.lastIndexOf('}');
							if(remove_after != -1){
								let dataresult =  result.substring(0,remove_after +1);
								let res = JSON.parse(result);
								console.log(res);
							
								if(res.status ==true){
													
									swal("Successfully Unsynced Article");
									$("#row_"+guid).addClass('table-dlt dlMng');
									$("#imageArticlerestore"+guid).show();
									$("#"+guid).hide();
								} else if(res.status == false) {	    				
											
									if(res.error.errorCode != 1004 && res.error.errorCode != 1005){
										swal(res.error.message);
										$("#unsync_"+guid).prepend(`<div class="notice notice-error is-dismissible"><p>${res.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
										$(".notice-error").delay(2000).fadeOut(500);
									} else {

										$('.modal-body').text(res.error.message);
										$('#server_msg_modal_'+guid).modal('show');

									}
								}	
							}else {
								console.log("This is not valid JSON format")
							}  

						}  catch (e){						
							console.log(e);
							return false;
						} 			
				}
				});
            },
			
			restoreArticle : function (){

				let post_id = $(this).attr('data-guid');

				var site_url = $(this).attr('data-site_url');

				var project_slug = $(this).attr('data-project_slug');
                var current_user = $(this).attr('data-current_user');
                if (typeof gtag === 'function') {
                  gtag('event', 'sortd_action', {
                    'sortd_page_title': 'sortd_settings',
                    'sortd_feature': 'Sync Article',
                    'sortd_domain': site_url,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                  });
                }

				$.ajax({
					url: sortd_ajax_obj_dashboard.ajax_url,
					data: { 'action': 'sortd_restore', 'post_id': post_id, 'sortd_nonce': sortd_ajax_obj_dashboard.nonce },
					type: 'post',
					success: function (result) {

					//	console.log(result);return false;
						let incStr = result.includes("<!--");
						try {
							let remove_after = result.lastIndexOf('}');
							if (remove_after != -1) {
								let dataresult = result.substring(0, remove_after + 1);
								let res = JSON.parse(result);
							
								if (res.status === true) {
									swal("Successfully Synced Article");
									$("#row_" + post_id).removeClass('table-dlt');
									$("#imageArticlerestore" + post_id).hide();
									$("#" + post_id).show();
								} else if (res.status == false) {
									if (res.error.errorCode != 1004 && res.error.errorCode != 1005) {
										$("#sync_" + post_id).prepend(`<div class="notice notice-error is-dismissible"><p>${ res.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
										$(".notice-error").delay(2000).fadeOut(500);
									} else {
										$('.modal-body').text(res.error.message);
										$('#server_msg_modal_' + post_id).modal('show');
									}
								}
							} else {
								console.log("This is not valid JSON format")
							}
						} catch (e) {
							console.log(e);
							return false;
						}
					}
				});

				return false;
			},


			dailyIngestedArticles : function (){
				let labels = [];
    			let dataval = [];
				$.ajax({
    				url: sortd_ajax_obj_dashboard.ajax_url,
			  		
			  		data : {'action':'sortd_dailyingestedarticles','sortd_nonce' : sortd_ajax_obj_dashboard.nonce},
			  		type : 'post',  
			  		success: function(result){
			  			let chartdata = JSON.parse(result);

			  			$.each(chartdata.data,function(field,val){
			  			
			  				 labels.push(String(val._id));
			  				 dataval.push(val.count);

			  			});

			  		
			  			dashboard.dynamicDataAppendOnChart(labels, dataval);
			  		}
				});
			} ,

			typesOfArticles : function(){
				let pieLabels = [];
				let pieCounts = [];
				let piecolors = []


    			$.ajax({
    				url: sortd_ajax_obj_dashboard.ajax_url,
			  		data : {'action':'article-type-count','sortd_nonce' : sortd_ajax_obj_dashboard.nonce},
			  		type : 'post',  
			  		success: function(result){
	  					let incStr = result.includes("<!--");  
	  					try{
							let remove_after= result.lastIndexOf('}');
							if(remove_after != -1){
								let dataresult =  result.substring(0,remove_after +1);
								let chartdataPie = JSON.parse(result);
				
								$.each(chartdataPie.data,function(field,val){
								
									if(val._id == 'gallery'){

										piecolors.push("#0e60ef");
										pieCounts.push(val.count);
										pieLabels.push(val._id);
									
									} else  if(val._id == 'news'){

										piecolors.push("#1c3c9b");
										pieCounts.push(val.count);
										pieLabels.push(val._id);
									
									} else  if(val._id == 'video'){

										piecolors.push("#5c7aea");
										pieCounts.push(val.count);
										pieLabels.push(val._id);
									
									}
									else  if(val._id == 'audio'){

										piecolors.push("#A7A9A7");
										pieCounts.push(val.count);
										pieLabels.push(val._id);

									} else  if(val._id == null){

										piecolors.push("blue");
										pieCounts.push(val.count);
										pieLabels.push(val._id);
									}

								});

			  					dashboard.getPieChart(pieLabels, pieCounts,piecolors);
							}else {
								console.log("This is not valid JSON format")
							} 
						}catch (e){
							console.log(e);
							return false;
						}
			  		}
			  	});
			} ,

			webstories_count : function(){
				let pieLabels = [];
				let pieCounts = [];
				let piecolors = []
				
				
    			$.ajax({
    				url: sortd_ajax_obj_dashboard.ajax_url,
			  		data : {'action':'webstories_count','sortd_nonce' : sortd_ajax_obj_dashboard.nonce},
			  		type : 'post',  
			  		success: function(result){
						//console.log(result);return false;
						
	  					let incStr = result.includes("<!--");  
	  					try{
							let remove_after= result.lastIndexOf('}'); 
							if(remove_after != -1){
								let dataresult =  result.substring(0,remove_after +1);
								let chartdataPie = JSON.parse(result);
				
								$.each(chartdataPie.data,function(field,val){
								console.log(val);
									if(field == "webstoryCount" && val!= 0){

										piecolors.push("#1c3c9b");
										pieCounts.push(val);
										pieLabels.push(field);
									
									}

								});

			  					dashboard.getPieChartWebstory(pieLabels, pieCounts,piecolors);
							}else {
								console.log("This is not valid JSON format")
							} 
						}catch (e){
							console.log(e);
							return false;
						}
			  		}
			  	});
			} ,

			getNotificationStats : function(){
				let pieLabelsStats = [];
				let pieCountsStats = [];
			  	let piecolorsStats = [];
			  	let pieLabelsStatsMonth = [];
				let pieCountsStatsMonth = [];
			  	let piecolorsStatsMonth = [];
			  	let pieLabelsStatsTilldate = [];
				let pieCountsStatsTilldate = [];
			  	let piecolorsStatsTilldate = [];
  
			  	$.ajax({
					  url: sortd_ajax_obj_dashboard.ajax_url,
					  data : {'action':'get_notification_stats_dashboard','sortd_nonce' : sortd_ajax_obj_dashboard.nonce},
					  type : 'post',  
					  success: function(result){
  
						if(result !== 'null'){
  
							let incStr = result.includes("<!--");  
							try
								{
									
									let remove_after= result.lastIndexOf('}');
									if(remove_after != -1){
									let dataresult =  result.substring(0,remove_after +1);
										let chartdataPie = JSON.parse(result);
									
										if(chartdataPie !== undefined){
				
											$.each(chartdataPie.today,function(field,val){
												
												
													pieLabelsStats.push(field);
												
													pieCountsStats.push(val);
					
												if(field == 'article_promotion'){
					
													piecolorsStats.push("#005BF0");
												
												} else  if(field== 'general'){
					
													piecolorsStats.push("#ff00cc");
												
												} 
					
											});
				
											$.each(chartdataPie.thisMonth,function(field,val){
											
											
												pieLabelsStatsMonth.push(field);
											
												pieCountsStatsMonth.push(val);
				
												if(field == 'article_promotion'){
					
													piecolorsStatsMonth.push("#005BF0");
												
												} else  if(field == 'general'){
					
													piecolorsStatsMonth.push("#ff00cc");
												
												} 
					
											});
				
											$.each(chartdataPie.total,function(field,val){
											
											
												pieLabelsStatsTilldate.push(field);
											
												pieCountsStatsTilldate.push(val);
				
												if(field== 'article_promotion'){
					
													piecolorsStatsTilldate.push("#005BF0");
												
												} else  if(field == 'general'){
					
													piecolorsStatsTilldate.push("#ff00cc");
												
												} 
					
											});
				
											dashboard.getPieChartForStats(pieLabelsStats, pieCountsStats,piecolorsStats,'doChartStats');
											dashboard.getPieChartForStats(pieLabelsStatsMonth, pieCountsStatsMonth,piecolorsStatsMonth,'doChartStatsthismonth');
											dashboard.getPieChartForStats(pieLabelsStatsTilldate, pieCountsStatsTilldate,piecolorsStatsTilldate,'doChartStatsTilldate');
										}
									}else {
										console.log("This is not valid JSON format")
									} 
							} catch (e){		  
								console.log(e);
								return false;
							}
						}
							 
					  }
  
				  	});
			} , 
			configCompleteness : function(){
				let site_url = $(".hiddenurl").val();
				let nonce = $("#nonce_input").val(); // Retrieve the nonce from the hidden input.

				$.ajax({
					url: sortd_ajax_obj_dashboard.ajax_url,
					data : {'action':'get_config_data','sortd_nonce' : sortd_ajax_obj_dashboard.nonce},
					type : 'post',  
					success: function(result){
						let response = JSON.parse(result);
						if(response.status == true){
					//	console.log(Object.keys(response.data.top_sections).length);return false;
						let length = Object.keys(response.data.top_sections).length;
						
						if(length !== 0){
							let str = '';
							let sub_str = '';
							let concat_array = [];
							$.each(response.data.top_sections,function(i,j){
								console.log(i,j)

								if(j.length !== 0){

									if(j.subgroup.length !== 0){

										$.each(j.subgroup,function(k,l){
											concat_array.push(l.display_name);
											
										});

										let array_to_string = concat_array.join(" , ");
										//let array_to_string = j.subgroup.display_name.join("</b><b>");
										sub_str += `<span><b>${array_to_string}</b></span>`;
										concat_array = [];
									}
									// str += `
									// <div class="topLink_card"> <a href="${site_url}/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter=${i}">${j.display_name}</a>${sub_str}<a class="top3link" href="${site_url}/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter=${i}"><i class="bi bi-arrow-right-circle-fill"></i></a></div>																	 
									// `;

									str += `
									<div class="topLink_card">
										<a href="${site_url}/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter=${i}&_wpnonce=${nonce}">
											${j.display_name}
										</a>
										${sub_str}
										<a class="top3link" href="${site_url}/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter=${i}&_wpnonce=${nonce}">
											<i class="bi bi-arrow-right-circle-fill"></i>
										</a>
									</div>
									`;

							
								}
							
								 sub_str = '';
							});
							$(".top_links").html(str);
							$(".linkstoadd").html(`Top ${length} links to boost your performance.`);
							
							str = '';
						
						}
						//response.data.completed_percentage = 100;
						$(".progress-bar-config").attr('style', 'width:' + response.data.completed_percentage + '%');
						if(response.data.completed_percentage == 100){
							$(".prgDiv").hide();
						}
						$(".complte_percent").html(response.data.completed_percentage+'% Completed');
					
						} else {
							$(".complte_percent").html('Could not fetch data');
							$(".prgDiv").hide();
						}
					}

				});

				//return false;
			
			},


			
			
        } 

	
		$(window).load(dashboard.configCompleteness);
        $(".themebtn").click(dashboard.changeTemplate);
		$(".alertbutton").click(dashboard.showHideAlerts);
		$(document).mouseup(dashboard.onmouseupalert);
		$(window).load(dashboard.dailyIngestedArticles);
		$(window).load(dashboard.typesOfArticles);
		$(window).load(dashboard.getNotificationStats);
		$(".deleteClass").click(dashboard.deleteArticle);
		$(".imageArticle").click(dashboard.restoreArticle);  

		$(window).load(dashboard.webstories_count);

})( jQuery );
