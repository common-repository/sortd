<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
$projectTitle = get_bloginfo( 'name' ) ;
$projectDescription = get_bloginfo( 'description' ); 

$jsonString = str_replace("'", " ", $getSchema);

$fname = basename(__FILE__);


	$fieldsobj = json_decode($getSchema);
	
    $demoHost = $projectDetails->data->domain->demo_host;

    //echo "<pre>";print_r($demoHost);die;

	if(isset($response['data'])){
		$categories = $response['data'];
	}


	$dataAll = json_decode($alldata);

	

	foreach($dataAll->data as $kdata => $vData){
		$savedCOnfig[$kdata] = json_decode($vData);
	}

	//echo "<pre>";print_r($savedCOnfig);die;

	if(isset($_GET['parameter'])){
		$gerparameter = $_GET['parameter'];
	} 

	if(isset($gerparameter)){
		$valuesCo = json_decode($dataAll->data->$gerparameter);
	}
	

	$header = json_decode($dataAll->data->header);

	
	if(get_option('sortd_config_save_status') == 1){

		// echo '<div class="notice notice-success is-dismissible configPopup" style="font-weight: bold;"><p>Config Successfully Saved. Changes may take time to reflect.</p><span class="closeicon configiconcross" aria-hidden="true">&times;</span></div>';

		echo '';

	}

	update_option('sortd_config_save_status',0);

?>

<style type="text/css">
.saveBtn{
  	background: transparent;
    border: 1px solid #fff !important;
}
.saveBtn:hover{
	background: #0b4ec1 !important
}

.btn-cancl {
    color: rgb(255 255 255) !important;
    background: #333 !important;
    font-size: 16px !important;
    border-radius: 4px !important;
    border: 1px solid #f1f1f1 !important;
    margin-right: 6px;
    font-family: 'Barlow', sans-serif;
    font-size: 16px !important;
}
.headingNameTop .nextStep .goLnk {
    background: #005BF0;
    padding: 16px 60px;
    border-radius: 4px;
    width: 100%;
    font-size: 1em;
    line-height: 1em;
    height: auto;
    border-bottom: 4px solid rgba(0, 0, 0,0.2);
}
.btn.btn-info {
    background: #0660f0 !important;
    color: #fff !important;
    border: none !important;
    padding: 3px 10px 4px !important;
    font-size: 12px !important;
}
.spanincdec {
    background: #ebebeb;
    width: 100%;
    position: relative;
    float: left;
    margin-bottom: 20px;
    color: #747373;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid #cbcbcb;
}

.btn-info.btn-ad.removeBtn {
    background: #dc3545 !important;
    border-color: #dc3545 !important;
}
.sepRater {
    width: 100%;
    float: left;
    background: #ebebeb;
    height: 10px;
    margin-bottom: 20px;
    border-radius: 10px;
}
.rcAdbdr {
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 30px;
}
.form-box .smBtnArea {
    display: inline-block;
}
</style>

