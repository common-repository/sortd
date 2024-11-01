<?php

/**
 * Provide a home dashboard view for the plugin
 *
 * This file is used to markup the home dashboard aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */

$wp_domain = get_site_url();
$sortd_current_user = wp_get_current_user()->display_name;
$project_details = Sortd_Helper::get_cached_project_details();
$project_slug = $project_details->data->slug;

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
      .progBox {
    width: 100%;
    float: left;
    padding: 0px 20px;
}
h3.complte_percent {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 0px;
    font-family: 'Barlow', sans-serif;
}
.top_links a {
    text-transform: capitalize;
    margin-right: 10px;
    font-size: 15px;
    display: block;
    font-family: 'Barlow', sans-serif;
    font-weight: 500;
}
.top_links span {
    font-size: 12px;
    width: 100%;
    float: left;
    font-family: 'Barlow', sans-serif;
}
.cardNdash-1{
   width: 100%;
   float: left;
}
/*.top_links .topLink_card:nth-of-type(2) {
    margin-left: 3%;
}
.top_links .topLink_card:nth-of-type(1) {
    margin-right: 3%;
}

.topLink_card {
    width: 47%;
    float: left;
    background: #f9f9f9;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    min-height:200px;
    position: relative;
}
.top_links span b {
    width: 100%;
    float: left;
    font-weight: 400;
    text-transform: capitalize;
    color: #5a5a5a;
    margin: 3px 0px;
}
.topLink_card a i{
     float: right;
    font-size: 18px;
    color: #0660f0;
}
.top3link {
    position: absolute;
    bottom: 10px;
    right: 10px;
    margin-right: 0px;
}*/
.top_links .topLink_card:nth-of-type(3) {
    border-radius: 0px 0px 10px 10px;
}
.topLink_card {
    width: 100%;
    float: left;
    background: #f9f9f9;
    border: 1px solid #eee;
    padding: 20px;
    position: relative;
}
.top_links span b {
    font-weight: 400;
    text-transform: capitalize;
    color: #5a5a5a;
    margin-right: 10px;
    font-family: 'Barlow', sans-serif;
    font-size: 13px;
}
.topLink_card a i{
     float: right;
    font-size: 18px;
    color: #0660f0;
}
.top3link {
    position: absolute;
    top: 20px;
    right: 10px;
    /* margin-right: 0px; */
}
.topLink_card:hover {
    background: #0660f0;
}
.topLink_card:hover a{
   color: #fff;
}
.topLink_card:hover a i{
   color: #fff;
}
.topLink_card:hover span b{
   color: #fff;
   font-weight: 500;
}
.bt0 {
    border-radius: 10px 10px 0px 0px;
}



/* Banner CSS start*/


.bluebox{
   background: #0660f0;
   height: 150px;
   width: 8%;
   border-radius: 8px;
   margin: 5px;
   

}
#mddash{
  flex: 0 0 auto;
  width: 100%;
  display: inline-flex;
}
.priceupdate {
  margin-left: 30%;
  height: 30%;
  width: 80%;
}
/* Banner CSS end*/


/* consent management css */

