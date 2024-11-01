<?php 

if ( ! defined( 'ABSPATH' ) ) exit; 

$sortd_projectId = get_option('sortd_projectid');

$opValue = get_option('sortd_credentials');
$licenseData =  get_option('sortd_'.$sortd_projectId.'_license_data');

if(!empty($opValue)){
$accessK = Sortd_Helper::sortd_decrypt($opValue['access_key']);
$secretK = Sortd_Helper::sortd_decrypt($opValue['secret_key']);

} else{
  $accessK = '';
  $secretK = '';

}

 

//echo "<pre>";print_r($licenseData);die;


if(isset($licenseData)){

    
    if(isset($licenseData['project_name'])){
      $project_name = $licenseData['project_name'];
    } else {
        $project_name = '';
    }

    if(isset($licenseData['project_id'])){
        $project_id = $licenseData['project_id'];
    } else {
        $project_id = '';
    }

    if(isset($licenseData['host'])){
        $host = $licenseData['host'];
    } else {
        $host = '';
    }


    $jsonA = array("access_key"=>$accessK , "secret_key"=>$secretK,"project_name"=>$project_name,"project_id"=>$project_id,"host"=>$host);

} else {

   
    $jsonA = array("access_key"=>$accessK , "secret_key"=>$secretK,"project_name"=>'',"project_id"=>'',"host"=>'');
}


if(!empty($opValue)){
  $creFlag = 1;
} else {
  $creFlag = 0;
}



if(!empty($opValue)){
$jsonEncode = json_encode($jsonA,JSON_PRETTY_PRINT);
} else{
  $jsonEncode = '';
}

$plandata = json_decode($response);
?>

<style type="text/css">
/*.veriBx{
    background: linear-gradient(to left, #ff00cc, #005BF0);
    padding: 50px 40px 30px;
    border-radius: 10px;
}
*/
.veriBx {
    background: #f6f6f6;
    padding: 30px;
    border-radius: 10px;
    border: 1px solid #eee;
}

/*.verifycredentialsbtn{
    border: 1px solid #fff !important;
    background: transparent;
}
*/

/*.verifycredentialsbtn:not(:disabled):not(.disabled){
    border: 1px solid #fff !important;
}
*/
.videoSecn2{
        min-height: 400px;
    height: 400px;
    overflow: hidden;
    background: #005BF0;
    border-radius: 46px;
    background-image: url(https://mcmscache.epapr.in/mcms/515/2457037dd2c3c35f43fad6df06e2bbe49cab104a.jpg);
    background-size: cover !important;
    border-top: 10px solid #2b7bff;
}
/*.enter_cred_span{
        background: #000;
    color: #fff !important;
    padding: 0px 0 0 10px;
    border-radius: 10px 10px 0 0;
    display: inline-block !important;
    line-height: 2px !important;
    margin: -20px 0 0 0;
}
*/
.already_verified_onload {
    /* background-color: #27CA85; */
    color: #02ab37;
    font-size: 13px;
    font-weight: 300;
    padding: 4px 10px;
    margin: 13px 0 0px 0px;
    display: inline-block;
    border-radius: 4px;
    font-weight: 500;
    font-family: 'Barlow';
    border: 1px dotted #02ab37;
}
.already_verified{
    color: #02ab37;
    font-size: 13px;
    font-weight: 300;
    padding: 4px 10px;
    margin: 13px 0 0px 0px;
    display: inline-block;
    border-radius: 4px;
    font-weight: 500;
    font-family: 'Barlow';
    border: 1px dotted #02ab37;
}

.form_dedicated_heading{
    color: #000;
    opacity: 1;
    font-weight: 500;
    font-size: 1.2em;
    padding: 0 0 30px 0;
}

#congratsdiv {
    width: 40%;
    position: relative;
    padding: 28px 30px 10px;
    border-radius: 10px;
    background: #ffffff;
    /*box-shadow: -1px 1px 14px 0px #e2e2e2;*/
    animation: zoom-in-zoom-out 1s ease-out;
    position: absolute;
    top: 100px;
    height: auto;
    margin-left: 26%;
    z-index: 2
}

/*#congratsdiv{
    width: 100%;
    float: left;
    position: relative;
    padding: 28px 30px 10px;
    border-radius: 10px;
    margin: 30px 0 0px;
    background: #ffffff;
    box-shadow: -1px 1px 14px 0px #e2e2e2;
     animation: zoom-in-zoom-out 1s ease-out;
}
*/

