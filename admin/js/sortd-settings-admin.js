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

 // $(document).ready(function(){
 //        $('[data-toggle="tooltip"]').tooltip();
 //    });

$(".unsyncBtn").on('click',function(e) {

	e.preventDefault();

	var guid = $(this).data('guid');
	var site_url = $(this).attr('data-siteurl');
	//var nonce = $(this).attr('data-nonce');
	//console.log(guid);	

	$.ajax({
				url: sortd_ajax_obj.ajax_url,
	  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  		data : {'action':'unsyncArticle','guid':guid,'sortd_nonce' : sortd_ajax_obj.nonce},
	  		type : 'post', 
	  		success: function(result){

	  				var incStr = result.includes("<!--");  
	  				try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 

	  			

	    			if(res.status === true){
	    				$(".unsync_"+guid).hide();
	    				$("#unsync_"+guid).text('UnSynced Successfully');	    				
						console.log('Article UnSynced Successfully : ' + guid);
						$(".sortusyncnotify"+guid).hide();
	  				
	    				$(".timeupdatepostid"+guid).hide();
	    			} else  if(res.status == false) {	    				
						//console.log('Article Not Updated');

								

						if(res.error.errorCode != 1004 && res.error.errorCode != 1005){
							$("#unsync_"+guid).prepend('<div class="notice notice-error is-dismissible"><p>'+res.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
							$(".notice-error").delay(2000).fadeOut(500);
						} else {

							//console.log("ddsds");return false;
							$('.modal-body').text(res.error.message);
							$('#server_msg_modal_'+guid).modal('show');

						}

						
	    				

	    			}	 

	    		}   catch (e)
						{
							
							console.log(e);
							return false;
						} 			
	  		}
	});	
});

