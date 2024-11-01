<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
   if(isset($resultArray['projectDetails']->data->domain->public_host)&& !empty($resultArray['projectDetails']->data->domain->public_host)){
     $host = $resultArray['projectDetails']->data->domain->public_host;
   } else {
      $host = $resultArray['projectDetails']->data->domain->demo_host;
   }

   $date_format = get_option('date_format').' '.get_option('time_format');
   $TimeZoneNameTo = wp_timezone_string();

   $count = sizeof($notificationsAlerts->data->alerts);

  // echo "<pre>";print_r($projectDomains);die;

//echo "<pre>";print_r(($notificationsAlerts->data->alerts));die;
   $get_option = get_option('sortd_'.$projectId.'_redirection_code');
   $get_option_amp = get_option('sortd_'.$projectId.'redirectValueAmp');
//echo $projectId;die;
   if($get_option == 'true' || $get_option_amp == 'true'){
      $redirectionEnabled = 1;
   } else{
      $redirectionEnabled = 0;
   }
   
     foreach($statsNotificationData->data as $k => $v){
   
          if($k == 'thisMonth'){
   
              foreach($v as $key => $value){
   
                  if($value->_id == 'article_promotion'){
                      $notificationStats['thisMonth']['article_promotion'] = $value->count;
                  }
   
                  if($value->_id == 'general'){
                      $notificationStats['thisMonth']['general'] = $value->count;
                  }
              }
   
             
          }
   
          if($k == 'today'){
   
              foreach($v as $key => $value){
   
                  if($value->_id == 'article_promotion'){
                      $notificationStats['today']['article_promotion'] = $value->count;
                  }
   
                  if($value->_id == 'general'){
                      $notificationStats['today']['general'] = $value->count;
                  }
              }
   
             
          }
   
          if($k == 'total'){
   
              foreach($v as $key => $value){
   
                  if($value->_id == 'article_promotion'){
                      $notificationStats['total']['article_promotion'] = $value->count;
                  }
   
                  if($value->_id == 'general'){
                      $notificationStats['total']['general'] = $value->count;
                  }
              }
   
             
          }
      }

      function convertFromBytes($bytes)
         {
             
            if($bytes >= 1024 && $bytes < 1048576){
               $resSize = number_format($bytes/1024) .' MB';
            } else if($bytes >= 1048576){
                $resSize = number_format($bytes/(1024 * 1024)) .' GB';
            } else{
                 $resSize = number_format($bytes) .' KB';
            }
             //$bytes /= 1024;
             // if ($bytes >= 1024 * 1024) {
             //     $bytes /= 1024;
             //     return number_format($bytes / 1024, 1) . ' GB';
             // } elseif($bytes >= 1024 && $bytes < 1024 * 1024) {
             //     return number_format($bytes / 1024, 1) . ' MB';
             // } else {
             //     return number_format($bytes, 1) . ' KB';
             // }

            return $resSize;
         }

      $mediastorage = convertFromBytes($resultArray['mediaStorage']);

   $url = parse_url($host);

   