@keyframes zoom-in-zoom-out {
  0% {
    transform: scale(0.5, 0.5);
  }
  50% {
    transform: scale(1, 1);
  }
  100% {
    transform: scale(1, 1);
  }
}

.prog1 {
    width: 120px;
    height: 8px;
    margin: 0px auto;
    margin-bottom:10px;
}
.progress-value {
  animation: load 7s normal forwards;
  background:#5554cf;
  width: 0;
  height: 8px
}

@keyframes load {
  0% { width: 0; }
  100% { width: 100%; }
}
</style>
<div id="opacityBox" style="display: none;"></div>
<div class="content-section">
  <div class="container-pj">
   <div class="message"></div>
    <div class="row">
        <div class="col-md-12">
           <div class="heading-main">
              <div class="logoLft">
                 <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
                 <h5>Verify Credentials </h5>
              </div>
              
              <div class="headingNameTop">
            
              </div>
           </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mt-30">
           <div class="sortCard">
               <div class="veriBx">
                    <!-- <h5 class="sortHed">Verify the license</h5> -->
                    <div class="form-group">
                    <label class="pure-material-textfield-outlined pasFld" for="exampleFormControlTextarea1">
                    <!--  <input type="text" class="form-control" id="licensekey" value='<?php echo $jsonEncode;?>'> -->
                    <textarea id="licensekey"><?php echo esc_attr($jsonEncode);?></textarea>
                     <span style="color: #000;font-weight:500;">Enter Credentials</span>
                     <span id="validationspan" style="color:red"></span>

                     <?php if($creFlag == 1) { ?> 
                    <span style="" class="already_verified_onload" >Already verified</span>
                    <?php } ?>
                    <span style="display:none" class="already_verified" >Already verified</span>
                        </label>
                          <input type="hidden" id="siteurl" value="<?php echo site_url();?>">
                    </div>
                    <button  class="btn btn-ad-dflt verifycredentialsbtn" value="">Verify</button>
                </div>

                <div class="credQue">
                  <h3>Why are these credentials required?</h3>
                  <p class="lsP">These credentials are required to verify & assign you SORTD Publishing Plan.</p>
                
                  <h3>Do I need to purchase any plans to start my PWA + AMP ?</h3>
                  <p>No. You don’t need to purchase any plan to start. A complimentary Basic Plan is assigned to your account which could be extended based on usage.</p>
                </div>

                <div id="congratsdiv" style="display:none;">
                   <span><img src="<?php echo plugin_dir_url( __DIR__ );?>css/mainCongrats.png"></span>
                   <h1>Congratulations!</h1>
                   <h2>Your Credentials have been verified.</h2>
                   <h3>Now you'll be able to setup categories & design your PWA & AMP!</h3>
                   
                   <div id="activeplandiv"></div>
                   <span id="plandetailspan"></span>
                   <div class="progress prog1">
                      <div class="progress-value progress-bar-striped progress-bar-animated" ></div>
                    </div>
                      <span class="plwt">please wait..</span>
                   <!-- <button  class="btn btn-ad setuppwaandamp" value="">Setup PWA and AMP</button> -->
                   <!-- <button  class="btn btn-ad setuppwaandamp" value=""
                     onclick="window.location.href='<?php //echo site_url();?>/wp-admin/admin.php?page=sortd_setup';">Setup PWA and AMP</button> -->
                </div> 

                <input type="hidden" id="sortd_cre_key" value="<?php echo esc_attr($creFlag);?>">
                <div id="successfully_verified" style="display:none">
                  <span><img src="<?php echo plugin_dir_url( __DIR__ );?>css/mainCongrats.png"></span>
                  <h1>Congratulations!</h1>
                   <h2>Your Credentials have been verified.</h2>
                </div> 

                <div class="sortCard user_creds_verify" style="display:none;">
                  <h5 class="sortHed" style="color: #fff;background: #555;">User Details</h5>
                  <ul class="usrBx">
                     <li> <?php echo  bloginfo('admin_email');?></li>

                     <li> <?php echo esc_attr($sortd_projectId);?></li>
                     <li><a href="https://console.sortd.mobi/"><span><i class="bi bi-box-arrow-up-right"></i></span> Go to Sortd Console</a> </li>

                  </ul>
               </div>

           </div>
        </div>

  


