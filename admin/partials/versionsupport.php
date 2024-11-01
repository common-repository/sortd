<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
//echo "<pre>";print_r($projectDetails);die;

	//echo "<pre>";print_r(plugin_dir_url( __DIR__ ));die;
?>

<div class="content-section">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo plugin_dir_url( __DIR__ );?>/css/logo.png">
	              <h5></h5>
	            </div>
	            <div class="headingNameTop">
	               <h2></h2>
	            </div>
	         </div>
	      </div>
	   </div>

	   <div class="row">
	   		<div class="col-md-12">
	   			<div class="msgSectn">
	   				<div class="erorMsg">
	   					<h1>Oops?<span>?</span></h1>
		   				<h5><?php echo esc_attr($projectDetails->error->message);?></h5>
		   			</div>
		   			<div class="erorImg">
		   				<img src="<?php echo plugin_dir_url( __DIR__ );?>/css/wom.png">
		   			</div>
	   			</div>
	   		</div>
	   </div>
	</div>
</div>

<!-- <div>
	<span>This version is no longer supported <?php //echo $projectDetails->error->message;?></span>
</div> -->