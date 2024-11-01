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
        
        const notifications = {
            
            getNotifications : function(){
                
                let page = $(this).attr('data-page');
                let pagecount = $("#pagecount").val();


                let pageid = $(this).attr('id');

                if(pageid !== 'previous' && pageid !== 'next'){
                        $('.page-link').removeClass('activePage');
                        $(this).addClass('activePage');
                }

                let activeid = $(".page-link.activePage").attr('id');

                if(pageid == 'previous'){
                    let splitpage = activeid.split('page');
                    if( splitpage[1] == 1){
                        page = 1;
                    } else {
                        page = splitpage[1] - 1;
                    }

                    let startPage = Math.floor((page - 1) / 10) * 10 + 1;

                    for (let i = 1; i <= pagecount; i++) {
                      let pageElement = document.getElementById('page' + i);
                      if (pageElement) {
                          pageElement.style.display = 'none';
                      }
                    }

                    for (let i = startPage; i < startPage + 10; i++) {
                      let pageElement = document.getElementById('page' + i);
                      if (pageElement) {
                          pageElement.style.display = 'block';
                      }
                    }
                    console.log("START", startPage)

                    $('.page-link').removeClass('activePage');
                    $("#page"+page).addClass('activePage');
                    
                } else if(pageid == 'next'){
                    if(!activeid) {
                      activeid = 'page1';
                    }
                    let splitpage = activeid.split('page');
                    console.log(activeid)

                    if(pagecount == splitpage[1]){
                        page = pagecount;
                    } else {
                        page = parseInt(splitpage[1]) + 1;
                    }
                    
                    let startPage = Math.floor((page - 1) / 10) * 10 + 1;
                    for (let i = 1; i <= pagecount; i++) {
                        let pageElement = document.getElementById('page' + i);
                        if (pageElement) {
                            pageElement.style.display = 'none';
                        }
                    }
                    for (let i = startPage; i < startPage + 10; i++) {
                        let pageElement = document.getElementById('page' + i);
                        if (pageElement) {
                            pageElement.style.display = 'block';
                        }
                    }

                    $('.page-link').removeClass('activePage');
                    $("#page"+page).addClass('activePage');

                }


                $.ajax({
                    url: sortd_ajax_obj_notifications.ajax_url,
                    data : {'action':'sortd_get_notifications','page' : page,'sortd_nonce' : sortd_ajax_obj_notifications.nonce},
                    type : 'post', 
                    success: function(result){
                        //console.log(result);return false;
                        try{
                          let newtr = '';
                            let remove_after= result.lastIndexOf('}');
                            if(remove_after != -1){
                              let dataresult =  result.substring(0,remove_after +1);
                              let res = JSON.parse(result);
                              if(res.status == true){
                                 
                                  $.each(res.data.notificationList,function(i,j){
                                    newtr += `<tr><td>${j.message}</td><td>${j.platform}</td><td>${j.message_type}</td><td>${j.sent_on}</td></tr>`
                                    //newtr += "<tr><td>"+j.message+"</td><td>"+j.platform+"</td><td>"+j.message_type+"</td><td>"+j.sent_on+"</td></tr>"
                                  });
                              } else {
                                  let strPrepend = '';
                                  strPrepend +=`<div class="notice notice-error is-dismissible"><p>${res.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`;
                                  $(".mesnotify").prepend(strPrepend);
                              }

                              $("#getlist").html(newtr);
                          }else {
                            console.log("This is not valid JSON format")
                          } 

                        } catch (e){
                            console.log(e);
                            return false;
                        } 	

                    }

                });

            },

            sendNotification : function(){
                
                let url_string  = location.href;
                let url = new URL(url_string);
                let postid= url.searchParams.get("post");
                let page= url.searchParams.get("page");
                let site_url = $(this).attr('data-siteurl');
                let valueType = $("#notificationtype").val();
                let slugType = $("#notificationslug").val();
                let title = $("#snippet").val();
                // let platform = $("#notifyform input[type='radio']:checked").val();   
                
                var project_slug = $(this).attr('data-project_slug');
                var current_user = $(this).attr('data-current_user');
                if (typeof gtag === 'function') {
                  gtag('event', 'sortd_action', {
                    'sortd_page_title': 'sortd_notification',
                    'sortd_feature': 'Send Notifications',
                    'sortd_domain': site_url,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                  });
                }

                let platform = "";
                let hasAll = false;

                $("#notifyform input[type='checkbox']:checked").each(function() {
                  let value = $(this).val();
                  if (value === "6") {
                    hasAll = true;
                  } else {
                    platform += value + ",";
                  }
                });

                if (hasAll) {
                  platform = "all";
                } else if (platform !== "") {
                  // Remove the trailing comma
                  platform = platform.slice(0, -1);
                }
                
                //while(true){}

                let nonce = $("#sortd-hidden-nonce").val();
                let public_hostset = $(".hiddenpublichostflag").val();   
                
                window.leavepageflag = true;
             
                if(title.match(/^\s+$/) === null && title.length == 0) {
                
                    $(".snippetRequired").show();
                    return false;
                } else {

                        $(".snippetRequired").hide();
                        $(".snippetRequiredspace").hide();
                       
                      
                        if(public_hostset == "0"){
                            swal({
                                    icon: 'error',
                                    text: "Public host is not setup",
                                   timer: 3000
                                  });
                        } else {
                            $.ajax({
                            url: sortd_ajax_obj_notifications.ajax_url,
                            data : {'action':'sortd_send_notification','post_id' : postid,'slug':slugType,'type':valueType,'title':title,'message':title,'platform':platform,'sortd_nonce' : sortd_ajax_obj_notifications.nonce},
                            type : 'post', 
                            success: function(result){

                                try{
                                    let remove_after= result.lastIndexOf('}');
                                    if(remove_after != -1){
                                      // console.log("if section");
                                      
                                        let dataresult =  result.substring(0,remove_after +1);
                                        let res = JSON.parse(result);
                                        console.log(res);

                                    if(res.status == true){
                                        swal({
                                            icon: 'success',
                                            title: 'Successfully Sent Push Notification',
                                            timer: 3000
                                        }) .then(function() {
                                                location.href = site_url+'/wp-admin/admin.php?page=sortd_notification'
                                        });
                                        $(this).removeAttr('disabled');
                                    }else {
                                            if(public_hostset == 0){
                                                  
                                            } else {
                                                swal({
                                                    icon: 'error',
                                                    text: res.error.message,//'Notification could not be sent !!',
                                                   timer: 3000
                                                  });
                                            }
                                        $(this).removeAttr('disabled');
                                    }
                                  }else {
                                    console.log("This is not valid JSON format")
                                  } 

                                } catch (e){
                                    console.log(e);
                                    return false;
                                } 
                            }
                        });

                     }
                }
            },

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
                  url: sortd_ajax_obj_notifications.ajax_url,
                  data : {'action':'get_notification_stats','sortd_nonce' : sortd_ajax_obj_notifications.nonce},
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
                         
                                                            
                                                          
                  if(chartdataPie.status !== false && chartdataPie !== undefined){
        
                    $.each(chartdataPie.data.today,function(field,val){
                                
                               
                      pieLabelsStats.push(val._id);
                      
                      pieCountsStats.push(val.count);
        
                      if(val._id == 'article_promotion'){
        
                        piecolorsStats.push("#005BF0");
                      
                      } else  if(val._id == 'general'){
        
                        piecolorsStats.push("#ff00cc");
                      
                      } 
        
                    });
        
                    $.each(chartdataPie.data.thisMonth,function(field,val){
                                    
                      
                      pieLabelsStatsMonth.push(val._id );
                      
                      pieCountsStatsMonth.push(val.count);
        
                      if(val._id  == 'article_promotion'){
        
                        piecolorsStatsMonth.push("#005BF0");
                      
                      } else  if(val._id  == 'general'){
        
                        piecolorsStatsMonth.push("#ff00cc");
                      
                      } 
        
                    });
        
                    $.each(chartdataPie.data.total,function(field,val){
                      
                      
                      pieLabelsStatsTilldate.push(val._id );
                      
                      pieCountsStatsTilldate.push(val.count);
        
                      if(val._id  == 'article_promotion'){
        
                        piecolorsStatsTilldate.push("#005BF0");
                      
                      } else  if(val._id  == 'general'){
        
                        piecolorsStatsTilldate.push("#ff00cc");
                      
                      } 
        
                    });
                              //console.log(pieCountsStats);return false;
                                notifications.getPieChartForStats(pieLabelsStats, pieCountsStats,piecolorsStats,'doChartStats');
                                notifications.getPieChartForStats(pieLabelsStatsMonth, pieCountsStatsMonth,piecolorsStatsMonth,'doChartStatsthismonth');
                                notifications.getPieChartForStats(pieLabelsStatsTilldate, pieCountsStatsTilldate,piecolorsStatsTilldate,'doChartStatsTilldate');
                    
                                $('html, body').animate({
                                  scrollTop: $("#notifyform").offset().top - 80
                                }, 1000);
                              }
                    }else {
                            console.log("This is not valid JSON format")
                    } 
                  } catch (e)
                                  {
                                    
                                    console.log(e);
                                    return false;
                                  }
                              }
                    
                  }
        
        
        
                });
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

            loadLeaveAlert : function(){
              window.leavepageflag =true;
              $("#snippet").on('keypress',function(e) {
                window.leavepageflag =false;
              });
             
              $(document).on('keypress',function(e) {
                  window.leavepageflag =false;
              });

              window.addEventListener("beforeunload", function (e) {
                if (window.leavepageflag == false) {

                    var confirmationMessage = 'It looks like you have been editing something. '
                        + 'If you leave before saving, your changes will be lost.';

                    (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                    return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

                }
              });
            }
        }
        
        
        $(".sendPushNotifications").click(notifications.sendNotification);
        $(window).load(notifications.getNotificationStats);
        $(window).load(notifications.loadLeaveAlert);
        $(".page-link").click(notifications.getNotifications);
        var check_array = []; // Define an empty array to store checked values

        var check_array = []; // Define an empty array to store checked values

        $(".radioVal").change(function() {
            // Create an empty array to store the currently checked values
            var currentCheckedValues = [];
        
            // Iterate through all checked checkboxes and add their values to the array
            $(".radioVal:checked").each(function() {
                currentCheckedValues.push($(this).val());
            });
        
            check_array = currentCheckedValues; // Update the check_array with the current checked values
        
      
            if($.inArray("6", check_array) != -1  && (check_array.length > 1)) {
              $(".platform_validation").show();
              $(".sendPushNotifications").attr("disabled",true);
              return false;
            } else {
              $(".platform_validation").hide();
              $(".sendPushNotifications").attr("disabled",false);
            } 


        });
        
        

        
})( jQuery );
