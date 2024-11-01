<?php

/**
 * Provide a project details view for the plugin
 *
 * This file is used to markup the project details aspects of the plugin.
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
   
   ?>
<div class="content-section mt-0">
  <div class="container-pj">
    

    <div class="row">
      <div class="col-md-6">
         <div class="sortCard">
            <h5 class="sortHed">24 Hours</h5>
            <span class="cardT">Article Stats</span>
            <div class="sort-body">
               <div class="row">
                  <div class="col-md-4">
                     <p class="cardH">SYNCED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['twentyfour']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">UNSYNCED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['twentyfournot']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">PUBLISHED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['twentyfourpublished']);?></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="sortCard">
            <h5 class="sortHed">7 Days</h5>
            <span class="cardT">Article Stats</span>
            <div class="sort-body">
               <div class="row">
                  <div class="col-md-4">
                     <p class="cardH">SYNCED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['seven']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">UNSYNCED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['sevennot']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">PUBLISHED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['sevenpublished']);?></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class ="col-md-6">
         <div class="sortCard">
            <h5 class="sortHed">30 Days</h5>
            <span class="cardT">Article Stats</span>
            <div class="sort-body">
               <div class="row">
                  <div class="col-md-4">
                     <p class="cardH">SYNCED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['thirty']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">UNSYNCED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['thirtynot']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">PUBLISHED</p>
                     <a href="#" class="valueBx"><?php echo wp_kses_data($timearray['thirtypublished']);?></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
    </div>

    <div class="proDtl" id="sortd_configContainer">
      <form id="" action=""  method="post">
         <div class="form-group">
            <h5 class="sortHed mb1">Project Details</h5>
            <?php 
               if($project_details->status === true){
                 foreach ($project_details->data as $key => $value) { 
                     
                    if(is_object($value)){ ?>
                        <label class="domLb">
                           <h5 class="sortHed"><?php echo wp_kses_data(ucfirst($key));?></h5>
                        </label>
                        <br>
                        <?php foreach ($value as $domain_status => $domain_status_value) { 
                           if($domain_status === 'status' && $domain_status_value === 1){
                               $domain_status_value = "SSL Not Verified";
                             } else if($domain_status === 'status' && $domain_status_value === 0){
                               $domain_status_value = "SSL Pending";
                             }  else if($domain_status === 'status' &&  $domain_status_value === 2){
                               $domain_status_value = "Deployment Pending";
                             } else if($domain_status === 'status' &&  $domain_status_value === 3){
                               $domain_status_value = "Deployment Complete";
                             } else if($domain_status === 'https_only' &&  $domain_status_value === 1){
                               $domain_status_value = "true";
                             } else if($domain_status === 'https_only' &&  $domain_status_value !== 1){
                               $domain_status_value = "false";
                             } else if($domain_status === 'behind_login' &&  $domain_status_value !== 1){
                               $domain_status_value = "false";
                             } else if($domain_status === 'behind_login' &&  $domain_status_value === 1){
                               $domain_status_value = "true";
                             } 

                             if($domain_status !== 'id' && $domain_status !== 'project_id') {
                                  if($domain_status === 'demo_host' || $domain_status === 'public_host') { 
                           ?>
                          <div class="trow">



                            <span class="thead"><?php echo wp_kses_data($domain_status);?>:</span>
                            <span><?php echo wp_kses_data($domain_status_value);?></span>

                          </div>
                           <?php } ?>
                        <?php }
                           } ?>
                <?php } else { 
                            if($key === 'status' && $value === 1){

                              $value = "Active";
                            } else if($key === 'status' && $value === 0){

                              $value = "Inactive";
                            }  else if($key === 'status' && ($value !== 0 || $value !== 1)){
                              $value = "Suspended";
                            }
               
                            if($key !== 'id' && $key !== 'publishers_id') {
                                 if($key === 'name' || $key === 'desc' ||  $key === 'status' || $key === 'slug') { 
                          ?> 
                                <div class="trow">
                                  <span class="thead"><?php echo wp_kses_data($key);?>:</span>
                                  <span><?php echo wp_kses_data($value);?></span>
                                </div>
                                    <?php } ?>
                       <?php  }
               } 
              
             }
            
           } else if($project_details->status !== true){ ?>
            <div class="notice notice-error is-dismissible">
               <p><?php echo wp_kses_data($project_details->error->message);?></p>
               <span class="closeicon" aria-hidden="true">&times;</span>
            </div>
            <?php }
               ?>
         </div>
      </form>
    </div>
  </div>
</div>