<!-- <div class="update-nag notice notice-warning inline">After Completing config sortd setup . Setup domain deployment</a>.</div> -->
<div class="notice notice-error is-dismissible curlErrorDivConfig" style="display:none"><p id="curlErrorp"></p><span class="closeicon" aria-hidden="true">&times;</span></div>
	<!-- header start -->
	<div class="header pdT">
		<div class="head-top">
			<div class="container-pj">
				<div class="heading-main">
		  			<div class="logoLft ">
		               <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
		               <h5>Manage all aspects of your mobile</h5>
		            </div>
		            <div class="headingNameTop">
		            	<button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
						<!-- <div class="nextStep">Enable Mobile Redirection
							<a class="goLnk" href="<?php //echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_redirection';?>">
								 <i class="bi bi-box-arrow-up-right"></i>
							</a>
    					</div> -->
		            </div>
		  		</div>
				
			</div>
		</div>

		<div id="view_changes_inqr"  style="display:none;">
			
			<h3> Congratulations! Site Published. </h3>

			<div class="scan_QRcode_steps_container">

				<h5> How to Scan a QR Code ? </h5>

				<ul class="scan_QRcode_steps_container_ul">
					<li> Open the Camera / QR Code reader on your smartphone. </li>
					<li> Hold your device over a QR Code so that it’s clearly visible within your smartphone’s screen. </li>
					<li> Presto! Your smartphone reads the code and navigates to the intended destination </li>
				</ul>

			</div>

			<div class="scan_QRcode_container">

				<h6> Scan QR code on your smartphone to view changes. </h6>

				<img src="<?php echo $demoHost; ?>/sortd-service/qrcode/v22-01/medium?url=<?php echo base64_encode($demoHost); ?>"></img>

				<div class="view_changes_inqr_close">
					<button type="button" class="close scanpopup-close view_changes_inqr_closebtn" aria-label="Close">
		  				<span aria-hidden="true"> DONE </span>
					</button>
				</div>

			</div>
			

			
		</div>

		<!-- menu tabing start -->
		<div class="menuBx stickyhead config-main-div">
			<div class="container-pj">
				<div class="tabMenu">

					<?php 

					if($fieldsobj->status == 1){

						foreach ($fieldsobj->data as $key => $value) { ?>
								

							<button class="tablinks" id="<?php echo wp_kses_data($key);?>"><?php echo wp_kses_data($value->label); ?></button>

						<?php 	


						}  

					} ?>	

					
				 
				</div>
			</div>
		</div>
		<!-- menu tabing end -->
	</div>
	<!-- header end -->
	
	<div class="content-section config-main-div">
		<div class="container-pj">
			<div class="menuContent-area">
				<input type="hidden" id="site_url" value="<?php echo site_url();?>">
				<input type="hidden" id="blogname" value="<?php echo wp_kses_data($projectTitle);?>">
				<input type="hidden" id="blogdescription" value="<?php echo wp_kses_data($projectDescription);?>">
				<input type="hidden" class="getschema" value='<?php echo $jsonString;?>'>
				<input type="hidden" class="getcategoriesAll" value='<?php echo json_encode($categories, JSON_HEX_APOS);?>'>
				<input type="hidden" class="getSavedConfig" value='<?php echo json_encode($dataAll , JSON_HEX_APOS);?>'>
				<?php 

				if($fieldsobj->status == 1){
					
					foreach ($fieldsobj->data  as $keyV => $valueV) {
						
				?>	
							<div id="tabcontent_id_<?php echo wp_kses_data($keyV); ?>"  class="tabcontent">
								
								<div class="inerContent-body">
									<form id="form<?php echo wp_kses_data($keyV); ?>" method="post" action="" >
										
				  		<!-- main heading tabing start -->
				  					<div class="heading-main">
				  						<div class="headingName">
				  							<h2><?php echo wp_kses_data($valueV->label); ?></h2>
				  						</div>
				  						<!-- <div class="butn-area">
							  				<button class="def-Btn">Cancel</button>
							  				<button  class="def-Btn saveBtn" data-btn="<?php //echo $keyV; ?>">Save</button>
							  			</div> -->
				  					</div>

				  					<!-- main heading tabing end -->
							  		<div class="second-heding">
						  				<h5>Change the basic site settings</h5>
						  			</div>

						  			<!-- content-section-inner start -->
			  						<div class="inner-sectn-body">
			  							<!-- left menu start -->
						  				
						  				<div class="leftMenu stickkey<?php echo wp_kses_data($keyV);?>" id="sticky-menu-box">

						  					<nav class="navigation_sectn" >
											
											<?php foreach ($valueV->items as $keyI => $valueI) { 

														$arrayfind = array(' ','(',')');

					    								$arrayReplace = array('_','_','');

												?>
												

											
					                          <!-- <a class="navigation__link active" href="#1"><?php //echo $keyI ?></a> -->
					                          <a class="navigation__link nav_<?php echo  str_replace($arrayfind,$arrayReplace,$keyV.$valueI->label);?>" href="#" id="navlink_<?php echo  str_replace(' ','_',$valueI->label);?>" data-nav-id="<?php echo  str_replace($arrayfind,$arrayReplace,$keyV.$valueI->label);?>"><?php echo wp_kses_data($valueI->label);?></a>
					                        
					                          


					                      <?php } ?>

					                      </nav>
					                        
					                 </div>

					                 <div class="contentMenu-left contentMenu_<?php echo wp_kses_data($keyV);?>" id="stickContnt" data-div = "<?php echo wp_kses_data($keyV);?>">

					    <?php foreach ($valueV->items as $keyI => $valueI) { 


					    		if(empty($valueI->items)){ 

					    	$arrayfind = array(' ','(',')');

					    		$arrayReplace = array('_','_','');

					    			?>


			  					 <div class="page-section-a hero" id="page_section_<?php echo  str_replace($arrayfind,$arrayReplace,$keyV.$valueI->label); ?>" data-pageDiv="<?php echo wp_kses_data($keyI);?>">
                              		<h2 class="card-titl ssss"><?php echo  wp_kses_data($valueI->label); ?></h2>

                              		<div class="content-card" id="div_content_card_<?php echo  str_replace(' ','_',$valueI->label);?>"> 
					    			<?php if($valueI->type == 'boolean'){ 

					    				
					    					//echo "b1";

					    					if(($valueI->default) == 1){

					    						$prop = 'checked';
					    						$checkedvalue = "true";
					    					} else {

					    						$prop = '';
					    						$checkedvalue = "false";
					    					}

					    					if(!empty($valueI->required)){

								    						

								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = '';
								    						$star = '';
								    					}

					    					$class = 'name = "'.$keyI.'"';
					    					$id = $keyI;


					    			 ?>



	                              							  <!-- TOGGLE SWITCH START -->
												    <div class="singl-section">
												    	<h5 class="subName"><?php echo wp_kses_data($valueI->label); ?>
												    	 <?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
															  <span class="inputMsg sinfo"><?php echo wp_kses_data($valueI->helptext);?></span>
															  <?php } ?><span style = "color:red"><?php echo wp_kses_data($star);?></span>
												    	
												    </h5>

												    
												    	<label class="switch-tog">
														    <input type="checkbox" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class); ?> value="<?php echo wp_kses_data($checkedvalue);?>" onclick="getEvent(this)">
														    <span class="slider-tog round"></span>
														     
														</label>

															
												    </div>
												    <!-- TOGGLE SWITCH END -->





	                              			<?php } if($valueI->type == 'html'){ 
	                              					
	                              					$classcolor = 'name = "'.$keyI.'"';
	                              					$id = $keyI;

	                              					
	                              				?>

	                              					<!-- COLOR PICKER START -->
	                              			<div class="form-box">
	                              								<h5 class="subName"> <?php echo wp_kses_data($valueI->label); ?> <?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueI->helptext);?></div>
																		  <?php } ?></h5>
				                              				<label class="pure-material-textfield-outlined">
															    <textarea class="form-control" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($classcolor);?>  rows="3"></textarea>
															  <span><?php echo wp_kses_data($valueI->label); ?></span>
															    

															</label>
				                              			</div>
											<!-- COLOR PICKER END -->
	                              		
	                              	
	                              			<?php } 	if($valueI->type == 'string'){  

	                              					
	                              					
	                              					if(!empty($valueI->default)){

								    						$defaultString = $valueI->default;
								    					
								    					} else {

								    						$defaultString = '';
								    					}

								    					if(!empty($valueI->required)){

								    						

								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = "";
								    						$star = '';
								    					}

								    					$class = 'name = "'.$keyI.'"';
								    					$id = $keyI;



	                              				?>
				                              			<div class="form-box">
				                              					<h5 class="subName"><?php echo wp_kses_data($valueI->label); ?><span style = "color:red"><?php echo wp_kses_data($star);?></span>
				                              						<?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueI->helptext);?></div>

				                              				<?php } ?>
				                              					
				                              				</h5>
				                              			
				                              				<label class="pure-material-textfield-outlined">
															  <input placeholder=" " id= "<?php echo wp_kses_data($id);?>" type="text" <?php echo wp_kses_data($class);?> <?php echo wp_kses_data($param);?> value='<?php echo wp_kses_data($defaultString); ?>'>
															<span><?php echo wp_kses_data($valueI->label); ?></span>
															

															
															</label>
															
				                              			</div>

				                              			

	                              			<?php } if($valueI->type == 'enum'){ 

	                              						
	                              						//echo "e1";
	                              							if(!empty($valueI->default)){

										    						$defaultEnum = $valueI->default;
										    					
										    					} else {

										    						$defaultEnum = '';
										    					}

										    						if(!empty($valueI->required)){

								    						

											    						$paramenum="data-enum = 'required'";
											    						$starenum = '*';
								    					
											    					} else {

											    						
											    						$paramenum = "";
											    						$starenum = '';
											    					}

										    					$class = 'name = "'.$keyI.'"';
								    							$id = $keyI;
	                              				?>
	                              								<div class="singl-section">
															<h5 class="subName"> <?php echo wp_kses_data($valueI->label); ?>

																<?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>

															  <div class="inputMsg"><?php echo wp_kses_data($valueI->helptext);?></div>

															  <?php } ?><span style = "color:red"><?php echo wp_kses_data($starenum);?></span>
															</h5>

																<label class="pure-material-textfield-outlined">
															  <select a onchange="handleOnchangeEvent()" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?>>

															  	<optgroup  label=" Select <?php echo wp_kses_data($valueI->label);?>">

	                              							<?php foreach ($valueI->type_items as $keyIV => $valueIV) { 

	                              										if($valueI->default == $defaultEnum){

	                              											$select = 'selected';
	                              										} else {
	                              											$select = '';
	                              										}


	                              								?>
	                              								
	                 											  <option <?php echo wp_kses_data($select);?> value="<?php echo wp_kses_data($valueIV->value);?>"><?php echo wp_kses_data($valueIV->label);?></option>
													 
													    <?php 

													    	} ?>

													    	</optgroup>

													    	</select>
															  <span><?php echo wp_kses_data($valueI->label); ?></span>
															    
															</label>

													    	</div>



											<?php }  if($valueI->type == 'file_upload'){ 


	                              							//echo "f3";

	                              							if(!empty($valueI->required)){

									    						
									    						$param = "";
									    						$uploadstar = '*';
									    					
									    					} else {

									    						$param = '';
									    						$uploadstar = '';
									    					}



	                              				if(!empty($valueI->default)){

								    						$defaultString = $valueI->default;
								    					
								    					} else {

								    						
								    							$defaultString = '';
								    						

								    						
								    					}

									    				$class = 'name = "'.$keyI.'"';
								    					$id = $keyI;
								    					$accept = $valueI->file_type;
								    					$sizes = explode('x',$valueI->size);
									    				$width = $sizes[0];
									    				$height = $sizes[1];

									    	

	                              				?>

	                              	
	                              		    <!-- IMAGE UPLOAD START -->
										    <div class="singl-section">
										    	<div class="avatar-upload">
											        <div class="avatar-edit">
											        	<label for="<?php echo ($id);?>"><h5 class="subName"><?php wp_kses_data($valueI->label); ?><span style="color:red"><?php echo wp_kses_data($uploadstar);?></span></h5>
											        	</label> 
											        	<div class="avatar-preview" >
												            <div id="imagePreview" class="favImage"><img id="dvPreview<?php echo wp_kses_data($id);?>" src="<?php echo wp_kses_data($defaultString);?>" />
												            </div>
												        </div>
											        	<div class="up-input">
											        	 	<input type='file' data-height="<?php echo wp_kses_data($height);?>" data-width="<?php echo wp_kses_data($width);?>" <?php echo ($param);?> id="<?php echo wp_kses_data($id);?>"   accept="<?php echo wp_kses_data($accept);?>"  />
											        	 
											        		<input type='hidden' <?php echo ($class);?>  id="hidden_<?php echo wp_kses_data($id);?>"   />

											        		<span class="spnerror<?php echo wp_kses_data($id);?> msg-eror" style="color:red;display:none">Please upload image of <?php echo wp_kses_data($width);?>x<?php echo wp_kses_data($height);?> </span>

											        		 <button type="button" id="remove<?php echo wp_kses_data($id);?>" class=" btn btn-danger imageRemoveBtn"><i class="fa fa-times"></i>Remove</button>
											        	</div>
											       
											        </div>
											    </div>
											</div>
	                              		
	                              	
	                              			<?php } if($valueI->type == 'integer'){ 

	                              					//echo "i2";
	                              					if(!empty($valueI->default)){

								    						$defaultInteger = $valueI->default;
								    					
								    					} else {

								    						$defaultInteger = '';
								    					}

								    					if(!empty($valueI->required)){

									    						
									    						$param = "required";
									    						$starinte = '*';
									    					
									    				} else {

									    						
									    						$param = "";
									    						$starinte = "";
									    				}

									    				$class = 'name = "'.$keyI.'"';
									    				$id = $keyI;

	                              			 ?>
				                              			<div class="form-box">
				                              				<h5 class="subName"><?php echo wp_kses_data($valueI->label); ?>
				                              				<span style="color:red;"><?php echo wp_kses_data($starinte);?></span>

				                              				</h5>
				                              				<label class="pure-material-textfield-outlined">
															  <input placeholder=" " id= "<?php echo wp_kses_data($id);?>" type="number" <?php echo wp_kses_data($class);?> data-intattr= "<?php echo wp_kses_data($param);?>" value='<?php echo wp_kses_data($defaultInteger);?>' min="0">
															  <span><?php echo wp_kses_data($valueI->label); ?></span>
															    <?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueI->helptext);?></div>
															  <?php } ?>
															</label>
				                              			</div>

				                              			


	                              			<?php }  if($valueI->type == 'url'){ 
	                              					//echo "u4";

	                              					if(!empty($valueI->required)){

									    						
									    						$param="data-param-url='required'";
									    						$starurl = '*';
									    					
									    				} else {

									    						
									    						$param="";
									    						$starurl = "";
									    				}

									    				$classurl = 'name = "'.$keyI.'"';
									    				$id=$keyI;
	                              				?>

	                              						<div class="form-box">
	                              							<h5 class="subName"><?php echo ($valueI->label); ?><span style="color:red;"><?php echo wp_kses_data($starurl);?></span>
	                              							 <?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueI->helptext);?></div>
															  <?php } ?>
	                              						

	                              							</h5>
				                              				<label class="pure-material-textfield-outlined">
															  <input placeholder=" " class="urlclass" id="<?php echo wp_kses_data($id);?>" type="url" <?php echo wp_kses_data($classurl);?> <?php echo wp_kses_data($param);?>>
															 
															   <span class="hidevallabel"><?php echo wp_kses_data($valueI->label); ?></span>
															   
															  <span id="urlhttps<?php echo wp_kses_data($id);?>" style="color:red;display:none">Only https:// is allowed</span>


				                              			</div>

				                              			

	                              		
	                              	
	                              			<?php } if($valueI->type == 'hex_color'){ 
	                              						
	                              					//echo "h4";
	                              						if(!empty($valueI->default)){

								    						$defaultHexColor = $valueI->default;
								    					
								    					} else {

								    						$defaultHexColor = '';
								    					}


								    					$class = 'name = "'.$keyI.'"';
								    					$id = $keyI;




								    				

	                              			 ?>
	                              								<div class="singl-section">
							                              			<div class="picker">
							                              				<h5 class="subName"><?php echo ucfirst(wp_kses_data($valueI->label)); ?>

							                              				 <?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueI->helptext);?></div>
																		  <?php } ?>

							                              			</h5>
																	  <input type="color" class="colorpicker" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?> value="<?php echo wp_kses_data($defaultHexColor);?>">

																	  
												 
																   <input type="text"  id="hex_<?php echo wp_kses_data($id);?>" class="hexcolor" <?php echo wp_kses_data($class);?>autocomplete="off" spellcheck="false"  value='<?php echo wp_kses_data($defaultHexColor);?>'>
																	 <span class= "hexspan_<?php echo wp_kses_data($id);?>" style="color:red;display:none">Only Hex color code is accepted</span> 
																	</div>
																	
																</div>



	                              						

	                              			<?php }   if($valueI->type == 'multi-input'){
	                              				//echo "m1";
	                              			

			                        			foreach ($valueI->type_items as $keyTypeI => $valueTypeI) { ?>
			                        				<div class="multiinput_div3">
			                        						
			                        				<?php foreach ($valueTypeI as $k1=> $valueVKI) {
			                        				
			                        					if($valueVKI->type == 'file_upload') {

			                        						//echo "f4";
			                        						if(!empty($valueVKI->required)){

									    						$param = '';
									    						$uploadstar = '*';
									    					
									    					} else {

									    						$param = '';
									    						$uploadstar = '';
									    					}

									    					if(!empty($valueVKI->default)){

								    						$defaultString = $valueVKI->default;
								    					
								    					} else {

								    						
								    							$defaultString = '';
								    						

								    						
								    					}

									    					$class = 'name = "'.$keyI.':'.$keyTypeI.':'.$k1.'"';
								    						$id = $keyI.'-'.$keyTypeI.'-'.$k1;
								    						$accept = $valueVKI->file_type;
								    						$sizes = explode('x',$valueVKI->size);
										    				$width = $sizes[0];
										    				$height = $sizes[1];

										    				




			                        			 ?>
			                        				
												   <!-- IMAGE UPLOAD START -->
										    <div class="singl-section">
										    	<div class="avatar-upload">
											        <div class="avatar-edit">
											        	<label for="imageUpload"><h5 class="subName"><?php echo wp_kses_data($valueVKI->label); ?><span style="color:red"><?php echo wp_kses_data($uploadstar);?></span><

											        	<?php if(isset( $valueVKI->helptext)&& !empty($valueVKI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueVKI->helptext);?></div>
																		  <?php } ?>

											        	</h5> 
																</div></label>
											            <input type='file' data-height="<?php echo wp_kses_data($height);?>" data-width="<?php echo wp_kses_data($width);?>" <?php echo ($param);?> id="<?php echo wp_kses_data($id);?>" <?php echo ($class);?> accept="<?php echo wp_kses_data($accept);?>" /><span class="spnerror<?php echo wp_kses_data($id);?>" style="color:red;display:none">Please upload image of <?php echo wp_kses_data($width);?>x<?php echo wp_kses_data($height);?> </span>
											            <button type="button" id="remove<?php echo wp_kses_data($id);?>" class=" btn btn-danger"><i class="fa fa-times"></i>Remove</button>
												        <input type='hidden' name="<?php echo ($class);?>" id="hidden_<?php echo wp_kses_data($id);?>"   />
											        </div>
											        <div class="avatar-preview">
											             <div id="imagePreview" ><img id="dvPreview<?php echo wp_kses_data($id);?>" height="<?php echo wp_kses_data($height);?>" width="<?php echo wp_kses_data($width);?>" style="width:100px;height:100px;" src="<?php echo wp_kses_data($defaultString);?>"/>
											            </div>
											        </div>
											    </div>
											</div>

											
			                        			<?php }  if($valueVKI->type == 'string') { 

			                        					//echo "s2";

								    					if(!empty($valueVKI->required)){

								    						
								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = "";
								    						$star = '';
								    					}

								    					$class = 'name = "'.$keyI.':'.$keyTypeI.':'.$k1.'"';
								    					$id = $keyI.'-'.$keyTypeI.'-'.$k1;

			                        					if(!empty($valueVKI->default)) {  ?>


			                        						<div class="form-box">
			                        							<h5 class="subName"><?php echo wp_kses_data($valueVKI->label); ?></h5><span style = "color:red"><?php echo wp_kses_data($star);?></span>

			                        								<?php if(isset( $valueVKI->helptext)&& !empty($valueVKI->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueVKI->helptext);?></div>

				                              				<?php } ?>

			                        							</h5>

			                        							
							                              		<label class="pure-material-textfield-outlined">
																<input placeholder=" " id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?> <?php echo wp_kses_data($param);?> type="text" value='<?php echo wp_kses_data($valueVKI->default); ?>'>
																<span><?php echo wp_kses_data($valueVKI->label); ?></span>
																
																</label>
				                              				</div>

				                              				

				                              				


			                        					<?php } else { ?>


			                        						<div class="form-box">
			                        							<h5 class="subName"><?php echo wp_kses_data($valueVKI->label); ?><	<?php if(isset( $valueVKI->helptext) && !empty($valueVKI->helptext)) { ?>

			                        								<span data-icon="eva-question-mark-circle-outline" data-inline="false" data-toggle="tooltip"><label class="tooltiplabel"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="iconify" data-icon="eva-question-mark-circle-outline" data-inline="false" style="transform: rotate(360deg);"><title><?php echo ($valueVKI->helptext);?></title><g fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z"></path><path d="M12 6a3.5 3.5 0 0 0-3.5 3.5a1 1 0 0 0 2 0A1.5 1.5 0 1 1 12 11a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.16A3.49 3.49 0 0 0 12 6z"></path><circle cx="12" cy="17" r="1"></circle></g></svg></label></span>
			                        							<!-- <span  title="<?php //echo $valueVKI->helptext;?>" class="iconify" data-icon="eva-question-mark-circle-outline" data-inline="false"></span> -->

			                        						<?php } ?></h5>
							                              		<label class="pure-material-textfield-outlined">
																<input placeholder=" " <?php echo wp_kses_data($class);?> type="text">
															
																</label>
				                              				</div>

			                        				<?php 	} 

			                        					} 
			                        						
			                        				 } ?>

			                        				</div>

			                        			<?php } ?>

			                        				<div class="multiinput_div4"></div>

			                        				<div class="form-box">
				                              				<label class="pure-material-textfield-outlined smBtnArea">
															  <button type="button" class=" btn btn-info btn-ad addIconbtn recentAddMore" data-activetab="<?php echo $keyV;?>" data-div5="<?php echo  $keyV.'-'.str_replace(' ','_',$valueVI->label).'-'.$keyI.'-'.$keyTypeI.'-'.$k1;?>">Add More</button>
															  <button type="button" class=" btn btn-danger removeIconbtn">Remove</button>
															</label>
				                              			</div>


			                        			
			                        		<?php } if($valueI->type == 'array'){ 

			                        				//echo "a1";

			                        				if(isset($valueI->source)){

			                        					$class = 'name = "'.$keyI;
								    					$id = $keyI;

								    			
			                        			?>




			                        				<div class="singl-section">
															<h5 class="subName"> <?php echo wp_kses_data($valueI->label); ?>

															</h5>

																<label class="pure-material-textfield-outlined">
																	<div class="w-bg1"></div>
															  <select b id="<?php echo wp_kses_data($id);?>" name="categories#multidropdown" required multiple>

																

															  	<optgroup label=" <?php echo wp_kses_data($valueI->label);?>">


															  		<?php  foreach ($categories as $keyCategories => $valueCategories) { 

															  			

															  			if(!empty($valueCategories['sub_categories'])){ 


															  					foreach ($valueCategories['sub_categories'] as $keyCat => $valueCat) { 




															  						?>



															  						 <option value="<?php echo wp_kses_data($valueCat['cat_guid']);?>"><?php echo wp_kses_data($valueCat['name']);?></option> 





															  						
															  				<?php 	}

															  			}

															  			?>
															  			   <option value="<?php echo wp_kses_data($valueCategories['cat_guid']);?>"><?php echo wp_kses_data($valueCategories['name']);?></option>
															  		<?php } ?>


													    <?php 

													    	 ?>

													    	</optgroup>

													    	</select>
															  <span id="catspan"><?php echo wp_kses_data($valueI->label); ?></span>
															   <?php if(isset( $valueI->helptext)&& !empty($valueI->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php wp_kses_data($valueI->helptext);?></div>

				                              				<?php } ?>
												   
															</label>

													    	</div>
			                        		<?php }  


			                        		 } ?>

			                        		 <div class="emptyScrollDiv"> </div>

			                        </div></div>

					    		<?php } else {  

					    	?>
						  				
					    		<?php $arrayfind = array(' ','(',')');

					    		$arrayReplace = array('_','_','');
					    		?>
						  		
			  					<div class="page-section-a hero" id="page_section_<?php echo  str_replace($arrayfind,$arrayReplace,$keyV.$valueI->label); ?>">
                              		<h2 class="card-titl"><?php echo  wp_kses_data($valueI->label); ?><?php if(isset($valueI->helptext) && !empty($valueI->helptext)) { ?>
                                        					<span data-icon="eva-question-mark-circle-outline" data-inline="false" data-toggle="tooltip"><label class="tooltiplabel">
                                        						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="iconify" data-icon="eva-question-mark-circle-outline" data-inline="false" style="transform: rotate(360deg);">
                                        							<title><?php echo ($valueI->helptext);?></title>
                                        							<g fill="currentColor">
                                        								<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z"></path>
                                        								<path d="M12 6a3.5 3.5 0 0 0-3.5 3.5a1 1 0 0 0 2 0A1.5 1.5 0 1 1 12 11a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.16A3.49 3.49 0 0 0 12 6z"></path>
                                        								<circle cx="12" cy="17" r="1"></circle></g></svg></label></span>
                                        				
                                   						 <?php } ?></h2>
                              		


                              		<div class="content-card" id="div_content_card_<?php echo  str_replace(' ','_',$valueI->label);?>">





                              			<?php foreach ($valueI->items as $keyVI => $valueVI) { 


                              		if(!empty($valueVI->type)) {


	                              		if($valueVI->type == 'string'){ 

	                              			
	                              			//echo "s3";

	                              				if(!empty($valueVI->default)){

								    						$defaultString = $valueVI->default;
								    					
								    					} else {

								    						if($valueVI->label == 'Project Title'){
								    							$defaultString = $projectTitle;
								    						} elseif($valueVI->label =='Project Description'){
								    							$defaultString = $projectDescription;

								    						}else {
								    							$defaultString = '';
								    						}

								    						
								    					}

								    			if(!empty($valueVI->required)){

								    						

								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = '';
								    						$star = '';
								    					}

								    					$class ='name = "'.$keyI.':'.$keyVI.'"'; 
								    					$id= $keyI.'-'.$keyVI;


								    					
	                              			?>

	                              		
	                              			<div class="form-box">
	                              				
													<h5 class="subName"><?php echo wp_kses_data($valueVI->label); ?>
													<span style = "color:red"><?php echo wp_kses_data($star);?></span>
													<?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueVI->helptext);?></div>

				                              				<?php } ?>

													</h5>
													
												  <label class="pure-material-textfield-outlined">
												    <input placeholder=" " <?php echo wp_kses_data($class);?> <?php echo wp_kses_data($param);?> type="text" id="<?php echo wp_kses_data($id);?>" value='<?php echo wp_kses_data($defaultString);?>'><span><?php echo wp_kses_data($valueVI->label); ?></span>
												    
												   
												</label>

	                              			</div>
	                              		



	                              		<?php 	}  if($valueVI->type == 'boolean'){ 
	                              						
	                              						//echo "b2";
	                              						if(($valueVI->default) == 1){

								    						$prop = 'checked';
								    						$checkedvalue = "true";
								    					} else {

								    						$prop = '';
								    						$checkedvalue = "false";
								    					}

								    					if(!empty($valueVI->required)){

								    						

								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = '';
								    						$star = '';
								    					}




								    					$class ='name = "'.$keyI.':'.$keyVI.'"';
								    					$id= $keyI.'-'.$keyVI;


	                              			?>

	                              	
	                              		   <!-- TOGGLE SWITCH START -->
										    <div class="singl-section">
										    	<h5 class="subName"><?php echo wp_kses_data($valueVI->label); ?>

										    	 <?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
															  <span class="inputMsg sinfo"><?php echo wp_kses_data($valueVI->helptext);?></span>
															  <?php } ?>
															 <span style = "color:red"><?php echo wp_kses_data($star);?></span>
										    	</h5>
										    	  
										    	<label class="switch-tog">
												    <input type="checkbox" id="<?php echo wp_kses_data($id); ?>" <?php echo wp_kses_data($class); ?> value="<?php echo wp_kses_data($checkedvalue);?>" onclick="getEvent(this)"  <?php echo  wp_kses_data($prop);?>>
												    <span class="slider-tog round"></span>
												    
												</label>
												
										    </div>


										    <!-- TOGGLE SWITCH END -->
	                              		
	                              	
	                              			<?php } if($valueVI->type == 'file_upload'){ 

	                              						//echo "f1";
	                              						//echo $valueVI->label;


	                              							if(!empty($valueVI->required)){

									    						
									    						$param = "";
									    						$uploadstar = '*';
									    					
									    					} else {

									    						$param = '';
									    						$uploadstar = '';
									    					}

									    					if(!empty($valueVI->default)){

								    						$defaultString = $valueVI->default;
								    					
								    					} else {

								    						
								    							$defaultString = '';
								    						

								    						
								    					}


									    					
									    			$class ='name = "'.$keyI.':'.$keyVI.'"';
								    				$id= $keyI.'-'.$keyVI;

								    				$accept = $valueVI->file_type;
								    				$sizes = explode('x',$valueVI->size);
								    				$width = $sizes[0];
								    				$height = $sizes[1];

								    				

								    				   if($id == "design-favicon"){

                                                        $favClass = "fav-bx";
                                                        $favclassimage = "";
                                                    } else {
                                                        $favClass = "";
                                                         $favclassimage = "favImage";
                                                    }


	                              				?>

	                              	
	                              		    <!-- IMAGE UPLOAD START -->
										    <div class="singl-section">


										    	<div class="avatar-upload">


											        <div class="avatar-edit">
											        	 <label for="<?php echo wp_kses_data($id);?>"><h5 class="subName"><?php echo wp_kses_data($valueVI->label).'('.$valueVI->size.')'  ?>
											        	
											        	 <span style="color:red"><?php echo wp_kses_data($uploadstar);?></span>
											        	<?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueVI->helptext);?></div>
																		  <?php } ?></label>
											        	</h5>
											        	 
											        	<div class="avatar-preview" >
												            <div id="imagePreview" class="<?php echo  wp_kses_data($favclassimage);?> <?php echo wp_kses_data($favClass);?>"><img id="dvPreview<?php echo wp_kses_data($id);?>" src="<?php echo wp_kses_data($defaultString);?>"/>
												            </div>

												           
												        </div>
											        	<div class="up-input">
											        	 	<input type='file' data-height="<?php echo wp_kses_data($height);?>" data-width="<?php echo wp_kses_data($width);?>" <?php echo ($class);?> id="<?php echo ($id);?>"   accept="<?php echo wp_kses_data($accept);?>" <?php echo wp_kses_data($param);?> />
											        	 
											        		<input type='hidden' <?php echo ($class);?>  id="hidden_<?php echo wp_kses_data($id);?>"   />

											        		<span class="spnerror<?php echo wp_kses_data($id);?> msg-eror" style="color:red;display:none">Please upload image of <?php echo wp_kses_data($width)?>x<?php echo wp_kses_data($height)?> </span>

											        		 <button type="button" id="remove<?php echo wp_kses_data($id);?>" class=" btn btn-danger imageRemoveBtn"><i class="fa fa-times"></i>Remove</button>
											        	</div>

											          	

											           	
											       
											        </div>
											    </div>
											</div>
	                              		
	                              	
	                              			<?php } if($valueVI->type == 'url'){ 

	                              					//echo "u1";

	                              					
	                              						if(!empty($valueVI->required)){

									    						
									    						$param="data-param-url='required'";
									    						$starurl = '*';
									    					
									    				} else {

									    						
									    						$param="";
									    						$starurl = "";
									    				}

									    				//'name = "'.str_replace(' ','_',$valueVI->label).'"';
									    				$classurl ='name = "'.$keyI.':'.$keyVI.'"';
									    				$id= $keyI.'-'.$keyVI;
	                              				?>

	                              				<div class="form-box">
	                              					<h5 class="subName"><?php echo wp_kses_data($valueVI->label); ?>
	                              					<span style="color:red;"><?php echo wp_kses_data($starurl);?></span>
	                              					
	                              					 <?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueVI->helptext);?></div>
															  <?php } ?>	

	                              					</h5>
		                              				<label class="pure-material-textfield-outlined">
													  <input placeholder=" " class="urlclass" type="url" id= "<?php echo wp_kses_data($id); ?>" <?php echo ($classurl);?>  <?php echo ($param);?> >
													   <span class="hidevallabel"><?php echo wp_kses_data($valueVI->label); ?></span>
														  
														  <span id="urlhttps<?php echo wp_kses_data($id);?>" style="color:red;display:none">Only https:// is allowed</span>
															</label>
													</label>
	                              				</div>

	                              			
	                              		
	                              	
	                              			<?php } if($valueVI->type == 'hex_color'){ 

	                              				//echo "h1";
	                              						
	                              						if(!empty($valueVI->default)){

								    						$defaultHexColor = $valueVI->default;
								    					
								    					} else {

								    						$defaultHexColor = '';
								    					}

								    					
								    					$classcolor ='name = "'.$keyI.':'.$keyVI.'"';

								    					$id= $keyI.'-'.$keyVI;


	                              				?>

	                              					<!-- COLOR PICKER START -->
	                              			<div class="singl-section">
		                              			<div class="picker">
		                              				<h5 class="subName"><?php echo ucfirst(wp_kses_data($valueVI->label)); ?> 

		                              				
		                              					 <?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueVI->helptext);?></div>
																		  <?php } ?>


		                              				</h5>
												  <input type="color"  id="<?php echo wp_kses_data($id);?>" class="colorpicker" <?php echo wp_kses_data($classcolor);?>  value="<?php echo wp_kses_data($defaultHexColor);?>">
												 
												   <input type="text"  id="hex_<?php echo wp_kses_data($id);?>" class="hexcolor" <?php echo wp_kses_data($classcolor);?>  autocomplete="off" spellcheck="false"  value='<?php echo wp_kses_data($defaultHexColor);?>'>
													 <span class= "hexspan_<?php echo wp_kses_data($id);?>"style="color:red;display:none;">Only Hex color code is accepted</span> 
												</div>
											
											</div>


											<!-- COLOR PICKER END -->
	                              		
	                              	
	                              			<?php } if($valueVI->type == 'html'){ 
	                              					$classcolor ='name = "'.$keyI.':'.$keyVI.'"';
	                              					$id =$keyI.'-'.$keyVI;

	                              					
	                              				?>

	                              					<!-- COLOR PICKER START -->
	                              			<div class="form-box">
	                              								<h5 class="subName"> <?php echo wp_kses_data($valueVI->label); ?> <?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueVI->helptext);?></div>
																		  <?php } ?></h5>
				                              				<label class="pure-material-textfield-outlined">
															    <textarea class="form-control" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($classcolor);?>  rows="3"></textarea>
															  <span><?php echo wp_kses_data($valueVI->label); ?></span>
															    

															</label>
				                              			</div>
											<!-- COLOR PICKER END -->
	                              		
	                              	
	                              			<?php } 	if($valueVI->type == 'multi-input'){
	                              							//echo "m2";
	                              							if(isset($valuesCo->$keyI->$keyVI)){
	                              								$multiInput = $valuesCo->$keyI->$keyVI;

	                              							

			                        							$size = sizeof($multiInput);
	                              							} else {
	                              								
	                              								$multisize = ($savedCOnfig[$keyV]->$keyI->$keyVI);

	                              								if(isset($multisize)){

	                              									
	                              									$size = sizeof($multisize);
	                              								} else {

	                              										
	                              									$size = 1;
	                              								}

	                              								
	                              							}
	                              						

			                        					
	                              				 ?>

	                              					

			                        			<?php foreach ($valueVI->type_items as $keyTypeVI => $valueTypeVI) { 
			                        					$iddiv =  $keyI.'-'.$keyVI;

			                        					

			                        				?>

			                        				<div class="multiinput_div5_<?php echo  str_replace(' ','_',$valueI->label);?>"  id="multiinputdiv1-<?php echo wp_kses_data($iddiv);?>"  >
			                        					<h2 class='card-titl'><?php echo wp_kses_data($valueVI->label); ?><?php if(isset($valueVI->helptext) && !empty($valueVI->helptext));?></h2>	
			                        				<?php 

			                        				if(isset($size) && empty($size)){

			                        					
			                        					$size=1;
			                        				}

			                        				
			                        				for($i=0;$i<$size;$i++){ ?>
			                        					<span class="sepRater"></span>
			                        					<div id="divattr_<?php echo $keyI.'-'.$keyVI.'-'.$i;?>" class="multidivclass_<?php echo str_replace(' ','_',$keyV);?>">
			                        					
			                        				<?php foreach ($valueTypeVI as $kq=> $valueVKVI) { ?>

			                        					
			                        				
			                        					<?php if($valueVKVI->type == 'integer') {

			                        							//echo "i3";
			                        						if(!empty($valueVKVI->default)){

									    						$defaultInteger = $valueVKVI->default;
									    					
									    					} else {

									    						$defaultInteger = '';
									    					}


									    					if(!empty($valueVKVI->required)){

										    						
										    						$param = "required";
										    						$starinte = '*';
										    					
										    				} else {

										    						
										    						$param = "";
										    						$star = "";
										    				}

										    				$class = 'name = "'.$keyI.':'.$keyVI.':'.$kq.'/'.$i.'"';
										    				$id = $keyI.'-'.$keyVI.'-'.$i.'-'.$kq;
			                        			 ?>
			                        				
												 <div class="form-box">
												 				<h5 class="subName"><?php echo wp_kses_data($valueVKVI->label); ?></h5><span style="color:red;"><?php echo wp_kses_data($starinte);?></span>
												 				


												 				</h5>
				                              					<label class="pure-material-textfield-outlined">
															  <input placeholder=" " data-intattr= "<?php echo wp_kses_data($param);?>" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?> type="number" value='<?php echo wp_kses_data($defaultInteger);?>' min="0">
															  <span><?php echo wp_kses_data($valueVKVI->label); ?></span>
															     <?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueVKVI->helptext);?></div>
															  <?php } ?>
															</label>
				                              			</div>

				                              		

			                        			
			                        			<?php }  if($valueVKVI->type == 'boolean'){ 
			                        					
			                        					//echo "b3";
	                              						if(($valueVKVI->default) == 1){

								    						$prop = 'checked';
								    						$checkedvalue = true;
								    					} else {

								    						$prop = '';
								    						$checkedvalue = false;
								    					}

								    					if(!empty($valueVKVI->required)){

								    						

								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = '';
								    						$star = '';
								    					}

								    					$class = 'name = "'.$keyI.':'.$keyVI.':'.$kq.'/'.$i.'"';
								    					$id =$keyI.'-'. $keyVI.'-'.$i.'-'.$kq;

								    					

	                              			?>

	                              	
	                              		   <!-- TOGGLE SWITCH START -->
										    <div class="singl-section">
										    	<h5 class="subName"><?php echo wp_kses_data($valueVKVI->label); ?>

										    	 <?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
															  <span class="inputMsg sinfo"><?php echo wp_kses_data($valueVKVI->helptext);?></span>
															  <?php } ?><span style = "color:red"><?php echo wp_kses_data($star);?></span>
										    	</h5>
										    	 
										    	<label class="switch-tog">
												    <input type="checkbox" value="<?php echo wp_kses_data($checkedvalue);?>"  id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?> onclick="getEvent(this)" <?php echo  wp_kses_data($prop);?>>
												    <span class="slider-tog round"></span>
												    
												</label>
												
										    </div>


										    <!-- TOGGLE SWITCH END -->
	                              		
	                              	
	                              			<?php } if($valueVKVI->type == 'string') { 

	                              				//echo "s4";

	                              				$class = $keyI.':'.$keyVI.':'.$kq.'/'.$i;
	                              				$classvar = $keyI.':'.$keyVI.':'.$kq.'/0';
	                              				$id = $keyI.'-'.$keyVI.'-'.$i.'-'.$kq;
	                              				$idclass =  $keyI.'-'.$keyVI.'-'.$kq;

	                              				

	                              						if(isset($valueVKVI->source)){ ?>

	                              								<div class="singl-section">
															<h5 class="subName"><?php echo wp_kses_data($valueVKVI->label); ?> 

																<?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueVKVI->helptext);?></div>

				                              				<?php } ?>

															</h5>
															 

																<label class="pure-material-textfield-outlined">
																	<div class="w-bg1"></div>
															  <select c  onchange="handleOnchangeEvent(this)"id="<?php echo wp_kses_data($id);?>" name="<?php echo wp_kses_data($class);?>" class="<?php echo wp_kses_data($idclass);?>">

																

															  	<option value="Select Category" label="<?php echo wp_kses_data($valueVKVI->label);?>">Select Category</option>

															  		<?php if($keyVI== 'special_widgets'){ ?>

															  		<option value="not_applicable">Not Applicable</option>

															  		<?php } ?>
															  		<?php  foreach ($categories as $keyCategories => $valueCategories) { 
															  			
															  				if(!empty($valueCategories['sub_categories'])){ 


															  					foreach ($valueCategories['sub_categories'] as $keyCat => $valueCat) { 




															  						?>



															  						 <option value="<?php echo wp_kses_data($valueCat['cat_guid']);?>"><?php echo wp_kses_data($valueCat['name']);?></option> 





															  						
															  				<?php 	}

															  			}

															  			if($keyCategories == 0){
															  				$attr="selected";
															  			} else {
															  				$attr="";
															  			}


															  		?>
															  			   <option <?php echo $attr;?> value="<?php echo wp_kses_data($valueCategories['cat_guid']);?>"><?php echo wp_kses_data($valueCategories['name']);?></option>
															  		<?php } ?>


													    <?php 

													    	 ?>

													    

													    	</select>
															  <span><?php echo wp_kses_data($valueVKVI->label); ?></span>
															   <span id="selectalert<?php echo $id;?>" style="color:red;display:none">Select Category</span>
															    
															</label>

													    	</div>

	                              						<?php } else {


			                        					if(!empty($valueVKVI->default)){

								    						$defaultString = $valueVKVI->default;
								    					
								    					} else {

								    						$defaultString = '';
								    					}

								    					if(!empty($valueVKVI->required)){

								    						//$class = 'requiredClass';
								    					
								    					} else {

								    						//$class = '';
								    					}

								    					$class = 'name = "'.$keyI.':'.$keyVI.':'.$kq.'/'.$i.'"';
	                              						$id = $keyI.'-'.$keyVI.'-'.$i.'-'.$kq;
			                        				?>


			                        						<div class="form-box">
			                        						<h5 class="subName"><?php echo wp_kses_data($valueVKVI->label); ?>


			                        					</h5>
				                              				<label class="pure-material-textfield-outlined">
															  <input placeholder=" " id="<?php echo wp_kses_data($id);?>" <?php echo  wp_kses_data($class);?> type="text" value='<?php echo wp_kses_data($defaultString);?>'> <span><?php echo wp_kses_data($valueVKVI->label); ?></span>
															   <?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueVKVI->helptext);?></div>

				                              				<?php } ?>

				                              				 
															</label>

											
				                              			</div>

			                        				<?php	}

			                        				} if($valueVKVI->type == 'hex_color'){  

			                        						//echo "h2";
			                        					

			                              						if(!empty($valueVKVI->default)){

										    						$defaultHexColor = $valueVKVI->default;
										    					
										    					} else {

										    						$defaultHexColor = '';
										    					}

										    			$class = 'name = "'.$keyI.':'.$keyVI.':'.$kq.'/'.$i.'"';
										    			$id = $keyI.'-'.$keyVI.'-'.$i.'-'.$kq;


			                        					?>
	                              								<div class="singl-section">
							                              			<div class="picker">
							                              				<h5 class="subName"><?php echo ucfirst(wp_kses_data($valueVKVI->label)); ?>
							                              				 <?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueVKVI->helptext);?></div>
																		  <?php } ?>

							                              				</h5>
																	  <input type="color"  class="colorpicker" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?> value="<?php echo wp_kses_data($defaultHexColor);?>">
																	 
												 
																   <input type="text"  id="hex_<?php echo ($id);?>" class="hexcolor" <?php echo wp_kses_data($class);?> autocomplete="off" spellcheck="false"  value='<?php echo wp_kses_data($defaultHexColor);?>'>
																	 <span class= "hexspan_<?php echo wp_kses_data($id);?>" style="color:red;display:none">Only Hex color code is accepted</span> 
																	</div>
																	

																</div>


	                              						

	                              			<?php } if($valueVKVI->type == 'enum'){ 

	                              					
	                              						//echo "e2";
	                              							if(!empty($valueVKVI->default)){

										    						$defaultEnum = $valueVKVI->default;
										    					
										    					} else {

										    						$defaultEnum = '';
										    					}

										    					if(!empty($valueVKVI->required)){

								    						

											    						$paramenum="data-enum = 'required'";
											    						$starenum = '*';
								    					
											    					} else {

											    						
											    						$paramenum = "";
											    						$starenum = '';
											    					}

										    					$class = 'name = "'.$keyI.':'.$keyVI.':'.$kq.'/'.$i.'"';
										    					$id = $keyI.'-'.$keyVI.'-'.$i.'-'.$kq;
	                              				?>
	                              								<div class="singl-section">
															<h5 class="subName"> <span style = "color:red"><?php echo wp_kses_data($starenum);?></span><?php echo wp_kses_data($valueVKVI->label); ?>
																<?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueVKVI->helptext);?></div>
															  <?php } ?>

															</h5>

																<label class="pure-material-textfield-outlined">
															  <select d onchange="handleOnchangeEvent(this)" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?>>

															  
															  		<optgroup label=" <?php echo wp_kses_data($valueVKVI->label);?>">
															  <?php if($keyVI== 'special_widgets'){ ?>

															  	<option value="not_applicable">Not Applicable</option>

															  <?php } ?>

	                              							<?php foreach ($valueVKVI->type_items as $keytypeVKVI => $valuetypeVKVI) { 

	                              										if($valuetypeVKVI->value == $defaultEnum){

	                              											$select = 'selected';
	                              										} else {
	                              											$select = '';
	                              										}


	                              								?>
	                              								
	                 											  <option <?php echo wp_kses_data($select);?> value="<?php echo wp_kses_data($valuetypeVKVI->value);?>"><?php echo wp_kses_data($valuetypeVKVI->label);?></option>
													 
													    <?php 

													    	} ?>

													    	
													    </optgroup>
													    	</select>
															  <span><?php echo wp_kses_data($valueVKVI->label); ?></span>
															    
															</label>

													    	</div>


											<?php } if($valueVKVI->type == 'html') { 

												$class = 'name = "'.$keyI.':'.$keyVI.':'.$kq.'/'.$i.'"';
												$id = $keyI.'-'.$keyVI.'-'.$i.'-'.$kq;


												?>


			                        						<div class="form-box">

			                        								<h5 class="subName"> <?php echo wp_kses_data($valueVKVI->label); ?>  <?php if(isset( $valueVKVI->helptext)&& !empty($valueVKVI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueVKVI->helptext);?></div>
															  <?php } ?></h5>
				                              				<label class="pure-material-textfield-outlined">
															    <textarea class="form-control" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?>  rows="3"></textarea>
															  <span><?php echo wp_kses_data($valueVKVI->label); ?></span>
															   
															</label>
				                              			</div>

			                        				<?php	} ?>

			                        				
			                        				<?php } 

			                        					//if($i !== 0){
			                        				?>
			                        				

			                        				<div class="form-box">
				                              				<label class="pure-material-textfield-outlined smBtnArea">
															  <button type="button" class=" btn btn-info btn-ad  removeBtn" data-attr-page="<?php echo $valueI->label;?>"data-total ="<?php echo $size;?>" data-activesection = "<?php echo  str_replace(' ','_',$valueI->label);?>" data-activetab="<?php echo $keyV;?>" data-div5="<?php echo  $keyI.'-'.$keyVI;?>" id="<?php echo  $keyI.'-'.$keyVI.'-'.$i;?>">Remove</button>
															  <span style="color:red;display:none;" class="spancantremove<?php echo  $keyI.'-'.$keyVI.'-'.$i;?>">You can't remove the last element </span>
															</label>
				                              			</div>

				                              		<?php 

			                        					//}

			                        				?> 

				                              		</div>

			                        			<?php  }
			                        				?>
			                        			

			                        				</div>


			                        			<?php } ?>

			                        			
			                        					<?php 
			                        						$class = 'name = "'.$keyI.':'.$keyVI.'"';

			                        					?>
			                        				

			                        					<div class="form-box">

			                        						<?php if($keyVI== 'special_widgets'){ 

			                        							if($size < 1){
			                        							
			                        							?>


			                        							 <input type="hidden" <?php echo  $class;?> value=[]>

			                        						<?php } 

			                        					}

			                        						?>
			                        						 
				                              				<label class="pure-material-textfield-outlined smBtnArea">
															  <button type="button" class=" btn btn-info btn-ad addCatOtherbtn recentAddMore" data-activesection = "<?php echo  str_replace(' ','_',$valueI->label);?>" data-activetab="<?php echo $keyV;?>" data-div5="<?php echo  $keyI.'-'.$keyVI;?>">Add More</button>
															 <!--  <button type="button" class="btn-round btn btn-danger removeCatOtherbtn"  data-div5="<?php //echo  str_replace(' ','_',$valueI->label);?>">Remove</button> -->
															</label>
				                              			</div>


			                        			
			                        		<?php } if($valueVI->type == 'enum'){ 
			                        				//echo "e3";
			                        					
			                        					if(!empty($valueVI->default)){

										    						$defaultEnum = $valueVI->default;
										    					
										    					} else {

										    						$defaultEnum = '';
										    					}

										    					if(!empty($valueVI->required)){

								    						

											    						$paramenum="data-enum = 'required'";
											    						$starenum = '*';
								    					
											    					} else {

											    						
											    						$paramenum = "";
											    						$starenum = '';
											    					}

										    					$class = 'name = "'.$keyI.':'.$keyVI.'"';
										    					$id = $keyI.'-'.$keyVI;

			                        			?>



			                        			<div class="singl-section">
															<h5 class="subName"> <?php echo wp_kses_data($valueVI->label); ?><span style = "color:red"><?php echo wp_kses_data($starenum);?></span>
															<?php if(isset( $valueVI->helptext)&& !empty($valueVI->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueVI->helptext);?></div>
															  <?php } ?>
											
														</h5>

																<label class="pure-material-textfield-outlined">
															  <select e onchange="handleOnchangeEvent()" id="<?php echo wp_kses_data($id);?>" <?php echo wp_kses_data($class);?>>

															  	<optgroup label=" <?php echo wp_kses_data($valueVI->label);?>">

	                              							<?php foreach ($valueVI->type_items as $keytypeitems => $valuetypeitems) { 

	                              										if($valuetypeitems->value == $defaultEnum){

	                              											$select = 'selected';
	                              										} else {
	                              											$select = '';
	                              										}


	                              								?>
	                              								
	                 											  <option  <?php echo ($select);?> value="<?php echo wp_kses_data($valuetypeitems->value);?>"><?php echo wp_kses_data($valuetypeitems->label);?></option>
													 
													    <?php 

													    	} ?>

													    	</optgroup>

													    	</select>
															  <span><?php echo wp_kses_data($valueVI->label); ?></span>
															   
															</label>

													    	</div> <?php  }

	                              				} else { ?>
	                              						
	                              						<h2 class='card-titl'><?php echo wp_kses_data($valueVI->label); ?><?php if(isset($valueVI->helptext) && !empty($valueVI->helptext)) { ?>
                                        					<span data-icon="eva-question-mark-circle-outline" data-inline="false" data-toggle="tooltip"><label class="tooltiplabel">
                                        						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="iconify" data-icon="eva-question-mark-circle-outline" data-inline="false" style="transform: rotate(360deg);">
                                        							<title><?php echo ($valueVI->helptext);?></title>
                                        							<g fill="currentColor">
                                        								<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z"></path>
                                        								<path d="M12 6a3.5 3.5 0 0 0-3.5 3.5a1 1 0 0 0 2 0A1.5 1.5 0 1 1 12 11a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.16A3.49 3.49 0 0 0 12 6z"></path>
                                        								<circle cx="12" cy="17" r="1"></circle></g></svg></label></span>
                                        				
                                   						 <?php } ?></h2>

	                              						
	                        <?php foreach ($valueVI->items as $keyItems => $valueItems) { 


			                        		if($valueItems->type == 'multi-input'){
			                        			
			                        			
			                        			//echo "m3";

			                        			if(isset($valuesCo->$keyI->$keyVI->$keyItems)){
                      								$multiInput = $valuesCo->$keyI->$keyVI->$keyItems;

                      						

                        							$size = sizeof($multiInput);
                      							} else {

                      								$multisize = ($savedCOnfig[$keyV]->$keyI->$keyVI->$keyItems);

                      								if(isset($multisize)){
	                              									$size = sizeof($multisize);
	                              								} else {
	                              									$size = 1;
	                              								}
                      							}
                        			
			                        			
			                        		

			                        		foreach ($valueItems->type_items as $keyType => $valueType) {

			                        				$iddiv = $keyI.'-'.$keyVI.'-'.$keyItems;


			                        			 ?>
			                        						<div class="multiinput_div1" id="multiinputdiv-<?php echo wp_kses_data($iddiv);?>" >


			                        							
			                        				<?php 
			                        				
			                        				for($i = 0;$i< $size;$i++){ ?>
			                        					<span class="sepRater"></span>
			                        					<div id="<?php echo $keyI.'-'.$keyVI.'-'.$keyItems.'-'.$i;?>">

					                        				<?php foreach ($valueType as $k => $valueVK) { 


					                        					if($valueVK->type == 'url') {

					                        					
					                        					
					                        					
			                              						if(!empty($valueVK->required)){

											    						
											    						$param="data-param-url='required'";
											    						$starurl = '*';
											    					
											    				} else {

											    						
											    						$param="";
											    						$starurl = "";
											    				}

											    				$class =$keyI.':'.$keyVI.':'.$keyItems.':'.$k.'/'.$i;
											    				$id =$keyI.'-'.$keyVI.'-'.$keyItems.'-'.$i.'-'.$k;
					                        			 ?>
					                        				
														 <div class="form-box">
														 			<h5 class="subName"><?php echo wp_kses_data($valueVK->label); ?>
														 			<span style="color:red;"><?php echo wp_kses_data($starurl);?></span>
														 			 <?php if(isset( $valueVK->helptext)&& !empty($valueVK->helptext)) { ?>
																	  <div class="inputMsg"><?php echo wp_kses_data($valueVK->helptext);?></div>
																	  <?php } ?>
														 			</h5>
						                              					<label class="pure-material-textfield-outlined">
																	  <input placeholder=" "id="<?php echo wp_kses_data($id);?>" class="urlclass" type="url" data-multi="multiinput" name="<?php echo wp_kses_data($class);?>" <?php echo wp_kses_data($param);?> >
																	  <span class="hidevallabel"><?php echo wp_kses_data($valueVK->label); ?></span>
																	  <span id="urlhttps<?php echo wp_kses_data($id);?>" style="color:red;display:none">Only https:// is allowed</span>
																	

																	</label>
																	
						                              			</div>

						                              		

						                              		
						                              
					                        			
					                        			<?php } if($valueVK->type == 'string') { 

					                        					//echo "s5";
					                        				
					                        					if(!empty($valueVK->default)){

										    						$defaultString = $valueVK->default;
										    					
										    					} else {

										    						$defaultString = '';
										    					}


										    					if(!empty($valueVK->required)){

										    						
										    						$param="data-param = 'required'";
										    						$star = '*';
										    					
										    					} else {

										    						
										    						$param = "";
										    						$star = '';
										    					}

										    					$class =$keyI.':'.$keyVI.':'.$keyItems.':'.$k.'/'.$i;
										    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'-'.$i.'-'.$k;

					                        				?>


					                        						<div class="form-box">
					                        							<h5 class="subName"><?php echo wp_kses_data($valueVK->label); ?><span style = "color:red"><?php echo wp_kses_data($star);?></span>

					                        							 <?php if(isset( $valueVK->helptext)&& !empty($valueVK->helptext)) { ?>
						                              				
																		<div class="inputMsg"><?php echo wp_kses_data($valueVK->helptext);?></div>

						                              				<?php } ?>
																	 
					                        						</h5>
					                        						
						                              				<label class="pure-material-textfield-outlined">
																	  <input placeholder=" " id= "<?php echo wp_kses_data($id);?>" name="<?php echo wp_kses_data($class);?>"  type="text" <?php echo wp_kses_data($param) ?> value='<?php echo wp_kses_data($defaultString);?>'>

																	   <span><?php echo wp_kses_data($valueVK->label); ?></span>
																	   
																	</label>

																
						                              			</div>
						                              			
						                              			

					                        					<?php	} 

					                        						} //if($i !== 0){
			                        				         ?>

					                        					<div class="form-box">
				                              				<label class="pure-material-textfield-outlined smBtnArea">
															  <button type="button" class=" btn btn-info btn-ad  removeBtn" data-attr-page="<?php echo $valueI->label;?>" data-total ="<?php echo $size;?>" data-activesection = "<?php echo  str_replace(' ','_',$valueI->label);?>" data-activetab="<?php echo $keyV;?>" data-div5="<?php echo  $keyI.'-'.$keyVI.'-'.$keyItems;?>" id="<?php echo  $keyI.'-'.$keyVI.'-'.$keyItems.'-'.$i;?>">Remove</button>
															  <span style="color:red;display:none;" class="spancantremove<?php echo  $keyI.'-'.$keyVI.'-'.$keyItems.'-'.$i;?>">You can't remove the last element </span>
															</label>
				                              			</div>

				                              		<?php// } ?>
				                              		</div>
					                        				<?php   

			                        					} ?>

			                        					

			                        			<?php } ?>
			                        					

			                        				</div>

			                        				<div class="multiinput_div5_<?php echo  str_replace(' ','_',$valueI->label);?>" ></div>

			                        				<div class="form-box">
				                              				<label class="pure-material-textfield-outlined smBtnArea">
															  <button type="button" class=" btn btn-info btn-ad  recentAddMore" data-activesection = "<?php echo  str_replace(' ','_',$valueI->label);?>" data-activetab="<?php echo $keyV;?>" data-div5="<?php echo  $keyI.'-'.$keyVI.'-'.$keyItems;?>">Add More</button>
															<!--   <button type="button" class="btn-round btn btn-danger removeLinkbtn">Remove</button> -->
															</label>
				                              			</div>
			                        			
			                        		<?php }

	                              						if($valueItems->type == 'enum'){  

	                              							//echo "e4";	
	                              								if(!empty($valueItems->default)){

										    						$defaultEnum = $valueItems->default;
										    					
										    					} else {

										    						$defaultEnum = '';
										    					}

										    					if(!empty($valueVI->required)){

								    						

											    						$paramenum="data-enum = 'required'";
											    						$starenum = '*';
								    					
											    					} else {

											    						
											    						$paramenum = "";
											    						$starenum = '';
											    					}


										    					$classurl =$keyI.':'.$keyVI.':'.$keyItems.'';
										    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';
	                              							?>
	                              								<div class="singl-section">
															<h5 class="subName"> <span style = "color:red"><?php echo wp_kses_data($starenum);?></span><?php echo wp_kses_data($valueItems->label); ?>

															<?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>
															  <?php } ?>
															</h5>

																<label class="pure-material-textfield-outlined">
															  <select f onchange="handleOnchangeEvent()" id="<?php echo wp_kses_data($id);?>" name="<?php echo wp_kses_data($classurl);?>">

															  	<optgroup label=" <?php echo wp_kses_data($valueItems->label);?>">

	                              							<?php foreach ($valueItems->type_items as $keytype => $valuetype) { 
	                              									if($valuetype->value == $defaultEnum){

	                              											$select = 'selected';
	                              										} else {
	                              											$select = '';
	                              										}

	                              							 ?>
	                              								
	                 											  <option <?php echo wp_kses_data($select);?> value="<?php echo wp_kses_data($valuetype->value);?>"><?php echo wp_kses_data($valuetype->label);?></option>
													 
													    <?php 

													    	} ?>

													    	</optgroup>

													    	</select>
															  <span><?php echo wp_kses_data($valueItems->label); ?></span>
															   
															</label>

													    	</div>


											<?php } if($valueItems->type == 'url'){ 

													
														//echo "u3";

	                              						if(!empty($valueItems->required)){

									    						
									    						$param="data-param-url='required'";
									    						$starurl = '*';
									    					
									    				} else {

									    						
									    						$param="";
									    						$starurl = "";
									    				}

									    				$classurl =$keyI.':'.$keyVI.':'.$keyItems.'';
									    				$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';

									    				

												?>

													    <div class="form-box">
													    	<h5 class="subName"><?php echo wp_kses_data($valueItems->label); ?><span style="color:red;"><?php echo wp_kses_data($starurl);?></span>
													    	 <?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>
															  <?php } ?>
													    	</h5>
				                              				
				                              				<label class="pure-material-textfield-outlined">
															  <input placeholder=" " class="urlclass" value = true id="<?php echo wp_kses_data($id);?>" type="url" name="<?php echo wp_kses_data($classurl);?>" <?php echo wp_kses_data($param);?> >
															  <span class="hidevallabel"><?php echo wp_kses_data($valueItems->label); ?></span>
															   
															 <span id="urlhttps<?php echo wp_kses_data($id);?>" style="color:red;display:none">Only https:// is allowed</span>

															</label>
			                              				</div>

			                              				


	                              			<?php } if($valueItems->type == 'boolean'){  

	                              						
	                              						//echo "b4";
	                              						if(($valueItems->default) == 1){

								    						$prop = 'checked';
								    						$checkedvalue = "true";
								    					} else {

								    						$prop = '';
								    						$checkedvalue = "false";
								    					}

								    					if(!empty($valueItems->required)){

								    						

								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = '';
								    						$star = '';
								    					}


								    					$classcolor =$keyI.':'.$keyVI.':'.$keyItems.'';
								    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';
								    					


	                              				?>

	                              							  <!-- TOGGLE SWITCH START -->
												    <div class="singl-section">
												    	<h5 class="subName"><?php echo wp_kses_data($valueItems->label); ?>

												    	<?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
															  <span class="inputMsg sinfo" ><?php echo wp_kses_data($valueItems->helptext);?></span>
															  <?php } ?><span style = "color:red"><?php echo wp_kses_data($star);?></span>
												    	</h5>
												    	
												    	<label class="switch-tog">
														    <input type="checkbox" name="<?php echo wp_kses_data($classcolor);?>" value="<?php echo wp_kses_data($checkedvalue);?>" id= "<?php echo wp_kses_data($id);?>" <?php echo  wp_kses_data($prop);?> onclick="getEvent(this)">
														    <span class="slider-tog round"></span>
														     
														</label>
														 
												    </div>


												    <!-- TOGGLE SWITCH END -->


	                              			<?php } if($valueItems->type == 'html'){ 
	                              					
	                              						$classcolor =$keyI.':'.$keyVI.':'.$keyItems.'';
								    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';

	                              					
	                              				?>

	                              					<!-- COLOR PICKER START -->
	                              			<div class="form-box">
	                              								<h5 class="subName"> <?php echo wp_kses_data($valueItems->label); ?> <?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>
																		  <?php } ?></h5>
				                              				<label class="pure-material-textfield-outlined">
															    <textarea class="form-control" id="<?php echo wp_kses_data($id);?>" name="<?php echo wp_kses_data($classcolor);?>"  rows="3"></textarea>
															  <span><?php echo wp_kses_data($valueItems->label); ?></span>
															    

															</label>
				                              			</div>
											<!-- COLOR PICKER END -->
	                              		
	                              	
	                              			<?php } if($valueItems->type == 'hidden'){  

	                              						if(!empty($valueItems->default)){

								    						$defaultHidden = $valueItems->default;
								    					
								    					} else {

								    						$defaultHidden = '';
								    					}

								    					$classcolor =$keyI.':'.$keyVI.':'.$keyItems.'';
								    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';
	                              				?>

	                              				<div class="form-box">
			                              				<label class="pure-material-textfield-outlined">
														  <input placeholder=" "  name="<?php echo wp_kses_data($classcolor);?>"  id= "<?php echo wp_kses_data($id);?>" type="hidden" value='<?php echo wp_kses_data($defaultHidden);?>'>
														  <!-- <span><?php //echo $valueItems->label; ?></span> -->
														</label>
	                              				</div>
	                              		


	                              			<?php } if($valueItems->type == 'file_upload'){

	                              					//echo "f2";

	                              			
	                              			
								    					$class =$keyI.':'.$keyVI.':'.$keyItems.'';
								    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';
								    					$accept = $valueItems->file_type;
								    					$sizes = explode('x',$valueItems->size);
									    				$width = $sizes[0];
									    				$height = $sizes[1];


									    				if(!empty($valueItems->required)){

								    						
								    						$param = '';
				                              				$uploadstar = '*';
								    					
								    					} else {

								    						
								    						$param = "";
								    						$uploadstar = '';
								    					}
									    				
								    				if(!empty($valueItems->default)){

								    						$defaultString = $valueItems->default;
								    					
								    					} else {

								    						
								    							$defaultString = '';
								    						

								    						
								    					}



	                              			 ?>

	                              							    <!-- IMAGE UPLOAD START -->
											    <div class="singl-section">
											    	<div class="avatar-upload">


											        <div class="avatar-edit">
											        	 <label for="<?php echo wp_kses_data($id);?>"><h5 class="subName"><?php echo wp_kses_data($valueItems->label).'('.$valueItems->size.')' ?>

											        	 <span style="color:red"><?php echo wp_kses_data($uploadstar);?></span>
											        	<?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
																		  <div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>
																		  <?php } ?>
											        	</h5>
											        		
											        </label>
											        	 
											        	<div class="avatar-preview fav-bx" >
												            <div id="imagePreview" class="favImage"><img id="dvPreview<?php echo wp_kses_data($id);?>" src="<?php echo wp_kses_data($defaultString);?>"/>
												            </div>

												           
												        </div>
											        	<div class="up-input">

											        		<?php if($id== 'components-header_template-external_icon1'){

											        			if(empty($header->components->header_template->external_icon1)){
				                              							$param = '';
				                              						} else {
				                              							$param = '';
				                              						}
				                              					?>

				                              					<input type='file' data-height="<?php echo wp_kses_data($height);?>" name="<?php echo ($class)?>" data-width="<?php echo wp_kses_data($width);?>" <?php echo wp_kses_data($param);?> id="<?php echo wp_kses_data($id);?>"   accept="<?php echo wp_kses_data($accept);?>"  />
											        	 


				                              					<?php }	 else if($id== 'components-header_template-external_icon2'){

											        			if(empty($header->components->header_template->external_icon2)){
				                              							$param = '';
				                              							$uploadstar = '<span style="color:red">*</span>';
				                              						} else {
				                              							$param = '';
				                              							$uploadstar = '';
				                              						}
				                              					?>

				                              					<input type='file' data-height="<?php echo wp_kses_data($height);?>" name="<?php echo ($class)?>" data-width="<?php echo wp_kses_data($width);?>" <?php echo wp_kses_data($param);?> id="<?php echo wp_kses_data($id);?>"   accept="<?php echo wp_kses_data($accept);?>"  />
											        	 


				                              					<?php }	else { ?>

				                              						<input type='file' data-height="<?php echo wp_kses_data($height);?>" name="<?php echo ($class)?>" data-width="<?php echo wp_kses_data($width);?>"  id="<?php echo wp_kses_data($id);?>"   accept="<?php echo wp_kses_data($accept);?>"  />
											        	 

				                              					<?php } ?>


											        		 
											        	 	
											        		<input type='hidden' name="<?php echo ($class);?>"  id="hidden_<?php echo wp_kses_data($id);?>"   />

											        		<span class="spnerror<?php echo wp_kses_data($id);?> msg-eror" style="color:red;display:none">Please upload image of <?php echo wp_kses_data($width)?>x<?php echo wp_kses_data($height)?> </span>

											        		 <button type="button" id="remove<?php echo wp_kses_data($id);?>" class=" btn btn-danger imageRemoveBtn"><i class="fa fa-times"></i>Remove</button>
											        	</div>

											          	

											           	
											       
											        </div>
											    </div>
												</div>
	                              		


	                              			<?php } if($valueItems->type == 'hex_color'){  

	                              						//echo "h3";

	                              						if(!empty($valueItems->default)){

								    						$defaultHexColor = $valueItems->default;
								    					
								    					} else {

								    						$defaultHexColor = '';
								    					}

								    				$classcolor =$keyI.':'.$keyVI.':'.$keyItems.'';
								    				$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';


	                              				?>
	                              								<div class="singl-section">
							                              			<div class="picker">
							                              				<h5 class="subName"><?php echo ucfirst(wp_kses_data($valueItems->label)); ?> 
							                              		 <?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
																		  <div class="inputMsg"><?php wp_kses_data($valueItems->helptext);?></div>
																		  <?php } ?>
							                              			</h5>
																	  <input  type="color" class="colorpicker" id= "<?php echo wp_kses_data($id);?>" name="<?php echo wp_kses_data($classcolor);?>" value="<?php echo wp_kses_data($defaultHexColor);?>">
																	  
																	 
												 
																   <input type="text"  id="hex_<?php echo wp_kses_data($id);?>" class="hexcolor" name="<?php echo wp_kses_data($classcolor);?>"  autocomplete="off" spellcheck="false"  value='<?php echo wp_kses_data($defaultHexColor);?>'>
																	 <span class= "hexspan_<?php echo wp_kses_data($id);?>"  style="color:red;display:none">Only Hex color code is accepted</span> 
																	</div>
																	
																</div>



	                              						

	                              			<?php } if($valueItems->type == 'string'){  

	                              					//echo "s6";

			                        					if(!empty($valueItems->default)){

								    						$defaultString = $valueItems->default;
								    					
								    					} else {

								    						$defaultString = '';
								    					}

								    					if(!empty($valueItems->required)){

								    						
								    						$param="data-param = 'required'";
								    						$star = '*';
								    					
								    					} else {

								    						
								    						$param = " ";
								    						$star = '';
								    					}

								    					$class =$keyI.':'.$keyVI.':'.$keyItems.'';//'name = "'.str_replace(' ','_',$valueItems->label).'"';
								    					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';

	                              				?>
				                              			<div class="form-box">
				                              				<h5 class="subName"><?php echo wp_kses_data($valueItems->label); ?><span style = "color:red"><?php echo wp_kses_data($star);?></span>

				                              				 <?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>

				                              				<?php } ?>

				                              				</h5>
				                              				 
				                              				<label class="pure-material-textfield-outlined">
															  <input placeholder=" " id= "<?php echo wp_kses_data($id);?>" name ="<?php echo wp_kses_data($class);?>" <?php echo wp_kses_data($param);?>  type="text" value='<?php echo wp_kses_data($defaultString);?>'>
															   <span><?php echo wp_kses_data($valueItems->label); ?></span>
															  
															</label>
				                              			</div>

				                              			

	                              			<?php }   if($valueItems->type == 'integer') {

	                              						//echo "i1";

			                        						if(!empty($valueItems->default)){

									    						$defaultInteger = $valueItems->default;
									    					
									    					} else {

									    						$defaultInteger = '';
									    					}


									    					if(!empty($valueItems->required)){

									    					

									    						$param = "required";
									    						$starinte = '*';
									    					
									    					} else {

									    						
									    						$param = "";
									    						$starinte = "";
									    					}

									    					$class =$keyI.':'.$keyVI.':'.$keyItems.'';
									    					$id =$keyI.'-'.$keyVI.'-'.$keyItems;
			                        			 ?>
			                        				
												 <div class="form-box">
												 					<h5 class="subName"><?php echo wp_kses_data($valueItems->label); ?><span style="color:red;"><?php echo wp_kses_data($starinte);?></span>

												 						

												 				


												 				</h5>
				                              					<label class="pure-material-textfield-outlined">
															  <input placeholder=" " name="<?php echo wp_kses_data($class);?>" type="number" data-intattr= "<?php echo wp_kses_data($param);?>" id= "<?php echo wp_kses_data($id);?>" value='<?php echo wp_kses_data($defaultInteger);?>' min="0">
															  <span><?php echo wp_kses_data($valueItems->label); ?></span>
															  <?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
															  <div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>
															  <?php } ?>
															</label>
				                              			</div>

				                              		
			                        			
			                        			<?php }  if($valueItems->type == 'array') { 

					                        			//echo "a2";
			                        					$class =$keyI.':'.$keyVI.':'.$keyItems.'';
			                        					$id =$keyI.'-'.$keyVI.'-'.$keyItems.'';

			                        				?>



	                              				<div class="singl-section">
															<h5 class="subName"> <?php echo wp_kses_data($valueItems->label); ?> 
															 <?php if(isset( $valueItems->helptext)&& !empty($valueItems->helptext)) { ?>
				                              				
																<div class="inputMsg"><?php echo wp_kses_data($valueItems->helptext);?></div>

				                              				<?php } ?>
															</h5>

																<label class="pure-material-textfield-outlined">
																	
														
															

															  <select a id="<?php echo wp_kses_data($id);?>" name="<?php echo wp_kses_data($class);?>#multidropdown" multiple>

															  	<optgroup label="Select <?php echo wp_kses_data($valueItems->label);?>">



															  		<?php  foreach ($categories as $keyCategories => $valueCategories) { 

															  			

															  			if(!empty($valueCategories['sub_categories'])){ 


															  					foreach ($valueCategories['sub_categories'] as $keyCat => $valueCat) { 




															  						?>



															  						 <option value="<?php echo wp_kses_data($valueCat['cat_guid']);?>"><?php echo wp_kses_data($valueCat['name']);?></option> 





															  						
															  				<?php 	}

															  			}

															  			?>
															  			   <option value="<?php echo wp_kses_data($valueCategories['cat_guid']);?>"><?php echo wp_kses_data($valueCategories['name']);?></option>
															  		<?php } ?>

	                              						

													    	</optgroup>

													    	</select>
															  <span>Select <?php echo wp_kses_data($valueItems->label); ?></span>
															
															

													    	</div> 
													    	</label>

													    <?php }
	                              					

	                              						
	                    } ?>


	                              					
	                              				
	                              				<?php }

	                              			?>

	                              		<?php } ?>

	                              		<div class="emptyScrollDiv"></div>
		                        	</div>
		                      </div>

			  			

			  			<?php } 

			  			


			  		}  ?>

			  							</div>
			  						</div>
			  						<!-- <div class="heading-main"> -->
				  						<!-- <div class="headingName">
				  							<h2></h2>
				  						</div> -->


				  						<div class="butn-area savefixed_btndivs">
				  							<img class="ldGf" src="<?php echo plugin_dir_url( __DIR__ );?>css/load.gif" style="display:none">
							  				<!-- <button class="def-Btn">Cancel</button>
							  				<button  class="def-Btn saveBtn" data-btn="<?php //echo $keyV; ?>">Save</button> -->
							  				<button class="btn btn-cancl" onclick ="window.leavepageflag = true; (window.location.href='<?php echo site_url();?>/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter=<?php echo wp_kses_data($keyV); ?>')" id="cancelbtnid">Cancel</button>
							  				<button  class="btn btn-ad saveBtn saveConfigBtn" data-btn="<?php echo wp_kses_data($keyV); ?>"> Publish </button>
							  				
							  			</div>


				  					<!-- </div> -->
			  					</form>
				  				</div>
							</div>
				<?php
						
				}	
				
			} ?>
				
			</div>
		</div>
	</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<!-- <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script> -->