.consent {
  margin-top: 185px;
}
.sort_consent_quicklink {
  padding: 12px;
}
/* /consent management csss */



   </style>
   <div class="content-section">
      <div class="container-pj">
         <div class="row">
           
            <div class="col-md-12">
               <div class="heading-main">
                  <div class="logoLft">
                     <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
                     <h5> Dashboard </h5>
                  </div>
                  <div class="headingNameTop upBox df_bTn" style="float:right">
                  <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
                    <button  type="button" class="btn  alertbutton" data-toggle="modal" data-target="#myModal"><i class="bi bi-bell"></i><span class=" pulse badge badge-light notifycount"></span></button>
                    <button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
                 <?php 
                     if(isset($project_details->data->update_available) && $project_details->data->update_available === true){
                          ?>

                          <a href="">Plugin Update Available</a>
                    <?php  } ?>
                  
                  <div class="sortdalerts" style="display:none">
                    <?php 
                   
                    if(!empty($get_alerts_data->data->alerts)) {

                       foreach($get_alerts_data->data->alerts as $kN => $vN) { 
                             if(isset($vN->read_at)){
                                $color = "unread-not";
                             } else {
                                $color = "read-not";
                             }
                    
                    ?>
                       <div class="readnotify" id="<?php echo wp_kses_data($vN->id);?>" style="margin:2px;">
                             <p><?php echo wp_kses_data($vN->message);?></p>
                              <?php if(isset($vN->message_details->navigate_url) && !empty($vN->message_details->navigate_url)) { if($vN->message_details->type === 'demo_deployed') {  ?>
                              <span class="vwAlrt"><a target="_blank" href="<?php echo wp_kses_data($vN->message_details->navigate_url);?>">View</a></span>
                            <?php } else { ?> 
                                  <span class="vwAlrt"><a target="_blank" href="<?php echo wp_kses_data($vN->message_details->navigate_url);?>">View</a></span>
                            <?php } 

                         } ?>
                             <span class="dte"><?php echo wp_kses_data(date_create($vN->createdat, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($timezone_name_to))->format($date_format)); ?></span>
                          </div>   
                      
                    <?php } 
                    } else { ?>

                       <div class="readnotify notFnd" id="" style="margin:2px;">No notifications found </div> 

                    <?php } ?>
                 </div>
            
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12" >
                  <?php 
                  echo wp_kses_post($banner_html_data);
                  ?>
                  
               </div>
            </div>

             <div class="col-md-12">

                  <?php if(isset($project_details->data->id)  && get_option('sortd_one_click_manual_sync'.$project_details->data->id) === '0') {  ?>
                  <div class="notStrip">
                     <p>Sync more <b>CATEGORIES</b> to add more sections on homepage.</p>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-manage-settings&section=sortd_manage_categories",SORTD_NONCE));?>" class="btn-sync">Sync Now <i class="bi bi-arrow-right-circle-fill"></i></a>
                  </div>

               <?php } ?>

                  <?php if(isset($cname_response->data) && !empty($cname_response->data)){
                    if(($cname_response->data->allowPublicHostSetup === true) && (!isset($project_domains->data->public_host) || empty($project_domains->data->public_host))) {  ?>

                  <div class="notStrip">
                     <p>Complete your <b>CNAME</b> setup to make the project live !</p>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-manage-settings&section=sortd_manage_domains",SORTD_NONCE));?>" class="btn-sync">Start Now <i class="bi bi-arrow-right-circle-fill"></i></a>
                  </div>

               <?php } } ?>

               <?php if(isset($cname_response->data) && !empty($cname_response->data)){
                  
               if($cname_response->data->allowPublicHostSetup !== true){ ?>

               <div class="notStrip">
                     <p>To take your <b>PUBLIC HOST </b> live , contact us !</p>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-manage-settings&section=contact-us",SORTD_NONCE));?>" target="_blank" class="btn-sync">Contact Us<i class="bi bi-arrow-right-circle-fill"></i></a>
                  </div>

               <?php } }?>
            </div>
            
         </div>


         





         <!-- cards start -->
          <div class="row">
            <div class="col-md-6 mt30">
              <div class="cardNdash">
                <div class="cardNdash-box">
                  <h3><?php if(isset($project_details->data->name)) { echo esc_attr($project_details->data->name);} ?></h3>
                  <span><a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings',SORTD_NONCE));?>"><i class="bi bi-gear"></i></a></span>
                </div>

                <?php if($host_flag === 1){
                        $scheme = '';
                   } else {
                      $scheme = 'https://';

                   } ?>
                <div class="lnkNdash"><i class="bi bi-link-45deg"></i><?php echo wp_kses_data($host);?> <a target="popup" class="projectlink" href="<?php echo wp_kses_data($scheme);?><?php echo wp_kses_data($host);?>" onclick="window.open('<?php echo wp_kses_data($scheme);?><?php echo wp_kses_data($host);?>','popup','width=350,height=600'); return false;"><i class="bi bi-box-arrow-up-right"></i></a></div>
                <hr class="bdr1">

                    <?php  if($redirection_enabled === 1) { ?>
                    <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_redirection',SORTD_NONCE));?>"><div class="lnkNdash grIcn "><i class="bi bi-circle-fill"></i> Currently Active <i class="bi bi-box-arrow-right" style="padding-left:10px;color:#0660f0;font-size: 15px;"></i></div></a> 
                  <?php } ?>
                  <?php if($redirection_enabled === 0) { ?>
                   <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_redirection',SORTD_NONCE));?>"><div class="lnkNdash rdIcns " style="font-weight:bold;"><i class="bi bi-circle-fill"></i> Currently Inactive <i class="bi bi-box-arrow-right" style="padding-left:10px;color:#0660f0;font-size: 15px;"></i></div> </a>
                  <?php } ?>
              <!-- grIcn use class for active  -->
              </div>

              <div class="consent"> 
                  <div class="cardNdash">
                     <div class="cardNdash-box plBox">
                        <h3>Consent Management</h3>
                        <span></span>
                     </div>
                     <div class="sort_consent_quicklink" >
                        
                        <div class="sort-consent quickActn">
                           <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=general_settings&navigate=design',SORTD_NONCE));?>">Click here to configure the setting for Consent Management.<i class="bi bi-arrow-right-circle-fill"></i></a>
                        </div>
                     </div>     
                  </div>
               </div>

              <div class="cardNdash mt30 bt0">
                <div class="cardNdash-box plBox">

                  <h3>Performance Boost Meter</h3>
                  <span></span>
  
               </div>
               <div class="progBox">
                  <h3 class="complte_percent"></h3>
                  <div class="prgDiv">
                        <div class="progress configprogress">
                           <div class="progress-bar progress-bar-config progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                        </div>
                        <p class="linkstoadd"></p>
                  </div>
               </div>

              </div>

              <input type="hidden" id="nonce_input" value="<?php echo esc_attr(wp_create_nonce(SORTD_NONCE)); ?>">

              <div class="cardNdash-1">
               <div class="top_links "></div>
              </div>
            </div>

            
            <div class="col-md-6">

               <div class="cardNdash mt30">
                   <div class="cardNdash-box plBox">
                     <h3>Plan Details</h3>
                     <span><a href="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-settings&section=sortd_plans",SORTD_NONCE));?>><i class="bi bi-box-arrow-up-right"></i></a></span>
                   </div>
                   <div class="lnkNdash actvPlan_info"><?php if(isset($plans_data->data->plan_name)) { echo wp_kses_data(ucfirst($plans_data->data->plan_name));} ?> <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-settings&section=sortd_plans",SORTD_NONCE));?>" class="actvBtn">Upgrade</a></div>
                   <hr class="bdr1">
                   <div class="lnkNdash">Valid Upto : <span><?php if(isset($plans_data->data->plan_expire_date)) { echo wp_kses_data(gmdate('d-m-Y', strtotime($plans_data->data->plan_expire_date)));} ?></span></div>
               </div>
              <div class="sortCard cardslabel" >
                  <h5 class="sortHed">Quick Links</h5>
                  <div class="sort-body quickActn">
                     <h4></h4>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=home&navigate=ads',SORTD_NONCE));?>">Home Screen Ads  <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=category&navigate=ads',SORTD_NONCE));?>">Category Ads <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=article&navigate=ads',SORTD_NONCE));?>">Article Screen Ads <i class="bi bi-arrow-right-circle-fill"></i></a>
                      <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=general_settings&navigate=social',SORTD_NONCE));?>">Header Links  <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=top_menu',SORTD_NONCE));?>">Website Menu Setting <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo wp_kses_data(admin_url());?>edit.php">Sync / Unsync Posts <i class="bi bi-arrow-right-circle-fill"></i></a>
                     <a href="<?php echo wp_kses_data(wp_nonce_url(site_url()."/wp-admin/admin.php?page=sortd_notification",SORTD_NONCE));?>> View all Notifications<i class="bi bi-arrow-right-circle-fill"></i></a>
                  </div>
              </div>
            </div>
          </div>
         <!-- cards end -->

         <!-- Top Statistics -->
         <div class="row">
            <div class="col-xl-3 col-sm-6">
               <div class="sortDascard zooM">
                  <a href = "<?php echo wp_kses_data(admin_url()).'edit.php'?>">
                     <div class="sort-body">
                        <input type="hidden" class="hiddenurl" value="<?php echo wp_kses_data(site_url());?>">
                        <div class="icon-big">
                           <i class="bi bi-file-earmark dashboardIcon"></i>
                        </div>
                        <div class="numbersStat">
                           <p class="cardCatgName">Articles</p>
                           <p class="paraStyle"><?php if(isset($get_stats_data['article'])) { echo esc_attr($get_stats_data['article']);} ?></p>
                        </div>
                     </div>
                  </a>
               </div>
            </div>

            <div class="col-xl-3 col-sm-6">
               <div class="sortDascard zooM">
                  <a href = "<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_categories',SORTD_NONCE))?>">
                     <div class="sort-body">
                        <div class="icon-big">
                           <i class="bi bi-card-list dashboardIconCategory"></i>
                        </div>
                        <div class="numbersStat">
                           <p class="cardCatgName ">Categories</p>
                           <p class="paraStyle"><?php if(isset($get_stats_data['category'])) { echo esc_attr($get_stats_data['category']);} ?></p>
                        </div>
                     </div>
                  </a>
                  
               </div>
            </div>
      
            <div class="col-xl-3 col-sm-6">
               <div class="sortDascard zooM">
                  <div class="sort-body">
                     <div class="icon-big ">
                        <i class="bi bi-hdd dashboardIcon"></i>
                     </div>
                     <div class="numbersStat">
                        <p class="cardCatgName">Media Storage</p>
                        <p class="paraStyle"><?php  if(isset($get_stats_data['media_storage'])) { echo esc_attr($get_stats_data['media_storage']);} ?></p>
                     </div>
                  </div>
                  
               </div>
            </div> 

            <div class="col-xl-3 col-sm-6">
               <div class="sortDascard zooM">
               <a href = "<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_paid_articles',SORTD_NONCE))?>">
                  <div class="sort-body">
                     <div class="icon-big ">
                        <i class="bi bi-file-earmark-lock"></i>
                     </div>
                     <div class="numbersStat">
                        <p class="cardCatgName">Paid Articles</p>
                        <p class="paraStyle"><?php  if(isset($get_stats_data['paid_article_count'])) { echo esc_attr($get_stats_data['paid_article_count']);} ?></p>
                     </div>
                  </div>
                  </a>  
               </div>
            </div> 
         </div>

   <div class="row">
      <div class="col-lg-6">
         <h2 class="defltHed psh_notif">Push Notifications</h2>
      </div>
      
   </div>

      <div class="row">

         <div class="col-md-4">
            <div class="sortCard cardslabel">
               <div class="cardNot">
                  <input type="hidden" class="hiddenurl" value="<?php echo wp_kses_data(site_url());?>">
                  <h5 class="sortHed">Today</h5>
                  <div class="card-default cardPieChart">
                     <?php if(!isset($stats_notifications_data['today'])){ ?> 
                     <h5 class = "noarticlefoundclass">No Notification sent</h5>
                     <?php } else { ?>
                     <canvas id="doChartStats" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                     <?php } ?>
                  </div>
               </div>
               <?php if(isset($stats_notifications_data['today'])) { ?>
               <div class="statsdef">
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion -</li>
                        <li class="mb-2 datachartli"><?php if(isset($stats_notifications_data['today']['article_promotion'])) { echo esc_attr($stats_notifications_data['today']['article_promotion']); } else { echo 0;} ?></li>
                     </ul>
                  </div>
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General -</li>
                        <li class="mb-2 datachartli"><?php if(isset($stats_notifications_data['today']['general'])) { echo esc_attr($stats_notifications_data['today']['general']); } else { echo 0;} ?></li>
                     </ul>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
         <div class="col-md-4">
            <div class="sortCard cardslabel">
                 <div class="cardNot">
                     <input type="hidden" class="hiddenurl" value="<?php echo wp_kses_data(site_url());?>">
                     <h5 class="sortHed">This Month</h5>
                     <div class="card-default cardPieChart">
                        <?php if(!isset($stats_notifications_data['thisMonth'])){ ?> 
                        <h5 class = "noarticlefoundclass">No Notification sent</h5>
                        <?php } else { ?>
                        <canvas id="doChartStatsthismonth" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                        <?php } ?>
                     </div>
                  </div>
                  <?php if(isset($stats_notifications_data['thisMonth'])) { ?>
                  <div class="statsdef">
                     <div class="sectnDiv">
                        <ul>
                           <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion -</li>
                           <li class="mb-2 datachartli"><?php if(isset($stats_notifications_data['thisMonth']['article_promotion'])) { echo esc_attr($stats_notifications_data['thisMonth']['article_promotion']); } else { echo 0;} ?></li>
                        </ul>
                     </div>
                     <div class="sectnDiv">
                        <ul>
                           <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General -</li>
                           <li class="mb-2 datachartli"><?php if(isset($stats_notifications_data['thisMonth']['general'])) { echo esc_attr($stats_notifications_data['thisMonth']['general']); } else { echo 0;} ?></li>
                        </ul>
                     </div>
                  </div>
                  <?php } ?>
           </div>
         </div>
         <div class="col-md-4">
             <div class="sortCard cardslabel">
                <div class="cardNot">
                   <input type="hidden" class="hiddenurl" value="<?php echo wp_kses_data(site_url());?>">
                   <h5 class="sortHed">Till Date</h5>
                   <div class="card-default cardPieChart">
                      <?php if(!isset($stats_notifications_data['total'])){ ?> 
                      <h5 class = "noarticlefoundclass">No Notification sent</h5>
                      <?php } else { ?>
                      <canvas id="doChartStatsTilldate" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                      <?php } ?>
                   </div>
                </div>
                <?php if(isset($stats_notifications_data['total'])) { ?>
                <div class="statsdef ">
                   <div class="sectnDiv">
                      <ul>
                         <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion -</li>
                         <li class="mb-2 datachartli"><?php if(isset($stats_notifications_data['total']['article_promotion'])) { echo esc_attr($stats_notifications_data['total']['article_promotion']); } else { echo 0;} ?></li>
                      </ul>
                   </div>
                   <div class="sectnDiv">
                      <ul>
                         <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General -</li>
                         <li class="mb-2 datachartli"><?php if(isset($stats_notifications_data['total']['general'])) { echo esc_attr($stats_notifications_data['total']['general']); } else { echo 0;} ?></li>
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
                     <?php if(isset($get_stats_data['article']) && (int)$get_stats_data['article'] === 0){ ?> 
                     <h5 style="text-align: center;">No articles found</h5>
                     <?php } else { ?>
                     <canvas id="doChart" ></canvas>
                     <?php } ?>
                  </div>
                  <?php if(isset($get_stats_data['article']) && $get_stats_data['article'] > 0){ ?> 
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


               <!-- start Sortd_Webstories -->
               <div class="sortCard cardPieChart cardslabel non_min_heigt">
                  <h5 class="sortHed mb3">Webstories</h5>
                  <div class="card-body" >
                     <?php   if(isset($webstory_data) && (int)$webstory_data === 0){ ?> 
                     <h5 style="text-align: center;">No Webstories found</h5>
                     <?php } else { ?>
                     <canvas id="WebStoryChart" ></canvas>
                     <?php } ?>
                  </div>
                  
                  <div class="sectnDiv">
                        <ul class="colrInfo">
                           <li class="rd"><i class="bi bi-circle-fill"></i>Webstories</li>
                        </ul>
                  </div>
                  
               </div>

               <!-- end of Sortd-Webstories -->


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
                              <?php if(!empty($get_stats_data['recent_articles'])){ foreach($get_stats_data['recent_articles'] as $k => $v){ 
                                 if($v->status === true){
                                 
                                      $class = "";
                                 } else if($v->status === false){
                                      $class = "table-dlt";
                                 }
                                 
                                 ?>
                              <tr id="row_<?php echo wp_kses_data($v->guid);?>" class="<?php echo esc_attr($class);?>">
                              <td class="text-left raImg"><?php if(!empty($v->thumburl)){ ?><div class="bgThumImg" style="background-image:url(<?php echo esc_attr($v->thumburl);?>)" ></div><?php } else{$logo = SORTD_CSS_URL.'/logo.png';echo wp_kses_post("<img src=".$logo.">");}?> </td>
                                 <td class="text-left"><?php echo wp_kses_data($v->title);?></td>

                                 <td class="text-left pd0">

                                  <?php if($v->status === true){ 

                                    $style = "display:block";

                                  } else if($v->status === false) {

                                     $style = "display:none";


                                  } ?>


                                    <a title="Delete" href="javascript:void(0)" class="deleteClass" id="<?php echo wp_kses_data($v->guid);?>" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-site_url="<?php echo esc_url($wp_domain);?>" data-nonce="<?php echo wp_kses_data(wp_create_nonce('rw-sortd-delete-article'.$v->guid));?>" style="<?php echo wp_kses_data($style);?>">
                                       <i class="bi bi-trash-fill"></i>

                                    </a>
                                    <?php     if($v->status === true) { 
                                       $style1 = "display:none";
                                       
                                       }  else if($v->status === false) {
                                       
                                       $style1 = "display:block";
                                       
                                       
                                       } ?>

                                  

                                    <a title="Restore" href="javascript:void(0)" class="imageArticle" id="imageArticlerestore<?php echo esc_attr($v->guid);?>" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-site_url="<?php echo esc_url($wp_domain);?>"  data-nonce="<?php echo wp_kses_data(wp_create_nonce('rw-sortd-sync-article'.$v->guid));?>" data-guid="<?php echo esc_attr($v->guid);?>" style="<?php echo esc_attr($style1);?>">
                                       <span><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/restore.png"></span>


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

   <script><?php echo wp_kses_data($chatbot_data->data); ?></script>
    
