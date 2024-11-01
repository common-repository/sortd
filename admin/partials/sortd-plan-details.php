<?php

/**
 * Provide a plan details view for the plugin
 *
 * This file is used to markup the plan details aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */

?>

<style>
   .editmode {display:none;}
   .featureshead{
   text-align: center;
   }
   .featureheaderstable{
   font-weight: bold;
   }
</style>
<div class="content-section">
<div class="container-pj">
   <div class="menuContent-area">
      <div id="General" class="tabcontent" style="display:block">
         <div class="inerContent-body">
            <!-- main heading tabing start -->
            <div class="heading-main">
               <div class="logoLft">
                  <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
                  <h5> Plan Details</h5>
               </div>

               <div class="headingNameTop">
                   <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0-l" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
                  <button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
                </div>

            </div>

           
            <!-- content-section-inner start -->
            <div class="inner-sectn-body">
            <?php if (isset($plan_data->data->expire_status) && $plan_data->data->expire_status === 1 && $plan_data->data->plan_type === 'trial') { ?> 
               
               <div class="notStrip notStrip_ov">
                <p> <b> <?php echo wp_kses_data(ucfirst($plan_data->data->plan_type));?> </b> Plan is Expiring in <?php echo wp_kses_data($msg);?> </p>
                <a href="<?php echo wp_kses_data($console_url);?>/plans/buyplan/<?php echo wp_kses_data($slug);?>" class="btn-sync" style="width:auto;">Upgrade Now <i class="bi bi-arrow-right-circle-fill"></i></a>
              </div>

            <?php } ?> 

            <?php if (isset($plan_data->data->expire_status) && $plan_data->data->expire_status === 1 && $plan_data->data->plan_type !== 'trial') { ?> 
               <div class="notice notice-error is-dismissible configPopup">
                  <p>Your <?php echo wp_kses_data(ucfirst($plan_data->data->plan_type));?> Plan is Expiring in <?php echo wp_kses_data($msg);?> . <a href="<?php echo wp_kses_data($console_url);?>/plans/buyplan/<?php echo wp_kses_data($slug);?>">Renew Now</a></p>
               </div>
            <?php } ?> 


             <?php if (isset($plan_data->data->expire_status) && $plan_data->data->expire_status === 2 && $plan_data->data->plan_type === 'trial') { ?> 
               <div class="notice notice-error is-dismissible configPopup">
                  <p>Your <?php echo wp_kses_data(ucfirst($plan_data->data->plan_type));?> Plan has Expired on <?php echo wp_kses_data(gmdate('d-m-Y',strtotime($plan_data->data->plan_expire_date)));?>. <a href="<?php echo wp_kses_data($console_url);?>/plans/buyplan/<?php echo wp_kses_data($slug);?>">Buy Now</a></p>
               </div>
            <?php } ?> 

            <?php if (isset($plan_data->data->expire_status) && $plan_data->data->expire_status === 2 && $plan_data->data->plan_type !== 'trial') { ?> 
               <div class="notice notice-error is-dismissible configPopup">
                  <p>Your <?php echo wp_kses_data(ucfirst($plan_data->data->plan_type));?> Plan has Expired on <?php echo wp_kses_data(gmdate('d-m-Y',strtotime($plan_data->data->plan_expire_date)));?>. <a href="<?php echo esc_url($console_url);?>/plans/buyplan/<?php echo wp_kses_data($slug);?>">Renew Now</a></p>
               </div>
            <?php } ?> 


            <div class="content-card content-card-plus">

               <div class="dfltDiv" style="padding-top:0px;"><div class="pLanHead">
                  <h4 class="card-title" style="font-weight: bold;">Select a plan for your project </h4>

                  <!-- <a href="mailto:helpdesk@sortd.mobi" target="_blank" class="cutmPlanBtn">Contact us<span style="opacity:0.5;">For Enterprise Plan</span></a> -->

                </div>
               
               <div class="plnCrd">
                    <div class="row">


                      <?php echo wp_kses_post($html_plan->data);?>
                    </div>
                  </div>


                <div class="notStrip notStrip_ov margintop_40">
                  
                  <p> <b> NOT FITTING IN ANY ABOVE PLANS ? </b> </p>

                  <a target="_blank" href="https://www.sortd.mobi/contact/" class="btn-sync" style="width:auto;"> Contact Us <i class="bi bi-headset"></i></a>

              </div>


              <div class="card-footer-wp">* &nbsp;&nbsp;&nbsp;&nbsp; All pricing are exclusive of GST(Goods &amp; Services Tax)<br>** &nbsp;&nbsp; Features having monthly limits.</div>


            </div>
            
              <!--  <div id="chartContainer" style="height: 300px; width: 100%;"></div> -->
               <!-- content menu left start -->
               <div class="contentMenu-left renameDiv w100" id="stickContnt">
                  <div class="page-section-b" id="1">
                     <!-- <h2 class="navigation__link active featureheaderstable"></h2> -->
                     <div class="content-card content-card-plus">
                        <div class="form-box">
                           <!-- <?php if(empty($response['data'])) { ?> <h5>No categories synced</h5><?php } ?>
                              <input type ="hidden" id="siteurl" value="<?php echo wp_kses_data(site_url());?>"> -->
                           <?php if(isset($plan_data->error->errorCode)){ ?>
                           <div class="notice notice-error is-dismissible curlErrorDiv">
                              <p><?php echo wp_kses_data($plan_data->error->message);?></p>
                           </div>
                           <?php  } else { ?>

                          <h4 class="card-title"> <b> Active Plan Details for <?php echo esc_attr($plan_data->data->project_name);?> </b> </h4>

                          <div class="row">
                          <div class="col-md-6">

                           <table class="table table_inside">
                              <thead class="bgHed">
                                 
                                 <input type="hidden" id="hiddenstartdate" value="<?php echo wp_kses_data($plan_data->data->plan_start_date);?>">
                                 <input type="hidden" id="hiddenenddate" value="<?php echo wp_kses_data($plan_data->data->plan_expire_date);?>">
                                 <input type="hidden" id="hiddencurrentdate" value="<?php echo wp_kses_data($date->format('Y-m-d\TH:i:s\Z'));?>">
                                 <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col"></th>
                                    <th scope="col">Plan Details</th>
                                    <!-- <th class="colsuccess" style="display:none;"></th> -->
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php 
                                    if ($plan_data->status):
                                    
                                    	
                                    ?>
                                 <tr>
                                    <td><span id="" class="">Plan Name</span>
                                    </td>
                                    <td></td>
                                    <td><span id="" class=""><?php echo wp_kses_data($plan_data->data->plan_name);?></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td><span id="" class="">Plan Duration</span>
                                    </td>
                                    <td></td>
                                    <td><span id="" class=""><?php echo wp_kses_data($plan_data->data->plan_type);?></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td><span id="" class="">Plan Start Date</span>
                                    </td>
                                    <td></td>
                                    <td><span id="" class=""><?php echo wp_kses_data(gmdate('d-m-Y h:i:s A', strtotime($plan_data->data->plan_start_date)));?></span>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td><span id="" class="">Plan Expiry Date</span>
                                    </td>
                                    <td></td>
                                    <td><span id="" class=""><?php echo wp_kses_data(gmdate('d-m-Y h:i:s A', strtotime($plan_data->data->plan_expire_date)));?></span>
                                    </td>
                                 </tr>

                                 <?php if(isset($plan_data->data->is_extended) && $plan_data->data->is_extended === 1) {  ?>
                                 <tr class="extendedplan"><td></td><td><span style="color:green">Your plan has been extended till <?php echo wp_kses_data(gmdate('d-m-Y', strtotime($plan_data->data->extended_date)));?></span></td><td></td></tr>
                              <?php } ?>
                                 <?php 
                                    endif; 
                                    ?>


                              </tbody>
                           </table> 

                         </div>

                         <div class="col-md-6">

                          <div class="sortCard cardslabel mt-0 subscriptioncard">
                            <h5 class="sortHed">Subscription Plan</h5>
                            <div id="chartContainer">
                                <canvas id="subscriptionpiechart" height="133" width="266" style="display: block; width: 418px; height: 210px;" class="chartjs-render-monitor"></canvas>
                            </div>
                            <div class="sectnDiv">
                              <ul class="colrInfo">
                                 <li class="pas"><i class="bi bi-circle-fill"></i>Days Passed</li>
                                 <li class="lft"><i class="bi bi-circle-fill"></i>Days Left</li>
                              </ul>
                            </div>
                          </div>

                         </div> 

                       </div>


                         <div class="col-md-12">

                           <table class="table table_inside">
                              <thead class="bgHed">
                                 <!-- <h5 class="sortHed mb3 mt-30">Features</h5> -->
                                 <tr>
                                    <th scope="col">Feature Group</td>
                                    <th scope="col">Feature Name</td>
                                    <th scope="col">Type</td>
                                    <th scope="col">Quota</td>
                                    <th scope="col">Used</td>
                                    <!-- <th class="colsuccess" style="display:none;"></th> -->
                                 </tr>
                                 <?php foreach($plan_data->data->features as $key => $value)  { ?>
                              </thead>
                              <tbody>
                                 <?php  foreach($value as $k => $v){ ?>
                                 <tr>
                                    <td><?php echo wp_kses_data($key);?></td>
                                    <td><?php echo wp_kses_data($v->name);?></td>
                                    <td><?php echo wp_kses_data($v->type);?></td>
                                    <td><?php echo wp_kses_data($v->quota);?> 
                                    <?php echo ($v->name==='Media Upload Limit') ? 'GB' : '' ?>
                                    <?php echo (empty($v->unit)) ? '' : '('.wp_kses_data(ucfirst($v->unit)).')';?> 
                                    </td>
                                    <td><?php echo wp_kses_data($v->quota_used);?></td>
                                 </tr>
                                 <?php 
                                    $key = '';
                                 } ?>
                              </tbody>
                              <?php } ?>
                           </table> <div>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
                  <!-- content menu left end -->
               </div>
               <!-- content section inner end -->
            </div>
	             <div class="col-md-6">
		            
		        </div>
        <!--     <div class="card-default cardPieChart">
             <canvas id="subscriptionpiechart" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                    
            </div> -->


         </div>
      </div>
   </div>
</div>
<script type="module">

   window.onload = function() {
   
   	var plantrialstart = jQuery("#hiddenstartdate").val();
   	var plantrialend = jQuery("#hiddenenddate").val();
   	var currentdate = jQuery("#hiddencurrentdate").val();
   	

   
   		var days = daysdifference(plantrialstart, currentdate);  
   // Add two dates to two variables    
   


      
   function daysdifference(firstDate, secondDate){  
      var startDay = new Date(firstDate);  
      var endDay = new Date(secondDate);  
    
   // Determine the time difference between two dates     
      var millisBetween = startDay.getTime() - endDay.getTime();  
    
   // Determine the number of days between two dates  
      var days = millisBetween / (1000 * 3600 * 24);  

     
    
   // Show the final number of days between dates     
      return Math.floor(Math.abs(days));  
   } 
   
   var dayscurrent = daysdifferencecurrent(currentdate, plantrialend); 
   
   
   
   function daysdifferencecurrent(firstDate, secondDate){  
      var startDay = new Date(firstDate);  
      var endDay = new Date(secondDate);

    //  console.log(startDay,endDay);return false;

    
   // Determine the time difference between two dates     
      var millisBetween = startDay.getTime() - endDay.getTime();  
    
   // Determine the number of days between two dates  
      var days = millisBetween / (1000 * 3600 * 24);  
    
   // Show the final number of days between dates     
      return Math.floor(Math.abs(days));  
   } 

if(Date.parse(plantrialend) === Date.parse(currentdate)){
 
}

if(Date.parse(plantrialstart) === Date.parse(currentdate)){

  
}

var total = daysdifference(plantrialstart, plantrialend); 

if(Date.parse(plantrialend) >= Date.parse(currentdate)){

     
     getPieChartForStats(days, dayscurrent);

     console.log(days,dayscurrent,total);
   
} else {
    jQuery(".subscriptioncard").hide();
}

      function getPieChartForStats(days, dayscurrent){

               

                          /*======== 11. DOUGHNUT CHART ========*/
                              var doughnut = document.getElementById("subscriptionpiechart");
                              if (doughnut !== null) {
                                var myDoughnutChart = new Chart(doughnut, {
                                  type: "doughnut",
                                  data: {
                                    labels: ['Days passed','Days left'],
                                    datasets: [
                                      {
                                        label:['Days passed','Days left'],
                                       data: [days,dayscurrent],
                                     //data:[22,8],
                                        backgroundColor: ["#0e60ef", "#f166cc"],
                                        borderWidth: 1
                                       
                                      }
                                    ]
                                  },
                                  options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    legend: {
                                      display: false
                                    },
                                    cutoutPercentage: 75,
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
   
   }
   
   

</script>