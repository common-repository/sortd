<?php

/**
 * Provide a notifications view for the plugin
 *
 * This file is used to markup the notifications aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

$wp_domain = get_site_url();
$sortd_current_user = wp_get_current_user()->display_name;
$project_details = Sortd_Helper::get_cached_project_details();
$project_slug = $project_details->data->slug;

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
               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
              <h5>Notifications</h5>
            </div>
            <div class="headingNameTop df_bTn">
            <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
              <button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-4">
         <div class="sortCard cardslabel">
            <div class="cardNot">
               <input type="hidden" class="hiddenurl" value="<?php echo wp_kses_data(site_url());?>">
               <input type="hidden" class="hiddenpublichostflag" value="<?php echo wp_kses_data($flagpublic);?>">
               <h5 class="sortHed">Today</h5>
               <div class="card-default cardPieChart">
                  <?php if(!isset($notifications_stats['today'])){ ?> 
                  <h5 class = "noarticlefoundclass">No Notification sent</h5>
                  <?php } else { ?>
                  <canvas id="doChartStats" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                  <?php } ?>
               </div>
            </div>
            <?php if(isset($notifications_stats['today'])) { ?>
            <div class="statsdef">
               <div class="sectnDiv">
                  <ul>
                     <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion</li>
                     <li class="mb-2 datachartli"><?php if(isset($notifications_stats['today']['article_promotion'])) { echo esc_attr($notifications_stats['today']['article_promotion']); } else { echo 0;} ?></li>
                  </ul>
               </div>
               <div class="sectnDiv">
                  <ul>
                     <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General</li>
                     <li class="mb-2 datachartli"><?php if(isset($notifications_stats['today']['general'])) { echo esc_attr($notifications_stats['today']['general']); } else { echo 0;} ?></li>
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
                     <?php if(!isset($notifications_stats['thisMonth'])){ ?> 
                     <h5 class = "noarticlefoundclass">No Notification sent</h5>
                     <?php } else { ?>
                     <canvas id="doChartStatsthismonth" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                     <?php } ?>
                  </div>
               </div>
               <?php if(isset($notifications_stats['thisMonth'])) { ?>
               <div class="statsdef">
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion</li>
                        <li class="mb-2 datachartli"><?php if(isset($notifications_stats['thisMonth']['article_promotion'])) { echo esc_attr($notifications_stats['thisMonth']['article_promotion']); } else { echo 0;} ?></li>
                     </ul>
                  </div>
                  <div class="sectnDiv">
                     <ul>
                        <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General</li>
                        <li class="mb-2 datachartli"><?php if(isset($notifications_stats['thisMonth']['general'])) { echo esc_attr($notifications_stats['thisMonth']['general']); } else { echo 0;} ?></li>
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
                   <?php if(!isset($notifications_stats['total'])){ ?> 
                   <h5 class = "noarticlefoundclass">No Notification sent</h5>
                   <?php } else { ?>
                   <canvas id="doChartStatsTilldate" height="133" width="266" style="display: block; width: 266px; height: 133px;" class="chartjs-render-monitor"></canvas>
                   <?php } ?>
                </div>
             </div>
             <?php if(isset($notifications_stats['total'])) { ?>
             <div class="statsdef ">
                <div class="sectnDiv">
                   <ul>
                      <li class="mb-2 articlepromotionclass artCrcl"><i class="bi bi-circle-fill"></i>Article Promotion</li>
                      <li class="mb-2 datachartli"><?php if(isset($notifications_stats['total']['article_promotion'])) { echo esc_attr($notifications_stats['total']['article_promotion']); } else { echo 0;} ?></li>
                   </ul>
                </div>
                <div class="sectnDiv">
                   <ul>
                      <li class="mb-2 articlepromotionclass gnrCrcl"><i class="bi bi-circle-fill"></i>General</li>
                      <li class="mb-2 datachartli"><?php if(isset($notifications_stats['total']['general'])) { echo esc_attr($notifications_stats['total']['general']); } else { echo 0;} ?></li>

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
                  <input type="hidden" id="hiddenSiteUrl" value="<?php echo wp_kses_data(site_url());?>">

                  <?php if (isset($title) && !empty($title)): ?>
                  <div class="artLnk" style='float:left;width:40%;height:172px;'>
                    <div class="pusImg" >
                     <?php if (has_post_thumbnail($post_id) ): 
                        $post_image = get_the_post_thumbnail_url($post_id, $size='thumbnail');
                     ?>
                      <div class="imgBgBx" style="background-image: url('<?php echo esc_attr($post_image); ?>');"></div>
                      
                     <?php endif; ?>
                     </div>
                     <span class="artNm"><?php echo '<a href="'.wp_kses_data(get_permalink($post_id)).'" target="_blank">'.wp_kses_data($title).'</a>'; ?></span>   
                  </div>                  
                  <?php endif; ?>

                  <label class="pure-material-textfield-outlined" for="snippet">
                  <textarea class="form-control" id="snippet" rows="3"  ><?php if(isset($title) && !empty( $title)){ echo wp_kses_data($title);} ?></textarea>
                  <span>Message</span>
                  <span class="clear" id='remainingC' style="margin-left:5px;color:#ff0000;"></span>                 
                  <span class="snippetRequired" style="display: none;color:red">This field is required</span>
                   <span class="snippetRequiredspace" style="display: none;color:red">space is not allowed</span>
                  </label>
                 
                  <div class="row">
                     <div class="col-lg-2">
                        <label for = "name">Platform</label>
                     </div>
                     <div class="col-lg-8">
                        <input type = "hidden" id="notificationtype">
                        <?php if($platforms->status === true) { 
                           foreach($platforms->data as $k => $v)   {  
                               if($v === 1){
                                   $name = 'pwa';
                                   $checked = "checked";
                               } else if($v === 2){
                                   $name = 'android';
                                   $checked = "";
                               } else if($v === 3){
                                   $name = 'ios';
                                   $checked = "";
                               } else if($v === 4){
                                   $name = 'ipad';
                                   $checked = "";
                               } else if($v === 5){
                                   $name = 'androidtv';
                                   $checked = "";
                               } else if($v === 6){
                                 $name = 'all';
                                 $checked = "";
                             }
                           
                           ?> 
                        <div class = "radio form-group">
                           <label>
                           <input type = "checkbox" class="radioVal" name = "optionsRadios" id = "optionsRadios1" value = "<?php echo wp_kses_data($v);?>" <?php echo wp_kses_data($checked);?>><?php echo wp_kses_data($name);?>
                           </label>
                        </div>
                       
                        <?php 
                           } ?>

                        <div>
                           <span class="platform_validation" style="display:none;color:red;">
                           if all option is selected then deselect already selected other options or if selected other options then deselect all option.
                           </span>
                        </div>
                           
                        <?php   } ?>
                     </div>

                 
                     <div class="col-lg-2"></div>
                  </div>
                  <button type="button" class="btn btn-ad-dflt sendPushNotifications" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-siteurl="<?php echo wp_kses_data(site_url());?>">Send Notification</button>
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
                        if(empty($recent_notifications->data->notificationList )) { ?>
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
                      
                        foreach($recent_notifications->data->notificationList as $not_key => $recent_notification){?>
                        
                        <tr>
                           <td><?php echo wp_kses_data($recent_notification->message);?></td>
                           <td><?php echo wp_kses_data($recent_notification->platform);?></td>
                           <td><?php echo wp_kses_data(($recent_notification->message_type==='article_promotion') ? 'Article': wp_kses_data($recent_notification->message_type));?></td>
                           <td><?php if(function_exists('wp_timezone_string')){
                                $timezone_name_to = wp_timezone_string();
                                $date = date_create($recent_notification->sent_on, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($timezone_name_to))->format($date_format);                          
                               } else {
                                 $date = gmdate( $date_format,$recent_notification->sent_on);
                               }
                           echo wp_kses_data($date); ?></td>
                        </tr>
                        <?php } 
                        } ?>
                     </tbody>
                  </table>
                  <?php if(!empty($recent_notifications->data->notificationList )) { ?>
                  <ul class="pagination sortPag">
                     <li class="page-item">
                        <a class="page-link" id="previous" href="javascript:void(0);" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                        </a>
                     </li>
                     <?php $pages_count = ceil($recent_notifications->data->count / 10); 
                        for($i = 1; $i <= $pages_count ;$i++){ 
                           if($i <= 10) {
                              ?>
                              <li class="page-item"><a class="page-link" id="page<?php echo wp_kses_data($i);?>" data-page="<?php echo wp_kses_data($i);?>" href="javascript:void(0);"><?php echo wp_kses_data($i);?></a></li>
                              <?php
                           } else {?>
                              <li class="page-item"><a class="page-link" id="page<?php echo wp_kses_data($i);?>" style="display:none;" data-page="<?php echo wp_kses_data($i);?>" href="javascript:void(0);"><?php echo wp_kses_data($i);?></a></li>
                     <?php } }?>
                     <input type ="hidden" id="pagecount" value="<?php echo wp_kses_data($pages_count);?>">
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