$('.syncBtn,.syncfailedBtn').on( 'click', function( e ) {

	 		var site_url = $(this).attr('data-siteurl');
	 		var post_id = $(this).attr('data-guid');
	 		//var nonce = $(this).attr('data-nonce');
	 		//console.log(site_url);return false;


			$.ajax({
				url: sortd_ajax_obj.ajax_url,
	  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  		data : {'action':'sortdmanualsync','post_id' : post_id,'sortd_nonce' : sortd_ajax_obj.nonce},
	  		type : 'post', 
	  		success: function(result){

	  			//console.log(result);return false;
	  			var incStr = result.includes("<!--");  
	  				try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 
	  				
	  				//var res = JSON.parse(result); 
	  				//console.log(res.response.error);return false;
	  				if(res.response.status === true){
	    				//$$(".abcd").show();
	    				$("#post-"+post_id).find(".sortdview").html('<img class="unsyncBtn unsync_'+post_id+' " src="'+site_url+'/wp-content/plugins/wp_sortd/admin/css/check.png">')
	    				$("#sync_"+post_id).text('Synced Successfully');	
	    				$("#syncfailed_"+post_id).text("Synced Successfully");
	    				$(".timeupdatepostid"+post_id).hide();
	    				//$("#syncfailed_464"+post_id).text('Synced Successfully');	    				
						console.log('Article Synced Successfully : ' + post_id);	

						$(".sortsyncnotify"+post_id).show();  				
	    			
	    			} else if(res.response.status == false) {	    				
						

	    				if(res.response.error.errorCode != 1004 && res.response.error.errorCode != 1005){
							console.log(res.response.error.message);
							$("#syncfailed_"+post_id).prepend('<div class="notice notice-error is-dismissible"><p>'+res.response.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
							$("#sync_"+post_id).prepend('<div class="notice notice-error is-dismissible"><p>'+res.response.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
							$(".notice-error").delay(2000).fadeOut(500);
						} else {

							//console.log("ddsds");return false;
							$('.modal-body').text(res.response.error.message);
							$('#server_msg_modal_'+post_id).modal('show');

						}
	    			}
	  			}  catch (e)
						{
							
							console.log(e);
							return false;
						} 		    			
	  		}
		});	

		return false;

	});


$(window).scroll(function(){
  var sticky = $('.stickyhead'),
      scroll = $(window).scrollTop();

  if (scroll >= 100) sticky.addClass('fixedhead');
  else sticky.removeClass('fixedhead');
});

 


$( window ).load(function() {
	var url_string  = window.location.href;
	var url = new URL(url_string);
	var postids = url.searchParams.get("postids");
	var sp = url_string.split('/wp-admin');
	var siteurl = sp[0];
	var pluginUrl = sortd_ajax_obj.pluginsUrl;

	  		
	if (window.location.href.indexOf("bulk_sync_posts") > -1) {

		$.ajax({
			url: sortd_ajax_obj.ajax_url,
	  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
	  		data : {'action':'bulksortdsync','post_id' : postids,'sortd_nonce' : sortd_ajax_obj.nonce},
	  		type : 'post', 
	  		success: function(result){

	  				var incStr = result.includes("<!--");  
	  				try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 

	  			

	  			if(res[0].msg_error_key == 3 && (res[0].msg_value == 'SORTD Server is not reachable' || res[0].msg_value == 'This project has been suspended! for more detail contact our support team at support@sortd.mobi')){

	  				$('.modal-body').text(res[0].msg_value);
	  				$('#server_msg_modal_'+res[0].post_id).modal('show');
	  			
	  			} else {

	  				//console.log(res);return false;
		  			$.each(res,function(i,j){

		  				if(j.meta_value == 2){
		  					$("#bulk"+j.post_id).prepend('<p><img class="failurebulk" title="Post categories are not synced with sortd" src="'+pluginUrl+'css/cannotsync.png"></p>');
		  					//$('#server_msg_modal_'+j.post_id).modal('show');
		  				} else if(j.meta_value == 1){
		  					$("#bulk"+j.post_id).prepend('<p><img class="successbulk" title="Successfully Synced" src="'+pluginUrl+'css/successful_sync.jpg"></p>');
		  				} else if(j.meta_value == 3){
		  					$("#bulk"+j.post_id).prepend('<p><img class="successbulk" title="Already Synced" src="'+pluginUrl+'css/download.png"></p>');
		  				} else if(j.msg_error_key == 3){
		  					$("#bulk"+j.post_id).prepend('<p><img class="failurebulk" title="'+j.msg_value+'" src="'+pluginUrl+'css/cannotsync.png"></p>');
		  				}

		  				
		  			});


	  			}

	  			
	  			$(".failurebulk,.successbulk").delay(8000).fadeOut();
	  				$(".bulksortdaction").delay(5000).fadeOut();

	  			$.ajax({
			  		url: sortd_ajax_obj.ajax_url,
	  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
	  		data : {'action':'bulksortdsyncmsg','post_id' : postids,'sortd_nonce' : sortd_ajax_obj.nonce},
			  		type : 'post',  
			  		success: function(result){
			  			//console.log(result)
			  		}});
	  			
	  		} catch (e)
						{
							
							console.log(e);
							return false;
						}

	  		}
	  	});
      
    }



    var page = url.searchParams.get("page");

    var labels = [];
    var dataval = [];
    
    if(page == 'sortd_dashboard' || page == 'sortd-settings'){

    		$.ajax({
    				url: sortd_ajax_obj.ajax_url,
			  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
			  		data : {'action':'dailyingestedarticles','sortd_nonce' : sortd_ajax_obj.nonce},
			  		type : 'post',  
			  		success: function(result){
			  			var chartdata = JSON.parse(result);

			  			$.each(chartdata.data,function(field,val){
			  				//console.log(val._id);

			  				 labels.push(String(val._id));
			  				
			  				 dataval.push(val.count);
				  	
					  		

			  			});

			  			//console.log(labels);return false;

			  			
			  				dynamicDataAppendOnChart(labels, dataval);
			  		}



			  	});


    		var pieLabels = [];
    		var pieCounts = [];
    		var piecolors = []


    		$.ajax({
    			url: sortd_ajax_obj.ajax_url,
			  	//	url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
			  		data : {'action':'article-type-count','sortd_nonce' : sortd_ajax_obj.nonce},
			  		type : 'post',  
			  		success: function(result){
			  				console.log(result);

	  				var incStr = result.includes("<!--");  
	  				try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var chartdataPie = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 
			  		

			  			

			  			$.each(chartdataPie.data,function(field,val){
			  				//console.log(val._id);

			  				
			  				
			  				// pieCounts.push(val.count);

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
			  				 
			  				 } else  if(val._id == null){

			  				 	piecolors.push("blue");
			  				 	pieCounts.push(val.count);
			  				 	 pieLabels.push(val._id);
			  				 }

			  				 
				  	
					  		

			  			});


			  			
			  				getPieChart(pieLabels, pieCounts,piecolors);

			  			}	catch (e)
								{
									
									console.log(e);
									return false;
								}

			  		}



			  	});

    		

    	 // On page loading you will be having your own data so you can pass them, or if you want that to be happen with ajax when page loading itself you can trigger the click event.

  
      function dynamicDataAppendOnChart(labels, dataval) {
      //	alert(labels);return false;
      	
      	/*======== 27. ACQUISITION3 ========*/
						var acquisition3 = document.getElementById("bar3");

						if (acquisition3 !== null) {
						  var acChart3 = new Chart(acquisition3, {
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
					}


					function getPieChart(pieLabels,pieCounts,piecolors){

				

						  /*======== 11. DOUGHNUT CHART ========*/
							  var doughnut = document.getElementById("doChart");
							  if (doughnut !== null) {
							    var myDoughnutChart = new Chart(doughnut, {
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
					}

			}


			if(page == 'sortd_notification' || page == 'sortd-settings'){

				       function getPieChartForStats(pieLabelsStats, pieCountsStats,piecolorsStats,idStas){

                

                          /*======== 11. DOUGHNUT CHART ========*/
                              var doughnut = document.getElementById(idStas);
                              if (doughnut !== null) {
                                var myDoughnutChart = new Chart(doughnut, {
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
                    }

	          var pieLabelsStats = [];
	          var pieCountsStats = [];
            var piecolorsStats = [];
            var pieLabelsStatsMonth = [];
	          var pieCountsStatsMonth = [];
            var piecolorsStatsMonth = [];
            var pieLabelsStatsTilldate = [];
	          var pieCountsStatsTilldate = [];
            var piecolorsStatsTilldate = [];

            var hiddenurl = $(".hiddenurl").val();

           
            $.ajax({
                   // url: hiddenurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
                    url: sortd_ajax_obj.ajax_url,
                    data : {'action':'getNotificationStatsForAjax','sortd_nonce' : sortd_ajax_obj.nonce},
                    type : 'post',  
                    success: function(result){


                    	if(result !== 'null'){

                    		var incStr = result.includes("<!--");  
							  				try
												{
													
													var remove_after= result.lastIndexOf('}');
													if(remove_after != -1){
													  var dataresult =  result.substring(0,remove_after +1);
													 	var chartdataPie = JSON.parse(result);
													}else {
													  console.log("This is not valid JSON format")
													} 
                      

                        //console.log(chartdataPie.today);

                        $.each(chartdataPie.today,function(field,val){
                            
                        	
                              pieLabelsStats.push(field);
                            
                              pieCountsStats.push(val);

                             if(field == 'article_promotion'){

                                piecolorsStats.push("#005BF0");
                            
                             } else  if(field == 'general'){

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

                             if(field == 'article_promotion'){

                                piecolorsStatsTilldate.push("#005BF0");
                            
                             } else  if(field == 'general'){

                                piecolorsStatsTilldate.push("#ff00cc");
                             
                             } 

                        });

                        getPieChartForStats(pieLabelsStats, pieCountsStats,piecolorsStats,'doChartStats');
                        getPieChartForStats(pieLabelsStatsMonth, pieCountsStatsMonth,piecolorsStatsMonth,'doChartStatsthismonth');
                        getPieChartForStats(pieLabelsStatsTilldate, pieCountsStatsTilldate,piecolorsStatsTilldate,'doChartStatsTilldate');
                      } catch (e)
													{
														
														console.log(e);
														return false;
													}
											}
                           
                    }



                });
			}
});


$('.saveRedirection').on( 'click', function( e ) {

	var siteurl = $("#siteUrlId").val();
	var exclude_url = [];
	var validationCount = [];
	var emptyCount = [];
	var redirection_code = $("#redirection_code").prop('checked');
	var nonce = $("#sortd-hidden-nonce").val();

	var redirection_code_amp = $("#redirection_code_amp").prop('checked');

	
	if(redirection_code == true  && redirection_code_amp == true){
		var redirectValue = true;
		var redirectValueAmp = true;
	} else if(redirection_code == false && redirection_code_amp == true){
		var redirectValue = false;
		var redirectValueAmp =true;
	} else if(redirection_code == true && redirection_code_amp == false){
		var redirectValue = true;
		var redirectValueAmp = false;
	} else if(redirection_code == false && redirection_code_amp == false){
		var redirectValue = false;
		var redirectValueAmp = false;
	}


	

	var domain_name = $("#domain_code").val();

	
	var countV = 1;
	var countEmpty = 1;
	 $("#excludeUrls input[type=text]")
            .each(function () {
            var my_string = this.value;

         
            var dataAttr = $(this).attr("data-exclude");

            var count = dataAttr.split('_');

	        if(my_string.match(/^\s+$/) === null) {

	        	if(my_string == '' || !my_string){

	  

	        		emptyCount.push({id: this.id,  count:countEmpty});


	        		countEmpty++;
	        		
					




				} else {

					
					$("#exclude_url_span_add"+count[1]).hide();

					exclude_url.push(my_string);

				}

	       
			} else{

				$("#exclude_url_span_add"+count[1]).show();

				validationCount.push(countV);

				countV++;

				return false;
			}

        });

           // console.log(emptyCount.length);return false;

            if(emptyCount.length == 1 && emptyCount[0].id == "exclude_url_add1"){

            	$(".emptyString").html("Please enter value or remove the fields");
							$(".emptyString").hide();
            } else if(emptyCount.length >= 1 ) {

            	console.log(emptyCount);

            	$.each(emptyCount,function(i,j){

            		var newid = j.id.split('add');

            		$("#exclude_url_spanvalida_add"+newid[1]).show();
            	});
            //	$(".emptyString").html("Please enter value or remove the fields");
					//		$(".emptyString").show();

							return false;
            }


	  		
          if((exclude_url.length != 0 && validationCount.length == 0 && redirectValue == 1) || (redirectValue == 0) || (redirectValue == 1 && exclude_url.length == 0  && validationCount.length == 0 )){

				$.ajax({
			  		 url: sortd_ajax_obj.ajax_url,
			  		data : {'action':'redirectTurbo','exclude_url':exclude_url,'redirection_code':redirectValue,'redirectValueAmp':redirectValueAmp,'domain_name':domain_name,'sortd_nonce' : sortd_ajax_obj.nonce},
			  		type : 'post', 
		  			success: function(result){

		  				//console.log(result);return false;
						window.leavepageflag = true;
		  			//	console.log(result);return false;


						window.location.href = siteurl+'/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_redirection&leavepage=false'

		  		 			
		  			}
				});	

			}

		return false;

	});

$(".removelink").click(function(){

	$(".emptyString").hide();
});


$(".deleteClass").click(function(){

	var site_url = $(".hiddenurl").val();

		var guid = this.id;
		//var nonce = $(this).attr('data-nonce');


			$.ajax({
				url: sortd_ajax_obj.ajax_url,
	  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  		data : {'action':'unsyncArticle','guid':guid,'sortd_nonce' : sortd_ajax_obj.nonce},
	  		type : 'post', 
	  		success: function(result){


	  			//console.log(result);return false;

	  					var incStr = result.includes("<!--");  
							  				try
												{
													
												var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 
	  				

	  			//console.log(res);return false;
	    			if(res.status ==true){
	    				 				
							swal("Successfully Unsynced Article");

							$("#row_"+guid).addClass('table-dlt dlMng');

							$("#imageArticlerestore"+guid).show();

							// $("#articleiddiv"+guid).css("background-color", "#f8d7da");
							// $("#imagearticle"+guid).show();
							 $("#"+guid).hide();

	  				
	    			
	    			} else  if(res.status == false) {	    				
								

						if(res.error.errorCode != 1004 && res.error.errorCode != 1005){
							$("#unsync_"+guid).prepend('<div class="notice notice-error is-dismissible"><p>'+res.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
							$(".notice-error").delay(2000).fadeOut(500);
						} else {

							//console.log("ddsds");return false;
							$('.modal-body').text(res.error.message);
							$('#server_msg_modal_'+guid).modal('show');

						}

						
	    				

	    			}	 

	    			}  catch (e)
								{
														
										console.log(e);
										return false;
								} 			
	  		}
	});
});




var nType;
var nSlug;
var title;

$(window).load(function() {

			
     	var url_string  = window.location.href;
			var url = new URL(url_string);
			var postid= url.searchParams.get("post");
			var page= url.searchParams.get("page");
			var site_url = $("#hiddenSiteUrl").val();

				$("#page1").addClass('activePage');

				$(".notice-success").delay(8000).fadeOut(500);

				
$(".closeicon").click(function(){
	$(".templateClassdiv").hide();
});
$(".notice-dismiss").click(function(){
	$(".templateClassdiv").hide();
});

			if(page == 'sortd_notification' &&  postid !== null){

	
	  		
				$.ajax({
		  		url: sortd_ajax_obj.ajax_url,
		  		data : {'action':'sendArticleNotification','post_id' : postid,'sortd_nonce' : sortd_ajax_obj.nonce},
		  		type : 'post', 
		  		success: function(result){
		  		//	console.log(result);return false;
		  			var res = JSON.parse(result);
		  			
		  			$("#snippet").val(res.title);
		  			//$("#notificationtype").val(res.type);
		  			$('input[id=notificationtype]').val(res.type);
		  			$('input[id=notificationslug]').val(res.slug);

		  			nType = $("#notificationtype").val();
		  			nSlug = $("#notificationslug").val();
		  			title = $("#snippet").val();
		  		}

	  		});


	} else if(page == 'sortd_notification' && postid === null){

	  		
	
				$.ajax({
		  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
		  		url: sortd_ajax_obj.ajax_url,
		  		data : {'action':'sendGeneralNotification','sortd_nonce' : sortd_ajax_obj.nonce},
		  		type : 'post', 
		  		success: function(result){

		  				//console.log(result);return false;
		  			var res = JSON.parse(result);
		  		
		  			$('input[id=notificationtype]').val(res.type);
		  			nType = $("#notificationtype").val();
		  		}

	  		});


	}


});

$(".page-link").click(function(){

var page = $(this).attr('data-page');
var site_url = $("#hiddenSiteUrl").val();
var pagecount = $("#pagecount").val();

var pageid = $(this).attr('id');

if(pageid !== 'previous' && pageid !== 'next'){
	
	$('.page-link').removeClass('activePage');
	$(this).addClass('activePage');

	
}



var activeid = $(".page-link.activePage").attr('id');



if(pageid == 'previous'){

	var splitpage = activeid.split('page');

	if( splitpage[1] == 1){
		page = 1;
	} else {

		
		page = splitpage[1] - 1;
	}

	$('.page-link').removeClass('activePage');
		$("#page"+page).addClass('activePage');


	
} else if(pageid == 'next'){



	var splitpage = activeid.split('page');

		if(pagecount == splitpage[1]){
			page = pagecount;
		} else {
			page = parseInt(splitpage[1]) + 1;
		}
	

	 $('.page-link').removeClass('activePage');
		$("#page"+page).addClass('activePage');

	
}


			$.ajax({
			  		url: sortd_ajax_obj.ajax_url,
			  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
			  		data : {'action':'getNotifications','page' : page,'sortd_nonce' : sortd_ajax_obj.nonce},
			  		type : 'post', 
			  		success: function(result){

			  			var incStr = result.includes("<!--");  
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
					  				var newtr = '';

						  			$.each(res.data.notificationList,function(i,j){


						  				 newtr += "<tr><td>"+j.message+"</td><td>"+j.platform+"</td><td>"+j.message_type+"</td><td>"+j.sent_on+"</td></tr>"
						  			
						  			});
					  		} else {
					  			$(".mesnotify").prepend('<div class="notice notice-error is-dismissible"><p>'+res.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');
					  		}


			  		

			  			$("#getlist").html(newtr);

			  		} catch (e)
								{
														
										console.log(e);
										return false;
								} 	
			  			
			  		}

		  		});

});


$(".sendPushNotifications").click(function(){

	var url_string  = window.location.href;
	var url = new URL(url_string);
	var postid= url.searchParams.get("post");
	var page= url.searchParams.get("page");
	var site_url = $(this).attr('data-siteurl');
	var valueType = $("#notificationtype").val();
	var slugType = $("#notificationslug").val();
	var title = $("#snippet").val();
	var platform = $("#notifyform input[type='radio']:checked").val();
	var nonce = $("#sortd-hidden-nonce").val();
	var public_hostset = $(".hiddenpublichostflag").val();
	var project_title = $(".hiddenprojecttitle").val();


	window.leavepageflag = true;
	var my_string = title;
	var spaceCount = (my_string.split(" ").length - 1);
	var string = (my_string.split(" "));
	$(this).attr('disabled','disabled');
	
	if(my_string.match(/^\s+$/) === null && my_string.length !== 0) {

			if(title == ''){

		$(".snippetRequired").show();
		return false;

	} else {


		$(".snippetRequired").hide();
			$(".snippetRequiredspace").hide();
		if(page == 'sortd_notification' &&  postid !== null){

			if(public_hostset == "0"){
			  	swal({
											  icon: 'error',
											  text: "Public host is not setup",//'Notification could not be sent !!',
											 timer: 3000
											});
				} else {
	  		
					$.ajax({
			  		url: sortd_ajax_obj.ajax_url,
			  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
			  		data : {'action':'sendArticlePushNotification','post_id' : postid,'slug':slugType,'type':valueType,'title':project_title,'message':title,'platform':platform,'sortd_nonce' : sortd_ajax_obj.nonce},
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

			  	

			  			//console.log(res);return false;

			  				if(res.status == true){
			  					//swal("Successfully Sent Push Notification");
			  					swal({
									 
									  icon: 'success',
									  title: 'Successfully Sent Push Notification',
									 
									  timer: 3000
									}) .then(function() {

											window.location = site_url+'/wp-admin/admin.php?page=sortd_notification'


										});

										$(this).removeAttr('disabled');
			  				}else {

			  					if(public_hostset == 0){
			  						console.log("failed");
			  					} else {
				  					swal({
										  icon: 'error',
										  text: res.error.message,//'Notification could not be sent !!',
										 timer: 3000
										});

			  					}

										$(this).removeAttr('disabled');
			  				}
			  		
			  			}  catch (e)
								{
														
										console.log(e);
										return false;
								} 


							
			  		}

		  		});

		  }


		} else if(page == 'sortd_notification' && postid === null){

				if(postid === null){
					postid = "";
				}

				if(public_hostset == "0"){
			  	swal({
											  icon: 'error',
											  text: "Public host is not setup",//'Notification could not be sent !!',
											 timer: 3000
											});
				} else {
						$.ajax({
			  	//	url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
			  			url: sortd_ajax_obj.ajax_url,
			  		data : {'action':'sendGeneralPushNotification','post_id' : postid,'slug':slugType,'type':valueType,'title':title,'message':title,'platform':platform,'sortd_nonce' : sortd_ajax_obj.nonce},
			  		type : 'post', 
			  		success: function(result){

			  			

			  				var incStr = result.includes("<!--");  
							  				try
												{
													
												var remove_after= result.lastIndexOf('}');
												if(remove_after != -1){
												  var dataresult =  result.substring(0,remove_after +1);
												 var res = JSON.parse(result);
													}else {
												  console.log("This is not valid JSON format")
												} 
			  		//	console.log(res);return false;

			  				if(res.status == true){
			  						swal({
									 
									  icon: 'success',
									  title: 'Successfully Sent Push Notification',
									 
									  timer: 3000
									}).then(function() {

											window.location = site_url+'/wp-admin/admin.php?page=sortd_notification'


										})

										$(this).removeAttr('disabled');
			  				} else {
			  							
					  					swal({
											  icon: 'error',
											  text: res.error.message,//'Notification could not be sent !!',
											 timer: 3000
											});

				  					

										$(this).removeAttr('disabled');
			  				}
			  		
			  			} catch (e)
								{
														
										console.log(e);
										return false;
								} 	
			  			}

		  		});
				}

	
		
		}

	}


	}  else {
			$(".snippetRequiredspace").show();
			return false;

	}
	
	
		

	
});

// $(".one_click").click(function(){

// 	var site_url = $(this).attr('data-siteurl');
// 	var nonce = $("#sortd-oneclick-nonce").val();
// 	var category_quota = $("#category_quota").val();
// 	var article_quota = $("#article_quota").val();
// 	var date_till = $("#date_till").val();
// 	$('.one_click').prop('disabled', true);

// 		$(".sync_cat_label").show();
// 		$(".minute_label").show();
// 		$.ajax({
// 			  		url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
// 	  				data : {'param':'syncCategories','category_quota' : category_quota,'article_quota' : article_quota,'date_till' : date_till,'sortd_nonce' : nonce},
// 	  				type : 'post',  
// 			  		success: function(result){

// 			  			//console.log(result);return false;
// 			  	//		$(".mesnotify").prepend('<div class="notice notice-error is-dismissible"><p>'+res.error.message+'</p><span class="closeicon" aria-hidden="true">&times;</span></div>');	
// 					  				try
// 										{													
// 												var remove_after= result.lastIndexOf('}');
// 												if(remove_after != -1){
// 												  var dataresult =  result.substring(0,remove_after +1);
// 												 	var res = JSON.parse(result);
// 												}else {
// 												  console.log("This is not valid JSON format")
// 												}

// 							  				if(res.flag == "true" || res.flag == true){
							  					
// 							  					$(".sync_cat_label").hide();
// 							  					$(".sync_cat_result").html(res.count+' Categories have been synced to SORTD').show();
// 													syncArticles(site_url,nonce,res.total_posts);

// 							  				}else {

// 							  					/*if(res.response.status == false && res.response.error.errorCode){
// 							  							Swal.fire({
// 															  icon: 'error',
// 															  text: res.response.error.message,//'Articles could not be synced !!!',
// 															 timer: 3000
// 															});	
// 							  					} else {*/
// 							  							swal({
// 														  	icon: 'error',
// 														  	text: 'Categories could not be synced !!!',
// 															 timer: 3000
// 															});
// 							  					//}
							  				
// 							  				}
			  		
// 			  						}  
// 			  						catch (e) {																
// 												console.log(e);
// 												return false;
// 										} 	
// 			  		}

// 		  		});

// });


// function syncArticles(site_url,nonce,total_posts) {

// 		var category_quota = $("#category_quota").val();
// 		var article_quota = $("#article_quota").val();
// 		var date_till = $("#date_till").val();
// 		var synced_posts = 0;
// 		var counter = 0; 
// 		var post_count = 10;
// 		var num_loops =  Math.ceil(total_posts/post_count);

// 		$(".sync_article_label").html('Starting to sync '+total_posts+' articles.....').show();
// 		$(".progress-bar").attr('aria-valuemax',total_posts);
// 		$(".prgDiv").show();

// 		for (let page = 0; page < num_loops; page++) {

// 		 			$.ajax({
// 			  		url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
// 	  				data : {'param':'syncArticles','page' : page,'post_count' : post_count,'date_till' : date_till,'sortd_nonce' : nonce},
// 	  				type : 'post', 
// 			  		success: function(result){
			  			
// 					  				try
// 										{													
// 												var remove_after= result.lastIndexOf('}');
// 												if(remove_after != -1){
// 												  var dataresult =  result.substring(0,remove_after +1);
// 												 	var res = JSON.parse(result);
// 												}else {
// 												  console.log("This is not valid JSON format")
// 												}

// 							  				if(res.flag == "true" || res.flag == true){
							  					
// 							  					//$(".sync_article_label").hide();
// 							  					synced_posts += res.count;
// 							  					var percent = (synced_posts/total_posts)*100;
// 							  					$(".progress-bar").attr('style','width:'+percent+'%');
// 							  					$(".sync_article_result").html(synced_posts+'/'+total_posts+' Articles have been synced to SORTD').show();
																										
// 													if (counter == (num_loops-1)) {
// 														setTimeout(function () {
// 															configsetup(site_url);
// 														},1000);
// 													}
// 													counter++;												

// 													$(".sync_article_label").hide();
												
// 													setTimeout(function () {
// 																$(".minute_label").hide();
// 														},5000);

// 							  				}else {

// 							  						/*if(res.response.status == false && res.response.error.errorCode){
// 							  						Swal.fire({
// 															  icon: 'error',
// 															  text: res.response.error.message,//'Articles could not be synced !!!',
// 															 timer: 3000
// 															});
// 							  					} else {*/
// 							  					swal({
// 															  icon: 'error',
// 															  text: 'Articles could not be synced !!!',
// 															 timer: 3000
// 															});

// 							  					//}
// 							  				}
			  		
// 			  						}  
// 			  						catch (e) {																
// 												console.log(e);
// 												return false;
// 										} 	
// 			  		}

// 		  		});
// 		}
		
// }




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

$(".create_domain").click(function(){

	var site_url = $(this).attr('data-siteurl');
	//var nonce = $("#sortd-domains-nonce").val();
	var public_host = $("#public_host").val().trim();

	
	var flagdomain = frmValidate(public_host);

	
	if(flagdomain == false){
		$(".validdomain").show();

		//	$(this).attr('disabled',true);
		return false;
	}

	if(public_host == ''){

		$(".hostRequired").show();

			//$(this).attr('disabled',true);
		return false;

	} else {
	
		$(".hostRequired").hide();
		$(".infoAlrt").show();
		$(this).attr('disabled',false);
		$.ajax({
						url: sortd_ajax_obj.ajax_url,
			  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  				data : {'action':'createDomain','public_host' : public_host,'sortd_nonce' : sortd_ajax_obj.nonce},
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

							  				if(res.status == "true" || res.status == true){
							  					
							  					$(".infoAlrt").hide();

							  						
							  					window.location = site_url+'/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'

							  					$(this).removeAttr('disabled');

							  				}else {
							  					$(".infoAlrt").hide();
							  					swal({
													  icon: 'error',
													  text: res.error.message,//'Domain could not be created !!!',
													 timer: 3000
													});
							  					$(this).removeAttr('disabled');
														
							  				}

							  	
			  		
			  						}  
			  						catch (e) {																
												console.log(e);
												return false;
										} 	
			  		}

		  		});
	}
});

$(".generate_ssl").click(function(){

	var site_url = $(this).attr('data-siteurl');

	
		//$(this).prop('disabled',true);
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
	

		$(".infoAlrt").show();
		$.ajax({
						url: sortd_ajax_obj.ajax_url,
			  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  				data : {'action':'generateSsl','sortd_nonce' : sortd_ajax_obj.nonce},
	  				type : 'post',  
			  		success: function(result){
			  				console.log(result);
					  				try
										{													
												var remove_after= result.lastIndexOf('}');
												if(remove_after != -1){
												  var dataresult =  result.substring(0,remove_after +1);
												 	var res = JSON.parse(result);
												}else {
												  console.log("This is not valid JSON format")
												}
												//console.log(res.error);return false;
							  				if(res.data == "true" || res.data == true || res.status == true){

							  					setTimeout(function () {
														$(".infoAlrt").hide();
							  						window.location = site_url+'/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'
											  	},3000);
							  					$(this).removeAttr('disabled');
							  					
							  				}else {
							  					console.log(res.error.errorCode,"gdfg");
					  		
							  				
							  					if(res.error.errorCode == 1012){
							  						//console.log(res.error.errorCode,"gasasddfg");
					  		
							  				

							  						$('.onClik_lft').removeClass('heigtDv');

							  						var html = '';
							  						html += `<span>${res.error.message.msg}.</span><span>  Kindly refresh after adding CAA record</span><table class="table">
																	  <thead>
																	    <tr>
																	      <th scope="col">Record</th>
																	      <th scope="col">Key</th>
																	      <th scope="col">Type</th>
																	      <th scope="col">Value</th>
																	    </tr>
																	  </thead>
																	  <tbody>
																	    <tr>
																	      <th scope="row">1</th>
																	      <td>${res.error.message.records.key}</td>
																	      <td>${res.error.message.records.type}</td>
																	      <td>${res.error.message.records.value}</td>
																	    </tr>
																	   
																	  </tbody>
																	</table>`;
							  						var errormsg = html;

							  						$(".keypairtable").html(html);

							  						$(".keypairtable").show();
							  						$(".generate_ssl").prop('disabled',false);

							  					} else {

							  						var errormsg = res.error.message;
								  						swal({
															 
															  icon: 'error',
															  text: errormsg,
															  timer: 7000

															});

																$(this).prop('disabled',false);
							  					}
							  					$(".infoAlrt").hide();
							  					
													
							  				}
			  		
			  						}  
			  						catch (e) {																
												console.log(e);
												return false;
										} 	
			  		}

		  		});

	}
});


$(".validate_ssl").click(function(){

	var site_url = $(this).attr('data-siteurl');
	//var nonce = $("#sortd-domains-nonce").val();
	var public_host = $("#public_host").val();
	
	$(this).attr('disabled',true);
		$(".infoAlrt").show();
		$.ajax({
						url: sortd_ajax_obj.ajax_url,
			  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  				data : {'action':'validateSsl','sortd_nonce' : sortd_ajax_obj.nonce},
	  				type : 'post',  
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

							  				if(res.status == "true" || res.status == true){
							  					
							  					$(".infoAlrt").hide();

													swal({
														icon: 'success',
														title: res.data,
														timer: 3000
													}) .then(function() {
														window.location = site_url+'/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'
													});
							  					
													$(this).removeAttr('disabled');
							  				}else {
							  					$(".infoAlrt").hide();
							  					swal({
													  icon: 'error',
													  text: 'Validate SSL could not be completed !!!',
													 timer: 3000
													});
													$(this).removeAttr('disabled');
							  				}
			  		
			  						}  
			  						catch (e) {																
												console.log(e);
												return false;
										} 	
			  		}

		  		});
});


$(".deploy_cdn").click(function(){

	var site_url = $(this).attr('data-siteurl');
	//var nonce = $("#sortd-domains-nonce").val();
	var public_host = $("#public_host").val();
	
		$(this).attr('disabled',true);
		$(".infoAlrt").show();
		$.ajax({
						url: sortd_ajax_obj.ajax_url,
			  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  				data : {'action':'deployCdn','sortd_nonce' : sortd_ajax_obj.nonce},
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

							  				if(res.data == "true" || res.data == true){
							  					
							  					$(".infoAlrt").hide();
							  					window.location = site_url+'/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_domains'
							  					$(this).removeAttr('disabled');
							  				}else {
							  					$(".infoAlrt").hide();
							  					swal({
													  icon: 'error',
													  text: 'CDN was alerady deployed or there is some error !!!',
													 timer: 3000
													});

														$(this).removeAttr('disabled');
							  				}
			  		
			  						}  
			  						catch (e) {																
												console.log(e);
												return false;
										} 	
			  		}

		  		});
});

// Check

})( jQuery );