<script type="text/javascript">
	

	function getEvent(event){

	event.value==="true" ? event.value="false" : event.value="true" ;
		//event.value="hi"


	}


</script>



<script>
jQuery(document).ready(function(){

  jQuery('.colorpicker').on('input', function() {

  		var id = this.id;

  		//console.log(this.value);
		jQuery('#hex_'+id).val(this.value);


	});
	jQuery('.hexcolor').on('input', function() {
		var idcolor = this.id;
		var splitvalue = idcolor.split('hex_')
	  jQuery('#'+splitvalue[1]).val(this.value);

	  var flag =  isValidColor(this.value);

		if(flag == false){
			jQuery(".hexspan_"+splitvalue[1]).show();
			 jQuery('.saveBtn').prop('disabled', true);
		}
	});

});


function isValidColor(str) {
    return str.match(/^#[a-f0-9]{6}$/i) !== null;
}

jQuery('.urlclass').on('input', function() {

	  var urlval = this.value;

	  

  		var id = this.id;

  	    var valid_url = isUrl(urlval);

  		if(urlval == ""){
		   jQuery('.saveBtn').prop('disabled', false);
		} else {
			if(valid_url == false){
	  			jQuery('#urlhttps'+id).show();
	  			jQuery('.saveBtn').prop('disabled', true);
	  		} else {
	  			jQuery('#urlhttps'+id).hide();
	  			jQuery('.saveBtn').prop('disabled', false);
	  		}
		}

  		

  		

	});


function isUrl(s) {
    var regexp = /(ftp|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
}


//var a = isUrl("https://localhost/validationcheck.php?url=https%3A%2F%2Fabc.com");

//console.log(a);

function handleOnchangeEvent(data){

	//console.log(data.id);

	//console.log($("#"+id).attr('name'))
	var id = data.id;


	var nameattribute = $("#"+id).attr('name');

	if (nameattribute.indexOf('/') > -1)
	{
	 var namestr =  nameattribute.split('/');

		if(namestr[0] == 'components:special_widgets:name' ){
			if($("#"+id).val() !== 'not_applicable'){

				console.log("namestr")
				$("#components-special_widgets-"+namestr[1]+"-category_id option[value='not_applicable']").hide();
				$("#components-special_widgets-"+namestr[1]+"-category_id option[value='not_applicable']").attr('selected',false);
			} else {
					$("#components-special_widgets-"+namestr[1]+"-category_id option[value='not_applicable']").show();
			}
		}
	}

	

	
}

</script>
