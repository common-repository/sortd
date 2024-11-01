<?php 

if ( ! defined( 'ABSPATH' ) ) exit; 


//echo "<pre>";print_r($projectDetails->data->name);die;
if(isset($responseDdata->data->isPublicHostSet) && empty($responseDdata->data->isPublicHostSet) ){
   $flagpublic = 0;
  
} else {
    $flagpublic = 1;
   
}

//echo "<pre>";print_r($flagpublic);die;

if(isset($statsNotificationData) && isset($statsNotificationData->data)){
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

   }
   
   ?>
<style>
   fieldset.scheduler-border {
   border: 1px groove #ddd !important;
   padding: 0 1.4em 1.4em 1.4em !important;
   margin: 0 0 1.5em 0 !important;
   -webkit-box-shadow:  0px 0px 0px 0px #000;
   box-shadow:  0px 0px 0px 0px #000;
   }
   legend.scheduler-border {
   font-size: 1.2em !important;
   font-weight: bold !important;
   text-align: left !important;
   width:auto;
   padding:0 10px;
   border-bottom:none;
   }
   .sendPushNotifications{
   text-align: center;
   margin-top: 20px
   }
   .swal2-popup {
   font-size: 12px!important;
   }
   .activePage{
   background-color:#87CEFA;
   }
   .pagination{
   display: flex;
   justify-content: center;
   }
   .scrollit{
   overflow:scroll;
   height:100px;
   }
   .statsheading{
   display: flex;
   justify-content: center;
   }
   .statsdef{
   margin-top: 10px;
   }
   .datachartli{
   display: flex;
   justify-content: center;
   }
   .noarticlefoundclass{
   margin-top:50px;
   text-align: center;
   font-size: 17px;
   }
   .articlepromotionclass{
   font-size: 10px;
   }