<?php if(!empty($opValue)) { ?> 
  <div class="col-md-12">
   <div class="proDtl verifyprojectdetials sortCard cardslabel non_min_heigt" id="sortd_configContainer" style="display: block;">
      <form id="" action=""  method="post">
         <div class="formBx">

            <h5 class="sortHed form_dedicated_heading">Project Details</h5>
            <?php 

               if($projectDetails->status == 1){
                $manage_public_host = 0;
                $add_public_host = 0;
                 foreach ($projectDetails->data as $key => $value) { 
                     if(is_object($value)){ 
                        if (!isset($value->public_host)) {
                            $add_public_host = 1;
                        } else if (isset($value->status) && ($value->status==0||$value->status==1||$value->status==2)) {
                            $manage_public_host = 1;
                        }

                       ?>
            <div class="domLb">
               <h5 class="sortHed form_dedicated_heading"><?php echo ucfirst(esc_attr($key));?></h5>
            </div>
       
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

                        if($keyV == 'demo_host'){
                            $keyV = 'Demo Host Name';
                            if ($add_public_host) {
                                 $valueV = $valueV;
                                $linkhost = '<a id="demohostclick" href="'.admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_domains">Add Public Host</a>';
                            }
                        }
                        if($keyV == 'public_host'){
                            $keyV = 'Public Host Name';
                            if ($manage_public_host) {
                                 $valueV = $valueV;
                                $linkhost = '<a  id="demohostclick" class="hstLnk" href="'.admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_domains">Manage Public Host Deployment <i class="bi bi-box-arrow-up-right"></i></a>';
                            }
                        }
               ?>
              <div class="trow">

                 
                
                <span class="thead"><?php echo esc_attr($keyV);?>:</span>
                <span><?php echo wp_kses_data($valueV);?></span>
                <?php  echo $linkhost; ?>
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
              <span class="thead"><?php echo ucfirst(esc_attr($key));?>:</span>
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


        <?php if(isset($plandata->data)) { ?> 

         <div class="formBx">
            <h5 class="sortHed form_dedicated_heading">Plan Details</h5>
             <div class="trow">
              <span class="thead">Name</span>
              <span><?php echo esc_attr($plandata->data->plan_name);?></span>
            </div>
            <div class="trow">
              <span class="thead">Type</span>
              <span><?php echo esc_attr($plandata->data->plan_type);?></span>
            </div>
            <div class="trow">
              <span class="thead">Start Date</span>
              <span><?php echo date('d-m-Y', strtotime($plandata->data->plan_start_date));?></span>
            </div>
            <div class="trow">
              <span class="thead">End Date</span>
              <span><?php echo date('d-m-Y', strtotime($plandata->data->plan_expire_date));?></span>
            </div>
         </div>

       <?php } ?>
      </form>
    </div>

  <?php } ?>      
  <div class="proDtl verifyprojectdetialsfinal sortCard cardslabel non_min_heigt" id="sortd_configContainer" style="display: none;">
      <form id="" action=""  method="post">
         <div class="formBx">
            <h5 class="sortHed mb1">Project Details</h5>
            <?php 
               if($projectDetails->status == 1){
                 foreach ($projectDetails->data as $key => $value) { 
                     if(is_object($value)){ 
               
               
                       ?>
           <div class="domLb"> 
               <h5 class="sortHed mb1"><?php echo ucfirst(esc_attr($key));?></h5>
           </div>
          
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

                 
                
                <span class="thead"><?php echo ucfirst(esc_attr($keyV));?>:</span>
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
              <span class="thead"><?php echo ucfirst(esc_attr($key));?>:</span>
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

         <div class="formBx">
            <h5 class="sortHed mb1">Plan Details</h5>
             <div class="trow">
              <span class="thead">Name</span>
                <span><?php if(isset($plandata->data->plan_name)){
                        echo esc_attr($plandata->data->plan_name);
                    } ?>
                </span>
            </div>
            <div class="trow">
              <span class="thead">Type</span>
              <span><?php if(isset($plandata->data->plan_type)){
                        echo esc_attr($plandata->data->plan_type);
                    } ?></span>
            </div>
            <div class="trow">
              <span class="thead">Start Date</span>
              <span><?php if(isset($plandata->data->plan_start_date)){
                         echo date('d-m-Y', strtotime($plandata->data->plan_start_date));
                    } ?></span>
            </div>
            <div class="trow">
              <span class="thead">End Date</span>
              <span><?php if(isset($plandata->data->plan_expire_date)){
                      echo date('d-m-Y', strtotime($plandata->data->plan_expire_date));
                    } ?></span>
            </div>
         </div>
      </form>
    </div>                                 
</div>
  </div>
</div>


    