if(isset($url['scheme']) && $url['scheme'] == 'https'){
  $hostflag = 1;
} else {
    $hostflag = 0;
}

   
   ?>

 

   <style>
      .dashboardIcon{ 
      font-size: 3rem !important;
      padding-top: 10px;
      }
      .dashboardIconCategory{
      font-size: 3rem !important;
      padding-left: 4px;
      }
      .categoryCard{
      font-size: 12px;
      font-weight: 200;
      }
      .globeIcon{
      font-size: 3rem !important;
      }
      .statsfooter{
      padding-left:0px !important;
      padding-right:0px !important;
      }
   /*   .iconStyle{
      padding-right: 10px;
      }*/
      .paraStyleProject{
      color: black;
      }
      .card-category{
      padding-left: 10px;
      }
      .cardSubusers{
      padding-left: 4px !important;
      }
      .card {
      border-radius: 12px;
      }
      .sortddashboardheader{
      color: black;
      }
      .titleClass{
      float: left;
      }
  /*    .cardslabel{
      max-height: 182px;
      }*/
      .cardproject{
      padding: 0px;
      }
      .card-stats{
      margin: 0px;
      }
      .projectlink{
      font-size: 15px;
      }
    
   /*   #adminmenu {
      font-family: "Open Sans", sans-serif;
      }*/
      #adminmenu div.separator {
      /* height: 2px; */
      /* padding: 0; */
      display: none;
      }
   </style>

   <div class="content-section">
      <div class="container-pj">
         <div class="row">
           
            <div class="col-md-12">
               <div class="heading-main">
                  <div class="logoLft">
                     <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
                     <h5> Dashboard </h5>
                  </div>
                  <div class="headingNameTop upBox df_bTn" style="float:right">
                    <button  type="button" class="btn  alertbutton" data-toggle="modal" data-target="#myModal"><i class="bi bi-bell"></i><span class=" pulse badge badge-light notifycount"></span></button>
                    <button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
                 <?php 
                     if($resultArray['projectDetails']->data->update_available == 1){
                          ?>

                          <a href="">Plugin Update Available</a>
                    <?php  } ?>
                  
                  <div class="sortdalerts" style="display:none">
                    <?php 
                   
                    if(!empty($notificationsAlerts->data->alerts)) {

                      // echo "sadasd";die;
                       foreach($notificationsAlerts->data->alerts as $kN => $vN) { 
                             if(isset($vN->read_at)){
                                $color = "unread-not";
                             } else {
                                $color = "read-not";
                             }
                    
                    ?>
                       <div class="readnotify" id="<?php echo $vN->id;?>" style="margin:2px;">
                             <p><?php echo $vN->message;?></p>
                              <?php if(isset($vN->message_details->navigate_url) && !empty($vN->message_details->navigate_url)) { if($vN->message_details->type == 'demo_deployed') {  ?>
                              <span class="vwAlrt"><a target="_blank" href="<?php echo $vN->message_details->navigate_url;?>">View</a></span>
                            <?php } else { ?> 
                                  <span class="vwAlrt"><a target="_blank" href="<?php echo $vN->message_details->navigate_url;?>">View</a></span>
                            <?php } 

                         } ?>
                             <span class="dte"><?php echo date_create($vN->createdat, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format($date_format); ?></span>
                          </div>   
                      
                    <?php } 
                    } else { ?>

                       <div class="readnotify notFnd" id="<?php echo $vN->id;?>" style="margin:2px;">No notifications found </div> 

                    <?php } ?>
                 </div>
            
                  </div>
               </div>
            </div>

             <div class="col-md-12">

            <?php if(get_option('sortd_catsynconeclick_'.$projectId) != 0) {  ?>
              <div class="notStrip">
                <p>Sync more <b>CATEGORIES</b> to add more sections on homepage.</p>
                <a href="<?php echo admin_url();?>admin.php?page=sortd-manage-settings&section=sortd_manage_categories" class="btn-sync">Sync Now <i class="bi bi-arrow-right-circle-fill"></i></a>
              </div>

           <?php } ?>

              <?php if($projectDomains->data->status != 4) {  ?>

              <div class="notStrip">
                <p>Complete your <b>CNAME</b> setup to make the project live !</p>
                <a href="<?php echo admin_url();?>admin.php?page=sortd-manage-settings&section=sortd_manage_domains" class="btn-sync">Start Now <i class="bi bi-arrow-right-circle-fill"></i></a>
              </div>

           <?php } ?>
            </div>
            
         </div>




         <!-- cards start -->
          <div class="row">
            <div class="col-md-6 mt30">
              <div class="cardNdash">
                <div class="cardNdash-box">
                  <h3><?php echo esc_attr($resultArray['projectDetails']->data->name);?></h3>
                  <span><a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings';?>"><i class="bi bi-gear"></i></a></span>
                </div>

                <?php if($hostflag == 1){
                        $scheme = '';
                   } else {
                      $scheme = 'https://';

                   } ?>
                <div class="lnkNdash"><i class="bi bi-link-45deg"></i><?php echo $host;?> <a target="popup" class="projectlink" href="<?php echo $scheme;?><?php echo esc_attr($host);?>" onclick="window.open('<?php echo $scheme;?><?php echo esc_attr($host);?>','popup','width=350,height=600'); return false;"><i class="bi bi-box-arrow-up-right"></i></a></div>
                <hr class="bdr1">

                    <?php if($redirectionEnabled == 1) { ?>
                    <a href="<?php echo admin_url();?>admin.php?page=sortd-manage-settings&section=sortd_redirection"><div class="lnkNdash grIcn "><i class="bi bi-circle-fill"></i> Currently Active <i class="bi bi-box-arrow-right" style="padding-left:10px;color:#0660f0;font-size: 15px;"></i></div></a> 
                  <?php } ?>
                  <?php if($redirectionEnabled == 0) { ?>
                   <a href="<?php echo admin_url();?>admin.php?page=sortd-manage-settings&section=sortd_redirection"><div class="lnkNdash rdIcns " style="font-weight:bold;"><i class="bi bi-circle-fill"></i> Currently Inactive <i class="bi bi-box-arrow-right" style="padding-left:10px;color:#0660f0;font-size: 15px;"></i></div> </a>
                  <?php } ?>
              <!-- grIcn use class for active  -->
              </div>

              <div class="cardNdash mt30">
                <div class="cardNdash-box plBox">
                  <h3>Plan Details</h3>
                  <span><a href="<?php echo admin_url();?>admin.php?page=sortd-settings&section=sortd_plans"><i class="bi bi-box-arrow-up-right"></i></a></span>
                </div>
                <div class="lnkNdash actvPlan_info"><?php echo ucfirst(esc_attr($plandata->data->plan_name));?> <a href="<?php echo admin_url();?>admin.php?page=sortd-settings&section=sortd_plans" class="actvBtn">Upgrade</a></div>
                <hr class="bdr1">
                <div class="lnkNdash">Valid Upto : <span><?php echo date('d-m-Y', strtotime($plandata->data->plan_expire_date));?></span></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="sortCard cardslabel" >
                  <h5 class="sortHed">Quick Links</h5>
                  <div class="sort-body quickActn">
                     <h4></h4>
                     <a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=home';?>">Home Screen Ads  <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=category';?>">Category Ads <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=article';?>">Article Screen Ads <i class="bi bi-arrow-right-circle-fill"></i></a>
                      <a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=general_settings';?>">Header Links  <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=top_menu';?>">Website Menu Setting <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo admin_url();?>edit.php">Sync / Unsync Posts <i class="bi bi-arrow-right-circle-fill"></i></a>
                  </div>
              </div>
            </div>
          </div>
         <!-- cards end -->

         <!-- Top Statistics -->
         <div class="row">
            <div class="col-xl-4 col-sm-6">
               <div class="sortDascard zooM">
                  <a href = "<?php echo admin_url().'edit.php'?>">
                     <div class="sort-body">
                        <input type="hidden" class="hiddenurl" value="<?php echo site_url();?>">
                        <div class="icon-big">
                           <i class="bi bi-file-earmark-image dashboardIcon"></i>
                        </div>
                        <div class="numbersStat">
                           <p class="cardCatgName">Articles</p>
                           <p class="paraStyle"><?php echo esc_attr($resultArray['article']);?></p>
                        </div>
                     </div>
                  </a>
               </div>
            </div>

            <div class="col-xl-4 col-sm-6">
               <div class="sortDascard zooM">
                  <a href = "<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_categories'?>">
                     <div class="sort-body">
                        <div class="icon-big">
                           <i class="bi bi-card-list dashboardIconCategory"></i>
                        </div>
                        <div class="numbersStat">
                           <p class="cardCatgName ">Categories</p>
                           <p class="paraStyle"><?php echo esc_attr($resultArray['category']);?></p>
                        </div>
                     </div>
                  </a>
                  
               </div>
            </div>
      
            <div class="col-xl-4 col-sm-6">
               <div class="sortDascard zooM">
                  <div class="sort-body">
                     <div class="icon-big ">
                        <i class="bi bi-hdd dashboardIcon"></i>
                     </div>
                     <div class="numbersStat">
                        <p class="cardCatgName">Media Storage</p>
                        <p class="paraStyle"><?php echo esc_attr($mediastorage);//round($resultArray['mediaStorage']/1024);?></p>
                     </div>
                  </div>
                  
               </div>
            </div> 
         </div>

   <div class="row">
      <div class="col-lg-6">
         <h2 class="defltHed psh_notif">Push Notifications</h2>
      </div>
       <div class="col-lg-6">
         <span class="notlink"><a href="<?php echo site_url();?>/wp-admin/admin.php?page=sortd_notification"> View all Notifications</a></span>
      </div>
   </div>

      <div class="row">

         <div class="col-md-4">
            <div class="sortCard cardslabel">
               <div class="cardNot">
                  <input type="hidden" class="hiddenurl" value="<?php echo site_url();?>">
                  <h5 class="sortHed">Today</h5>
                  <div class="card-default cardPieChart">
                     <?php if(!isset($notificationStats['today'])){ ?> 
                     <h5 class = "noarticlefoundclass">No Notification sent</h5>
                     <?php } else { ?>
                     <canvas id="doChartStats" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                     <?php } ?>
                  </div>
               </div>
               <?php if(isset($notificationStats['today'])) { ?>
               <div class="statsdef">
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion -</li>
                        <li class="mb-2 datachartli"><?php if(isset($notificationStats['today']['article_promotion'])) { echo esc_attr($notificationStats['today']['article_promotion']); } else { echo 0;} ?></li>
                     </ul>
                  </div>
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General -</li>
                        <li class="mb-2 datachartli"><?php if(isset($notificationStats['today']['general'])) { echo esc_attr($notificationStats['today']['general']); } else { echo 0;} ?></li>
                     </ul>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
         <div class="col-md-4">
            <div class="sortCard cardslabel">
                 <div class="cardNot">
                     <input type="hidden" class="hiddenurl" value="<?php echo site_url();?>">
                     <h5 class="sortHed">This Month</h5>
                     <div class="card-default cardPieChart">
                        <?php if(!isset($notificationStats['thisMonth'])){ ?> 
                        <h5 class = "noarticlefoundclass">No Notification sent</h5>
                        <?php } else { ?>
                        <canvas id="doChartStatsthismonth" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                        <?php } ?>
                     </div>
                  </div>
                  <?php if(isset($notificationStats['thisMonth'])) { ?>
                  <div class="statsdef">
                     <div class="sectnDiv">
                        <ul>
                           <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion -</li>
                           <li class="mb-2 datachartli"><?php if(isset($notificationStats['thisMonth']['article_promotion'])) { echo esc_attr($notificationStats['thisMonth']['article_promotion']); } else { echo 0;} ?></li>
                        </ul>
                     </div>
                     <div class="sectnDiv">
                        <ul>
                           <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General -</li>
                           <li class="mb-2 datachartli"><?php if(isset($notificationStats['thisMonth']['general'])) { echo esc_attr($notificationStats['thisMonth']['general']); } else { echo 0;} ?></li>
                        </ul>
                     </div>
                  </div>
                  <?php } ?>
           </div>
         </div>
         <div class="col-md-4">
             <div class="sortCard cardslabel">
                <div class="cardNot">
                   <input type="hidden" class="hiddenurl" value="<?php echo site_url();?>">
                   <h5 class="sortHed">Till Date</h5>
                   <div class="card-default cardPieChart">
                      <?php if(!isset($notificationStats['total'])){ ?> 
                      <h5 class = "noarticlefoundclass">No Notification sent</h5>
                      <?php } else { ?>
                      <canvas id="doChartStatsTilldate" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                      <?php } ?>
                   </div>
                </div>
                <?php if(isset($notificationStats['total'])) { ?>
                <div class="statsdef ">
                   <div class="sectnDiv">
                      <ul>
                         <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion -</li>
                         <li class="mb-2 datachartli"><?php if(isset($notificationStats['total']['article_promotion'])) { echo esc_attr($notificationStats['total']['article_promotion']); } else { echo 0;} ?></li>
                      </ul>
                   </div>
                   <div class="sectnDiv">
                      <ul>
                         <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General -</li>
                         <li class="mb-2 datachartli"><?php if(isset($notificationStats['total']['general'])) { echo esc_attr($notificationStats['total']['general']); } else { echo 0;} ?></li>
                      </ul>
                   </div>
                </div>
                <?php } ?>
             </div>
         </div>
    </div>
         <div class="row">
            <div class="col-xl-6 col-md-12">
               <!-- Sales Graph -->
            
         
         

               <!-- start Daily Ingested Articles Sales Graph -->
               <div class="sortCard cardslabel" >
                  <h5 class="sortHed mb3">Daily Ingested Articles</h5>
                  <div class="sort-body">
                     <canvas id="bar3" class="chartjs" style="display: block;width:400px !important;height: 268px;"></canvas>
                  </div>
               </div>
               <!-- end Daily Ingested Articles Sales Graph -->

               <!-- start Types of Articles Graph -->
               <div class="sortCard cardPieChart cardslabel non_min_heigt">
                  <h5 class="sortHed mb3">Types of Articles</h5>
                  <div class="card-body" >
                     <?php if($resultArray['article'] == 0){ ?> 
                     <h5 style="text-align: center;">No articles found</h5>
                     <?php } else { ?>
                     <canvas id="doChart" ></canvas>
                     <?php } ?>
                  </div>
                  <?php if($resultArray['article'] > 0){ ?> 
                  <div class="sectnDiv">
                        <ul class="colrInfo">
                           <li class="rd"><i class="bi bi-circle-fill"></i>News</li>
                           <li class="grn"><i class="bi bi-circle-fill"></i>Gallery</li>
                           <li class="yel"><i class="bi bi-circle-fill"></i>Video</li>
                           <li class="blu"><i class="bi bi-circle-fill"></i>Audio</li>
                        </ul>
                  </div>
                  <?php } ?>
               </div>
               <!-- end Types of Articles Graph -->


            </div>

            <div class="col-xl-6 col-md-12">
               <!-- To Do list -->
               <div class="sortCard todo-table cardslabel non_min_heigt" id="todo" data-scroll-height="550">
                  <h5 class="sortHed mb3">Recent Articles</h5>
                  <div class="sort-body slim-scroll">
                     <div class="todo-single-item d-none" id="todo-input">
                        <form >
                           <div class="form-group">
                              <input type="text" class="form-control" placeholder="Enter Todo" autofocus>
                           </div>
                        </form>
                     </div>
                     <div class="todo-list" id="todo-list">
                        <table class="table">
                           <thead class="text-primary"></thead>
                           <tbody>
                              <?php if(!empty($resultArray['recentArticles'])){ foreach($resultArray['recentArticles'] as $k => $v){ 
                                 if($v->status == true){
                                 
                                      $class = "";
                                 } else if($v->status == false){
                                      $class = "table-dlt";
                                 }
                                 
                                 ?>
                              <tr id="row_<?php echo wp_kses_data($v->guid);?>" class="<?php echo esc_attr($class);?>">
                                 <td class="text-left raImg"><?php if(!empty($v->thumburl)){ ?><div class="bgThumImg" style="background-image:url(<?php echo esc_attr($v->thumburl);?>)" ></div><?php } ?></td>
                                 <td class="text-left"><?php echo esc_attr($v->title);?></td>

                                 <td class="text-left pd0">

                                  <?php if($v->status == true){ 

                                    $style = "display:block";

                                  } else if($v->status == false) {

                                     $style = "display:none";


                                  } ?>


                                    <a title="Delete" href="javascript:void(0)" class="deleteClass" id="<?php echo wp_kses_data($v->guid);?>" data-nonce="<?php echo wp_create_nonce('rw-sortd-delete-article'.$v->guid);?>" style="<?php echo esc_attr($style);?>">
                                       <i class="bi bi-trash-fill"></i>

                                    </a>
                                    <?php     if($v->status == true) { 
                                       $style1 = "display:none";
                                       
                                       }  else if($v->status == false) {
                                       
                                       $style1 = "display:block";
                                       
                                       
                                       } ?>

                                  

                                    <a title="Restore" href="javascript:void(0)" class="imageArticle" id="imageArticlerestore<?php echo esc_attr($v->guid);?>"  data-nonce="<?php echo wp_create_nonce('rw-sortd-sync-article'.$v->guid);?>" data-guid="<?php echo esc_attr($v->guid);?>" style="<?php echo esc_attr($style1);?>">
                                       <span><img src="<?php echo plugin_dir_url( __DIR__ );?>css/restore.png"></span>


                                       <!-- <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="trash-restore-alt" class="svg-inline--fa fa-trash-restore-alt fa-w-14 " role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                          <path fill="currentColor" d="M32 464a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128H32zm91.31-172.8l89.38-94.26a15.41 15.41 0 0 1 22.62 0l89.38 94.26c10.08 10.62 2.94 28.8-11.32 28.8H256v112a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V320h-57.37c-14.26 0-21.4-18.18-11.32-28.8zM432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z"></path>
                                       </svg> -->
                                    </a>
                           
                                 </td>
                              </tr>
                              <?php } 
                                 } else { ?>
                              <tr>
                                 <td>
                                    <h5 style="text-align: center;">No articles found</h5>
                                 </td>
                              </tr>
                              <?php } ?>  
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="mt-3"></div>
               </div>
            </div>
         </div>

      </div>

   </div>


    