</style>
<div class="content-section">
<div class="container-pj">
   <div class="row">
      <div class="col-md-12">
         <div class="heading-main">
            <div class="logoLft">
               <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
              <h5>Notifications</h5>
            </div>
            <div class="headingNameTop df_bTn">
              <button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-4">
         <div class="sortCard cardslabel">
            <div class="cardNot">
               <input type="hidden" class="hiddenurl" value="<?php echo site_url();?>">
               <input type="hidden" class="hiddenpublichostflag" value="<?php echo $flagpublic;?>">
               <input type="hidden" class="hiddenprojecttitle" value="<?php echo $projectDetails->data->name;?>">
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
                     <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion</li>
                     <li class="mb-2 datachartli"><?php if(isset($notificationStats['today']['article_promotion'])) { echo esc_attr($notificationStats['today']['article_promotion']); } else { echo 0;} ?></li>
                  </ul>
               </div>
               <div class="sectnDiv">
                  <ul>
                     <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General</li>
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
                        <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion</li>
                        <li class="mb-2 datachartli"><?php if(isset($notificationStats['thisMonth']['article_promotion'])) { echo esc_attr($notificationStats['thisMonth']['article_promotion']); } else { echo 0;} ?></li>
                     </ul>
                  </div>
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General</li>
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
                      <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion</li>
                      <li class="mb-2 datachartli"><?php if(isset($notificationStats['total']['article_promotion'])) { echo esc_attr($notificationStats['total']['article_promotion']); } else { echo 0;} ?></li>
                   </ul>
                </div>
                <div class="sectnDiv">
                   <ul>
                      <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General</li>
                      <li class="mb-2 datachartli"><?php if(isset($notificationStats['total']['general'])) { echo esc_attr($notificationStats['total']['general']); } else { echo 0;} ?></li>

                   </ul>
                </div>
             </div>
             <?php } ?>
          </div>
      </div>
    </div>
      <div class="row">
         <div class="col-lg-12">
            <div class="sortCard cardslabel">
               <form id="notifyform">
                  <!-- <fieldset class="scheduler-border"> -->
                  <!-- <legend class="scheduler-border">Send Push Notification</legend> -->
                  <h5 class="sortHed m-b20">Send Push Notification : <?php echo esc_attr($notification_type); ?></h5>
                  <?php wp_nonce_field('rw-sortd-send-notification', 'sortd-hidden-nonce'); ?>
                  <input type="hidden" id="hiddenSiteUrl" value="<?php echo site_url();?>">

                  <?php if (isset($title) && !empty($title)): ?>
                  <div class="artLnk" style='float:left;width:40%;height:172px;'>
                    <div class="pusImg" >
                     <?php if (has_post_thumbnail($postId) ): 
                        $post_image = get_the_post_thumbnail_url($postId, $size='thumbnail');
                     ?>
                      <div class="imgBgBx" style="background-image: url('<?php echo esc_attr($post_image); ?>');"></div>
                       <!--  <img style='float:left;' src="<?php //echo $post_image; ?>"/> -->
                     <?php endif; ?>
                     </div>
                     <span class="artNm"><?php echo '<a href="'.get_permalink($postId).'" target="_blank">'.esc_attr($title).'</a>'; ?></span>   
                  </div>                  
                  <?php endif; ?>

                  <label class="pure-material-textfield-outlined" for="snippet">
                  <textarea class="form-control" id="snippet" rows="3"  ></textarea>
                  <span>Message</span>
                  <span class="clear" id='remainingC' style="margin-left:5px;color:#ff0000;"></span>                 
                  <span class="snippetRequired" style="display: none;color:red">This field is required</span>
                   <span class="snippetRequiredspace" style="display: none;color:red">space is not allowed</span>
                  </label>
                  <!--  <div class="row">
                     <div class="col-lg-2">
                     
                        <div class="form-group">
                           <label for="snippet">Message</label>
                        </div>
                     </div>
                     <div class="col-lg-8">
                        <div class="form-group">
                           <textarea class="form-control" id="snippet" rows="3"  onkeypress="limitKeypress(event,this.value, 100)"></textarea>
                           <span class="snippetRequired" style="display: none;color:red">This field is required</span>
                        </div>
                     </div>
                     <div class="col-lg-2">
                     </div>
                     </div> -->
                  <div class="row">
                     <div class="col-lg-2">
                        <label for = "name">Platform</label>
                     </div>
                     <div class="col-lg-8">
                        <input type = "hidden" id="notificationtype">
                        <?php if($response->status == 1) { 
                           foreach($response->data as $k => $v)   {  
                           
                               if($v == 1){
                                   $name = 'pwa';
                                   $checked = "checked";
                               } else if($v == 2){
                                   $name = 'android';
                                   $checked = "";
                               } else if($v == 3){
                                   $name = 'ios';
                                   $checked = "";
                               } else if($v == 4){
                                   $name = 'ipad';
                                   $checked = "";
                               } else if($v == 5){
                                   $name = 'androidtv';
                                   $checked = "";
                               }
                           
                           ?> 
                        <div class = "radio form-group">
                           <label>
                           <input type = "radio" class="radioVal" name = "optionsRadios" id = "optionsRadios1" value = "<?php echo esc_attr($v);?>" <?php echo esc_attr($checked);?>><?php echo esc_attr($name);?>
                           </label>
                        </div>
                        <?php 
                           } 
                           
                           } ?>
                     </div>
                     <div class="col-lg-2"></div>
                  </div>
                  <button type="button" class="btn btn-ad-dflt sendPushNotifications" data-siteurl="<?php echo site_url();?>">Send Notification</button>
                  <!-- </fieldset> -->
               </form>
            </div>
         </div>
         <div class="col-lg-12">
            <div class="sortCard cardslabel">
               <form>
                  <!--   <fieldset class="scheduler-border">
                     <legend class="scheduler-border">Recently Sent Notifications</legend> -->
                  <h5 class="sortHed m-b20">Recently Sent Notifications</h5>
                  <!-- 
                     <table class="table"><thead class="text-primary"></thead><tbody><td colspan="4" class="text-center"><h4 class="card-title">No Notifications Found</h4></td></tbody></table> -->
                  <table id="table" class="display table" style="width:100%">
                     <?php
                        if(empty($responseDdata->data->notificationList )) { ?>
                     <thead class="text-primary"></thead>
                     <tbody>
                        <td colspan="4" class="text-center">
                           <h5 class = "noarticlefoundclass">No Notifications Found</h5>
                        </td>
                     </tbody>
                     <?php } else { ?>
                     <thead class="bgHed" >
                        <tr>
                           <th class="headth">Message</th>
                           <th class="headth">Platform</th>
                           <th class="headth">Type</th>
                           <th class="headth">Sent On</th>
                        </tr>
                     </thead>
                     <tbody id="getlist">
                        <?php 
                        $date_format = get_option('date_format').' '.get_option('time_format');
                        $TimeZoneNameTo = wp_timezone_string();
                        foreach($responseDdata->data->notificationList as $kd => $vd){ ?>
                        <tr>
                           <td><?php echo esc_attr($vd->message);?></td>
                           <td><?php echo esc_attr($vd->platform);?></td>
                           <td><?php echo ucfirst((esc_attr($vd->message_type)=='article_promotion') ? 'Article': esc_attr($vd->message_type));?></td>
                           <td><?php echo date_create($vd->sent_on, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($TimeZoneNameTo))->format($date_format); ?></td>
                        </tr>
                        <?php } 
                        } ?>
                     </tbody>
                  </table>
                  <?php if(!empty($responseDdata->data->notificationList )) { ?>
                  <ul class="pagination sortPag">
                     <li class="page-item">
                        <a class="page-link" id="previous" href="javascript:void(0);" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                        </a>
                     </li>
                     <?php $pages = ceil($responseDdata->data->count / 10); 
                        for($i = 1; $i <= $pages ;$i++){ ?>
                     <li class="page-item"><a class="page-link" id="page<?php echo esc_attr($i);?>" data-page="<?php echo esc_attr($i);?>" href="javascript:void(0);"><?php echo esc_attr($i);?></a></li>
                     <?php } ?>
                     <input type ="hidden" id="pagecount" value="<?php echo esc_attr($pages);?>">
                     <!-- <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li> -->
                     <li class="page-item">
                        <a class="page-link" id="next" href="javascript:void(0);" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                        </a>
                     </li>
                  </ul>
                  <?php } else { ?>
                     <div class="mesnotify"></div>
                  <?php } ?>
                  </fieldset>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
   function limitKeypress(event, value, maxLength) {       
      var text_remaining = maxLength - value.toString().length;
      document.getElementById('remainingC').innerHTML = text_remaining + ' characters remaining';

      if (value !== undefined && value.toString().length >= maxLength) {
         event.preventDefault();   
      }
   }

   jQuery(document).ready(function() {
        jQuery('#snippet').on('input propertychange', function() {
            charLimit(this, 160);
        });
    });

    function charLimit(input, maxChar) {
        var len = jQuery(input).val().length;
        jQuery('#remainingC').text(maxChar - len + ' characters remaining');
        
        if (len > maxChar) {
            jQuery(input).val(jQuery(input).val().substring(0, maxChar));
            jQuery('#remainingC').text(0 + ' characters remaining');
        }
    }
</script>