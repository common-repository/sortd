<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 


	//echo "<pre>";print_r($version);die;
$projectId = get_option('sortd_projectid');
 $get_option = get_option('sortd_'.$projectId.'_redirection_code');
 $get_option_amp = get_option('sortd_'.$projectId.'redirectValueAmp');

 

   if($get_option == 'true' || $get_option ==  true|| $get_option_amp == 'true' || $get_option_amp == true){
      $redirectionEnabled = 1;
   } else{
      $redirectionEnabled = 0;
   }
?>

<div class="content-section">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo plugin_dir_url( __DIR__ );?>/css/logo.png">
	              <h5>Settings</h5>
	            </div>
	            <div class="headingNameTop df_bTn">
	               <a href="<?php echo $consoleurl;?>" target="_blank"><button class="butn-df">Go To Console  <i class="bi bi-box-arrow-up-right"></i></button></a>
	            </div>
	         </div>
	      </div>
	   </div>

	   <div class="row">
	   		<div class="col-md-12">
	   			<div class="designSeting">
	   				<span>
	   					<i class="bi bi-palette"></i>
	   				</span>
	   				<div class="ddBox">
		   				<h1>Design and Develop</h1>
		   				<p>Customise your entire mobile web user interface and functionalities. Engage your end users with best in class mobile experience.</p>
		   			</div>
		   			<div class="mngBtns">
		   				<a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config';?>">
			   				<button class="butn-df btn-md">Manage Design  <i class="bi bi-arrow-right-circle-fill"></i>
			   					
			   				</button>
		   				</a>
		   				<a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_templates';?>">
		   				<button class="butn-df btn-Thm">Themes <i class="bi bi-arrow-right-circle-fill"></i></button></a>
		   			</div>
	   			</div>
	   		</div>

	   		<div class="col-md-6">
	   			<div class="designCatag_oder">
	   				<h3>Manage Categories</h3>
	   				<p>Manage sync / unsync of the categories in mobile.</p>
	   				<span>
	   					<a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_categories';?>"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>

	   			<div class="designCatag_oder">
	   				<h3>Credentials <span class="verifyAlt">Already Verified</span></h3>
	   				<p>Validate your unique token & key to keep your account secure.</p>
	   				<span>
	   					<a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_credential_settings';?>"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>

	   			<div class="designCatag_oder">
					   <h3> Set Mobile Search Console  </h3>
					   <p> Create search visibilty for advanced mobile setup. </p>
					   <span>
					   <a target="_blank" href="https://search.google.com/search-console/about"><i class="bi bi-arrow-right-circle-fill"></i></a>
					   </span>
				   </div>

	   		</div>
	   		<?php if($redirectionEnabled == 1){ 

	   			$statusbtn="Active";
	   			$classname = "setactvBtn";

	   		} else if($redirectionEnabled == 0){

	   			$statusbtn="Inactive";
	   			$classname = "setdeactvBtn";


	   		} ?>
	   		<div class="col-md-6">
	   			<div class="designCatag_oder">
	   				<h3>Mobile Subdomain Status</h3>
	   				<p style="width:44%;">Control your website redirection to mobile urls. </p>
	   				<a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_redirection';?>" class="<?php echo $classname;?>"><?php echo $statusbtn;?></a>
	   				<hr class="bdr2">

	   				<h4>Mobile Url: </h4> <span class="mobDom"><?php echo $host;?></span>
	   				<a href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_domains';?>" class="mngDom" style="margin-top:23px;margin-bottom:12px;">Manage Mobile Domain <i class="bi bi-arrow-right-circle-fill"></i></a>
	   			</div>
	   		</div>

	   </div>
	</div>
</div>

<!-- <div>
	<span>This version is no longer supported <?php //echo $projectDetails->error->message;?></span>
</div> -->