<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
   //echo "<pre>";print_r($projectDetails->data->domain->demo_host);die;
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
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['twentyfour']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">UNSYNCED</p>
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['twentyfournot']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">PUBLISHED</p>
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['twentyfourpublished']);?></a>
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
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['seven']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">UNSYNCED</p>
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['sevennot']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">PUBLISHED</p>
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['sevenpublished']);?></a>
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
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['thirty']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">UNSYNCED</p>
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['thirtynot']);?></a>
                  </div>
                  <div class="col-md-4">
                     <p class="cardH">PUBLISHED</p>
                     <a href="#" class="valueBx"><?php echo esc_attr($timearray['thirtypublished']);?></a>
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
               if($projectDetails->status == 1){
                 foreach ($projectDetails->data as $key => $value) { 
                     if(is_object($value)){ 
               
               
                       ?>
            <label class="domLb">
               <h5 class="sortHed"><?php echo ucfirst(esc_attr($key));?></h5>
            </label>
            <br>
            <?php foreach ($value as $keyV => $valueV) { 
               if($keyV == 'status' && $valueV == 1){
               
                   $valueV = "SSL Not Verified";
                 } else if($keyV == 'status' && $valueV == 0){
               
                   $valueV = "SSL Pending";
                 }  else if($keyV == 'status' &&  $valueV == 2){
                   $valueV = "Deployment Pending";
                 } else if($keyV == 'status' &&  $valueV == 3){
                   $valueV = "Deployment Complete";
                 } else if($keyV == 'https_only' &&  $valueV == 1){
                   $valueV = "true";
                 } else if($keyV == 'https_only' &&  $valueV != 1){
                   $valueV = "false";
                 } else if($keyV == 'behind_login' &&  $valueV != 1){
                   $valueV = "false";
                 } else if($keyV == 'behind_login' &&  $valueV == 1){
                   $valueV = "true";
                 } 
               
                 if($keyV != 'id' && $keyV != 'project_id') {
                      if($keyV == 'demo_host' || $keyV == 'public_host') { 
               ?>
              <div class="trow">

                 
                
                <span class="thead"><?php echo esc_attr($keyV);?>:</span>
                <span><?php echo esc_attr($valueV);?></span>
                 
              </div>
               <?php } ?>
            <?php }
               } ?>
            <?php } else { 
               if($key == 'status' && $value == 1){
               
                 $value = "Active";
               } else if($key == 'status' && $value == 0){
               
                 $value = "Inactive";
               }  else if($key == 'status' && ($value != 0 || $value != 1)){
                 $value = "Suspended";
               }
               
                 if($key != 'id' && $key != 'publishers_id') {
                      if($key == 'name' || $key == 'desc' ||  $key == 'status' || $key == 'slug') { 
               ?> 
            <div class="trow">
              <span class="thead"><?php echo esc_attr($key);?>:</span>
              <span><?php echo esc_attr($value);?></span>
            </div>
                <?php } ?>
            <?php  }
               } 
              
               }
            
               } else if($projectDetails->status != 1){ ?>
            <div class="notice notice-error is-dismissible">
               <p><?php echo esc_attr($projectDetails->error->message);?></p>
               <span class="closeicon" aria-hidden="true">&times;</span>
            </div>
            <?php }
               ?>
         </div>
      </form>
    </div>
  </div>
</div>